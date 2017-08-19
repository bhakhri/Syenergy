<?php
//-------------------------------------------------------
// Purpose: To store the records of lecture type in array from the database, pagination and search, delete 
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/LectureManager.inc.php");
    $lectureTypeManager = LectureTypeManager::getInstance();

    /////////////////////////
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' (lectureName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    ////////////
    
    $totalArray = $lectureTypeManager->getTotalLectureType($filter);
    $lecturetypeRecordArray = $lectureTypeManager->getLectureTypeList($filter,$limit);
    

// for VSS
// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Lecture
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/17/08    Time: 11:33a
//Updated in $/Leap/Source/Library/Lecture
//updated issue no 0000062,0000061,0000070
 
?>