<?php
//-------------------------------------------------------
// Purpose: To store the records of payment history in array from the database 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

	/// Search filter /////  
	/*if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
	   $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")';         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
	   $filter .= ' AND (rollNo LIKE "%'.add_slashes($REQUEST_DATA['studentRoll']).'%")';         
	}*/
	////////////

	$concatDegreeId = $REQUEST_DATA['degree'];
	$concatArr		= explode("-", $concatDegreeId);
	$universityID	= $concatArr[0];
	$degreeID		= $concatArr[1];
	$branchID		= $concatArr[2];
	$batchId		= $REQUEST_DATA['batch'];
	$studyperiodId	= $REQUEST_DATA['studyperiod'];

	$studentName	= $REQUEST_DATA['studentName'];
	$studentRoll	= $REQUEST_DATA['studentRoll'];
	
	if($universityID!='' && $batchId!='' && $studyperiodId!='')
	{
		$classIDArr		= $studentManager->getClass($universityID,$degreeID,$branchID,$batchId,$studyperiodId);

		$classId		= $classIDArr[0][classId];
		$className		= $classIDArr[0][className];
		$classNameArr = explode(" - ", $className);

		if($classId=='')
			$classId = "-1";
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['studentName'])) {
	   $filter .= ' AND (firstName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%" OR lastName LIKE "%'.add_slashes($REQUEST_DATA['studentName']).'%")';         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['studentRoll'])) {
	   $filter .= ' AND (rollNo LIKE "%'.add_slashes($REQUEST_DATA['studentRoll']).'%")';         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['feeCycle'])) {
	   $filter .= ' AND (fr.feeCycleId = '.add_slashes($REQUEST_DATA['feeCycle']).')';         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
	   $filter .= " AND (receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
	   $filter .= " AND (receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['fromAmount'])) {
	   $filter .= ' AND (totalAmountPaid >='.add_slashes($REQUEST_DATA['fromAmount']).')';         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['toAmount'])) {
	   $filter .= ' AND (totalAmountPaid <='.add_slashes($REQUEST_DATA['toAmount']).')';         
	}
	
	$totalArray = $studentManager->getTotalFeesStudent($filter);

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feeReceiptId';
    
    $orderBy = " $sortField $sortOrderBy";  

    $studentRecordArray = $dashboardManager->getAllFeesDue($filter,$limit,$orderBy,$classId);

    
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {

		$studentRecordArray[$i]['rollNo'] = $studentRecordArray[$i]['rollNo'] == '' ? '--' : $studentRecordArray[$i]['rollNo'] ;

		$studentRecordArray[$i]['universityRollNo'] = $studentRecordArray[$i]['universityRollNo'] == '' ? '--' : $studentRecordArray[$i]['universityRollNo'] ;
		$feeReceiptId = $studentRecordArray[$i]['feeReceiptId'];

		$outstanding = "0.00";
		if($studentRecordArray[$i]['previousOverPayment']>0)
			$outstanding = "-".$studentRecordArray[$i]['previousOverPayment'];
		if($studentRecordArray[$i]['previousDues']>0)
			$outstanding = $studentRecordArray[$i]['previousDues'];
        
		// add subjectId in actionId to populate edit/delete icons in User Interface   
        
		
		$valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxFeesDueList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/08/08    Time: 3:39p
//Created in $/Leap/Source/Library/Student
//intial chekin
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/02/08    Time: 8:05p
//Updated in $/Leap/Source/Library/Student
//updated file with bug fixes
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:04p
//Updated in $/Leap/Source/Library/Student
//updated fees concept by making use of previousDues and
//previousOverPayment 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/28/08    Time: 1:09p
//Updated in $/Leap/Source/Library/Student
//updated fee receipt payment report
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/07/08    Time: 3:03p
//Created in $/Leap/Source/Library/Student
//intial checkin
?>
