<?php

class CommentStorageMySQL implements CommentStorage {
  protected $bd;

  public function __construct($bd) {
    $this->bd = $bd;
  }

  public function read($voitureID) {
    $rq = "SELECT login, texte FROM comments WHERE voiture_id = :voitureID";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":voitureID", $voitureID, PDO::PARAM_INT);
    $stmt->execute();
    $info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res = array();
    foreach ($info as $value) {
      $res[] = new Comment($voitureID, $value['login'], $value['texte']);
    }
    return $res;
  }

  public function create(Comment $comment) {
    $rq = "INSERT INTO comments (voiture_id, login, texte) VALUE(:voiture_id, :login, :texte);";
    $stmt = $this->bd->prepare($rq);
    $data = [
      'voiture_id' => $comment->getVoitureID(),
      'login' => $comment->getLogin(),
      'texte' => $comment->getTexte(),
  ];
    $stmt->execute($data);
  }

}
