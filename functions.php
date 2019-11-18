<?php

//Global connection variables
$servername = "localhost";
$db = "p2";
$username = "root";
$password = "root";
$conn;

$debug = true;

function connectToDatabase() {
    global $conn, $servername, $db, $username, $password;
    $conn = new mysqli($servername, $username, $password, $db);
    
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
    }
}

function fetchQuestionBank() {

}

function fetchAssignmentQuestions() {

}

function login() {
    global $conn;
    connectToDatabase();

    //Verify valid user ID
    $sql = "select uid from users where uid = " . $_POST['userid'];
    $result = $conn->query($sql);

    //If the user id is not found, restart the session
    if($result->num_rows == 0) {
        echo "Invalid User ID";
        $_SESSION = array();
        session_destroy();
    }
    else {
        //This is probably unsecure
        $_SESSION["user"] = $_POST['userid'];
    }
}

function logout() {
    $_SESSION = array();
    session_destroy();
}

function fetchCourses() {
    global $conn;
    connectToDatabase();
    $user = $_SESSION["user"];

    //Query a users courses
    $sql = "select name, cid from courses, users where courses.uid = " . $user;
    $result = $conn->query($sql);

    //Set the selectedCourseName to first row of the fetch if not already set.
    if($result->num_rows > 0 && !isset($_SESSION['selectedCourseName'])) {
        $row = $result->fetch_assoc();
        $_SESSION['selectedCourseName'] = $row['name'];
        $_SESSION['selectedCourseCID'] = $row['cid'];
    }

    //Set the default seletion to the current selection
    if(isset($_SESSION["selectedCourseName"])) {
        echo "<option selected=\"selected\">" . $_SESSION["selectedCourseName"] . "</option>";
    }

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            if($_SESSION["selectedCourseName"] !== $row["name"]) {
                echo "<option value=\"". $row["name"] ."\">" . $row["name"] . "</option>";
            }
        }
    }

    $conn->close();
}

function saveCourseToSession($selectedCourseName) {
    global $conn;
    connectToDatabase();
    $user = $_SESSION["user"];

    //Query a users courses 
    $sql = "select name, cid from courses where uid = ". $user . " and name = '" . $selectedCourseName . "';";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $_SESSION['selectedCourseName'] = $row['name'];
    $_SESSION['selectedCourseCID'] = $row['cid'];

    $conn->close();
}

function fetchAssignments() {
    global $conn;
    connectToDatabase();
    $user = $_SESSION["user"];
    $course = $_SESSION['selectedCourseCID'];

    //Query a users assignments
    $sql = "select title, aid from assignments where cid = " . $course;
    $result = $conn->query($sql);

    //Set the selectedCourseName to first row of the fetch if not already set.
    if($result->num_rows > 0 && !isset($_SESSION['selectedAssignmentTitle'])) {
        $row = $result->fetch_assoc();
        $_SESSION['selectedAssignmentTitle'] = $row['title'];
        $_SESSION['selectedAssignmentAID'] = $row['aid'];
    }

    //Set the default seletion to the current selection
    if(isset($_SESSION["selectedAssignmentTitle"])) {
        echo "<option selected=\"selected\">" . $_SESSION["selectedAssignmentTitle"] . "</option>";
    }

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            if($_SESSION["selectedAssignmentTitle"] !== $row["title"]) {
                echo "<option value=\"". $row["title"] ."\">" . $row["title"] . "</option>";
            }
        }
    } 

    $conn->close();
}

function saveAssignmentToSession($selectedAssignmentName) {
    global $conn;
    connectToDatabase();
    $user = $_SESSION["user"];
    $cid = $_SESSION['selectedCourseCID'];

    //Query a users courses 
    $sql = "select title, aid from assignments where cid = " . $cid . " and title = '" . $selectedAssignmentName . "';";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $_SESSION['selectedAssignmentTitle'] = $row['title'];
    $_SESSION['selectedAssignmentAID'] = $row['aid'];

    $conn->close();
}

function selectAssignment() {

}

function addNewQuestion() {

}

function addQuestionToAssignment() {

}

function removeQuestionFromAssignment() {

}