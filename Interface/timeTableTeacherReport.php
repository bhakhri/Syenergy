<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
//
//
// Author :Jaineesh
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TimeTableTeacher');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Taught By Teacher Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false), 
								new Array('employeeName','Teacher','width=10%','',true), 
								new Array('className','Class','width="12%"','',true), 
								new Array('labelName','Time Table Label Name','width="12%"','',true), 
								new Array('subjectCode','Subject','width="18%"','align="left"',true));

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/TimeTable/initTimeTableTeacherReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'timeTableForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'employeeName';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array(
		new Array("labelId","<?php echo SELECT_TIMETABLE;?>"),
		new Array("degree","<?php echo SELECT_DEGREE;?>")
		//new Array("subjectId","<?php echo SELECT_SUBJECT;?>"),
		//new Array("employeeId","<?php echo SELECT_EMPLOYEE;?>")
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
	form = document.timeTableForm;
	path='<?php echo UI_HTTP_PATH;?>/timeTableReportPrint.php?timeTableLabelId='+form.labelId.value+'&classId='+form.degree.value+'&subjectId='+form.subjectId.value+'&employeeId='+form.employeeId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	a = window.open(path,"StudentAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	form = document.timeTableForm;
    //var qstr="searchbox="+trim(document.searchBox1.searchbox.value);
    //qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/timeTableReportPrintCSV.php?timeTableLabelId='+form.labelId.value+'&classId='+form.degree.value+'&subjectId='+form.subjectId.value+'&employeeId='+form.employeeId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function getClassSubjects() {
	form = document.timeTableForm;
	var degree = form.degree.value;
	var labelId = form.labelId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/initTimeTableClassSubjects.php';
	var pars = 'labelId='+labelId+'&degree='+degree;
	if (degree == '') {
		document.timeTableForm.subjectId.length = null;
		addOption(document.timeTableForm.subjectId, '', 'Select');
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
				if(j==0) {
					form.subjectId.length = null;
					addOption(form.subjectId, '', 'Select');
					return false;
				}
				
				len = j.length;
				
				document.timeTableForm.subjectId.length = null;
				
				if (len > 0) {
					addOption(document.timeTableForm.subjectId, '0', 'All');
				}
				
				for(i=0;i<len;i++) { 
					  addOption(document.timeTableForm.subjectId, j[i].subjectId, j[i].subjectCode);
				}
				// now select the value
				//document.studentAttendanceForm.subjectId.value = j[0].subjectId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function getClassTeacher() {
	form = document.timeTableForm;
	var degree = form.degree.value;
	var labelId = form.labelId.value;
	var subjectId = form.subjectId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/initTimeTableClassTeachers.php';
	var pars = 'labelId='+labelId+'&degree='+degree;
	if (degree == '') {
		document.timeTableForm.employeeId.length = null;
		addOption(document.timeTableForm.employeeId, '', 'Select');
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
				if(j==0) {
					form.employeeId.length = null;
					addOption(form.employeeId, '', 'Select');
					return false;
				}

				len = j.length;
				document.timeTableForm.employeeId.length = null;
				
				if (len > 0) {
					addOption(document.timeTableForm.employeeId, '0', 'All');
				}
				
				for(i=0;i<len;i++) { 
					  addOption(document.timeTableForm.employeeId, j[i].employeeId, j[i].employeeName);
				}
				// now select the value
				//document.studentAttendanceForm.subjectId.value = j[0].subjectId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function getTimeTableClasses() {
	form = document.timeTableForm;
	form.subjectId.length = null;
	addOption(form.subjectId, '', 'Select');
	form.employeeId.length = null;
	addOption(form.employeeId, '', 'Select');

	var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/initGetAllClasses.php';
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
			form.degree.length = len;
			if(form.degree.length == 0) {
				addOption(form.degree, '', 'Select');
			}
			else {
				form.degree.length = null;	
				addOption(form.degree, '', 'Select');
				addOption(form.degree, '0', 'ALL');
				for(i=0;i<len;i++) {
					addOption(form.degree, j[i].classId, j[i].className);
				}
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
    require_once(TEMPLATES_PATH . "/TimeTable/listTimeTableTeacherReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History:  $
//
?>