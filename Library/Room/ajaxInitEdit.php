
<?php
//-------------------------------------------------------
// Purpose: To update room table data
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
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomName']) || trim($REQUEST_DATA['roomName']) == '')) {
        $errorMessage .= ENTER_ROOM_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomAbbreviation']) || trim($REQUEST_DATA['roomAbbreviation']) == '')) {
        $errorMessage .= ENTER_ROOM_ABBR. '<br/>';
    }
    /*if ($errorMessage == '' && (!isset($REQUEST_DATA['roomTypeName']) || trim($REQUEST_DATA['roomTypeName']) == '')) {
        $errorMessage .= ENTER_ROOM_TYPE '<br/>';
    } */
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
	  $foundArray = RoomManager::getInstance()->getRoom(' AND ( ( UCASE(roomAbbreviation)="'.add_slashes(trim($REQUEST_DATA['roomAbbreviation'])).'" OR UCASE(roomName)="'.add_slashes(trim($REQUEST_DATA['roomName'])).'" ) AND b.blockId='.$REQUEST_DATA['blockName'].' AND ri.instituteId IN ('.$REQUEST_DATA['instituteId'].')) AND r.roomId!='.$REQUEST_DATA['roomId']);
      if(trim($foundArray[0]['roomAbbreviation'])=='') {
	    //starting transaction............ 
        if(SystemDatabaseManager::getInstance()->startTransaction()) {
           $returnStatus = RoomManager::getInstance()->editRoom($REQUEST_DATA['roomId']);
           if($returnStatus===false){
               echo FAILURE;
               die;
           }
          //if($sessionHandler->getSessionVariable('RoleId')==1) {
                ///checking of whether this room is used time_table or not
                $found=RoomManager::getInstance()->checkInTimeTable(' AND r.roomId='.$REQUEST_DATA['roomId']);
                $fl=0;
                if(is_array($found) and count($found)>0){
                    $found=explode(',',UtilityManager::MakeCSList($found,'found'));
                    $insCnt=count($found);
                    $instituteCodeStr='';
                    //global variable for institutes information
                    $instituteCodes=$sessionHandler->getSessionVariable('InstituteCodeArray');
                    for($i=0;$i<$insCnt;$i++){
                        if(!in_array($found[$i],$uniqueInstituteIds)){
                            $fl=1;
                            if($instituteCodeStr!=''){
                                $instituteCodeStr .=',  ';
                            }
                            $instituteCodeStr .=$instituteCodes[$found[$i]];
                        }
                    }
                    //user is trying to de-allocate institute which is used in time_table with that room
                    if($fl==1){
                      echo "This room is already used in following intitutes : \n".$instituteCodeStr;
                      die;  
                    }
                }
     
              $cnt=count($uniqueInstituteIds);
              $str='';
              //first delete the mapping
              $ret=RoomManager::getInstance()->deleteRoomInstituteMapping($REQUEST_DATA['roomId']);
              if($ret===false){
                echo FAILURE;
                die;  
              }
              
              $roomId=$REQUEST_DATA['roomId'];
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
          //}
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
    
    //$History: ajaxInitEdit.php $
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 4/19/10    Time: 3:48p
//Updated in $/LeapCC/Library/Room
//fixed bug no.0003289
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/03/09    Time: 7:33p
//Updated in $/LeapCC/Library/Room
//fixed bug nos.0001440, 0001433, 0001432, 0001423, 0001239, 0001406,
//0001405, 0001404
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/08/09   Time: 14:17
//Updated in $/LeapCC/Library/Room
//Added the check : If a room is used in time table then it cannot be
//deleted and cannot be de-allocated from the institute with which it is
//associated in time table
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 14/08/09   Time: 16:43
//Updated in $/LeapCC/Library/Room
//Done enhancement in "Room" module---added room and institute mapping so
//that one room can be shared across institutes
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/27/09    Time: 6:25p
//Updated in $/LeapCC/Library/Room
//modified in query during sending condition put AND instead of WHERE
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Room
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/Room
//define access module
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 9/30/08    Time: 3:09p
//Updated in $/Leap/Source/Library/Room
//modified for NULL value
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 9/26/08    Time: 3:05p
//Updated in $/Leap/Source/Library/Room
//new field exam capacity added 
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:39p
//Updated in $/Leap/Source/Library/Room
//modified in indentation
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/21/08    Time: 3:25p
//Updated in $/Leap/Source/Library/Room
//modified in messages
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/21/08    Time: 12:29p
//Updated in $/Leap/Source/Library/Room
//modified in messages
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/11/08    Time: 12:40p
//Updated in $/Leap/Source/Library/Room
//modified for duplicate record check
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/09/08    Time: 3:09p
//Updated in $/Leap/Source/Library/Room
//modified for checking duplate records 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:39p
//Updated in $/Leap/Source/Library/Room
//change error message with echo
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/12/08    Time: 11:38a
//Updated in $/Leap/Source/Library/Room
//modified in block with blockname through blockid
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/02/08    Time: 8:26p
//Updated in $/Leap/Source/Library/Room
//modified all the functions with latest coding & comments
?>