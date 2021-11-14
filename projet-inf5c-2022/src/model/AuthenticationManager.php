<?php
class AuthenticationManager {
  private $comptes;

  public function __construct($comptes) {
    $this->comptes = $comptes;
  }

  public function connectUser($login, $password) {
    $account = $this->comptes->checkAuth($login, $password);
    if ($account !== null) {
        $_SESSION['user'] = $account;
        $_SESSION['statut'] = $account->getStatut();
        return true;
    }
    return false;
  }

  public function connectAdmin($login, $password)
  {
    $account = $this->comptes->checkAuth($login, $password);
    if ($account !== null && $account->getStatut() =="admin") {
      $_SESSION['user'] = $account;
      return true;
    } else {
      return false;
    }
  }

  public function getLogin() {
    return $this->isUserConnected() ? $_SESSION['user']->getLogin() : null;
  }

  public function isUserConnected() {
    return key_exists('user', $_SESSION);
  }

  public function isAdminConnected() {
    return $this->isUserConnected() && $_SESSION['user']->getStatut() === 'admin';
  }

  public function disconnectUser() {
    unset($_SESSION['user']);
  }
}
 ?>
