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

  <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<!-- Establish session -->
<?php 
    include 'functions.php';

    //Handle login
    if(isset($_POST['login'])) {
        login();
        getAdminStatus();
        
        //Redirect after processing post to get rid of annoying refresh alert
        header('Location: index.php');
    }

    if(isset($_GET['courseSelect'])) {
        saveCourseToSession($_GET['courseSelect']);
    }

    if(isset($_GET['assignmentSelect'])) {
        saveAssignmentToSession($_GET['assignmentSelect']);
    }

    if(isset($_POST['logout'])) {
        logout();

        //Redirect after processing post to get rid of annoying refresh alert
        header('Location: index.php');
    }
?>

<body>

<!-- Page title header -->
<div class="titleBar bg-dark navbar-dark">Real Work</div>


<!-- Navbar containing login form and course/assignment selection form -->
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

    
    <!-- Div used for spacing the divs in the navbar -->
    <div class="navSpacer"></div>

    <!-- Assignment and course form -->
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

<div class="questionPoolDiv">
</div>

<div class="assignmentQuestionPoolDiv">
</div>

<div class="newQuestionDiv">
</div>

</body>

</html>



<!-- DEBUGGING -->
<?php
    
if($debug) {
    echo "DEBUG <br>";
    echo "\$_SESSION['user'] = " . $_SESSION['user']; echo "<br>";
    echo "\$_SESSION['admin'] = " . $_SESSION['admin']; echo "<br>";
    echo "\$_SESSION['selectedCourseName'] = " . $_SESSION['selectedCourseName']; echo "<br>";
    echo "\$_SESSION['selectedCourseCID'] = " . $_SESSION['selectedCourseCID']; echo "<br>";
    echo "\$_SESSION['selectedAssignmentTitle'] = " . $_SESSION['selectedAssignmentTitle']; echo "<br>";
    echo "\$_SESSION['selectedAssignmentAID'] = " . $_SESSION['selectedAssignmentAID']; echo "<br>";
}

?>