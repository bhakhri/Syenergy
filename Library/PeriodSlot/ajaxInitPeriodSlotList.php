<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of leave details in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (20.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PeriodSlotMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true); 
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/PeriodSlotManager.inc.php");
    $periodSlotManager = PeriodSlotManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
        }
        elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
        }
	    else {
		   $type=-1;
	    }

        $filter = ' WHERE ( slotName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR slotAbbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR isActive LIKE "'.$type.'")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'slotName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray             = $periodSlotManager->getTotalPeriodSlotDetail($filter);
    $periodSlotRecordArray = $periodSlotManager->getPeriodSlotDetail($filter,$limit,$orderBy);
    $cnt = count($periodSlotRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $periodSlotRecordArray[$i]['periodSlotId'] , 'srNo' => ($records+$i+1) ),$periodSlotRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitPeriodSlotList.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/28/09    Time: 1:40p
//Updated in $/LeapCC/Library/PeriodSlot
//Gurkeerat: resolved issue 1313
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/06/09    Time: 5:10p
//Updated in $/LeapCC/Library/PeriodSlot
//search on abbr.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/29/09    Time: 6:41p
//Updated in $/LeapCC/Library/PeriodSlot
//fixed bug nos.0000737, 0000736,0000734,0000735, 0000585, 0000584,
//0000583
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:33p
//Created in $/LeapCC/Library/PeriodSlot
//to get the file add, delete & edit records
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:16p
//Created in $/Leap/Source/Library/PeriodSlot
//used the file for paging & filteration
//
?>
