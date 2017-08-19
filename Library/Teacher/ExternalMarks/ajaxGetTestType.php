<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE testType List
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['subjectId'] ) != '' and trim($REQUEST_DATA['classId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    if(isset($REQUEST_DATA['conductingAuthority'])){
        if($REQUEST_DATA['conductingAuthority']==1 or $REQUEST_DATA['conductingAuthority']==2){
         $foundArray = TeacherManager::getInstance()->getTestType($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId']," AND conductingAuthority=".$REQUEST_DATA['conductingAuthority']); 
        } 
    }
    else{
      $foundArray = TeacherManager::getInstance()->getTestType($REQUEST_DATA['subjectId']);
    } 
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetTestType.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/06/08    Time: 10:41a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/25/08    Time: 2:55p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Modified so that it can be used in assignment,external and general
//enter 
//marks modules based on conducting authority
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/24/08    Time: 11:58a
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>