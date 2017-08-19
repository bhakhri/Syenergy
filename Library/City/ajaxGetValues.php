<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['cityId'] ) != '') {
    require_once(MODEL_PATH . "/CityManager.inc.php");
    $foundArray = CityManager::getInstance()->getCity(' WHERE cityId="'.$REQUEST_DATA['cityId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
?>

<?php
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/City
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:35p
//Updated in $/Leap/Source/Library/City
//Added access rules
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/28/08    Time: 11:23a
//Updated in $/Leap/Source/Library/City
//Added AjaxList Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:04p
//Updated in $/Leap/Source/Library/City
//Completed Comment Insertion
?>