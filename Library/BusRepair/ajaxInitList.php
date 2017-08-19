<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BusRepairCourse');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BusRepairManager.inc.php");
    $busManager = BusRepairManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       //$filter = ' AND  ( trs.name LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bs.busName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR br.serviceFor LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR br.cost LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR br.workshopName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR br.billNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
       $filter = ' AND  ( trs.name LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bs.busNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR br.serviceFor LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR br.cost LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR br.workshopName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR br.billNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'name';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $busManager->getTotalBusRepair($filter);
    $busRecordArray = $busManager->getBusRepairList($filter,$limit,$orderBy);
    $cnt = count($busRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $busRecordArray[$i]['dated']=UtilityManager::formatDate($busRecordArray[$i]['dated']);
        $valueArray = array_merge(array('action' => $busRecordArray[$i]['repairId'] , 'srNo' => ($records+$i+1) ),$busRecordArray[$i]);

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
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:11
//Updated in $/LeapCC/Library/BusRepair
//Replicated bus repair module's enhancements from leap to leapcc
//
//*****************  Version 3  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Library/BusRepair
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/BusRepair
//Updated fleet mgmt file in Leap 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 18/04/09   Time: 10:36
//Updated in $/SnS/Library/BusRepair
//Done Bug Fixing
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 12:55
//Created in $/SnS/Library/BusRepair
//Created Bus Repair Module
?>
