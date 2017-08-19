<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE testType List
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['subjectId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    if(isset($REQUEST_DATA['conductingAuthority'])){
        if($REQUEST_DATA['conductingAuthority']==1 or $REQUEST_DATA['conductingAuthority']==2){
         $foundArray = ScTeacherManager::getInstance()->getTestType($REQUEST_DATA['subjectId']," AND conductingAuthority=".$REQUEST_DATA['conductingAuthority']); 
        } 
    }
    else{
      $foundArray = ScTeacherManager::getInstance()->getTestType($REQUEST_DATA['subjectId']);
    } 
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: scAjaxGetTestType.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>