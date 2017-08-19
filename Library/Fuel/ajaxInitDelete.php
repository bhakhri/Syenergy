<?php
//-------------------------------------------------------
// Purpose: To delete Fuel detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FuelMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['fuelId']) || trim($REQUEST_DATA['fuelId']) == '') {
        $errorMessage = 'Invalid Fuel Uses Record';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FuelManager.inc.php");
        $transportManager =  FuelManager::getInstance();
        
        $foundArray = FuelManager::getInstance()->getFuel(' AND fuelId="'.$REQUEST_DATA['fuelId'].'"');
        if(is_array($foundArray) && count($foundArray)>0 ) {
         $foundArray2=FuelManager::getInstance()->getMaxFuelId(' WHERE busId="'.$foundArray[0]['busId'].'"');
        
         if($foundArray2[0]['fuelId']==$REQUEST_DATA['fuelId']){ //if it is last record===Allow Delete
           if($transportManager->deleteFuel($REQUEST_DATA['fuelId']) ) {
              echo DELETE;
           }
         else {
               echo DEPENDENCY_CONSTRAINT;
         }
        }
        else{
            echo -1;
        }
       }
     }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/15/10    Time: 3:41p
//Updated in $/Leap/Source/Library/Fuel
//solved query error during fuel
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