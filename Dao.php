<?php
require_once 'Student.php';

class Dao {
    private $host = "us-cdbr-east-02.cleardb.com";
    private $db = "heroku_7467d4db5f1e822";
    private $user = "b1efda53ad3950";
    private $pass = "ca9ce9f7";

    public function getConnection () {
        try {
          $conn = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass);
          return $conn;
        } catch (Exception $e) {
          echo print_r($e,1);
          exit;
        }
    }

    public function getID($username) {
        $conn=$this->getConnection();
        $setQuery="SELECT studentID FROM students WHERE username=:username";
        $q=$conn->prepare($setQuery);
        $q->bindParam(":username",$username);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC)[0]['studentID'];
    }

    public function addToClass($sid,$cid) {
        $conn=$this->getConnection();
        $setQuery="INSERT INTO course_roster (class_code, studentID) VALUES (:cid,:sid)";
        $q=$conn->prepare($setQuery);
        $q->bindParam(":sid",$sid);
        $q->bindParam(":cid",$cid);
        $q->execute();
    }

    public function addStudent($studentID, $firstName, $lastName, $email, $username){
        $conn = $this->getConnection();
        $setQuery = "INSERT INTO students (studentID, firstName, lastName, email, username) VALUES (:studentID, e, :lastName, :email, :username)";
        $q = $conn->prepare($setQuery);
        $q->bindParam(":studentID", $studentID);
        $q->bindParam(":firstName", $firstName);
        $q->bindParam(":lastName", $lastName);
        $q->bindParam(":email", $email);
        $q->bindParam(":username", $username);
        $q->execute();
    }

    public function getStudents() {
        $conn=$this->getConnection();
        $setQuery = "SELECT * FROM students";
        $q=$conn->prepare($setQuery);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoster($cid) {
        $conn=$this->getConnection();
        $setQuery="SELECT * FROM students RIGHT JOIN course_roster ON students.studentID=course_roster.studentID WHERE course_roster.class_code=:cid";
        $q=$conn->prepare($setQuery);
        $q->bindParam(":cid",$cid);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClasses() {
        $conn = $this->getConnection();
        $retval = $conn->query("SELECT * FROM classes", PDO::FETCH_ASSOC);
        return $retval;
    }

    public function deleteClass($class_id) {
        $conn=$this->getConnection();
        $query="DELETE FROM classes WHERE class_code=:class_id";
        $q=$conn->prepare($query);
        $q->bindParam(":class_id",$class_id);
        $q->execute();
    }

    public function deleteCourseroster($studentID) {
        $conn=$this->getConnection();
        $query="DELETE FROM course_roster WHERE studentID=:studentID";
        $q=$conn->prepare($query);
        $q->bindParam(":studentID",$studentID);
        $q->execute();
    }

    public function deleteStudent($firstName,$lastName) {
        $this->deleteCourseRoster($this->getID(strtolower($firstName.$lastName)));
        $conn=$this->getConnection();
        $query="DELETE FROM students WHERE firstName=:firstName AND lastName=:lastName";
        $q=$conn->prepare($query);
        $q->bindParam(":firstName", $firstName);
        $q->bindParam(":lastName",$lastName);
        $q->execute();
    }

    public function getStudentClasses($studentID){
        $conn = $this->getConnection();
        $retval = $conn->query("SELECT class, class_code, semester FROM classes WHERE studentID='{$studentID}'", PDO::FETCH_ASSOC);
    }

    public function addClass($code, $class, $semester){
        $conn = $this->getConnection();
        $setQuery = "INSERT INTO classes (class_code, class, semester) VALUES (:code, :class, :semester)";
        $q = $conn->prepare($setQuery);
        $q->bindParam(":code", $code);
        $q->bindParam(":class", $class);
        $q->bindParam(":semester", $semester);
        $q->execute();
    }

    public function dupeStuID($studentID) {
        $conn = $this->getConnection();
        $retval = $conn->query("SELECT * FROM students WHERE studentID='{$studentID}'", PDO::FETCH_ASSOC);
        $retval->execute();
        return $retval->fetchAll(PDO::FETCH_ASSOC);
    }

    public function userExists($username) {
        $conn = $this->getConnection();
        $retval = $conn->query("SELECT * FROM students WHERE username='{$username}'");
        $retval->execute();
        return $retval->fetchAll(PDO::FETCH_ASSOC);
    }
    
}