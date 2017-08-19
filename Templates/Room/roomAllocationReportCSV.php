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
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="#,Name ,Roll No.,Class,Hostel,Room,Charges,Security,Ledger Debit,Ledger Credit,Total,Hostel Fee Paid,Balance,Issue Date, Comments";
    $csvData .="\n";

    for($i=0;$i<$cnt;$i++) {
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
        
	
        
      $issueDate ='';
      if($roomRecordArray[$i]['passIssueDate']!='0000-00-00') {
        $issueDate = UtilityManager::formatDate($roomRecordArray[$i]['passIssueDate']);
      }    
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['studentName']).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['rollNo']).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['className']).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['hostelName']).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['studentName']).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['roomName']).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['hostelCharges']).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['securityAmount']).",";
	   $csvData .= parseCSVComments($roomRecordArray[$i]['ledgerDebit']).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['ledgerCredit']).",";
	   $csvData .= parseCSVComments($totalAmount).",";
	   $csvData .= parseCSVComments($roomRecordArray[$i]['paidAmount']).",";
      $csvData .= parseCSVComments($balance).",";
      $csvData .= parseCSVComments($issueDate).",";
	  $csvData .= parseCSVComments($roomRecordArray[$i]['comments'])."\n";
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
