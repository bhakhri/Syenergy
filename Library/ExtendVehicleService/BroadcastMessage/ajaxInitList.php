<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BroadcastMessage');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BroadcastMessageManager.inc.php");
    $messageManager = BroadcastMessageManager::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (messageText LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'messageDate';
    $orderBy = " $sortField $sortOrderBy";

    $totalArray = $messageManager->getTotalMessage($filter);
    $msgRecordArray = $messageManager->getMessageList($filter,$limit,$orderBy);
    $cnt = count($msgRecordArray);

    for($i=0;$i<$cnt;$i++) {
       $msgRecordArray[$i]['messageDate'] =UtilityManager::formatDate($msgRecordArray[$i]['messageDate']);
       if(strlen($msgRecordArray[$i]['messageText'])>200){
          $msgRecordArray[$i]['messageText']=substr($msgRecordArray[$i]['messageText'],0,197).'...';
       }
       $valueArray = array_merge(array('action' => $msgRecordArray[$i]['messageId'] , 'srNo' => ($records+$i+1) ),$msgRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
?>