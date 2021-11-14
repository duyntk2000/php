<?php
class VoitureBuilder {
  protected $data;
  protected $error;

  const MARQUE_REF = "marque";
  const PUISSANCE_REF = "puissance";
  const DATE_REF = "date_creation";
  const AUTHOR_REF = "login";

  public function __construct($data) {
      $this->data = $data;
      $this->error = null;
  }

  public function fillData(Voiture $voiture) {
      $this->data = array(
          self::MARQUE_REF => $voiture->getMarque(),
          self::PUISSANCE_REF => $voiture->getPuissance(),
          self::DATE_REF => $voiture->getDate_creation(),
      );
  }

  public function getData() {
    return $this->data;
  }

  public function getError() {
    return $this->error;
  }

  public function createVoiture() {
    return new Voiture(
      htmlspecialchars($this->data[self::MARQUE_REF]), 
      htmlspecialchars($this->data[self::PUISSANCE_REF]), 
      $this->data[self::DATE_REF], 
      $_SESSION['user']->getLogin());
  }

  public function isValid() {
    if ($this->data[self::MARQUE_REF] === '' || $this->data[self::PUISSANCE_REF] === '' || $this->data[self::DATE_REF] < 0) {
      $this->error = "Data required";
      return false;
    }
    return true;
  }


}

 ?>
