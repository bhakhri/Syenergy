<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Student Roll No. Generation
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
define('MODULE','AssignRollNumbers');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assign Roll Numbers </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
 //This function Validates Form 
var tableHeadArray = new Array(new Array('srNo','#','width="2%" ','',false), new Array('select','#','width="2%" ','',false), new Array('regNo','Reg. No','width="15%"','',true) , new Array('studentName','Student Name','width="25%"','',true), new Array('oldRollNo','Old Roll No','width="20%"','',true), new Array('rollNo','New Roll No','width="20%"','',true));


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"), new Array("rollNoLength","<?php echo SELECT_ROLL_NO_LENGTH;?>"), new Array("sorting","<?php echo SELECT_SORTING;?>"));
	prefixValue = frm.prefix.value;
	suffixValue = frm.suffix.value;

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		else {
		}
    }
	prefixLength = frm.prefix.value.length;
	suffixLength = frm.suffix.value.length;
	totalMandatoryLength = prefixLength + suffixLength;
	if (parseInt(frm.rollNoLength.value) < totalMandatoryLength) {
		messageBox("<?php echo ROLL_NO_LENGTH_SHORT;?>");
		return false;
	}
	else {

		var studentCount;

		frm2 = document.assignRoleNo.name;//variable frm is used already
		var url = '<?php echo HTTP_LIB_PATH;?>/Student/getStudentCount.php';
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
					j = eval('('+ transport.responseText + ')');
					studentCount = parseInt(j['studentCount']);
					studentAssignedCount = parseInt(j['studentAssignedCount']);
					//studentPendingCount = j['studentPendingCount'];
					//studentAssignedCount = j['studentAssignedCount'];

					if (studentCount == 0) {
						messageBox("<?php echo CLASS_HAS_NO_STUDENT;?>");
						return false;
					}
					/*
					else if (studentAssignedCount > 0) {
						messageBox("<?php echo ROLL_NO_ASSIGNED_ALREADY;?>");
						return false;
					}
					*/
					/*
					if (studentPendingCount == 0) {
						messageBox("All students have been issued roll numbers for this class");
						return false;
					}
					if (studentAssignedCount > 0) {
						messageBox("Role Numbers have been assigned for this class already.");
						return false;
					}
					*/
					var currentForm = document.assignRoleNo;
					var startSeriesFrom = currentForm.startSeriesFrom.value;
					if (startSeriesFrom == '') {
						startSeriesFrom = 0;
					}
					var totalCount = parseInt(startSeriesFrom) + parseInt(studentCount) - 1;//to start the series from that number.
					var countLength = totalCount.toString().length;
					totalMandatoryLength += countLength;

					if (parseInt(frm.rollNoLength.value) < totalMandatoryLength) {
						messageBox("<?php echo ROLL_NO_LENGTH_SHORT;?>");
						return false;
					}
					else {
						showRoleNoAssignment();
					}
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}

function unselect(id,oldVal) {
	if (document.getElementById(id).className != 'highlightPermission') {
		document.getElementById(id).className='highlightPermission';
	}
	else {
		document.getElementById(id).className=oldVal;
	}
}

function selAll() {
	formName = 'assignRoleNo';
	elementName = 'chk[]';

	var i;
	str = '';
	loopCnt = eval("document."+formName+".elements['"+elementName+"'].length");
	for (var i = 0; i < loopCnt; i++) {
		eval("document."+formName+".elements['"+elementName+"'][i].checked = document.assignRoleNo.checkAll.checked");
	}
}

