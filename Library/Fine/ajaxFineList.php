<?php
//-------------------------------------------------------
// Purpose: To store the records of payment history in array from the database 
//
// Author : Saurabh Thukral
// Created on : (13.09.2012 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','StudentFineHistoryReport');
	define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineCategoryManager = FineManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlManager = HtmlFunctions::getInstance();
    
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	
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

		$genName=" OR CONCAT(TRIM(stu.firstName),' ',TRIM(stu.lastName)) LIKE '".add_slashes($genName)."%'";
	}  

	return $genName;
	}


/*	// Student Name
	if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
		$studentName = trim($REQUEST_DATA['studentName']);
		$parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $filter .= " AND (
						  TRIM(stu.firstName) LIKE '".add_slashes(trim($studentName))."%' 
						  $parsedName
					    )";
	  // $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")';         
	}

	// Roll No
	if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
	   $filter .= ' AND (rollNo LIKE "%'.add_slashes(trim($REQUEST_DATA['studentRoll'])).'%")';         
	}

	 
	// from Date
	/*if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
       if(trim($REQUEST_DATA['finePaidRadio'])==1){
	     $filter .= " AND (receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";
       }
       else{
         $filter .= " AND (fs.fineDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";    
       }
	}

	// to date
	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
      if(trim($REQUEST_DATA['finePaidRadio'])==1){
	   $filter .= " AND (receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";
      }
      else{
          $filter .= " AND (fs.fineDate <='".add_slashes($REQUEST_DATA['toDate'])."')";
      }
	}
	
	if($REQUEST_DATA['searchDate']=='1') {
	   $filter .= ' AND (frd.receiptDate BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'" )';
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['receiptNo'])) {
	   $filter .= ' AND (frd.fineReceiptNo LIKE "%'.add_slashes(trim($REQUEST_DATA['receiptNo'])).'%")';
	} 
*/

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
    
    $condition = "";
    $whereCondition='';
    
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
      $condition .= " AND c.classId = '$classId' ";
    }
    if($rollNo!='') {
      $condition .= " AND (stu.rollNo LIKE '$rollNo%' OR stu.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $condition .= " AND stu.fatherName LIKE '$fatherName%' ";
    }
    if($receiptNo!='') {
      $condition .= " AND frd.fineReceiptNo LIKE '$receiptNo%' ";
    }
    
    if($fromDate!='' && $toDate!='') {
      $condition .= " AND (frd.receiptDate BETWEEN '$fromDate' and '$toDate') ";
    }	
	    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    $orderBy = " ORDER by $sortField $sortOrderBy";	

    $studentCountFineList  = $fineCategoryManager->studentCountFineList($condition);
    $totalRecords = $studentCountFineList[0]['totalRecords'];
   
    $studentFineList  = $fineCategoryManager->studentFineList($condition,$limit,$orderBy);
    $cnt = count($studentFineList);

    for($i=0;$i<$cnt;$i++) {
   		$printAction = "<a href='#' onClick='printDetailReceipt(\"".$studentFineList[$i]['fineReceiptNo']."\")' title='Detail'><u><img src='".IMG_HTTP_PATH."/zoom.gif' border='0' /></u></a>&nbsp;&nbsp;<a href='#' onClick='printReceipt(\"".$studentFineList[$i]['fineReceiptNo']."\")' title='Print'><u><img src='".IMG_HTTP_PATH."/print1.gif' border='0' /></u></a>
		&nbsp;&nbsp;<a name='Delete' onclick='deleteReceipt(\"".$studentFineList[$i]['fineReceiptDetailId']."\");return false;' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'></a>";
   		
		$studentFineList[$i]['receiptDate'] = UtilityManager::formatDate($studentFineList[$i]['receiptDate']);
   	 	$valueArray = array_merge(array('printAction'=>$printAction,'srNo' => ($records+$i+1) ),$studentFineList[$i]);

   		if(trim($json_val)=='') {
        	$json_val = json_encode($valueArray);
   		}
   		else {
        	$json_val .= ','.json_encode($valueArray);           
   		}
 	}
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';
?>
