<?php
class Image {
  protected $voitureID;
  protected $image;

  public function __construct($voitureID, $image) {
    $this->voitureID = $voitureID;
    $this->image = $image;
  }

  public function getVoitureID() {
    return $this->voitureID;
  }

  public function getImage() {
    return $this->image;
  }
}
?>
