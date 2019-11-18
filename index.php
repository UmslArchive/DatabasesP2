<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Assignment Generator</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<?php 
    include 'functions.php';

    //Handle login
    if(isset($_POST['login'])) {
        login();
    }

    if(isset($_GET['courseSelect'])) {
        saveCourseToSession($_GET['courseSelect']);
    }

    if(isset($_GET['assignmentSelect'])) {
        saveAssignmentToSession($_GET['assignmentSelect']);
    }

    if(isset($_POST['logout'])) {
        logout();
    }
?>

<body>
<style>
    .titleBar {
        margin: auto;
        font-weight: bold;
        font-size: 200%;
        color: #d9d9d9;
        text-align: center;
    }

    .navbar {
        color: #d9d9d9;
    }

    .navSpacer {
        margin: auto;
    }

    .courseAssign {
        font-weight: bold;
        position: relative;
    }

    .courseAssignEle {
        position: relative;
        float: right;
        padding: 1vh;
    }

    #loginForm {
        position: relative;
    }

    select {
        min-width: 20vw;
    }

</style>

<div class="titleBar bg-dark navbar-dark">Real Work</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">

    <?php

        if(!isset($_SESSION['user'])) {
            echo    "<form action=\"index.php\" method=\"post\" id=\"loginForm\"> User ID: 
                        <input type=\"text\" class=\"nav-item\" name=\"userid\">
                        <input type=\"submit\" value=\"Login\" name=\"login\">        
                    </form>";
        }
        else {
            echo    "<form action=\"index.php\" method=\"post\" id=\"logoutForm\">
                       UserID " . $_SESSION['user'] . "    <input type=\"submit\" value=\"Logout\" name=\"logout\">
                    </form>";
        }
    ?>

    

    <div class="navSpacer"></div>

    <div class="courseAssign">
        <form action="index.php" method="get">
            <div class="courseAssignEle">
                Course 
                <select name="courseSelect" onchange="this.form.submit()">
                    <?php fetchCourses(); ?>
                </select>
            </div>
            <br>
            <div class="courseAssignEle">
                Assignment
                <select name="assignmentSelect" onchange="this.form.submit()">
                    <?php fetchAssignments(); ?>
                </select>
            </div>
        </form>
    </div>
</nav>
</body>

</html>

<!-- DEBUGGING -->
<?php
    
if($debug) {
    echo "DEBUG <br>";
    echo "\$_SESSION['user'] = " . $_SESSION['user']; echo "<br>";
    echo "\$_SESSION['selectedCourseName'] = " . $_SESSION['selectedCourseName']; echo "<br>";
    echo "\$_SESSION['selectedCourseCID'] = " . $_SESSION['selectedCourseCID']; echo "<br>";
    echo "\$_SESSION['selectedAssignmentTitle'] = " . $_SESSION['selectedAssignmentTitle']; echo "<br>";
    echo "\$_SESSION['selectedAssignmentAID'] = " . $_SESSION['selectedAssignmentAID']; echo "<br>";
}

?>