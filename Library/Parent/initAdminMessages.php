<?php

//It contains the admin messages
//
// Author :Jaineesh
// Created on : 02.09.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
	
    $totalAdminArray = $studentInformationManager->getTotalAdminMessages();
	$adminRecordArray = $studentInformationManager->getAdminMessages($limit);   
    

//$History: initAdminMessages.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/21/09    Time: 12:48p
//Created in $/LeapCC/Library/Parent
//
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:34p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/11/08    Time: 6:33p
//Updated in $/Leap/Source/Library/Student
//modify for paging
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/13/08    Time: 3:57p
//Updated in $/Leap/Source/Library/Student
//modified in template
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/23/08    Time: 10:10a
//Created in $/Leap/Source/Library/Student
//file contain for search & get the data base query for teacher comments
//


?>