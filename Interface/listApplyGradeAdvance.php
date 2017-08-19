<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in attendanceMissedReport Form
//
// Author :Ajinder Singh
// Created on : 23-oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApplyGradesAdvance');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Student/initListGrades.php");//required. DONT REMOVE THIS LINE
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Apply Grade(Advanced)</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>
<script type="text/javascript" src="<?php echo JS_PATH;?>/swfobject.js"></script>
<script language="javascript">
var flagCheck=0; 
var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), 
                               new Array('subjectCode','Course','width=25%  align=left',' align=left',false), 
                               new Array('sectionName','Section','width="20%"  align=left',' align=left',false), 
                               new Array('testName','Test Name','width="20%"  align=left',' align=left',false)); 

 //This function Validates Form 
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'marksNotEnteredForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'ASC';
pendingStudents = 0;
 //This function Validates Form 
allSlidersArray = new Array();


function getLabelClass(){

    form = document.marksNotEnteredForm;
    var timeTable = form.labelId.value;
     
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetLabelMarksTransferredClass.php';
    var pars = 'timeTable='+timeTable;
    form.degreeId.length = null; 
    addOption(form.degreeId, '', 'Select');
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
                 
                form.degreeId.length = null;
                addOption(form.degreeId, '', 'Select');
                if (len > 0) {
                    //addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
                }
                for(i=0;i<len;i++) { 
                    addOption(form.degreeId, j[i].classId, j[i].className);
                }
                // now select the value
                form.degreeId.value = j[0].classId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}


function getClassSubjects() {
    form = document.marksNotEnteredForm;
    var degree = form.degreeId.value;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetSubjects.php';
    var timeTable = form.labelId.value;
    var pars = 'timeTable='+timeTable+'&degree='+degree;
    if (degree == '') {
        form.subjectId.length = null;
        addOption(form.subjectId, '', 'Select');
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
                form.subjectId.length = null;
                addOption(form.subjectId, '', 'Select');
                /*
                if (len > 0) {
                    addOption(document.testWiseMarksReportForm.subjectId, 'all', 'All');
                }
                */
                for(i=0;i<len;i++) { 
                    addOption(form.subjectId, j[i].subjectId, j[i].subjectCode);
                }
                // now select the value
                form.subjectId.value = j[0].subjectId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}

function validateAddForm() {
	hideResults();
    form = document.marksNotEnteredForm;

    if (form.labelId.value == '') {
        messageBox("<?php echo SELECT_TIMETABLE;?>");
        form.labelId.focus();
        return false;
    }
   
    if (form.degreeId.value == '') {
        messageBox("<?php echo SELECT_CLASS;?>");
        document.getElementById('degreeId').focus();
        return false;
    }

    if (form.subjectId.value == '') {
        messageBox("<?php echo SELECT_SUBJECT;?>");
        form.subjectId.focus();
        return false;
    }

    if (form.gradingFormula.value == '') {
        messageBox("<?php echo SELECT_ROUNDING;?>");
        form.gradingFormula.focus();
        return false;
    }

	document.getElementById("nameRow").style.display='';
	document.getElementById("resultRow").style.display='';
	showGraph();
    showManualDiv();
//	saveGrades();
//	openStudentLists(frm.name,'class','Asc');    
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('graphRow').style.display='none';
	document.getElementById('sliderRow').style.display='none';
    document.getElementById('manualDiv').style.display='none';
    document.getElementById('resultsDiv1').innerHTML='none';
}

function printReport() {
	form = document.marksNotEnteredForm;
	path='<?php echo UI_HTTP_PATH;?>/scCourseMarksTransferredPrint.php?subjectId='+form.subjectId.value+'&sectionId='+form.sectionId.value;
	window.open(path,"MarksNotEnteredReport","status=1,menubar=1,scrollbars=1, width=900");
}

window.onload = function () {
    document.marksNotEnteredForm.labelId.focus();
}
/***************************************************** GRAPH CODE STARTS HERE **********************************/

function showGraph() {
// showResults();
	hideResults();
	form = document.marksNotEnteredForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/getHistogram2.php'; 
	queryString = generateQueryString('marksNotEnteredForm');
//	var pars = 'subjectId='+form.subjectId.value+'&histogramId='+form.histogramId.value;
	var pars = queryString;//'subjectId='+form.subjectId.value+'&gadeLabelId='+form.gadeLabelId.value;
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
            if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				subjectCode = form.subjectId.options[form.subjectId.selectedIndex].text;
				showLineChartResults(subjectCode);
			 }
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

}

