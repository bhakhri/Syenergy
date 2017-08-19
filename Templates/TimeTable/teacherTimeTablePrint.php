<?php 
//This file is used as printing version for teacher timetable.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php"); 
    $htmlFunctionsManager = HtmlFunctions::getInstance();    
    
    
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();


    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    $timeTableLabelId = trim($REQUEST_DATA['labelId']);
    $employeeId = trim($REQUEST_DATA['teacherId']);
    $timeTableType = trim($REQUEST_DATA['timeTableType']);
    
    // This value is being fetched from Display Teacher Load 
    $timeTableLoad = trim($REQUEST_DATA['load']);
    if($timeTableLoad=='') {
      $timeTableLoad=0;  
    }
    
    if($timeTableLabelId=='') {
      $timeTableLabelId=0;  
    }
    
    if($employeeId=='') {
      $employeeId=0;  
    }
    
    if($timeTableType=='') {
      $timeTableType=0;  
    }
    
    $condDate ='';
    if($timeTableType==2 && $timeTableLoad=='0') {
       $fromDate = $REQUEST_DATA['fromDate'];
       $toDate = $REQUEST_DATA['toDate'];
       $condDate = " AND ttl.timeTableType=$timeTableType  AND (tt.fromDate BETWEEN '$fromDate' AND '$toDate') "; 
    }
    
 
  
    global $sessionHandler;
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');

    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";  


    //Get the time table date according to class selected
    $conditions  = " AND ttl.timeTableType=$timeTableType AND tt.employeeId=".$employeeId." AND tt.timeTableLabelId=".$timeTableLabelId;  
                      

    // Search Start 
    $formattedDate = date('d-M-y'); //UtilityManager::formatDate($tillDate);    
    $foundArray = $studentManager->getSingleField('time_table_labels', 'labelName', "WHERE timeTableLabelId  = ".$timeTableLabelId);
    $labelName = $foundArray[0]['labelName'];
    
    $foundArray = $studentManager->getSingleField('employee', "DISTINCT employeeId, CONCAT(employeeName,' (',employeeCode,')') AS employeeName", "WHERE employeeId  = ".$employeeId);
    $className = $foundArray[0]['employeeName'];
    $search = $labelName."<br>".$className;
    if($timeTableType==2 && $timeTableLoad=='0') {
      $search .= "<br>From&nbsp;".UtilityManager::formatDate($fromDate)."&nbsp;To&nbsp;".UtilityManager::formatDate($toDate);      
    }
    else {
       $search .= "<br>As On $formattedDate ";                  
    }
    
    $reportManager->setReportInformation($search);
    $reportManager->setReportHeading("Teacher Time Table Report");
    
    // Search End 
    
    // Fetch Period Arrays
    $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
    $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
    $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
     
    $findTimeTable=''; 
    
    //Get the time table date according to class selected
    for($ps=0; $ps < count($periodSlotArr); $ps++) {
        $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
        
        $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
        $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
        $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);  
        
        $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId']." ".$condDate;;
        
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
              if($timeTableType==1) {
                 if($timetableFormat=='1') {
                   $value = $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                   reportGenerate($value,$search);     
                 }
                 else 
                 if($timetableFormat=='2') {
                    $value = $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                    reportGenerate($value,$search);     
                 }
           }
           else 
           if($timeTableType==2) {  
              $value= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
              reportGenerate($value,$search);     
           }
           $findTimeTable='1';
        }
     }
     
   if($findTimeTable=='') {
     $value="<div class='dataFont' align='center'><b>".NO_DATA_FOUND."</b></div>";    
     reportGenerate($value,$search);
   }
     
 //Report generate
   function reportGenerate($value,$heading) {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('Teacher Time Table Report');
        $reportManager->setReportInformation($heading);      
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="95%" align="center">
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
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>  align="center"><?php echo $reportManager->getReportInformation(); ?></th></tr>
            </table> <br>
            <table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
            <tr>
            <td valign="top">
            <?php echo $value; ?>        
            </td>
            </tr> 
            </table>       
            <br>
            <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <br class='page'>
        </div>
<?php        
   }
?> 
		 
<?php 
// $History: teacherTimeTablePrint.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Templates/TimeTable
//validation & condition format updated 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/17/09   Time: 2:12p
//Updated in $/LeapCC/Templates/TimeTable
//periodslotwise report function updated
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/30/09    Time: 7:08p
//Updated in $/LeapCC/Templates/TimeTable
//Updated print report heading with correct label
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/05/09    Time: 11:22a
//Updated in $/LeapCC/Templates/TimeTable
//Changed Time table format so that admin can decide the display of time
//table i.e in periods in rows or periods in column
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 4/03/09    Time: 12:31p
//Updated in $/LeapCC/Templates/TimeTable
//Updated time table with groupShort field
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TimeTable
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/21/08    Time: 5:43p
//Created in $/Leap/Source/Templates/TimeTable
//intial checkin
?>