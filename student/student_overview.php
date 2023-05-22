<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../config.php');
session_start();

if (!isset($_SESSION['name']) && $_SESSION["role"] != "student") {
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

// Pripojenie k databáze
$conn = new mysqli($hostname, $username, $password, $dbname);

// Skontroluj pripojenie
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Získanie dát z tabuľky "results"
$result = $conn->query("SELECT * FROM results WHERE student_id = " . $_SESSION['student_id']);

$assignmentSets = $conn->query("SELECT set_name, points FROM assignments_sets");

// Vytvorenie asociatívneho poľa s hodnotami points
$pointsMap = array();
while ($rowSet = $assignmentSets->fetch_assoc()) {
    $pointsMap[$rowSet['set_name']] = $rowSet['points'];
}

$result1 = $conn->query("SELECT * FROM results WHERE student_id = " . $_SESSION['student_id']);
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



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/student.css">

    <!-- Include MathJax library -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>


    <title><?php echo $lang['student-title']; ?></title>
</head>

<body>
    <div class="navbar">
        <a href="student_assignment.php"><?php echo $lang['generateAssignments']; ?></a>
        <a href="student_overview.php"><?php echo $lang['assignmentsOverview']; ?></a>
        <a href="student.php"><?php echo $lang['student-homepage']; ?></a>

        <div class="language">
            <a href="student.php?lang=sk">SK</a>
            <a href="student.php?lang=en">EN</a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Test name</th>
                <th>Full possible Points</th>
                <th>Your Points</th>
                <th>Submitted</th>
                <th>Your Answer</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <?php
                $setNames = explode(', ', $row['set_name']);
                $studentResultString = $row['student_result'];
                $studentResultString = str_replace('\\', '\\\\', $studentResultString); // Escape backslash character
                $studentResults = json_decode($studentResultString, true, JSON_UNESCAPED_SLASHES);

                if ($studentResults === null) {
                    echo 'Error decoding JSON: ' . json_last_error_msg();
                    echo 'JSON Data: ' . htmlspecialchars($row['student_result']);
                    continue; // Skip this iteration and move to the next row
                }

                $rowCount = max(count($setNames), count($studentResults));

                for ($i = 0; $i < $rowCount; $i++) {

                ?>
                    <tr>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo isset($setNames[$i]) ? $setNames[$i] : ''; ?></td>
                        <td><?php echo isset($setNames[$i]) ? $pointsMap[$setNames[$i]] : ''; ?></td>
                        <td><?php echo $row['points']; ?></td>
                        <td><?php echo $row['submitted']; ?></td>
                        <td>
                            <?php
                            if (isset($studentResults[$i])) {
                                // Wrap the LaTeX expression in a MathJax span with appropriate delimiters
                                $latexExpression = $studentResults[$i];
                                $modifiedExpression = str_replace('\\', '\\\\', $latexExpression);
                                $modifiedExpression = str_replace('frac', '\\dfrac', $modifiedExpression); // Fix fractions with explicit size
                                $modifiedExpression = str_replace('sqrt', '\\sqrt', $modifiedExpression); // Replace sqrt with \\sqrt
                                $mathjaxExpression = '\(' . $modifiedExpression . '\)';

                                echo '<script>';
                                echo 'console.log("MathJax Expression: ' . $mathjaxExpression . '");';
                                echo 'console.log("LaTeX Expression: ' . $modifiedExpression . '");';
                                echo '</script>';
                                echo '<span class="mathjax">' . $mathjaxExpression . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="submit-form">
    <h2>Submit my assignments</h2>
    <form id="final_submit" action="" method="post">
        <input type="submit" name="final_submit" value="send" class="generate-button">
    </form>
    
</div>

<script src="../answer_checker/solvingAlg.js"></script>

<script>
    var evaluateResults = []; // Vytvorenie prázdneho poľa evaluateResults

    document.getElementById('final_submit').addEventListener('submit', function(event) {
    event.preventDefault(); 
    var studentResults = <?php echo json_encode($studentResults); ?>;
    
    var final_example = final(`<?php echo isset($examples[1]) ? addslashes($examples[1]) : ''; ?>`);
    var final_studentResult = final(`<?php echo isset($studentResults[1]) ? addslashes($studentResults[1]) : ''; ?>`);
    
    var evaluateResult = evaluate(final_example, final_studentResult);
    evaluateResults.push(evaluateResult); // Pridanie výsledku do poľa evaluateResults
    
    console.log('student_results:', studentResults);
    console.log("example result: " + final_example);
    console.log("student result: " + final_studentResult);
    console.log("evaluate result: " + evaluateResult);
    console.log("evaluate result pole: " + evaluateResults);
    console.log("velkost: " + studentResults.length);
    
    // Vytvorenie dát pre odoslanie
    var data = new URLSearchParams();
    data.append('evaluateResults', evaluateResults);
    
    // Vytvorenie požiadavky na server
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_results.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    // Odoslanie dát na server
    xhr.send(data);
});
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["final_submit"])) {
  
    $evaluateResults = $_POST['evaluateResults'];
    $conn = new mysqli($hostname, $username, $password, $dbname);
  
$final_submit = $_POST['final_submit'];
echo $final_submit;

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



    <footer>
        <p><?php echo $lang['student-rights']; ?></p>
    </footer>


</body>

</html>