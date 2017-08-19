<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
//
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectWiseConsolidatedReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: List Students </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="10%" align="left"','align="left"',false), new Array('rangeLabel','Range','width="45%" align="left"','align="left"',false), new Array('studentCount','No. of Students','width="45%"','',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initSubjectWiseConsolidatedReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'subjectWiseConsolidatedReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"),new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	//openStudentLists(frm.name,'rollNo','Asc');
	showReport();

}

function resetStudyPeriod() {
	//document.subjectWiseConsolidatedReportForm.studyPeriodId.selectedIndex = 0;
}

function showReport() {

	frm = document.forms[0].name;
	var pars = generateQueryString(frm);
	
	form = document.subjectWiseConsolidatedReportForm;
	if (form.degree.value == '' || form.studyPeriodId.value == '' || form.subjectId.value == '') {
		return false;
	}
	if (form.subjectId.value == 'all') {

		var tableData = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #9F9F9F;" rules="all">';
		tableData += '<tr class="rowheading"><td rowspan="2" width="2%" class="searchhead_text">#</td><td rowspan="2" width="10%" class="searchhead_text">Range</td>';


		new Ajax.Request(listURL,
		{
			method:'post',
			parameters: pars,
			asynchronous:false,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){
				hideWaitDialog(true);
				fetchedData = trim(transport.responseText);
				j = eval('('+ transport.responseText + ')');
				totalSubjects = j['subjects'].length;
				tableData += '<td rowspan="1" width="88%" class="searchhead_text" align="center" colspan="'+totalSubjects+'">No. of Students</td></tr>';
				perSubjectSpace = parseInt(88/totalSubjects)+'%';

				tableData += '<tr>';

				for(i=0;i<totalSubjects;i++) { 
					subjectId = j['subjects'][i]['subjectId'];
					subjectCode = j['subjects'][i]['subjectCode']; 
					tableData += '<td class="searchhead_text" style="text-align:center;border-bottom:1px solid #9F9F9F;">'+subjectCode+'&nbsp;</td>';
				}
				tableData += '</tr>';

				var resultDataLength = j['data'].length;

				for(x = 0; x < resultDataLength; x++) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					tableData += '<tr '+bg+'>';
					tableData += '<td class="padding_top">'+j['data'][x]['srNo']+'&nbsp;</td>';
					tableData += '<td class="padding_top">'+j['data'][x]['rangeLabel']+'&nbsp;</td>';
					for(i=0;i<totalSubjects;i++) { 
						subjectCode = j['subjects'][i]['subjectCode'];
						tableData += '<td width="'+perSubjectSpace+'" class="padding_top"  align="right">'+j['data'][x][subjectCode]+'&nbsp;</td>';
					}
					tableData += '</tr>';
				}

				tableData += '</table>';

				document.getElementById('resultsDiv').style.display='';
				document.getElementById('resultRow').style.display='';
				document.getElementById('nameRow').style.display='';
				document.getElementById('nameRow2').style.display='';
				document.getElementById('resultsDiv').innerHTML = tableData;


			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
	else {
		new Ajax.Request(listURL,
		{
			method:'post',
			parameters: pars,
			asynchronous:false,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){
				hideWaitDialog(true);
				fetchedData = trim(transport.responseText);
				j = eval('('+ transport.responseText + ')');
				document.getElementById('resultsDiv').style.display='';
				document.getElementById('resultRow').style.display='';
				document.getElementById('nameRow').style.display='';
				document.getElementById('nameRow2').style.display='';
				printResultsNoSorting('resultsDiv', j, tableHeadArray);
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}

function printReport() {
	form = document.subjectWiseConsolidatedReportForm;
	path='<?php echo UI_HTTP_PATH;?>/subjectWiseConsolidatedReportPrint.php?degree='+form.degree.value+'&studyPeriodId='+form.studyPeriodId.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"SubjectWiseConsolidatedReport","status=1,menubar=1,scrollbars=1, width=900");
}
function printGraph() {
	form = document.subjectWiseConsolidatedReportForm;
	path='<?php echo UI_HTTP_PATH;?>/subjectWiseConsolidatedGraphPrint.php?degree='+form.degree.value+'&studyPeriodId='+form.studyPeriodId.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"SubjectWiseConsolidatedReport","status=1,menubar=1,scrollbars=1, width=900");
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function getSubjects() {
	form = document.subjectWiseConsolidatedReportForm;
	var mixedVal = form.degree.value;
	var studyPeriodId = form.studyPeriodId.value;

	if (mixedVal == '' || studyPeriodId == '') {
		document.subjectWiseConsolidatedReportForm.subjectId.length = null;
		addOption(document.subjectWiseConsolidatedReportForm.subjectId, '', 'Select');
		return false;
	}
	

	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentGetSubjects.php';
	var pars = 'mixedValue='+mixedVal+'&studyPeriod='+studyPeriodId;
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
				document.subjectWiseConsolidatedReportForm.subjectId.length = null;
				addOption(document.subjectWiseConsolidatedReportForm.subjectId, '', 'Select');
				if (len > 0) {
					addOption(document.subjectWiseConsolidatedReportForm.subjectId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
					addOption(document.subjectWiseConsolidatedReportForm.subjectId, j[i].subjectId, j[i].subjectName);
				}
				// now select the value
				document.subjectWiseConsolidatedReportForm.subjectId.value = j[0].subjectId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getTimeTableClasses() {
	form = document.subjectWiseConsolidatedReportForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
	var pars = 'labelId='+form.labelId.value;

	if (form.labelId.value=='') {
		form.degree.length = null;
		addOption(form.degree, '', 'Select');
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
			form.degree.length = null;
			for(i=0;i<len;i++) {
				addOption(form.degree, j[i].classId, j[i].className);
			}
			// now select the value
			form.degree.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

window.onload=function(){
	document.getElementById('labelId').focus();
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listSubjectWiseConsolidatedReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: subjectWiseConsolidatedReport.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/27/09    Time: 10:19a
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001254, 0001253, 0001243 and put time table in reports
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/08/09    Time: 12:03p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/27/08    Time: 3:24p
//Updated in $/Leap/Source/Interface
//removed some extra space
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/25/08    Time: 7:14p
//Updated in $/Leap/Source/Interface
//removed code which was making unnecessary server trip
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/22/08    Time: 3:22p
//Updated in $/Leap/Source/Interface
//added code for "all subjects"
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 6:38p
//Created in $/Leap/Source/Interface
//file added for subject wise consolidated report
//



?>
