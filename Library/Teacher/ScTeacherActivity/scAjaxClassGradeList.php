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
UtilityManager::ifTeacherNotLoggedIn();
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

require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
$teacherManager = ScTeacherManager::getInstance();

//creates the condition
$conditions=($REQUEST_DATA['rollNo']!="" ? " AND s.rollNo='".$REQUEST_DATA['rollNo']."'" : " AND su.subjectId=".$REQUEST_DATA['subjectId']." AND ssc.subjectId=".$REQUEST_DATA['subjectId']." AND ssc.sectionId=".$REQUEST_DATA['sectionId']);

$teacherGradeArray = $teacherManager->getClassWiseGradeList($conditions);

$gradeStr="";
//build the string
$gradeStr='<table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid" >';
$gradeStr .= '<tr class="rowheading">
    <td width="5%" valign="middle" align="left" style="padding-left:3px" ><b>S.No</b>
    <td valign="middle" align="left" width="20%"><b>Name</b></td>
    <td valign="middle" align="left" width="15%"><b>Subject</b></td>
    <td valign="middle" align="left" width="15%" ><b>Exam</b></td>
    <td valign="middle" align="left" width="15%"><b>TestType</b></td>
    <td valign="middle" align="left" width="8%"><b>Test</b></td>
    <td valign="middle" align="right" width="8%"><b>T.Mark</b></td>
    <td valign="middle" align="right" width="10%" style="padding-right:3px"><b>Obtained</b></td>
    </tr>';

                $recordCount = count($teacherGradeArray);
                if($recordCount >0 && is_array($teacherGradeArray) ) {
                   $studentName ="" ; $sN="";
                   $subjectName =""; $subN="";
                   $examType    ="";  $eT="";
                   $testTypeName="";  $tTN="";
                   $j=0;
                   $k=0;
                   for($i=0; $i<$recordCount; $i++ ) {
                            $bg = $bg =='row0' ? 'row1' : 'row0';
                            if($studentName==$teacherGradeArray[$i]['studentName']){
                               $sN="";
                               $j="";$k++; 
                            }
                           else{
                               $j=$i-$k+1;
                               $studentName=$teacherGradeArray[$i]['studentName'];
                               if(trim($teacherGradeArray[$i]['rollNo'])!=""){
                                $sN=$studentName.'<br />['.trim($teacherGradeArray[$i]['rollNo']).']';
                               }
                               else{
                                   $sN=$studentName.'<br />[---]'; 
                               }
                               $subjectName="";
                               $examType="";
                               $testTypeName="";
                             if($i!=0)  //if it is not the first record
                                $gradeStr .='<tr><td colspan="8" ><hr></td></tr>'; //create a <hr> row as new student name found

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
                            <td valign="top" class="padding_top" >'.$j.'</td>
                            <td class="padding_top2" >'.$sN.'</td>
                            <td class="padding_top2">'.$subN.'</td>
                            <td class="padding_top2">'.$eT.'</td>
                            <td class="padding_top2">'.$tTN.'</td>
                            <td class="padding_top2">'.$teacherGradeArray[$i]['testName'].'</td> 
                            <td valign="top" class="padding_top" align="right">'.$teacherGradeArray[$i]['totalMarks'].'</td> 
                            <td valign="top" class="padding_top" align="right">'.$teacherGradeArray[$i]['obtainedMarks'].'</td> 
                            </tr>';
                         }
                      }
                      else {
                       $gradeStr .= '<tr><td colspan="8" align="center">No record found</td></tr>';
                      }
 //echo the result
 echo $gradeStr;               
//$History: scAjaxClassGradeList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>