//this function shows the possible assignment.
function showRoleNoAssignment() {
	frm = document.assignRoleNo.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/getRoleAssignment.php';
	var pars = generateQueryString(frm);

		var tableData;
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
			
			if(fetchedData != "<?php echo SERIES_ROLL_NO_ASSIGNED_ALREADY;?>" && fetchedData != "<?php echo USERNAMES_EXIST_ALREADY;?>" && fetchedData != "<?php echo INVALID_SERIES_STARTING_NUMBER;?>") {
				j = eval('('+ transport.responseText + ')');
				total = j.length;
			
				tableData = globalTB;
				tableData += '<tr class="rowheading">';
				tableData += '<td width="2%" class="searchhead_text ">#</td>';
				tableData += '<td width="10%" align="center" class="searchhead_text ">Select <input name="checkAll" type="checkbox"  onClick="selAll();"></td>';
				tableData += '<td width="13%" class="searchhead_text " align="left">Reg No.</td>';
				tableData += '<td width="25%" class="searchhead_text " align="left">Student Name</td>';
				tableData += '<td width="20%" class="searchhead_text " align="left">Old Roll No.</td>';
				tableData += '<td width="20%" class="searchhead_text " align="left">New Roll No.</td></tr>';

				if (total > 0) {
					for(x = 0; x < total; x++) {
						var bg = bg=='row0' ? 'row1' : 'row0';
						tableData += '<tr id="test_'+x+'" class="'+bg+'">';
						tableData += '<td class="" onmouseover="unselect(\'test_'+x+'\',\''+bg+'\')" onmouseout="unselect(\'test_'+x+'\',\''+bg+'\')">'+j[x]['srNo']+'&nbsp;</td>';
						tableData += '<td class="" align="center"><input onmouseover="unselect(\'test_'+x+'\',\''+bg+'\')" onmouseout="unselect(\'test_'+x+'\',\''+bg+'\')" type="checkbox" checked value="'+j[x]['studentId']+'" name="chk[]">&nbsp;</td>';
						tableData += '<td class="" align="left" onmouseover="unselect(\'test_'+x+'\',\''+bg+'\')" onmouseout="unselect(\'test_'+x+'\',\''+bg+'\')">'+j[x]['regNo']+'&nbsp;</td>';
						tableData += '<td class="" align="left" onmouseover="unselect(\'test_'+x+'\',\''+bg+'\')" onmouseout="unselect(\'test_'+x+'\',\''+bg+'\')">'+j[x]['studentName']+'&nbsp;</td>';
						tableData += '<td class="" align="left" onmouseover="unselect(\'test_'+x+'\',\''+bg+'\')" onmouseout="unselect(\'test_'+x+'\',\''+bg+'\')">'+j[x]['oldRollNo']+'&nbsp;</td>';
						tableData += '<td class="" align="left" onmouseover="unselect(\'test_'+x+'\',\''+bg+'\')" onmouseout="unselect(\'test_'+x+'\',\''+bg+'\')">'+j[x]['rollNo']+'&nbsp;</td>';
						tableData += '</tr>';
					}
				}
				else {
					tableData += '<tr class="">';
					tableData += '<td colspan="6" align="center">'+noDataFoundVar+'</td></tr>';
				}
				tableData += "</table>";
				document.getElementById("resultsDiv").innerHTML = tableData;
				document.getElementById('resultsDiv').style.display='';
				document.getElementById('resultRow').style.display='';
				document.getElementById('nameRow').style.display='';
				document.getElementById('nameRow2').style.display='';

				if (total > 0) {
					document.getElementById('showSaveButton1').style.display='';
					document.getElementById('showSaveButton2').style.display='';
				}
				else {
					document.getElementById('showSaveButton1').style.display='none';
					document.getElementById('showSaveButton2').style.display='none';
				}
				//printResultsNoSorting('resultsDiv', j, tableHeadArray);
			}
			else {
				messageBox(fetchedData);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function hideResults() {
	document.getElementById('resultsDiv').innerHTML='';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
	document.getElementById('resultsDiv').style.display='none';
	document.getElementById('resultRow').style.display='none';
}

function blankValues() {
	form = document.assignRoleNo;
	form.degree.value = '';
	form.rollNoLength.value = '';
	form.prefix.value = '';
	form.suffix.value = '';
	form.sorting.value = '';
	form.leet.checked = false;
	form.alreadyAssigned.checked = false;
}

function getUnchecked(formName,elementName) {
	var i;
	str = '';
	loopCnt = eval("document."+formName+".elements['"+elementName+"'].length");
	for (var i = 0; i < loopCnt; i++) {
		if (eval("document."+formName+".elements['"+elementName+"'][i].checked") === false) {
			if (str != '') {
				str += ',';
			}
			str+= eval("document."+formName+".elements['"+elementName+"'][i].value");
		}
	}
	return str;
}


function assignRollNo() {
	if(confirm("<?php echo ROLL_NO_ASSIGNMENT_CONFIRM;?>")) {
		frm = document.assignRoleNo.name;
		var url = '<?php echo HTTP_LIB_PATH;?>/Student/doRoleAssignment.php';
		var pars = generateQueryString(frm);
		uncheckedBoxes = getUnchecked('assignRoleNo','chk[]');
		pars += '&unselectedUsers='+uncheckedBoxes;

		new Ajax.Request(url,
		{
			method:'post',
			parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){
				hideWaitDialog(true);
				messageBox(trim(transport.responseText));
				if (trim(transport.responseText) == "<?php echo ROLL_NO_ASSIGNED_SUCCESSFULLY;?>") {
					hideResults();
					blankValues();
				}
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}

function openStudentLists(frm) {
	   var queryString= generateQueryString(frm) ;
	   path='<?php echo UI_HTTP_PATH;?>/listStudentLabelsPrint.php?'+queryString;
	   window.open(path,"PrintStudentLabels","status=1,menubar=1,scrollbars=1");
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listAssignRollNo.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: assignRollNo.php $
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/07/09    Time: 4:47p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/04/09    Time: 1:22p
//Updated in $/LeapCC/Interface
//fixed bug no.s 842, 841, 840, 839, 814, 813, 812
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 7/30/09    Time: 5:33p
//Updated in $/LeapCC/Interface
//fixed minor flaw in roll no. missed earlier.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/30/09    Time: 1:51p
//Updated in $/LeapCC/Interface
//fixed bug no.0000755
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/16/09    Time: 10:04a
//Updated in $/LeapCC/Interface
//done no changes.
//


?>
