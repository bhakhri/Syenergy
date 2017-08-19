<?php 
// This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    
    require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
    $guestHouseManager = GuestHouseManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$qryString = "";
    
    
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
    $search .="<br/>Budget Heads : ".$headName." Status : ".$statusName;
    $search .=" Req. By : ".$userName;
   

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Guest House Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead					=	array();
	$reportTableHead['srNo']			=   array('#','width="2%"', "align='center' ");
    $reportTableHead['bookingNo']       =   array('Booking No.','width=5% align="left"', 'align="left"');
	$reportTableHead['guestName']		=   array('Guest Name','width=10% align="left"', 'align="left"');
	$reportTableHead['hostelName']		=   array('Guest House','width="10%" align="left" ', 'align="left"');
    $reportTableHead['roomName']        =   array('Room','width="5%" align="left" ', 'align="left"');
    $reportTableHead['arrivalDate']     =   array('Arr. Date','width="7%" align="center" ', 'align="center"');
    $reportTableHead['arrivalTime']     =   array('Time','width="5%" align="center" ', 'align="center"');
    $reportTableHead['departureDate']   =   array('Dept. Date','width="8%" align="center" ', 'align="center"');
    $reportTableHead['departureTime']   =   array('Time','width="5%" align="center" ', 'align="center"');
    $reportTableHead['isAllocated']     =   array('Status','width="6%" align="left" ', 'align="left"');
    $reportTableHead['userName']        =   array('Req. By','width="7%" align="left" ', 'align="left"');
    $reportTableHead['headName']        =   array('Head','width="5%" align="left" ', 'align="left"');
    
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();
?>