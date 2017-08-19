<?php 
//This file is used as printing version for Attendance Register Print.
//
// Author : Parveen Sharma
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<script>
    //alert(location.search);
    var str=unescape(location.search);
    var strArray=str.split('heading=');
    var len=strArray.length;
    var heading=strArray[1];
</script>

<?php    
    ini_set('MEMORY_LIMIT','500M');
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);    
    UtilityManager::headerNoCache(); 
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();
     
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    $employeeId = $sessionHandler->getSessionVariable('EmployeeId'); 
    $timeTableLabelId = add_slashes($REQUEST_DATA['timeTable']);  
    $degree = add_slashes($REQUEST_DATA['degree']);
    $subjectId = add_slashes($REQUEST_DATA['subjectId']);  
    $groupId = add_slashes($REQUEST_DATA['groupId']);  
    $fromDate = add_slashes($REQUEST_DATA['fromDate']);
    $toDate= add_slashes($REQUEST_DATA['toDate']);
    $nosCol= add_slashes($REQUEST_DATA['nosCol']); 
    $consolidatedId = add_slashes($REQUEST_DATA['consolidatedId']);
    $reportType = add_slashes($REQUEST_DATA['reportType']);    
    $headings  = add_slashes($REQUEST_DATA['heading']);     
    $dutyLeave = add_slashes($REQUEST_DATA['dutyLeave']);      
      
    $blankSymbol= "X"; 
    $notMemberPrefix = "N";  
     
    if($reportType=='') {
      $reportType=1;  
    }
     
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';

        
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, studentName)';
        $sortField2 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",att.studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, rollNo)';
        $sortField2 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",att.studentId, rollNo)';
    }
    else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, universityRollNo)';
        $sortField2 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",att.studentId, universityRollNo)';
    }
    else {
       $sortField == 'studentName';
       $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, studentName)';
       $sortField2 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",att.studentId, studentName)'; 
    }
    
    $orderBy = " $sortField1 $sortOrderBy";    
    $orderBy2 = " $sortField2 $sortOrderBy";   
    
    
    $groupTypeId = -1;
    // Findout Group Type Id
    if($consolidatedId==1) {
       $groupTypeArray = $studentManager->getSingleField('`group`', 'groupTypeId', "WHERE groupId  = $groupId");
       $groupTypeId = $groupTypeArray[0]['groupTypeId'];
    }
    $rowspan2="";
    $rowspan3="";
    $colspan1=""; 
    $colspan2="";
    if($groupTypeId==1 || $groupTypeId==3) {
       $rowspan2="rowspan='2'";
       $rowspan3="rowspan='3'";
       $colspan1="colspan='2'"; 
       $colspan2="colspan='3'";          
    }
     
    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;  
    }
    
    if($degree=='') {
      $degree = 0;  
    }
   
    if($subjectId=='') {
      $subjectId = 0;  
    }
    
    if($groupId=='') {
      $groupId = 0;  
    }
    
    
    if($nosCol=='') {
      $nosCol=20;  
    }

    if($dutyLeave=='') {
      $dutyLeave=0; 
    }  
   
   
    if($employeeId=='') {
      $employeeId = 0;  
    } 
     
    $reportFormat = 1;
    
    $tableData = "";
    $studentCondition = "";
    $attendanceCondition = "";
    
    
    $countColumns=0;      
    
    // Findout Time Table Name
    $employeeNameArray = $studentManager->getSingleField('employee', "CONCAT(employeeName,' (',employeeCode,')') AS employeeName", "WHERE employeeId  = $employeeId");
    $employeeName = $employeeNameArray[0]['employeeName'];
    if($employeeName=='') {
      $employeeName = NOT_APPLICABLE_STRING;  
    }  
    
    // Findout Time Table Name
    $timeNameArray = $studentManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $timeTableLabelId");
    $timeTableName = $timeNameArray[0]['labelName'];
    if($timeTableName=='') {
      $timeTableName = NOT_APPLICABLE_STRING;  
    }
   
    // Findout Class Name
    if($degree != '') {   
      $classNameArray = $studentManager->getSingleField('class', 'className', "WHERE classId  = $degree");
      $className = $classNameArray[0]['className'];
      $className2 = str_replace("-",' ',$className);
    }
    
    if($subjectId != '') {   
      $classNameArray = $studentManager->getSingleField('subject', "CONCAT(subjectName,' (',subjectCode,')') AS subjectName", "WHERE subjectId  = $subjectId");
      $className = $classNameArray[0]['subjectName'];
      $subjectName = $className; 
    }
    
    if($groupId != '') {   
      $classNameArray = $studentManager->getSingleField('`group`', 'groupName', "WHERE groupId  = $groupId");
      $className = $classNameArray[0]['groupName'];
      $groupName = $className;
    }
    
    
    if($reportType==1) {
      $employeeCond = " AND tt.employeeId=$employeeId ";
    }
   
   // Find Student    
    $studentCondition = " $employeeCond AND c.classId = $degree AND tt.subjectId = $subjectId AND tt.groupId = $groupId AND tt.timeTableLabelId='".$timeTableLabelId."'"; 
    $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
    $cnt = count($studentArray);
    
    $ffStudentId = 0;    
    for($i=0; $i<$cnt; $i++) {
      $ffStudentId .= ",".$studentArray[$i]['studentId'];  
    }
    
    
    if($reportType==1) {     
      $employeeCond = " AND att.employeeId=$employeeId ";
    }
    
 // Findout Pervious Attended/Delivered Lecture Total 
    $tgroupId =  " AND att.studentId IN ($ffStudentId) AND att.groupId IN ($groupId)  $employeeCond ";
    $tgroupId1 = " AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1)  $employeeCond ";
    $tgroupId3 = " AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (3) $employeeCond ";
    
