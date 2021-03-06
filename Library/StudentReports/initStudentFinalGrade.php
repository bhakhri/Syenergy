<?php
//--------------------------------------------------------
//This file returns the array of of Test Time Period
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentFinalGradeReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();  
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();

    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
    $classId  = $REQUEST_DATA['classId'];
    $subjectId   = $REQUEST_DATA['subjectId'];
    $incMarksPer   = $REQUEST_DATA['incMarksPer']; 
   
    if($timeTableLabelId=='') {
       $timeTableLabelId = 0;  
    }
   
    if($classId=='') {
       $classId = 0;  
    }
    
    if($subjectId=='') {
       $subjectId = 0;  
    }
    
    if($incMarksPer=='') {
      $incMarksPer=0;  
    }
    
     $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
     $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'universityRollNo';
     
     if ($sortField == 'studentName') {
        $sortField1 = 'IF(IFNULL(studentName,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, studentName)';
     }
     else if ($sortField == 'rollNo') {
        $sortField1 = 'IF(IFNULL(rollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, rollNo)';
     } 
     else if ($sortField == 'universityRollNo') {
        $sortField1 = 'IF(IFNULL(universityRollNo,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'",studentId, universityRollNo)';
     }
    
     $orderBy = " $sortField1 $sortOrderBy";    
     
     // $recordsPerPage = RECORDS_PER_PAGE;
     $recordsPerPage = 5000; 
     // to limit records per page    
     $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
     $records    = ($page-1)* $recordsPerPage;
     $limit      = ' LIMIT '.$records.','.$recordsPerPage;
     
     //this function will format number to $decimal places
     function formatTotal($input,$decimal=2){
        return number_format($input,1,'.','');
     }
     
     $marksNotTransferredIndicator='MNT';  
     
     $attCondition = " AND att.classId= '$classId' AND att.subjectId = '$subjectId'";
     $attOrderBy = " subjectCode ";
     $consolidated = "1";                                                              
     $studentAttendanceArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($attCondition,$attOrderBy,$consolidated);  
     
     
     $studentCondition = " AND c.classId = $classId AND tt.subjectId IN ($subjectId) AND tt.timeTableLabelId='".$timeTableLabelId."'";  
     
     $totalStudentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
     $totalStudent = count($totalStudentArray);
     $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy,$limit);  
     
     $studentTestTypeCategoryArray=$studentReportManager->getStudentTestTypeCategoryCount($classId,$subjectId);
     $ttcCount=count($studentTestTypeCategoryArray);
     
     $tableData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                    <tr class='rowheading'>
                      <td width='2px'  class='searchhead_text' align='left'><b><nobr>#</nobr></b></td>
                      <td width='5px'  class='searchhead_text' align='left'><strong><nobr>Roll No.</nobr></strong></td>
                      <td width='20px' class='searchhead_text' align='left'><strong><nobr>Student Name</nobr></strong></td>"; 
     for($j=0; $j< $ttcCount; $j++) {
        $testTypeName =  $studentTestTypeCategoryArray[$j]['testTypeName'];
        $tableData .= "<td width='20px' class='searchhead_text' align='center'><strong><nobr>$testTypeName</nobr></strong></td>";   
     }
     
                                                                                                                                      
     $tableData .= "<td width='20px' class='searchhead_text' align='center'><strong><nobr>Final</nobr></strong></td>
                    <td width='20px' class='searchhead_text' align='center'><strong><nobr>Letter Grade</nobr></strong></td>
                    <td width='20px' class='searchhead_text' align='center'><strong><nobr>Grade Point</nobr></strong></td>";  
     if($incMarksPer==1) {                
       $tableData .= "<td width='20px' class='searchhead_text' align='center'><strong><nobr>Attendance</nobr></strong></td>";
     }
     else {
       $tableData .= "<td width='20px' class='searchhead_text' align='center'><strong><nobr>Attendance<br>%</nobr></strong></td>";
     }
     $tableData .= "<td width='20px' class='searchhead_text' align='center'><strong><nobr>Attendance<br>Grade Cut</nobr></strong></td>
                    <td width='20px' class='searchhead_text' align='center'><strong><nobr>Final Grade</nobr></strong></td>
                    <td width='20px' class='searchhead_text' align='center'><strong><nobr>Letter Grade</nobr></strong></td>
                    </tr>";
   
     $valueArray = array();
     
    // Show Student Test Type Details
     for($i=0; $i< count($studentArray); $i++) {
        $studentId = $studentArray[$i]['studentId']; 
        $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
        $tableData .= "<tr class='$bg'>
                         <td valign='top' class='padding_top' align='left'>".($records+$i+1)."</td>  
                         <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['rollNo']."</td>
                         <td valign='top' class='padding_top' align='left'>".$studentArray[$i]['studentName']."</td>"; 
     
        $valueArray[$i]['studentId'] = $studentId;
        $valueArray[$i]['classId'] = $classId;
        $valueArray[$i]['subjectId'] = $subjectId;
        $valueArray[$i]['rollNo'] = $studentArray[$i]['rollNo'];
        $valueArray[$i]['studentName'] = $studentArray[$i]['studentName'];
        
        $total="";    
        $maxMarks="";     
        //get test typa variations(how many sessionals,assignments etc)
        for($j=0; $j< $ttcCount; $j++) { 
          $testTypeCategoryId = $studentTestTypeCategoryArray[$j]['testTypeCategoryId'];       
          $ids = "test".$j;
          
          $categoryCondition =  " AND  ttc.testTypeCategoryId = $testTypeCategoryId ";
          //get test typa variations(how many sessionals,assignments etc)
          $testTypeCategoryArray=$studentReportManager->getTestTypeCategoryCount($studentId,$classId,$subjectId,$categoryCondition);
          $testTypeCategoryIds=UtilityManager::makeCSList($testTypeCategoryArray,'testTypeCategoryId');
       
          $testTypeIds = '-1';  
          if($testTypeCategoryIds!=''){
            //get the testypes corresponding to these categories
            $testTypeArray=$studentReportManager->getTestTypesDetails($testTypeCategoryIds);
            $testTypeIds =UtilityManager::makeCSList($testTypeArray,'testTypeId');
          }
          
          if($testTypeIds=='') {
            $testTypeIds = '-1';  
          }
          
          $showGrade = NOT_APPLICABLE_STRING;
          $showGradeValue = NOT_APPLICABLE_STRING;
          
          $condition = " AND tt.conductingAuthority = 1 AND tt.testTypeCategoryId = $testTypeCategoryId";
          $testTransferredMarksArray=$studentReportManager->getSubjectWiseTestTransferredMarks($testTypeIds,$studentId,$classId,$subjectId, $condition);
          if(count($testTransferredMarksArray)>0) {
             $tot = formatTotal($testTransferredMarksArray[0]['marksScored']).'/'.formatTotal($testTransferredMarksArray[0]['maxMarks']);
             $per = formatTotal(($testTransferredMarksArray[0]['marksScored']/$testTransferredMarksArray[0]['maxMarks'])*100);
             if($incMarksPer==1) {
                $tableData .= "<td valign='top' class='padding_top' align='center'>$tot ($per%)</td>";
             }
             else {
                $tableData .= "<td valign='top' class='padding_top' align='center'>$per</td>";
             }
             if($total=="") {
               $total=0;   
               $maxMarks=0;
             }
             $total = doubleval($total) + doubleval($tot);
             $maxMarks = doubleval($maxMarks) + doubleval($testTransferredMarksArray[0]['maxMarks']);
             
             $showGrade = $testTransferredMarksArray[0]['gradeLabel'];
             $showGradeValue = $testTransferredMarksArray[0]['gradePoints'];
          }                
          else { 
            $tableData .= "<td valign='top' class='padding_top' align='center'>$marksNotTransferredIndicator</td>";
          }
        }
        if($total=="") {
          $tableData .= "<td valign='top' colspan='3' class='padding_top' align='center'>$marksNotTransferredIndicator</td>";
          $valueArray[$i]['total'] = "";
        }
        else {
          
          $showGrade = $marksNotTransferredIndicator;  
          $showGradeValue = $marksNotTransferredIndicator;
          $condition = " AND ttm.studentId = $studentId AND ttm.classId = $classId AND ttm.subjectId = $subjectId ";
          $studentTotalMarksArray = $studentReportManager->getSubjectTransferredDetails($condition); 
          if(count($studentTotalMarksArray)>0) {
            $total = $studentTotalMarksArray[0]['marksScored'];    
            $showGrade =    $studentTotalMarksArray[0]['gradeLabel'];
            $showGradeValue =  $studentTotalMarksArray[0]['gradePoints'];            
          }
          $valueArray[$i]['grade'] = $showGrade;  
          $valueArray[$i]['point'] = $showGradeValue; 
         
          $tableData .= "<td valign='top' class='padding_top' align='center'>".formatTotal($total)."</td>
                         <td valign='top' class='padding_top' align='center'>".$showGrade."</td>
                         <td valign='top' class='padding_top' align='center'>".$showGradeValue."</td>";  
        }     
        /*  $attTot = $marksNotTransferredIndicator;  
            $attPer = $marksNotTransferredIndicator;
            $condition = " AND ttm.conductingAuthority = 3";
            $studentTotalMarksArray = $studentReportsManager->getStudentTotalTransferredMarks($classId,$studentId,$subjectId,$condition); 
            if(count($studentTotalMarksArray)>0) {
               $attTot = formatTotal($studentTotalMarksArray[0]['marksScored']).'/'.formatTotal($studentTotalMarksArray[0]['maxMarks']);
               $attPer = formatTotal(($studentTotalMarksArray[0]['marksScored']/$studentTotalMarksArray[0]['maxMarks'])*100);                          
            }
        */
        $find=0; 
        for($att=0;$att<count($studentAttendanceArray);$att++) {          
           if($studentAttendanceArray[$att]['studentId'] == $studentId) {
             $find=1;  
             $attended = $studentAttendanceArray[$att]['attended'];
             $delivered = $studentAttendanceArray[$att]['delivered'];
             $leaveTaken = $studentAttendanceArray[$att]['leaveTaken'];
             $valueArray[$i]['attended'] = 0;  
             $valueArray[$i]['delivered'] = 0;  
             $valueArray[$i]['dutyLeave'] = 0;  
             $valueArray[$i]['percentage'] = 0;
             if($delivered=='0') {
                $tot = 0;  
                $per = 0;
             } 
             else {
                $tot = ($attended+$leaveTaken).'/'.$delivered;
                $per = formatTotal((($attended+$leaveTaken)/$delivered*100));  
                $valueArray[$i]['attended'] = $attended;  
                $valueArray[$i]['delivered'] = $delivered;  
                $valueArray[$i]['dutyLeave'] = $leaveTaken;  
                $valueArray[$i]['percentage'] = $per;  
             }
             if($tot==0) {
               $result=0; 
             }
             else {
                if($incMarksPer==1) { 
                  $result = "$tot ($per%)";
                }
                else {
                  $result = "$per";
                }
             }
             $tableData .= "<td valign='top' class='padding_top' align='center'>".$result."</td>"; 
             break;
           }
        }
        if($find==0) {
          $valueArray[$i]['attended'] = "";  
          $valueArray[$i]['delivered'] = "";  
          $valueArray[$i]['dutyLeave'] = "";  
          $valueArray[$i]['percentage'] = ""; 
          $tableData .= "<td valign='top' class='padding_top' align='center'>".NOT_APPLICABLE_STRING."</td>";  
        }
        $per = $valueArray[$i]['percentage'];
        $conditionAttGradeDeduct = " AND ('$per' BETWEEN minval AND maxval) "; 
        $attendanceGradeDeductArray = $studentReportManager->getAttendanceGradeDeduct($conditionAttGradeDeduct);  
        $valueArray[$i]['gradeDeduct'] = "";  
        $valueArray[$i]['attendanceDeductId'] = "";
        if(is_array($attendanceGradeDeductArray) && count($attendanceGradeDeductArray)>0 ) { 
           $valueArray[$i]['gradeDeduct'] = $attendanceGradeDeductArray[0]['point']; 
           $valueArray[$i]['attendanceDeductId'] = $attendanceGradeDeductArray[0]['attendanceGradeId'];       
           $tableData .= "<td valign='top' class='padding_top' align='center'>".$valueArray[$i]['gradeDeduct']."</td>"; 
        }
        else {
          $tableData .= "<td valign='top' class='padding_top' align='center'></td>";   
        }
        $finalPoint = "";
        if($valueArray[$i]['gradeDeduct']=='') {
          $finalPoint = $valueArray[$i]['point'];
        }
        else {
          $finalPoint = doubleval($valueArray[$i]['point'])-doubleval($valueArray[$i]['gradeDeduct']);  
        }
        $valueArray[$i]['finalPoint'] = $finalPoint;  
        $tableData .= "<td valign='top' class='padding_top' align='center'>".$finalPoint."</td>"; 
       
        $finalPoint = $valueArray[$i]['finalPoint'];
        $conditionCGPAGrade = " AND ('$finalPoint' BETWEEN minval AND maxval) "; 
        $finalCGPAArray = $studentReportManager->getStudentFinalCGPAGrade($conditionCGPAGrade); 
        $valueArray[$i]['finalGrade'] = "I";  
        if(is_array($finalCGPAArray) && count($finalCGPAArray)>0 ) { 
          $valueArray[$i]['finalGrade'] = $finalCGPAArray[0]['grade'];                         
        }
        $tableData .= "<td valign='top' class='padding_top' align='center'>".$valueArray[$i]['finalGrade']."</td>
                       </tr>";
     }
      
     $tableData .= "</table>";      
     echo $tableData.'!~~!'.$totalStudent;
     die;
?>