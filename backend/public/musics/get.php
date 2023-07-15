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
      $titulo = isset($_POST['titulo']) ? $_POST['titulo']: false;
      $artista = isset($_POST['artista']) ? $_POST['artista']: false;
      $ano = isset($_POST['ano']) ? $_POST['ano'] : false;
      $owner_token = $_POST['owner_token'];
      
      try {
        $key = 'prova';
        $decoded = JWT::decode($owner_token, new Key($key, 'HS256'));
        $token = $decoded;
        $userID = $token->userID;
        
        try {
          try {
            if ($token != null){
              $values ='';
              if ($titulo) {
                $values .= "AND titulo LIKE '%".$titulo."%'";
              }
              if($artista){
                $values .= "AND artista = '{$artista}' ";
              }
              if($ano){
                $values .= "AND ano = {$ano} ";
              }
              
              $select = "SELECT * FROM musica WHERE 1=1";
              if($values != ''){
                $select = "SELECT * FROM musica WHERE 1=1 {$values}";

              }
            }
          } catch (\Throwable $th) {
            throw $th;
            die('mount query fail');
          }
          
          $data = $conx->query($select)->fetch_all(MYSQLI_ASSOC);
          $status = 200;
          foreach ($data as $music) {
            $response[] =
             [
                '<div class="card-musica" id="'.$music['idMusica'].'">
              <div class="header">
                <p>'.$music['titulo'].'</p>
                <span>'.$music['artista'].'</span>
              </div>
              <div class="info">
                <span class="ano">'.$music['ano'].'</span>
                <span class="to-playlist">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
              </svg>
              </div>
            </div>'
          ];


              
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
