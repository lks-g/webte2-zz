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

// Vypíš priklady v konzole
echo 'Priklady: ';
print_r($examples);

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
        <input type="submit" name="final_submit" value="Send" class="generate-button">
    </form>
</div>

<script src="../answer_checker/solvingAlg.js"></script>

<script>
    // Pripojenie udalosti submit na formulár
    document.getElementById('final_submit').addEventListener('submit', function(event) {
        event.preventDefault(); 
        var studentResults = <?php echo json_encode($studentResults); ?>;

        // Výpis pole student_results do konzole
        console.log('student_results:', studentResults);
        
        final_example =  final(`<?php echo isset($examples[0]) ? addslashes($examples[0]) : ''; ?>`);
        final_studentResult = final(`<?php echo isset($studentResults[0]) ? addslashes($studentResults[0]) : ''; ?>`);
        
        console.log("example result: " + final_example);
        console.log("student result: " + final_studentResult);
        
        evaluate(final_example, final_studentResult);

        
        
    });
</script>

    <footer>
        <p><?php echo $lang['student-rights']; ?></p>
    </footer>


</body>

</html>