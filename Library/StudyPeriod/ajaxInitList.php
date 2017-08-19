<?php
//-------------------------------------------------------
// Purpose: To store the records of StudyPeriod in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudyPeriodMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

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
       $filter = ' AND (stp.periodName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR stp.periodValue LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR per.periodicityName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'periodName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $studyPeriodManager->getTotalStudyPeriod($filter);
    $studyPeriodRecordArray = $studyPeriodManager->getStudyPeriodList($filter,$limit,$orderBy);
    $cnt = count($studyPeriodRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        if(strlen($studyPeriodRecordArray[$i]['periodName'])>30){
            $studyPeriodRecordArray[$i]['periodName']=substr($studyPeriodRecordArray[$i]['periodName'],0,30).'...';
        }  
        $valueArray = array_merge(array('action' => $studyPeriodRecordArray[$i]['studyPeriodId'] , 'srNo' => ($records+$i+1) ),$studyPeriodRecordArray[$i]);

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
//User: Dipanjan     Date: 10/07/09   Time: 13:53
//Updated in $/LeapCC/Library/StudyPeriod
//Corrected "period name" display
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Library/StudyPeriod
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudyPeriod
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:58a
//Updated in $/Leap/Source/Library/StudyPeriod
//Added access rules
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
