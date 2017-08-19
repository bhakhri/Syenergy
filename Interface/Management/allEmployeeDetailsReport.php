<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report of Employee for management.
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
define('MODULE','ManagementEmployeeInfo');
define('ACCESS','view'); 
UtilityManager::ifManagementNotLoggedIn();
//require_once(BL_PATH . "/Management/scFetchEmployeeData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Detail Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js"); 

$flashPath = IMG_HTTP_PATH."/ampie.swf";
?> 
<script language="javascript">
x = Math.random() * Math.random();
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

	 
	var url = '<?php echo HTTP_LIB_PATH;?>/Management/getEmployeeGraph.php';
	var pars = 'searchStudent='+document.getElementById('searchStudent').value;
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous:false,
		 onCreate: function(){
			 showWaitDialog();
		 },
		onSuccess: function(transport){
		hideWaitDialog();
		
		if("rolewise" == trim(transport.responseText)) {
			
			showRolePieResults();
		}
		if("teaching" == trim(transport.responseText)) {
			
			showTeachingPieResults();
		}
		if("marital" == trim(transport.responseText)) {
			
			showMaritalPieResults();
		}
		if("city" == trim(transport.responseText)) {
			
			showCityPieResults();
		}
		if("state" == trim(transport.responseText)) {
			
			showStatePieResults();
		}
		if("designation" == trim(transport.responseText)) {
			
			showDesignationPieResults();
		}
		if("branch" == trim(transport.responseText)) {
			
			showBranchPieResults();
		}
		if("gender" == trim(transport.responseText)) {
			
			showGenderPieResults();
		}
	},
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function showRolePieResults() {

	 
	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "900", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable("additional_chart_settings","<settings><pie><y>500</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeRoleWiseData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showTeachingPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeTeachingData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showMaritalPieResults() {

	   var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeMaritalData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showCityPieResults() {

	   var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "900", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><y>450</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeCityData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showStatePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "700", "500", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><y>350</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeStateDetailData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showDesignationPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "500", "600", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><y>350</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeDesignationData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showBranchPieResults() {

	 var so = new SWFObject("<?php echo $flashPath?>", "ampie", "600", "700", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><y>300</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeBranchData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showGenderPieResults() {

	 var so = new SWFObject("<?php echo $flashPath?>", "ampie", "500", "600", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");  
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart	
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/pieChartSetting.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><y>200</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/employeeGenderData.xml?t="+x));
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
  
  if(roleArr[1]=="role"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/roleWiseEmployeePrint.php?roleId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }

  if(roleArr[1]=="Teaching"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/teachingEmployeePrint.php?teachId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }

  if(roleArr[1]=="marital"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/maritalEmployeePrint.php?maritalId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }

  if(roleArr[1]=="city"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/cityEmployeePrint.php?cityId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }

  if(roleArr[1]=="state"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/stateEmployeePrint.php?stateId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }

  if(roleArr[1]=="designation"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/designationEmployeePrint.php?designationId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }

   if(roleArr[1]=="gender"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/genderEmployeePrint.php?genderId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }

   if(roleArr[1]=="branch"){
	 
	path='<?php echo UI_HTTP_PATH;?>/Management/branchEmployeePrint.php?branchId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }

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
  flashMovie.exportImage('ampie/export.php');  
}
</script>
 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Management/allEmployeeDetailsReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: allEmployeeDetailsReport.php $
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