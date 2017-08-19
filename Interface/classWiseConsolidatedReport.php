<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
//
//
// Author :Ajinder Singh
// Created on : 27-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
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
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassConsolidatedReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'classWiseConsolidatedReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"),new Array("studyPeriodId","<?php echo SELECT_STUDYPERIOD;?>"), new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));

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
	form = document.classWiseConsolidatedReportForm;
	if (form.teacherId.value == '') {
		messageBox("<?php echo SELECT_TEACHER;?>");
		form.teacherId.focus();
		return false;
	}
	else if(form.teacherId.value != 'all' && form.groupId.value == '') {
		messageBox("<?php echo SELECT_GROUP;?>");
		form.groupId.focus();
		return false;
	}
	else if(form.testId.value == '') {
		messageBox("<?php echo SELECT_TEST;?>");
		form.testId.focus();
		return false;
	}
	showReport();

}

function resetStudyPeriod() {
	form = document.classWiseConsolidatedReportForm;
	form.studyPeriodId.selectedIndex = 0;
	form.subjectId.selectedIndex = 0;
	form.teacherId.selectedIndex = 0;
	form.groupId.selectedIndex = 0;
	form.testId.selectedIndex = 0;
}

function showReport() {

	form = document.classWiseConsolidatedReportForm;
	var mixedVal = form.degree.value;
	var studyPeriodId = form.studyPeriodId.value;
	var subjectId = form.subjectId.value;
	var teacherId = form.teacherId.value;
	var groupId = form.groupId.value;
	var testId = form.testId.value;


	
	if (mixedVal == '' || studyPeriodId == '' || subjectId == '' || teacherId == '') {
		document.classWiseConsolidatedReportForm.testId.length = null;
		addOption(document.classWiseConsolidatedReportForm.testId, '', 'Select');
		return false;
	}



	if (form.degree.value == '' || form.studyPeriodId.value == '' || form.subjectId.value == '') {
		return false;
	}

		var pars = 'mixedValue='+mixedVal+'&studyPeriod='+studyPeriodId+'&subjectId='+subjectId+'&teacherId='+teacherId+'&groupId='+groupId+'&testId='+testId;


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
				total2 = j['data'].length;
				if (total2 == 0) {
					var tableData = '<table border="0" cellpadding="0" cellspacing="0" width="800" class="reportBorder reportTableBorder" rules="all">';
					tableData += '<tr class="">';
					tableData += '<td colspan="3" align="center" class="reportBorder">No Detail Found</td>';
					tableData += '</tr></table>';
				}
				else {
					var tableData = '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="reportBorder reportTableBorder" rules="all">';
					tableData += '<tr class="">';
					tableData += '<td colspan="3" align="center" class="reportBorder"><b>Class&nbsp;:&nbsp;</b> '+j['degreeCode']+' '+j['branchCode']+' '+j['studyPeriod']+',&nbsp;&nbsp;<b>Subject&nbsp;:&nbsp;</b> '+j['subject']+',&nbsp;&nbsp;<b>Teacher&nbsp;:&nbsp;</b>'+j['employee']+',&nbsp;&nbsp;<b>Group&nbsp;:&nbsp;</b> '+j['group']+', &nbsp;&nbsp;<b>Test&nbsp;:&nbsp;</b>' +j['testName']+'</td>';
					tableData += '</tr>';
					tableData += '<tr><td colspan="3" height="20"></td></tr>';
					tableData += '<tr height="20">';
					tableData += '<td class="reportBorder">&nbsp;</td>';
					tableData += '<td rowspan="1" colspan="1" class="reportBorder" align="right"><b>Total No.</b>&nbsp;</td>';
					tableData += '<td rowspan="1" colspan="1" class="reportBorder" align="right"><b>Percentage</b>&nbsp;</td></tr>';
					tableData += '<tr height="20">';
					tableData += '<td align="left" class="reportBorder"><B>&nbsp;Total Students</B></td>';
					tableData += '<td align="right">'+j['totalStudents'][0]+'&nbsp;</td><td  class="reportBorder">&nbsp;</td>';
					tableData += '</tr>';
					tableData += '<tr height="20">';
					tableData += '<td align="left" class="reportBorder">&nbsp;<B>Students Appeared</B></td>';
					tableData += '<td  class="reportBorder" align="right">'+j['presentStudents'][0]+'&nbsp;</td><td align="right">'+j['presentStudents'][1]+'&nbsp;</td>';
					tableData += '</tr>';
					tableData += '<tr height="20">';
					tableData += '<td align="left" class="reportBorder">&nbsp;<B>Students Absent</B></td>';
					tableData += '<td  class="reportBorder" align="right">'+j['absentStudents'][0]+'&nbsp;</td>';
					tableData += '<td  class="reportBorder" align="right">'+j['absentStudents'][1]+'&nbsp;</td>';
					tableData += '</tr>';

					total = j['data'].length;
					for(i=0; i<total;i++) {
						tableData += '<tr height="20">';
						tableData += '<td align="left" class="reportBorder">&nbsp;<B>'+j['data'][i]['rangeLabel']+'</B></td>';
						tableData += '<td  class="reportBorder" align="right">'+j['data'][i]['cnt']+'&nbsp;</td>';
						tableData += '<td  class="reportBorder" align="right">'+j['data'][i]['per']+'&nbsp;</td>';
						tableData += '</tr>';
					}
					tableData += '</table>';
					tableData += '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="reportBorder" rules="all">';
					x = Math.random() * Math.random();
					tableData += '<tr><td><img src="'+j['imagePath']+'?x='+x+'" /></td></tr></table>';
				}


				document.getElementById('resultsDiv').style.display='';
				document.getElementById('resultRow').style.display='';
				document.getElementById('nameRow').style.display='';
				document.getElementById('nameRow2').style.display='';
				document.getElementById('resultsDiv').innerHTML = tableData;


			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
}

