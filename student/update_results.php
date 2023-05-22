<?php

require_once('../config.php');

if (isset($_POST['data'])) {
  // Získať hodnoty z POST dát
  $data = json_decode($_POST['data'], true);
  $userInputArray = $data['userInputArray'];
  $studentId = $data['studentId'];

  // Pripojiť sa k databáze
  $conn = new mysqli($hostname, $username, $password, $dbname);

  // Skontrolovať pripojenie
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Aktualizovať záznam v databáze
  $userInputArrayJson = json_encode($userInputArray);
  $sql = "UPDATE results SET student_result = '$userInputArrayJson' WHERE student_id = '$studentId' AND submitted IS NULL";
  if ($conn->query($sql) === TRUE) {
    echo "Pole userInputArray úspešne uložené do databázy.";
    echo( $userInputArrayJson);
  } else {
    echo "Chyba pri ukladaní pola userInputArray do databázy: " . $conn->error;
  }

  // Uzavrieť spojenie s databázou
  $conn->close();
}
?>




