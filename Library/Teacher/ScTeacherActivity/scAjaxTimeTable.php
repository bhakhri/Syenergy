<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Diapnajn BHattacharjee
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------   
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}

require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
$teacherManager = ScTeacherManager::getInstance();

//Get the time table date according to [ (subject+section)/+class ] selected
if($REQUEST_DATA['classId']!=""){
    $conditions=" AND tt.subjectId=".$REQUEST_DATA['subjectId']." AND tt.sectionId=".$REQUEST_DATA['sectionId']." AND cl.classId=".$REQUEST_DATA['classId'];
}
elseif($REQUEST_DATA['subjectId']!="" && $REQUEST_DATA['sectionId']!=""){
    $conditions=" AND tt.subjectId=".$REQUEST_DATA['subjectId']." AND tt.sectionId=".$REQUEST_DATA['sectionId'];
}
else{
    $conditions="";
}

//$conditions=($REQUEST_DATA['classId']!=0 ? " AND cl.classId=".$REQUEST_DATA['classId'] : "");
$teacherRecordArray = $teacherManager->getTeacherTimeTable($conditions);

$timeTableStr="";
//build the string
$timeTableStr='<table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid" style="border:2px solid #C7C7C7">';
                $recordCount = count($teacherRecordArray);
                if($recordCount >0 && is_array($teacherRecordArray) ) { 
                 $timeTableStr .= '<tr class="rowheading">
                        <td width="5%" valign="middle" align="center" ><b>Period</b>
                        <td valign="middle" align="center" width="10%"><b>Monday</b></td>
                        <td valign="middle" align="center" width="10%" ><b>Tuesday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Wednesday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Thursday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Friday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Saturday</b></td>
                        <td valign="middle" align="center" width="10%"><b>Sunday</b></td>
                      </tr>';
                    $pno="";
                    $preMatch=1;   //check previous matched date
                    $fl=0;         //check whether createBlankTd() is called first time or not
                    $el=0;         //check number of times control goes to else part        
                    for($i=0; $i<$recordCount; $i++ ) {
                      for ($j=1; $j<8 ;$j++) {
                         if($pno!=strip_slashes($teacherRecordArray[$i]['periodNumber']) and $pno==""){   
                              $preMatch=1;
                              $fl=0;
                              $bg = $bg =='trow0' ? 'trow1' : 'trow0';      
                              $timeTableStr .= '<tr class='.$bg.'>';        
                              $timeTableStr .= '<td align="center" class="timtd"><b>'.strip_slashes($teacherRecordArray[$i]['periodNumber']).'</b><br />'.strip_slashes($teacherRecordArray[$i]['pTime']).'</td>';
                              $pno=strip_slashes($teacherRecordArray[$i]['periodNumber']);   
                          }
                         elseif($pno!=strip_slashes($teacherRecordArray[$i]['periodNumber']) and $pno!=""){
                             $bg = $bg =='trow0' ? 'trow1' : 'trow0';
                             $timeTableStr .=  createBlankTD(7-$preMatch);  
                             $preMatch=1;   
                             $fl=0;
                             $el=0;
                             $timeTableStr .= '</tr><tr><td height="3px" colspan="8"></td></tr><tr class='.$bg.'>';        
                             $timeTableStr .=  '<td align="center" class="timtd"><b>'.strip_slashes($teacherRecordArray[$i]['periodNumber']).'</b><br />'.strip_slashes($teacherRecordArray[$i]['pTime']).'</td>';
                             $pno=strip_slashes($teacherRecordArray[$i]['periodNumber']);    
                         }
                        if (trim($teacherRecordArray[$i]['daysOfWeek'])==$j){
                            if($fl==0){
                                $timeTableStr .= createBlankTD($el);
                                $fl=1;
                             }
                            else{
                               $timeTableStr .= createBlankTD($j-$preMatch-1); 
                            } 
                            $timeTableStr .='<td valign="top"  align="center" class="timtd">'.strip_slashes($teacherRecordArray[$i]['subjectAbbreviation']).'<br>'.strip_slashes($teacherRecordArray[$i]['className']).'<br>'.strip_slashes($teacherRecordArray[$i]['roomAbbreviation']).'</td>';
                            $preMatch=$j;
                            $el=0;
                         } 
                       else{
                          $el++; 
                       }  
                     }
                   }
                  if($pno==strip_slashes($teacherRecordArray[$i-1]['periodNumber']) and $pno!=""){
                    $timeTableStr .= createBlankTD(7-$preMatch);  
                  }           
                }
               else{
                   $timeTableStr .= '<tr><td colspan="8" align="center">No record found</td></tr>';
                }
                         
               $timeTableStr .= '</table>';
 //echo the result
 echo $timeTableStr;               
//$History: scAjaxTimeTable.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/07/08    Time: 2:10p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/02/08    Time: 1:03p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/01/08    Time: 11:46a
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>
