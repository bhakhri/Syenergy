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

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BlockCourse');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

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
       $filter = ' AND (bl.blockName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bl.abbreviation LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR bi.buildingName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'blockName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $blockManager->getTotalBlock($filter);
    $blockRecordArray = $blockManager->getBlockList($filter,$limit,$orderBy);
    $cnt = count($blockRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $blockRecordArray[$i]['blockId'] , 'srNo' => ($records+$i+1) ),$blockRecordArray[$i]);

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
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Library/Block
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Block
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:41p
//Updated in $/Leap/Source/Library/Block
//Added access rules
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
