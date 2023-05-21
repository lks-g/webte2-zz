<?php

require_once('../config.php');

session_start();

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if (isset($_GET['file_name']) && !empty($_GET['file_name'])) {
    $file_name = $_GET['file_name'];

    $stmt = $db->prepare("SELECT latex_data FROM assignments WHERE file_name = :file_name");
    $stmt->bindParam(":file_name", $file_name);
    $stmt->execute();
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($assignment) {
        $latex_contents = $assignment['latex_data'];

        preg_match_all("/\\\\begin{(responsetask|task)}(.*?)\\\\end{(responsetask|task)}(.*?)(?=\\\\begin{(responsetask|task)}|$)/s", $latex_contents, $matches);
        $tasks = $matches[2];
        $solutions = $matches[4];

        $data = array();
        foreach ($tasks as $index => $problem) {
            $solution = $solutions[$index];

            if (preg_match("/\\\\includegraphics{(.*?)}/s", $problem, $image_matches)) {
                $image = $image_matches[1];
            } else {
                $image = "";
            }

            $parsed_problem = array(
                'problem' => $problem,
                'solution' => $solution,
                'image' => $image
            );

            $data[] = $parsed_problem;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo "No assignment found with the given file name.";
    }
} else {
    echo "File name parameter is missing or empty.";
}

$db = null;
?>
