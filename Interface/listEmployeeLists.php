<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in EmployeeListsReport Form
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
define('MODULE','EmployeeList');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee List </title>
<?php require_once(TEMPLATES_PATH .'/jsCssHeader.php'); ?>
<style>
    BR.page { page-break-after: always }
</style> 
<script language="javascript">
//This function Validates Form 
var  queryString;
function validateAddForm(frm) {
      //alert(frm);
  
    //code checks whether atleast one checkbox is selected or not
    queryString = '';
    form = document.allDetailsForm;

    //roll no. + first name + last name
    queryString += 'employeeCode='+form.employeeCode.value;
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
    //from date of birth
    birthDateFF = form.birthYearF.value+'-'+form.birthMonthF.value+'-'+form.birthDateF.value;
    queryString += '&birthDateF='+birthDateFF;

    
    //to date of birth
    $birthDateTT = form.birthYearT.value+'-'+form.birthMonthT.value+'-'+form.birthDateT.value;
    queryString += '&birthDateT='+$birthDateTT;
    

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
    
    //from date of joining
    joiningDateFF = form.joiningYearF.value+'-'+form.joiningDateF.value+'-'+form.joiningMonthF.value;
    queryString += '&joiningDateF='+joiningDateFF;
  
    //to date of joining
    joiningDateTT = form.joiningYearT.value+'-'+form.joiningDateT.value+'-'+form.joiningMonthT.value;
    queryString += '&joiningDateT='+joiningDateTT;
           
    
    
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
    
    //from date of leaving
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
    
  /*
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
    */
    
    
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
    
    
     //country
    totalRoleId = form.elements['roleName'].length;
    var name = document.getElementById('roleName');
    selectedRole='';
    selectedRoleText='';
    countRole=0;
    for(i=0;i<totalRoleId;i++) {
        if (form.elements['roleName'][i].selected == true) {
            if (selectedRole != '') {
                selectedRole += ',';
                selectedRoleText += ', ';
            }
            countRole++;
            selectedRole += form.elements['roleName'][i].value;
            selectedRoleText += eval("name.options["+i+"].text");
        }
    }
    queryString += '&roleName='+selectedRole;
    if(countRole==totalRoleId || countRole==0)
        selectedRoleText ="ALL";
    queryString += '&roleName='+selectedRole+'&roleText='+selectedRoleText;
          
    //queryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField
       
   // showHide("hideAll");       
   // document.getElementById("nameRow").style.display='';  
   // document.getElementById("nameRow2").style.display='';           
   // document.getElementById("resultRow").style.display='';   
   // dontMakeQueryString = true;        
      
   count = 0;
    var objCheckBoxes = document.employeeListReport.elements['check[]'];
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
      
    if(frm=='employeeListSubmit'){
     //For print
        var queryString3=queryString;
        var queryString1= generateQueryString1('employeeListReport') ;  //New function generateQueryString1 used instead of old function as to make print functionality working according to id
        var queryString2=queryString3+'&'+queryString1;
        openEmployeeLists(queryString2);   //alert(queryString)
    }                     
    else { // for CSV   
        var queryString3=queryString;
        var queryString1= generateQueryString1('employeeListReport') ; //New function generateQueryString1 used instead of old function as to make print functionality working according to id
        queryString2=queryString3+'&'+queryString1; 
        printCSV(queryString2);  
    }
    
    return false;
}

//This function adds form through ajax 


function openEmployeeLists(queryString2) {
      if (document.employeeListReport.incAllInsitute.checked)  { 
          incAllInsitute=1; 
       }
       else {
          incAllInsitute=0; 
       }
      // alert(      queryString);
       path='<?php echo UI_HTTP_PATH;?>/listEmployeeListPrint.php?'+queryString2+'&incAllInsitute='+incAllInsitute;
       window.open(path,"EmployeesListReportPrint","status=1,menubar=1,scrollbars=1, width=900");    
}

function printCSV(queryString2) {     //alert(queryString2);  
//      document.getElementById('generateCSV2').href='scEmployeeListsReportPrintCSV.php?queryString='+queryString2;
//      alert(document.getElementById('generateCSV2').href);
      path='<?php echo UI_HTTP_PATH;?>/listEmployeeListsReportPrintCSV.php?'+queryString2;
      window.location = path;
      //window.open(path,"EmployeesListReportCSV","status=1,menubar=1,scrollbars=1");
}   

//autopopulate for batch and study period
/*
function autoPopulate(val,type,frm)
{
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   if(frm=="Add"){
       if(type=="scEmployeeLists"){
            document.employeeListReport.batchId.options.length=0;
            var objOption = new Option("SELECT","");
            document.employeeListReport.batchId.options.add(objOption); 
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
            document.employeeListReport.batchId.length = null;
            // add option Select initially
            addOption(document.employeeListReport.batchId, '', 'Select');
            for(i=0;i<len;i++) { 
             addOption(document.employeeListReport.batchId, j.batchArr[i].batchId, j.batchArr[i].batchName);
       }
            len = j.studyArr.length;
            document.employeeListReport.studyPeriodId.length = null;
            addOption(document.employeeListReport.studyPeriodId, '', 'Select');
            for(i=0;i<len;i++) { 
             addOption(document.employeeListReport.studyPeriodId, j.studyArr[i].studyPeriodId, j.studyArr[i].periodName);
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
    require_once(TEMPLATES_PATH . "/EmployeeReports/listEmployeeListsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>                                                  
</body>
</html>

<?php 

//$History: listEmployeeLists.php $
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/15/09   Time: 3:14p
//Updated in $/LeapCC/Interface
//print & CSV report heading name updated (bug no. 1772)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 9/10/09    Time: 2:50p
//Updated in $/LeapCC/Interface
//template table formatting & print date formating updated 
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/04/09    Time: 12:46p
//Updated in $/LeapCC/Interface
//Gurkeerat: corrected title of page
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/01/09    Time: 5:06p
//Updated in $/LeapCC/Interface
//search condition updated
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:02a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/19/09    Time: 1:22p
//Updated in $/LeapCC/Interface
//show filter description
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/23/08   Time: 5:21p
//Updated in $/LeapCC/Interface
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/23/08   Time: 3:23p
//Created in $/LeapCC/Interface
//inital checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/07/08   Time: 1:49p
//Updated in $/Leap/Source/Interface
//bug fix
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/03/08   Time: 4:54p
//Updated in $/Leap/Source/Interface
//filter selection check condition update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/03/08   Time: 12:46p
//Updated in $/Leap/Source/Interface
//date formatting set of  Birth, Joining, Leaving
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/03/08   Time: 10:16a
//Updated in $/Leap/Source/Interface
//date condition checks update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/31/08   Time: 12:14p
//Created in $/Leap/Source/Interface
//employee list  added
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/23/08    Time: 7:01p
//Updated in $/Leap/Source/Interface
//changed the button name
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/19/08    Time: 6:13p
//Updated in $/Leap/Source/Interface
//used oncreate for ajax
//
//*****************  Version 1  *****************
//User: Arvind       Date: 9/18/08    Time: 7:37p
//Created in $/Leap/Source/Interface
//initial chekin
?>