<?php

//The file contains functions to get student attendance
//
// Author :Jaineesh
// Created on : 04.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    
    require_once($FE . "/Library/common.inc.php"); //for studentId 
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifStudentNotLoggedIn();
	UtilityManager::headerNoCache();
	
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
	$commonQueryManager = CommonQueryManager::getInstance(); 
    
    global $sessionHandler;
    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
		
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
		
	$orderBy = " $sortField $sortOrderBy";  

    $studentId= $sessionHandler->getSessionVariable('StudentId');
	
			
	$fromDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];
	
	$classId = $REQUEST_DATA['studyPeriodId'];
		
	//$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
	//$classId = $classIdArray[0]['classId'];

	if($fromDate) {
			$where = " AND fromDate BETWEEN '$fromDate' AND '$toDate'";
	}
	if($toDate) {
			$where .= " AND toDate BETWEEN '$fromDate' AND '$toDate'";
	}

	if ($where != "") {
		$totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
		
		$studentInformationArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"order by $orderBy");		
	}
	else {

		$totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,"");
		
		$studentInformationArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,"","order by $orderBy");
	}

	$cnt = count($studentInformationArray);
    
    for($i=0;$i<$cnt;$i++) {
		//$studentInformationArray[$i]['fromDate']=strip_slashes($studentInformationArray[$i]['fromDate']);
		//$studentInformationArray[$i]['toDate']=strip_slashes($studentInformationArray[$i]['toDate']);
		$studentInformationArray[$i]['Percentage']=ROUND((($studentInformationArray[$i]['attended'] /  $studentInformationArray[$i]['delivered'])*100),2);
		// add subjectId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentInformationArray[$i]);
        
         if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    //print_r($valueArray);
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecordArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
	
          
 
?>

<?php 

//$History: scAjaxInitStudentAttendance.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/20/09    Time: 6:42p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/14/08   Time: 4:03p
//Updated in $/Leap/Source/Library/ScStudent
//modification in name
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/14/08   Time: 4:02p
//Updated in $/Leap/Source/Library/ScStudent
//modification in name
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/13/08   Time: 5:54p
//Updated in $/Leap/Source/Library/ScStudent
//make ajax function for semester wise detail
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/11/08   Time: 12:38p
//Updated in $/Leap/Source/Library/ScStudent
//modified for study period
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/03/08   Time: 1:46p
//Created in $/Leap/Source/Library/ScStudent
//make new ajax file to select attendance semester wise 
//

?>
