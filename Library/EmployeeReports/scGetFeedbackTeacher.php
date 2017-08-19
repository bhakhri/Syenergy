<?php
//-------------------------------------------------------
//  This File is used for fetching classes for a subject
//
//
// Author :Ajinder Singh
// Created on : 18-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	$feedbackSurveyId = $REQUEST_DATA['feedbackSurveyId'];

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $feedBackManager = TeacherManager::getInstance();

	$teacherArray = $feedBackManager->getEmployeeNames("AND   fq.feedbackSurveyId=$feedbackSurveyId");
	echo json_encode($teacherArray);

// $History: scGetFeedbackTeacher.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:34p
//Created in $/LeapCC/Library/EmployeeReports
//Intial checkin
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 3/27/09    Time: 12:12p
//Updated in $/Leap/Source/Library/ScEmployeeReports
//added Management Define.
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