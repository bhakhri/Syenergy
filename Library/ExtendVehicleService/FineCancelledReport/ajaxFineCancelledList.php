<?php
//-------------------------------------------------------
// Purpose: To store the records of payment history in array from the database 
//
// Author : Saurabh Thukral
// Created on : (13.09.2012 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','FineCancelledReport');
	define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineCancelledReportManager.inc.php");
    $fineCancelledReportManager = FineCancelledReportManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlManager = HtmlFunctions::getInstance();
    
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    $instituteId  = trim($REQUEST_DATA['instituteId']); 
    $degreeId  = trim($REQUEST_DATA['degreeId']); 
    $branchId  = trim($REQUEST_DATA['branchId']); 
    $batchId  = trim($REQUEST_DATA['batchId']); 
    $classId  = trim($REQUEST_DATA['classId']); 
    $fromDate  = trim($REQUEST_DATA['fromDate']); 
    $toDate  = trim($REQUEST_DATA['toDate']); 
    $receiptNo  = htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo']))); 
    $rollNo  = htmlentities(add_slashes(trim($REQUEST_DATA['rollNo']))); 
    $studentName  = htmlentities(add_slashes(trim($REQUEST_DATA['studentName']))); 
    $fatherName = htmlentities(add_slashes(trim($REQUEST_DATA['fatherName']))); 
    
    $condition = "";
    $whereCondition='';
    
    if($instituteId!='') {
      $condition .= " AND c.instituteId = '$instituteId' ";
    }
    if($degreeId!='') {
      $condition .= " AND c.degreeId = '$degreeId' ";
    }
    if($branchId!='') {
      $condition .= " AND c.branchId = '$branchId' ";
    }
    if($batchId!='') {
      $condition .= " AND c.batchId = '$batchId' ";
    }
    if($classId!='') {
      $condition .= " AND c.classId = '$classId' ";
    }
    if($rollNo!='') {
      $condition .= " AND (stu.rollNo LIKE '$rollNo%' OR stu.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $condition .= " AND stu.fatherName LIKE '$fatherName%' ";
    }
    if($receiptNo!='') {
      $condition .= " AND frd.fineReceiptNo LIKE '$receiptNo%' ";
    }
    
    if($fromDate!='' && $toDate!='') {
      $condition .= " AND (frd.receiptDate BETWEEN '$fromDate' and '$toDate') ";
    }
	    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    $orderBy = " ORDER by $sortField $sortOrderBy";	

    $studentCountFineList  = $fineCancelledReportManager->studentCountFineList($condition);
    $totalRecords = $studentCountFineList[0]['totalRecords'];
   
    $studentFineList  = $fineCancelledReportManager->studentFineList($condition,$limit,$orderBy);
    $cnt = count($studentFineList);

    for($i=0;$i<$cnt;$i++) {   		
   		
		$studentFineList[$i]['receiptDate'] = UtilityManager::formatDate($studentFineList[$i]['receiptDate']);
   	 	$valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentFineList[$i]);

   		if(trim($json_val)=='') {
        	$json_val = json_encode($valueArray);
   		}
   		else {
        	$json_val .= ','.json_encode($valueArray);           
   		}
 	}
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';
?>
