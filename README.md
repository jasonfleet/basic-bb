# NHR Assessment

## Files
```
mysql/
  create-natural-hr-db.sql
php/
  Dockerfile
public/
  api/
    files.php
  class/
    Db.php
    Files.php
    User.php
  include/
    app.php
    config.php
  page/
    files.php
    login.php
  storage/
index.php
env.mysql
emv.php
docker-compose.yml
README.md
```

## Introduction

This README is a simple guide to using Docker with this project and presumes little prior knowledge. If you're not using Docker only the files in the `public/` folder are required, and the database tables setup is in `mysql/create-natural-hr-db.sql`.

## Setup

The project has been built using Docker (there is more about that below).

```
PHP 7.4.22 (extensions - mysqli, pdo, and pdo_mysql)
MySql 8.0.30
Apache2
```

### Docker

This is simple *guide* to using Docker in this project, see -

- [https://docs.docker.com/](https://docs.docker.com/) for more about Docker.

- [https://yukisato.dev/build-lamp-environment-with-docker-step-by-step.html](https://yukisato.dev/build-lamp-environment-with-docker-step-by-step.html) for more about the container used here.

>
> **A note on container names**
>
> The *container-name* is used to connect to a Docker container.
>
> The names, from the `docker-compose.yml` file, are -
>
> - local_web
> - local_db
>
> If you're having issues connecting to a container using
>
> `docker exec -it local_db`
>
> or see the message
>
> `error: No such container: local_db`
>
> Enter
>
> `docker container ls`
>
> For  list of the running containers.
>
> ```
> CONTAINER ID   IMAGE               COMMAND                  CREATED         STATUS         PORTS                                   NAMES
> 115baab155e4   php:7.4.22-apache   "docker-php-entrypoi…"   6 minutes ago   Up 6 minutes   0.0.0.0:8080->80/tcp, :::8080->80/tcp   local_web
> c6017a9ca3c1   mysql:8.0.26        "docker-entrypoint.s…"   23 hours ago    Up 6 minutes   3306/tcp, 33060/tcp                     c6017a9ca3c1_local_db
> ```
>
> and use the value in the `NAMES` column as the *container-name*.
>
> `docker exec -it c6017a9ca3c1_local_db`
>
> The same is true for `local_web`.

#### Build

First time - build and run the container.
```
docker-compose up --build
```

After the first time.
```
docker-compose up
```

To open a bash shell in the container.
```
docker exec -it local_web bash
```

Open the browser to [http://localhost:8080/](http://localhost:8080/)

### PHP and APACHE

To add files you will need to change the *permission* of the storage folder -

```
## open a bash shell to the container

docker exec -it local_web bash

## then change the permission

chown 1000:www-data storage
```

Presumes the user is `www-data`.  If it is not the command below (courtesy of stack-exchange)

```
ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1
```

### MySQL

The database is created during build.

> Database: `nhr`
> Username: `web_user`
> Password: `password`
> Root Password: `password`

(see `.env.mysql` and `.env.php`)

To intialise tables and data.

```
## Open a bash shell to the container.

docker exec -it local_db bash

## Open mysql cli

mysql -uroot -ppassword

## use the `create-natural-hr-db.sql` file to initialise the tables.

mysql> source /home/create-natural-hr-db.sql
```

It will create 2 tables.

```
mysql> DESCRIBE files;

+---------------+--------------+------+-----+---------+----------------+
| Field         | Type         | Null | Key | Default | Extra          |
+---------------+--------------+------+-----+---------+----------------+
| id            | int unsigned | NO   | PRI | NULL    | auto_increment |
| user_id       | int unsigned | NO   |     | NULL    |                |
| original_name | varchar(255) | NO   |     | NULL    |                |
| stored_name   | varchar(255) | NO   |     | NULL    |                |
| type          | varchar(255) | NO   |     | NULL    |                |
| created_at    | timestamp    | NO   |     | NULL    |                |
+---------------+--------------+------+-----+---------+----------------+

mysql> DESCRIBE users;

+---------------+--------------+------+-----+---------+----------------+
| Field         | Type         | Null | Key | Default | Extra          |
+---------------+--------------+------+-----+---------+----------------+
| id            | int unsigned | NO   | PRI | NULL    | auto_increment |
| password      | varchar(255) | NO   |     | NULL    |                |
| token         | varchar(255) | YES  |     | NULL    |                |
| username      | varchar(255) | NO   | MUL | NULL    |                |
| created_at    | timestamp    | NO   |     | NULL    |                |
| last_login_at | timestamp    | YES  |     | NULL    |                |
+---------------+--------------+------+-----+---------+----------------+
```

The `users` table has one user -

Username: `natural`
Password: `password`

The `create-natural-hr-db.sql` can be modified and used to initialise the database at any time.

## End Note

The solution here is not an approach I would take towards a larger project - it is focused and will not scale.

The setup has been kept relatively basic - no use of composer packages or jQuery plugins, and no changes to the server (no URL rewriting). I use Tailwind for convenience.

The mix of procedural and Class php code

The token used for the API request is arbitrary, JWT is the accepted and secure way.
