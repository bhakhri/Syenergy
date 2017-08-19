<?php
//-------------------------------------------------------
// Purpose: To display the records of room  from the database
// functionality
//
// Author : Jaineesh
// Created on : (2.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoomAllocation');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
    $roomManager = RoomAllocationManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    $searchClassStatus = $REQUEST_DATA['searchClassStatus'] ;
    
    $searchHostel = $REQUEST_DATA['searchHostel'] ;
	$searchRoom = $REQUEST_DATA['searchRoom'] ;
    $searchRollNo = trim($REQUEST_DATA['searchRollNo']);
    $searchStudentName = trim($REQUEST_DATA['searchStudentName']);    
    $searchFatherName = trim($REQUEST_DATA['searchFatherName']);    
    $searchRoomTypeId = trim($REQUEST_DATA['searchRoomType']);
    
    $searchDate    = $REQUEST_DATA['searchDate'] ;
    $fromDate  = $REQUEST_DATA['fromDate'] ;
    $toDate = $REQUEST_DATA['toDate'] ;
    $searchFor  = $REQUEST_DATA['searchFor'] ;  
    
    
    if($searchClassStatus=='') {
      $searchClassStatus='-1';  
    }
    
    $filter = '';
    if($searchClassStatus!='4') {
      $filter = " AND c.isActive = '$searchClassStatus' "; 
    }
    
    if($searchHostel!='') {
       $filter .= " AND r.hostelId = '$searchHostel' "; 
    }
	
     if($searchRoom!='') {
       $filter .= " AND hs.hostelRoomId = '$searchRoom' "; 
    }    

    
    if($searchRollNo!='') {
       $filter .= " AND (s.rollNo LIKE '$searchRollNo%' OR  s.regNo LIKE '$searchRollNo%' OR s.universityRollNo LIKE '$searchRollNo%' ) ";  
    }
    
    if($searchStudentName!='') {
       $filter .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$searchStudentName%' ) ";  
    }
    
    if($searchFatherName!='') {
       $filter .= " AND (IFNULL(s.fatherName,'') LIKE '$searchFatherName%' ) ";  
    }
    
    if($searchRoomTypeId!='') {
       $filter .= " AND r.hostelRoomTypeId = '$searchRoomTypeId' ";  
    }
    
    $having = '';     
    if($searchFor=='1') {  //  Pending Pass
      $filter .= " AND hs.isPassStatus = 0 AND IFNULL(frd.feeReceiptId,'') != ''  ";     
      if($searchDate!='') {
        $filter .= " AND (  (hs.dateOfCheckIn BETWEEN '$fromDate' AND '$toDate') 
                            OR
                            (hs.dateOfCheckOut BETWEEN '$fromDate' AND '$toDate') 
                         )  ";      
      }
    }
    else if($searchFor=='2') {   //  Generated Pass
      $filter .= " AND hs.isPassStatus > 0  ";       
      if($searchDate!='') {
        $filter .= " AND (DATE_FORMAT(frd.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate') ";      
      }
    }
    else if($searchFor=='3') {    //  Total Paid
      $filter .= " AND IFNULL(frd.feeReceiptId,'') != ''   ";         
      if($searchDate!='') {
        $filter .= " AND  (DATE_FORMAT(frd.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate') ";      
      }
    }
    else if($searchFor=='4') {    //  Total Un-Paid
      $filter .= " AND IFNULL(frd.feeReceiptId,'') = '' ";           
      if($searchDate!='') {
        $filter .= " AND (  (hs.dateOfCheckIn BETWEEN '$fromDate' AND '$toDate') 
                            OR
                            (hs.dateOfCheckOut BETWEEN '$fromDate' AND '$toDate') 
                         )  ";     
      }
    }
    else if($searchFor=='5') {    //  Pending Fee
      $filter .= " AND IFNULL(frd.feeReceiptId,'') <> '' "; 
      $having .= " HAVING 
                       (hs.hostelCharges+hs.securityAmount) <> paidAmount ";           
      if($searchDate!='') {
        $filter .= " AND ((hs.dateOfCheckIn BETWEEN '$fromDate' AND '$toDate'))  ";     
      }
    }
    else {
       if($searchDate!='') {
         $filter .= " AND ((hs.dateOfCheckIn BETWEEN '$fromDate' AND '$toDate') )  ";      
       }
    }
   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         
    
    $roomRecordArray = $roomManager->getRoomAllocationList($filter,'',$orderBy,$having);
    $cnt = count($roomRecordArray);
    
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $classId = $roomRecordArray[$i]['classId'];
        $studentId = $roomRecordArray[$i]['studentId'];
        $currentClassId = $roomRecordArray[$i]['currentClassId'];
        
        $printReceipt = "<a href='' onClick='printFeeReceipt(\"hostel\",$classId,$studentId,$currentClassId); return false;'>
                            <img src='".IMG_HTTP_PATH."/hostel_icon.png'>
                         </a>&nbsp;";
        $roomRecordArray[$i]['dateOfCheckIn']=UtilityManager::formatDate($roomRecordArray[$i]['dateOfCheckIn']);
        if($roomRecordArray[$i]['dateOfCheckOut']!=NOT_APPLICABLE_STRING){
          $roomRecordArray[$i]['dateOfCheckOut']=UtilityManager::formatDate($roomRecordArray[$i]['dateOfCheckOut']);
        }
        
        $hostelName = $roomRecordArray[$i]['hostelName']."<br>";
        $hostelName .= $roomRecordArray[$i]['dateOfCheckIn']." to ".$roomRecordArray[$i]['dateOfCheckOut'];
        
        $roomRecordArray[$i]['hostelName'] = $hostelName;
      
        $totalAmount =doubleval($roomRecordArray[$i]['hostelCharges']) +doubleval($roomRecordArray[$i]['securityAmount']) +doubleval($roomRecordArray[$i]['ledgerDebit']);
	$totalAmount = $totalAmount -doubleval($roomRecordArray[$i]['ledgerCredit']);

        if($roomRecordArray[$i]['paidAmount']!=NOT_APPLICABLE_STRING) {
          $balance = (doubleval($roomRecordArray[$i]['hostelCharges'])+doubleval($roomRecordArray[$i]['securityAmount'])+doubleval($roomRecordArray[$i]['ledgerDebit']))-doubleval($roomRecordArray[$i]['paidAmount']);
	  $balance = $balance-doubleval($roomRecordArray[$i]['ledgerCredit']);
        }
        else {
          $balance = doubleval($roomRecordArray[$i]['hostelCharges'])+doubleval($roomRecordArray[$i]['securityAmount'])+doubleval($roomRecordArray[$i]['ledgerDebit']);  
	 $balance = $balance-doubleval($roomRecordArray[$i]['ledgerCredit']);
        }
        
	
        
        $paidAmount = "<a href='' onClick='printFeeReceipt(\"hostel\",$classId,$studentId,$currentClassId); return false;'>
                            <img src='".IMG_HTTP_PATH."/hostel_icon.png'>
                         </a>&nbsp;";
      
        $issueDate ='';
        if($roomRecordArray[$i]['passIssueDate']!='0000-00-00') {
          $issueDate = UtilityManager::formatDate($roomRecordArray[$i]['passIssueDate']);
        }  
        
        $valueArray[] = array_merge(array('action' => $roomRecordArray[$i]['hostelStudentId'] ,
                                        'printReceipt' => $printReceipt,
                                        'issueDate' => $issueDate,
                                        'balance' => $balance,
					'totalAmount' => $totalAmount,
                                        'srNo' => ($records+$i+1) ),
                                        $roomRecordArray[$i]);

    }
   $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Room Allocation Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	

