<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A PUBLISHING
//
//
// Author : Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

require_once(MODEL_PATH . "/EmployeeManager.inc.php");
$publishingManager = EmployeeManager::getInstance();

define('MODULE','EmployeeInformation');
define('ACCESS','add');
UtilityManager::ifTeacherNotLoggedIn(true);   
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
     
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');  
     
    if(trim($errorMessage) == '') {
      
      $returnStatus = $publishingManager->addPublishing($employeeId);
      if($returnStatus === false) {
         $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);    
         echo FAILURE;
      }
      else {
        //Id to upload logo
        $sessionHandler->setSessionVariable('IdToFileUpload',SystemDatabaseManager::getInstance()->lastInsertId());
        $sessionHandler->setSessionVariable('OperationMode',1);
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

 
// $History: ajaxInitPublishingAdd.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/01/09    Time: 11:22a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//publisher upload file code updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/17/09    Time: 5:26p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//role permission, alignment, new enhancements added 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/13/09    Time: 3:39p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity/Publishing
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 12:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity/Publishing
//initial checkin
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