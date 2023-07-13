<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../global/config.php';
require_once __DIR__ . '/../global/class.connection.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
  $status = 500;
  try {
    $conx = DB::connection();
    
    try {
      $titulo = $_POST['titulo'];
      $artista = $_POST['artista'];
      $ano = $_POST['ano'];
      $owner_token = $_POST['owner_token'];
      
      try {
        $key = 'prova';
        $decoded = JWT::decode($owner_token, new Key($key, 'HS256'));
        $token = $decoded;
        $userID = $token->userID;
        
        try {
          try {
            if ($token != null){
              $select = "SELECT * FROM musica WHERE titulo ='{$titulo}' OR artista ='{$artista}' OR ano ='{ano}' ";
            }
          } catch (\Throwable $th) {
            throw $th;
            die('mount query fail');
          }
          
          $data = $conx->query($select)->fetch_all(MYSQLI_ASSOC);
          $status = 200;
          foreach ($data as $music) {
            $response[] = $music;
          }
          
        } catch (Exception $e) {
          $status = 401;
          $response = ['msg' => 'query failed', 'err' => $e->getMessage()];
        }
      } catch (Exception $e) {
        $status = 401;
        $response = ['msg' => 'Token decode failed', 'err' => $e->getMessage()];
      }
    } catch (Exception $e) {
      $status = 409;
      $response = ['msg' => 'Minimum fields not provided', 'err' => $e->getMessage()];
    }
  } catch (Exception $e) {
    $status = 500;
    $response = ['msg' => 'DB connection refused', 'err' => $e->getMessage()];
  }
} catch (Exception $e) {
  echo "erro desconhecido";
}

http_response_code($status);
echo json_encode($response);
