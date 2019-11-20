<?php

//Config
$servername = "localhost";
$db = "p2";
$username = "root";
$password = "root";
$conn;

$debug = true;

//=============================================================================

function connectToDatabase() {
    global $conn, $servername, $db, $username, $password;
    $conn = new mysqli($servername, $username, $password, $db);
    
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
    }
}

function login() {
    //Reset session
    $_SESSION = array();
    session_destroy();
    session_start();

    global $conn;
    connectToDatabase();

    //Verify valid user ID
    $sql = "select uid from users where uid = " . $_POST['userid'];
    $result = $conn->query($sql);

    //If the user id is not found, restart the session
    if($result->num_rows == 0) {
        echo "Invalid User ID";
        $_SESSION['invalidUser'] = true;
    }
    else {
        //This is probably unsecure
        $_SESSION["user"] = $_POST['userid'];
    }

    $conn->close();
}

function getAdminStatus() {
    global $conn;
    connectToDatabase();

    if(isset($_SESSION['user'])) {
        $sql = "select isAdmin from users where uid = " . $_SESSION['user'];

        $result = $conn->query($sql);

        if($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $_SESSION["admin"] = $row['isAdmin'];
        }
    }

    $conn->close();
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
    $sql = "select name, cid from courses where courses.uid = " . $user;
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

function fetchQuestionBank() {
    global $conn;
    connectToDatabase();

    $selectedAID = $_SESSION['selectedAssignmentAID'];

    //Print the table:

    //Select all questions
    $sql = "select qid, qText, aid from questions";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        //Add a delete button if aid is current assignment
        if($row['aid'] === $selectedAID && $row['aid'] !== NULL) {
            echo    "<tr>" .
                        "<td><button style='width:25px;' type='submit' form=\"addRemForm\" name='rm' value='" . $row['qid'] . "'>-</button> " . $row['qText'] . "</td>" .
                    "</tr>";
        }
        else {
            echo    "<tr>" .
                        "<td><button style='width:25px;' type='submit' form=\"addRemForm\" name='add' value='" . $row['qid'] . "'>+</button> " . $row['qText'] . "</td>" .
                    "</tr>";
        }
    }

    $conn->close();
}

function fetchAssignmentQuestions() {
    global $conn;
    connectToDatabase();

    $aid = $_SESSION['selectedAssignmentAID'];

    $sql = "select qText from questions where aid = " . $aid;
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo    "<tr>" .
                        "<td>" . $row['qText'] . "</td>" .
                    "</tr>";
        }
    }
    else {
        echo "0 Assigned Questions";
    }

    $conn->close();
}

function addNewQuestion($qText) {
    global $conn;
    connectToDatabase();

    $aid = $_SESSION['selectedAssignmentAID'];

    //Check if question is already in
    $sql = "select qText from questions where qText = '" . $qText . "';";
    $result = $conn->query(htmlspecialchars($sql));

    //Insert into the question pool if no results found
    if($result->num_rows == 0) {
        $sql = "insert into questions (qText, aid) values ('" . $qText . "', null);";
        $result = $conn->query(htmlspecialchars($sql));
    }

    $conn->close();
}

function addQuestionToAssignment() {

}

function removeQuestionFromAssignment() {

}