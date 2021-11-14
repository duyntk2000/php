<?php
class PrivateView extends View {
  protected Account $account;

  public function __construct($router, $feedback, Account $account) {
    parent::__construct($router, $feedback);
    $this->account = $account;
    $this->menu[$this->router->getConnexionURL()] = "Déconnexion";
  }


  /******************************************************************************/
	/* Méthodes de génération des pages                                           */
	/******************************************************************************/
  public function makeVoitureCreationPage(VoitureBuilder $aB) {
    $this->title = 'New Voiture';
    $this->content .= "<div><p style='color: red;'>".$aB->getError()."</p>";
    $this->content .= '<form action="'.$this->router->getVoitureSaveURL().'" method="post">';
    $this->content .= $this->makeForm($aB);
    $this->content .= '</form></div>';
  }

  public function makeVoitureGuestPage(Voiture $voiture, $id, Array $imgs, Array $comments) {
    $this->makeVoiturePage($voiture);
    $this->makeGallery($imgs, $id);
    $this->makeComment($comments);
    $this->makeCommentArea($id);
  }

  public function makeVoitureAuthorPage(Voiture $voiture, $id, Array $imgs, Array $comments) {
    $this->makeVoiturePage($voiture);
    $this->makeGalleryAuthor($imgs, $id);
    $this->content .= '<div class="mod">';
    $this->makeUpload($id);
    $this->makeModSup($id);
    $this->content .= '</div>';
    $this->makeComment($comments);
    $this->makeCommentArea($id);
  }

  public function makeVoitureAdminPage(Voiture $voiture, $id, Array $imgs, Array $comments) {
    $this->makeVoiturePage($voiture);
    $this->makeGallery($imgs, $id);
    $this->content .= '<div class="mod">';
    $this->makeModSup($id);
    $this->content .= '</div>';
    $this->makeComment($comments);
    $this->makeCommentArea($id);
  }

  public function makeAdminPage() {
    $this->title = 'Admin page';
    $this->content .= "<div>Vous êtes Administrateur</div>";
  }

  //Supprimer les voitures.
  public function makeVoitureDeletionPage($id) {
    $this->title = 'Delete';
    $this->content .= "<div><p>Do you want to delete this page?</p>
                      <form action='".$this->router->getVoitureURL($id)."' method='post'>
                        <input type='submit' formaction='".$this->router->getVoitureDeletionURL($id)."' name='delete' value='Delete'>
                        <input type='submit' name='cancel' value='Cancel'>
                      </form></div>";

  }

  public function makeDeletedPage() {
    $this->title = 'Delete';
    $this->content .= "<h2>Deleted successfully!</h2>";
  }

  //Modifier les voiture.
  public function makeModifyVoiturePage(VoitureBuilder $aB, $id) {
      $this->title = 'Modify Voiture';
      $this->content .= "<div><p style='color: red;'>".$aB->getError()."</p>";
      $this->content .= '<form action="'.$this->router->getVoitureSaveModificationURL($id).'" method="post">';
      $this->content .= $this->makeForm($aB);
      $this->content .= '</form></div>';
  }

  public function makeModImgPage($id, $img) {
    $this->content .= "<div class='image'>
                        <h1>Image Modification</h1>
                        <h3>Replace : </h3>
                        <img src='".$this->router->getStorage()."/$id/$img'/>
                        <h3>By : </h3>";
    $this->makeModImg($id, $img);
  }


  /******************************************************************************/
	/* Méthodes utilitaires                                                       */
	/******************************************************************************/
  public function makeVoiturePage(Voiture $voiture) {
    $this->title = "Page sur ".$voiture->getMarque();
    $this->content .= "<div class='description'><p>".$voiture->getMarque()." est une voiture avc une puissance de ".$voiture->getPuissance()." et créer en ".$voiture->getDate_creation()." !!!</p>
                      <p> Added by ".$voiture->getLogin()."</p></div>";
  }

  public function makeCommentArea($id) {
    $this->content .= "<div class='comment'>
                        <form action='".$this->router->getNewCommentURL($id)."'method='post'>
                          <input type='text' name='comment'/>
                          <input type='submit' value='Comment'/>
                        </form>
                      </div>";
  }

  public function makeComment(Array $comments) {
    $this->content .= '<div class="comment-zone">';
    foreach ($comments as $comment) {
      $this->content .= '<p><strong>'.$comment->getLogin().'</strong> : '.$comment->getTexte().'</p>';
    }
    $this->content .= '</div>';
  }

  public function makeGallery(Array $imgs, $id) {
    foreach ($imgs as $value) {
      $this->content .= '<div class="image">
                            <img src="'.$this->router->getStorage()."/$id/".$value->getImage().'"/>
                         </div>';
    }
  }

  public function makeGalleryAuthor(Array $imgs, $id) {
    foreach ($imgs as $value) {
      $marque = $value->getImage();
      $source = $this->router->getStorage()."/$id/".$marque;
      $this->content .= '<div class="image">
                            <img src="'.$source.'"/>
                            <a href="'.$this->router->getDeleteImg($id, $marque).'">Delete</a>
                            <a href="'.$this->router->getModifyImg($id, $marque).'">Modify</a>
                         </div>';
    }
  }

  public function makeUpFile() {
    $this->content .= '<input type="file" name="img">
                       <button type="submit">Valider</button>';
  }

  public function makeUpload($id) {
    $this->content .= '<p>Upload an image:</p>
                        <form enctype="multipart/form-data" action="'.$this->router->getUpload($id).'" method="POST">';
    $this->makeUpFile();
    $this->content .= '</form>';
  }

  public function makeModImg($id, $img) {
    $this->content .= '<p>Upload an image:</p>
                        <form enctype="multipart/form-data" action="'.$this->router->getModifyImg($id, $img).'" method="POST">';
    $this->makeUpFile();
    $this->content .= '</form>';
  }

  public function makeModSup($id) {
    $this->content .= "<a href='".$this->router->getVoitureAskDeletionURL($id)."'>Delete</a>
                       <a href='".$this->router->getVoitureModifyURL($id)."'>Modify</a>";
  }

  /******************************************************************************/
	/* Méthodes rédiriger                                                         */
	/******************************************************************************/
  public function displayCommentCreationFailure($id) {
    $this->router->POSTredirect($this->router->getVoitureURL($id),"Comment fail");
  }

  public function displayCommentCreationSuccess($id) {
    $this->router->POSTredirect($this->router->getVoitureURL($id),"Comment success");
  }

  public function displayDeleteImgSuccess($id) {
    $this->router->POSTredirect($this->router->getVoitureURL($id),"Delete image success");
  }

  
}
?>
