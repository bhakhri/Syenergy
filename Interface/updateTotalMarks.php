<?php

//-------------------------------------------------------
//  This File contains Validation and ajax function used in assigning sections
//
//
// Author :Ajinder Singh
// Created on : 04-Dec-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UpdateTotalMarks');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Update Total marks </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script><?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");
?>
<script language="javascript">
var tabsShown = false;

 //This function Validates Form

var tableHeadArray = new Array(new Array('srNo','#','width="2%" ','',false), new Array('studentName','Student Name','width="20%"','',false) );

function validateAddForm(frm) {
	document.getElementById('studentNameSpan').innerHTML ='';
	form = document.updateMarksForm;
    var fieldsArray = new Array(new Array("rollNo","<?php echo ENTER_ROLLNO;?>"));

	var len = fieldsArray.length;

	for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value")==0) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	showStudentMarks(true);
}

function showStudentMarks(callHideResults) {
	if (callHideResults == true) {
		hideResults();
	}
	frm = document.updateMarksForm.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/showStudentMarks.php';
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
			fetchedData = trim(transport.responseText);
			if (fetchedData == "<?php echo INVALID_ROLL_NO; ?>") {
				messageBox(fetchedData);
				return false;
			}
			j = eval('('+ transport.responseText + ')');
			total = j.length;

			form = document.updateMarksForm;

			var tableData = globalTB;
			tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text">#</td><td width="15%" class="searchhead_text">Class</td><td width="10%" class="searchhead_text">Subject Code</td><td width="10%" class="searchhead_text">Attendance</td><td width="10%" class="searchhead_text">Internal Marks</td><td width="10%" class="searchhead_text ">External Marks</td><td width="10%" class="searchhead_text ">Total Marks</td><td width="10%" class="searchhead_text ">Grade</td><td width="5%" class="searchhead_text ">Regular</td><td width="5%" class="searchhead_text ">Re-exam</td></tr>';
			//tableData += '<td width="5%" class="searchhead_text ">Re-exam</td></tr>';

			newClassId = '';
			classIdStr = '';

			newResultHoldedClass = '';
			resultHoldedClassesStr = '';

			var studentName = j[0]['studentName'];
			document.getElementById('studentNameSpan').innerHTML = "Student Name:&nbsp;"+studentName+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Father's Name:&nbsp;"+j[0]['fatherName'];
			document.updateMarksForm.studentName.value = studentName;


			for(x = 0; x < total; x++) {
				studentId = j[x]['studentId'];
				classId = j[x]['classId'];
				if (newClassId != classId) {
					if (classIdStr != '') {
						classIdStr += ',';
					}
					classIdStr += classId+'#'+j[x]['className'];
				}
				subjectId = j[x]['subjectId'];
				holdResult = j[x]['holdResult'];

				if (holdResult == "1" || holdResult == 1) {
					if (newResultHoldedClass != classId) {
						if (resultHoldedClassesStr != '') {
							resultHoldedClassesStr += ',';
						}
						resultHoldedClassesStr += classId;
					}
				}

				var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
				var bg2 = bg2 == "row0" ? "row1" : "row0";
				tableData += '<tr id="test_'+x+'" '+bg+' onmouseover="if(this.className != \'specialHighlight\') this.className=\'highlightPermission\'" onmouseout="if(this.className != \'specialHighlight\') this.className=\''+bg2+'\'" >';
				tableData += '<td class="">'+j[x]['srNo']+'&nbsp;</td>';
				tableData += '<td class="" nowrap>'+j[x]['className']+'&nbsp;</td>';
				tableData += '<td class="">'+j[x]['subjectCode']+'&nbsp;</td>';
				tableData += '<td class="">'+j[x]['attendance']+'&nbsp;</td>';
				tableData += '<td class="">'+j[x]['preCompre']+'&nbsp;</td>';
				tableData += '<td class="">'+j[x]['compre']+'&nbsp;</td>';				
				tableData += '<td class="">'+j[x]['totalMarks']+'&nbsp;</td>'; 
				tableData += '<td class="">'+j[x]['grades']+'&nbsp;</td>';

				//tableData += '<td class=""><img src="<?php echo STORAGE_HTTP_PATH;?>/Images/editBlue.gif" onClick=\'updateGrades('+studentId+','+classId+','+subjectId+')\'"></td>';
                tableData += '<td class=""><img src="<?php echo STORAGE_HTTP_PATH;?>/Images/editBlue.gif" onClick=\'updateGrades('+studentId+','+classId+','+subjectId+')\'"></td><td><img src="<?php echo STORAGE_HTTP_PATH;?>/Images/editRed.gif" onClick=\'updateReAppear('+studentId+','+classId+','+subjectId+')\'"></td>';
				//tableData += '<td><img src="<?php echo STORAGE_HTTP_PATH;?>/Images/editRed.gif" onClick=\'updateReAppear('+studentId+','+classId+','+subjectId+')\'"></td>';
				tableData += '</tr>';
				newClassId = classId;
				newResultHoldedClass = classId
			}
			tableData += "</table>";
			classIdArray = classIdStr.split(',');
			totalClassId = classIdArray.length;
			var holdResultData = globalTB;
			holdResultData += '<tr class="rowheading"><td width="2%" class="searchhead_text">#</td><td width="20%" class="searchhead_text">Degree</td><td width="73%" class="searchhead_text ">Hold Result</td></tr>';
			for (t=0; t < totalClassId; t++) {
				classIdNameArray = classIdArray[t].split('#');
				var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
				var bg2 = bg2 == "row0" ? "row1" : "row0";
				ctr = t+1;
				holdResultData += '<tr '+bg+' onmouseover="if(this.className != \'specialHighlight\') this.className=\'highlightPermission\'" onmouseout="if(this.className != \'specialHighlight\') this.className=\''+bg2+'\'" >';
				holdResultData += '<td class="">'+ctr+'&nbsp;</td>';
				holdResultData += '<td class="">'+classIdNameArray[1]+'&nbsp;</td>';
				holdResultData += '<td class=""><input type="checkbox" name="chk_'+classIdNameArray[0]+'" />&nbsp;</td>';
				holdResultData += '</tr>';
			}
			holdResultData += "</table>";
			document.getElementById("holdResultDiv").innerHTML = holdResultData;
			//document.getElementById('holdResultDiv').style.display='';
			//document.getElementById('holdResultRow').style.display='';
			//document.getElementById('holdResultRow2').style.display='';
//			hideResults();
			document.getElementById("resultsDiv").innerHTML = tableData;
			document.getElementById('resultsDiv').style.display='';
			document.getElementById('resultRow').style.display='';
			document.getElementById('nameRow').style.display='';
			document.getElementById('nameRow2').style.display='';

			if (resultHoldedClassesStr != '') {
				resultHoldedClassesArray = resultHoldedClassesStr.split(',');
				for (q = 0; q < resultHoldedClassesArray.length; q++) {
					chkElement = 'chk_'+resultHoldedClassesArray[q];
					eval("form."+chkElement+".checked = true");
				}
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function holdUnholdResult() {
	pars = generateQueryString('updateMarksForm');

	url = '<?php echo HTTP_LIB_PATH;?>/Student/saveHoldUnholdResult.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 asynchronous: false,
		 onCreate: function(){
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
			 hideWaitDialog();
			 res = trim(transport.responseText);
			 messageBox(res);

			 if ("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				 hiddenFloatingDiv('updateMarks');
				 showStudentMarks(false);
			 }

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function makeReappearTable() {
	var form = document.marksReappearForm;
	var reappearTableHTML = "";
	var gradeList = document.getElementById("hiddenGrades").innerHTML;
	var gradeRes = eval('('+ gradeList + ')');


	var marksSelectBox = "<option value='A'>A</option><option value='UMC'>UMC</option><option value='I'>I</option><option value='MU'>MU</option><option value='Marks'>Marks</option>";
	reappearTableHTML += '<table border="0" cellpadding="1" cellspacing="1" width="100%" style="border:1px solid #CCC;">';
	reappearTableHTML += "<tr class=\"rowheading\"><td  class='searchhead_text'>Component</td><td class='searchhead_text'>Status</td><td class='searchhead_text'>Marks Scored</td><td class='searchhead_text'> Max. Marks</td></tr>";
	if (form.reappearExam[0].checked == true) {
		reappearTableHTML += "<tr class=\"row0\"><td>Attendance</td>";
		reappearTableHTML += "<td class='searchhead_text'><select name='updateAttendanceSelect' class='htmlElement'><option value='Marks'>Marks</option></select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateAttendanceMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateAttendanceMaxMarks'></td></tr>";
		reappearTableHTML += "<tr class=\"row1\"><td>PreCompre</td>";
		reappearTableHTML += "<td><select name='updatePreCompreSelect' class='htmlElement'>";
		reappearTableHTML += marksSelectBox;
		reappearTableHTML += "</select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreMaxMarks'></td></tr>";
		reappearTableHTML += "<tr class=\"row0\"><td>Compre</td>";
		reappearTableHTML += "<td><select name='updateCompreSelect' class='htmlElement'>";
		reappearTableHTML += marksSelectBox;
		reappearTableHTML += "</select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateCompreMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateCompreMaxMarks'></td></tr>";
	}
	else if (form.reappearExam[1].checked == true) {
		reappearTableHTML += "<tr class=\"row0\"><td>Attendance</td>";
		reappearTableHTML += "<td><select name='updateAttendanceSelect' class='htmlElement'><option value='Marks'>Marks</option></select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateAttendanceMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateAttendanceMaxMarks'></td></tr>";
		reappearTableHTML += "<tr class=\"row1\"><td>PreCompre + Compre</td>";
		reappearTableHTML += "<td><select name='updatePreCompreCompreSelect' class='htmlElement'>";
		reappearTableHTML += marksSelectBox;
		reappearTableHTML += "</select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreCompreMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreCompreMaxMarks'></td></tr>";
	}
	else if (form.reappearExam[2].checked == true) {
		reappearTableHTML += "<tr class=\"row0\"><td>Compre</td>";
		reappearTableHTML += "<td><select name='updateCompreSelect' class='htmlElement'>"+marksSelectBox+"</select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateCompreMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateCompreMaxMarks'></td></tr>";
		reappearTableHTML += "<tr class=\"row1\"><td>PreCompre + Attendance</td>";
		reappearTableHTML += "<td><select name='updatePreCompreAttendanceSelect' class='htmlElement'>";
		reappearTableHTML += marksSelectBox;
		reappearTableHTML += "</select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreAttendanceMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreAttendanceMaxMarks'></td></tr>";
	}
	else if (form.reappearExam[3].checked == true) {
		reappearTableHTML += "<tr class=\"row0\"><td>PreCompre</td>";
		reappearTableHTML += "<td><select name='updatePreCompreSelect' class='htmlElement'>"+marksSelectBox+"</select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreMaxMarks'></td></tr>";
		reappearTableHTML += "<tr class=\"row1\"><td>Compre + Attendance</td>";
		reappearTableHTML += "<td><select name='updateCompreAttendanceSelect' class='htmlElement'>";
		reappearTableHTML += marksSelectBox;
		reappearTableHTML += "</select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateCompreAttendanceMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updateCompreAttendanceMaxMarks'></td></tr>";
	}
	else if (form.reappearExam[4].checked == true) {
		reappearTableHTML += "<tr class=\"row0\"><td>Attendance + PreCompre + Compre</td>";
		reappearTableHTML += "<td><select name='updatePreCompreCompreAttendanceSelect' class='htmlElement'>";
		reappearTableHTML += marksSelectBox;
		reappearTableHTML += "</select></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreCompreAttendanceMarksScored'></td>";
		reappearTableHTML += "<td><input class='htmlElement' type=\"text\" name='updatePreCompreCompreAttendanceMaxMarks'></td></tr>";
	}
	reappearTableHTML += "<tr><td colspan='1'>Grade </td><td colspan='3'><select name='gradeId' class='htmlElement' style='width:60px;'>";
	var totalGrades = gradeRes.length;
	for (i=0; i<totalGrades; i++) {
		gradeId = gradeRes[i]['gradeId'];
		gradeLabel = gradeRes[i]['gradeLabel'];
		reappearTableHTML += "<option value='"+gradeId+"'>"+gradeLabel+"</option>";
	}
	reappearTableHTML += "<option value=''>I</option>";

	reappearTableHTML += "</select></td></tr>";
	reappearTableHTML += "<tr><td colspan='4'>Reason : <textarea name='reappearReason' rows='3' cols='30' class='selectfield' style='vertical-align:top;'></textarea><input type='image' name='imageField' src='<?php echo IMG_HTTP_PATH;?>/save.gif' onClick='return saveReappearMarks();return false;' /></td></tr>";
	reappearTableHTML += "</table>";
	document.getElementById("reappearTable").innerHTML = reappearTableHTML;
}

function saveFinalMarks() {
	var form = document.finalMarksForm;
	var pars = generateQueryString('finalMarksForm');

	url = '<?php echo HTTP_LIB_PATH;?>/Student/saveFinalMarks.php';
	new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 asynchronous: false,
		 onCreate: function(){
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
			 hideWaitDialog();
			 res = trim(transport.responseText);
			 messageBox(res);

			 if ("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				hiddenFloatingDiv('updateMarksStatus');
			 }

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function saveReappearMarks() {
	var form = document.marksReappearForm;
	var pars = generateQueryString('marksReappearForm');
	dataSaved = false;

	url = '<?php echo HTTP_LIB_PATH;?>/Student/saveReappearMarks.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 asynchronous: false,
		 onCreate: function(){
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
			 hideWaitDialog();
			 res = trim(transport.responseText);
			 //messageBox(res);
             if ("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				 dataSaved = true;
			 }
             else {
                 messageBox(res);
             }        
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
	   if (dataSaved == true) {
		     //hiddenFloatingDiv('updateReappearMarks');
		     showStudentMarks(false);
		     finalizeMarks();
	   }
}

function finalizeMarks() {
	var pars = generateQueryString('marksReappearForm');
	//alert(pars);
	url = '<?php echo HTTP_LIB_PATH;?>/Student/showAllMarks.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 asynchronous: false,
		 onCreate: function(){
			 showWaitDialog();
		 },
		 onSuccess: function(transport){
			 hideWaitDialog();
			 res = trim(transport.responseText);
			 document.getElementById("finalMarksDiv").innerHTML = res;
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	hiddenFloatingDiv('updateReappearMarks');
	displayFloatingDiv('updateMarksStatus','',600,800,100,400);
}

function cleanReappearTable() {
	document.getElementById("reappearTable").innerHTML = "";
}


function updateGrades(studentId,classId,subjectId) {
	var studentName = document.updateMarksForm.studentName.value;
	var pars = 'studentName='+studentName+'&studentId='+studentId+'&classId='+classId+'&subjectId='+subjectId;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/showStudentSubjectMarks.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			fetchedData = trim(transport.responseText);
			if (fetchedData == "<?php echo INVALID_DETAILS;?>") {
				messageBox(fetchedData);
				return false;
			}
			else {
				//document.getElementById("internalDiv").innerHTML = '';
				//document.getElementById('internalDiv').innerHTML = transport.responseText;
				res = trim(transport.responseText);
				document.getElementById("internalDiv").innerHTML = res;
				displayFloatingDiv('updateMarks','',400,800,100,400);
				document.getElementById("preCompreTab").style.display='';
				document.getElementById("compreTab").style.display='';
				document.getElementById("gradeTab").style.display='';


				//document.getElementById("innerDiv").innerHTML = table;

				if (tabsShown == true) {
					cleanActiveTabIndex();
					eval(tabsShown1);
				}
				else {
					jsCodeStartIndex = res.indexOf("<!--");
					jsCodeEndIndex = res.indexOf("-->");
					tabsShown1 = res.substring(jsCodeStartIndex+4,jsCodeEndIndex);
					//alert(tabsShown1);
					eval(tabsShown1);
					tabsShown = true;
				}
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
changeColor(currentThemeId);
}

function updateReAppear2(studentId,classId,subjectId) {
	hiddenFloatingDiv('updateMarksStatus');
	updateReAppear(studentId,classId,subjectId);
}

function updateReAppear(studentId,classId,subjectId) {
	var studentName = document.updateMarksForm.studentName.value;
	var pars = 'studentName='+studentName+'&studentId='+studentId+'&classId='+classId+'&subjectId='+subjectId;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/showStudentSubjectAllMarks.php';
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			fetchedData = trim(transport.responseText);
			if (fetchedData == "<?php echo INVALID_DETAILS;?>") {
				messageBox(fetchedData);
				return false;
			}
			else {
				//document.getElementById("internalDiv").innerHTML = '';
				//document.getElementById('internalDiv').innerHTML = transport.responseText;
				res = trim(transport.responseText);
				document.getElementById("reappearDiv").innerHTML = res;
				displayFloatingDiv('updateReappearMarks','',450,800,50,400);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
changeColor(currentThemeId);
}

function checkNewMarks() {
		formName = document.marksUpdationForm.name;
		pars = generateQueryString(formName);
        url = '<?php echo HTTP_LIB_PATH;?>/Student/previewStudentMarks.php';
		new Ajax.Request(url,
           {
             method:'post',
             parameters: pars,
             asynchronous: false,
			 onCreate: function(){
				 showWaitDialog();
			 },
             onSuccess: function(transport){
				 hideWaitDialog();
				 resTrim = trim(transport.responseText);
				 if (resTrim.indexOf('"') == -1) {
					 messageBox(resTrim);
					 return false;
				 }
				 res = eval('(' + transport.responseText + ')');
				
                 
                 var attendanceMarks=  parseFloat(res['attendanceMarks']);
                 if(isNaN(attendanceMarks)) {
                   attendanceMarks=0;  
                 }
                 var preCompreMarks = parseFloat(res['preCompreMarks']);
                 if(isNaN(preCompreMarks)) {
                   preCompreMarks=0;  
                 }
                 var compreMarks = parseFloat(res['compreMarks']);
                 if(isNaN(compreMarks)) {
                   compreMarks=0;  
                 }
				 var totalMarksScoredValue = attendanceMarks + preCompreMarks + compreMarks;
                 
                 
                 var attendanceMarksTotal=  parseFloat(res['attendanceMarksTotal']);
                 if(isNaN(attendanceMarksTotal)) {
                   attendanceMarksTotal=0;  
                 }
                 var preCompreMarksTotal = parseFloat(res['preCompreMarksTotal']);
                 if(isNaN(preCompreMarksTotal)) {
                   preCompreMarksTotal=0;  
                 }
                 var compreMarksTotal = parseFloat(res['compreMarksTotal']);
                 if(isNaN(compreMarksTotal)) {
                   compreMarksTotal=0;  
                 }
				 var totalMaxMarksValue = attendanceMarksTotal+preCompreMarksTotal+compreMarksTotal;

                 document.marksUpdationForm.attendanceMarksNew.value = attendanceMarks;
                 document.marksUpdationForm.attendanceMarksTotal.value = attendanceMarksTotal;
                 document.marksUpdationForm.preCompreMarksNew.value = preCompreMarks;
                 document.marksUpdationForm.preCompreMarksTotal.value = preCompreMarksTotal;
                 document.marksUpdationForm.compreMarksNewCal.value = compreMarks;
                 document.marksUpdationForm.compreMarksTotalCal.value = compreMarksTotal;
                 document.marksUpdationForm.gradeNew.value = res['grade'];
                 
				 document.marksUpdationForm.sumTotalMarksScored.value = totalMarksScoredValue.toFixed(3);
				 document.marksUpdationForm.sumTotalMaxMarks.value = totalMaxMarksValue.toFixed(3);
				 document.marksUpdationForm.gradeNew.disabled = true;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function hideResults() {
	document.getElementById('resultsDiv').innerHTML='';
	document.getElementById('holdResultRow').style.display='none';
	document.getElementById('holdResultRow2').style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
	document.getElementById('resultsDiv').style.display='none';
	document.getElementById('resultRow').style.display='none';
}

function checkCompre() {
}
function checkPreCompre() {
}

function validateMarksUpdationForm() {
	form = document.marksUpdationForm;

	reason = trim(form.reason.value);
	if (reason == '') {
		messageBox("<?php echo ENTER_REASON;?>");
		form.reason.focus();
		return false;
	}

	checkNewMarks();
	savemarks();
}

function savemarks() {
		formName = document.marksUpdationForm.name;
		pars = generateQueryString(formName);
         url = '<?php echo HTTP_LIB_PATH;?>/Student/saveStudentMarks.php';
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
				 messageBox(trim(transport.responseText));
				 if ("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('updateMarks');
					 showStudentMarks(false);
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listUpdateTotalMarks.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
//$History: scUpdateTotalMarks.php $
//
//*****************  Version 15  *****************
//User: Ajinder      Date: 1/12/10    Time: 11:20a
//Updated in $/Leap/Source/Interface
//fixed issues: 2576, 2577, 2578
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 9/09/09    Time: 5:40p
//Updated in $/Leap/Source/Interface
//improved design, corrected bug in calculation
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 9/01/09    Time: 5:19p
//Updated in $/Leap/Source/Interface
//added student name,
//corrected attendance bug
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 8/31/09    Time: 12:28p
//Updated in $/Leap/Source/Interface
//added code for subjective-objective marks.
//added defines.
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 5/06/09    Time: 3:09p
//Updated in $/Leap/Source/Interface
//message was not coming up. this was fixed.
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 5/01/09    Time: 5:05p
//Updated in $/Leap/Source/Interface
//added code for hold/unhold result.
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 3/02/09    Time: 4:22p
//Updated in $/Leap/Source/Interface
//modified to make it working for last level.
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 2/23/09    Time: 3:19p
//Updated in $/Leap/Source/Interface
//modified to fix issue for update total marks problem with IE
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 1/14/09    Time: 1:11p
//Updated in $/Leap/Source/Interface
//applied access rights
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 1/07/09    Time: 12:57p
//Updated in $/Leap/Source/Interface
//added MU in code for -4
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 12/25/08   Time: 4:06p
//Updated in $/Leap/Source/Interface
//added I for incomplete
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/15/08   Time: 12:33p
//Updated in $/Leap/Source/Interface
//applied code for "reason"
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/04/08   Time: 6:09p
//Updated in $/Leap/Source/Interface
//applied "check" missing previously.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/04/08   Time: 4:33p
//Created in $/Leap/Source/Interface
//file made for marks updation for single student
//
//

?>
