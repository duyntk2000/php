<?php
class Voiture {
    protected $marque;
    protected $puissance;
    protected $date_creation;
    protected $login;

    public function __construct($marque, $puissance, $date_creation, $login) {
        $this->marque = $marque;
        $this->puissance = $puissance;
        $this->date_creation = $date_creation;
        $this->login = $login;
    }

    public function getMarque() {
        return $this->marque;
    }

    public function getPuissance() {
        return $this->puissance;
    }

    public function getDate_creation() {
        return $this->date_creation;
    }

    public function getLogin() {
        return $this->login;
    }
}
?>
