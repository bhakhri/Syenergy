<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentListsReport Form
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentList');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student List </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//echo UtilityManager::includeCSS("css.css");
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>
<!-- <link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" /> -->

<style>
BR.page { page-break-after: always }

</style> 
<script language="javascript">
 
 //This function Validates Form 

var  queryString;
function validateAddForm(frm) {

    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"),new Array("batchId","<?php echo SELECT_BATCH;?>"),new Array("studyPeriodId","<?php echo SELECT_STUDYPERIOD;?>"));
    var len = fieldsArray.length;
   /* for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
            
    }    */
    
    //code checks whether atleast one checkbox is selected or not
    queryString = '';
    form = document.allDetailsForm;

    if(!isEmpty(document.getElementById('birthYearF').value) || !isEmpty(document.getElementById('birthMonthF').value) || !isEmpty(document.getElementById('birthDateF').value)){
        
        if(isEmpty(document.getElementById('birthYearF').value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthMonthF').value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");
           document.allDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthDateF').value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");
           document.allDetailsForm.birthDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearT').value) || !isEmpty(document.getElementById('birthMonthT').value) || !isEmpty(document.getElementById('birthDateT').value)){
        
        if(isEmpty(document.getElementById('birthYearT').value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.allDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthMonthT').value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");  
           document.allDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthDateT').value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");  
           document.allDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearF').value) && !isEmpty(document.getElementById('birthMonthF').value) && !isEmpty(document.getElementById('birthDateF').value) && !isEmpty(document.getElementById('birthYearT').value) && !isEmpty(document.getElementById('birthMonthT').value) && !isEmpty(document.getElementById('birthDateT').value)){
    
        dobFValue = document.getElementById('birthYearF').value+"-"+document.getElementById('birthMonthF').value+"-"+document.getElementById('birthDateF').value

        dobTValue = document.getElementById('birthYearT').value+"-"+document.getElementById('birthMonthT').value+"-"+document.getElementById('birthDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("<?php echo RESTRICTION_BIRTH_DAY; ?>");  
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

    /* admission date*/
    if(!isEmpty(document.getElementById('admissionYearF').value) || !isEmpty(document.getElementById('admissionMonthF').value) || !isEmpty(document.getElementById('admissionDateF').value)){
        
        if(isEmpty(document.getElementById('admissionYearF').value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");  
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionMonthF').value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");  
           document.allDetailsForm.admissionMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionDateF').value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");  
           document.allDetailsForm.admissionDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('admissionYearT').value) || !isEmpty(document.getElementById('admissionMonthT').value) || !isEmpty(document.getElementById('admissionDateT').value)){
        
        if(isEmpty(document.getElementById('admissionYearT').value)){
           
          messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");
           document.allDetailsForm.admissionYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionMonthT').value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");   
           document.allDetailsForm.admissionMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionDateT').value)){
           
          messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");    
           document.allDetailsForm.admissionDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('admissionYearF').value) && !isEmpty(document.getElementById('admissionMonthF').value) && !isEmpty(document.getElementById('admissionDateF').value) && !isEmpty(document.getElementById('admissionYearT').value) && !isEmpty(document.getElementById('admissionMonthT').value) && !isEmpty(document.getElementById('admissionDateT').value)){
    
        doaFValue = document.getElementById('admissionYearF').value+"-"+document.getElementById('admissionMonthF').value+"-"+document.getElementById('admissionDateF').value

        doaTValue = document.getElementById('admissionYearT').value+"-"+document.getElementById('admissionMonthT').value+"-"+document.getElementById('admissionDateT').value

        if(dateCompare(doaFValue,doaTValue)==1){
           
           messageBox("<?php echo RESTRICTION_ADMISSION_DAY; ?>");      
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
    }
    
    //roll no. + first name + last name
    queryString += '&rollNo='+form.rollNo.value;
    queryString += '&studentName='+form.studentName.value;

    //roll no. + first name + last name
    queryString += 'rollNo='+form.rollNo.value;
    queryString += '&studentName='+form.studentName.value;
    queryString += '&gender='+form.gender.value;
    queryString += '&quotaId='+form.quotaId.value;
    quotaText ='';
    var quotaText = document.getElementById('quotaId');
    quotaTextValue = quotaText.options[quotaText.selectedIndex].text;
    queryString += '&quotaText='+quotaTextValue;

    // Blood Group
    queryString += '&bloodGroup='+form.bloodGroup.value;
    bloodGroupText ='';
    var bloodGroupText = document.getElementById('bloodGroup');
    bloodGroupTextValue = bloodGroupText.options[bloodGroupText.selectedIndex].text;
    queryString += '&bloodGroupText='+bloodGroupTextValue;

    
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
    queryString += '&degreeId='+selectedDegrees;
    if(countDegree==totalDegreeId || countDegree==0)
        selectedDegreeText ="ALL";
    queryString += '&degs='+selectedDegrees+'&degsText='+selectedDegreeText;
    //end degree
    

    //Start branch 
    totalBranchId = form.elements['branchId[]'].length;
    var name = document.getElementById('branchId'); 
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
            selectedBranchesText += eval("name.options["+i+"].text");
        }
    }
    queryString += '&branchId='+selectedBranches;

    if(countBranches==totalBranchId || countBranches==0)
        selectedBranchesText ="ALL";

    queryString += '&brans='+selectedBranches+'&bransText='+selectedBranchesText;
    //End branch 
    
    //start periodicity
    totalPeriodicityId = form.elements['periodicityId[]'].length;
    var name = document.getElementById('periodicityId');

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
            selectedPeriodicityText += eval("name.options["+i+"].text");
        }
    }
    queryString += '&periodicityId='+selectedPeriodicity;
    if(countPeriod==totalPeriodicityId || countPeriod==0)
        selectedPeriodicityText ="ALL";
    queryString += '&periods='+selectedPeriodicity+'&periodsText='+selectedPeriodicityText;
    //end periodicity
    
    //start Course
    totalCourseId = form.elements['courseId[]'].length;
    var name = document.getElementById('courseId'); 
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
            selectedCourseText += eval("name.options["+i+"].text");
        }
    }
   
    queryString += '&courseId='+selectedCourse;   

    if(countPeriod==totalCourseId || countPeriod==0)
        selectedCourseText ="ALL";

     queryString += '&course='+selectedCourse+'&courseText='+selectedCourseText;
    //end Course

    
    //start group.
    totalGroupId = form.elements['groupId[]'].length;
    var name = document.getElementById('groupId');  
    selectedGroup='';
    selectedGroupText='';
    countGroup=0;
    for(i=0;i<totalGroupId;i++) {
        if (form.elements['groupId[]'][i].selected == true) {
            if (selectedGroup != '') {                                             
                selectedGroup += ',';
                selectedGroupText += ', ';
            }
            countGroup++;
            selectedGroup += form.elements['groupId[]'][i].value;
            selectedGroupText += eval("name.options["+i+"].text");
        }
    }
    
    queryString += '&groupId='+selectedGroup;   
    
    if(countGroup==totalGroupId || countGroup==0)
        selectedGroupText ="ALL";
    queryString += '&group='+selectedGroup+'&groupText='+selectedGroupText;
    
    //end group.

    //start univ.
    totalUniversityId = form.elements['universityId[]'].length;
    var name = document.getElementById('universityId');
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
            selectedUniversityText += eval("name.options["+i+"].text");
        }
    }
    queryString += '&universityId='+selectedUniversity;   
    
    if(countUniversity==totalUniversityId || countUniversity==0)
        selectedUniversityText ="ALL";

    queryString += '&univs='+selectedUniversity+'&univsText='+selectedUniversityText;
    //end univ.
    
    
    //start city
    totalCityId = form.elements['cityId[]'].length;
    var name = document.getElementById('cityId');
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
            selectedCityText += eval("name.options["+i+"].text");
        }
    }
    queryString += '&cityId='+selectedCity;

    if(countCity==totalCityId || countCity==0)
        selectedCityText ="ALL";

    queryString += '&citys='+selectedCity+'&citysText='+selectedCityText;
    
    // end city
    
    
    //start state
    totalStateId = form.elements['stateId[]'].length;
    var name = document.getElementById('stateId');

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
            selectedStateText += eval("name.options["+i+"].text");
        }
    }
    queryString += '&stateId='+selectedState;

    if(countState==totalStateId || countState==0)
        selectedStateText ="ALL";

    queryString += '&states='+selectedState+'&statesText='+selectedStateText;
    //end state


    //start country
    totalCountryId = form.elements['countryId[]'].length;
    var name = document.getElementById('countryId');

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
            selectedCountryText += eval("name.options["+i+"].text");
        }
    }
    queryString += '&countryId='+selectedCountry;

    if(countCountry==totalCountryId || countCountry==0)
        selectedCountryText ="ALL";

    queryString += '&cnts='+selectedCountry+'&cntsText='+selectedCountryText;
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
    var name = document.getElementById('hostelId');

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
            selectedHostelText += eval("name.options["+i+"].text");
        }
    }
    queryString += '&hostelId='+selectedHostel;
    if(countHostel==totalHostelId || countHostel==0)
        selectedHostelText ="ALL";

    queryString += '&hostels='+selectedHostel+'&hostelsText='+selectedHostelText;
    //end hotsel


    //Start bus stop
    totalBusStopId = form.elements['busStopId[]'].length;
    var name = document.getElementById('busStopId');

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
            selectedBusStopText += eval("name.options["+i+"].text");
        }
    }
    
   queryString += '&busStopId='+selectedBusStop;

    if(countBusstop==totalBusStopId || countBusstop==0)
        selectedBusStopText ="ALL";

    queryString += '&buss='+selectedBusStop+'&bussText='+selectedBusStopText;
    //End bus stop

    //start bus route
    totalBusRouteId = form.elements['busRouteId[]'].length;
    var name = document.getElementById('busRouteId');

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
            selectedBusRouteText += eval("name.options["+i+"].text");
        }
    }
    
    queryString += '&busRouteId='+selectedBusRoute;
    if(countBusroute==totalBusRouteId || countBusroute==0)
        selectedBusRouteText ="ALL";

    queryString += '&routs='+selectedBusRoute+'&routsText='+selectedBusRouteText;

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

    //end bus route
    
    
    /*

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
    
    queryString += '&quotaId='+form.quotaId.value;       
*/

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
    //queryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField

    showHide("hideAll");
    //document.getElementById("nameRow").style.display='';
    //document.getElementById("nameRow2").style.display='';
    //document.getElementById("resultRow").style.display='';
    dontMakeQueryString = true;
    
    count = 0;
    var objCheckBoxes = document.studentListReport.elements['check[]'];
    len = objCheckBoxes.length;
    for (var i = 0; i < len; i++) {
        var e = objCheckBoxes[i];
        if (e.checked) {
            count=count+1;
        }
    }

    if (count == 0){
        messageBox ("<?php echo SELECT_ATLEASTONE_CHECKBOX;?>")
        return false;
    }
    
    
    if(true == isEmpty(document.getElementById('startingRecord').value)){
        messageBox("<?php echo START_RECORD ?>");
        document.allDetailsForm.startingRecord.focus();
        return false;
    }
    
    if(false == isNumeric(document.getElementById('startingRecord').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.allDetailsForm.startingRecord.focus(); 
       return false;  
    }
    
    if(document.getElementById('startingRecord').value <= 0 ) {
       messageBox("<?php echo "Starting value for 'Starting Record No.' field should be 1 "; ?>");
       document.allDetailsForm.startingRecord.focus(); 
       return false;  
    }

     if(true == isEmpty(document.getElementById('totalRecords').value)){
        messageBox("<?php echo "ENTER No. Of Records In Report" ?>");
        document.allDetailsForm.totalRecords.focus();
        return false;
    }

    if(false == isNumeric(document.getElementById('totalRecords').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.allDetailsForm.totalRecords.focus(); 
       return false;  
    }
    
    if(document.getElementById('totalRecords').value <= 0 ) { 
       messageBox("<?php echo "No. of Records Field Should Be 1 or Greater "; ?>");
       document.allDetailsForm.totalRecords.focus(); 
       return false;  
    }
    
    
    
    if(frm=='studentListSubmit'){
     //For print
        var queryString3=queryString;
        var queryString1= generateQueryString1('studentListReport') ;  //New function generateQueryString1 used instead of old function as to make print functionality working according to id
        var queryString2=queryString3+'&'+queryString1;
        openStudentLists(queryString2);  
    }                     
    else { // for CSV   
        var queryString3=queryString;
        var queryString1= generateQueryString1('studentListReport') ; //New function generateQueryString1 used instead of old function as to make print functionality working according to id
        queryString2=queryString3+'&'+queryString1; 
        printCSV(queryString2);  
    }
   return false; 
}

//This function adds form through ajax 


function openStudentLists(queryString2) {
       //alert(queryString2);
       start = document.getElementById('startingRecord').value;
       end = document.getElementById('totalRecords').value;
       
       if (document.allDetailsForm.studentStatusId[0].checked)  { 
          id=1; 
       }
      else if (document.allDetailsForm.studentStatusId[2].checked)  { 
          id=4; 
       }
       else {
          id=0; 
       }
       
       if (document.studentListReport.incAll.checked)  { 
          incAll=1; 
       }
       else {
          incAll=0; 
       }
        if (document.studentListReport.incAllInsitute.checked)  { 
          incAllInsitute=1; 
       }
       else {
          incAllInsitute=0; 
       }
       
       path='<?php echo UI_HTTP_PATH;?>/listStudentListPrint.php?'+queryString2+'&start='+start+'&end='+end+'&studentStatusId='+id+'&incAll='+incAll+'&incAllInsitute='+incAllInsitute;      
	 window.open(path,"StudentListReport","status=1,menubar=1,scrollbars=1, width=900");    
}

function printCSV(queryString2) {     //alert(queryString2);  
   // document.getElementById('generateCSV2').href='scStudentListsReportPrintCSV.php?queryString='+queryString2;
   // document.getElementById('generateCSV2').href='scStudentListsReportPrintCSV.php?queryString='+queryString2;
   // alert(document.getElementById('generateCSV2').href);
      start = document.getElementById('startingRecord').value;
      end = document.getElementById('totalRecords').value;
      if (document.allDetailsForm.studentStatusId[0].checked)  { 
          id=1; 
      }
     else if (document.allDetailsForm.studentStatusId[2].checked)  { 
          id=4; 
      }
      else {
          id=0; 
      }      
       if (document.studentListReport.incAll.checked)  { 
          incAll=1; 
       }
       else {
          incAll=0; 
       }
        if (document.studentListReport.incAllInsitute.checked)  { 
          incAllInsitute=1; 
       }
       else {
          incAllInsitute=0; 
       }
       
      path='<?php echo UI_HTTP_PATH;?>/listStudentListPrintCSV.php?'+queryString2+'&start='+start+'&end='+end+'&studentStatusId='+id+'&incAll='+incAll+'&incAllInsitute='+incAllInsitute;  
      window.location= path;  
}  

window.onload=function(){
var roll = document.getElementById("rollNo");
 autoSuggest(roll);
} 

//autopopulate for batch and study period
/*
function autoPopulate(val,type,frm)
{
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   if(frm=="Add"){
       if(type=="scStudentLists"){
            document.studentListReport.batchId.options.length=0;
            var objOption = new Option("SELECT","");
            document.studentListReport.batchId.options.add(objOption); 
       }
    }
   
new Ajax.Request(url,
{
     method:'post',
     parameters: {type: type,id: val},
     onCreate: function() {
                 showWaitDialog(true);
             },
     onSuccess: function(transport){
                     hideWaitDialog(true);
            j = trim(transport.responseText).evalJSON();   
            len = j.batchArr.length;
            //alert(len);
            //alert(j.subjectArr);
            document.studentListReport.batchId.length = null;
            // add option Select initially
            addOption(document.studentListReport.batchId, '', 'Select');
            for(i=0;i<len;i++) { 
             addOption(document.studentListReport.batchId, j.batchArr[i].batchId, j.batchArr[i].batchName);
       }
            len = j.studyArr.length;
            document.studentListReport.studyPeriodId.length = null;
            addOption(document.studentListReport.studyPeriodId, '', 'Select');
            for(i=0;i<len;i++) { 
             addOption(document.studentListReport.studyPeriodId, j.studyArr[i].studyPeriodId, j.studyArr[i].periodName);
            }
          
     },
     onFailure: function(){ alert('Something went wrong...') }
   }); 
}
          */
//showHide("hideAll");  
                           
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentListsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: listStudentLists.php $
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/02/09   Time: 3:17p
//Updated in $/LeapCC/Interface
//radio button check add (studentStatus)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/02/09   Time: 1:20p
//Updated in $/LeapCC/Interface
//studentStatus check added
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/11/09    Time: 12:13p
//Updated in $/LeapCC/Interface
//new enhacments added (bloodGroup, regNo, className added)
//
//*****************  Version 5  *****************
//User: Parveen      Date: 5/28/09    Time: 3:10p
//Updated in $/LeapCC/Interface
//validation added to control the "Memory Limit exceeds" of student
//records 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/21/09    Time: 5:32p
//Updated in $/LeapCC/Interface
//bug fix
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/21/09    Time: 2:43p
//Updated in $/LeapCC/Interface
//update format
//

?>
