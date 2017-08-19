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
define('MODULE','ProvideGeneralSurvey');
define('ACCESS','add');
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$feedBackManager = TeacherManager::getInstance();

$errorMessage ='';

$feedBackSurveyId=$REQUEST_DATA['feedBackSurvey'];

$generalSurveyArray = $feedBackManager->getAttempts("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");
$count = count($generalSurveyArray);

$attempts = $generalSurveyArray[0]['attempts'];


$employeeFeedBackArray = $feedBackManager->getFeedBackEmpData("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");
$recordCount = count($employeeFeedBackArray);


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
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/01/09    Time: 17:42
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created teacher feedback functionaility
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:02p
//Created in $/Leap/Source/Library/ScStudent
//new file for add general feed back
//

?>