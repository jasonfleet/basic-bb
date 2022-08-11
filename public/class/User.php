<?php

/**
 * Undocumented class
 */
class User
{

  /**
   * True if the user has been authenticated
   *
   * @var bool
   */
  public $authenticated;

  /**
   * User properties as columnName-value pairs
   *
   * @var array
   */
  public $properties;

  /**
   * The user token
   *
   * @var string
   */
  public $token;

  /**
   * The login username
   *
   * @var string
   */
  public $username;

  /**
   * Returns the user with the $password and $username if there is one.
   *
   * @param string $username
   * @param string $password
   *
   * @return User
   */
  public static function authenticate(string $username, string $password): User
  {
    $user = new User();

    $user->username = $username;

    $hashedPassword = hash('sha256', $password . PASSWORD_SALT);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

    $result = DB::query($sql, [$user->username, $hashedPassword]);

    if (!empty($result)) {
      unset($result[0]['password']);

      $user->authenticated = true;
      $user->properties = $result[0];

      $sql = "UPDATE users SET last_login_at = ? WHERE username = ?";

      DB::update($sql, [date('Y-m-d H:i:s'), $user->username]);
    }

    return $user;
  }

  /**
   * Returns a user for $token if one exists
   *
   * @param string $token
   *
   * @return User
   */
  public static function getByToken(string $token): User
  {
    $user = new User();

    $sql = "SELECT * FROM users WHERE token = ?";

    $result = DB::query($sql, [$token]);

    if (!empty($result)) {
      $user->username = $result[0]['username'];
      unset($result[0]['password']);

      $user->authenticated = true;
      $user->token = $token;
      $user->properties = $result[0];
    }

    return $user;
  }

  /**
   * Set and return the token for user
   *
   * @return string
   */
  public function setToken(): string
  {
    $token = 'token-' . bin2hex(random_bytes(4));

    $this->token = $token;

    $sql = "UPDATE users SET token = ? WHERE username = ?";

    DB::update($sql, [$token, $this->username]);

    return $token;
  }
}
