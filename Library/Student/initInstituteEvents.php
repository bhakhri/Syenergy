<?php

//It contains Institute Events
//
// Author :Jaineesh
// Created on : 11.09.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();
    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
          
    $totalEventsArray = $studentInformationManager->getTotalEvents();
	$instituteRecordArray = $studentInformationManager->getEventList('','e.endDate desc',$limit);   
    

//$History: initInstituteEvents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:33p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/12/08    Time: 2:28p
//Created in $/Leap/Source/Library/Student
//to show institute events
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