<?php 
//-------------------------------------------------------
//  This File contains html form for all details report for events
//
//
// Author :Rajeev Aggarwal
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Notice Detail</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="605">
		<tr>
			<td valign="top" class="content">
			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
			 <tr>
				<td valign="top" class="content" align="center">
				<div id="flashcontent">
					<strong>You need to upgrade your Flash Player</strong>
				</div>
				<script type="text/javascript">
				  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "500", "600", "8", "#FFFFFF");
				  so.addVariable("path", "ampie/");  
				  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
				  so.addParam("wmode", "transparent");
				  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
				  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
				  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/noticeMonthwiseData.xml"));
				  so.addVariable("preloader_color", "#999999");
				  so.write("flashcontent");
				</script>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
	</td>
 </tr>
 </table>
<?php 
//$History: scAllNoticeDetailsReport.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 11/19/08   Time: 12:18p
//Updated in $/Leap/Source/Templates/Management
//updated xml path
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 11/13/08   Time: 3:39p
//Updated in $/Leap/Source/Templates/Management
//updated XML path
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/30/08   Time: 3:36p
//Updated in $/Leap/Source/Templates/Management
//added param parameter
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/20/08   Time: 3:43p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>