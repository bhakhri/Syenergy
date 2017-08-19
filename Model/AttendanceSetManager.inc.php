<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
class AttendanceSetManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "AttendanceSetManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "AttendanceSetManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addAttendanceSet($setName,$setCondition) {
		return SystemDatabaseManager::getInstance()->runAutoInsert('attendance_set', 
            array('attendanceSetName','evaluationCriteriaId'), 
            array($setName,$setCondition) 
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editAttendanceSet($id,$setName,$setCondition) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('attendance_set', 
            array('attendanceSetName','evaluationCriteriaId'), 
            array($setName,$setCondition),
            "attendanceSetId=$id"
        );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getAttendanceSet($conditions='') {
     
        $query = "SELECT * FROM attendance_set $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//----------------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS attendanceSet EXISTS IN subject_to_class TABLE OR NOT(DELETE CHECK)
// $setId : attendanceSetId of the atendance_set
// Author :Dipanjan Bhattacharjee 
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------------------             
    public function checkInSubjectToClass($setId) {
     
        $query = "SELECT 
                        COUNT(*) AS found 
                  FROM 
                        subject_to_class
                  WHERE 
                        attendanceSetId=$setId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function checkInAttendanceMarksPercent($setId) {
     
        $query = "SELECT 
                        COUNT(*) AS found 
                  FROM 
                        attendance_marks_percent
                  WHERE 
                        attendanceSetId=$setId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
   public function checkInAttendanceMarksSlabs($setId) {
     
        $query = "SELECT 
                        COUNT(*) AS found 
                  FROM 
                        attendance_marks_slabs
                  WHERE 
                        attendanceSetId=$setId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING AN Attendance Set
// $setId : attendanceSetId of the atendance_set
// Author :Dipanjan Bhattacharjee 
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------      
    public function deleteAttendanceSet($setId) {
     
        $query = "DELETE 
                  FROM 
                        attendance_set 
                  WHERE 
                        attendanceSetId=$setId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Attendance Set LIST
// $conditions :db clauses
// $limit:specifies limit
// orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------       
    
    public function getAttendanceList($conditions='', $limit = '', $orderBy=' at.attendanceSetName') {
     
        $query = "SELECT 
                        at.* 
                  FROM 
                        attendance_set at
                  $conditions 
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Attendance Lists
// $conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (29.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------      
    public function getTotalAttendanceSet($conditions='') {
    
        $query = "SELECT 
                        COUNT(*) AS totalRecords 
                  FROM 
                        attendance_set at
                  $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
  
}
// $History: AttendanceSetManager.inc.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/01/10   Time: 14:45
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids---
//0002683,0002682,0002268,0001960,
//0002619,0002623
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/12/09   Time: 13:38
//Created in $/LeapCC/Model
//Added  "Attendance Set Module"
?>