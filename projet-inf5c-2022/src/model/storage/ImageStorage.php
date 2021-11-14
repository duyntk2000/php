<?php
/**
 *
 */
interface ImageStorage
{
  public function read($voitureID);
  public function create(Image $img);
  public function delete($voitureID, $image);
  public function update($voitureID, $oldImg, $newImg);
}


 ?>
