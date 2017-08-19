<?php
//-------------------------------------------------------
// Purpose: To store the records of universities in array from the database, pagination and search, delete
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PlacementDriveMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Placement/DriveManager.inc.php");
    $driveManager = DriveManager::getInstance();

    /////////////////////////


    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    $driveDate = date('Y-m-d', strtotime($REQUEST_DATA['searchbox']));
	 if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND ( c.companyCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR d.placementDriveCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR startDate = "'.$driveDate.'" OR endDate = "'.$driveDate.'" or startTime = "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" or startTimeAmPm = "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  or endTime =  "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  or endTimeAmPm = "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'placementDriveCode';

    $orderBy = " $sortField $sortOrderBy";

    ////////////

    $totalArray = $driveManager->getTotalPlacementDrive($filter);
    $driveRecordArray = $driveManager->getPlacementDriveList($filter,$limit,$orderBy);
    $cnt = count($driveRecordArray);

    for($i=0;$i<$cnt;$i++) {

        $time=explode(':',$driveRecordArray[$i]['startTime']);
        $driveRecordArray[$i]['startTime']=$time[0].':'.$time[1].' '.$driveRecordArray[$i]['startTimeAmPm'];

        $time=explode(':',$driveRecordArray[$i]['endTime']);
        $driveRecordArray[$i]['endTime']=$time[0].':'.$time[1].' '.$driveRecordArray[$i]['endTimeAmPm'];


       $driveRecordArray[$i]['startDate']=UtilityManager::formatDate($driveRecordArray[$i]['startDate']);
       $driveRecordArray[$i]['endDate']=UtilityManager::formatDate($driveRecordArray[$i]['endDate']);

       $valueArray = array_merge(array('action' => $driveRecordArray[$i]['placementDriveId'] , 'srNo' => ($records+$i+1) ),$driveRecordArray[$i]);
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