<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/ Re-exam Flow
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/HoldUnholdClassManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $HoldUnholdClassManager = HoldUnholdClassManager::getInstance(); 
    

    $classId = trim($REQUEST_DATA['classId']);
    $hiddenClassId = trim($REQUEST_DATA['hiddenClassId']); 
    
    if($classId=='') {
      $classId=0;  
    }
    
    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 10000;  
    $limit      = ' LIMIT '.$records.',10000';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";
        
    
    $foundArray = $HoldUnholdClassManager->getIndividualStudents($classId,$hiddenClassId); 
    $cnt = count($foundArray);
    
    $tableArray ="<table border='0px' width='100%' cellpadding='0px' cellspacing='2px' align='center' >
                   <tr class='rowheading'>  
                        <td align='left' style='padding-left:6px' width='4%'><b>#</b></td>
                        <td align='left' width='16%'><b>Student Name</b></td>
                        <td align='left' width='12%'><b>Roll No.</b></td>
                        <td align='left' width='10%'><b>Leet</b></td>
                        <td align='left' width='10%'><b>Migration</b></td>
                        <td align='center' width='10%'><b>Attendance<Br><input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAllStudentCheck(2);\"></b></td>
                        <td align='center' width='10%'><b>Test Marks<Br><input type=\"checkbox\" id=\"checkbox3\" name=\"checkbox3\" onclick=\"doAllStudentCheck(3);\"></b></td>
                        <td align='center' width='10%'><b>Final Result<Br><input type=\"checkbox\" id=\"checkbox4\" name=\"checkbox4\" onclick=\"doAllStudentCheck(4);\"></b></td>
                        <td align='center' width='10%'><b>Grades<Br><input type=\"checkbox\" id=\"checkbox5\" name=\"checkbox5\" onclick=\"doAllStudentCheck(5);\"></b></td>
                    </tr>"; 

    //<td align='center' width='10%'><b>All Classes<Br><input type=\"checkbox\" id=\"checkbox6\" name=\"checkbox6\" onclick=\"doAllStudentCheck(6);\"></b></td>                    
                    
    for($i=0;$i<$cnt;$i++) {
        $classId =  $foundArray[$i]['classId'];
	    $studentId =  $foundArray[$i]['studentId'];
        $studentName = $foundArray[$i]['studentName'];
        $rollNo = $foundArray[$i]['rollNo'];
        $regNo = $foundArray[$i]['regNo'];
        $studentLeet = $foundArray[$i]['studentLeet'];
        $studentMigration = $foundArray[$i]['studentMigration']; 
        
        $chk1='';
        $chk2='';
        $chk3='';
        $chk4='';
        $chk5='';
        $span1="<span id='spanAtt1$studentId'>Unheld</span>";
        $span2="<span id='spanMarks1$studentId'>Unheld</span>";
        $span3="<span id='spanFinal1$studentId'>Unheld</span>";
        $span4="<span id='spanGrade1$studentId'>Unheld</span>";
        $span5="<span id='spanAllClass$studentId'>Unheld</span>";
	
        if($foundArray[$i]['holdAttendance']=='1') {
          $chk1="checked='checked'";
          $span1="<span id='spanAtt1$studentId'>Held</span>"; 
        }
        if($foundArray[$i]['holdTestMarks']=='1') {
          $chk2="checked='checked'"; 
          $span2="<span id='spanMarks1$studentId'>Held</span>"; 
        }
        if($foundArray[$i]['holdFinalResult']=='1') {
          $chk3="checked='checked'";  
          $span3="<span id='spanFinal1$studentId'>Held</span>"; 
        }
        if($foundArray[$i]['holdGrades']=='1') {
          $chk4="checked='checked'"; 
          $span4="<span id='spanGrade1$studentId'>Held</span>"; 
        }  
       
        $attendance = "<input type='checkbox' onclick='getHoldStudents($studentId,\"chbattendance1~spanAtt1\");' $chk1 name='chbattendance1[]' id='chbattendance1_".$studentId."' value='$studentId'>".$span1;
	    $marks = "<input type='checkbox' onclick='getHoldStudents($studentId,\"chbmarks1~spanMarks1\");' $chk2 name='chbmarks1[]' id='chbmarks1_".$studentId."' value='$studentId'>".$span2; 
	    $finalResult = "<input type='checkbox' onclick='getHoldStudents($studentId,\"chbfinalResult1~spanFinal1\");' $chk3 name='chbfinalResult1[]' id='chbfinalResult1_".$studentId."' value='$studentId'>".$span3; 
	    $grades = "<input type='checkbox' onclick='getHoldStudents($studentId,\"chbgrades1~spanGrade1\");' $chk4 name='chbgrades1[]' id='chbgrades1_".$studentId."' value='$studentId'>".$span4; 
        $allClasses = "<input type='checkbox' onclick='getHoldStudents($studentId,\"chbAllClass1~spanAllClass\");' $chk5 name='chbAllClass1[]' id='chbAllClass1_".$studentId."' value='$studentId'>".$span5; 
        $hiddenStudentId = "<input type='hidden' name='chbstudentId[]' id='chbstudentId_".$studentId."' value='$studentId'>";
    
        $bg = $bg =='row0' ? 'row1' : 'row0'; 
        $tableArray .="<tr class=".$bg."> 
                            <td align='left'>".($i+1).$hiddenStudentId."</td>
                            <td align='left'>".$studentName."</td>
                            <td align='left'>".$rollNo."</td>
                            <td align='left'>".$studentLeet."</td>
                            <td align='left'>".$studentMigration."</td>
                            <td align='center'>".$attendance."</td>
                            <td align='center'>".$marks."</td>
                            <td align='center'>".$finalResult."</td>
                            <td align='center'>".$grades."</td>
                         </tr>"; 
    }
    if($cnt==0) {
      $tableArray .="<tr class=".$bg.">  
                        <td align='center'><b>No Data Found</td>
                     </tr>";   
    }                   
    echo $tableArray;
?>
