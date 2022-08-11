USE nhr

DROP TABLE IF EXISTS files;

CREATE TABLE IF NOT EXISTS files (
  `id` INT UNSIGNED AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `stored_name` VARCHAR(255) NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS users;

CREATE TABLE users (
  `id` INT UNSIGNED AUTO_INCREMENT,
  `password` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255),
  `username` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `last_login_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`username`)
);

INSERT INTO users (`password`, `username`, `created_at`, `last_login_at`)
VALUES ('ee34ade261e74cd5c69e8a828e6b3f04263c24c06038cd25420359dd5075076e', 'natural', NOW(), NULL);
