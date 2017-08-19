<?php

//-------------------------------------------------------
// Purpose: To add room detail
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
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
        echo STUDENT_NOT_EXISTS;
        die;
    }
    if ((!isset($REQUEST_DATA['classId']) || trim($REQUEST_DATA['classId']) == '')) {
        echo STUDENT_NOT_EXISTS;
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['hostelId']) || trim($REQUEST_DATA['hostelId']) == '')) {
        echo  SELECT_HOSTEL;
        die;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomId']) || trim($REQUEST_DATA['roomId']) == '')) {
        echo  SELECT_ROOM;
        die;
    }
    $dateCheckIn = $REQUEST_DATA['chkIn'];
    $dateCheckOut = $REQUEST_DATA['pChkOut'];  
    
    if (trim($errorMessage) == '') {
          // duplicate value check added
          $condition = " studentId='".$REQUEST_DATA['studentId']."' AND classId = '".$REQUEST_DATA['classId']."'";
          $ret1=$roomManager->getStudentAlreadyExist($condition);
          if(count($ret1) >0 ) {    
            echo "Student already exist";
            die;
          }
        
          //check for already exist student where checkOut date is filled
          $condition = " WHERE studentId='".$REQUEST_DATA['studentId']."'
                               AND ('$dateCheckIn' BETWEEN dateOfCheckIn AND dateOfCheckOut)  ";
          $ret1=$roomManager->checkStudentData($condition);
          if(count($ret1) >0 ) {    
            echo HOSTEL_STUDENT_ALREADY_EXIST;
            die;
          }
          
          //check for room capacity  
          $ret2=$roomManager->checkRoomCapacity(' AND hr.hostelRoomId='.$REQUEST_DATA['roomId']);
          
          $condition = " hostelRoomId='".$REQUEST_DATA['roomId']."' AND ('$dateCheckIn' BETWEEN dateOfCheckIn AND dateOfCheckOut)";
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
          
          if($isAllow=='1') {
              $roomRent = trim($REQUEST_DATA['hostelCharges']);
              $classId  = trim($REQUEST_DATA['classId']);
			  $studentId  = trim($REQUEST_DATA['studentId']);
			  $roomId = $REQUEST_DATA['roomId'];
			  $hostelId = $REQUEST_DATA['hostelId'];
              //****************************************************************************************************************    
              //***********************************************STRAT TRANSCATION************************************************
              //****************************************************************************************************************
              if(SystemDatabaseManager::getInstance()->startTransaction()) {
              	//Check for Generate Fee Student Table		start	
              	    $hostelSecurity = $REQUEST_DATA['securityAmount'] ; 	
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
                      //allocate student
                      $returnStatus=$roomManager->addRoomAllocation($roomRent);
                      if($returnStatus===false){
                        echo FAILURE;
                        die;
                      } 
                      //update room & hostel in main student table
                      if($REQUEST_DATA['chkOut']==''){
                        $returnStatus = $roomManager->updateRoomAllocationInStudentTable($REQUEST_DATA['studentId'],$REQUEST_DATA['hostelId'],$REQUEST_DATA['roomId'], $classId);
                      }
                      else{
                        $returnStatus = $roomManager->updateRoomAllocationInStudentTable($REQUEST_DATA['studentId'],NULL,NULL,$classId);
                      }
                      if($returnStatus===false){
                        echo FAILURE;
                        die;
                      } 
                      
                      //****************************************************************************************************************    
                      //***********************************************COMMIT TRANSCATION************************************************
                      //****************************************************************************************************************
                      if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                         echo SUCCESS;    
                      }
                      else {
                         echo FAILURE;      
                      }   
              } 
          }
          else{
              echo ROOM_CAPACITY_VALIDATION;
              die;
          }
    }      
    else {
        echo $errorMessage;
    }

//$History : $
    
?>
