<?php
//-------------------------------------------------------
// Purpose: To store the records of fee cycle in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeCycleMaster'); 
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeeCycleManager.inc.php");
    $feeCycleManager = FeeCycleManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (cycleName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        DATE_FORMAT(fromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        DATE_FORMAT(toDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                        cycleAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';          
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'cycleName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $feeCycleManager->getTotalFeeCycle($filter);
    $feeCycleRecordArray = $feeCycleManager->getFeeCycleList($filter,$limit,$orderBy);
    $cnt = count($feeCycleRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $feeCycleRecordArray[$i]['fromDate']=UtilityManager::formatDate($feeCycleRecordArray[$i]['fromDate']);
        $feeCycleRecordArray[$i]['toDate']=UtilityManager::formatDate($feeCycleRecordArray[$i]['toDate']);
        $valueArray = array_merge(array('action' => $feeCycleRecordArray[$i]['feeCycleId'] , 'srNo' => ($records+$i+1) ),$feeCycleRecordArray[$i]);

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
//User: Parveen      Date: 8/08/09    Time: 3:54p
//Updated in $/LeapCC/Library/FeeCycle
//bug no. 951 (date format updated)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeCycle
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeCycle
//Define Module, Access  Added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/02/08    Time: 10:53a
//Updated in $/Leap/Source/Library/FeeCycle
//added instituteId
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/28/08    Time: 1:44p
//Created in $/Leap/Source/Library/FeeCycle
//ajax functions used for delete, edit, update, search, add 


  
?>
