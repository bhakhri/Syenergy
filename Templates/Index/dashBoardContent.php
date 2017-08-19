<?php
//-------------------------------------------------------
// Purpose: to design admin dashboard.
//
// Author : Rajeev Aggarwal
// Created on : (22.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
			 <?php
			 if(isset($REQUEST_DATA['z'])) {
			 ?>
			 <table width='100%' class='accessDenied'><tr><td><?php echo ACCESS_DENIED;?></td></tr></table><br>
			 <?php } ?>
			 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" height="400">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Dashboard: </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
				<div id="div_Outer">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="padding_top" align="center"><b><?php echo $greetingMsg;  ?></b> </td></tr> 
				<tr>
				<td valign="top"  align="left" >
				<table width="960" border="0" align="center">
                <tr>
					<td height="163" scope="col" valign="top" align="left">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Branch Wise Detail','width=320' ,'height=150','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashBarPath = IMG_HTTP_PATH."/amcolumn.swf";
					?>           
					<table width="100%" height="150" border="0">
					<tr>
						<td valign="top" align="center">
						<table width="100%" border="0">
						<tr>
							<td valign="top">
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  x = Math.random() * Math.random();
							  var so = new SWFObject("<?php echo $flashBarPath?>", "amcolumn", "290", "200", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>140</y><rotate>true</rotate><text>Number Of Students ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>170</y><text>Branches---></text><text_size>10</text_size></label></labels></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentBranchBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							</td>
						</tr>
						</table>
						</td>

					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					 </td>
					  
					 <td height="163" scope="col" valign="top" align="center">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Degree Wise Detail','width=320' ,'height=150','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashPath = IMG_HTTP_PATH."/ampie.swf";
					?>           
					<table width="100%" height="150" border="0">
					<tr>
						<td valign="top" align="center">
						<table width="100%" border="0">
						<tr>
							<td valign="top">
							<div id="flashcontent1">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							x = Math.random() * Math.random();
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentDegreeData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent1");
							</script>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					 </td>
					 <td height="163" scope="col" valign="top" align="center">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Study Period Wise Details','width=320' ,'height=150','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashPath = IMG_HTTP_PATH."/ampie.swf";
					?>           
					<table width="100%" height="150" border="0">
					<tr>
						<td valign="top" align="center">
						<table width="100%" border="0">
						<tr>
							<td valign="top">
							<div id="flashcontent2">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  x = Math.random() * Math.random();
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentStudyPeriodData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent2");
							</script>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					 </td>
				</tr>  
				<tr>
					<td scope="col" valign="top" align="left">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('City Wise Details','width=320' ,'height=248','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashPath = IMG_HTTP_PATH."/ampie.swf";
					?>           
					<table width="100%" height="248" border="0">
					<tr>
						<td valign="top" align="center">
						<table width="100%" border="0">
						<tr>
							<td valign="top">
							<div id="flashcontent4">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  x = Math.random() * Math.random();
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentCityData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent4");
							</script>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					</td>
					<td scope="col" valign="top" align="left">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Gender Wise Details','width=320' ,'height=248','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashPath = IMG_HTTP_PATH."/ampie.swf";
					?>           
					<table width="100%" height="248" border="0">
					<tr>
						<td valign="top" align="center">
						<table width="100%" border="0" align="center">
						<tr>
							<td valign="top">
							<div id="flashcontent5">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  x = Math.random() * Math.random();
							  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "240", "200", "8", "#FFFFFF");
							  so.addVariable("path", "ampie/");  
							  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
							  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>110</y></pie></settings>");
							  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentGenderData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent5");
							</script>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					</td>
					<td scope="col" valign="top" align="left">
					<?php
						 //*************Used For Creating*********
						require_once(BL_PATH.'/HtmlFunctions.inc.php');
						echo HtmlFunctions::getInstance()->tableBlueHeader('Custom Details','width=320' ,'height=150','align=center');
						echo UtilityManager::includeJS("swfobject.js");
						$flashPath = IMG_HTTP_PATH."/ampie.swf";
					?>           
					<table width="100%" height="150" border="0">
					<tr>
						<td valign="top" align="center">
						<table width="100%" border="0" align="center" height="242">
						<tr>	
							<td class="padding" valign="top"><form action="" method="POST" name="searchForm" id="searchForm">
							<select size="1" name="searchStudent" id="searchStudent"class="inputbox1" onChange="return getSearch();">
								<option value="">Select</option>
								<option value="Batch" <?php if($_REQUEST['searchStudent']=='Batch') echo "Selected";?>>Batch</option>
								<option value="Category" <?php if($_REQUEST['searchStudent']=='Category') echo "Selected";?>>Category</option>
								<option value="Hostel" <?php if($_REQUEST['searchStudent']=='Hostel') echo "Selected";?>>Hostel</option>
								<option value="State" <?php if($_REQUEST['searchStudent']=='State') echo "Selected";?>>State</option>
								<option value="Nationality" <?php if($_REQUEST['searchStudent']=='Nationality') echo "Selected";?>>Nationality</option>
								<option value="BusRoute" <?php if($_REQUEST['searchStudent']=='BusRoute') echo "Selected";?>>Bus Route</option>
								<option value="BusStop" <?php if($_REQUEST['searchStudent']=='BusStop') echo "Selected";?>>Bus Stop</option>
								<option value="Institute" <?php if($_REQUEST['searchStudent']=='Institute') echo "Selected";?>>Institute</option>
							</select>
							</td>
						</tr>
						<tr>
							<td valign="top" class="content" align="center"><div id="resultsDiv"></div></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					<?php 
						echo HtmlFunctions::getInstance()->tableBlueFooter();
						//floatingDiv_Close(); 
						//*************End of Div*********
					?>  
					</td>
					</tr>
					</table>
					<?php 
						floatingDiv_Close(); 
						//*************End of Div*********
					?>
					</td>
					</tr>
				</table>          
				</div>    
			</td>
			</tr>
        </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

	<!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice '); ?>
<form name="NoticeForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td width="11%" valign="top" align="right"><nobr><b>Subject: &nbsp;</b></nobr></td>
        <td width="89%"><div id="noticeSubject" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Notice: &nbsp;</b></nobr></td>
        <td><div id="noticeText" style="overflow:auto; width:550px; height:200px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td valign="top" align="right"><nobr><b>From: &nbsp;</b></nobr></td>
        <td><div id="visibleFromDate" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>

    <tr>    
        <td valign="top" align="right"><nobr><b>To: &nbsp;</b></nobr></td>
        <td><div id="visibleToDate" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td>
	</tr>
</table>
</form> 
<?php floatingDiv_End(); ?>


<!--Start Event  Div-->
<?php floatingDiv_Start('divEvent','Event '); ?>
<form name="EventForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td width="20%" valign="top" align="right"><nobr><b>Event: &nbsp;</b></nobr></td>
        <td width="80%"><div id="eventTitle" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
    <tr>
    <td colspan="2" valign="top" align="right">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <tr>
        <td width="20%" align="right" valign="top"><nobr><b>From: &nbsp;</b></nobr></td>
        <td width="15%" valign="top" align="left" nowrap><div id="startDate" style="width:30px; height:20px"></div></td>
        <td width="5%" valign="top"><nobr><b>To: &nbsp;</b></nobr></td>
        <td valign="top" align="left" nowrap><div id="endDate" style="width:30px; height:20px"></div></td>
      </tr>
     </table>
    </td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Description(S): &nbsp;</b></nobr></td>
        <td valign="top"><div id="shortDescription" style="overflow:auto; width:225px; height:20px" ></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Description(L): &nbsp;</b></nobr></td>
        <td  valign="top"><div id="longDescription" style="overflow:auto; width:300px; height:200px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
 
<?php 
// $History: dashBoardContent.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Index
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/02/09    Time: 4:42p
//Updated in $/LeapCC/Templates/Index
//Updated with random number parameter in flash reports
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 1:41p
//Created in $/LeapCC/Templates/Index
//Intial checkin
?>