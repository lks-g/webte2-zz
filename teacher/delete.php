<?php

require_once('../config.php');

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION['name']) &&  $_SESSION["role"] != "teacher") {
    header("Location: ../index.php");
}

if (isset($_GET['file'])) {
    $file_name = $_GET['file'];

    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("DELETE FROM assignments WHERE file_name = :file_name");
        $stmt->bindParam(":file_name", $file_name);
        $stmt->execute();

        $stmt = $db->prepare("SELECT file_name, date_created FROM assignments");
        $stmt->execute();
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = array("success" => true, "message" => "File '$file_name' has been deleted from the database.", "files" => $files);
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = array("success" => false, "message" => "Error deleting the file: " . $e->getMessage());
        echo json_encode($response);
    }

    $db = null;
} else {
    $response = array("success" => false, "message" => "Invalid request.");
    echo json_encode($response);
}

?>
