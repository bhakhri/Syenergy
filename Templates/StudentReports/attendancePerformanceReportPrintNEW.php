<?php 
//This file is used as printing version for final marks
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
	$medicalLeaves    = trim($REQUEST_DATA['medicalLeaves']);
	$attendanceRange  = trim($REQUEST_DATA['attendanceRange']);
	$reportFormat     =  trim($REQUEST_DATA['reportFormat']);

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
$tR=explode(',',$attendanceRange); 
$intervalArr = array();
$finalRangArray = array();
$len1=count($tR);
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
    $finalRangArray[$i]['low']=trim($tRange[0]);
    $finalRangArray[$i]['high']=trim($tRange[1]);
    $finalRangArray[$i]['univRollNo']='';
    $finalRangArray[$i]['studentName']='';
    $finalRangArray[$i]['rollNoName']='';
    $finalRangArray[$i]['studentCount']='0';
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
    $consolidated = '1';
    $conditions = " AND att.classId = $classId AND att.subjectId = $genSubjectId";
    $orderBy = "universityRollNo, rollNo";
    if($groupId!=-1 and $groupId!=''){
    //$foundArray = $reportManager->getAttendanceDistribution($classId,$genSubjectId,$groupIds,$queryConditions);
      $conditions.=" AND att.groupId IN ('$groupIds')";
      $foundArray = $commonQueryManager->getStudentAttendanceReport($conditions,$orderBy,$consolidated);
    }
    else{
      $foundArray = $commonQueryManager->getStudentAttendanceReport($conditions,$orderBy,$consolidated);
    } 
    $cnt = count($foundArray);
    
    for($k=0; $k<$cnt ; $k++) {
    	//if duty leave is checked and medical is not
    	if($dutyLeaves==1 && $medicalLeaves!=1){
    		$percentage=ceil((($foundArray[$k]['attended'] + $foundArray[$k]['leaveTaken'])/ $foundArray[$k]['delivered'])*100);
    	}
    	//if duty and medical leaves are checked OR medical leave is checked but duty leave is not
    	if(($medicalLeaves==1 && $dutyLeaves==1) || ($medicalLeaves==1 && $dutyLeaves!=1) ){
    		$percentage=ceil($foundArray[$k]['per']);
    	}
    	// if neither duty nor medical leaves are checked
    	if($dutyLeaves!=1 && $medicalLeaves!=1){
    		$percentage=ceil($foundArray[$k]['per1']);
    	}
    	
    	//if university roll number is not entered
    	if($foundArray[$k]['universityRollNo']=='---'){
    		$foundArray[$k]['universityRollNo']="N.A";
    	}

 		//check for range and store the details
    	for($i=0; $i<count($finalRangArray) ; $i++){
			if($percentage >= $finalRangArray[$i]['low'] && $percentage <=  $finalRangArray[$i]['high']){
				if($finalRangArray[$i]['univRollNo'] == ''){
					$finalRangArray[$i]['univRollNo']=$foundArray[$k]['universityRollNo'];
				}
				else{
					$finalRangArray[$i]['univRollNo'].=" , ".$foundArray[$k]['universityRollNo'];
				}
				if($finalRangArray[$i]['studentName'] == ''){
					$finalRangArray[$i]['studentName']=$foundArray[$k]['studentName'];
				}
				else{
					$finalRangArray[$i]['studentName'].=" , ".$foundArray[$k]['studentName'];
				}
				if($finalRangArray[$i]['rollNoName'] == ''){
					$finalRangArray[$i]['rollNoName']=trim($foundArray[$k]['universityRollNo'])."--".trim($foundArray[$k]['studentName']);
				}
				else{
					$finalRangArray[$i]['rollNoName'].=",<br>".trim($foundArray[$k]['universityRollNo'])."--".trim($foundArray[$k]['studentName']);
				}
				$finalRangArray[$i]['studentCount']++;
			}//end of if
    	}//end of for i loop
    }//end of for k loop

    $sameWidth=floor(86/$len2);

    $tableString .=generateHeaderString($genSubjectName,$teacherNameCodeString);
    $tableString .='<table border="1" cellspacing="0" class="reportTableBorder" width="100%">';
    
    $tableString .='<tr >
                      <th align="left" class = "headingFont" width="14%">%age Range</td>';
    for($i=0;$i<$len1;$i++){
       $tableString .='<td class = "headingFont" align="center" width="'.$sameWidth.'"><nobr>'.$intervalArr[$i].'%&nbsp;</nobr></td>';
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
     
     for($i=0; $i<count($finalRangArray); $i++) {
     	$align='left';
     	$displayString='';
     	if($reportFormat!=4){
		 	if($finalRangArray[$i]['studentCount'] == 0){
		 		$align='center';
		 		$displayString=NOT_APPLICABLE_STRING;
		 	}
		 	else{
		 		if($reportFormat==1){
					$displayString=$finalRangArray[$i]['univRollNo'];
				}
				else if($reportFormat==2){
					 $displayString=$finalRangArray[$i]['studentName'];
				}
				else if($reportFormat==3){
					 $displayString=$finalRangArray[$i]['rollNoName'];
				}
				else{
					 $displayString=$finalRangArray[$i]['univRollNo'];
				}
		 	}
		 	$tableString .='<td class="dataFont" align="'.$align.'" valign="top">'.$displayString.'</td>';
	 	}
     }//end of i loop
     $tableString .='</tr>';
    
     //total no of students
     $tableString .='<tr >
                      <td class="dataFont" valign="top"><b>Total No. of students</b></td>';   
      for($i=0;$i<count($finalRangArray);$i++){
       $tableString .='<td class="dataFont" align="center">'.$finalRangArray[$i]['studentCount'].'</td>';
       //empty the array side-by-side
        $finalRangArray[$i]['univRollNo']='';
		$finalRangArray[$i]['studentName']='';
		$finalRangArray[$i]['rollNoName']='';
		$finalRangArray[$i]['studentCount']='0';
      } 
     $tableString .='</tr>';

    }
    $tableString .='</table>'.generateFooterString($classSubjectArrayCount,($x+1)).'<br class="page" />';

  }//end of for loop

}    //end of classSubjectArray
    
echo $tableString;

// $History: subjectWiseMarksComparisonPrint.php $
?>
