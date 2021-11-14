<?php
/**
 *
 */
interface VoitureStorage
{
  public function read($id);
  public function readAll();
  public function create(Voiture $a);
  public function delete($id);
  public function update($id, Voiture $a);
  public function search($q);
}


 ?>
