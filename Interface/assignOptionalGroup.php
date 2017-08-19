<?php

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Group Assignment
//
//
// Author :Ajinder Singh
// Created on : 11-June-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignOptionalSubjects');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assign Optional Subjects </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

 //This function Validates Form
var tableHeadArray = new Array(new Array('srNo','#','width="10%"','',false), new Array('rollNo','Roll No','width="20%"','',true), new Array('firstName','First Name','width="25%"','',true), new Array('lastName','Last Name','width="25%"','',true), new Array('regNo','Reg. No','width="20%"','',true));

//validate form
//check if all students have been assigned group, then prompt, if admin says yes, remove the previous assignment, then show the list

noStudentAdmitted = false;
rollNoNotAssigned = false;
attendanceAlreadyTaken = false;
testsAlreadyTaken = false;

function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"), new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	getOptionalSubjectGroups();
}

function getOptionalSubjectGroups() {
	var form = document.assignGroup;
	frm2 = document.assignGroup.name;
	degreeValue = form.degree.value;
	subjectIdValue = form.subjectId.value;
	if (degreeValue == '' || subjectIdValue == '') {
		return false;
	}
	if (document.getElementById('categorySubjectsSpan').style.display == '') {
		if (form.categorySubjects.value == '') {
			messageBox("Select category subject");
			form.categorySubjects.focus();
			return false;
		}
	}
	var pars = generateQueryString(frm2);
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetOptionalSubjectGroups.php';
	//working here

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
				hideWaitDialog(true);
				j = eval('('+transport.responseText+')');
				totalGroups = j['groups'].length;

				var tableData = globalTB;
				tableData += '<tr class="rowheading"><td width="5%" class="searchhead_text">#</td><td width="10%" class="searchhead_text">Roll No.</td><td width="10%" class="searchhead_text">U.Roll No.</td><td width="20%" class="searchhead_text">Student Name</td>';

				totalGroups = j['groups'].length;
				spacePerTd = (55 / totalGroups) + '%';

				for(i = 0; i < totalGroups; i++) {
					groupId = j['groups']['groupId'];
					tableData += '<td width="1%" class="searchhead_text">'+j['groups'][i]['groupShort']+'</td>';
				}
				tableData += '<td width="1%" class="searchhead_text">None</td>';
				tableData += '</tr>';

				
				
				totalStudents = j['students'].length;
				if (totalStudents == 0) {
					document.getElementById("nameRow").style.display='none';
					document.getElementById("nameRow2").style.display='none';
					tableData += '<tr class="'+bg+'" style="height:25px;">';
					totalCols = totalGroups + 2;
					tableData += '<td colspan="'+totalCols+'" align="center">No data found</td>';
					tableData += '<tr>';
				}
				else {
					for(i = 0; i < totalStudents; i++) {
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						var bg2 = bg2 == "row0" ? "row1" : "row0";
						ctr = i+1;
						tableData +='<tr '+bg+' value="'+bg2+'" style="height:25px;" onmouseover="if(this.className != \'specialHighlight\') this.className=\'highlightPermission\'" onmouseout="if(this.className != \'specialHighlight\') this.className=\''+bg2+'\'" >';
						tableData += '<td>'+ctr+'</td>';
						tableData += '<td>'+j['students'][i]['rollNo']+'</td>';
						tableData += '<td>'+j['students'][i]['universityRollNo']+'</td>';
						tableData += '<td>'+j['students'][i]['studentName']+'</td>';
						for(x = 0; x < totalGroups; x++) {
							groupId = j['groups'][x]['groupId'];
							checked = '';
							tdClass = '';
							studentId = j['students'][i]['studentId'];
							if (j['students'][i][groupId] != null) {
								checked = ' checked ';
								tdClass = 'highlightPermission';
							}
							tableData += '<td id="td_'+studentId+"_"+groupId+'" width="'+spacePerTd+'" ><input type="radio" value="'+groupId+'" name="s_'+studentId+'" '+checked+'></td>';
						}
						tableData += '<td id="td_'+studentId+"_"+groupId+'" width="'+spacePerTd+'" ><input type="radio" value="0" name="s_'+studentId+'" '+checked+'></td>';

						tableData += '<tr>';
					}
					document.getElementById("nameRow").style.display='';
					document.getElementById("nameRow2").style.display='';
				}

			tableData += '</table>';
			document.getElementById("resultRow").style.display='';
			document.getElementById("resultsDiv").innerHTML = tableData;

		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

}


