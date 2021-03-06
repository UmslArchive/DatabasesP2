<?php

//Config
$servername = "localhost";
$db = "p2";
$username = "root";
$password = "root";
$conn;

$debug = false;

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

function createAssignment($aTitle) {
    if($aTitle === "")
        return;

    global $conn;
    connectToDatabase();

    $user = $_SESSION['user'];

    //Query a users courses
    $sql = "select name, cid from courses where courses.uid = " . $user;
    $result = $conn->query($sql);

    //do nothing if there are no courses
    if($result->num_rows == 0) {
        $conn->close();
        return;
    }

    $course = $_SESSION['selectedCourseCID'];

    //Insert
    $sql = "insert into assignments (title, cid) values ('". htmlspecialchars($aTitle) ."', " . $course . ");";
    $result = $conn->query($sql);

    //Update session state
    saveAssignmentToSession($aTitle);

    $conn->close();
}

function fetchCourses() {
    global $conn;
    connectToDatabase();
    $user = $_SESSION['user'];

    //Query a users courses
    $sql = "select name, cid from courses where courses.uid = " . $user;
    $result = $conn->query($sql);

    if($result->num_rows == 0) {
        $conn->close();
        return;
    }

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
    if(!isset($_SESSION ['user'])) {
        return;
    }

    global $conn;
    connectToDatabase();

    $selectedAID = $_SESSION['selectedAssignmentAID'];

    //Print the table:

    //Select all questions
    $sql = "select qid, qText, aid from questions;";
    $result = $conn->query($sql);

    if($result->num_rows === 0) {
        echo "<tr>0 Banked Questions</tr>";
    }

    while($row = $result->fetch_assoc()) {

        //Set the text contained in the square brackets ($aTitle)
        if($row['aid'] === NULL) {
            $aTitle = "Unassigned";
        }
        else {
            $subQuery = "select title from assignments where aid = " . $row['aid'];
            $subResult = $conn->query($subQuery);
            $aTitle = $subResult->fetch_assoc()['title'];
        }
        //Add a delete button if aid is current assignment
        if($row['aid'] === $selectedAID && $row['aid'] !== NULL) {
            echo    "<tr>" .
                        "<td id='testtd'><button style='width:25px;' type='submit' form=\"addRemForm\" name='rm' value='" . $row['qid'] . "'>-</button> [" . $aTitle . "] </td><td>" . $row['qText'] . "</td>" .
                    "</tr>";
        }
        else {
            echo    "<tr>" .
                        "<td id='testtd'><button style='width:25px;' type='submit' form=\"addRemForm\" name='add' value='" . $row['qid'] . "'>+</button> [" . $aTitle . "] </td><td>" . $row['qText'] . "</td>" .
                    "</tr>";
        }
    }

    $conn->close();
}

function fetchAssignmentQuestions() {
    if(!isset($_SESSION ['user'])) {
        return;
    }

    if(!isset($_SESSION['selectedAssignmentAID'])) {
        echo "Create an assignment";
        return;
    }

    global $conn;
    connectToDatabase();

    $aid = $_SESSION['selectedAssignmentAID'];

    $sql = "select qText from questions where aid = " . $aid;
    $result = $conn->query($sql);
    $count = 0;
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $count++;
            echo    "<tr>" .
                        "<td><b>" . $count . ".</b> " . $row['qText'] . "</td>" .
                    "</tr>";
        }
    }
    else {
        echo "0 Assigned Questions";
    }

    $conn->close();
}

function addNewQuestion($qText) {
    if($qText === "")
        return;

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

function addQuestionToAssignment($qid) {
    global $conn;
    connectToDatabase();

    $selectedAID = $_SESSION['selectedAssignmentAID'];


    $sql = "update questions set aid = ". $selectedAID . " where qid = " . $qid;
    $result = $conn->query($sql);

    $conn->close();
}

function removeQuestionFromAssignment($qid) {
    global $conn;
    connectToDatabase();

    $selectedAID = $_SESSION['selectedAssignmentAID'];


    $sql = "update questions set aid = NULL where qid = " . $qid;
    $result = $conn->query($sql);

    $conn->close();
}

//Bonus
function executeArbitrarySqlStatement($sql) {
    global $conn;
    connectToDatabase();

    $conn->query($sql);

    $conn->close();

    //Refresh session
    $_POST['userid'] = $_SESSION['user'];
    login();
    getAdminStatus();
}

function displayAdminTools() {
    if($_SESSION['admin'] == 1) {
        echo    "<hr style='border-top:2px solid black'>" . 
                "<div id='adminDiv' style='margin: auto; width: 50%; background-color:#372b3b; color:lightgray;'> Admin" .
                    "<form action='index.php' method='post'>" .
                        "<input type='text' name='adminExec' style='width:500px;'> " .
                        "<input type='submit' value='Execute'>" .
                    "</form>" .
                "</div>";
    }
}