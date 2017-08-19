<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Fine Category LIST
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineCategoryMaster');
define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['fineCategoryId'] ) != '') {
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $foundArray = FineManager::getInstance()->getFineCategory(' WHERE fineCategoryId="'.$REQUEST_DATA['fineCategoryId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/07/09    Time: 16:46
//Updated in $/LeapCC/Library/Fine
//Changes html and model files names in "Fine  Category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/07/09    Time: 15:30
//Created in $/LeapCC/Library/Fine
//Added files for "fine_category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:07
//Created in $/LeapCC/Library/FineCategory
//Created "Fine Category Master" module
?>