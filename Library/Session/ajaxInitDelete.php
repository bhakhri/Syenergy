<?php
//-------------------------------------------------------
// Purpose: To delete attendance Code detail
//
// Author : Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/SessionsManager.inc.php");   
define('MODULE','SessionMaster');  
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    global $sessionHandler; 
    
    $sessionId=$REQUEST_DATA['sessionId'];


	if (!isset($REQUEST_DATA['sessionId']) || trim($REQUEST_DATA['sessionId']) == '') {
        $errorMessage = SESSION_NOT_EXIST;
    }
    
    if (trim($errorMessage) == '') {
        $sessionsManager =  SessionsManager::getInstance();

        $foundArray = $sessionsManager->getSessionCheck($sessionId);    
        if($foundArray[0]['cnt']>0) {   
           echo DEPENDENCY_CONSTRAINT;
           die;
        }
        
        $foundArray = $sessionsManager->getSession(' WHERE sessionId='.$sessionId);
        if($foundArray[0]['active']==0){
            if($sessionsManager->deleteSession($sessionId) ) {
                echo DELETE;
            }
           else {
                echo DEPENDENCY_CONSTRAINT;
            }
        }
       else{
           echo ACTIVE_SESSION_DELETE;
       }  
    }
   else {
        echo $errorMessage;
    }

// $History: ajaxInitDelete.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/28/09    Time: 4:05p
//Updated in $/LeapCC/Library/Session
//fixed bug no.s 615,614,603,458,456,450
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/26/09    Time: 6:56p
//Updated in $/LeapCC/Library/Session
//SessionsManager name change
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Session
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/15/08   Time: 10:50a
//Updated in $/Leap/Source/Library/Session
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/12/08   Time: 2:36p
//Updated in $/Leap/Source/Library/Session
//Active Session added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/10/08   Time: 11:51a
//Updated in $/Leap/Source/Library/Session
//add define access in module
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/26/08    Time: 11:32a
//Updated in $/Leap/Source/Library/Session
//done the common messaging
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/21/08    Time: 10:20a
//Updated in $/Leap/Source/Library/Session
//Added last line comment for VSS
 

?>