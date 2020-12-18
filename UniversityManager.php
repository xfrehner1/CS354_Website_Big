<?php
require_once "Student.php";
require_once "Dao.php";
require_once "UniClass.php";

$dao = new Dao();
$newClass;

$val='';
function printOptions() {
    fwrite(STDOUT,"What would you like to do?\n");
    fwrite(STDOUT,"a.) Create a class\n");
    fwrite(STDOUT,"b.) Delete a class\n");
    fwrite(STDOUT,"c.) Enroll a student\n");
    fwrite(STDOUT,"d.) Drop a student\n");
    fwrite(STDOUT,"e.) Register a student for a class\n");
    fwrite(STDOUT,"f.) View roster\n");
}
    
printOptions();

while($val!="exit\n") {
    fwrite(STDOUT, "\$ ");
    $val=fgets(STDIN);

    if($val=="a\n") {
        fwrite(STDOUT,"What is the name of this class? ");
        $className=fgets(STDIN);
        $className=str_replace("\n","",$className);
        fwrite(STDOUT,"What is the class code? ");
        $classCode=fgets(STDIN);
        $classCode=str_replace("\n","",$classCode);
        fwrite(STDOUT,"Which semester will this class be held? ");
        $classSem=fgets(STDIN);
        $classSem=str_replace("\n","",$classSem);
        $newClass=new UniClass($className,$classCode,$classSem);

        fwrite(STDOUT, "\nClass created. Details below:\n\n");
        fwrite(STDOUT, $newClass->stringRep());
        
        fwrite(STDOUT, "\nDoes this all look right?[y/n] ");
        $confirmation=fgets(STDIN);
        $confirmation=str_replace("\n","",$confirmation);
        if($confirmation=="y") {
            //add class
            $dao->addClass($classCode,$className,$classSem);
            fwrite(STDOUT, "Class created successfully\n\n");
            printOptions();
        } else {
            fwrite(STDOUT, "Please try again\n\n");
            printOptions();
        }
    } else if($val=="b\n") {
        fwrite(STDOUT,"Which class would you like to delete? (give course number)\n\n");
        foreach($dao->getClasses() as $class) {
            fwrite(STDOUT,"Code: ".$class['class_code']." Name: ".$class['class']." Semester: ".$class['semester']."\n");
        }
        fwrite(STDOUT,"\n");
        $deleteID=fgets(STDIN);
        $deleteID=str_replace("\n","",$deleteID);

        //TODO -- NEED TO CHECK IF DELETEID EXISTS IN CLASSES
        $res=$dao->deleteClass($deleteID);
        fwrite(STDOUT,"Class successfully deleted.\n\n");
    } else if($val=="c\n") {
        fwrite(STDOUT,"What is the first name of this student?\n");
        $firstName=fgets(STDIN);
        fwrite(STDOUT, "What is the last name of this student?\n");
        $lastName=fgets(STDIN);

        $firstName=str_replace("\n","",$firstName);
        $lastName=str_replace("\n","",$lastName);

        $student=new Student($firstName,$lastName);

        fwrite(STDOUT, "\nStudent successfully enrolled.\n\n");
        printOptions();
    } else if($val=="d\n") {
        fwrite(STDOUT,"What is the first name of this student?\n");
        $firstName=fgets(STDIN);
        fwrite(STDOUT, "What is the last name of this student?\n");
        $lastName=fgets(STDIN);

        $firstName=str_replace("\n","",$firstName);
        $lastName=str_replace("\n","",$lastName);

        $dao->deleteStudent($firstName,$lastName);
        printOptions();
    } else if($val=="e\n") {
        fwrite(STDOUT,"Which student wants to sign up for a class? (Please enter username.)\n");
        $signup=fgets(STDIN);
        $signup=str_replace("\n","",$signup);

        fwrite(STDOUT, "Which class does this student want to take? (Please provide course number)\n\n");
        foreach($dao->getClasses() as $class) {
            fwrite(STDOUT,"Code: ".$class['class_code']." Name: ".$class['class']." Semester: ".$class['semester']."\n");
        }

        $courseID=fgets(STDIN);
        $courseID=str_replace("\n","",$courseID);

        $studentID=$dao->getID($signup);

        $dao->addToClass($studentID,$courseID);
        
        fwrite(STDOUT, "\nSuccessfully added {$signup} to {$courseID}\n\n");
        printOptions();
    } else if($val=="f\n") {
        fwrite(STDOUT, "Which class would you like to view? (please provide course number)\n");
        $courseID=fgets(STDIN);
        $courseID=str_replace("\n","",$courseID);

        fwrite(STDOUT,"\n");
        foreach($dao->getRoster($courseID) as $student) {
            fwrite(STDOUT," Student Name: ".$student['firstName']." ".$student['lastName']." Email: ".$student['email']." Student ID: ".$student['studentID']."\n");
        }
        fwrite(STDOUT,"\n");
        printOptions();
    } else if($val!="exit\n"){
        $val=str_replace("\n","",$val);
        fwrite(STDOUT,"$val is an invalid option, try again\n");
    }
}