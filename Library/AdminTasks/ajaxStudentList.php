<?php
//--------------------------------------------------------
//This file fetch teacher attendance register details
//
// Author :Parveen Sharma
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();
     
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
    
    
    $blankSymbol= "X";
    
    
    $timeTableLabelId = add_slashes($REQUEST_DATA['timeTable']);  
    $degree = add_slashes($REQUEST_DATA['degree']);
    $subjectId = add_slashes($REQUEST_DATA['subjectId']);  
    $groupId = add_slashes($REQUEST_DATA['groupId']);  
    $fromDate = add_slashes($REQUEST_DATA['fromDate']);
    $toDate= add_slashes($REQUEST_DATA['toDate']);
    $nosCol= add_slashes($REQUEST_DATA['nosCol']); 
    $consolidatedId = add_slashes($REQUEST_DATA['consolidatedId']);
    
    $dutyLeave = add_slashes($REQUEST_DATA['dutyLeave']);    
    
    
    
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
    

    
    /*  ----------------- Search Condition Start -------------------  */
    /*
        $search ='';
        $search1 ='';
        $search2 ='';                             
        $search3 ='';
        
        
        global $sessionHandler;
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');      
        
        // Fetch Employee Name
        $employeeName='';
        $tableName = " employee e, `group` g,  ".TIME_TABLE_TABLE." tt ";
        $fieldsName ="GROUP_CONCAT(DISTINCT employeeName,' (',employeeCode,')' SEPARATOR ', ') AS employeeName";
        $empCondition = " WHERE 
                                e.employeeId=tt.employeeId AND   
                                tt.groupId=g.groupId AND  
                                tt.toDate IS NULL AND  
                                g.classId=$degree  AND tt.subjectId = $subjectId AND tt.timeTableLabelId=$timeTableLabelId AND
                                tt.sessionId=$sessionId AND tt.instituteId=$instituteId AND tt.groupId = $groupId
                          GROUP BY 
                                g.classId, tt.subjectId";  
        $employeeArray = $studentManager->getSingleField($tableName, $fieldsName, $empCondition);
        $employeeName = $employeeArray[0]['employeeName'];
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
        
        $search1 = "$timeTableName<br>";
        $search1 .= "$className2<br>$subjectName<br>$groupName<br>";
        $search1 .= "$employeeName<br>"; 
        $search2 .= "From&nbsp;".UtilityManager::formatDate($fromDate);
        $search2 .= "&nbsp;&nbsp;To&nbsp;&nbsp;".UtilityManager::formatDate($toDate);
        
        $search = $search1.$search2; 
        
        $tableSearch = "<table width='100%' class='reportTableBorder'>
                            <tr><td width='100%' style='padding-left:5px' class='dataFont' align='left'>$search</td></tr>    
                        </table>"; 
     */   
    /*  ----------------- Search Condition End -------------------  */
    
    
    
    $countColumns=0;
    //paging
    $page = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records  = ($page-1)* RECORDS_PER_PAGE;
    $limit   = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
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
    if($groupTypeId==1  || $groupTypeId==3) {
       $rowspan2="rowspan='2'";
       $rowspan3="rowspan='3'";
       $colspan1="colspan='2'"; 
       $colspan2="colspan='3'";          
    }
    
   
    $reportFormat = 1;
    
    $tableData = "";
    $studentCondition = "";
    $attendanceCondition = "";
    
 // Find Student    
    $studentCondition = " AND c.classId = $degree AND tt.subjectId = $subjectId AND tt.groupId = $groupId 
                          AND tt.timeTableLabelId='".$timeTableLabelId."'"; 
                          
    //$studentCondition .= " AND s.studentId=4339 ";                                
    $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
    $cnt = count($studentArray);
    $ffStudentId = 0;    
    for($i=0; $i<$cnt; $i++) {
      $ffStudentId .= ",".$studentArray[$i]['studentId'];  
    }
    

 // Findout Pervious Attended/Delivered Lecture Total 
    $tgroupId =  " AND att.studentId IN ($ffStudentId) AND att.groupId IN ($groupId) ";
    $tgroupId1 = " AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1) ";
    $tgroupId3 = " AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (3) ";
    
 // Findout Duty Leave 
    $dtgroupId =  " AND dl.studentId IN ($ffStudentId) AND dl.groupId IN ($groupId) ";
    $dtgroupId1 = " AND dl.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1) ";
    $dtgroupId3 = " AND dl.studentId IN ($ffStudentId) AND gt.groupTypeId IN (3) ";
    
    if($consolidatedId==1) {  
      if($groupTypeId==1  || $groupTypeId==3) {
        $tgroupId  =  " AND att.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1,3) ";
        $dtgroupId =  " AND dl.studentId IN ($ffStudentId) AND gt.groupTypeId IN (1,3) ";
      }  
    }
    
    
    // Findout Previous Attended/Delivered Lecture Total
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
    
    
    if($reportFormat==1) {
      
      $colspan=4;  
      
      $tableData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                        <tr class='rowheading'>
                            <td width='2%'  valign='middle' $rowspan3 class='searchhead_text' ><b>#</b></td>
                            <td width='5%'  valign='middle' $rowspan3 class='searchhead_text' align='left'><strong>Roll No.</strong></td>
                            <td width='5%'  valign='middle' $rowspan3 class='searchhead_text' align='left'><strong>URoll No.</strong></td>
                            <td width='10%' valign='middle' $rowspan3 class='searchhead_text' align='left'><strong>Student Name</strong></td>";
       
   
      if($cnt>0) {
         $attendanceCondition  = " AND att.classId = $degree AND att.subjectId = $subjectId "; 
         $attendanceCondition .= " AND (att.fromDate >= '$fromDate' AND att.fromDate <= '$toDate') "; 

         $dAttendanceCondition  = " AND dl.classId = $degree AND dl.subjectId = $subjectId "; 
         $dAttendanceCondition .= " AND (dl.dutyDate >= '$fromDate' AND dl.dutyDate <= '$toDate') "; 
         
         if($groupTypeId==1  || $groupTypeId==3) { 
             
           // Date format
              $attendanceConditionDate =  $attendanceCondition." $tgroupId";
              $fieldName=" DISTINCT 
                                   att.fromDate, att.toDate, IFNULL(periodNumber,'') AS periodNumber  "; 
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
             $attendanceOrderBy  = $orderBy2.", att.classId, att.subjectId, att.studentId, att.fromDate, gt.groupTypeId, att.groupId, att.periodId ";
             $attendanceArray1   = $studentReportManager->getStudentAttendanceData('',$attendanceConditionDate,$attendanceOrderBy,'',$conslidated);
            
             $attendanceConditionDate =  $attendanceCondition." $tgroupId3";   
             $attendanceOrderBy  = $orderBy2.", att.classId, att.subjectId, att.studentId, att.fromDate, gt.groupTypeId, att.groupId, att.periodId ";
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
            if($groupTypeId==1  || $groupTypeId==3) {    
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
            
            $tableData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                            <tr class='rowheading'>
                                <td width='2%'  valign='middle'  class='searchhead_text' $rowspan align='left'><b>#</b></td>
                                <td width='5%'  valign='middle'  class='searchhead_text' $rowspan align='left'><strong>Roll No.</strong></td>
                                <td width='5%'  valign='middle'  class='searchhead_text' $rowspan align='left'><strong>URoll No.</strong></td>
                                <td width='10%' valign='middle'  class='searchhead_text' $rowspan align='left'><strong>Student Name</strong></td>";
            //for($i=0; $i<$cnt1; $i++) { 
            if($cnt1>0) {  
               for($i=0;$i<$nosCol;$i++) {   
                   
                 $tableData .= "<td valign='top' width='2%' $colspan1 class='searchhead_text' align='center'><strong>".($i+1)."</strong></td>"; 
               }    
            }

            if($cnt1==0) { 
               for($j=0;$j<$nosCol;$j++) {
                  if($j<9) { 
                     $tableData .= "<td valign='middle' width='2%' class='searchhead_text' align='center'><strong>&nbsp;0".($j+1)."&nbsp;</strong></td>";   
                  }
                  else {
                     $tableData .= "<td valign='middle' width='2%' class='searchhead_text' align='center'><strong>&nbsp;".($j+1)."&nbsp;</strong></td>";   
                  }
                  $dd[$j]['heading']='';
                  $dd[$j]['dt']='';
                  $dd[$j]['dt1']=''; 
               }
            }
            
            
            if($groupTypeId==1 || $groupTypeId==3) {  
               $tableData .= "<td width='2%' valign='middle' $colspan2 class='searchhead_text' $rowspan2 align='center'><strong>Total</strong></td>
                              <td width='2%' valign='middle' $colspan2 class='searchhead_text' $rowspan2 align='center'><strong>%age</strong></td> ";   
            }
            else {
               $tableData .= "<td width='2%' valign='middle' class='searchhead_text' $rowspan align='center'><strong>Total</strong></td>
                              <td width='2%' valign='middle' class='searchhead_text'  $rowspan align='center'><strong>%age</strong></td> ";     
            }                                                                                                                          
            $tableData .= "</tr>";
            
            if($cnt1 > 0) {  
                $tableData .= "<tr class='rowheading'>"; 
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
                   if($groupTypeId==1 || $groupTypeId==3) {
                       if(count($attendanceArray3)>0  || count($attendanceArray1)>0 ) {      
                         $colspan11=$colspan1;
                       }
                       else {
                         $colspan11='';
                       }
                       $tableData .= "<td valign='top' width='5%' $colspan11 class='searchhead_text' align='center'><strong>".$val."</strong></td>";
                   }
                   else {
                     $tableData .= "<td valign='top' width='5%' $colspan1 class='searchhead_text' align='center'><strong>".$val."</strong></td>";
                   }
                   $colspan = $colspan + 1;
                }  
                
                if($groupTypeId!=1 && $groupTypeId!=3) {  
                    for($i=$cnt1; $i<$nosCol; $i++) { 
                       $tableData .= "<td valign='top' width='5%' $colspan1 class='searchhead_text' align='center'><strong>&nbsp;</strong></td>"; 
                    }
                }
                if($groupTypeId==1 || $groupTypeId==3) {  
                    if(count($attendanceArray3)>0  || count($attendanceArray1)>0 ) {      
                       $colspan11=$colspan1;
                    }
                    else {
                       $colspan11='';
                    }
                    for($i=$countColumns; $i<$nosCol; $i++) {
                      $tableData .= "<td width='5%' valign='top' $colspan11 class='padding_top' align='center'>&nbsp;</td>";  
                    }
                }
                $tableData .= "</tr>";
       
              
               // Create a Lectrue & Tutorials 
                if($groupTypeId==1 || $groupTypeId==3) {  
                      $tableData .= "<tr class='rowheading'>";
                      for($i=0; $i<$countColumns; $i++) {    
                          
                          
                        $tableData .= "<td valign='top' width='5%' class='searchhead_text' align='center'><strong>L</strong></td>";
                        $tableData .= "<td valign='top' width='5%' class='searchhead_text' align='center'><strong>T</strong></td>"; 
                        $colspan+=2;
                      }
                      
                      for($i=$countColumns; $i<$nosCol; $i++) {
                         $tableData .= "<td width='5%' valign='top' class='padding_top' colspan='2' align='center'>&nbsp;</td>";  
                         $colspan+=2; 
                      }
                      
                      if(count($attendanceArray3)>0  || count($attendanceArray1)>0 ) {   
                          $tableData .= "<td valign='top' width='5%' class='searchhead_text' align='center'><strong>L</strong></td>
                                         <td valign='top' width='5%' class='searchhead_text' align='center'><strong>T</strong></td>
                                         <td valign='top' width='5%' class='searchhead_text' align='center'><strong>Total</strong></td>
                                         <td valign='top' width='5%' class='searchhead_text' align='center'><strong>L</strong></td>
                                         <td valign='top' width='5%' class='searchhead_text' align='center'><strong>T</strong></td>
                                         <td valign='top' width='5%' class='searchhead_text' align='center'><strong>Total</strong></td>
                                        </tr>";
                          $colspan +=6;   
                      }
                }
            }
      }       
   
      if($groupTypeId==1 || $groupTypeId==3) {      
                  $k=0;
                  $cntAttendance =  count($dateArray);

                  for($i=0; $i<$cnt; $i++) {    
                          $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                          $tableData .= "<tr class='$bg'>
                                           <td valign='top' class='padding_top' align='left'>".($i+1)."</td>  
                                           <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['rollNo']."</td>
                                           <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['universityRollNo']."</td>
                                           <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['studentName']."</td>";
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
                                 
                                 $dutyGroupId  = $attendanceArray3[$kL]['groupId'];
                                 $dutyPeriodId = $attendanceArray3[$kL]['periodId'];
                                 
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
                                            if($dattendanceArray3[$dl]['studentId']==$studentId && $dattendanceArray3[$dl]['dutyDate']==$dFromDate && $dattendanceArray3[$dl]['periodId']==$dutyPeriodId && $dattendanceArray3[$dl]['groupId']==$dutyGroupId) {
                                               $totalA_Lec = $totalA_Lec + $dattendanceArray3[$dl]['attended'];
                                               $temp = "<b>DL</b>"; 
                                               break;
                                            }
                                         } 
                                     }
                                 }
                                 $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>".$temp."</td>";    
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
                                 $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>".$temp."</td>";       
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
                                 
                                 $dutyGroupId  = $attendanceArray1[$kT]['groupId'];
                                 $dutyPeriodId = $attendanceArray1[$kT]['periodId'];
                                 
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
                                            if($dattendanceArray1[$dl]['studentId']==$studentId && $dattendanceArray1[$dl]['dutyDate']==$dFromDate  && $dattendanceArray1[$dl]['groupId']==$dutyGroupId && $dattendanceArray1[$dl]['periodId']==$dutyPeriodId) {
                                               $totalA_Tut = $totalA_Tut + $dattendanceArray1[$dl]['attended'];
                                               $temp = "<b>DL</b>"; 
                                               break;
                                            }
                                         } 
                                     }
                                   
                                 }
                                 $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>".$temp."</td>";   
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
                                 $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>".$temp."</td>";      
                              }
                    }   
                    
                    if(count($attendanceArray3)>0 || count($attendanceArray1)>0 ) {    
                      $colspan11="colspan='2'";
                    }
                    else {
                      $colspan11='';   
                    }
                    for($j=$countColumns; $j<$nosCol; $j++) {
                      $tableData .= "<td width='5%' valign='top' class='padding_top' $colspan11 align='center'>&nbsp;</td>";   
                    }
                    
                    
                    
                    if(count($attendanceArray3)>0 || count($attendanceArray1)>0 ) {
                        $tableData .= "<td valign='top' class='padding_top' align='center'>".$totalA_Lec.'/'.$totalD_Lec."</td>
                                       <td valign='top' class='padding_top' align='center'>".$totalA_Tut.'/'.$totalD_Tut."</td>
                                       <td valign='top' class='padding_top' align='center'>".($totalA_Lec+$totalA_Tut).'/'.($totalD_Lec+$totalD_Tut)."</td>";
                        
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
                         
                         $tableData .= "<td valign='top' class='padding_top' align='center'>".$perL."%</td>
                                        <td valign='top' class='padding_top' align='center'>".$perT."%</td>
                                        <td valign='top' class='padding_top' align='center'>".$perTot."%</td>
                                        ";
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
      //if($groupTypeId!=1 && $groupTypeId!=3) {      
                  $k=0;
                  $cntAttendance =  count($attendanceArray);
                  for($i=0; $i<$cnt; $i++) {    
                          $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                          $tableData .= "<tr class='$bg'>
                                           <td valign='top' class='padding_top' align='left'>".($i+1)."</td>  
                                           <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['rollNo']."</td>
                                           <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['universityRollNo']."</td>
                                           <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['studentName']."</td>";
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
                             if($groupTypeId==1  || $groupTypeId==3) { 
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
                                $aGroupTypeId  = $attendanceArray[$k]['groupTypeId'];  
                              
                                $dutyPeriodId  = $attendanceArray[$k]['periodId'];  
                                $dutyGroupId  = $attendanceArray[$k]['groupId'];  
                                
                                
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
                                     //$tableData .= "<td width='5%' class='padding_top' align='center'>".$attendanceCode."</td>"; 
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
                                                if($dattendanceArray[$dl]['studentId']==$studentId && $dattendanceArray[$dl]['groupId']==$dutyGroupId && $dattendanceArray[$dl]['periodId']==$dutyPeriodId && $dattendanceArray[$dl]['dutyDate']==$dFromDate) {
//echo "<br>Att. == ".$dattendanceArray[$dl]['studentId']."==".$studentId."==".$dattendanceArray[$dl]['groupId']."==".$dutyGroupId."==".$dattendanceArray[$dl]['periodId']."==".$dutyPeriodId."==".$dattendanceArray[$dl]['dutyDate']."==".$dFromDate;
                                                   $totalA = $totalA + $dattendanceArray[$dl]['attended'];
                                                   $temp = "<b>DL</b>"; 
                                                   break;
                                                }
                                             } 
                                         }  
                                     }
                                     $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>".$temp."</td>";    
                                     break;
                               }
                            }
                            if($chk==0) {
                               $temp = $blankSymbol; 
                              /* if($dutyLeave == 1) {
                                 for($dl=0;$dl<count($dattendanceArray); $dl++) {
                                    if($dattendanceArray[$dl]['studentId']==$studentId && $dattendanceArray[$dl]['dutyDate']==$dFromDate) {
                                       $totalA = $totalA + $dattendanceArray[$dl]['attended'];
                                       $temp = "<b>DL</b>"; 
                                       break;
                                    }
                                 } 
                               } */ 
                               $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>".$temp."</td>";   
                            }
                            $dt=$dt+1;
                            if($dt==$countColumns) {
                              break;
                            }
                            $k++;
                          }   
                          
                          if($dt<$countColumns) {
                            for($j=$dt; $j<$countColumns; $j++) {
                              $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>".$blankSymbol."</td>";  
                            }
                          }
                          
                          /*
                          if($dt<$cnt1) {
                            for($j=$dt; $j<$cnt1; $j++) {
                              $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>".NOT_APPLICABLE_STRING."</td>";  
                            }
                          }
                          */
                          for($j=$countColumns; $j<$nosCol; $j++) {
                             $tableData .= "<td width='5%' valign='top' class='padding_top' align='center'>&nbsp;</td>";  
                          }
                          
                          //if($cnt1>0) {
                          if($cnt1>0) {
                              if($dt==0) {
                                 $tableData .= "<td valign='top' class='padding_top' align='center'>".$blankSymbol."</td>";  
                                 $tableData .= "<td valign='top' class='padding_top' align='center'>".$blankSymbol."</td>";  
                              }
                              else {
                                 if($totalD==0) {
                                   $per = "0.00";
                                 } 
                                 else {
                                   $per = round(($totalA/$totalD)*100,2);  
                                 }
                                 $tableData .= "<td valign='top' class='padding_top' align='center'>".$totalA.'/'.$totalD."</td>
                                                <td valign='top' class='padding_top' align='center'>".$per."%</td>";
                              }
                          }
                          else {             
                             $tableData .= "<td valign='top' class='padding_top' align='center'>&nbsp;</td>";  
                             $tableData .= "<td valign='top' class='padding_top' align='center'>&nbsp;</td>";  
                            /* for($s=0; $s<count($dd); $s++) { 
                                $val = $dd[$s]['dt1'] ; 
                                 $tableData .= "<td width='5%' valign='top' class='searchhead_text' align='center'><strong>".$val."</strong></td>";    
                               }
                           */  
                         }
                         $tableData .= "</tr>";     
                  }
                  
      }    // Group Type Condition END
      
      if($cnt==0) {
         $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
         $tableData .= "<tr class='$bg'>
                           <td valign='top' class='padding_top' align='center' colspan='$colspan'>No Record Found</td>  
                        </tr>";             
      }
      $tableData .= "</table>"; 
      
      echo $tableData;
      die;        
  }
    
  if($reportFormat==0) {
        //$condition  = " AND att.classId = $degree AND att.subjectId = $subjectId  AND  att.groupId = $groupId "; 
        //$condition .= " AND (att.fromDate >= '$fromDate' AND att.fromDate <= '$toDate') ";    
        $condition = " AND c.classId = $degree AND tt.subjectId = $subjectId AND tt.groupId = $groupId AND tt.timeTableLabelId='".$timeTableLabelId."'"; 
        
        // Fetch all student in classwise
        $studentArray =  $studentReportManager->getClasswiseStudent($condition,$orderBy);  
        $cnt = count($studentArray);
        //$studentArray =  $studentReportManager->getStudentAttendanceData($condition,$orderBy);  
        
         
        $dd = array();
        $dt = $fromDate;
        $j=1;
        while(1) {
          $dtArr = explode('-',$dt);
          $dt1 = date('Y-m-d',mktime(0, 0, 0, date($dtArr[1]), date($dtArr[2]), date($dtArr[0])));
          
          $dd[($j-1)]['heading'] =$j."<br>".$dtArr[2]."/".$dtArr[1];
          $dd[($j-1)]['dt']=$dtArr[2]."/".$dtArr[1];  
          
          if($dt1==$toDate) {
             break;
          }
          $dt = date('Y-m-d',mktime(0, 0, 0, date($dtArr[1]), date($dtArr[2]+1), date($dtArr[0])));
          $j = $j + 1;
        } 
        
        
        for($k=0; $k<count($dd); $k++) { 
          for($i=0;$i<$cnt;$i++) {
            $studentArray[$i]['d'.trim($k+1)]='';    
          }
        }

        for($i=0;$i<$cnt;$i++) {
          $valueArray = array_merge(array('srNo' => ($records+$i+1)), $studentArray[$i]);   
          if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
          }
          else {
            $json_val .= ','.json_encode($valueArray);           
          }
        }
   }
    
   echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 

