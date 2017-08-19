<?php

//This file calls Listing Function and creates Global Array in "Display Notices in Parent " Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();

	$studentId = $sessionHandler->getSessionVariable('StudentId');
	$fromDate = $REQUEST_DATA['startDate2'];
	$toDate = $REQUEST_DATA['endDate2'];
     
	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();    
 	$parentSubjectAttendanceArray = $commonQueryManager->getAttendance("WHERE s.studentId='$studentId'");
	$cnt = count($parentSubjectAttendanceArray);
	
?>

<?php 

//$History: initDisplayAttendance.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/01/08    Time: 12:39p
//Updated in $/Leap/Source/Library/Parent
//mdified the percentage parameter
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/31/08    Time: 5:34p
//Updated in $/Leap/Source/Library/Parent
//removed the parameter of getattendance()
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/28/08    Time: 6:55p
//Updated in $/Leap/Source/Library/Parent
//modified the Calculate Attenmdance Section as per database change
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/17/08    Time: 3:47p
//Updated in $/Leap/Source/Library/Parent
//no change
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/15/08    Time: 6:26p
//Created in $/Leap/Source/Library/Parent
//added two new files for "displayAttendance" Module 

?>
