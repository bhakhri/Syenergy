<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
//
// Author :Rajeev Aggarwal
// Created on : 27-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ManagementAdmissionInfo');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
//require_once(BL_PATH . "/Management/scFetchStudentData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Detail Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js"); 

$flashPath = IMG_HTTP_PATH."/amcolumn.swf";
?> 
<script language="javascript">

function getSearch(){

	document.getElementById('resultsDiv').innerHTML="";
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
	var url = '<?php echo HTTP_LIB_PATH;?>/Management/getStudentAdmission.php';
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
		
		if("city" == trim(transport.responseText)) {
			
			showCityStackedResults();
		}
		if("branch" == trim(transport.responseText)) {
			
			showBranchStackedResults();
		}
		if("degree" == trim(transport.responseText)) {
			
			showDegreeStackedResults();
		}
		if("batch" == trim(transport.responseText)) {
			
			showBatchStackedResults();
		}
		if("category" == trim(transport.responseText)) {
			
			showCategoryStackedResults();
		}
		if("hostel" == trim(transport.responseText)) {
			
			showHostelStackedResults();
		}
		if("gender" == trim(transport.responseText)) {
			
			showGenderStackedResults();
		}
	},
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function showCityStackedResults() {

	//alert("<?php echo $flashPath?>");
	 var so = new SWFObject("<?php echo $flashPath?>", "amcolumn", "650", "380", "8", "#FFFFFF");
	 so.addVariable("path", "ampie/");  
	 so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
	 so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentCityAdmission.xml?t="+x));
	 so.addVariable("preloader_color", "#999999");
	 so.write("resultsDiv");
}

function showBranchStackedResults() {

	//alert("<?php echo $flashPath?>");
	 var so = new SWFObject("<?php echo $flashPath?>", "amcolumn", "650", "620", "8", "#FFFFFF");
	 so.addVariable("path", "ampie/");  
	 so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
	 so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentBranchAdmission.xml?t="+x));
	 so.addVariable("preloader_color", "#999999");
	 so.write("resultsDiv");
}

function showDegreeStackedResults() {

	 var so = new SWFObject("<?php echo $flashPath?>", "amcolumn", "650", "620", "8", "#FFFFFF");
	 so.addVariable("path", "ampie/");  
	 so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
	 so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentDegreeAdmission.xml?t="+x));
	 so.addVariable("preloader_color", "#999999");
	 so.write("resultsDiv");
}

function showBatchStackedResults() {

	 var so = new SWFObject("<?php echo $flashPath?>", "amcolumn", "650", "860", "8", "#FFFFFF");
	 so.addVariable("path", "ampie/");  
	 so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
	 so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentBatchAdmission.xml?t="+x));
	 so.addVariable("preloader_color", "#999999");
	 so.write("resultsDiv");
}

function showCategoryStackedResults() {

	 var so = new SWFObject("<?php echo $flashPath?>", "amcolumn", "650", "460", "8", "#FFFFFF");
	 so.addVariable("path", "ampie/");  
	 so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
	 so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentCategoryAdmission.xml?t="+x));
	 so.addVariable("preloader_color", "#999999");
	 so.write("resultsDiv");
}

function showHostelStackedResults() {

	 var so = new SWFObject("<?php echo $flashPath?>", "amcolumn", "650", "600", "8", "#FFFFFF");
	 so.addVariable("path", "ampie/");  
	 so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
	 so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentHostelAdmission.xml?t="+x));
	 so.addVariable("preloader_color", "#999999");
	 so.write("resultsDiv");
}

function showGenderStackedResults() {

	 var so = new SWFObject("<?php echo $flashPath?>", "amcolumn", "650", "460", "8", "#FFFFFF");
	 so.addVariable("path", "ampie/");  
	 so.addVariable("settings_file", encodeURIComponent("../../Templates/Xml/stackSettings.xml"));
	 so.addVariable("data_file", encodeURIComponent("../../Templates/Xml/studentGenderAdmission.xml?t="+x));
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

function showData(graphType){

	//alert(graphType);
	graphtypeArr = graphType.split('~');
	selectedYear = graphtypeArr[0];
	selectedTo   = graphtypeArr[1];
	selectedType = graphtypeArr[2];

	if(selectedType=='branch'){
		
		path='<?php echo UI_HTTP_PATH;?>/Management/branchYearWiseStudentPrint.php?branchCode='+selectedTo+'&selectedYear='+selectedYear;
		window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
	}

	if(selectedType=='degree'){
		
		path='<?php echo UI_HTTP_PATH;?>/Management/degreeYearWiseStudentPrint.php?branchCode='+selectedTo+'&selectedYear='+selectedYear;
		window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
	}

	if(selectedType=='batch'){
		
		path='<?php echo UI_HTTP_PATH;?>/Management/batchYearWiseStudentPrint.php?branchCode='+selectedTo+'&selectedYear='+selectedYear;
		window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
	}
	if(selectedType=='category'){
		
		path='<?php echo UI_HTTP_PATH;?>/Management/categoryYearWiseStudentPrint.php?branchCode='+selectedTo+'&selectedYear='+selectedYear;
		window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
	}
	if(selectedType=='hostel'){
		
		path='<?php echo UI_HTTP_PATH;?>/Management/hostelYearWiseStudentPrint.php?branchCode='+selectedTo+'&selectedYear='+selectedYear;
		window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
	}
	if(selectedType=='gender'){
		
		path='<?php echo UI_HTTP_PATH;?>/Management/genderYearWiseStudentPrint.php?branchCode='+selectedTo+'&selectedYear='+selectedYear;
		window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
	}
}

</script>
 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Management/listAdmissionDetailsReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: admissionDetailsReport.php $
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
//User: Rajeev       Date: 12/27/08   Time: 5:30p
//Created in $/LeapCC/Interface/Management
//intial checkin
?>