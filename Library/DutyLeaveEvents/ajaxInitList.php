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
    define('MODULE','DutyLeaveEvents');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DutyLeaveEventsManager.inc.php");
    $leaveManager = DutyLeaveEventsManager::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////

	 $eventDate = date('Y-m-d', strtotime($REQUEST_DATA['searchbox']));
	 if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (d.eventTitle LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR t.labelName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" or d.startDate = "'.$eventDate.'"  or d.endDate = "'.$eventDate.'" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventTitle';
    $orderBy = " $sortField $sortOrderBy";

    ////////////

    $totalArray = $leaveManager->getTotalEvent($filter);
    $leaveRecordArray = $leaveManager->getEventList($filter,$limit,$orderBy);
    $cnt = count($leaveRecordArray);

    for($i=0;$i<$cnt;$i++) {
       $leaveRecordArray[$i]['startDate']=UtilityManager::formatDate($leaveRecordArray[$i]['startDate']);
       $leaveRecordArray[$i]['endDate']=UtilityManager::formatDate($leaveRecordArray[$i]['endDate']);

       $valueArray = array_merge(array('action' => $leaveRecordArray[$i]['eventId'] , 'srNo' => ($records+$i+1) ),$leaveRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: ajaxInitList.php $
?>