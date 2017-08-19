<?php
//-------------------------------------------------------
// THIS FILE IS USED TO GET ALL INFORMATION FROM "study_period" TABLE AND DELETION AND PAGING
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
    require_once(MODEL_PATH . "/StudyPeriodManager.inc.php");
    $studyPeriodManager = StudyPeriodManager::getInstance();
    
    
        
    /////////////////////////
    // to limit records per page 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    
       //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (stp.periodName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
     ////////////   
    $totalArray = $studyPeriodManager->getTotalStudyPeriod($filter);
    $studyPeriodRecordArray = $studyPeriodManager->getStudyPeriodList($filter,$limit);
    
?>

<?php
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudyPeriod
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/02/08    Time: 6:48p
//Updated in $/Leap/Source/Library/StudyPeriod
//Created "StudyPeriod Master"  Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/02/08    Time: 4:00p
//Created in $/Leap/Source/Library/StudyPeriod
//Initial Checkin

?>