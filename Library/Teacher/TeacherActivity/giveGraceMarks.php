<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of class wise attendance
//
// Author : Dipanjan Bbhattacharjee
// Created on : (07.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','edit');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    //if grace marks is not allowed
    /*
    if($sessionHandler->getSessionVariable('GRACE_MARKS_ALLOWED')!=1){
       echo 'You do not have the permission to give grace marks';
       die; 
    }
    */
    
    $classId=trim($REQUEST_DATA['classId']);
    if($classId==''){
         echo 'Required parameter missing';
         die;
    }
    /*CHECK FOR FROZEN CLASS*/
     $isFrozenArray=CommonQueryManager::getInstance()->checkFrozenClass($classId);
     if($isFrozenArray[0]['isFrozen']==1){
         echo FROZEN_CLASS_RESTRICTION.$isFrozenArray[0]['className'];
         die;
     }
    /*CHECK FOR FROZEN CLASS*/
    

    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    $studentIds=explode(',',$REQUEST_DATA['studentIds']);
    $graceMarks=explode(',',$REQUEST_DATA['graceMarks']);
    $cnt=count($studentIds);
    
    ////////////
  if(SystemDatabaseManager::getInstance()->startTransaction()) {
    for($i=0;$i<$cnt;$i++) {
        //create the condition
        $conditions ='';
        $conditions = " AND ttm.classId=".$REQUEST_DATA['classId']." AND ttm.subjectId=".$REQUEST_DATA['subjectId']." AND sg.groupId=".$REQUEST_DATA['group'].' AND s.studentId='.$studentIds[$i];
        
        if(trim($REQUEST_DATA['studentRollNo'])!=''){
          $conditions .=' AND s.rollNo="'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'"';
        }
        
        $graceMarksRecordArray = $teacherManager->getGraceMarksList($conditions,$REQUEST_DATA['subjectId']);
        
        //IF (MARKS SCORED+GRACE MARKS > MAX MARKS)*****************
        if(($graceMarks[$i] + $graceMarksRecordArray[0]['marksScored']) > $graceMarksRecordArray[0]['maxMarks'] ){
            echo GRACE_MARKS_VALIDATION;
            die;
        }
        else{
            $ret1=$teacherManager->deleteGraceMarks($studentIds[$i],$REQUEST_DATA['classId'],$REQUEST_DATA['subjectId']);
            if($ret1===true){
                $ret2=$teacherManager->insertGraceMarks($studentIds[$i],$REQUEST_DATA['classId'],$REQUEST_DATA['subjectId'],$graceMarks[$i]);
                if($ret2===false){
                    echo FAILURE;
                    die;
                }
            }
            else{
                echo FAILURE;
                die;
            }
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

    
// for VSS
// $History: giveGraceMarks.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 17/12/09   Time: 15:47
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added the code for "Freezed" class
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/12/09    Time: 12:07
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected code for mba
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 3  *****************
//User: Administrator Date: 13/06/09   Time: 11:19
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made bulk attendance,duty leaves and grace marks in teacher end
//configurable
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 11:06
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected query and logic in grace marks modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Grace Marks Master"
?>
