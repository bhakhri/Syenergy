<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To get values of parent quota name from the database
//
// Author : Jaineesh
// Created on : (17.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','QuotaMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/QuotaManager.inc.php");
    $foundArray = QuotaManager::getInstance()->getQuota();
    $cnt=count($foundArray);
    if ($cnt>0){
        echo json_encode($foundArray);
    }
    else {
        echo 0; // no record found
   }

// $History: ajaxGetParentQuota.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Quota
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:49p
//Updated in $/Leap/Source/Library/Quota
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/17/08    Time: 4:56p
//Created in $/Leap/Source/Library/Quota
//Initial checkin
?>

