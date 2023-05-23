<?php

session_start();

/*
if (!isset($_SESSION['name']) && $_SESSION["role"] != "student" || $_SESSION["role"] != "teacher") {
    header("Location: ../index.php");
}*/

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
    <script src="solvingAlg.js" defer></script>
    <link rel="stylesheet" href="../css/answerIndex.css">
    <title><?php echo $lang['testingTitle']; ?></title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../auth/Login.php"><?php echo $lang['backToHomepage']; ?></a>
                </li>
            </ul>
            <div class="language">
                <a class="nav-link" href="answIndex.php?lang=sk">SK</a>
                <a class="nav-link" href="answIndex.php?lang=en">EN</a>
            </div>
        </div>
    </nav>

    <div id="main">
        <div id="info_input">
            <label for="odpoved" class="label"><?php echo $lang['answer']; ?></label>
            <input id="odpoved" type="text">
            <label for="riesenie" class="label"><?php echo $lang['solution']; ?></label>
            <input id="riesenie" type="text">
            <input type="button" id="testingButton" value="<?php echo $lang['compare']; ?>">
        </div>

        <div id="response">
            <label for="odozva" class="label"><?php echo $lang['resp']; ?> </label>
            <p id="odozva"></p>
        </div>
    </div>

    <script>
        const odpoved = document.getElementById("odpoved");
        const riesenie = document.getElementById("riesenie");
        const testingButton = document.getElementById("testingButton");
        const odozva = document.getElementById("odozva");

        testingButton.addEventListener("click", () => {
            var tmpOdpoved = final(odpoved.value.toString());
            var tmpRiesenie = final(riesenie.value.toString());
            var answer = evaluate(tmpRiesenie, tmpOdpoved)
            odozva.innerHTML = '';
            if (answer === false) {
                odozva.innerHTML = <?php echo $lang['incorrect']; ?>;
            } else {
                odozva.innerHTML = <?php echo $lang['correct']; ?>;
            }
        })
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>