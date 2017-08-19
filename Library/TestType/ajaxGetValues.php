<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE TESTTYPE LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestTypesMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['testtypeId'] ) != '') {
    require_once(MODEL_PATH . "/TestTypeManager.inc.php");
    $foundArray = TestTypeManager::getInstance()->getTestType(' WHERE testTypeId="'.$REQUEST_DATA['testtypeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:10a
//Updated in $/Leap/Source/Library/TestType
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/01/08    Time: 1:04p
//Updated in $/Leap/Source/Library/TestType
//Modified DataBase Column names
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/30/08    Time: 11:30a
//Updated in $/Leap/Source/Library/TestType
//Added AjaxList & AjaxSearch Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:26a
//Updated in $/Leap/Source/Library/TestType
//Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:41p
//Created in $/Leap/Source/Library/TestType
//Initial CheckIn
?>