$reportTableHead      =    array();
//associated key                  col.label,            col. width,      data align        
$reportTableHead['srNo']	=    array('#',                'width="4%" align="left"', "align='left'");
$reportTableHead['studentName']			=    array('Name ',         ' width=15% align="left" ','align="left" ');
$reportTableHead['rollNo']	=    array('Roll No.',        ' width="15%" align="left" ','align="left"');
$reportTableHead['className']	=    array('Class',        ' width="15%" align="left" ','align="left"');
$reportTableHead['hostelName']	=    array('Hostel',                'width="4%" align="left"', "align='left'");
$reportTableHead['roomName']=    array('Room ',         ' width=15% align="left" ','align="left" ');
$reportTableHead['hostelCharges']=    array('Charges',        ' width="15%" align="right" ','align="right"');
$reportTableHead['securityAmount']=    array('Security',        ' width="15%" align="right" ','align="right"');
$reportTableHead['ledgerDebit']	=    array('Ledger Debit ','width="10%" align="right"','align="right"');
$reportTableHead['ledgerCredit']=    array('Ledger Credit ','width="10%" align="right"','align="right"');
$reportTableHead['totalAmount']	=    array( 'Total','width="10%" align="center"','align="center"');
$reportTableHead['paidAmount']	=    array('Hostel Fee Paid',        ' width="15%" align="left" ','align="left"');
$reportTableHead['balance']    =    array('Balance',        ' width="15%" align="left" ','align="left"');
$reportTableHead['issueDate']    =    array('Pass Issue',        ' width="15%" align="left" ','align="left"');
$reportTableHead['comments']    =    array('Comments',        ' width="15%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

?>
