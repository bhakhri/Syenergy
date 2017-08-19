<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality of student detail for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (09.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SuperLogin');
define('ACCESS','view');
//define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
$showTitle = "none";
$showData  = "none";
$showPrint = "none";
/*
if($sessionHandler->getSessionVariable('RoleId')!=1){
   redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
   die;
}
*/
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

$queryString =  $_SERVER['QUERY_STRING'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Super Login</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<style type="text/css">
a.whiteText:hover{
    color:#FFFFFF;
}
a.whiteText:active{
    color:#FFFFFF;
}
</style>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
            new Array('srNo','#','width="3%"','',false), 
            new Array('studentName','Student','width="12%"','',true) , 
            new Array('className','Class','width="17%"','',true),
            new Array('rollNo','Roll No','width="8%"','',true),
            new Array('fatherName','Father','width="12%"','',true),
            new Array('motherName','Mother','width="12%"','',true),
            new Array('guardianName','Guardian','width="12%"','',true)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
//listURL = '<?php echo HTTP_LIB_PATH;?>/SuperLogin/ajaxInitList.php';
listURL = '<?php echo HTTP_LIB_PATH;?>/SuperLogin/ajaxSuperLoginList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';
var queryString='';

var dtArray=new Array();  
var dtDegree='';
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}

function checkAndRedirect(val){
    if(val==''){
        return false;
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/SuperLogin/ajaxCheckUserCredentials.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 val: (val),
                 searchString : generateQueryString('allDetailsForm')+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&page='+page
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     var ret=trim(transport.responseText);
                     if(ret==3){
                         window.location= "<?php echo UI_HTTP_PARENT_PATH; ?>/index.php";
                     }
                     else if(ret==4){
                         window.location= "<?php echo UI_HTTP_STUDENT_PATH; ?>/index.php";
                     }
                     else{
                         hideWaitDialog(true);
                         messageBox(ret);
                         return false;
                     }
             },
             onFailure: function(){ hideWaitDialog(true); messageBox("<?php echo TECHNICAL_PROBLEM; ?>"); }
           });
}

function validateForm() {
   queryString = '';
   form = document.allDetailsForm; 
   page=1;

   queryString = '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

   sendReq(listURL,divResultName,searchFormName,queryString);
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
    /* END: search filter*/
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


//populate list
window.onload=function(){
//alert("<?php echo $queryString?>");
   getSearchValue('');      
   if("<?php echo $queryString?>"!=''){
      sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
      document.getElementById("nameRow").style.display='';
      document.getElementById("nameRow2").style.display='';
      document.getElementById("resultRow").style.display='';
   }
   
}

function getSearchValue(str) {
    
      dtArray.splice(0,dtArray.length); //empty the array  
        
      ttStr = str;  
      if(ttStr=='' || ttStr =='Branch') {
         if(dtDegree=='') { 
           document.allDetailsForm.degreeId.length = null; 
         }
         document.allDetailsForm.branchId.length = null; 
         addOption(document.getElementById('degreeId'), '', 'All');
         addOption(document.getElementById('branchId'), '', 'All');
         ttStr='';
      }
      
      if(ttStr=='' || ttStr =='Batch') {
        document.allDetailsForm.batchId.length = null; 
        addOption(document.getElementById('batchId'), '', 'All');
        ttStr='';
      }
      
      if(ttStr=='' || ttStr =='Class') {
        document.allDetailsForm.classId.length = null; 
        addOption(document.getElementById('classId'), '', 'All');
      }
           
       var len= document.getElementById('hiddenClassId').options.length;
       var t=document.getElementById('hiddenClassId');
       if(len>0) {
          for(k=1;k<len;k++) { 
             if(t.options[k].value != '') { 
                 retId = (t.options[k].value).split('!!~!!~!!');
                 retName = (t.options[k].text).split('!!~!!~!!');
                 // instituteCode, className, degreeCode, batchName, branchCode
                 ttStr = str;           
                 
                 if(ttStr=='' || ttStr =='Branch') {
                     if(dtDegree=='') { 
                       if(checkDuplicate(retName[2]+"degree")!=0) {
                         addOption(document.getElementById('degreeId'), retId[2],  retName[2]);
                       }
                     }
                     temp1='';
                     temp2='';
                     if(document.getElementById('degreeId').value!='') {
                       temp1 = document.getElementById('degreeId').value+"~";
                       temp2 = retId[2]+"~";  
                     }
                     
                     if(temp1=='') {
                       if(checkDuplicate(retName[4]+"branch")!=0) {
                         addOption(document.getElementById('branchId'), retId[4],  retName[4]);
                       }
                     }
                     else {
                       if(temp1==temp2) {
                         if(checkDuplicate(retName[4]+"branch")!=0) { 
                           addOption(document.getElementById('branchId'), retId[4],  retName[4]);
                         }  
                       }
                     }
                     ttStr = '';
                 }
                 
                 if(ttStr=='' || ttStr =='Batch') {
                    temp1='';
                    temp2='';
                    if(document.getElementById('degreeId').value!='') {
                      temp1 = document.getElementById('degreeId').value+"~";
                      temp2 = retId[2]+"~";  
                    }
                    if(document.getElementById('branchId').value!='') {
                      temp1 += document.getElementById('branchId').value+"~";
                      temp2 += retId[4]+"~";
                    }
                     
                    if(temp1=='') {
                       if(checkDuplicate(retName[3]+"batch")!=0) { 
                         addOption(document.getElementById('batchId'), retId[3],  retName[3]);
                       }
                    }
                    else {
                       if(temp1==temp2) {
                          if(checkDuplicate(retName[3]+"batch")!=0) {   
                            addOption(document.getElementById('batchId'), retId[3],  retName[3]);
                          }
                       }  
                    }
                    ttStr = '';
                 }
                 
                 if(ttStr=='' || ttStr =='Class') {
                      temp1='';
                      temp2='';
                      if(document.getElementById('degreeId').value!='') {
                        temp1 = document.getElementById('degreeId').value+"~";
                        temp2 = retId[2]+"~";  
                      }
                      if(document.getElementById('branchId').value!='') {
                        temp1 += document.getElementById('branchId').value+"~";
                        temp2 += retId[4]+"~";
                      }
                      if(document.getElementById('batchId').value!='') {
                         temp1 += document.getElementById('batchId').value+"~";
                         temp2 += retId[3]+"~";
                      }
                      
                      if(temp1=='') {
                         if(checkDuplicate(retName[1]+"class")!=0) { 
                           addOption(document.getElementById('classId'), retId[1],  retName[1]);
                         }
                      }
                      else {
                         if(temp1==temp2) {
                           if(checkDuplicate(retName[1]+"class")!=0) {  
                             addOption(document.getElementById('classId'), retId[1],  retName[1]);
                           }
                         }  
                      }  
                      ttStr = '';
                 }
              } // end If
           } // end for loop
       } // end if condition
      
       dtDegree='1';
}

function checkDuplicate(value) {
    
    var ii= dtArray.length;
    var fl=1;
    for(var kk=0;kk<ii;kk++){
      if(dtArray[kk]==value){
        fl=0;
        break;
      }  
    }
    if(fl==1){
      dtArray.push(value);
    } 
    
    return fl;
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    //require_once(TEMPLATES_PATH . "/SuperLogin/listStudentContents.php");
    require_once(TEMPLATES_PATH . "/SuperLogin/listSuperLoginContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: superLoginList.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/04/10    Time: 19:10
//Created in $/Leap/Source/Interface
//Created module "Super Login"
?> 