?>

<?php  
//$History: ajaxStudentList.php $
//
//*****************  Version 15  *****************
//User: Parveen      Date: 4/22/10    Time: 1:00p
//Updated in $/LeapCC/Library/AdminTasks
//sorting order update (fromDate, periodId )
//
//*****************  Version 14  *****************
//User: Parveen      Date: 4/12/10    Time: 12:28p
//Updated in $/LeapCC/Library/AdminTasks
//validation format updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 4/12/10    Time: 11:15a
//Updated in $/LeapCC/Library/AdminTasks
//sorting order updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 4/10/10    Time: 5:20p
//Updated in $/LeapCC/Library/AdminTasks
//validation & format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 4/06/10    Time: 1:12p
//Updated in $/LeapCC/Library/AdminTasks
//alignment format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 4/06/10    Time: 12:20p
//Updated in $/LeapCC/Library/AdminTasks
//optional subject code format updated 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 4/05/10    Time: 1:42p
//Updated in $/LeapCC/Library/AdminTasks
//student optional subject code updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 3/19/10    Time: 2:39p
//Updated in $/LeapCC/Library/AdminTasks
//attendance sorting order updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 3/17/10    Time: 12:21p
//Updated in $/LeapCC/Library/AdminTasks
//timeTableLabelId check added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/20/10    Time: 6:26p
//Updated in $/LeapCC/Library/AdminTasks
//format update %age added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/19/10    Time: 1:39p
//Updated in $/LeapCC/Library/AdminTasks
//search condition format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/19/10    Time: 1:18p
//Updated in $/LeapCC/Library/AdminTasks
//format updated (no. of columns check) 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/18/10    Time: 6:19p
//Updated in $/LeapCC/Library/AdminTasks
//format & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 4:28p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//


?>