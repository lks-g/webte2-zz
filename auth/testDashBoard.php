<?php
//TODO pridaj toto php (len php cast) na zaciatok kodu aby sa skontrolovalo ci je clovek prihlaseny, popripade uprav aby testoval ci ma
//TODO spravnu rolu , pre student $_SESSION['role'] = "student" a pre teacher $_SESSION['role'] = "teacher"
session_start();
if(!isset($_SESSION['name']) || !isset($_SESSION['role'])){
    header("Location: AuthIndex.php");
}

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>testDashBoard</title>
</head>
<body>
<header>
    <h1> Prihlaseny ako <?php echo $_SESSION['name']?> pod rolou <?php echo $_SESSION['role']?></h1>
    <a href="Logout.php">Odhlásiť sa</a>
</header>