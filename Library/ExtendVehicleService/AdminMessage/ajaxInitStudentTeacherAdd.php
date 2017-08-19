<?php
//-------------------------------------------------------
// Purpose: to design the layout for Teacher Feed Back
//
// Author : Rajeev Aggarwal
// Created on : (17.11.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1); 
define('MODULE','COMMON');
define('ACCESS','add');
if($sessionHandler->getSessionVariable('RoleId')==2) {     
    UtilityManager::ifTeacherNotLoggedIn(true);
}
else {
    UtilityManager::ifNotLoggedIn(true);   
}
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Parent/ParentMessageManager.inc.php");
$parentTeacherManager = ParentTeacherManager::getInstance();

$errorMessage ='';

$chb=$REQUEST_DATA['chb'];
$rollNo=$REQUEST_DATA['rollNo'];
$messageSubject=$REQUEST_DATA['messageSubject'];
$messageText=$REQUEST_DATA['messageText'];


$errorMessage ='';
if (trim($errorMessage) == '') {
        $mailCheck = explode(',',$chb);
        $chk=='';  
        for($i=0; $i<count($mailCheck); $i++) {
            if($mailCheck[$i]=='S') {
              $foundArray = StudentTeacherManager::getInstance()->getUserId("IFNULL(userId,'-1') AS userId ", "WHERE userId = '$studentId'"); 
            }
            else if($mailCheck[$i]=='F') {
              $foundArray = StudentTeacherManager::getInstance()->getUserId("IFNULL(fatherUserId,'-1') AS userId", "WHERE userId = '$studentId'"); 
            }
            else if($mailCheck[$i]=='M') {
              $foundArray = StudentTeacherManager::getInstance()->getUserId("IFNULL(motherUserId,'-1') AS userId", "WHERE userId = '$studentId'"); 
            }
            else if($mailCheck[$i]=='G') {
              $foundArray = StudentTeacherManager::getInstance()->getUserId("IFNULL(guardianUserId,'-1') AS userId", "WHERE userId = '$studentId'"); 
            }
            
            if(count($foundArray) > 0 && $foundArray[0]['userId']!='-1')                                
            {
                $Id = $foundArray[0]['userId'];
                $chk='1';
                $returnStatus = StudentTeacherManager::getInstance()->addStudentTeacherMessage($Id);
                if($returnStatus === false) {
	                echo FAILURE;
                    $chk='2';
                }
            }
        }
        if($chk=='1') {
          echo SUCCESS;
        }           
        else
        if($chk=='') {
           echo "It's user doesn't exist";               
        }
}
else {
	echo $errorMessage;
}

// $History: ajaxInitStudentTeacherAdd.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-01   Time: 1:15p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Updated with Session check
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/18/09    Time: 4:36p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//code update
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/02/09    Time: 4:25p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Intial checkin
?>