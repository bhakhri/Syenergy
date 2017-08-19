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
    define('MODULE','StudentDetailFineCollectionReport');
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
      $condition = " AND (DATE_FORMAT(a.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
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

	//echo($studentName);

	$filter = "AND (fr.receiptDate BETWEEN '$startDate' AND '$toDate')";
	if ($rollNo != '') {
		$filter .= " AND st.rollNo LIKE \"%$rollNo%\"";
	}
	if ($studentName != '') {
		$filter .= " AND CONCAT(st.firstName,' ',st.lastName) LIKE \"%$studentName%\"";
	}
	//echo ($filter);*/
	$fineTotalStudentDetailRecordArray = $fineManager->getTotalStudentDetailFineCollectionReportDetailNew($condition);
	$collectionFineStudentDetailRecordArray = $fineManager->getStudentDetailFineCollectionReportDetailNew($condition,$limit,$orderBy);
	$cnt = count($collectionFineStudentDetailRecordArray);
	
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
		//$studentId = $collectionFineRecordArray[$i]['studentId'];
		//$fineCategoryId = $collectionFineStudentWiseRecordArray[$i]['fineCategoryId'];
		$collectionFineStudentDetailRecordArray[$i]['fineDate'] = UtilityManager::formatDate($collectionFineStudentDetailRecordArray[$i]['fineDate']);
		$collectionFineStudentDetailRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($collectionFineStudentDetailRecordArray[$i]['receiptDate']);

        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$collectionFineStudentDetailRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($fineTotalStudentDetailRecordArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: initStudentDetailFineCollectionReport.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Fine
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/09/09   Time: 2:33p
//Updated in $/LeapCC/Library/Fine
//fixed bug No.0002201
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/08/09   Time: 6:49p
//Updated in $/LeapCC/Library/Fine
//fixed bug nos.0002210, 0002184, 0002207, 0002205
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Library/Fine
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/07/09    Time: 2:25p
//Created in $/LeapCC/Library/Fine
//new ajax file to show student detail fine collection report
//
?>
