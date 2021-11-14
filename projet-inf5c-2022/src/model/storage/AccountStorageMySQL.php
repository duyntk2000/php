<?php

class AccountStorageMySQL implements AccountStorage {
  protected $bd;

  public function __construct($bd) {
    $this->bd = $bd;
  }

  public function checkAuth($login, $password) {
    $rq = "SELECT * FROM accounts WHERE login = :login;";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":login", $login);
    $stmt->execute();
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($info !== false && password_verify($password, $info['password'])) {
      return new Account($info['nom'], $info['login'], $info['password'], $info['statut']);
    }
    return null;
  }

  public function create(Account $acc) {
    $rq = "INSERT INTO accounts (login, password, nom, statut) VALUE(:login, :password, :nom, :statut);";
    $stmt = $this->bd->prepare($rq);
    $data = [
      'login' => $acc->getLogin(),
      'password' => $acc->getPassword(),
      'nom' => $acc->getNom(),
      'statut' => $acc->getStatut(),
  ];
    $stmt->execute($data);
  }

  public function exists($login) {
    $rq = "SELECT EXISTS(SELECT * FROM accounts WHERE login = :login) AS 'exists';";
    $stmt = $this->bd->prepare($rq);
    $stmt->bindValue(":login", $login);
    $stmt->execute();
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    return boolval($info['exists']);
  }

}
