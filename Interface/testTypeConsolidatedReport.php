<?php 
//-------------------------------------------------------
//  This File contains consolidated report for the student.
//
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestTypeDistributionReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Type Distribution Consolidated Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeJS("swfobject.js");
?>

<script language="javascript">


function validateAddForm(frm) {

    var fieldsArray = new Array(new Array("timeTable","<?php echo SELECT_TIME_TABLE;?>"),new Array("degree","<?php echo SELECT_DEGREE;?>"),new Array("subjectTypeId","<?php echo SELECT_SUBJECT_TYPE;?>"),new Array("testCategoryId","<?php echo SELECT_TEST_TYPE;?>") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
       if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }

	showReport();
	
}

function showReport() {

	form = document.testWiseMarksReportForm;
	var timeTable = form.timeTable.value;
	var degree	  = form.degree.value;
	var subjectTypeId = form.subjectTypeId.value;
	var subjectId = form.subjectId.value;
	var groupId   = form.groupId.value;
	var testCategoryId = form.testCategoryId.value;

	
	var name = document.getElementById('subjectTypeId'); 
	var name1 = document.getElementById('groupId'); 
	var name2 = document.getElementById('testCategoryId');
	
	 
	var pars = 'timeTable='+timeTable+'&degree='+degree+'&subjectTypeId='+subjectTypeId+'&subjectId='+subjectId+'&groupId='+groupId+'&testCategoryId='+testCategoryId+'&typeName='+name.options[name.selectedIndex].text+'&groupName='+name1.options[name1.selectedIndex].text+'&categoryName='+name2.options[name2.selectedIndex].text;
	 
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initTestTypeConsolidatedReport.php';
	//var pars = 'class1=';

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
		 returnString = (trim(transport.responseText));
		 if("<?php echo SUCCESS;?>" == trim(returnString)) {
			
			showTestBarChartResults();
		 }
	},
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	document.getElementById("nameRow2").style.display='';
}	
function showTestBarChartResults() {
	//document.getElementById("resultRow").style.display='';
	form = document.testWiseMarksReportForm;
	// form = document.listForm;
	 x = Math.random();
	var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "900", "380", "5", "#FFFFFF");
	so.addVariable("path", "./");
	so.addParam("wmode", "transparent");
	so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart	
	so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Test Count ---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Subject---></text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Student Consolidated Detail:" +form.degree.options[form.degree.selectedIndex].text+" ("+form.subjectTypeId.options[form.subjectTypeId.selectedIndex].text+") For Group "+form.groupId.options[form.groupId.selectedIndex].text+"</text><text_size>12</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left><bottom>55</bottom></margins></plot_area></settings>");
	so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/testTypeChartSetting.xml"));
	so.addVariable("data_file", encodeURIComponent("../Templates/Xml/testTypeConsolidatedData.xml?t="+x));
	 
	  so.write("results1");
	   
}

function resetStudyPeriod() {
	document.testWiseMarksReportForm.subjectTypeId.selectedIndex = 0;
}

function printReport() {
	showWaitDialog(true);
	exportImage();
}


