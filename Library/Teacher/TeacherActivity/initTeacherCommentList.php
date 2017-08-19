<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices
//
// Author : Dipanjan Bbhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    
    
        
    /////////////////////////
    // to limit records per page 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    
    //no search functionality needed
    $filter="";     
    
     ////////////   
    $totalArray = $teacherManager->getTotalTeacherComment($filter);
    $teacherCommentRecordArray = $teacherManager->getTeacherCommentList($filter,$limit);

    // $History: initTeacherCommentList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:56p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>