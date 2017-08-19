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
	define('MODULE','HandleComplaints');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ReportComplaintsManager.inc.php");
    $reportComplaintsManager = ReportComplaintsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (c.subject LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.description LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR cc.catName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
    
     $orderBy = "$sortField $sortOrderBy";

    ////////////
    
	$hostelId = $REQUEST_DATA['hostel'];
	$roomId = $REQUEST_DATA['room'];
	$studentId = $REQUEST_DATA['reportedBy'];
	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];

	if ($hostelId == '' && $roomId == '' && $studentId =='') {
		$conditions = "AND c.complaintOn BETWEEN '$startDate' AND '$toDate'";
		$totalArray = $reportComplaintsManager->getTotalAllHandleComplaintDetail($conditions,$orderBy);
		$reportCompalintRecordArray = $reportComplaintsManager->getHandleAllComplaintDetailList($conditions,$orderBy,$limit);
		$cnt = count($reportCompalintRecordArray);	
	}
	
	if ($hostelId != '' && $roomId != '' && $studentId == '') {
		$conditions = "AND c.hostelRoomId = $roomId AND c.complaintOn BETWEEN '$startDate' AND '$toDate'";
		$totalArray = $reportComplaintsManager->getTotalAllHandleComplaintDetail($conditions,$orderBy);
		$reportCompalintRecordArray = $reportComplaintsManager->getHandleAllComplaintDetailList($conditions,$orderBy,$limit);
		$cnt = count($reportCompalintRecordArray);
	}
	
	if ($hostelId != '' && $roomId != '' && $studentId !='') {
		$conditions = "AND c.studentId=$studentId AND c.hostelRoomId = $roomId AND c.complaintOn BETWEEN '$startDate' AND '$toDate'";
		$totalArray = $reportComplaintsManager->getTotalAllHandleComplaintDetail($conditions,$orderBy);
		$reportCompalintRecordArray = $reportComplaintsManager->getHandleAllComplaintDetailList($conditions,$orderBy,$limit);
		$cnt = count($reportCompalintRecordArray);
	}
    
    for($i=0;$i<$cnt;$i++) {
        // add hostelRoomId in actionId to populate edit/delete icons in User Interface
		$reportCompalintRecordArray[$i]['complaintOn']  = UtilityManager::formatDate($reportCompalintRecordArray[$i]['complaintOn']);
		if ($reportCompalintRecordArray[$i]['completionDate'] == "0000-00-00") {
			$reportCompalintRecordArray[$i]['completionDate'] = NOT_APPLICABLE_STRING;	
		}
		else {
			$reportCompalintRecordArray[$i]['completionDate']  = UtilityManager::formatDate($reportCompalintRecordArray[$i]['completionDate']);	
		}
		
		if ($reportCompalintRecordArray[$i]['complaintStatus'] == 3 || $reportCompalintRecordArray[$i]['complaintStatus'] == 2) {
		$valueArray = array_merge(array('handleComplaint' => NOT_APPLICABLE_STRING,'action' => $reportCompalintRecordArray[$i]['complaintId'], 'srNo' => ($records+$i+1) ),
										$reportCompalintRecordArray[$i]);
		}
		else {
       $valueArray = array_merge(array('handleComplaint' => '<img src='.IMG_HTTP_PATH.'/sub.gif border="0" alt="Handle Complaint" title="Handle Complaint" width="15" height="15" style="cursor:hand" onclick="showHandleComplaintDetail('.$reportCompalintRecordArray[$i]['complaintId'].',\'divHandleComplaint\',320,320);return false;" title="Handle Complaint Detail">','action' => $reportCompalintRecordArray[$i]['complaintId'], 'srNo' => ($records+$i+1) ),
										$reportCompalintRecordArray[$i]);
		}
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: initComplaintsReport.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/13/09    Time: 4:34p
//Updated in $/LeapCC/Library/ReportComplaints
//fixed bug nos.0000116,0000099,0000117,0000119,0000121,0000097
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:07p
//Updated in $/LeapCC/Library/ReportComplaints
//make the changes as per discussion with pushpender sir
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:16p
//Created in $/LeapCC/Library/ReportComplaints
//new ajax files for report complaints & handle complaints
//
//
?>