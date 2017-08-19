<?php
//
//  This File calls Edit Function used in adding Session Records
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/LeaveSessionsManager.inc.php");  
define('MODULE','COMMON');  
define('ACCESS','edit'); 
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    global $sessionHandler; 
    
    $sessionId=$REQUEST_DATA['sessionId'];

	 if (!isset($REQUEST_DATA['sessionName']) || trim($REQUEST_DATA['sessionName']) == '') {
        echo ENTER_SESSION_NAME;
        die;
    }
  
    if (!isset($REQUEST_DATA['fromDate1']) || trim($REQUEST_DATA['fromDate1']) == '') {
        echo LEAVE_SESSION_START_DATE;
        die;
    }
    
    if (!isset($REQUEST_DATA['toDate1']) || trim($REQUEST_DATA['toDate1']) == '') {
        echo LEAVE_SESSION_END_DATE;
        die;
    }

    $fromDate = $REQUEST_DATA['fromDate1'];
    $toDate = $REQUEST_DATA['toDate1'];
    
    $condition = " WHERE ( (sessionStartDate BETWEEN '$fromDate' AND '$toDate') OR (sessionEndDate BETWEEN '$fromDate' AND '$toDate'))
                   AND leaveSessionId!=".$sessionId ; 
   
     if (trim($errorMessage) == '') {
         if(trim($REQUEST_DATA['Active'])==1) {    
            $foundArray = LeaveSessionsManager::getInstance()->getSession(' WHERE UCASE(sessionName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['sessionName']))).'" AND leaveSessionId!='.$sessionId);
            if(trim($foundArray[0]['sessionName'])=='') {  //DUPLICATE CHECK
                $returnStatus = LeaveSessionsManager::getInstance()->editSession($sessionId);
                 if($returnStatus === false) {
                    echo FAILURE;
                 }   
                 else {
                     $activeLabelArray=LeaveSessionsManager::getInstance()->makeAllSessionInActive(" AND tt1.leaveSessionId !=".$sessionId); //make previous entries inactive
                     echo SUCCESS;           
                 }
            }
            else {     
                echo LEAVE_SESSION_NAME_ALREADY_EXIST;
            }
       }
       else{
            $activeCheckArray=LeaveSessionsManager::getInstance()->getSession(' WHERE active=1 AND leaveSessionId!='.$sessionId);
            if(trim($activeCheckArray[0]['active'])!=''){  //if there is a active label other than this
                $foundArray = LeaveSessionsManager::getInstance()->getSession(' WHERE UCASE(sessionName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['sessionName']))).'" AND leaveSessionId!='.$sessionId);
                if(trim($foundArray[0]['sessionName'])=='') {  //DUPLICATE CHECK
                        $returnStatus = LeaveSessionsManager::getInstance()->editSession($sessionId);
                        if($returnStatus == false) {
                            echo FAILURE;
                        }
                       else{
                           echo SUCCESS;
                       } 
                 }
                 else {
                    echo LEAVE_SESSION_NAME_ALREADY_EXIST;
                 } 
          }
          else {
              echo LEAVE_ACTIVE_SESSION_EXISTS ;
          } 
       }        
  }  
  else {
     echo $errorMessage;
  }          
    
 ?>

