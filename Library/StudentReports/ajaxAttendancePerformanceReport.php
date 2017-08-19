<?php
//This file sends the data, creates the image on runtime
// Author :Dipanjan Bhattacharjee
// Created on : 17-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAttendancePerformanceReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$reportManager = StudentReportsManager::getInstance();

$timeTableLabelId = trim($REQUEST_DATA['timeTable']);
$subjectId        = trim($REQUEST_DATA['subjectId']);
$classId          = trim($REQUEST_DATA['classId']);
$groupId          = trim($REQUEST_DATA['groupId']);
$dutyLeaves       = trim($REQUEST_DATA['dutyLeaves']);
$attendanceRange  = trim($REQUEST_DATA['attendanceRange']);
$reportFormat    =  trim($REQUEST_DATA['reportFormat']);



if($timeTableLabelId=='' or $subjectId=='' or $attendanceRange=='' or $classId=='' or $groupId==''){
    echo 'Required Paramaters Missing';
    die;
}


if($reportFormat=='') {
  $reportFormat=1;
}


//fidinging group hierarchy
$groupIds='';
if($groupId!='' and $groupId!=-1){
  $groupIds=$reportManager->getGroupHierarchy($classId,$groupId);
}


//validating input data
$queryConditions='';
$tR=explode(',',$attendanceRange);
$len1=count($tR);
$intervalArr=array();
for($i=0;$i<$len1;$i++){
    $tRange=explode('-',$tR[$i]);
    $len2=count($tRange);
    if($len2!=2){
        echo INVALID_ATTENDANCE_RANGE;
        die;
    }
    for($k=0;$k<$len2;$k++){
        if(!is_numeric(trim($tRange[$k]))){
           echo ENTER_NUMERIC_VALUE_FOR_ATTENDANCE_RANGE;
           die;
        }
    }
    if($queryConditions!=''){
        $queryConditions .=' , ';
    }
    //build the query conditions simultaneously
    if($dutyLeaves==0){
     $queryConditions .= ' SUM( IF( CEIL(per) BETWEEN '.trim($tRange[0]).'  AND '.trim($tRange[1]).' , 1, 0 ) ) AS "'.trim($tRange[0]).' - '.trim($tRange[1]).'"';
    }
    else{
     $queryConditions .= ' SUM( IF( CEIL(per2) BETWEEN '.trim($tRange[0]).'  AND '.trim($tRange[1]).' , 1, 0 ) ) AS "'.trim($tRange[0]).' - '.trim($tRange[1]).'"';
    }
    $intervalArr[]=trim($tRange[0]).' - '.trim($tRange[1]);
}


//first find subjects of this class corresponding to selected time table
$subjectCondition='';
if($subjectId!=-1 and $subjectId!=''){
    $subjectCondition=' AND t.subjectId='.$subjectId;
}

if($groupId!=-1 and $groupId!=''){
    $groupCondition2=' AND g.groupId IN ('.$groupIds.')';
}

