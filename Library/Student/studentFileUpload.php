<?php
//-------------------------------------------------------------------
// THIS FILE IS USED TO upload student photo from student login 
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
$globalCheckStudentFileAccess=0;
UtilityManager::ifStudentNotLoggedIn(true);

/*
if($sessionHandler->getSessionVariable('STUDENT_CAN_CHANGE_IMAGE')!=1){
    echo '<script language="javascript">parent.photoUpload("'.ACCESS_DENIED.'",2)</script>';
    die;
}
*/

//check of max. file size
if($_FILES['studentPhoto']['name']!='' and $_FILES['studentPhoto']['tmp_name']==''){
    echo '<script language="javascript">parent.photoUpload("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
    die;
}


global $sessionHandler;
$studentId=$sessionHandler->getSessionVariable('StudentId');
if($_FILES['studentPhoto']['name']!=''){
 if($studentId!='') {
    $fileObj = FileUploadManager::getInstance('studentPhoto');
    $tempAllowedExtension=array('gif','jpg','jpeg','png','bmp');
    $fileObj->allowedExtensions=$tempAllowedExtension;
    $filename = $studentId.'.'.$fileObj->fileExtension;
    if($fileObj->upload(IMG_PATH.'/Student/',$filename)) {
        require_once(MODEL_PATH . "/StudentManager.inc.php");
        StudentManager::getInstance()->updatePhotoFilenameInStudent($studentId,$filename);
        logError($fileObj->message);
		$imgSrc=STUDENT_PHOTO_PATH."/".$filename;
        echo '<script language="javascript">parent.photoUpload("'.$imgSrc.'")</script>';
    }
    else {
        logError($fileObj->message); 
    }
  }
  else {
      logError('Upload Error: Session ID missing.'); 
  }
}
  
//code for documents uploading
if($studentId!='') {
    $documentArrayCnt=count($globalStudentDocumentsArray);
    for($x=0;$x<$documentArrayCnt;$x++){
      $uFileName=$_FILES['uploadDocs_'.($x+1)]['name'];
      $fileTempName=$_FILES['uploadDocs_'.($x+1)]['tmp_name'];
      if($uFileName!=''){
       if($fileTempName==''){
         echo '<script language="javascript">parent.documentUpload("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
         die;  
       }
    
      $fileObj = FileUploadManager::getInstance('uploadDocs_'.($x+1));
      $fileObj->allowedExtensions=$allowedExtensionsArray;
      $fileName = $studentId.'_'.($x+1).'.'.$fileObj->fileExtension;
      if($fileObj->upload(IMG_PATH.'/Student/Documents/',$fileName)) {
        require_once(MODEL_PATH . "/StudentManager.inc.php");
        StudentManager::getInstance()->deleteDocumentFile($studentId,($x+1));
        StudentManager::getInstance()->insertDocumentFile($studentId,($x+1),$fileName);
        logError($fileObj->message);
      }
      else {
        logError($fileObj->message);
        echo '<script language="javascript">parent.documentUpload("Files could not be uploaded",2)</script>';
        die;
      }
    }
   }
   if($documentArrayCnt>0){
     echo '<script language="javascript">parent.documentUpload("Files uploaded",2)</script>';
     die;  
   }
   else{
     echo '<script language="javascript">parent.documentUpload("No Files Uploaded ",2)</script>';
     die;  
   } 
 }
 else {
      logError('Upload Error: Session ID missing.'); 
 }  

// $History: fileUpload.php $
?>