<?php

session_start();





if(!isset($_SESSION['name']) &&  $_SESSION["role"] != "student" ){
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
    <title>Student Home Page</title>
</head>

<body>
    <div class="navbar">
    <a href="student_assignment.php">Generate Assignments</a>
        <a href="#">Overview of Assignments</a>
        <a href="student.php">Student Home P/age</a>
    
        <div class="language">
            <a href="teacher.php?lang=sk">SK</a>
            <a href="teacher.php?lang=en">EN</a>
        </div>
    </div>

    <div id="main">
        <h1>Student Home Page</h1>
        <h2>Welcome to the Student page</h2>
        <h1> Prihlaseny ako <?php echo $_SESSION['name']?> pod rolou <?php echo $_SESSION['role']?></h1>
    <a href="../auth/Logout.php">Odhlásiť sa</a>
    </div>

    <footer>
        <p>© 2023 - Student Home Page.</p>
    </footer>
</body>

</html>