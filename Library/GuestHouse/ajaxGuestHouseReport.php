<?php
//-------------------------------------------------------
// Purpose: To make list view for guest house requests
// Author : Dipanjan Bbhattacharjee
// Created on : (17.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GuestHouseReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
    $guestHouseManager = GuestHouseManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
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
    }
    if($roomId!='-1'){
        $filter .=' AND ( gh.hostelRoomId='.$roomId.')';
    }
    if($headId!='-1'){
        $filter .=' AND ( gh.budgetHeadId='.$headId.' )';
    }
    if($status!='-1'){
        $filter .=' AND ( gh.isAllocated='.$status.' )';
    }
    if($requestId!='-1'){
        $filter .=' AND ( gh.requesterUsedId='.$requestId.' )';
    }
    
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bookingNo';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    $totalArray      = $guestHouseManager->getTotalGuestHouseReport($filter);
    $guestHouseRecordArray = $guestHouseManager->getGuestHouseReportList($filter,$limit,$orderBy);
    $cnt = count($guestHouseRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        $actionString=NOT_APPLICABLE_STRING;
        
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
        
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$guestHouseRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
?>