function printActualReport(imageName) {

	 
	hideWaitDialog(true);

	form = document.testWiseMarksReportForm;
	var timeTable = form.timeTable.value;
	var degree	  = form.degree.value;
	var subjectTypeId = form.subjectTypeId.value;
	var subjectId = form.subjectId.value;
	var groupId   = form.groupId.value;
	var testCategoryId   = form.testCategoryId.value;
	 
	
	var name = document.getElementById('subjectTypeId'); 
	var name1 = document.getElementById('groupId'); 
	var name2 = document.getElementById('testCategoryId');
	var name3 = document.getElementById('subjectId');
	var name4 = document.getElementById('degree'); 
	var pars = 'timeTable='+timeTable+'&degree='+degree+'&subjectTypeId='+subjectTypeId+'&subjectId='+subjectId+'&groupId='+groupId+'&testCategoryId='+testCategoryId+'&typeName='+name.options[name.selectedIndex].text+'&groupName='+name1.options[name1.selectedIndex].text+'&categoryName='+name2.options[name2.selectedIndex].text+'&subjectName='+name3.options[name3.selectedIndex].text+'&className='+name4.options[name4.selectedIndex].text+'&imageName='+imageName;
 
	path='<?php echo UI_HTTP_PATH;?>/testTypeWiseConsolidatedReportPrint.php?'+pars;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=950, height=600");
}
function showData(subjectId,classId,groupId,testTypeCategoryId,groupName) {
 
	//alert(subjectId);
	//alert(classId);
	//alert(groupId);
	//alert(testTypeCategoryId);

	var selected=0;
    var studentCheck='';
    
	var name = document.getElementById('timeTable');
	var name1 = document.getElementById('degree');
	var name2 = document.getElementById('testCategoryId');
	 
	var name4 = document.getElementById('subjectId');
	
	var pars = 'timeTable='+document.getElementById('timeTable').value+'&degree='+document.getElementById('degree').value+'&testCategoryId='+document.getElementById('testCategoryId').value+'&timeName='+name.options[name.selectedIndex].text+'&className='+name1.options[name1.selectedIndex].text+'&categoryName='+name2.options[name2.selectedIndex].text+'&subjectId='+subjectId+'&groupId='+groupId+'&subjectName='+name4.options[name4.selectedIndex].text+'&groupName='+groupName;

	path='<?php echo UI_HTTP_PATH;?>/testTypeWiseReportPrint.php?'+pars;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=650,height=400,top=100,left=100");
	
}
/*
function printReport() {
	form = document.testWiseMarksReportForm;
	path='<?php echo UI_HTTP_PATH;?>/testWiseMarksReportPrint.php?class1='+form.degree.value+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900");
}
*/
function hideResults() {
	//document.getElementById("resultRow").style.display='none';
	//document.getElementById('nameRow').style.display='none';
	//document.getElementById('nameRow2').style.display='none';
}

