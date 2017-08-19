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

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    
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
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $classId = $vehicleRouteArray[$i]['classId'];
        $studentId = $vehicleRouteArray[$i]['studentId'];
        $currentClassId = $vehicleRouteArray[$i]['currentClassId'];
        
        $vehicleRouteArray[$i]['validFrom'] = UtilityManager::formatDate($vehicleRouteArray[$i]['validFrom']); 
        $vehicleRouteArray[$i]['validTo'] = UtilityManager::formatDate($vehicleRouteArray[$i]['validTo']); 
        
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
        
        
        $issueDate ='';
        if($vehicleRouteArray[$i]['passIssueDate']!='0000-00-00') {
          $issueDate = UtilityManager::formatDate($vehicleRouteArray[$i]['passIssueDate']);
        }
        
        $valueArray[] = array_merge(array('action' => $vehicleRouteArray[$i]['busRouteStudentMappingId'] , 
                                        'printReceipt' => $printReceipt, 
                                        'issueDate' => $issueDate,
					'balance' => $balance,
					'totalAmount' => $totalAmount,
                                        'srNo' => ($records+$i+1) ),$vehicleRouteArray[$i]);

    }
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Vehicle Route Allocation Report Print');
	//$reportManager->setReportInformation("Search by: ".$search);
	

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="left"', "align='left'");
    $reportTableHead['studentName']			=    array('Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['rollNo']	=    array('Roll No.',        ' width="15%" align="left" ','align="left"');
	$reportTableHead['cityName']			=    array('Stop City',        ' width="15%" align="left" ','align="left"');
     $reportTableHead['stopName']				=    array('Stop Name',                'width="4%" align="left"', "align='left'");
    $reportTableHead['routeName']			=    array('Route Name ',         ' width=15% align="left" ','align="left" ');
    $reportTableHead['seatNumber']	=    array('Seat No.',        ' width="10%" align="left" ','align="left"');
	$reportTableHead['validFrom']			=    array('Valid From',        ' width="10%" align="left" ','align="center"');
    $reportTableHead['validTo']				=    array('Valid To',                'width="10%" align="left"', "align='center'");
    $reportTableHead['routeCharges']			=    array('Transport Charges ',         ' width=15% align="right" ','align="right" ');
    $reportTableHead['ledgerDebit']	=    array('Ledger Debit ','width="10%" align="right"','align="right"');
$reportTableHead['ledgerCredit']=    array('Ledger Credit ','width="10%" align="right"','align="right"');
$reportTableHead['totalAmount']	=    array( 'Total','width="10%" align="center"','align="center"');
    $reportTableHead['paidAmount']	=    array('Transport Fee Paid',        ' width="15%" align="right" ','align="right"');
      $reportTableHead['balance']	=    array('Balance',        ' width="15%" align="right" ','align="right"');
    $reportTableHead['issueDate']    =    array('Pass Issue',        ' width="15%" align="left" ','align="left"');
     $reportTableHead['comments']	=    array('Comments',        ' width="15%" align="left" ','align="left"');
     

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

?>
