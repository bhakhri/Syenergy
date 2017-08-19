<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Arvind Singh Rawat
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Subject');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/SubjectManager.inc.php");
$subjectManager =  SubjectManager::getInstance();


	if (!isset($REQUEST_DATA['subjectId']) || trim($REQUEST_DATA['subjectId']) == '') {
        $errorMessage = 'Invalid subject ';
    }
    if (trim($errorMessage) == '') {
        
        $condition = " FROM  ".TIME_TABLE_TABLE." WHERE subjectId = '".$REQUEST_DATA['subjectId']."'";
        $foundArray = $subjectManager->getCheckSubject($condition);
        if($foundArray[0]['cnt']>0) {
		   echo DEPENDENCY_CONSTRAINT;  
		   die;
		}

		$condition = " FROM subject_to_class WHERE subjectId = '".$REQUEST_DATA['subjectId']."'";
        $foundArray = $subjectManager->getCheckSubject($condition);
        if($foundArray[0]['cnt']>0) { 
		   echo DEPENDENCY_CONSTRAINT;  
		   die;
		}

        $condition = " FROM ".ATTENDANCE_TABLE." WHERE subjectId = '".$REQUEST_DATA['subjectId']."'";
        $foundArray = $subjectManager->getCheckSubject($condition);
        if($foundArray[0]['cnt']>0) {
		   echo DEPENDENCY_CONSTRAINT;  
		   die;
		}

	   //****************************************************************************************************************    
	   //***********************************************STRAT TRANSCATION************************************************
	   //****************************************************************************************************************
       if(SystemDatabaseManager::getInstance()->startTransaction()) {

			$returnStatus = $subjectManager->deleteSubjectTopic($REQUEST_DATA['subjectId']);
			if($returnStatus === false) {
               echo FAILURE; 
               die;
            }

			$returnStatus = $subjectManager->deleteSubject($REQUEST_DATA['subjectId']);
			if($returnStatus === false) {
               echo FAILURE; 
               die;
             }
			
			//*****************************COMMIT TRANSACTION************************* 
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
               echo DELETE;  
            }
            else {
              echo DEPENDENCY_CONSTRAINT;  
            }    	
	   }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:46p
//Updated in $/LeapCC/Library/Subject
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/Subject
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Subject
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/05/08    Time: 6:46p
//Updated in $/Leap/Source/Library/Subject
//modify
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/15/08    Time: 10:44a
//Updated in $/Leap/Source/Library/Subject
//Added a condition of Dependency constraint
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:42p
//Created in $/Leap/Source/Library/Subject
//added delete file which performs deletion through ajax functiuon
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//added code to delete state
//
?>

