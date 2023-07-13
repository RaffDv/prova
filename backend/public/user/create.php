<?php
require_once __DIR__.'/../global/class.connection.php';
require_once __DIR__.'/../global/config.php';

$status = 500;
try {
  $conx = DB::connection();

  try { // get data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    try {

      if($email != '')
      {
        if ($pass != '') {
          $insert = "INSERT INTO users(username,email,pass) VALUES ('{$username}','{$email}','{$pass}');";
          $conx->query($insert);
    
          $status = 200;
          $response = ['msg' => 'User created'];
          
        }
        else
        {
          $status = 409;
          $response = [
          'msg' => 'pass is null'
          ];
        }

      }
      else
      {
        $status = 409;
        $response = [
        'msg' => 'Email is null'
        ];
      }

    } catch (Exception $e) {
      if (stripos($e->getMessage(), 'violation: 1062') !== false) {
        $status = 401;
        $response = [
            'msg' => 'Username in use - code 1062 ',
            'err' => $e->getMessage()
        ];
    }
    else
    {
      $status = 401;
      $response = [
      'msg' => 'Query failed',
      'err' => $e->getMessage()
      ];

    }

    }

  } catch (Exception $e) {

    $status = 409;
    $response = [
    'msg' => 'Get data return a error',
    'err' => $e->getMessage()
    ];
  }

} catch (Exception $e) {
  $status = 401;
  $response = [
    'msg' => 'DB connection refused',
    'err' => $e->getMessage()
  ];
}

http_response_code($status);
echo json_encode($response);