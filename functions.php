<?php
    //Global connection variables
    $servername = "localhost";
    $db = "p2";
    $username = "root";
    $password = "";
    $conn;


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
        connectToDatabase();
        $_SESSION["user"] = $_POST['userid'];
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

        echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;
        echo "<option value=\"courses1\">test_course</option>";
        echo "<option value=\"courses2\">test_course2</option>";
        echo "<option value=\"courses3\">".$_SESSION["user"]."</option>";
    }

    function fetchAssignments() {
        global $conn;
        connectToDatabase();
        $user = $_SESSION["user"];

        //Query a users assignments

        echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;
        echo "<option value=\"assignments1\">". mysqli_get_host_info($conn) ."</option>";
        echo "<option value=\"assignments2\">".$user."</option>";
        echo "<option value=\"assignments3\">test_assign3</option>";
        echo "<option value=\"assignments4\">test_assign4</option>";
    }

    function selectAssignment() {

    }

    function addNewQuestion() {

    }

    function addQuestionToAssignment() {

    }

    function removeQuestionFromAssignment() {

    }