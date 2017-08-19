<?php
//-------------------------------------------------------
// THIS FILE IS USED TO update student class 
// Author : Dipanjan Bhattacharjee
// Created on : (29.06.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ChangeStudentBranch');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$classId=trim($REQUEST_DATA['classId']);
if($classId==''){
    echo SELECT_CLASS;
    die;
}

$inputString=trim($REQUEST_DATA['inputString']);
if($inputString==''){
    echo NO_DATA_SUBMIT;
    die;
}

$inputArray=explode(',',$inputString);
$classIds='';
foreach($inputArray as $input){
    $classArray=explode('_',$input);
    if($classIds!=''){
        $classIds .=',';
    }
    if($classArray[0]==''){
        echo 'Student Information Missing';
        die;
    }
    if($classArray[1]==''){
        echo SELECT_NEW_CLASS_BRANCH;
        die;
    }
    $classIds .=$classArray[1];
}

if($classIds==''){
    echo NO_DATA_SUBMIT;
    die;
}

$uniqueClassIdsArray=array_values(array_unique(explode(',',$classIds)));
$uniqueClassIds=implode(',',$uniqueClassIdsArray);

//check whether OLD classId falls in new classes or not
if(in_array($classId,$uniqueClassIdsArray)){
    echo 'Old and new class can not be same';
    die;
}

require_once(MODEL_PATH . "/ChangeStudentBranchManager.inc.php");
$studentManager = ChangeStudentBranchManager::getInstance();

//check whether attendance and test has been conducted or not for old and new classes
$attArray1= $studentManager->checkAtttendance($classId);
if($attArray1[0]['cnt']!=0){
    echo "Attendance records found for old class";
    die;
}
$testArray1= $studentManager->checkTest($uniqueClassIds);
if($attArray1[0]['cnt']!=0){
    echo "Marks records found for new classes";
    die;
}

$attArray2= $studentManager->checkAtttendance($uniqueClassIds);
if($attArray2[0]['cnt']!=0){
    echo "Attendance records found for new classes";
    die;
}
$testArray2= $studentManager->checkTest($classId);
if($attArray2[0]['cnt']!=0){
    echo "Marks records found for new classes";
    die;
}


//now start the process
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
if(SystemDatabaseManager::getInstance()->startTransaction()) {
  
  foreach($inputArray as $input){
    $classArray=explode('_',$input);
    //update student class a.k.a branch
    $ret=$studentManager->updateStudentClass($classArray[0],$classArray[1]);
    if($ret==false){
        echo FAILURE;
        die;
    }
    //delete group allocation
    $ret=$studentManager->deleteStudentCompulsoryGroupAllocation($classArray[0],$classId);
    if($ret==false){
        echo FAILURE;
        die;
    }
    $ret=$studentManager->deleteStudentOptionalGroupAllocation($classArray[0],$classId);
    if($ret==false){
        echo FAILURE;
        die;
    }
  }
    
  if(SystemDatabaseManager::getInstance()->commitTransaction()) {
   echo SUCCESS;
   die;
  }
  else {
   echo FAILURE;
   die;
  }
 }
 else {
  echo FAILURE;
  die;
 } 

    
?>