
<h1 class="text-3xl font-bold text-clifford">
  Login
</h1>

<form action="/" enctype="multipart/form-data" method="POST" name="login">
  <label class="block mb-2">
    <span class="block text-sm font-medium text-slate-700">Username</span>
    <input
      class="block form-input placeholder:text-slate-400 peer px-4 py-2 rounded-lg"
      name="username"
      placeholder="Username"
      required
      type="text"
      value="<?php if (!is_null($app['user'])) { echo $app['user']->username; }?>"
    />
  </label>

  <label class="block">
    <span class="block text-sm font-medium text-slate-700">Password</span>
    <input class="block form-input placeholder:text-slate-400 px-4 py-2 rounded-lg" name="password" placeholder="Password" required type="password" value="password" />
  </label>

  <div class="flex flex-auto items-center mt-4">
    <span class="grow text-pink-600 text-sm">
      <?php if (!is_null($app['user']) && !$app['user']->authenticated) { echo 'The credentials are not valid.'; }?>
    </span>
    <button class="text-slate-800 px-2 border border-slate-600 rounded-md" type="submit">Login</button>
  </div>

  <input type="hidden" name="form-name" value="login-form"/>
</form>
