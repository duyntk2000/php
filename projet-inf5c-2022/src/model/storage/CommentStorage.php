<?php

/**
 *
 */
interface CommentStorage
{
  public function read($voiture_id);
  public function create(Comment $comment);
}
