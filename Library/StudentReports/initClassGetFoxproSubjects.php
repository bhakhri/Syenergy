<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Parveen Sharma
// Created on : 15-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentReportsManager = StudentManager::getInstance();

	$classId = $REQUEST_DATA['degree'];
    
    if(trim($classId)==''){
      $classId = 0;  
    }                                         

    //$subjectArray = $studentReportsManager->getTransferredSubjectList($classId);
    
    $tableName="subject a, ".TOTAL_TRANSFERRED_MARKS_TABLE." b ";
    $fieldName="DISTINCT a.subjectId, a.subjectCode, a.subjectName, a.hasAttendance, a.hasMarks";
    $whereCondition=" WHERE a.subjectId = b.subjectId AND b.classId IN ($classId) AND b.conductingAuthority IN (1,3) ";
    $subjectArray = $studentReportsManager->getSingleField($tableName, $fieldName, $whereCondition);
    
    echo json_encode($subjectArray);


// $History: initClassGetFoxproSubjects.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/05/10    Time: 5:40p
//Updated in $/LeapCC/Library/StudentReports
//condition format updated (student_group base check updated)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/05/10    Time: 1:03p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//

?>