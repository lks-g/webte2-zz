<?php
include 'config.php';

    if($_POST["radioGroup"] || $_POST["email"] || $_POST['aisId']){
        $myPDO = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        if($_POST["radioGroup"] == "student"){
            $stmt = $myPDO->prepare("SELECT * FROM students WHERE email = :email");
            $stmt->bindParam(":email", $_POST["email"]);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = $myPDO->prepare("SELECT * FROM students WHERE student_id = :aisId");
            $stmt->bindParam(":aisId", $_POST["aisId"]);
            $stmt->execute();
            $data2 = $stmt->fetch(PDO::FETCH_ASSOC);
            if($data == null || $data2 == null){
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
                //TODO to premen na lokaciu kam ma presmerovat studenta
                header("Location:testDashBoard.php");
            }

        }
        elseif($_POST["radioGroup"] == "teacher"){
            $stmt = $myPDO->prepare("SELECT * FROM teachers WHERE email = :email");
            $stmt->bindParam(":email", $_POST["email"]);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = $myPDO->prepare("SELECT * FROM teachers WHERE student_id = :aisId");
            $stmt->bindParam(":aisId", $_POST["aisId"]);
            $stmt->execute();
            $data2 = $stmt->fetch(PDO::FETCH_ASSOC);
            if($data == null || $data2 == null){
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
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="login.css">
    <script src="Register.js" defer></script>
    <title>Register</title>
</head>
<body>
<div id="dash">
    <form action="Register.php" method="post" id="regForm">
        <h2>Registrácia</h2>
        <input class="inp" type="text" placeholder="Krstné meno" name="name" id="firstname" required>
        <input class="inp" type="text" placeholder="Priezvisko" name="surname" id="surname" required>
        <input class="inp" type="text" placeholder="Prezivka" name="username" id="username" required>
        <input class="inp" type="text" placeholder="AisID" name="aisId" id="aisId" pattern="\d*" required min="6" max="6" >
        <p id="aisId_error" class="inp">aisId uz je zabrati alebo je zle zadane</p>
        <p id="aisIdLeangh_error" class="inp">aisId musi byt 6 cisel</p>
        <input class="inp" type="email" placeholder="Email" name="email" id="email" required>
        <p id="email_error" class="inp">Email uz je zabrati</p>
        <input class="inp" type="password" placeholder="Heslo" name="password" id="password" required>
        <input class="inp" type="password" placeholder="Zopakuj Heslo" name="re_password" id="re_password" required>
        <p id="password_error" class="inp">Hesla sa nerovnaju</p>
        <div id="volba">
            <input type="radio" name="radioGroup" id="studentOption" value="student" checked>
            <label for="studentOption">Student</label>
            <input type="radio" name="radioGroup" id="teacherOption" value="teacher">
            <label for="teacherOption">Ucitel</label>
        </div>
        <input class="btn" id="regButton" type="submit" value="Register">
    </form>
    <div>
        <span><p>Už maš účet ?</p> <a href="AuthIndex.php">Prihlás sa</a> </span>
    </div>
</div>

</body>
</html>