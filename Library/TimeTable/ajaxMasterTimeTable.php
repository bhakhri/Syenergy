<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Rajeev Aggarwal
// Created on : 05-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();

//------------------------------------------------------------------------------------------------
// This Function  creates blank TDs
//
// Author :Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------   
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}

require_once(MODEL_PATH . "/TimeTableManager.inc.php");
$timeTableManager = TimeTableManager::getInstance();
//Get the time table date according to class selected
$conditions=($REQUEST_DATA['studentGroupId']!=0 ? " AND tt.groupId=".$REQUEST_DATA['studentGroupId'] : "");
$teacherRecordArray = $timeTableManager->getTeacherTimeTable($conditions);

 

$timeTableStr="";
//build the string
$timeTableStr='<table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid" >';
                $recordCount = count($teacherRecordArray);
                if($recordCount >0 && is_array($teacherRecordArray) ) { 
                 $timeTableStr .= '<tr class="rowheading">
                        <td width="5%" valign="middle" align="center" style="font-size:12px"><b>Period</b>
                        <td valign="middle" align="center" width="10%" style="font-size:12px"><b>Monday</b></td>
                        <td valign="middle" align="center" width="10%" style="font-size:12px"><b>Tuesday</b></td>
                        <td valign="middle" align="center" width="10%" style="font-size:12px"><b>Wednesday</b></td>
                        <td valign="middle" align="center" width="10%" style="font-size:12px"><b>Thursday</b></td>
                        <td valign="middle" align="center" width="10%" style="font-size:12px"><b>Friday</b></td>
                        <td valign="middle" align="center" width="10%" style="font-size:12px"><b>Saturday</b></td>
                        <td valign="middle" align="center" width="10%" style="font-size:12px"><b>Sunday</b></td>
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
				  $timeTableStr .= '<tr><td height="20"></td></tr><tr><td colspan="8" align="right"><div id = "saveDiv"><a href="#" onClick="printReport();"><img src='.IMG_HTTP_PATH.'/print.gif border="0"></a>&nbsp;</div></td></tr>';
                }
               else{
                   $timeTableStr .= '<tr><td colspan="8" align="center"  style="font-size:11px">No record found</td></tr>';
                }
                         
               $timeTableStr .= '</table>';
 //echo the result
 echo $timeTableStr;               
//$History: ajaxMasterTimeTable.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTable
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/04/08    Time: 5:55p
//Created in $/Leap/Source/Library/TimeTable
//intial checkin
 
?>
