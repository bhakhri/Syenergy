<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE QUOTA LIST
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
define('MODULE','QuotaMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['quotaId'] ) != '') {
    require_once(MODEL_PATH . "/QuotaManager.inc.php");
    $foundArray = QuotaManager::getInstance()->getQuota(' WHERE quotaId="'.$REQUEST_DATA['quotaId'].'"');
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
//Created in $/LeapCC/Library/Quota
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:49p
//Updated in $/Leap/Source/Library/Quota
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/28/08    Time: 2:40p
//Updated in $/Leap/Source/Library/Quota
//Added AjaxListing Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:19p
//Updated in $/Leap/Source/Library/Quota
//Completed Comments Insertion
?>


