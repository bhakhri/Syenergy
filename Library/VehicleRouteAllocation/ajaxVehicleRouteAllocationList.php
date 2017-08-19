<?php
//-------------------------------------------------------
// Purpose: To store the records of vehicle Route in array from the database, pagination and search, delete 
// functionality
// Author : NISHU BINDAL
// Created on : (2.07.2008 )
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
   
    $having = '';
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
    
   
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
     $orderBy = " $sortField $sortOrderBy";         
    
    $totalArray = $VehicleRouteAllocationManager->getTotalVehicleRouteAllocation($filter,$having);
    $vehicleRouteArray = $VehicleRouteAllocationManager->getVehicleRouteAllocationList($filter,$limit,$orderBy,$having);
    $cnt = count($vehicleRouteArray);
    
    for($i=0;$i<$cnt;$i++) {
        $chkId = "chb".$i;
        $employeeId= $vehicleRouteArray[$i]['employeeId'];
        $studentId= $vehicleRouteArray[$i]['studentId'];
        $classId= $vehicleRouteArray[$i]['classId'];
        $checkall = "<input type='checkbox' name='chb[]' id='$chkId' value='$studentId~$classId' >";

        $currentClassId = $vehicleRouteArray[$i]['currentClassId'];
        $vFrom = UtilityManager::formatDate($vehicleRouteArray[$i]['validFrom']); 
        $vTo = UtilityManager::formatDate($vehicleRouteArray[$i]['validTo']); 
        
        $validFromTo = $vFrom." to ".$vTo;
        
        $vehicleRouteArray[$i]['routeName'] .= " (".$vehicleRouteArray[$i]['seatNumber'].")";
         
        if($vehicleRouteArray[$i]['feeReceiptId'] == '') {
          $vehicleRouteArray[$i]['paidAmount'] = NOT_APPLICABLE_STRING; 
          $checkall = NOT_APPLICABLE_STRING; 
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
        
        $printReceipt = "<a href='' onClick='printFeeReceipt(\"transport\",$classId,$studentId,$currentClassId); return false;'>
                            <img src='".IMG_HTTP_PATH."/transport_icon.png'>
                         </a>&nbsp;";
                         
        $isAllocation = $vehicleRouteArray[$i]['isAllocation'];
        $id = "'".$vehicleRouteArray[$i]['busRouteStudentMappingId']."~".$isAllocation."'";
   
        $valueArray = array_merge(array('action' => $id , 
                                        'printReceipt' => $printReceipt,
                                        'checkAll' => $checkall,
				        'balance' => $balance,
					'totalAmount' => $totalAmount,   
                                        'validFromTo' => $validFromTo,                     
                                        'srNo' => ($records+$i+1) ),$vehicleRouteArray[$i]);

        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);           
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

?>

