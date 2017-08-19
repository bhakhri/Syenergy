<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentDemographics');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

//for displaying notices,events etc
require_once(BL_PATH . "/Index/demographicList.php");
$flashPath = IMG_HTTP_PATH."/ampie.swf";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Demographics </title>

<?php require_once(TEMPLATES_PATH .'/jsCssHeader.php'); ?>
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

	var url = '<?php echo HTTP_LIB_PATH;?>/Index/getCustomGraph.php';
	var pars = 'searchStudent='+document.getElementById('searchStudent').value;
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous:false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
		hideWaitDialog(true);
		if("batch" == trim(transport.responseText)) {

			showBatchPieResults();
		}
		if("category" == trim(transport.responseText)) {

			showCategoryPieResults();
		}
		if("hostel" == trim(transport.responseText)) {

			showHostelPieResults();
		}
		if("state" == trim(transport.responseText)) {

			showStatePieResults();
		}
		if("nationality" == trim(transport.responseText)) {

			showNationalityPieResults();
		}
		if("busroute" == trim(transport.responseText)) {

			showBusRoutePieResults();

		}
		if("busstop" == trim(transport.responseText)) {

			showBusStopPieResults();
		}
		if("institute" == trim(transport.responseText)) {

			showInstitutePieResults();
		}

	},
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function showBatchPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentBatchData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showCategoryPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentQuotaData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showHostelPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentHostelData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showStatePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentStateData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}
function showNationalityPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentNationalityData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}
function showBusStopPieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentBusStopData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}
function showBusRoutePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentBusRouteData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}
function showInstitutePieResults() {

	  var so = new SWFObject("<?php echo $flashPath?>", "ampie", "220", "180", "8", "#FFFFFF");
	  so.addVariable("path", "ampie/");
	  so.addVariable("chart_id", "ampie"); // if you have more then one chart in one page, set different chart_id for each chart
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/pieChartSetting1.xml"));
	  so.addVariable ("additional_chart_settings", "<settings><pie><x>110</x><y>100</y></pie></settings>");
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/studentInstituteData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("resultsDiv");
}

function showData(id)
{
	//alert(id);
	path='<?php echo UI_HTTP_PATH;?>/branchWiseStudentPrint.php?branchId='+id;
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
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
  //alert(description);
  roleArr = description.split("~");
  //alert('i m clicked for '+chart_id+', '+index+', '+title+', '+value+', '+percents+', '+color+', '+description);
  /*alert(roleArr[1]);
  if(roleArr[1]=="branch"){

	path='<?php echo UI_HTTP_PATH;?>/Management/branchWiseStudentPrint.php?branchId='+roleArr[0];
	//alert(path);
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }*/
//alert(roleArr[1]);
  if(roleArr[1]=="degree"){

	path='<?php echo UI_HTTP_PATH;?>/Management/degreeWiseStudentPrint.php?degreeId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="batch"){
	path='<?php echo UI_HTTP_PATH;?>/Management/batchWiseStudentPrint.php?batchId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="studyperiod"){

	path='<?php echo UI_HTTP_PATH;?>/Management/periodWiseStudentPrint.php?studyperiodId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="quota"){

	path='<?php echo UI_HTTP_PATH;?>/Management/quotaWiseStudentPrint.php?quotaId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="hostel"){

	path='<?php echo UI_HTTP_PATH;?>/Management/hostelWiseStudentPrint.php?hostelId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="hosteldetail"){

	path='<?php echo UI_HTTP_PATH;?>/Management/hostelDetailStudentPrint.php?hostelId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="city"){

	path='<?php echo UI_HTTP_PATH;?>/Management/cityWiseStudentPrint.php?cityId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="state"){

	path='<?php echo UI_HTTP_PATH;?>/Management/stateWiseStudentPrint.php?stateId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="nationality"){

	path='<?php echo UI_HTTP_PATH;?>/Management/nationalityWiseStudentPrint.php?nationalityId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="busroute"){
	path='<?php echo UI_HTTP_PATH;?>/Management/busRouteWiseStudentPrint.php?busRouteId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="busstop"){

	path='<?php echo UI_HTTP_PATH;?>/Management/busStopWiseStudentPrint.php?busStopId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="gender"){

	path='<?php echo UI_HTTP_PATH;?>/Management/genderWiseStudentPrint.php?genderId='+roleArr[0];
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
  }
  if(roleArr[1]=="institute"){

	path='<?php echo UI_HTTP_PATH;?>/Management/instituteWiseStudentPrint.php?instituteId='+roleArr[0];
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
	require_once(TEMPLATES_PATH . "/Index/dashBoardContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
