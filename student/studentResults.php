<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['name']) && $_SESSION["role"] != "student") {
    header("Location: ../index.php");
}

require_once('../config.php');

$conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$result = $conn->prepare("SELECT student_result FROM results WHERE student_id = " . $_SESSION['student_id']);
$result->execute();
$data =  $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>