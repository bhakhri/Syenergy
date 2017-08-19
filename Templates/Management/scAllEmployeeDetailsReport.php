<?php 
//-------------------------------------------------------
//  This File contains html form for all details report
//
//
// Author :Rajeev Aggarwal
// Created on : 18-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Employee Detail</td>
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
                <td class="contenttab_border2" height="20">

                   <form action="" method="POST" name="searchForm" id="searchForm">
                    <table border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td height="5"></td>
					</tr>
					<tr>	
						<td class="contenttab_internal_rows"><nobr><b>Please select the filter criteria: </b></nobr></td>
						<td class="padding"><select size="1" name="searchStudent" id="studentSubject"class="inputbox1">
						<option value="">Select</option>
						<option value="RoleWise" <?php if($_REQUEST['searchStudent']=='RoleWise') echo "Selected";?>>Role Wise</option>
						<option value="Teaching" <?php if($_REQUEST['searchStudent']=='Teaching') echo "Selected";?>>Teaching/Non-Teaching</option>
						<option value="Marital" <?php if($_REQUEST['searchStudent']=='Marital') echo "Selected";?>>Marital Status</option>

						<option value="City" <?php if($_REQUEST['searchStudent']=='City') echo "Selected";?>>City</option>
						<option value="State" <?php if($_REQUEST['searchStudent']=='State') echo "Selected";?>>State</option>
						
						<option value="Designation" <?php if($_REQUEST['searchStudent']=='Designation') echo "Selected";?>>Designation</option>
						<option value="Branch" <?php if($_REQUEST['searchStudent']=='Branch') echo "Selected";?>>Branch</option>

						<option value="Gender" <?php if($_REQUEST['searchStudent']=='Gender') echo "Selected";?>>Gender</option>
						</select></td>
						<td align="left"  valign="middle">
                        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/display.gif"   onClick="return getSearch();"/>
                        </td>
					</tr>
					<tr><td colspan="4" height="5px"></td></tr>

					</table>
					 </form>
                </td>
             </tr>
				<tr>
					<td valign="top" class="content" align="center">
						<?php
						if($_REQUEST['searchStudent']=="RoleWise")
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeRoleWiseData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Teaching")
						{ 
						?>
						<!-- start of ampie script -->
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeTeachingData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Marital")
						{ 
						?>
						<!-- start of ampie script -->
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeMaritalData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="City")
						{ 
						?>
						<!-- start of ampie script -->
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeCityData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="State")
						{ 
						?>
						<!-- start of ampie script -->
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeStateDetailData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						
						<?php
						}
						else if($_REQUEST['searchStudent']=="Designation")
						{ 
						?>
						<!-- start of ampie script -->
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeDesignationData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Branch")
						{ 
						?>
						<!-- start of ampie script -->
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "600", "1700", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeBranchData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Gender")
						{ 
						?>
						<!-- start of ampie script -->
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeGenderData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						
						<?php
						}	
						?>
					</td>
				</tr>
			</table>
		</table>
<?php 
//$History: scAllEmployeeDetailsReport.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 11/19/08   Time: 12:18p
//Updated in $/Leap/Source/Templates/Management
//updated xml path
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 11/13/08   Time: 3:39p
//Updated in $/Leap/Source/Templates/Management
//updated XML path
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10/30/08   Time: 3:36p
//Updated in $/Leap/Source/Templates/Management
//added param parameter
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10/30/08   Time: 2:37p
//Updated in $/Leap/Source/Templates/Management
//updated management reports
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/21/08   Time: 1:30p
//Updated in $/Leap/Source/Templates/Management
//updated selected box validations
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/21/08   Time: 10:03a
//Updated in $/Leap/Source/Templates/Management
//updated javascript validation on select box
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/18/08   Time: 6:26p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>