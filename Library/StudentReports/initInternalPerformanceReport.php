<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality 
//
// Author : Rajeev Aggarwal
// Created on : (01.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAcademicReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$reportManager = StudentReportsManager::getInstance();


$timeTable = $REQUEST_DATA['timeTable'];
$degree = $REQUEST_DATA['degree'];
$testCategoryId = $REQUEST_DATA['testCategoryId'];
 
$timeName = $REQUEST_DATA['timeName'];
$className = $REQUEST_DATA['className'];
$testTypeName = $REQUEST_DATA['testTypeName'];

$fromDate = $REQUEST_DATA['fromDate'];
$toDate	  = $REQUEST_DATA['toDate'];

 
//fetch classId which match the criteria
$classFilter = "  AND tt.classId= $degree AND ttc.testTypeCategoryId =$testCategoryId";   
if($fromDate!='' AND $toDate!=''){

	$classFilter .= "  AND testDate BETWEEN '".$fromDate."' AND '".$toDate."'";   
}
// to limit records per page    
$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
$records    = ($page-1)* RECORDS_PER_PAGE;
$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
$orderBy = $REQUEST_DATA['sortField'].' '.$REQUEST_DATA['sortOrderBy'];  
$totalRecordsArray = $reportManager->getCountInternalPerformanceData($classFilter,$orderBy);

$studentsArray = $reportManager->getInternalPerformanceData($classFilter,$orderBy,$limit);
$cnt1= count($totalRecordsArray);
$cnt = count($studentsArray);


    for($i=0;$i<$cnt;$i++) {

		
		$checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($studentsArray[$i]['studentId']).'">';
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array(	'checkAll' =>  $checkall,'srNo' => ($records+$i+1) , 
								
								'universityRegNo' => $studentsArray[$i]['universityRegNo'],
								'rollNo' => $studentsArray[$i]['rollNo'],
								'studentName' => $studentsArray[$i]['studentName'],
								'studentMobileNo' => $studentsArray[$i]['studentMobileNo'],
								'studentEmail' => $studentsArray[$i]['studentEmail'],
								'studentGender' => $studentsArray[$i]['studentGender'] 
							);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 


// $History: initInternalPerformanceReport.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-08-24   Time: 1:05p
//Updated in $/LeapCC/Library/StudentReports
//Updated with Institute Wise Checks including ACCESS rights DEFINE
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/02/09    Time: 10:48a
//Created in $/LeapCC/Library/StudentReports
//Intial checkin
?>