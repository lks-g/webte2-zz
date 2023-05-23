<?php

session_start();

if (!isset($_SESSION['name']) &&  $_SESSION["role"] != "student") {
    header("Location: ../index.php");
}

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
    <link rel="stylesheet" href="../css/student.css">
    <title><?php echo $lang['student-title']; ?></title>
</head>

<body>
    <div class="navbar">
        <a href="student_assignment.php"><?php echo $lang['generateAssignments']; ?></a>
        <a href="student_overview.php"><?php echo $lang['assignmentsOverview']; ?></a>
        <a href="student.php"><?php echo $lang['student-homepage']; ?></a>
        <a class="nav-link" href="../answer_checker/answIndex.php"><?php echo $lang['menu4']; ?></a>

        <div class="language">
            <a href="student.php?lang=sk">SK</a>
            <a href="student.php?lang=en">EN</a>
        </div>
    </div>

    <div id="main">
        <h1><?php echo $lang['student-homepage']; ?></h1>
        <h2><?php echo $lang['student-welcome']; ?></h2>
        <h1><?php echo $lang['loggedIn'];?> <?php echo $_SESSION['name'] ?> <?php $lang['student-role']?> <?php echo $_SESSION['role'] ?> a tvoje id je : <?php echo $_SESSION['student_id'] ?></h1>
        <a href="../auth/Logout.php"><?php echo $lang['logOut']?></a>
    </div>

    <footer>
        <p><?php echo $lang['student-rights']; ?></p>
    </footer>
</body>

</html>