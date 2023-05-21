<?php

require_once('../config.php');

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


if ($_POST["radioGroup"] || $_POST["email"] || $_POST['aisId']) {
    $myPDO = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    if ($_POST["radioGroup"] == "student") {
        $stmt = $myPDO->prepare("SELECT * FROM students WHERE email = :email");
        $stmt->bindParam(":email", $_POST["email"]);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $myPDO->prepare("SELECT * FROM students WHERE student_id = :aisId");
        $stmt->bindParam(":aisId", $_POST["aisId"]);
        $stmt->execute();
        $data2 = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data == null || $data2 == null) {
            $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt = $myPDO->prepare("INSERT INTO students (first_name, last_name, student_id, username, email, password) VALUES (:first_name, :last_name, :aisId, :username,  :email, :password)");
            $stmt->bindParam(":first_name", $_POST["name"]);
            $stmt->bindParam(":last_name", $_POST["surname"]);
            $stmt->bindParam(":username", $_POST["username"]);
            $stmt->bindParam(":aisId", $_POST["aisId"]);
            $stmt->bindParam(":email", $_POST["email"]);
            $stmt->bindParam(":password", $passwordHash);
            $stmt->execute();
            if (!isset($user_id))
                $user_id = $myPDO->lastInsertId();
            session_start();
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $user_id;
            $_SESSION['role'] = $_POST["radioGroup"];
            header("Location:../student/student.php");
        }
    } elseif ($_POST["radioGroup"] == "teacher") {
        $stmt = $myPDO->prepare("SELECT * FROM teachers WHERE email = :email");
        $stmt->bindParam(":email", $_POST["email"]);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $myPDO->prepare("SELECT * FROM teachers WHERE student_id = :aisId");
        $stmt->bindParam(":aisId", $_POST["aisId"]);
        $stmt->execute();
        $data2 = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data == null || $data2 == null) {
            $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt = $myPDO->prepare("INSERT INTO teachers (first_name, last_name, student_id, username,  email, password) VALUES (:first_name, :last_name, :aisId, :username, :email, :password)");
            $stmt->bindParam(":first_name", $_POST["name"]);
            $stmt->bindParam(":last_name", $_POST["surname"]);
            $stmt->bindParam(":username", $_POST["username"]);
            $stmt->bindParam(":aisId", $_POST["aisId"]);
            $stmt->bindParam(":email", $_POST["email"]);
            $stmt->bindParam(":password", $passwordHash);
            $stmt->execute();
            if (!isset($user_id))
                $user_id = $myPDO->lastInsertId();
            session_start();
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $user_id;
            $_SESSION['role'] = $_POST["radioGroup"];
            //TODO to premen na lokaciu kam ma presmerovat ucitela
            header("Location:../teacher/teacher.php");
        }
    }
}
?>

<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/login.css">
    <script src="Register.js" defer></script>
    <title><?php echo $lang['title7']; ?></title>
</head>

<body>
    <div id="login">
        <form action="Register.php" method="post" id="regForm">
            <h2><?php echo $lang['register-title']; ?></h2>
            <input class="inp" type="text" placeholder="<?php echo $lang['firstName']; ?>" name="name" id="firstname" required>
            <input class="inp" type="text" placeholder="<?php echo $lang['lastName']; ?>" name="surname" id="surname" required>
            <input class="inp" type="text" placeholder="<?php echo $lang['nick']; ?>" name="username" id="username" required>
            <input class="inp" type="text" placeholder="<?php echo $lang['aisID']; ?>" name="aisId" id="aisId" pattern="\d*" required min="6" max="6">
            <p id="aisId_error" class="inp"><?php echo $lang['aidIDError']; ?></p>
            <p id="aisIdLeangh_error" class="inp"><?php echo $lang['aisIDLengthError']; ?></p>
            <input class="inp" type="email" placeholder="<?php echo $lang['email_placeholder']; ?>" name="email" id="email" required>
            <p id="email_error" class="inp"><?php echo $lang['emailError']; ?></p>
            <input class="inp" type="password" placeholder="<?php echo $lang['password_placeholder']; ?>" name="password" id="password" required>
            <input class="inp" type="password" placeholder="<?php echo $lang['retryPassword']; ?>" name="re_password" id="re_password" required>
            <p id="password_error" class="inp"><?php echo $lang['passError']; ?></p>
            <div id="volba">
                <input type="radio" name="radioGroup" id="studentOption" value="student" checked>
                <label for="studentOption"><?php echo $lang['student']; ?></label>
                <input type="radio" name="radioGroup" id="teacherOption" value="teacher">
                <label for="teacherOption"><?php echo $lang['teacher']; ?></label>
            </div>
            <input class="btn" id="regButton" type="submit" value="<?php echo $lang['register']; ?>">
        </form>
        <div>
            <span>
                <p><?php echo $lang['haveAcc']; ?></p> <a href="../index.php"><?php echo $lang['loginNow']; ?></a>
            </span>
        </div>

        <div class="language">
            <a href="Register.php?lang=sk">SK</a>
            <a href="Register.php?lang=en">EN</a>
        </div>
    </div>

</body>

</html>