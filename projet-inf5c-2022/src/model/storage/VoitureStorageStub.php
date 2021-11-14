<?php
class VoitureStorageStub implements VoitureStorage {
  protected $storage;

  public function __construct() {
    $this->storage = array(
        'Bugati' => new Voiture('Bugati', '420 km/h', 1909, 'admin'),
        'honda' => new Voiture('honda', '120 ch/L', 1999, 'admin'),
        'peugeot' => new Voiture('peugeot', '325 ch', 1929, 'admin'),
    );
  }

  public function read($id) {
    if (key_exists($id, $this->storage)) {
      return $this->storage[$id];
    }
    return null;
  }

  public function readAll() {
    return $this->storage;
  }

  public function search($q) {
    return $this->storage;
  }

  public function create(Voiture $a) {
    $this->storage[$a->marque] = $a;
  }

  public function delete($id) {
    unset($this->storage[$id]);
  }

  public function update($id, Voiture $a) {
    $this->storage[$id] = $a;
  }
}
 ?>
