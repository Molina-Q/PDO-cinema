<?php
require_once "./bdd/DAO.php";



class HomeController{

    public function homePage() {
        require "./view/home.php";
    }
}

?>