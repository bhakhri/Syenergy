<?php
//-------------------------------------------------------
// Purpose: to design the layout for General Feed Back
//
// Author : Rajeev Aggarwal
// Created on : (06.01.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GeneralFeedbackReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
$feedBackManager = TeacherManager::getInstance();

require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
$commonQueryManager = CommonQueryManager::getInstance(); 

$feedbackSurveyId=$REQUEST_DATA['feedbackSurveyId'];  
$teacherId=$REQUEST_DATA['teacherId'];

$filter = " WHERE feedbackSurveyId = ".$feedbackSurveyId;
$recordFeedBackArray = $commonQueryManager -> getFeedBackGradeDESC($filter);

$recordFeedBackCount = count($recordFeedBackArray);

$errorMessage ='';
$condition = " AND fq.feedbackSurveyId = ".$feedbackSurveyId;  

echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>
		<tr class='rowheading'>
			<td width='3%' class='searchhead_text' style='padding-left:15px'><b>#</b></td>
			<td width='42%' class='searchhead_text' align='left'><strong>Contents</strong></td>
			<td width='55%' class='searchhead_text' align='center' colspan='$recordFeedBackCount'><strong>Comments</strong></td>
			<td width='8%' class='searchhead_text' align='right' style='padding-right:15px'>
                          <strong>Statistics</strong></td>
		</tr>
		<tr>
			<td></td>
			<td></td>";
				if($recordFeedBackCount > 0 ) {
				$fieldValue='';
				for ($k=0;$k<$recordFeedBackCount;$k++) {
					
					echo "<td class='searchhead_text' align='right' valign='top' width='10%'><b> ".$recordFeedBackArray[$k]['feedbackGradeLabel']; 
					$fieldValue.=' SUM(if(sgs.feedbackGradeId='.$recordFeedBackArray[$k]['feedbackGradeId'].', 1 ,0)) AS feedGrade'.$recordFeedBackArray[$k]['feedbackGradeId'].' , ';
					echo "</b></td>";
					$strText .= $recordFeedBackArray[$k]['feedbackGradeLabel'].'~';
			   }
			} 
			echo "
		</tr>";
		
		$FeedBackArray = $feedBackManager->getFeedBackData1($fieldValue,$condition);
		$recordCount = count($FeedBackArray);
		
		if($recordCount > 0 ) {  
			
			$categoryName = "";
			for($k=0;$k<$recordCount;$k++){
	
				$categoryName1 = "<tr>
						 <td valign='top' colspan='6' style='font-size:12px'  ><b>".strip_slashes($FeedBackArray[$k]['feedbackCategoryName'])."</b></td></tr>";    
				$categoryName1 = ($categoryName == $categoryName1)?$categoryName1 = "": $categoryName1;
				$bg = $bg =='trow0' ? 'trow1' : 'trow0';      
				echo $categoryName1;

				echo "<tr class='$bg'>
						 <td valign='top' class='padding_top' style='padding-left:15px' height='20'>".($records+$k+1)."</td>  
						 <td valign='top' class='padding_top' >".strip_slashes($FeedBackArray[$k]['feedbackQuestion'])."</td>";    
						 for ($i=0;$i<$recordFeedBackCount;$i++) {   
							
							echo "<td valign='top' align='right' class='padding_top' style='padding-left:15px'>".
								strip_slashes($FeedBackArray[$k]['feedGrade'.($recordFeedBackArray[$i]['feedbackGradeId'])]); 
							 echo "</td>";
							  $strValue .= $FeedBackArray[$k]['feedGrade'.($recordFeedBackArray[$i]['feedbackGradeId'])].'~';
						  }
						   echo "<td valign='top' align='center' class='padding_top' style='padding-right:15px'><a href='Javascript:void(0)'  onClick='printStatistics(\"".$strText."\",\"".$strValue."\",\"".$FeedBackArray[$k]['feedbackQuestion']."\");return false;'><img src='".IMG_HTTP_PATH."/zoom.gif'></a></td>"; 
									   $strValue='';
				echo "</tr>";  
				
				if($categoryName1 != "")
					$categoryName = $categoryName1;
			}
			echo '<tr><td height="20"></td></tr><tr><td colspan="8" align="right"><div id = "saveDiv"><input type="image" name="teacherPrintSubmit" value="teacherPrintSubmit" src="'.IMG_HTTP_PATH.'/print.gif" onClick="printReport()" />&nbsp;&nbsp;<input type="image" name="imageField" src="'.IMG_HTTP_PATH.'/excel.gif" onClick="printReportCSV()"/></div></td></tr>';
	  }  
	  else {
		echo "<tr><td colspan='10' align='center'>No record found</td></tr>";
	}
	  
echo "</table>";
?>       
<?php 
// $History: scAjaxInitGeneralFeedBackList.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/EmployeeReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:34p
//Created in $/LeapCC/Library/EmployeeReports
//Intial checkin
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/18/09    Time: 7:26p
//Updated in $/Leap/Source/Library/ScEmployeeReports
//added Graphical view of feedback given question wise
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 3/30/09    Time: 6:59p
//Updated in $/Leap/Source/Library/ScEmployeeReports
//Fixed bugs and added new theme
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/27/09    Time: 12:12p
//Updated in $/Leap/Source/Library/ScEmployeeReports
//added Management Define.
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/13/09    Time: 4:58p
//Updated in $/Leap/Source/Library/ScEmployeeReports
//issue fix
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/08/09    Time: 2:57p
//Updated in $/Leap/Source/Library/ScEmployeeReports
//table name update
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/06/09    Time: 6:58p
//Created in $/Leap/Source/Library/ScEmployeeReports
//Intial checkin
?>