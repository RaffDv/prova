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
      
      $email = $_POST['userEmail'];
        
        try {
          try {
            if ($email != ''){
              $select = 
                "SELECT m.idMusica,m.titulo,m.artista,m.ano FROM musica m
                JOIN pessoa_musica on pessoa_musica.idMusica = m.idMusica
                JOIN pessoa on pessoa.idPessoa = pessoa_musica.idPessoa
                WHERE pessoa.email = '{$email}'";
            }
          } catch (\Throwable $th) {
            throw $th;
            die('mount query fail');
          }
          
          
          $data = $conx->query($select)->fetch_all(MYSQLI_ASSOC);

          $status = 200;
          foreach ($data as $music) 
          {
            $response[] =
            [
             '<div class="card-musica" id="'.$music['idMusica'].'">
             <div class="header">
               <p>'.$music['titulo'].'</p>
               <span>'.$music['artista'].'</span>
             </div>
             <div class="info">
               <span class="ano">'.$music['ano'].'</span>
             </div>
           </div>
           '
          ];
          }

        } catch (Exception $e) {
          $status = 401;
          $response = ['msg' => 'query failed', 'err' => $e->getMessage()];
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
