<?php

define("PASSWORD_SALT", "some text to salt the password with");
define("SESSION_SALT", "some text to salt session with");

define("SESSION_TTL_SECONDS", 360);

define("DIR_ROOT", getcwd());

define('DB_HOST', getenv('MYSQL_HOST') ?: 'db');
define('DB_DATABASE', getenv('MYSQL_DATABASE') ?: 'nhr');
define('DB_USER', getenv('MYSQL_USER') ?: 'natural');
define('DB_PASSWORD', getenv('MYSQL_PASSWORD') ?: 'password');
