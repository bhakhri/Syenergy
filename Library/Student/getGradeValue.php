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
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

  
  $id = trim($REQUEST_DATA['id']);
  if($id=='') {
    $id='0';  
  }
    
  $condition = " AND b.gradingLabelId = '$id' ";
  $gradesArray = $studentManager->getShowGrades($condition);
    
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
        //$gradeFrom = $gradesArray[$i]['gradingRangeFrom'];
        $gradeTo = $gradesArray[$i]['gradingRangeTo'];
        $gradingScaleId = $gradesArray[$i]['gradingScaleId'];
        $gradeId = $gradesArray[$i]['gradeId'];
       
        $idVal = $gradingScaleId."~".$gradeId;
        
        $readOnly1="";
        if($i!=0) {
          $readOnly1="readonly='readonly'  style='background-color:#4C6D9D;'";
        }
        else {
           $gradeFrom = $gradesArray[$i]['gradingRangeFrom']; 
        }
        
        if($gradeFrom=='') {
          $gradeFrom=0;  
        }
        
        $readOnly="";
        if(($i+1)==count($gradesArray)) {
          $readOnly="readonly='readonly' style='background-color:#4C6D9D;'";  
        }
        
        $txtFrom = "<input type='text' $readOnly1 name='ttGradeFrom[]' id='ttGradeFrom$gradeId' class='htmlElement2' value='".$gradeFrom."' >";  
        $txtTo   = "<input type='text' $readOnly name='ttGradeTo[]' onkeyup='setGradeValue(); return false;'  id='ttGradeTo$gradeId' class='htmlElement2' maxlength='7' value='".$gradeTo."' >
                    <input type='hidden' name='hiddenGradeId[]'  id='hiddenGradeId$gradeId'  value='".$gradeId."'  >";
        $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
        $tableData .= "<tr class='$bg'>  
                          <td class='padding_top' ><lable>$gradeLabel</lable></td>
                          <td class='padding_top' >".$txtFrom."</td>
                          <td class='padding_top' >".$txtTo."</td>
                      </tr>";
        $gradeFrom = $gradeTo;
        
        if(intval($gradeFrom)==$gradeFrom) {
          $gradeFrom = $gradeFrom + 1;  
        }
        else {
          $gradeFrom = $gradeFrom + .01;  
        }
     }
     
  }
  
  
	
  echo $tableData;

?>