function showLineChartResults(subjectCode) {
	document.getElementById("graphRow").style.display='';
	form = document.marksNotEnteredForm;
	x = Math.random() * Math.random();
	var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline", "1300", "380", "5", "#FFFFFF");
	so.addVariable("path", "./");
	so.addParam("wmode", "transparent");
	so.addVariable("chart_id", "amline"); // if you have more then one chart in one page, set different chart_id for each chart	
	so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>250</y><rotate>true</rotate><text>Student Count ---></text><text_size>10</text_size></label><label id='2'><x>50</x><y>350</y><text>Marks:---></text><text_size>10</text_size></label><label id='3'><x>150</x><y>10</y><rotate>false</rotate><text>Histogram For: "+subjectCode+"</text><text_size>17</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency></category></values></settings>");
	so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/barChartSetting2.xml"));
	so.addVariable("data_file", encodeURIComponent("../Templates/Xml/courseMaksTransferred.xml?t="+x));
	so.write("graphDiv");
	//document.getElementById("nameRow2").style.display='';
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
  form = document.marksNotEnteredForm;
  flashMovie.exportImage('image.php?name=Histogram For '+form.subjectId.options[form.subjectId.selectedIndex].text);  
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
}      

// ERROR /////////////////////////////////////////////////////////////////////////////////
// amError(chart_id, message)
// This function is called when an error occurs, such as no data, or file not found.
function amError(chart_id, message){
  alert(message);
} 


function showManualDiv() {
    
	form = document.marksNotEnteredForm;
	queryString = generateQueryString('marksNotEnteredForm');
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/showGradeAdvance.php';
	
    var pars = queryString;
	
    document.getElementById("nameRow").style.display='';
	document.getElementById("sliderRow").style.display='';
	document.getElementById("resultRow").style.display='';
    document.getElementById("sliderRow1").style.display = 'none';
    document.getElementById("resultRow").style.display='none'; 
    document.getElementById("manualDiv").innerHTML=''; 
    document.getElementById("resultsDiv1").innerHTML='';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
				hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');

				var total = j['gradesArray'].length; 
                var pendingStudents = parseInt(j['pendingStudents']);
                var internalTotalMarks = j['internalTotalMarks']; 
                var lastGradeCount = j['lastGradeCount']; 
                
				if(pendingStudents > 0) {
				   //messageBox("<?php echo MARKS_NOT_TRANSFERRED_FOR_ALL_STUDENTS; ?>");
                   hideResults();
                   displayWindow('PendingStudentListDiv',350,350);
                   document.getElementById("marksNotTransferStudentList").innerHTML=j['allPendingStudentsDetail'];  
                   return false;
				}
				else {
                    if(internalTotalMarks==null|| internalTotalMarks==0) {
                      document.getElementById("sliderRow1").style.display = '';
			          document.getElementById("sliderRow").style.display = 'none';
                      document.getElementById("resultRow").style.display = 'none'; 
                      document.getElementById("manualDiv").style.display = 'none';
                      document.getElementById("resultRow").style.display='none'; 
			          document.getElementById("nameRow").style.display = '';
                      document.getElementById("manualDiv").innerHTML='';
                      return false;
			        }
					if(total==null|| total==0) { 
                      messageBox("Not defined grade set and grading");
                      hideResults();
                      return false;
                    }
                    document.getElementById("resultRow").style.display='none'; 
                    document.getElementById("sliderRow1").style.display = 'none';
                    document.getElementById("sliderRow").style.display = '';
                    document.getElementById("nameRow").style.display = '';
                    document.getElementById("manualDiv").style.display = '';
                    document.getElementById("manualDiv").innerHTML= j['gradeTable'];   
				}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function calculateManualMGPA() {
	
    var form = document.marksNotEnteredForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/showGradesMGPAAdvance.php';
    var queryString = generateQueryString('marksNotEnteredForm');
    
    var pars = queryString;
	document.getElementById("resultRow").style.display='';
    document.getElementById("resultsDiv1").innerHTML='';
    new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
				hideWaitDialog(true);
				responseMsg = trim(transport.responseText);
				if (responseMsg.indexOf("<?php echo MARKS_NOT_TRANSFERRED_FOR_ALL_STUDENTS ;?>") != -1 || responseMsg.indexOf("<?php echo INCORRECT_VALUES_FOR_GRADE_ ;?>") != -1 || responseMsg.indexOf("<?php echo INCORRECT_RANGE_FOR_GRADE_ ;?>") != -1) {
					messageBox(responseMsg);
				}
				else {
					var j = eval('(' + transport.responseText + ')');
					
					totalGrades = j['gradeArray'].length;
					mgpaMarks = 0;
					totalStudents = 0;
					nominatorValue = 0;
					var tableData = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">';
					tableData += '<tr><td class="searchhead_text" colspan="2">MGPA Details</td></tr>';
					thisString = '';
					for (i=0; i < totalGrades; i++) {
						gradeId = j['gradeArray'][i]['gradeId'];
						gradeLabel = j['gradeArray'][i]['gradeLabel'];
						mgpaStudents = parseInt(j['studentCountArray'][gradeId]['studentCount']);
						mgpaMarks = parseInt(j['studentCountArray'][gradeId]['gradePoints']);
						totalStudents  += mgpaStudents;
						nominatorValue += (mgpaStudents * mgpaMarks);
						tableData += '<tr><td width="100%" class="" colspan="2">Students with Grade: '+gradeLabel+' are : '+mgpaStudents+'</tr>';
						if (thisString != '') {
							thisString += ', ';
						}
						thisString += gradeLabel+':'+mgpaStudents;
					}
					mgpa = Math.round((nominatorValue / totalStudents)*100)/100;
					tableData += '<tr><td colspan="2" width="8%" class="searchhead_text ">MGPA: '+mgpa+'</td></tr>';
					tableData += '<tr><td class="" width="50%">Grading Label:<input type="text" style="width:150px;" class="htmlElement" name="gradingLabelName1" id="gradingLabelName1"></td><td align="left"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/apply_grades.gif" onClick="saveManualGrades()" style="align:absmiddle" /></td></tr>';
					tableData += '</table>';
                    document.getElementById("resultsDiv1").style.display='';
					document.getElementById("resultsDiv1").innerHTML = tableData;
					subjectCode = form.subjectId.options[form.subjectId.selectedIndex].text;
					subjectCode += ' {'+thisString+'}';
					subjectCode += ' [MGPA: '+mgpa+']';
					showLineChartResults(subjectCode);
				}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});	
