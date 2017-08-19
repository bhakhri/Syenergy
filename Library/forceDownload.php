<?php
//-------------------------------------------------------
// THIS FILE IS USED for force downloading of file
// Author : Dipanjan Bhattacharjee
// Created on : (06.05.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn(true);
}
else if($roleId==3){
 UtilityManager::ifParentNotLoggedIn(true);
}
else if($roleId==4){
 UtilityManager::ifStudentNotLoggedIn(true);
}
else if($roleId==1 or $roleId==5){
 UtilityManager::ifNotLoggedIn(true);
}
else{
  redirectBrowser(HTTP_PATH);  
}
//UtilityManager::headerNoCache();
$fileId=trim($REQUEST_DATA['fileId']);
$callingModule=trim($REQUEST_DATA['callingModule']);

if($fileId=='' or $callingModule==''){
    echo 'Required Parameters Missing';
    die;
}

if($callingModule=='ResourceDownload'){
   if($roleId!=4){ //only students can download file through this module
       echo 'You are not authorised to download';
       die;
   }
   $studentId=$sessionHandler->getSessionVariable('StudentId');
   if(trim($studentId)==''){
       echo 'Student information missing';
       die;
   }
   $dated=date('Y-m-d h:i:s');
   //read file name from "course_resources" table
   require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
   $studentInfoManager = StudentInformationManager::getInstance();
   $fileNameArray=$studentInfoManager->getCourceResourceFileName($fileId);
   if(is_array($fileNameArray) and count($fileNameArray)>0){
       $fileName=IMG_PATH.'/CourseResource/'.$fileNameArray[0]['attachmentFile'];
       $actualFileName=$fileNameArray[0]['attachmentFile'];
       $employeeId=$fileNameArray[0]['employeeId'];
       if(!file_exists($fileName)){
          echo 'This file does not exists';
          die;  
       }
       //now read and echo contents of this file
       ob_end_clean();
       header("Cache-Control: public, must-revalidate");
       Header("Content-type: application/force-download");
       header('Content-type: application/octet-stream');
       header("Content-Length: " .@filesize($fileName) );
       header('Content-Disposition: attachment; filename="'.$actualFileName.'"');
       //header("Content-Transfer-Encoding: binary\n");
       @readfile($fileName);
       
       //now make entries in counter table
       $studentInfoManager->insertCourceResourceCounter($fileId,$studentId,$dated,$employeeId);
   }
   else{
       echo 'File Name Missing';
       die;
   }
}

if($callingModule=='StudentInfoDetail'){
   if($roleId!=4){ //only students can download file through this module
       echo 'You are not authorised to download';
       die;
   }
   $studentId=$sessionHandler->getSessionVariable('StudentId');
   if(trim($studentId)==''){
       echo 'Student information missing';
       die;
   }
   
   $docId=trim($fileId);
   //read file name from "student_document" table
   require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
   $studentInfoManager = StudentInformationManager::getInstance();
   $fileNameArray=$studentInfoManager->getStudentAttachedDocuments($studentId,' AND documentId='.$docId);
   if(is_array($fileNameArray) and count($fileNameArray)>0){
       $fileName=IMG_PATH.'/Student/Documents/'.$fileNameArray[0]['documentFileName'];
       $actualFileName=$fileNameArray[0]['documentFileName'];
       if(!file_exists($fileName)){
          echo 'This file does not exists';
          die;  
       }
       //now read and echo contents of this file
       ob_end_clean();
       header("Cache-Control: public, must-revalidate");
       Header("Content-type: application/force-download");
       header('Content-type: application/octet-stream');
       header("Content-Length: " .@filesize($fileName) );
       header('Content-Disposition: attachment; filename="'.$actualFileName.'"');
       //header("Content-Transfer-Encoding: binary\n");
       @readfile($fileName);
   }
   else{
       echo 'File Name Missing';
       die;
   }
}

//$History: ajaxConsultingGetValues.php $
?>