<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE List room 
//
//
// Author : Jaineesh
// Created on : (2.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['roomId'] ) != '') {
    require_once(MODEL_PATH . "/RoomManager.inc.php");
    $foundArray  = RoomManager::getInstance()->getRoom(' AND r.roomId="'.$REQUEST_DATA['roomId'].'"');
   // if($sessionHandler->getSessionVariable('RoleId')==1){
     $foundArray2 = RoomManager::getInstance()->getInstituteRoomMapping(' AND r.roomId="'.$REQUEST_DATA['roomId'].'"');
   // }
    if(is_array($foundArray) && count($foundArray)>0 ) {
      //if($sessionHandler->getSessionVariable('RoleId')==1){  
        echo json_encode($foundArray[0]).'~!~!~'.json_encode($foundArray2);
      //}
      //else{
         // echo json_encode($foundArray[0]);
      //}
    }
    else {
        echo 0;
    }
}

//$History : $ 

?>

