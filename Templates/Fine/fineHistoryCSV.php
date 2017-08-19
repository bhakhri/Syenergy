<?php 
//This file is used as csv version for payment history.
//
// Author :Rajeev Aggarwal
// Created on : 24-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineCategoryManager = FineManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
    UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
    UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();  
 
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

    $startingRecord  = htmlentities(add_slashes(trim($REQUEST_DATA['startingRecord']))); 
    $totalRecords = htmlentities(add_slashes(trim($REQUEST_DATA['totalRecords']))); 
    
    if($startingRecord=='') {
      $startingRecord = 0; 
    }
    if($startingRecord>0) {
      $startingRecord=$startingRecord-1;  
    }
    else {
      $startingRecord=0;  
    }
    if($totalRecords=='') {
       $totalRecords = 500; 
    }
    $limit  = ' LIMIT '.$startingRecord.','.$totalRecords;
   

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

	/// Search filter /////  
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;

	function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    $orderBy = " ORDER by $sortField $sortOrderBy";	

    $studentFineList  = $fineCategoryManager->studentFineList($condition,$limit,$orderBy);
    $cnt = count($studentFineList);
   
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $studentFineList[$i]['receiptDate'] = UtilityManager::formatDate($studentFineList[$i]['receiptDate']);
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$studentFineList[$i]);
    }
	
	   //$search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    $csvData ='';
    //$csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="#,Receipt Date,Receipt No.,Name,Roll No.,Fine Class,Paid At,Collected By,Cash,DD,Total Receipt";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
           
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['srNo'])."";
	  $csvData .= parseCSVComments($studentFineList[$i]['receiptDate']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['fineReceiptNo']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['fullName']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['rollNo']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['className']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['paidAt']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['employeeCodeName']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['receiveCash']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['receiveDD']).",";
	  $csvData .= parseCSVComments($studentFineList[$i]['totalAmount'])."\n";
 
    }
	  
       
	  //print_r($csvData);
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment; filename="roomAllocationReport.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;  
	die;   


?>
