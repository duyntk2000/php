<?php
const AUTH_ACT = ["show", "nouveau", "sauverNouveau", "deleteConfirm" ,"deleted",
                  "modify", "saveMod", "deconnexion", "Admin", "upload", "newComment",
                  "deleteImg", "modImg"];

class Router {
  public function main($storage, $images, $accounts, $comments) {
    session_start();
    $feedback = isset($_SESSION['feedback']) ? $_SESSION['feedback'] : "";
    $view = isset($_SESSION['user']) ? new PrivateView($this, $feedback, $_SESSION['user']) : new View($this, $feedback);
    $_SESSION['feedback'] = "";
    $controller = new Controller($view, $storage, $images, $accounts, $comments);

    //Traiter l'url
    $path = key_exists("PATH_INFO", $_SERVER) ? explode("/",$_SERVER['PATH_INFO']) : null;
    if ($path !== null) {
      if (count($path) === 2) {
        $action = $path[1];
      } else {
        $id = $path[1];
        $action = $path[2];
        $img = isset($path[3]) ? $path[3] : null;
      }
    } else {
      $action = "accueil";
    }
    if (in_array($action, AUTH_ACT, true) && !isset($_SESSION['user'])) {
      $action = "connexion";
    }

    switch ($action) {
      case "accueil" :
        $controller->showMain();
        break;
      case "show" :
        $controller->showInformation($id);
        break;
      case "liste" :
        $controller->showListe();
        break;
      case "nouveau" :
        $controller->showNewVoiture();
        break;
      case "sauverNouveau" :
        $controller->saveNewVoiture($_POST);
        break;
      case "deleteConfirm" :
        $controller->askVoitureDeletion($id);
        break;
      case "deleted" :
        $controller->deleteVoiture($id);
        break;
      case "modify" :
        $controller->modifyVoiture($id);
        break;
      case "saveMod" :
        $controller->saveModifiedVoiture($_POST, $id);
        break;
      case "connexion" :
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $controller->connexion($_POST);
        } else {
          $controller->showConnexion();
        }
        break;
      case "deconnexion" :
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $controller->deconnexion();
        } else {
          $controller->showConnexion();
        }
        break;
      case "Admin" :
        $controller->checkAdmin();
        break;
      case "upload" :
        $controller->upload($id);
        break;
      case "register" :
        $controller->register();
        break;
      case "saveAccount" :
        $controller->saveAccount($_POST);
        break;
      case "newComment" :
        $controller->comment($_POST, $id);
        break;
      case "deleteImg" :
        $controller->deleteImg($id, $img);
        break;
      case "modImg" :
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $controller->saveModImg($id, $img);
        } else {
          $controller->modImg($id, $img);
        }
        break;
      case "search" :
        $controller->search($_GET['q']);
        break;
      case "apropos" :
        $controller->showAPropos();
        break;
      default :
        throw new Exception("Invalid URL");
    }

    $view->render();
  }

  public function getRoot() {
    return $_SERVER['SCRIPT_NAME'];
  }

  public function getVoitureURL($id) {
    return $this->getRoot()."/$id/show";
  }

 public function getSearchURL() {
    return $this->getRoot()."/search";
  }

  public function getVoitureCreationURL() {
    return $this->getRoot()."/nouveau";
  }

  public function getVoitureSaveURL() {
    return $this->getRoot()."/sauverNouveau";
  }

  public function getVoitureListURL() {
    return $this->getRoot()."/liste";
  }

  public function getAdminConnexionURL() {
    return $this->getRoot()."/Admin";
  }

  public function getVoitureAskDeletionURL($id) {
    return $this->getRoot()."/$id/deleteConfirm";
  }

  public function getVoitureDeletionURL($id) {
    return $this->getRoot()."/$id/deleted";
  }

  public function getVoitureModifyURL($id) {
    return $this->getRoot()."/$id/modify";
  }

  public function getVoitureSaveModificationURL($id) {
    return $this->getRoot()."/$id/saveMod";
  }

  public function getConnexionURL() {
    return isset($_SESSION['user']) ? $this->getRoot()."/deconnexion" : $this->getRoot()."/connexion";
  }

  public function getStorage() {
    return dirname($_SERVER['SCRIPT_NAME'])."/src/storage/img";
  }

  public function getUpload($id) {
    return $this->getRoot()."/$id/upload";
  }

  public function getAccountCreationURL() {
    return $this->getRoot()."/register";
  }

  public function getAccountSaveURL() {
    return $this->getRoot()."/saveAccount";
  }

  public function getNewCommentURL($id) {
    return $this->getRoot()."/$id/newComment";
  }

  public function getDeleteImg($id,$img) {
    return $this->getRoot()."/$id/deleteImg/$img";
  }

  public function getModifyImg($id,$img) {
    return $this->getRoot()."/$id/modImg/$img";
  }

  public function getAProposURL() {
    return $this->getRoot()."/apropos";
  }

  public function POSTredirect($url, $feedback) {
    $_SESSION['feedback'] = $feedback;
    header("Location: " . $url, true, 303);
  }
}
 ?>
