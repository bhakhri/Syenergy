<?php
//-------------------------------------------------------
// Purpose: To store the records of states in array from the database, pagination and search, delete 
// functionality
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/StatesManager.inc.php");
    $stateManager = StatesManager::getInstance();

    /////////////////////////
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (st.stateName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR st.stateCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    ////////////
    
    $totalArray = $stateManager->getTotalStates($filter);
    $stateRecordArray = $stateManager->getStateList($filter,$limit);

// for VSS
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/States
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 8/27/08    Time: 3:45p
//Updated in $/Leap/Source/Library/States
//optimized code and  removed trailing spaces
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