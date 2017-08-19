<?php
//-------------------------------------------------------
// THIS FILE IS USED TO add A message
//
// Author : Parveen Sharma
// Created on : (04.02.2009)
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifParentNotLoggedIn(true);       
UtilityManager::headerNoCache();


require_once(MODEL_PATH . "/Parent/ParentMessageManager.inc.php");
$parentTeacherManager = ParentTeacherManager::getInstance();

if($sessionHandler->getSessionVariable('SuperUserId')!=''){
  echo ACCESS_DENIED;
  die;
}

    $teacherId=$REQUEST_DATA['teacherId'];
    $messageSubject=$REQUEST_DATA['messageSubject'];
    $messageText=$REQUEST_DATA['messageText'];

    $errorMessage ='';
    if (trim($errorMessage) == '') {
       //Stores commentId in Session for using in file uploading
       //logError("ajinder:=====inserting new record");
       $returnStatus = ParentTeacherManager::getInstance()->addParentTeacherMessage();
       if($returnStatus === false) {
	     echo FAILURE;
       }
       else {                                                                         
         $sessionHandler->setSessionVariable('IdToFileUpload',SystemDatabaseManager::getInstance()->lastInsertId());
         $sessionHandler->setSessionVariable('OperationMode',1);
         //Stores file upload info
         $sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
         echo SUCCESS;                                                  
         //$sessionHandler->setSessionVariable('IdToFileUpload',$commentId);
       }
    }
    else {
	    echo $errorMessage;
    }

// $History: ajaxInitParentTeacherAdd.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/08/10    Time: 6:33p
//Updated in $/Leap/Source/Library/ScParent
//access right added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/18/09    Time: 1:23p
//Updated in $/Leap/Source/Library/ScParent
//formating & alingments
//bug fix 1097, 1096, 1056, 1049, 1048,
//1043, 1008 1042, 506  
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/17/09    Time: 7:02p
//Updated in $/Leap/Source/Library/ScParent
//bug fix  (file attachement & format updated)
//1041, 1097, 1040, 1041, 1105, 1106, 1109 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/04/09    Time: 4:27p
//Created in $/Leap/Source/Library/ScParent
//initial checkin 
//

?>