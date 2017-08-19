<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Jaineesh
// Created on : (06.07.09)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FineCollectionReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager = FineManager::getInstance();

    /////////////////////////
    
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
      $condition .= " AND frm.feeClassId = '$classId' ";
    }
    if($rollNo!='') {
      $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $condition .= " AND s.fatherName LIKE '$fatherName%' ";
    }
    if($receiptNo!='') {
      $condition .= " AND frd.receiptNo LIKE '$receiptNo%' ";
    }
    
    if($fromDate!='' && $toDate!='') {
      $condition .= " AND (DATE_FORMAT(a.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'st.rollNo';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////
/*
	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];
	$rollNo = $REQUEST_DATA['rollNo'];
	$studentName = $REQUEST_DATA['studentName'];
	$feeClassId  = trim($REQUEST_DATA['classId']);

	//echo($studentName);

	//$filter = "AND (fr.receiptDate BETWEEN '$startDate' AND '$toDate')";
	if ($rollNo != '') {
		$filter .= " AND st.rollNo LIKE \"%$rollNo%\"";
	}
	if ($studentName != '') {
		$filter .= " AND CONCAT(st.firstName,' ',st.lastName) LIKE \"%$studentName%\"";
	}
	if ($feeClassId != '') {
		$filter .= " AND c.classId=$feeClassId";
	}
	//echo ($filter);*/
	//$fineTotalStudentWiseRecordArray = $fineManager->getTotalStudentWiseFineCollectionReportDetail($filter);
	$collectionFineStudentWiseRecordArray = $fineManager->getStudentWiseFineCollectionReportDetailNew($condition,$limit,$orderBy);
	$cnt = count($collectionFineStudentWiseRecordArray);
	
	//print_r($collectionFineStudentWiseRecordArray);
	
    for($i=0;$i<$cnt;$i++) {
		$totalFineAmount=$collectionFineStudentWiseRecordArray[$i]['totalFineAmount'];
		$totalFinePaid=$collectionFineStudentWiseRecordArray[$i]['totalFinePaid'];

		if($totalFinePaid==''){
			$collectionFineStudentWiseRecordArray[$i]['totalFinePaid']='---';
		}
		
		//$studentId = $collectionFineStudentWiseRecordArray[$i]['studentId'];		
		
		if($totalFinePaid!=''){
			$balance=($totalFineAmount - $totalFinePaid);
		}
		else{
			$balance='---';
		}
		
		$viewDetail = '<img src='.IMG_HTTP_PATH.'/print.jpg border="0" alt="View" title="View" width="20" height="20" style="cursor:hand" onclick="printReport('.$studentId.');return false;" title="View Detail">';
        $valueArray = array_merge(array('srNo' => ($records+$i+1),'balance' => $balance ),$collectionFineStudentWiseRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($fineTotalStudentWiseRecordArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
 
?>
