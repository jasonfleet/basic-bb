<?php

include_once 'include/config.php';
include_once 'class/Db.php';
include_once 'class/Files.php';
include_once 'class/User.php';

if ($_REQUEST['token']) {
  $user = User::getByToken($_REQUEST['token']);

  if ($user->authenticated) {
    switch ($_SERVER["REQUEST_METHOD"]) {
      case 'GET':
        if ($_REQUEST['id']) {
          return Files::getOneForUser($user, $_REQUEST['id']);
        } else {
          if ($_REQUEST['page']) {
            $page = $_REQUEST['page'];
          } else {
            $page = 1;
          }

          $result = Files::getForUser($user, $page);
          $result['token'] = $user->token;
          echo json_encode($result);
        }

        break;
      case 'POST':
        Files::addToUser(
          $user,
          $_REQUEST['data'],
          $_REQUEST['name'],
          $_REQUEST['size'],
          $_REQUEST['type']
        );
        echo json_encode(Files::getForUser($user, 1));
        break;
      default:
      echo '{}';
    }
  }
}
