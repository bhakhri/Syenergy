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
    define('MODULE','QuotaMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/QuotaManager.inc.php");
    $quotaManager = QuotaManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (qt.quotaName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR qt.quotaAbbr LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR p.quotaName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'parentQuota';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $quotaManager->getTotalQuota($filter);
    $quotaRecordArray = $quotaManager->getQuotaList($filter,$limit,$orderBy);
    $cnt = count($quotaRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add quotaId in actionId to populate edit/delete icons in User Interface
        if(trim($quotaRecordArray[$i]['parentQuota'])==''){
            $quotaRecordArray[$i]['parentQuota']=NOT_APPLICABLE_STRING;
        }
        $valueArray = array_merge(array('action' => $quotaRecordArray[$i]['quotaId'] , 'srNo' => ($records+$i+1) ),$quotaRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 12:55
//Updated in $/LeapCC/Library/Quota
//Corrected quota master
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Quota
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:49p
//Updated in $/Leap/Source/Library/Quota
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/02/08    Time: 11:44a
//Updated in $/Leap/Source/Library/Quota
//Removed State Field from the quota master
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:40p
//Updated in $/Leap/Source/Library/Quota
//Added AjaxListing Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/28/08    Time: 1:13p
//Created in $/Leap/Source/Library/Quota
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 12:58p
//Updated in $/Leap/Source/Library/Degree
//Added AjaxList Functinality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:25a
//Created in $/Leap/Source/Library/Degree
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Library/City
//Added AjaxList Functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/27/08    Time: 5:08p
//Created in $/Leap/Source/Library/City
//Initial Checkin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
  
?>
