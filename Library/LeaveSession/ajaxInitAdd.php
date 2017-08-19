<?php
/*
  This File calls addFunction used in adding Session Records

// Author :Parveen Sharma   
 Created on : 19-July-2008
 Copyright 2008-2009: syenergy Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php"); 
require_once(MODEL_PATH . "/LeaveSessionsManager.inc.php");   
define('MODULE','COMMON');  
define('ACCESS','add'); 
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 $errorMessage ='';
 
    if (!isset($REQUEST_DATA['sessionName']) || trim($REQUEST_DATA['sessionName']) == '') {
        echo ENTER_SESSION_NAME;
		die;
    }
  
    if (!isset($REQUEST_DATA['fromDate']) || trim($REQUEST_DATA['fromDate']) == '') {
        echo LEAVE_SESSION_START_DATE;
		die;
    }
    
    if (!isset($REQUEST_DATA['toDate']) || trim($REQUEST_DATA['toDate']) == '') {
        echo LEAVE_SESSION_END_DATE;
        die;
    }

    $fromDate = $REQUEST_DATA['fromDate'];
    $toDate = $REQUEST_DATA['toDate'];
    
	
    $condition = " WHERE ( (sessionStartDate BETWEEN '$fromDate' AND '$toDate') OR (sessionEndDate BETWEEN '$fromDate' AND '$toDate'))";
             
    if (trim($errorMessage) == '') {
        $foundArray = LeaveSessionsManager::getInstance()->getSessionName(' WHERE UCASE(sessionName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['sessionName']))).'"');  

        if(trim($foundArray[0]['sessionName'])=='') {  //DUPLICATE CHECK
         $returnStatus = LeaveSessionsManager::getInstance()->addSession();
           if($returnStatus === false) {
                $errorMessage = FAILURE;
           }
           else {
             if(trim($REQUEST_DATA['Active'])==1){
               $sessionId=SystemDatabaseManager::getInstance()->lastInsertId();   
               $activeLabelArray=LeaveSessionsManager::getInstance()->makeAllSessionInActive(" AND tt1.leaveSessionId !=".$sessionId); //make previous entries inactive
             }
             echo SUCCESS;           
           }
        }
        else {
            echo LEAVE_SESSION_NAME_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
