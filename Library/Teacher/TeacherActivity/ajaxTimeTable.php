<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Diapnajn BHattacharjee
// Created on : 22-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherTimeTableDisplay');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;     
    
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php");      
    $htmlFunctionsManager = HtmlFunctions::getInstance();    
    
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');
    $timeTableLabel = $REQUEST_DATA['timeTableLabel'];
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');    
 
    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
    
?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
    <tr>
        <td>
            <div id="scroll2" style="overflow:auto;HEIGHT:350px">
<?php       
    if($REQUEST_DATA['timeTableLabelId']!='0'  ) {
  
        $findTimeTable='';
        
        // Fetch Period Arrays
        $periodCondition = " tt.timeTableLabelId = ".$REQUEST_DATA['timeTableLabelId'];
        $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
        $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
        
        //Get the time table date according to class selected
        for($ps=0; $ps < count($periodSlotArr); $ps++) {
            $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
            
            $periodCondition = " tt.timeTableLabelId = ".$REQUEST_DATA['timeTableLabelId']." AND p.periodSlotId = ".$periodSlotId;
            $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
            $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);  
          
            $conditions=($employeeId!='' ? " AND tt.employeeId=".$employeeId : "");
            $conditions .=($REQUEST_DATA['timeTableLabelId']!='' ? " AND tt.timeTableLabelId=".$REQUEST_DATA['timeTableLabelId'] : "");
            $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId'];
            
            
            $fieldName="DISTINCT timeTableType";
            $orderFrom = " ORDER BY timeTableType";
            $studentRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
            
        
            $timeTableType=1;
            if(count($studentRecordArray)>0) {
               $timeTableType = $studentRecordArray[0]['timeTableType'];
            }
            
            if($timeTableType==1) {
               $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
            }
            else 
            if($timeTableType==2) {
               $orderBy = " ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber";
            }             
            
            if($timeTableType==2) {
                // Date Format 
                $fieldName = " DISTINCT tt.fromDate";
                $orderFrom = " ORDER BY fromDate";
                $timeTableDateArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
            }  
            
            $teacherRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderBy);
            $recordCount = count($teacherRecordArray);
            if($recordCount >0 && is_array($teacherRecordArray)) { 
                $findTimeTable .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                              <tr>
                                   <td width="92%" class="contenttab_internal_rows1" valign="bottom" aliign="left"><nobr>'.$timeTableLabel.'</nobr></td>
                              </tr>
                              </table>'; 
                if($timeTableType==1) {       
                    if($timetableFormat=='1') {
                       $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,1);
                       $findTimeTable .= "<br>";
                    }
                    else{ 
                        if($timetableFormat=='2') {
                            $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray,1);
                            $findTimeTable .= "<br>";
                        }                  
                    }
                }
                else 
                if($timeTableType==2) {       
                   $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,1,$timeTableType,$timeTableDateArray);
                   $findTimeTable .= "<br>";
                }
            }
        } 
    }
    else  {
       $results = CommonQueryManager::getInstance()->getTimeTableLabel('');
       if(isset($results) && is_array($results)) {
           $findTimeTable='';   
           for($i=0; $i<count($results); $i++) {
                 //Get the time table date according to class selected
                 $timeTableLabelId = $results[$i]['timeTableLabelId'];  
                 if($timeTableLabelId=='') {
                    $timeTableLabelId=0; 
                 }
                 
                // Fetch Period Arrays
                $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
                $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
     
                //Get the time table date according to time table slot wise
                for($ps=0; $ps < count($periodSlotArr); $ps++) {          // Period Slot Wise  --  Start --
                   $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
                
                   $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
                   $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                   $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);  
                                    
              
                   $conditions=($employeeId!='' ? " AND tt.employeeId=".$employeeId : "");
                   $conditions .=($timeTableLabelId!='' ? " AND tt.timeTableLabelId=".$timeTableLabelId : "");
                  
                   $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId'];
                   
                   $fieldName="DISTINCT timeTableType";
                   $orderFrom = " ORDER BY timeTableType";
                   $studentRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
                   $timeTableType=1;
                   if(count($studentRecordArray)>0) {
                       $timeTableType = $studentRecordArray[0]['timeTableType'];
                   }
                    
                   if($timeTableType==1) {
                       $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
                   }
                   else 
                   if($timeTableType==2) {
                      $orderBy = " ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber";   
                   }             
                    
                   if($timeTableType==2) {
                        // Date Format 
                        $fieldName = " DISTINCT tt.fromDate";
                        $orderFrom = " ORDER BY fromDate";
                        $timeTableDateArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
                   }  
                   
                   $teacherRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderBy);
                   $recordCount = count($teacherRecordArray);
                   if($recordCount >0 && is_array($teacherRecordArray)) { 
                      $findTimeTable .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" id="anyid">
                       <tr>
                       
                        <td width="92%" class="contenttab_internal_rows1" valign="bottom" align="left"><nobr>'.$results[$i]['labelName'].'</nobr></td>
                       </tr>
                      </table>';    
                       if($timeTableType==1) {       
                            if($timetableFormat=='1') {
                               $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,1);
                               $findTimeTable .= "<br>";
                            }
                            else{ 
                                if($timetableFormat=='2') {
                                    $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray,1);
                                    $findTimeTable .= "<br>";
                                }                  
                            }
                       }
                       else 
                       if($timeTableType==2) {       
                           $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,1,$timeTableType,$timeTableDateArray);
                           $findTimeTable .= "<br>";
                       }
                   }
                }  // Period Slot Wise  --  End --
          }
      }
   }   
   if($findTimeTable=='') {
     echo "<div align='center'>No record found</div></td></tr></table></div>";
   }
   else {
       $findTimeTable .= '</td></tr></table></div><div id = "saveDiv" align="right" style="padding-top:15px" valign="bottom" >
                           <input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/print.gif" onClick="printReport()"/>&nbsp;
                           </div>';
   }
   echo $findTimeTable ;                
              
//$History: ajaxTimeTable.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/20/10    Time: 2:37p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//daily and weekly base report format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/17/09   Time: 2:12p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//periodslotwise report function updated
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 3  *****************
//User: Administrator Date: 3/06/09    Time: 17:22
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done these modifications :
//
//1. My Time Table in Teacher: Add a link in the cell of Period/Day in My
//Time Table of teacher module, that takes the teacher to Daily
//Attendance interface and sets the value in Class, Subject,  and group
//DDMs from the time table. however, teacher will need to select Date and
//Period manually.
//
//2. Student Info in Teacher: Please add just "And/Or" between Name and
//Roll No search text boxes.
//
//3. Department wise Employee Selection in send messages links in teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/05/09    Time: 10:35
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified files to show new time time format in teacher login
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/22/08    Time: 2:53p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
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
