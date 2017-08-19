<?php
//-------------------------------------------------------
// Purpose: To display the records of display Notices in Parents in array from the database, pagination and search  functionality
//
// Author : Arvind Singh Rawat
// Created on : 14-07-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifParentNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();

	
	$studentId = $sessionHandler->getSessionVariable('StudentId');
	$fromDate = $REQUEST_DATA['startDate2'];
	$toDate = $REQUEST_DATA['endDate2'];
	
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
   
          

    ////////////
    
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();    
 	//$parentSubjectAttendanceArray = $commonQueryManager->getAttendance("WHERE s.studentId='$studentId' AND fromDate BETWEEN '$fromDate' AND '$toDate' AND toDate BETWEEN '$fromDate' AND '$toDate'");
	
    if($fromDate=='' && $toDate=='')
    {    
        $parentSubjectAttendanceArray = $commonQueryManager->getAttendance("WHERE s.studentId='$studentId' ");     
   
    }
    else
    {      
        $parentSubjectAttendanceArray = $commonQueryManager->getAttendance("WHERE s.studentId='$studentId' AND fromDate BETWEEN '$fromDate' AND '$toDate' AND toDate BETWEEN '$fromDate' AND '$toDate'");           
    } 
    
    $totalArray= count($parentSubjectAttendanceArray);
	
    for($i=0;$i<$totalArray;$i++) {
        $parentSubjectAttendanceArray[$i]['fromDate']= UtilityManager::formatDate($parentSubjectAttendanceArray[$i]['fromDate']);
        $parentSubjectAttendanceArray[$i]['toDate']=   UtilityManager::formatDate($parentSubjectAttendanceArray[$i]['toDate']);    
        $parentSubjectAttendanceArray[$i]['Percentage']=($parentSubjectAttendanceArray[$i]['attended'] /  $parentSubjectAttendanceArray[$i]['delivered'])*100;
        $parentSubjectAttendanceArray[$i]['Percentage']=number_format(strip_slashes($parentSubjectAttendanceArray[$i]['Percentage']), 2, '.', '');
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$parentSubjectAttendanceArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>