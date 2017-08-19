<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Rajeev Aggarwal
// Created on : 07-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  
  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  define('MODULE','COMMON');
  define('ACCESS','view');
  UtilityManager::ifNotLoggedIn(true);
  UtilityManager::headerNoCache();
  
  global $sessionHandler;     
  

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php");      
    $htmlFunctionsManager = HtmlFunctions::getInstance();    
    
   
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');
    
    $classId = $REQUEST_DATA['classId'];
    if($classId=='') {
      $classId=0;  
    }
    
    $studentId=$REQUEST_DATA['studentId'];       
    if($studentId=='') {
      $studentId=0;  
    }                          
 
    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
    
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
                   
                   $fieldName="DISTINCT timeTableType, className";
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
   echo $findTimeTable ;    

//$History: ajaxStudentTimeTable.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Library/Student
//validation & condition format updated 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 4/21/10    Time: 6:22p
//Updated in $/LeapCC/Library/Student
//daily and weekly base format updated
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/14/09    Time: 10:31a
//Updated in $/LeapCC/Library/Student
//removed bug when time table label is not found
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 6/01/09    Time: 7:37p
//Updated in $/LeapCC/Library/Student
//Fixed issues of find student of formatting
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/18/09    Time: 9:53a
//Updated in $/LeapCC/Library/Student
//Updated Time table format as per the parameter set from Config Paramter
//"TIMETABLE_FORMAT"
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/07/09    Time: 12:39p
//Updated in $/LeapCC/Library/Student
//Updated formatting
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Library/Student
//updated functionality as per CC
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:20a
//Created in $/LeapCC/Library/Student
//Intial Checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 11/20/08   Time: 3:25p
//Updated in $/Leap/Source/Library/ScStudent
//added QUIZ functionality in time
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 11/13/08   Time: 11:47a
//Created in $/Leap/Source/Library/ScStudent
//intial checkin
?>