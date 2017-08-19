<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel room in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','HostelRoomType');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/HostelRoomTypeManager.inc.php");
    $hostelRoomTypeManager = HostelRoomTypeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (roomType LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roomType';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $hostelRoomTypeManager->getTotalHostelRoomType($filter);
    $hostelRoomRecordArray = $hostelRoomTypeManager->getHostelRoomTypeList($filter,$limit,$orderBy);
    $cnt = count($hostelRoomRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add hostelRoomId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $hostelRoomRecordArray[$i]['hostelRoomTypeId'] , 'srNo' => ($records+$i+1) ),$hostelRoomRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitHostelRoomTypeList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:48a
//Created in $/LeapCC/Library/HostelRoomType
//
?>