<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
//testing
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Missed Attendance Report </title>
<?php 
echo UtilityManager::includeJS("jquery-1.2.2.pack.js"); 
echo UtilityManager::includeJS("animatedcollapse.js");  
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), new Array('studentName','Student Name','width=20%','',true), new Array('rollNo','Roll No','width="25%"','',true), new Array('programme','Programme','width="30%"','',true), new Array('periodName','Period Name','width="20%"','',true)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initAllDetailsReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'Asc';
 //This function Validates Form 
var queryString;

function validateAddForm() {
	/*
    var fieldsArray = new Array(new Array("class","<?php echo SELECT_DEGREE;?>"),new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));

    if (frm.class.value != 'all') {
		var len = fieldsArray.length;
		for(i=0;i<len;i++) {
			if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
				messageBox(fieldsArray[i][1]);
				eval("frm."+(fieldsArray[i][0])+".focus();");
				return false;
				break;
			}
		}
	}
	*/

	queryString = '';
	form = document.allDetailsForm;

	//roll no. + first name + last name
	queryString += 'rollNo='+form.rollNo.value;
	queryString += '&studentName='+form.studentName.value;


	//degree
	totalDegreeId = form.elements['degreeId[]'].length;
	selectedDegrees='';
	for(i=0;i<totalDegreeId;i++) {
		if (form.elements['degreeId[]'][i].selected == true) {
			if (selectedDegrees != '') {
				selectedDegrees += ',';
			}
			selectedDegrees += form.elements['degreeId[]'][i].value;
		}
	}

	queryString += '&degreeId='+selectedDegrees;

	//branch
	totalBranchId = form.elements['branchId[]'].length;
	selectedBranches='';
	for(i=0;i<totalBranchId;i++) {
		if (form.elements['branchId[]'][i].selected == true) {
			if (selectedBranches != '') {
				selectedBranches += ',';
			}
			selectedBranches += form.elements['branchId[]'][i].value;
		}
	}

	queryString += '&branchId='+selectedBranches;

	//periodicity
	totalPeriodicityId = form.elements['periodicityId[]'].length;
	selectedPeriodicity='';
	for(i=0;i<totalPeriodicityId;i++) {
		if (form.elements['periodicityId[]'][i].selected == true) {
			if (selectedPeriodicity != '') {
				selectedPeriodicity += ',';
			}
			selectedPeriodicity += form.elements['periodicityId[]'][i].value;
		}
	}

	queryString += '&periodicityId='+selectedPeriodicity;

	//course [subject]
	totalSubjectId = form.elements['courseId[]'].length;
	selectedSubjectId='';
	for(i=0;i<totalSubjectId;i++) {
		if (form.elements['courseId[]'][i].selected == true) {
			if (selectedSubjectId != '') {
				selectedSubjectId += ',';
			}
			selectedSubjectId += form.elements['courseId[]'][i].value;
		}
	}

	queryString += '&courseId='+selectedSubjectId;

	//group
	totalGroupId = form.elements['groupId[]'].length;
	selectedGroupId='';
	for(i=0;i<totalGroupId;i++) {
		if (form.elements['groupId[]'][i].selected == true) {
			if (selectedGroupId != '') {
				selectedGroupId += ',';
			}
			selectedGroupId += form.elements['groupId[]'][i].value;
		}
	}

	queryString += '&groupId='+selectedGroupId;



	//from date of admission
	fromDateA = form.admissionMonthF.value+'-'+form.admissionDateF.value+'-'+form.admissionYearF.value;
	queryString += '&fromDateA='+fromDateA;

	
	//to date of admission
	toDateA = form.admissionMonthT.value+'-'+form.admissionDateT.value+'-'+form.admissionYearT.value;
	queryString += '&toDateA='+toDateA;

	//from date of birth
	fromDateD = form.birthMonthF.value+'-'+form.birthDateF.value+'-'+form.birthYearF.value;
	queryString += '&fromDateD='+fromDateD;

	//to date of birth
	toDateD = form.birthMonthT.value+'-'+form.birthDateT.value+'-'+form.birthYearT.value;
	queryString += '&toDateD='+toDateD;

	//gender + mgmt. category + quota
	queryString += '&gender='+form.gender.value;
	queryString += '&categoryId='+form.categoryId.value;
	queryString += '&quotaId='+form.quotaId.value;


	//hotsel
	totalHostelId = form.elements['hostelId[]'].length;
	selectedHostel='';
	for(i=0;i<totalHostelId;i++) {
		if (form.elements['hostelId[]'][i].selected == true) {
			if (selectedHostel != '') {
				selectedHostel += ',';
			}
			selectedHostel += form.elements['hostelId[]'][i].value;
		}
	}

	queryString += '&hostelId='+selectedHostel;

	//bus stop
	totalBusStopId = form.elements['busStopId[]'].length;
	selectedBusStop='';
	for(i=0;i<totalBusStopId;i++) {
		if (form.elements['busStopId[]'][i].selected == true) {
			if (selectedBusStop != '') {
				selectedBusStop += ',';
			}
			selectedBusStop += form.elements['busStopId[]'][i].value;
		}
	}

	queryString += '&busStopId='+selectedBusStop;


	//bus route
	totalBusRouteId = form.elements['busRouteId[]'].length;
	selectedBusRoute='';
	for(i=0;i<totalBusRouteId;i++) {
		if (form.elements['busRouteId[]'][i].selected == true) {
			if (selectedBusRoute != '') {
				selectedBusRoute += ',';
			}
			selectedBusRoute += form.elements['busRouteId[]'][i].value;
		}
	}
	queryString += '&busRouteId='+selectedBusRoute;


	//city
	totalCityId = form.elements['cityId[]'].length;
	selectedCity='';
	for(i=0;i<totalCityId;i++) {
		if (form.elements['cityId[]'][i].selected == true) {
			if (selectedCity != '') {
				selectedCity += ',';
			}
			selectedCity += form.elements['cityId[]'][i].value;
		}
	}
	queryString += '&cityId='+selectedCity;

	//state
	totalStateId = form.elements['stateId[]'].length;
	selectedState='';
	for(i=0;i<totalStateId;i++) {
		if (form.elements['stateId[]'][i].selected == true) {
			if (selectedState != '') {
				selectedState += ',';
			}
			selectedState += form.elements['stateId[]'][i].value;
		}
	}
	queryString += '&stateId='+selectedState;

	//country
	totalCountryId = form.elements['countryId[]'].length;
	selectedCountry='';
	for(i=0;i<totalCountryId;i++) {
		if (form.elements['countryId[]'][i].selected == true) {
			if (selectedCountry != '') {
				selectedCountry += ',';
			}
			selectedCountry += form.elements['countryId[]'][i].value;
		}
	}
	queryString += '&countryId='+selectedCountry;

	//univ.
	totalUniversityId = form.elements['universityId[]'].length;
	selectedUniversity='';
	for(i=0;i<totalUniversityId;i++) {
		if (form.elements['universityId[]'][i].selected == true) {
			if (selectedUniversity != '') {
				selectedUniversity += ',';
			}
			selectedUniversity += form.elements['universityId[]'][i].value;
		}
	}
	queryString += '&universityId='+selectedUniversity;

	//institute
	/*
	totalInstituteId = form.elements['instituteId[]'].length;
	selectedInstitute='';
	for(i=0;i<totalInstituteId;i++) {
		if (form.elements['instituteId[]'][i].selected == true) {
			if (selectedInstitute != '') {
				selectedInstitute += ',';
			}
			selectedInstitute += form.elements['instituteId[]'][i].value;
		}
	}
	queryString += '&instituteId='+selectedInstitute;
	*/
	queryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField

	showHide("hideAll");
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	dontMakeQueryString = true;
	sendReq(listURL,divResultName,searchFormName, queryString);
//	openStudentLists(frm.name,'class','Asc');    
}

function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function printReport() {
	form = document.allDetailsForm;
	path='<?php echo UI_HTTP_PATH;?>/allDetailsReportPrint.php?queryString='+queryString;
	window.open(path,"AllDetailsReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
	document.getElementById('generateCSV').href='allDetailsReportPrintCSV.php?queryString='+queryString;
	document.getElementById('generateCSV2').href='allDetailsReportPrintCSV.php?queryString='+queryString;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listAllDetailsReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: allDetailsReport.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/23/08   Time: 11:58a
//Updated in $/LeapCC/Interface
//changed subjectId to courseId
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/22/08   Time: 6:11p
//Updated in $/LeapCC/Interface
//added code for groups and subjects
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/13/08    Time: 2:45p
//Created in $/Leap/Source/Interface
//file added for "allDetailsReport.php"
//

?>