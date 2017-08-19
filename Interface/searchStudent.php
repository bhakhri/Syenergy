<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentInfo');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
$showTitle = "none";
$showData  = "none";
$showPrint = "none";
//require_once(BL_PATH . "/Student/initList.php");
$queryString =  $_SERVER['QUERY_STRING'];
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Student Detail</title>
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
var topPos = 0;
var leftPos = 0;
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag


var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('firstName','Student Name','width="15%"','',true), 
                               new Array('rollNo','<?php echo COLUMN_ROLL_NO ?>','width="8%"','',true), 
                               new Array('universityRollNo','<?php echo COLUMN_UNIV_ROLL_NO ?>','width="8%"','',true),
                               new Array('regNo','<?php echo COLUMN_REG_NO ?>','width="8%"','',true),
                               new Array('className','Class','width="12%"','',true),
                               new Array('groupId','Group','width="12%"','',true),
                               new Array('studentMobileNo','Mobile','width="5%"','',false), 
                               new Array('imgSrc','Photo','width="5%" align="center"','align="center"',false), 
                               new Array('act','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'firstName';
sortOrderBy    = 'ASC';
var queryString='';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}

function validateAddForm() {
 
	/* START: search filter */

	queryString = '';
	form = document.allDetailsForm;
	
	if(!isEmpty(document.getElementById('birthYearF').value) || !isEmpty(document.getElementById('birthMonthF').value) || !isEmpty(document.getElementById('birthDateF').value)){
		
		if(isEmpty(document.getElementById('birthYearF').value)){
		   
		   messageBox("Please select date of birth year");
		   document.allDetailsForm.birthYearF.focus();
		   return false;
		}
		if(isEmpty(document.getElementById('birthMonthF').value)){
		   
		   messageBox("Please select date of birth month");
		   document.allDetailsForm.birthMonthF.focus();
		   return false;
		}
		if(isEmpty(document.getElementById('birthDateF').value)){
		   
		   messageBox("Please select date of birth date");
		   document.allDetailsForm.birthDateF.focus();
		   return false;
		}
		dT1= document.getElementById('birthDateF').value;
		dM1= document.getElementById('birthMonthF').value;
		dY1= document.getElementById('birthYearF').value;
		dateStr1 = dM1+"-"+dT1+"-"+dY1;
		if(!isDate(dateStr1)) {
			 return false;
	}
}
	
	if(!isEmpty(document.getElementById('birthYearT').value) || !isEmpty(document.getElementById('birthMonthT').value) || !isEmpty(document.getElementById('birthDateT').value)){
		
		if(isEmpty(document.getElementById('birthYearT').value)){
		   
		   messageBox("Please select date of birth year");
		   document.allDetailsForm.birthYearT.focus();
		   return false;
		}
		if(isEmpty(document.getElementById('birthMonthT').value)){
		   
		   messageBox("Please select date of birth month");
		   document.allDetailsForm.birthMonthT.focus();
		   return false;
		}
		if(isEmpty(document.getElementById('birthDateT').value)){
		   
		   messageBox("Please select date of birth date");
		   document.allDetailsForm.birthDateT.focus();
		   return false;
		}
		dT2= document.getElementById('birthDateT').value;
		dM2= document.getElementById('birthMonthT').value;
		dY2= document.getElementById('birthYearT').value;
		dateStr2 = dM2+"-"+dT2+"-"+dY2;
		if(!isDate(dateStr2)) {
			return false;
		}
	}
    
	if(!isEmpty(document.getElementById('birthYearF').value) && !isEmpty(document.getElementById('birthMonthF').value) && !isEmpty(document.getElementById('birthDateF').value) && !isEmpty(document.getElementById('birthYearT').value) && !isEmpty(document.getElementById('birthMonthT').value) && !isEmpty(document.getElementById('birthDateT').value)){
	
		dobFValue = document.getElementById('birthYearF').value+"-"+document.getElementById('birthMonthF').value+"-"+document.getElementById('birthDateF').value

		dobTValue = document.getElementById('birthYearT').value+"-"+document.getElementById('birthMonthT').value+"-"+document.getElementById('birthDateT').value

		if(dateCompare(dobFValue,dobTValue)==1){
		   
		   messageBox("From Date of birth cannot be greater than To Date of birth");
		   document.allDetailsForm.birthYearF.focus();
		   return false;
		}
	}

	/* admission date*/
	if(!isEmpty(document.getElementById('admissionYearF').value) || !isEmpty(document.getElementById('admissionMonthF').value) || !isEmpty(document.getElementById('admissionDateF').value)){
		
		if(isEmpty(document.getElementById('admissionYearF').value)){
		   
		   messageBox("Please select date of admission year");
		   document.allDetailsForm.admissionYearF.focus();
		   return false;
		}
		if(isEmpty(document.getElementById('admissionMonthF').value)){
		   
		   messageBox("Please select date of admission month");
		   document.allDetailsForm.admissionMonthF.focus();
		   return false;
		}
		if(isEmpty(document.getElementById('admissionDateF').value)){
		   
		   messageBox("Please select date of admission date");
		   document.allDetailsForm.admissionDateF.focus();
		   return false;
		}
	}
	

	if(!isEmpty(document.getElementById('admissionYearT').value) || !isEmpty(document.getElementById('admissionMonthT').value) || !isEmpty(document.getElementById('admissionDateT').value)){
		
		if(isEmpty(document.getElementById('admissionYearT').value)){
		   
		   messageBox("Please select date of admission year");
		   document.allDetailsForm.admissionYearT.focus();
		   return false;
		}
		if(isEmpty(document.getElementById('admissionMonthT').value)){
		   
		   messageBox("Please select date of admission month");
		   document.allDetailsForm.admissionMonthT.focus();
		   return false;
		}
		if(isEmpty(document.getElementById('admissionDateT').value)){
		   
		   messageBox("Please select date of admission date");
		   document.allDetailsForm.admissionDateT.focus();
		   return false;
		}
	}

	if(!isEmpty(document.getElementById('admissionYearF').value) && !isEmpty(document.getElementById('admissionMonthF').value) && !isEmpty(document.getElementById('admissionDateF').value) && !isEmpty(document.getElementById('admissionYearT').value) && !isEmpty(document.getElementById('admissionMonthT').value) && !isEmpty(document.getElementById('admissionDateT').value)){
	
		doaFValue = document.getElementById('admissionYearF').value+"-"+document.getElementById('admissionMonthF').value+"-"+document.getElementById('admissionDateF').value

		doaTValue = document.getElementById('admissionYearT').value+"-"+document.getElementById('admissionMonthT').value+"-"+document.getElementById('admissionDateT').value

		if(dateCompare(doaFValue,doaTValue)==1){
		   
		   messageBox("From Date of admission cannot be greater than To Date of admission");
		   document.allDetailsForm.admissionYearF.focus();
		   return false;
		}
	}
	//roll no. + first name + last name
	queryString += 'rollNo='+form.rollNo.value;
	queryString += '&studentName='+form.studentName.value;
	queryString += '&gender='+form.gender.value;
	queryString += '&quotaId='+form.quotaId.value;
	quotaText ='';
	var quotaText = document.getElementById('quotaId');
	quotaTextValue = quotaText.options[quotaText.selectedIndex].text;
	queryString += '&quotaText='+quotaTextValue;
	document.allDetailsForm.quotaText.value = quotaTextValue;

	queryString += '&bloodGroup='+form.bloodGroup.value;
	bloodGroupText ='';
	var bloodGroupText = document.getElementById('bloodGroup');
	bloodGroupTextValue = bloodGroupText.options[bloodGroupText.selectedIndex].text;
	queryString += '&bloodGroupText='+bloodGroupTextValue;
	document.allDetailsForm.bloodGroupText.value = bloodGroupTextValue;

	//from date of birth
	//fromDateD = form.birthMonthF.value+'-'+form.birthDateF.value+'-'+form.birthYearF.value;
	queryString += '&birthMonthF='+form.birthMonthF.value+'&birthDateF='+form.birthDateF.value+'&birthYearF='+form.birthYearF.value;

	//to date of birth
	//toDateD = form.birthMonthT.value+'-'+form.birthDateT.value+'-'+form.birthYearT.value;
	queryString += '&birthMonthT='+form.birthMonthT.value+'&birthDateT='+form.birthDateT.value+'&birthYearT='+form.birthYearT.value;

	
	//start degree
	totalDegreeId = form.elements['degreeId[]'].length;
	var name = document.getElementById('degreeId');
	selectedDegrees='';
	selectedDegreeText='';
	countDegree=0;
	for(i=0;i<totalDegreeId;i++) {
		if (form.elements['degreeId[]'][i].selected == true) {
			if (selectedDegrees != '') {
				selectedDegrees += ',';
				selectedDegreeText += ', ';
				
			}
			countDegree++;
			selectedDegrees += form.elements['degreeId[]'][i].value;
			selectedDegreeText += eval("name.options["+i+"].text");
		}
	}
	if(countDegree==totalDegreeId || countDegree==0)
		selectedDegreeText ="ALL";
	
	queryString += '&degs='+selectedDegrees+'&degsText='+selectedDegreeText;
	document.allDetailsForm.degs.value = selectedDegrees;
	document.allDetailsForm.degsText.value = selectedDegreeText;
	//end degree

	//start branch
	totalBranchId = form.elements['branchId[]'].length;
	var branchText = document.getElementById('branchId');

	selectedBranches='';
	selectedBranchesText='';
	countBranches=0;
	for(i=0;i<totalBranchId;i++) {
		if (form.elements['branchId[]'][i].selected == true) {
			if (selectedBranches != '') {
				selectedBranches += ',';
				selectedBranchesText += ', ';
			}
			countBranches++;
			selectedBranches += form.elements['branchId[]'][i].value;
			selectedBranchesText += eval("branchText.options["+i+"].text");
		}
	}
	if(countBranches==totalBranchId || countBranches==0)
		selectedBranchesText ="ALL";

	queryString += '&brans='+selectedBranches+'&bransText='+selectedBranchesText;
	document.allDetailsForm.brans.value = selectedBranches;
	document.allDetailsForm.branText.value = selectedBranchesText;
	//end branch
	
	//start periodicity
	totalPeriodicityId = form.elements['periodicityId[]'].length;
	var periodText = document.getElementById('periodicityId');

	selectedPeriodicity='';
	selectedPeriodicityText='';
	countPeriod=0;
	for(i=0;i<totalPeriodicityId;i++) {
		if (form.elements['periodicityId[]'][i].selected == true) {
			if (selectedPeriodicity != '') {
				selectedPeriodicity += ',';
				selectedPeriodicityText += ', ';
			}
			countPeriod++;
			selectedPeriodicity += form.elements['periodicityId[]'][i].value;
			selectedPeriodicityText += eval("periodText.options["+i+"].text");
		}
	}
	if(countPeriod==totalPeriodicityId || countPeriod==0)
		selectedPeriodicityText ="ALL";

	queryString += '&periods='+selectedPeriodicity+'&periodsText='+selectedPeriodicityText;
	document.allDetailsForm.periods.value = selectedPeriodicity;
	document.allDetailsForm.periodsText.value = selectedPeriodicityText;
	//end periodicity

	//start Course
	totalCourseId = form.elements['courseId[]'].length;
	var courseText = document.getElementById('courseId');

	selectedCourse='';
	selectedCourseText='';
	countPeriod=0;
	for(i=0;i<totalCourseId;i++) {
		if (form.elements['courseId[]'][i].selected == true) {
			if (selectedCourse != '') {
				selectedCourse += ',';
				selectedCourseText += ', ';
			}
			countPeriod++;
			selectedCourse += form.elements['courseId[]'][i].value;
			selectedCourseText += eval("courseText.options["+i+"].text");
		}
	}
	if(countPeriod==totalCourseId || countPeriod==0)
		selectedCourseText ="ALL";

	queryString += '&course='+selectedCourse+'&courseText='+selectedCourseText;

	 
	document.allDetailsForm.subjectId.value = selectedCourse;
	document.allDetailsForm.courseText.value = selectedCourseText;
	 
	//end Course

	 
	//start group.
	totalGroupId = form.elements['groupId[]'].length;
	var groupText = document.getElementById('groupId');

	selectedGroup='';
	selectedGroupText='';
	countSection=0;

	for(i=0;i<totalGroupId;i++) {
		if (form.elements['groupId[]'][i].selected == true) {
			if (selectedGroup != '') {
				selectedGroup += ',';
				selectedGroupText += ', ';
			}
			countSection++;
			selectedGroup += form.elements['groupId[]'][i].value;
			selectedGroupText += eval("groupText.options["+i+"].text");
		}
	}
	if(countSection==totalGroupId || countSection==0)
		selectedGroupText ="ALL";

	queryString += '&grps='+selectedGroup+'&grpsText='+selectedGroupText;
	document.allDetailsForm.grps.value = selectedGroup;
	document.allDetailsForm.grpsText.value = selectedGroupText;
	//end group.
//alert(queryString);
	//start univ.
	totalUniversityId = form.elements['universityId[]'].length;
	var univText = document.getElementById('universityId');

	selectedUniversity='';
	selectedUniversityText='';
	countUniversity=0;

	for(i=0;i<totalUniversityId;i++) {
		if (form.elements['universityId[]'][i].selected == true) {
			if (selectedUniversity != '') {
				selectedUniversity += ',';
				selectedUniversityText += ', ';
			}
			countUniversity++;
			selectedUniversity += form.elements['universityId[]'][i].value;
			selectedUniversityText += eval("univText.options["+i+"].text");
		}
	}
	if(countUniversity==totalUniversityId || countUniversity==0)
		selectedUniversityText ="ALL";

	queryString += '&univs='+selectedUniversity+'&univsText='+selectedUniversityText;
	document.allDetailsForm.univs.value = selectedUniversity;
	document.allDetailsForm.univsText.value = selectedUniversityText;

	//end univ.

	//alert(queryString);

	//start city
	totalCityId = form.elements['cityId[]'].length;
	var cityText = document.getElementById('cityId');

	selectedCity='';
	selectedCityText='';
	countCity=0;

	for(i=0;i<totalCityId;i++) {
		if (form.elements['cityId[]'][i].selected == true) {
			if (selectedCity != '') {
				selectedCity += ',';
				selectedCityText += ', ';
			}
			countCity++;
			selectedCity += form.elements['cityId[]'][i].value;
			selectedCityText += eval("cityText.options["+i+"].text");
		}
	}
	if(countCity==totalCityId || countCity==0)
		selectedCityText ="ALL";

	queryString += '&citys='+selectedCity+'&citysText='+selectedCityText;
	document.allDetailsForm.citys.value = selectedCity;
	document.allDetailsForm.citysText.value = selectedCityText;

	//start state
	totalStateId = form.elements['stateId[]'].length;
	var stateText = document.getElementById('stateId');

	selectedState='';
	selectedStateText='';
	countState=0;

	for(i=0;i<totalStateId;i++) {
		if (form.elements['stateId[]'][i].selected == true) {
			if (selectedState != '') {
				selectedState += ',';
				selectedStateText += ', ';
			}
			countState++;
			selectedState += form.elements['stateId[]'][i].value;
			selectedStateText += eval("stateText.options["+i+"].text");
		}
	}
	if(countState==totalStateId || countState==0)
		selectedStateText ="ALL";

	queryString += '&states='+selectedState+'&statesText='+selectedStateText;
	document.allDetailsForm.states.value = selectedState;
	document.allDetailsForm.statesText.value = selectedStateText;
	//end state

	//start country
	totalCountryId = form.elements['countryId[]'].length;
	var countryText = document.getElementById('countryId');

	selectedCountry='';
	selectedCountryText='';
	countCountry=0;

	for(i=0;i<totalCountryId;i++) {
		if (form.elements['countryId[]'][i].selected == true) {
			if (selectedCountry != '') {
				selectedCountry += ',';
				selectedCountryText += ', ';
			}
			countCountry++;
			selectedCountry += form.elements['countryId[]'][i].value;
			selectedCountryText += eval("countryText.options["+i+"].text");
		}
	}
	if(countCountry==totalCountryId || countCountry==0)
		selectedCountryText ="ALL";

	queryString += '&cnts='+selectedCountry+'&cntsText='+selectedCountryText;
	document.allDetailsForm.cnts.value = selectedCountry;
	document.allDetailsForm.cntsText.value = selectedCountryText;
	//end country

	//management category
	//var categoryText = document.getElementById('categoryId');
	//categoryTextValue = categoryText.options[categoryText.selectedIndex].text;

	queryString += '&categoryId='+form.categoryId.value;
	 
	//from date of admission
	//fromDateA = form.admissionMonthF.value+'-'+form.admissionDateF.value+'-'+form.admissionYearF.value;
	queryString += '&admissionMonthF='+form.admissionMonthF.value+'&admissionDateF='+form.admissionDateF.value+'&admissionYearF='+form.admissionYearF.value;
	
	//to date of admission
	//toDateA = form.admissionMonthT.value+'-'+form.admissionDateT.value+'-'+form.admissionYearT.value;
	queryString += '&admissionMonthT='+form.admissionMonthT.value+'&admissionDateT='+form.admissionDateT.value+'&admissionYearT='+form.admissionYearT.value;

	//start hotsel
	totalHostelId = form.elements['hostelId[]'].length;
	var hostelText = document.getElementById('hostelId');

	selectedHostel='';
	selectedHostelText='';
	countHostel=0;

	for(i=0;i<totalHostelId;i++) {
		if (form.elements['hostelId[]'][i].selected == true) {
			if (selectedHostel != '') {
				selectedHostel += ',';
				selectedHostelText += ', ';
			}
			countHostel++;
			selectedHostel += form.elements['hostelId[]'][i].value;
			selectedHostelText += eval("hostelText.options["+i+"].text");
		}
	}
	if(countHostel==totalHostelId || countHostel==0)
		selectedHostelText ="ALL";

	queryString += '&hostels='+selectedHostel+'&hostelsText='+selectedHostelText;
	document.allDetailsForm.hostels.value = selectedHostel;
	document.allDetailsForm.hostelsText.value = selectedHostelText;
	//end hotsel

	//Start bus stop
	totalBusStopId = form.elements['busStopId[]'].length;
	var busstopText = document.getElementById('busStopId');

	selectedBusStop='';
	selectedBusStopText='';
	countBusstop=0;

	for(i=0;i<totalBusStopId;i++) {
		if (form.elements['busStopId[]'][i].selected == true) {
			if (selectedBusStop != '') {
				selectedBusStop += ',';
				selectedBusStopText += ', ';
			}
			countBusstop++;
			selectedBusStop += form.elements['busStopId[]'][i].value;
			selectedBusStopText += eval("busstopText.options["+i+"].text");
		}
	}
	if(countBusstop==totalBusStopId || countBusstop==0)
		selectedBusStopText ="ALL";

	queryString += '&buss='+selectedBusStop+'&bussText='+selectedBusStopText;
	document.allDetailsForm.buss.value = selectedBusStop;
	document.allDetailsForm.bussText.value = selectedBusStopText;
	//End bus stop

	//start bus route
	totalBusRouteId = form.elements['busRouteId[]'].length;
	var busrouteText = document.getElementById('busRouteId');

	selectedBusRoute='';
	selectedBusRouteText='';
	countBusroute=0;

 
	for(i=0;i<totalBusRouteId;i++) {
		if (form.elements['busRouteId[]'][i].selected == true) {
			if (selectedBusRoute != '') {
				selectedBusRoute += ',';
				selectedBusRouteText += ', ';
			}
			countBusroute++;
			selectedBusRoute += form.elements['busRouteId[]'][i].value;
			selectedBusRouteText += eval("busrouteText.options["+i+"].text");
		}
	}
	if(countBusroute==totalBusRouteId || countBusroute==0)
		selectedBusRouteText ="ALL";

	queryString += '&routs='+selectedBusRoute+'&routsText='+selectedBusRouteText;
	document.allDetailsForm.routs.value = selectedBusRoute;
	document.allDetailsForm.routsText.value = selectedBusRouteText;
	//end bus route

	//################################# FOR SHOWING ATTENDANCE FROM / TO ###################################################//
    if(!isEmpty(document.getElementById('attendanceFrom').value) && !isEmpty(document.getElementById('attendanceTo').value)){
        if (parseInt(document.getElementById('attendanceFrom').value) > parseInt(document.getElementById('attendanceTo').value)){

            messageBox("attendance from value cannot be greater than attendance to value");
            document.getElementById('attendanceFrom').focus();
            return false; 
        }
    }
    if(!isEmpty(document.getElementById('attendanceFrom').value)){
        
        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.getElementById('attendanceFrom').value)){

            messageBox("Please enter correct attendance from value");
            document.getElementById('attendanceFrom').focus();
            return false; 
        }
        else if (document.getElementById('attendanceFrom').value>100){
            
            messageBox("attendance from cannot be greater than 100");
            document.getElementById('attendanceFrom').focus();
            return false; 
        }
    }
    queryString += '&attendanceFrom='+document.getElementById('attendanceFrom').value;
    if(!isEmpty(document.getElementById('attendanceTo').value)){
        
        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.getElementById('attendanceTo').value)){

            messageBox("Please enter correct attendance to value");
            document.getElementById('attendanceTo').focus();
            return false; 
        }
        else if (document.getElementById('attendanceTo').value>100){
            
            messageBox("attendance to cannot be greater than 100");
            document.getElementById('attendanceTo').focus();
            return false; 
        }
    }
    queryString += '&attendanceTo='+document.getElementById('attendanceTo').value;

