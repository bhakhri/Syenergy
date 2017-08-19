<?php 
//This file is used as printing version for payment status.
// Author :Nishu Bindal
// Created on : 11-05-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
 
    require_once(MODEL_PATH . "/Fee/PaymentHistoryReportManager.inc.php");   
    $PaymentHistoryReportManager = PaymentHistoryReportManager::getInstance();
    
    require_once(BL_PATH . '/ScReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
	
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
      $search .= "<B>Degree:</B>".$REQUEST_DATA['degreeName'];         
    }
    
    // Branch
    if(UtilityManager::notEmpty($REQUEST_DATA['branch'])) {
       $filter .= " AND c.branchId = '".$REQUEST_DATA['branch']."'";            
       $search .= "&nbsp;&nbsp;<B>Branch:</B>".$REQUEST_DATA['branchName'];         
    }
    
    // Batch
    if(UtilityManager::notEmpty($REQUEST_DATA['batch'])) {
        $filter .= ' AND (c.batchId = '.add_slashes($REQUEST_DATA['batch']).')';        
       $search .= "&nbsp;&nbsp;<B>Batch:</B>".$REQUEST_DATA['batchName'];         
    }

    // Class 
    if(UtilityManager::notEmpty($REQUEST_DATA['classId'])) {
         $filter .= ' AND (c.classId = '.add_slashes($REQUEST_DATA['classId']).')';        
       $search .= "&nbsp;&nbsp;<B>Class Name:</B>".$REQUEST_DATA['className'];
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
       $search .="<br><b>Name:</b>".$studentName;
      // $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")';         
    }

    // Roll No
    if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
       $filter .= ' AND (s.rollNo LIKE "%'.add_slashes(trim($REQUEST_DATA['studentRoll'])).'%")';         
       $search .="&nbsp;&nbsp;<b>Roll No.:</b>".$REQUEST_DATA['studentRoll'];
    }

    // fee cycle
    if(UtilityManager::notEmpty($REQUEST_DATA['feeCycle'])) {
       $filter .= ' AND (frm.feeCycleId = '.add_slashes($REQUEST_DATA['feeCycle']).')';       
       $search .= "<br><B>Fee Cycle:</B>".$REQUEST_DATA['feecycleName'];
    }

    // from Date
    if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
        $filter .= " AND (date_format(frd.receiptDate, '%Y-%m-%d' ) >='".add_slashes($REQUEST_DATA['fromDate'])."')";   
       $search .= "<B>From Date:</B>".UtilityManager::formatDate($REQUEST_DATA['fromDate']);      
    }

    // to date
    if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
        $filter .= " AND (date_format(frd.receiptDate, '%Y-%m-%d' ) <='".add_slashes($REQUEST_DATA['toDate'])."')";       
       $search .= "&nbsp;<B>To Date:</B>".UtilityManager::formatDate($REQUEST_DATA['toDate']);      
    }
    
    if(UtilityManager::notEmpty($REQUEST_DATA['receiptNo'])) {
      $filter .= ' AND (frd.receiptNo LIKE "%'.add_slashes(trim($REQUEST_DATA['receiptNo'])).'%")';   
       $search .= "&nbsp;&nbsp;<B>Receipt No.:</B>".$REQUEST_DATA['receiptNo'];         
    }
    ////////////
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $search .= "<br>As On $formattedDate";
    
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
    
    for($i=0;$i<$cnt;$i++) { 
        $studentRecordArray[$i]['installmentNo'] = "Installment-".$studentRecordArray[$i]['installmentNo'];
        $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['receiptDate']);  
        
        if($studentRecordArray[$i]['receiveDD']=='0.00') {
          $studentRecordArray[$i]['receiveDD']='';  
        }
        
        if($studentRecordArray[$i]['receiveCash']=='0.00') {
          $studentRecordArray[$i]['receiveCash']='';  
        }
        
        $valueArray[] = array_merge(array('srNos' => ($records+$i+1) ),
                                        $studentRecordArray[$i]);
    }
    
   
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Receipt Status Report Print');
    $reportManager->setReportInformation($search);
    
    $reportTableHead                      =   	array();
                    //associated key                  col.label,             col. width,      data align        
    $reportTableHead['srNos']             =  	array('#',                  ' width="2%"  align="left"', "align='left'");
    $reportTableHead['receiptDate']       =  	array('Receipt Date ',   ' width=12%   align="center" ','align="center" ');
    $reportTableHead['receiptNo']         =  	array('Receipt No',            ' width="10%" align="left" ','align="left"');
    $reportTableHead['studentName']       =  	array('Name',               ' width="12%" align="left" ','align="left"');
    $reportTableHead['rollNo']            =  	array('Roll No.',           ' width="10%" align="left" ','align="left"');
    $reportTableHead['className']         =  	array('Fee Class',          ' width="15%" align="left" ','align="left"');
    $reportTableHead['cycleName']         =  	array('Fee Cycle',          ' width="9%" align="left" ','align="left"');
    $reportTableHead['installmentNo']     =   	array('Installment',        ' width="11%" align="left" ','align="left"');
    $reportTableHead['receiveCash']       =     array('Cash(Rs.)',          ' width="10%" align="right" ','align="right"');    
    $reportTableHead['receiveDD']         =     array('DD(Rs.)',            ' width="10%" align="right" ','align="right"');
    $reportTableHead['ddDetail']          =     array('DD Detail',          ' width="13%" align="right" ','align="right"');
    $reportTableHead['amount']     	      =   	array('Total Receipt (Rs.)',' width="13%" align="right" ','align="right"');
     $reportTableHead['employeeCodeName']     	      =   	array('Collected By',' width="13%" align="right" ','align="right"');
                                       
    

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

    die;
    


?>
