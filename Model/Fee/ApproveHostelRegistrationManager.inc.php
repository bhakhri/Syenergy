<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class ApproveHostelRegistrationManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct(){
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
   
   
      
    public function getApproveHostelRegistration($condition='') {
	
        global $sessionHandler;
	 
    	$query = "SELECT    				
        				DISTINCT s.studentId , c.className,c.classId,
        				CONCAT(s.firstName,' ',s.lastName) AS studentName,
        				s.fatherName,s.rollNo,s.regNo, hr.hostelRegistrationId,
        				hr.studentId , hr.classId, hr.dateOfEntry,
        				hr.roomTypeId, s.studentMobileNo AS contactNo,
        				hr.registrationStatus,hr.wardenComments, hr.wardenCommentDate
    				FROM
    				   student s,class c,
    				   hostel_registration hr
    				WHERE 
    					hr.studentId =  s.studentId
    					AND c.classId = hr.classId	
					  $condition	";
   
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   } 
	public function getHostelRoomType($xxRoomType='',$condition='') {
	
        global $sessionHandler;
	 
    	$query = "SELECT    				
        				DISTINCT hrt.hostelRoomTypeId, hrt.roomType
    				FROM
    				   hostel_room_type hrt
    				WHERE 
    					hrt.hostelRoomTypeId IN($xxRoomType)
					  $condition";

    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   } 
     public function getAddStudentHostelRegistration($strQuerry='',$hostelCondition='') {
	
        global $sessionHandler;
	 
     $query = "UPDATE
     			 hostel_registration
     			 SET      			            
                  $strQuerry
                  WHERE
                  	$hostelCondition";
			
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);   
   } 
   
    public function getCheckHostelRegistration($studentId='',$classId='') {
	
        global $sessionHandler;
	 
    	$query = "SELECT    				
        				studentId,classId,registrationStatus,wardenComments,userId,wardenCommentDate
    				FROM
    				   hostel_registration
    				WHERE 
    					studentId =  '$studentId' AND
    					classId = '$classId'	    					
					  $condition	";
   
    	 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
   } 
     public function getDeletePrevHostelRegistration($hostelRegistrationId='') {
      
       global $sessionHandler; 
       
       $query = "DELETE FROM `hostel_registration` WHERE hostelRegistrationId = '$hostelRegistrationId'";
        
       return  SystemDatabaseManager::getInstance()->executeDelete($query); 
    }   
    
     
}
// $History: StudentConcessionManager.inc.php $
?>
