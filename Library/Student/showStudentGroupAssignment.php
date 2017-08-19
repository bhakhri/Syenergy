<?php
//-------------------------------------------------------
// This File contains Validation and ajax function used for group change
// Author :Ajinder Singh
// Created on : 07-Mar-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','UpdateStudentGroups');
	define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$rollNo = add_slashes($REQUEST_DATA['rollNo']);

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	$studentDetailArray = $studentManager->getStudentDetail($rollNo);
	if (!isset($studentDetailArray[0]['studentId']) or empty($studentDetailArray[0]['studentId'])) {
		echo INVALID_ROLL_NO;
		echo ' or Student Does not belong to Current Session/Institute';
		die;
	}
	$studentId = $studentDetailArray[0]['studentId'];
	$classId = $studentDetailArray[0]['classId'];

	$classThGroups = $studentManager->getClassGroupTypeGroups($classId,3,' AND isOptional=0');//Theory Groups
	$classTutGroups = $studentManager->getClassGroupTypeGroups($classId,1,' AND isOptional=0');//Tut Groups
	$classPrGroups = $studentManager->getClassGroupTypeGroups($classId,2,' AND isOptional=0');//Pr Groups
	$studentGroups = $studentManager->getStudentCurrentGroups($studentId,$classId);
    /*****CODE FOR OPTIONAL GROUPS*****/
    $studentOptionalSubjectGroupArray=$studentManager->getSubjectOptionalGroups($studentId,$classId);
    $cnt=count($studentOptionalSubjectGroupArray);
    if($cnt>0){
     $uniqueSubjectIds=array_values(array_unique(explode(',',UtilityManager::makeCSList($studentOptionalSubjectGroupArray,'subjectId'))));
     $uniqueSubjects=array_values(array_unique(explode(',',UtilityManager::makeCSList($studentOptionalSubjectGroupArray,'subjectCode'))));
     $uCnt=count($uniqueSubjectIds);
     $tableSting='<table border="1" width="100%" rules="all">';
     $tableSting .='<tr><td colspan="'.$uCnt.'">Optional Groups</td></tr>';
     $tableSting .='<tr>';
     for($i=0;$i<$uCnt;$i++){
         $tableSting .='<td>'.$uniqueSubjects[$i].'</td>';
     }
     $tableSting .='</tr>';
     $tableSting .='<tr>';
     for($i=0;$i<$uCnt;$i++){
         $tableSting .='<td>';
         $subjectId=$uniqueSubjectIds[$i];
         $tempString='<table border="0" width="100%" rules="none">';
         $k=0;
         for($j=0;$j<$cnt;$j++){
             if($studentOptionalSubjectGroupArray[$j]['subjectId']!=$subjectId){
                 continue;
             }
             if($k % 3 ==0){
                 $tempString .='<tr>';
             }
             $checked='';
             $className='';
             if($studentOptionalSubjectGroupArray[$j]['assignedGroup']==1){
                 $checked='checked="checked"';
                 $className='class="highlightPermission"';
             }
             $tempString .='<td '.$className.' id="td_'.$studentOptionalSubjectGroupArray[$j]['groupId'].'"><input onclick="changeClass('.$studentOptionalSubjectGroupArray[$j]['groupId'].',2);" '.$checked.' type="checkbox" name="chkop_'.$studentOptionalSubjectGroupArray[$j]['groupId'].'" value="1" />'.$studentOptionalSubjectGroupArray[$j]['groupShort'].'</td>';
             $k++;
             if($k % 3 ==0){
                 $tempString .='</tr>';
             }
         }
         if($k % 3 ==0){
              $tempString .='</tr>';
         }
         $tempString .='</table>';
         $tableSting .=$tempString.'</td>';
      }
     $tableSting .='</tr>';
     $tableSting .='</table>';
    }
    else{
        $tableSting=-1;
    }

    /*****CODE FOR OPTIONAL GRPS*****/
	$allGroupsArray = array('ThGroups'=>$classThGroups, 'TutGroups'=>$classTutGroups, 'PrGroups'=>$classPrGroups, 'studentGroups'=>$studentGroups,'studentOptionalGroupInfo'=>$tableSting);
	echo json_encode($allGroupsArray);

//$History: showStudentGroupAssignment.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/21/09    Time: 11:26a
//Updated in $/LeapCC/Library/Student
//file changed to correct attendance problem.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/24/09    Time: 11:30a
//Updated in $/LeapCC/Library/Student
//fixed bug no.1204
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/07/09    Time: 4:34p
//Created in $/LeapCC/Library/Student
//file added for group change
//
?>