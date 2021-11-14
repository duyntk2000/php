<?php
class Controller {
    protected $view;
    protected $storage;
    protected $images;
    protected $accounts;
    protected $comments;
    protected $auth;

    public function __construct(View $view, VoitureStorage $storage, ImageStorage $images, AccountStorage $accounts, CommentStorage $comments) {
        $this->view = $view;
        $this->storage = $storage;
        $this->images = $images;
        $this->accounts = $accounts;
        $this->comments = $comments;
        $this->auth = new AuthenticationManager($accounts);
    }

    /*
     * Les Pages Principals 
     * 
     */

    public function showInformation($voitureID) {
        $voiture = $this->storage->read($voitureID);
        if ($voiture != null) {
            $imgs = $this->images->read($voitureID);
            $comments = $this->comments->read($voitureID);
            if ($_SESSION['user']->getLogin() === $voiture->getLogin()) {
              $this->view->makeVoitureAuthorPage($voiture, $voitureID, $imgs, $comments);
            } 
            elseif ($_SESSION['user']->getStatut() === 'admin') {
              $this->view->makeVoitureAdminPage($voiture, $voitureID, $imgs, $comments);
            } else {
              $this->view->makeVoitureGuestPage($voiture, $voitureID, $imgs, $comments);
            }
        } else {
          $this->view->makeUnknownVoiturePage();
        }
    }

    public function showMain() {
        $this->view->makeMainPage();
    }

    public function showListe() {
        $this->view->makeListPage($this->storage->readAll());
    }

    public function showNewVoiture() {
      $aB = isset($_SESSION['currentNewVoiture']) ? $_SESSION['currentNewVoiture'] : new VoitureBuilder(array(
        VoitureBuilder::MARQUE_REF => "",
        VoitureBuilder::PUISSANCE_REF => "",
        VoitureBuilder::DATE_REF => 0,
      ));
      $this->view->makeVoitureCreationPage($aB);
    }
    
    public function showConnexion() {
      $this->view->makeLoginFormPage(isset($_SESSION['user']) ? true : false);
    }

    public function showAdminConnexion() {
      $this->view->makeAdminFormPage(isset($_SESSION['user']) ? true : false);
    }

    public function showAPropos() {
          $this->view->makeAProposPage();
        }

    

    /*
     * Les Fonctions Avec Des Objets 
     * 
     */

    //Les Voitures
    public function saveNewVoiture(array $data) {
      $aB = new VoitureBuilder($data);
      if (!$aB->isValid()) {
        $_SESSION['currentNewVoiture'] = $aB;
        $this->view->displayVoitureCreationFailure();
      } else {
        unset($_SESSION['currentNewVoiture']);
        $a = $aB->createVoiture();
        $id = $this->storage->create($a);
        mkdir("./src/storage/img/$id/");
        $this->view->displayVoitureCreationOrModSuccess($id);
      }
    }

    public function askVoitureDeletion($voitureID) {
      if (($voiture = $this->storage->read($voitureID)) !== null) {
        if ($this->checkAuth($voiture)) {
          $this->view->makeVoitureDeletionPage($voitureID);
        } else {
          $this->view->makeUnauthorizedPage();
        }
      } else {
        $this->view->makeUnknownVoiturePage();
      }
    }

    public function deleteVoiture($voitureID) {
      $imgs = $this->images->read($voitureID);
      foreach ($imgs as $img) {
        $name = $img->getImage();
        $this->images->delete($voitureID, $name);
        if (file_exists("./src/storage/img/$voitureID/$img")) {
          unlink("./src/storage/img/$voitureID/$img");
        }
      }
      $this->storage->delete($voitureID);
      rmdir("./src/storage/img/$voitureID/");
      $this->view->makeDeletedPage();
    }

    public function modifyVoiture($voitureID) {
      $aB = new VoitureBuilder(array());
      if (($voiture = $this->storage->read($voitureID)) !== null) {
        if ($this->checkAuth($voiture)) {
          $aB->fillData($voiture);
          $this->view->makeModifyVoiturePage($aB, $voitureID);
        } else {
          $this->view->makeUnauthorizedPage();
        }
      } else {
        $this->view->makeUnknownVoiturePage();
      }
    }

    public function saveModifiedVoiture(array $data, $voitureID) {
      $aB = new VoitureBuilder($data);
      if (!$aB->isValid()) {
        $this->view->displayVoitureModificationFailure($voitureID);
      } else {
        $a = $aB->createVoiture();
        $this->storage->update($voitureID, $a);
        $this->view->displayVoitureCreationOrModSuccess($voitureID);
      }
    }

    //Les Comptes
    
    public function register() {
      $accB = isset($_SESSION['currentNewAccount']) ? $_SESSION['currentNewAccount'] : new AccountBuilder(array(
        AccountBuilder::NAME_REF => "",
        AccountBuilder::LOGIN_REF => "",
        AccountBuilder::PASSWORD_REF => "",
        AccountBuilder::STATUT_REF => "user",
      ), $this->accounts);
      $this->view->makeAccountCreationPage($accB);
    }

