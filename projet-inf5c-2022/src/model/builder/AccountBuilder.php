<?php
class AccountBuilder {
  protected $data;
  protected $error;

  const NAME_REF = "nom";
  const LOGIN_REF = "login";
  const PASSWORD_REF = "password";
  const STATUT_REF = "statut";

  public function __construct($data) {
      $this->data = $data;
      $this->error = null;
  }

  public function fillData(Account $account) {
      $this->data = array(
          self::NAME_REF => $account->getName(),
          self::LOGIN_REF => $account->getLogin(),
          self::PASSWORD_REF => $account->getPassword(),
          self::STATUT_REF => $account->getStatut(),
      );
  }

  public function getData() {
    return $this->data;
  }

  public function getError() {
    return $this->error;
  }

  public function createAccount() {
    return new Account(
      htmlspecialchars($this->data[self::NAME_REF]),
      htmlspecialchars($this->data[self::LOGIN_REF]),
      password_hash(htmlspecialchars($this->data[self::PASSWORD_REF]), PASSWORD_BCRYPT),
      'user');
  }

  public function isValid($accounts) {
    if ($accounts->exists($this->data[self::LOGIN_REF])) {
      $this->error = "Login unavailable, try new one";
      return false;
    }
    if ($this->data[self::NAME_REF] === '' || $this->data[self::LOGIN_REF] === '' || $this->data[self::PASSWORD_REF] === '') {
      $this->error = "Data required";
      return false;
    }
    return true;
  }
}

 ?>
