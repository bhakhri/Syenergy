<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of course resource
//
// Author : Dipanjan Bbhattacharjee
// Created on : (04.11.2008 ) 
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
    
    
      /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND ( description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceUrl LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    
    
     ////////////   
    $totalArray          = $teacherManager->getTotalResource($filter);
    $resourceRecordArray = $teacherManager->getResourceList($filter,$limit);
    
// $History: initCourseResourceList.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/04/08   Time: 11:20a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Upload Resource" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:45p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created CourseResource Module
?>