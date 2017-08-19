<?php
//-------------------------------------------------------
//  This File contains all details report of Fees for management.
//
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ManagementFeesInfo');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
//require_once(BL_PATH . "/Management/scFetchFeesData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fees Detail Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js"); 

$flashPath = IMG_HTTP_PATH."/ampie.swf";
?> 
<script language="javascript">

function getSearch(){

	if(isEmpty(document.getElementById('searchStudent').value)){
       
	   messageBox("Please select search parameter");
	   document.searchForm.searchStudent.focus();
	   return false;
    }
    showGraph();
	return false;
}

function showGraph() {

	x = Math.random() * Math.random(); 
	var url = '<?php echo HTTP_LIB_PATH;?>/Management/getFeesDetailGraph.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {searchStudent: (document.getElementById('searchStudent').value),graphType: (document.getElementById('graphType').value)},
		asynchronous:false,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
		hideWaitDialog();
		//alert(trim(transport.responseText));
		if("FeeCyclePie" == trim(transport.responseText)) {
			
			showFeeCyclePieResults();
		}
		if("FeeCycleBar" == trim(transport.responseText)) {
			
			showFeeCycleBarResults();
		}
		if("ClassWisePie" == trim(transport.responseText)) {
			
			showClassWisePieResults();
		}
		if("ClassWiseBar" == trim(transport.responseText)) {
			
			showClassWiseBarResults();
		}
		if("BatchWisePie" == trim(transport.responseText)) {
			
			showBatchWisePieResults();
		}
		if("BatchWiseBar" == trim(transport.responseText)) {
			
			showBatchWiseBarResults();
		}
		if("StudyPeriodPie" == trim(transport.responseText)) {
			
			showStudyPeriodPieResults();
		}
		if("StudyPeriodBar" == trim(transport.responseText)) {
			
			showStudyPeriodBarResults();
		}
		if("HostelPie" == trim(transport.responseText)) {
			
			showHostelPieResults();
		}
		if("HostelBar" == trim(transport.responseText)) {
			
			showHostelBarResults();
		}
		if("TransportPie" == trim(transport.responseText)) {
			
			showTransportPieResults();
		}
		if("TransportBar" == trim(transport.responseText)) {
			
			showTransportBarResults();
		}
		if("GenderPie" == trim(transport.responseText)) {
			
			showGenderPieResults();
		}
		if("GenderBar" == trim(transport.responseText)) {
			
			showGenderBarResults();
		}
		if("PaymentTypePie" == trim(transport.responseText)) {
			
			showPaymentTypePieResults();
		}
		if("PaymentTypeBar" == trim(transport.responseText)) {
			
			showPaymentTypeBarResults();
		}
		if("CategoryPie" == trim(transport.responseText)) {
			
			showCategoryPieResults();
		}
		if("CategoryBar" == trim(transport.responseText)) {
			
			showCategoryBarResults();
		}
		if("CityPie" == trim(transport.responseText)) {
			
			showCityPieResults();
		}
		if("CityBar" == trim(transport.responseText)) {
			
			showCityBarResults();
		}
		if("StatePie" == trim(transport.responseText)) {
			
			showStatePieResults();
		}
		if("StateBar" == trim(transport.responseText)) {
			
			showStateBarResults();
		}

		
	},
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function showFeeCyclePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feecycleTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showFeeCycleBarResults() {

	 var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Fee Cycle ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feecycleTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}


function showClassWisePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeClassTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showClassWiseBarResults() {

	  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Class ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1000</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeclassTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showBatchWisePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeBatchTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showBatchWiseBarResults() {

	  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Batch ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1000</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeBatchTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showStudyPeriodPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeStudyPeriodTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showStudyPeriodBarResults() {

	  var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Study Period ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeStudyPeriodTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showHostelPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeHostelTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showHostelBarResults() {

	 var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Hostel ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeHostelTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showTransportPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeTransportTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showTransportBarResults() {

	 var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Bus Route ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeTransportTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showGenderPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeGenderTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showGenderBarResults() {

	 var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Gender ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeGenderTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showPaymentTypePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeInstrumentTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showPaymentTypeBarResults() {

	var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Payment Type ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeInstrumentTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showCategoryPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeCategoryTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showCategoryBarResults() {

	 var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "590", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>Category ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeCategoryTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showCityPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeCityTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showCityBarResults() {

	 var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "960", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>City ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeCityTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showStatePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeStateTotal.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showStateBarResults() {

	 var so = new SWFObject("<?php echo IMG_HTTP_PATH?>/amcolumn.swf", "amcolumn", "960", "400", "8", "#FFFFFF");
	  so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>320</y><rotate>true</rotate><text>Amount ---></text><text_size>10</text_size></label><label id='2'><x>80</x><y>370</y><text>State ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>75</left></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/feeStateTotalBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

 
//////////////////////////////////////////////////////////////////////////////////////////
// Functions that are called by the chart ////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
      
// CHART INITED //////////////////////////////////////////////////////////////////////////    
// amChartInited(chart_id)
// This function is called when the chart is fully loaded and initialized.
function amChartInited(chart_id){
  // get the flash object into "flashMovie" variable   
  flashMovie = document.getElementById(chart_id);
  // tell the field with id "chartfinished" that this chart was initialized
  document.getElementById("chartfinished").value = "chart " + chart_id + " is finished";           
}      
      
// RETURN DATA ///////////////////////////////////////////////////////////////////////////
// amReturnData(chart_id, data)
// This function is called when you request data from a chart 
//  by calling the flashMove.getData() function.
function amReturnData(chart_id, data){
  document.getElementById("data").value = unescape(data);
}

// RETURN PARAM //////////////////////////////////////////////////////////////////////////
// amReturnParam(chart_id, param)
// This function is called when you request a setting from a chart  
// by calling the flashMovie.getParam(param) function.
function amReturnParam(chart_id, param){
  document.getElementById("returnedparam").value = unescape(param);
}

// RETURN SETTINGS ///////////////////////////////////////////////////////////////////////
// amReturnSettings(chart_id, settings)
// This function is called when you request settings from a chart 
// by calling flashMovie.getSettings() function.  
function amReturnSettings(chart_id, settings){
  document.getElementById("settings").value = unescape(settings);
}      

// RETURN IMAGE DATA /////////////////////////////////////////////////////////////////////
// amReturnImageData(chart_id, data)
// This function is called when the export to image process is finished and might be used
// as alternative way to get image data (instead of posting it to some file)
function amReturnImageData(chart_id, data){
  // your own functions here
}

// ERROR /////////////////////////////////////////////////////////////////////////////////
// amError(chart_id, message)
// This function is called when an error occurs, such as no data, or file not found.
function amError(chart_id, message){
  alert(message);
}    

// FIND OUT WHICH SLICE WAS CLICKED //////////////////////////////////////////////////////
// amSliceClick(chart_id, index, title, value, percents, color, description)
// This function is called when the viewer clicks on the slice. It returns chart_id, 
// the sequential number of the slice (index), the title, value, percent value, 
// color and description.
function amSliceClick(chart_id, index, title, value, percents, color, description){

  roleArr = description.split("~");
  
  /*if(roleArr[1]=="feecycle"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/scFeeCycleTotalPrint.php?roleId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }*/

  document.getElementById("sliceclick").value = index;
} 

// FIND OUT WHICH SLICE WAS HOVERED //////////////////////////////////////////////////////
// amSliceOver(chart_id, index, title, value, percents, color, description)
// This function is called when the viewer rolls over the slice. It returns chart_id, the 
// sequential number of the slice (index), the title, value, percent value, 
// color and description.
function amSliceOver(chart_id, index, title, value, percents, color, description){
  document.getElementById("sliceover").value = index;
}

// FIND OUT WHEN THE MOUSE WAS MOVED AWAY FROM A SLICE ///////////////////////////////////
// amSliceOut(chart_id)
// This function is called when the viewer rolls away from the slice.
function amSliceOut(chart_id){
  document.getElementById("sliceover").value = "";
} 
  

// EXPORT AS IMAGE ///////////////////////////////////////////////////////////////////////
// flashMovie.exportImage([file_name]) 
// This function will start the process of exporting the chart as an image. The file_name
// is a name of a file to which image data will be posted (files provided in the download 
// package are export.php and export.aspx). The file_name is optional and can be set in 
// the <export_as_image><file> setting.

function exportImage() {
	 
  flashMovie.exportImage('export.php');  
}
</script>
 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Management/allFeesDetailsReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: allFeesDetailsReport.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface/Management
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/02/09    Time: 4:19p
//Updated in $/LeapCC/Interface/Management
//Updated with random number genration for flash report
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Interface/Management
//Inital checkin
?>