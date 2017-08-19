<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL "Slabs" table and display them
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/SlabsManager.inc.php");
    $slabsManager = SlabsManager::getInstance();
    
 
    /////pagination code    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
    ////filter code
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (sl.deliveredFrom LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sl.deliveredTo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    
    ////
    $totalArray = $slabsManager->getTotalSlabs($filter);
    $slabsRecordArray = $slabsManager->getSlabsList($filter,$limit);
    
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Slabs
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/24/08   Time: 12:12p
//Updated in $/Leap/Source/Library/Slabs
//Corrected search criteria
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/12/08    Time: 11:44a
//Created in $/Leap/Source/Library/Slabs
?>