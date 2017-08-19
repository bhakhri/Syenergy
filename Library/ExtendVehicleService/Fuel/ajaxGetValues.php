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
define('MODULE','FuelMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['fuelId'] ) != '') {
    require_once(MODEL_PATH . "/FuelManager.inc.php");
    $foundArray = FuelManager::getInstance()->getFuel(' AND fuelId="'.$REQUEST_DATA['fuelId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        $foundArray2=FuelManager::getInstance()->getMaxFuelId(' WHERE busId="'.$foundArray[0]['busId'].'"');
        
        if($foundArray2[0]['fuelId']==$REQUEST_DATA['fuelId']){ //if it is last record===Allow Edit
          echo json_encode($foundArray[0]);
        }
        else{
             echo -1;        //do not allow to edit
        }
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Library/Fuel
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Library/Fuel
//Updated fleet mgmt file in Leap 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:04
//Updated in $/SnS/Library/Fuel
//Enhanced fuel master
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 18:37
//Created in $/SnS/Library/Fuel
//Created Fuel Master
?>