// Findout Duty Leave 
    $dtgroupId =  " AND dl.studentId IN ($ffStudentId) AND dl.groupId IN ($groupId) ";
    $dtgroupId1 = " AND dl.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1) ";
    $dtgroupId3 = " AND dl.studentId IN ($ffStudentId) AND gt.groupTypeId IN (3) ";    
    
    if($consolidatedId==1) {  
      if($groupTypeId==1 || $groupTypeId==3) {
        $tgroupId =  " AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1,3)  $employeeCond ";
        $dtgroupId = " AND dl.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1,3) "; 
      }  
    }
    
    $filter = " tt.studentId, tt.groupId, tt.groupTypeId,
                    IFNULL(SUM(tt.attended),0)  AS  attended,
                    IFNULL(SUM(tt.delivered),0) AS  delivered ";
    $field = " att.studentId, att.groupId, gt.groupTypeId,
                IF(att.isMemberOfClass =0,0,IF(att.attendanceType=2,(ac.attendanceCodePercentage /100),att.lectureAttended)) AS attended,
                IF(att.isMemberOfClass=0, 0,att.lectureDelivered) AS  delivered ";
    $attendanceCountCondition  = " AND att.classId = $degree AND att.subjectId = $subjectId  $tgroupId "; 
    $attendanceCountCondition .= " AND (att.fromDate < '$fromDate') ";  
    $groupBy=" att.classId, att.subjectId, att.groupId, att.studentId, att.fromDate, att.periodId";
    $groupBy1=" GROUP BY tt.studentId, tt.groupId, tt.groupTypeId "; 
    $perviousAttended =  $studentReportManager->getTotalDeliveredAttendance($attendanceCountCondition,$field,$groupBy,$filter,$groupBy1); 
    
    // Findout Previous Duty Leave Total
    if($dutyLeave==1) {
        $dFilter = " tt.studentId, tt.groupId, tt.groupTypeId, IFNULL(COUNT(tt.studentId),0) AS attended ";
        $dField  = " dl.studentId, dl.groupId, gt.groupTypeId, dl.dutyLeaveId ";
        $dAttendanceCountCondition  = " AND dl.classId = $degree AND dl.subjectId = $subjectId  $dtgroupId AND (dl.dutyDate < '$fromDate') ";  
        $dGroupBy=" dl.classId, dl.subjectId, dl.groupId, dl.studentId, dl.dutyDate, dl.periodId";
        $dGroupBy1=" GROUP BY tt.studentId, tt.groupId, tt.groupTypeId "; 
        $dPerviousAttended =  $studentReportManager->getStudentPreviousDutyLeave($dAttendanceCountCondition,$dField,$dGroupBy,$dFilter,$dGroupBy1); 
    }
    
    
    $studentCondition = "";
    $attendanceCondition = "";
    $tableData = "";
    $tableHeading = "";

    $search ='';
    $search1 ='';
    $search2 ='';                             
    $search3 ='';
    
    $search1 = "Time Table&nbsp;:&nbsp;$timeTableName<br>";
    $search1 .= "Degree&nbsp;:&nbsp;$className2<br>Subject&nbsp;:&nbsp;$subjectName<br>Group&nbsp;:&nbsp;$groupName<br>";
    $search1 .= "Teacher&nbsp;:&nbsp;$employeeName<br>"; 
    $search2 .= "From&nbsp;".UtilityManager::formatDate($fromDate);
    $search2 .= "&nbsp;&nbsp;To&nbsp;&nbsp;".UtilityManager::formatDate($toDate);
    $search3 .= "<br><script>unescape(document.write(heading));</script>";
    
    $search = $search1.$search2.$search3; 
    
    
      $colspan=4;  
      $tableHeading = "<table border='1' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'> 
                        <tr>
                            <td width='2%'  valign='middle' $rowspan3 ".$reportManager->getReportDataStyle()."  ><b>#</b></td>
                            <td width='5%'  valign='middle' $rowspan3 ".$reportManager->getReportDataStyle()."  align='left'><strong>Roll No.</strong></td>
                            <td width='5%'  valign='middle' $rowspan3 ".$reportManager->getReportDataStyle()."  align='left'><strong>URoll No.</strong></td>
                            <td width='10%' valign='middle' $rowspan3 ".$reportManager->getReportDataStyle()."  align='left'><strong>Student Name</strong></td>";
       
   
      if($cnt>0) {
         $attendanceCondition  = " AND att.classId = $degree AND att.subjectId = $subjectId "; 
         $attendanceCondition .= " AND (att.fromDate >= '$fromDate' AND att.fromDate <= '$toDate') $employeeCond "; 

         $dAttendanceCondition  = " AND dl.classId = $degree AND dl.subjectId = $subjectId "; 
         $dAttendanceCondition .= " AND (dl.dutyDate >= '$fromDate' AND dl.dutyDate <= '$toDate') "; 
         
         if($groupTypeId==1 || $groupTypeId==3) {
             
           // Date format
              $attendanceConditionDate =  $attendanceCondition." $tgroupId";
              $fieldName=" DISTINCT 
                                   att.fromDate, att.toDate "; 
              $dateOrderBy = "att.classId, att.subjectId, att.fromDate";
              $dateArray =  $studentReportManager->getStudentAttendanceData($fieldName, $attendanceConditionDate, $dateOrderBy);
              $cnt1 = count($dateArray);
           
           // Fetch Dates wise period           
              $attendanceConditionDate =  $attendanceCondition." $tgroupId1";
              $fieldName=" DISTINCT 
                                   att.fromDate, att.toDate, IFNULL(periodNumber,'') AS periodNumber "; 
              $dateOrderBy = "att.classId, att.subjectId, att.fromDate, att.periodId, att.studentId";
              $dateArray1 =  $studentReportManager->getStudentAttendanceData($fieldName, $attendanceConditionDate, $dateOrderBy);
              $cntDate1 = count($dateArray1);
             
              $attendanceConditionDate =  $attendanceCondition." $tgroupId3";
              $fieldName=" DISTINCT 
                                   att.fromDate, att.toDate, IFNULL(periodNumber,'') AS periodNumber "; 
              $dateOrderBy = "att.classId, att.subjectId, att.fromDate, att.periodId, att.studentId ";
              $dateArray3 =  $studentReportManager->getStudentAttendanceData($fieldName, $attendanceConditionDate, $dateOrderBy);
              $cntDate3 = count($dateArray3);
             
          
          // Fetch Dates wise attendance 
             $conslidated = "1";
             $attendanceConditionDate =  $attendanceCondition." $tgroupId";   
             $attendanceOrderBy  = $orderBy2.", att.classId, att.subjectId, att.fromDate, att.studentId, gt.groupTypeId, att.groupId, att.studentId ";
             $attendanceArray   = $studentReportManager->getStudentAttendanceData('',$attendanceConditionDate,$attendanceOrderBy,'',$conslidated);
             
             $attendanceConditionDate =  $attendanceCondition." $tgroupId1";   
             $attendanceOrderBy  = $orderBy2.", att.classId, att.subjectId, att.studentId, att.fromDate, gt.groupTypeId, att.groupId ";
             $attendanceArray1   = $studentReportManager->getStudentAttendanceData('',$attendanceConditionDate,$attendanceOrderBy,'',$conslidated);
            
             $attendanceConditionDate =  $attendanceCondition." $tgroupId3";   
             $attendanceOrderBy  = $orderBy2.", att.classId, att.subjectId, att.studentId, att.fromDate, gt.groupTypeId, att.groupId ";
             $attendanceArray3   = $studentReportManager->getStudentAttendanceData('',$attendanceConditionDate,$attendanceOrderBy,'',$conslidated);
             
             
             // Fetch Dates wise Duty Leaves  
             if($dutyLeave==1) {
                 $dattendanceConditionDate =  $dAttendanceCondition." $dtgroupId";   
                 $dattendanceOrderBy  = " dl.classId, dl.subjectId, dl.dutyDate, dl.studentId, gt.groupTypeId, dl.groupId, dl.studentId ";
                 $dattendanceArray   = $studentReportManager->getStudentFindDutyLeave('',$dattendanceConditionDate,$dattendanceOrderBy,'',$conslidated);
                 
                 $dattendanceConditionDate =  $dAttendanceCondition." $dtgroupId1";   
                 $dattendanceOrderBy  = " dl.classId, dl.subjectId, dl.studentId, dl.dutyDate, gt.groupTypeId, dl.groupId ";
                 $dattendanceArray1   = $studentReportManager->getStudentFindDutyLeave('',$dattendanceConditionDate,$dattendanceOrderBy,'',$conslidated);
                
                 $dattendanceConditionDate =  $dAttendanceCondition." $dtgroupId3";   
                 $dattendanceOrderBy  = " dl.classId, dl.subjectId, dl.studentId, dl.dutyDate, gt.groupTypeId, dl.groupId ";
                 $dattendanceArray3   = $studentReportManager->getStudentFindDutyLeave('',$dattendanceConditionDate,$dattendanceOrderBy,'',$conslidated);                 
                 
                 if($dutyLeave==1) {  
                    $dattendanceConditionDate =  $dAttendanceCondition." $dtgroupId";   
                    //$attendanceOrderBy = "att.studentId, att.periodId, att.fromDate "; 
                    $dattendanceOrderBy = " dl.classId, dl.subjectId, dl.studentId, dl.dutyDate, dl.periodId, dl.groupId, gt.groupTypeId ";
                    $dattendanceArray   = $studentReportManager->getStudentFindDutyLeave('',$dattendanceConditionDate,$dattendanceOrderBy);  
                 } 
             }
           
         }
         else {
             
           // Date format
             $attendanceConditionDate =  $attendanceCondition." $tgroupId";
             $fieldName=" DISTINCT 
                                   att.fromDate, att.toDate, IFNULL(periodNumber,'') AS periodNumber "; 
             $dateOrderBy = "att.classId, att.subjectId, att.fromDate, att.periodId";    
             $dateArray =  $studentReportManager->getStudentAttendanceData($fieldName, $attendanceConditionDate, $dateOrderBy);
             $cnt1 = count($dateArray);
             
             $attendanceConditionDate =  $attendanceCondition." $tgroupId";   
             //$attendanceOrderBy = "att.studentId, att.periodId, att.fromDate "; 
             $attendanceOrderBy = $orderBy2.", att.classId, att.subjectId, att.studentId, att.fromDate, att.periodId, att.groupId, gt.groupTypeId";  
             $attendanceArray   = $studentReportManager->getStudentAttendanceData('',$attendanceConditionDate,$attendanceOrderBy);
             
              if($dutyLeave==1) {  
                $dattendanceConditionDate =  $dAttendanceCondition." $dtgroupId";   
                //$attendanceOrderBy = "att.studentId, att.periodId, att.fromDate "; 
                $dattendanceOrderBy = " dl.classId, dl.subjectId, dl.studentId, dl.dutyDate, dl.periodId, dl.groupId, gt.groupTypeId ";
                $dattendanceArray   = $studentReportManager->getStudentFindDutyLeave('',$dattendanceConditionDate,$dattendanceOrderBy);  
             } 
             
         }
         
            
           
           if($groupTypeId==1 || $groupTypeId==3) {
               if($cnt1==0) {  
                 $rowspan = ""; 
                 $rowspan2="";   
                 $colspan1=""; 
                 $colspan2="";       
               }
               else {
                 $rowspan = $rowspan3;          
               }
            }
            else {
               if($cnt1==0) {  
                 $rowspan = "";           
                 $rowspan2="";
                 $colspan1=""; 
                 $colspan2="";       
               }
               else {
                 $rowspan = "rowspan='2'";          
               } 
            }
            
            $tableHeading = "<table border='1' cellpadding='0px' cellspacing='2px' width='100%' class='reportTableBorder'  align='center'> 
                            <tr>
                                <td width='2%'  valign='middle'  ".$reportManager->getReportDataStyle()."  $rowspan align='left'><b>#</b></td>
                                <td width='5%'  valign='middle'  ".$reportManager->getReportDataStyle()."  $rowspan align='left'><strong>Roll No.</strong></td>
                                <td width='5%'  valign='middle'  ".$reportManager->getReportDataStyle()."  $rowspan align='left'><strong>URoll No.</strong></td>
                                <td width='10%' valign='middle'  ".$reportManager->getReportDataStyle()."  $rowspan align='left'><strong>Student Name</strong></td>";
            //for($i=0; $i<$cnt1; $i++) { 
            if($cnt1>0) {  
               for($i=0;$i<$nosCol;$i++) {   
                   
                 $tableHeading .= "<td valign='top' width='2%' $colspan1 ".$reportManager->getReportDataStyle()."  align='center'><strong>".($i+1)."</strong></td>"; 
               }    
            }

             if($cnt1==0) { 
               for($j=0;$j<$nosCol;$j++) {
                  if($j<9) { 
                     $tableHeading .= "<td valign='middle' width='2%' ".$reportManager->getReportDataStyle()."  align='center'><strong>&nbsp;0".($j+1)."&nbsp;</strong></td>";   
                  }
                  else {
                     $tableHeading .= "<td valign='middle' width='2%' ".$reportManager->getReportDataStyle()."  align='center'><strong>&nbsp;".($j+1)."&nbsp;</strong></td>";   
                  }
                  $dd[$j]['heading']='';
                  $dd[$j]['dt']='';
                  $dd[$j]['dt1']=''; 
               }
            }
            
            if($groupTypeId==1 || $groupTypeId==3) {  
               $tableHeading .= "<td width='2%' valign='middle' $colspan2 ".$reportManager->getReportDataStyle()."  $rowspan2 align='center'><strong>Total</strong></td>
                              <td width='2%' valign='middle' $colspan2 ".$reportManager->getReportDataStyle()."  $rowspan2 align='center'><strong>%age</strong></td> ";   
            }
            else {
               $tableHeading .= "<td width='2%' valign='middle' ".$reportManager->getReportDataStyle()."  $rowspan align='center'><strong>Total</strong></td>
                              <td width='2%' valign='middle' ".$reportManager->getReportDataStyle()."   $rowspan align='center'><strong>%age</strong></td> ";     
            }                                                                                                                          
            $tableHeading .= "</tr>";
            
            if($cnt1 > 0) {  
                $tableHeading .= "<tr>"; 
                if($nosCol<=$cnt1) {
                  $countColumns=$nosCol;
                }
                else {
                  $countColumns = $cnt1;  
                }
                
                for($i=0; $i<$countColumns; $i++) {
                   $fromArr = explode('-',$dateArray[$i]['fromDate']);
                   $toArr = explode('-',$dateArray[$i]['toDate']);
                   $periodNumber = $dateArray[$i]['periodNumber'];
                   if($periodNumber=='') {
                     $periodNumber = NOT_APPLICABLE_STRING;  
                   }
                   if($periodNumber == NOT_APPLICABLE_STRING) { 
                     if($groupTypeId==1 || $groupTypeId==3) {  
                        if($fromArr==$toArr) {
                          $val = $fromArr[2].'/'.$fromArr[1];
                        }
                        else {
                           $val = $fromArr[2].'/'.$fromArr[1].'<br>to<br>'.$toArr[2].'/'.$toArr[1];  
                        }
                     }
                     else {
                        $val = $fromArr[2].'/'.$fromArr[1].'<br>to<br>'.$toArr[2].'/'.$toArr[1]; 
                     }
                     //."<br>".$periodNumber; 
                   } 
                   else {
                      $fromArr = explode('-',$dateArray[$i]['fromDate']);
                      $val = $fromArr[2].'/'.$fromArr[1]; 
                      if($groupTypeId!=1 && $groupTypeId!=3) {    
                        $val.="<br><div style='text-align:center'>".$periodNumber."</div>";
                      } 
                   }
                   $tableHeading .= "<td valign='top' width='5%' $colspan1 ".$reportManager->getReportDataStyle()."  align='center'><strong>".$val."</strong></td>";
                   $colspan = $colspan + 1;
                }  
                
                if($groupTypeId!=1 && $groupTypeId!=3) {  
                    for($i=$cnt1; $i<$nosCol; $i++) { 
                       $tableHeading .= "<td valign='top' width='5%' $colspan1 ".$reportManager->getReportDataStyle()."  align='center'><strong>&nbsp;</strong></td>"; 
                    }
                }
                if($groupTypeId==1 || $groupTypeId==3) {  
                    for($i=$countColumns; $i<$nosCol; $i++) {
                         $tableHeading .= "<td width='5%' valign='top' $colspan1 ".$reportManager->getReportDataStyle()."  align='center'>&nbsp;</td>";  
                    }
                }
                $tableHeading .= "</tr>";
       
              
               // Create a Lectrue & Tutorials 
                if($groupTypeId==1 || $groupTypeId==3) {    
                      $tableData .= "<tr>";
                      for($i=0; $i<$countColumns; $i++) {    
                        $tableHeading .= "<td valign='top' width='5%' ".$reportManager->getReportDataStyle()."  align='center'><strong>L</strong></td>
                                       <td valign='top' width='5%' ".$reportManager->getReportDataStyle()."  align='center'><strong>T</strong></td>"; 
                        $colspan+=2;
                      }
                      
                      for($i=$countColumns; $i<$nosCol; $i++) {
                         $tableHeading .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  colspan='2' align='center'>&nbsp;</td>";  
                         $colspan+=2; 
                      }
                      $tableHeading .= "<td valign='top' width='5%' ".$reportManager->getReportDataStyle()."  align='center'><strong>L</strong></td>
                                     <td valign='top' width='5%' ".$reportManager->getReportDataStyle()."  align='center'><strong>T</strong></td>
                                     <td valign='top' width='5%' ".$reportManager->getReportDataStyle()."  align='center'><strong>Total</strong></td>
                                     <td valign='top' width='5%' ".$reportManager->getReportDataStyle()."  align='center'><strong>L</strong></td>
                                     <td valign='top' width='5%' ".$reportManager->getReportDataStyle()."  align='center'><strong>T</strong></td>
                                     <td valign='top' width='5%' ".$reportManager->getReportDataStyle()."  align='center'><strong>Total</strong></td>
                                    </tr>";
                      $colspan +=6;   
                }
            }
      }
   
     $tableData = $tableHeading;
     $reportChk=0;  
  
                              
   
      if($groupTypeId==1 || $groupTypeId==3) {  
                  $k=0;
                  $cntAttendance =  count($dateArray);
                  for($i=0; $i<$cnt; $i++) {    
                        if(($i+1)%20==0) {
                            $tableData .= "</table>"; 
                            $reportChk=1;
                            reportGenerate($tableData,$search);    
                            $tableData = $tableHeading;
                        }
                        $reportChk=0;    
                          //$bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                          $tableData .= "<tr>
                                           <td valign='top' ".$reportManager->getReportDataStyle()."  align='left'>".($i+1)."</td>  
                                           <td valign='top' ".$reportManager->getReportDataStyle()."  align='left'>".$studentArray[$i]['rollNo']."</td>
                                           <td valign='top' ".$reportManager->getReportDataStyle()."  align='left'>".$studentArray[$i]['universityRollNo']."</td>
                                           <td valign='top' ".$reportManager->getReportDataStyle()."  align='left'>".$studentArray[$i]['studentName']."</td>";
                          $studentId = $studentArray[$i]['studentId'];
                          
                          $totalA_Tut = 0;
                          $totalD_Tut = 0; 
                          $totalA_Lec = 0;
                          $totalD_Lec = 0;
                          
                           $find=0; 
                          // Findout Pervious Duty Leaves
                          if($dutyLeave == 1) { 
                              for($k=0;$k<count($dPerviousAttended);$k++) {
                                 $aStudentId = $dPerviousAttended[$k]['studentId']; 
                                 if($aStudentId==$studentId) {
                                    $find=1; 
                                    if($dPerviousAttended[$k]['groupTypeId']==1) { 
                                      $totalA_Tut = $dPerviousAttended[$k]['attended']; 
                                    }
                                    if($perviousAttended[$k]['groupTypeId']==3) { 
                                      $totalA_Lec = $dPerviousAttended[$k]['attended']; 
                                    }
                                 }
                                 if($find==1 && $aStudentId!=$studentId) {
                                   break;  
                                 }
                              }
                          }
                          
                          
                          $find=0;
                          // Findout Pervious Attendacne 
                          for($k=0;$k<count($perviousAttended);$k++) {
                             $aStudentId = $perviousAttended[$k]['studentId']; 
                             if($aStudentId==$studentId) {
                                $find=1; 
                                if($perviousAttended[$k]['groupTypeId']==1) { 
                                  $totalA_Tut = $totalA_Tut + $perviousAttended[$k]['attended']; 
                                  $totalD_Tut = $perviousAttended[$k]['delivered'];    
                                }
                                if($perviousAttended[$k]['groupTypeId']==3) { 
                                  $totalA_Lec = $totalA_Lec + $perviousAttended[$k]['attended']; 
                                  $totalD_Lec = $perviousAttended[$k]['delivered'];    
                                }
                             }
                             if($find==1 && $aStudentId!=$studentId) {
                               break;  
                             }
                          }
                      
                          $k=0;
                          $j=0;
                          $dt=0;    
                          
                          // Findout Student Id  == Lecture 
                          $findL=0;
                          $kL=0;
                          for($kk=0;$kk<count($attendanceArray3);$kk++) {
                             $aStudentIdL = $attendanceArray3[$kk]['studentId']; 
                             $aFromDateL = '';
                             $aToDateL = '';
                             if($aStudentIdL==$studentId) {
                                $findL=1;
                                $aFromDateL = $attendanceArray3[$kk]['fromDate'];                                  
                                $aToDateL = $attendanceArray3[$kk]['toDate'];  
                                $findL=1;
                                $kL=$kk;
                                break; 
                             }
                          }
                          
                          // Findout Student Id  == Tutorial 
                          $findT=0;
                          $kT=0;
                          for($kk=0;$kk<count($attendanceArray1);$kk++) {
                             $aStudentIdT = $attendanceArray1[$kk]['studentId']; 
                             $aFromDateT = '';
                             $aToDateT = '';
                             if($aStudentIdT==$studentId) {
                                $findT=1;   
                                $aFromDateT = $attendanceArray1[$kk]['fromDate'];                                  
                                $aToDateT = $attendanceArray1[$kk]['toDate'];  
                                $findT=1;
                                $kT=$kk;
                                break; 
                             }
                          }
                          
                          for($k=0; $k<$countColumns; $k++) {
                              $dFromDate   = $dateArray[$k]['fromDate'];
                              $dToFromDate = $dateArray[$k]['toDate'];           
                              
                             
                              if($aFromDateL==$dFromDate && $aToDateL==$dToFromDate && $aStudentIdL==$studentId && $findL==1 ) { 
                                 $aStudentIdL       = $attendanceArray3[$kL]['studentId'];
                                 $aFromDateL        = $attendanceArray3[$kL]['fromDate'];
                                 $aToDateL          = $attendanceArray3[$kL]['toDate'];
                                 $aAttended         = $attendanceArray3[$kL]['attended']; 
                                 $lectureAttended   = $attendanceArray3[$kL]['lectureAttended'];   
                                 $lectureDelivered  = $attendanceArray3[$kL]['lectureDelivered'];
                                 $attendanceCode    = $attendanceArray3[$kL]['attendanceCode'];
                                 $tAttendanceCode    = $attendanceArray3[$kL]['attendanceCode'];
                                 $attendanceCodePercentage   = $attendanceArray3[$kL]['attendanceCodePercentage'];  
                                 $aGroupTypeId  = $attendanceArray3[$kL]['groupTypeId'];  
                                 
                                 if($attendanceCode=='-2') {
                                   $attendanceCode = NOT_APPLICABLE_STRING;  
                                 }
                                 else if($attendanceCode=='-1') {
                                   //$attendanceCode = $lectureAttended.'/'.$lectureDelivered;   
                                   $attendanceCode = $aAttended.'/'.$lectureDelivered;    
                                 }
                                 
                                 $totalD_Lec = $totalD_Lec + $lectureDelivered; 
                                 
                                 //if($tAttendanceCode=='P') {
                                 if($tAttendanceCode=='-1') {
                                   $totalA_Lec = $totalA_Lec + $aAttended;
                                   $temp = $totalA_Lec.'/'.$totalD_Lec;
                                 }
                                 else if($attendanceCodePercentage!=0) {
                                   $totalA_Lec = $totalA_Lec + $aAttended;
                                   $temp = $totalA_Lec;
                                 }
                               
                                 if($attendanceCodePercentage==0) {    
                                      //if($tAttendanceCode=='A') {
                                      $totalA_Lec = $totalA_Lec + 0;
                                      $temp = $blankSymbol;
                                      if($dutyLeave == 1) {
                                         for($dl=0;$dl<count($dattendanceArray3); $dl++) {
                                            if($dattendanceArray3[$dl]['studentId']==$studentId && $dattendanceArray3[$dl]['dutyDate']==$dFromDate) {
                                               //$totalA_Lec = $totalA_Lec + $dattendanceArray3[$dl]['attended'];
                                               $totalA_Lec = $totalA_Lec + 1;
                                               $temp = "<b>DL</b>"; 
                                               break;
                                            }
                                         } 
                                     }
                                 }
                                 $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$temp."</td>";    
                                 $kL++;
                                 $aStudentIdL = $attendanceArray3[$kL]['studentId'];
                                 $aFromDateL = $attendanceArray3[$kL]['fromDate'];
                                 $aToDateL  = $attendanceArray3[$kL]['toDate'];
                              }
                              else {
                                 $temp = $blankSymbol;  
                                /*if($dutyLeave == 1) {
                                     for($dl=0;$dl<count($dattendanceArray3); $dl++) {
                                        if($dattendanceArray3[$dl]['studentId']==$studentId && $dattendanceArray3[$dl]['dutyDate']==$dFromDate) {
                                           $totalA_Lec = $totalA_Lec + $dattendanceArray3[$dl]['attended'];
                                           $temp = "<b>DL</b>"; 
                                           break;
                                        }
                                     } 
                                 } 
                                */  
                                 $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$temp."</td>";      
                              }
                              
                              
                             // Findout Student Id  == Tutorial 
                             if($aFromDateT==$dFromDate && $aToDateT==$dToFromDate && $aStudentIdT==$studentId  && $findT==1) {  
                                 $aStudentIdT       = $attendanceArray1[$kT]['studentId'];
                                 $aFromDateT        = $attendanceArray1[$kT]['fromDate'];
                                 $aToDateT          = $attendanceArray1[$kT]['toDate'];
                                 $aAttended         = $attendanceArray1[$kT]['attended']; 
                                 $lectureAttended   = $attendanceArray1[$kT]['lectureAttended'];   
                                 $lectureDelivered  = $attendanceArray1[$kT]['lectureDelivered'];
                                 $attendanceCode    = $attendanceArray1[$kT]['attendanceCode'];
                                 $tAttendanceCode   = $attendanceArray1[$kT]['attendanceCode'];
                                 $attendanceCodePercentage   = $attendanceArray1[$kT]['attendanceCodePercentage'];  
                                 $aGroupTypeId  = $attendanceArray1[$kT]['groupTypeId'];  
                                 
                                 if($attendanceCode=='-2') {
                                   $attendanceCode = NOT_APPLICABLE_STRING;  
                                 }
                                 else if($attendanceCode=='-1') {
                                   //$attendanceCode = $lectureAttended.'/'.$lectureDelivered;   
                                   $attendanceCode = $aAttended.'/'.$lectureDelivered;    
                                 }
                                 
                                 $totalD_Tut = $totalD_Tut + $lectureDelivered; 
                                 //if($tAttendanceCode=='P') {
                                 if($tAttendanceCode=='-1') {
                                   $totalA_Tut = $totalA_Tut + $aAttended;
                                   $temp = $totalA_Tut.'/'.$totalD_Tut;
                                 }
                                 else if($attendanceCodePercentage!=0) {
                                   $totalA_Tut = $totalA_Tut + $aAttended;
                                   $temp = $totalA_Tut;
                                 }
                                 else 
                                 if($attendanceCodePercentage==0) {    
                                      //if($tAttendanceCode=='A') {
                                      $totalA_Tut = $totalA_Tut + 0;
                                      $temp = $blankSymbol;
                                      if($dutyLeave == 1) {
                                         for($dl=0;$dl<count($dattendanceArray1); $dl++) {
                                            if($dattendanceArray1[$dl]['studentId']==$studentId && $dattendanceArray1[$dl]['dutyDate']==$dFromDate) {
                                               //$totalA_Tut = $totalA_Tut + $dattendanceArray1[$dl]['attended'];
                                               $totalA_Tut = $totalA_Tut + 1;
                                               $temp = "<b>DL</b>"; 
                                               break;
                                            }
                                         } 
                                     }
                                   
                                 }
                                 $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$temp."</td>";   
                                 $kT++; 
                                 $aStudentIdT = $attendanceArray1[$kT]['studentId'];
                                 $aFromDateT = $attendanceArray1[$kT]['fromDate'];
                                 $aToDateT  = $attendanceArray1[$kT]['toDate'];
                              }
                              else {
                                 $temp = $blankSymbol;  
                                 /* if($dutyLeave == 1) {
                                     for($dl=0;$dl<count($dattendanceArray1); $dl++) {
                                        if($dattendanceArray1[$dl]['studentId']==$studentId && $dattendanceArray1[$dl]['dutyDate']==$dFromDate) {
                                           $totalA_Tut = $totalA_Tut + $dattendanceArray1[$dl]['attended'];
                                           $temp = "<b>DL</b>"; 
                                           break;
                                        }
                                     } 
                                 } 
                                */   
                                 $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$temp."</td>";      
                              }
                    }   
                    
                    if(count($attendanceArray3)>0 || count($attendanceArray1)>0 ) {    
                      $colspan11="colspan='2'";
                    }
                    else {
                      $colspan11='';   
                    }
                    for($j=$countColumns; $j<$nosCol; $j++) {
                      $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()." $colspan11 align='center'>&nbsp;</td>";   
                    }
                      
                        
                    
                    if(count($attendanceArray3)>0 || count($attendanceArray1)>0 ) {    
                        $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$totalA_Lec.'/'.$totalD_Lec."</td>
                                       <td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$totalA_Tut.'/'.$totalD_Tut."</td>
                                       <td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".($totalA_Lec+$totalA_Tut).'/'.($totalD_Lec+$totalD_Tut)."</td>";
                        
                         if($totalD_Lec==0) {
                           $perL = "0.00";
                         } 
                         else {
                           $perL = round(($totalA_Lec/$totalD_Lec)*100,2);  
                         }
                         
                         if($totalD_Tut==0) {
                           $perT = "0.00";
                         } 
                         else {
                           $perT = round(($totalA_Tut/$totalD_Tut)*100,2);  
                         }
                         
                         if(($totalD_Lec+$totalD_Tut)==0) {
                           $perTot = "0.00";
                         } 
                         else {
                           $perTot = round(($totalA_Lec+$totalA_Tut)/($totalD_Lec+$totalD_Tut)*100,2);  
                         }
                         
                         $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$perL."%</td>
                                        <td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$perT."%</td>
                                        <td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$perTot."%</td>";
                   }
                   else {
                       $tableData .= "<td width='5%' valign='top' class='padding_top' $colspan11 align='center'>&nbsp;</td>
                                      <td width='5%' valign='top' class='padding_top' $colspan11 align='center'>&nbsp;</td>";    
                   }  
                   $tableData .= "</tr>";
                    //if($i==2) {
                    //  echo $tableData;    
                    //  die;
                    // }
             }
      }    // Group Type Condition END
      
      else  {
      //if($groupTypeId!=1) {      
                  $k=0;
                  $cntAttendance =  count($attendanceArray);
                  for($i=0; $i<$cnt; $i++) {  
                        if(($i+1)%26==0) {
                            $tableData .= "</table>"; 
                            $reportChk=1;
                            reportGenerate($tableData,$search);    
                            $tableData = $tableHeading;
                        }
                        $reportChk=0;      
                         // $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                          $tableData .= "<tr>
                                           <td valign='top' ".$reportManager->getReportDataStyle()."  align='left'>".($i+1)."</td>  
                                           <td valign='top' ".$reportManager->getReportDataStyle()."  align='left'>".$studentArray[$i]['rollNo']."</td>
                                           <td valign='top' ".$reportManager->getReportDataStyle()."  align='left'>".$studentArray[$i]['universityRollNo']."</td>
                                           <td valign='top' ".$reportManager->getReportDataStyle()."  align='left'>".$studentArray[$i]['studentName']."</td>";
                          $studentId = $studentArray[$i]['studentId'];
                          
                          $totalA = 0;
                          $totalD = 0;
                          $totalA_Tut = 0;
                          $totalD_Tut = 0; 
                          $totalA_Lec = 0;
                          $totalD_Lec = 0;
                          
                          $groupTypeIdFind=0;
                          
                          $find=0;
                          // Findout Pervious Duty Leave
                          for($k=0;$k<count($dPerviousAttended);$k++) {
                             $aStudentId = $dPerviousAttended[$k]['studentId']; 
                             if($groupTypeId==1  || $groupTypeId==3) { 
                                 if($aStudentId==$studentId) {
                                    $find=1; 
                                    if($dPerviousAttended[$k]['groupTypeId']==1) { 
                                      $totalA_Tut = $dPerviousAttended[$k]['attended']; 
                                    }
                                    if($dPerviousAttended[$k]['groupTypeId']==3) { 
                                      $totalA_Lec = $dPerviousAttended[$k]['attended']; 
                                    }
                                 }
                                 if($find==1 && $aStudentId!=$studentId) {
                                   break;  
                                 }
                             }
                             else { 
                                if($aStudentId==$studentId) {
                                   $totalA = $dPerviousAttended[$k]['attended']; 
                                   break; 
                                }
                             }
                          }
                          
                          
                          $find=0;
                          // Findout Pervious Attendacne 
                          for($k=0;$k<count($perviousAttended);$k++) {
                             $aStudentId = $perviousAttended[$k]['studentId']; 
                             if($groupTypeId==1 || $groupTypeId==3) {  
                                 if($aStudentId==$studentId) {
                                    $find=1; 
                                    if($perviousAttended[$k]['groupTypeId']==1) { 
                                      $totalA_Tut = $totalA_Tut + $perviousAttended[$k]['attended']; 
                                      $totalD_Tut = $perviousAttended[$k]['delivered'];    
                                    }
                                    if($perviousAttended[$k]['groupTypeId']==3) { 
                                      $totalA_Lec = $totalA_Lec + $perviousAttended[$k]['attended']; 
                                      $totalD_Lec = $perviousAttended[$k]['delivered'];    
                                    }
                                 }
                                 if($find==1 && $aStudentId!=$studentId) {
                                   break;  
                                 }
                             }
                             else { 
                                if($aStudentId==$studentId) {
                                   $totalA = $totalA + $perviousAttended[$k]['attended']; 
                                   $totalD = $perviousAttended[$k]['delivered'];    
                                   break; 
                                }
                             }
                          }
                          
                          $k=0;
                          $j=0;
                          $dt=0;
                          
                          // Findout Student Id
                              $attFindout='0';
                              for($k=0;$k<$cntAttendance;$k++) {
                                 $aStudentId = $attendanceArray[$k]['studentId'];    
                                 if($aStudentId==$studentId) {
                                    $attFindout='1';  
                                    break; 
                                 }
                              }
                               $posAtt=0;   
                              while($k <= $cntAttendance && $attFindout=='1') {
                                $aStudentId        = $attendanceArray[$k]['studentId'];
                                $aFromDate         = $attendanceArray[$k]['fromDate'];
                                $aToDate           = $attendanceArray[$k]['toDate'];
                                $aPeriodNumber     = $attendanceArray[$k]['periodNumber'];
                                $aAttended         = $attendanceArray[$k]['attended']; 
                                $lectureAttended   = $attendanceArray[$k]['lectureAttended'];   
                                $lectureDelivered  = $attendanceArray[$k]['lectureDelivered'];
                                $attendanceCode    = $attendanceArray[$k]['attendanceCode'];
                                $tAttendanceCode    = $attendanceArray[$k]['attendanceCode'];
                                $attendanceCodePercentage   = $attendanceArray[$k]['attendanceCodePercentage'];  
                                $aGroupTypeId  = $attendanceArray[$k]['groupTypeId'];  
                              
                                
                                // Check Status 
                                if($aPeriodNumber=='') {
                                  $aPeriodNumber = NOT_APPLICABLE_STRING;  
                                }
                                
                                if($attendanceCode=='-2') {
                                  $attendanceCode = NOT_APPLICABLE_STRING;  
                                }
                                else if($attendanceCode=='-1') {
                                   //$attendanceCode = $lectureAttended.'/'.$lectureDelivered;   
                                   $attendanceCode = $aAttended.'/'.$lectureDelivered;    
                                }
                                
                                if($aStudentId != $studentId) {
                                  break;  
                                }
                                $chk=0;
                               
                            for($j=0; $j<$countColumns; $j++) {
                               $dFromDate     = $dateArray[$j]['fromDate'];
                               $dToFromDate   = $dateArray[$j]['toDate'];
                               $dPeriodNumber = $dateArray[$j]['periodNumber'];  
                               $temp=NOT_APPLICABLE_STRING;
                               if($dPeriodNumber=='') {
                                 $dPeriodNumber = NOT_APPLICABLE_STRING; 
                               }
                               if($aFromDate==$dFromDate && $aToDate==$dToFromDate && $aPeriodNumber==$dPeriodNumber) {
                                     //$tableData .= "<td width='5%' ".$reportManager->getReportDataStyle()."  align='center'>".$attendanceCode."</td>"; 
                                     $chk=1;
                                     $totalD = $totalD + $lectureDelivered; 
                                     //if($tAttendanceCode=='P') {
                                     if($tAttendanceCode=='-1') {
                                       $totalA = $totalA + $aAttended;
                                       $temp = $totalA.'/'.$totalD;
                                     }
                                     else if($attendanceCodePercentage!=0) {
                                       $totalA = $totalA + $aAttended;
                                       $temp = $totalA;
                                     }
                                     else 
                                     if($attendanceCodePercentage==0) {    
                                          //if($tAttendanceCode=='A') {
                                          $totalA = $totalA + 0;
                                          $temp = $blankSymbol;
                                          if($dutyLeave == 1) {
                                            for($dl=0;$dl<count($dattendanceArray); $dl++) {
                                               if($dattendanceArray[$dl]['studentId']==$studentId && $dattendanceArray[$dl]['dutyDate']==$dFromDate) {
                                                 //$totalA = $totalA + $dattendanceArray[$dl]['attended'];
                                                 $totalA = $totalA + 1;
                                                 $temp = "<b>DL</b>"; 
                                                 break;
                                               }
                                            } 
                                         }  
                                     }
                                     $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()." align='center'>".$temp."</td>";    
                                     $posAtt++;  
                                     break;
                               }
                               else {
                                  if($j==$posAtt && $dFromDate != $aFromDate) {
                                    $chk=1;  
                                    $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$notMemberPrefix."</td>";    
                                    $posAtt++;
                                    $dt=$dt+1;  
                                  }
                               }
                            }
                            if($chk==0 && ($aStudentId == $studentId)) {  
                                $temp = $blankSymbol; 
                               /*if($dutyLeave == 1) {
                                     for($dl=0;$dl<count($dattendanceArray); $dl++) {
                                        if($dattendanceArray[$dl]['studentId']==$studentId && $dattendanceArray[$dl]['dutyDate']==$dFromDate) {
                                           $totalA = $totalA + $dattendanceArray[$dl]['attended'];
                                           $temp = "<b>DL</b>"; 
                                           break;
                                        }
                                     } 
                               } */   
                               $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$temp."</td>";   
                            }
                            $dt=$dt+1;
                            if($dt==$countColumns) {
                              break;
                            }
                            $k++;
                          }   
                          
                          if($dt<$countColumns) {
                            for($j=$dt; $j<$countColumns; $j++) {
                              $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$notMemberPrefix."</td>";  
                            }
                          }
                          
                          /*
                          if($dt<$cnt1) {
                            for($j=$dt; $j<$cnt1; $j++) {
                              $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".NOT_APPLICABLE_STRING."</td>";  
                            }
                          }
                          */
                          for($j=$countColumns; $j<$nosCol; $j++) {
                             $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'>&nbsp;</td>";  
                          }
                          
                          //if($cnt1>0) {
                          if($cnt1>0) {
                              if($dt==0) {
                                 $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$blankSymbol."</td>";  
                                 $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$blankSymbol."</td>";  
                              }
                              else {
                                 if($totalD==0) {
                                   $per = "0.00";
                                 } 
                                 else {
                                   $per = round(($totalA/$totalD)*100,2);  
                                 }
                                 $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$totalA.'/'.$totalD."</td>
                                                <td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>".$per."%</td>";
                              }
                          }
                          else {             
                             $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>&nbsp;</td>";  
                             $tableData .= "<td valign='top' ".$reportManager->getReportDataStyle()."  align='center'>&nbsp;</td>";  
                            /* for($s=0; $s<count($dd); $s++) { 
                                $val = $dd[$s]['dt1'] ; 
                                 $tableData .= "<td width='5%' valign='top' ".$reportManager->getReportDataStyle()."  align='center'><strong>".$val."</strong></td>";    
                               }
                           */  
                         }
                         $tableData .= "</tr>";     
                  }
                  
    }    // Group Type Condition END
      
    if($cnt==0) {
     //$bg = $bg =='trow0' ? 'trow1' : 'trow0';    
     $tableData .= "<tr>
                       <td valign='top' ".$reportManager->getReportDataStyle()."  align='center' colspan='$colspan'>No Record Found</td>  
                    </tr>
                    </table>";                         
    }
    
    if($reportChk==0) {
       $tableData .= "</table>"; 
       reportGenerate($tableData,$search);    
    }
     
    // Report generate
    function reportGenerate($value,$heading) {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(780);
        $reportManager->setReportHeading('Attendance Register Report');
        $reportManager->setReportInformation("$heading");     
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
            <tr>
            <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
            <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
            <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                </tr>
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                </tr>
            </table>
            </td>
            </tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>  align="center"><?php echo $reportManager->getReportInformation(); ?></th></tr>
            </table> <br>
            <table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
            <tr>
            <td valign="top">
            <?php echo $value; ?>        
            </td>
            </tr> 
            </table>       
            <br>
            <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <br class='page'> 
        </div>    
<?php        
    }
