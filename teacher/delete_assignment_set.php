<?php

require_once('../config.php');

if(!isset($_SESSION['name']) || !isset($_SESSION['role']) != "teacher") {
    header("Location: ../index.php");
}

if (isset($_GET['set_id'])) {
    $set_id = $_GET['set_id'];

    try {
        $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("DELETE FROM assignments_sets WHERE id = :id");
        $stmt->bindParam(":id", $set_id);
        $stmt->execute();

        echo "Assignment set deleted successfully.";
    } catch (PDOException $e) {
        echo "Error deleting assignment set: " . $e->getMessage();
    }

    $db = null;
}
?>
