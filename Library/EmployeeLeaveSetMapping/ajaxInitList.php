<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','EmployeeEmployeeLeaveSetMapping');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeLeaveSetMappingManager.inc.php");
    $employeeLeaveSetMappingManager = EmployeeLeaveSetMappingManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ls.leaveSetName  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        emp.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        emp.employeeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    $filter .=" AND s.active=1";
    $totalArray = $employeeLeaveSetMappingManager->getTotalEmployeeLeaveSetMapping($filter);
    $empLeaveSetMappingRecordArray = $employeeLeaveSetMappingManager->getEmployeeLeaveSetMappingList($filter,$limit,$orderBy);
    $cnt = count($empLeaveSetMappingRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if(trim($empLeaveSetMappingRecordArray[$i]['employeeCode'])==''){
            $empLeaveSetMappingRecordArray[$i]['employeeCode']=NOT_APPLICABLE_STRING;
        }
        if(trim($empLeaveSetMappingRecordArray[$i]['employeeName'])==''){
            $empLeaveSetMappingRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        $valueArray = array_merge(array('action' => $empLeaveSetMappingRecordArray[$i]['employeeLeaveSetMappingId'] , 'srNo' => ($records+$i+1) ),$empLeaveSetMappingRecordArray[$i]);

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