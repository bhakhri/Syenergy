<?php

//It contains the teacher comments
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();
    
          
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (noticeSubject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'")';         
    }
   
    $totalNoticesArray = $studentInformationManager->getTotalNotices();
    $studentRecordArray = $studentInformationManager->getInstituteNotices($filter,$limit);   
    
?>

<?php 

//$History: initInstituteNotices.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
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