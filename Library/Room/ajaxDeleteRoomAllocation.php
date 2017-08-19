<?php
//-------------------------------------------------------
// Purpose: To delete room detail
// Author: Jaineesh
// Created on: (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomAllocation');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
$roomManager =  RoomAllocationManager::getInstance();

    if (!isset($REQUEST_DATA['hostelStudentId']) || trim($REQUEST_DATA['hostelStudentId']) == '') {
        $errorMessage = INVALID_ROOM_ALLOCATION;
    }

    if (trim($errorMessage) == '') {

        $studentId = '0';
        $classId = '0';
        $hostelStudentId = $REQUEST_DATA['hostelStudentId'];
        
        if($hostelStudentId=='') {
          $hostelStudentId=0;  
        }
        $condition = " AND hs.hostelStudentId = '".$hostelStudentId."'";
        $ret1=$roomManager->getStudentClassData($condition);
        if(count($ret1) >0 ) {    
           $studentId = $ret1[0]['studentId'];
           $classId = $ret1[0]['classId'];
        }
        
        $condition = " AND studentId = '$studentId' AND classId = '$classId' "; 
        $ret1=$roomManager->getHostelPayement($condition);
        if($ret1[0]['totalRecords'] > 0)  {    
           echo DEPENDENCY_CONSTRAINT; 
           die;
        }
  
        if(SystemDatabaseManager::getInstance()->startTransaction()) {
            $returnArray = $roomManager->deleteRoomAllocation($REQUEST_DATA['hostelStudentId']);
            if($returnArray===true) {
                $returnArray  = $roomManager->deallocateHostelRoom($studentId,$classId);
                if($returnArray===false) {
                  echo FAILURE;
                  die;  
                }
                $returnArray  = $roomManager->deallocateHostelFee($studentId,$classId);
                if($returnArray===false) {
                  echo FAILURE;
                  die;      
                }
            }
            else {
                echo DEPENDENCY_CONSTRAINT;
                die;
            }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                echo DELETE;
            }
        }
    }
    else {
        echo $errorMessage;
    }
?>

