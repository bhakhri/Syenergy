<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedBackManager = FeedBackManager::getInstance();

	$feedBackSurveyId=$REQUEST_DATA['sourceSurvey'];

	$surveyType=$REQUEST_DATA['surveyType'];
	$surveyName=$REQUEST_DATA['surveyName'];

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	 

	require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
	$commonQueryManager = CommonQueryManager::getInstance(); 

	$recordFeedBackArray = $commonQueryManager ->  getFeedBackGrade(' feedbackGradeId'," AND feedback_grade.feedbackSurveyId='".$feedBackSurveyId."'");
	$recordFeedBackCount = count($recordFeedBackArray);
	 
	 
	$studentFeedBackArray = $feedBackManager->getFeedBackData("AND fs.feedbackSurveyId='".$feedBackSurveyId."'");
	$recordCount = count($studentFeedBackArray);

	if($recordCount >0 && is_array($studentFeedBackArray) ) { 

	$reportHeader ='<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
	<tr>
		<td align="left" colspan="1" width="25%" class="">'.$reportManager->showHeader().'</td>
		<th align="center" colspan="1" width="50%" '.$reportManager->getReportTitleStyle().'>'.$reportManager->getInstituteName().'</th>
		<td align="right" colspan="1" width="25%" class="">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="" colspan="1" '.$reportManager->getDateTimeStyle().'align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" '.$reportManager->getDateTimeStyle().'>'.date("d-M-y").'</td>
				</tr>
				<tr>
					<td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right">Time :&nbsp;</td><td valign="" colspan="1" '.$reportManager->getDateTimeStyle().'>'.date("h:i:s A").'</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><th colspan="3" '.$reportManager->getReportHeadingStyle().' align="center">Feedback Survey Report</th></tr>
	 
	<tr><th colspan="3" '.$reportManager->getReportInformationStyle().'>For <B>Survey Type:</B>'.$surveyType.'&nbsp;&nbsp;<B>Survey Name:</B>'.$surveyName.'</th></tr>
	 
	</table> <br>';
	 
	$reportFooter = "<table border='0' cellspacing='0' cellpadding='0' width='90%' align='center'>
	<tr>
		<td valign='' align='left' colspan='".count($reportManager->tableHeadArray)."' ".$reportManager->getFooterStyle().">".$reportManager->showFooter()."</td>
	</tr>
	</table>";

		$header ='<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center"  class="reportTableBorder">
	 <tr class="rowheading">
		<td width="2%" class="unsortable"><b>#</b></td>
		<td width="40%"   class="searchhead_text" align="left"><strong>Contents</strong></td>
		<td width="55%"   class="searchhead_text" align="center" colspan="'.$recordFeedBackCount.'"><strong>Comments</strong></td>
	 </tr>
	 <tr>
		<td></td>
		<td></td>';
		if ($recordFeedBackCount > 0 && is_array($studentFeedBackArray)) {

			for ($k=0;$k<$recordFeedBackCount;$k++) {
		 
				$header .='<td valign="top" align="center" '.$reportManager->getReportDataStyle().'><b>'.$recordFeedBackArray[$k]['feedbackGradeLabel'].'</b></td>';
			} 
		} 
		 
	   $header .='</tr>';
		   
		   echo $reportHeader;
		   echo $header;	
		   $catName ='';
		   $j=0;
		   for($i=0; $i<$recordCount; $i++ ) {
			
				$catName1 = strip_slashes($studentFeedBackArray[$i]['feedbackCategoryName']);
				$catName1 = ($catName == $catName1)?$catName1 = "": $catName1;
				$questionId = $studentFeedBackArray[$i]['feedbackQuestionId'];
		  ?>
		<tr>
			<td colspan="2" <?php echo $reportManager->getReportDataStyle()?>><b><?php echo $catName1?></b></td>
		</tr>
		 
		<tr>
			<td valign="top" <?php echo $reportManager->getReportDataStyle()?> width='5px'><?php echo $records+$i+1 ?></td>
			 
			<td <?php echo $reportManager->getReportDataStyle()?> valign="top" ><?php echo strip_slashes($studentFeedBackArray[$i]['feedbackQuestion'])?></td>
			<?php if ($recordFeedBackCount > 0 && is_array($studentFeedBackArray)) {
			 
			  for ($k=0;$k<$recordFeedBackCount;$k++) {
				   
			?>
			<td class="padding_top" valign="top" align="center"><input name="radio_<?php echo $i;?>" type="radio"></td>
			<?php } 
			 } 
			?>
		</tr>
	   <?php
			 $j++;
			 if(($j%25)==0){
		
				echo "</table>".$reportFooter."<br class='page'>".$reportHeader.$header;				
			 }
			 if($catName1 != "")
				$catName = $catName1;
		}
		}
		else {
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" >';
			echo '<tr><td colspan="10" align="center" ><b>No record found</b></td></tr>';
		}
		
		?> 
				
        </table>
		</table> <br>
<?php
		echo $reportFooter;

// $History: previewSurveyPrint.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/24/09    Time: 11:26a
//Updated in $/LeapCC/Templates/FeedBack
//0000272: Preview Survey -Admin > Title of page is not correct; and
//“Report” keyword must be added in heading part.
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:34p
//Created in $/LeapCC/Templates/FeedBack
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/19/09    Time: 4:51p
//Created in $/Leap/Source/Templates/FeedBack
//Added Preview survey related function.
?>