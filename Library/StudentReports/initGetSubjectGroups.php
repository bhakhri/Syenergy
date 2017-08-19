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
	define('MANAGEMENT_ACCESS',1);
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    $classId = $REQUEST_DATA['classId'];
	$subjectId = $REQUEST_DATA['subjectId'];

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
    
    $condition = "";
    if($classId!='') {
       $condition = " AND sg.classId = ".$classId;
    }
    
    if($subjectId!='' && $subjectId!='all') {     
      $condition .= " AND sc.subjectId = ".$subjectId;  
    }
    $groupArray = $studentReportsManager->getSubjectwiseGroups($condition);
    
    echo json_encode($groupArray);
    
    
// $History: initGetSubjectGroups.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/14/09   Time: 3:25p
//Updated in $/LeapCC/Library/StudentReports
//class base format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/19/09    Time: 5:21p
//Created in $/LeapCC/Library/StudentReports
//file added
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/03/08   Time: 1:45p
//Updated in $/Leap/Source/Library/ScStudentReports
//modified for define('MODULE','MarksNotEntered');
//define('ACCESS','view');
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/15/08   Time: 1:09p
//Updated in $/Leap/Source/Library/ScStudentReports
//added code for time table labels
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Library/ScStudentReports
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
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