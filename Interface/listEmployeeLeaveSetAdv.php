<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF FeedBackGrades
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeEmployeeLeaveSetMappingAdv');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;  charset=utf-8" />
<title><?php echo SITE_NAME;?>: Employee Leave Set Mapping(Advance) </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array( new Array('srNo','#','width="2%"','',false), 
                                new Array('employeeName','Name','width=14% align="left"','align="left"',true), 
                                new Array('employeeCode','Emp. Code','width="10%" align="left"','align="left"',true), 
                                new Array('designationName','Designation','width="15%" align="left"','align="left"',true), 
                                new Array('departmentAbbr','Deptt.','width="9%" align="left"','align="left"',true), 
                                new Array('dateOfJoining','Date of Joining','width="15%" align="center"','align="center"',true),  
                                new Array('leaveSet','Leave Set','width="25%" align="left"','align="left"',true));

recordsPerPage = <?php echo 99999;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxInitEmployeeList.php';
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

 
var myQueryString;
var studentCheck;

function validateAddForm() {

    //code checks whether atleast one checkbox is selected or not
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

    // Join Date                                
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
    
/*  from date of joining
    joiningDateFF = form.joiningYearF.value+'-'+form.joiningDateF.value+'-'+form.joiningMonthF.value;
    queryString += '&joiningDateF='+joiningDateFF;
  
   to date of joining
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
    
/*   from date of leaving
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
   
    sendReq(listURL,divResultName,searchFormName, queryString,true);
    
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';   
     
    return false;
}



//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW DEGREE
// Author : Dipanjan Bhattacharjee
// Created on : (19.05.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addEmployeeLeaveSetMapping(frm) {
   
   var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeLeaveSetMapping/ajaxEmployeeLeaveSetBulkInitAdd.php';
   params = generateQueryString('resultForm');
   
   formx = document.resultForm;
   if(formx.length==0) {
     messageBox("No Data Found");  
     return false;
   }
   
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo QUOTA_SLAB_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText) || trim("<?php echo QUOTA_SLAB_UPDATE_SUCCESSFULLY;?>") == trim(transport.responseText)) {
            hideWaitDialog(true);    
            messageBox(trim(transport.responseText));  
            cleanUpTable();
            resetValues();
            document.getElementById('classId').selectedIndex=0;  
            document.getElementById('classId').focus();
            document.getElementById('trAttendance').style.display='none';
            document.getElementById('results').style.display='none';
            document.getElementById('results11').style.display='none';
            return false;
        }
        else if(trim("<?php echo FAILURE;?>") == trim(transport.responseText)) {
           messageBox(trim(transport.responseText));  
        }
        else {   
           hideWaitDialog(true);    
           //messageBox("Nothing to Save"); 
           var ret=trim(transport.responseText).split('!~~!');
           var j0 = trim(ret[0]);
           var j1 = trim(ret[1]); 
           if(j1!='') {
             ret=j1.split(','); 
             for($i=0;$i<ret.length;$i++) {  
               id = "leaveSet"+ret[$i];
               eval("document.getElementById('"+id+"').className='inputboxRed'"); 
               eval("document.getElementById('"+id+"').focus()");
             }
           }
           messageBox(j0);
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
    require_once(TEMPLATES_PATH . "/EmployeeLeaveSetMapping/listEmployeeLeaveSetMappingAdvContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
