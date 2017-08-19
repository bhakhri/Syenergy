<?php

//-------------------------------------------------------
// Purpose: To add room detail
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/RoomManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

define('MODULE','RoomsMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if ((!isset($REQUEST_DATA['roomName']) || trim($REQUEST_DATA['roomName']) == '')) {
        $errorMessage .=  ENTER_ROOM_NAME. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomAbbreviation']) || trim($REQUEST_DATA['roomAbbreviation']) == '')) {
        $errorMessage .=  ENTER_ROOM_ABBR. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomType']) || trim($REQUEST_DATA['roomType']) == '')) {
        $errorMessage .=  ENTER_ROOM_TYPE. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['blockName']) || trim($REQUEST_DATA['blockName']) == '')) {
        $errorMessage .= CHOOSE_BLOCK_NAME. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['capacity']) || trim($REQUEST_DATA['capacity']) == '')) {
        $errorMessage .= ENTER_ROOM_CAPACITY. '<br/>';
    }
    
    //check for institute validation
    $instituteId=trim($REQUEST_DATA['instituteId']);
    if($instituteId==''){
        echo 'Invalid Institute';
        die;
    }
    $institutesArray=CommonQueryManager::getInstance()->checkInstitutes(' WHERE instituteId IN ('.$instituteId.')');
    $institutesArrayIds=explode(',',UtilityManager::makeCSList($institutesArray,'instituteId'));
    $uniqueInstituteIds=array_unique(explode(',',$instituteId));
    if(count(array_diff($uniqueInstituteIds,$institutesArrayIds))>0){
        echo 'Invalid Institute';
        die;
    }
    
    if (trim($errorMessage) == '') {
        //DUPLICATE CHECK
		$foundArray = RoomManager::getInstance()->getRoom(' AND ( ( UCASE(roomAbbreviation)="'.add_slashes(trim($REQUEST_DATA['roomAbbreviation'])).'" OR UCASE(roomName)="'.add_slashes(trim($REQUEST_DATA['roomName'])).'" ) AND b.blockId='.$REQUEST_DATA['blockName'].' AND ri.instituteId IN ('.$REQUEST_DATA['instituteId'].'))');
 	    if(trim($foundArray[0]['roomAbbreviation'])=='') {
            //starting transaction............ 
            if(SystemDatabaseManager::getInstance()->startTransaction()) {
               $returnStatus = RoomManager::getInstance()->addRoom();
               if($returnStatus===false){
                   echo FAILURE;
                   die;
               }
              //fetching last inserted room id 
              $roomId=SystemDatabaseManager::getInstance()->lastInsertId();
              $cnt=count($uniqueInstituteIds);
              //if ordinary user comes in then,only one institute can be mapped.so......
              if($sessionHandler->getSessionVariable('RoleId')!=1){
                  if($cnt>1){
                    echo 'Invalid Institute';
                    die;  
                  }
              }
              $str='';
              //building the multiple insertion query...........
              for($i=0;$i<$cnt;$i++){
                  if($str!=''){
                      $str .=',';
                  }
                  $str .=" ( $roomId,$uniqueInstituteIds[$i] ) ";
              }
              if($str!=''){
                //do the mapping.........
                $ret=RoomManager::getInstance()->doRoomInstituteMapping($str);
                if($ret===false){
                   echo FAILURE;
                   die; 
                }
              }
              else{
                echo FAILURE;
                die;  
              }
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                  //if all goes well,echo "Sucess"
                   echo SUCCESS;
                   die;
              }
              else {
                   echo FAILURE;
                   die;
               }
              }
              else {
                   echo FAILURE;
                   die;
              }

		}
		else {
		   if( strtoupper(trim($foundArray[0]['roomName']))==strtoupper(trim($REQUEST_DATA['roomName']))){
            echo ROOM_NAME_EXIST;
            die;
           }
           if( strtoupper(trim($foundArray[0]['roomAbbreviation']))==strtoupper(trim($REQUEST_DATA['roomAbbreviation']))){
            echo ROOM_ALREADY_EXIST;
            die;
		   }
           echo ROOM_NAME_EXIST;
           die;
        }   
     }
    else {
        echo $errorMessage;
    }

//$History : $
?>