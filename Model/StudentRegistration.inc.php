<?php
//-------------------------------------------------------
// THIS FILE Contains All The DataBase Queries Of The Registration Form
// Author : Ankur Aggarwal
// Created on : 25-July-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
 

class StudentRegistration {
	private static $instance = null;

	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function insertStudentToRegistration() {
		global $REQUEST_DATA;



                $name		= $REQUEST_DATA['name'];
		$fatherName	= $REQUEST_DATA['fatherName'];
		$id	        = $REQUEST_DATA['rollNo'];
		$studentEmail	= $REQUEST_DATA['studentEmail'];
		$parentsNumber	= $REQUEST_DATA['parentsNumber'];
    	        $studentNumber   = $REQUEST_DATA['studentNumber'];
                $bloodGroup      =$REQUEST_DATA['bloodGroup'];
                $allergy        = $REQUEST_DATA['allergy'];
		$address	= $REQUEST_DATA['address'];
		$mentor	        = $REQUEST_DATA['mentor'];
		$cgpa           = $REQUEST_DATA['cgpa'];
                $scholarType  = $REQUEST_DATA['status'];                         //  // This will check the status of the status (whether Hosteler or Day Scholar)
                $travellingStatus=$REQUEST_DATA['travel'];
                $studentId        = $REQUEST_DATA['studentId'];
                $classId        = $REQUEST_DATA['currentClassId'];       
		$parentEmail = $REQUEST_DATA['parentEmail'];
		$hostelName = $REQUEST_DATA['hostelName'];
		$roomNo = $REQUEST_DATA['roomNo'];
		$wardenContact = $REQUEST_DATA['wardenContact'];
         	$routeNo = $REQUEST_DATA['routeNo'];
		$pickUp = $REQUEST_DATA['pickUp'];
		$vehicleType = $REQUEST_DATA['vehicleType'];
		$travellingPt = $REQUEST_DATA['travellingPt'];
         	$vehicleRegistration = $REQUEST_DATA['vehicleRegistration'];
                $landlineNo=$REQUEST_DATA['landlineNo'];
                $wardenName=$REQUEST_DATA['wardenName'];
		$dt = date('Y-m-d');
                  



		$query = "INSERT INTO `student_registration` SET
		`rollNo`='$id' ,
		`studentId`='$studentId' ,
		`classId`='$classId' ,
		`studentName`='$name' ,
		`studentMobileNo`='$studentNumber' ,
		`studentEmail`='$studentEmail' ,
		`fatherName`='$fatherName' ,
		`fatherMobile`='$parentsNumber' ,
		`landlineNo`='$landlineNo' ,
		`parentEmail`='$parentEmail' ,
		`permAddress1`='$address' ,
                `bloodGroup`='$bloodGroup',
                `allergy`='$allergy',
		`mentor`='$mentor' ,
		`cgpa`='$cgpa' ,
                `registrationDate`='$dt',
		`scholarType`='$scholarType' ,
		`hostelName`='$hostelName' ,
		`roomNo`='$roomNo' ,
                `wardenName`='$wardenName',
		`wardenContact`='$wardenContact' ,
		`travellingStatus`='$travellingStatus' ,
		`routeNo`='$routeNo' ,
		`pickUp`='$pickUp' ,
		`travellingPt`='$travellingPt' ,
		`vehicleType`='$vehicleType' ,
		`vehicleRegistrationNumber`='$vehicleRegistration'  ";


		return SystemDatabaseManager::getInstance()->executeUpdate($query);
	}
	public function updateStudentRegistration() {
		global $REQUEST_DATA;



                $name		= $REQUEST_DATA['name'];
		$fatherName	= $REQUEST_DATA['fatherName'];
		$id	        = $REQUEST_DATA['rollNo'];
		$studentEmail	= $REQUEST_DATA['studentEmail'];
		$parentsNumber	= $REQUEST_DATA['parentsNumber'];
                $bloodGroup      =$REQUEST_DATA['bloodGroup'];
                $allergy        = $REQUEST_DATA['allergy'];
    	        $studentNumber   = $REQUEST_DATA['studentNumber'];
		$address	= $REQUEST_DATA['address'];
		$mentor	        = $REQUEST_DATA['mentor'];
		$cgpa           = $REQUEST_DATA['cgpa'];
                $scholarType  = $REQUEST_DATA['status'];                 // This will check the status of the status (whether Hosteler or Day Scholar)
                $travellingStatus=$REQUEST_DATA['travel'];
                $classId        = $REQUEST_DATA['currentClassId'];
                $studentId        = $REQUEST_DATA['studentId']; 
		$parentEmail = $REQUEST_DATA['parentEmail'];
                $hostelName = $REQUEST_DATA['hostelName'];
		$roomNo = $REQUEST_DATA['roomNo'];
		$wardenContact = $REQUEST_DATA['wardenContact'];
		$routeNo = $REQUEST_DATA['routeNo'];
		$pickUp = $REQUEST_DATA['pickUp'];
		$vehicleType = $REQUEST_DATA['vehicleType'];
		$travellingPt = $REQUEST_DATA['travellingPt'];
		$vehicleRegistration = $REQUEST_DATA['vehicleRegistration'];
                $landlineNo=$REQUEST_DATA['landlineNo'];
                $wardenName=$REQUEST_DATA['wardenName'];
		$dt = date('Y-m-d');



		$query = "update `student_registration` SET
		`rollNo`='$id' ,
		`classId`='$classId' ,
		`studentName`='$name' ,
		`studentMobileNo`='$studentNumber' ,
		`studentEmail`='$studentEmail' ,
		`fatherName`='$fatherName' ,
		`fatherMobile`='$parentsNumber' ,
		`landlineNo`='$landlineNo' ,
		`parentEmail`='$parentEmail' ,
		`permAddress1`='$address' ,
                `bloodGroup`='$bloodGroup',
                `allergy`='$allergy',
		`mentor`='$mentor' ,
		`cgpa`='$cgpa' ,
                `registrationDate`='$dt',
		`scholarType`='$scholarType' ,
		`hostelName`='$hostelName' ,
		`roomNo`='$roomNo' ,
                `wardenName`='$wardenName',
		`wardenContact`='$wardenContact' ,
		`travellingStatus`='$travellingStatus' ,
		`routeNo`='$routeNo' ,
		`pickUp`='$pickUp' ,
		`travellingPt`='$travellingPt' ,
		`vehicleType`='$vehicleType' ,
		`vehicleRegistrationNumber`='$vehicleRegistration' where `studentId`='$studentId'";


		return SystemDatabaseManager::getInstance()->executeUpdate($query);

	}


