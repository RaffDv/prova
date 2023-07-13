<?php
require(__DIR__. '/../../vendor/autoload.php');
class DB
{

  public static function connection() {

    try {
        $pdo = new PDO('mysql:host=mysql;port=3306;dbname=Reddit','root','secret');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die('Erro ao conectar com o banco de dados: ' . $e->getMessage());
    }
}
}
