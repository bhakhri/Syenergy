<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
set_time_limit(0); //to 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AllocateAssignment');
define('ACCESS','edit');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$teacherManager = TeacherManager::getInstance();

$teacherId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId    

if ($errorMessage == '' && (!isset($REQUEST_DATA['msgBody']) || trim($REQUEST_DATA['msgBody']) == '')) {
    $errorMessage .= 'Enter message Body <br/>';
}

 if(trim($REQUEST_DATA['classId'])==''){
    die(SELECT_CLASS);
 }
 if(trim($REQUEST_DATA['subject'])==''){
    die(SELECT_SUBJECT);
 }
 if(trim($REQUEST_DATA['group'])==''){
    die(SELECT_GROUP);
 }

 if(trim($REQUEST_DATA['student'])==''){
    die(NO_DATA_SUBMIT);
 }

 if (trim($errorMessage) == '') {
   $teacherManager->updateTeacherAssignment(); //add comment in "teacher_comment" table    
   // $sessionHandler->setSessionVariable('IdToFileUpload',$commentId);
    echo SUCCESS;
 }
 else {
    echo $errorMessage;
 }
// $History: scAjaxEditSendStudentAssignment.php $
?>