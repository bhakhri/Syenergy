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
 
    require_once(MODEL_PATH . "/Fee/CanceledReceiptsManager.inc.php");   
    $canceledReceiptsManager = CanceledReceiptsManager::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
	
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
    $paidFee = trim($REQUEST_DATA['paidFee']); //used to print fee-academic wise-1,hostel,wise-2,transport wise-3,all-4.
    
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
      if($paidFee=='1') {//academic
      $condition .= " AND frd.feeType IN(1,4)";
    }else if($paidFee=='2') {//transport
      $condition .= " AND frd.feeType IN(2,4)";
    }else if($paidFee=='3') {//hostel
      $condition .= " AND frd.feeType IN(3,4) ";
	}
    if($fromDate!='' && $toDate!='') {
      $condition .= " AND (DATE_FORMAT(frd.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
    }
    
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
      $sortField1 = 'frd.receiptDate';  
    }
    else if($sortField=='receiptNo') {
      $sortField1 = 'LENGTH(frd.receiptNo)+0,frd.receiptNo';  
    }
    $orderBy = "$sortField1 $sortOrderBy";  
    
    $studentRecordArray = $canceledReceiptsManager->getPaymentHistoryDetailsNew($condition,$limit,$sortOrderBy,$sortField);
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
    
   
    $reportManager->setReportWidth(1000);
    $reportManager->setReportHeading('Delete Receipt Status Report Print');
    $reportManager->setReportInformation($search);
    
    $reportTableHead                      =   	array();
                    //associated key                  col.label,             col. width,      data align        
    $reportTableHead['srNos']             =  	array('#',                  ' width="2%"  align="left"',   "align='left'");
    $reportTableHead['receiptDate']       =  	array('Receipt Date ',      ' width="11%" align="center" ','align="center" ');
    $reportTableHead['receiptNo']         =  	array('Receipt No',         ' width="10%" align="left" ',  'align="left"');
    $reportTableHead['studentName']       =  	array('Name',               ' width="10%" align="left" ',  'align="left"');
    $reportTableHead['rollNo']            =  	array('Roll No.',           ' width="10%" align="left" ',  'align="left"');
    $reportTableHead['className']         =  	array('Fee Class',          ' width="15%" align="left" ',  'align="left"');
    $reportTableHead['cycleName']         =  	array('Fee Cycle',          ' width="8%"  align="left" ',  'align="left"');
    $reportTableHead['installmentNo']     =   	array('Installment',        ' width="11%" align="left" ',  'align="left"');
    $reportTableHead['receiveCash']       =     array('Cash(Rs.)',          ' width="10%" align="right" ', 'align="right"');    
    $reportTableHead['receiveDD']         =     array('DD(Rs.)',            ' width="10%" align="right" ', 'align="right"');
    $reportTableHead['ddDetail']          =     array('DD Detail',          ' width="13%" align="right" ', 'align="right"');
    $reportTableHead['amount']     	  =   	array('Total Receipt (Rs.)',' width="13%" align="right" ', 'align="right"');
    $reportTableHead['reason']     	  =   	array('Cancellation Reason',' width="10%" align="right" ', 'align="right"');
    $reportTableHead['employeeCodeName']  =   	array('Deleted By',         ' width="13%" align="right" ', 'align="right"');
                                       
    

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

    die;
    


?>
