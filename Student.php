<?php
require_once "Dao.php";

class Student {

    public $username;
    public $studentID;
    public $email;
    public $firstname;
    public $lastname;
    public $dao;
    
    public function __construct($firstname, $lastname) {
        $this->dao = new Dao();
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $tmpname = $firstname . $lastname;
        $this->username = $this->newUsername(strtolower($tmpname));
        $this->studentID = $this->newID();
        $this->email = $this->newEmail($this->username);
        $this->dao->addStudent($this->studentID,$this->firstname,$this->lastname,$this->email,$this->username);
    }

    public function newUsername($tmpname) {
        // check if username is already taken, if so increment end digits
        $dup = $this->dao->userExists($tmpname);
        if (isset($dup) && !empty($dup)){
            if(preg_match_all('!\d+!', $tmpname, $matches)) {
                $endDig = $matches[0][0];
                $endDig++;
                $tmpname = preg_replace('!\d+!', $endDig, $tmpname); // replaces digits at end w/ ++digit
            } else {
                $tmpname .= 1;
            }
            $this->newUsername($tmpname);
        }
        return $tmpname;
    }

    public function newID() {
        $stuID = '';
        while (strlen((string)$stuID) < 4) {
            $stuID .= rand(0, 9);
        }
        // check if stuID already exists, if so, do it again
        $dup = $this->dao->dupeStuID($stuID);
        if (isset($dup) && !empty($dup)) {
            $this->newID();
        }
        return $stuID;
    }

    public function newEmail($user) {
        return $user."@phpschool.edu";
    }

    public function getUser() {
        return $this->username;
    }

    public function getID() {
        return $this->studentID;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFirst() {
        return $this->firstname;
    }

    public function getLast() {
        return $this->lastname;
    }
}