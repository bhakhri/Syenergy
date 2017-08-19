<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (24.11.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TyreRetreading');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $tyreRetreadingManager = TyreRetreadingManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (tm.tyreNumber LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR b.busNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tr.retreadingDate LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR tr.totalRun LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tyreNumber';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $tyreRetreadingManager->getTotalTyreRetreading($filter);
    $tyreRetreadingRecordArray = $tyreRetreadingManager->getTyreRetreadingList($filter,$limit,$orderBy);
    $cnt = count($tyreRetreadingRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $tyreRetreadingRecordArray[$i]['retreadingDate'] = UtilityManager::formatDate($tyreRetreadingRecordArray[$i]['retreadingDate']);
        $valueArray = array_merge(array('action' => $tyreRetreadingRecordArray[$i]['retreadingId'] , 'srNo' => ($records+$i+1) ),$tyreRetreadingRecordArray[$i]);

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
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Library/TyreRetreading
//fixed bug on fleet management
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/05/09   Time: 10:33a
//Updated in $/Leap/Source/Library/TyreRetreading
//Modification  in code for search
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/04/09   Time: 3:35p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
//
?>