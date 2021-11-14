<?php

class ImageStorageMySQL implements ImageStorage {
  protected $bd;

  public function __construct($bd) {
    $this->bd = $bd;
  }

  public function read($voitureID) {
    $rq = "SELECT voiture_id, image FROM voiture_images WHERE voiture_id = :voitureID";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":voitureID", $voitureID, PDO::PARAM_INT);
    $stmt->execute();
    $info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res = array();
    foreach ($info as $value) {
      $res[] = new Image($value['voiture_id'], $value['image']);
    }
    return $res;
  }

  public function create(Image $img) {
    $rq = "INSERT INTO voiture_images (voiture_id, image) VALUE(:voiture_id, :image);";
    $stmt = $this->bd->prepare($rq);
    $data = [
      'voiture_id' => $img->getVoitureID(),
      'image' => $img->getImage(),
  ];
    $stmt->execute($data);
  }

  public function exists($voitureID, $image) {
    $rq = "SELECT EXISTS(SELECT * FROM voiture_images WHERE voiture_id = :voiture_id AND image = :image) AS 'exists';";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":voiture_id", $voitureID);
    $stmt->bindValue(":image", $image);
    $stmt->execute();
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    return boolval($info['exists']);
  }

  public function delete($voitureID, $image) {
    $rq = "DELETE FROM voiture_images WHERE voiture_id = :voiture_id AND image = :image;";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":voiture_id", $voitureID);
    $stmt->bindValue(":image", $image);
    $stmt->execute();
  }

  public function update($voitureID, $oldImg, $newImg) {
    $rq = "UPDATE voiture_images
           SET image = :newImage
           WHERE voiture_id = :voiture_id
           AND image = :oldImage;";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":voiture_id", $voitureID);
    $stmt->bindValue(":newImage", $newImg);
    $stmt->bindValue(":oldImage", $oldImg);
    $stmt->execute();
  }

}
?>
