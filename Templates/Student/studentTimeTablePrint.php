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
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$studentId    = $REQUEST_DATA['studentId'];
	$studentName  = $REQUEST_DATA['studentName'];
	$studentLName = $REQUEST_DATA['studentLName'];
    $studentRecordArray = $studentManager->getStudentTimeTable($studentId);
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	$reportManager->setReportInformation("For ".$studentName.' '.$studentLName." As On $formattedDate ");
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
                $recordCount = count($studentRecordArray);
                if($recordCount >0 && is_array($studentRecordArray) ) { 
                    $pno="";
                    $preMatch=1;   //check previous matched date
                    $fl=0;         //check whether createBlankTd() is called first time or not
                    $el=0;         //check number of times control goes to else part        
                    for($i=0; $i<$recordCount; $i++ ) {
                      for ($j=1; $j<8 ;$j++) {
                         if($pno!=strip_slashes($studentRecordArray[$i]['periodNumber']) and $pno==""){   
                              $preMatch=1;
                              $fl=0;
                              
                              echo '<tr>';        
                echo  '<td style="padding-left:10px;"'.$reportManager->getReportDataStyle().'><b> '.strip_slashes($studentRecordArray[$i]['periodNumber']).'</b></td>';
                              $pno=strip_slashes($studentRecordArray[$i]['periodNumber']);   
                          }
                         elseif($pno!=strip_slashes($studentRecordArray[$i]['periodNumber']) and $pno!=""){
                            
                             echo  createBlankTD(7-$preMatch);  
                             $preMatch=1;   
                             $fl=0;
                             $el=0;
                             echo '</tr><tr>';        
                             echo  '<td style="padding-left:10px;"'.$reportManager->getReportDataStyle().'  ><b>'.strip_slashes($studentRecordArray[$i]['periodNumber']).'</b></td>';
                             $pno=strip_slashes($studentRecordArray[$i]['periodNumber']);    
                         }
                        if (trim($studentRecordArray[$i]['daysOfWeek'])==$j){
                            if($fl==0){
                                echo createBlankTD($el);
                                $fl=1;
                             }
                            else{
                               echo createBlankTD($j-$preMatch-1); 
                            } 
                            echo '<td valign="top" valign="middle" align="center"'.$reportManager->getReportDataStyle().' >'.strip_slashes($studentRecordArray[$i]['subjectAbbreviation']).'<br>'.strip_slashes($studentRecordArray[$i]['employeeName']).'<br>'.strip_slashes($studentRecordArray[$i]['roomName']).'</td>';
                            $preMatch=$j;
                            $el=0;
                         } 
                       else{
                          $el++; 
                       }  
                     }
                   }
                  if($pno==strip_slashes($studentRecordArray[$i-1]['periodNumber']) and $pno!=""){
                    echo  createBlankTD(7-$preMatch);  
                  }           

                    if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="8" align="right" '.$reportManager->getReportDataStyle().' >'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    }
                }
                else {
                    echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                }
                echo  '</tr>';
                ?>          
                </table> <br>
				<table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
				<tr>
					<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
				</tr>
				</table>
