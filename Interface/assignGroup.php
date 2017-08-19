<?php

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Group Assignment
//
//
// Author :Ajinder Singh
// Created on : 24-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignGroupsToStudents');
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
?>
<script language="javascript">

 //This function Validates Form
var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), new Array('select','<input type="checkbox" name="checkAll" id="checkAll" onClick="selAll();" />Select','width="10%"','',false), new Array('rollNo','Roll No','width="20%"','',true), new Array('firstName','First Name','width="25%"','',true), new Array('lastName','Last Name','width="25%"','',true), new Array('regNo','Reg. No','width="20%"','',true));

//validate form
//check if all students have been assigned group, then prompt, if admin says yes, remove the previous assignment, then show the list

noStudentAdmitted = false;
rollNoNotAssigned = false;
attendanceAlreadyTaken = false;
testsAlreadyTaken = false;

function selAll() {
	formName = 'assignGroup';
	elementName = 'chk[]';

	var i;
	str = '';
	loopCnt = eval("document."+formName+".elements['"+elementName+"'].length");
	if (document.assignGroup.elements['chk[]']) {
		if(document.assignGroup.elements['chk[]'].length) {
			for (var i = 0; i < loopCnt; i++) {
				eval("document."+formName+".elements['"+elementName+"'][i].checked = document.assignGroup.checkAll.checked");
			}
		}
		else {
			document.assignGroup.elements['chk[]'].checked = document.assignGroup.checkAll.checked;
		}
	}
}


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"), new Array("groupType","<?php echo SELECT_GROUP_TYPE;?>"), new Array("groupId","<?php echo SELECT_GROUP;?>"), new Array("assignNo","<?php echo ENTER_STUDENTS_TO_ASSIGN_GROUP;?>"));

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

	pendingStudents = parseInt(document.getElementById("groupPendingStudentsSpan").innerHTML);
	assignNo = frm.assignNo.value;
	if ((isInteger(assignNo) == false) || (assignNo == 0)) {
		messageBox('<?php echo ENTER_STUDENTS_TO_ASSIGN_GROUP;?>');
		return false;
	}
	else if (assignNo > pendingStudents && (pendingStudents > 0)) {
		messageBox('<?php echo ENTER_VALID_NO_TO_ASSIGN_GROUP;?>');
		return false;
	}
	if (noStudentAdmitted == true) {
		messageBox("<?php echo CLASS_HAS_NO_STUDENT;?>");
		return false;
	}
	if(rollNoNotAssigned == true) {
		messageBox("<?php echo ALL_STUDENTS_NOT_ASSIGNED_ROLL_NO;?>");
		return false;
	}
	if(attendanceAlreadyTaken == true) {
		messageBox("<?php echo ATTENDANCE_ENTERED_FOR_THIS_CLASS;?>");
		return false;
	}
	if(testsAlreadyTaken == true) {
		messageBox("<?php echo TESTS_TAKEN_FOR_THIS_CLASS;?>");
		return false;
	}
	if (pendingStudents == 0) {
		if(confirm("<?php echo GROUP_ASSIGNED_ALREADY_CONFIRM;?>")) {
			showGroupAssignment();
		}
	}
	else {
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		showGroupAssignment();
	}
}

