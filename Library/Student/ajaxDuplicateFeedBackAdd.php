<?php
//-------------------------------------------------------
// Purpose: to design the layout for Teacher Feed Back
//
// Author : Jaineesh
// Created on : (17.11.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentTeacherFeedBack');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
$feedBackManager = StudentInformationManager::getInstance();

$errorMessage ='';

$teacher=$REQUEST_DATA['teacherName'];

$feedBackSurveyId=$REQUEST_DATA['feedBackSurvey'];


    if (trim($errorMessage) == '') {

		$teacherName = $feedBackManager->getTeacherName('AND employeeId = "'.add_slashes($REQUEST_DATA['teacherName']).'" AND fs.feedbackSurveyId="'.$feedBackSurveyId.'"');
		if ($teacherName[0]['found']>0) {
			echo "Duplicate Record";
		}
	}
	else {
        echo $errorMessage;
    }

// $History: ajaxDuplicateFeedBackAdd.php $
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
//User: Jaineesh     Date: 5/26/09    Time: 1:29p
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
//User: Jaineesh     Date: 11/20/08   Time: 11:58a
//Updated in $/Leap/Source/Library/ScStudent
//modified insertion of questionId
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/20/08   Time: 10:21a
//Created in $/Leap/Source/Library/ScStudent
//file is used to give student feed back for their teacher
//

?>