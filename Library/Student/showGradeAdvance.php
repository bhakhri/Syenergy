<?php
//This file saves student grades
//
// Author :Ajinder Singh
// Created on : 21-oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    
    $labelId = $REQUEST_DATA['labelId'];
    $subjectId = $REQUEST_DATA['subjectId'];
    $degreeId = $REQUEST_DATA['degreeId'];
    $degreeList = $degreeId;
    $gradingFormula = $REQUEST_DATA['gradingFormula'];

    
    // Fetch Active Grading List    
    $gradesArray = $studentManager->getActiveSetGrades();

    $allPendingStudentsDetail='';
    $pendingStudentsArray = array();
    $pendingStudents =0;
    
    /*
    // Fetch Not Transferred Marks of Students
    $pendingStudentsArray = $studentManager->checkSubjectNotTransferredStudents($subjectId, $degreeList);
    $pendingStudents = $pendingStudentsArray[0]['cnt'];
    if($pendingStudents > 0 ) {
      $allPendingStudentsArray = $studentManager->checkSubjectNotTransferredStudentsList($subjectId, $degreeList);     
      
      $allPendingStudentsDetail='';
      $allPendingStudentsDetail="<table border='0' cellpadding='5px' cellspacing='2px' width='100%' class='border'>
                                    <tr class='rowheading'>
                                        <td class='searchhead_text' width='5%'><b>#</b></td>
                                        <td class='searchhead_text' width='25%'><b>Roll No.</b></td>
                                        <td class='searchhead_text' width='30%'><b>Student Name</b></td>
                                        <td class='searchhead_text' width='40%'><b>Father's Name</b></td>
                                    </tr>";
      
      for($i=0;$i<count($allPendingStudentsArray);$i++) {
          $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
          $allPendingStudentsDetail .= "<tr class='$bg'>
                                          <td class='padding_top' >".($i+1)."</td>
                                          <td class='padding_top' >".$allPendingStudentsArray[$i]['rollNo']."</td>
                                          <td class='padding_top' >".$allPendingStudentsArray[$i]['studentName']."</td>
                                          <td class='padding_top' >".$allPendingStudentsArray[$i]['fatherName']."</td>
                                        </tr>";
      }
      $allPendingStudentsDetail .="</table>";
    }
    */
    
        // Fetch Internal + External  Marks
	    $subjectArray = $studentManager->getSingleField('subject_to_class', 
							    'DISTINCT IFNULL(internalTotalMarks,0)+IFNULL(externalTotalMarks,0) AS internalTotalMarks', 
							    "WHERE subjectId = '$subjectId' AND  classId = '$degreeId' ");
                                
	    $internalTotalMarks = $subjectArray[0]['internalTotalMarks'];

        
        // Fetch Previous Grading Scale Values 
	    $gradesLastArray = $studentManager->getLastGradingScalesNew($internalTotalMarks); 
	    $lastGradeCount=count($gradesLastArray);

        $tableData="<table border='0' cellpadding='5px' cellspacing='2px' width='50%' class='contenttab_border2'>
                        <tr class='rowheading'>
                            <td class='searchhead_text' width='20%'><b>Grade</b></td>
                            <td class='searchhead_text' width='20%'><b>From</b></td>
                            <td class='searchhead_text' width='20%'><b>To</b></td>
                        </tr>";
      
          if(count($gradesArray)>0) {
             $valFrom='0';
             for($i=0;$i<count($gradesArray);$i++) {
                $gradeLabel = $gradesArray[$i]['gradeLabel'];
                $gradeId = $gradesArray[$i]['gradeId'];
                $gradeTo ='';
                $gradeFrom ='';
                if(count($gradesLastArray)==count($gradesArray)) {
                  $gradeFrom = $gradesLastArray[$i]['gradingRangeFrom'];
                  $gradeTo = $gradesLastArray[$i]['gradingRangeTo'];
                }
                $idVal = $gradeId;
                
                $readOnly1="";
                if($i!=0) {
                  $readOnly1="readonly='readonly'  style='background-color:#4C6D9D;'";
                }
               
                $readOnly="";
                if(($i+1)==count($gradesArray)) {
                  $gradeTo = $internalTotalMarks;  
                  $readOnly="readonly='readonly' style='background-color:#4C6D9D;'";  
                }
                
                $txtFrom = "<input type='text' $readOnly1 name='ttGradeFrom[]' id='ttGradeFrom$gradeId' class='htmlElement2' value='".$gradeFrom."' >";  
                $txtTo   = "<input type='text' $readOnly  name='ttGradeTo[]' onkeyup='setGradeValue(); return false;'  id='ttGradeTo$gradeId' class='htmlElement2' maxlength='7' value='".$gradeTo."' >
                            <input type='hidden' name='hiddenGradeId[]'  id='hiddenGradeId$gradeId'  value='".$gradeId."'  >";
                $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                $tableData .= "<tr class='$bg'>  
                                  <td class='padding_top' ><lable>$gradeLabel</lable></td>
                                  <td class='padding_top' >".$txtFrom."</td>
                                  <td class='padding_top' >".$txtTo."</td>
                              </tr>";
             }
             $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
             $tableData .="<tr class='$bg'>
                              <td class='padding_top' align='left' colspan='3'>
                                 <input type='image' src='".IMG_HTTP_PATH."/calculate_mgpa.gif' onClick='calculateManualMGPA();' />
                              </td>
                            </tr>";
          }
          else {
             $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
             $tableData .="<tr class='$bg'><td class='padding_top' align='center' colspan='3'>Grading Not Defined</td></tr>";
          }
          $tableData .="</table>";
    
  
      $totalArray = Array('gradesArray' => $gradesArray,  
                          'pendingStudents' => $pendingStudents, 
                          'internalTotalMarks' => $internalTotalMarks,
                          'gradesLastArray' =>$gradesLastArray,
                          'lastGradeCount' => $lastGradeCount,
                          'gradeTable' =>  $tableData,
                          'allPendingStudentsDetail' => $allPendingStudentsDetail
                         );
                    
      echo json_encode($totalArray);
?>
