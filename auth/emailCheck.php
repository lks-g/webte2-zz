<?php
include 'config.php';
    $myPDO = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $stmt = $myPDO->prepare("SELECT * FROM students WHERE email = :email;");
    $stmt->bindParam(":email", $_GET["email"]);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if($data == null){
        $stmt = $myPDO->prepare("SELECT * FROM teachers WHERE email = :email;");
        $stmt->bindParam(":email", $_GET["email"]);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data == null){
            echo "yes";
        }
        else{
            echo "no";
        }
    }
    else{
        echo "no";
    }
