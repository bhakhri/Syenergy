<?php
//-------------------------------------------------------
// Purpose: To add room detail
// Author : Dipanjan Bhattacharjee
// Created on : (23.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomAllocation');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoomAllocationManager.inc.php");
    $roomManager = RoomAllocationManager::getInstance();
    
    $errorMessage ='';
    
    if ((!isset($REQUEST_DATA['studentId']) || trim($REQUEST_DATA['studentId']) == '')) {
        $errorMessage .=  STUDENT_NOT_EXISTS. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['hostelId']) || trim($REQUEST_DATA['hostelId']) == '')) {
        $errorMessage .=  SELECT_HOSTEL. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomId']) || trim($REQUEST_DATA['roomId']) == '')) {
        $errorMessage .=  SELECT_ROOM. '<br/>';
    }

    $dateCheckIn = $REQUEST_DATA['chkIn'];
    $dateCheckOut = $REQUEST_DATA['chkOut'];  
    
    $studentId = trim($REQUEST_DATA['studentId']);
    $classId = trim($REQUEST_DATA['classId']);
    
    
    
    
    
    
    
    if (trim($errorMessage) == '') {
          $roomRent = trim($REQUEST_DATA['hostelCharges']);
          $classId  = trim($REQUEST_DATA['classId']);

          //check for room capacity  
          $ret2=$roomManager->checkRoomCapacity(' AND hr.hostelRoomId='.$REQUEST_DATA['roomId']);
          $condition = " hostelRoomId='".$REQUEST_DATA['roomId']."' AND  ('$dateCheckIn' BETWEEN dateOfCheckIn AND dateOfCheckOut) AND
                         (studentId <> '".$REQUEST_DATA['studentId']."' AND classId <> '$classId')  ";
          $ret3=$roomManager->checkRoomCurrentStatus($condition,$dateCheckIn);
      
          // Room Capacity vacant 4 4 5
          $isAllow='0';
          if($ret3[0]['capacity'] < $ret2[0]['capacity']) {
            $isAllow='1';
          } 
          
          if($isAllow=='0') {
            if($ret3[0]['expectedCapacity'] >= 1) {
              $isAllow='1';   
            }    
          }
                          
/*
          if($isAllow=='0') {
            $retArray=$roomManager->getRoomCheck($studentId,$classId);
            if($retArray[0]['hostelId'] ==  $REQUEST_DATA['hostelId'] &&  $retArray[0]['hostelRoomId'] == $REQUEST_DATA['roomId']) {
              $isAllow='1';     
            } 
          }
*/                
           $hostelSecurity = $REQUEST_DATA['securityAmount']  ;    
             $roomId = $REQUEST_DATA['roomId'];
			  $hostelId = $REQUEST_DATA['hostelId'];        
          if($isAllow=='1') {
             //****************************************************************************************************************    
             //***********************************************STRAT TRANSCATION************************************************
             //****************************************************************************************************************
             if(SystemDatabaseManager::getInstance()->startTransaction()) {
             	//Check for Generate Fee Student Table		start		
					 if($sessionHandler->getSessionVariable('STUDENT_GENERATE_FEE')=='1'){	
						  $checkGenerateStudent = $roomManager->getGenerateStudentFeeValue($studentId,$classId);
			 			$strQuery =" hostelFee ='$roomRent',
									   hostelSecurity ='$hostelSecurity',
									   hostelRoomId ='$roomId',
									   hostelId ='$hostelId' ";
						 if(count($checkGenerateStudent) >0){											
											
							 $updateGenerateStudent = $roomManager->updateGenerateStudentFeeValue($studentId,$classId,$strQuery);	
							 if($updateGenerateStudent===false){		  		
								echo FAILURE;
						  	}
						
						 }else{
							 	$strQuery .=",studentId ='$studentId',
											classId ='$classId'";
							$insertGenerateStudent = $roomManager->insertGenerateStudentFeeValue($strQuery);	
							 if($insertGenerateStudent===false){		  		
								echo FAILURE;
						  	}
						 }
					 }
					 //Check for Generate Fee Student Table		END
                  $ret=$roomManager->editRoomAllocation($REQUEST_DATA['hostelStudentId']);
                  if($ret===false) {
                     echo FAILURE;   
                  }
                  //update room & hostel in main sc_student table
                  if($REQUEST_DATA['chkOut']==''){
                    $ret = $roomManager->updateRoomAllocationInStudentTable($REQUEST_DATA['studentId'],NULL,NULL,$classId);
                  }
                  else{
                    $ret = $roomManager->updateRoomAllocationInStudentTable($REQUEST_DATA['studentId'],$REQUEST_DATA['hostelId'],$REQUEST_DATA['roomId'],$classId);
                  }
                  if($ret===false) {
                    echo FAILURE;   
                  }
                  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                     echo SUCCESS;    
                  }
                  else {
                     echo FAILURE;      
                  }   
              } 
          }
          else {
            echo ROOM_CAPACITY_VALIDATION;
            die;
          }
    }      
    else {
        echo $errorMessage;
    }

//$History : $
    
?>
