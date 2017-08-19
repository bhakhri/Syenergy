<?php 
//This file is used as printing version for teacher timetable.
//
// Author :Rajeev Aggarwal
// Created on : 17-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");

    require_once($FE . "/Library/HtmlFunctions.inc.php"); 
    require_once(BL_PATH . '/ReportManager.inc.php');   
    
    global $sessionHandler;    
    
    $timeTableManager = TimeTableManager::getInstance();
    $htmlFunctionsManager = HtmlFunctions::getInstance();  
    $reportManager = ReportManager::getInstance();
    
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId');    
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
     

    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');

    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";     

    $findTimeTable='';   
      
    
    if($REQUEST_DATA['timeTableLabelId']=='0'  ) {
        //Get the time table date according to class selected
       $results = CommonQueryManager::getInstance()->getTimeTableLabel('');
       if(isset($results) && is_array($results)) {
           //Get the time table date according to class selected           
           for($i=0; $i<count($results); $i++) {
               $timeTableLabelId = $results[$i]['timeTableLabelId'];    
                if($timeTableLabelId=='') {
                    $timeTableLabelId=0; 
                } 
                
               // Fetch Period Arrays
               $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
               $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
               $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
     
               //Get the time table date according to class selected
               for($ps=0; $ps < count($periodSlotArr); $ps++) {
                  $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
            
                  $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
                  $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                  $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);  
               
                  $timeTableLabel = $results[$i]['labelName'];
                  $conditions =($employeeId!='' ? " AND tt.employeeId=".$employeeId : "");
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
                       $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,	OfWeek";
                  }
                  else 
                  if($timeTableType==2) {
                       $orderBy = " ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber ";
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
                    $employeeName = $teacherRecordArray[0]['employeeName'];
                    $reportManager->setReportInformation("For ".$employeeName." As On $formattedDate ");
                    $reportManager->setReportHeading("My Time Table Print");
?>
    <table border='0' cellspacing='0' width="90%" align="center">
    <tr>
        <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
        <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
        <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                </tr>
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
    <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
    </table> <br>
     
    <table border='0' cellspacing='0' width="90%" align="center">
    <tr>
        <td>
            <?php
                            echo "<b ".$reportManager->getReportHeadingStyle().">SearchBy :</b><span class='contenttab_internal_rows1'>".$timeTableLabel."</span><br>";
                            //echo "<b ".$reportManager->getReportHeadingStyle().">".$timeTableLabel."</b><br>";
                            if($timeTableType==1) {       
                                if($timetableFormat=='1') {
                                   echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                                   echo "<br>";
                                }
                               else 
                               if($timetableFormat=='2') {
                                 echo  $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                                 echo "<br>";
                               }
                            }
                            else
                            if($timeTableType==2) {       
                               echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                               echo "<br>";
                            }
                        echo '</td>
                                        </tr>
                                            </table>
                                                <br> <br class="page" />';
?>                                                
                    <table border='0' cellspacing='0' width="90%" align="center">
                        <tr>
                            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
                        </tr>
                    </table>
                <?php    
              }
            }
         }
       }
    }
    else
    {
         $timeTableLabel = $REQUEST_DATA['timeTableLabel'];
         $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
              
         $conditions=($employeeId!='' ? " AND tt.employeeId=".$employeeId : "");
         $conditions .=($timeTableLabelId!='' ? " AND tt.timeTableLabelId=".$timeTableLabelId : "");
                
        // Fetch Period Arrays
        $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
        $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
        $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
     
        //Get the time table date according to class selected
        for($ps=0; $ps < count($periodSlotArr); $ps++) {
            $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
            
            $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
            $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
            $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList); 
              
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
                $orderBy =" ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber" ;
            }             
            
            if($timeTableType==2) {
                // Date Format 
                $fieldName = " DISTINCT tt.fromDate";
                $orderFrom = " ORDER BY fromDate";
                $timeTableDateArray = $timeTableManager->getTeacherTimeTable($cond1,$orderFrom,'','',$fieldName);
            } 
            
              
             $employeeName = $teacherRecordArray[0]['employeeName'];
             
             $teacherRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderBy);    
             $recordCount = count($teacherRecordArray);
             if($recordCount >0 && is_array($teacherRecordArray)) { 
                $reportManager->setReportInformation("For ".$employeeName." As On $formattedDate ");
                $reportManager->setReportHeading("My Time Table Print");
?>
    <table border='0' cellspacing='0' width="90%" align="center">
    <tr>
        <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
        <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
        <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                </tr>
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
    <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
    </table> <br>
     
    <table border='0' cellspacing='0' width="90%" align="center">
    <tr>
        <td>
            <?php
                     
                            //echo "<b ".$reportManager->getReportHeadingStyle().">SearchBy :</b><span class='contenttab_internal_rows1'>".$timeTableLabel."</span><br>";
                            if($timeTableType==1) {       
                                if($timetableFormat=='1') {
                                   echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                                   echo "<br>";
                                }
                               else 
                               if($timetableFormat=='2') {
                                 echo  $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                                 echo "<br>";
                               }
                            }
                            else
                            if($timeTableType==2) {       
                               echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                               echo "<br>";
                            }
                        echo '     </td>
                                        </tr>
                                            </table>
                                                <br> <br class="page" />';
            ?>
    <table border='0' cellspacing='0' width="90%" align="center">
        <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
        </tr>
    </table>
<?php    
       }
    }
  }
// $History: teacherTimeTableReportPrint.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/20/10    Time: 2:37p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//daily and weekly base report format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/17/09   Time: 2:12p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//periodslotwise report function updated
//
//*****************  Version 4  *****************
//User: Administrator Date: 29/05/09   Time: 11:43
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing------ Issues [28-May-09]Build# cc0007
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/05/09    Time: 10:35
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified files to show new time time format in teacher login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 3/04/09    Time: 17:07
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Displays group in time table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 13:04
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//Added the functionality of time table print in teacher section
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/03/09    Time: 12:36
//Created in $/SnS/Templates/Teacher/TeacherActivity
//Added TimeTable Print Functionality in Teacher Section
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 12/05/08   Time: 12:12p
//Updated in $/Leap/Source/Templates/ScTimeTable
//showed QUIZ in time table
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 11/17/08   Time: 1:23p
//Updated in $/Leap/Source/Templates/ScTimeTable
//added time table label functionality to see time table for other labels
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/03/08   Time: 5:34p
//Updated in $/Leap/Source/Templates/ScTimeTable
//updated the formatting
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/03/08   Time: 3:36p
//Updated in $/Leap/Source/Templates/ScTimeTable
//updated the time table format for teacher
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/17/08    Time: 3:50p
//Created in $/Leap/Source/Templates/ScTimeTable
//intial checkin
?>