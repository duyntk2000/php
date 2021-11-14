<?php
class CommentBuilder {
  protected $data;
  protected $error;

  const VOITURE_ID_REF = "voiture_id";
  const LOGIN_REF = "login";
  const TEXT_REF = "texte";

  public function __construct($data) {
      $this->data = $data;
      $this->error = null;
  }

  public function getData() {
    return $this->data;
  }

  public function getError() {
    return $this->error;
  }

  public function createComment() {
    return new Comment(
      htmlspecialchars($this->data[self::VOITURE_ID_REF]),
      htmlspecialchars($this->data[self::LOGIN_REF]),
      htmlspecialchars($this->data[self::TEXT_REF]));
  }

  public function isValid() {
    if ($this->data[self::TEXT_REF] === '') {
      $this->error = "Comment something";
      return false;
    }
    return true;
  }
}

 ?>
