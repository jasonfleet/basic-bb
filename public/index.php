<?php

include 'include/app.php';

$app = app();

$page = sprintf(
  "./page/%s.php",
  $app['page']
);

// TODO: no cache header
?>
<!DOCTYPE html>
<html class="h-full" lang="en">
<head>
  <title>NHR Assessment - Jason Fleet</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="description" content="" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
</head>

<body class="h-full">

  <div class="prose mx-auto justify-items-stretch grid place-content-center h-full w-full">

    <?php

      if (file_exists($page)) {
        require_once $page;
      } else {
        require_once 'page/login.php';
      }

    ?>

    <?php if ($app['user']->authenticated) { ?>
      <hr />

      <div class="justify-self-center ">
        <form enctype="multipart/form-data" method="POST" name="logout">
          <button class="text-slate-800 px-2 border border-slate-600 rounded-md" type="submit">Logout</button>

          <input type="hidden" name="form-name" value="logout-form"/>
        </form>
      </div>
    <?php } ?>

  </div>
</body>
</html>
