<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','CleaningHistoryMaster');
	define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CleaningRoomManager.inc.php");
    $cleaningRoomManager = CleaningRoomManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'et.tempEmployeeName';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
	$tempEmployeeId = $REQUEST_DATA['tempEmployee'];
	$hostelId = $REQUEST_DATA['hostel'];
	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];

	if ($hostelId == '' && $tempEmployeeId == '') {
		$conditions = "AND Dated BETWEEN '$startDate' AND '$toDate'";
		$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}
	if ($tempEmployeeId != '' && $hostelId == '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.tempEmployeeId IN($tempEmployeeId)";
		$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}

	if ($tempEmployeeId != '' && $hostelId != '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.tempEmployeeId IN($tempEmployeeId)
						AND hcr.hostelId IN($hostelId)";
		$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}

	if ($tempEmployeeId == '' && $hostelId != '' ) {
		$conditions = "	AND Dated BETWEEN '$startDate' AND '$toDate'
						AND hcr.hostelId IN($hostelId)";
		$totalArray = $cleaningRoomManager->getTotalCleaningHistoryDetail($conditions);
		$cleaningRoomRecordArray = $cleaningRoomManager->getCleaningHistoryList($conditions,$orderBy);
		$cnt = count($cleaningRoomRecordArray);	
	}
	
    for($i=0;$i<$cnt;$i++) {
        // add hostelRoomId in actionId to populate edit/delete icons in User Interface
		$cleaningRoomRecordArray[$i]['Dated']  = UtilityManager::formatDate($cleaningRoomRecordArray[$i]['Dated']);
       $valueArray = array_merge(array('action' => $cleaningRoomRecordArray[$i]['cleanId'], 'srNo' => ($records+$i+1) ),
										$cleaningRoomRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: initCleaningHistoryReport.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/CleaningHistory
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/16/09   Time: 5:29p
//Updated in $/LeapCC/Library/CleaningHistory
//fixed bug nos.0002013,0002014
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:24p
//Created in $/LeapCC/Library/CleaningHistory
//new file for cleaning history report
//
//
?>