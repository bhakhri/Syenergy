<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
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
define('MODULE','StudentAttendance');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Attendance Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentAttendanceReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'studentAttendanceForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array(
     new Array("labelId","<?php echo SELECT_TIMETABLE;?>"),
     new Array("degree","<?php echo SELECT_DEGREE;?>"),
     new Array("subjectId","<?php echo SELECT_SUBJECT;?>")
    );

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
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}


function printReport() {
	form = document.studentAttendanceForm;
	path='<?php echo UI_HTTP_PATH;?>/attendanceReportPrint.php?degree='+form.degree.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	a = window.open(path,"StudentAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");

}

function printCSV() {
	form = document.studentAttendanceForm;
    //var qstr="searchbox="+trim(document.searchBox1.searchbox.value);
    //qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/attendanceReportPrintCSV.php?degree='+form.degree.value+'&subjectId='+form.subjectId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function getClassSubjects() {
	form = document.studentAttendanceForm;
	var degree = form.degree.value;
	var labelId = form.labelId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassAttendanceSubject.php';
	var pars = 'timeTable='+labelId+'&degree='+degree;
	if (degree == '') {
		document.studentAttendanceForm.subjectId.length = null;
		addOption(document.studentAttendanceForm.subjectId, '', 'Select');
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
				document.studentAttendanceForm.subjectId.length = null;
				addOption(document.studentAttendanceForm.subjectId, '', 'Select');
				/*
				if (len > 0) {
					addOption(document.studentAttendanceForm.subjectId, 'all', 'All');
				}
				*/
				for(i=0;i<len;i++) { 
				  addOption(document.studentAttendanceForm.subjectId, j[i].subjectId, j[i].subjectCode);
				}
				// now select the value
				//document.studentAttendanceForm.subjectId.value = j[0].subjectId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function getTimeTableClasses() {
	form = document.studentAttendanceForm;
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
		asynchronous:false,
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
			form.degree.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

	getClassSubjects();
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
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentAttendanceReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
<script language="javascript">  
    var cnt = document.studentAttendanceForm.attendanceCodeId.length;   
    
    var rowspan1='width="3%"';
    var rowspan='width="10%"';
    var colspan = "width='10%'";
    if(cnt==1) {
      rowspan  += " rowspan=2";
      rowspan1 += " rowspan=2";    
    }  
    else if(cnt>1) {
      rowspan  += " rowspan=2";
      rowspan1 += " rowspan=2";  
      colspan  += " colspan="+cnt;  
    }
    
    var tableHeadArray = new Array(new Array('srNo','#',rowspan1,'',false), 
                                   new Array('rollNo','Roll No.',rowspan,'',true), 
                                   new Array('universityRollNo','Univ. Roll No.',rowspan,'',true), 
                                   new Array('studentName','Student Name',rowspan,'',true),
                                   new Array('lectureDelivered','Lect. Delivered',rowspan,'align="right"',false), 
                                   new Array('lectureAttended','Lect. Attended',rowspan,'align="right"',false), 
                                   new Array('Percentage','Percentage',rowspan,'align="right"',false));
    tableHeadArray.push(new Array('aa','Attendance Code',colspan,'align="center"',false,true));                                   
    var cnt = document.studentAttendanceForm.attendanceCodeId.length;
    if(cnt>0) {
      for(var i=0;i<cnt;i++) {
        id = 'att'+document.studentAttendanceForm.attendanceCodeId.options[i].value;  
        str =  document.studentAttendanceForm.attendanceCodeId.options[i].text;
        tableHeadArray.push(new Array(id,str,'width="5%"','align="center"',true));
      } 
    } 
</script>
</html>

<?php 

////$History: studentAttendanceReport.php $
//
//*****************  Version 14  *****************
//User: Parveen      Date: 3/22/10    Time: 10:36a
//Updated in $/LeapCC/Interface
//function updated getTimeTableClasses
//
//*****************  Version 13  *****************
//User: Gurkeerat    Date: 4/02/10    Time: 18:57
//Updated in $/LeapCC/Interface
//resolved issues 0002650,0002620,0002098,0001602,0002788,0002785
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 2/03/10    Time: 1:45p
//Updated in $/LeapCC/Interface
//fixed issue 0002667
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 2/03/10    Time: 1:38p
//Updated in $/LeapCC/Interface
//fixed issue 0001958
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 1/14/10    Time: 4:36p
//Updated in $/LeapCC/Interface
//corrected code, called time-table labelId in ajax request
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
//User: Jaineesh     Date: 8/27/09    Time: 10:19a
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001254, 0001253, 0001243 and put time table in reports
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/08/09    Time: 12:03p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 6/01/09    Time: 6:43p
//Updated in $/LeapCC/Interface
//corrected from class1 to degree as part of checking/fixing all reports.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 5/28/09    Time: 5:20p
//Updated in $/LeapCC/Interface
//removed study period from form.
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/17/08   Time: 11:14a
//Updated in $/LeapCC/Interface
//Added define('MANAGEMENT_ACCESS',1); for management access
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 8/25/08    Time: 6:32p
//Updated in $/Leap/Source/Interface
//code applied for reducing unnecessary server trip
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 8/18/08    Time: 5:55p
//Updated in $/Leap/Source/Interface
//file modified for setting print button and improving design.
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/09/08    Time: 10:47a
//Updated in $/Leap/Source/Interface
//updated the code of ajax request, and changed messages
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:05p
//Updated in $/Leap/Source/Interface
//done minor changes
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/07/08    Time: 6:23p
//Updated in $/Leap/Source/Interface
//removed field "lectures missed" as told
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/06/08    Time: 2:10p
//Updated in $/Leap/Source/Interface
//removed unused code
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/06/08    Time: 2:04p
//Updated in $/Leap/Source/Interface
//file changed for making it as per new format
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/02/08    Time: 5:19p
//Updated in $/Leap/Source/Interface
//done cosmetic changes
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 7/29/08    Time: 11:08a
//Updated in $/Leap/Source/Interface
//made the changes as per new query
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/25/08    Time: 2:38p
//Updated in $/Leap/Source/Interface
//file updated and made working with new attendance table
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/18/08    Time: 4:33p
//Updated in $/Leap/Source/Interface
//done the coding for report printing part
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/17/08    Time: 7:26p
//Updated in $/Leap/Source/Interface
//done the coding for studentAttendanceReport completion
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/17/08    Time: 10:30a
//Created in $/Leap/Source/Interface
//File made for : StudentAttendanceReport
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/15/08    Time: 12:39p
//Created in $/Leap/Source/Interface
//Added one new file for StudentLabels Report Module


?>
