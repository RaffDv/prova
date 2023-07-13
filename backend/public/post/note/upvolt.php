<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../global/config.php';
require_once __DIR__ . '/../../global/class.connection.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$status=500;
try {
  $conx = DB::connection();
  try {
    $count = $_POST['count'];
    $post_id = $_POST['post_id'];
    $user = $_POST['user'];


    try {

      try {
        $key = 'reddit';
        $decoded = JWT::decode($user, new Key($key, 'HS256'));
        $token = $decoded;
        $user_id = $token->userID;
      } catch (Exception $e) {
        $status = 401;
        $response= ['msg' => 'token fail on decode', 'err' => $e->getMessage()];
      }

      $verifyVote = "SELECT COUNT(*) AS row_count FROM user_like WHERE user_id = {$user_id} AND post_id = {$post_id}";
      $row = $conx->query($verifyVote)->fetchAll();
      $row_count = intval($row[0]['row_count']);
      
      if ($row_count > 0) {
        $updateLike = "UPDATE user_like SET upvolt = 1, downvolt = 0 WHERE user_id = {$user_id} AND post_id = {$post_id}";
        
      } else {
        $updateLike = "INSERT INTO user_like(user_id,post_id,upvolt) VALUES ({$user_id},{$post_id},1)";
      }

         
      $conx->query($updateLike);
      
      $update = "UPDATE posts SET note = (note+{$count}) WHERE posts.id = {$post_id}";
      $conx->query($update);
      
      $status =200;
      $response= ['msg' => $updateLike,'queryVefify'=>$row_count];

    } catch (Exception $e) {
      $status = 401;
      $response= ['msg' => 'DB query fail', 'err' => $e->getMessage()];
      
    }

  } catch (Exception $e) {
    $status = 401;
    $response= ['msg' => 'data not provided', 'err' => $e->getMessage()];
  }

 
} catch (Exception $e) {
  $status = 500;
  $response= ['msg' => 'DB Connectio refused', 'err' => $e->getMessage()];
}

http_response_code($status);
echo json_encode($response);