?>
<?php
// $History: attendanceRegisterReportPrint.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 4/22/10    Time: 1:00p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//sorting order update (fromDate, periodId )
//
//*****************  Version 7  *****************
//User: Parveen      Date: 4/12/10    Time: 12:18p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//validation format updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/06/10    Time: 1:12p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//alignment format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/06/10    Time: 12:20p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//optional subject code format updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/05/10    Time: 1:42p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//student optional subject code updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/19/10    Time: 2:39p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//attendance sorting order updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/17/10    Time: 12:26p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//teacher login code updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/17/10    Time: 10:22a
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin
//
//*****************  Version 7  *****************
//User: Parveen      Date: 3/03/10    Time: 3:11p
//Updated in $/LeapCC/Templates/AdminTasks
//format updated (page break)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/20/10    Time: 6:26p
//Updated in $/LeapCC/Templates/AdminTasks
//format update %age added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/19/10    Time: 1:39p
//Updated in $/LeapCC/Templates/AdminTasks
//search condition format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/19/10    Time: 1:18p
//Updated in $/LeapCC/Templates/AdminTasks
//format updated (no. of columns check) 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/18/10    Time: 6:19p
//Updated in $/LeapCC/Templates/AdminTasks
//format & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 4:28p
//Created in $/LeapCC/Templates/AdminTasks
//initial checkin
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/12/09   Time: 11:01
//Updated in $/LeapCC/Templates/AdminTasks
//Corrected coding in Attendance history display logic
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/09    Time: 11:24
//Updated in $/LeapCC/Templates/AdminTasks
//Corrected Date Formate in CSV and column headings
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:10
//Created in $/LeapCC/Templates/AdminTasks
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
?>