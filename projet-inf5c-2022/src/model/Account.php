<?php
class Account {
  protected $nom;
  protected $login;
  protected $password;
  protected $statut;

  public function __construct($nom, $login, $password, $statut) {
    $this->nom = $nom;
    $this->login = $login;
    $this->password = $password;
    $this->statut = $statut;
  }

  public function getPassword() {
    return $this->password;
  }

  public function getNom() {
    return $this->nom;
  }

  public function getLogin() {
    return $this->login;
  }

  public function getStatut() {
    return $this->statut;
  }
}

 ?>
