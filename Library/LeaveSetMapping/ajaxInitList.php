<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','LeaveSetMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/LeaveSetMappingManager.inc.php");
    $leaveSetMappingManager = LeaveSetMappingManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (TRIM(ls.leaveSetName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR TRIM(lt.leaveTypeName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR TRIM(lsm.leaveValue) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveSetName';
    
    $sortField2=$sortField;    
    /*
    if($sortField=='leaveTypeName'){
        $sortField =' LENGTH(leaveTypeName)+0,leaveTypeName';
    }
    */
    $orderBy = " $sortField $sortOrderBy";
    $sortField=$sortField2;

    ////////////
    
    $totalArray = $leaveSetMappingManager->getTotalLeaveSetMapping($filter);
    $leaveSetMappingRecordArray = $leaveSetMappingManager->getLeaveSetMappingList($filter,$limit,$orderBy);
    $cnt = count($leaveSetMappingRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $valueArray = array_merge(array('action' => $leaveSetMappingRecordArray[$i]['leaveSetMappingId'] , 'srNo' => ($records+$i+1) ),$leaveSetMappingRecordArray[$i]);

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
