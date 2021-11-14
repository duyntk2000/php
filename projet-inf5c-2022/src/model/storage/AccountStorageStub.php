<?php
/**
 *
 */
class AccountStorageStub implements AccountStorage {

  protected $accounts;

  function __construct() {
    $this->accounts = array(
        "vanier" => new Account("Vanier", "vanier", "toto", "user"),
        "kiheou" => new Account("kiheou", "kiheou", "toto", "user"),
        "admin" => new Account("Nguyen", "admin", "toto", "admin"),
    );
  }

  public function checkAuth($login, $password) {
    $account = (key_exists($login, $this->accounts)) ? $this->accounts[$login] : null;
    return ($account === null) ? $account : (($account->getPassword() === $password) ? $account : null);
  }

  public function create(Account $acc) {
    $this->accounts[] = $acc;
  }

  public function exists($login) {
    return key_exists($login, $this->accounts);
  }
}

?>
