<?php

session_start();

include_once 'config.php';
include_once 'class/Db.php';
include_once 'class/User.php';

function app(): array
{
  $requestMethod = $_SERVER["REQUEST_METHOD"];
  $formName = $_REQUEST["form-name"];

  if ($requestMethod == 'POST' && $formName == 'logout-form') {
    return unauthenticated(null);
  }

  if ($_SESSION['not-valid-after'] && time() < $_SESSION['not-valid-after']) {
    $user = User::getByToken($_SESSION['token']);

    if ($user->authenticated) {
      return authenticated($user);
    }

    return unauthenticated($user);
  }

  if ($requestMethod == 'POST' && $formName == 'login-form') {
    $password = $_REQUEST['password'];
    $username = $_REQUEST['username'];

    if ($password && $username) {
      $user = User::authenticate($username, $password);

      if ($user->authenticated) {
        header('Location: ' . '/');

        return authenticated($user);
      }
    }
  }

  return unauthenticated($user);
}

/**
 * Undocumented function
 *
 * @param User $user
 *
 * @return array
 */
function authenticated(User $user): array
{
  $token = $user->setToken();

  $_SESSION['not-valid-after'] = time() + SESSION_TTL_SECONDS;
  $_SESSION['token'] = $token;

  setcookie('token', $token);

  return [
    'page' => 'files',
    'user' => $user,
  ];
}

/**
 * Undocumented function
 *
 * @param User|null $user
 *
 * @return array
 */
function unauthenticated($user): array
{
  session_destroy();

  return [
    'page' => 'login',
    'user' => $user,
  ];
}
