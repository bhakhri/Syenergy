<?php
//-------------------------------------------------------
// Purpose: To delete room detail
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','RoomsMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['roomId']) || trim($REQUEST_DATA['roomId']) == '') {
        $errorMessage = INVALID_ROOM;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/RoomManager.inc.php");
        $roomManager =  RoomManager::getInstance();
        ///checking of whether this room is used time_table or not
        $found=$roomManager->checkInTimeTable(' AND r.roomId='.$REQUEST_DATA['roomId']);
        if($found[0]['found']!=''){
          echo DEPENDENCY_CONSTRAINT;
          die;  
        }
        
        //starting transaction............ 
        if(SystemDatabaseManager::getInstance()->startTransaction()) {
          $returnStatus = $roomManager->deleteRoomInstituteMapping($REQUEST_DATA['roomId']);
          if($returnStatus===false){
                   echo FAILURE;
                   die;
          }
         
         if($roomManager->deleteRoom($REQUEST_DATA['roomId'])===false) {
            echo DEPENDENCY_CONSTRAINT;
            die;
         }
              
          if(SystemDatabaseManager::getInstance()->commitTransaction()) {
              //if all goes well,echo "DELETE"
               echo DELETE;
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
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/08/09   Time: 14:17
//Updated in $/LeapCC/Library/Room
//Added the check : If a room is used in time table then it cannot be
//deleted and cannot be de-allocated from the institute with which it is
//associated in time table
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 14/08/09   Time: 16:43
//Updated in $/LeapCC/Library/Room
//Done enhancement in "Room" module---added room and institute mapping so
//that one room can be shared across institutes
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Room
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/Room
//define access module
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/26/08    Time: 4:04p
//Updated in $/Leap/Source/Library/Room
//modified in message
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/02/08    Time: 8:26p
//Created in $/Leap/Source/Library/Room
//add ajax delete function for deletion of room data

?>

