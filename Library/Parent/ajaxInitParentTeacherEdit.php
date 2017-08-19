<?php
//-------------------------------------------------------
// THIS FILE IS USED TO edit A message
//
//
// Author : Rajeev Aggarwal
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/Parent/ParentMessageManager.inc.php");           

define('MODULE','COMMON');
define('ACCESS','add');
UtilityManager::ifParentNotLoggedIn(true);       
UtilityManager::headerNoCache();  

 $errorMessage ='';

if($sessionHandler->getSessionVariable('SuperUserId')!=''){
  echo ACCESS_DENIED;
  die;
}    
    
    if (trim($errorMessage) == '') {
        $returnStatus = ParentTeacherManager::getInstance()->editParentMessage($REQUEST_DATA['messageId']);
        if($returnStatus === false) {
            $errorMessage = FAILURE;
        }
        else {
            //Stores messageId in Session for using in file uploading
            //As "IdToFileUpload" is initialized in model file itself during edit
            //$sessionHandler->setSessionVariable('IdToFileUpload',$REQUEST_DATA['messageId']);
            $sessionHandler->setSessionVariable('OperationMode',2);
            //Stores file upload info
            $sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
            echo SUCCESS;           
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitParentTeacherEdit.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/08/10    Time: 6:33p
//Updated in $/Leap/Source/Library/ScParent
//access right added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/19/09    Time: 6:55p
//Updated in $/Leap/Source/Library/ScParent
//formating & validation updated
//1132, 1130, 54, 1045, 1044, 500, 1042, 1043 issue resolve
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