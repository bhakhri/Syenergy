<?php
//-------------------------------------------------------
// Purpose: to design the layout for Teacher Feed Back
//
// Author : Jaineesh
// Created on : (17.11.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentGeneralFeedBack');
define('ACCESS','edit');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
$feedBackManager = StudentInformationManager::getInstance();

$errorMessage ='';

$feedBackSurveyId=$REQUEST_DATA['feedBackSurvey'];

$generalSurveyArray = $feedBackManager->getAttempts("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");
$count = count($generalSurveyArray);

$attempts = $generalSurveyArray[0]['attempts'];


$studentFeedBackArray = $feedBackManager->getFeedBackData("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");
$recordCount = count($studentFeedBackArray);


    if (trim($errorMessage) == '') {
		$feedbackInsert = "";
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		
		$dated = date('Y-m-d H:i:s');
		$feedbackInsert = '';
		if ($count==0 && is_array($generalSurveyArray)) {
		$attempts = 1;
		for($i=0;$i<$recordCount;$i++) {
		    if($feedbackInsert!=''){
					$feedbackInsert .= ",";
		    }
			$feedbackInsert .= "('".$REQUEST_DATA['feedbackQuestionId_'.$i]."','".$REQUEST_DATA['radio_'.$i]."','".$dated."','".$userId."','".$attempts."')";	
		}

		$returnStatus   = $feedBackManager->insertGeneralSurvey($feedbackInsert);
		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	}
	else {
		$feedbackInsert = "";
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		
		$dated = date('Y-m-d H:i:s');
		for($i=0;$i<$recordCount;$i++) {
//			$feedbackInsert .= "('".$REQUEST_DATA['feedbackQuestionId_'.$i]."','".$REQUEST_DATA['radio_'.$i]."','".$dated."','".$userId."','".$attempts."')";	
			$returnStatus   = $feedBackManager->updateGeneralSurvey($REQUEST_DATA['feedbackQuestionId_'.$i],$REQUEST_DATA['radio_'.$i],$dated,$userId);
			if($returnStatus === false) {
				echo FAILURE;
			}
		}
		//$returnStatus   = $feedBackManager->updateGeneralSurvey($feedbackInsert);
		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	
	}
	}
	else {
        echo $errorMessage;
    }

// $History: ajaxInitGeneralFeedBackAdd.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/27/09    Time: 11:02a
//Updated in $/LeapCC/Library/Student
//copy from sc and modifications in the files as per requirement of CC
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/27/09    Time: 9:57a
//Created in $/LeapCC/Library/Student
//copy file from sc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:02p
//Created in $/Leap/Source/Library/ScStudent
//new file for add general feed back
//

?>