<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TransportStuffMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TransportStuffManager.inc.php");
    $tranportManager = TransportStuffManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE  ( name LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR stuffCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR dlNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR dlIssuingAuthority LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'name';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray     = $tranportManager->getTotalTransportStuff($filter);
    $transportRecordArray = $tranportManager->getTransportStuffList($filter,$limit,$orderBy);
    $cnt = count($transportRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        $transportRecordArray[$i]['dlExpiryDate'] = UtilityManager::formatDate($transportRecordArray[$i]['dlExpiryDate']);
        $transportRecordArray[$i]['joiningDate']  = UtilityManager::formatDate($transportRecordArray[$i]['joiningDate']);
        $transportRecordArray[$i]['stuffType']    = $transportStuffTypeArr[$transportRecordArray[$i]['stuffType']];
        
        $valueArray = array_merge(array('action' => $transportRecordArray[$i]['stuffId'] , 'srNo' => ($records+$i+1) ),$transportRecordArray[$i]);

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
//User: Dipanjan     Date: 8/27/09    Time: 1:44p
//Updated in $/LeapCC/Library/TransportStuff
//Gurkeerat: resolved issue 1300
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:37
//Created in $/LeapCC/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:01
//Created in $/Leap/Source/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Library/TransportStuff
//Created module Transport Stuff Master
?>
