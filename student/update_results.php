<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../config.php'); 

if (isset($_POST['updateQuery']) && isset($_POST['values'])) {
  $updateQuery = $_POST['updateQuery'];
  $values = $_POST['values'];

  $conn = new mysqli($hostname, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare($updateQuery);
  $stmt->bind_param("s", $values, $student_id); // Assuming student_id is already defined and available

  if ($stmt->execute()) {
    echo "Results updated successfully.";
  } else {
    echo "Error updating results: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
} else {
  echo "Invalid request.";
}
?>






