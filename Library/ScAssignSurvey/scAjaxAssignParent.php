<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','AssignSurveyMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(MODEL_PATH . "/ScAssignSurveyManager.inc.php");
$assignSurveyManager = ScAssignSurveyManager::getInstance();

$userId=$sessionHandler->getSessionVariable('UserId');  
$instituteId=$sessionHandler->getSessionVariable('InstituteId'); 
$sessionId=$sessionHandler->getSessionVariable('SessionId'); 

$insQuery=""; 
$errorMessage='';

$searchIdArr=$assignSurveyManager->checkParentExistence("WHERE svu.targetIds = scs.studentId AND (scs.fatherUserId = fsa.userId OR scs.motherUserId = fsa.userId OR scs.guardianUserId = fsa.userId) AND svu.userType='P'");
$studentIdList = UtilityManager::makeCSList($searchIdArr, 'studentId');
$cntSearch = count($searchIdArr);

if (trim($errorMessage) == '') {
    $parentIds=explode(",",$REQUEST_DATA['parent']);
    $cnt=count($parentIds);
    $surveyId=$REQUEST_DATA['surveyId'];
    $userType='P';     //Parent
    
    for($i=0;$i<$cnt;$i++){
        if($insQuery==''){
            $insQuery=" INSERT IGNORE INTO survey_visible_to_users (feedbackSurveyId,targetIds,userType)
                        VALUES($surveyId,$parentIds[$i],'".$userType."')";
        }
        else{
            $insQuery = $insQuery .", ($surveyId,$parentIds[$i],'".$userType."')";
        }
    }
    $ret1=false;
    $ret=true;
    
    //delete already assigned records
	if ($cntSearch>0 && is_array($searchIdArr)) {
		$ret1=$assignSurveyManager->deleteAssignedSurvey($surveyId,$userType,"AND targetIds NOT IN ($studentIdList)");
	}
	else {
		$ret1=$assignSurveyManager->deleteAssignedSurvey($surveyId,$userType,'');
	}
    
    if($insQuery!=''){
        if($ret1===true){
         //insert fresh records   
         $ret=$assignSurveyManager->assignSurvey($insQuery);
        }
    }
    
    if($ret1===true and $ret===true){
        echo SUCCESS;   //if previous data deleted and new records inserted
    }
    elseif($ret1===true and $ret===false){
		if($cntSearch < 0 OR $cntSearch == "") {
          echo SURVEY_DEASSINED_PARENT; //if previous data deleted and new records not inserted
    }
		else {
				echo SUCCESS;
			}
	}
    else{
        echo DATA_UNSAVED;    //if both condition fails
    }  
} 
else {
       echo $errorMessage;
}
// $History: scAjaxAssignParent.php $
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 14:41
//Created in $/LeapCC/Library/ScAssignSurvey
//Copied "Assign Survey" module from Leap to LeapCC
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/24/09    Time: 11:59a
//Updated in $/Leap/Source/Library/ScAssignSurvey
//fixed bugs in survey assign for student, parent, employee can not
//delete the record if exist in another table
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 13/01/09   Time: 12:29
//Updated in $/Leap/Source/Library/ScAssignSurvey
//Fixed bugs related to "Assign Survey" module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 5/01/09    Time: 18:12
//Updated in $/Leap/Source/Library/ScAssignSurvey
//Corrected Access Codes
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/01/09    Time: 18:07
//Created in $/Leap/Source/Library/ScAssignSurvey
//Created "Assign Survey"  module
?>