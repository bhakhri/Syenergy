<?php
//-------------------------------------------------------
// THIS FILE IS USED TO SEARCH THE GROUP TYPE AND GIVE THE LIST
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
    $groupTypeManager = GroupTypeManager::getInstance();
    
    
        //to limit records per page 
        
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    /// Search filter ///// 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (groupTypeName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR groupTypeCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    ////////
    $totalArray = $groupTypeManager->getTotalGroupType($filter);
    $groupTypeRecordArray = $groupTypeManager->getGroupTypeList($filter,$limit);

// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/GroupType
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/28/08    Time: 2:58p
//Updated in $/Leap/Source/Library/GroupType
//modification in indentation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/08    Time: 5:22p
//Updated in $/Leap/Source/Library/GroupType
//modified for delete function
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:37p
//Updated in $/Leap/Source/Library/GroupType
?>