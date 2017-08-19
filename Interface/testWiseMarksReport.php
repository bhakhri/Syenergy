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
define('MODULE','TestWiseMarksReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test wise Marks Report </title>
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
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"), new Array("subjectId","<?php echo SELECT_SUBJECT;?>"), new Array("groupId","<?php echo SELECT_GROUP;?>") );

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
	var groupId = form.groupId.value;
	var sorting = form.sorting.value;
   var ordering = form.ordering[0].checked==true?'asc':'desc';
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initTestWiseMarksReport.php';
	var pars = 'class1='+degree+'&subjectId='+subjectId+'&groupId='+groupId+'&sorting='+sorting+'&ordering='+ordering+'&page='+page;


	 if (degree == '' || subjectId == '' || groupId == '') {
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
					tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text reportBorder">#</td><td width="8%" class="searchhead_text reportBorder">Roll No.</td><td width="8%" class="searchhead_text reportBorder">U.Roll No.</td><td width="20%" class="searchhead_text reportBorder">Student Name</td>';
					tableData += '<td width=62% class="searchhead_text reportBorder">&nbsp;</td></tr><tr '+bg+'>';
					tableData += '<td class="padding_top" align=center colspan=5>No details found</td></tr>';
				}
				else {
					var tableData = globalTB;
					tableData += '<tr class="rowheading"><td rowspan="3" width="2%" class="searchhead_text">#</td><td rowspan="3" width="8%" class="searchhead_text">C.Roll No.</td><td rowspan="3" width="8%" class="searchhead_text">U.Roll No.</td><td width="20%" rowspan="3" class="searchhead_text">Student Name</td>';

					allTests = 0;
					for(i=0;i<totalTests;i++) { 
						testTypeId = j['testTypes'][i]['testTypeCategoryId'];
						testTypeIdTests = j['testDetails'][testTypeId].length; 
						allTests += testTypeIdTests;
					}

					perTestSpace = parseInt(62/allTests)+'%';
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
					tableData += '<tr class="rowheading">';
					for(i=0;i<totalTests;i++) { 
						testTypeId = j['testTypes'][i]['testTypeCategoryId'];
						testTypeIdTests = j['testDetails'][testTypeId].length;
						for(m=0; m<testTypeIdTests;m++) {
							tableData += '<td class=" searchhead_text" align="right">'+j['testDetails'][testTypeId][m]['maxMarks']+'&nbsp;</td>';
						}
					}
					tableData += '</tr>';



					var resultDataLength = j['resultData'].length;
					
					for(x = 0; x < resultDataLength; x++) {
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						tableData += '<tr '+bg+'>';
						tableData += '<td >'+j['resultData'][x]['srNo']+'&nbsp;</td>';
						tableData += '<td >'+j['resultData'][x]['rollNo']+'&nbsp;</td>';
						tableData += '<td >'+j['resultData'][x]['universityRollNo']+'&nbsp;</td>';
						tableData += '<td >'+j['resultData'][x]['studentName']+'&nbsp;</td>';
						for(i=0;i<totalTests;i++) { 
							testTypeId = j['testTypes'][i]['testTypeCategoryId'];
							testTypeIdTests = j['testDetails'][testTypeId].length;
							if (testTypeIdTests > 0) {
								for(m=0; m<testTypeIdTests;m++) {
									//var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
									thisTest = 'ms'+j['testDetails'][testTypeId][m]['testId'];
									tableData += '<td width="'+perTestSpace+'" class="padding_top"  align="right">'+j['resultData'][x][thisTest]+'&nbsp;</td>';
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
	var sorting = form.sorting.value;
   var ordering = form.ordering[0].checked==true?'asc':'desc';
	path='<?php echo UI_HTTP_PATH;?>/testWiseMarksReportPrint.php?class1='+form.degree.value+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value+'&sorting='+sorting+'&ordering='+ordering;
	window.open(path,"TestWiseMarksReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	form = document.testWiseMarksReportForm;
	var sorting = form.sorting.value;
   var ordering = form.ordering[0].checked==true?'asc':'desc';
	path='<?php echo UI_HTTP_PATH;?>/testWiseMarksReportCSV.php?class1='+form.degree.value+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value+'&sorting='+sorting+'&ordering='+ordering;
   window.location = path;
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
                  //if(j[i].hasMarks==1 ) {
					addOption(document.testWiseMarksReportForm.subjectId, j[i].subjectId, j[i].subjectCode);
                  //}
				}
				// now select the value
				// document.testWiseMarksReportForm.subjectId.value = '';
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function clearClassGroups() {
	document.testWiseMarksReportForm.groupId.length = null;
	addOption(document.testWiseMarksReportForm.groupId, '', 'Select');
	return false;
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
	if (degree == '') {
		document.testWiseMarksReportForm.groupId.length = null;
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
				document.testWiseMarksReportForm.groupId.length = null;
				addOption(document.testWiseMarksReportForm.groupId, '', 'Select');
				if (len > 0) {
					//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
					addOption(document.testWiseMarksReportForm.groupId, j[i].groupId, j[i].groupName);
				}
				// now select the value
				document.testWiseMarksReportForm.groupId.value = '';
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function getTimeTableClasses() {
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
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.degree.length = null;
			addOption(form.degree, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.degree, j[i].classId, j[i].className);
			}
			// now select the value
			//form.degree.value = j[0].classId;
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
    require_once(TEMPLATES_PATH . "/StudentReports/listTestWiseMarksReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: testWiseMarksReport.php $
//
//*****************  Version 15  *****************
//User: Ajinder      Date: 3/24/10    Time: 12:52p
//Updated in $/LeapCC/Interface
//done changes, FCNS No.1459
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 11/23/09   Time: 6:54p
//Updated in $/LeapCC/Interface
//fixed bugs: 2112, 2113
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 11/20/09   Time: 10:45a
//Updated in $/LeapCC/Interface
//modified for bug fixing: FCNS ref. no. 822
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 10/29/09   Time: 10:01a
//Updated in $/LeapCC/Interface
//fixed query error
//
//*****************  Version 11  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Interface
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Interface
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/08/09    Time: 12:03p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 3/30/09    Time: 5:48p
//Updated in $/LeapCC/Interface
//done changes of test type category
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 3/30/09    Time: 11:44a
//Updated in $/LeapCC/Interface
//changed subject code instead of subject name.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 3/21/09    Time: 4:07p
//Updated in $/LeapCC/Interface
//changed display to make it as per rest of software.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 3/17/09    Time: 5:45p
//Updated in $/LeapCC/Interface
//added code for pagination
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/16/09    Time: 12:55p
//Updated in $/LeapCC/Interface
//added code for if no test exists
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/17/08   Time: 11:49a
//Updated in $/LeapCC/Interface
//Added define('MANAGEMENT_ACCESS',1); for management role
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 9/20/08    Time: 1:02p
//Updated in $/Leap/Source/Interface
//removed code for all subjects.
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 9/10/08    Time: 2:51p
//Updated in $/Leap/Source/Interface
//done the coding to improve design in IE
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 9/08/08    Time: 12:26p
//Updated in $/Leap/Source/Interface
//fixed bug related to data not shown subject wise.
//added code for making report flexible for all subjects.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/25/08    Time: 7:14p
//Updated in $/Leap/Source/Interface
//removed code which was making unnecessary server trip
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/18/08    Time: 6:15p
//Updated in $/Leap/Source/Interface
//file modified for showing print button and improving page design
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/14/08    Time: 5:19p
//Updated in $/Leap/Source/Interface
//
?>
