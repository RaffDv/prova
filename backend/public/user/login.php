<?php
require_once __DIR__. '/../../vendor/autoload.php';
require_once __DIR__. '/../global/class.connection.php';
require_once __DIR__. '/../global/config.php';

use Firebase\JWT\JWT;

$status=500;
try {
  $conn = DB::connection();

  try {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    try {
      $select = "SELECT idPessoa,email FROM pessoa WHERE email = '{$email}' and senha = '{$pass}'";
      $r = $conn->query($select)->fetch_all(MYSQLI_ASSOC);


      $key = 'prova';
      $payload = [
          'exp' => strtotime('+5day'),
          'iat' => time(),
          'userID' => $r[0]['idPessoa'],
          'email' => $r[0]['email']
      ];

      try {
        $token = JWT::encode($payload, $key, 'HS256');
      } catch (\Throwable $th) {
        $status = 405;
        $response = ['msg' => 'cannot encode JWT token'];
      }

      
      $status = 200;
      $response = ['token'=> $token ];

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