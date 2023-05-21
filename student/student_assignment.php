<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once('../config.php');

if (!isset($_SESSION['name']) &&  $_SESSION["role"] != "student") {
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
    <title><?php echo $lang['student-asgTitle']; ?></title>
</head>

<body>
    <div class="navbar">
        <a href="student_assignment.php"><?php echo $lang['generateAssignments']; ?></a>
        <a href="#"><?php echo $lang['assignmentsOverview']; ?></a>
        <a href="student.php"><?php echo $lang['student-homepage']; ?></a>

        <div class="language">
            <a href="student_assignment.php?lang=sk">SK</a>
            <a href="student_assignment.php?lang=en">EN</a>
        </div>
    </div>

    <div id="main">
        <h1><?php echo $lang['generate-title']; ?></h1>
        <h2><?php echo $lang['generate-click']; ?></h2>

        <form method="post" action="">
            <input type="submit" name="generate" value="<?php echo $lang['generateAssignments']; ?>" class="generate-button">
        </form>

        <?php
        if (isset($_POST['generate'])) {
            $conn = new mysqli($hostname, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve a random file_name from assignment_sets table
            $sql = "SELECT file_name, set_name FROM assignments_sets";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $fileName = $row['file_name'];
                $setName = $row['set_name'];

                // Retrieve latex_data based on the file_name from assignments table
                $sql = "SELECT latex_data FROM assignments WHERE file_name = '$fileName' ";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $latexData = $row['latex_data'];
                }

                echo "<script>";
                echo "console.log(" . json_encode($latexData) . ");";
                echo "</script>";

                $startTags = array('\begin{task}', '\begin{responsetask}');
                echo "<script>";
                echo "console.log(" . json_encode($startTags) . ");";
                echo "</script>";

                $startPosArray = array();

                foreach ($startTags as $tag) {
                    $startPos = strpos($latexData, $tag);
                    while ($startPos !== false) {
                        $startPosArray[] = $startPos;
                        $startPos = strpos($latexData, $tag, $startPos + 1);
                    }
                }

                $randomIndex = array_rand($startPosArray);
                $startPos = $startPosArray[$randomIndex];
                echo "<script>";
                echo "console.log(" . json_encode($startPos) . ");";
                echo "</script>";

                $startTag = '';
                $endTag = '';
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

                    echo "<div class='task'>";
                    echo "<h3>" . $lang['taskFrom'] . "$setName ( $fileName )</h3>";
                    echo "<div id='mathjax-content'>";
                    echo "<span>  $taskContent</span>";
                    echo "</div>";
                    echo "</div>";

                    echo '<div id="calculator"></div>';
                    echo '<button id="check" onclick="logUserInput()">' . $lang['sendAnswer'] . '</button>';
                } else {
                    echo "<div class='task'>";
                    echo "<h3>" . $lang['randomTask'] . "</h3>";
                    echo $lang['noTaskFound'];
                    echo "</div>";
                }
            } else {
                echo $lang['noAsgFound'];
            }

            $conn->close();
        }
        ?>

    </div>
    <script>
        var elt = document.getElementById('calculator');
        var options = {
            graphpaper: false,
        };

        var calculator = Desmos.GraphingCalculator(elt, options);

        calculator.observe('change', function() {
            var expressions = calculator.getState().expressions.list;

            expressions.forEach(function(expression) {
                console.log(expression.latex);
            });
        });

        function logUserInput() {
            var expressions = calculator.getState().expressions.list;
            var userInput = expressions.map(function(expression) {
                return expression.latex;
            });
            console.log("User input: ", userInput);
        }
    </script>
    <footer>
        <p><?php echo $lang['student-rights']; ?></p>
    </footer>
</body>

</html>