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
define('MODULE','StudentTeacherFeedBack');
define('ACCESS','edit');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
$feedBackManager = StudentInformationManager::getInstance();

$errorMessage ='';

$teacher=$REQUEST_DATA['teacherName'];

$feedBackSurveyId=$REQUEST_DATA['feedBackSurvey'];

$studentFeedBackArray = $feedBackManager->getFeedBackData("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");

$recordCount = count($studentFeedBackArray);


    if (trim($errorMessage) == '') {

		$feedbackInsert = "";
		global $sessionHandler;
		$studentId = $sessionHandler->getSessionVariable('StudentId');
		$classId = $sessionHandler->getSessionVariable('ClassId');
		$dated = date('Y-m-d H:i:s');
		

		for($i=0;$i<$recordCount;$i++) {
			$querySeprator = '';
			    if($feedbackInsert!=''){

					$querySeprator = ",";
			    }

			$feedbackInsert .= "$querySeprator('".$REQUEST_DATA['teacherName']."','".$REQUEST_DATA['feedbackQuestionId_'.$i]."','".$REQUEST_DATA['radio_'.$i]."','".$dated."','".$studentId."','".$classId."')";	

		}

		$returnStatus   = $feedBackManager->insertFeedBackTeacher($feedbackInsert);
		if($returnStatus === false) {
			echo FAILURE;
		}
		else {
			echo SUCCESS;
		}
	}
	else {
        echo $errorMessage;
    }

// $History: ajaxInitFeedBackAdd.php $
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
//User: Jaineesh     Date: 5/26/09    Time: 1:02p
//Created in $/LeapCC/Library/Student
//copy file from sc
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/30/08   Time: 5:58p
//Updated in $/Leap/Source/Library/ScStudent
//modified code for feedback survey
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/25/08   Time: 7:03p
//Updated in $/Leap/Source/Library/ScStudent
//modified in code for duplicate teacher name
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/24/08   Time: 4:29p
//Updated in $/Leap/Source/Library/ScStudent
//modified for studentnotloggedin
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/20/08   Time: 12:10p
//Updated in $/Leap/Source/Library/ScStudent
//modified the fields for insertion 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 10:21a
//Created in $/Leap/Source/Library/ScStudent
//file is used to give student feed back for their teacher
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 11/06/08   Time: 10:53a
//Updated in $/Leap/Source/Library/ScSubjectToClass
//added "Access" rights parameter
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10/14/08   Time: 12:56p
//Updated in $/Leap/Source/Library/ScSubjectToClass
//updated edit functionality for midsem and final exam dates
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10/13/08   Time: 1:49p
//Updated in $/Leap/Source/Library/ScSubjectToClass
//updated with validation of midsem and final exam date
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/29/08    Time: 1:51p
//Updated in $/Leap/Source/Library/ScSubjectToClass
//updated with midsem and final exam date
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/16/08    Time: 4:51p
//Updated in $/Leap/Source/Library/ScSubjectToClass
//updated the model file name
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/05/08    Time: 7:30p
//Created in $/Leap/Source/Library/ScSubjectToClass
//intial checkin
?>