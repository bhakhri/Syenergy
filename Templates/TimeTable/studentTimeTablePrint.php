<?php 
//This file is used as printing version for payment history.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DisplayStudentTimeTable');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php"); 
    $htmlFunctionsManager = HtmlFunctions::getInstance();  

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();


    global $sessionHandler;
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');

    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
     
    $rollNo = $REQUEST_DATA['rollNo'];
    $timeTableType = $REQUEST_DATA['timeTableType'];
       
    if($rollNo=='') {
      $rollNo=-1;  
    }
    
    if($timeTableType=='') {
      $timeTableType=0;  
    }
    
    $condDate='';
    if($timeTableType==2) {
        $fromDate = $REQUEST_DATA['fromDate'];
        $toDate = $REQUEST_DATA['toDate'];
        $condDate = " AND ttl.timeTableType=$timeTableType  AND (tt.fromDate BETWEEN '$fromDate' AND '$toDate') "; 
    }
    
  
    $condition = " AND s.rollNo = '$rollNo' ";
    //$conditions='', $order='ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber, daysOfWeek',$startDate='',$endDate='',$fieldName=''
  
    $fieldName = "DISTINCT 
                        sg.studentId, sg.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        cl.className,
                        IF(IFNULL(s.rollNo,'')='','".NOT_APPLICABLE_STRING."',s.rollNo) AS rollNo,
                        IF(IFNULL(s.universityRollNo,'')='','".NOT_APPLICABLE_STRING."',s.universityRollNo) AS universityRollNo,
                        IF(IFNULL(s.fatherName,'')='','".NOT_APPLICABLE_STRING."',s.fatherName) AS fatherName,
                        IF(IFNULL(s.motherName,'')='','".NOT_APPLICABLE_STRING."',s.motherName) AS motherName,
                        IF(IFNULL(s.guardianName,'')='','".NOT_APPLICABLE_STRING."',s.guardianName) AS guardianName";
    $orderFrom = " ORDER BY studentId, classId DESC";                      
    $studentRecordArray = $timeTableManager->getStudentShowTimeTable($condition,$orderFrom,'','',$fieldName);
  
    $classId=0;
    $studentId=0;
    if(count($studentRecordArray)>0) {
        $studentId =$studentRecordArray[0]['studentId'];
        $classId =$studentRecordArray[0]['classId'];
        $studentName = "Class: ".$studentRecordArray[0]['className']."<br>
                        Name: ".$studentRecordArray[0]['studentName']."<br>
                        Roll No.: ".$studentRecordArray[0]['rollNo']."<br>
                        Univ. Roll No.: ".$studentRecordArray[0]['universityRollNo'];
    }
    
    $search = $studentName;
   
   if($timeTableType==2) {
      $search .= "<br>From&nbsp;".UtilityManager::formatDate($fromDate)."&nbsp;To&nbsp;".UtilityManager::formatDate($toDate);      
   }
   else {
      $formattedDate = date('d-M-y'); //UtilityManager::formatDate($tillDate);          
      $search .= "<br>As On $formattedDate ";                  
   }  
    
    
  $chkTimeTable='';    
  $findTimeTable = '';
  $results = CommonQueryManager::getInstance()->getTimeTableLabel('');  
  if(isset($results) && is_array($results)) {
       for($i=0; $i<count($results); $i++) {
            //Get the time table date according to class selected
            $labelName = $results[$i]['labelName']; 
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
               
               $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId']." ".$condDate; 
               
               /*
                  $fieldName="DISTINCT timeTableType, className AS className";
                  $orderFrom = " ORDER BY timeTableType";
                  $studentRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                  $timeTableType=1;
                  if(count($studentRecordArray)>0) {
                    $timeTableType = $studentRecordArray[0]['timeTableType'];
                    $className = $studentRecordArray[0]['className'];
                  } 
               */
                
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
                    if($timeTableType==1) {       
                        if($timetableFormat=='1') {
                            $value = $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                            reportGenerate($value,$search);      
                        }
                        else if($timetableFormat=='2') {
                            $value = $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                            reportGenerate($value,$search);      
                        }
                    }
                    else 
                    if($timeTableType==2) {       
                        $value = $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                        reportGenerate($value,$search);      
                    }
                    $chkTimeTable='1';
               }
          } 
      }
  }
  
  if($chkTimeTable=='') {
     $value="<div class='dataFont' align='center'><b>".NO_DATA_FOUND."</b></div>";    
     reportGenerate($value,$search);
   }
     
 //Report generate
   function reportGenerate($value,$heading) {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('Student Time Table Report');
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
//$History: studentTimeTablePrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Templates/TimeTable
//validation & condition format updated 
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/30/09    Time: 7:08p
//Updated in $/LeapCC/Templates/TimeTable
//Updated print report heading with correct label
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/05/09    Time: 11:22a
//Updated in $/LeapCC/Templates/TimeTable
//Changed Time table format so that admin can decide the display of time
//table i.e in periods in rows or periods in column
?>