////////////////////////////////////////////////////////////////////////////
	

	queryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

	//alert(queryString);
	//return false;
	showHide("hideAll");
	
	
	//dontMake//queryString = true;
	sendReq(listURL,divResultName,searchFormName);
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	/* END: search filter*/

}

/* function to print all student report*/
function printReport() {
	 
	queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	 
    qtr = "<?php echo $queryString?>";
	//if(qtr!='')
		//queryString = qtr;

//alert(queryString);
	form = document.allDetailsForm;
    
    var alumniStudent=1;
    if(form.alumniStudent[0].checked==true){
       alumniStudent=1; 
    }
    else if(form.alumniStudent[1].checked==true){
       alumniStudent=2; 
    }
    else{
       alumniStudent=3; 
    }
    
    queryString += '&alumniStudent='+alumniStudent;
    
	path='<?php echo UI_HTTP_PATH;?>/searchStudentReportPrint.php?listStudent=1&'+queryString;
	hideUrlData(path,true);
	// window.open(path,"MissedAttendanceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to print student profile report*/
function printStudentReport(studentId,classId) {
	
	var form = document.allDetailsForm;
	path='<?php echo UI_HTTP_PATH;?>/studentPrint.php?studentId='+studentId+'&classId='+classId;
	
	hideUrlData(path,true);
	
	// window.open(path,"MissedAttendanceReport","status=1,menubar=1,scrollbars=1, width=700, height=500, top=100,left=50");
}
/* function for help*/
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
	 if(document.getElementById('helpChk').checked == false) {
		 return false;
	 }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

/* function to print all student report*/
function printStudentCSV() {

	queryString = '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    /*
	qtr = "<?php echo $queryString?>";
	if(qtr!='')
		queryString = qtr;
	*/
    var alumniStudent=1;
    form = document.allDetailsForm;
    if(form.alumniStudent[0].checked==true){
       alumniStudent=1; 
    }
    else if(form.alumniStudent[1].checked==true){
       alumniStudent=2; 
    }
    else{
       alumniStudent=3; 
    }
    
    queryString += '&alumniStudent='+alumniStudent;
    
	window.location='searchStudentReportCSV.php?'+queryString;

	/*queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    qtr = "<?php echo $queryString?>";
	if(qtr!='')
		queryString = qtr;

	document.getElementById('generateCSV').href='searchStudentReportCSV.php?queryString='+queryString;
	document.getElementById('generateCSV1').href='searchStudentReportCSV.php?queryString='+queryString;
	*/
}
//populate list
window.onload=function(){

	//alert("<?php echo $queryString?>");
   if("<?php echo $queryString?>"!=''){
       
       var showAlumni="<?php echo $REQUEST_DATA['alumniStudent'];?>";
       if(showAlumni==1){
           document.allDetailsForm.alumniStudent[0].checked=true;
       }
       else if(showAlumni==2){
           document.allDetailsForm.alumniStudent[1].checked=true;
       }
       else if(showAlumni==3){
           document.allDetailsForm.alumniStudent[2].checked=true;
       }
       
       sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
       document.getElementById("nameRow").style.display='';
       document.getElementById("nameRow2").style.display='';
       document.getElementById("resultRow").style.display='';
   }
   var roll = document.getElementById("rollNo");
 autoSuggest(roll);
}

function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
	 if(document.getElementById('helpChk').checked == false) {
		 return false;
	 }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

function vanishData(){
    document.getElementById("nameRow").style.display='none';
    document.getElementById("nameRow2").style.display='none';
    document.getElementById("resultRow").style.display='none';
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: searchStudent.php $
//
//*****************  Version 17  *****************
//User: Ajinder      Date: 2/08/10    Time: 4:06p
//Updated in $/LeapCC/Interface
//fixed bug: 2790
//
//*****************  Version 16  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 3:43p
//Updated in $/LeapCC/Interface
//added values for var topPos,var leftPos
//
//*****************  Version 15  *****************
//User: Parveen      Date: 12/05/09   Time: 11:43a
//Updated in $/LeapCC/Interface
//studentPhoto column added
//
//*****************  Version 14  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 10:58a
//Updated in $/LeapCC/Interface
//added func showHelpDetails
//
//*****************  Version 13  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 09-09-05   Time: 10:47a
//Updated in $/LeapCC/Interface
//Updated formatting for search student
//i.e. updated student list grid width
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 7/13/09    Time: 12:36p
//Updated in $/LeapCC/Interface
//Updated class name on sachin sir request "Class should be printed as
//BTech CSE-1 Sem, not as 2009-PTU-BTech-CSE-1 SEM"
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 6/24/09    Time: 1:36p
//Updated in $/LeapCC/Interface
//0000188: Find Student (Admin-CC) > Data is not displaying in correct
//order on “student list report print” window 
//
//0000183: Find Student - Admin > Search is not working properly in IE
//browser 
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Interface
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/09/09    Time: 5:14p
//Updated in $/LeapCC/Interface
//Enhanced student list with university roll no, group and removed email
//address from search student
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/09/09    Time: 4:15p
//Updated in $/LeapCC/Interface
//Updated issues sent by Sachin sir dated 9thjune
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Interface
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Interface
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Interface
//updated functionality as per CC
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Interface
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Interface
//updated as respect to subject centric
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 9/03/08    Time: 3:10p
//Updated in $/Leap/Source/Interface
//updated formatting and spacing
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:02p
//Updated in $/Leap/Source/Interface
//updated with default display of student attendance, student print
//report
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/23/08    Time: 12:47p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/22/08    Time: 5:48p
//Updated in $/Leap/Source/Interface
//updated print reports
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Interface
//updated formatting and print reports
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/14/08    Time: 3:40p
//Updated in $/Leap/Source/Interface
//added print report function
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/13/08    Time: 12:38p
//Updated in $/Leap/Source/Interface
//updated the formatting of student list
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Interface
//updated the formatting and other issues
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/08    Time: 12:33p
//Updated in $/Leap/Source/Interface
//updated the label
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:18p
//Updated in $/Leap/Source/Interface
//made ajax based
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:44p
//Updated in $/Leap/Source/Interface
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/08    Time: 11:19a
//Created in $/Leap/Source/Interface
//intial checkin
?>