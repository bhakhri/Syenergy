<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Sc_Grade_Scales" Module
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class GradeScaleManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "GradeScaleManager" CLASS
//
//-------------------------------------------------------------------------------     

	
	private function __construct() {
	}
	

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "GradeManager" CLASS
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
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
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Grade
//
//
//-------------------------------------------------------------------------------       

	
	public function addGradingScale() {
		global $REQUEST_DATA;
        
		return SystemDatabaseManager::getInstance()->runAutoInsert('sc_grading_scales', array('gradingLabelId','gradingRangeFrom','gradingRangeTo','gradeId'), array($REQUEST_DATA['gradingLabelId'],$REQUEST_DATA['gradingRangeFrom'],$REQUEST_DATA['gradingRangeTo'],$REQUEST_DATA['gradeId']));
	}
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Grade
//
//-------------------------------------------------------------------------------       	
	
	
    public function editGradingScale($id) {
        global $REQUEST_DATA;

        //global $sessionHandler;
        //$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        //$sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('sc_grading_scales', array('gradingLabelId','gradingRangeFrom','gradingRangeTo','gradeId'), array($REQUEST_DATA['gradingLabelId'],$REQUEST_DATA['gradingRangeFrom'],$REQUEST_DATA['gradingRangeTo'],$REQUEST_DATA['gradeId']), "gradingScaleId=$id");        
    }    
	
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A "Grade" RECORD
//
//-------------------------------------------------------------------------------     

    public function deleteGradingScale($gradingScaleId) {

        $query = "DELETE 
        FROM sc_grading_scales 
        WHERE gradingScaleId=$gradingScaleId ";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade LIST 
//-------------------------------------------------------------------------------          
    public function getGradingScale($conditions='') {
    
        global $sessionHandler;
    
        //$instituteId = $sessionHandler->getSessionVariable('InstituteId');
       // $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
         $query = 'SELECT gradingScaleId, a.gradingLabelId, gradingRangeFrom,gradingRangeTo, c.gradeId, gradeLabel, gradingLabel 
                  FROM 
                        sc_grading_scales a, sc_grading_labels b, grades c
                  WHERE 
                            a.gradingLabelId = b.gradingLabelId
                        AND a.gradeId = c.gradeId
                        AND instituteId='.$sessionHandler->getSessionVariable('InstituteId').' AND sessionId='.$sessionHandler->getSessionVariable('SessionId').$conditions;
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Grade LIST 
//-------------------------------------------------------------------------------          
   	
	
    public function getGradingScaleList($conditions, $limit, $orderBy) {
        
        global $sessionHandler;
        //$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        //$sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT a.gradingScaleId, a.gradingLabelId, a.gradingRangeFrom,a.gradingRangeTo, c.gradeId, c.gradeLabel, b.gradingLabel 
                  FROM 
                        sc_grading_scales a, sc_grading_labels b, grades c
                  WHERE 
                            a.gradingLabelId = b.gradingLabelId
                        AND a.gradeId = c.gradeId
                    AND instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' AND sessionId='".$sessionHandler->getSessionVariable('SessionId')."' $conditions ORDER BY $orderBy $limit ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Grade" TABLE
//-------------------------------------------------------------------------------       
		
	 
    public function getTotalGradingScale($conditions='') {
    
        global $sessionHandler;
    
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');

        $query = "SELECT COUNT(*) AS totalRecords 
             FROM 
                    sc_grading_scales a, sc_grading_labels b
             WHERE 
                    a.gradingLabelId=b.gradingLabelId AND 
                    b.instituteId=$instituteId AND b.sessionId=$sessionId ";
                    
		if ($conditions != '') {
			$query .= " $conditions ";
		}
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
}

?>

<?php
// $History: GradingScalesManager.inc.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 10/25/08   Time: 3:05p
//Updated in $/Leap/Source/Model
//file modified
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/25/08   Time: 1:24p
//Updated in $/Leap/Source/Model
//set column alias


?>