<?php
//-------------------------------------------------------------------
// THIS FILE IS USED TO upload student photo from student login 
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/HtmlFunctions.inc.php");
require_once(BL_PATH . "/FileUploadManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

if($sessionHandler->getSessionVariable('OverrideAppraisalModuleAccess')==1){
    die(ACCESS_DENIED);
}

define('MODULE','AppraisalForm');
define('ACCESS','edit');
$roleId=$sessionHandler->getSessionVariable('RoleId');
if(SHOW_EMPLOYEE_APPRAISAL_FORM!=1){
  die(ACCESS_DENIED);
}
if($roleId==2){
 UtilityManager::ifTeacherNotLoggedIn();
}
else if($roleId==5){
 UtilityManager::ifManagementNotLoggedIn();
}
else if($roleId!=1 and $roleId>5){
  UtilityManager::ifNotLoggedIn();    
}
else{
  die(ACCESS_DENIED);
}

UtilityManager::headerNoCache();

$employeeId=$sessionHandler->getSessionVariable('EmployeeId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');

$proofId=trim($REQUEST_DATA['proofId']);
$appraisalId=trim($REQUEST_DATA['appraisalId']);


if($proofId=='' or $appraisalId==''){
    echo '<script language="javascript">parent.fileUploadError("Required Parameters Missing")</script>';
    die; 
}

if($proofId < 1 or $proofId > 34){
   echo '<script language="javascript">parent.fileUploadError("This Form Is Not Available")</script>';
   die;
}

function doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$hodEvalueation,$sessionId){
   global $appDataManager;
    
  $foundArray=$appDataManager->checkMainAppraisal($appraisalId,$employeeId,$sessionId);
  if($foundArray[0]['cnt']==0){
       //insert fresh data
       $ret=$appDataManager->insertMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$hodEvalueation,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
  }
  else{
       //update data
       $ret=$appDataManager->updateMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$sessionId);
       if($ret==false){
           die(FAILURE);
       }
   }  
}


require_once(MODEL_PATH . "/Appraisal/AppraisalDataManager.inc.php");
$appDataManager = AppraisalDataManager::getInstance();

$allowedFilesForAppraisal=array('gif','jpg','jpeg','png','bmp','pdf');

