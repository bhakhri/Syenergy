<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
    $guestHouseManager = GuestHouseManager::getInstance();
    

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

    $filter='';
    $hostelId=trim($REQUEST_DATA['guestHouseId']);
    $roomId=trim($REQUEST_DATA['roomId']);
    $headId=trim($REQUEST_DATA['headId']);
    $status=trim($REQUEST_DATA['allocationId']);
    $requestId=trim($REQUEST_DATA['requestId']);
    $fromDate=trim($REQUEST_DATA['fromDate']);
    $toDate=trim($REQUEST_DATA['toDate']);
    if(strtotime($fromDate)>strtotime($toDate)){
        die("To date can not be smaller than from date");
    }
    
    $filter =' WHERE ( gh.arrivalDate >= "'.$fromDate.'" AND gh.arrivalDate <= "'.$toDate.'" )';
    
    if($hostelId!='-1'){
        $filter .='  AND ( h.hostelId='.$hostelId.')';
        $hostelArray=$guestHouseManager->getHostelInfo($hostelId);
        $hostelName=trim($hostelArray['hostelName'])!=''?trim($hostelArray['hostelName']):NOT_APPLICABLE_STRING;
    }
    else{
        $hostelName="All";
    }
    if($roomId!='-1'){
        $filter .=' AND ( gh.hostelRoomId='.$roomId.')';
        $roomArray=$guestHouseManager->getRoomInfo($roomId);
        $roomName=trim($roomArray['roomName'])!=''?trim($roomArray['roomName']):NOT_APPLICABLE_STRING;
    }
    else{
        $roomName="All";
    }
    if($headId!='-1'){
        $filter .=' AND ( gh.budgetHeadId='.$headId.' )';
        $headArray=$guestHouseManager->getBudgetHeadInfo($headId);
        $headName=trim($headArray['headName'])!=''?trim($headArray['headName']):NOT_APPLICABLE_STRING;
    }
    else{
        $headName="All";
    }
    if($status!='-1'){
        $filter .=' AND ( gh.isAllocated='.$status.' )';
        if($status==0){
           $statusName='Waiting'; 
        }
        else if($status==1){
           $statusName='Allocated'; 
        }
        else if($status==2){
           $statusName='Rejected'; 
        }
        else{
           $statusName=NOT_APPLICABLE_STRING;  
        }
    }
    else{
        $statusName="All";
    }
    if($requestId!='-1'){
        $filter .=' AND ( gh.requesterUsedId='.$requestId.' )';
        $userArray=$guestHouseManager->getGuestHouseRequesterData(' AND u.userId='.$requestId);
        $userName=trim($userArray['userName'])!=''?trim($userArray['userName']):NOT_APPLICABLE_STRING;
    }
    else{
        $userName="All";
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bookingNo';

    $orderBy=" $sortField $sortOrderBy"; 


    $guestHouseRecordArray = $guestHouseManager->getGuestHouseReportList($filter,' ',$orderBy);

    $formattedDate = date('d-M-y');

    $cnt = count($guestHouseRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($guestHouseRecordArray[$i]['isAllocated']==0){
            $guestHouseRecordArray[$i]['isAllocated']='Waiting';
        }
        else if($guestHouseRecordArray[$i]['isAllocated']==1){
            $guestHouseRecordArray[$i]['isAllocated']='Allocated';
        }
        else{
            $guestHouseRecordArray[$i]['isAllocated']='Rejected';
        }
        
        if($guestHouseRecordArray[$i]['hostelName']==''){
            $guestHouseRecordArray[$i]['hostelName']=NOT_APPLICABLE_STRING;
        }
        if($guestHouseRecordArray[$i]['roomName']==''){
            $guestHouseRecordArray[$i]['roomName']=NOT_APPLICABLE_STRING;
        }
        
        $guestHouseRecordArray[$i]['arrivalDate']=UtilityManager::formatDate($guestHouseRecordArray[$i]['arrivalDate']);
        $guestHouseRecordArray[$i]['departureDate']=UtilityManager::formatDate($guestHouseRecordArray[$i]['departureDate']);
        
        $time=explode(':',$guestHouseRecordArray[$i]['arrivalTime']);
        $guestHouseRecordArray[$i]['arrivalTime']=$time[0].':'.$time[1].' '.$guestHouseRecordArray[$i]['arrivalAmPm'];
        
        $time=explode(':',$guestHouseRecordArray[$i]['departureTime']);
        $guestHouseRecordArray[$i]['departureTime']=$time[0].':'.$time[1].' '.$guestHouseRecordArray[$i]['departureAmPm'];
        
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$guestHouseRecordArray[$i]);
   }
   
    
    $search="From Date : ".UtilityManager::formatDate($fromDate)." To Date : ".UtilityManager::formatDate($toDate);
    $search .=" Guest House : ".$hostelName." Room : ".$roomName;
    $search .=" Budget Heads : ".$headName." Status : ".$statusName;
    $search .=" Req. By : ".$userName."\n";

	$csvData = "Search By:".$search;
    $csvData .= "#, Booking No., Guest Name, Guest House, Room, Arr. Date , Time, Dept. Date , Time , Status , Req. By , Head \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['bookingNo']).', '.parseCSVComments($record['guestName']).', '.parseCSVComments($record['hostelName']).','.parseCSVComments($record['roomName']).','.parseCSVComments($record['arrivalDate']).','.parseCSVComments($record['arrivalTime']).','.parseCSVComments($record['departureDate']).','.parseCSVComments($record['departureTime']).','.parseCSVComments($record['isAllocated']).','.parseCSVComments($record['userName']).','.parseCSVComments($record['headName']);
		$csvData .= "\n";
	}
    if(count($valueArray)==0){
        $csvData .=' , '.NO_DATA_FOUND;
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="guestHouseReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
?>