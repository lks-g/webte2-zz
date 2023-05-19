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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/teacher.css">
    <title><?php echo $lang['title2']; ?></title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="teacher.php"><?php echo $lang['header']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="generator.php"><?php echo $lang['menu1']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="grade_overview.php"><?php echo $lang['menu2']; ?></a>
                </li>
            </ul>
            <div class="language">
                <a class="nav-link" href="teacher.php?lang=sk">SK</a>
                <a class="nav-link" href="teacher.php?lang=en">EN</a>
            </div>
        </div>
    </nav>

    <div id="main">
        <h1><?php echo $lang['header']; ?></h1>
        <h2><?php echo $lang['welcome']; ?></h2>

        <iframe src="https://site98.webte.fei.stuba.sk/zz/teacher/frame_generator.php" width="80%" height="800px"></iframe>

    </div>

    <footer>
        <p><?php echo $lang['rights']; ?></p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>