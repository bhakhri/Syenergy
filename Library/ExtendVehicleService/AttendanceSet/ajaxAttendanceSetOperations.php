<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceSetMaster');
$opeMode=trim($REQUEST_DATA['modeType']);
if($opeMode==1){
   $opeMode='add';
}
else if($opeMode==2){
   $opeMode='edit';
}
else if($opeMode==3){
   $opeMode='delete';
}
else{
   echo TECHNICAL_PROBLEM;
   die; 
}
define('ACCESS',$opeMode);

UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

   $errorMessage ='';
   if($opeMode=='add' or $opeMode=='edit'){ 
    if (!isset($REQUEST_DATA['setName']) || trim($REQUEST_DATA['setName']) == '') {
        $errorMessage .=  ENTER_ATTENDANCE_SET_NAME."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['setCondition']) || trim($REQUEST_DATA['setCondition']) == '')) {
        $errorMessage .= ATTENDANCE_SET_CONDITION_MISSING."\n";  
    }
   }
    if($opeMode=='edit' or $opeMode=='delete'){
        if(trim($REQUEST_DATA['setId'])==''){
            echo ATTENDANCE_SET_NOT_EXIST;
            die;
        }
        $setId=trim($REQUEST_DATA['setId']);
    }

    if (trim($errorMessage) == '') {
       require_once(MODEL_PATH . "/AttendanceSetManager.inc.php");
       $attendanceSetManager = AttendanceSetManager::getInstance();
       
       if($opeMode=='add' or $opeMode=='edit'){
        $setName      = trim($REQUEST_DATA['setName']);
        if(strlen($setName)>100){
            echo ATTENDANCE_SET_NAME_MAX_CHECK;
            die;
        }
        if(strlen($setName)==0){
            echo ATTENDANCE_SET_NAME_LENGTH;
            die;
        }
        
        $setCondition = trim($REQUEST_DATA['setCondition']);
        if($setCondition==1){
            $setCondition=PERCENTAGES;  //percentage
        }
        else if($setCondition==0){
            $setCondition=SLABS;  //slabs
        }
        else{
            echo INVALID_ATTENDANCE_SET_CRITERIA;
            die;
        }
       }
        
        //for addition
        if($opeMode=='add'){
           //check for duplicate names
	       $cond = "  WHERE UCASE(attendanceSetName)='".add_slashes(strtoupper($setName))."'"; 	
           $foundArray = $attendanceSetManager->getAttendanceSet($cond);
           if(trim($foundArray[0]['attendanceSetName'])!=''){
               echo ATTENDANCE_SET_NAME_ALREADY_EXIST;
               die;
           }
          
           //now add
           $ret=$attendanceSetManager->addAttendanceSet($setName,$setCondition);
           if($ret===true){
               echo SUCCESS;
               die;
           }
           else{
               echo FAILURE;
               die;
           }
        }
       //for editing 
       else if($opeMode=='edit'){
           //check for key existence
           $foundArray2 = $attendanceSetManager->getAttendanceSet(" WHERE attendanceSetId='".$setId."'");
           if(!is_array($foundArray2) or count($foundArray2)==0){
               echo ATTENDANCE_SET_NOT_EXIST;
               die;
           }
           
          //check for duplicate names
           $foundArray = $attendanceSetManager->getAttendanceSet(" WHERE UCASE(attendanceSetName)='".add_slashes(strtoupper($setName))."' AND attendanceSetId!='".$setId."'");
           if(trim($foundArray[0]['attendanceSetName'])!=''){
               echo ATTENDANCE_SET_NAME_ALREADY_EXIST;
               die;
           }
           
           //check in subject_to_class
           $foundArray = $attendanceSetManager->checkInSubjectToClass($setId);
           if($foundArray[0]['found']!=0){//if used in subject_to_class,then do not allow to edit criteria
             if($foundArray2[0]['evaluationCriteriaId']!=$setCondition){
                 echo EVALUATION_CRITERIA_RESTRICTION;
                 die;
             }
           }
           
           //now edit
           $ret=$attendanceSetManager->editAttendanceSet($setId,$setName,$setCondition);
           if($ret===true){
               echo SUCCESS;
               die;
           }
           else{
               echo FAILURE;
               die;
           }
           
       }
      //for deleting 
      else if($opeMode=='delete'){
          
          //check for key existence
           $foundArray2 = $attendanceSetManager->getAttendanceSet(" WHERE attendanceSetId='".$setId."'");
           if(!is_array($foundArray2) or count($foundArray2)==0){
               echo ATTENDANCE_SET_NOT_EXIST;
               die;
           }
           
          //check in subject_to_class,attendance_marks_percent and attendance_marks_slabs
          //if used then do not allow to delete
           $foundArray = $attendanceSetManager->checkInSubjectToClass($setId);
           if($foundArray[0]['found']!=0){
             echo DEPENDENCY_CONSTRAINT;
             die;
           }
           
           $foundArray = $attendanceSetManager->checkInAttendanceMarksPercent($setId);
           if($foundArray[0]['found']!=0){
             echo DEPENDENCY_CONSTRAINT;
             die;
           }
           
           $foundArray = $attendanceSetManager->checkInAttendanceMarksSlabs($setId);
           if($foundArray[0]['found']!=0){
             echo DEPENDENCY_CONSTRAINT;
             die;
           }
           
           //now delete
           $ret=$attendanceSetManager->deleteAttendanceSet($setId);
           if($ret===true){
               echo DELETE;
               die;
           }
           else{
               echo FAILURE;
               die;
           }
      }
      //if operation type does not match
      else{
        echo TECHNICAL_PROBLEM;
        die;  
      } 
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxAttendanceSetOperations.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/01/10   Time: 14:45
//Updated in $/LeapCC/Library/AttendanceSet
//Done bug fixing.
//Bug ids---
//0002683,0002682,0002268,0001960,
//0002619,0002623
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/12/09   Time: 13:38
//Created in $/LeapCC/Library/AttendanceSet
//Added  "Attendance Set Module"
?>
