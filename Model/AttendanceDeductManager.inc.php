<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "AssignFinalGrade" Module
//
// Author : Parveen Sharma
// Created on : 30-March-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class AttendanceDeductManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "LecturePercentManager" CLASS
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

	private function __construct() {
	}
	
	//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "LecturePercentManager" CLASS
//
// Author :Jaineesh 
// Created on : 30-March-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

    public function addAttendanceDeduct($fieldValue='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "INSERT INTO `attendance_grade_deduct` 
                  (`minval`, `maxval`, `point`,`instituteId`,`sessionId`)
                  VALUES
                  $fieldValue";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Get Final Grading List
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getAttendanceDeductList($condition='', $orderBy='attendanceGradeId') {
        
        global $sessionHandler; 
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        if($orderBy=='') {
          $orderBy='attendanceGradeId';  
        }
        
        $query = "SELECT 
                       attendanceGradeId, minval, maxval, `point`, instituteId, sessionId
                  FROM 
                      attendance_grade_deduct ag
                  WHERE 
                       ag.sessionId = $sessionId AND
                       ag.instituteId = $instituteId
                   $condition 
                   ORDER BY
                        $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }  
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Delete Seats Intake quota FORM  ADD/EDIT
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function deleteAttendanceDeduct($instituteId,$sessionId,$condition='') {
        
        global $sessionHandler;
        
        $query = "DELETE FROM attendance_grade_deduct WHERE instituteId = '$instituteId' AND sessionId = '$sessionId'  $condition ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

}
?>