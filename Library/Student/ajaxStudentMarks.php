<?php
//-------------------------------------------------------
// Purpose: To store the records of student marks from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : 05-12-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    // to limit records per page 
	
	$studentId  = $REQUEST_DATA['studentId'];
	$classId  = $REQUEST_DATA['rClassId'];
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
   /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ct.cityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ct.cityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
   */ 
   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    
     

    $condition = " AND su.hasMarks = 1"; 
    
    $orderBy = " $sortField $sortOrderBy";
    //$sectionCondtion = " AND sct.studentId = $studentId";
	$studentSectionArray = $studentManager->getStudentMarks($studentId,$classId,$orderBy,$limit,$condition);
	$totalArray = $studentManager->getStudentMarksCount($studentId,$classId,$condition);
	
	 
    $cnt = count($studentSectionArray);
    
    for($i=0;$i<$cnt;$i++) {

		 $studentSectionArray[$i]['testDate'] = (UtilityManager::formatDate($studentSectionArray[$i]['testDate']));
		 
		 $studentSectionArray[$i]['obtainedMarks'] = "<span class='padding_right'>".$studentSectionArray[$i]['obtainedMarks']."</span>";

		 $studentSectionArray[$i]['colorCode']='<div style="height:20px;width:35px;background-color:#'.$studentSectionArray[$i]['colorCode'].'"></div>';
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentSectionArray[$i]);
       if(trim($json_val)==''){

			$json_val = json_encode($valueArray);
       }
       else{
          
			$json_val .= ','.json_encode($valueArray);           
       }
    }
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

// $History: ajaxStudentMarks.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/Library/Student
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 5  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Library/Student
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 6/01/09    Time: 7:37p
//Updated in $/LeapCC/Library/Student
//Fixed issues of find student of formatting
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 1/22/09    Time: 1:38p
//Updated in $/LeapCC/Library/Student
//Updated with padding_right CSS
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:20a
//Created in $/LeapCC/Library/Student
//Intial Checkin
?>