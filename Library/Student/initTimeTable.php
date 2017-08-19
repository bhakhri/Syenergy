<?php

//It contains the time table
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    //require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn(true);   
	$studentId= $sessionHandler->getSessionVariable('StudentId');

	$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
	$classId = $classIdArray[count($classIdArray)-1]['classId'];
        
    //$studentInformationManager = StudentInformationManager::getInstance();
    
    //$studentRecordArray = $studentInformationManager->getStudentTimeTable();
	
    
?>

<?php 

//$History: initTimeTable.php $
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
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:29p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:41p
//Created in $/Leap/Source/Library/Student
//contain the data base function to run the queries
//
//


?>