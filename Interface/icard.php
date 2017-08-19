<?php
//-----------------------------------------------------------------------------
//  To generate Studnet ICard functionality      
//
//
// Author :Parveen Sharma
// Created on : 26-Dec-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentIcardReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student I-Card Report </title>
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

var tableHeadArray = new Array(
                     new Array('srNo','#','width="3%"','',false), 
                     new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                     //new Array('checkAll','<input type="checkbox" id="checkbox2" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
                     new Array('rollNo','Roll No.','width="10%"','',true), 
                     new Array('studentName','Name','width="10%"','',true), 
                     new Array('fatherName','Father`s Name','width="15%"','',true),  
                     new Array('DOB','Date of Birth','width="15%"','align="center"',true),
                     new Array('programme','Branch','width="15%"','',true),
                     new Array('studentMobileNo','Mobile No.','width=15%','',true),
                     new Array('permAddress','Perm. Address','width="25%"','',false)
                  );

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Icard/initStudentICardReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 48;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'Asc';
 //This function Validates Form 
 
var myQueryString;
var studentCheck;

function validateAddForm() {
    page=1; 
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
    
    queryString='';
    var form = document.allDetailsForm;    
    
    if(form.cardView.value=='') {
       messageBox("<?php echo SELECT_ICARD; ?>");
       eval("form.cardView.focus();");
       return false;
    }
    
       /* START: search filter */

    queryString = '';
    
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
    sendReq(listURL,divResultName,searchFormName, queryString,false);
    
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    return false;
    /* END: search filter*/


    /*  queryString = '';

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

    queryString = 'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&fromDateA='+fromDateA+'&toDateA='+toDateA+'&fromDateD='+fromDateD+'&toDateD='+toDateD;
    myQueryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    showHide("hideAll");
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
//  dontMakeQueryString = true;
    
    sendReq(listURL,divResultName,searchFormName, queryString);
//  openStudentLists(frm.name,'class','Asc');    
   */
   
}

function doAll(){

   formx = document.allDetailsForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){

            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
            
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            
            if(formx.elements[i].type=="checkbox"){
            
                formx.elements[i].checked=false;
            }
        }
    }
}

function getAdmitCard(id) {
    var formx = document.allDetailsForm;
    document.getElementById('adminCard').style.display='none';    
    document.getElementById('busPass').style.display='none';    
    document.getElementById('iCard').style.display='none';    
    
    if(id==1) {
       document.getElementById('busPass').style.display='';
       formx.busReceipt.value='';
       formx.busValidity.value='';
       eval("formx.busReceipt.focus();");
       return false;    
    }
    else
    if(id==2) {
       document.getElementById('iCard').style.display='';
       formx.icardTitle.value='';
       eval("formx.icardTitle.focus();");
       return false;    
    }
    else {
        if(id==3) {
           document.getElementById('adminCard').style.display='';
           formx.heading1.value='';
           formx.heading2.value='';
           eval("formx.heading1.focus();");
           return false;    
        }
    }
}


function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function printReport() {                    
    
    var selected=0;
    studentCheck='';
    
    var formx = document.allDetailsForm;
    if(formx.cardView.value=='') {
       messageBox("<?php echo SELECT_ICARD; ?>");
       eval("formx.cardView.focus();");
       return false;
    }
    
    if(document.allDetailsForm.cardView.value==1) {             // Bus Pass
        if(trim(formx.busReceipt.value)=='') {
           messageBox("<?php echo ENTER_RECEIPT; ?>");
           eval("formx.busReceipt.focus();");
           return false;
        }
        
        if(!isAlphaCharCustom(trim(formx.busReceipt.value),"0123456789-")) {
           messageBox("<?php echo ENTER_RECEIPT_CHAR; ?>");
           eval("formx.busReceipt.focus();");
           return false;
        }
        
        if(trim(formx.busValidity.value)=='') {
           messageBox("<?php echo ENTER_VALIDITY; ?>");
           eval("formx.busValidity.focus();");
           return false;
        }
        
        if(!isAlphaCharCustom(trim(formx.busValidity.value),"0123456789- &.,")) {
           messageBox("<?php echo ENTER_VALIDITY_CHAR; ?>");
           eval("formx.busValidity.focus();");
           return false;
        }
    }
    
    
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                }
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        }
    }
    if(selected==0)    {
       alert("Please select atleast one record!");
       return false;
    }
    
    form = document.allDetailsForm;
    str='';
    
    if(document.allDetailsForm.cardView.value==3) {             // Admit Card
       str = '&heading1='+escape(trim(form.heading1.value))+'&heading2='+escape(trim(form.heading2.value));    
    }
    
    if(document.allDetailsForm.cardView.value==2) {             // Icard Title
       showId=1;
       if(form.chkOption[0].checked){ 
         showId=1;  // Roll No.
       }
       else if(form.chkOption[1].checked){ 
         showId=2;  // Univeristy Roll No.
       }
       else {
         showId=3;  // Reg No.
       }
       str = '&showId='+showId+'&icardTitle='+escape(trim(form.icardTitle.value));    
    }
    
 /* if(document.allDetailsForm.cardView.value==1) {             // Bus Pass
       str = '&busReceipt='+trim(form.busReceipt.value)+'&busValidity='+trim(form.busValidity.value);    
    } */                                                                                                                           
    path='<?php echo UI_HTTP_PATH;?>/icardPrint.php?student='+studentCheck+'&cardView='+form.cardView.value+'+&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str;
    window.open(path,"ICardReport","status=1,menubar=1,scrollbars=1, width=900, height=500");
}

window.onload=function(){
 var roll = document.getElementById("rollNo");
 //roll.focus();
 autoSuggest(roll);
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Icard/listICardContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 
//$History: icard.php $
//
//*****************  Version 13  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 5:27p
//Updated in $/LeapCC/Interface
//resolved issues(0002281,0002305,0002282,0002279,0002280,0002277,0002307
//)
//
//*****************  Version 12  *****************
//User: Parveen      Date: 12/16/09   Time: 12:27p
//Updated in $/LeapCC/Interface
//validation function updated (sendReq false vlaue set)
//
//*****************  Version 11  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 10  *****************
//User: Parveen      Date: 10/06/09   Time: 3:59p
//Updated in $/LeapCC/Interface
//icard display record limit increased and updated look & feel
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/05/09   Time: 2:00p
//Updated in $/LeapCC/Interface
//validation message updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/01/09   Time: 4:59p
//Updated in $/LeapCC/Interface
//icard title input box added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Interface
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 5:43p
//Updated in $/LeapCC/Interface
//resolved issue 1635
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/LeapCC/Interface
//issue fix format & conditions & alignment updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/11/09    Time: 5:24p
//Updated in $/LeapCC/Interface
//validations functions Formatting updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/23/09    Time: 2:48p
//Updated in $/LeapCC/Interface
//formatting update (admitCard, Buspass, Icard)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/12/09    Time: 4:40p
//Updated in $/LeapCC/Interface
//inital checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/12/09    Time: 3:45p
//Created in $/LeapCC/Interface
//icard file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/27/08   Time: 4:26p
//Updated in $/Leap/Source/Interface
//checkbox added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/26/08   Time: 3:43p
//Created in $/Leap/Source/Interface
//initial checkin
//

?>