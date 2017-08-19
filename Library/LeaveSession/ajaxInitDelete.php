<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/LeaveSessionsManager.inc.php");   
define('MODULE','COMMON');  
define('ACCESS','delete'); 
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    global $sessionHandler; 
    
    $LeaveSessionsManager =  LeaveSessionsManager::getInstance();
    
    $sessionId=$REQUEST_DATA['sessionId'];
    if(!isset($REQUEST_DATA['sessionId']) || trim($REQUEST_DATA['sessionId']) == '') {
      $errorMessage = SESSION_NOT_EXIST;
    }
    
    if (trim($errorMessage) == '') {
        $foundArray = $LeaveSessionsManager->checkLeaveSession($REQUEST_DATA['sessionId']);  
        if($foundArray[0]['leaveSessionId']=='') {
            $foundArray = $LeaveSessionsManager->getSession(' WHERE leaveSessionId='.$REQUEST_DATA['sessionId']);
            if($foundArray[0]['active']==0){
                if($LeaveSessionsManager->deleteSession($REQUEST_DATA['sessionId']) ) {
                    echo DELETE;
                }
               else {
                    echo DEPENDENCY_CONSTRAINT;
                }
           }
           else{
               echo LEAVE_ACTIVE_SESSION_DELETE;
           }  
        }
        else {
           echo DEPENDENCY_CONSTRAINT;
        }
   }
   else {
      echo $errorMessage;
   }
 