	public function getClassId($studentId) {
        $query="select classId from student_registration where studentId='$studentId'";  // will fetch the ClassId from the Table
       return SystemDatabaseManager::getInstance()->executeQuery($query);
       }

       public function getCGPA($studentId) {
        $query="SELECT	ROUND(SUM(gradeIntoCredits)/sum(credits),3) as cgpa from student_cgpa where studentId = '$studentId'"; //Will Return the CGPA for studentId
       return SystemDatabaseManager::getInstance()->executeQuery($query);

     }

        public function checkStudentId($studentId) {
        $query="select count(studentId) from student_registration where studentId='$studentId'";
       return SystemDatabaseManager::getInstance()->executeQuery($query); //Will Return True If StudentId Exist Else False

     }

        public function countClassId($studentId,$classId) {
        $query="select count(classId) from student_registration where classId='$classId' and studentId='$studentId'";
       return SystemDatabaseManager::getInstance()->executeQuery($query); //Will Return True If StudentId Exist Else False

     }

       public function getStudentInfo($studentId){
       $query="select * from student_registration where studentId='$studentId'";
       return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
       public function getEnableClasses(){
	$query="select value from config where param='ENABLE_REGISTRATION'";
	return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

       public function getMentorName($studentRollNo){
        $query="select mentorUserName from mentor where studentRollNo = '$studentRollNo'"; // Will get the Mentor Name Corressponding To The University Roll No
	return SystemDatabaseManager::getInstance()->executeQuery($query);
     }

      public function getScholarType($studentRollNo){
      $query="select dayScholar from student_hostel_bus_status where studentRollNo='$studentRollNo'"; // will return whether student is day scholar or hosteler
      return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
      public function getMentorEmail($studentRollNo){
      $query="select emailAddress from employee where userId in (select mentorUserId from mentor where studentRollNo='$studentRollNo')";
      return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

     public function getCSVInfo(){
     $query="select * from student_registration";
     return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
       
}

?>
