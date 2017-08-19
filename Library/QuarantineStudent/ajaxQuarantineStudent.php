<?php
//-------------------------------------------------------
// To quarantine students after searching stuents
// Author : Dipanjan Bhattacharjee
// Created on :06.11.2008  
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
define('MODULE','QuarantineStudentMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;
$queryDescription =''; 
$errorMessage ='';
$studentIds=(trim($REQUEST_DATA['studentIds'])=='' ? 0 : trim($REQUEST_DATA['studentIds']));
require_once(MODEL_PATH . "/QuarantineStudentManager.inc.php");
$quarantineStudentManager = QuarantineStudentManager::getInstance();

$auditTrialDescription = "Following student(s) have been deleted:";
$studentNameArray = $commonQueryManager->getStudentName($studentIds);
$studentNameList = UtilityManager::makeCSList($studentNameArray,'studentName');


if (trim($errorMessage) == '') {
   if(SystemDatabaseManager::getInstance()->startTransaction()) {
    
    $studentIds=(trim($REQUEST_DATA['studentIds'])=='' ? 0 : trim($REQUEST_DATA['studentIds']));
    //fetch selected students current classId like (studentId~classId)
    $studentClassArray=$quarantineStudentManager->fetchCurrentClassIds($studentIds);
	
    if(is_array($studentClassArray) and count($studentClassArray)>0){
        $studentClassIds=UtilityManager::makeCSList($studentClassArray,'studentClassIds');
        $ret=$quarantineStudentManager->deleteFromStudentGroups($studentClassIds);
        if($ret==false){
            die(FAILURE);
        }$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
        $ret=$quarantineStudentManager->deleteFromStudentOptionalGroups($studentClassIds);
        if($ret==false){
            die(FAILURE);
        }$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
    }
    
    //make students quarantine    
    $returnStatus = $quarantineStudentManager->quarantineStudents($studentIds);   
    if($returnStatus == false){
     die(FAILURE);
    }$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
    
    //delete students from stuent table
    $returnStatus = $quarantineStudentManager->deleteStudents($studentIds);   
    if($returnStatus == false){
     die(FAILURE);
    }$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');

    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################

		$auditTrialDescription .= $studentNameList;
		$type =STUDENT_IS_QUARANTINED; //Deleted students
		$returnStatus = $commonQueryManager->addAuditTrialRecord($type,$auditTrialDescription,$queryDescription);
		if($returnStatus == false) {
			echo  "Error while saving data for audit trail";
			die;
		} 

	########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
		

        die(SUCCESS);
    }
    else {
        die(FAILURE);
    }
   }
  else {
    die(FAILURE);
  }  
} 
else {
       echo $errorMessage;
}
// $History: ajaxQuarantineStudent.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/03/08   Time: 6:48p
//Created in $/LeapCC/Library/QuarantineStudent
//Created quarantine student module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:47p
//Updated in $/Leap/Source/Library/ScQuarantineStudent
//Removed "management access" from module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:12p
//Created in $/Leap/Source/Library/ScQuarantineStudent
//Created Quarantine(delete) Student Module
?>
