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
    
    if(trim($REQUEST_DATA['hostelId'])=='' or trim($REQUEST_DATA['roomId'])=='' or trim($REQUEST_DATA['toDate'])==''){
       echo 'Required Parameters Missing';
       die;
    }
    
    $filter .= ' AND h.hostelId='.trim($REQUEST_DATA['hostelId']);
    $filter .= ' AND hr.hostelRoomId='.trim($REQUEST_DATA['roomId']);
    $filter2 = ' AND ( hs.possibleDateOfCheckOut >= "'.trim($REQUEST_DATA['toDate']).'" ) ';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray            = $roomAllocationManager->getPossibleTotalVacantRoomOccupants($filter,$filter2);
    $vacantRoomRecordArray = $roomAllocationManager->getPossibleVacantRoomOccupantsList($filter,$limit,$orderBy,$filter2);
    $cnt = count($vacantRoomRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       
       $vacantRoomRecordArray[$i]['dateOfCheckIn']=UtilityManager::formatDate($vacantRoomRecordArray[$i]['dateOfCheckIn']);
       $actionStr='<input type="checkbox" name="studentChk" value="'.$vacantRoomRecordArray[$i]['studentId'].'~'.$vacantRoomRecordArray[$i]['hostelId'].'~'.$vacantRoomRecordArray[$i]['hostelRoomId'].'" />';
       $valueArray = array_merge(array('students'=>$actionStr,'srNo' => ($records+$i+1) ),$vacantRoomRecordArray[$i]);

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