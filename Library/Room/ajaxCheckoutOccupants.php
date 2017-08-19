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
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','RoomAllocation');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

   require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
   $roomManager = RoomAllocationManager::getInstance();
   
   $studentString=trim($REQUEST_DATA['studentString']);
   if($studentString==''){
       echo 'Required Paramertes Missing';
       die;
   }
   
   $studentInfoArray=explode('!',$studentString);
   $count=count($studentInfoArray);
   
   if(SystemDatabaseManager::getInstance()->startTransaction()) {
     
     for($i=0;$i<$count;$i++){
       $info=explode('~',$studentInfoArray[$i]);
       $studentId=$info[0];
       $hostelId=$info[1];
       $roomId=$info[2];
       if($studentId=='' or $hostelId=='' or $roomId==''){
          echo 'Required Paramertes Missing';
          die;
       }
       //update hostel_student table
       $ret=$roomManager->checkOutOccupants($studentId,$roomId);
       if($ret==false){
           echo FAILURE;
           die;
       }
       //update student table
       $ret=$roomManager->deallocateHostelRoom($studentId);
       if($ret==false){
           echo FAILURE;
           die;
       }
    }
    
    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
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
   

//$History : $
?>