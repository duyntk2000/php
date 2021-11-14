<?php
interface AccountStorage {
  public function checkAuth($login, $password);
  public function create(Account $acc);
  public function exists($login);
}
?>
