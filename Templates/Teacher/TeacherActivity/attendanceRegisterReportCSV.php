<?php 
//This file is used as csv version for for Attendance Register CSV
//
// Author :Parveen Sharma
// Created on : 24.10.2008
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
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);    
    UtilityManager::headerNoCache(); 
    
    //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
       } 
       else {
         return $comments.chr(160); 
       }
    }    
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId'); 
    $timeTableLabelId = add_slashes($REQUEST_DATA['timeTable']);  
    $degree = add_slashes($REQUEST_DATA['degree']);
    $subjectId = add_slashes($REQUEST_DATA['subjectId']);  
    $groupId = add_slashes($REQUEST_DATA['groupId']);  
    $fromDate = add_slashes($REQUEST_DATA['fromDate']);
    $toDate= add_slashes($REQUEST_DATA['toDate']);
    $nosCol= add_slashes($REQUEST_DATA['nosCol']);   
    $heading= add_slashes($REQUEST_DATA['heading']); 
    
    $heading = urldecode($heading);
    
    //$heading = "<script>unescape(document.write(heading));</script>";
    if($employeeId=='') {
      $employeeId = 0;  
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
    
    $countColumns=0;    
    
    
  // Findout Time Table Name
    $employeeNameArray = $studentManager->getSingleField('employee', "CONCAT(employeeName,' (',employeeCode,')') AS employeeName", "WHERE employeeId  = $employeeId");
    $employeeName = $employeeNameArray[0]['employeeName'];
    if($employeeName=='') {
      $employeeName = NOT_APPLICABLE_STRING;  
    }
    
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
    
    $search  = "Teacher,".parseCSVComments($employeeName)."\n";   
    $search .= "Time Table,".parseCSVComments($timeTableName)."\n";
    $search .= "Degree,".parseCSVComments($className2)."\nSubject,".parseCSVComments($subjectName)."\n";
    $search .= "Group,".parseCSVComments($groupName)."\n";
    $search1 .= "Date,".UtilityManager::formatDate($fromDate).",to,".UtilityManager::formatDate($toDate)."\n";
    if($heading!='') {
      $search2 .= parseCSVComments($heading)."\n";
    }
    
    if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, studentName)';
        $sortField2 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",att.studentId, studentName)';
    }
    else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, rollNo)';
        $sortField2 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",att.studentId, studentName)';
    }
    else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",sg.studentId, universityRollNo)';
        $sortField2 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",att.studentId, studentName)';
    }
    else {
       $sortField == 'studentName';
       $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, studentName)';
       $sortField2 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",att.studentId, studentName)'; 
    }
    
    $orderBy = " $sortField1 $sortOrderBy";    
    $orderBy2 = " $sortField2 $sortOrderBy";   
    
    
    // Findout Pervious Attended/Delivered Lecture Total 
    $filter = " tt.studentId, 
                    IFNULL(SUM(tt.attended),0)  AS  attended,
                    IFNULL(SUM(tt.delivered),0) AS  delivered ";
    $field = " att.studentId, 
                IF(att.isMemberOfClass =0,0,IF(att.attendanceType=2,(ac.attendanceCodePercentage /100),att.lectureAttended)) AS attended,
                IF(att.isMemberOfClass=0, 0,att.lectureDelivered) AS  delivered ";
    $attendanceCountCondition  = " AND att.classId = $degree AND att.subjectId = $subjectId  AND  att.groupId = $groupId "; 
    $attendanceCountCondition .= " AND (att.fromDate < '$fromDate') AND att.employeeId = $employeeId ";  
    $groupBy=" att.classId, att.subjectId, att.groupId, att.studentId, att.fromDate, att.periodId ";
    $groupBy1=" GROUP BY tt.studentId "; 
    $perviousAttended =  $studentReportManager->getTotalDeliveredAttendance($attendanceCountCondition,$field,$groupBy,$filter,$groupBy1); 
    
  
    
   
    $studentCondition = "";
    $attendanceCondition = "";
    $csvData1 = '';
    $csvData = '';

    $csvData1 = "Sr. No.,Roll No.,Univ. Roll No.,Student Name";
    
    //$studentCondition = " AND c.classId = $degree AND stc.subjectId = $subjectId AND sg.groupId = $groupId "; 
    $studentCondition = "  AND tt.employeeId = $employeeId  AND c.classId = $degree AND tt.subjectId = $subjectId AND tt.groupId = $groupId AND tt.timeTableLabelId='".$timeTableLabelId."'"; 
    $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
    $cnt = count($studentArray);

   
    $rowspan = "2";           
    if($cnt>0) {
         $attendanceCondition  = " AND att.classId = $degree AND att.subjectId = $subjectId  AND  att.groupId = $groupId "; 
         $attendanceCondition .= " AND att.employeeId = $employeeId  AND (att.fromDate >= '$fromDate' AND att.fromDate <= '$toDate') ";  
         
         $fieldName=" DISTINCT 
                                att.fromDate, att.toDate, IFNULL(periodNumber,'') AS periodNumber "; 
         $dateOrderBy = "att.classId, att.subjectId, att.groupId, att.studentId, att.fromDate, att.periodId  "; 
         $dateArray =  $studentReportManager->getStudentAttendanceData($fieldName, $attendanceCondition, $dateOrderBy);
         $cnt1 = count($dateArray);
         
         
            //$attendanceOrderBy = "att.studentId, att.periodId, att.fromDate "; 
            $attendanceOrderBy = $orderBy2.", att.classId, att.subjectId, att.groupId, att.studentId, att.fromDate, att.periodId  ";   
            $attendanceArray   = $studentReportManager->getStudentAttendanceData('',$attendanceCondition,$attendanceOrderBy);
            
            $csvData1 = "Sr. No.,Roll No.,Univ. Roll No.,Student Name";
             if($cnt1>0) {  
               for($i=0;$i<$nosCol;$i++) {   
                 $csvData1 .= ",".parseCSVComments(($i+1)); 
               }    
            }

            if($cnt1==0) {  
              $rowspan = "";    
              $search = $search.$search2;       
            }
            else {
              $rowspan = "rowspan='2'";           
              $search = $search.$search1.$search2;
            }
            if($cnt1==0) { 
               for($j=0;$j<$nosCol;$j++) {
                  if($j<9) { 
                     $csvData1 .= ",".parseCSVComments("0".($j+1));    
                  }
                  else {
                     $csvData1 .= ",".parseCSVComments(($j+1));    
                  }
                  $dd[$j]['heading']=' ';
                  $dd[$j]['dt']=' ';
                  $dd[$j]['dt1']=' '; 
               }    
            }
            
            $csvData1 .= ",Total,%age";
            $csvData1 .= "\n";
            

            
            if($cnt1 > 0) {
                $csvData1 .= ",,,";
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
                     $val = $fromArr[2].'/'.$fromArr[1].' to '.$toArr[2].'/'.$toArr[1];
                     //." (".$periodNumber.")"; 
                   }
                   else {
                     $fromArr = explode('-',$dateArray[$i]['fromDate']);
                     $val = $fromArr[2].'/'.$fromArr[1]." (".$periodNumber.")"; 
                   }
                   $csvData1 .= ",".parseCSVComments($val);
                   $colspan = $colspan + 1;
                }
                for($i=$cnt1; $i<$nosCol; $i++) { 
                   $csvData1 .= ",";
                } 
                $csvData1 .= "\n";        
            }
      }
                 
       
      
          $reportChk=0;  
          $k=0;
          $cntAttendance =  count($attendanceArray);
          $csvData = '';
          
          for($i=0; $i<$cnt; $i++) {    
             
              $reportChk=0;
              $csvData .= ($i+1).",".parseCSVComments($studentArray[$i]['rollNo']).",".parseCSVComments($studentArray[$i]['universityRollNo']);
              $csvData .= ",".parseCSVComments($studentArray[$i]['studentName']);
              $studentId = $studentArray[$i]['studentId'];
              $totalA = 0;
              $totalD = 0;
               // Findout Pervious Attendacne 
              for($k=0;$k<count($perviousAttended);$k++) {
                 $aStudentId = $perviousAttended[$k]['studentId'];    
                 if($aStudentId==$studentId) {
                    $totalA = $perviousAttended[$k]['attended']; 
                    $totalD = $perviousAttended[$k]['delivered'];    
                    break; 
                 }
              }
              
              $k=0;
              $j=0;
              $dt=0;
              
              // Findout Student Id
              for($k=0;$k<$cntAttendance;$k++) {
                 $aStudentId = $attendanceArray[$k]['studentId'];    
                 if($aStudentId==$studentId) {
                    break; 
                 }
              }
              while($k <= $cntAttendance) {
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
                  
                if($aPeriodNumber=='') {
                  $aPeriodNumber = NOT_APPLICABLE_STRING;  
                }
                
                if($attendanceCode=='-2') {
                  $attendanceCode = NOT_APPLICABLE_STRING;  
                }
                else if($attendanceCode=='-1') {
                   $attendanceCode = $aAttended.'/'.$lectureDelivered;   
                }
                
                if($aStudentId != $studentId) {
                  break;  
                }
                $chk=0;
                for($j=0; $j<$cnt1; $j++) {
                   $dFromDate    = $dateArray[$j]['fromDate'];
                   $dToFromDate   = $dateArray[$j]['toDate'];
                   $dPeriodNumber = $dateArray[$j]['periodNumber'];  
                   $temp=NOT_APPLICABLE_STRING;
                   if($dPeriodNumber=='') {
                     $dPeriodNumber = NOT_APPLICABLE_STRING; 
                   }
                   if($aFromDate==$dFromDate && $aToDate==$dToFromDate && $aPeriodNumber==$dPeriodNumber) {
                     //$tableData .= "<td width='5%' class='padding_top' align='center'>".$attendanceCode."</td>"; 
                     $chk=1;
                     $totalD = $totalD + $lectureDelivered;    
                     //if($tAttendanceCode=='P') {
                     if($tAttendanceCode=='-1') {
                       $totalA = $totalA + $aAttended;
                       $temp = $totalA.'/'.$totalD; 
                     }
                     else 
                     if($attendanceCodePercentage!=0) {
                       $totalA = $totalA + $aAttended;
                       $temp = $totalA;
                     }
                     else 
                     if($attendanceCodePercentage==0) {   
                     //if($tAttendanceCode=='A') {
                       $totalA = $totalA + 0;
                       $temp = NOT_APPLICABLE_STRING;
                     }
                     $csvData .= ",".parseCSVComments($temp);    
                    
                     break;
                   }
                }
                if($chk==0) {
                  $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);  
                }
                $dt=$dt+1;
                if($dt==$countColumns) {
                  break;
                }
                $k++;
             }   
              
             if($dt<$countColumns) {
                for($j=$dt; $j<$countColumns; $j++) {
                  $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);  
                }
             }
              
             for($j=$countColumns; $j<$nosCol; $j++) {
                $csvData .= ",";  
             }
              
              
             if($cnt1>0) {
                  if($dt==0) {
                     $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);  
                     $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING); 
                  }
                  else {
                    if($totalD==0) {
                       $per = "0.00";
                     } 
                     else {
                       $per = round(($totalA/$totalD)*100,2);  
                     }
                     $csvData .= ",".parseCSVComments($totalA.'/'.$totalD).",".parseCSVComments($per);
                  }
             }
                 $csvData2 = $search.$csvData1.$csvData;

             $csvData .= "\n";     
      }
      if($cnt==0) {
         //$bg = $bg =='trow0' ? 'trow1' : 'trow0';    
         $csvData .= "\n"; 
         $csvData .= "No Record Found";
      }
    
    $csvData2 = $search.$csvData1.$csvData;

    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    header('Content-type: application/octet-stream; charset=utf-8');
    header("Content-Length: " .strlen($csvData2) );
    header('Content-Disposition: attachment;  filename="AttendanceRegister.csv"');
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData2;
    die;    

// $History: attendanceRegisterReportCSV.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 4/22/10    Time: 1:00p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//sorting order update (fromDate, periodId )
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/12/10    Time: 12:18p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//validation format updated
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