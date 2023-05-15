<?php

session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'sk';
}

if ($_SESSION['lang'] == 'sk') {
    include('../lang/sk.php');
} else {
    include('../lang/en.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title><?php echo $lang['title2']; ?></title>
</head>

<body>
    <div class="navbar">
        <a href="#"><?php echo $lang['menu1']; ?></a>
        <a href="#"><?php echo $lang['menu2']; ?></a>
        <a href="#"><?php echo $lang['menu3']; ?></a>
        <div class="language">
            <a href="teacher.php?lang=sk">SK</a>
            <a href="teacher.php?lang=en">EN</a>
        </div>
    </div>

    <div id="main">
        <h1><?php echo $lang['header']; ?></h1>
        <h2><?php echo $lang['welcome']; ?></h2>
    </div>

    <footer>
        <p><?php echo $lang['rights']; ?></p>
    </footer>
</body>

</html>