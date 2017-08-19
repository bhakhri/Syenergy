<?php
//-------------------------------------------------------
// Purpose: To get values of parent group name from the database
//
// Author : Jaineesh
// Created on : (14.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeads');     
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/FeeHeadManager.inc.php");
    $foundArray = FeeHeadManager::getInstance()->getFeeHeadName();
    $cnt=count($foundArray);
    if ($cnt>0){
        for ($i=0;$i<$cnt;$i++){
            $json_val .= json_encode($foundArray[$i]).($i==($cnt-1) ? '' : ',');
        }
        echo '{"info" : ['.$json_val.']}';
    }
    else {
        echo 0; // no record found
   }

// $History: ajaxInitFeeHeadName.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeHead
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeHead
//Define Module, Access  Added
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/15/08    Time: 6:53p
//Created in $/Leap/Source/Library/FeeHead
//added a new file for parent head dropdown fields
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/15/08    Time: 6:43p
//Created in $/Leap/Source/Library/Group
//get the group name into dropdownlist of parent group


?>

