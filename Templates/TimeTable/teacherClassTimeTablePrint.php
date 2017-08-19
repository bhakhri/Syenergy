<?php 
//This file is used as printing version for teacher timetable.
//
// Author :Rajeev Aggarwal
// Created on : 22-08-2008
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

	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId	 = $sessionHandler->getSessionVariable('SessionId');
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $teacherRecordArray = TimeTableManager::getInstance()->getTeacherClassTimeTable(' AND tt.subjectId="'.$REQUEST_DATA['subject'].'" and tt.employeeId="'.$REQUEST_DATA['teacher'].'" and tt.groupId="'.$REQUEST_DATA['studentGroup'].'"');
	
	$className = $teacherRecordArray[0]['className']."---".$REQUEST_DATA['teacherName']."---".$REQUEST_DATA['subname']."---".$REQUEST_DATA['grpname'];
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	$reportManager->setReportInformation("For ".$className." As On $formattedDate ");
	$reportManager->setReportHeading("Time Table Report");
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
		<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">

		 
		<tr>
				<td width="5%" align="center" class = 'headingFont'><b>&nbsp;Period</b>
				<td valign="middle" align="center" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Monday</b></td>
				<td valign="middle" align="center" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Tuesday</b></td>
				<td valign="middle" align="center" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Wednesday</b></td>
				<td valign="middle" align="center" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Thursday</b></td>
				<td valign="middle" align="center" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Friday</b></td>
				<td valign="middle" align="center" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Saturday</b></td>
				<td valign="middle" align="center" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Sunday</b></td>
		</tr>
        <?php  
                $recordCount = count($teacherRecordArray);
                if($recordCount >0 && is_array($teacherRecordArray) ) { 
                  $pno="";
                    $preMatch=1;   //check previous matched date
                    $fl=0;         //check whether createBlankTd() is called first time or not
                    $el=0;         //check number of times control goes to else part        
                    for($i=0; $i<$recordCount; $i++ ) {
                      for ($j=1; $j<8 ;$j++) {
                         if($pno!=strip_slashes($teacherRecordArray[$i]['periodNumber']) and $pno==""){   
                              $preMatch=1;
                              $fl=0;
                              
                              $timeTableStr .= '<tr class='.$bg.'>';        
                              $timeTableStr .= '<td align="center" '.$reportManager->getReportDataStyle().'><b>'.strip_slashes($teacherRecordArray[$i]['periodNumber']).'</b><br />'.strip_slashes($teacherRecordArray[$i]['pTime']).'</td>';
                              $pno=strip_slashes($teacherRecordArray[$i]['periodNumber']);   
                          }
                         elseif($pno!=strip_slashes($teacherRecordArray[$i]['periodNumber']) and $pno!=""){
                             
                             $timeTableStr .=  createBlankTD(7-$preMatch);  
                             $preMatch=1;   
                             $fl=0;
                             $el=0;
                             $timeTableStr .= '</tr><tr '.$reportManager->getReportDataStyle().'>';        
                             $timeTableStr .=  '<td align="center" '.$reportManager->getReportDataStyle().'><b>'.strip_slashes($teacherRecordArray[$i]['periodNumber']).'</b><br />'.strip_slashes($teacherRecordArray[$i]['pTime']).'</td>';
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
                            $timeTableStr .='<td valign="top"  align="center" '.$reportManager->getReportDataStyle().'>'.strip_slashes($teacherRecordArray[$i]['subjectAbbreviation']).'<br>'.strip_slashes($teacherRecordArray[$i]['className']).'<br>'.strip_slashes($teacherRecordArray[$i]['roomAbbreviation']).'</td>';
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
                   $timeTableStr .= '<tr><td colspan="8" align="center"  style="font-size:11px">No record found</td></tr>';
                }
                         
               $timeTableStr .= '</table>';
 //echo the result
 echo $timeTableStr;   
                ?>          
                 <br>
				<table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
				<tr>
					<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
				</tr>
				</table>
<?php 
// $History: teacherClassTimeTablePrint.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TimeTable
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/22/08    Time: 12:33p
//Created in $/Leap/Source/Templates/TimeTable
//intial checkin
?>