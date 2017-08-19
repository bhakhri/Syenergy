<?php
//-------------------------------------------------------
// Purpose: To store the records of room in array from the database, pagination and search, delete 
// functionality
// Author : Jaineesh
// Created on : (2.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');

    global $sessionHandler; 
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
      UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else if($roleId==3){
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else if($roleId==4){
      UtilityManager::ifParentNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineReportManager.inc.php");
    $fineReportManager = FineReportManager::getInstance();
    
 
 // CSV data field Comments added 
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

    $classId= trim($REQUEST_DATA['classId']);   
    $showPending= trim($REQUEST_DATA['showPending']);  
    $studentName= htmlentities(add_slashes(trim($REQUEST_DATA['studentName'])));
    $rollNo= htmlentities(add_slashes(trim($REQUEST_DATA['rollNo'])));
    $fatherName= htmlentities(add_slashes(trim($REQUEST_DATA['fatherName'])));
    $receiptNo= htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo'])));  
    $fromDate= trim($REQUEST_DATA['fromDate']);  
    $toDate= trim($REQUEST_DATA['toDate']);  
            
    $condition ='';
    $having ='';
    $payCondition ='';
    
    if($classId=='') {
      $classId='0';  
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
   
    if($classId!='') {
      $condition .= " AND fs.classId IN ($classId) ";
    }
    
    if($receiptNo!='') {
      $payCondition .= " AND frd.fineReceiptNo LIKE '$receiptNo%' ";
    }
    
    if($fromDate!='' && $toDate!='') {
      $payCondition = " AND (frd.receiptDate BETWEEN '$fromDate' and '$toDate') ";
    }
    
    if($showPending=='1') { // Paid
      if($having=='') {
        $having .= " HAVING (IFNULL(totalAmount,0) - IFNULL(paidAmount,0)) <= 0 ";    
      }  
      else {
        $having .= " AND (IFNULL(totalAmount,0) - IFNULL(paidAmount,0)) <= 0 ";      
      }
    }
    else if($showPending=='2') {   // UnPaid
      if($having=='') {
        $having .= " HAVING (IFNULL(totalAmount,0) - IFNULL(paidAmount,0)) > 0 ";    
      }  
      else {
        $having .= " AND (IFNULL(totalAmount,0) - IFNULL(paidAmount,0)) > 0 ";      
      }
    }
    
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

 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         

    $fineRecordArray = $fineReportManager->getFineStudentNew($condition,$orderBy,$limit,$having,$payCondition);
    $cnt = count($fineRecordArray);
    for($i=0;$i<$cnt;$i++) {
    	
       
       $valueArray[] = array_merge(array('action' => $fineRecordArray[$i]['studentId'] ,
     									  'fineCategory'=>$fineCategoryName,
                                       'srNo' => ($records+$i+1) ),
                                       $fineRecordArray[$i]);
     }

    //$search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="#,Name ,Roll No.,Class Name,Fine Category,Total Fine,Paid Fine,Balance";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
    	$studentId = $fineRecordArray[$i]['studentId'];
		$ttcondition = "AND s.studentId = '$studentId'";
	 $fineCategoryArray = $fineReportManager->getStudentFineCategory($ttcondition);
	 $fineCategoryName="";
	 if(count($fineCategoryArray)>0){
	 	for($xx=0;$xx<count($fineCategoryArray);$xx++){
	 		if($fineCategoryName!=''){
	 			$fineCategoryName .= ","; 
	 		}
	 		$fineCategoryName .= $fineCategoryArray[$xx]['fineCategoryName'];	
	 	} 
	 }
	 
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($fineRecordArray[$i]['studentName']).",";
	  $csvData .= parseCSVComments($fineRecordArray[$i]['rollNo']).",";
	  $csvData .= parseCSVComments($fineRecordArray[$i]['instituteClassName']).",";
	   $csvData .= parseCSVComments($fineCategoryName).",";
	  $csvData .= parseCSVComments($fineRecordArray[$i]['totalAmount']).",";
	  $csvData .= parseCSVComments($fineRecordArray[$i]['paidAmount']).",";
	  $csvData .= parseCSVComments($fineRecordArray[$i]['balanceAmount'])."\n";
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
