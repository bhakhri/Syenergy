<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute events
//
// Author : Dipanjan Bbhattacharjee
// Created on : (28.07.2008 )
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
       $filter = ' AND (adm.subject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR adm.message LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    

    
    $totalArray = $teacherManager->getTotalAdminMessage($filter);
    $msgRecordArray = $teacherManager->getAdminMessageList($filter,$limit,' adm.dated DESC');
    
// $History: initAdminMessagesList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/29/08    Time: 6:22p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/24/08    Time: 1:36p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Corrected date range in event showing criteria
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/25/08    Time: 1:14p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/30/08    Time: 1:54p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>