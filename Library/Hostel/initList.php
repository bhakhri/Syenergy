<?php
//-------------------------------------------------------
// Purpose: To store the records of hostel in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/HostelManager.inc.php");
    $hostelManager = HostelManager::getInstance();

    /////////////////////////
    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (hostelName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hostelCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    ////////////
    
    $totalArray = $hostelManager->getTotalHostel($filter);
    $hostelRecordArray = $hostelManager->getHostelList($filter,$limit);
    

// for VSS
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 11:10a
//Created in $/LeapCC/Library/Hostel
//ajax files include add, delete, edit & list functions made by Jaineesh
//& modifications by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:30p
//Updated in $/Leap/Source/Library/Hostel
//modification in indentation
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:30p
//Created in $/Leap/Source/Library/Hostel
//ajax functions for add, edit, delete and search
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