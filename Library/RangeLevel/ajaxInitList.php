<?php
//-------------------------------------------------------
// Purpose: To store the records of Range Level in array from the database, pagination and search, delete 
// functionality
//
// Author : Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','RangeLevelMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RangeLevelManager.inc.php");
    $rangeLevelManager = RangeLevelManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE rangeLabel LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR rangeFrom LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR rangeTo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rangeFrom';
    
     $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $rangeLevelManager->getTotalRangeLevel($filter);
    $rangeLevelRecordArray = $rangeLevelManager->getRangeLevelList($filter,$limit,$orderBy);
	
    $cnt = count($rangeLevelRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add ranngeId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $rangeLevelRecordArray[$i]['rangeId'] , 'srNo' => ($records+$i+1) ),$rangeLevelRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/26/09    Time: 1:32p
//Updated in $/LeapCC/Library/RangeLevel
//fixed issues: 9 & 10 ---SGI
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/RangeLevel
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 11:54a
//Updated in $/Leap/Source/Library/RangeLevel
//add define access in module
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 3:07p
//Created in $/Leap/Source/Library/RangeLevel
//file added for range level masters
//


?>
