<?php
//-------------------------------------------------------
// Purpose: To store the records of group in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/GroupManager.inc.php");
    $groupManager = GroupManager::getInstance();

    /////////////////////////
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (c.groupName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR c.groupShort LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    ////////////
    
    $totalArray = $groupManager->getTotalGroup($filter);
    $groupRecordArray = $groupManager->getGroupList($filter,$limit);
       

// for VSS
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Group
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:04p
//Updated in $/Leap/Source/Library/Group
//modified for edit 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/03/08    Time: 7:04p
//Created in $/Leap/Source/Library/Group
//containing functions for add, edit, delete or search

?>