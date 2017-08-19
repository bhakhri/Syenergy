<?php

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Group Assignment
//
//
// Author :Ajinder Singh
// Created on : 24-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AssignGroupAdvanced');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assign Groups (Advanced) </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

 //This function Validates Form
var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), new Array('select','<input type="checkbox" name="checkAll" id="checkAll" onClick="selAll();" Select','width="10%"','',false), new Array('rollNo','Roll No','width="20%"','',true), new Array('firstName','First Name','width="25%"','',true), new Array('lastName','Last Name','width="25%"','',true), new Array('regNo','Reg. No','width="20%"','',true));

//validate form
//check if all students have been assigned group, then prompt, if admin says yes, remove the previous assignment, then show the list

noStudentAdmitted = false;
rollNoNotAssigned = false;
attendanceAlreadyTaken = false;
testsAlreadyTaken = false;
shiftPressed = false;


function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("labelId","<?php echo SELECT_TIMETABLE;?>"), 
                                new Array("degree","<?php echo SELECT_CLASS;?>"), 
                                new Array("sortBy","<?php echo SELECT_SORTING;?>"));

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
	showStudentGroups();
}

function showStudentGroups() {
	form = document.assignGroup;
    var labelId = form.labelId.value;      
	var degree = form.degree.value;
	var sortBy = form.sortBy.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/initShowStudentGroupsNew.php';
	var pars = 'labelId='+labelId+'&degree='+degree+'&sortBy='+sortBy;


	 if(degree == '' || sortBy == '') {
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

				totalGroups = j['totalGroups'];

				/*
				totalRecords = parseInt(j['totalRecords']);
				totalTests = j['testTypes'].length;
				*/
				groupRowData = '';
                tableHeading = ''; 
				if (totalGroups == 0) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					var tableData = globalTB;
					tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text reportBorder">#</td><td width="8%" class="searchhead_text reportBorder">Roll No.</td><td width="8%" class="searchhead_text reportBorder">U.Roll No.</td><td width="15%" class="searchhead_text reportBorder">Student Name</td>';
					tableData += '<td width=78% class="searchhead_text reportBorder">&nbsp;</td></tr><tr '+bg+'>';
					tableData += '<td class="padding_top" align=center colspan=5>No details found</td></tr>';
				}
				else {
					spacePerGroup = parseInt(75 / totalGroups);
					var tableData = globalTB;
					tableData += '<tr class="rowheading"><td rowspan="2" width="2%" class="searchhead_text">#</td><td rowspan="2" width="8%" class="searchhead_text">Roll No.</td><td width="8%" rowspan="2" class="searchhead_text">U.Roll No.</td><td width="15%" rowspan="2" class="searchhead_text">Student Name</td>';
                    tableHeading += '<tr class="rowheading"><td width="2%" class="searchhead_text">#</td><td width="8%" class="searchhead_text">Roll No.</td><td width="8%" class="searchhead_text">U.Roll No.</td><td width="15%" class="searchhead_text">Student Name</td>';
					totalGroupTypes = j['groupTypes'].length;
					groupTypeCtr = 0;
					while (groupTypeCtr < totalGroupTypes) {
						groupTypeName = j['groupTypes'][groupTypeCtr];
						totalGroups = j['groups'][groupTypeName].length;
						tableData += '<td width="'+spacePerGroup+'" colspan="'+totalGroups+'" class="searchhead_text">'+groupTypeName+'</td>';
                        tableData += '<td rowspan="2" width="8%" class="searchhead_text">Roll No.</td>';
						groupTypeCtr++;
					}
					tableData += '</tr>';
					tableData += '<tr class="rowheading">';
					groupTypeCtr = 0;
					while (groupTypeCtr < totalGroupTypes) {
						groupTypeName = j['groupTypes'][groupTypeCtr];
						totalGroups = j['groups'][groupTypeName].length;
						groupCtr = 0;
						groupRowData += '<tr>';
						while (groupCtr < totalGroups) {
							groupName = j['groups'][groupTypeName][groupCtr]['groupName'];
							groupId = j['groups'][groupTypeName][groupCtr]['groupId'];
							spanName = groupName+'Span';
							studentsAllocated = 0;
							if (j['countGroupStudent'][groupId]) {
								studentsAllocated = j['countGroupStudent'][groupId];
							}
							groupRowData += '<td>'+groupName+'</td><td><b>&nbsp;:&nbsp;'+studentsAllocated+'</b></td>';
							tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">'+groupName+'</td>';
                            tableHeading += '<td width="'+spacePerGroup+'" class="searchhead_text">'+groupName+'</td>';
							groupCtr++;
						}
                        tableHeading += '<td width="'+spacePerGroup+'" class="searchhead_text">Roll No.</td>';
						groupRowData += '</tr>';
						groupTypeCtr++;
					}
					tableData += '</tr>';
                    
                    tableHeading += '</tr>';
                    
                    //tableHeading = tableData;

					totalStudentGroups = j['studentGroups'].length;
					studentCounter = 0;
                    
					while (studentCounter < totalStudentGroups) {
						recordCounter = studentCounter + 1;
                        if(recordCounter%13==0) {
                          tableData +=tableHeading;
                        }
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						tableData += '<tr '+bg+'>';
						tableData += '<td>'+recordCounter+'</td>';
						studentId = j['studentGroups'][studentCounter]['studentId'];
						tableData += '<td>'+j['studentGroups'][studentCounter]['rollNo']+'</td>';
						tableData += '<td>'+j['studentGroups'][studentCounter]['universityRollNo']+'</td>';
						tableData += '<td>'+j['studentGroups'][studentCounter]['studentName']+'</td>';
						groupsAllocated = j['studentGroups'][studentCounter]['groupsAllocated'];
						if (groupsAllocated !== null) {
							groupsAllocatedArray = groupsAllocated.split(',');

						}
						groupTypeCtr = 0;
						while (groupTypeCtr < totalGroupTypes) {
							groupTypeName = j['groupTypes'][groupTypeCtr];
							totalGroups = j['groups'][groupTypeName].length;
							groupCtr = 0;
							while (groupCtr < totalGroups) {
								groupId = j['groups'][groupTypeName][groupCtr]['groupId'];
								groupName = j['groups'][groupTypeName][groupCtr]['groupName'];
								checkBoxName = "chk["+groupId+"][]";
								checkBoxId = "chk_"+groupId+"_"+recordCounter;
								tdId = "td_"+groupId+"_"+recordCounter;
								checked = '';
								tdClass = ' class="" ';
								if (groupsAllocated !== null) {
									totalGroupsAllocated = groupsAllocatedArray.length;
									groupCounter = 0;
									while (groupCounter < totalGroupsAllocated) {
										selectedGroupId = groupsAllocatedArray[groupCounter];
										if (selectedGroupId == groupId) {
											checked = ' checked';
											tdClass = ' class = "highlightPermission" ';
										}
										groupCounter++;
									}
								}
								tableData += '<td '+tdClass+' width="'+spacePerGroup+'" id="'+tdId+'"><input type="checkbox" '+checked+' id="'+checkBoxId+'" name="'+checkBoxName+'" value="'+studentId+'" onclick=\"return makeSelection(this.id);\" ></td>';
								groupCtr++;
							}
                            tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">'+j['studentGroups'][studentCounter]['rollNo']+'</td>';  
							groupTypeCtr++;
						}
						tableData += '</tr>';
						studentCounter++;
					}
                    
                    if(studentCounter==0) {
                       var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                        tableData += '<tr '+bg+'>';
                        tableData += '<td class="padding_top" align=center colspan="50"><b>No details found</b></td></tr>';
                    }
                    
				}
				tableData += "</table>";

				document.getElementById("groupRow").style.display='';
				document.getElementById("nameRow").style.display='';
				document.getElementById("nameRow2").style.display='';
				document.getElementById("resultRow").style.display='';
				document.getElementById("resultsDiv").innerHTML = tableData;
				document.getElementById("groupRow").innerHTML = "<b>Group Wise Student Counter:</b><table border='0' rules='rows' cellspacing='5' cellpadding='3' style='border:1px solid #000000;'>"+groupRowData+"</table>";
				countSelection();
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function countSelection() {
	/*
	var x;
	for (x in form.elements['chk[][]']) {
		alert(form.elements['chk[][]'][x]);
	}
	*/
}

function makeSelection(eleId) {
	eleIdArray = eleId.split('_');
	groupName = eleIdArray[1];
	groupCounter = eleIdArray[2];
	groupCounter2 = groupCounter;
	if (shiftPressed == true) {
		preEleId = "chk_"+groupName+"_"+groupCounter2;
		while (document.getElementById(preEleId)) {
			preEleId = "chk_"+groupName+"_"+groupCounter2;
			if (document.getElementById(preEleId)) {
				if (document.getElementById(preEleId).checked == false) {
					document.getElementById(preEleId).checked = true;
					tdId = "td_"+groupName+"_"+groupCounter2;
					if (document.getElementById(tdId)) {
						document.getElementById(tdId).className = 'highlightPermission';
					}
				}
				else {
					break;
				}
			}
			groupCounter2--;
		}
		shiftPressed = false;
	}
	if (document.getElementById(eleId).checked == false) {
		tdId = "td_"+groupName+"_"+groupCounter2;
		if (document.getElementById(tdId)) {
			document.getElementById(tdId).className = '';
		}
	}
	else{
		tdId = "td_"+groupName+"_"+groupCounter2;
		if (document.getElementById(tdId)) {
			document.getElementById(tdId).className = 'highlightPermission';
		}
	}
	return true;
}

function saveSelectedStudents() {
	pars = generateQueryString('assignGroup');

    url = '<?php echo HTTP_LIB_PATH;?>/Student/initStudentAssignGroupAdvancedNew.php';
    new Ajax.Request(url,
    {
	     method:'post',
	     parameters: pars,
         asynchronous:false,
         onCreate: function () {
             showWaitDialog(true);
         },
	     onSuccess: function(transport){

			    hideWaitDialog(true);
                //alert(transport.responseText);
			    j = trim(transport.responseText);

				if (j == "<?php echo SUCCESS;?>") {
					hideResults();
					messageBox(j);
				}
				else {
					displayFloatingDiv("groupSave",100,200);
					document.getElementById("groupSaveDiv").innerHTML = j;
				}

	     },
	     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
	document.getElementById('groupRow').style.display='none';
}

document.onkeydown = function (e) {
	var ev = e|| window.event;
	thisKeyCode = ev.keyCode;
	if (thisKeyCode == '16') {
		shiftPressed = true;
	}
}

function printReport() {
	form = document.assignGroup;
	degree = form.degree.value;
	sortBy = form.sortBy.value;
	var path='<?php echo UI_HTTP_PATH;?>/displayAssignGroupAdvancedReport.php?degree='+degree+'&sortBy='+sortBy;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"AssignGroupReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){

    }
}

/* function to output data to a CSV*/
function printCSV() {
	form = document.assignGroup;
	degree = form.degree.value;
	sortBy = form.sortBy.value;
    path='<?php echo UI_HTTP_PATH;?>/displayAssignGroupAdvancedCSV.php?degree='+degree+'&sortBy='+sortBy;
	window.location = path;
}




function getTimeTableClasses() {
    hideResults();
    var form = document.assignGroup;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
    var pars = 'labelId='+form.labelId.value;
    
    form.degree.options.length = 0;
    addOption(form.degree, '', 'Select');
    if (form.labelId.value=='') {
        return false;
    }

    new Ajax.Request(url,
    {
        method:'post',
        asynchronous : false,
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
	    
	    form.degree.options.length = 0;
            addOption(form.degree, '', 'Select');
            for(i=0;i<len;i++) {
                addOption(form.degree, j[i].classId, j[i].className);
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
    require_once(TEMPLATES_PATH . "/Student/listAssignGroupAdvanced.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
//$History: assignGroupAdvanced.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 3/17/10    Time: 12:41p
//Updated in $/LeapCC/Interface
//done coding for print & csv, FCNS No. 1427
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/17/10    Time: 10:53a
//Updated in $/LeapCC/Interface
//put export to excel & print buttons
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 11/12/09   Time: 2:28p
//Updated in $/LeapCC/Interface
//resolved issue 1966
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 10/08/09   Time: 3:13p
//Created in $/LeapCC/Interface
//file added for assign groups advanced


?>
