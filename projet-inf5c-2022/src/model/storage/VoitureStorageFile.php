<?php
class VoitureStorageFile implements VoitureStorage {
    protected $db;

    public function __construct($file) {
        $this->db = new ObjectFileDB($file);
    }

    public function reinit() {
        $this->db->deleteAll();
        $this->db->insert(new Voiture('Bugati', '420 km/h', 1909, 'admin'));
        $this->db->insert(new Voiture('honda', '120 ch/L', 1999, 'admin'));
        $this->db->insert(new Voiture('peugeot', '325 ch', 1929, 'admin'));
    }

    public function exists($id) {
      return $this->db->exists($id);
    }

    public function read($id) {
        return ($this->db->exists($id)) ? $this->db->fetch($id) : null;
    }

    public function readAll() {
        return $this->db->fetchAll();
    }

    public function search($q) {
        return $this->db->fetchAll();
    }

    public function create(Voiture $a) {
        return $this->db->insert($a);
    }

    public function delete($id) {
        $this->db->delete($id);
    }

    public function update($id, Voiture $a) {
        $this->db->update($id, $a);
    }

}
?>