$classSubjectArray=$reportManager->getTimeTableClassSubject(' AND c.classId='.$classId.' AND t.timeTableLabelId='.$timeTableLabelId.$subjectCondition.$groupCondition2);
$classSubjectArrayCount=count($classSubjectArray);
if(is_array($classSubjectArray) and $classSubjectArrayCount>0){
   for($x=0;$x<$classSubjectArrayCount;$x++){

    $genSubjectId=$classSubjectArray[$x]['subjectId'];
    $genSubjectName=$classSubjectArray[$x]['subjectName'].'( '.$classSubjectArray[$x]['subjectCode'].' )';

    //finding teaching employees of a subject
    //$teacherArray=$reportManager->getTimeTableClassSubjectTeacher(' AND c.classId='.$classId.' AND t.subjectId='.$subjectId.' AND t.timeTableLabelId='.$timeTableLabelId);
    if($groupId!=-1 and $groupId!=''){
      $groupCondition=' AND t.groupId IN ('.$groupIds.')';
      $groupCondition2=' AND att.groupId IN ('.$groupIds.')';
    }
    $teacherArray=$reportManager->getTimeTableClassSubjectTeacher(' AND c.classId='.$classId.' AND t.subjectId='.$genSubjectId.' AND t.timeTableLabelId='.$timeTableLabelId.$groupCondition);
    $teacherNameCodeString='';
    $extraTRString='';
    if(is_array($teacherArray) and count($teacherArray)>0){
        $extraTRString='';
        foreach($teacherArray as $teacher){
           $employeeId=$teacher['employeeId'];
           $teacherAttendanceString=' AND ttc.timeTableLabelId='.$timeTableLabelId.' AND c.classId='.$classId.$groupCondition2.' AND e.employeeId='.$employeeId.' AND att.subjectId='.$genSubjectId;
           if($teacherNameCodeString!=''){
                $teacherNameCodeString .='<br/>';
           }
           //fetch attendance record of this teacher
           $teacherAttendanceRecordArray=$reportManager->getTeacherAttendanceSummeryList($teacherAttendanceString);
           $teacherAttendane='';
           foreach($teacherAttendanceRecordArray as $attendanceRecord){
             if($teacherAttendane!=''){
                 $teacherAttendane .=',';
             }
             $teacherAttendane .=''.$attendanceRecord['groupName'].' ('.$attendanceRecord['totalDelivered'] .') ';
           }
           $extraTRString ='<tr class="row0">';
           if($teacherAttendane!=''){
             $teacherNameCodeString .=$teacher['employeeName'].' ( '.$teacher['employeeCode'].' ) [ '.$teacherAttendane.' ]';
           }
           else{
             $teacherNameCodeString .=$teacher['employeeName'].' ( '.$teacher['employeeCode'].' )';
           }
           $extraTRString .='<td class="padding_top" width="10%" ><b>Teachers</b></td><td class="padding_top" width="90%" colspan="'.($len1).'">'.$teacherNameCodeString.'</td>';
           $extraTRString .='</tr>';
        }
    }
    if($teacherNameCodeString==''){
        $teacherNameCodeString=NOT_APPLICABLE_STRING;
    }


    //Now fetch attendance distribution
    //$foundArray = $reportManager->getAttendanceDistribution($classId,$subjectId,$queryConditions);
    if($groupId!=-1 and $groupId!=''){
     $foundArray = $reportManager->getAttendanceDistribution($classId,$genSubjectId,$groupIds,$queryConditions);
    }
    else{
      $foundArray = $reportManager->getAttendanceDistribution($classId,$genSubjectId,'',$queryConditions);
    }
    $cnt = count($foundArray);

    $sameWidth=floor(86/$len2);

    $tableString .='<table cellpadding="1" cellspacing="1" width="100%">';
    $tableString .='<tr class="row0">
                      <td class="padding_top" width="10%" ><b>Subject</b></td>
                      <td class="padding_top" width="90%" colspan="'.($len1).'">'.$genSubjectName.'</td></tr>';
    $tableString .=$extraTRString;
    /*$tableString .='<tr class="row0">
                      <td class="padding_top" width="100%" colspan="'.($len1+1).'"><nobr><b>Teachers</b> : '.$teacherNameCodeString.'</nobr></td></tr>';*/
    $tableString .='<tr class="rowheading">
                      <td class="searchhead_text" width="14%">%age Range</td>';
    for($i=0;$i<$len1;$i++){
       $tRange=explode('-',$tR[$i]);
       $interval=trim($tRange[0]).' - '.trim($tRange[1]);
       $tableString .='<td class="searchhead_text" align="center" width="'.$sameWidth.'"><nobr>'.$interval.'%&nbsp;</nobr></td>';
    }
    $tableString .='</tr>';
    if($cnt==0){
       $tableString .='<tr class="row0">
                        <td class="padding_top" colspan="'.($len1+1).'" align="center">'.NO_DATA_FOUND.'</td>
                      </tr>';
    }
    else{
     if(trim($REQUEST_DATA['reportFormat'])==1){
      $tableString .='<tr class="row0">
                      <td class="padding_top" valign="top" valign="top"><b>Roll No. of students</b></td>';
     }
     else if(trim($REQUEST_DATA['reportFormat'])==2){
         $tableString .='<tr class="row0">
                      <td class="padding_top" valign="top" valign="top"><b>Name of students</b></td>';
     }
     else if(trim($REQUEST_DATA['reportFormat'])==3){
         $tableString .='<tr class="row0">
                      <td class="padding_top" valign="top" valign="top"><b>Roll No.--Name of students</b></td>';
     }
    /* else{
         $tableString .='<tr class="row0">
                      <td class="padding_top" valign="top" valign="top"><b>Roll No. of students</b></td>';
     }*/
      for($i=0;$i<$len1;$i++){
       $tRange=explode('-',$tR[$i]);
       $interval=trim($tRange[0]).' - '.trim($tRange[1]);
       if($dutyLeaves==0){
         $havingCondition=' HAVING per BETWEEN '.trim($tRange[0]).' AND '.trim($tRange[1]);
       }
       else{
         $havingCondition=' HAVING per2 BETWEEN '.trim($tRange[0]).' AND '.trim($tRange[1]);
       }
       //$rollNoArray=$reportManager->getAttendanceDistributionWithRollNo($classId,$subjectId,$havingCondition);
       if($groupId!=-1 and $groupId!=''){
         $rollNoArray=$reportManager->getAttendanceDistributionWithRollNo($classId,$genSubjectId,$groupIds,$havingCondition);
       }
       else{
         $rollNoArray=$reportManager->getAttendanceDistributionWithRollNo($classId,$genSubjectId,'',$havingCondition);
       }
       $align='left';
	   if(trim($REQUEST_DATA['reportFormat'])!=4){
		   if($rollNoArray[0]['concatenatedRollNo']==''){
			   $align='center';
			   $rollNoArray[0]['concatenatedRollNo']=NOT_APPLICABLE_STRING;
		   }
		   if($rollNoArray[0]['concatenatedName']==''){
			   $align='center';
			   $rollNoArray[0]['concatenatedName']=NOT_APPLICABLE_STRING;
		   }
		   if($rollNoArray[0]['concatenatedBoth']==''){
			   $align='center';
			   $rollNoArray[0]['concatenatedBoth']=NOT_APPLICABLE_STRING;
		   }
		   if(trim($REQUEST_DATA['reportFormat'])==1){
			$displayString=$rollNoArray[0]['concatenatedRollNo'];
		   }
		   else if(trim($REQUEST_DATA['reportFormat'])==2){
			 $displayString=$rollNoArray[0]['concatenatedName'];
		   }
		   else if(trim($REQUEST_DATA['reportFormat'])==3){
			 $displayString=$rollNoArray[0]['concatenatedBoth'];
		   }
		   else{
			 $displayString=$rollNoArray[0]['concatenatedRollNo'];
		   }
		   $tableString .='<td class="padding_top" align="'.$align.'" valign="top">'.$displayString.'</td>';
		  }
	  }
     $tableString .='</tr>';

     //total no of students
     $tableString .='<tr class="row1">
                      <td class="padding_top" valign="top"><b>Total No. of students</b></td>';
      for($i=0;$i<$len1;$i++){
       $tRange=explode('-',$tR[$i]);
       $interval=trim($tRange[0]).' - '.trim($tRange[1]);
       $tableString .='<td class="padding_top" align="center">'.$foundArray[0][$interval].'</td>';
      }
     $tableString .='</tr>';

    }
   $tableString .='</table>';

  }//end of for loop

}
else{
    echo NO_DATA_FOUND;
    die;
}

echo $tableString;
//$History: ajaxSubjectMarksDistribution.php $
?>