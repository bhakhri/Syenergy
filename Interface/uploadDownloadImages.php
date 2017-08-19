<?php 
//-------------------------------------------------------
//  This File contains Download Images
//
// Author :Parveen Sharma
// Created on : 03-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>

<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DownloadImagesReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload & Download Images </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("ajax.js");  
echo UtilityManager::includeJS("tab-view.js");  
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var listURL='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetStudentList.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

//recordsPerPage = <?php //echo RECORDS_PER_PAGE;?>;
recordsPerPage = 20;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
page=1; //default page
sortField = 'firstName';
sortOrderBy    = 'ASC';
queryString = ""; 
studentIds = "";
studentName = "";
globalFL=1;



empListURL='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetEmployeeList.php';
divResultName1  = "employeeResultsDiv";
searchFormName1 = "employeeDetailsForm";
empQueryString = ""; 
employeeIds = "";
employeeName = "";
empGlobalFL=1;
empPage=1;
tempEmpPage=empPage;
sortField1 = 'employeeName';
sortOrderBy1    = 'ASC';



// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


// Student Filter

//This function Validates Form 
function validateAddForm(frm) {
    /* START: search filter */
    queryString = '';
    form = document.allDetailsForm;
    if(!isEmpty(form.birthYearF.value) || !isEmpty(form.birthMonthF.value) || !isEmpty(form.birthDateF.value)){
        if(isEmpty(form.birthYearF.value)){
           messageBox("Please select date of birth year");
           form.birthYearF.focus();
           return false;
        }
        if(isEmpty(form.birthMonthF.value)){
           
           messageBox("Please select date of birth month");
           form.birthMonthF.focus();
           return false;
        }
        if(isEmpty(form.birthDateF.value)){
           
           messageBox("Please select date of birth date");
           form.birthDateF.focus();
           return false;
        }
    }

    if(!isEmpty(form.birthYearT.value) || !isEmpty(form.birthMonthT.value) || !isEmpty(form.birthDateT.value)){
        
        if(isEmpty(form.birthYearT.value)){
           
           messageBox("Please select date of birth year");
           form.birthYearT.focus();
           return false;
        }
        if(isEmpty(form.birthMonthT.value)){
           
           messageBox("Please select date of birth month");
           form.birthMonthT.focus();
           return false;
        }
        if(isEmpty(form.birthDateT.value)){
           
           messageBox("Please select date of birth date");
           form.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(form.birthYearF.value) && !isEmpty(form.birthMonthF.value) && !isEmpty(form.birthDateF.value) && !isEmpty(form.birthYearT.value) && !isEmpty(form.birthMonthT.value) && !isEmpty(form.birthDateT.value)){
    
        dobFValue = form.birthYearF.value+"-"+form.birthMonthF.value+"-"+form.birthDateF.value

        dobTValue = form.birthYearT.value+"-"+form.birthMonthT.value+"-"+form.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

    /* admission date*/
    if(!isEmpty(form.admissionYearF.value) || !isEmpty(form.admissionMonthF.value) || !isEmpty(form.admissionDateF.value)){
        
        if(isEmpty(form.admissionYearF.value)){
           
           messageBox("Please select date of admission year");
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
        if(isEmpty(form.admissionMonthF.value)){
           
           messageBox("Please select date of admission month");
           document.allDetailsForm.admissionMonthF.focus();
           return false;
        }
        if(isEmpty(form.admissionDateF.value)){
           
           messageBox("Please select date of admission date");
           document.allDetailsForm.admissionDateF.focus();
           return false;
        }
    }

    if(!isEmpty(form.admissionYearT.value) || !isEmpty(form.admissionMonthT.value) || !isEmpty(form.admissionDateT.value)){
        
        if(isEmpty(form.admissionYearT.value)){
           
           messageBox("Please select date of admission year");
           document.allDetailsForm.admissionYearT.focus();
           return false;
        }
        if(isEmpty(form.admissionMonthT.value)){
           
           messageBox("Please select date of admission month");
           document.allDetailsForm.admissionMonthT.focus();
           return false;
        }
        if(isEmpty(form.admissionDateT.value)){
           
           messageBox("Please select date of admission date");
           document.allDetailsForm.admissionDateT.focus();
           return false;
        }
    }

    if(!isEmpty(form.admissionYearF.value) && !isEmpty(form.admissionMonthF.value) && !isEmpty(form.admissionDateF.value) && !isEmpty(form.admissionYearT.value) && !isEmpty(form.admissionMonthT.value) && !isEmpty(form.admissionDateT.value)){
    
        doaFValue = form.admissionYearF.value+"-"+form.admissionMonthF.value+"-"+form.admissionDateF.value

        doaTValue = form.admissionYearT.value+"-"+form.admissionMonthT.value+"-"+form.admissionDateT.value

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
    var stateText = form.stateId;

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
    getStudentList();
    
    /* END: search filter*/
    
    return false;
}


function getStudentList(){
        tableHeadArray = new Array(new Array('srNo','#','width="2%"',false), 
                                       new Array('firstName','Name','width="15%" align="left"',true), 
                                       new Array('regNo','Reg. No.','width="8%" align="left"',true), 
                                       new Array('rollNo','Roll No.','width="8%" align="left"',true),
                                       new Array('universityRollNo','Univ. No.','width="8%" align="left"',true),
                                       new Array('imgSrc','Photo','width="10%" align="center"',false), 
                                       new Array('checkAll','Download&nbsp;<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="10%" align="center"',false),
                                        new Array('upload','Upload <span style="font-size:12px; ">(Max. Size: <?php echo (MAXIMUM_FILE_SIZE/1024); ?> KB)</span>', 'width="25%" align="left" align="left"',false)                               
                                       );
    //showWaitDialog(true);   
	// sendReq(listURL,divResultName,searchFormName,false);

    listObjStudent = new initPage(listURL,recordsPerPage,linksPerPage,page,searchFormName,'firstName','ASC',divResultName,'','',true,'listObjStudent',tableHeadArray,'','','');
    sendRequest(listURL, listObjStudent, 'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true);

	 
	 
	 //hideWaitDialog(true);


    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
}


function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


/*This function Validates Form*/ 



//This function Validates Form 

function empPrintReport() {
    
    formx = document.employeeDetailsForm;
    formx1 = document.employeeDetailsForm;
    var employeeCheck = "";
    var employeeName = "";
    var selected = 0;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb2[]"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox4")){
                if(employeeCheck=='') {
                   employeeCheck=formx.elements[i].value; 
                   if(formx1.elements[i].name!="employeeNames") {
                     employeeName=formx1.elements[i].value;  
                   }
                }
                else {
                    employeeCheck = employeeCheck + ',' +formx.elements[i].value; 
                    if(formx1.elements[i].name!="employeeNames") {
                       employeeName=employeeName + ','+formx1.elements[i].value;  
                   }
                }
                selected++;
            }
        }
    }
    
    if(selected==0)    {
       alert("Please select atleast one record!");
       return false;
    }
    
    var pars = "reportDownload=2&employeeId="+escape(employeeCheck)+"&employeeName="+escape(employeeName);
    var listURL='<?php echo HTTP_PATH;?>/Templates/Xml/initDownloadImages.php';

    new Ajax.Request(listURL,
    {
        method:'post',
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            returnStatus = trim(transport.responseText);
            returnStatusArray = returnStatus.split('#');
            if (returnStatusArray[0] == "<?php echo SUCCESS;?>") {
                window.location = returnStatusArray[1];
            }
            else {
                messageBox(trim(transport.responseText));
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE employee Photo
//Author : Parveen Sharma
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteEmployeeImage(id) {
     
     empPage = document.getElementById('employeesPage').value;
     sortOrderBy1 = document.getElementById('employeesSortOrderBy').value;
     sortField1 = document.getElementById('employeesSortField').value;
     
     if(false===confirm("Do you want to delete this image?")) {
         return false;
     }
     else { 
         var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxDeleteEmployeeImage.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {employeeId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                        sendRequest(empListURL, listObj1, 'page='+listObj1.page+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField,false); 
                        return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
     }    
}

function empUploadImages() {
    
     if(empGlobalFL==0){
       messageBox("Another request is in progress.");
       return false;
     }   
     
     selected = 0; 
     var formx = document.employeeDetailsForm;
     for(var i=1;i<formx.length;i++) {
       if(formx.elements[i].name=="eEmployeeId[]"){
         selected++;
         break;
       }
     }
    
     if(selected==0) {
       messageBox("<?php echo "No Data Found"; ?>");
       return false;
     }
      
     var len = (formx.empFileId.length);
     selected = 0;
     empPage = document.getElementById('employeesPage').value;
     sortOrderBy1 = document.getElementById('employeesSortOrderBy').value;
     sortField1 = document.getElementById('employeesSortField').value;
     
     fileName = "";
     employeeIds = "";
     fileName = "";
     employeeName = "";
     for(var i=0;i<len;i++){      
         if(trim(formx.empFileId[i].value)!=""){
            if(!checkFileExtensionsUpload(trim(formx.empFileId[i].value))){
                messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
                formx.empFileId[i].focus();  
                return false;
             }
             if(employeeIds == "") { 
                employeeIds = formx.eEmployeeId[i].value;
                employeeName = escape(formx.empEmployeeNames[i].value);
             }
             else {
                employeeIds += ","+formx.eEmployeeId[i].value;
                employeeName += ","+ escape(formx.empEmployeeNames[i].value); 
             }
             selected = 1;
         }
     } 

     //alert(employeeIds+', '+employeeName);
     if(selected==1) {
        empInitAdd(2);  
     }
     else {
        messageBox("<?php echo "Please browse atleast one file."; ?>");  
     }
}


function empInitAdd(mode) {
    empGlobalFL=1;   
    if(mode==2){
      //showWaitDialog(true); 
      queryString = "reportFormat=2&employeeIds="+employeeIds+"&employeeNames1="+employeeName;
      //alert(queryString);
      document.getElementById('employeeDetailsForm').target = 'empUploadTargetEdit';
      document.getElementById('employeeDetailsForm').action= "<?php echo HTTP_LIB_PATH;?>/AdminTasks/fileUpload.php?"+queryString;
      document.getElementById('employeeDetailsForm').submit(); 
   } 
}

function empFileUploadError(str){
   hideWaitDialog(true);

   document.getElementById('employeesPage').value = listObj1.page;
   document.getElementById('employeesSortOrderBy').value =  listObj1.sortOrderBy;
   document.getElementById('employeesSortField').value  = listObj1.sortField;
 
   
   if("<?php echo NOT_WRITEABLE_FOLDER;?>" == trim(str)) {
      messageBox("<?php echo NOT_WRITEABLE_FOLDER; ?>");       
      //listObj2 = new initPage(empListURL,recordsPerPage,linksPerPage,'','','','',divResultName1,'','',true,'listObj2',tableHeadArray1,'','',empQueryString);
      //sendRequest(empListURL, listObj2,' ',true);      
      sendRequest(empListURL, listObj1, 'page='+listObj1.page+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField,false);
      return false;
   }
   else
   if("<?php echo SUCCESS;?>" == trim(str)) {
      sendRequest(empListURL, listObj1, 'page='+listObj1.page+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField,false); 
      return false;
   }
   else {
      //Invalid file extension or maximum upload size exceeds
      messageBox("Some files were not uploaded as they exceeded maximum upload size limit of "+<?php echo ceil(MAXIMUM_FILE_SIZE/1024); ?>+"kb");
      //showDetails(trim(str),'divInformation',300,250)
      sendRequest(empListURL, listObj1, 'page='+listObj1.page+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField,false); 
      return false;
   }
   sendRequest(empListURL, listObj1, 'page='+listObj1.page+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField,false); 
   
   return false;
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "error in upload Show Details" DIV
function empShowDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv('divInformation','', w, h, 300, 250)
    document.getElementById('uploadInfo').innerHTML= id;    
}

function doAll2(){

    formx = document.employeeDetailsForm;
    if(formx.checkbox4.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb2[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb2[]" ){
                formx.elements[i].checked=false;
            }
        }
    }
}

function validateEmployeeList(frm) {

    
    /* START: search filter */
    empQueryString = '';
    form = document.employeeDetailsForm;

    
    //roll no. + first name + last name
    empQueryString += 'employeeCode='+form.employeeCode.value;
    empQueryString += '&employeeName='+form.employeeName.value;
        
     
     //designation
    totalDesignationId = form.elements['designationId[]'].length;
    var name = document.getElementById('designationId');
    selectedDesignation='';
    selectedDesignationText='';
    countDesignation=0;
    for(i=0;i<totalDesignationId;i++) {
        if (form.elements['designationId[]'][i].selected == true) {
            if (selectedDesignation != '') {
                selectedDesignation += ',';
                selectedDesignationText += ', ';
            }
            countDesignation++;
            selectedDesignation += form.elements['designationId[]'][i].value;
            selectedDesignationText += eval("name.options["+i+"].text");
        }
    }
    
    empQueryString += '&designationId='+selectedDesignation;
    if(countDesignation==totalDesignationId || countDesignation==0)
        selectedDesignationText ="ALL";
    empQueryString += '&designation='+selectedDesignation+'&designationText='+selectedDesignationText;

    if(!isEmpty(form.birthYearF.value) || !isEmpty(form.birthMonthF.value) || !isEmpty(form.birthDateF.value)){
        
        if(isEmpty(form.birthYearF.value)){
           
           messageBox("Please select date of birth year");
           document.employeeDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(form.birthMonthF.value)){
           
           messageBox("Please select date of birth month");
           form.birthMonthF.focus();
           return false;
        }
        if(isEmpty(form.birthDateF.value)){
           
           messageBox("Please select date of birth date");
           form.birthDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(form.birthYearT.value) || !isEmpty(form.birthMonthT.value) || !isEmpty(form.birthDateT.value)){
        
        if(isEmpty(form.birthYearT.value)){
           
           messageBox("Please select date of birth year");
           document.employeeDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(form.birthMonthT.value)){
           
           messageBox("Please select date of birth month");
           document.employeeDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(form.birthDateT.value)){
           
           messageBox("Please select date of birth date");
           document.employeeDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(form.birthYearF.value) && !isEmpty(form.birthMonthF.value) && !isEmpty(form.birthDateF.value) && !isEmpty(form.birthYearT.value) && !isEmpty(form.birthMonthT.value) && !isEmpty(form.birthDateT.value)){
    
        dobFValue = form.birthYearF.value+"-"+form.birthMonthF.value+"-"+form.birthDateF.value

        dobTValue = form.birthYearT.value+"-"+form.birthMonthT.value+"-"+form.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.employeeDetailsForm.birthYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(form.birthYearF.value) && !isEmpty(form.birthMonthF.value) && !isEmpty(form.birthDateF.value) && !isEmpty(form.birthYearT.value) && !isEmpty(form.birthMonthT.value) && !isEmpty(form.birthDateT.value)){
    
        dobFValue = form.birthYearF.value+"-"+form.birthMonthF.value+"-"+form.birthDateF.value

        dobTValue = form.birthYearT.value+"-"+form.birthMonthT.value+"-"+form.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.employeeDetailsForm.birthYearF.focus();
           return false;
        }
    }
    //from date of birth
    birthDateFF = form.birthYearF.value+'-'+form.birthMonthF.value+'-'+form.birthDateF.value;
    empQueryString += '&birthDateF='+birthDateFF;

    
    //to date of birth
    $birthDateTT = form.birthYearT.value+'-'+form.birthMonthT.value+'-'+form.birthDateT.value;
    empQueryString += '&birthDateT='+$birthDateTT;
    

    // Joining Date                                
    if(!isEmpty(form.joiningYearF.value) || !isEmpty(form.joiningMonthF.value) || !isEmpty(form.joiningDateF.value)){
        
        if(isEmpty(form.joiningYearF.value)){
           
           messageBox("Please select date of joining year");
           document.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
        if(isEmpty(form.joiningMonthF.value)){
           
           messageBox("Please select date of joining month");
           document.employeeDetailsForm.joiningMonthF.focus();
           return false;
        }
        if(isEmpty(form.joiningDateF.value)){
           
           messageBox("Please select date of joining date");
           document.employeeDetailsForm.joiningDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(form.joiningYearT.value) || !isEmpty(form.joiningMonthT.value) || !isEmpty(form.joiningDateT.value)){
        
        if(isEmpty(form.joiningYearT.value)){
           
           messageBox("Please select date of joining year");
           document.employeeDetailsForm.joiningYearT.focus();
           return false;
        }
        if(isEmpty(form.joiningMonthT.value)){
           
           messageBox("Please select date of joining month");
           document.employeeDetailsForm.joiningMonthT.focus();
           return false;
        }
        if(isEmpty(form.joiningDateT.value)){
           
           messageBox("Please select date of joining date");
           document.employeeDetailsForm.joiningDateT.focus();
           return false;
        }
    }

    if(!isEmpty(form.joiningYearF.value) && !isEmpty(form.joiningMonthF.value) && !isEmpty(form.joiningDateF.value) && !isEmpty(form.joiningYearT.value) && !isEmpty(form.joiningMonthT.value) && !isEmpty(form.joiningDateT.value)){
    
        dobFValue = form.joiningYearF.value+"-"+form.joiningMonthF.value+"-"+form.joiningDateF.value

        dobTValue = form.joiningYearT.value+"-"+form.joiningMonthT.value+"-"+form.joiningDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(form.joiningYearF.value) && !isEmpty(form.joiningMonthF.value) && !isEmpty(form.joiningDateF.value) && !isEmpty(form.joiningYearT.value) && !isEmpty(form.joiningMonthT.value) && !isEmpty(form.joiningDateT.value)){
    
        dobFValue = form.joiningYearF.value+"-"+form.joiningMonthF.value+"-"+form.joiningDateF.value

        dobTValue = form.joiningYearT.value+"-"+form.joiningMonthT.value+"-"+form.joiningDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    //from date of joining
    joiningDateFF = form.joiningYearF.value+'-'+form.joiningDateF.value+'-'+form.joiningMonthF.value;
    empQueryString += '&joiningDateF='+joiningDateFF;
  
    //to date of joining
    joiningDateTT = form.joiningYearT.value+'-'+form.joiningDateT.value+'-'+form.joiningMonthT.value;
    empQueryString += '&joiningDateT='+joiningDateTT;
           
    
    
    // Leaving Date                                
    
    if(!isEmpty(form.leavingYearF.value) || !isEmpty(form.leavingMonthF.value) || !isEmpty(form.leavingDateF.value)){
        
        if(isEmpty(form.leavingYearF.value)){
           
           messageBox("Please select date of leaving year");
           document.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
        if(isEmpty(form.leavingMonthF.value)){
           
           messageBox("Please select date of leaving month");
           document.employeeDetailsForm.leavingMonthF.focus();
           return false;
        }
        if(isEmpty(form.leavingDateF.value)){
           
           messageBox("Please select date of leaving date");
           document.employeeDetailsForm.leavingDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(form.leavingYearT.value) || !isEmpty(form.leavingMonthT.value) || !isEmpty(form.leavingDateT.value)){
        
        if(isEmpty(form.leavingYearT.value)){
           
           messageBox("Please select date of leaving year");
           document.employeeDetailsForm.leavingYearT.focus();
           return false;
        }
        if(isEmpty(form.leavingMonthT.value)){
           
           messageBox("Please select date of leaving month");
           document.employeeDetailsForm.leavingMonthT.focus();
           return false;
        }
        if(isEmpty(form.leavingDateT.value)){
           
           messageBox("Please select date of leaving date");
           document.employeeDetailsForm.leavingDateT.focus();
           return false;
        }
    }

    if(!isEmpty(form.leavingYearF.value) && !isEmpty(form.leavingMonthF.value) && !isEmpty(form.leavingDateF.value) && !isEmpty(form.leavingYearT.value) && !isEmpty(form.leavingMonthT.value) && !isEmpty(form.leavingDateT.value)){
    
        dobFValue = form.leavingYearF.value+"-"+form.leavingMonthF.value+"-"+form.leavingDateF.value

        dobTValue = form.leavingYearT.value+"-"+form.leavingMonthT.value+"-"+form.leavingDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(form.leavingYearF.value) && !isEmpty(form.leavingMonthF.value) && !isEmpty(form.leavingDateF.value) && !isEmpty(form.leavingYearT.value) && !isEmpty(form.leavingMonthT.value) && !isEmpty(form.leavingDateT.value)){
    
        dobFValue = form.leavingYearF.value+"-"+form.leavingMonthF.value+"-"+form.leavingDateF.value

        dobTValue = form.leavingYearT.value+"-"+form.leavingMonthT.value+"-"+form.leavingDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
    //from date of leaving
    leavingDateFF = form.leavingYearF.value+'-'+form.leavingDateF.value+'-'+form.leavingMonthF.value;
    empQueryString += '&leavingDateF='+leavingDateFF;
  
    //to date of leaving
    leavingDateTT = form.leavingYearT.value+'-'+form.leavingDateT.value+'-'+form.leavingMonthT.value;
    empQueryString += '&leavingDateT='+leavingDateTT;

       
    //gender + qualification + Married + teachingEmployee
    empQueryString += '&genderRadio='+form.genderRadio.value;
    empQueryString += '&qualification='+form.qualification.value;
    empQueryString += '&isMarried='+form.isMarried.value;
    empQueryString += '&teachEmployee='+form.teachEmployee.value;
     
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
    empQueryString += '&cityId='+selectedCity;

    if(countCity==totalCityId || countCity==0)
        selectedCityText ="ALL";
    empQueryString += '&citys='+selectedCity+'&citysText='+selectedCityText;
    
    //state
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
    empQueryString += '&stateId='+selectedState;
    if(countState==totalStateId || countState==0)
        selectedStateText ="ALL";
    empQueryString += '&state='+selectedState+'&stateText='+selectedStateText;
    
    //country
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
    empQueryString += '&countryId='+selectedCountry;
    if(countCountry==totalCountryId || countCountry==0)
        selectedCountryText ="ALL";
    empQueryString += '&country='+selectedCountry+'&countryText='+selectedCountryText;
    
    //institute
    totalInstituteId = form.elements['instituteId[]'].length;
    var name = document.getElementById('instituteId');
    selectedInstitute='';
    selectedInstituteText='';
    countInstitute=0;
    for(i=0;i<totalInstituteId;i++) {
        if (form.elements['instituteId[]'][i].selected == true) {
            if (selectedInstitute != '') {
                selectedInstitute += ',';
                selectedInstituteText += ', ';
            }
            countInstitute++;
            selectedInstitute += form.elements['instituteId[]'][i].value;
            selectedInstituteText += eval("name.options["+i+"].text");
        }
    }
    empQueryString += '&instituteId='+selectedInstitute;
    if(countInstitute==totalInstituteId || countInstitute==0)
        selectedInstituteText ="ALL";
    empQueryString += '&institute='+selectedInstitute+'&instituteText='+selectedInstituteText;
    
    
    //Department Id
/*    totalDepartmentId = form.elements['departmentId[]'].length;
    var name = document.getElementById('departmentId');
    selectedDepartment='';
    selectedDepartmentText='';
    countDepartment=0;
    for(i=0;i<totalDepartmentId;i++) {
        if (form.elements['departmentId[]'][i].selected == true) {
            if (selectedDepartment != '') {
                selectedDepartment += ',';
                selectedDepartmentText += ', ';
            }
            countDepartment++;
            selectedDepartment += form.elements['departmentId[]'][i].value;
            selectedDepartmentText += eval("name.options["+i+"].text");
        }
    }
    empQueryString += '&departmentId='+selectedDepartment;
    if(countDepartment==totalDepartmentId || countDepartment==0)
        selectedDepartmentText ="ALL";
    empQueryString += '&department='+selectedDepartment+'&departmentText='+selectedDepartmentText;
*/
    
    empPage = document.getElementById('employeesPage').value;
    sortOrderBy1 = document.getElementById('employeesSortOrderBy').value;
    sortField1 = document.getElementById('employeesSortField').value;
    empQueryString += '&page1='+empPage+'&sortOrderBy1='+sortOrderBy1+'&sortField1='+sortField1;

    showHide("hideAll");
       
    //dontMake//queryString = true;
    getEmployeeList();

    
   /* END: search filter*/
    
   return false;
}


function getEmployeeList(){
    tableHeadArray1 = new Array(new Array('srNo','#','width="2%"','',false), 
       new Array('employeeName','Employee Name','width="15%" align="left"',true), 
       new Array('employeeCode','Employee Code','width="15%" align="left"',true), 
       new Array('designationName','Designation','width="15%" align="left"',true),
       new Array('imgSrc','Photo','width="10%" align="center"',false), 
       new Array('checkAll','Download&nbsp;<input type=\"checkbox\" id=\"checkbox4\" name=\"checkbox4\" onclick=\"doAll2();\">','width="10%" align="center"',false),
       new Array('upload','Upload <span style="font-size:12px; ">(Max. Size: <?php echo (MAXIMUM_FILE_SIZE/1024); ?> KB)</span>', 'width="25%" align="left"',false));
       
       //new Array('employeeName','Employee Name','width="15%" align="left"','align="left"',true), 
       //new Array('employeeCode','Employee Code','width="8%" align="center"','align="left"',true), 
       //new Array('designationName','Designation','width="8%" align="left"','align="left"',true),
       //new Array('imgSrc','Photo','width="10%" align="center"','align="center"',false), 
       //new Array('checkAll','Download&nbsp;<input type=\"checkbox\" id=\"checkbox4\" name=\"checkbox4\" onclick=\"doAll2();\">','width="10%"','align="center"',false),
       //new Array('upload','Upload <span style="font-size:12px; ">(Max. Size: <?php echo (MAXIMUM_FILE_SIZE/1024); ?> KB)</span>', 'width="25%" align="left"','align="left"',false));

	//showWaitDialog(true);
	//setTimeOut("showWaitDialog(true)",1);
   listObj1 = new initPage(empListURL,recordsPerPage,linksPerPage,'1','','employeeName','ASC',divResultName1,'','',true,'listObj1',tableHeadArray1,'','',empQueryString);   
   document.getElementById('employeesPage').value = listObj1.page;
   document.getElementById('employeesSortOrderBy').value =  listObj1.sortOrderBy;
   document.getElementById('employeesSortField').value  = listObj1.sortField;
	//alert('1');
   sendRequest(empListURL, listObj1, ' ',true);
   //hideWaitDialog(true);    
   
   //sendReq(empListURL,divResultName1,searchFormName1, empQueryString,false);
   document.getElementById("resultRow4").style.display='';
   document.getElementById('nameRow4').style.display='';
   document.getElementById('nameRow5').style.display='';


}


function hideResults1() {
    document.getElementById("resultRow4").style.display='none';
    document.getElementById('nameRow4').style.display='none';
    document.getElementById('nameRow5').style.display='none';
}


//master function for student
var selStudent=new Array();
function checkUncheckStudent(value,state){
    var c1=selStudent.length;  
    if(state==true){
      var xx = true;  
      for(var i = 0; i < c1; i++) 
       {
         if(value==selStudent[i]){
             xx=false;
             break;
         }
     }
    if(xx == true){selStudent.push(value);} 
   }
   else{
      for(var i = 0; i < c1; i++) 
       {
         if(value==selStudent[i]){
             selStudent.splice(i,1);
             break;
         }     
     }
        
   }
   document.getElementById('selectedStudent').value=selStudent; //assign to the hidden variable
}



//master function for employee
var selEmployee=new Array();
function checkUncheckEmployee(value,state){
    var c1=selEmployee.length;  
    if(state==true){
      var xx = true;  
      for(var i = 0; i < c1; i++) 
       {
         if(value==selEmployee[i]){
             xx=false;
             break;
         }
     }
    if(xx == true){selEmployee.push(value);} 
   }
   else{
      for(var i = 0; i < c1; i++) 
       {
         if(value==selEmployee[i]){
             selEmployee.splice(i,1);
             break;
         }     
     }
        
   }
   document.getElementById('selectedEmp').value=selEmployee; //assign to the hidden variable
}


//used when user clicks on "Assign to all students"
function sdaStudent(state){
    selStudent.splice(0,selStudent.length); //empty the array   
    if(state==true){
       var len=allStudentId.length; 
       for(var k=0;k<len;k++){ 
        //push into array
        selStudent.push(allStudentId[k]['studentId']);   
      }
   } 
   document.getElementById('selectedStudent').value=selStudent; //assign to the hidden variable
   
    if(!chkObject('students')){
     document.listFrm.students.checked =state;
     return true;  
    }
    
    formx = document.listFrm; 
    var l=formx.students.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.students[ i ].checked=state;

    }
    
}


//used when user clicks on "Assign to all employees"
function sdaEmployee(state){
    selEmployee.splice(0,selEmployee.length); //empty the array   
    if(state==true){
       var len=allEmployeeId.length; 
       for(var k=0;k<len;k++){ 
        //push into array
        selEmployee.push(allEmployeeId[k]['employeeId']);   
      }
   } 
   document.getElementById('selectedEmp').value=selEmployee; //assign to the hidden variable
   
    if(!chkObject3('employees')){
     document.listFrm2.employees.checked =state;
     return true;  
    }
    
    formx = document.listFrm2; 
    var l=formx.employees.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.employees[ i ].checked=state;

    }
    
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Parveen Sharma
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

// Student Filter
function checkFileExtensionsUpload(value) {
      //get the extension of the file 
      var val=value.substring(value.lastIndexOf('.')+1,value.length);

      var extArr = new Array('gif','jpg','jpeg','png','bmp');

      var fl=0;
      var ln=extArr.length;
      
      for(var i=0; i <ln; i++){
          if(val.toUpperCase()==extArr[i].toUpperCase()){
              fl=1;
              break;
          }
      }
      
      if(fl==1){
        return true;
      }
      else{
        return false;
      }   
}


function printReport() {
    
    formx = document.allDetailsForm;
    formx1 = document.allDetailsForm;
    var studentCheck = "";
    var studentName = "";
    var selected = 0;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                   if(formx1.elements[i].name!="studentNames") {
                     studentName=formx1.elements[i].value;  
                   }
                }
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                    if(formx1.elements[i].name!="studentNames") {
                       studentName=studentName + ','+formx1.elements[i].value;  
                   }
                }
                selected++;
            }
        }
    }
    
    if(selected==0)    {
       alert("Please select atleast one record!");
       return false;
    }
    
    var pars = "reportDownload=1&studentId="+escape(studentCheck)+"&studentName="+escape(studentName);
    var listURL='<?php echo HTTP_PATH;?>/Templates/Xml/initDownloadImages.php';

    new Ajax.Request(listURL,
    {
        method:'post',
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            returnStatus = trim(transport.responseText);
            returnStatusArray = returnStatus.split('#');
            if (returnStatusArray[0] == "<?php echo SUCCESS;?>") {
                window.location = returnStatusArray[1];
            }
            else {
                messageBox(trim(transport.responseText));
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
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
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=false;
            }
        }
    }
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE Student Photo
//Author : Parveen Sharma
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteStudentImage(id) {
     if(false===confirm("Do you want to delete this image?")) {
         return false;
     }
     else { 
         var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxDeleteStudentImage.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {studentId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         //document.getElementById('imageDisplayDiv').innerHTML='';
                         //messageBox("Image Deleted");
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);  
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
     }    
}

function uploadImages() {
    
     if(globalFL==0){
       messageBox("Another request is in progress.");
       return false;
     }    
    
     var selected = 0; 
     
     var formx = document.allDetailsForm;
     for(var i=0;i<formx.length;i++) {
       if(formx.elements[i].name=="sStudentId[]"){
         selected++;
         break;
       }
     }
    
     if(selected==0) {
       messageBox("<?php echo "No Data Found"; ?>");
       return false;
     }
    

     var len = (formx.fileId.length);
    
     selected = 0;
     fileName = "";
     studentIds = "";
     fileName = "";
     studentName = "";
      
     if(typeof len === "undefined") {
        if(document.getElementById('fileId').value!='') {
           if(!checkFileExtensionsUpload(document.getElementById('fileId').value)){
              messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
              document.getElementById('fileId').focus();  
              return false;
           } 
           studentIds = document.getElementById('sStudentId').value;
           studentName = escape(document.getElementById('studentNames').value);
           selected = 1; 
        } 
     }
     else {
         for(var i=0;i<len;i++){      
             if(trim(formx.fileId[i].value)!=""){
                if(!checkFileExtensionsUpload(trim(formx.fileId[i].value))){
                    messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
                    formx.fileId[i].focus();  
                    return false;
                 }
                 if(studentIds == "") { 
                    studentIds = formx.sStudentId[i].value;
                    studentName = escape(formx.studentNames[i].value);
                 }
                 else {
                    studentIds += ","+formx.sStudentId[i].value;
                    studentName += ","+ escape(formx.studentNames[i].value); 
                 }
                 selected = 1;
             }
         } 
     }
     
     if(selected==1) {
        initAdd(2);  
     }
     else {
        messageBox("<?php echo "Please browse atleast one file."; ?>");  
     }
}


function initAdd(mode) {
    globalFL=1;   
    if(mode==2){
      //showWaitDialog(true); 
      queryString = "reportFormat=1&studentIds="+studentIds+"&studentNames1="+studentName;
      //alert(queryString);
      document.getElementById('allDetailsForm').target = 'uploadTargetEdit';
      document.getElementById('allDetailsForm').action= "<?php echo HTTP_LIB_PATH;?>/AdminTasks/fileUpload.php?"+queryString;
      document.getElementById('allDetailsForm').submit(); 
   } 
}

function fileUploadError(str){
   hideWaitDialog(true);

   if("<?php echo NOT_WRITEABLE_FOLDER;?>" == trim(str)) {
      messageBox("<?php echo NOT_WRITEABLE_FOLDER; ?>");       
      sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
      return false;
   }
   else
   if("<?php echo SUCCESS;?>" == trim(str)) {
      sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
      return false;
   }
   else {
      //Invalid file extension or maximum upload size exceeds
      messageBox("Some files were not uploaded as they exceeded maximum upload size limit of "+<?php echo ceil(MAXIMUM_FILE_SIZE/1024); ?>+"kb");
      //showDetails(trim(str),'divInformation',300,250)
      sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
      return false;
   }
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
   return false;
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "error in upload Show Details" DIV
function showDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv('divInformation','', w, h, 300, 250)
    document.getElementById('uploadInfo').innerHTML= id;    
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AdminTasks/downloadImagesContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
//$History: uploadDownloadImages.php $
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 4/16/10    Time: 10:36a
//Updated in $/LeapCC/Interface
//updated code, fixed issue of sorting links not coming.
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 4/13/10    Time: 7:13p
//Updated in $/LeapCC/Interface
//fixed bugs. FCNS No. 1574
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 2/19/10    Time: 6:25p
//Updated in $/LeapCC/Interface
//fixed bug. 0002903
//
//*****************  Version 11  *****************
//User: Parveen      Date: 2/17/10    Time: 4:57p
//Updated in $/LeapCC/Interface
//page link updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/15/10    Time: 2:53p
//Updated in $/LeapCC/Interface
//validation format & condition updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 1/06/10    Time: 6:33p
//Updated in $/LeapCC/Interface
//sorting format updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/06/10    Time: 6:04p
//Updated in $/LeapCC/Interface
//sorting format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 1/06/10    Time: 3:13p
//Updated in $/LeapCC/Interface
//employee photos upload and download functionality added 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/23/09   Time: 6:53p
//Updated in $/LeapCC/Interface
//is_writeable function added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/21/09   Time: 11:07a
//Updated in $/LeapCC/Interface
//validation format updatd 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/19/09   Time: 12:26p
//Updated in $/LeapCC/Interface
//condition & validation fromat updated (Show Student List)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/18/09   Time: 4:30p
//Updated in $/LeapCC/Interface
//form name updated
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/18/09   Time: 12:44p
//Updated in $/LeapCC/Interface
//updated tilte
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/10/09   Time: 1:16p
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/05/09   Time: 5:54p
//Updated in $/LeapCC/Interface
//upload & download format & validation added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/04/09   Time: 6:54p
//Created in $/LeapCC/Interface
//initial checkin

?>
