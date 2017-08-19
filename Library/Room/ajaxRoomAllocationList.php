<?php
//-------------------------------------------------------
// Purpose: To store the records of room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (2.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
    
    
   
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $roomManager->getTotalRoomAllocation($filter,$having);
    $roomRecordArray = $roomManager->getRoomAllocationList($filter,$limit,$orderBy,$having);
    $cnt = count($roomRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
	
	 $chkId = "chb".$i;
        
        $studentId= $roomRecordArray[$i]['studentId'];
        $classId= $roomRecordArray[$i]['classId'];
	    $checkall = "<input type='checkbox' name='chb[]' id='$chkId' value='$studentId~$classId' >";
        
        if($roomRecordArray[$i]['feeReceiptId'] == '') {
          $roomRecordArray[$i]['paidAmount'] = NOT_APPLICABLE_STRING; 
          $checkall = NOT_APPLICABLE_STRING; 
        }
        
        $currentClassId = $roomRecordArray[$i]['currentClassId'];
        
        $printReceipt = "<a href='' onClick='printFeeReceipt(\"hostel\",$classId,$studentId,$currentClassId); return false;'>
                            <img src='".IMG_HTTP_PATH."/hostel_icon.png'>
                         </a>&nbsp;";
        $roomRecordArray[$i]['dateOfCheckIn']=UtilityManager::formatDate($roomRecordArray[$i]['dateOfCheckIn']);
        if($roomRecordArray[$i]['dateOfCheckOut']!=NOT_APPLICABLE_STRING){
          $roomRecordArray[$i]['dateOfCheckOut']=UtilityManager::formatDate($roomRecordArray[$i]['dateOfCheckOut']);
        }
        
        $hostelName = $roomRecordArray[$i]['hostelName']."<br>".$roomRecordArray[$i]['roomName'];
        $hostelName .= $roomRecordArray[$i]['dateOfCheckIn']." to ".$roomRecordArray[$i]['dateOfCheckOut'];
        
        $roomRecordArray[$i]['hostelName'] = $hostelName;
        
        $paidAmount = "<a href='' onClick='printFeeReceipt(\"hostel\",$classId,$studentId,$currentClassId); return false;'>
                            <img src='".IMG_HTTP_PATH."/hostel_icon.png'>
                         </a>&nbsp;";
	        
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
        
	
        $valueArray = array_merge(array('action' => $roomRecordArray[$i]['hostelStudentId'] ,
                                        'printReceipt' => $printReceipt,
					 'checkAll' => $checkall,
					'totalAmount' => $totalAmount,
                                        'balance' => $balance,
                                        'srNo' => ($records+$i+1) ),
                                        $roomRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>
