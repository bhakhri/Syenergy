<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "study_period" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class StudyPeriodManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudyPeriodManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudyPeriodManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
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
// THIS FUNCTION IS USED FOR ADDING A StudyPeriod
//
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addStudyPeriod() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('study_period', array('periodName','periodValue','periodicityId'), array(strtoupper($REQUEST_DATA['periodName']),$REQUEST_DATA['periodValue'],$REQUEST_DATA['periodicityId']) );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A StudyPeriod
//
//$id:studyPeriodId
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editStudyPeriod($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('study_period', array('periodName','periodValue','periodicityId'), array(strtoupper($REQUEST_DATA['periodName']),$REQUEST_DATA['periodValue'],$REQUEST_DATA['periodicityId']), "studyPeriodId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING StudyPeriod LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getStudyPeriod($conditions='') {
     
        $query = "SELECT studyPeriodId,periodName,periodValue,periodicityId
        FROM study_period
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS studyPeriodId EXISTS IN CLASS TABLE OR NOT(DELETE CHECK)
//
//$studyPeriodId :studyPeriodId of the study_period
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInClass($studyPeriodId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM class
        WHERE studyPeriodId=$studyPeriodId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A StudyPeriod
//
//$studyPeriodId :studyPeriodId of the study_period
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteStudyPeriod($studyPeriodId) {
     
        $query = "DELETE 
        FROM study_period 
        WHERE studyPeriodId=$studyPeriodId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING STUDY PERIOD LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getStudyPeriodList($conditions='', $limit = '', $orderBy=' stp.periodName') {
     
        $query = "SELECT stp.studyPeriodId,stp.periodName,stp.periodValue,per.periodicityName,per.periodicityCode
        FROM study_period stp, periodicity per
        WHERE stp.periodicityId=per.periodicityId $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF STUDY PERIODS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalStudyPeriod($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM study_period stp, periodicity per
        WHERE stp.periodicityId=per.periodicityId $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
?>
<?php
// $History: StudyPeriodManager.inc.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/08/09    Time: 14:28
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids---
//0000825,0000826,0000833,0000834,0000835,0000836,0000837
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:43p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/02/08    Time: 6:48p
//Updated in $/Leap/Source/Model
//Created "StudyPeriod Master"  Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/02/08    Time: 4:01p
//Created in $/Leap/Source/Model
//Initial checkin
?>