function getLabelClass(){

	form = document.testWiseMarksReportForm;
	var timeTable = form.timeTable.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetLabelClass.php';
	var pars = 'timeTable='+timeTable;
	document.testWiseMarksReportForm.degree.length = null; 
	addOption(document.testWiseMarksReportForm.degree, '', 'Select');
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
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				 
				document.testWiseMarksReportForm.degree.length = null;
				addOption(document.testWiseMarksReportForm.degree, '', 'Select');
				if (len > 0) {
					//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
					addOption(document.testWiseMarksReportForm.degree, j[i].classId, j[i].className);
				}
				// now select the value
				//document.testWiseMarksReportForm.groupId.value = j[0].groupId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}
function getClassSubjects() {
	 
	form = document.testWiseMarksReportForm;
	var degree = form.degree.value;
	document.testWiseMarksReportForm.groupId.length = null; 
	addOption(document.testWiseMarksReportForm.groupId, '', 'ALL'); 
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetSubjectType.php';
	var pars = 'class1='+degree;
	if (degree == '') {
		document.testWiseMarksReportForm.subjectId.length = null;
		document.testWiseMarksReportForm.groupId.length = null;
		addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
		addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
		return false;
	}
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
				 
				j = trim(transport.responseText).evalJSON();   
				len = j.subjectArr.length;
				document.testWiseMarksReportForm.subjectId.length = null;
			    // add option Select initially
			    addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
			    for(i=0;i<len;i++) { 
			    
					addOption(document.testWiseMarksReportForm.subjectId, j.subjectArr[i].subjectId, j.subjectArr[i].subjectCode);
			    }
 
				len = j.typeArr.length;
				document.testWiseMarksReportForm.subjectTypeId.length = null;
			    // add option Select initially
			    addOption(document.testWiseMarksReportForm.subjectTypeId, '', 'Select');
			    for(i=0;i<len;i++) { 
			    
					addOption(document.testWiseMarksReportForm.subjectTypeId, j.typeArr[i].subjectTypeId, j.typeArr[i].subjectTypeName);
			    }
				/*
				document.testWiseMarksReportForm.subjectId.length = null;
				addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
				 
				for(i=0;i<len;i++) { 
					addOption(document.testWiseMarksReportForm.subjectId, j[i].subjectId, j[i].subjectCode);
				}
				*/ 
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function getTestTypeData() {
	form = document.testWiseMarksReportForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetTestType.php';
	var subjectTypeId = form.subjectTypeId.value;
	
	
	var pars = 'subjectTypeId='+subjectTypeId;
	
	form.testCategoryId.length = null;
	addOption(form.testCategoryId, '', 'Select');

	if (form.timeTable.value=='') {
		messageBox("Please enter timeTable");
		return false;
	}
	if(form.degree.value=='') {
		messageBox("Please enter degree");
		return false;
	}
	if(form.subjectTypeId.value==''){
		messageBox("Please enter subjectTypeId");
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.testCategoryId.length = null;
			
			addOption(form.testCategoryId, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.testCategoryId, j[i].testTypeCategoryId, j[i].testTypeName);
			}
			// now select the value
			//form.testCategoryId.value = j[0].testCategoryId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	
}
function getTypeGroups() {

	form = document.testWiseMarksReportForm;
	var degree = form.degree.value;
	var subjectTypeId = form.subjectTypeId.value;
	 
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetSubjectTypeClassGroup.php';
	var pars = 'class1='+degree+'&subjectTypeId='+subjectTypeId;
	
	document.testWiseMarksReportForm.groupId.length = null; 
	addOption(document.testWiseMarksReportForm.groupId, '', 'ALL'); 
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
				//var j = eval('(' + transport.responseText + ')');
				j= trim(transport.responseText).evalJSON();
				len =j.subjectinfo.length;
				//alert(len);
				document.testWiseMarksReportForm.subjectId.length = null;
				addOption(document.testWiseMarksReportForm.subjectId, '', 'ALL');  
				if (len > 0) {
					//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 

					addOption(document.testWiseMarksReportForm.subjectId, j.subjectinfo[i].subjectId, j.subjectinfo[i].subjectCode);
				}
				len =0;
				len =j.groupinfo.length;
				//alert(len);
				document.testWiseMarksReportForm.groupId.length = null;
				addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');  
				if (len > 0) {
					//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 

					addOption(document.testWiseMarksReportForm.groupId, j.groupinfo[i].groupId, j.groupinfo[i].groupName);
				}
				// now select the value
				//document.testWiseMarksReportForm.groupId.value = j[0].groupId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function getGroups() {
	form = document.testWiseMarksReportForm;
	var degree = form.degree.value;
	var subjectId = form.subjectId.value;
	if (degree == '' || subjectId == '') {
		return false;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetSubjectTestGroups.php';
	var pars = 'class1='+degree+'&subjectId='+subjectId;
	document.testWiseMarksReportForm.groupId.length = null; 
	addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
	if (degree == '') {
		document.testWiseMarksReportForm.groupId.length = null;
		addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
		return false;
	}
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
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				document.testWiseMarksReportForm.groupId.length = null;
				addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
				if (len > 0) {
					//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
					addOption(document.testWiseMarksReportForm.groupId, j[i].groupId, j[i].groupName);
				}
				// now select the value
				//document.testWiseMarksReportForm.groupId.value = j[0].groupId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

 
</script>
<script type="text/javascript">
var flashMovie;
//////////////////////////////////////////////////////////////////////////////////////////
// Functions that control the chart //////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

// SET DATA //////////////////////////////////////////////////////////////////////////////
// flashMovie.setData(data, rebuild)
// This function can be used for setting the chart's data on the fly. 
// Data must be in CSV or XML form, as per the <data_type> setting.
// The "rebuild" parameter is optional and can be "true" or "false". "false" means that 
// the new data will not be applied right after this function is called.   

function setData() {
  // gets data from the text area with id "data"
  var data = document.getElementById("data").value;
  // sets data
  flashMovie.setData(data);
}

// APPEND DATA ///////////////////////////////////////////////////////////////////////////
// flashMovie.appendData(data[, remove_count])
// This function can be used to append new data to the existing dataset. 
// The data must be in CSV or XML form, as per the <data_type> setting. 
// The remove_count variable is optional and sets the number of data points
// that should be removed from the beginning of dataset.
function appendData() {
  // gets data from the text area with id "data"
  var data = document.getElementById("data").value;
  // appends data
  flashMovie.appendData(data, 0);
}

// SET SETTINGS //////////////////////////////////////////////////////////////////////////
// flashMove.setSettings(settings[, rebuild])
// This function can be used to set some part or all the settings. The settings should be
// in XML format, inside <settings></settings>. The "rebuild" parameter is optional and 
// can be "true" or "false". "false" means that the new settings will not be applied right
// after this function is called. They will can be applied using flashMovie.rebuild()
// function or by adding some more setings with the "rebuild" set to "true". The default
// value of "rebuild" is "true"  
function setSettings() {
  var settings = document.getElementById("settings").value;
  flashMovie.setSettings(settings, true);
}   

// REBUILD CHART /////////////////////////////////////////////////////////////////////////
// flashMovie.rebuild();
// This function might be used to rebuild the chart after several portions of settings were
// set using setSettings(settings, rebuild) function, with the rebuild set to false
function rebuild() {
  flashMovie.rebuild();
}

// RELOAD DATA ///////////////////////////////////////////////////////////////////////////
// flashMove.reloadData([file_name])
// This function will reload the data. The file_name variable is optional, if you do not
// set it here, data from the original file will be reloaded.       
function reloadData() {
  flashMovie.reloadData(); 
}

// RELOAD SETTINGS ///////////////////////////////////////////////////////////////////////
// flashMovie.reloadSettings([file_name])
// This function will reload the settings. The file_name variable is optional, if you do
// not set it here, settings from the original file will be reloaded. 
function reloadSettings() {
  flashMovie.reloadSettings();
}

// RELOAD ALL ////////////////////////////////////////////////////////////////////////////
// flashMovie.reloadAll([data_file_name][,settings_file_name])
// This function will reload both data and settings. The names of the files are optional. 
// If you do not set them, the original file names will be used.
function reloadAll() {
  flashMovie.reloadAll();
}

// SET PARAM /////////////////////////////////////////////////////////////////////////////
// flashMovie.setParam(param, value)
// This function lets you change a single setting. The parameter names are formed using 
// the section name and the parameter name, separated with a period. For example: 
// background.alpha or labels.label[1].text 
function setParam() {
  // gets param name from a field with id "param"
  var param = document.getElementById("param").value;
  // gets param value from a field with id "value"
  var value = document.getElementById("value").value;
  // sets param
  flashMovie.setParam(param, value);
}
      
// GET PARAM /////////////////////////////////////////////////////////////////////////////
// flashMovie.getParam(param)
// This function will ask Flash to return the value of a setting. The parameter name is
// formed in the same way as the setParam function (described above). When you call this
// function to return the setting value, Flash will return the value by calling the 
// amReturnParam(chart_id, param_value) function
function getParam() {
  // get the param name from a field with id "getparam"
  var param = document.getElementById("getparam").value;            
  flashMovie.getParam(param);
} 

// GET DATA //////////////////////////////////////////////////////////////////////////////
// flashMovie.getData()
// This function will ask Flash to return the whole data. When you call this function to
// return the data, Flash will call the amReturnData(chart_id, data) function.
function getData() {
  flashMovie.getData();
}   
      
// GET SETTINGS //////////////////////////////////////////////////////////////////////////
// flashMovie.getSettings()
// This function will ask Flash to return the whole settings XML. When you call this 
// function to return the settings, Flash will call the 
// amReturnSettings(chart_id, settings) function. 
function getSettings() {
  flashMovie.getSettings();
}   
      
// EXPORT AS IMAGE ///////////////////////////////////////////////////////////////////////
// flashMovie.exportImage([file_name]) 
// This function will start the process of exporting the chart as an image. The file_name
// is a name of a file to which image data will be posted (files provided in the download 
// package are export.php and export.aspx). The file_name is optional and can be set in 
// the <export_as_image><file> setting.
function exportImage() {


  flashMovie.exportImage();  
 
 
}

// PRINT /////////////////////////////////////////////////////////////////////////////////
// flashMovie.print()
// This function will print the chart. Use this print function if you don't have any
// values rotated by 90 degrees, also if you don't have a custom bitmap background.
function print(){
  flashMovie.print();
}
      
// PRINT AS BITMAP ///////////////////////////////////////////////////////////////////////
// flashMovie.printAsBitmap()
// Use it if you have values rotated by 90 degrees and/or a custom bitmap background.
function printAsBitmap(){
  flashMovie.printAsBitmap();
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
//  document.getElementById("chartfinished").value = chart_id;           
}   

// PROCESS COMPLETED //////////////////////////////////////////////////////////////////////////    
// amProcessCompleted(chart_id, process_name)
// This function is called when the chart finishes doing some task triggered by another 
// JavaScript function 
function amProcessCompleted(chart_id, process_name){
  document.getElementById("processcompleted").value = process_name;  

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

// FIND WHICH COLUMN/BULLET WAS CLICKED //////////////////////////////////////////////////
// amClickedOnBullet(chart_id, graph_index, value, series, url, description)
// This function is called when the viewer clicks on a graph bullet. It returns the
// sequential number of a graph (graph_index), the value of the data point (value),
// the series value (series), the URL and the description attributes.
function amClickedOnBullet(chart_id, graph_index, value, series, url, description){
  document.getElementById("column_clicked").value = value;
}          
// FIND WHICH COLUMN/BULLET WAS HOVERED /////////////////////////////////////////////////
// amRolledOverBullet(chart_id, graph_index, value, series, url, description)
// This function is called when the viewer rolls over a graph bullet. It returns the 
// sequential number of a graph (graph_index), the value of the data point (value), 
// the series value (series), the URL and the description attributes.
function amRolledOverBullet(chart_id, graph_index, value, series, url, description){
  document.getElementById("column_hover").value = value;
}

// RETURN IMAGE DATA /////////////////////////////////////////////////////////////////////
// amReturnImageData(chart_id, data)
// This function is called when the export to image process is finished and might be used
// as alternative way to get image data (instead of posting it to some file)
function amReturnImageData(chart_id, data){
  // your own functions here
 
	 
	document.addForm.imageData.value = data;
	document.addForm.submit();
}      

// ERROR /////////////////////////////////////////////////////////////////////////////////
// amError(chart_id, message)
// This function is called when an error occurs, such as no data, or file not found.
function amError(chart_id, message){
  alert(message);
}      
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listTestTypeConsolidatedReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
//$History: testTypeConsolidatedReport.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 2/03/10    Time: 6:06p
//Updated in $/LeapCC/Interface
//resolved issue 0002642,0002689,0002015,0002666
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 10/29/09   Time: 10:01a
//Updated in $/LeapCC/Interface
//fixed query error
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-08-24   Time: 1:05p
//Updated in $/LeapCC/Interface
//Updated with Institute Wise Checks including ACCESS rights DEFINE
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 12:03p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 5/15/09    Time: 3:55p
//Updated in $/LeapCC/Interface
//Updated test type distribution report according to all subjects and all
//groups
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/14/09    Time: 2:36p
//Updated in $/LeapCC/Interface
//Updated the query to generate print reports
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/08/09    Time: 5:51p
//Created in $/LeapCC/Interface
//Intail checkin: Added test type distribution 
?>