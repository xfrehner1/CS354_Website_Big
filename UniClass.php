<?php

require_once "Dao.php";

class UniClass {
    private $code;
    private $class;
    private $semester;

    public function __construct($code,$class,$semester) {
        $this->code=$code;
        $this->class=$class;
        $this->semester=$semester;
    }

    public function getCode() {
        return $this->code;
    }

    public function getName() {
        return $this->class;
    }

    public function getSemester() {
        return $this->semester;
    }

    public function stringRep() {
        return "Class code: ".$this->code."\nClass name: ".$this->class."\nClass semester: ".$this->semester."\n";
    }
}