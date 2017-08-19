<?php 
//This file is used as printing version for final marks
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    $timeTableLabelId = trim($REQUEST_DATA['timeTable']);
    $subjectId        = trim($REQUEST_DATA['subjectId']);
    $classId          = trim($REQUEST_DATA['classId']);
    $groupId          = trim($REQUEST_DATA['groupId']);
    $dutyLeaves       = trim($REQUEST_DATA['dutyLeaves']);
    $attendanceRange  = trim($REQUEST_DATA['attendanceRange']);

    if($timeTableLabelId=='' or $subjectId=='' or $attendanceRange=='' or $classId=='' or $groupId==''){
     echo 'Required Paramaters Missing';
     die;
    }
    
function generateHeaderString($subjectName,$teacherString){
  global $reportManager;
  global $REQUEST_DATA;     
  return $headerString='
        <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
          <tr>
        <td align="left" width="25%" class="">'.$reportManager->showHeader().'</td>
        <th align="center" width="50%"'.$reportManager->getReportTitleStyle().'valign="top">
            <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th align="center" colspan="1" nowrap '.$reportManager->getReportTitleStyle().'>'.$reportManager->getInstituteName().'</th>
            </tr>
             <tr><th colspan="3" '.$reportManager->getReportHeadingStyle().'valign="bottom">Student Attendance Performance Report</th></tr>
             <tr><td colspan="3" height="10px;"></td></tr>
            </table>
        </th>
        <td align="right" colspan="1" width="25%" valign="top">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" '.$reportManager->getDateTimeStyle().'>'.date("d-M-y").'</td>
                </tr>
                <tr>
                    <td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right">Time :&nbsp;</td><td valign="" colspan="1" '.$reportManager->getDateTimeStyle().'>'.date("h:i:s A").'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
     <td colspan="3">
      <table border="0" cellpadding="0" cellspacing="0" align="left">
          <tr>
               <td class="dataFont" width="10%"><nobr><b>Time Table&nbsp;</nobr></b></td>
               <td class="dataFont" width="1%"><b>:</b></td>
               <td class="dataFont" style="padding-left:10px;" colspan="4">'.trim($REQUEST_DATA['timeTableName']).'</td>
           </tr>
          <tr>
               <td class="dataFont"><b>Class</b></td>
               <td class="dataFont"><b>:</b></td>
               <td class="dataFont" style="padding-left:10px;" width="30%"><nobr>'.trim($REQUEST_DATA['className']).'</nobr></td>
               <td class="dataFont" width="7%"><b>Group</b></td>
               <td class="dataFont" width="1%"><b>:</b></td>
               <td class="dataFont" style="padding-left:10px;"><nobr>'.trim($REQUEST_DATA['groupName']).'</nobr></td>
           </tr>
           <tr>
               <td class="dataFont" width="10%"><nobr><b>Subject&nbsp;</nobr></b></td>
               <td class="dataFont"><b>:</b></td>
               <td class="dataFont" style="padding-left:10px;" colspan="4">'.$subjectName.'</td>
           </tr>
          <tr>
               <td class="dataFont" valign="top"><b>Teachers</b></td>
               <td class="dataFont" valign="top"><b>:</b></td>
               <td class="dataFont" style="padding-left:10px;" colspan="4"><nobr>'.$teacherString.'</nobr></td>
           </tr>
           <tr><td height="5px;" colspan="6"></td></tr>
        </table>
     </td>
    </tr>
   <tr><td colspan="3">'; 
}

function generateFooterString($totalRecords,$currentRecord){
  global $reportManager;  
  return  $footerString='</td></tr></table>
            <table border="0" cellspacing="0" class="reportTableBorder" width="90%" align="center">
             <tr><td valign="top" height="10" colspan="4"></td></tr>
             </table>
            <br>
            <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
             <tr>
              <td valign="" align="left" colspan="'.count($reportManager->tableHeadArray).'" '.$reportManager->getFooterStyle().'>'.$reportManager->showFooter().'</td>
              <td align="right" colspan="1" '.$reportManager->getFooterStyle().'>Page '.$currentRecord.' / '.$totalRecords.'</td>
             </tr>
            </table>';    
}
    //fidinging group hierarchy
    $groupIds='';
    if($groupId!='' and $groupId!=-1){
     $groupIds=$studentManager->getGroupHierarchy($classId,$groupId);
    }


//$subjectArray=$commonQueryManager->getSubjectInformation(' WHERE subjectId='.$subjectId);

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

$classSubjectArray=$studentManager->getTimeTableClassSubject(' AND c.classId='.$classId.' AND t.timeTableLabelId='.$timeTableLabelId.$subjectCondition.$groupCondition2);
$classSubjectArrayCount=count($classSubjectArray);

