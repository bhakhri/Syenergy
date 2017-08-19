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
        
        //$valueArray = array_merge(array('actionString' => $actionString , 'srNo' => ($records+$i+1) ),$recordArray[$i]);
        
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Guest House Authorization Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead					   =   array();
	$reportTableHead['srNo']			   =   array('#','width=2% align="left"', 'align="left"');
    $reportTableHead['bookingNo']          =   array('Booking No.','width=5% align="left"', 'align="left"');
	$reportTableHead['guestName']		   =   array('Guest Name','width=12% align="left"', 'align="left"');
	$reportTableHead['hostelName']		   =   array('Guest House','width="10%" align="left" ', 'align="left"');
    $reportTableHead['roomName']           =   array('Room','width="5%" align="left" ', 'align="left"');
    $reportTableHead['arrivalDate']        =   array('Arr. Date','width="10%" align="center" ', 'align="left"');
    $reportTableHead['aTime']			=      array('Time','width="8%" align="center" ', 'align="center"');
    $reportTableHead['departureDate']      =   array('Dept. Date','width="10%" align="center" ', 'align="center"');
    $reportTableHead['dTime']      =           array('Time','width="8%" align="center" ', 'align="center"');
    $reportTableHead['isAllocated']        =   array('Allocated','width="7%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>