<?php
class VoitureStorageMySQL implements VoitureStorage {
  protected $bd;

  public function __construct($bd) {
    $this->bd = $bd;
  }

  public function read($id) {
    $rq = "SELECT marque, puissance, date_creation, login FROM voitures WHERE id = :id";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($info !== false) ? new Voiture($info['marque'],$info['puissance'],$info['date_creation'],$info['login']) : null;
  }

  public function readAll() {
    $stmt = $this->bd->query("SELECT * FROM voitures");
    $tableau = $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    foreach ($tableau as $i => $row) {
      $tableau[$i] = new Voiture($row['marque'], $row['puissance'], $row['date_creation'], $row['login']);
    }
    return $tableau;
  }
  public function create(Voiture $a) { 
    $rq = "INSERT INTO voitures (marque, puissance, date_creation, login) VALUE(:marque, :puissance, :date_creation, :login);";
    $stmt = $this->bd->prepare($rq);
    $data = [
      'marque' => $a->getMarque(),
      'puissance' => $a->getPuissance(),
      'date_creation' => $a->getDate_creation(),
      'login' => $a->getLogin(),
    ];
    $stmt->execute($data);

    return $this->bd->lastInsertId();
  }
  public function delete($id) {
    $rq = "DELETE FROM voitures WHERE id = :id;";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
  }
  public function update($id, Voiture $a) {
    $rq = "UPDATE voitures
           SET marque = :marque, puissance = :puissance, date_creation = :date_creation
           WHERE id = :id;";
    $stmt = $this->bd->prepare($rq);
    $data = [
      'marque' => $a->getMarque(),
      'puissance' => $a->getPuissance(),
      'date_creation' => $a->getDate_creation(),
      'id' => $id,
    ];
    $stmt->execute($data);
  }

  public function search($q) {
    $rq = "SELECT * FROM voitures WHERE marque LIKE :q";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":q", "%$q%");
    $stmt->execute();
    $tableau = $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    foreach ($tableau as $i => $row) {
      $tableau[$i] = new Voiture($row['marque'], $row['puissance'], $row['date_creation'], $row['login']);
    }
    return $tableau;
  }
}