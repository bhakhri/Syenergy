<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "institite" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    //Paging code goes here
    require_once(MODEL_PATH . "/UniversityManager.inc.php");
    $universityManager = UniversityManager::getInstance();
    
    /////paginatio   
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE; 
    
    
    /////filter 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND ( un.universityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR un.universityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    
    ///////////
    $totalArray = $universityManager->getTotalUniversity($filter);
    $universityRecordArray = $universityManager->getUniversityList($filter,$limit);
    
?>

<?php
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/University
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Library/University
//Added Image upload functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/09/08    Time: 11:09a
//Created in $/Leap/Source/Library/University
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/26/08    Time: 2:55p
//Updated in $/Leap/Source/Library/University
//Added AjaxEnabled Delete Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:19p
//Updated in $/Leap/Source/Library/University
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:30p
//Created in $/Leap/Source/Library/University
//Initial Checkin
?>