<?php
    //Paging code goes here
    require_once(MODEL_PATH . "/RoomManager.inc.php");
    $roomManager = RoomManager::getInstance();
    
    //Delete code goes here
   
        
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (roomName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomAbbreviation LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR capacity LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    $totalArray = $roomManager->getTotalRoom($filter);
    $roomRecordArray = $roomManager->getRoomList($filter,$limit);
?>