<?php 
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Modified by : Pushpender Kumar
// Modified on : (19.09.2008 )
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/AssignFinalGradeManager.inc.php");    
define('MODULE','AttendanceIncentiveDetails');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 

    $assignincentive = AssignFinalGradeManager::getInstance();   
    
  
	global $sessionHandler;
	 
    $errorMessage ='';
     
    $idNos  =   $REQUEST_DATA['idNos'];  
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $incentiveId = $REQUEST_DATA['incentiveDetailId'];
	if($incentiveId==1){
	$weightageFormat=1;
		
	}
	else
		{
		$weightageFormat=2;
		}
    
    $errorMessage ='';
    $str = ''; 
             
    if (trim($errorMessage) == '') {
          //****************************************************************************************************************    
          //***********************************************STRAT TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
              $totalValues = count($REQUEST_DATA['idNos']);     
            
             $returnStatus =  $assignincentive->deleteAssignIncentive($instituteId,$sessionId,$weightageFormat);
              /*if($returnStatus === false) {
                //echo FAILURE; 
                die;
              }*/
               
              $str = '';
              for($i = 0; $i < $totalValues; $i++) {
                 if($weightageFormat==1){
		      $attendancePerFrom = $REQUEST_DATA['attendancePerFrom'][$i];
                      $attendancePerTo = $REQUEST_DATA['attendancePerTo'][$i];
		      $common = $REQUEST_DATA['common'][$i];
                   }
		else{
		      $attendancePerFrom = $REQUEST_DATA['attendancePerFrom1'][$i];
                      $attendancePerTo = $REQUEST_DATA['attendancePerTo1'][$i];
		      $common = $REQUEST_DATA['common1'][$i];
		}
               
                
                if(!empty($str)) {
                  $str .= ',';
                }                                            
                $str .= "('$attendancePerFrom','$attendancePerFrom', '$common','$weightageFormat' ,'$instituteId','$sessionId')";
              }
             
              $returnStatus = $assignincentive->addIncentiveDetail($str);
              if($returnStatus === false) {
                echo FAILURE; 
                die;
              }
               
              //*****************************COMMIT TRANSACTION************************* 
              if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                 $errorMessage = SUCCESS;
                 if($totalValues==0) {
                   $errorMessage = FINAL_GRADE_DELETE_SUCCESSFULLY;  
                 }
                 else if($finalGradeId==1) { 
                   $errorMessage = FINAL_GRADE_UPDATE_SUCCESSFULLY;
                 }
              }
              else {
                 $errorMessage =  FAILURE;
              }    
       }
    }
    echo $errorMessage;
 
?>
    
