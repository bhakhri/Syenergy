<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A ROOM TYPE 
//
//
// Author : Gurkeerat Sidhu
// Created on : (19.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomTypeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
       if ($errorMessage == '' && (!isset($REQUEST_DATA['roomType']) || trim($REQUEST_DATA['roomType']) == '')) {
        $errorMessage .= ENTER_ROOM_TYPE1."\n";    
    }
      if ($errorMessage == '' && (!isset($REQUEST_DATA['abbr']) || trim($REQUEST_DATA['abbr']) == '')) {
        $errorMessage .= ENTER_ABBR."\n";    
    }
	  if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RoomTypeManager.inc.php");
        $foundArray = RoomTypeManager::getInstance()->getRoomType(' WHERE (UCASE(roomType) = "'.add_slashes(trim(strtoupper($REQUEST_DATA['roomType']))).'" OR UCASE(abbr)="'.add_slashes(strtoupper($REQUEST_DATA['abbr'])).'") AND roomTypeId!='.$REQUEST_DATA['roomTypeId']);
                 if(trim($foundArray[0]['abbr'])=='') {  //DUPLICATE CHECK  
                   $returnStatus = RoomTypeManager::getInstance()->editRoomType($REQUEST_DATA['roomTypeId']);
                        if($returnStatus === false) {
                            echo FAILURE;
                        }
                        else {
                            echo SUCCESS;           
                        }
                    }
                    else {
                       if(trim(strtoupper($foundArray[0]['abbr']))==trim(strtoupper($REQUEST_DATA['abbr']))){ 
                           echo ABBR_EXIST;
                           die;
                       }
                       elseif(trim(strtoupper($foundArray[0]['roomType']))==trim(strtoupper($REQUEST_DATA['roomType']))){
                           echo ROOM_TYPE_EXIST;
                           die;
                       }
                    }
    }
    else {
        echo $errorMessage;
    }

 
?>
