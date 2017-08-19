<?php
//-------------------------------------------------------------------------------------------------------------- 
// Purpose: To store the records of block in array from the database, pagination and search, delete 
// functionality
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------- 

    require_once(MODEL_PATH . "/BlockManager.inc.php");
    $blockManager = BlockManager::getInstance();

    /////////////////////////
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (bl.blockName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bl.abbreviation LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    ////////////
    
    $totalArray = $blockManager->getTotalBlock($filter);
    $blockRecordArray = $blockManager->getBlockList($filter,$limit);
// for VSS
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Block
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/11/08    Time: 12:43p
//Updated in $/Leap/Source/Library/Block
//Created "Block" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 7:05p
//Created in $/Leap/Source/Library/Block
//Initial Checkin
?>