<?php
require_once(__DIR__ . '/../../vendor/autoload.php');
// Antes de enviar qualquer cabeçalho
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
  header('Access-Control-Allow-Credentials: true');
}

// Verifique o método OPTIONS e retorne uma resposta vazia
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header('Access-Control-Allow-Methods: POST,GET');
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
  exit;
}
