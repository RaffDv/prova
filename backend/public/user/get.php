<?php
require_once __DIR__. '/../../vendor/autoload.php';
require_once __DIR__. '/../global/class.connection.php';
require_once __DIR__. '/../global/config.php';
require_once __DIR__. '/../global/decode.php';

use Firebase\JWT\JWT;

$status=500;
try {
  $conn = DB::connection();

  try {

    $owner_token = $_GET['owner_token'];
      
      try {
        
       $user = decode($owner_token);
      } catch (\Throwable $th) {
        $response = ['msg' => 'error to decode token'];
      }

    try {
      
      $status = 200;
      $response = ['email'=> $user->email];

    } catch (Exception $e) {
      $status = 405;
      $response = ['msg' => 'data provided refused', 'err' => $e->getMessage()];
    }

  } catch (Exception $e) {
    $status = 409;
    $response = ['msg' => 'Minumum Fields required not informed', 'err' => $e->getMessage()];

  }

} catch (Exception $e) {
  $status = 405;
  $response = ['msg' => 'DB conn refused', 'err' => $e->getMessage()];
}

http_response_code($status);
echo json_encode($response);