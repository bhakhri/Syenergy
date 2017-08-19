<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRepairCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['repairId'] ) != '') {
    require_once(MODEL_PATH . "/BusRepairManager.inc.php");
    $foundArray = BusRepairManager::getInstance()->getBusRepair(' WHERE repairId="'.$REQUEST_DATA['repairId'].'"');
    //fetching repair action details
    $foundArray2 = BusRepairManager::getInstance()->getBusRepairedAction(' WHERE repairId="'.$REQUEST_DATA['repairId'].'"');
    
    if(is_array($foundArray) && count($foundArray)>0 ){  
      if(is_array($foundArray2) && count($foundArray2)>0 ) {    
        echo json_encode($foundArray[0]).'~!~'.json_encode($foundArray2);
      }
     else{
         echo json_encode($foundArray[0]);
     } 
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/06/09   Time: 12:11
//Updated in $/LeapCC/Library/BusRepair
//Replicated bus repair module's enhancements from leap to leapcc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/BusRepair
//Updated fleet mgmt file in Leap 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/04/09    Time: 15:15
//Updated in $/SnS/Library/BusRepair
//Enhanced bus repair module by adding action (Engine Oil Change,Gear Box
//Oil Change etc) and their due dates
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 12:55
//Created in $/SnS/Library/BusRepair
//Created Bus Repair Module
?>