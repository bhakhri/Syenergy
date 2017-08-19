<?php
//-------------------------------------------------------
// Purpose: To count the students in class
//
// Author : Ajinder Singh
// Created on : (24.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','AssignRollNumbers');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
	//check if any student is admitted to the class or not?
	$totalArray = $studentManager->countClassStudents($REQUEST_DATA['degree']);
	$studentCount = $totalArray[0]['cnt'];

	/*

	//check if any student is pending whom roll is not issued. if this is empty, then all students have got roll nos.
	$totalPendingArray = $studentManager->countClassStudents($REQUEST_DATA['degree']," AND rollNo = ''");
	$studentPendingCount = $totalPendingArray[0]['cnt'];

	//check if atleast one student has got the roll no. if yes then we cant follow this process again.

	*/

	$totalAssignedArray = $studentManager->countClassStudents($REQUEST_DATA['degree']," AND rollNo != '' AND rollNo IS NOT NULL");
	$studentAssignedCount = $totalAssignedArray[0]['cnt'];

	$studentArray = array('studentCount'=>$studentCount, 'studentAssignedCount' => $studentAssignedCount);

	echo json_encode($studentArray);

    
// for VSS
// $History: getStudentCount.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 3  *****************
//User: Admin        Date: 8/05/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Student
//file changed to make it as per new format
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/25/08    Time: 4:22p
//Updated in $/Leap/Source/Library/Student
//done the changes as per "change in functionality"
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/24/08    Time: 4:25p
//Created in $/Leap/Source/Library/Student
//file added for assigning roll no.s to students

?>
