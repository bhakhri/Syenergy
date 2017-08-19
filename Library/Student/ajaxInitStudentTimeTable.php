<?php

//The file contains data base functions work on dashboard
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  define('MODULE','StudentDisplayTimeTable');
  define('ACCESS','view');
  UtilityManager::ifStudentNotLoggedIn(true);
  UtilityManager::headerNoCache();
  
  global $sessionHandler;     
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php");      
    $htmlFunctionsManager = HtmlFunctions::getInstance();    
    
   
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');
    
    $classId = $REQUEST_DATA['semesterDetail'];
    if($classId=='') {
      $classId=0;  
    }
    
    $studentId=$sessionHandler->getSessionVariable('StudentId');    
    if($studentId=='') {
      $studentId=0;  
    }
 
    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
    
?>
  <table width="99%" border="0"  align="center" cellspacing="0" cellpadding="0" class="reportTableBorder">
    <tr>
        <td>
            <div id="scroll2" style="overflow:auto;HEIGHT:350px">
<?php       
    if($classId!='0') {
  
        $findTimeTable='';
       
        $results = CommonQueryManager::getInstance()->getTimeTableLabel('');  
        if(isset($results) && is_array($results)) {
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
                                    
                   $conditions  = " AND sg.studentId=".$studentId." AND cl.classId = $classId";
                   $conditions .= " AND tt.timeTableLabelId=".$timeTableLabelId;
                   
                   $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId'];
                   
                   $fieldName="DISTINCT timeTableType, SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-1) AS className";
                   $orderFrom = " ORDER BY timeTableType";
                   $studentRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                   $timeTableType=1;
                   if(count($studentRecordArray)>0) {
                       $timeTableType = $studentRecordArray[0]['timeTableType'];
                       $className = $studentRecordArray[0]['className'];
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
                        $timeTableDateArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                   }  
                   
                   $teacherRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderBy);
                   $recordCount = count($teacherRecordArray);
                   if($recordCount >0 && is_array($teacherRecordArray)) { 
                      $findTimeTable .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportTableBorder">
                       <tr>
                          <td width="100%" class="contenttab_internal_rows1" valign="bottom" align="left"><nobr<b>'.$className.'</b></nobr></td>
                       </tr>
                      </table>';  
                       if($timeTableType==1) {       
                            if($timetableFormat=='1') {
                               $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                               $findTimeTable .= "<br>";
                            }
                            else{ 
                                if($timetableFormat=='2') {
                                    $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                                    $findTimeTable .= "<br>";
                                }                  
                            }
                       }
                       else 
                       if($timeTableType==2) {       
                           $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                           $findTimeTable .= "<br>";
                       }
                    }
                } 
           }
        }
    }
    else  {
        // Period Slot Array    
        $results = CommonQueryManager::getInstance()->getTimeTableLabel('');
        
        // Fetch All Classes    
        $classFetchArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
        $classRecordCount = count($classFetchArray);
        $findTimeTable='';  
        if($classRecordCount >0 && is_array($classFetchArray)) { 
           for($k=0;$k<$classRecordCount;$k++) {
               $classId = $classFetchArray[$k]['classId'];
               $className = $classFetchArray[$k]['periodName']; 
               if(isset($results) && is_array($results)) {
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
                                            
                           $conditions  = " AND sg.studentId=".$studentId." AND cl.classId = $classId";
                           $conditions .= " AND tt.timeTableLabelId=".$timeTableLabelId;
                           
                           $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId'];
                           
                           $fieldName="DISTINCT timeTableType";
                           $orderFrom = " ORDER BY timeTableType";
                           $studentRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
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
                                $timeTableDateArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                           }  
                           
                           $teacherRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderBy);
                           $recordCount = count($teacherRecordArray);
                           if($recordCount >0 && is_array($teacherRecordArray)) { 
                              $findTimeTable .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportTableBorder">
                               <tr>
                                  <td width="100%" class="contenttab_internal_rows1" valign="bottom" align="left"><nobr<b>'.$className.'</b></nobr></td>
                               </tr>
                              </table>';    
                               if($timeTableType==1) {       
                                    if($timetableFormat=='1') {
                                       $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                                       $findTimeTable .= "<br>";
                                    }
                                    else{ 
                                        if($timetableFormat=='2') {
                                            $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                                            $findTimeTable .= "<br>";
                                        }                  
                                    }
                               }
                               else 
                               if($timeTableType==2) {       
                                   $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                                   $findTimeTable .= "<br>";
                               }
                           }
                        }  // Period Slot Wise  --  End --
                  }
              } // Period 
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
?>
<?php 

//$History: ajaxInitStudentTimeTable.php $
//
//*****************  Version 13  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Library/Student
//validation & condition format updated 
//
//*****************  Version 12  *****************
//User: Parveen      Date: 4/21/10    Time: 5:47p
//Updated in $/LeapCC/Library/Student
//access right added
//
//*****************  Version 11  *****************
//User: Parveen      Date: 4/21/10    Time: 5:38p
//Updated in $/LeapCC/Library/Student
//report format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 4/21/10    Time: 12:02p
//Updated in $/LeapCC/Library/Student
//optional subject report Format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 4/21/10    Time: 11:25a
//Updated in $/LeapCC/Library/Student
//validation format updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 4/20/10    Time: 2:37p
//Updated in $/LeapCC/Library/Student
//daily and weekly base report format updated
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/18/09   Time: 6:58p
//Updated in $/LeapCC/Library/Student
//student uploading and change in time table to show group short
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/31/09    Time: 10:27a
//Updated in $/LeapCC/Library/Student
//modified in message for hostel room type
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/06/09    Time: 2:13p
//Updated in $/LeapCC/Library/Student
//Show time table as per column wise or row wise in interface & print
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:27p
//Created in $/LeapCC/Library/Student
//new files for cc
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/20/08   Time: 12:11p
//Updated in $/Leap/Source/Library/ScStudent
//modified for quiz
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/15/08   Time: 12:14p
//Updated in $/Leap/Source/Library/ScStudent
//modified code for print & csv
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/14/08   Time: 6:30p
//Created in $/Leap/Source/Library/ScStudent
//new file for student time table semester wise detail
//

?>