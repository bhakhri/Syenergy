<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','VacantRoomMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
    $roomAllocationManager = RoomAllocationManager::getInstance();
   
    

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $filter  ='';
    $filter2 ='';
    if(isset($REQUEST_DATA['hostelId']) and  $REQUEST_DATA['hostelId']!='' and $REQUEST_DATA['hostelId']!='-1'){
       $filter .= ' AND h.hostelId='.$REQUEST_DATA['hostelId'];
    }
    if(isset($REQUEST_DATA['roomType']) and  $REQUEST_DATA['roomType']!=''){
       $filter .= ' AND hr.hostelRoomTypeId='.$REQUEST_DATA['roomType'];
    }
    
    if(isset($REQUEST_DATA['toDate']) and  trim($REQUEST_DATA['toDate'])!=''){
       //$filter2 = ' WHERE ( hs.possibleDateOfCheckOut BETWEEN CURRENT_DATE() AND "'.trim($REQUEST_DATA['toDate']).'" ) ';
       $filter2 = ' AND ( hs.possibleDateOfCheckOut >= "'.trim($REQUEST_DATA['toDate']).'" ) ';
    }
    
    if(trim($REQUEST_DATA['toDate'])==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray            = $roomAllocationManager->getPossibleTotalVacantRoom($filter,$filter2);
    $vacantRoomRecordArray = $roomAllocationManager->getPossibleVacantRoomList($filter,$limit,$orderBy,$filter2);
    $cnt = count($vacantRoomRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       if($vacantRoomRecordArray[$i]['vacant']==0){
           $vacantRoomRecordArray[$i]['vacant']='<font color="red"><b>FULL</b></font>';
       }
       if($vacantRoomRecordArray[$i]['occupied']!=0){
           $vacantRoomRecordArray[$i]['occupied']='<span onclick="displayRoomAllocationDetails('.$vacantRoomRecordArray[$i]['hostelRoomId'].')" class="padding_right" style="cursor:pointer" title="Click to view room allocation details">'.$vacantRoomRecordArray[$i]['occupied'].'</span>';
           $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="popUpdateAllocationDiv('.$vacantRoomRecordArray[$i]['hostelId'].','.$vacantRoomRecordArray[$i]['hostelRoomId'].');return false;"></a>';
       }
       else{
          $actionStr=NOT_APPLICABLE_STRING; 
       }
       
       
       $valueArray = array_merge(array('actionString'=>$actionStr,'srNo' => ($records+$i+1) ),$vacantRoomRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxVacantRoomList.php $
?>