<?php
//This file is used as printing version for Teacher Survery FeedBack 
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $feedBackManager = TeacherManager::getInstance();

    $quest=$REQUEST_DATA['quest'];
    $surveyName=$REQUEST_DATA['surveyName'];   
	$teacherName=$REQUEST_DATA['teacherName'];   
    $text=$REQUEST_DATA['text'];    
    $val=$REQUEST_DATA['val'];   
    $textArr = explode("~", $text);
	$valArr = explode("~", $val);
	if($teacherName){
		
		$teacher ="<B>Teacher:</B>".$teacherName;
	}

	$feedbackSurveyId=$REQUEST_DATA['feedbackSurveyId'];
	$surveryArr = $feedBackManager->getSurveyDetail(" AND fs.feedbackSurveyId =".$feedbackSurveyId);
    /* START: function to fetch student feedback data */
	$recordFeedBackCount = count($surveryArr); 
	$cnt = count($textArr);
	$strList2 .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt-1;$i++) {

		$strList2 .= "<slice title='".$textArr[$i]."'>".$valArr[$i]."</slice>\n";
		 
    } 
	$strList2 .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/studentFeedbackData.xml";
	UtilityManager::writeXML($strList2, $xmlFilePath);

	echo UtilityManager::includeJS("swfobject.js");
	$flashPath = IMG_HTTP_PATH."/ampie.swf";
	/* END: function to fetch student feedback data */ 

// $History: scGeneralFeedBackStatisticsPrint.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:34p
//Created in $/LeapCC/Templates/EmployeeReports
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/18/09    Time: 7:27p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//Intial checkin for graphical view of feeback question wise
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
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Teacher Survey Feedback</th></tr>
	 
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>>For <B>Question:</B>"<?php echo $quest?>"</th></tr>
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><B>Survey:</B><?php echo $surveyName."&nbsp;".$teacher?></th></tr> 
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><B>Opened On:</B><?php echo (UtilityManager::formatDate($surveryArr[0]['visibleFrom']));?>&nbsp;&nbsp;<B>Closed On:</B><?php echo (UtilityManager::formatDate($surveryArr[0]['visibleTo']));?></th></tr> 
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?> align="center">
	<table><tr>
	<?php
		if($recordFeedBackCount > 0 ) {
                
				echo "<td><b>Assigned To </b></td>";

			for ($k=0;$k<$recordFeedBackCount;$k++) {

				echo "<td><b>".$surveryArr[$k]['surveyUser'].":</b></td>";
				echo "<td>".$surveryArr[$k]['totalRecords']."</td>";
			}
		}
	?>
	
	</tr></table>
	</th></tr> 
	</table> <br>
	 	 	 	 	 	 	 
	<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
	 
	<tr>
		<td valign="top" align='center'>
		<div id="flashcontent1">
			<strong>You need to upgrade your Flash Player</strong>
		</div>
		<script type="text/javascript">
		x = Math.random() * Math.random();
		  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "640", "350", "8", "#FFFFFF");
		  so.addVariable("path", "ampie/");  
		  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
		  so.addParam("wmode", "transparent");
		  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting.xml"));
		  so.addVariable ("additional_chart_settings", "<settings><pie><x>310</x><y>175</y></pie><legend><enabled>true</enabled></legend></settings>");
		  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentFeedbackData.xml?t="+x));
		  so.addVariable("preloader_color", "#999999");
		  so.write("flashcontent1");
		</script>
		</td>
		 
	</tr>
	 
	</table>
	<br>
	<table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
	<tr>
		<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
	</tr>
	</table>