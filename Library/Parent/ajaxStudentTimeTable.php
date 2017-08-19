<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Parveen Sharma
// Created on : 07-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  define('MODULE','COMMON');
  define('ACCESS','view');
  UtilityManager::ifParentNotLoggedIn(true); 
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
    
    $studentId=$sessionHandler->getSessionVariable('StudentId');    
    if($studentId=='') {
      $studentId=0;  
    }
    
    $condStudent = " AND s.studentId = '$studentId' ";
    $fieldName = "DISTINCT 
                        sg.studentId, sg.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        className,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                        IF(IFNULL(s.motherName,'')='','".NOT_APPLICABLE_STRING."',s.motherName) AS motherName,
                        IF(IFNULL(s.guardianName,'')='','".NOT_APPLICABLE_STRING."',s.guardianName) AS guardianName";
    $orderFrom = " ORDER BY studentId";                      
    $studentRecordArray = $timeTableManager->getStudentShowTimeTable($condStudent,$orderFrom,'','',$fieldName);
    if(count($studentRecordArray)>0) {
       $studentName  =$studentRecordArray[0]['studentName'];
       $rollNo  =$studentRecordArray[0]['rollNo']; 
    }
    
    
    
    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber " : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek ";
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
               $className = $classFetchArray[$k]['className1']; 
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
                           <input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/print.gif" onClick="printTimeTableReport()"/>&nbsp;
                           </div>';
   }
   echo $findTimeTable ;                
?>

<?php
//$History: ajaxStudentTimeTable.php $
//
//*****************  Version 13  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Library/Parent
//validation & condition format updated 
//
//*****************  Version 12  *****************
//User: Parveen      Date: 4/21/10    Time: 12:17p
//Updated in $/LeapCC/Library/Parent
//optional subject base query format updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 4/20/10    Time: 2:37p
//Updated in $/LeapCC/Library/Parent
//daily and weekly base report format updated
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//added access rights
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 10/14/09   Time: 5:53p
//Updated in $/LeapCC/Library/Parent
//updated access rights
//
//*****************  Version 8  *****************
//User: Parveen      Date: 9/23/09    Time: 1:39p
//Updated in $/LeapCC/Library/Parent
//conditions format updated  (timetable labelId check)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 9/04/09    Time: 5:37p
//Updated in $/LeapCC/Library/Parent
//showTimeTablePeriodsColumnsCSV, showTimeTablePeriodsRowsCSV function
//base code update
//

?>