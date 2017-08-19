<?php

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
//
//
// Author :Ajinder Singh
// Created on : 12-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestWiseMarksConsolidatedReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Type Category wise Detailed Report </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=right','align=right',false), new Array('conductingAuthority2','Conducting Authority','width=18% align=left','align=left',true), new Array('subjectCode','Subject','width="15%" align=left','align=left',true), new Array('testTypeName','Test Type','width="18%" align="left"','align="left"',false), new Array('weightageAmount','Weightage Amt.','width="18%" align="right"','align="right"',false), new Array('weightagePercentage','Weightage %','width="18%" align="right"','align="right"',false));

 //This function Validates Form
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'testWiseMarksReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"), new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));

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

	hideResults();

	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';


	showReport(page);
	//sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function showReport(page) {
	form = document.testWiseMarksReportForm;
	var degree = form.degree.value;
	var subjectId = form.subjectId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initTestWiseMarksConsolidatedReport.php';
	var pars = 'class1='+degree+'&subjectId='+subjectId+'&page='+page;


	 if (degree == '' || subjectId == '') {
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
				totalRecords = parseInt(j['totalRecords']);
				totalTests = j['testTypes'].length;
				if (totalTests == 0) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					var tableData = globalTB;
					tableData += '<tr class="rowheading"><td width="5%" class="searchhead_text reportBorder">#</td><td width="8%" class="searchhead_text reportBorder">Roll No.</td><td width="8%" class="searchhead_text reportBorder">U.Reg No.</td><td width="20%" class="searchhead_text reportBorder">Student Name</td><td width="20%" class="searchhead_text reportBorder">Group Name</td>';
					tableData += '<td width=62% class="searchhead_text reportBorder">&nbsp;</td></tr><tr '+bg+'>';
					tableData += '<td class="padding_top" align=center colspan=6>No details found</td></tr>';
				}
				else {
					var tableData = globalTB;
					tableData += '<tr class="rowheading"><td rowspan="2" width="5%" class="searchhead_text">#</td><td rowspan="2" width="8%" class="searchhead_text">Roll No.</td><td rowspan="2" width="8%" class="searchhead_text">U.Reg No.</td><td width="15%" rowspan="2" class="searchhead_text">Student Name</td><td width="10%" rowspan="2" class="searchhead_text">Group Name</td>';

					allTests = 0;
					for(i=0;i<totalTests;i++) {
						testTypeId = j['testTypes'][i]['testTypeCategoryId'];
						testTypeIdTests = j['testDetails'][testTypeId].length;
						allTests += testTypeIdTests;
					}

					perTestSpace = parseInt(57/allTests)+'%';
					for(i=0;i<totalTests;i++) {
						testTypeId = j['testTypes'][i]['testTypeCategoryId'];
						testTypeIdTests = j['testDetails'][testTypeId].length;
						tableData += '<td align="center" class=" searchhead_text" colspan = "'+testTypeIdTests+'">'+j['testTypes'][i]['testTypeName']+'&nbsp;</td>';
					}
					tableData += '</tr>';
					tableData += '<tr class="rowheading">';
					for(i=0;i<totalTests;i++) {
						testTypeId = j['testTypes'][i]['testTypeCategoryId'];
						testTypeIdTests = j['testDetails'][testTypeId].length;
						for(m=0; m<testTypeIdTests;m++) {
							tableData += '<td align="center" class=" searchhead_text">'+j['testDetails'][testTypeId][m]['testName']+'&nbsp;</td>';
						}
					}
					tableData += '</tr>';
					/*
					tableData += '<tr class="rowheading">';
					for(i=0;i<totalTests;i++) {
						testTypeId = j['testTypes'][i]['testTypeCategoryId'];
						testTypeIdTests = j['testDetails'][testTypeId].length;
						for(m=0; m<testTypeIdTests;m++) {
							tableData += '<td class=" searchhead_text" align="right">'+j['testDetails'][testTypeId][m]['maxMarks']+'&nbsp;</td>';
						}
					}
					tableData += '</tr>';
					*/



					var resultDataLength = j['resultData'].length;


					for(x = 0; x < resultDataLength; x++) {
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						tableData += '<tr '+bg+'>';
						tableData += '<td >'+j['resultData'][x]['srNo']+'&nbsp;</td>';
						tableData += '<td >'+j['resultData'][x]['rollNo']+'&nbsp;</td>';
						tableData += '<td >'+j['resultData'][x]['universityRegNo']+'&nbsp;</td>';
						tableData += '<td >'+j['resultData'][x]['studentName']+'&nbsp;</td>';
						tableData += '<td >'+j['resultData'][x]['groupName']+'&nbsp;</td>';
						for(i=0;i<totalTests;i++) {
							testTypeId = j['testTypes'][i]['testTypeCategoryId'];
							testTypeIdTests = j['testDetails'][testTypeId].length;
							if (testTypeIdTests > 0) {
								for(m=0; m<testTypeIdTests;m++) {
									//var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
									thisTest = 'ms'+j['testDetails'][testTypeId][m]['testName'];
									currentTestMarks = j['resultData'][x][thisTest];
									if (currentTestMarks == 'null' || currentTestMarks == null) {
										currentTestMarks = 'TNH';
									}
									thisGroup = 'ms'+j['testDetails'][testTypeId][m]['groupName'];
									groupName = j['resultData'][x][thisGroup];
									if (groupName == 'null' || groupName == null) {
										groupName = 'GNA';
									}
									tableData += '<td width="'+perTestSpace+'" class="padding_top"  align="right">'+currentTestMarks+'&nbsp;</td>';
								}
							}
							else {
								tableData += '<td width="'+perTestSpace+'" class="padding_top"  align="right">&nbsp;</td>';
							}
						}
						tableData += '</tr>';
					}

				}
				tableData += "</table>";


				document.getElementById("resultsDiv").innerHTML = tableData;

				pagingData='';
				document.getElementById("pagingDiv").innerHTML = pagingData;

				totalPages = totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
				completePages = parseInt(totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
				if (totalPages > completePages) {
					completePages++;
				}
				if (allTests > 0) {
					pagingData = pagination2(page, totalRecords, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
					document.getElementById("pagingDiv").innerHTML = pagingData;
				}
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

}

function resetStudyPeriod() {
	document.testWiseMarksReportForm.subjectTypeId.selectedIndex = 0;
}

function printReport() {
	form = document.testWiseMarksReportForm;
	path='<?php echo UI_HTTP_PATH;?>/testWiseMarksConsolidatedReportPrint.php?class1='+form.degree.value+'&subjectId='+form.subjectId.value;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900");
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function getClassSubjects() {
	form = document.testWiseMarksReportForm;
	var degree = form.degree.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetTestSubjects.php';
	var pars = 'class1='+degree;
	if (degree == '') {
		document.testWiseMarksReportForm.subjectId.length = null;
		document.testWiseMarksReportForm.groupId.length = null;
		addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
		addOption(document.testWiseMarksReportForm.groupId, '', 'Select');
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
				document.testWiseMarksReportForm.subjectId.length = null;
				addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
				/*
				if (len > 0) {
					addOption(document.testWiseMarksReportForm.subjectId, 'all', 'All');
				}
				*/
				for(i=0;i<len;i++) {
					addOption(document.testWiseMarksReportForm.subjectId, j[i].subjectId, j[i].subjectCode);
				}
				// now select the value
				document.testWiseMarksReportForm.subjectId.value = j[0].subjectId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}



function getTimeTableClasses() {
	document.testWiseMarksReportForm.subjectId.length = null;
	addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
	hideResults();
	form = document.testWiseMarksReportForm;
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
		asynchronous:false,
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
			getClassSubjects();
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

}

window.onload=function(){
	document.getElementById('labelId').focus();
	getTimeTableClasses();

	
}


</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listTestWiseMarksConsolidatedReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php

////$History: testWiseMarksConsolidatedReport.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 11/25/09   Time: 11:12a
//Updated in $/LeapCC/Interface
//fixed bug no. 2120
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/20/09   Time: 10:45a
//Updated in $/LeapCC/Interface
//modified for bug fixing: FCNS ref. no. 822
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 11/16/09   Time: 10:54a
//Updated in $/LeapCC/Interface
//UPDATED TITLE OF PAGE
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/23/09    Time: 3:53p
//Created in $/LeapCC/Interface
//added file for test wise marks consolidated report.
//




?>