if(is_array($classSubjectArray) and $classSubjectArrayCount>0){
   for($x=0;$x<$classSubjectArrayCount;$x++){
       
    $genSubjectId=$classSubjectArray[$x]['subjectId'];
    $genSubjectName=$classSubjectArray[$x]['subjectName'].'( '.$classSubjectArray[$x]['subjectCode'].' )';

    //finding teaching employees of a subject
    //$teacherArray=$studentManager->getTimeTableClassSubjectTeacher(' AND c.classId='.$classId.' AND t.subjectId='.$subjectId.' AND t.timeTableLabelId='.$timeTableLabelId);
    if($groupId!=-1 and $groupId!=''){
      $groupCondition=' AND t.groupId IN ('.$groupIds.')';
      $groupCondition2=' AND att.groupId IN ('.$groupIds.')';
    }
    $teacherArray=$studentManager->getTimeTableClassSubjectTeacher(' AND c.classId='.$classId.' AND t.subjectId='.$genSubjectId.' AND t.timeTableLabelId='.$timeTableLabelId.$groupCondition);
    $teacherNameCodeString='';
    if(is_array($teacherArray) and count($teacherArray)>0){
        foreach($teacherArray as $teacher){
           $employeeId=$teacher['employeeId'];
           $teacherAttendanceString=' AND ttc.timeTableLabelId='.$timeTableLabelId.' AND c.classId='.$classId.$groupCondition2.' AND e.employeeId='.$employeeId.' AND att.subjectId='.$genSubjectId; 
           if($teacherNameCodeString!=''){
            $teacherNameCodeString .='<br/>';
           } 
           //fetch attendance record of this teacher
           $teacherAttendanceRecordArray=$studentManager->getTeacherAttendanceSummeryList($teacherAttendanceString);
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
             $teacherNameCodeString .=$teacher['employeeName'].' ( '.$teacher['employeeCode'].' ) ';
           }
        }
    }
    if($teacherNameCodeString==''){
        $teacherNameCodeString=NOT_APPLICABLE_STRING;
    }

    //Now fetch attendance distribution
    //$foundArray = $studentManager->getAttendanceDistribution($classId,$subjectId,$queryConditions);
    if($groupId!=-1 and $groupId!=''){ 
     $foundArray = $studentManager->getAttendanceDistribution($classId,$genSubjectId,$groupIds,$queryConditions);
    }
    else{
      $foundArray = $studentManager->getAttendanceDistribution($classId,$genSubjectId,'',$queryConditions);  
    }
    $cnt = count($foundArray);

    $sameWidth=floor(86/$len2);

    $tableString .=generateHeaderString($genSubjectName,$teacherNameCodeString);
    $tableString .='<table border="1" cellspacing="0" class="reportTableBorder" width="100%">';
    /*
    $tableString .='<tr class="row0">
                      <td class="dataFont" width="100%" colspan="'.($len1+1).'"><nobr><b>Subject</b> : '.$genSubjectName.'    &nbsp;&nbsp;<b>Teachers</b> : '.$teacherNameCodeString.'</nobr></td></tr>';
    */
    $tableString .='<tr >
                      <th align="left" class = "headingFont" width="14%">%age Range</td>';
    for($i=0;$i<$len1;$i++){
       $tRange=explode('-',$tR[$i]); 
       $interval=trim($tRange[0]).' - '.trim($tRange[1]);
       $tableString .='<td class = "headingFont" align="center" width="'.$sameWidth.'"><nobr>'.$interval.'%&nbsp;</nobr></td>';
    } 
    $tableString .='</tr>';
    if($cnt==0){
       $tableString .='<tr class="row0">
                        <td class="dataFont" colspan="'.($len1+1).'" align="center">'.NO_DATA_FOUND.'</td>
                      </tr>'; 
    }
    else{
     //roll no of students
      if(trim($REQUEST_DATA['reportFormat'])==1){
      $tableString .='<tr class="row0">
                      <td class="dataFont" valign="top" valign="top"><b>Roll No. of students</b></td>';
      }
     else if(trim($REQUEST_DATA['reportFormat'])==2){
         $tableString .='<tr class="row0">
                      <td class="dataFont" valign="top" valign="top"><b>Name of students</b></td>';
     }
     else if(trim($REQUEST_DATA['reportFormat'])==3){
         $tableString .='<tr class="row0">
                      <td class="dataFont" valign="top" valign="top"><b>Roll No.--Name of students</b></td>';
     }
     else{
         $tableString .='<tr class="row0">
                      <td class="dataFont" valign="top" valign="top"><b>Roll No. of students</b></td>';
      }
      for($i=0;$i<$len1;$i++){
       $tRange=explode('-',$tR[$i]);    
       $interval=trim($tRange[0]).' - '.trim($tRange[1]);
       if($dutyLeaves==0){
         $havingCondition=' HAVING per BETWEEN '.trim($tRange[0]).' AND '.trim($tRange[1]);
       }
       else{
         $havingCondition=' HAVING per2 BETWEEN '.trim($tRange[0]).' AND '.trim($tRange[1]);   
       }
       //$rollNoArray=$studentManager->getAttendanceDistributionWithRollNo($classId,$subjectId,$havingCondition);
       if($groupId!=-1 and $groupId!=''){ 
         $rollNoArray=$studentManager->getAttendanceDistributionWithRollNo($classId,$genSubjectId,$groupIds,$havingCondition);
       }
       else{
         $rollNoArray=$studentManager->getAttendanceDistributionWithRollNo($classId,$genSubjectId,'',$havingCondition);  
       }
       $align='left';
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
       $tableString .='<td class="dataFont" align="'.$align.'" valign="top">'.$displayString.'</td>';
      } 
     $tableString .='</tr>';
     
     //total no of students
     $tableString .='<tr >
                      <td class="dataFont" valign="top"><b>Total No. of students</b></td>';   
      for($i=0;$i<$len1;$i++){
       $tRange=explode('-',$tR[$i]);    
       $interval=trim($tRange[0]).' - '.trim($tRange[1]);
       $tableString .='<td class="dataFont" align="center">'.$foundArray[0][$interval].'</td>';
      } 
     $tableString .='</tr>';

    }
    $tableString .='</table>'.generateFooterString($classSubjectArrayCount,($x+1)).'<br class="page" />';

  }//end of for loop

}    
    
echo $tableString;

// $History: subjectWiseMarksComparisonPrint.php $
?>