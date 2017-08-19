<?php
//
//  This File calls Edit Function used in adding Session Records
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/SessionsManager.inc.php");  
define('MODULE','SessionMaster');  
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    global $sessionHandler; 
    
    $sessionId=$REQUEST_DATA['sessionId'];

	$currentSessionId = $sessionHandler->getSessionVariable('SessionId');
	if ($currentSessionId == $sessionId) {
		echo CAN_NOT_EDIT_CURRENT_SESSION;
		die;
	}

    if (!isset($REQUEST_DATA['sessionName']) || trim($REQUEST_DATA['sessionName']) == '') {
        echo ENTER_SESSION_NAME;
		die;
    }
 
    if (!isset($REQUEST_DATA['fromDate']) || trim($REQUEST_DATA['fromDate']) == '') {
        echo SESSION_FROM_DATE;
        die;
    }

    if (!isset($REQUEST_DATA['toDate']) || trim($REQUEST_DATA['toDate']) == '') {
        echo SESSION_TO_DATE;
        die;
    }

 
    $fromDate = $REQUEST_DATA['fromDate'];
    $toDate = $REQUEST_DATA['toDate'];  
       
                                                           
    $sessionYear = date("Y",strtotime($fromDate));
    $sessionAbbr = $sessionYear."-".substr(date("Y",strtotime($toDate)),2,2);
    $sessionName = add_slashes(strtoupper(trim($REQUEST_DATA['sessionName'])));  
    
    $condition = " WHERE ( (startDate BETWEEN '$fromDate' AND '$toDate') OR (endDate BETWEEN '$fromDate' AND '$toDate') ) 
                   AND sessionId!=".$sessionId ;                  
     
     
     if (trim($errorMessage) == '') {
         if(trim($REQUEST_DATA['Active'])==1) {    
                $foundArray = SessionsManager::getInstance()->getSession(' WHERE UCASE(sessionName)="'.$sessionName.'" AND sessionId!='.$sessionId);
                if(trim($foundArray[0]['sessionName'])=='') {  //DUPLICATE CHECK
                    $foundArray3 = SessionsManager::getInstance()->getSessionYear($condition);
                    if (trim($foundArray3[0]['sessionYear'] == '')) {  
                        $returnStatus = SessionsManager::getInstance()->editSession($sessionId);
                         if($returnStatus === false) {
                            echo FAILURE;
                         }   
                         else {
                             //make previous entries inactive                                                   
                             $activeLabelArray=SessionsManager::getInstance()->makeAllSessionInActive(" AND tt1.sessionId !=".$sessionId); 
                             echo SUCCESS;           
                         }
                    }
                    else {
                        echo FROM_TO_SESSION_ALREADY_EXIST;     
                    }
             }
             else {
                echo SESSION_NAME_ALREADY_EXIST;
             }
       }
       else{
            $activeCheckArray=SessionsManager::getInstance()->getSession(' WHERE active=1 AND sessionId!='.$sessionId);
            if(trim($activeCheckArray[0]['active'])!=''){  //if there is a active label other than this
            $foundArray = SessionsManager::getInstance()->getSession(' WHERE UCASE(sessionName)="'.$sessionName.'" AND sessionId!='.$sessionId);
            if(trim($foundArray[0]['sessionName'])=='') {  //DUPLICATE CHECK
                  $foundArray = SessionsManager::getInstance()->getSession($condition);
                  if(trim($foundArray[0]['sessionName'])=='') {  //DUPLICATE CHECK
                    $returnStatus = SessionsManager::getInstance()->editSession($sessionId);
                    if($returnStatus == false) {
                        echo FAILURE;
                    }
                   else{
                       echo SUCCESS;
                   } 
                 }
                 else {
                    echo FROM_TO_SESSION_ALREADY_EXIST;      
                 }
             }
             else {
                echo SESSION_NAME_ALREADY_EXIST;
             } 
          }
          else {
              echo ACTIVE_SESSION_EXISTS ;
          } 
       }        
  }  
  else {
        echo $errorMessage;
    }          
    
 ?>
