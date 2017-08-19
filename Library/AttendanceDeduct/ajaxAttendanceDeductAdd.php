<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Modified by : Pushpender Kumar
// Modified on : (19.09.2008 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/AttendanceDeductManager.inc.php");    
define('MODULE','AssignFinalGrade');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 

   
   $attendanceDeduct = AttendanceDeductManager::getInstance();     
  
	global $sessionHandler;
	 
    $errorMessage ='';
     
    $idNos  =   $REQUEST_DATA['idNos'];  
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $finalGradeId = $REQUEST_DATA['finalGradeId'];
   
    $errorMessage ='';
    $str = '';
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
              $totalValues = count($REQUEST_DATA['idNos']);     
             
       
              $returnStatus =  $attendanceDeduct->deleteAttendanceDeduct($instituteId,$sessionId);
              if($returnStatus === false) {
                echo FAILURE; 
                die;
              }
               
              $str = '';
              for($i = 0; $i < $totalValues; $i++) {
                $marksFrom = $REQUEST_DATA['marksFrom'][$i];
                $marksTo = $REQUEST_DATA['marksTo'][$i];
                $points = $REQUEST_DATA['points'][$i];
                
                if(!empty($str)) {
                  $str .= ',';
                }                                            
                $str .= "('$marksFrom', '$marksTo','$points','$instituteId','$sessionId')";
              }
              
             
              $returnStatus = $attendanceDeduct->addAttendanceDeduct($str);
              if($returnStatus === false) {
                echo FAILURE; 
                die;
              }
               
              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                 $errorMessage = SUCCESS;
                 if($totalValues==0) {
                   $errorMessage = ATTENDANCE_DEDUCT_DELETE_SUCCESSFULLY;  
                 }
                 else if($finalGradeId==1) { 
                   $errorMessage = ATTENDANCE_DEDUCT_UPDATE_SUCCESSFULLY;
                 }
              }
              else {
                 $errorMessage =  FAILURE;
              }    
       }
    }
    echo $errorMessage;
 
?>
    