function getOptionalSubjects() {
	hideResults();
	var form = document.assignGroup;
	frm2 = document.assignGroup.name;
	degreeValue = form.degree.value;
	if (degreeValue == '') {
		return false;
	}
	var pars = generateQueryString(frm2);
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetClassOptionalSubjects.php';
	//working here

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
				hideWaitDialog(true);
				fetchedData = eval('('+transport.responseText+')');
				totalOptionalSubjects = fetchedData.length;
				document.assignGroup.subjectId.length = null;
				addOption(form.subjectId, '', 'Select');
				for (i=0; i < totalOptionalSubjects; i++) {
					addOption(form.subjectId, fetchedData[i]['subjectId'], fetchedData[i]['subjectCode']);
				}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

}

function saveSelectedStudents() {
	var studentCount;
	frm2 = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/initListAssignOptionalGroup.php';
	var pars = generateQueryString(frm2);
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
				if(fetchedData == '<?php echo GROUP_ASSIGNED_SUCCESSFULLY;?>') {
					messageBox(fetchedData);
					blankValues();
					hideResults();
				}
				else {
					messageBox(fetchedData);
				}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function blankValues() {
	document.assignGroup.degree.value='';
	document.assignGroup.subjectId.value='';
	document.assignGroup.categorySubjects.value='';
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
	document.getElementById('categorySubjectsSpan').style.display='none';
}

function checkCategory() {
	hideResults();
	form = document.assignGroup;
	//var degree = form.degree.value;
	//var subjectId = form.subjectId.value;
	degreeValue = form.degree.value;
	subjectIdValue = form.subjectId.value;
	if (degreeValue == '' || subjectIdValue == '') {
		return false;
	}

	var url = '<?php echo HTTP_LIB_PATH;?>/Student/checkSubjectCategory.php';
	var pars = generateQueryString('assignGroup');
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
				if(fetchedData == '<?php echo SUBJECT_DOES_NOT_HAVE_PARENT_CATEGORY;?>') {
					//do nothing
					document.assignGroup.categorySubjects.length = null;
					document.getElementById("categorySubjectsSpan").style.display='none';
				}
				else {
					document.getElementById("categorySubjectsSpan").style.display='';
					var j = eval('(' + transport.responseText + ')');
					len = j.length;
					document.assignGroup.categorySubjects.length = null;
					addOption(document.assignGroup.categorySubjects, '', 'Select');
					for(i=0;i<len;i++) { 
						addOption(document.assignGroup.categorySubjects, j[i].subjectId, j[i].subjectCode);
					}
					// now select the value
					document.assignGroup.categorySubjects.value = j[0].subjectId;
					
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
    require_once(TEMPLATES_PATH . "/Student/listAssignOptionalGroup.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
//$History: assignOptionalGroup.php $
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 4/17/10    Time: 4:29p
//Updated in $/LeapCC/Interface
//done changes as per FCNS No. 1601
//
//*****************  Version 8  *****************
//User: Rahul.nagpal Date: 11/18/09   Time: 3:15p
//Updated in $/LeapCC/Interface
//issue #2043 resolved.
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 10/28/09   Time: 12:04p
//Updated in $/LeapCC/Interface
//stopped calling to server side file if all parameters not selected.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/04/09    Time: 11:49a
//Updated in $/LeapCC/Interface
//Gurkeerat: corrected title of page
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/09    Time: 6:13p
//Updated in $/LeapCC/Interface
//removed alert, which was placed accidently.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/14/09    Time: 5:49p
//Updated in $/LeapCC/Interface
//added extra column for if no group is to be selected
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/07/09    Time: 11:08a
//Updated in $/LeapCC/Interface
//removed un-necessay code and added code for category subjects.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/11/09    Time: 12:45p
//Updated in $/LeapCC/Interface
//added access defines.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 6/11/09    Time: 12:44p
//Created in $/LeapCC/Interface
//file added for assigning optional subject to students
//




?>
