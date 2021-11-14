<?php
class ImageStorageStub implements ImageStorage {
  protected $storage;

  public function __construct() {
    $this->storage = array(
            new Image('1',"i1.jpeg"),
            new Image('1',"i2.jpeg"),
            new Image('2',"i3.jpeg"),
    );
  }

  public function read($voitureID) {
    $images = array();
    foreach ($this->storage as $value) {
      if ($value->getVoitureID() === $voitureID) {
        $images[] = $value;
      }
    }
    return $images;
  }

  public function create(Image $img) {
    $this->storage[] = $img;
  }

  public function delete($voitureID, $image) {
    unset($this->storage[$id]);
  }

  public function update($voitureID, $oldImg, $newImg) {
    $this->storage[$voitureID] = $img;
  }

}
