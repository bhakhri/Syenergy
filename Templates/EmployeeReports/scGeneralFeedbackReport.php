<?php 
//it contain the template of General survery feedback 
//
// Author :Rajeev Aggarwal
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		
		<tr>
		  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="400">
		<tr>
		 <td valign="top" class="content">
		
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
			<td class="contenttab_border2" height="20">
				<table width="30%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td class="contenttab_internal_rows"><nobr><b>Survey: </b></nobr></td>
					<td class="padding">
					<select size="1" class="inputbox1" name="feedbackSurveyId" id="feedbackSurveyId">
					<?php
					   require_once(BL_PATH.'/HtmlFunctions.inc.php');
					   echo HtmlFunctions::getInstance()->getFeedBackLabelData(""," WHERE surveyType =1");
					?>
					</select></td>
					 
					<td align="left"  valign="middle">
					 <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return getTeacherFeedBackData();return false;" />
					</td>
				</tr>
				</table>
			</td>
		 </tr>
		 <tr>
		 <td valign="top" >
		 <!--General Survey Feedback Data Template-->
		  <div id="results"></div>
		 <!--General Survey Feedback Data Template Ends-->           
		 </td>
	  </tr>
	  </table>
	</td>
</tr>
</table>
<?php
//$History: scGeneralFeedbackReport.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/EmployeeReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:34p
//Created in $/LeapCC/Templates/EmployeeReports
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 1/06/09    Time: 6:57p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//Intial checkin
?>