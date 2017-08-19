<?php 
//This file is used as CSV version for payment status for subject centric.
// Author :Nishu Bindal
// Created on : 11-05-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

   require_once(MODEL_PATH . "/Fee/FineSetUpManager.inc.php");   
$fineSetUpManager = FineSetUpManager::getInstance();  
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
       //to parse csv values    
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
    
    $classId  =   $REQUEST_DATA['fineClassId'];	
    $fineTypeId  =  $REQUEST_DATA['fineTypeId'];
    $fromDate = $REQUEST_DATA['fromDate'];
    $toDate = $REQUEST_DATA['toDate'];	
    $chargesFormat = $REQUEST_DATA['chargesFormat'];	
    $charges = $REQUEST_DATA['charges'];

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

 $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    
     $orderBy = " $sortField $sortOrderBy"; 

      if($classId=='') {
	  $classId =0;
	 }
        $condition="";
	
	 if($fineTypeId!='') {
	  $condition .="AND fn.feeFineTypeId = '$fineTypeId'";
	 }

	if($classId!='') {
	  $condition .="AND fn.classId IN ('$classId')";
	 } 

	if($fromDate!='') {
	  $condition .="AND fn.fromDate = '$fromDate'";
	 }

	if($toDate!='') {
	  $condition .="AND fn.toDate = '$toDate''";
	 }

	if($chargesFormat!='') {
	  $condition .=" AND fn.chargesFormat = '$chargesFormat'";
	 }

	if($charges!='') {
	  $condition .=" AND fn.charges = '$charges'";
	 }    

		
    $foundArray = $fineSetUpManager->getFineSetUpList($condition,$orderBy,$limit);
    
  $cnt = count($foundArray);




 $csvData .= "#,Class,From Date,To Date,Fine Type,Charges Format,Amount";
    $csvData .= "\n";
   
    for($i=0;$i<$cnt;$i++) { 
	  
      $id = $foundArray[$i]['feeFineId'];  
      $foundArray[$i]['fromDate'] = UtilityManager::formatDate($foundArray[$i]['fromDate']);  
      $foundArray[$i]['toDate'] = UtilityManager::formatDate($foundArray[$i]['toDate']);  
    
  
  
        $csvData .= ($i+1).",".parseCSVComments($foundArray[$i]['className']).",".parseCSVComments($foundArray[$i]['fromDate']); 
        $csvData .= ",".parseCSVComments($foundArray[$i]['toDate']).",".parseCSVComments($foundArray[$i]['feeFineTypeId']);
        $csvData .= ",".parseCSVComments($foundArray[$i]['chargesFormat']).",".parseCSVComments($foundArray[$i]['charges']); 
        $csvData .= "\n";
       
}
 
   
   
    
    if($i==0) {
      $csvData .= ",,,No Data Found \n";   
    }
    
    UtilityManager::makeCSV($csvData,'ClassFineDetailSetUpReport.csv');
    die;   
?>