if(SystemDatabaseManager::getInstance()->startTransaction()) {
 
 $previousDataArray=$appDataManager->gerProofData($proofId,$employeeId,$sessionId);
    
 if($proofId==18){
    $patent_name=add_slashes(trim($REQUEST_DATA['patent_name'])); 
    $cofiler1=add_slashes(trim($REQUEST_DATA['cofiler1']));
    
    $patent_granted=add_slashes(trim($REQUEST_DATA['patent_granted'])); 
    $cofiler2=add_slashes(trim($REQUEST_DATA['cofiler2']));
    
    //check of max. file size
    if($_FILES['patent1']['name']!='' and $_FILES['patent1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['patent2']['name']!='' and $_FILES['patent2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($patent_name!=''){
        if($cofiler1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-filler names",2)</script>';
          die;   
        }
    }
    if($cofiler1!=''){
        if($patent_name==''){
          echo '<script language="javascript">parent.fileUploadError("Enter patent names",2)</script>';
          die;   
        }
    }
    
    if($patent_granted!=''){
        if($cofiler2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-filler names",2)</script>';
          die;   
        }
    }
    if($cofiler2!=''){
        if($patent_granted==''){
          echo '<script language="javascript">parent.fileUploadError("Enter patents granted",2)</script>';
          die;   
        }
    }
    
    $fileObj1     = FileUploadManager::getInstance('patent1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('patent2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData18($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData18($employeeId,$sessionId,$patent_name,$cofiler1,$patent_granted,$cofiler2,$act1_marks,$act2_marks,$file1,$file2);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof18/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData18($proofDataId,$newFileName,100,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=100;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof18/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData18($proofDataId,$newFileName,200,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=200;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
 }
 else if($proofId==19){
     
    $pub1    = add_slashes(trim($REQUEST_DATA['pub1'])); 
    $co1     = add_slashes(trim($REQUEST_DATA['co1']));
    $jname1  = add_slashes(trim($REQUEST_DATA['jname1'])); 
    $impact1 = add_slashes(trim($REQUEST_DATA['impact1']));
    
    $pub2    = add_slashes(trim($REQUEST_DATA['pub2'])); 
    $co2     = add_slashes(trim($REQUEST_DATA['co2']));
    $jname2  = add_slashes(trim($REQUEST_DATA['jname2'])); 
    $impact2 = add_slashes(trim($REQUEST_DATA['impact2']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($pub1!=''){
        if($co1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co1!=''){
        if($jname1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname1!=''){
        if($impact1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact1!=''){
        if(!is_numeric($impact1)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact1<1){
          echo '<script language="javascript">parent.fileUploadError("Impact value can not be less than 1",2)</script>';
          die;   
        }
        if($pub1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    if($pub2!=''){
        if($co2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co2!=''){
        if($jname2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname2!=''){
        if($impact2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact2!=''){
        if(!is_numeric($impact2)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact2<1){
          echo '<script language="javascript">parent.fileUploadError("Impact value can not be less than 1",2)</script>';
          die;   
        }
        if($pub2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData19($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData19($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$act1_marks,$act2_marks,$file1,$file2);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof19/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData19($proofDataId,$newFileName,100,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=100;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof19/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData19($proofDataId,$newFileName,100,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=100;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
else if($proofId==20){
     
    $pub1    = add_slashes(trim($REQUEST_DATA['pub1'])); 
    $co1     = add_slashes(trim($REQUEST_DATA['co1']));
    $jname1  = add_slashes(trim($REQUEST_DATA['jname1'])); 
    $impact1 = add_slashes(trim($REQUEST_DATA['impact1']));
    
    $pub2    = add_slashes(trim($REQUEST_DATA['pub2'])); 
    $co2     = add_slashes(trim($REQUEST_DATA['co2']));
    $jname2  = add_slashes(trim($REQUEST_DATA['jname2'])); 
    $impact2 = add_slashes(trim($REQUEST_DATA['impact2']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($pub1!=''){
        if($co1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co1!=''){
        if($jname1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname1!=''){
        if($impact1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact1!=''){
        if(!is_numeric($impact1)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact1<.3 or $impact1>.9){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be between 0.3 and 0.9",2)</script>';
          die;   
        }
        if($pub1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    if($pub2!=''){
        if($co2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co2!=''){
        if($jname2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname2!=''){
        if($impact2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact2!=''){
        if(!is_numeric($impact2)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact2<.3 or $impact2>.9){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be between 0.3 and 0.9",2)</script>';
          die;   
        }
        if($pub2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData20($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData20($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$act1_marks,$act2_marks,$file1,$file2);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof20/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData20($proofDataId,$newFileName,75,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=75;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof20/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData20($proofDataId,$newFileName,75,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=75;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
 
else if($proofId==21){
     
    $pub1    = add_slashes(trim($REQUEST_DATA['pub1'])); 
    $co1     = add_slashes(trim($REQUEST_DATA['co1']));
    $jname1  = add_slashes(trim($REQUEST_DATA['jname1'])); 
    $impact1 = add_slashes(trim($REQUEST_DATA['impact1']));
    
    $pub2    = add_slashes(trim($REQUEST_DATA['pub2'])); 
    $co2     = add_slashes(trim($REQUEST_DATA['co2']));
    $jname2  = add_slashes(trim($REQUEST_DATA['jname2'])); 
    $impact2 = add_slashes(trim($REQUEST_DATA['impact2']));
    
    $pub3    = add_slashes(trim($REQUEST_DATA['pub3'])); 
    $co3     = add_slashes(trim($REQUEST_DATA['co3']));
    $jname3  = add_slashes(trim($REQUEST_DATA['jname3'])); 
    $impact3 = add_slashes(trim($REQUEST_DATA['impact3']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof3']['name']!='' and $_FILES['proof3']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($pub1!=''){
        if($co1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co1!=''){
        if($jname1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname1!=''){
        if($impact1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact1!=''){
        if(!is_numeric($impact1)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact1<0 or $impact1>.3){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be between 0 and 0.3",2)</script>';
          die;   
        }
        if($pub1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    if($pub2!=''){
        if($co2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co2!=''){
        if($jname2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname2!=''){
        if($impact2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact2!=''){
        if(!is_numeric($impact2)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact2<0 or $impact2>.3){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be between 0 and 0.3",2)</script>';
          die;   
        }
        if($pub2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    if($pub3!=''){
        if($co3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co3!=''){
        if($jname3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname3!=''){
        if($impact3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact3!=''){
        if(!is_numeric($impact3)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact3<0 or $impact1>.3){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be between 0 and 0.3",2)</script>';
          die;   
        }
        if($pub3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    $fileObj3     = FileUploadManager::getInstance('proof3');
    $fileTmpName3 = $fileObj3->tmp;
    $fileName3    = $fileObj3->name;
    $fileExt3     = $fileObj3->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName3!=''){
      if(!in_array($fileExt3,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    if($previousDataArray[0]['act3_marks']==''){
       $act3_marks=0;
    }
    else{
       $act3_marks=$previousDataArray[0]['act3_marks']; 
    }
    
    if($previousDataArray[0]['file3']==''){
       $file3='';
    }
    else{
       $file3=$previousDataArray[0]['file3']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks+$act3_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData21($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData21($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$pub3,$co3,$jname3,$impact3,$act1_marks,$act2_marks,$act3_marks,$file1,$file2,$file3);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof21/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData21($proofDataId,$newFileName,50,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=50;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof21/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData21($proofDataId,$newFileName,50,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=50;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName3!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt3;
          if($fileObj3->upload(IMG_PATH.'/Appraisal/Proof21/Third/',$newFileName)) {
            $ret=$appDataManager->updateProofData21($proofDataId,$newFileName,50,3);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act3_marks=50;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
 
else if($proofId==22){
     
    $pub1    = add_slashes(trim($REQUEST_DATA['pub1'])); 
    $co1     = add_slashes(trim($REQUEST_DATA['co1']));
    $jname1  = add_slashes(trim($REQUEST_DATA['jname1'])); 
    $impact1 = add_slashes(trim($REQUEST_DATA['impact1']));
    
    $pub2    = add_slashes(trim($REQUEST_DATA['pub2'])); 
    $co2     = add_slashes(trim($REQUEST_DATA['co2']));
    $jname2  = add_slashes(trim($REQUEST_DATA['jname2'])); 
    $impact2 = add_slashes(trim($REQUEST_DATA['impact2']));
    
    $pub3    = add_slashes(trim($REQUEST_DATA['pub3'])); 
    $co3     = add_slashes(trim($REQUEST_DATA['co3']));
    $jname3  = add_slashes(trim($REQUEST_DATA['jname3'])); 
    $impact3 = add_slashes(trim($REQUEST_DATA['impact3']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof3']['name']!='' and $_FILES['proof3']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($pub1!=''){
        if($co1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co1!=''){
        if($jname1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname1!=''){
        if($impact1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact1!=''){
        if(!is_numeric($impact1)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact1<0){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be greater than zero",2)</script>';
          die;   
        }
        if($pub1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    if($pub2!=''){
        if($co2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co2!=''){
        if($jname2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname2!=''){
        if($impact2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact2!=''){
        if(!is_numeric($impact2)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact2<0){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be greater than zero",2)</script>';
          die;   
        }
        if($pub2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    if($pub3!=''){
        if($co3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co3!=''){
        if($jname3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter journal name",2)</script>';
          die;   
        }
    }
    if($jname3!=''){
        if($impact3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact3!=''){
        if(!is_numeric($impact3)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact3<0){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be greater than zero",2)</script>';
          die;   
        }
        if($pub3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    $fileObj3     = FileUploadManager::getInstance('proof3');
    $fileTmpName3 = $fileObj3->tmp;
    $fileName3    = $fileObj3->name;
    $fileExt3     = $fileObj3->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName3!=''){
      if(!in_array($fileExt3,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    if($previousDataArray[0]['act3_marks']==''){
       $act3_marks=0;
    }
    else{
       $act3_marks=$previousDataArray[0]['act3_marks']; 
    }
    
    if($previousDataArray[0]['file3']==''){
       $file3='';
    }
    else{
       $file3=$previousDataArray[0]['file3']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks+$act3_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData22($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData22($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$pub3,$co3,$jname3,$impact3,$act1_marks,$act2_marks,$act3_marks,$file1,$file2,$file3);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof22/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData22($proofDataId,$newFileName,30,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=30;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof22/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData22($proofDataId,$newFileName,30,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=30;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName3!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt3;
          if($fileObj3->upload(IMG_PATH.'/Appraisal/Proof22/Third/',$newFileName)) {
            $ret=$appDataManager->updateProofData22($proofDataId,$newFileName,30,3);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act3_marks=30;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
 }
 
else if($proofId==23){
     
    $pub     = add_slashes(trim($REQUEST_DATA['pub'])); 
    $co      = add_slashes(trim($REQUEST_DATA['co']));
    $publish = add_slashes(trim($REQUEST_DATA['publish']));
    
    //check of max. file size
    if($_FILES['proof']['name']!='' and $_FILES['proof']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($pub!=''){
        if($co==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co!=''){
        if($publish==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publishing house name",2)</script>';
          die;   
        }
    }
    if($publish!=''){
        if($pub==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication title",2)</script>';
          die;   
        }
    }
    
    $fileObj     = FileUploadManager::getInstance('proof');
    $fileTmpName = $fileObj->tmp;
    $fileName    = $fileObj->name;
    $fileExt     = $fileObj->fileExtension;
    
    if($fileName!=''){
      if(!in_array($fileExt,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
    }
    else{
       $act_marks=$previousDataArray[0]['act_marks']; 
    }
    
    if($previousDataArray[0]['file']==''){
       $file='';
    }
    else{
       $file=$previousDataArray[0]['file']; 
    }

    $selfEvaluation=($act_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData23($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData23($employeeId,$sessionId,$pub,$co,$publish,$act_marks,$file);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt;
          if($fileObj->upload(IMG_PATH.'/Appraisal/Proof23/',$newFileName)) {
            $ret=$appDataManager->updateProofData23($proofDataId,$newFileName,200,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act_marks=200;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
 } 
 
else if($proofId==24){
     
    $pub1     = add_slashes(trim($REQUEST_DATA['pub1'])); 
    $co1      = add_slashes(trim($REQUEST_DATA['co1']));
    $publish1 = add_slashes(trim($REQUEST_DATA['publish1']));
    
    $pub2     = add_slashes(trim($REQUEST_DATA['pub2'])); 
    $co2      = add_slashes(trim($REQUEST_DATA['co2']));
    $publish2 = add_slashes(trim($REQUEST_DATA['publish2']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($pub1!=''){
        if($co1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co1!=''){
        if($publish1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publishing house name",2)</script>';
          die;   
        }
    }
    if($publish1!=''){
        if($pub1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication title",2)</script>';
          die;   
        }
    }
    
    if($pub2!=''){
        if($co2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co2!=''){
        if($publish2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publishing house name",2)</script>';
          die;   
        }
    }
    if($publish2!=''){
        if($pub2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication title",2)</script>';
          die;   
        }
    }
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }

    $selfEvaluation=($act1_marks+$act2_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData24($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData24($employeeId,$sessionId,$pub1,$co1,$publish1,$act1_marks,$file1,$pub2,$co2,$publish2,$act2_marks,$file2);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof24/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData24($proofDataId,$newFileName,75,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=75;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof24/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData24($proofDataId,$newFileName,75,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=75;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
 }
 
else if($proofId==25){
     
    $pub1     = add_slashes(trim($REQUEST_DATA['pub1'])); 
    $co1      = add_slashes(trim($REQUEST_DATA['co1']));
    $conf_name1 = add_slashes(trim($REQUEST_DATA['conf_name1']));
    
    $pub2     = add_slashes(trim($REQUEST_DATA['pub2'])); 
    $co2      = add_slashes(trim($REQUEST_DATA['co2']));
    $conf_name2 = add_slashes(trim($REQUEST_DATA['conf_name2']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($pub1!=''){
        if($co1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co1!=''){
        if($conf_name1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter conference name",2)</script>';
          die;   
        }
    }
    if($conf_name1!=''){
        if($pub1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication title",2)</script>';
          die;   
        }
    }
    
    if($pub2!=''){
        if($co2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co2!=''){
        if($conf_name2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter conference name",2)</script>';
          die;   
        }
    }
    if($conf_name2!=''){
        if($pub2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication title",2)</script>';
          die;   
        }
    }
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }

    $selfEvaluation=($act1_marks+$act2_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData25($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData25($employeeId,$sessionId,$pub1,$co1,$conf_name1,$act1_marks,$file1,$pub2,$co2,$conf_name2,$act2_marks,$file1,$file2);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof25/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData25($proofDataId,$newFileName,50,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=50;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof25/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData25($proofDataId,$newFileName,50,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=50;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
 }
 
 
else if($proofId==26){
     
    $pub1    = add_slashes(trim($REQUEST_DATA['pub1'])); 
    $co1     = add_slashes(trim($REQUEST_DATA['co1']));
    $jname1  = add_slashes(trim($REQUEST_DATA['jname1'])); 
    $impact1 = add_slashes(trim($REQUEST_DATA['impact1']));
    
    $pub2    = add_slashes(trim($REQUEST_DATA['pub2'])); 
    $co2     = add_slashes(trim($REQUEST_DATA['co2']));
    $jname2  = add_slashes(trim($REQUEST_DATA['jname2'])); 
    $impact2 = add_slashes(trim($REQUEST_DATA['impact2']));
    
    $pub3    = add_slashes(trim($REQUEST_DATA['pub3'])); 
    $co3     = add_slashes(trim($REQUEST_DATA['co3']));
    $jname3  = add_slashes(trim($REQUEST_DATA['jname3'])); 
    $impact3 = add_slashes(trim($REQUEST_DATA['impact3']));
    
    $pub4    = add_slashes(trim($REQUEST_DATA['pub4'])); 
    $co4     = add_slashes(trim($REQUEST_DATA['co4']));
    $jname4  = add_slashes(trim($REQUEST_DATA['jname4'])); 
    $impact4 = add_slashes(trim($REQUEST_DATA['impact4']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof3']['name']!='' and $_FILES['proof3']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof4']['name']!='' and $_FILES['proof4']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($pub1!=''){
        if($co1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co1!=''){
        if($jname1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname1!=''){
        if($impact1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact1!=''){
        if(!is_numeric($impact1)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact1<0){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be greater than zero",2)</script>';
          die;   
        }
        if($pub1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    if($pub2!=''){
        if($co2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co2!=''){
        if($jname2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname2!=''){
        if($impact2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact2!=''){
        if(!is_numeric($impact2)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact2<0){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be greater than zero",2)</script>';
          die;   
        }
        if($pub2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    if($pub3!=''){
        if($co3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co3!=''){
        if($jname3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname3!=''){
        if($impact3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact3!=''){
        if(!is_numeric($impact3)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact3<0){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be greater than zero",2)</script>';
          die;   
        }
        if($pub3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    
    if($pub4!=''){
        if($co4==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($co4!=''){
        if($jname4==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($jname4!=''){
        if($impact4==''){
          echo '<script language="javascript">parent.fileUploadError("Enter co-author name",2)</script>';
          die;   
        }
    }
    if($impact4!=''){
        if(!is_numeric($impact4)){
          echo '<script language="javascript">parent.fileUploadError("Enter decimal value",2)</script>';
          die;   
        }
        if($impact4<0){
          echo '<script language="javascript">parent.fileUploadError("Impact value must be greater than zero",2)</script>';
          die;   
        }
        if($pub4==''){
          echo '<script language="javascript">parent.fileUploadError("Enter publication-title",2)</script>';
          die;   
        }
    }
    
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    $fileObj3     = FileUploadManager::getInstance('proof3');
    $fileTmpName3 = $fileObj3->tmp;
    $fileName3    = $fileObj3->name;
    $fileExt3     = $fileObj3->fileExtension;
    
    $fileObj4     = FileUploadManager::getInstance('proof4');
    $fileTmpName4 = $fileObj4->tmp;
    $fileName4    = $fileObj4->name;
    $fileExt4     = $fileObj4->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName3!=''){
      if(!in_array($fileExt3,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName4!=''){
      if(!in_array($fileExt4,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    if($previousDataArray[0]['act3_marks']==''){
       $act3_marks=0;
    }
    else{
       $act3_marks=$previousDataArray[0]['act3_marks']; 
    }
    
    if($previousDataArray[0]['file3']==''){
       $file3='';
    }
    else{
       $file3=$previousDataArray[0]['file3']; 
    }
    
    if($previousDataArray[0]['act4_marks']==''){
       $act4_marks=0;
    }
    else{
       $act4_marks=$previousDataArray[0]['act4_marks']; 
    }
    
    if($previousDataArray[0]['file4']==''){
       $file4='';
    }
    else{
       $file4=$previousDataArray[0]['file4']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks+$act3_marks+$act4_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData26($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData26($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$pub3,$co3,$jname3,$impact3,$pub4,$co4,$jname4,$impact4,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$file1,$file2,$file3,$file4);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof26/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData26($proofDataId,$newFileName,25,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=25;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof26/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData26($proofDataId,$newFileName,25,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=25;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName3!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt3;
          if($fileObj3->upload(IMG_PATH.'/Appraisal/Proof26/Third/',$newFileName)) {
            $ret=$appDataManager->updateProofData26($proofDataId,$newFileName,25,3);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act3_marks=25;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName4!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt4;
          if($fileObj4->upload(IMG_PATH.'/Appraisal/Proof26/Fourth/',$newFileName)) {
            $ret=$appDataManager->updateProofData26($proofDataId,$newFileName,25,4);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act4_marks=25;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
    
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks+$act3_marks+$act4_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
 }
 
else if($proofId==27){
     
    $workshop1    = add_slashes(trim($REQUEST_DATA['workshop1'])); 
    $institute1   = add_slashes(trim($REQUEST_DATA['institute1']));
    $test_input1  = add_slashes(trim($REQUEST_DATA['test_input1'])); 
    $test_input2  = add_slashes(trim($REQUEST_DATA['test_input2']));
    
    $workshop2    = add_slashes(trim($REQUEST_DATA['workshop2'])); 
    $institute2   = add_slashes(trim($REQUEST_DATA['institute2']));
    $test_input3  = add_slashes(trim($REQUEST_DATA['test_input3'])); 
    $test_input4  = add_slashes(trim($REQUEST_DATA['test_input4']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
        
    if($workshop1!=''){
        if($institute1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter institute name",2)</script>';
          die;   
        }
    }
    if($institute1!=''){
        if($test_input1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter held from date",2)</script>';
          die;   
        }
    }
    if($test_input1!=''){
        if($test_input2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter held to date",2)</script>';
          die;   
        }
    }
    if($test_input2!=''){
        if($workshop1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter workshop name",2)</script>';
          die;   
        }
    }

    
    if($workshop2!=''){
        if($institute2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter institute name",2)</script>';
          die;   
        }
    }
    if($institute2!=''){
        if($test_input3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter held from date",2)</script>';
          die;   
        }
    }
    if($test_input3!=''){
        if($test_input4==''){
          echo '<script language="javascript">parent.fileUploadError("Enter held to date",2)</script>';
          die;   
        }
    }
    if($test_input4!=''){
        if($workshop2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter workshop name",2)</script>';
          die;   
        }
    }
    
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData27($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData27($employeeId,$sessionId,$workshop1,$institute1,$test_input1,$test_input2,$act1_marks,$file1,$workshop2,$institute2,$test_input3,$test_input4,$act2_marks,$file2);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof27/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData27($proofDataId,$newFileName,50,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=50;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof27/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData27($proofDataId,$newFileName,50,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=50;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
 
  
else if($proofId==28){
     
    $workshop1    = add_slashes(trim($REQUEST_DATA['workshop1'])); 
    $institute1   = add_slashes(trim($REQUEST_DATA['institute1']));
    $test_input1  = add_slashes(trim($REQUEST_DATA['test_input1'])); 
    $test_input2  = add_slashes(trim($REQUEST_DATA['test_input2']));
    
    $workshop2    = add_slashes(trim($REQUEST_DATA['workshop2'])); 
    $institute2   = add_slashes(trim($REQUEST_DATA['institute2']));
    $test_input3  = add_slashes(trim($REQUEST_DATA['test_input3'])); 
    $test_input4  = add_slashes(trim($REQUEST_DATA['test_input4']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
        
    if($workshop1!=''){
        if($institute1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter institute name",2)</script>';
          die;   
        }
    }
    if($institute1!=''){
        if($test_input1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter held from date",2)</script>';
          die;   
        }
    }
    if($test_input1!=''){
        if($test_input2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter held to date",2)</script>';
          die;   
        }
    }
    if($test_input2!=''){
        if($workshop1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter workshop name",2)</script>';
          die;   
        }
    }

    
    if($workshop2!=''){
        if($institute2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter institute name",2)</script>';
          die;   
        }
    }
    if($institute2!=''){
        if($test_input3==''){
          echo '<script language="javascript">parent.fileUploadError("Enter held from date",2)</script>';
          die;   
        }
    }
    if($test_input3!=''){
        if($test_input4==''){
          echo '<script language="javascript">parent.fileUploadError("Enter held to date",2)</script>';
          die;   
        }
    }
    if($test_input4!=''){
        if($workshop2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter workshop name",2)</script>';
          die;   
        }
    }
    
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData28($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData28($employeeId,$sessionId,$workshop1,$institute1,$test_input1,$test_input2,$act1_marks,$file1,$workshop2,$institute2,$test_input3,$test_input4,$act2_marks,$file2);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof28/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData28($proofDataId,$newFileName,30,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=30;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
      if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof28/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData28($proofDataId,$newFileName,30,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=30;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
 
else if($proofId==29){
     
     $proposal    = add_slashes(trim($REQUEST_DATA['proposal'])); 
     $agency      = add_slashes(trim($REQUEST_DATA['agency']));
     $test_input  = add_slashes(trim($REQUEST_DATA['test_input']));
     $costing     = add_slashes(trim($REQUEST_DATA['costing'])); 
    
     //check of max. file size
     if($_FILES['proof']['name']!='' and $_FILES['proof']['tmp_name']==''){
      echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
      die;
     }
    
        
    if($proposal!=''){
        if($agency==''){
          echo '<script language="javascript">parent.fileUploadError("Enter name of agency",2)</script>';
          die;   
        }
    }
    if($agency!=''){
       if($test_input==''){
          echo '<script language="javascript">parent.fileUploadError("Select date of submission",2)</script>';
          die;   
       } 
    }
    if($test_input!=''){
        if($costing=='' or $costing==0){
          echo '<script language="javascript">parent.fileUploadError("Select costing",2)</script>';
          die;
        }
    }
    
    if($costing!='' and $costing!=0){
      if($proposal==''){
         echo '<script language="javascript">parent.fileUploadError("Enter proposal name",2)</script>';
         die; 
      }  
    }
    
    $fileObj1     = FileUploadManager::getInstance('proof');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
    }
    else{
       $act_marks=$previousDataArray[0]['act_marks']; 
    }
    
    if($previousDataArray[0]['file']==''){
       $file='';
    }
    else{
       $file=$previousDataArray[0]['file']; 
    }
    
    $selfEvaluation=($act_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData29($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    $multiplication_factor=1;
    
    if($file!=''){
     $act_marks=($costing)*($multiplication_factor);
    }
     
    //insert new records
    $ret=$appDataManager->insertProofData29($employeeId,$sessionId,$proposal,$agency,$test_input,$costing,$act_marks,$file);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          $act_marks=($costing)*($multiplication_factor);
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof29/',$newFileName)) {
            $ret=$appDataManager->updateProofData29($proofDataId,$newFileName,$act_marks,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act_marks=($costing)*($multiplication_factor);
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
   }
     
     //now update main appraisal table
     $selfEvaluation= ($act_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
 
else if($proofId==30){
     
     $proposal    = add_slashes(trim($REQUEST_DATA['proposal'])); 
     $agency      = add_slashes(trim($REQUEST_DATA['agency']));
     $test_input  = add_slashes(trim($REQUEST_DATA['test_input']));
     $costing     = add_slashes(trim($REQUEST_DATA['costing'])); 
    
     //check of max. file size
     if($_FILES['proof']['name']!='' and $_FILES['proof']['tmp_name']==''){
      echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
      die;
     }
    
        
    if($proposal!=''){
        if($agency==''){
          echo '<script language="javascript">parent.fileUploadError("Enter name of agency",2)</script>';
          die;   
        }
    }
    if($agency!=''){
       if($test_input==''){
          echo '<script language="javascript">parent.fileUploadError("Select date of submission",2)</script>';
          die;   
       } 
    }
    if($test_input!=''){
        if($costing=='' or $costing==0){
          echo '<script language="javascript">parent.fileUploadError("Select costing",2)</script>';
          die;
        }
    }
    
    if($costing!='' and $costing!=0){
      if($proposal==''){
         echo '<script language="javascript">parent.fileUploadError("Enter proposal name",2)</script>';
         die; 
      }  
    }
    
    $fileObj1     = FileUploadManager::getInstance('proof');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
    }
    else{
       $act_marks=$previousDataArray[0]['act_marks']; 
    }
    
    if($previousDataArray[0]['file']==''){
       $file='';
    }
    else{
       $file=$previousDataArray[0]['file']; 
    }
    
    $selfEvaluation=($act_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData30($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    $multiplication_factor=1;
    
    if($file!=''){
     $act_marks=($costing)*($multiplication_factor);
    }
     
    //insert new records
    $ret=$appDataManager->insertProofData30($employeeId,$sessionId,$proposal,$agency,$test_input,$costing,$act_marks,$file);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          $act_marks=($costing)*($multiplication_factor);
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof30/',$newFileName)) {
            $ret=$appDataManager->updateProofData30($proofDataId,$newFileName,$act_marks,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act_marks=($costing)*($multiplication_factor);
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
   }
     
     //now update main appraisal table
     $selfEvaluation= ($act_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
 
else if($proofId==31){
     
     $project     = add_slashes(trim($REQUEST_DATA['project'])); 
     $agency      = add_slashes(trim($REQUEST_DATA['agency']));
     $test_input  = add_slashes(trim($REQUEST_DATA['test_input']));
     $costing     = add_slashes(trim($REQUEST_DATA['costing'])); 
     $add_amount  = add_slashes(trim($REQUEST_DATA['add_amount']));
     
     $maxValue=10000*250; 
    
     //check of max. file size
     if($_FILES['proof']['name']!='' and $_FILES['proof']['tmp_name']==''){
      echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
      die;
     }
    
        
    if($project!=''){
        if($agency==''){
          echo '<script language="javascript">parent.fileUploadError("Enter name of agency",2)</script>';
          die;   
        }
    }
    if($agency!=''){
       if($test_input==''){
          echo '<script language="javascript">parent.fileUploadError("Select starting date",2)</script>';
          die;   
       } 
    }
    if($test_input!=''){
        if($costing=='' or $costing==0){
          echo '<script language="javascript">parent.fileUploadError("Select costing",2)</script>';
          die;
        }
    }
    
    if($costing!='' and $costing!=0){
      if($add_amount==''){
          echo '<script language="javascript">parent.fileUploadError("Please enter numeric values in addit. amt.",2)</script>';
          die;
      }
      if($project==''){
         echo '<script language="javascript">parent.fileUploadError("Enter project name",2)</script>';
         die; 
      }
    }

    if($add_amount==''){
      echo '<script language="javascript">parent.fileUploadError("Please enter numeric values in addit. amt.",2)</script>';
      die;
    }

    if(!is_numeric($add_amount)){
      echo '<script language="javascript">parent.fileUploadError("Please enter numeric values in addit. amt.",2)</script>';
      die;  
    }
    
    if($add_amount!=0){
      if($add_amount>$maxValue){
         echo '<script language="javascript">parent.fileUploadError("Addit. amt. can not be more than '.$maxValue.'",2)</script>';
         die;
      } 
      if($project==''){
         echo '<script language="javascript">parent.fileUploadError("Enter project name",2)</script>';
         die; 
      }
    }
    
    $fileObj1     = FileUploadManager::getInstance('proof');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
    }
    else{
       $act_marks=$previousDataArray[0]['act_marks']; 
    }
    
    if($previousDataArray[0]['file']==''){
       $file='';
    }
    else{
       $file=$previousDataArray[0]['file']; 
    }
    
    $selfEvaluation=($act_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData31($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    
    if($file!=''){
     $act_marks=($costing)+($add_amount/10000);
    }
     
    //insert new records
    $ret=$appDataManager->insertProofData31($employeeId,$sessionId,$project,$agency,$test_input,$costing,$add_amount,$act_marks,$file);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          $act_marks=($costing)+($add_amount/10000);
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof31/',$newFileName)) {
            $ret=$appDataManager->updateProofData31($proofDataId,$newFileName,$act_marks,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act_marks=($costing)+($add_amount/10000);
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
   }
     
     //now update main appraisal table
     $selfEvaluation= ($act_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
 
else if($proofId==32){
     
     $project     = add_slashes(trim($REQUEST_DATA['project'])); 
     $agency      = add_slashes(trim($REQUEST_DATA['agency']));
     $test_input  = add_slashes(trim($REQUEST_DATA['test_input']));
     $costing     = add_slashes(trim($REQUEST_DATA['costing'])); 
     $add_amount  = add_slashes(trim($REQUEST_DATA['add_amount']));
     
     $maxValue=10000*250; 
    
     //check of max. file size
     if($_FILES['proof']['name']!='' and $_FILES['proof']['tmp_name']==''){
      echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
      die;
     }
    
        
    if($project!=''){
        if($agency==''){
          echo '<script language="javascript">parent.fileUploadError("Enter name of agency",2)</script>';
          die;   
        }
    }
    if($agency!=''){
       if($test_input==''){
          echo '<script language="javascript">parent.fileUploadError("Select delivery date",2)</script>';
          die;   
       } 
    }
    if($test_input!=''){
        if($costing=='' or $costing==0){
          echo '<script language="javascript">parent.fileUploadError("Select costing",2)</script>';
          die;
        }
    }
    
    if($costing!='' and $costing!=0){
      if($add_amount==''){
          echo '<script language="javascript">parent.fileUploadError("Please enter numeric values in addit. amt.",2)</script>';
          die;
      }
      if($project==''){
         echo '<script language="javascript">parent.fileUploadError("Enter project name",2)</script>';
         die; 
      }
    }

    if($add_amount==''){
      echo '<script language="javascript">parent.fileUploadError("Please enter numeric values in addit. amt.",2)</script>';
      die;
    }

    if(!is_numeric($add_amount)){
      echo '<script language="javascript">parent.fileUploadError("Please enter numeric values in addit. amt.",2)</script>';
      die;  
    }
    
    if($add_amount!=0){
      if($add_amount>$maxValue){
         echo '<script language="javascript">parent.fileUploadError("Addit. amt. can not be more than '.$maxValue.'",2)</script>';
         die;
      } 
      if($project==''){
         echo '<script language="javascript">parent.fileUploadError("Enter project name",2)</script>';
         die; 
      }
    }
    
    $fileObj1     = FileUploadManager::getInstance('proof');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
    }
    else{
       $act_marks=$previousDataArray[0]['act_marks']; 
    }
    
    if($previousDataArray[0]['file']==''){
       $file='';
    }
    else{
       $file=$previousDataArray[0]['file']; 
    }
    
    $selfEvaluation=($act_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData32($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
        
    if($file!=''){
     $act_marks=($costing)+intval($add_amount/10000)*2;
    }
     
    //insert new records
    $ret=$appDataManager->insertProofData32($employeeId,$sessionId,$project,$agency,$test_input,$costing,$add_amount,$act_marks,$file);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          $act_marks=($costing)+intval($add_amount/10000)*2;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof32/',$newFileName)) {
            $ret=$appDataManager->updateProofData32($proofDataId,$newFileName,$act_marks,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act_marks=($costing)+intval($add_amount/10000)*2;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
   }
     
  //now update main appraisal table
  $selfEvaluation= ($act_marks);
  doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
     
 }
 
else if($proofId==33){
     
    $description = add_slashes(trim($REQUEST_DATA['description'])); 
    $byWhom      = add_slashes(trim($REQUEST_DATA['byWhom']));
    $test_input  = add_slashes(trim($REQUEST_DATA['test_input'])); 
    
    //check of max. file size
    if($_FILES['proof']['name']!='' and $_FILES['proof']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($description!=''){
        if($byWhom==''){
          echo '<script language="javascript">parent.fileUploadError("Enter value",2)</script>';
          die;   
        }
    }
    if($byWhom!=''){
        if($test_input==''){
          echo '<script language="javascript">parent.fileUploadError("Enter receiving date",2)</script>';
          die;   
        }
    }
    if($test_input!=''){
        if($description==''){
          echo '<script language="javascript">parent.fileUploadError("Enter description",2)</script>';
          die;   
        }
    }
    
    $fileObj1     = FileUploadManager::getInstance('proof');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act_marks']==''){
       $act_marks=0;
    }
    else{
       $act_marks=$previousDataArray[0]['act_marks']; 
    }
    
    if($previousDataArray[0]['file']==''){
       $file='';
    }
    else{
       $file=$previousDataArray[0]['file']; 
    }
    
    $selfEvaluation=($act_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData33($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData33($employeeId,$sessionId,$description,$byWhom,$test_input,$act_marks,$file);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof33/',$newFileName)) {
            $ret=$appDataManager->updateProofData33($proofDataId,$newFileName,200,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act_marks=200;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
      }
      
     //now update main appraisal table
     $selfEvaluation= ($act_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
 }
 
else if($proofId==34){
     
    $description1 = add_slashes(trim($REQUEST_DATA['description1']));
    $byWhom1      = add_slashes(trim($REQUEST_DATA['byWhom1']));
    $test_input1  = add_slashes(trim($REQUEST_DATA['test_input1']));
    
    $description2 = add_slashes(trim($REQUEST_DATA['description2']));
    $byWhom2      = add_slashes(trim($REQUEST_DATA['byWhom2']));
    $test_input2  = add_slashes(trim($REQUEST_DATA['test_input2']));
    
    //check of max. file size
    if($_FILES['proof1']['name']!='' and $_FILES['proof1']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    //check of max. file size
    if($_FILES['proof2']['name']!='' and $_FILES['proof2']['tmp_name']==''){
     echo '<script language="javascript">parent.fileUploadError("Size of file must be euqal to or less than '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).'MB",2)</script>';
     die;
    }
    
    if($description1!=''){
        if($byWhom1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter value",2)</script>';
          die;   
        }
    }
    if($byWhom1!=''){
        if($test_input1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter receiving date",2)</script>';
          die;   
        }
    }
    if($test_input1!=''){
        if($description1==''){
          echo '<script language="javascript">parent.fileUploadError("Enter description",2)</script>';
          die;   
        }
    }
    
    if($description2!=''){
        if($byWhom2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter value",2)</script>';
          die;   
        }
    }
    if($byWhom2!=''){
        if($test_input2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter receiving date",2)</script>';
          die;   
        }
    }
    if($test_input2!=''){
        if($description2==''){
          echo '<script language="javascript">parent.fileUploadError("Enter description",2)</script>';
          die;   
        }
    }
    
    $fileObj1     = FileUploadManager::getInstance('proof1');
    $fileTmpName1 = $fileObj1->tmp;
    $fileName1    = $fileObj1->name;
    $fileExt1     = $fileObj1->fileExtension;
    
    $fileObj2     = FileUploadManager::getInstance('proof2');
    $fileTmpName2 = $fileObj2->tmp;
    $fileName2    = $fileObj2->name;
    $fileExt2     = $fileObj2->fileExtension;
    
    if($fileName1!=''){
      if(!in_array($fileExt1,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($fileName2!=''){
      if(!in_array($fileExt2,$allowedFilesForAppraisal)){
          echo '<script language="javascript">parent.fileUploadError("This file extension is not allowed",2)</script>';
          die; 
      }
    }
    
    if($previousDataArray[0]['act1_marks']==''){
       $act1_marks=0;
    }
    else{
       $act1_marks=$previousDataArray[0]['act1_marks']; 
    }
    
    if($previousDataArray[0]['file1']==''){
       $file1='';
    }
    else{
       $file1=$previousDataArray[0]['file1']; 
    }
    
    if($previousDataArray[0]['act2_marks']==''){
       $act2_marks=0;
    }
    else{
       $act2_marks=$previousDataArray[0]['act2_marks']; 
    }
    
    if($previousDataArray[0]['file2']==''){
       $file2='';
    }
    else{
       $file2=$previousDataArray[0]['file2']; 
    }
    
    $selfEvaluation=($act1_marks+$act2_marks);
    
    //now add data in proof table
    //delete prev records
    $ret=$appDataManager->deleteProofData34($employeeId,$sessionId);
    if($ret==false){
        echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
        die; 
    }
    
    //insert new records
    $ret=$appDataManager->insertProofData34($employeeId,$sessionId,$description1,$byWhom1,$test_input1,$act1_marks,$file1,$description2,$byWhom2,$test_input2,$act2_marks,$file2);
    if($ret==false){
       echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
       die;
    }

    //get the last insert id
    $proofDataId=SystemDatabaseManager::getInstance()->lastInsertId();

    if($fileName1!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt1;
          if($fileObj1->upload(IMG_PATH.'/Appraisal/Proof34/First/',$newFileName)) {
            $ret=$appDataManager->updateProofData34($proofDataId,$newFileName,100,1);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act1_marks=100;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
    }
    
    if($fileName2!=''){
          //upload the file
          $newFileName=$proofDataId.'.'.$fileExt2;
          if($fileObj2->upload(IMG_PATH.'/Appraisal/Proof34/Second/',$newFileName)) {
            $ret=$appDataManager->updateProofData34($proofDataId,$newFileName,100,2);
            if($ret==false){
             echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
             die;
            }
            $act2_marks=100;
            logError($fileObj1->message);
        }
        else{
           echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
           die;
        }
    }
      
     //now update main appraisal table
     $selfEvaluation= ($act1_marks+$act2_marks);
     doMainAppraisal($employeeId,$appraisalId,$selfEvaluation,0,$sessionId);
 }
    
 //commiting transaction
 if(SystemDatabaseManager::getInstance()->commitTransaction()) {
   echo '<script language="javascript">parent.fileUploadError("'.$selfEvaluation.'",1)</script>';
   die;
  }
  else {
   echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
   die;
  }   
}
else{
  echo '<script language="javascript">parent.fileUploadError("'.FAILURE.'",2)</script>';
  die;
}

// $History: fileUpload.php $
?>