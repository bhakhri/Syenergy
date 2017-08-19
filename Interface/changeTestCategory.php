<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
//
//
// Author :Ajinder Singh
// Created on : 12-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ChangeTestCategory');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Change Test Category </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), new Array('testTopic','Test Topic','width=35% align=left','align=left',false), new Array('testAbbr','Test Abbr','width="10%" align=left','align=left',false), new Array('testTypeName','Test Type','width="10%" align="left"','align="left"',false), new Array('testIndex','Test Index','width="10%" align="right"','align="right"',false), new Array('studentCount','Student Count','width="10%" align="right"','align="right"',false), new Array('action2','Edit Test Category','width="12%"','align="right"',false)); 

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
sortField = 'testTypeName';
sortOrderBy    = 'ASC';

var listURL = '<?php echo HTTP_LIB_PATH;?>/ChangeTestCategory/initGetTestDetails.php';

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
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function showReport(page) {
	form = document.testWiseMarksReportForm;
	var degree = form.degree.value;
	var subjectId = form.subjectId.value;
	var groupId = form.groupId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/ChangeTestCategory/initGetTestDetails.php';
	var pars = 'class1='+degree+'&subjectId='+subjectId+'&groupId='+groupId+'&page='+page;

	 if (degree == '' || subjectId == '' || groupId == '') {
		 return false;
	 }
}

function getTestDetails(testId) {
	form = document.testWiseMarksReportForm;
	var degree = form.degree.value;
	var subjectId = form.subjectId.value;
	var groupId = form.groupId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/ChangeTestCategory/initGetTestCategoryDetails.php';
	var pars = generateQueryString('testWiseMarksReportForm');
	pars += '&testId='+testId;
	 
	 
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
				testTopic = j['currentCategory'][0]['testTopic'];
				testAbbr = j['currentCategory'][0]['testAbbr'];
				testTypeName = j['currentCategory'][0]['testTypeName'];
				testIndex = j['currentCategory'][0]['testIndex'];
				studentCount = j['currentCategory'][0]['studentCount'];

				document.getElementById('currentTestTopicSpan').innerHTML = testTopic;
				document.getElementById('currentTestAbbrSpan').innerHTML = testAbbr;
				document.getElementById('currentTestTypeSpan').innerHTML = testTypeName;
				document.getElementById('currentTestIndexSpan').innerHTML = testIndex;
				document.getElementById('currentStudentCountSpan').innerHTML = studentCount;
				document.editTestCategoryForm.testId.value = testId;
				
				otherCategoryArray = j['otherCategory'];
				totalOtherCategories = otherCategoryArray.length;
				document.editTestCategoryForm.newTestType.length = null;
				addOption(document.editTestCategoryForm.newTestType, '', 'Select');

				for(i=0;i<totalOtherCategories;i++) { 
					addOption(document.editTestCategoryForm.newTestType, otherCategoryArray[i].testTypeCategoryId, otherCategoryArray[i].testTypeName);
				}
				displayWindow('editTestCategory',315,250);
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getNewTestIndex() {
	form = document.testWiseMarksReportForm;
	form2 = document.editTestCategoryForm;
	if (form2.newTestType.value == '') {
		return false;
	}
	pars = generateQueryString('testWiseMarksReportForm') + '&' + generateQueryString('editTestCategoryForm');
	var url = '<?php echo HTTP_LIB_PATH;?>/ChangeTestCategory/initGetNewTestIndex.php';
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
				newTestIndex = trim(transport.responseText);
				document.getElementById('newTestIndexSpan').innerHTML = newTestIndex;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}


function updateTestIndex() {
	form = document.testWiseMarksReportForm;
	form2 = document.editTestCategoryForm;
	if (form2.newTestType.value == '') {
		messageBox("<?php echo SELECT_NEW_TEST_TYPE;?>");
		return false;
	}

        var testIndex=document.getElementById('newTestIndexSpan').innerHTML;  
	pars = generateQueryString('testWiseMarksReportForm') + '&' + generateQueryString('editTestCategoryForm')+'&testIndex='+testIndex; 
	var url = '<?php echo HTTP_LIB_PATH;?>/ChangeTestCategory/updateTestIndex.php';
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
				res = trim(transport.responseText);
				messageBox(res);
				if (res == "<?php echo SUCCESS;?>") {
					hiddenFloatingDiv('editTestCategory');
					sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

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
	path='<?php echo UI_HTTP_PATH;?>/testWiseMarksReportPrint.php?class1='+form.degree.value+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value;
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
                  //if(j[i].hasMarks==1 ) {
					addOption(document.testWiseMarksReportForm.subjectId, j[i].subjectId, j[i].subjectCode);
                  //}
				}
				// now select the value
				document.testWiseMarksReportForm.subjectId.value = '';
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
    require_once(TEMPLATES_PATH . "/ChangeTestCategory/listChangeTestCategory.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: changeTestCategory.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/30/09   Time: 4:40p
//Created in $/LeapCC/Interface
//file added for change test category
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
