<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in attendanceMissedReport Form
//
//
// Author :Ajinder Singh
// Created on : 05-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MarksNotEntered');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Marks Entered Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), new Array('className','Class','width=25%  align=left',' align=left',false), new Array('subjectCode','Subject','width="20%"  align=left',' align=left',false), new Array('groupName','Group','width="10%"  align=left',' align=left',false), new Array('employeeName','Faculty','width="20%"  align=left',' align=left',false), new Array('testName','Test Name','width="20%"  align=left',' align=left',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initMarksNotEnteredReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = 1000000000;//No paging to be shown. //<?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'marksNotEnteredForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'Asc';
 //This function Validates Form 


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"),new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));

    if (frm.degree.value != 'all') {
		var len = fieldsArray.length;
		for(i=0;i<len;i++) {
			if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
				messageBox(fieldsArray[i][1]);
				eval("frm."+(fieldsArray[i][0])+".focus();");
				return false;
				break;
			}
		}
	}

	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

//	openStudentLists(frm.name,'class','Asc');    
}

function resetSubject() {
	if(document.marksNotEnteredForm.degree.value == "all" || document.marksNotEnteredForm.degree.value == "") {
		document.marksNotEnteredForm.subjectId.length = null;
		addOption(document.marksNotEnteredForm.subjectId, '', 'Select');
		document.marksNotEnteredForm.groupId.length = null;
		addOption(document.marksNotEnteredForm.groupId, '', 'Select');
	}
	else {
		getSubjects();
		document.marksNotEnteredForm.subjectId.selectedIndex = 0;
	}
}

function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function printReport() {
	form = document.marksNotEnteredForm;
	path='<?php echo UI_HTTP_PATH;?>/marksNotEnteredReportPrint.php?degree='+form.degree.value+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"MarksNotEnteredReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    form = document.marksNotEnteredForm;
	path='<?php echo UI_HTTP_PATH;?>/marksNotEnteredReportCSV.php?degree='+form.degree.value+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

function getSubjects() {
	getGroups();
	document.marksNotEnteredForm.groupId.selectedIndex = 0;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetSubjects.php';
	frm = document.marksNotEnteredForm.name;
	var pars = generateQueryString(frm);
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
				document.marksNotEnteredForm.subjectId.length = null;
				addOption(document.marksNotEnteredForm.subjectId, '', 'Select');
				addOption(document.marksNotEnteredForm.subjectId, 'all', 'All');
				for(i=0;i<len;i++) { 
                  if(j[i].hasMarks==1) {   
					addOption(document.marksNotEnteredForm.subjectId, j[i].subjectId, j[i].subjectCode);
                  }
				}
				// now select the value
				document.marksNotEnteredForm.subjectId.value = j[0].subjectId;
		 },
		 onFailure: function(){messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getGroups() {
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetAllGroups.php';
	frm = document.marksNotEnteredForm.name;
	var pars = generateQueryString(frm);
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
				document.marksNotEnteredForm.groupId.length = null;
				addOption(document.marksNotEnteredForm.groupId, '', 'Select');
				addOption(document.marksNotEnteredForm.groupId, 'all', 'All');
				for(i=0;i<len;i++) { 
					addOption(document.marksNotEnteredForm.groupId, j[i].groupId, j[i].groupName);
				}
				// now select the value
				document.marksNotEnteredForm.groupId.value = j[0].groupId;
		 },
		 onFailure: function(){messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}


function getTimeTableClasses() {
	form = document.marksNotEnteredForm;
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
			if (len > 0) {
				addOption(form.degree, 'all', 'All');
			}
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
    require_once(TEMPLATES_PATH . "/StudentReports/listMarksNotEnteredReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: marksNotEnteredReport.php $
//
//*****************  Version 11  *****************
//User: Gurkeerat    Date: 11/16/09   Time: 10:54a
//Updated in $/LeapCC/Interface
//UPDATED TITLE OF PAGE
//
//*****************  Version 10  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Interface
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Interface
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 9/01/09    Time: 2:07p
//Updated in $/LeapCC/Interface
//report testing and changing view part.
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Interface
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/08/09    Time: 12:03p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 5/28/09    Time: 5:55p
//Updated in $/LeapCC/Interface
//changed 'class' variable to 'degree' to make it working in IE6.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/31/09    Time: 3:00p
//Updated in $/LeapCC/Interface
//flow modified.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 3/16/09    Time: 2:29p
//Updated in $/LeapCC/Interface
//files modified to check and make correction to non working part.
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
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/09/08    Time: 4:56p
//Updated in $/Leap/Source/Interface
//applied code to make it working in IE.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/05/08    Time: 1:19p
//Updated in $/Leap/Source/Interface
//removed sorting.
//


?>
