<?php
//-------------------------------------------------------
//  This File is used for fetching classes for a subject
//
//
// Author :Ajinder Singh
// Created on : 18-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    UtilityManager::headerNoCache();

	$feedbackSurveyId = $REQUEST_DATA['feedbackSurveyId'];

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $feedBackManager = ScTeacherManager::getInstance();

	$teacherArray = $feedBackManager->getEmployeeNames("AND   fq.feedbackSurveyId=$feedbackSurveyId");
	echo json_encode($teacherArray);

// $History: getFeedbackTeacher.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/23/08   Time: 4:07p
//Created in $/LeapCC/Library/EmployeeReports
//inital checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/01/08   Time: 5:53p
//Updated in $/Leap/Source/Library/ScEmployeeReports
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/01/08   Time: 3:07p
//Created in $/Leap/Source/Library/ScEmployeeReports
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 11/15/08   Time: 1:09p
//Updated in $/Leap/Source/Library/ScStudentReports
//added code for time table labels
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Library/ScStudentReports
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/19/08    Time: 6:45p
//Updated in $/Leap/Source/Library/ScStudentReports
//changed the flow, and picked classes based on attendance, not on
//subject to class mapping
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/18/08    Time: 5:26p
//Created in $/Leap/Source/Library/ScStudentReports
//file added for student attendance report - sc
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/18/08    Time: 5:24p
//Created in $/Leap/Source/Library/StudentReports
//file added for student attendance report - sc
//

?>