<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once('../config.php');

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
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>
    <title>Student Generate Assignment Page</title>
</head>

<body>
    <div class="navbar">
        <a href="student_assignment.php">Generate Assignments</a>
        <a href="#">Overview of Assignments</a>
        <a href="student.php">Student Home P/age</a>

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

    // Retrieve a random task from a random file
    $sql = "SELECT * FROM assignments ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileName = $row['file_name'];
        $latexData = $row['latex_data'];

        // Define possible start tags
        $startTags = array('\begin{task}', '\begin{responsetask}');

        $startPos = false;
        foreach ($startTags as $tag) {
            $startPos = strpos($latexData, $tag);
            if ($startPos !== false) {
                $startTag = $tag;
                break;
            }
        }

        if ($startTag === '\begin{task}') {
            $endTag = '\end{task}';
        } elseif ($startTag === '\begin{responsetask}') {
            $endTag = '\end{responsetask}';
        }

        $startPos = strpos($latexData, $startTag);
        $endPos = strpos($latexData, $endTag);

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
            echo "<h3>Random Task from $fileName:</h3>";
            echo "<div id='mathjax-content'>";
            echo "<span>  $taskContent</span>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='task'>";
            echo "<h3>Random Task from $fileName:</h3>";
            echo "No task found in the LaTeX file.";
            echo "</div>";
        }
    } else {
        echo "No assignments found.";
    }

    // Close the database connection
    $conn->close();
}
?>
    </div>

    <footer>
        <p>© 2023 - Student Home Page.</p>
    </footer>
</body>

</html>