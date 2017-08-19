<?php

//It contains the teacher comments
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $scStudentInformationManager = StudentInformationManager::getInstance();

	require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
	require_once($FE . "/Library/common.inc.php"); //for studentId 
    
          
    $studentId= $sessionHandler->getSessionVariable('StudentId');
	$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
	$classId = $classIdArray[count($classIdArray)-1]['classId'];
	    
?>

<?php 

//$History: initAllAlerts.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/20/09    Time: 6:42p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/03/08   Time: 12:12p
//Updated in $/Leap/Source/Library/ScStudent
//modified alerts for study period
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/24/08   Time: 1:07p
//Updated in $/Leap/Source/Library/ScStudent
//modified short attendance for study period
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/24/08   Time: 12:41p
//Updated in $/Leap/Source/Library/ScStudent
//modified student marks & time table alerts for study period
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/17/08   Time: 3:02p
//Created in $/Leap/Source/Library/ScStudent
//use to show alerts on next page
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:33p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/11/08    Time: 6:33p
//Updated in $/Leap/Source/Library/Student
//modify for paging
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/01/08    Time: 4:05p
//Updated in $/Leap/Source/Library/Student
//fix the bug
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:42p
//Created in $/Leap/Source/Library/Student
//contains the data base function to run data base queries
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/23/08    Time: 10:10a
//Created in $/Leap/Source/Library/Student
//file contain for search & get the data base query for teacher comments
//


?>