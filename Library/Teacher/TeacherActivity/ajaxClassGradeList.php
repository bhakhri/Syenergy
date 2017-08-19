<?php
//--------------------------------------------------------  
//It contains the class wise grade list
//
// Author :Diapnajn BHattacharjee
// Created on : 22-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ClassWiseGradeList');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

//------------------------------------------------------------------------------------------------
// This Function  creates blank TDs
//
// Author : Dipanjan Bhattacharjee
// Created on : 31.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------   
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$teacherManager = TeacherManager::getInstance();

//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Dipanjan Bhattacharjee
//Date:19.09.2008
//-----------------------------------------------------------------------------------
function parseName($value){
    $name=explode(' ',$value);
    $genName="";
    $len= count($name);
    if($len > 0){
      for($i=0;$i<$len;$i++){
          if(trim($name[$i])!=""){
              if($genName!=""){
                  $genName =$genName ." ".$name[$i];
              }
             else{
                 $genName =$name[$i];
             } 
          }
      }
    }
  if($genName!=""){
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".add_slashes($genName)."%'";
  }  
  
  return $genName;
}


//creates the search condition
$conditions=" AND c.classId='".$REQUEST_DATA['classId']."' 
              AND su.subjectId='".$REQUEST_DATA['subjectId']."' 
              AND g.groupId='".$REQUEST_DATA['groupId']."'";
if(trim($REQUEST_DATA['studentRollNo'])!=''){
  $conditions .=' AND ( s.rollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%" OR s.universityRollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%" ) ';
}

if(trim($REQUEST_DATA['studentName'])!=""){
        $parsedName=parseName(trim($REQUEST_DATA['studentName']));    //parse the name for compatibality
        $conditions .=" AND (
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($REQUEST_DATA['studentName']))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($REQUEST_DATA['studentName']))."%'
                                  $parsedName
                               )"; 
}

$sortField=' studentName';
$sortBy =' ASC ';

if(trim($REQUEST_DATA['sortField'])==1){
    $sortField=' LENGTH(s.rollNo)+0,s.rollNo';
}
if(trim($REQUEST_DATA['sortField'])==2){
    $sortField=' LENGTH(s.universityRollNo)+0,s.universityRollNo';
}
if(trim($REQUEST_DATA['sortField'])==3){
    $sortField=' studentName';
}

if(trim($REQUEST_DATA['sortBy'])==1){
    $sortBy=' ASC';
}
if(trim($REQUEST_DATA['sortBy'])==0){
    $sortBy=' DESC';
}

$orderBy=$sortField.'  '.$sortBy;

$teacherGradeArray = $teacherManager->getClassWiseGradeList($conditions,' ',$orderBy);

