<?php        
             
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "AssignFinalGrade" Module
//
// Author : Parveen Sharma
// Created on : 30-March-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class AssignFinalGradeManager {
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

    public function addFinalGrade($fieldValue='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "INSERT INTO `final_grade` 
                  (`minval`, `maxval`, `grade`, `point`,`instituteId`,`sessionId`)
                  VALUES
                  $fieldValue";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
public function addIncentiveDetail($fieldValue='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "INSERT INTO `student_incentive` 
                  (`attendancePercentageFrom`,`attendancePercentageTo`, `weigthage`, `weightageFormat`,`instituteId`,`sessionId`)

                  VALUES
                  $fieldValue";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Get Final Grading List
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getAssignFinalGradeList($condition='', $orderBy='finalGradeId') {
        
        global $sessionHandler; 
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        if($orderBy=='') {
          $orderBy='finalGradeId';  
        }
        
        $query = "SELECT 
                       finalGradeId, `minval`, `maxval`, `grade`, `point`,`instituteId`,`sessionId`
                  FROM 
                      final_grade fg
                  WHERE 
                       fg.sessionId = $sessionId AND
                       fg.instituteId = $instituteId
                   $condition 
                   ORDER BY
                        $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }  
    public function getIncentiveDetailListPrint($condition='', $orderBy='incentiveId',$weightageFormat) {
        
        global $sessionHandler; 
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        if($orderBy=='') {
          $orderBy='incentiveId';  
        }
        
        $query = "SELECT 
                       `incentiveId`, `attendancePercentageFrom`,`attendancePercentageTo`, `weigthage`,`weightageFormat`
                  FROM 
                      student_incentive si
                  WHERE 
                       si.sessionId = $sessionId AND
		       si.sessionId = $sessionId AND
                       si.weightageFormat = $weightageFormat
                   $condition 
                   ORDER BY
                        $orderBy";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }  
public function getIncentiveDetailList($condition='') {
        
        global $sessionHandler; 
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       `incentiveId`, `attendancePercentageFrom`,`attendancePercentageTo`, `weigthage`,`weightageFormat`
                  FROM 
                      student_incentive si
                  WHERE 
                       si.sessionId = $sessionId AND
		       si.instituteId = $instituteId
                   $condition 
                   ORDER BY
                        weightageFormat,incentiveId ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }  
//--------------------------------------------------------------
//  THIS FUNCTION IS Delete Seats Intake quota FORM  ADD/EDIT
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function deleteAssignFinalGrade($instituteId,$sessionId,$condition='') {
        
        global $sessionHandler;
        
        $query = "DELETE FROM final_grade WHERE instituteId = '$instituteId' AND sessionId = '$sessionId'  $condition ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

	public function deleteAssignIncentive($instituteId,$sessionId,$weightageFormat,$condition='') {
		
		global $sessionHandler;
		
		$query = "DELETE FROM student_incentive
                                  WHERE instituteId = '$instituteId'
                                       AND sessionId = '$sessionId'
                                      AND weightageFormat = '$weightageFormat'  $condition ";
		
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	    }

}
?>
