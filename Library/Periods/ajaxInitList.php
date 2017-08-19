<?php
//-------------------------------------------------------
// Purpose: To store the records of periods in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','PeriodsMaster');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/PeriodsManager.inc.php");
    $periodsManager = PeriodsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  

   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (pr.periodNumber LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ps.slotName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'slotName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $periodsManager->getTotalPeriods($filter);
    $periodsRecordArray = $periodsManager->getPeriodsList($filter,$limit,$orderBy);
    $cnt = count($periodsRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $periodsRecordArray[$i]['periodId'] , 'srNo' => ($records+$i+1) ),$periodsRecordArray[$i]);

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
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:46p
//Updated in $/LeapCC/Library/Periods
//fixed bug nos.0001611, 0001632, 0001612, 0001600, 0001599, 0001598,
//0001594
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/28/09    Time: 1:29p
//Updated in $/LeapCC/Library/Periods
//Resolved issue 1317
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:37p
//Created in $/LeapCC/Library/Periods
//get existing period files in leap
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:19p
//Updated in $/Leap/Source/Library/Periods
//modified to get slot name
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:10p
//Updated in $/Leap/Source/Library/Periods
//add define access in module
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/30/08   Time: 11:27a
//Updated in $/Leap/Source/Library/Periods
//modified
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/25/08   Time: 5:44p
//Updated in $/Leap/Source/Library/Periods
//add new field time table label Id
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/28/08    Time: 6:18p
//Updated in $/Leap/Source/Library/Periods
//modified in indentation
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/18/08    Time: 11:18a
//Updated in $/Leap/Source/Library/Periods
//modified for time
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/30/08    Time: 1:18p
//Created in $/Leap/Source/Library/Periods
//add new ajax list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:41a
//Created in $/Leap/Source/Library/States
//initial check in, added ajax state list functionality
?>