<?php 
//-------------------------------------------------------
//  This File contains html form for all details report for events
//
//
// Author :Rajeev Aggarwal
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
			
				<tr>
					  <?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");?>
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
				   x = Math.random() * Math.random();
				  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "500", "600", "8", "#FFFFFF");
				  so.addVariable("path", "ampie/");  
				  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
				  so.addParam("wmode", "transparent");
				  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
				  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
				  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/eventMonthwiseData.xml?t="+x));
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
//$History: allEventDetailsReport.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/02/09    Time: 4:19p
//Updated in $/LeapCC/Templates/Management
//Updated with random number genration for flash report
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
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