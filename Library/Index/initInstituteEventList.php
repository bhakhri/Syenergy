<?php
//---------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute events
//
// Author : Rajeev Aggarwal
// Created on : (13.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
 
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();
    
    
        
    /////////////////////////
    // to limit records per page 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
      /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (e.eventTitle LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
     ////////////   
    $totalArray = $dashboardManager->getTotalEvent($filter);
    $eventRecordArray = $dashboardManager->getEventList($filter,$limit);
// $History: initInstituteEventList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Index
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:36p
//Created in $/Leap/Source/Library/Index
//intial checkin
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/30/08    Time: 1:54p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>