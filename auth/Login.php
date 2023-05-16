<?php
include 'config.php';

if($_POST["password"] || $_POST["email"]) {
    $myPDO = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $stmt = $myPDO->prepare("SELECT * FROM students WHERE email = :email;");
        $stmt->bindParam(":email", $_POST["email"]);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data == null) {
            $stmt = $myPDO->prepare("SELECT * FROM teachers WHERE email = :email;");
            $stmt->bindParam(":email", $_POST["email"]);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if($data == null){
                header("Location:AuthIndex.php?error=Chyba");

            }
            else{
                if(!password_verify($_POST['password'], $data['password'])){
                    header("Location:AuthIndex.php?error=Chyba");
                    echo "chyba " . $data['password'] . " " . $_POST['password'];
                }
                else {
                    session_start();
                    $_SESSION['name'] = $data['username'];
                    $_SESSION['id'] = $data['id'];
                    $_SESSION['role'] = "teacher";
                    //TODO to premen na lokaciu kam ma presmerovat ucitela
                    header("Location:teacher.php");
                    echo "good " . $data['password'] . " " . $_POST['password'];
                }
            }
        }
        else{
            if(!password_verify($_POST['password'], $data['password'])){
                header("Location:AuthIndex.php?error=Chyba");
                echo "chyba " . $data['password'] . " " . $_POST['password'];
            }
            else {
                session_start();
                $_SESSION['name'] = $data['username'];
                $_SESSION['id'] = $data['id'];
                $_SESSION['role'] = "student";
                //TODO to premen na lokaciu kam ma presmerovat studenta
                header("Location:testDashBoard.php");
                echo "good " . $data['password'] . " " . $_POST['password'];
            }
        }

}