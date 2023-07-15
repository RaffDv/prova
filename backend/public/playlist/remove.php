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
      $musicaID = isset($_POST['musicaID'])?$_POST['musicaID']:false;
      $owner_token = isset($_POST['owner_token'])?$_POST['owner_token']:false;

      try {
        $key = 'prova';
        $decoded = JWT::decode($owner_token, new Key($key, 'HS256'));
        $token = $decoded;
        $userID = $token->userID;
        
        try {
          try {
            if ($token != null){
              $delete = "DELETE FROM pessoa_musica WHERE idPessoa = '{$userID}' and idMusica = '{$musicaID}'";
            }
          } catch (\Throwable $th) {
            throw $th;
            die('mount query fail');
          }
          
          $conx->query($delete);
          $status = 200;
          $response = ['msg' => 'success'];
        } catch (Exception $e) {
          $status = 401;
          $response = ['msg' => 'query failed', 'err' => $e->getMessage(),'query' => $delete];
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
