<?php 
//This file is used as printing version for class timetable.
//
// Author :Rajeev Aggarwal
// Created on : 05-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();

	//Get the time table date according to class selected
	$conditions=($REQUEST_DATA['groupId']!=0 ? " AND tt.groupId=".$REQUEST_DATA['groupId'] : "");
	

	$groupName = $REQUEST_DATA['groupName'];
	$className = $REQUEST_DATA['className'];
    $teacherName = $REQUEST_DATA['teacherName'];
 

    global $sessionHandler;
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');
    
    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : 
                                        " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";  

   
    // Fetch Period Arrays
    $periodCondition=($REQUEST_DATA['groupId']!=0 ? " tt.groupId=".$REQUEST_DATA['groupId'] : "");   
    $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
    $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
     
    $findTimeTable=''; 

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $reportManager->setReportInformation("For ".$teacherName." As On $formattedDate ");
    $reportManager->setReportHeading("Teacher Time Table Report");      

    //Get the time table date according to class selected
    for($ps=0; $ps < count($periodSlotArr); $ps++) {
        $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
        
        $periodCondition  = ($REQUEST_DATA['groupId']!=0 ? " tt.groupId=".$REQUEST_DATA['groupId'] : "");
        $periodCondition .= " AND p.periodSlotId = ".$periodSlotId;
        $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
        $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);  
        
        $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId'];
        $teacherRecordArray = $timeTableManager->getTeacherTimeTable($cond1,$orderBy);

?>
    <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
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
    <table border='0' cellspacing='0' width="90%"  align="center">
    <tr>
        <td valign="top">
        <?php
             
             $recordCount = count($teacherRecordArray);
             if($recordCount >0 && is_array($teacherRecordArray)) {     
                if($timetableFormat=='1') {
                   echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                  echo "<br>";
               }
               else { 
                    if($timetableFormat=='2') {
                        echo  $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                        echo "<br>";
                    }
               }
               $findTimeTable='1';
            }
         ?>
                 </td>
                </tr>
           </table>
             <br>
           <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
           <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
           </tr>
           </table>
         <?php
     }
     
if($findTimeTable=='') {
?>
    <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
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
    <table border='0' cellspacing='0' width="90%"  align="center">
    <tr>
        <td valign="top">
<?php         
        echo "<table align='center'><tr><td class='contenttab_internal_rows'>No Data Found</td></tr></table>";
?>
        </td>
                </tr>
           </table>
             <br>
           <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
           <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
           </tr>
           </table>         
<?php         
     }
?>        

<?php 
// $History: classTimeTablePrint.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/22/10    Time: 11:51a
//Updated in $/LeapCC/Templates/TimeTable
//validation & condition format updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/17/09   Time: 2:12p
//Updated in $/LeapCC/Templates/TimeTable
//periodslotwise report function updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TimeTable
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/04/08    Time: 5:55p
//Created in $/Leap/Source/Templates/TimeTable
//intial checkin
 
?>