$gradeStr="";
//build the string
$gradeStr='<table width="100%" border="0" cellspacing="1" cellpadding="1"  id="anyid" >';
$gradeStr .= '<tr class="rowheading">
    <td width="2%" valign="middle" align="left"  style="padding-left:3px" ><b>Sr.No.</b>
    <td valign="middle" align="left" width="15%" style="padding-left:3px;"><b>Name</b></td>
    <td valign="middle" align="left" width="10%" style="padding-left:3px;"><b>Roll No.</b></td>
    <td valign="middle" align="left" width="10%" style="padding-left:3px;"><b>Univ Roll. No.</b></td>
    <td valign="middle" align="left" width="10%" style="padding-left:3px;"><b>Subject</b></td>
    <td valign="middle" align="left" width="12%" style="padding-left:3px;"><b>Exam</b></td>
    <td valign="middle" align="left" width="15%" style="padding-left:3px;"><b>TestType</b></td>
    <td valign="middle" align="left" width="8%"  style="padding-left:3px;"><b>Test</b></td>
    <td valign="middle" align="right" width="8%"  style="padding-right:3px;"><b>T.Mark</b></td>
    <td valign="middle" align="right" width="10%" style="padding-right:3px"><b>Obtained</b></td>
    </tr>';

                $recordCount = count($teacherGradeArray);
                if($recordCount >0 && is_array($teacherGradeArray) ) {
                   $studentName ="" ; $sN="";
                   $studentId ="" ;
                   $subjectName =""; $subN="";
                   $examType    ="";  $eT="";
                   $testTypeName="";  $tTN="";
                   $sRollNo='';
                   $sUnivRollNo='';
                   //$rollNo = "";
                   $j=0;
                   $k=0;
                   for($i=0; $i<$recordCount; $i++ ) {
                            $bg = $bg =='row0' ? 'row1' : 'row0';
                            
                            if($studentId==$teacherGradeArray[$i]['studentId']){
                               $sN="";
                               $sRollNo='';
                               $sUnivRollNo='';
                               $j="";$k++; 
                            }
                           else{
                               $j=$i-$k+1;
                               $studentId=$teacherGradeArray[$i]['studentId'];
                               $studentName=$teacherGradeArray[$i]['studentName'];
                              /* 
                               if(trim($teacherGradeArray[$i]['rollNo'])!=""){
                                $sN=$studentName.'<br />['.trim($teacherGradeArray[$i]['rollNo']).']';
                               }
                               else{
                                   $sN=$studentName.'<br />[---]'; 
                               }
                               */
                               $sN=$studentName; 
                               $sRollNo=$teacherGradeArray[$i]['rollNo'];
                               $sUnivRollNo=$teacherGradeArray[$i]['universityRollNo'];
                               
                               $subjectName="";
                               $examType="";
                               $testTypeName="";
                             if($i!=0)  //if it is not the first record
                                $gradeStr .='<tr><td colspan="10" ><hr></td></tr>'; //create a <hr> row as new student name found

                            }  
                           if($subjectName==$teacherGradeArray[$i]['subjectName']){
                               $subN="";   
                            }
                           else{
                               $subjectName=$teacherGradeArray[$i]['subjectName'];
                               //$subN=$subjectName." ( ".$teacherGradeArray[$i]['subjectCode']." )";
                               $subN=$teacherGradeArray[$i]['subjectCode'];
                               $examType="";
                               $testTypeName="";   
                            }
                           if($examType==$teacherGradeArray[$i]['examType']){
                               $eT="";     
                            }
                           else{
                               $examType=$teacherGradeArray[$i]['examType'];
                               $eT=$examType;
                               $testTypeName="";     
                            }
                           if($testTypeName==$teacherGradeArray[$i]['testTypeName']){
                               $tTN="";     
                            }
                           else{
                               $testTypeName=$teacherGradeArray[$i]['testTypeName'];
                               $tTN=$testTypeName;   
                           }      
                              
                        $gradeStr .= '<tr class="'.$bg.'">
                            <td valign="top" class="padding_top" style="padding-left:3px;" >'.$j.'</td>
                            <td class="padding_top2" style="padding-left:3px;" >'.$sN.'</td>
                            <td class="padding_top2" style="padding-left:3px;">'.$sRollNo.'</td>
                            <td class="padding_top2" style="padding-left:3px;">'.$sUnivRollNo.'</td>
                            <td class="padding_top2" style="padding-left:3px;">'.$subN.'</td>
                            <td class="padding_top2" style="padding-left:3px;">'.$eT.'</td>
                            <td class="padding_top2" style="padding-left:3px;">'.$tTN.'</td>
                            <td class="padding_top2" style="padding-left:3px;">'.$teacherGradeArray[$i]['testName'].'</td> 
                            <td valign="top" class="padding_top" align="right" style="padding-right:3px;">'.$teacherGradeArray[$i]['totalMarks'].'</td> 
                            <td valign="top" class="padding_top" align="right" style="padding-right:3px;">'.$teacherGradeArray[$i]['obtainedMarks'].'</td> 
                            </tr>';
                         }
                      }
                      else {
                       $gradeStr .= '<tr><td colspan="8" align="center">No record found</td></tr>';
                      }
 //echo the result
 echo $gradeStr;               
//$History: ajaxClassGradeList.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 28/01/10   Time: 13:07
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Univ. Roll No." column in "Display Marks" report
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 29/06/09   Time: 11:32
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added name & roll no wise search in display attendance and marks
//display in teacher login
//
//*****************  Version 2  *****************
//User: Administrator Date: 20/05/09   Time: 12:12
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified Grade View Display
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/07/08    Time: 4:52p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>
