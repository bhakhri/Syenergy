<?php
//-------------------------------------------------------
// Purpose: To generate assign subject to class from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransferredMarksReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Transferred Marks Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
 
?> 
<script type="text/javascript" src="<?php echo JS_PATH;?>/swfobject.js"></script>
<script language="javascript">
 

function showTestGraph(frm) {

	var fieldsArray = new Array(new Array("studentClass","<?php echo SELECT_CLASS_FOR_REPORT;?>"), new Array("subjectType","<?php echo SELECT_SUBJECT_TYPE_REPORT;?>"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		 
    }
	
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/scGetInternalMarksGraph.php';
	var pars = 'classId='+document.getElementById('studentClass').value+'&subjectTypeId='+document.getElementById('subjectType').value+'&subjectId='+document.getElementById('subject').value;
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
		if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
			
			showTestBarChartResults();
			document.getElementById("nameRow3").style.display='';
			document.getElementById("nameRow2").style.display='';
			document.getElementById("showTitle").style.display='';  
		}
		else{
		
			document.getElementById('resultsDiv1').innerHTML='No Data Found';
		}
	},
	onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	
	
}
function showTestBarChartResults() {
	//document.getElementById("resultRow").style.display='';
	form = document.listForm;
	 x = Math.random() * Math.random();
	var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "900", "420", "5", "#FFFFFF");
	so.addVariable("path", "./");
	so.addParam("wmode", "transparent");
	so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart	
	so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Student Count ---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Marks---></text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Student Consolidated Detail: "+form.studentClass.options[form.studentClass.selectedIndex].text+"   ("+form.subjectType.options[form.subjectType.selectedIndex].text+")</text><text_size>18</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values><plot_area><margins><left>55</left></margins></plot_area></settings>");
	so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/barChartSetting3.xml"));
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/transferredStackData.xml?t="+x));
	  
	  so.write("resultsDiv1");
}

function autoPopulate(val,type,frm) {
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   if(frm=="Add"){
       if(type=="subject"){
            document.listForm.subject.options.length=0;
            var objOption = new Option("ALL","");
            document.listForm.subject.options.add(objOption); 
       }
   }

   type = "subject";
       
    new Ajax.Request(url,
    {
	     method:'post',
	     parameters: {type: type,id: val},
         onCreate: function () {
             showWaitDialog(true);
         },
	     onSuccess: function(transport){

			    hideWaitDialog(true);
                //alert(transport.responseText);
			    j = trim(transport.responseText).evalJSON();   
			    len = j.subjectArr.length;
			    document.listForm.subject.length = null;
			    // add option Select initially
			    addOption(document.listForm.subject, '', 'ALL');
			    for(i=0;i<len;i++) { 
			     addOption(document.listForm.subject, j.subjectArr[i].subjectId, j.subjectArr[i].subjectCode);
			    }

			   /* len = j.groupArr.length;
			    document.listForm.studentGroup.length = null;
			    // add option Select initially
			    addOption(document.listForm.studentGroup, '', 'Select');
			    for(i=0;i<len;i++) { 
					
					addOption(document.listForm.studentGroup, j.groupArr[i].groupId, j.groupArr[i].groupShort);
				}
				*/
				len = j.typeArr.length;
			    document.listForm.subjectType.length = null;
			    // add option Select initially
			    addOption(document.listForm.subjectType, '', 'Select');
			    for(i=0;i<len;i++) { 
					
					addOption(document.listForm.subjectType, j.typeArr[i].subjectTypeId, j.typeArr[i].subjectTypeName);
				}

			  
	     },
	     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 
}
function showData(interval,subjectCode,classId,subjectType){

	//alert(interval);
	//alert(subjectCode);
	//alert(classId);
	//alert(subjectType);

	var name = document.getElementById('studentClass');
	var name1 = document.getElementById('subject');
	path='<?php echo UI_HTTP_PATH;?>/transferredMarksPrint.php?classId='+classId+'&interval='+interval+'&subjectCode='+subjectCode+'&subjectType='+subjectType+'&className='+name.options[name.selectedIndex].text+'&subjectName='+name1.options[name1.selectedIndex].text;	
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=600, height=500, top=100,left=50");
}

function printReport() {
	showWaitDialog(true);
	exportImage();
}


function printActualReport(imageName) {

	hideWaitDialog(true);

	var name = document.getElementById('studentClass');
	var name1 = document.getElementById('subjectType');
	var name2 = document.getElementById('subject');
	
	var pars = 'classId='+document.getElementById('studentClass').value+'&subjectTypeId='+document.getElementById('subjectType').value+'&subjectId='+document.getElementById('subject').value+'&imageName='+imageName+'&className='+name.options[name.selectedIndex].text+'&typeName='+name1.options[name1.selectedIndex].text+'&subjectName='+name2.options[name2.selectedIndex].text;

	path='<?php echo UI_HTTP_PATH;?>/transferredMarksReportPrint.php?'+pars;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=950,height=600");
}

/*
function printReport() {

	var pars = 'classId='+document.getElementById('studentClass').value+'&subjectTypeId='+document.getElementById('subjectType').value+'&subjectId='+document.getElementById('subject').value;

	path='<?php echo UI_HTTP_PATH;?>/transferredMarksReportPrint.php?'+pars;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=750,height=500");
}*/
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
    require_once(TEMPLATES_PATH . "/StudentReports/listTranferredMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: tranferredMarksReport.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:54p
//Updated in $/LeapCC/Interface
//Fixed bug no 1441
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 09-08-21   Time: 12:50p
//Updated in $/LeapCC/Interface
//Added ACCESS right DEFINE in these modules
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/08/09    Time: 12:03p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/14/09    Time: 2:36p
//Updated in $/LeapCC/Interface
//Updated the query to generate print reports
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/01/09    Time: 10:44a
//Updated in $/LeapCC/Interface
//Updated print report
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 4/30/09    Time: 6:58p
//Updated in $/LeapCC/Interface
//Implemented print report
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:04p
//Updated in $/LeapCC/Interface
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/22/09    Time: 10:22a
//Created in $/LeapCC/Interface
//Intial checkin
?>