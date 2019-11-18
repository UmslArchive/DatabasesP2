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
    if(isset($_POST['login']))
    {
        login();
    }

    if(isset($_GET['setCourseAssign']))
    {
        
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
        right: 15vw;
    }

    .courseAssignEle {
        position: relative;
        float: right;
        padding: 1vh;
    }

    #courseAssignSubmit {
        position: relative;
        float: right;
        width: 50%;
        right: 9px;
    }

    #loginForm {
        position: relative;
        left: 15vw;
    }

</style>

<div class="titleBar bg-dark navbar-dark">Assignment Generator</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <form action="index.php" method="post" id="loginForm">
        User ID: <input type="text" class="nav-item" name="userid">
        <input type="submit" value="Login" name="login">        
    </form>

    <div class="navSpacer"></div>

    <div class="courseAssign">
        <form action="index.php" method="get">
            <div class="courseAssignEle">
                Course 
                <select name="courseSelect">
                    <?php
                        fetchCourses();
                    ?>
                </select>
            </div>
            <br>
            <div class="courseAssignEle">
                Assignment
                <select name="assignmentSelect">
                    <?php
                        fetchAssignments();
                    ?>
                </select>
            </div>
            <br>
            <input type="submit" id="courseAssignSubmit" value="Go" name="setCourseAssign">
        </form>
    </div>
</nav>



</body>
</html>