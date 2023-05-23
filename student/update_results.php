<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../config.php');

if (isset($_POST['data'])) {
  // Get values from POST data
  $data = json_decode($_POST['data'], true);
  $userInputArray = $data['userInputArray'];
  $studentId = $data['studentId'];

  // Connect to the database
  $conn = new mysqli($hostname, $username, $password, $dbname);

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $userInputArrayJson = json_encode($userInputArray);
  $sql = "UPDATE results SET student_result = '$userInputArrayJson' WHERE student_id = '$studentId' AND submitted IS NULL";
  if ($conn->query($sql) === TRUE) {
    echo "Pole userInputArray úspešne uložené do databázy.";
    echo $userInputArrayJson;
  } else {
    echo "Chyba pri ukladaní pola userInputArray do databázy: " . $conn->error;
  }

  $conn->close();
}

echo "TESTYTTE";

if (isset($_POST['evaluateResults'])) {
  
  $evaluateResults = $_POST['evaluateResults'];
  $conn = new mysqli($hostname, $username, $password, $dbname);

  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  // Prepare and bind the SQL statement
  $stmt = $conn->prepare("UPDATE results SET points = ?");
  
  echo $evaluateResults;

  foreach ($evaluateResults as $points) {
    if (!$points ){
      $stmt->bind_param("i", "0");
      $stmt->execute();
    }
   
  }

 
  $stmt->close();
  $conn->close();
}


?>