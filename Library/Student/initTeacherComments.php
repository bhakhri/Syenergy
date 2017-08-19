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
    $studentInformationManager = StudentInformationManager::getInstance();
    
          
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    //if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
    //   $filter = ' WHERE (comments LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'")';         
   // }
   
    $totalCommentArray = $studentInformationManager->getTotalComments();
    $studentRecordArray = $studentInformationManager->getCommentsListing($limit);   
	
    
?>

<?php 

//$History: initTeacherComments.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/04/08   Time: 6:24p
//Updated in $/Leap/Source/Library/Student
//modified for attachment the file
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/11/08    Time: 6:33p
//Updated in $/Leap/Source/Library/Student
//modify for paging
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/04/08    Time: 10:54a
//Updated in $/Leap/Source/Library/Student
//made functions for view detail
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
