<?php 
//-------------------------------------------------------
//  This File contains html form for all details report
//
//
// Author :Rajeev Aggarwal
// Created on : 19-11-2008
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
					<td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Fees Detail</td>
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
							<option value="FeeCycle" <?php if($_REQUEST['searchStudent']=='FeeCycle') echo "Selected";?>>Fee Cycle</option>
							<option value="ClassWise" <?php if($_REQUEST['searchStudent']=='ClassWise') echo "Selected";?>>Class Wise</option>
							<option value="BatchWise" <?php if($_REQUEST['searchStudent']=='BatchWise') echo "Selected";?>>Batch Wise</option>
							<option value="StudyPeriod" <?php if($_REQUEST['searchStudent']=='StudyPeriod') echo "Selected";?>>Study Period</option>
							<option value="Hostel" <?php if($_REQUEST['searchStudent']=='Hostel') echo "Selected";?>>Hostel</option>
							<option value="Transport" <?php if($_REQUEST['searchStudent']=='Transport') echo "Selected";?>>Transport</option>
							<option value="Gender" <?php if($_REQUEST['searchStudent']=='Gender') echo "Selected";?>>Gender</option>
							<option value="PaymentType" <?php if($_REQUEST['searchStudent']=='PaymentType') echo "Selected";?>>Payment Type</option>
							 
							<option value="Category" <?php if($_REQUEST['searchStudent']=='Category') echo "Selected";?>>Category</option>
							<option value="City" <?php if($_REQUEST['searchStudent']=='City') echo "Selected";?>>City</option>
							<option value="State" <?php if($_REQUEST['searchStudent']=='State') echo "Selected";?>>State</option>
						</select></td>
						<td class="padding"><select size="1" name="graphType" id="graphType"class="inputbox1">
							 
							<option value="PieChart" <?php if($_REQUEST['graphType']=='PieChart') echo "Selected";?>>Pie Chart</option>
							<option value="BarGraph" <?php if($_REQUEST['graphType']=='BarGraph') echo "Selected";?>>Bar Graph</option>
						</select></td>
						<td align="left"  valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/display.gif" onClick="return getSearch();"/></td>
					</tr>
					<tr><td colspan="4" height="5px"></td></tr>
					</table>
					 </form>
                </td>
             </tr>
				<tr>
					<td valign="top" class="content" align="center">
						<?php
						if(($_REQUEST['searchStudent']=="FeeCycle") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feecycleTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="FeeCycle") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Fee Cycle ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feecycleTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="ClassWise") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeClassTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="ClassWise") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Class ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1000</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeclassTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="BatchWise") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeBatchTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="BatchWise") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Batch ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1000</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeBatchTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="StudyPeriod") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeStudyPeriodTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="StudyPeriod") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Study Period ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeStudyPeriodTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="Hostel") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeHostelTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="Hostel") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Hostel ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeHostelTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="Transport") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeTransportTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="Transport") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Bus Route ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeTransportTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="Gender") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeGenderTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="Gender") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Gender ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeGenderTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="PaymentType") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeInstrumentTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="PaymentType") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Payment Type ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeInstrumentTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="Category") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeCategoryTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="Category") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Category ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeCategoryTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="City") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeCityTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="City") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "960", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>City ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeCityTotalBarData.xml?t="+x));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>
							
						<!-- end of ampie script -->
						
						<?php
						}	
						 
						if(($_REQUEST['searchStudent']=="State") && ($_REQUEST['graphType']=="PieChart"))
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
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeStateTotal.xml"));
							  so.addVariable("preloader_color", "#999999");
							  so.write("flashcontent");
							</script>

						<!-- end of ampie script -->
						<?php
						}
						if(($_REQUEST['searchStudent']=="State") && ($_REQUEST['graphType']=="BarGraph"))
						{
						?>
						<!-- start of ampie script -->
							 
							<div id="flashcontent">
								<strong>You need to upgrade your Flash Player</strong>
							</div>
							<script type="text/javascript">
							  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "960", "400", "8", "#FFFFFF");
							  so.addVariable("path", "./");  
							  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
							  x = Math.random() * Math.random();
							  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>State ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
							  so.addParam("wmode", "transparent");
							  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
							  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeStateTotalBarData.xml?t="+x));
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
//$History: scAllFeesDetailsReport.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 11/20/08   Time: 3:28p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>