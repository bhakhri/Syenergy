<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Cleaning History Form
//
//
// Author :Jaineesh
// Created on : 28.04.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CleaningHistoryMaster');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Cleaning History </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false),
								new Array('tempEmployeeName','Employee Name','width=10%','',true),
								new Array('hostelName','Hostel Name','width=10%','',true),
								new Array('Dated','Date','width="6%"','align="center"',true),
								new Array('toiletsCleaned','Toilet(s) Cleaned','width="8%"','align=right',true),
								new Array('noOfRoomsCleaned','Room(s) Cleaned','width="10%"','align=right',true),
								new Array('attachedRoomToiletsCleaned','Attached Room Toilet(s) Cleaned','width="10%"','align=right',true),
								new Array('dryMoppingInSqMeter','Dry Mopping','width="8%"','align=right',true),
								new Array('wetMoppingInSqMeter','Wet Mopping','width="8%"','align=right',true),
								new Array('roadCleanedInSqMeter','Road cleaned','width="8%"','align=right',true),
								new Array('noOfGarbageBinsDisposal','Garbage Disposal','width="10%"','align=right',true),
								new Array('noOfHoursWorked','No. of hrs.','width="8%"','align=right',true)


							);

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/CleaningHistory/initCleaningHistoryReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'CleaningHistoryForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'tempEmployeeName';
sortOrderBy    = 'ASC';

function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("startDate","<?php echo SELECT_DATE;?>"),
								new Array("toDate","<?php echo SELECT_TODATE;?>")
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
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}


function printReport() {
	form = document.CleaningHistoryForm;
	var totalTempEmployee = form.elements['tempEmployee[]'].length;
	var employeeText = document.getElementById('tempEmployee');

	var selectedEmployee='';
	var selectedEmployeeText='';
	var countEmployee=0;

	for(i=0;i<totalTempEmployee;i++) {
		if (form.elements['tempEmployee[]'][i].selected == true) {
			if (selectedEmployee != '') {
				selectedEmployee += ',';
				selectedEmployeeText += ', ';
			}
			countEmployee++;
			selectedEmployee += form.elements['tempEmployee[]'][i].value;
			selectedEmployeeText += eval("employeeText.options["+i+"].text");
		}
	}
	if(countEmployee==totalTempEmployee || countEmployee==0){
		selectedEmployeeText ="ALL";
	}
	var totalHostel = form.elements['hostel[]'].length;
	var hostelText = document.getElementById('hostel');

	var selectedHostel='';
	var selectedHostelText='';
	var countHostel=0;

	for(i=0;i<totalHostel;i++) {
		if (form.elements['hostel[]'][i].selected == true) {
			if (selectedHostel != '') {
				selectedHostel += ',';
				selectedHostelText += ', ';
			}
			countHostel++;
			selectedHostel += form.elements['hostel[]'][i].value;
			selectedHostelText += eval("hostelText.options["+i+"].text");
		}
	}
	if(countHostel==totalHostel || countHostel==0){
		selectedHostelText ="ALL";
	}
	path='<?php echo UI_HTTP_PATH;?>/cleaningHistoryReportPrint.php?tempEmployee='+form.tempEmployee.value+'&hostel='+form.hostel.value+'&startDate='+form.startDate.value+'&toDate='+form.toDate.value+'&employeeText='+selectedEmployeeText+'&hostelText='+selectedHostelText;
	a = window.open(path,"CleaningHistoryReport","status=1,menubar=1,scrollbars=1, width=900");
}
function printReportCSV() {
    
   	form = document.CleaningHistoryForm;
	var totalTempEmployee = form.elements['tempEmployee[]'].length;
	var employeeText = document.getElementById('tempEmployee');

	var selectedEmployee='';
	var selectedEmployeeText='';
	var countEmployee=0;

	for(i=0;i<totalTempEmployee;i++) {
		if (form.elements['tempEmployee[]'][i].selected == true) {
			if (selectedEmployee != '') {
				selectedEmployee += ',';
				selectedEmployeeText += ', ';
			}
			countEmployee++;
			selectedEmployee += form.elements['tempEmployee[]'][i].value;
			selectedEmployeeText += eval("employeeText.options["+i+"].text");
		}
	}
	if(countEmployee==totalTempEmployee || countEmployee==0){
		selectedEmployeeText ="ALL";
	}
	var totalHostel = form.elements['hostel[]'].length;
	var hostelText = document.getElementById('hostel');

	var selectedHostel='';
	var selectedHostelText='';
	var countHostel=0;

	for(i=0;i<totalHostel;i++) {
		if (form.elements['hostel[]'][i].selected == true) {
			if (selectedHostel != '') {
				selectedHostel += ',';
				selectedHostelText += ', ';
			}
			countHostel++;
			selectedHostel += form.elements['hostel[]'][i].value;
			selectedHostelText += eval("hostelText.options["+i+"].text");
		}
	}
	if(countHostel==totalHostel || countHostel==0){
		selectedHostelText ="ALL";
	}
    path='<?php echo UI_HTTP_PATH;?>/cleaningHistoryReportCSV.php?tempEmployee='+form.tempEmployee.value+'&hostel='+form.hostel.value+'&startDate='+form.startDate.value+'&toDate='+form.toDate.value+'&employeeText='+selectedEmployeeText+'&hostelText='+selectedHostelText;
    window.location = path;
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
    require_once(TEMPLATES_PATH . "/CleaningHistory/listCleaningHistoryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: listCleaningHistory.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/16/09   Time: 5:29p
//Updated in $/LeapCC/Interface
//fixed bug nos.0002013,0002014
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 4:34p
//Updated in $/LeapCC/Interface
//show some fields on list
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:20p
//Created in $/LeapCC/Interface
//new files for cleaning room
//
?>