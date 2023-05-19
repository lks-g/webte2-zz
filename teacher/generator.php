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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/teacher.css">
    <title><?php echo $lang['title3']; ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="teacher.php"><?php echo $lang['header']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo $lang['menu1']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="grade_overview.php"><?php echo $lang['menu2']; ?></a>
                </li>
            </ul>
            <div class="language">
                <a class="nav-link" href="generator.php?lang=sk">SK</a>
                <a class="nav-link" href="generator.php?lang=en">EN</a>
            </div>
        </div>
    </nav>

    <div id="main">
        <div class="data-table">
            <?php
            function get_assignment_sets($db, $file_name = null)
            {
                $query = "SELECT * FROM assignments_sets";
                if ($file_name) {
                    $query .= " WHERE file_name = :file_name";
                }

                $stmt = $db->prepare($query);
                if ($file_name) {
                    $stmt->bindParam(":file_name", $file_name);
                }

                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            function display_assignment_sets($assignment_sets, $lang)
            {
                echo "<h1>" . $lang['sets'] . "</h1>";
                if (count($assignment_sets) > 0) {
                    echo "<table>";
                    echo "<tr><th>" . $lang['fileName'] . "</th><th>" . $lang['asgName'] . "</th><th>" . $lang['asgDateStart'] . "</th><th>" . $lang['asgDateEnd'] . "</th><th>" . $lang['asgPoints'] . "</th><th>" . $lang['fileActions'] . "</th> </tr>";

                    foreach ($assignment_sets as $set) {
                        echo "<tr>";
                        echo "<td>{$set['file_name']}</td>";
                        echo "<td>{$set['set_name']}</td>";
                        echo "<td>{$set['start_date']}</td>";
                        echo "<td>{$set['end_date']}</td>";
                        echo "<td>{$set['points']}</td>";
                        echo "<td><button class='deleteBtn' onclick=\"deleteAssignmentSet('{$set['id']}')\">" . $lang['deleteBtn'] . "</button></td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }
            }

            function create_assignment_set($db, $set_name, $start_date, $end_date, $file_name, $points, $lang)
            {
                $stmt = $db->prepare("SELECT COUNT(*) FROM assignments_sets WHERE set_name = :set_name");
                $stmt->bindParam(":set_name", $set_name);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count == 0) {
                    $stmt = $db->prepare("INSERT INTO assignments_sets (set_name, start_date, end_date, file_name, points) 
                                          VALUES (:set_name, :start_date, :end_date, :file_name, :points)");
                    $stmt->bindParam(":set_name", $set_name);
                    $stmt->bindParam(":start_date", $start_date);
                    $stmt->bindParam(":end_date", $end_date);
                    $stmt->bindParam(":file_name", $file_name);
                    $stmt->bindParam(":points", $points);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        echo $lang['asgInsertSuccess'];
                    } else {
                        echo $lang['asgInsertError'];
                    }
                } else {
                    echo $lang['asgInsertErrorExists'];
                }
            }

            $files = array();

            try {
                $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $assignment_sets = get_assignment_sets($db);
                display_assignment_sets($assignment_sets, $lang);

                $stmt = $db->prepare("SELECT file_name, date_created FROM assignments");
                $stmt->execute();
                $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($files) > 0) {
                    echo "<div id='files'>";
                    echo "<h1>" . $lang['dbsFileTitle'] . "</h1>";
                    echo "<table>";
                    echo "<tr><th>" . $lang['fileName'] . "</th><th>" . $lang['fileUploadDate'] . "</th><th>" . $lang['fileActions'] . "</th></tr>";

                    foreach ($files as $file) {
                        echo "<tr>";
                        echo "<td>{$file['file_name']}</td>";
                        echo "<td>{$file['date_created']}</td>";
                        echo "<td><button class='deleteBtn' onclick=\"deleteFile('{$file['file_name']}')\">" . $lang['deleteBtn'] . "</button></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_assignment_set"])) {
                        $set_name = $_POST["set_name"];
                        $start_date = $_POST["start_date"];
                        $end_date = $_POST["end_date"];
                        $file_name = $_POST["file_name"];
                        $points = $_POST["points"];

                        create_assignment_set($db, $set_name, $start_date, $end_date, $file_name, $points, $lang);
                    }
                } else {
                    echo $lang['noFilesError'];
                }
            } catch (PDOException $e) {
                echo $lang['connFail'] . " " . $e->getMessage();
            }

            $db = null;
            ?>
        </div>

        <div class="assignment-set-form">
            <h1><?php echo $lang['createAsg'] ?></h1>
            <form method="post">
                <label for="file_name"><?php echo $lang['selectSet'] ?></label><br>
                <select name="file_name" id="file_name" required><br>
                    <?php
                    foreach ($files as $file) {
                        echo "<option value='{$file['file_name']}'>{$file['file_name']}</option>";
                    }
                    ?>
                </select><br>
                <label for="set_name"><?php echo $lang['asgName'] ?></label><br>
                <input type="text" name="set_name" id="set_name" required><br>
                <label for="start_date"><?php echo $lang['asgDateStart'] ?></label><br>
                <input type="date" name="start_date" id="start_date"><br>
                <label for="end_date"><?php echo $lang['asgDateEnd'] ?></label><br>
                <input type="date" name="end_date" id="end_date"><br>
                <label for="points"><?php echo $lang['asgPoints'] ?></label><br>
                <input type="number" name="points" id="points"><br>
                <input type="submit" name="create_assignment_set" value="<?php echo $lang['create'] ?>"><br>
            </form>
        </div>

        <h1><?php echo $lang['generator'] ?></h1>
        <form method="post" enctype="multipart/form-data">
            <label for="latex_file" class="file-upload">
                <input type="file" name="latex_file" id="latex_file">
                <?php echo $lang['select'] ?>
            </label>
            <input type="submit" value="<?php echo $lang['generate'] ?>" class="submit-button">
        </form>
        <br>
        <div id="problems">
            <?php

            function parse_problems($db, $latex_contents, $file_name)
            {
                preg_match_all("/\\\\begin{(responsetask|task)}(.*?)\\\\end{(responsetask|task)}(.*?)(?=\\\\begin{(responsetask|task)}|$)/s", $latex_contents, $matches);
                $tasks = $matches[2];
                $solutions = $matches[4];

                $problems = array();
                foreach ($tasks as $index => $problem) {
                    $solution = $solutions[$index];

                    if (preg_match("/\\\\includegraphics{(.*?)}/s", $problem, $image_matches)) {
                        $image = $image_matches[1];
                    } else {
                        $image = "";
                    }

                    $parsed_problem = array(
                        'task' => $problem,
                        'solution' => $solution,
                        'image' => $image,
                        'file_name' => $file_name
                    );

                    $stmt = $db->prepare("INSERT INTO assignments (file_name, latex_data) SELECT :file_name, :latex_data 
                  WHERE NOT EXISTS (SELECT 1 FROM assignments WHERE file_name = :file_name AND latex_data = :latex_data)");
                    $stmt->bindParam(":latex_data", $latex_contents);
                    $stmt->bindParam(":file_name", $file_name);
                    $stmt->execute();

                    $problems[] = $parsed_problem;
                }

                return $problems;
            }

            function display_problems($parsed_problems, $lang)
            {
                foreach ($parsed_problems as $index => $parsed_problem) {
                    $problem_number = $index + 1;
                    echo "<h2>" . $lang['problem'] . " " . $problem_number . "</h2>";

                    $task_text = $parsed_problem['task'];
                    preg_match('/\$\s*(.*?)\s*\$/', $task_text, $equation_match);
                    $equation = isset($equation_match[1]) ? $equation_match[1] : '';
                    $task_text = str_replace('$' . $equation . '$', '\\(' . $equation . '\\)', $task_text);

                    echo "<div>" . preg_replace('/\\\\includegraphics\{.*?\}/', '', preg_replace('/\$(.*?)\$/s', '\\(\1\\)', $task_text)) . "</div>";
                    echo "<img src='" . $parsed_problem['image'] . "'/>";

                    echo "<div class='solution'>";
                    echo "<h2>" . $lang['solution'] . "</h2>";
                    $solution_text = $parsed_problem['solution'];
                    preg_match('/\\\\begin\{solution\}(.*?)\\\\end\{solution\}/s', $solution_text, $solution_match);
                    if (isset($solution_match[1])) {
                        $solution_equation = $solution_match[1];
                        $solution_equation_tex = str_replace(array('\dfrac{', '}'), array('\\frac{', '}'), $solution_equation);
                        echo "<div>\(\displaystyle $solution_equation_tex\)</div>";
                    }
                    echo "</div>";
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
                            $file_name = $_FILES["latex_file"]["name"];
                            $parsed_problems = parse_problems($db, $latex_contents, $file_name);
                            display_problems($parsed_problems, $lang);
                        } else {
                            echo $lang['texOnly'];
                        }
                    } else {
                        echo $lang['uploadMsg'];
                    }
                }
            } catch (PDOException $e) {
                echo $lang['connFail'] . " " . $e->getMessage();
            }

            $db = null;
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script>
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
     <script src="../script/teacher.js"></script>

    <footer>
        <p><?php echo $lang['rights']; ?></p>
    </footer>

</body>

</html>