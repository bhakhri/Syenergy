<?php 
//This file is used as CSV version for payment status for subject centric.
// Author :Nishu Bindal
// Created on : 11-05-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

     require_once(MODEL_PATH . "/Fee/PaymentHistoryReportManager.inc.php");   
    $PaymentHistoryReportManager = PaymentHistoryReportManager::getInstance();
    
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
    
     /// Search filter /////  
    function parseName($value){
        $name=explode(' ',$value);
        $genName="";
        $len= count($name);
        if($len > 0){
          for($i=0;$i<$len;$i++){
            if(trim($name[$i])!=""){
               if($genName!=""){
                  $genName =$genName ." ".$name[$i];
                }
                else{
                  $genName =$name[$i];
                } 
             }
           }
        }
        if($genName!=""){
           $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".$genName."%'";
        }  
           return $genName;
    }

    
    $search='';
    /// Search filter ///// 
    // Degree
    $feeClassIdOld = "";
    $feeClassIdNew = "";
    
   
    if(UtilityManager::notEmpty($REQUEST_DATA['degree'])){
      //$feeClassIdOld = " AND stu.classId = ".$REQUEST_DATA['degree'];         
       $filter .= " AND c.degreeId = ".$REQUEST_DATA['degree'];
      $search .= "Degree,".parseCSVComments($REQUEST_DATA['degreeName']).",";         
    }
    
     // Branch
    if(UtilityManager::notEmpty($REQUEST_DATA['branch'])) {
       $filter .= " AND c.branchId = ".$REQUEST_DATA['branch'];
        $search .= "Branch,".parseCSVComments($REQUEST_DATA['branchName']).",";            
    }
    
    // Batch
    if(UtilityManager::notEmpty($REQUEST_DATA['batch'])) {
        $filter .= ' AND (c.batchId = '.add_slashes($REQUEST_DATA['batch']).')';        
       $search .= "Batch,".parseCSVComments($REQUEST_DATA['batchName']).",";
    }

   
     // Class 
    if(UtilityManager::notEmpty($REQUEST_DATA['classId'])) {
        $filter .= ' AND (c.classId = '.add_slashes($REQUEST_DATA['classId']).')';        
       $search .= "Class Name:,".parseCSVComments($REQUEST_DATA['className']).",";         
    }
    
    if($search != '') {
      $search .= "\n";  
    }

   
    // Student Name
    if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
        $studentName = $REQUEST_DATA['studentName'];
        $parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $filter .= " AND (
                          TRIM(s.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                          OR 
                          TRIM(s.lastName) LIKE '".add_slashes(trim($studentName))."%'
                          $parsedName
                     )";
     $search .="Name,".parseCSVComments($studentName).",";         
    }

    // Roll No
    if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
        $filter .= ' AND (s.rollNo LIKE "%'.add_slashes(trim($REQUEST_DATA['studentRoll'])).'%")';       
       $search .="Roll No.,".parseCSVComments($REQUEST_DATA['studentRoll']).",";
    }
    
    if($search != '') {
      $search .= "\n";  
    }

    // fee cycle
    if(UtilityManager::notEmpty($REQUEST_DATA['feeCycle'])) {
      $filter .= ' AND (frm.feeCycleId = '.add_slashes($REQUEST_DATA['feeCycle']).')';             
       $search .= "Fee Cycle,".parseCSVComments($REQUEST_DATA['feecycleName']).",";
    }

    // from Date
    if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
        $filter .= " AND (date_format(frd.receiptDate, '%Y-%m-%d' ) >='".add_slashes($REQUEST_DATA['fromDate'])."')";   
       $search .= "From Date,".parseCSVComments(UtilityManager::formatDate($REQUEST_DATA['fromDate'])).",";    
    }

    // to date
    if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
       $filter .= " AND (date_format(frd.receiptDate, '%Y-%m-%d' ) <='".add_slashes($REQUEST_DATA['toDate'])."')";      
       $search .= "To Date,".parseCSVComments(UtilityManager::formatDate($REQUEST_DATA['toDate'])).",";   
    }

    if($search != '') {
      $search .= "\n";  
    }
    
   

    if(UtilityManager::notEmpty($REQUEST_DATA['receiptNo'])) {
        $filter .= ' AND (frd.receiptNo LIKE "%'.add_slashes(trim($REQUEST_DATA['receiptNo'])).'%")';            
       $search .= "Receipt No.,".parseCSVComments($REQUEST_DATA['receiptNo']).",";
    }
    
    if($search!='') {
      $csvData = $search;  
      $csvData .= "\n";
    }
    else {
      $csvData ="";  
    }
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $csvData .= "As On, ".parseCSVComments($formattedDate);
    $csvData .= "\n";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    
    if($sortField=='undefined') {
      $sortField='receiptDate';  
    }
    
    if($sortOrderBy=='undefined') {
      $sortOrderBy='ASC';  
    }
    
    $sortField1 = $sortField;
    if($sortField=='receiptDate') {
      $sortField1 = 'fr.receiptDate';  
    }
    else if($sortField=='receiptNo') {
      $sortField1 = 'LENGTH(fr.receiptNo)+0,fr.receiptNo';  
    }
    $orderBy = "$sortField1 $sortOrderBy";    
    
    
    $studentRecordArray = $PaymentHistoryReportManager->getPaymentHistoryDetailsPrint($filter,$sortOrderBy,$sortField);
    $cnt = count($studentRecordArray);
 
    $csvData .= "#,Receipt Date,Receipt No.,Name,Roll No.,Fee Class,Fee Cycle,Installment,Cash(Rs.),DD(Rs.),DD Detail,Total Receipt (Rs.),Collected By";
    $csvData .= "\n";
    
    for($i=0;$i<$cnt;$i++) { 
        
        if($studentRecordArray[$i]['receiveDD']=='0.00') {
          $studentRecordArray[$i]['receiveDD']='';  
        }
        
        if($studentRecordArray[$i]['receiveCash']=='0.00') {
          $studentRecordArray[$i]['receiveCash']='';  
        }
        
        $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['receiptDate']);  
        $studentRecordArray[$i]['installmentNo'] = "Installment-".$studentRecordArray[$i]['installmentNo'];
        $csvData .= ($i+1).",".parseCSVComments($studentRecordArray[$i]['receiptDate']).",".parseCSVComments($studentRecordArray[$i]['receiptNo']); 
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['studentName']).",".parseCSVComments($studentRecordArray[$i]['rollNo']);
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['className']).",".parseCSVComments($studentRecordArray[$i]['cycleName']); 
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['installmentNo']).",".$studentRecordArray[$i]['receiveCash']; 
        $csvData .= ",".$studentRecordArray[$i]['receiveDD']; 
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['ddDetail']).",".$studentRecordArray[$i]['amount']; 
	$csvData .= ",".parseCSVComments($studentRecordArray[$i]['employeeCodeName']);
        $csvData .= "\n";
    }
    
    if($i==0) {
      $csvData .= ",,,No Data Found \n";   
    }
    
    UtilityManager::makeCSV($csvData,'FeeReceiptReport.csv');
    die;   
?>
