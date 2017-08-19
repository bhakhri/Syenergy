<?php
//-------------------------------------------------------
// Purpose: To displaythe records of vehicle Route 
// Author : 
// Created on : 
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','VehicleRouteAllocation');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/VehicleRouteAllocationManager.inc.php");
    $VehicleRouteAllocationManager = VehicleRouteAllocationManager::getInstance();

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
    $searchRollNo = trim($REQUEST_DATA['searchRollNo']);
    $searchStudentName = trim($REQUEST_DATA['searchStudentName']);    
    $searchFatherName = trim($REQUEST_DATA['searchFatherName']);  
    $searchRouteName = trim($REQUEST_DATA['searchRouteName']);  
    $searchStopCity = trim($REQUEST_DATA['searchStopCity']);  
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
   
    $having='';
    if($searchFor=='1') {  //  Pending Pass
      $filter .= " AND brsm.isPassStatus = 0 AND IFNULL(frd.feeReceiptId,'') != ''  ";     
      if($searchDate!='') {
        $filter .= " AND (  (brsm.validFrom BETWEEN '$fromDate' AND '$toDate') 
                            OR
                            (brsm.validTo BETWEEN '$fromDate' AND '$toDate') 
                         )  ";      
      }
    }
    else if($searchFor=='2') {   //  Generated Pass
      $filter .= " AND brsm.isPassStatus > 0  ";       
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
        $filter .= " AND (  (brsm.validFrom BETWEEN '$fromDate' AND '$toDate') 
                            OR
                            (brsm.validTo BETWEEN '$fromDate' AND '$toDate') 
                         )  ";      
      }
    }
	 else if($searchFor=='5') {    //  Pending Fee
      $filter .= " AND IFNULL(frd.feeReceiptId,'') <> '' "; 
      $having .= " HAVING 
                       (brsm.routeCharges) <> paidAmount ";           
      if($searchDate!='') {
        $filter .= " AND ((brsm.validFrom BETWEEN '$fromDate' AND '$toDate'))  ";     
      }
    }	
    
    else {
       if($searchDate!='') {
         $filter .= " AND ((brsm.validFrom BETWEEN '$fromDate' AND '$toDate') )  ";      
       }
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
    
  
    
    if($searchRouteName!='') {
       $filter .= " AND brsm.busRouteId = '$searchRouteName' ";     
    }
    
    if($searchStopCity!='') {
       $filter .= " AND brsm.busStopCityId = '$searchStopCity' ";
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         
    
    $vehicleRouteArray = $VehicleRouteAllocationManager->getVehicleRouteAllocationList($filter,'',$orderBy,$having);
    $cnt = count($vehicleRouteArray);
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="#,Name , Roll No,Stop City,Stop Name,Route Name,Seat No.,Valid From,Valid To,Transport Charges,Ledger Debit,Ledger Credit,Total,Transport Fee Paid,Balance, Pass Issue, Comments ";
    $csvData .="\n";    

    for($i=0;$i<$cnt;$i++) {
        $classId = $vehicleRouteArray[$i]['classId'];
        $studentId = $vehicleRouteArray[$i]['studentId'];
        $currentClassId = $vehicleRouteArray[$i]['currentClassId'];
        
        $issueDate ='';
        if($vehicleRouteArray[$i]['passIssueDate']!='0000-00-00') {
          $issueDate = UtilityManager::formatDate($vehicleRouteArray[$i]['passIssueDate']);
        }
	
	$totalAmount =doubleval($vehicleRouteArray[$i]['routeCharges']) +doubleval($vehicleRouteArray[$i]['ledgerDebit']);
	$totalAmount = $totalAmount -doubleval($vehicleRouteArray[$i]['ledgerCredit']);
	 if($vehicleRouteArray[$i]['paidAmount']!=NOT_APPLICABLE_STRING) {
          $balance = (doubleval($vehicleRouteArray[$i]['routeCharges'])+doubleval($vehicleRouteArray[$i]['ledgerDebit']))-doubleval($vehicleRouteArray[$i]['paidAmount']);
	  $balance = $balance-doubleval($vehicleRouteArray[$i]['ledgerCredit']);
        }
        else {
          $balance = doubleval($vehicleRouteArray[$i]['routeCharges'])+doubleval($vehicleRouteArray[$i]['ledgerDebit']); 
	  $balance = $balance-doubleval($vehicleRouteArray[$i]['ledgerCredit']);
        }
        
         
        $vehicleRouteArray[$i]['validFrom'] = UtilityManager::formatDate($vehicleRouteArray[$i]['validFrom']); 
        $vehicleRouteArray[$i]['validTo'] = UtilityManager::formatDate($vehicleRouteArray[$i]['validTo']); 
        
        $csvData .= ($i+1).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['studentName']).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['rollNo']).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['cityName']).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['stopName']).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['routeName']).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['seatNumber']).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['validFrom']).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['validTo']).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['routeCharges']).",";
	 $csvData .= parseCSVComments($vehicleRouteArray[$i]['ledgerDebit']).",";
	  $csvData .= parseCSVComments($vehicleRouteArray[$i]['ledgerCredit']).",";
	   $csvData .= parseCSVComments($totalAmount).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['paidAmount']).",".$issueDate.",";
	$csvData .= parseCSVComments($balance).",";
	$csvData .= parseCSVComments($vehicleRouteArray[$i]['comments'])."\n";
    }
   
  
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="VehicleRouteAllocationReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;  

?>
