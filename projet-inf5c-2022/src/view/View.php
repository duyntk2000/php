<?php
class View {
    protected $title;
    protected $content;
    protected $router;
    protected $menu;
    protected $feedback;
    public function __construct($router, $feedback) {
      $this->router = $router;
      $this->menu = array(
        $this->router->getRoot() => "Accueil",
        $this->router->getVoitureListURL() => "Liste",
        $this->router->getVoitureCreationURL() => "Nouveau",
        $this->router->getConnexionURL() => "Connexion",
        $this->router->getAdminConnexionURL() => "Partie Admin",
        $this->router->getAProposURL() => "A Propos",
        
    );
      $this->feedback = $feedback;
    }

    /******************************************************************************/
	  /* Méthodes de génération des pages                                           */
	  /******************************************************************************/

    public function makeMainPage() {
      $this->title = "Home";
      $this->content .= "<h2>Bienvenue Sur le Site concernant les types Voitures</h2>";
    }
    
    public function makeListPage($voituresTab) {
      $this->title = "Liste";
      $this->content .= "<div><h1>Liste des Voiture:</h1><ul>";
      foreach ($voituresTab as $key => $value) {
        $this->content .= "<li><a href='".$this->router->getVoitureURL($key)."'>".$value->getMarque()."</a></li>";
      }
      $this->content .= "</ul></div>";
    }

    public function makeLoginFormPage($connecte) {
      $this->title = "Connexion/déconnextion";
      $this->content .= "<div><h1>Page de connexion/déconnextion</h1>";

      if ($connecte) {
        $this->content .= "<form method='post'>
                            <button name='logout'>Se Déconnecter</button>
                          </form>
                         </div>";
      }
      else {
         $this->content .= "<form method='post'>
                            <div>
                              <label>Login :</label>
                              <input type='text' name='login'>
                            </div>
                            <div>
                              <label>Password :</label>
                              <input type='password' name='password'>
                            </div>
                            <button>Se connecter</button>
                            <p>Nouveau client ? <a href='".$this->router->getAccountCreationURL()."'>Commencer ici</a></p>
                            </form></div>";
      }
    }

    public function makeUserPage() {
    $this->title = 'User page';
    $this->content .= "<div>Vous êtes Utilisateur simple</div>";
    }

    public function makeAProposPage() {
      $this->title = "A Propos";
      $this->content .="<div class='apropos'><h1>A Propos : </h1>";
      $this->content .="<a>Nous avons réaliser en étant binôme, un mini-site qui présente des voitures
      que nous avons modéliser.</a>";
      $this->content .= " <h2>Les points réalisés:</h2>
                          <h3>Réalisation de base</h3>
                          <ul>
                            <li>La page de connexion et de déconnexion pour un utilisateur simple et Admin</li>
                            <li>La page de création de compte utilisateur</li>
                            <li>La page de liste des types voitures</li>
                            <li>La page à propos</li>
                            <li>La page de création de nouvelles voitures</li>
                            <li>Permettre aux clients de supprimer ou modifier une voiture</li>
                          </ul>
                          <h3>Compléments:</h3>
                          <ul>
                            <li>Faire une recherche d'une voiture</li>
                            <li>Associer des images : Une voiture peut être illustré par zéro, une ou plusieurs images (modifiables) uploadées par le créateur de l'objet.</li>
                            <li>Les Commentaires sur une voiture</li>
                          </ul>
                          <h3>Répartition des tâches</h3>
                          <ul>KIHEOU Essolizam:
                            <li>Créer le modèle Voiture et ses storage (MySQL,Stub,File) et builder (VoitureBuilder)</li>
                            <li>Connexion Admin</li>
                            <li>Faire une recherche d'une voiture</li>
                          </ul>
                          <h3>Tu Khanh Duy Nguyen</h3>
                          <ul>
                            <li>Associer des images : Une voiture peut être illustré par zéro, une ou plusieurs images (modifiables) uploadées par le créateur de l'objet.</li>
                            <li>Les Commentaires sur une voiture</li>
                          </ul>
                          <h3>Les autres:</h3>
                          <p>Fusionner les TPs de binôme</p>
                          <h3>Design:</h3>
                          <ul>
                            <li>Modèle: Voiture(objet), Account(comptes), Comment(commentaires), Image</li>
                            <li>Chaque modèle a un storage (généralement MySQL)</li>
                            <li>Voiture et Account et Comment ont des Builder pour le création</li>
                            <li>Vue: View (pour les utilisateurs non-authentifiés) PrivateView (pour les autres)</li>
                            <li>Contrôleur: un contrôleur qui gère les modèles et vues</li>
                          </ul>
                          <h3>Sécurité:</h3>
                          <ul>
                            <li>Les utilisateur ne peuvent pas supprimer/modifier les voitures des autres (sauf Admin)</li>
                            <li>Stocker les empreintes des mots de passe et noms des images</li>
                            <li>Les requêtes SQL sont préparés avant executés</li>
                            <li>Les images et les chaînes de caractères sont vérifiés/traités avec exif_imagetype() et htmlspecialchars()</li>
                          </ul>";
                          
      $this->content .="<h2>Groupe 37</h2>";
      $this->content .="<ul>
                          <li><h3>KIHEOU Essolizam : 22012596</h3></li>
                          <li><h3>Tu Khanh Duy Nguyen : 21811412</h3></li>
                        </ul>
                      </div>";
    }

