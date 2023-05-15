<?php

?>
<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="login.css">
    <script src="Login.js" defer></script>
    <title>Login</title>
</head>
<body>
<div id="dash">
    <form action="Login.php" method="post">
        <h2>Prihlásenie</h2>
<!--        <p class="inp" id="bad_email">Zly email alebo heslo</p>-->
        <input class="inp" type="email" placeholder="Email" name="email" id="email" required>
        <input class="inp" type="password" placeholder="Heslo" name="password" id="password" required>
        <input class="btn" type="submit" value="Prihlásiť sa">
    </form>

    <span><p>Nemaš účet ?</p> <a href="Register.php">Zaregistruj sa</a> </span>
</div>
</body>
</html>
