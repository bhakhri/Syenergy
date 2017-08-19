<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF FeedBackGrades
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeLeaveCarryForward');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1); 
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;  charset=utf-8" />
<title><?php echo SITE_NAME;?>: Employee Leave Carry Forward </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveCarryForward/ajaxInitEmployeeList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 750; //  add/edit form width
winLayerHeight = 595; // add/edit form height
deleteFunction = 'return deleteEmployee';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'employeeName';
sortOrderBy    = 'ASC';

var queryString = '';   
var myQueryString='';
var studentCheck;


function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById("pageRow").style.display='none';
    document.getElementById('resultsDiv').innerHTML='';
    document.getElementById("pagingDiv").innerHTML = '';
    document.getElementById("pagingDiv1").innerHTML = '';
}


function doAll(){
    formx = document.resultForm;
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

function validateAddForm() {

    //code checks whether atleast one checkbox is selected or not
    myQueryString = '';
    queryString = '';
    form = document.allDetailsForm;

    //roll no. + first name + last name
/*  queryString += 'employeeCode='+form.employeeCode.value;
    queryString += '&employeeName='+form.employeeName.value;
     
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
    
    queryString += '&designationId='+selectedDesignation;
    if(countDesignation==totalDesignationId || countDesignation==0)
        selectedDesignationText ="ALL";
    queryString += '&designation='+selectedDesignation+'&designationText='+selectedDesignationText;
*/
    
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
    
    if(!isEmpty(document.getElementById('birthYearF').value) && !isEmpty(document.getElementById('birthMonthF').value) && !isEmpty(document.getElementById('birthDateF').value) && !isEmpty(document.getElementById('birthYearT').value) && !isEmpty(document.getElementById('birthMonthT').value) && !isEmpty(document.getElementById('birthDateT').value)){
    
        dobFValue = document.getElementById('birthYearF').value+"-"+document.getElementById('birthMonthF').value+"-"+document.getElementById('birthDateF').value

        dobTValue = document.getElementById('birthYearT').value+"-"+document.getElementById('birthMonthT').value+"-"+document.getElementById('birthDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

/*  //from date of birth
    birthDateFF = form.birthYearF.value+'-'+form.birthMonthF.value+'-'+form.birthDateF.value;
    queryString += '&birthDateF='+birthDateFF;

    
    //to date of birth
    $birthDateTT = form.birthYearT.value+'-'+form.birthMonthT.value+'-'+form.birthDateT.value;
    queryString += '&birthDateT='+$birthDateTT;
*/    

    // Joining Date                                
    if(!isEmpty(document.getElementById('joiningYearF').value) || !isEmpty(document.getElementById('joiningMonthF').value) || !isEmpty(document.getElementById('joiningDateF').value)){
        
        if(isEmpty(document.getElementById('joiningYearF').value)){
           
           messageBox("Please select date of joining year");
           document.allDetailsForm.joiningYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('joiningMonthF').value)){
           
           messageBox("Please select date of joining month");
           document.allDetailsForm.joiningMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('joiningDateF').value)){
           
           messageBox("Please select date of joining date");
           document.allDetailsForm.joiningDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.getElementById('joiningYearT').value) || !isEmpty(document.getElementById('joiningMonthT').value) || !isEmpty(document.getElementById('joiningDateT').value)){
        
        if(isEmpty(document.getElementById('joiningYearT').value)){
           
           messageBox("Please select date of joining year");
           document.allDetailsForm.joiningYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('joiningMonthT').value)){
           
           messageBox("Please select date of joining month");
           document.allDetailsForm.joiningMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('joiningDateT').value)){
           
           messageBox("Please select date of joining date");
           document.allDetailsForm.joiningDateT.focus();
           return false;
        }
    }

    
    if(!isEmpty(document.getElementById('joiningYearF').value) && !isEmpty(document.getElementById('joiningMonthF').value) && !isEmpty(document.getElementById('joiningDateF').value) && !isEmpty(document.getElementById('joiningYearT').value) && !isEmpty(document.getElementById('joiningMonthT').value) && !isEmpty(document.getElementById('joiningDateT').value)){
    
        dobFValue = document.getElementById('joiningYearF').value+"-"+document.getElementById('joiningMonthF').value+"-"+document.getElementById('joiningDateF').value

        dobTValue = document.getElementById('joiningYearT').value+"-"+document.getElementById('joiningMonthT').value+"-"+document.getElementById('joiningDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.allDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.getElementById('joiningYearF').value) && !isEmpty(document.getElementById('joiningMonthF').value) && !isEmpty(document.getElementById('joiningDateF').value) && !isEmpty(document.getElementById('joiningYearT').value) && !isEmpty(document.getElementById('joiningMonthT').value) && !isEmpty(document.getElementById('joiningDateT').value)){
    
        dobFValue = document.getElementById('joiningYearF').value+"-"+document.getElementById('joiningMonthF').value+"-"+document.getElementById('joiningDateF').value

        dobTValue = document.getElementById('joiningYearT').value+"-"+document.getElementById('joiningMonthT').value+"-"+document.getElementById('joiningDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.allDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
/*  //from date of joining
    joiningDateFF = form.joiningYearF.value+'-'+form.joiningDateF.value+'-'+form.joiningMonthF.value;
    queryString += '&joiningDateF='+joiningDateFF;
  
    //to date of joining
    joiningDateTT = form.joiningYearT.value+'-'+form.joiningDateT.value+'-'+form.joiningMonthT.value;
    queryString += '&joiningDateT='+joiningDateTT;
*/           
    
    
    // Leaving Date                                
    
    if(!isEmpty(document.getElementById('leavingYearF').value) || !isEmpty(document.getElementById('leavingMonthF').value) || !isEmpty(document.getElementById('leavingDateF').value)){
        
        if(isEmpty(document.getElementById('leavingYearF').value)){
           messageBox("Please select date of leaving year");
           document.allDetailsForm.leavingYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('leavingMonthF').value)){
           
           messageBox("Please select date of leaving month");
           document.allDetailsForm.leavingMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('leavingDateF').value)){
           
           messageBox("Please select date of leaving date");
           document.allDetailsForm.leavingDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.getElementById('leavingYearT').value) || !isEmpty(document.getElementById('leavingMonthT').value) || !isEmpty(document.getElementById('leavingDateT').value)){
        
        if(isEmpty(document.getElementById('leavingYearT').value)){
           
           messageBox("Please select date of leaving year");
           document.allDetailsForm.leavingYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('leavingMonthT').value)){
           
           messageBox("Please select date of leaving month");
           document.allDetailsForm.leavingMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('leavingDateT').value)){
           
           messageBox("Please select date of leaving date");
           document.allDetailsForm.leavingDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('leavingYearF').value) && !isEmpty(document.getElementById('leavingMonthF').value) && !isEmpty(document.getElementById('leavingDateF').value) && !isEmpty(document.getElementById('leavingYearT').value) && !isEmpty(document.getElementById('leavingMonthT').value) && !isEmpty(document.getElementById('leavingDateT').value)){
    
        dobFValue = document.getElementById('leavingYearF').value+"-"+document.getElementById('leavingMonthF').value+"-"+document.getElementById('leavingDateF').value

        dobTValue = document.getElementById('leavingYearT').value+"-"+document.getElementById('leavingMonthT').value+"-"+document.getElementById('leavingDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.allDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.getElementById('leavingYearF').value) && !isEmpty(document.getElementById('leavingMonthF').value) && !isEmpty(document.getElementById('leavingDateF').value) && !isEmpty(document.getElementById('leavingYearT').value) && !isEmpty(document.getElementById('leavingMonthT').value) && !isEmpty(document.getElementById('leavingDateT').value)){
    
        dobFValue = document.getElementById('leavingYearF').value+"-"+document.getElementById('leavingMonthF').value+"-"+document.getElementById('leavingDateF').value

        dobTValue = document.getElementById('leavingYearT').value+"-"+document.getElementById('leavingMonthT').value+"-"+document.getElementById('leavingDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.allDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
/*   //from date of leaving
    leavingDateFF = form.leavingYearF.value+'-'+form.leavingDateF.value+'-'+form.leavingMonthF.value;
    queryString += '&leavingDateF='+leavingDateFF;
  
    //to date of leaving
    leavingDateTT = form.leavingYearT.value+'-'+form.leavingDateT.value+'-'+form.leavingMonthT.value;
    queryString += '&leavingDateT='+leavingDateTT;

       
    //gender + qualification + Married + teachingEmployee
    queryString += '&genderRadio='+form.genderRadio.value;
    queryString += '&qualification='+form.qualification.value;
    queryString += '&isMarried='+form.isMarried.value;
    queryString += '&teachEmployee='+form.teachEmployee.value;
     
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
    queryString += '&stateId='+selectedState;
    if(countState==totalStateId || countState==0)
        selectedStateText ="ALL";
    queryString += '&state='+selectedState+'&stateText='+selectedStateText;
    
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
    queryString += '&countryId='+selectedCountry;
    if(countCountry==totalCountryId || countCountry==0)
        selectedCountryText ="ALL";
    queryString += '&country='+selectedCountry+'&countryText='+selectedCountryText;
    
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
    queryString += '&instituteId='+selectedInstitute;
    if(countInstitute==totalInstituteId || countInstitute==0)
        selectedInstituteText ="ALL";
    queryString += '&institute='+selectedInstitute+'&instituteText='+selectedInstituteText;
    
    
    //Department Id
    totalDepartmentId = form.elements['departmentId[]'].length;
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
    queryString += '&departmentId='+selectedDepartment;
    if(countDepartment==totalDepartmentId || countDepartment==0)
        selectedDepartmentText ="ALL";
    queryString += '&department='+selectedDepartment+'&departmentText='+selectedDepartmentText;
*/    
          
    //queryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField
       
   // showHide("hideAll");       
   // document.getElementById("nameRow").style.display='';  
   // document.getElementById("nameRow2").style.display='';           
   // document.getElementById("resultRow").style.display='';   
   // dontMakeQueryString = true;        
   
    page=1;
    showReport(page);    
    return false;
   
    //sendReq(listURL,divResultName,searchFormName, queryString,false);
}

function showReport(page) {

     var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveCarryForward/ajaxInitEmployeeList.php';        

     params = generateQueryString('allDetailsForm');
     params = params + "&page="+page;    
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: params ,   
         asynchronous:true,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else
             if("<?php echo CREATE_NEXT_LEAVE_SESSION;?>" == trim(transport.responseText)) {
                messageBox("<?php echo CREATE_NEXT_LEAVE_SESSION?>");  
             }
             else
             if("<?php echo ACTIVE_LEAVE_SESSION;?>" == trim(transport.responseText)) {
                messageBox("<?php echo ACTIVE_LEAVE_SESSION?>");  
             }
             else {
                var ret=trim(transport.responseText).split('!~~!');
                var j0 = ret[0];
                var j1 = ret[1];
                
                if(j1=='') {
                  totalRecords = 0;
                }
                else {
                  totalRecords = j1; 
                }
                document.getElementById("nameRow").style.display='';
                document.getElementById("nameRow2").style.display='';
                document.getElementById("resultRow").style.display='';
                document.getElementById("pageRow").style.display='';    
                document.getElementById('resultsDiv').innerHTML=j0;
                //document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
                
                pagingData='';
                document.getElementById("pagingDiv").innerHTML = pagingData;
                document.getElementById("pagingDiv1").innerHTML = pagingData;
                
                totalPages = totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
                completePages = parseInt(totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
                if (totalPages > completePages) {
                    completePages++;
                }
                if (totalRecords > 0) {
                    pagingData = pagination2(page, totalRecords, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
                    document.getElementById("pagingDiv").innerHTML = pagingData;
                    document.getElementById("pagingDiv1").innerHTML = "<b>Total Records&nbsp;:&nbsp;</b>"+totalRecords; 
                }
             }
          },
          onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
        });

}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW DEGREE
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addEmployeeCarryLeave(frm) {
   
   var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveCarryForward/addEmployeeCarryLeave.php';
   
   params = generateQueryString('resultForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     asynchronous:true,      
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText)) {
           messageBox(trim(transport.responseText));    
           return false;
        }
        else {
           messageBox(trim(transport.responseText));   
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
    
}  


function printReport() {
    
   path='<?php echo UI_HTTP_PATH;?>/listEmployeeLeaveCarryForwardPrint.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all payment history to csv*/
function printReportCSV() {

   path='<?php echo UI_HTTP_PATH;?>/listEmployeeLeaveCarryForwardCSV.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   window.location=path;
}



</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeLeaveCarryForward/listEmployeeLeaveCarryForwardContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