    public function makeUnknownVoiturePage() {
        throw new Exception("Voiture inconnu");
    }

    public function makeUnauthorizedPage() {
      $this->title = "Error";
      $this->content .= "<h1>Vous n'êtes pas autorisé pour cette action.</h1>";
    }

    public function makeDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
    }

    //Créer les comptes.
    public function makeAccountCreationPage(AccountBuilder $accB) {
          $this->title = 'New Account';
          $this->content .= "<div>
                                <p style='color: red;'>".$accB->getError()."</p>
                                <h1>Create new account:</h1>";
          $this->content .= '<form action="'.$this->router->getAccountSaveURL().'" method="post">';
          $this->content .= $this->makeAccountForm($accB);
          $this->content .= '</form></div>';
        }

    /******************************************************************************/
	  /* Méthodes utilitaires                                                       */
	  /******************************************************************************/
    public function makeMenu() {
      $content = "<ul class='menu'>";
      foreach ($this->menu as $link => $texte) {
        $content .= "<li><a href='$link'>$texte</a></li>";
      }
      $content .= "</ul>";
      return $content;
    }

    public function makeForm(VoitureBuilder $aB) {
      return '
          <div>
              <label>Marque :</label>
              <input type="text" name="'.VoitureBuilder::MARQUE_REF.'" value="'.$aB->getData()[VoitureBuilder::MARQUE_REF].'">
          </div>
          <div>
              <label>Puissance:</label>
              <input type="text" name="'.VoitureBuilder::PUISSANCE_REF.'" value="'.$aB->getData()[VoitureBuilder::PUISSANCE_REF].'">
          </div>
          <div>
              <label>Date de Création :</label>
              <input type="number" name="'.VoitureBuilder::DATE_REF.'" value="'.$aB->getData()[VoitureBuilder::DATE_REF].'">
          </div>
          <button type="submit">Save</button>';
    }

    public function makeAccountForm(AccountBuilder $accB) {
      return '
          <div>
              <label>Nom :</label>
              <input type="text" name="'.AccountBuilder::NAME_REF.'" value="'.$accB->getData()[AccountBuilder::NAME_REF].'">
          </div>
          <div>
              <label>Login :</label>
              <input type="text" name="'.AccountBuilder::LOGIN_REF.'" value="'.$accB->getData()[AccountBuilder::LOGIN_REF].'">
          </div>
          <div>
              <label>Password :</label>
              <input type="password" name="'.AccountBuilder::PASSWORD_REF.'" value="">
          </div>
          <button type="submit">Save</button>';
    }

    /******************************************************************************/
	  /* Méthodes rédiriger                                                         */
	  /******************************************************************************/
    public function displayVoitureCreationOrModSuccess($id) {
        $this->router->POSTredirect($this->router->getVoitureURL($id), "Creation Successful");
    }

    public function displayVoitureCreationFailure() {
        $this->router->POSTredirect($this->router->getVoitureCreationURL(), "Creation Failed");
    }

    public function displayVoitureModificationFailure($id) {
        $this->router->POSTredirect($this->router->getVoitureModifyURL($id), "Modification Failed");
    }

    public function displayConnectionSuccess() {
      $this->router->POSTredirect($this->router->getRoot(), "Bienvenue sur le site ".$_SESSION['user']->getNom());
    }

    public function displayConnectionFail() {
      $this->router->POSTredirect($this->router->getConnexionURL(), "Connection fail");
    }


    public function displayDisconnection() {
      $this->router->POSTredirect($this->router->getRoot(), "Au revoir");
    }

    public function getSearconnect() {
      $this->router->POSTredirect($this->router->getSearchURL(), "Recherche");
    }

    public function displayUploadSuccess($id) {
      $this->router->POSTredirect($this->router->getVoitureURL($id), "Upload successful");
    }

    public function displayUploadFail($id) {
      $this->router->POSTredirect($this->router->getVoitureURL($id), "Upload fail");
    }

    public function displayAccountCreationFailure() {
        $this->router->POSTredirect($this->router->getAccountCreationURL(), "Creation failed");
    }

    public function render() {
?>
<!DOCTYPE html>
  <html lang='fr'>
    <head>
      <meta charset='UTF-8' />
      <meta name='author' content='22012596' />
      <title><?php echo $this->title ?></title>
      <link rel='stylesheet' href='/projet-inf5c-2022/skin/style.css'>
    </head>
    <body>
      <header>
        <?php echo $this->makeMenu() ?>
      </header>
      <form method='get' action='search'>
        <div>
          <input type='text' name='q'>
          <button type='submit'>Rechercher</button>
        </div>
      </form>
      <p><?php echo $this->feedback ?></p>
      <main>
        <?php echo $this->content ?>
      </main>
    </body>
  </html>
<?php
  }


}
?>
