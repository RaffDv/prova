<?php
require_once __DIR__ . '/../global/config.php';
require_once __DIR__ . '/../global/decode.php';
require_once __DIR__ . '/../global/class.connection.php';

$status=500;
try {
  $conx = DB::connection();

  try {
    $select = "SELECT title,content,note,createdAt,id FROM posts  ORDER BY note DESC";
    $r = $conx->query($select)->fetchAll();

    $token = $_GET['token'];
    $user_id = decode($token);


    $votes = "SELECT upvolt,downvolt,post_id FROM user_like WHERE user_id = {$user_id}";
    $votes = $conx->query($votes)->fetchAll();


    foreach ($votes as $value) {

      foreach($value as $idx => $pos){
        // print_r($idx . '-> '.$pos. PHP_EOL);
        // continue;
        $disableUp = $idx === 'upvolt' && $pos ? 'disabled' : '';
        $disableDw =  $idx === 'downvolt' && $pos  ? 'disabled' : '';
        $id = $idx === 'post_id' = $pos;

        echo $id . PHP_EOL;
        continue;

        print_r($disableDw . ' ' . $disableUp . PHP_EOL);
  
        $postHTML[] = '<div class="code-editor">
        <div class="header">
          <div class="note-div">
            <span id="upvolt-'.$post['id'].'" class="upvolt '.$disableUp.'">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up">
                <polyline points="18 15 12 9 6 15"/>
              </svg>
            </span>
            <strong class="note">
             '.$post['note'].'
            </strong>
            <span id="downvolt-'.$post['id'].'" class="downvolt '.$disableDw.'">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down">
                <polyline points="6 9 12 15 18 9"/>
              </svg>
            </span>
          </div>
          <span class="title">'.$post['title'].'</span>
        </div>
        <div class="editor-content">
          <div class="code">
          '.$post['content'].'
          </div>
        </div>
      </div>';
     
      }

    
    }

    $status = 200;
    $response= ['posts' => $postHTML];
  } catch (Exception $e) {
    $status = 401;
    $response= ['msg' => 'DB query fail', 'err' => $e->getMessage()];
    
  }
} catch (Exception $e) {
  $status = 500;
  $response= ['msg' => 'DB Connectio refused', 'err' => $e->getMessage()];
}

http_response_code($status);
echo json_encode($response);