function printReport() {
	form = document.classWiseConsolidatedReportForm;
	var mixedVal = form.degree.value;
	var studyPeriodId = form.studyPeriodId.value;
	var subjectId = form.subjectId.value;
	var teacherId = form.teacherId.value;
	var groupId = form.groupId.value;
	var testId = form.testId.value;

	if (mixedVal == '' || studyPeriodId == '' || subjectId == '' || teacherId == '') {
		document.classWiseConsolidatedReportForm.testId.length = null;
		addOption(document.classWiseConsolidatedReportForm.testId, '', 'Select');
		return false;
	}
	var pars = 'mixedValue='+mixedVal+'&studyPeriod='+studyPeriodId+'&subjectId='+subjectId+'&teacherId='+teacherId+'&groupId='+groupId+'&testId='+testId;
	path='<?php echo UI_HTTP_PATH;?>/classWiseConsolidatedReportPrint.php?'+pars;
	window.open(path,"classWiseConsolidatedReport","status=1,menubar=1,scrollbars=1, width=900");
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function getSubjects() {
	form = document.classWiseConsolidatedReportForm;
	var mixedVal = form.degree.value;
	var studyPeriodId = form.studyPeriodId.value;

	form.subjectId.selectedIndex = 0;
	form.teacherId.selectedIndex = 0;
	form.groupId.selectedIndex = 0;
	form.testId.selectedIndex = 0;

	
	if (mixedVal == '' || studyPeriodId == '') {
		document.classWiseConsolidatedReportForm.subjectId.length = null;
		addOption(document.classWiseConsolidatedReportForm.subjectId, '', 'Select');
		return false;
	}

	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassTeachingSubjects.php';
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
				document.classWiseConsolidatedReportForm.subjectId.length = null;
				addOption(document.classWiseConsolidatedReportForm.subjectId, '', 'Select');
				if (len > 0) {
					addOption(document.classWiseConsolidatedReportForm.subjectId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
					addOption(document.classWiseConsolidatedReportForm.subjectId, j[i].subjectId, j[i].subjectName);
				}
				// now select the value
				document.classWiseConsolidatedReportForm.subjectId.value = j[0].subjectId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getTeachers() {
	form = document.classWiseConsolidatedReportForm;
	var mixedVal = form.degree.value;
	var studyPeriodId = form.studyPeriodId.value;
	var subjectId = form.subjectId.value;

	form.teacherId.selectedIndex = 0;
	form.groupId.selectedIndex = 0;
	form.testId.selectedIndex = 0;

	
	if (mixedVal == '' || studyPeriodId == '' || subjectId == '') {
		document.classWiseConsolidatedReportForm.teacherId.length = null;
		addOption(document.classWiseConsolidatedReportForm.teacherId, '', 'Select');
		return false;
	}

	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initSubjectGetTeachers.php';
	var pars = 'mixedValue='+mixedVal+'&studyPeriod='+studyPeriodId+'&subjectId='+subjectId;
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
				document.classWiseConsolidatedReportForm.teacherId.length = null;
				addOption(document.classWiseConsolidatedReportForm.teacherId, '', 'Select');
				if (len > 0) {
					addOption(document.classWiseConsolidatedReportForm.teacherId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
					addOption(document.classWiseConsolidatedReportForm.teacherId, j[i].employeeId, j[i].employeeName);
				}
				// now select the value
				document.classWiseConsolidatedReportForm.teacherId.value = j[0].employeeId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getGroups() {
	form = document.classWiseConsolidatedReportForm;
	var mixedVal = form.degree.value;
	var studyPeriodId = form.studyPeriodId.value;
	var subjectId = form.subjectId.value;
	var teacherId = form.teacherId.value;

	form.groupId.selectedIndex = 0;
	form.testId.selectedIndex = 0;

	
	if (mixedVal == '' || studyPeriodId == '' || subjectId == '' || teacherId == '') {
		document.classWiseConsolidatedReportForm.groupId.length = null;
		addOption(document.classWiseConsolidatedReportForm.groupId, '', 'Select');
		return false;
	}

	if (teacherId == 'all') {
		document.classWiseConsolidatedReportForm.groupId.length = null;
		addOption(document.classWiseConsolidatedReportForm.groupId, '', 'Select');
		return false;
	}

	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initTeacherGetGroups.php';
	var pars = 'mixedValue='+mixedVal+'&studyPeriod='+studyPeriodId+'&subjectId='+subjectId+'&teacherId='+teacherId;
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
				document.classWiseConsolidatedReportForm.groupId.length = null;
				addOption(document.classWiseConsolidatedReportForm.groupId, '', 'Select');
				if (len > 0) {
					addOption(document.classWiseConsolidatedReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
					addOption(document.classWiseConsolidatedReportForm.groupId, j[i].groupId, j[i].groupShort);
				}
				// now select the value
				document.classWiseConsolidatedReportForm.groupId.value = j[0].groupId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getTests() {

	form = document.classWiseConsolidatedReportForm;
	var mixedVal = form.degree.value;
	var studyPeriodId = form.studyPeriodId.value;
	var subjectId = form.subjectId.value;
	var teacherId = form.teacherId.value;
	var groupId = form.groupId.value;

	form.testId.selectedIndex = 0;

	
	if (mixedVal == '' || studyPeriodId == '' || subjectId == '' || teacherId == '') {
		document.classWiseConsolidatedReportForm.testId.length = null;
		addOption(document.classWiseConsolidatedReportForm.testId, '', 'Select');
		return false;
	}


	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initSubjectGetTests.php';
	var pars = 'mixedValue='+mixedVal+'&studyPeriod='+studyPeriodId+'&subjectId='+subjectId+'&teacherId='+teacherId+'&groupId='+groupId;
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
				document.classWiseConsolidatedReportForm.testId.length = null;
				addOption(document.classWiseConsolidatedReportForm.testId, '', 'Select');
				for(i=0;i<len;i++) { 
					addOption(document.classWiseConsolidatedReportForm.testId, j[i].testId, j[i].testName);
				}
				// now select the value
				document.classWiseConsolidatedReportForm.testId.value = j[0].testId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getTimeTableClasses() {
	form = document.classWiseConsolidatedReportForm;
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
    require_once(TEMPLATES_PATH . "/StudentReports/listClassWiseConsolidatedReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: classWiseConsolidatedReport.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/27/09    Time: 10:29a
//Updated in $/LeapCC/Interface
//put time table dropdownlist
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
//User: Ajinder      Date: 9/22/08    Time: 12:01p
//Updated in $/Leap/Source/Interface
//added code for "no record found"
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/09/08    Time: 4:57p
//Updated in $/Leap/Source/Interface
//done the coding for IE to make it similar to FF and chrome.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/06/08    Time: 3:37p
//Updated in $/Leap/Source/Interface
//done designing change
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/28/08    Time: 12:25p
//Created in $/Leap/Source/Interface
//File added for "class performance graph"
//


?>