changeColor(currentThemeId);
}

function saveManualGrades() {
    
    gradingLabelName = trim(document.getElementById("gradingLabelName1").value);
    if (gradingLabelName == '') {
        messageBox("<?php echo ENTER_GRADING_LABEL_NAME;?>");
        return false;
    }
    gradeAssignmentConfirm = "<?php echo GRADE_ASSIGNMENT_CONFIRM;?>";
    if (pendingStudents > 0) {
        gradeAssignmentConfirm = "<?php echo PENDING_GRADE_ASSIGNMENT_CONFIRM;?>"
    }
    if(confirm(gradeAssignmentConfirm)) {
        form = document.marksNotEnteredForm;
        var url = '<?php echo HTTP_LIB_PATH;?>/Student/saveGradesAdvance.php';
        queryString = generateQueryString('marksNotEnteredForm');
        var pars = queryString; 
        new Ajax.Request(url,
        {
            method:'post',
            parameters: pars,
            asynchronous: false,   
            onCreate: function(){
                 showWaitDialog(true);
             },
            onSuccess: function(transport){
                    hideWaitDialog(true); 
                    messageBox(trim(transport.responseText));
                    if (trim(transport.responseText) == "<?php echo SUCCESS;?>") {
                        hideResults();
                    }
            },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });
    }
}


function setGradeValue() {
    
    document.getElementById("nameRow").style.display='none';
    document.getElementById("resultRow").style.display='none';
    
    formx = document.marksNotEnteredForm;   
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    var gradeFrom=0;
    var isCheck=0;
    for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('hiddenGradeId[]')>-1) {
         var id = trim(obj[i].value); 
         var gradeValue = eval("trim(document.getElementById('ttGradeTo"+id+"').value)");
         if(gradeValue=='') {
           eval("document.getElementById('ttGradeTo"+id+"').className='htmlElement2'");     
         }
         if(!isDecimal(gradeValue)) {                          
           eval("document.getElementById('ttGradeTo"+id+"').className='inputboxRed'");   
         }
         if(isCheck==0) {   
           isCheck++;
         }
         else {
           eval("document.getElementById('ttGradeFrom"+id+"').value=gradeFrom");  
         }
         if(isDecimal(gradeValue)) {                          
            eval("document.getElementById('ttGradeTo"+id+"').className='htmlElement2'");   
            if(parseInt(gradeValue)==gradeValue) {
              gradeValue= parseFloat(gradeValue,2)+1;  
            }
            else {
              gradeValue= parseFloat(gradeValue,2)+parseFloat(.01,2);  
            }
            gradeFrom =  gradeValue;
         }
         if(gradeValue!='') {
             ttFrom = eval("document.getElementById('ttGradeFrom"+id+"').value");
             ttTo = eval("document.getElementById('ttGradeTo"+id+"').value");
             if(parseFloat(ttFrom,2)>parseFloat(ttTo,2)) {
               eval("document.getElementById('ttGradeTo"+id+"').className='inputboxRed'");   
             }
         }
     } // End for if Condition
   } // End for Loop
}

function setFromText(id){
 
  value1=document.getElementById('txtto1'+id).value; 
  ++value1; 
  id=id-1;
  document.getElementById('txtfrom1'+id).value=value1;
}
  
</script>
</head>
<body>
  <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listApplyGradeAdvanceContent.php");  
    require_once(TEMPLATES_PATH . "/footer.php");
  ?>
</body>
</html>