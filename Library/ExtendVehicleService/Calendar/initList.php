<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "city" TABLE AND DELETION AND PAGING
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    //Paging code goes here
    require_once(MODEL_PATH . "/CalendarManager.inc.php");
    $calendarManager = CalendarManager::getInstance();
    define('MANAGEMENT_ACCESS',1);
    define('MODULE','AddEvent');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
    
        
    /////////////////////////
    // to limit records per page 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
    
    $filter = " AND ( $REQUEST_DATA[sdate] >=startDate AND $REQUEST_DATA[sdate]<=endDate) ";
    
     ////////////   
    $totalArray = $calendarManager->getTotalEvent($filter);
    $eventRecordArray = $calendarManager->getEventList($filter,$limit);
    
?>

<?php
// $History: initList.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/03/10    Time: 3:32p
//Updated in $/LeapCC/Library/Calendar
//access permission updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Calendar
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/08    Time: 7:19p
//Updated in $/Leap/Source/Library/Calendar
//Created Calendar(event) module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/03/08    Time: 12:34p
//Created in $/Leap/Source/Library/Calendar
//Initial Checkin
?>