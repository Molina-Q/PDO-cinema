<?php
require_once "./bdd/DAO.php";
class GenreController {
   
    public function findAll() {

        $dao = new DAO();
        $sql =
        "SELECT g.id_genre, g.libelle 
        FROM genre g";

        $genres = $dao->executerRequete($sql);
        require "./view/genre/everyGenres.php";
    }
    
}

?>