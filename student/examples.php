<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['name']) && $_SESSION["role"] != "student") {
    header("Location: ../index.php");
}

require_once('../config.php');


$conn = new mysqli($hostname, $username, $password, $dbname);

// Skontroluj pripojenie
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM results WHERE student_id = " . $_SESSION['student_id']);

$assignmentSets = $conn->query("SELECT set_name, points FROM assignments_sets");

// Vytvorenie asociatívneho poľa s hodnotami points
$pointsMap = array();
while ($rowSet = $assignmentSets->fetch_assoc()) {
    $pointsMap[$rowSet['set_name']] = $rowSet['points'];
}

$result1 = $conn->query("SELECT * FROM results WHERE student_id = " . $_SESSION['student_id']  );
$examples = array();

while ($row = $result1->fetch_assoc()) {
    // Získaj obsah stĺpca expected_result
    $expectedResult = $row['expected_result'];

    // Použi regulárny výraz na extrakciu prikladu medzi "\begin{equation*}" a "\end{equation*}"
    preg_match_all('/\\\\begin\{equation\*\}(.*?)\\\\end\{equation\*\}/s', $expectedResult, $matches);

    // Ak bola nájdená aspoň jedna zhoda, pridaj ju do poľa prikladov
    if (isset($matches[1]) && !empty($matches[1])) {
        $examples = array_merge($examples, $matches[1]);
    }  
}

echo json_encode($examples);
?>