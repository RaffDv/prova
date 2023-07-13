<?php
require_once(__DIR__ . '/../../vendor/autoload.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function decode(string $token) {
  try {
    $key = 'reddit';
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    $token = $decoded;
    $userID = $token->userID;
    return $userID;
  } catch (Exception $e) {
    return false;
  }

}