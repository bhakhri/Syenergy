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
             
    
	//$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
//    $records    = ($page-1)* RECORDS_PER_PAGE;
  //  $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    /// Search filter /////  
    //if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      // $filter = ' AND (receiptNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'")';         
//    }
  //  $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    //$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptNo';
         //$orderBy = " $sortField $sortOrderBy";         

    ////////////
    
 //   $totalArray = $studentInformationManager->getTotalFee($filter);
    $studentRecordArray = $studentInformationManager->getStudentFee(); 
    
?>

<?php 

//$History: initStudentFee.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:32p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/13/08    Time: 6:11p
//Updated in $/Leap/Source/Library/Student
//modified in student fee query
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/31/08    Time: 7:37p
//Updated in $/Leap/Source/Library/Student
//modification for paging and searching
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/31/08    Time: 12:17p
//Created in $/Leap/Source/Library/Student
//show the payment history of student
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