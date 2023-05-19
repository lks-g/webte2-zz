<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once('../config.php');

if (!isset($_GET['firstName'])) {
    header('Location: grade_overview.php');
    exit();
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


$firstName = $_GET['firstName'];

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT s.first_name, s.last_name, s.student_id, r.set_name, r.points
              FROM students AS s
              INNER JOIN results AS r ON s.id = r.student_id
              WHERE s.first_name = :firstName
              ORDER BY r.points DESC, s.last_name ASC";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) === 0) {
        header('Location: grade_overview.php');
        exit();
    }
} catch (PDOException $e) {
    echo $e->getMessage();
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
    <title><?php echo $lang['title5']; ?></title>
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
                    <a class="nav-link" href="generator.php"><?php echo $lang['menu1']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo $lang['menu2']; ?></a>
                </li>
            </ul>
            <div class="language">
                <a class="nav-link" href="get_student_details.php?lang=sk">SK</a>
                <a class="nav-link" href="get_student_details.php?lang=en">EN</a>
            </div>
        </div>
    </nav>


    <div id="main">
        <?php if (!empty($results)) : ?>
            <?php $firstResult = reset($results); ?>
            <h1><?php echo "[" . $firstResult['student_id'] . "] - " . $firstResult['first_name'] . " " . $firstResult['last_name']; ?></h1>

            <table>
                <thead>
                    <tr>
                        <th>Assignment</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result) : ?>
                        <tr>
                            <td><?= $result['set_name'] ?></td>
                            <td><?= $result['points'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="grade_overview.php">Back to Grade Overview</a>
        <?php else : ?>
            <p>No results found.</p>
            <a href="grade_overview.php">Back to Grade Overview</a>
        <?php endif; ?>
    </div>


    <footer>
        <p><?php echo $lang['rights']; ?></p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>