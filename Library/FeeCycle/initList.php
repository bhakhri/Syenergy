<?php
//-------------------------------------------------------
// Purpose: To store the records of fee cycle in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

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
       $filter = ' AND (cycleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR cycleAbbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" )';         
    }
    ////////////
    
    $totalArray = $feeCycleManager->getTotalFeeCycle($filter);
    $feeCycleRecordArray = $feeCycleManager->getFeeCycleList($filter,$limit);
    

// for VSS
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeCycle
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/28/08    Time: 1:44p
//Created in $/Leap/Source/Library/FeeCycle
//ajax functions used for delete, edit, update, search, add 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:34p
//Created in $/Leap/Source/Library/HostelRoom
//ajax functions of add, delete, edit & search
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:57p
//Updated in $/Leap/Source/Library/States
//removed delete code
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 6/13/08    Time: 4:53p
//Updated in $/Leap/Source/Library/States
//Added comments header and other action comments
    
?>