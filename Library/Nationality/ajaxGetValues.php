<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE NATIONALITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['nationId'] ) != '') {
    require_once(MODEL_PATH . "/NationalityManager.inc.php");
    $foundArray = NationalityManager::getInstance()->getNationality(' WHERE nationId="'.$REQUEST_DATA['nationId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 'Nationality does not exist.';
    }
}
?>
<?php
  // $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Nationality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:12p
//Updated in $/Leap/Source/Library/Nationality
//Complted Comments
?>

