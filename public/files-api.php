<?php

session_start();

include_once 'include/config.php';
include_once 'class/Db.php';
include_once 'class/Files.php';
include_once 'class/User.php';

switch ($_SERVER["REQUEST_METHOD"]) {
  case 'GET':
    if ($_GET['token']) {
      $user = User::getByToken($_GET['token']);

      if ($user->authenticated) {
        if ($_GET['id']) {
          $file = Files::getOneForUser($user, $_GET['id']);

          $filename = DIR_ROOT . '/storage/' . $file['storedName'];

          file_put_contents('./storage/tmp.txt', $file);

          if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file['originalName'] . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
          }
        } else {
          if ($_GET['page']) {
            $page = $_GET['page'];
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
