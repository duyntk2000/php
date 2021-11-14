<?php
class Comment {
  protected $voitureID;
  protected $login;
  protected $texte;

  public function __construct($voitureID, $login, $texte) {
    $this->voitureID = $voitureID;
    $this->login = $login;
    $this->texte = $texte;
  }

  public function getVoitureID() {
    return $this->voitureID;
  }

  public function getLogin() {
    return $this->login;
  }

  public function getTexte() {
    return $this->texte;
  }
}
 ?>
