<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in attendanceMissedReport Form
//
//
// Author :Ajinder Singh
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MarksStatusReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Marks Status Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), new Array('className','Degree','width=22%','',true), new Array('subjectCode','Subject','width="10%"','',true), Array('employeeName','Faculty','width="15%"','',true), new Array('groupShort','Group','width="10%"','',true), new Array('testTypeName','Test Type','width="10%"','',true), new Array('testAbbr','Test Abbr.','width="10%"',"",true), new Array('maxMarks','M.M.','width="6%"',"align='right'",true), new Array('studentCount','Students','width="8%"',"align='right'",true)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetMarksStatusReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'attendanceMissedForm'; // name of the form which will be used for search
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
    var fieldsArray = new Array(new Array("labelId","<?php echo SELECT_TIMETABLE;?>"),new Array("degree","<?php echo SELECT_DEGREE;?>"),new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));

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

	sortField = 'className';
	sortOrderBy    = 'Asc';

	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

//	openStudentLists(frm.name,'class','Asc');    
}

function resetSubject() {
	hideDetails();
	if(document.attendanceMissedForm.degree.value != "") {
		document.attendanceMissedForm.subjectId.length = null;
		addOption(document.attendanceMissedForm.subjectId, 'all', 'All');
		getSubjects();
		document.attendanceMissedForm.subjectId.selectedIndex = 0;
	}
}
function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function printReport() {
	form = document.attendanceMissedForm;
	path='<?php echo UI_HTTP_PATH;?>/marksStatusReportPrint.php?labelId='+form.labelId.value+'&degree='+form.degree.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"MarksStatusReportPrint","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    form = document.attendanceMissedForm;
	path='<?php echo UI_HTTP_PATH;?>/marksStatusReportCSV.php?labelId='+form.labelId.value+'&degree='+form.degree.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}
function getSubjects() {
	hideDetails();
	if(document.attendanceMissedForm.degree.value == "") {
		return false;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassSubjects.php';
	frm = document.attendanceMissedForm;
	var pars = generateQueryString('attendanceMissedForm');
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
				document.attendanceMissedForm.subjectId.length = null;
				addOption(document.attendanceMissedForm.subjectId, '', 'Select');
//				addOption(document.attendanceMissedForm.subjectId, '', 'Select');
				if (len > 0) {
					addOption(document.attendanceMissedForm.subjectId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
                    if(j[i].hasAttendance==1) {   
					  addOption(document.attendanceMissedForm.subjectId, j[i].subjectId, j[i].subjectCode);
                    }
				}
				// now select the value
				//document.attendanceMissedForm.subjectId.value = j[0].subjectId;
		 },
		 onFailure: function(){messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getTimeTableClasses() {
	hideDetails();
	form = document.attendanceMissedForm;
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
    require_once(TEMPLATES_PATH . "/StudentReports/listMarksStatusReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: marksStatusReport.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 1/19/10    Time: 1:24p
//Updated in $/LeapCC/Interface
//fixed bugs. FCNS No. 1096
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 1/13/10    Time: 12:05p
//Updated in $/LeapCC/Interface
//fixed bugs: 2590,2591,2593,2594
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/29/09   Time: 4:04p
//Updated in $/LeapCC/Interface
//corrected window title
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/29/09   Time: 1:55p
//Created in $/LeapCC/Interface
//file added for marks status report
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 11/20/09   Time: 12:47p
//Updated in $/LeapCC/Interface
//fixed bug no.0002026
//
//*****************  Version 10  *****************
//User: Rahul.nagpal Date: 11/17/09   Time: 5:21p
//Updated in $/LeapCC/Interface
//#2027 resolved
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Interface
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/04/09    Time: 12:46p
//Updated in $/LeapCC/Interface
//Gurkeerat: corrected title of page
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Interface
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/31/09    Time: 4:57p
//Updated in $/LeapCC/Interface
//added time table label.
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/27/09    Time: 10:19a
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001254, 0001253, 0001243 and put time table in reports
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/07/09    Time: 4:31p
//Updated in $/LeapCC/Interface
//Gurkeerat: Updated access defines
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/28/09    Time: 5:21p
//Updated in $/LeapCC/Interface
//changed 'class' variable to 'degree' as this was causing problems in
//IE6
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
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/18/08    Time: 7:03p
//Updated in $/Leap/Source/Interface
//improved page design and applied new buttons
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/09/08    Time: 10:47a
//Updated in $/Leap/Source/Interface
//updated the code of ajax request, and changed messages
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:32p
//Updated in $/Leap/Source/Interface
//corrected report
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/29/08    Time: 1:04p
//Created in $/Leap/Source/Interface
//file added for "attendance not entered report"

?>
