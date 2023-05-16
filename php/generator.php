<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    <title><?php echo $lang['title3']; ?></title>
</head>

<body>
    <div class="navbar">
        <a href="#"><?php echo $lang['menu1']; ?></a>
        <a href="teacher.php"><?php echo $lang['header']; ?></a>
        <a href="#"><?php echo $lang['menu3']; ?></a>
        <div class="language">
            <a href="generator.php?lang=sk">SK</a>
            <a href="generator.php?lang=en">EN</a>
        </div>
    </div>

    <div id="main">
        <h1>Generate Problems</h1>
        <form method="post" enctype="multipart/form-data">
            <label for="latex_file">Select LaTeX file:</label>
            <input type="file" name="latex_file" id="latex_file"><br><br>
            <label for="num_problems">Number of problems to generate:</label>
            <input type="number" name="num_problems" id="num_problems"><br><br>
            <input type="submit" value="Generate">
        </form>

        <div id="problems">
            <?php

            function parse_problems($db, $latex_contents, $num_problems) {
                preg_match_all("/\\\\begin{task}(.*?)\\\\end{task}(.*?)(?=\\\\begin{task}|$)/s", $latex_contents, $matches);
                $tasks = $matches[1];
                $solutions = $matches[2];

                $problems = array();
                for ($i = 0; $i < min($num_problems, count($tasks)); $i++) {
                    $problem = $tasks[$i];
                    $solution = $solutions[$i];

                    preg_match("/\\\\includegraphics{(.*?)}/s", $problem, $image_matches);
                    $image = $image_matches[1];

                    $parsed_problem = array(
                        'task' => $problem,
                        'solution' => $solution,
                        'image' => $image
                    );

                    $stmt = $db->prepare("INSERT INTO assignments (task, solution, image_path) VALUES (:task, :solution, :image_path)");
                    $stmt->bindParam(":task", $parsed_problem['task']);
                    $stmt->bindParam(":solution", $parsed_problem['solution']);
                    $stmt->bindParam(":image_path", $parsed_problem['image']);
                    $stmt->execute();

                    $problems[] = $parsed_problem;
                }

                return $problems;
            }

            function display_problems($parsed_problems) {
                foreach ($parsed_problems as $index => $parsed_problem) {
                    $problem_number = $index + 1;
                    echo "<h2>Problem $problem_number</h2>";
                    echo "<div>" . $parsed_problem['task'] . "</div>";
                    echo "<div>" . $parsed_problem['solution'] . "</div>";
                    echo "<img src='" . $parsed_problem['image'] . "'/>";
                }
            }

            try {
                $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_FILES["latex_file"]) && $_FILES["latex_file"]["error"] == 0) {
                        $allowed_ext = array("tex");
                        $ext = pathinfo($_FILES["latex_file"]["name"], PATHINFO_EXTENSION);
                        if (in_array($ext, $allowed_ext)) {
                            $latex_contents = file_get_contents($_FILES["latex_file"]["tmp_name"]);
                            $num_problems = (int)$_POST["num_problems"];
                            $parsed_problems = parse_problems($db, $latex_contents, $num_problems);
                            display_problems($parsed_problems);
                        } else {
                            echo "Only .tex files are allowed";
                        }
                    } else {
                        echo "Please upload a file";
                    }
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }

            $db = null;
            ?>
        </div>
    </div>

</body>

</html>