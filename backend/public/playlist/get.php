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
      
      $owner_token = $_GET['owner_token'];
      
      try {
        $key = 'prova';
        $decoded = JWT::decode($owner_token, new Key($key, 'HS256'));
        $token = $decoded;
        $userID = $token->userID;
        
        try {
          try {
            if ($token != null){
              $select = 
                "SELECT m.idMusica,m.titulo,m.artista,m.ano FROM musica m
                JOIN pessoa_musica on pessoa_musica.idMusica = m.idMusica
                JOIN pessoa on pessoa.idPessoa = pessoa_musica.idPessoa
                WHERE pessoa_musica.idPessoa = {$userID}";
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
               <span class="remove">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                 </svg>
               </span>
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
