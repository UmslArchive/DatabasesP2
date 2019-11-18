<?php
    //Global connection variables
    $servername = "localhost";
    $db = "p2";
    $username = "root";
    $password = "root";
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
        $sql = "select name from courses, users where courses.uid = " . $user;
        $result = $conn->query($sql);

        if(isset($_SESSION["selectedCourse"])) {
            echo "<option selected=\"selected\">" . $_SESSION["selectedCourse"] . "</option>";
        }

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                if($_SESSION["selectedCourse"] !== $row["name"]) {
                    echo "<option value=\"". $row["name"] ."\">" . $row["name"] . "</option>";
                }
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    }

    function fetchAssignments() {
        global $conn;
        connectToDatabase();
        $user = $_SESSION["user"];

        //Query a users assignments
    }

    function selectAssignment() {

    }

    function addNewQuestion() {

    }

    function addQuestionToAssignment() {

    }

    function removeQuestionFromAssignment() {

    }