<?php

//-------------------------------------------------------
//  This File contains Validation and ajax function used in student performance report Form
//
//
// Author :Ajinder Singh
// Created on : 29-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentPerformanceReport');
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
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=right','align=right',false), 
                               new Array('conductingAuthority2','Conducting Authority','width=18% align=left','align=left',true), 
                               new Array('subjectCode','Subject','width="15%" align=left','align=left',true), 
                               new Array('testTypeName','Test Type','width="18%" align="left"','align="left"',false), 
                               new Array('weightageAmount','Weightage Amt.','width="18%" align="right"','align="right"',false), 
                               new Array('weightagePercentage','Weightage %','width="18%" align="right"','align="right"',false));

 //This function Validates Form
var divResultName = 'resultsDiv';


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'studentPerformanceReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
	
    form = document.studentPerformanceReportForm;
    var rollNo = form.rollNo.value;
    
    if(trim(rollNo)=='') {
       messageBox("<?php echo ENTER_ROLLNO;?>");
       form.rollNo.focus();
       return false;
    }
    hideResults();

	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';

	showReport();
	//sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function showReport() {
	form = document.studentPerformanceReportForm;
	var rollNo = form.rollNo.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentPerformanceReport.php';
	var pars = 'rollNo='+rollNo;
	var tableData = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:0px solid #9F9F9F;">';


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
			var res = eval('(' + transport.responseText + ')');
			tableData += "<tr height='25'><td align='right' width='50%'><b>Student Name : </b></td><td>&nbsp;"+res['studentDetails'][0]['firstName']+'  '+res['studentDetails'][0]['lastName']+"</td></tr>";
			tableData += "<tr  height='25'><td align='right'  width='50%'><b>Degree  : </b></td><td>&nbsp;"+res['studentDetails'][0]['className']+"</td></tr>";
			totalSubjectTypes = res['subjectTypes'].length;
			for(i=0;i<totalSubjectTypes;i++) {
				//print report column headers
				subjectTypeName = res['subjectTypes'][i]['subjectTypeName'];
				tableData += "<tr><td colspan='2'>";
				tableData += "<table border='0' width='100%'>";
				tableData += "<tr><td class='searchhead_text1'><b>"+subjectTypeName+"</b></td></tr>";
				tableData += "</table>";
				tableData += "</td></tr>";
				tableData += "<tr><td colspan='2'>";
				tableData += "<table border='1' width='100%' class='reportBorder' rules='all'>";
				totalTestTypes = res[subjectTypeName+'#testTypes'].length;
				tableData += "<tr class='rowheading'><td rowspan='2' class='searchhead_text' align='left'>&nbsp;Subjects</td>";
				for(j=0;j<totalTestTypes;j++) {
					testTypeName = res[subjectTypeName+'#testTypes'][j]['testTypeName'];
					evCriteria = res[subjectTypeName+'#testTypes'][j]['evaluationCriteriaId'];
					if (evCriteria == 5 || evCriteria == 6) {
						//attendance
						tableData += "<td colspan='3' class='searchhead_text'>Lectures</td>";
					}
					else {
						testTypeCount = res[testTypeName+'#testCount'];
						thisCount = parseInt(testTypeCount) + 1;
						tableData += "<td  class='searchhead_text' align='center' colspan='"+thisCount+"'>"+testTypeName+"</td>";
					}
				}
				tableData += "<td rowspan='2' align='right' class='searchhead_text'>Total&nbsp;</td></tr>";
				//print second row
				tableData += "<tr>";
				for(j=0;j<totalTestTypes;j++) {
					testTypeName = res[subjectTypeName+'#testTypes'][j]['testTypeName'];
					evCriteria = res[subjectTypeName+'#testTypes'][j]['evaluationCriteriaId'];
					if (evCriteria == 5 || evCriteria == 6) {
						//attendance
						tableData += "<td align='right' ><B>Held</B>&nbsp;</td><td align='right' ><B>Attended</B>&nbsp;</td><td align='right' ><B>Total</B>&nbsp;</td>";
					}
					else {
						testTypeCount = res[testTypeName+'#testCount'];
						for(k=0;k<testTypeCount;k++) {
							tableData += "<td align='right'><B>"+res[testTypeName+'#tests'][k]['testName']+"</B>&nbsp;</td>";
						}
						tableData += "<td align='right'><B>Total</B>&nbsp;</td>";
					}
				}
				tableData += "</tr>";

				//print report data
				totalSubjects = res[subjectTypeName+'#subjects'].length;
				for(k=0;k<totalSubjects;k++) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					totalMarks = 0;
					subjectCode = res[subjectTypeName+'#subjects'][k]['subjectCode'];
					tableData += '<tr '+bg+'>';
					tableData += "<td align='left'>&nbsp;"+subjectCode+"</td>";

					for(j=0;j<totalTestTypes;j++) {
						testTypeName = res[subjectTypeName+'#testTypes'][j]['testTypeName'];
						evCriteria = res[subjectTypeName+'#testTypes'][j]['evaluationCriteriaId'];
						if (evCriteria == 5 || evCriteria == 6) {
							//attendance
							tableData += "<td align='right'>"+res[subjectTypeName+'#subjects#'+subjectCode+'#TestTypeParts#'+testTypeName][0]['lectureDelivered']+"&nbsp;</td>";
							tableData += "<td align='right'>"+res[subjectTypeName+'#subjects#'+subjectCode+'#TestTypeParts#'+testTypeName][0]['lectureAttended']+"&nbsp;</td>";
							tableData += "<td align='right'>"+res[subjectTypeName+'#subjects#'+subjectCode+'#TestTypeMain#'+testTypeName][0]['marksScored']+"&nbsp;</td>";
							totalMarks += parseFloat(res[subjectTypeName+'#subjects#'+subjectCode+'#TestTypeMain#'+testTypeName][0]['marksScored']);
						}
						else {
							testTypeTestCount = res[testTypeName+'#tests'].length;
							for(m=0;m<testTypeTestCount;m++) {
								testName = res[testTypeName+'#tests'][m]['testName'];
								testMarks = res[subjectTypeName+'#subjects#'+subjectCode+'#TestTypeParts#'+testTypeName+'#Test#'+testName][0]['marksScored'];
								tableData += "<td align='right'>"+testMarks+"&nbsp;</td>";
							}
							tableData += "<td align='right'>"+res[subjectTypeName+'#subjects#'+subjectCode+'#TestTypeMain#'+testTypeName][0]['marksScored']+"&nbsp;</td>";
							totalMarks += parseFloat(res[subjectTypeName+'#subjects#'+subjectCode+'#TestTypeMain#'+testTypeName][0]['marksScored']);
						}
					}
					tableData += "<td align='right'><B>"+totalMarks+"</B>&nbsp;</td>";
					tableData += "</tr>";
				}
			}


			tableData += "</table>";

			document.getElementById("resultRow").style.display = '';
			document.getElementById("resultsDiv").innerHTML = tableData;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

}


function printReport() {
	form = document.studentPerformanceReportForm;
	var rollNo = form.rollNo.value;
	var pars = 'rollNo='+rollNo;

	path='<?php echo UI_HTTP_PATH;?>/studentPerformanceReportPrint.php?'+pars;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900");
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

window.onload=function(){
 document.studentPerformanceReportForm.rollNo.focus();
 var roll = document.getElementById("rollNo");
 autoSuggest(roll);
}
</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentPerformanceReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php

////$History: studentPerformanceReport.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 4  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Interface
//It checks the value of hasAttendance, hasMarks field for every subject
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
//*****************  Version 3  *****************
//User: Ajinder      Date: 9/04/08    Time: 11:00a
//Updated in $/Leap/Source/Interface
//file modified and fixed bugs found during self testing
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 9/03/08    Time: 5:32p
//Updated in $/Leap/Source/Interface
//done minor improvement in text alignment
//
?>