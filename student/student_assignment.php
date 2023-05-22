<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once('../config.php');



if(!isset($_SESSION['name']) &&  $_SESSION["role"] != "student" ){
    header("Location: ../index.php");
}



if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'sk';
}

if ($_SESSION['lang'] == 'sk') {
    include('../lang/sk.php');
} else {
    include('../lang/en.php');
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/student.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>
    <script src="https://www.desmos.com/api/v1.8/calculator.js?apiKey=dcb31709b452b1cf9dc26972add0fda6"></script>
    
    
    <title>Student Generate Assignment Page</title>
</head>

<body>
    <div class="navbar">
        <a href="student_assignment.php">Generate Assignments</a>
        <a href="student_overview.php">Overview of Assignments</a>
        <a href="student.php">Student Home Page</a>

        <div class="language">
            <a href="teacher.php?lang=sk">SK</a>
            <a href="teacher.php?lang=en">EN</a>
        </div>
    </div>

    <div id="main">
        <h1>Student Generate Assignment page</h1>
        <h2>Click "Generate" to generate Assignment</h2>

<form method="post" action="">
    <input type="submit" name="generate" value="Generate" class="generate-button">
</form>

<?php
if (isset($_POST['generate'])) {
    $conn = new mysqli($hostname, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM results WHERE student_id = {$_SESSION['student_id']} AND submitted IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Student has a pending task, display an alert
        echo "<script>alert('You cannot generate a new task until you submit the pending task. You will be redirected to overview page'); window.location.href = 'student_overview.php';</script>";
    }else {
        // Retrieve all set_names from assignment_sets table
        $sql = "SELECT set_name FROM assignments_sets";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $setNames = array();
            while ($row = $result->fetch_assoc()) {
                $setNames[] = $row['set_name'];
            }

        // Get the student_id (replace this with your own method of obtaining the student_id)
        $studentId = $_SESSION['student_id'];

        // Create an array to store all the generated tasks
        $generatedTasks = array();

        $taskResults = array();
        // Iterate over each set_name
        foreach ($setNames as $setName) {
            // Retrieve a random file_name from assignment_sets table for the current set_name
            $sql = "SELECT file_name FROM assignments_sets WHERE set_name = '$setName' ORDER BY RAND() LIMIT 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $fileName = $row['file_name'];

                // Retrieve latex_data based on the file_name from assignments table
                $sql = "SELECT latex_data FROM assignments WHERE file_name = '$fileName' ";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $latexData = $row['latex_data'];

                    $startTags = array('\begin{task}', '\begin{responsetask}');
                    $startPosArray = array();

                    // Find the positions of startTags in latexData
                    foreach ($startTags as $tag) {
                        $startPos = strpos($latexData, $tag);
                        while ($startPos !== false) {
                            $startPosArray[] = $startPos;
                            $startPos = strpos($latexData, $tag, $startPos + 1);
                        }
                    }

                    if (!empty($startPosArray)) {
                        $randomIndex = array_rand($startPosArray);
                        $startPos = $startPosArray[$randomIndex];

                        $startTag = '';
                        $endTag = '';

                        // Find the corresponding startTag and endTag based on the startPos
                        foreach ($startTags as $tag) {
                            if (strpos($latexData, $tag, $startPos) === $startPos) {
                                $startTag = $tag;
                                if ($startTag === '\begin{task}') {
                                    $endTag = '\end{task}';
                                } elseif ($startTag === '\begin{responsetask}') {
                                    $endTag = '\end{responsetask}';
                                }
                                break;
                            }
                        }

                        $endPos = strpos($latexData, $endTag, $startPos);

                        if ($startPos !== false && $endPos !== false) {
                            $taskContent = substr($latexData, $startPos + strlen($startTag), $endPos - ($startPos + strlen($startTag)));
                        
                            // Replace equation environments with MathJax delimiters
                            $taskContent = str_replace('\begin{equation*}', '\\[', $taskContent);
                            $taskContent = str_replace('\end{equation*}', '\\]', $taskContent);
                        
                            $taskContent = preg_replace('/\$([^$]+)\$/', '\\(\1\\)', $taskContent);
                        
                            // Replace image paths with correct HTML syntax
                            $taskContent = str_replace('\includegraphics{../assignments/images/', '<img src="../assignments/images/', $taskContent);
                            $taskContent = str_replace('.jpg}', '.jpg" alt="Image">', $taskContent);
                        
                            // Add the task content to the generatedTasks array
                            $generatedTasks[] = $taskContent;
                        
                            // Extract the task result from the latexData
                            $resultStartTag = '\begin{solution}';
                            $resultEndTag = '\end{solution}';
                            $resultStartPos = strpos($latexData, $resultStartTag, $endPos);
                            $resultEndPos = strpos($latexData, $resultEndTag, $resultStartPos);
                        
                            if ($resultStartPos !== false && $resultEndPos !== false) {
                                $resultContent = substr($latexData, $resultStartPos + strlen($resultStartTag), $resultEndPos - ($resultStartPos + strlen($resultStartTag)));
                                $resultContent = $conn->real_escape_string($resultContent);
                                $taskResults[] = $resultContent;
                            }
                        }
                    } else {
                        echo "No tasks found for $setName ($fileName).";
                    }
                }
            } else {
                echo "No assignments found for $setName.";
            }
        }

        // Combine all the generated tasks into a single string
        $allAssignments = implode("\n", $generatedTasks);
        
        // Combine all set_names into a single string
        $allSetNames = implode(", ", $setNames);


        $allResults = implode("\n", $taskResults);
        // Insert the combined assignments and set_names into the results table for the student_id
        $insertSql = "INSERT INTO results (student_id, assignments, set_name, expected_result) VALUES ('$studentId', '$allAssignments', '$allSetNames', '$allResults')";
        if ($conn->query($insertSql) === TRUE) {
            // Insertion successful
        } else {
            // Insertion failed
        }

        // Display the generated tasks
        echo "<div class='task'>";
        echo "<h3>Tasks for Student ID: $studentId</h3>";
        echo "<h2>Enter your answers into editors and then send all the answer on bottom of the page</h3>";
        echo "<hr>";
        foreach ($generatedTasks as $index => $task) {
            echo "<div class='task-container'>";
            echo "<h4>Set Name: " . $setNames[$index] . "</h4>";
            echo "<div class='task-content'>";
            echo $task;
            echo "</div>";
            echo '<div id="calculator' . $index . '" style="width: 500px; height: 300px;"></div>';
            echo '</div>'; 
        
            if ($index !== count($generatedTasks) - 1) {
                echo "<hr>";
            }
        
        }
        
        echo "</div>";

        if (!empty($generatedTasks)) {
            echo '<form id="send_id" method="post" action="">';
            echo '<input type="submit" name="send" value="Send" class="generate-button">';
            echo '</form>';
        }
    } else {
        echo "No set names found.";
    }


    

    }
    $conn->close();
}

