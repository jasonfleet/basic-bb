<?php

session_start();

include_once 'include/config.php';
include_once 'class/Db.php';
include_once 'class/Files.php';
include_once 'class/User.php';

switch ($_SERVER["REQUEST_METHOD"]) {
  case 'GET':
    if ($_REQUEST['token']) {
      $user = User::getByToken($_REQUEST['token']);

      if ($user->authenticated) {
        if ($_REQUEST['id']) {
          return Files::getOneForUser($user, $_REQUEST['id']);
        } else {
          if ($_REQUEST['page']) {
            $page = $_REQUEST['page'];
          } else {
            $page = 1;
          }

          $result = Files::getForUser($user, $page);
          $result['token'] = $user->setToken();

          $_SESSION['not-valid-after'] = time() + SESSION_TTL_SECONDS;
          $_SESSION['token'] = $result['token'];

          echo json_encode($result);
        }
      }
    }
    break;
  case 'POST':
    if ($_POST['token'])
      $user = User::getByToken($_POST['token']);

      if ($user->authenticated) {
        Files::addToUser(
          $user,
          $_POST['data'],
          $_POST['name'],
          $_POST['size'],
          $_POST['type']
        );

        $result = Files::getForUser($user, 1);
        $result['token'] = $user->setToken();

        $_SESSION['not-valid-after'] = time() + SESSION_TTL_SECONDS;
        $_SESSION['token'] = $result['token'];


        echo json_encode($result);
      } else {
        echo '{}';
      }
    break;
  default:
    echo '{}';
}
