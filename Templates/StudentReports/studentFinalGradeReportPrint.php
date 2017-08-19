<?php
//--------------------------------------------------------
//This file returns the array of of Test Time Period
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

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
     
     $studentArray =  $studentReportManager->getClasswiseStudent($studentCondition,$orderBy);  
     
     $studentTestTypeCategoryArray=$studentReportManager->getStudentTestTypeCategoryCount($classId,$subjectId);
     $ttcCount=count($studentTestTypeCategoryArray);
     
     
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
     
        $valueArray[$i]['srNo'] = ($i+1);
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
          $valueArray[$i]['testTypeId_'.$j] = $marksNotTransferredIndicator;
          
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
                $valueArray[$i]['testTypeId_'.$j] = "$tot ($per%)";
             }
             else {
                $valueArray[$i]['testTypeId_'.$j] = $per;
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
             $valueArray[$i]['testTypeId_'.$j] = $marksNotTransferredIndicator;
          }
        }
        if($total=="") {
          $valueArray[$i]['testMarks'] = $marksNotTransferredIndicator;
          $valueArray[$i]['testGrade'] = $marksNotTransferredIndicator;
          $valueArray[$i]['testPoint'] = $marksNotTransferredIndicator;
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
          $valueArray[$i]['testMarks'] = formatTotal($total);
          $valueArray[$i]['testGrade'] = $showGrade;
          $valueArray[$i]['testPoint'] = $showGradeValue;
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
             $valueArray[$i]['attendance'] = $result;
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
           $valueArray[$i]['attendanceCut'] = $attendanceGradeDeductArray[0]['point'];
        }
        else {
          $tableData .= "<td valign='top' class='padding_top' align='center'></td>";   
        }
        $finalPoint = "";
        if($valueArray[$i]['attendanceCut']=='') {
          $finalPoint = $valueArray[$i]['testPoint'];
        }
        else {
          $finalPoint = doubleval($valueArray[$i]['testPoint'])-doubleval($valueArray[$i]['attendanceCut']);  
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
     }
     
 
    // Findout Time Table Name
    $timeNameArray = $studentReportManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = $timeTableLabelId");
    $timeTableName = $timeNameArray[0]['labelName'];
    if($timeTableName=='') {
      $timeTableName = NOT_APPLICABLE_STRING;
    }

    // Findout Class Name
    $classNameArray = $studentReportManager->getSingleField('class', 'className', "WHERE classId  = $classId");
    $className = $classNameArray[0]['className'];
    if($className=='') {
      $className = NOT_APPLICABLE_STRING;
    }
    
    // Findout Subject
    $subjectArray = $studentReportManager->getSingleField('subject', 'subjectName,subjectCode', "WHERE subjectId  = $subjectId");
    $subName = $subjectArray[0]['subjectName'];
    $subCode = $subjectArray[0]['subjectCode'];  
    
    
     
    $search  = "Time Table&nbsp;:&nbsp;$timeTableName<br>Class&nbsp;:&nbsp;$className<br>";
    $search .= "Subject&nbsp;:&nbsp;$subName&nbsp;($subCode)";
   

    $reportManager->setReportWidth(900);
    $reportManager->setReportHeading('Apply Student Final Grade Report');
    $reportManager->setReportInformation($search);    
    
    $reportTableHead                        =    array();
                //associated key          col.label,                       col. width,      data align
    $reportTableHead['srNo']           = array('#',           'width="5%"  align="center"',  'align="center"');
    $reportTableHead['rollNo']         = array('Roll No.',    'width="10%" align="left"', 'align="left"');
    $reportTableHead['studentName']    = array('Student Name','width="15%" align="left"', 'align="left"');
    for($j=0; $j< $ttcCount; $j++) { 
       $testTypeId =    "testTypeId_".$j;
       $testTypeName =  $studentTestTypeCategoryArray[$j]['testTypeName'];
       $reportTableHead[$testTypeId]    = array("$testTypeName",           'width="5%" align="center"', 'align="center"');
    }
    $reportTableHead['testMarks']       = array('Final',                   'width="5%" align="center"', 'align="center"');
    $reportTableHead['testGrade']       = array('Letter Grade',            'width="5%" align="center"', 'align="center"');
    $reportTableHead['testPoint']       = array('Grade Point',             'width="5%" align="center"', 'align="center"');
    if($incMarksPer==1) {                
      $reportTableHead['attendance']    = array('Attendance',              'width="5%" align="center"', 'align="center"');
    }
    else {
      $reportTableHead['attendance']    = array('Attendance<br>%',         'width="5%" align="center"', 'align="center"'); 
    }
    $reportTableHead['attendanceCut']   = array('Attendance<br>Grade Cut', 'width="5%" align="center"', 'align="center"'); 
    $reportTableHead['finalPoint']      = array('Final Grade',             'width="5%" align="center"', 'align="center"'); 
    $reportTableHead['finalGrade']      = array('Letter Grade',            'width="5%" align="center"', 'align="center"');   
   
    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
?>
