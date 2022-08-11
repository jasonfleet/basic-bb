<?php

/**
 * Undocumented class
 */
class Files
{
  /**
   * Files properties as columnName-value pairs
   *
   * @var array
   */
  public $properties;

  public static function addToUser(User $user, string $data, string $name, int $size, string $type): void
  {
    $fileData = explode(',', $data);
    $fileContent = base64_decode($fileData[1]);

    $uName = time() . bin2hex(random_bytes(4));

    $nameParts = explode('.', $name);
    $ext = array_pop($nameParts);
    $storageName = $uName . '.' . $ext;

    file_put_contents(
      sprintf(
        "./storage/%s",
        $storageName
      ),
      $fileContent
    );

    $sql = "INSERT INTO files (user_id, original_name, stored_name, type, created_at) VALUES (?, ?, ?, ?, ?)";

    Db::insert($sql, [$user->properties['id'], $name, $storageName, $type, date('Y-m-d H:i:s')]);
  }

  /**
   * Undocumented function
   *
   * @param User $user
   * @param int $page
   *
   * @return array
   */
  public static function getForUser(User $user, int $page): array
  {
    $sql = "SELECT count(*) as `count` FROM files WHERE user_id = ?";
    $count = DB::query($sql, [$user->properties['id']]);

    $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY created_at DESC LIMIT " . ($page - 1) * 5 . ", 5";
    $rows = DB::query($sql, [$user->properties['id']]);

    return [
      'countAll' => (int)$count[0]['count'],
      'count' => count($rows),
      'page' => $page,
      'rows' => $rows,
    ];
  }

  /**
   * Return a file for a user
   *
   * @param User $user
   * @param integer $id
   * @return void
   */
  public static function getOneForUser(User $user, int $id): void
  {
    $sql = "SELECT * FROM files WHERE id = ? AND user_id = ?";
    $rows = DB::query($sql, [$user->properties['id'], 22]);

    if (count($rows) > 0) {
      $originalName = $rows[0]['original_name'];
      $storedName = $rows[0]['stored_name'];
      $fileLocation = DIR_ROOT . "/storage/" . $storedName;

      header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
      header("Cache-Control: public");
      header("Content-Type: " . $rows[0]['type']);
      header("Content-Transfer-Encoding: Binary");
      header("Content-Length:" . filesize($fileLocation));
      header("Content-Disposition: attachment; filename=" . $originalName);

      readfile($fileLocation);
    }
  }
}