    public function saveAccount(array $data) {
      $accB = new AccountBuilder($data);
      if (!$accB->isValid($this->accounts)) {
        $_SESSION['currentNewAccount'] = $accB;
        $this->view->displayAccountCreationFailure();
      } else {
        unset($_SESSION['currentNewAccount']);
        $acc = $accB->createAccount();
        $this->accounts->create($acc);
        $this->connexion($data);
      }
    }

    public function connexion(array $data) {
      if ($this->auth->connectUser($data['login'], $data['password'])) {
        $this->view->displayConnectionSuccess();
      } else {
        $this->view->displayConnectionFail();
      }
    }

    public function deconnexion() {
      $this->auth->disconnectUser();
      $this->view->displayDisconnection();
    }

    //Les Images/Commentaires

    

    public function upload($voitureID) {
      if (($voiture = $this->storage->read($voitureID)) !== null) {
        if ($this->checkAuth($voiture)) {
          $img = $_FILES['img'];
          $error = $img['error'];
          $extension = array('gif','jpeg','png','swf','psd','bmp','tiff','tiff','jpc','jp2','jpx','jb2','swc','iff','wbmp','xbm','ico','webp');
          if ($error === 0 && $isImg = exif_imagetype($img['tmp_name']) !== false) {
            $name = hash('md5',$img['name'].strval(time())) . '.' . $extension[$isImg+1];
            $image = new Image($voitureID, $name);
            $this->images->create($image);
            move_uploaded_file($img["tmp_name"],"src/storage/img/$voitureID/".$name);
            $this->view->displayUploadSuccess($voitureID);
          } else {
            $this->view->displayUploadFail($voitureID);
          }
        } else {
          $this->view->makeUnauthorizedPage();
        }
      } else {
        $this->view->makeUnknownVoiturePage();
      }
    }

    

    public function comment(array $data, $voitureID) {
      if (empty($data['comment'])) {
        $this->view->displayCommentCreationFailure($voitureID);
      } else {
        $c = new Comment($voitureID, $_SESSION['user']->getLogin(), $data['comment']);
        $this->comments->create($c);
        $this->view->displayCommentCreationSuccess($voitureID);
      }
    }

    public function deleteImg($voitureID, $img) {
      if (($voiture = $this->storage->read($voitureID)) !== null && $this->images->exists($voitureID, $img)) {
        if ($this->checkAuth($voiture)) {
          $this->images->delete($voitureID, $img);
          if (file_exists("./src/storage/img/$voitureID/$img")) {
            unlink("./src/storage/img/$voitureID/$img");
          }
          $this->view->displayDeleteImgSuccess($voitureID);
        } else {
          $this->view->makeUnauthorizedPage();
        }
      } else {
        throw new Exception("invalid id / img");
      }
    }

    public function modImg($voitureID, $img) {
      if (($voiture = $this->storage->read($voitureID)) !== null && $this->images->exists($voitureID, $img)) {
        if ($this->checkAuth($voiture)) {
          $this->view->makeModImgPage($voitureID, $img);
        } else {
          $this->view->makeUnauthorizedPage();
        }
      } else {
        throw new Exception("invalid id / img");
      }
    }

    public function saveModImg($voitureID, $img) {
      $newImg = $_FILES['img'];
      $error = $newImg['error'];
      $extension = array('gif','jpeg','png','swf','psd','bmp','tiff','tiff','jpc','jp2','jpx','jb2','swc','iff','wbmp','xbm','ico','webp');
      if ($error === 0 && $isImg = exif_imagetype($newImg['tmp_name']) !== false) {
        $name = hash('md5',$newImg['name'].strval(time())) . '.' . $extension[$isImg+1];
        $this->images->update($voitureID, $img, $name);
        move_uploaded_file($newImg["tmp_name"],"src/storage/img/$voitureID/".$name);
        if (file_exists("./src/storage/img/$voitureID/$img")) {
          unlink("./src/storage/img/$voitureID/$img");
        }
        $this->view->displayUploadSuccess($voitureID);
      } else {
        $this->view->displayUploadFail($voitureID);
      }
    }

    /******************************************************************************/
	  /* Méthodes utilitaires                                                       */
	  /******************************************************************************/

    public function search($query){
      $searchResults = $this->storage->search($query);
      $this->view->makeListPage($searchResults);
    }
    
    public function checkAdmin() {
      if ($this->auth->isAdminConnected()) {
        $this->view->makeAdminPage();
      } else {
        $this->view->makeUserPage();
      }
    }
    
    public function checkAuth(Voiture $voiture) {
      return $this->auth->isAdminConnected() || $this->auth->getLogin() === $voiture->getLogin();
    }
}
?>
