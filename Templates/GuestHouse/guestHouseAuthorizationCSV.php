<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
    $guestHouseManager = GuestHouseManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    

//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}
    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $filter = ''; 
    if (!empty($search)) {
       $isAllocated=-1;
       $search=trim($REQUEST_DATA['searchbox']);
       if(strtoupper($search)=='WAITING'){
           $isAllocated=0;
       }
       if(strtoupper($search)=='ALLOCATED'){
           $isAllocated=1;
       }
       if(strtoupper($search)=='REJECTED'){
           $isAllocated=2;
       }
       $search=add_slashes($search);
    $filter = ' HAVING (gh.bookingNo LIKE "%'.$search.'%" OR gh.guestName LIKE "%'.$search.'%" OR gh.isAllocated LIKE "%'.$isAllocated.'%" OR h.hostelName LIKE "%'.$search.'%" OR hr.roomName LIKE "%'.$search.'%" OR dTime LIKE "%'.$search.'%" OR 
	   aTime LIKE "%'.$search.'%")';
    }
    //$conditions = '';
    //if (count($conditionsArray) > 0) {
        //$conditions = ' AND '.implode(' AND ',$conditionsArray);
    //}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bookingNo';

    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $guestHouseManager->getGuestHouseRequestList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($recordArray[$i]['isAllocated']==0){
            $recordArray[$i]['isAllocated']='Waiting';
        }
        else if($recordArray[$i]['isAllocated']==1){
            $recordArray[$i]['isAllocated']='Allocated';
        }
        else{
            $recordArray[$i]['isAllocated']='Rejected';
        }
        
        if($recordArray[$i]['hostelName']==''){
            $recordArray[$i]['hostelName']=NOT_APPLICABLE_STRING;
        }
        if($recordArray[$i]['roomName']==''){
            $recordArray[$i]['roomName']=NOT_APPLICABLE_STRING;
        }
        
        $recordArray[$i]['arrivalDate']=UtilityManager::formatDate($recordArray[$i]['arrivalDate']);
        $recordArray[$i]['departureDate']=UtilityManager::formatDate($recordArray[$i]['departureDate']);
        
        $time=explode(':',$recordArray[$i]['arrivalTime']);
        $recordArray[$i]['arrivalTime']=$time[0].':'.$time[1].' '.$recordArray[$i]['arrivalAmPm'];
        
        $time=explode(':',$recordArray[$i]['departureTime']);
        $recordArray[$i]['departureTime']=$time[0].':'.$time[1].' '.$recordArray[$i]['departureAmPm'];
        
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = '';
    $csvData .= "#, Booking No., Guest Name, Guest House, Room, Arr. Date, Time, Dept. Date, Time, Allocated \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['bookingNo']).', '.parseCSVComments($record['guestName']).','.parseCSVComments($record['hostelName']).','.parseCSVComments($record['roomName']).','.parseCSVComments($record['arrivalDate']).','.parseCSVComments($record['aTime']).','.parseCSVComments($record['departureDate']).','.parseCSVComments($record['dTime']).','.parseCSVComments($record['isAllocated']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="guestHouseAuthorization.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: testTypeCSV.php $
?>