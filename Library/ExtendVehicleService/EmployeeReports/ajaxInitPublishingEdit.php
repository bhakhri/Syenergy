<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPE
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/EmployeeManager.inc.php");
$publishingManager = EmployeeManager::getInstance();

define('MODULE','COMMON');
define('ACCESS','edit');

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['type']) || trim($REQUEST_DATA['type']) == '')) {
        $errorMessage .= ENTER_TYPE."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['scopeId']) || trim($REQUEST_DATA['scopeId']) == '')) {
        $errorMessage .= SELECT_SCOPE."\n";    
    }

    if ($errorMessage == '' && (!isset($REQUEST_DATA['publishedBy']) || trim($REQUEST_DATA['publishedBy']) == '')) {
        $errorMessage .= ENTER_PUBLISHER."\n";    
    }

    if ($errorMessage == '' && (!isset($REQUEST_DATA['description']) || trim($REQUEST_DATA['description']) == '')) {
        $errorMessage .= ENTER_DESCRIPTION."\n";    
    }
        
    if ($errorMessage == '' && (!isset($REQUEST_DATA['publishOn']) || trim($REQUEST_DATA['publishOn']) == '')) {
        $errorMessage .= ENTER_PUBLISHER_DATE."\n";    
    }
    
    if (trim($REQUEST_DATA['publishOn']) == '0000-00-00') {
        $errorMessage .= ENTER_PUBLISHER_DATE."\n";    
    }
	
	if (trim($errorMessage) == '') {
        $returnStatus = $publishingManager->editPublishing($REQUEST_DATA['publishId']);
        if($returnStatus === false) {
            $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
            echo FAILURE;
        }
        else {
            //Id to upload logo
             $sessionHandler->setSessionVariable('IdToFileUpload',$REQUEST_DATA['publishId']);
             $sessionHandler->setSessionVariable('OperationMode',2);
            //Stores file upload info
            $sessionHandler->setSessionVariable('HiddenFile1',$REQUEST_DATA['hiddenFile1']);
            $sessionHandler->setSessionVariable('HiddenFile2',$REQUEST_DATA['hiddenFile2']);
            $sessionHandler->setSessionVariable('ErrorMsg',SUCCESS); 
            echo SUCCESS;           
        }
    }
    else {
        $sessionHandler->setSessionVariable('ErrorMsg',$errorMessage); 
        echo $errorMessage;
    }
// $History: ajaxInitPublishingEdit.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 9/18/09    Time: 1:04p
//Updated in $/LeapCC/Library/EmployeeReports
//updated access defines
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/31/09    Time: 3:15p
//Updated in $/LeapCC/Library/EmployeeReports
//file upload coding updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/16/09    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//new enhancements added (publisher file uploads)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Library/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/13/09    Time: 12:34p
//Created in $/LeapCC/Library/EmployeeReports
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:14p
//Created in $/LeapCC/Library/Publishing
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:14p
//Created in $/Leap/Source/Library/Publishing
//initial checkin 
//
//
 
?>
