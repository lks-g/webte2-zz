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

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $lang['connFail'] . " " . $e->getMessage();
}

$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'test_count';
$sortOrder = 'DESC';

$sortableColumns = array('first_name', 'last_name', 'student_id', 'test_count');

if (!in_array($sortColumn, $sortableColumns)) {
    $sortColumn = 'test_count';
}

if (!is_numeric($sortColumn)) {
    $sortOrder .= ', last_name ASC';
}

$query = "SELECT s.first_name, s.last_name, s.student_id, COUNT(r.student_id) AS test_count, SUM(r.points) AS total_points
          FROM students AS s
          INNER JOIN results AS r ON s.id = r.student_id
          GROUP BY s.first_name, s.last_name, s.student_id
          ORDER BY $sortColumn $sortOrder";

$result = $db->query($query);

function generateCSV($data, $filename)
{
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    $output = fopen('php://output', 'w');

    fputcsv($output, array('First Name', 'Last Name', 'Student ID', 'Test Count'));

    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

if (isset($_GET['export'])) {
    $data = array();

    $result->execute();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[] = array($row['first_name'], $row['last_name'], $row['student_id'], $row['test_count']);
    }

    generateCSV($data, 'student_grades.csv');
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
    <title><?php echo $lang['title4']; ?></title>
</head>

<body id="frame-bg">

    <div id="main-frame">
        <h1><?php echo $lang['menu2']; ?></h1>

        <table class="table">
            <thead>
                <tr>
                    <th><a href="grade_overview.php?sort=first_name"><?php echo $lang['firstName']; ?></a></th>
                    <th><a href="grade_overview.php?sort=last_name"><?php echo $lang['lastName']; ?></a></th>
                    <th><a href="grade_overview.php?sort=student_id"><?php echo $lang['studentID']; ?></a></th>
                    <th><a href="grade_overview.php?sort=test_count"><?php echo $lang['tests']; ?></a></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $result->execute();
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td><a href="get_student_details.php?firstName=' . $row['first_name'] . '">' . $row['first_name'] . '</a></td>';
                    echo '<td><a href="get_student_details.php?firstName=' . $row['first_name'] . '">' . $row['last_name'] . '</a></td>';
                    echo '<td><a href="get_student_details.php?firstName=' . $row['first_name'] . '">' . $row['student_id'] . '</a></td>';
                    echo '<td>' . $row['test_count'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <div>
            <a href="grade_overview.php?export=1" class="btn btn-primary"><?php echo $lang['exportCSV']; ?></a>
        </div>

    </div>

    <div id="student-details" style="display: none;">
        <h2><?php echo $lang['studentDetails']; ?></h2>
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo $lang['firstName']; ?></th>
                    <th><?php echo $lang['lastName']; ?></th>
                    <th><?php echo $lang['studentID']; ?></th>
                    <th><?php echo $lang['setName']; ?></th>
                    <th><?php echo $lang['points']; ?></th>
                </tr>
            </thead>
            <tbody id="student-details-body"></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>