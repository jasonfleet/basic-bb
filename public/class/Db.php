<?php


class DB
{
  /**
   * The instance of PDO
   *
   * @var PDO
   */
  private static $pdo = null;

  /**
   * Returns the instance of PDO
   *
   * @return PDO
   */
  private function getPdo(): PDO
  {
    if (is_null(self::$pdo)) {
      self::setPdo();
    }

    return self::$pdo;
  }

  /**
   * Insert db table
   *
   * @param string $sql
   * @param array $values
   *
   * @return boolean
   */
  public static function insert(string $sql, array $values): bool
  {
    $pdo = self::getPdo();

    $query = $pdo->prepare($sql);

    $query->execute($values);

    return true;
  }

  /**
   * Query Db
   *
   * @param string $sql
   * @param string $class
   * @param array $values
   *
   * @return array
   */
  public static function query(string $sql, array $values): array
  {
    $pdo = self::getPdo();

    $query = $pdo->prepare($sql);

    $result = $query->execute($values);

    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Set the PDO instance
   *
   * @return void
   */
  private static function setPdo(): void
  {
    self::$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_DATABASE, DB_USER, DB_PASSWORD);
  }

  /**
   * Update db table
   *
   * @param string $sql
   * @param array $values
   *
   * @return boolean
   */
  public static function update(string $sql, array $values): bool
  {
    $pdo = self::getPdo();

    $query = $pdo->prepare($sql);

    return $query->execute($values);
  }
}