?>

    </div>
    <script>
    

    var calculators = [];

<?php foreach ($generatedTasks as $index => $task) { ?>
    <?php
    $safeIndex = addslashes($index);
    $safeTask = addslashes($task);
    ?>
    var elt<?php echo $safeIndex; ?> = document.getElementById('calculator<?php echo $safeIndex; ?>');
    var options<?php echo $safeIndex; ?> = {
        graphpaper: false,
    };

    var calculator<?php echo $safeIndex; ?> = Desmos.GraphingCalculator(elt<?php echo $safeIndex; ?>, options<?php echo $safeIndex; ?>);
    calculators.push(calculator<?php echo $safeIndex; ?>);

    calculator<?php echo $safeIndex; ?>.observe('change', function() {
        var expressions = calculator<?php echo $safeIndex; ?>.getState().expressions.list;
        expressions.forEach(function(expression) {
            console.log(expression.latex);
        });
    });
<?php } ?>


var form = document.getElementById('send_id');
    form.addEventListener('submit', logUserInput);

    function logUserInput() {
  event.preventDefault();
  var userInputArray = []; // Pole pre uloženie vstupu používateľa pre všetky úlohy
  var inputUserArray = []; // Pole pre uloženie textových výsledkov

  calculators.forEach(function(calculator, index) {
    var expressions = calculator.getState().expressions.list;
    var userInput = expressions.map(function(expression) {
      return expression.latex;
    });

    // Konvertovať pole s výsledkami na textový reťazec
    var userInputText = userInput.join(', '); // Alebo použite inú logiku, ako spájanie hodnôt

    console.log('Vstup používateľa pre úlohu ' + index + ':', userInputText);

    userInputArray.push(userInputText); // Pridať textový reťazec do poľa

    inputUserArray.push(userInputText);
  });

  console.log('Pole vstupov používateľa:', userInputArray);
  console.log('Pole textových výsledkov:', inputUserArray);

  var studentId = '<?php echo $_SESSION["student_id"]; ?>';

  var data = {
    userInputArray: userInputArray,
    studentId: studentId,
  };

  // Odoslať objekt s dátami na PHP skript pomocou AJAX
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'update_results.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      console.log('Dáta úspešne uložené.');
    }
  };

  // Konvertovať objekt na reťazec s formátom JSON
  var jsonData = JSON.stringify(data);
  console.log(data);
  xhr.send('data=' + encodeURIComponent(jsonData));

  return false;
}
</script>
    <footer>
        <p>© 2023 - Student Home Page.</p>
    </footer>
</body>

</html>