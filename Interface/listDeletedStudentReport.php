<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Offense Report
//
//
// Author :Jaineesh
// Created on : 15.04.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DeletedStudentReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Deleted Students Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','align="left"',false),
								new Array('rollNo','Roll No.','width=8%','align="left"',true),
								new Array('studentName','Student Name','width=30%','align="left"',true),
								new Array('className','Class Name','width="30%"','align=left',true),
								/*new Array('viewAttendanceDetail','Attendance','width="8%"','align=center',false),
								new Array('viewTestMarksDetail','Test Marks','width="8%"','align=center',false),
								new Array('viewFinalResultDetail','Final Result','width="8%"','align=center',false)*/
								new Array('act','Action','width="10%"','align=center',false)

							);

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initDeletedStudentListReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'searchForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';

function validateAddForm() {
	//openStudentLists(frm.name,'rollNo','Asc');    
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}


function printAttendanceReport(studentId) {
	path='<?php echo UI_HTTP_PATH;?>/deleteStudentAttendanceReportPrint.php?studentId='+studentId;
	a = window.open(path,"StudentAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printTestMarksReport(studentId) {
	path='<?php echo UI_HTTP_PATH;?>/deleteStudentTestMarks.php?studentId='+studentId;
	a = window.open(path,"StudentTestMarksReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printFinalResultReport(studentId) {
	path='<?php echo UI_HTTP_PATH;?>/deleteStudentFinalResult.php?studentId='+studentId;
	a = window.open(path,"StudentFinalResultReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    var qstr="noOfOffense="+trim(document.OffenseReportForm.noOfOffense.value);
    qstr=qstr+"&instances="+document.OffenseReportForm.instances.value+"&offenseCategory="+document.OffenseReportForm.offenseCategory.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path='<?php echo UI_HTTP_PATH;?>/offenseListReportCSV.php?'+qstr;
	window.location = path;
}

window.onload = function() {
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listDeletedStudentReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: $
//
?>