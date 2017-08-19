<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE class drop down[subject centric]
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
if(trim($REQUEST_DATA['subjectId'])!= '' and trim($REQUEST_DATA['classId'])!= '' ) {
    
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    $groupTypeArray = $teacherManager->getSubjectGroupTypes($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId']);
    $groupTypeIdList = UtilityManager::makeCSList($groupTypeArray, 'groupTypeId');
    $groupTypeIdListArray = explode(',', $groupTypeIdList);
    
    $timeTableLabelTypeConditions='';
    $date=date('Y-m-d');
    if($sessionHandler->getSessionVariable('TeacherTimeTableLabelType')==DAILY_TIMETABLE){
      $timeTableLabelTypeConditions=' AND t.fromDate <="'.$date.'"';
    }

    //if there is no restriction on lec-tuu group hierarchy
    if($sessionHandler->getSessionVariable('TEST_GROUP_RESTRICTION')!=1){
      $foundArray = $teacherManager->getSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'], " ",$timeTableLabelTypeConditions);  
      if(is_array($foundArray) && count($foundArray)>0 ) {  
         echo json_encode($foundArray);
      }
      else {
        echo 0;
      }
      die;  
    }
    /*Following code is added by Ajinder: We need to pick tutorial groups if the teacher is teaching tut groups, then show only tut groups else show theory groups*/
    if (in_array(1,$groupTypeIdListArray)) {
        //for tutorial, pick only tutorial groups
        $foundArray = $teacherManager->getSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'], " AND g.groupTypeId = 1 ",$timeTableLabelTypeConditions);
    }
    elseif (in_array(2,$groupTypeIdListArray)) {
        //for practical, pick only practical groups
        $foundArray = $teacherManager->getSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'], " AND g.groupTypeId = 2 ",$timeTableLabelTypeConditions);
    }
    else {
        //for theory, only theory groups found for this teacher, this subject in the timetable
        $groupTypeId = $groupTypeArray[0]['groupTypeId'];
        if ($groupTypeId == 3) {
           $foundArray = $teacherManager->getSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'], " AND g.groupTypeId = '$groupTypeId' ",$timeTableLabelTypeConditions);
            //checking if Tut is taken by any other teacher.
            $foundArray2 = $teacherManager->getSubjectGroupOtherTeacher($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'], " AND g.groupTypeId = 1 ");
            if (count($foundArray2) != 0) {
                $foundArray = array();
            }
        }
        else {
            $foundArray = $teacherManager->getSubjectGroup($REQUEST_DATA['subjectId'],$REQUEST_DATA['classId'], " AND g.groupTypeId = '$groupTypeId' ",$timeTableLabelTypeConditions);
        }
    }
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxTestGroupPopulate.php $
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 17/04/10   Time: 17:25
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made changes in Teacher module for DAILY_TIMETABLE issues 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 29/12/09   Time: 15:58
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected query in test marks module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 20/10/09   Time: 11:01
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Ajinder : Changed logic to populate groups having groupId greater than
//3
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 4/14/09    Time: 3:32p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//added code for checking if Tut is taken by any other teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/04/09    Time: 16:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added class check during group populate
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/06/09    Time: 1:02p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//new ajax file to get group for practical & theory
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/30/09    Time: 3:46p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//updated code to show only tut groups when the teacher is taking tut
//groups else show theory groups.
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/23/09    Time: 11:50a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/12/09    Time: 11:49a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//modified the files for topics taught
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/10/09    Time: 4:17p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/01/09   Time: 14:57
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Added the functionality:
//Teacher can select topics covered and enter his/her comments
//when taking attendance.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:36p
//Created in $/Leap/Source/Library/Teacher
?>