<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in total marks report
//
//
// Author :Ajinder Singh
// Created on : 28-nov-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TotalMarksReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:Total Marks Report (Post Transfer) </title>
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

searchFormName = 'totalMarksReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
	page = 1;
    var fieldsArray = new Array(new Array("labelId","<?php echo SELECT_LABEL;?>"), new Array("class1","<?php echo SELECT_DEGREE;?>"));

	form = document.totalMarksReportForm;

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
}

function showReport(page) {
	form = document.totalMarksReportForm;
	var class1 = form.class1.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initTotalMarksReport.php';
	var sorting = form.sorting.value;
    var ordering = form.ordering[0].checked==true?'asc':'desc';
	var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
	var pars = 'class1='+class1+'&page='+page+'&showGraceMarks='+showGraceMarks+'&sorting='+sorting+'&ordering='+ordering;
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
			    totalStudents = j['totalStudents'];
				totalSubjects = j['subjects'].length;

				if (totalStudents == 0) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					var tableData = globalTB;
					tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text ">#</td><td width="8%" class="searchhead_text ">Roll No.</td><td width="20%" class="searchhead_text ">Student Name</td>';
					tableData += '<td width=62% class="searchhead_text ">&nbsp;</td></tr><tr '+bg+'>';
					tableData += '<td class="padding_top" align=center colspan=5>No details found</td></tr>';
				}
				else {
					var tableData = globalTB;
					tableData += '<tr class="rowheading"><td rowspan="4" width="2%" class="searchhead_text ">#</td><td rowspan="4" width="8%" class="searchhead_text ">Roll No.</td><td width="15%" rowspan="4" class="searchhead_text ">Student Name</td>';

					allTests = 0;
					for(i=0;i<totalSubjects;i++) {
						subjectId = j['subjects'][i]['subjectId'];
						subjectCode = j['subjects'][i]['subjectCode'];
						tableData += '<td align="center" class=" searchhead_text" colspan = "5">'+subjectCode+'&nbsp;</td>';
						allTests += 4;
					}
					perTestSpace = parseInt(75/allTests)+'%';
					tableData += '</tr>';
					tableData += '<tr class="rowheading">';
					for(i=0;i<totalSubjects;i++) {
						subjectId = j['subjects'][i]['subjectId'];
						subjectCode = j['subjects'][i]['subjectName'];
						tableData += '<td align="center" class=" searchhead_text" colspan = "5">'+subjectCode+'&nbsp;</td>';
					}
					tableData += '</tr>';

					tableData += '<tr class="rowheading">';
					for(i=0;i<totalSubjects;i++) {
						tableData += '<td align="center" class=" searchhead_text">A</td>';
						tableData += '<td align="center" class=" searchhead_text">I</td>';
						tableData += '<td align="center" class=" searchhead_text">E</td>'; 
                        tableData += '<td align="center" class=" searchhead_text">T</td>';
						tableData += '<td align="center" class=" searchhead_text">G</td>';
					}
					tableData += '</tr>';

					tableData += '<tr class="rowheading">';
					for(i=0;i<parseInt(totalSubjects);i++) {
						subjectId = j['subjects'][i]['subjectId'];
						internal = j['subjects'][i]['internal'];
						externalMarks = j['subjects'][i]['externalMarks'];
						attendance = j['subjects'][i]['attendance'];    
                        
                        totMarks='0';
						if (attendance == null) {
							attendance = "-";
						}
						else {
							attendance = Math.round(attendance * 10) / 10;  
                            totMarks = parseFloat(attendance,10);
						}
						if (internal == null) {
							internal = "-";
						}
						else {
							internal = Math.round(internal * 10) / 10;
                            totMarks = parseFloat(totMarks,10) + parseFloat(internal,10);
						}
						if (externalMarks == null) {
							externalMarks = "-";
						}
						else {
							externalMarks = Math.round(externalMarks * 10) / 10;
                            totMarks = parseFloat(totMarks,10) + parseFloat(externalMarks,10);
						}   
                        
                        if(totMarks=='0') {
                          totMarks='';  
                        }           
                        
                        tableData += '<td align="center" class=" searchhead_text" colspan = "1">'+attendance+'&nbsp;</td>';
						tableData += '<td align="center" class=" searchhead_text" colspan = "1">'+internal+'&nbsp;</td>';
						tableData += '<td align="center" class=" searchhead_text" colspan = "1">'+externalMarks+'&nbsp;</td>'; 
                        tableData += '<td align="center" class=" searchhead_text" colspan = "1">'+totMarks+'</td>'; 
						tableData += '<td align="center" class=" searchhead_text" colspan = "1">&nbsp;</td>';
					}
					tableData += '</tr>';


					totalRecords = j['resultData'].length;
					recordCounter = (page - 1) * parseInt("<?php echo RECORDS_PER_PAGE; ?>");

					for(t=0; t<totalRecords;t++) {
						recordCounter++;
                        var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						var bg2 = bg2 == "row0" ? "row1" : "row0";
						tableData += '<tr '+bg+' onmouseover="if(this.className != \'specialHighlight\') this.className=\'highlightPermission\'" onmouseout="if(this.className != \'specialHighlight\') this.className=\''+bg2+'\'">';

						tableData += '<td align="left" class=" ">'+recordCounter+'&nbsp;</td>';
						//tableData += '<td align="left" class=" ">'+j['resultData'][t]['srNo']+'&nbsp;</td>';
						tableData += '<td align="left" class=" ">'+j['resultData'][t]['rollNo']+'&nbsp;</td>';
						tableData += '<td align="left" class=" ">'+j['resultData'][t]['studentName']+'&nbsp;</td>';
						for(i=0;i<totalSubjects;i++) {
							subjectId = j['subjects'][i]['subjectId'];
							alias = subjectId+'_Marks';
							aliasArray = '';
                            totMks='0';
							//alert(j['resultData'][t][alias]);
							if (j['resultData'][t][alias] == null) {
								j['resultData'][t][alias] = '';
								tableData += '<td width="'+perTestSpace+'" align="right" class="">&nbsp;</td>';
								tableData += '<td width="'+perTestSpace+'" align="right" class="">&nbsp;</td>';
								tableData += '<td width="'+perTestSpace+'" align="right" class="">&nbsp;</td>';
                                tableData += '<td width="'+perTestSpace+'" align="right" class="">&nbsp;</td>';
								tableData += '<td width="'+perTestSpace+'" align="right" class="">&nbsp;</td>';
							}
							else {
								aliasArray = j['resultData'][t][alias].split('#');
								/*
								if (aliasArray[0] == -1) {
									aliasArray[0] = "A";
								}
								else if (aliasArray[0] == -2) {
									aliasArray[0] = "UMC";
								}
								if (aliasArray[1] == -1) {
									aliasArray[1] = "A";
								}
								else if (aliasArray[1] == -2) {
									aliasArray[1] = "UMC";
								}
								*/
                                tableData += '<td width="'+perTestSpace+'" align="right" class="">'+aliasArray[2]+'&nbsp;</td>';
								tableData += '<td width="'+perTestSpace+'" align="right" class="">'+aliasArray[0]+'&nbsp;</td>';
								tableData += '<td width="'+perTestSpace+'" align="right" class="">'+aliasArray[1]+'&nbsp;</td>';
                                tableData += '<td width="'+perTestSpace+'" align="right" class="">'+aliasArray[4]+'&nbsp;</td>'; 
								tableData += '<td width="'+perTestSpace+'" align="center" class="">'+aliasArray[3]+'&nbsp;</td>'; 
							}
						}
						tableData += '</tr>';
					}
				}
				tableData += "</table>";

				document.getElementById("resultRow").style.display = '';
				document.getElementById("resultsDiv").innerHTML = tableData;
				pagingData='';
				totalPages = totalStudents / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
				completePages = parseInt(totalStudents / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
				if (totalPages > completePages) {
					completePages++;
				}
				pagingData = pagination2(page, totalStudents, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
				document.getElementById("pagingDiv").innerHTML = pagingData;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

}

function resetStudyPeriod() {
	document.totalMarksReportForm.subjectTypeId.selectedIndex = 0;
}

function printReport() {
	form = document.totalMarksReportForm;
	var class1 = form.class1.value;
	var sorting = form.sorting.value;
    var ordering = form.ordering[0].checked==true?'asc':'desc';
	var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
	path='<?php echo UI_HTTP_PATH;?>/totalMarksReportPrint.php?class1='+class1+'&showGraceMarks='+showGraceMarks+'&sorting='+sorting+'&ordering='+ordering;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900");
}
function printCSV() {
	form = document.totalMarksReportForm;
	var class1 = form.class1.value;
	var sorting = form.sorting.value;
    var ordering = form.ordering[0].checked==true?'asc':'desc';
	var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
	window.location='totalMarksReportPrintCSV.php?class1='+class1+'&showGraceMarks='+showGraceMarks+'&sorting='+sorting+'&ordering='+ordering;
	//document.getElementById('generateCSV').href='scTotalMarksReportPrintCSV.php?class1='+class1;
	//document.getElementById('generateCSV2').href='scTotalMarksReportPrintCSV.php?class1='+class1;
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function getMarksTotalClasses() {
	hideResults();
	form = document.totalMarksReportForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/getMarksTotalClasses.php';
	var pars = 'labelId='+form.labelId.value;

	if (form.labelId.value=='') {
		form.class1.length = null;
		addOption(form.class1, '', 'Select');
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
			form.class1.length = null;
			addOption(form.class1, '', 'Select');
			/*
			if (len > 0) {
				addOption(form.class1, 'all', 'All');
			}
			*/

			for(i=0;i<len;i++) {
				addOption(form.class1, j[i].classId, j[i].className);
			}
			// now select the value
			form.class1.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

window.onload = function() {
	document.getElementById('labelId').focus();
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listTotalMarksReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php

//$History: scTotalMarksReport.php $
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 3/16/10    Time: 6:59p
//Updated in $/Leap/Source/Interface
//fixed issues. FCNS No.1423
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 1/23/10    Time: 1:22p
//Updated in $/Leap/Source/Interface
//done changes. FCNS NO. 1134
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 11/26/09   Time: 3:15p
//Updated in $/Leap/Source/Interface
//done changes in total marks report.
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 4/04/09    Time: 4:31p
//Updated in $/Leap/Source/Interface
//added code to highlight row dynamically
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 3/27/09    Time: 11:25a
//Updated in $/Leap/Source/Interface
//changed image source to input type
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 3/10/09    Time: 12:33p
//Updated in $/Leap/Source/Interface
//files modified as per database changes in sc_test,
//total_transferred_marks tables
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 2/11/09    Time: 2:01p
//Updated in $/Leap/Source/Interface
//added code for attendance
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/17/08   Time: 1:48p
//Updated in $/Leap/Source/Interface
//changed the flow to make the report faster
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/28/08   Time: 1:44p
//Created in $/Leap/Source/Interface
//file added for total marks report
//


?>
