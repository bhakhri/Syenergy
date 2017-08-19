<?php
//-----------------------------------------------------------------------------
//  To generate Employee ICard functionality      
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
define('MODULE','EmployeeIcardReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;  charset=utf-8" />
<title><?php echo SITE_NAME;?>: Employee I-Card Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array( new Array('srNo','#','width="2%"','',false), 
                                new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                                new Array('employeeName','Name','width=14% align="left"','align="left"',true), 
                                new Array('employeeCode','Emp. Code','width="10%" align="left"','align="left"',true), 
                                new Array('designationName','Designation','width="15%" align="left"','align="left"',true), 
                                new Array('departmentAbbr','Deptt.','width="9%" align="left"','align="left"',true), 
                                new Array('dateOfJoining','Date of Joining','width="15%" align="center"','align="center"',true),  
                                new Array('contactNumber','Contact No.','width="12%"','align=left',true), 
                                new Array('issueDate','Issue Date','width="11%"','align=center',true),  
                                new Array('permAddress','Address','width="20%" align="left"','align="left"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Icard/ajaxInitEmployeeList.php';
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
   
    sendReq(listURL,divResultName,searchFormName, queryString,false);
    
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';   
     
    return false;
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

function printReport() {                    
    
    var selected=0;
    employeeId='';
    
    var formx = document.allDetailsForm;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2") && (formx.elements[i].name!="joiningDateCard")  && (formx.elements[i].name!="issueDateCard") && (formx.elements[i].name!="emailCard")){
                if(employeeId=='') {
                   employeeId=formx.elements[i].value; 
                }
                else {
                    employeeId = employeeId + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        }
    }
    if(selected==0)    {
       alert("Please select atleast one record!");
       return false;
    }
    setIssueDate =  formx.setIssueDate.value;
    form = document.allDetailsForm;
    path='<?php echo UI_HTTP_PATH;?>/icardEmployeePrint.php?employeeId='+employeeId+'&setIssueDate='+setIssueDate+'&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&joiningDateCard='+form.joiningDateCard.checked+'&issueDateCard='+form.issueDateCard.checked+'&emailCard='+form.emailCard.checked+'&icardTitle~12='+escape(trim(form.icardTitle.value));
    window.open(path,"ICardReport","status=1,menubar=1,scrollbars=1, width=900, height=500");
    sendReq(listURL,divResultName,searchFormName, queryString,false); 
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Icard/listEmployeeIcardContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 
//$History: employeeIcard.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/22/09   Time: 6:20p
//Updated in $/LeapCC/Interface
//date format & alignement updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/15/09   Time: 1:05p
//Updated in $/LeapCC/Interface
//escape function use 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/14/09   Time: 3:38p
//Updated in $/LeapCC/Interface
//validate format updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/01/09   Time: 3:26p
//Updated in $/LeapCC/Interface
//icard title added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/10/09    Time: 2:08p
//Created in $/LeapCC/Interface
//initial checkin
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