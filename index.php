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

<?php 
    include 'functions.php';

    //All of these 'isset' conditionals handle a form submission by either
    //updating the state variables in the _session global or by updating the 
    //database. Then the header function wipes away the get and post variables,
    //so all of these conditionals are skipped, but the results are stored.

    //Set 'user' session variable
    if(isset($_POST['login'])) {
        login();
        getAdminStatus();
        header('Location: index.php');
    }

    //set 'selectedCourse' session state variable
    if(isset($_GET['courseSelect'])) {
        saveCourseToSession($_GET['courseSelect']);
    }

    if(isset($_GET['assignmentSelect'])) {
        saveAssignmentToSession($_GET['assignmentSelect']);
    }

    if(isset($_GET['courseSelect']) || isset($_GET['assignmentSelect'])) {
        header('Location: index.php');
    }

    if(isset($_POST['logout'])) {
        logout();
        header('Location: index.php');
    }

    if(isset($_GET['newQuestionText'])) {
        addNewQuestion($_GET['newQuestionText']);
        header('Location: index.php');
    }
?>

<body>

<!-- Page title header -->
<div class="titleBar bg-dark navbar-dark">Assignment Generator</div>


<!-- Navbar containing login form and course/assignment selection form -->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">

    <?php

        if(!isset($_SESSION['user'])) {
            echo    "<form action=\"index.php\" method=\"post\" id=\"loginForm\"> User ID: 
                        <input type=\"text\" class=\"nav-item\" name=\"userid\">
                        <input type=\"submit\" value=\"Login\" name=\"login\">        
                    </form>";
            
            if(isset($_SESSION['invalidUser'])) {
                echo "<div id=\"invalidUserDiv\">Invalid User ID</div>";
            }
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

<!-- Tables -->
<div class="tablesContainer">
    
    <!-- Table titles -->
    <div class="row">
        <div class="col"><b>Questions</b></div>
        <div class="col"><b>Assignment Questions</b></div>
    </div>
    
    <!-- row 1 -->
    <div class="row">
        <div style="height:30vh;overflow:auto;" id="questionPoolDiv" class="col">
            <table>
                <tr>
                    <td><input type='button' value='Add'/> test</td>
                </tr>
                <tr>
                    <td><input type='button' value='Add'/> fillerrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr</td>
                </tr>
            </table>
        </div>

        <div id="assignmentQuestionPoolDiv" class="col">
            there
        </div>
    </div>

    <div class="row">
        <div class="col"></div>
        <div class="col-10"><b>New Question</b></div>
        <div class="col"></div>
    </div>

    <!-- row 2 -->
    <div class="row">
        <div class="col"></div> <!-- empty column -->
        
        <div id="newQuestionDiv" class="col-10">
            <form action="index.php" method="get">
                <input type="text" name="newQuestionText" style="width:400px;">
                <input type="submit" value="Add">
            </form>
        </div>
        
        <div class="col"></div> <!-- empty column -->
    </div>

</div>

<hr style="border-top: 2px solid black;">

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