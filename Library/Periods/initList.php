<?php

//-------------------------------------------------------
// THIS FILE IS USED TO SEARCH THE PERIOD AND GIVE THE LIST
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
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
       $filter = ' AND (pr.periodNumber LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ttl.labelName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';    
    }
    
    ////////////
    
    $totalArray = $periodsManager->getTotalPeriods($filter);
    $periodsRecordArray = $periodsManager->getPeriodsList($filter,$limit);

// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:37p
//Created in $/LeapCC/Library/Periods
//get existing period files in leap
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/25/08   Time: 5:44p
//Updated in $/Leap/Source/Library/Periods
//add new field time table label Id
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/28/08    Time: 6:18p
//Updated in $/Leap/Source/Library/Periods
//modified in indentation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/08    Time: 12:58p
//Updated in $/Leap/Source/Library/Periods
//modified in coding for delete
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:17p
//Updated in $/Leap/Source/Library/Periods
?>