<?php

session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'sk';
}

if ($_SESSION['lang'] == 'sk') {
    include('lang/sk.php');
} else {
    include('lang/en.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lang['title1']; ?></title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div id="login">
        <h1><?php echo $lang['login']; ?></h1>
        <form action="php/teacher.php" method="post">
            <label for="username"><?php echo $lang['username']; ?></label>
            <input type="text" name="username" id="username" placeholder="<?php echo $lang['username_placeholder']; ?>">
            <label for="password"><?php echo $lang['password']; ?></label>
            <input type="password" name="password" id="password" placeholder="<?php echo $lang['password_placeholder']; ?>">
            <input type="submit" value="<?php echo $lang['submit']; ?>">
            <a id="register" href="php/register.php"><?php echo $lang['register-text']; ?></a>
        </form>
      
        <div class="language">
            <a href="index.php?lang=sk">SK</a>
            <a href="index.php?lang=en">EN</a>
        </div>
       
    </div>
</body>

</html>