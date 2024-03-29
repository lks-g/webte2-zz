<?php

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
        <a class="nav-link" href="../answer_checker/answIndex.php"><?php echo $lang['menu4']; ?></a>

        <div class="language">
            <a href="student_overview.php?lang=sk">SK</a>
            <a href="student_overview.php?lang=en">EN</a>
        </div>
    </div>
    <div class="text_container">
    <h1><?php echo $lang['active']; ?></h1>
    </div>
    <table class="table">
    <thead>
        <tr>
            <th><?php echo $lang['tID']; ?></th>
            <th><?php echo $lang['tName']; ?></th>
            <th><?php echo $lang['tMaxPoints']; ?></th>
            <th><?php echo $lang['tPoints']; ?></th>
            <th><?php echo $lang['tSubmitted']; ?></th>
            <th><?php echo $lang['sResult']; ?></th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <?php
        $setNames = explode(', ', $row['set_name']);
        $studentResultString = $row['student_result'];
        
        if (!empty($studentResultString)) {
            $studentResultString = str_replace('\\', '\\\\', $studentResultString); // Escape backslash character
            $studentResults = json_decode($studentResultString, true, JSON_UNESCAPED_SLASHES);

            if ($studentResults === null) {
                echo 'Error decoding JSON: ' . json_last_error_msg();
                echo 'JSON Data: ' . htmlspecialchars($studentResultString);
                continue; // Skip this iteration and move to the next row
            }
        } else {
            $studentResults = [];
        }

        $points = explode(',', $row['points']); // Rozdelenie hodnôt stĺpca "points"

        $rowCount = max(count($setNames), count($studentResults), count($points));

        for ($i = 0; $i < $rowCount; $i++) {
            ?>
            <tr>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo isset($setNames[$i]) ? $setNames[$i] : ''; ?></td>
                <td><?php echo isset($setNames[$i]) ? $pointsMap[$setNames[$i]] : ''; ?></td>
                <td><?php echo isset($points[$i]) ? $points[$i] : ''; ?></td>
                <td><?php echo $row['submitted']; ?></td>
                <td>
                    <?php
                    if (isset($studentResults[$i])) {
                        $latexExpression = $studentResults[$i];
                        $modifiedExpression = str_replace('\\', '\\\\', $latexExpression);
                        $modifiedExpression = str_replace('frac', '\\dfrac', $modifiedExpression);
                        $modifiedExpression = str_replace('sqrt', '\\sqrt', $modifiedExpression);
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
    <h2><?php echo $lang['submitAsg'];?></h2>
    <form id="final_submit" action="" method="post">
        <input id="pekne_id" type="hidden" name="test" value="<?php echo $lang['sendAnswer']; ?>">
        <input type="submit" name="final_submit" value="<?php echo $lang['sendAnswer']; ?>" class="generate-button">
    </form>
    
</div>

<script src="../answer_checker/solvingAlg.js"></script>

<script>

let examples;
var parsed_data;

async function get_examples() {
    await fetch(`examples.php`)
        .then(response => response.json()) // Update this line
        .then(data => {
            examples = data;
            console.log("fetch_data: " +data);
           
        });
}

async function get_students() {
    await fetch(`studentResults.php`)
        .then(response => response.json()) // Update this line
        .then(data => {
        console.log(data[0].student_result);
        var student_data = data[0].student_result;
         parsed_data = JSON.parse(student_data);
        console.log(parsed_data);
        });
}


get_examples();
get_students();

  var evaluateResults = []; // Vytvorenie prázdneho poľa evaluateResults
    var pekne_id_var  = document.getElementById("pekne_id");
    console.log("examples test:  " + examples);


    document.getElementById('final_submit').addEventListener('submit', function(event) {
    var studentResults = <?php echo json_encode($studentResults); ?>;
    var final_example;
    var final_studentResult;
    
    
    console.log("example_mid:" +examples[0]);

    console.log("parsed_Data_mid:" +parsed_data[0]);
    

    
    
    for(var i = 0; i < studentResults.length; i++){
        
       
        if (parsed_data[i].includes("frac")) {
         parsed_data[i] = parsed_data[i].replace(/frac/g, "\\dfrac");
       
    }

        if (parsed_data[i].includes("rac")) {
        parsed_data[i] = parsed_data[i].replace(/rac/g, "\\frac");
       }

       if (parsed_data[i].includes("qrt")) {
        parsed_data[i] = parsed_data[i].replace(/qrt/g, "\\sqrt");
       
    }
    
    /* if (parsed_data[i].includes("eft")) {
        parsed_data[i] = parsed_data[i].replace(/eft/g, "\\left");
       
    }
    if (parsed_data[i].includes("ight")) {
        parsed_data[i] = parsed_data[i].replace(/ight/g, "\\right");
       
    } */
    if (parsed_data[i].includes("ta")) {
        parsed_data[i] = parsed_data[i].replace(/ta/g, "\\eta");
       
    }
    console.log("parsed_Data_midTESTTT:" +parsed_data[i]);
    var final_example = final(examples[i]);
    var final_studentResult = final(parsed_data[i]);
    
    

console.log("FINAL STUDENT TEST?::" + final_studentResult[i]);
console.log("parsed_Data_midTESTTT22222:" +parsed_data[i]);
    var evaluateResult = evaluate(final_example, final_studentResult);
    evaluateResults.push(evaluateResult); 
    pekne_id_var.value = evaluateResults;
}
    
    
    
    console.log('student_results:', studentResults);
    console.log("example result: " + final_example);
    console.log("student result: " + final_studentResult);
    console.log("evaluate result: " + evaluateResult);
    console.log("evaluate result pole: " + evaluateResults);
    console.log("velkost: " + studentResults.length);
    

   

});

</script>




<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["test"])) {
    
    $evaluateResults = $_POST['test'];
    $student_id = $_SESSION['student_id']; 
    $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    // Prepare the SQL statement outside the loop
    $stmt = $conn->prepare("UPDATE results SET points = :points WHERE student_id = :student_id;");
    
    $pointsArray = array();
    
    $resultsArray = explode(",", $evaluateResults); 
    $arrayLength = count($resultsArray);
    
  


    $stmtNames = $conn->prepare("SELECT set_name FROM results WHERE student_id = :student_id");
    $stmtNames->bindParam(":student_id", $student_id);
    $stmtNames->execute();
    $resultSet = $stmtNames->fetchAll(PDO::FETCH_COLUMN);
    
    
    $nameArray = array();
    foreach ($resultSet as $setName) {
        $setNameValues = explode(",", $setName);
        foreach ($setNameValues as $value) {
            $nameArray[] = trim($value);
        }
    }
    
    
  

    
    for ($i = 0; $i < $arrayLength; $i++) {
        $tmp = trim($resultsArray[$i]);
    
        if ($tmp == "false") {
            $pointsArray[] = 0;
        } else {
            $setName = $nameArray[$i]; // Získanie hodnoty z $nameArray pre aktuálny index $i
            
            $stmtPoints = $conn->prepare("SELECT points FROM assignments_sets WHERE set_name = :set_name");
            $stmtPoints->bindParam(":set_name", $setName);
            $stmtPoints->execute();
            $row = $stmtPoints->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $points = $row['points'];
                $pointsArray[] = $points;
            } else {
                // Ak sa nenájde zhoda v databáze, pridajte hodnotu 0
                $pointsArray[] = 0;
            }
        }
    }
    
    
    
    
    $pointsString = implode(",", $pointsArray);
    $stmt->bindParam(":points", $pointsString);
    $stmt->bindParam(":student_id", $student_id);
    $stmt->execute();
    
    
    $submittedValue = "true"; // Nastaviť hodnotu "true" pre stĺpec "submitted"

// Aktualizovať hodnotu stĺpca "submitted"
$stmtSubmitted = $conn->prepare("UPDATE results SET submitted = :submitted WHERE student_id = :student_id");
$stmtSubmitted->bindParam(":submitted", $submittedValue);
$stmtSubmitted->bindParam(":student_id", $student_id);
$stmtSubmitted->execute();

}
?>



<!-- for ($i = 0; $i < $arrayLength; $i++) {
        $tmp = trim($resultsArray[$i]); // Trim whitespace from each element
        echo "TMP :   ".$tmp;
        if ($tmp == "false") {
            $stmt->bindParam(":points", $points);
            $stmt->execute();
        } else {
            $stmt->bindParam(":points", $points1);
            $stmt->execute();
        }
    }
 -->




    <footer>
        <p><?php echo $lang['student-rights']; ?></p>
    </footer>


</body>

</html>