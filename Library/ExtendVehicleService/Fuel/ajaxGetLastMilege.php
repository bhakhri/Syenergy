<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE BUSSTOP LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FuelMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['busId'] ) != '') {
    require_once(MODEL_PATH . "/FuelManager.inc.php");
    $foundArray = FuelManager::getInstance()->getLastMilege(' WHERE busId="'.$REQUEST_DATA['busId'].'" AND dated <= "'.$REQUEST_DATA['fdate'].'"');
    $foundArray2 = FuelManager::getInstance()->getLastFuelRecord(' WHERE busId="'.$REQUEST_DATA['busId'].'" AND dated <= "'.$REQUEST_DATA['fdate'].'"');
    
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        //echo json_encode($foundArray[0]);
        if($foundArray2[0]['litres']!='' and $foundArray2[0]['dated']!=''){
         echo $foundArray[0]['currentMilege'].'~~~'.$foundArray2[0]['litres'].' litres fuel was last filled on '.UtilityManager::formatDate($foundArray2[0]['dated']);
        }
       else{
           echo $foundArray[0]['currentMilege'];
       } 
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetLastMilege.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Library/Fuel
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/Fuel
//Updated fleet mgmt file in Leap 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/04/09   Time: 10:37
//Updated in $/SnS/Library/Fuel
//Done bug fixing
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:36
//Updated in $/SnS/Library/Fuel
//Enhanced fuel master
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:04
//Created in $/SnS/Library/Fuel
//Enhanced fuel master
?>