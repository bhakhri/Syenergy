<?php

//It contains the student marks
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    
	require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
	require_once($FE . "/Library/common.inc.php"); //for studentId 
	    
	$studentId= $sessionHandler->getSessionVariable('StudentId');
	$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
	$classId = $classIdArray[count($classIdArray)-1]['classId'];

//    $studentRecordArray = $studentInformationManager->getStudentMarks();   
    
?>

<?php 

//$History: initStudentMarks.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:31p
//Updated in $/LeapCC/Library/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:29p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/06/08    Time: 4:43p
//Updated in $/Leap/Source/Library/Student
//modified for student marks
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:44p
//Created in $/Leap/Source/Library/Student
//it contains the data base functions to run data base queries for
//student marks
//
//

?>