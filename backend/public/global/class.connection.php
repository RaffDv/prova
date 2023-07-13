<?php
require(__DIR__. '/../../vendor/autoload.php');
class DB
{

  public static function connection() {
    $conn = new mysqli("localhost", "root", "", "playlists");
    return $conn;
    try {
       
    } catch (Exception $e) {
        die('Erro ao conectar com o banco de dados: ' . $e->getMessage());
    }
}
}