function countDegreeStudents() {
//	blankValues();
	frm = document.assignGroup;
	frm.groupType.value='';
	frm.groupId.value='';
	frm.assignNo.value='';
	frm.groupAssignment.value='rollNo';
	document.getElementById("groupTypeAssignedToStudentsSpan").innerHTML='0';
	document.getElementById("groupTypePendingStudentsSpan").innerHTML='0';
	document.getElementById("groupAssignedSpan").innerHTML='0';
	document.getElementById("siblingGroupAssignedSpan").innerHTML='0';
	document.getElementById("groupPendingStudentsSpan").innerHTML='0';
	
	
	frm2 = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/countDegreeStudents.php';
	var pars = generateQueryString(frm2);
	var testVar = 0;
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
			fetchedData = trim(transport.responseText);
			if(fetchedData != '<?php echo ERROR_OCCURED;?>') {
				j = trim(transport.responseText);
				document.getElementById("totalDegreeStudentsSpan").innerHTML=j;
			}
			else {
				messageBox(fetchedData)
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

	resetGroupType();
}

function countGroupTypeStudents() {
	frm2 = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/countGroupTypeStudents.php';
	var pars = generateQueryString(frm2);
	var testVar = 0;
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
			fetchedData = trim(transport.responseText);
			if(fetchedData != '<?php echo ERROR_OCCURED;?>') {
				j = trim(transport.responseText);
				document.getElementById("groupTypeAssignedToStudentsSpan").innerHTML=j;
				countgroupTypePendingStudentsSpan();
			}
			else {
				messageBox(fetchedData)
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
getClassGroups();	
}

function countgroupTypePendingStudentsSpan() {
	var totalDegreeStudents = parseInt(document.getElementById("totalDegreeStudentsSpan").innerHTML);
	var groupTypeGroupAssignedStudents = parseInt(document.getElementById("groupTypeAssignedToStudentsSpan").innerHTML);
	var groupTypePendingStudentsSpan = totalDegreeStudents - groupTypeGroupAssignedStudents;
	document.getElementById("groupTypePendingStudentsSpan").innerHTML = groupTypePendingStudentsSpan;
}

function getClassGroups() {
	frm2 = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/getClassGroups.php';
	var pars = generateQueryString(frm2);
	var testVar = 0;
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
			fetchedData = trim(transport.responseText);
			if(fetchedData.indexOf('<?php echo PLEASE_CREATE_;?>') == -1) {
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				document.assignGroup.groupId.length = null;
				addOption(document.assignGroup.groupId, '', 'Select');
				for(i=0;i<len;i++) { 
					addOption(document.assignGroup.groupId, j[i].groupId, j[i].groupShort);
				}
				//document.assignGroup.groupId.value = j[0].groupShort;
			}
			else {
				messageBox(fetchedData)
				document.getElementById("resultRow").style.display='none';
				document.getElementById('nameRow').style.display='none';
				document.getElementById('nameRow2').style.display='none';
				document.assignGroup.groupId.length = null;
				addOption(document.assignGroup.groupId, '', 'Select');
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function showGroupAssignment() {
	frm2 = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/showGroupAssignment.php';
	var pars = generateQueryString(frm2);
	pendingStudents = parseInt(document.getElementById("groupPendingStudentsSpan").innerHTML);
	pars += '&pendingStudents='+pendingStudents;
	var testVar = 0;
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
			fetchedData = trim(transport.responseText);
			if(fetchedData != "<?php echo ATTENDANCE_ENTERED_FOR_GROUP;?>" && fetchedData != "<?php echo TEST_ENTERED_FOR_GROUP;?>") {
				j = eval('('+ transport.responseText + ')');
				document.getElementById("nameRow").style.display='';
				document.getElementById("nameRow2").style.display='';
				document.getElementById("resultRow").style.display='';
				printResultsNoSorting('resultsDiv', j, tableHeadArray);
			}
			else {
				messageBox(fetchedData)
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function saveSelectedStudents() {
	var studentCount;
	frm2 = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/initListAssignGroup.php';
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
					messageBox(trim(transport.responseText));
					blankValues();
					hideResults();
				}
				else {
					messageBox(trim(transport.responseText))
				}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

}




function countPendingStudents() {
	hideResults();
	frm = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/countPendingStudents.php';
	var pars = generateQueryString(frm);
	if (document.assignGroup.groupType.value !='') {
		new Ajax.Request(url,
		{
			method:'post',
			parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){
				hideWaitDialog(true);
				j = eval('(' + transport.responseText + ')');
				document.getElementById("groupAssignedSpan").innerHTML = j['thisGroupAllocation'];
				document.getElementById("siblingGroupAssignedSpan").innerHTML = j['siblingGroupAllocation'];
				document.getElementById("groupPendingStudentsSpan").innerHTML = j['pendingGroupAllocation'];

			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
	else {
		document.getElementById("groupPendingStudentsSpan").innerHTML='';
	}

}

function resetGroupType() {
	hideResults();
	document.assignGroup.groupType.value='';
	if (document.assignGroup.degree.value != '') {
		checkUnAssignedRollNos();
	}
}

function checkUnAssignedRollNos() {
	frm = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/countUnAssignedStudents.php';
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
					j = parseInt(trim(transport.responseText));
					if (j == -1) {
						messageBox("<?php echo CLASS_HAS_NO_STUDENT;?>");
						noStudentAdmitted = true;
						return false;
					}
					else if (j > 0) {
						messageBox("<?php echo ALL_STUDENTS_NOT_ASSIGNED_ROLL_NO;?>");
						rollNoNotAssigned = true;
						return false;
						//document.assignGroup.listBtn.disabled = true;
					}
					else if (j == -2) {
						messageBox("<?php echo ATTENDANCE_ENTERED_FOR_THIS_CLASS;?>");
						attendanceAlreadyTaken = true;
						return false;
						//document.assignGroup.listBtn.disabled = true;
					}
					else if (j == -3) {
						messageBox("<?php echo TESTS_TAKEN_FOR_THIS_CLASS;?>");
						testsAlreadyTaken = true;
						return false;
						//document.assignGroup.listBtn.disabled = true;
					}
					else {
						noStudentAdmitted = false;
						rollNoNotAssigned = false;
						attendanceAlreadyTaken = false;
						testsAlreadyTaken = false;
					}
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
}

function blankValues() {
	document.assignGroup.degree.value='';
	document.assignGroup.groupType.value='';
	document.getElementById("totalDegreeStudentsSpan").innerHTML='0';
	document.getElementById("groupTypeAssignedToStudentsSpan").innerHTML='0';
	document.getElementById("groupTypePendingStudentsSpan").innerHTML='0';
	document.getElementById("groupAssignedSpan").innerHTML='0';
	document.getElementById("siblingGroupAssignedSpan").innerHTML='0';
	document.getElementById("groupPendingStudentsSpan").innerHTML='0';
	document.assignGroup.groupId.value='';
	document.assignGroup.assignNo.value='';
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listAssignGroup.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
//$History: assignGroup.php $
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 9/02/09    Time: 10:58a
//Updated in $/LeapCC/Interface
//added code for assigning groups to pending students only.
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 8/17/09    Time: 12:02p
//Updated in $/LeapCC/Interface
//code modified to fix IE issue.
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 8/14/09    Time: 5:46p
//Updated in $/LeapCC/Interface
//added checkbox for individual group assignment
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/07/09    Time: 4:47p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 5/26/09    Time: 5:53p
//Updated in $/LeapCC/Interface
//fixed issue related to IE of groups not coming.
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 12/30/08   Time: 4:33p
//Updated in $/LeapCC/Interface
//improved coding
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 12/22/08   Time: 1:18p
//Updated in $/LeapCC/Interface
//applied check for if attendance is already taken or tests already taken
//for that class, then stop user.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/13/08   Time: 4:31p
//Updated in $/LeapCC/Interface
//updated code for student group assignment.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/10/08   Time: 6:33p
//Updated in $/LeapCC/Interface
//working on student group allocation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 9/10/08    Time: 2:59p
//Updated in $/Leap/Source/Interface
//applied check to stop unnecessary server trip
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 9/01/08    Time: 5:29p
//Updated in $/Leap/Source/Interface
//fixed issue found during self testing
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 8/25/08    Time: 3:22p
//Updated in $/Leap/Source/Interface
//fixed bugs and improved functionality
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 8/18/08    Time: 6:53p
//Updated in $/Leap/Source/Interface
//improved page design for buttons
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/09/08    Time: 10:47a
//Updated in $/Leap/Source/Interface
//updated the code of ajax request, and changed messages
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/08/08    Time: 7:36p
//Updated in $/Leap/Source/Interface
//changed file and applied checks for if no student exists in class
//
//*****************  Version 5  *****************
//User: Admin        Date: 8/05/08    Time: 11:33a
//Updated in $/Leap/Source/Interface
//changed as per new format and shown the list which are assigned group
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/01/08    Time: 7:42p
//Updated in $/Leap/Source/Interface
//done minor changes for bug fixing
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/28/08    Time: 4:33p
//Updated in $/Leap/Source/Interface
//changes made to make it working

?>
