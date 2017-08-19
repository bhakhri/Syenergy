<?php 
//-------------------------------------------------------
//  This File contains html form for all details report
//
//
// Author :Rajeev Aggarwal
// Created on : 13-10-2008
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
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Student Detail</td>
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
							<option value="Branch" <?php if($_REQUEST['searchStudent']=='Branch') echo "Selected";?>>Branch</option>
							<option value="Degree" <?php if($_REQUEST['searchStudent']=='Degree') echo "Selected";?>>Degree</option>
							<option value="Section" <?php if($_REQUEST['searchStudent']=='Section') echo "Selected";?>>Section</option>
							<option value="Batch" <?php if($_REQUEST['searchStudent']=='Batch') echo "Selected";?>>Batch</option>
							<option value="StudyPeriod" <?php if($_REQUEST['searchStudent']=='StudyPeriod') echo "Selected";?>>Study Period</option>
							<option value="Category" <?php if($_REQUEST['searchStudent']=='Category') echo "Selected";?>>Category</option>
							<option value="Hostel" <?php if($_REQUEST['searchStudent']=='Hostel') echo "Selected";?>>Hostel</option>
							<option value="City" <?php if($_REQUEST['searchStudent']=='City') echo "Selected";?>>City</option>
							<option value="State" <?php if($_REQUEST['searchStudent']=='State') echo "Selected";?>>State</option>
							<option value="Nationality" <?php if($_REQUEST['searchStudent']=='Nationality') echo "Selected";?>>Nationality</option>
							<option value="BusRoute" <?php if($_REQUEST['searchStudent']=='BusRoute') echo "Selected";?>>Bus Route</option>
							<option value="BusStop" <?php if($_REQUEST['searchStudent']=='BusStop') echo "Selected";?>>Bus Stop</option>
							<option value="Gender" <?php if($_REQUEST['searchStudent']=='Gender') echo "Selected";?>>Gender</option>
							<option value="Institute" <?php if($_REQUEST['searchStudent']=='Institute') echo "Selected";?>>Institute</option>
						</select></td>
						<td align="left"  valign="middle">
                        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/display.gif"  onClick="return getSearch();"/>
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
						if($_REQUEST['searchStudent']=="City")
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "600", "1300", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentCityData.xml"));
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
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "400", "600", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentBranchData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Degree")
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
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>300</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentDegreeData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Hostel")
						{ 
						?>
						<!-- start of ampie script -->
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "350", "600", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><x>150</x> <y>300</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentHostelData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

							<div id="flashcontent1">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "350", "600", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie1"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><x>150</x> <y>300</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentHostelDetailData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent1");
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
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>300</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentGenderData.xml"));
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
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "500", "600", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>300</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentStateData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="BusStop")
						{ 
						?>
						<!-- start of ampie script -->
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "900", "1600", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>700</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentBusStopData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Section")
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
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>400</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentSectionData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->

						<?php
						}
						else if($_REQUEST['searchStudent']=="BusRoute")
						{ 
						?>
						<!-- start of ampie script -->
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "600", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>300</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentBusRouteData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->

						<?php
						}
						else if($_REQUEST['searchStudent']=="Nationality")
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentNationalityData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Category")
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentQuotaData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->

						<?php
						}
						else if($_REQUEST['searchStudent']=="Batch")
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
							  so.addVariable ("additional_chart_settings", "<settings><pie><y>300</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentBatchData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->

						<?php
						}
						else if($_REQUEST['searchStudent']=="StudyPeriod")
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentStudyPeriodData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->
						<?php
						}
						else if($_REQUEST['searchStudent']=="Institute")
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentInstituteData.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
						<!-- end of ampie script -->


						<?php
						}	
						?>
						<!--form action="" id="myform">
						  <input type="button" value="export image" onclick="exportImage();" />        
						</form-->
					</td>
				</tr>
			</table>
		</table>
<?php 
//$History: scListAllDetailsReport.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 11/19/08   Time: 12:18p
//Updated in $/Leap/Source/Templates/Management
//updated xml path
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 11/13/08   Time: 3:39p
//Updated in $/Leap/Source/Templates/Management
//updated XML path
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 10/30/08   Time: 5:37p
//Updated in $/Leap/Source/Templates/Management
//added param parameter to increase the zindex of javascript menu
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 10/30/08   Time: 2:37p
//Updated in $/Leap/Source/Templates/Management
//updated management reports
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10/24/08   Time: 5:51p
//Updated in $/Leap/Source/Templates/Management
//added all student print reports
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 10/21/08   Time: 1:30p
//Updated in $/Leap/Source/Templates/Management
//updated selected box validations
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 10/21/08   Time: 10:03a
//Updated in $/Leap/Source/Templates/Management
//updated javascript validation on select box
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10/18/08   Time: 6:22p
//Updated in $/Leap/Source/Templates/Management
//updated with employee graphs
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10/18/08   Time: 1:49p
//Updated in $/Leap/Source/Templates/Management
//added hostel in select box
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10/17/08   Time: 5:21p
//Updated in $/Leap/Source/Templates/Management
//updated section with section type
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/17/08   Time: 2:24p
//Updated in $/Leap/Source/Templates/Management
//updated file permission
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:28p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>