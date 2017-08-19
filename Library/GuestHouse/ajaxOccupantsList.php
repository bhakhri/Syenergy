<?php
//-------------------------------------------------------
// Purpose: To make list view for guest house requests
// Author : Dipanjan Bbhattacharjee
// Created on : (17.05.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GuestHouseAuthorization');
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
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
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
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bookingNo';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
  //  $totalArray      = $guestHouseManager->getTotalGuestHouseRequest($filter);
	$guestHouseRecordArray = $guestHouseManager->getGuestHouseRequestList($filter,'',$orderBy);
	$count = COUNT($guestHouseRecordArray);
    $guestHouseRecordArray = $guestHouseManager->getGuestHouseRequestList($filter,$limit,$orderBy);
    $cnt = count($guestHouseRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        $actionString=NOT_APPLICABLE_STRING;
          $actionString='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" alt="Edit"   onclick="editWindow('.$guestHouseRecordArray[$i]['allocationId'].');return false;"></a>
                         <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="deleteGuestHouseAuthorization('.$guestHouseRecordArray[$i]['allocationId'].');"/></a>';
        
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
      //  echo $guestHouseRecordArray[$i]['aTime'];
        $time=explode(':',$guestHouseRecordArray[$i]['departureTime']);
        $guestHouseRecordArray[$i]['departureTime']=$time[0].':'.$time[1].' '.$guestHouseRecordArray[$i]['departureAmPm'];
        
        $valueArray = array_merge(array('actionString' => $actionString , 'srNo' => ($records+$i+1) ),$guestHouseRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
?>