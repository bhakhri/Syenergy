<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "LecturePercent" Module
//
//
// Author : Jaineesh
// Created on : 30-March-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class LecturePercentManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "LecturePercentManager" CLASS
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

	private function __construct() {
	}
	
	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "LecturePercentManager" CLASS
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

// THIS FUNCTION IS USED FOR ADDING A Lecture
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    public function getActiveTimeTableLabelId() {
        global $REQUEST_DATA;
		global $sessionHandler;
        
        $query = "SELECT timeTableLabelId FROM time_table_labels WHERE isActive=1 AND instituteId = ".$sessionHandler->getSessionVariable('InstituteId');

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function addLecturePercent($str) {
		global $REQUEST_DATA;
        
       // $query = "INSERT INTO attendance_marks_slabs (lectureDelivered,lectureAttended,marksScored,subjectTypeId,timeTableLabelId, instituteId, degreeId) values $str";
        $query = "INSERT INTO attendance_marks_slabs (lectureDelivered,lectureAttended,marksScored, instituteId, attendanceSetId) values $str"; 
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");        
	}
 
// THIS FUNCTION IS USED FOR EDITING A Attendance
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
 
    public function editLecturePercent($id) {
        global $REQUEST_DATA;
		global $sessionHandler;

        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('attendance_marks_slabs', array('lectureDelivered','lectureAttended','marksScored','subjectTypeId','timeTableLabelId', 'instituteId'), array(strtoupper($REQUEST_DATA['percentFrom']),$REQUEST_DATA['percentTo'],$REQUEST_DATA['marksScored'],$REQUEST_DATA['subjectTypeId'],$REQUEST_DATA['timeTableLabelId'], $sessionHandler->getSessionVariable('InstituteId')), "attendanceMarksId=$id" );  
    }    
 
// THIS FUNCTION IS USED FOR GETTING Lecture LIST
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
 
 
    public function getLecturePercent($conditions='') {
        
        global $sessionHandler; 
        $query = "SELECT 
                        ams.attendanceMarksId,  ams.lectureDelivered, MIN(ams.lectureAttended) AS lectureAttendedFrom, 
                        MAX(ams.lectureAttended) AS lectureAttendedTo, ams.marksScored, 
                        ams.subjectTypeId, ams.timeTableLabelId,
                        att.attendanceSetId, att.attendanceSetName, att.evaluationCriteriaId   
                  FROM 
                        `attendance_marks_slabs` ams, attendance_set att 
                  WHERE
                        ams.attendanceSetId = att.attendanceSetId AND
                        ams.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                   $conditions
                   GROUP BY ams.instituteId, att.attendanceSetId, ams.lectureDelivered, ams.marksScored
				   ORDER BY ams.instituteId, att.attendanceSetId, ams.lectureDelivered, ams.lectureAttended "; 
              
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	
// THIS FUNCTION IS USED FOR GETTING Lecture LIST 
//
// Author :Jaineesh
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	
    public function getAttendancePercentList($conditions='', $limit = '', $orderBy=' attendancePercentName') {
    
        $query = "SELECT 
                        ams.attendanceMarksId,  ams.lectureDelivered, ams.lectureAttended , 
                        ams.marksScored, ams.subjectTypeId, ams.timeTableLabelId
                  FROM 
                        `attendance_marks_slabs`  ams, attendance_set att
                  WHERE
                        ams.attendanceSetId = att.attendanceSetId AND
                        ams.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $conditions                   
                  ORDER BY $orderBy $limit";
         
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
	
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "lecturePercent" TABLE
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	
    public function getTotalLecturePercent($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        `attendance_marks_slabs`  ams, attendance_set att 
                  WHERE
                        ams.attendanceSetId = att.attendanceSetId AND
                        ams.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                  $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
	
// THIS FUNCTION IS USED FOR DELETING A "lecturePercent" RECORD
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     public function deleteLecturePercent($condition='') {
        $query = "DELETE 
                  FROM `attendance_marks_slabs`  
                  $condition";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
	
// THIS FUNCTION IS USED TO CHECK DUPLICATE LECTURE
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
 
    public function checkDuplicateLecture($conditions='') {
        
        global $sessionHandler; 
        
        $query = "SELECT 
                        COUNT(lectureAttended) AS countAttended 
                  FROM 
                        `attendance_marks_slabs`
                        $conditions"; 
              
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
}    

//$History: LecturePercentManager.inc.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/29/09   Time: 6:52p
//Updated in $/LeapCC/Model
//attendance Set Id base code updated
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:34a
//Updated in $/LeapCC/Model
//add new field degree in lecture percent and fixed bugs
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/19/09   Time: 1:02p
//Updated in $/LeapCC/Model
//modified in query for lecture attendance marks
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/18/09   Time: 3:33p
//Updated in $/LeapCC/Model
//Add Time Table Label dropdown and change in interface of attendance
//marks slabs. Now user can add the marks between the range for Lecture
//attended. 
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 3/31/09    Time: 11:19a
//Updated in $/LeapCC/Model
//modified code to make it working even better
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/31/09    Time: 10:21a
//Updated in $/LeapCC/Model
//modified to check some validations
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/30/09    Time: 3:45p
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/18/09    Time: 12:28p
//Created in $/LeapCC/Model
//file added

?>