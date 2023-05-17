<?php

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

            function parse_problems($db, $latex_contents, $num_problems)
            {
                preg_match_all("/\\\\begin{(responsetask|task)}(.*?)\\\\end{(responsetask|task)}(.*?)(?=\\\\begin{(responsetask|task)}|$)/s", $latex_contents, $matches);
                $tasks = $matches[2];
                $solutions = $matches[4];

                $problems = array();
                for ($i = 0; $i < min($num_problems, count($tasks)); $i++) {
                    $problem = $tasks[$i];
                    $solution = $solutions[$i];

                    if (preg_match("/\\\\includegraphics{(.*?)}/s", $problem, $image_matches)) {
                        $image = $image_matches[1];
                    } else {
                        $image = "";
                    }

                    $parsed_problem = array(
                        'task' => $problem,
                        'solution' => $solution,
                        'image' => $image
                    );

                    $stmt = $db->prepare("INSERT INTO assignments (task, solution, image_path) SELECT :task, :solution, :image_path 
                    WHERE NOT EXISTS (SELECT 1 FROM assignments WHERE task = :task AND solution = :solution AND image_path = :image_path)");
                    $stmt->bindParam(":task", $parsed_problem['task']);
                    $stmt->bindParam(":solution", $parsed_problem['solution']);
                    $stmt->bindParam(":image_path", $parsed_problem['image']);
                    $stmt->execute();
                    $problems[] = $parsed_problem;
                }

                return $problems;
            }

            function display_problems($parsed_problems)
            {
                foreach ($parsed_problems as $index => $parsed_problem) {
                    $problem_number = $index + 1;
                    echo "<h2>Problem $problem_number</h2>";

                    $task_text = $parsed_problem['task'];
                    preg_match('/\$\s*(.*?)\s*\$/', $task_text, $equation_match);
                    $equation = isset($equation_match[1]) ? $equation_match[1] : '';
                    $task_text = strtr($task_text, '', $equation);

                    echo "<div>" . preg_replace('/\\\\includegraphics\{.*?\}/', '', $task_text) . "</div>";
                    echo "<img src='" . $parsed_problem['image'] . "'/>";

                    echo "<h2>Solution</h2>";
                    $solution_text = $parsed_problem['solution'];
                    preg_match('/\\\\begin\{solution\}(.*?)\\\\end\{solution\}/s', $solution_text, $solution_match);
                    if (isset($solution_match[1])) {
                        $solution_equation = $solution_match[1];
                        $solution_equation_tex = str_replace(array('\dfrac{', '}'), array('\\frac{', '}'), $solution_equation);
                        echo "<div>\(\displaystyle $solution_equation_tex\)</div>";
                    }
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

    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script>
    <script>
        MathJax = {
            tex: {
                inlineMath: [
                    ['$', '$'],
                    ['\\(', '\\)']
                ]
            }
        };
    </script>

</body>

</html>