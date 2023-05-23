
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../config.php');

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