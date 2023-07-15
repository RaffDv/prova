<?php
require_once(__DIR__ . '/../../vendor/autoload.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function decode(string $token) {
  try {
    $key = 'prova';
    $data = JWT::decode($token, new Key($key, 'HS256'));
    return $data;
  } catch (Exception $e) {
    return false;
  }

}