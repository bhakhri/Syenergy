<?php
//---------------------------------------------------------------------------
//  THIS FILE used for sending message(sms/email/dashboard) to students
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','AssignSurveyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Assign Survey to Students,Parents,Employees</title>
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

var tableHeadArray = new Array(
 new Array('srNo','#','width="3%"','',false),
 new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%"','align=\"left\"',false), 
 new Array('studentName','Name','width="20%"','',true),
 new Array('rollNo','Roll No.','width="15%"','',true) ,
 new Array('degreeAbbr','Degree','width="8%"','',true) ,
 new Array('branchCode','Branch','width="8%"','',true) ,
 new Array('periodName','Study Period','width="8%"','',true) 
 );
 
 

recordsPerPage = <?php echo RECORDS_PER_PAGE ;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;



/*
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether a control is object or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (24.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function chkObject(id){
 var obj = document.listFrm.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;    
  }
}

function chkObject2(id){
  var obj = document.listFrm1.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;    
  }
}


function chkObject3(id){
  var obj = document.listFrm2.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;    
  }
}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all student checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectStudents(){
    
    //state:checked/not checked
    var state=document.getElementById('studentList').checked;
    if(!chkObject('students')){
     document.listFrm.students.checked =state;
     //push into array
     checkUncheckStudent(document.listFrm.students.value,state);
     return true;  
    }
    formx = document.listFrm; 
    var l=formx.students.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.students[ i ].checked=state;
        //push into array
        checkUncheckStudent(formx.students[ i ].value,state);
    }
}


function  selectParents(){
    
    //state:checked/not checked
    var state=document.getElementById('parentList').checked;
    if(!chkObject2('parents')){
     document.listFrm1.parents.checked =state;
     //push into array
     checkUncheckParent(document.listFrm1.parents.value,state);
     return true;  
    }
    formx = document.listFrm1; 
    var l=formx.parents.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.parents[ i ].checked=state;
        //push into array
        checkUncheckParent(formx.parents[ i ].value,state);
    }
    
}

function  selectEmployees(){
    
    //state:checked/not checked
    var state=document.getElementById('employeeList').checked;
    if(!chkObject3('employees')){
     document.listFrm2.employees.checked =state;
     //push into array
     checkUncheckEmployee(document.listFrm2.employees.value,state);
     return true;  
    }
    formx = document.listFrm2; 
    var l=formx.employees.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.employees[ i ].checked=state;
        //push into array
        checkUncheckEmployee(formx.employees[ i ].value,state);
    }
    
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any student checkboxes selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkStudents(){
    
    var fl=0; 
    if(!chkObject('students')){
     if(document.listFrm.students.checked==true){
         fl=1;
     }
     return fl;
   }
    formx = document.listFrm; 
    var l=formx.students.length;
    
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.students[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
}


function checkParents(){
    
    var fl=0; 
    if(!chkObject2('parents')){
     if(document.listFrm1.parents.checked==true){
         fl=1;
     }
     return fl;
   }
    formx = document.listFrm1; 
    var l=formx.parents.length;
    
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.parents[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
}


function checkEmployees(){
    
    var fl=0; 
    if(!chkObject3('employees')){
     if(document.listFrm2.employees.checked==true){
         fl=1;
     }
     return fl;
   }
    formx = document.listFrm2; 
    var l=formx.employees.length;
    
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.employees[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){
    
    if(mode==2){
     document.getElementById(id).style.display='none';
    }
    else{
        document.getElementById(id).style.display='block';
    }
}




//This function Validates Form 
var myQueryString;
var allStudentId;

function validateStudentList() {
   
    myQueryString = '';
    //alert(document.allDetailsForm.rollNo.value);
    //form = document.allDetailsForm;
    if(document.getElementById('surveyId').value==''){
        messageBox("<?php echo SELECT_SURVEY; ?>");  
        document.getElementById('surveyId').focus();
        return false;
    }
    
    if(!isEmpty(document.allDetailsForm.birthYearF.value) || !isEmpty(document.allDetailsForm.birthMonthF.value) || !isEmpty(document.allDetailsForm.birthDateF.value)){
        
        if(isEmpty(document.allDetailsForm.birthYearF.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.birthMonthF.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");
           document.allDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.birthDateF.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");
           document.allDetailsForm.birthDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.allDetailsForm.birthYearT.value) || !isEmpty(document.allDetailsForm.birthMonthT.value) || !isEmpty(document.allDetailsForm.birthDateT.value)){
        
        if(isEmpty(document.allDetailsForm.birthYearT.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.allDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.birthMonthT.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");  
           document.allDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.birthDateT.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");  
           document.allDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.allDetailsForm.birthYearF.value) && !isEmpty(document.allDetailsForm.birthMonthF.value) && !isEmpty(document.allDetailsForm.birthDateF.value) && !isEmpty(document.allDetailsForm.birthYearT.value) && !isEmpty(document.allDetailsForm.birthMonthT.value) && !isEmpty(document.allDetailsForm.birthDateT.value)){
    
        dobFValue = document.allDetailsForm.birthYearF.value+"-"+document.allDetailsForm.birthMonthF.value+"-"+document.allDetailsForm.birthDateF.value

        dobTValue = document.allDetailsForm.birthYearT.value+"-"+document.allDetailsForm.birthMonthT.value+"-"+document.allDetailsForm.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("<?php echo RESTRICTION_BIRTH_DAY; ?>");  
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

    /* admission date*/
    if(!isEmpty(document.allDetailsForm.admissionYearF.value) || !isEmpty(document.allDetailsForm.admissionMonthF.value) || !isEmpty(document.allDetailsForm.admissionDateF.value)){
        
        if(isEmpty(document.allDetailsForm.admissionYearF.value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");  
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.admissionMonthF.value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");  
           document.allDetailsForm.admissionMonthF.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.admissionDateF.value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");  
           document.allDetailsForm.admissionDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.allDetailsForm.admissionYearT.value) || !isEmpty(document.allDetailsForm.admissionMonthT.value) || !isEmpty(document.allDetailsForm.admissionDateT.value)){
        
        if(isEmpty(document.allDetailsForm.admissionYearT.value)){
           
          messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");
           document.allDetailsForm.admissionYearT.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.admissionMonthT.value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");   
           document.allDetailsForm.admissionMonthT.focus();
           return false;
        }
        if(isEmpty(document.allDetailsForm.admissionDateT.value)){
           
          messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");    
           document.allDetailsForm.admissionDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.allDetailsForm.admissionYearF.value) && !isEmpty(document.allDetailsForm.admissionMonthF.value) && !isEmpty(document.allDetailsForm.admissionDateF.value) && !isEmpty(document.allDetailsForm.admissionYearT.value) && !isEmpty(document.allDetailsForm.admissionMonthT.value) && !isEmpty(document.allDetailsForm.admissionDateT.value)){
    
        doaFValue = document.allDetailsForm.admissionYearF.value+"-"+document.allDetailsForm.admissionMonthF.value+"-"+document.allDetailsForm.admissionDateF.value

        doaTValue = document.allDetailsForm.admissionYearT.value+"-"+document.allDetailsForm.admissionMonthT.value+"-"+document.allDetailsForm.admissionDateT.value

        if(dateCompare(doaFValue,doaTValue)==1){
           
           messageBox("<?php echo RESTRICTION_ADMISSION_DAY; ?>");      
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
    }

    //roll no. + first name + last name
    myQueryString += '&rollNo='+document.allDetailsForm.rollNo.value;
    myQueryString += '&studentName='+document.allDetailsForm.studentName.value;


    //degree
    totalDegreeId = document.allDetailsForm.elements['degreeId[]'].length;
    selectedDegrees='';
    for(i=0;i<totalDegreeId;i++) {
        if (document.allDetailsForm.elements['degreeId[]'][i].selected == true) {
            if (selectedDegrees != '') {
                selectedDegrees += ',';
            }
            selectedDegrees += document.allDetailsForm.elements['degreeId[]'][i].value;
        }
    }

    myQueryString += '&degreeId='+selectedDegrees;

    //branch
    totalBranchId = document.allDetailsForm.elements['branchId[]'].length;
    selectedBranches='';
    for(i=0;i<totalBranchId;i++) {
        if (document.allDetailsForm.elements['branchId[]'][i].selected == true) {
            if (selectedBranches != '') {
                selectedBranches += ',';
            }
            selectedBranches += document.allDetailsForm.elements['branchId[]'][i].value;
        }
    }

    myQueryString += '&branchId='+selectedBranches;

    //periodicity
    totalPeriodicityId = document.allDetailsForm.elements['periodicityId[]'].length;
    selectedPeriodicity='';
    for(i=0;i<totalPeriodicityId;i++) {
        if (document.allDetailsForm.elements['periodicityId[]'][i].selected == true) {
            if (selectedPeriodicity != '') {
                selectedPeriodicity += ',';
            }
            selectedPeriodicity += document.allDetailsForm.elements['periodicityId[]'][i].value;
        }
    }

    myQueryString += '&periodicityId='+selectedPeriodicity;

    //from date of admission
    fromDateA = document.allDetailsForm.admissionMonthF.value+'-'+document.allDetailsForm.admissionDateF.value+'-'+document.allDetailsForm.admissionYearF.value;
    myQueryString += '&fromDateA='+fromDateA;

    
    //to date of admission
    toDateA = document.allDetailsForm.admissionMonthT.value+'-'+document.allDetailsForm.admissionDateT.value+'-'+document.allDetailsForm.admissionYearT.value;
    myQueryString += '&toDateA='+toDateA;

    //from date of birth
    fromDateD = document.allDetailsForm.birthMonthF.value+'-'+document.allDetailsForm.birthDateF.value+'-'+document.allDetailsForm.birthYearF.value;
    myQueryString += '&fromDateD='+fromDateD;

    //to date of birth
    toDateD = document.allDetailsForm.birthMonthT.value+'-'+document.allDetailsForm.birthDateT.value+'-'+document.allDetailsForm.birthYearT.value;
    myQueryString += '&toDateD='+toDateD;

    //gender + mgmt. category + quota
    myQueryString += '&gender='+document.allDetailsForm.gender.value;
    myQueryString += '&categoryId='+document.allDetailsForm.categoryId.value;
    myQueryString += '&quotaId='+document.allDetailsForm.quotaId.value;


    //hotsel
    totalHostelId = document.allDetailsForm.elements['hostelId[]'].length;
    selectedHostel='';
    for(i=0;i<totalHostelId;i++) {
        if (document.allDetailsForm.elements['hostelId[]'][i].selected == true) {
            if (selectedHostel != '') {
                selectedHostel += ',';
            }
            selectedHostel += document.allDetailsForm.elements['hostelId[]'][i].value;
        }
    }

    myQueryString += '&hostelId='+selectedHostel;

    //bus stop
    totalBusStopId = document.allDetailsForm.elements['busStopId[]'].length;
    selectedBusStop='';
    for(i=0;i<totalBusStopId;i++) {
        if (document.allDetailsForm.elements['busStopId[]'][i].selected == true) {
            if (selectedBusStop != '') {
                selectedBusStop += ',';
            }
            selectedBusStop += document.allDetailsForm.elements['busStopId[]'][i].value;
        }
    }

    myQueryString += '&busStopId='+selectedBusStop;


    //bus route
    totalBusRouteId = document.allDetailsForm.elements['busRouteId[]'].length;
    selectedBusRoute='';
    for(i=0;i<totalBusRouteId;i++) {
        if (document.allDetailsForm.elements['busRouteId[]'][i].selected == true) {
            if (selectedBusRoute != '') {
                selectedBusRoute += ',';
            }
            selectedBusRoute += document.allDetailsForm.elements['busRouteId[]'][i].value;
        }
    }
    myQueryString += '&busRouteId='+selectedBusRoute;


    //city
    totalCityId = document.allDetailsForm.elements['cityId[]'].length;
    selectedCity='';
    for(i=0;i<totalCityId;i++) {
        if (document.allDetailsForm.elements['cityId[]'][i].selected == true) {
            if (selectedCity != '') {
                selectedCity += ',';
            }
            selectedCity += document.allDetailsForm.elements['cityId[]'][i].value;
        }
    }
    myQueryString += '&cityId='+selectedCity;

    //state
    totalStateId = document.allDetailsForm.elements['stateId[]'].length;
    selectedState='';
    for(i=0;i<totalStateId;i++) {
        if (document.allDetailsForm.elements['stateId[]'][i].selected == true) {
            if (selectedState != '') {
                selectedState += ',';
            }
            selectedState += document.allDetailsForm.elements['stateId[]'][i].value;
        }
    }
    myQueryString += '&stateId='+selectedState;

    //country
    totalCountryId = document.allDetailsForm.elements['countryId[]'].length;
    selectedCountry='';
    for(i=0;i<totalCountryId;i++) {
        if (document.allDetailsForm.elements['countryId[]'][i].selected == true) {
            if (selectedCountry != '') {
                selectedCountry += ',';
            }
            selectedCountry += document.allDetailsForm.elements['countryId[]'][i].value;
        }
    }
    myQueryString += '&countryId='+selectedCountry;

    
    //course
    totalCourseId = document.allDetailsForm.elements['courseId[]'].length;
    selectedCourse='';
    for(i=0;i<totalCourseId;i++) {
        
        if (document.allDetailsForm.elements['courseId[]'][i].selected == true) {
            if (selectedCourse != '') {
                selectedCourse += ',';
            }
            selectedCourse += document.allDetailsForm.elements['courseId[]'][i].value;
        }
    }
    myQueryString += '&courseId='+selectedCourse;
    
   /* 
    //section
    totalSectionId = document.allDetailsForm.elements['sectionId[]'].length;
    selectedSection='';
    for(i=0;i<totalSectionId;i++) {
        if (document.allDetailsForm.elements['sectionId[]'][i].selected == true) {
            if (selectedSection != '') {
                selectedSection += ',';
            }
            selectedSection += document.allDetailsForm.elements['sectionId[]'][i].value;
        }
    }
    myQueryString += '&sectionId='+selectedSection;
    
  */  
    //univ.
    totalUniversityId = document.allDetailsForm.elements['universityId[]'].length;
    selectedUniversity='';
    for(i=0;i<totalUniversityId;i++) {
        if (document.allDetailsForm.elements['universityId[]'][i].selected == true) {
            if (selectedUniversity != '') {
                selectedUniversity += ',';
            }
            selectedUniversity += document.allDetailsForm.elements['universityId[]'][i].value;
        }
    }
    myQueryString += '&universityId='+selectedUniversity;
  /*  
    if(!isEmpty(document.allDetailsForm.cgpaFrom.value) && !isEmpty(document.allDetailsForm.cgpaTo.value)){
    
        if(document.allDetailsForm.cgpaFrom.value>document.allDetailsForm.cgpaTo.value){

            messageBox("CGPA from value cannot be greater than CGPA to value");
            document.allDetailsForm.cgpaFrom.focus();
            return false; 
        }
    }
  
    if(!isEmpty(document.allDetailsForm.cgpaFrom.value)){
        
        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.allDetailsForm.cgpaFrom.value)){

            messageBox("Please enter correct CGPA from value");
            document.allDetailsForm.cgpaFrom.focus();
            return false; 
        }
        else if (document.allDetailsForm.cgpaFrom.value>10){
            
            messageBox("CGPA cannot be greater than 10");
            document.allDetailsForm.cgpaFrom.focus();
            return false; 
        }
    }
    myQueryString += '&cgpaFrom='+document.allDetailsForm.cgpaFrom.value;
    if(!isEmpty(document.allDetailsForm.cgpaTo.value)){
        
        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.allDetailsForm.cgpaTo.value)){

            messageBox("Please enter correct CGPA to value");
            document.allDetailsForm.cgpaTo.focus();
            return false; 
        }
        else if (document.allDetailsForm.cgpaTo.value>10){
            
            messageBox("CGPA cannot be greater than 10");
            document.allDetailsForm.cgpaTo.focus();
            return false; 
        }
    }
    myQueryString += '&cgpaTo='+document.allDetailsForm.cgpaTo.value;
   */


    queryString = 'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&fromDateA='+fromDateA+'&toDateA='+toDateA+'&fromDateD='+fromDateD+'&toDateD='+toDateD;
    //myQueryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    showHide("hideAll");
	     //clears the array
     var len=selStudent.length;
     selStudent.splice(0,len);
     document.getElementById('selectedStudent').value='';
    
    //show student list
    showStudentList(myQueryString);
    
    
}

//show student list
function showStudentList(queryString){
    document.getElementById('totalStudentsRecordsId').innerHTML='<b>0</b>';
    document.getElementById('sendToAllStudentChk').checked=false; 
    document.getElementById('sendToAllStudentChk').style.display='none'; 
    document.getElementById('studentLabel').style.display='none'; 
    document.getElementById('studentSummeryDiv').innerHTML='';
    getStudentList(queryString);
     
    if(j.studentInfo.length>0){
     allStudentId=j.studentInfo; //all studentIds
     document.getElementById('sendToAllStudentChk').style.display=''; 
     document.getElementById('studentLabel').style.display=''; 
     
     //clears the array
     var len=selStudent.length;
     selStudent.splice(0,len);
     document.getElementById('selectedStudent').value='';
     
     //add to the array
     var cnt= j.studentInfo.length;
     for(i=0;i<cnt;i++){
      if(j.studentInfo[i].studentAssigned=='Yes'){   
        selStudent.push(j.studentInfo[i].studentId);
      }
     }
     document.getElementById('selectedStudent').value=selStudent; //assign to the hidden variable
     
     document.getElementById('studentSummeryDiv').innerHTML=' [ Assigned to <b>'+selStudent.length+'</b>  students]';
    }
    else{
       document.getElementById('studentSummeryDiv').innerHTML='  [Assigned to <b>0</b> students]';
   }
    
    var a=listObj.totalRecords;
    document.getElementById('totalStudentsRecordsId').innerHTML='<b>'+a+'</b>'; 
    
   
    hide_div('showList',1);
    if((document.listFrm.students.length - 2) > 0){
       document.getElementById('divButton1').style.display='block';
     }
    else{
           document.getElementById('divButton1').style.display='none';
     } 
}

function getStudentList(queryString){
  var url = '<?php echo HTTP_LIB_PATH;?>/ScAssignSurvey/scAjaxStudentList.php'; 
  
  var tableColumns = new Array(
                         new Array('srNo','#','width="2%"','',false),
                         new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%" align="left"',false), 
                         new Array('studentName','Name','width="15%"',true),
                         new Array('studentAssigned','Assg.','width="5%"',true),
                         new Array('rollNo','Roll No.','width="15%"',true) ,
                         new Array('degreeAbbr','Degree','width="8%"',true) ,
                         new Array('branchCode','Branch','width="8%"',true) ,
                         new Array('periodName','Study Period','width="8%"',true) 
                      );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'allDetailsForm','studentName','ASC','studentSearchDiv','','',true,'listObj',tableColumns,'','','&surveyId='+document.getElementById('surveyId').value);
 sendRequest(url, listObj, queryString,false); 
 

}

/*This function Validates Form*/ 
var myQueryString2;
var allParentId;

function validateParentList() {
   
    myQueryString2 = '';
    //alert(document.parentDetailsForm.rollNo.value);
    //form = document.parentDetailsForm;
     if(document.getElementById('surveyId').value==''){
        messageBox("<?php echo SELECT_SURVEY; ?>");  
        document.getElementById('surveyId').focus();
        return false;
    }
    
    if(!isEmpty(document.parentDetailsForm.birthYearF.value) || !isEmpty(document.parentDetailsForm.birthMonthF.value) || !isEmpty(document.parentDetailsForm.birthDateF.value)){
        
        if(isEmpty(document.parentDetailsForm.birthYearF.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.parentDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.parentDetailsForm.birthMonthF.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");
           document.parentDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.parentDetailsForm.birthDateF.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");
           document.parentDetailsForm.birthDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.parentDetailsForm.birthYearT.value) || !isEmpty(document.parentDetailsForm.birthMonthT.value) || !isEmpty(document.parentDetailsForm.birthDateT.value)){
        
        if(isEmpty(document.parentDetailsForm.birthYearT.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.parentDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.parentDetailsForm.birthMonthT.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");  
           document.parentDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.parentDetailsForm.birthDateT.value)){
           
           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");  
           document.parentDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.parentDetailsForm.birthYearF.value) && !isEmpty(document.parentDetailsForm.birthMonthF.value) && !isEmpty(document.parentDetailsForm.birthDateF.value) && !isEmpty(document.parentDetailsForm.birthYearT.value) && !isEmpty(document.parentDetailsForm.birthMonthT.value) && !isEmpty(document.parentDetailsForm.birthDateT.value)){
    
        dobFValue = document.parentDetailsForm.birthYearF.value+"-"+document.parentDetailsForm.birthMonthF.value+"-"+document.parentDetailsForm.birthDateF.value

        dobTValue = document.parentDetailsForm.birthYearT.value+"-"+document.parentDetailsForm.birthMonthT.value+"-"+document.parentDetailsForm.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("<?php echo RESTRICTION_BIRTH_DAY; ?>");  
           document.parentDetailsForm.birthYearF.focus();
           return false;
        }
    }

    /* admission date*/
    if(!isEmpty(document.parentDetailsForm.admissionYearF.value) || !isEmpty(document.parentDetailsForm.admissionMonthF.value) || !isEmpty(document.parentDetailsForm.admissionDateF.value)){
        
        if(isEmpty(document.parentDetailsForm.admissionYearF.value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");  
           document.parentDetailsForm.admissionYearF.focus();
           return false;
        }
        if(isEmpty(document.parentDetailsForm.admissionMonthF.value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");  
           document.parentDetailsForm.admissionMonthF.focus();
           return false;
        }
        if(isEmpty(document.parentDetailsForm.admissionDateF.value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");  
           document.parentDetailsForm.admissionDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.parentDetailsForm.admissionYearT.value) || !isEmpty(document.parentDetailsForm.admissionMonthT.value) || !isEmpty(document.parentDetailsForm.admissionDateT.value)){
        
        if(isEmpty(document.parentDetailsForm.admissionYearT.value)){
           
          messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");
           document.parentDetailsForm.admissionYearT.focus();
           return false;
        }
        if(isEmpty(document.parentDetailsForm.admissionMonthT.value)){
           
           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");   
           document.parentDetailsForm.admissionMonthT.focus();
           return false;
        }
        if(isEmpty(document.parentDetailsForm.admissionDateT.value)){
           
          messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");    
           document.parentDetailsForm.admissionDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.parentDetailsForm.admissionYearF.value) && !isEmpty(document.parentDetailsForm.admissionMonthF.value) && !isEmpty(document.parentDetailsForm.admissionDateF.value) && !isEmpty(document.parentDetailsForm.admissionYearT.value) && !isEmpty(document.parentDetailsForm.admissionMonthT.value) && !isEmpty(document.parentDetailsForm.admissionDateT.value)){
    
        doaFValue = document.parentDetailsForm.admissionYearF.value+"-"+document.parentDetailsForm.admissionMonthF.value+"-"+document.parentDetailsForm.admissionDateF.value

        doaTValue = document.parentDetailsForm.admissionYearT.value+"-"+document.parentDetailsForm.admissionMonthT.value+"-"+document.parentDetailsForm.admissionDateT.value

        if(dateCompare(doaFValue,doaTValue)==1){
           
           messageBox("<?php echo RESTRICTION_ADMISSION_DAY; ?>");      
           document.parentDetailsForm.admissionYearF.focus();
           return false;
        }
    }

    //roll no. + first name + last name
    myQueryString2 += '&rollNo='+document.parentDetailsForm.rollNo.value;
    myQueryString2 += '&studentName='+document.parentDetailsForm.studentName.value;


    //degree
    totalDegreeId = document.parentDetailsForm.elements['degreeId[]'].length;
    selectedDegrees='';
    for(i=0;i<totalDegreeId;i++) {
        if (document.parentDetailsForm.elements['degreeId[]'][i].selected == true) {
            if (selectedDegrees != '') {
                selectedDegrees += ',';
            }
            selectedDegrees += document.parentDetailsForm.elements['degreeId[]'][i].value;
        }
    }

    myQueryString2 += '&degreeId='+selectedDegrees;

    //branch
    totalBranchId = document.parentDetailsForm.elements['branchId[]'].length;
    selectedBranches='';
    for(i=0;i<totalBranchId;i++) {
        if (document.parentDetailsForm.elements['branchId[]'][i].selected == true) {
            if (selectedBranches != '') {
                selectedBranches += ',';
            }
            selectedBranches += document.parentDetailsForm.elements['branchId[]'][i].value;
        }
    }

    myQueryString2 += '&branchId='+selectedBranches;

    //periodicity
    totalPeriodicityId = document.parentDetailsForm.elements['periodicityId[]'].length;
    selectedPeriodicity='';
    for(i=0;i<totalPeriodicityId;i++) {
        if (document.parentDetailsForm.elements['periodicityId[]'][i].selected == true) {
            if (selectedPeriodicity != '') {
                selectedPeriodicity += ',';
            }
            selectedPeriodicity += document.parentDetailsForm.elements['periodicityId[]'][i].value;
        }
    }

    myQueryString2 += '&periodicityId='+selectedPeriodicity;

    //from date of admission
    fromDateA = document.parentDetailsForm.admissionMonthF.value+'-'+document.parentDetailsForm.admissionDateF.value+'-'+document.parentDetailsForm.admissionYearF.value;
    myQueryString2 += '&fromDateA='+fromDateA;

    
    //to date of admission
    toDateA = document.parentDetailsForm.admissionMonthT.value+'-'+document.parentDetailsForm.admissionDateT.value+'-'+document.parentDetailsForm.admissionYearT.value;
    myQueryString2 += '&toDateA='+toDateA;

    //from date of birth
    fromDateD = document.parentDetailsForm.birthMonthF.value+'-'+document.parentDetailsForm.birthDateF.value+'-'+document.parentDetailsForm.birthYearF.value;
    myQueryString2 += '&fromDateD='+fromDateD;

    //to date of birth
    toDateD = document.parentDetailsForm.birthMonthT.value+'-'+document.parentDetailsForm.birthDateT.value+'-'+document.parentDetailsForm.birthYearT.value;
    myQueryString2 += '&toDateD='+toDateD;

    //gender + mgmt. category + quota
    myQueryString2 += '&gender='+document.parentDetailsForm.gender.value;
    myQueryString2 += '&categoryId='+document.parentDetailsForm.categoryId.value;
    myQueryString2 += '&quotaId='+document.parentDetailsForm.quotaId.value;


    //hotsel
    totalHostelId = document.parentDetailsForm.elements['hostelId[]'].length;
    selectedHostel='';
    for(i=0;i<totalHostelId;i++) {
        if (document.parentDetailsForm.elements['hostelId[]'][i].selected == true) {
            if (selectedHostel != '') {
                selectedHostel += ',';
            }
            selectedHostel += document.parentDetailsForm.elements['hostelId[]'][i].value;
        }
    }

    myQueryString2 += '&hostelId='+selectedHostel;

    //bus stop
    totalBusStopId = document.parentDetailsForm.elements['busStopId[]'].length;
    selectedBusStop='';
    for(i=0;i<totalBusStopId;i++) {
        if (document.parentDetailsForm.elements['busStopId[]'][i].selected == true) {
            if (selectedBusStop != '') {
                selectedBusStop += ',';
            }
            selectedBusStop += document.parentDetailsForm.elements['busStopId[]'][i].value;
        }
    }

    myQueryString2 += '&busStopId='+selectedBusStop;


    //bus route
    totalBusRouteId = document.parentDetailsForm.elements['busRouteId[]'].length;
    selectedBusRoute='';
    for(i=0;i<totalBusRouteId;i++) {
        if (document.parentDetailsForm.elements['busRouteId[]'][i].selected == true) {
            if (selectedBusRoute != '') {
                selectedBusRoute += ',';
            }
            selectedBusRoute += document.parentDetailsForm.elements['busRouteId[]'][i].value;
        }
    }
    myQueryString2 += '&busRouteId='+selectedBusRoute;


    //city
    totalCityId = document.parentDetailsForm.elements['cityId[]'].length;
    selectedCity='';
    for(i=0;i<totalCityId;i++) {
        if (document.parentDetailsForm.elements['cityId[]'][i].selected == true) {
            if (selectedCity != '') {
                selectedCity += ',';
            }
            selectedCity += document.parentDetailsForm.elements['cityId[]'][i].value;
        }
    }
    myQueryString2 += '&cityId='+selectedCity;

    //state
    totalStateId = document.parentDetailsForm.elements['stateId[]'].length;
    selectedState='';
    for(i=0;i<totalStateId;i++) {
        if (document.parentDetailsForm.elements['stateId[]'][i].selected == true) {
            if (selectedState != '') {
                selectedState += ',';
            }
            selectedState += document.parentDetailsForm.elements['stateId[]'][i].value;
        }
    }
    myQueryString2 += '&stateId='+selectedState;

    //country
    totalCountryId = document.parentDetailsForm.elements['countryId[]'].length;
    selectedCountry='';
    for(i=0;i<totalCountryId;i++) {
        if (document.parentDetailsForm.elements['countryId[]'][i].selected == true) {
            if (selectedCountry != '') {
                selectedCountry += ',';
            }
            selectedCountry += document.parentDetailsForm.elements['countryId[]'][i].value;
        }
    }
    myQueryString2 += '&countryId='+selectedCountry;

    
    //course
    totalCourseId = document.parentDetailsForm.elements['courseId[]'].length;
    selectedCourse='';
    for(i=0;i<totalCourseId;i++) {
        
        if (document.parentDetailsForm.elements['courseId[]'][i].selected == true) {
            if (selectedCourse != '') {
                selectedCourse += ',';
            }
            selectedCourse += document.parentDetailsForm.elements['courseId[]'][i].value;
        }
    }
    myQueryString2 += '&courseId='+selectedCourse;
    
   /* 
    //section
    totalSectionId = document.parentDetailsForm.elements['sectionId[]'].length;
    selectedSection='';
    for(i=0;i<totalSectionId;i++) {
        if (document.parentDetailsForm.elements['sectionId[]'][i].selected == true) {
            if (selectedSection != '') {
                selectedSection += ',';
            }
            selectedSection += document.parentDetailsForm.elements['sectionId[]'][i].value;
        }
    }
    myQueryString2 += '&sectionId='+selectedSection;
   */ 
    
    
    //univ.
    totalUniversityId = document.parentDetailsForm.elements['universityId[]'].length;
    selectedUniversity='';
    for(i=0;i<totalUniversityId;i++) {
        if (document.parentDetailsForm.elements['universityId[]'][i].selected == true) {
            if (selectedUniversity != '') {
                selectedUniversity += ',';
            }
            selectedUniversity += document.parentDetailsForm.elements['universityId[]'][i].value;
        }
    }
    myQueryString2 += '&universityId='+selectedUniversity;
   
   /* 
    if(!isEmpty(document.parentDetailsForm.cgpaFrom.value) && !isEmpty(document.parentDetailsForm.cgpaTo.value)){
    
        if(document.parentDetailsForm.cgpaFrom.value>document.parentDetailsForm.cgpaTo.value){

            messageBox("CGPA from value cannot be greater than CGPA to value");
            document.parentDetailsForm.cgpaFrom.focus();
            return false; 
        }
    }
    if(!isEmpty(document.parentDetailsForm.cgpaFrom.value)){
        
        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.parentDetailsForm.cgpaFrom.value)){

            messageBox("Please enter correct CGPA from value");
            document.parentDetailsForm.cgpaFrom.focus();
            return false; 
        }
        else if (document.parentDetailsForm.cgpaFrom.value>10){
            
            messageBox("CGPA cannot be greater than 10");
            document.parentDetailsForm.cgpaFrom.focus();
            return false; 
        }
    }
    myQueryString2 += '&cgpaFrom='+document.parentDetailsForm.cgpaFrom.value;
    if(!isEmpty(document.parentDetailsForm.cgpaTo.value)){
        
        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.parentDetailsForm.cgpaTo.value)){

            messageBox("Please enter correct CGPA to value");
            document.parentDetailsForm.cgpaTo.focus();
            return false; 
        }
        else if (document.parentDetailsForm.cgpaTo.value>10){
            
            messageBox("CGPA cannot be greater than 10");
            document.parentDetailsForm.cgpaTo.focus();
            return false; 
        }
    }
    myQueryString2 += '&cgpaTo='+document.parentDetailsForm.cgpaTo.value;
   */


    queryString = 'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&fromDateA='+fromDateA+'&toDateA='+toDateA+'&fromDateD='+fromDateD+'&toDateD='+toDateD;
   // myQueryString2 += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    showHideParent("hideAll");

	//clears the array
     var len=selParent.length;
     selParent.splice(0,len);
     document.getElementById('selectedParent').value='';
    
    //show parent list
    showParentList(myQueryString2);
    
    
   
}

//show parent list
function showParentList(queryString){
    document.getElementById('totalParentsRecordsId').innerHTML='<b>0</b>';
    document.getElementById('sendToAllParentChk').checked=false; 
    document.getElementById('sendToAllParentChk').style.display='none'; 
    document.getElementById('parentLabel').style.display='none'; 
    
    document.getElementById('parentSummeryDiv').innerHTML=''; 
    
    getParentList(queryString);
    
   if(j.parentInfo.length>0){
     allParentId=j.parentInfo; //all parentIds[studentIds]
     document.getElementById('sendToAllParentChk').style.display=''; 
     document.getElementById('parentLabel').style.display=''; 
     
     //clears the array
     var len=selParent.length;
     selParent.splice(0,len);
     document.getElementById('selectedParent').value='';
     
     //add to the array
     var cnt= j.parentInfo.length;
     for(i=0;i<cnt;i++){
      if(j.parentInfo[i].parentAssigned=='Yes'){   
        selParent.push(j.parentInfo[i].studentId);
      }
     }
     document.getElementById('selectedParent').value=selParent; //assign to the hidden variable
     
     document.getElementById('parentSummeryDiv').innerHTML=' [ Assigned to <b>'+selParent.length+'</b> parents]';
    }
    else{
       document.getElementById('parentSummeryDiv').innerHTML='  [Assigned to <b>0</b> parents]';
   }
    
    var a=listObj1.totalRecords;
    document.getElementById('totalParentsRecordsId').innerHTML='<b>'+a+'</b>';
    
    //allStudentId=j.studentInfo; //all studentIds
    hide_div('showList1',1);
    if((document.listFrm1.parents.length - 2) > 0){
       document.getElementById('divButton2').style.display='block';
     }
    else{
           document.getElementById('divButton2').style.display='none';
     } 
}

function getParentList(queryString){
  var url = '<?php echo HTTP_LIB_PATH;?>/ScAssignSurvey/scAjaxParentList.php'; 
  
  var tableColumns = new Array(
                         new Array('srNo','#','width="2%"','',false),
                         new Array('parents','<input type=\"checkbox\" id=\"parentList\" name=\"parentList\" onclick=\"selectParents();\">','width="3%" align="left"',false), 
                         new Array('studentName','Name','width="20%"',true),
                         new Array('parentAssigned','Assg.','width="5%"',true),
                         new Array('fatherName','Father','width="15%"',true) ,
                         new Array('motherName','Mother','width="15%"',true) ,
                         new Array('guardianName','Guardian','width="15%"',true)
                      );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'parentDetailsForm','studentName','ASC','parentSearchDiv','','',true,'listObj1',tableColumns,'','','&surveyId='+document.getElementById('surveyId').value);
 sendRequest(url, listObj1, queryString,false); 
}

/*This function Validates Form*/ 
var myQueryString3;
var allEmployeeId;

//This function Validates Form 
function validateEmployeeList(frm) {
      //alert(frm);
  
    //code checks whether atleast one checkbox is selected or not
    myQueryString3 = '';
   // form = document.allDetailsForm;
    if(document.getElementById('surveyId').value==''){
        messageBox("<?php echo SELECT_SURVEY; ?>");  
        document.getElementById('surveyId').focus();
        return false;
    }

    //roll no. + first name + last name
    myQueryString3 += 'employeeCode='+document.employeeDetailsForm.employeeCode.value;
    myQueryString3 += '&employeeName='+document.employeeDetailsForm.employeeName.value;
        
     
    //designation
    totalDesignationId = document.employeeDetailsForm.elements['designationId[]'].length;
    selectedDesignation='';
    for(i=0;i<totalDesignationId;i++) {
        if (document.employeeDetailsForm.elements['designationId[]'][i].selected == true) {
            if (selectedDesignation != '') {
                selectedDesignation += ',';
            }
            selectedDesignation += document.employeeDetailsForm.elements['designationId[]'][i].value;
        }
    }
    
    myQueryString3 += '&designationId='+selectedDesignation;

    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) || !isEmpty(document.employeeDetailsForm.birthMonthF.value) || !isEmpty(document.employeeDetailsForm.birthDateF.value)){
        
        if(isEmpty(document.employeeDetailsForm.birthYearF.value)){
           
           messageBox("Please select date of birth year");
           document.allDetailsdocument.employeeDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthMonthF.value)){
           
           messageBox("Please select date of birth month");
           document.allDetailsdocument.employeeDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthDateF.value)){
           
           messageBox("Please select date of birth date");
           document.allDetailsdocument.employeeDetailsForm.birthDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.birthYearT.value) || !isEmpty(document.employeeDetailsForm.birthMonthT.value) || !isEmpty(document.employeeDetailsForm.birthDateT.value)){
        
        if(isEmpty(document.employeeDetailsForm.birthYearT.value)){
           
           messageBox("Please select date of birth year");
           document.allDetailsdocument.employeeDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthMonthT.value)){
           
           messageBox("Please select date of birth month");
           document.allDetailsdocument.employeeDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthDateT.value)){
           
           messageBox("Please select date of birth date");
           document.allDetailsdocument.employeeDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) && !isEmpty(document.employeeDetailsForm.birthMonthF.value) && !isEmpty(document.employeeDetailsForm.birthDateF.value) && !isEmpty(document.employeeDetailsForm.birthYearT.value) && !isEmpty(document.employeeDetailsForm.birthMonthT.value) && !isEmpty(document.employeeDetailsForm.birthDateT.value)){
    
        dobFValue = document.employeeDetailsForm.birthYearF.value+"-"+document.employeeDetailsForm.birthMonthF.value+"-"+document.employeeDetailsForm.birthDateF.value

        dobTValue = document.employeeDetailsForm.birthYearT.value+"-"+document.employeeDetailsForm.birthMonthT.value+"-"+document.employeeDetailsForm.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsdocument.employeeDetailsForm.birthYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) && !isEmpty(document.employeeDetailsForm.birthMonthF.value) && !isEmpty(document.employeeDetailsForm.birthDateF.value) && !isEmpty(document.employeeDetailsForm.birthYearT.value) && !isEmpty(document.employeeDetailsForm.birthMonthT.value) && !isEmpty(document.employeeDetailsForm.birthDateT.value)){
    
        dobFValue = document.employeeDetailsForm.birthYearF.value+"-"+document.employeeDetailsForm.birthMonthF.value+"-"+document.employeeDetailsForm.birthDateF.value

        dobTValue = document.employeeDetailsForm.birthYearT.value+"-"+document.employeeDetailsForm.birthMonthT.value+"-"+document.employeeDetailsForm.birthDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsdocument.employeeDetailsForm.birthYearF.focus();
           return false;
        }
    }
    //from date of birth
    birthDateFF = document.employeeDetailsForm.birthYearF.value+'-'+document.employeeDetailsForm.birthMonthF.value+'-'+document.employeeDetailsForm.birthDateF.value;
    myQueryString3 += '&birthDateF='+birthDateFF;

    
    //to date of birth
    $birthDateTT = document.employeeDetailsForm.birthYearT.value+'-'+document.employeeDetailsForm.birthMonthT.value+'-'+document.employeeDetailsForm.birthDateT.value;
    myQueryString3 += '&birthDateT='+$birthDateTT;
    

    // Joining Date                                
    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) || !isEmpty(document.employeeDetailsForm.joiningMonthF.value) || !isEmpty(document.employeeDetailsForm.joiningDateF.value)){
        
        if(isEmpty(document.employeeDetailsForm.joiningYearF.value)){
           
           messageBox("Please select date of joining year");
           document.allDetailsdocument.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningMonthF.value)){
           
           messageBox("Please select date of joining month");
           document.allDetailsdocument.employeeDetailsForm.joiningMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningDateF.value)){
           
           messageBox("Please select date of joining date");
           document.allDetailsdocument.employeeDetailsForm.joiningDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.joiningYearT.value) || !isEmpty(document.employeeDetailsForm.joiningMonthT.value) || !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
        
        if(isEmpty(document.employeeDetailsForm.joiningYearT.value)){
           
           messageBox("Please select date of joining year");
           document.allDetailsdocument.employeeDetailsForm.joiningYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningMonthT.value)){
           
           messageBox("Please select date of joining month");
           document.allDetailsdocument.employeeDetailsForm.joiningMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningDateT.value)){
           
           messageBox("Please select date of joining date");
           document.allDetailsdocument.employeeDetailsForm.joiningDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) && !isEmpty(document.employeeDetailsForm.joiningMonthF.value) && !isEmpty(document.employeeDetailsForm.joiningDateF.value) && !isEmpty(document.employeeDetailsForm.joiningYearT.value) && !isEmpty(document.employeeDetailsForm.joiningMonthT.value) && !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
    
        dobFValue = document.employeeDetailsForm.joiningYearF.value+"-"+document.employeeDetailsForm.joiningMonthF.value+"-"+document.employeeDetailsForm.joiningDateF.value

        dobTValue = document.employeeDetailsForm.joiningYearT.value+"-"+document.employeeDetailsForm.joiningMonthT.value+"-"+document.employeeDetailsForm.joiningDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.allDetailsdocument.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) && !isEmpty(document.employeeDetailsForm.joiningMonthF.value) && !isEmpty(document.employeeDetailsForm.joiningDateF.value) && !isEmpty(document.employeeDetailsForm.joiningYearT.value) && !isEmpty(document.employeeDetailsForm.joiningMonthT.value) && !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
    
        dobFValue = document.employeeDetailsForm.joiningYearF.value+"-"+document.employeeDetailsForm.joiningMonthF.value+"-"+document.employeeDetailsForm.joiningDateF.value


        dobTValue = document.employeeDetailsForm.joiningYearT.value+"-"+document.employeeDetailsForm.joiningMonthT.value+"-"+document.employeeDetailsForm.joiningDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.allDetailsdocument.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    //from date of joining
    joiningDateFF = document.employeeDetailsForm.joiningYearF.value+'-'+document.employeeDetailsForm.joiningDateF.value+'-'+document.employeeDetailsForm.joiningMonthF.value;
    myQueryString3 += '&joiningDateF='+joiningDateFF;
  
    //to date of joining
    joiningDateTT = document.employeeDetailsForm.joiningYearT.value+'-'+document.employeeDetailsForm.joiningDateT.value+'-'+document.employeeDetailsForm.joiningMonthT.value;
    myQueryString3 += '&joiningDateT='+joiningDateTT;
           
    
    
    // Leaving Date                                
    
    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) || !isEmpty(document.employeeDetailsForm.leavingMonthF.value) || !isEmpty(document.employeeDetailsForm.leavingDateF.value)){
        
        if(isEmpty(document.employeeDetailsForm.leavingYearF.value)){
           
           messageBox("Please select date of leaving year");
           document.allDetailsdocument.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingMonthF.value)){
           
           messageBox("Please select date of leaving month");
           document.allDetailsdocument.employeeDetailsForm.leavingMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingDateF.value)){
           
           messageBox("Please select date of leaving date");
           document.allDetailsdocument.employeeDetailsForm.leavingDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.leavingYearT.value) || !isEmpty(document.employeeDetailsForm.leavingMonthT.value) || !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
        
        if(isEmpty(document.employeeDetailsForm.leavingYearT.value)){
           
           messageBox("Please select date of leaving year");
           document.allDetailsdocument.employeeDetailsForm.leavingYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingMonthT.value)){
           
           messageBox("Please select date of leaving month");
           document.allDetailsdocument.employeeDetailsForm.leavingMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingDateT.value)){
           
           messageBox("Please select date of leaving date");
           document.allDetailsdocument.employeeDetailsForm.leavingDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) && !isEmpty(document.employeeDetailsForm.leavingMonthF.value) && !isEmpty(document.employeeDetailsForm.leavingDateF.value) && !isEmpty(document.employeeDetailsForm.leavingYearT.value) && !isEmpty(document.employeeDetailsForm.leavingMonthT.value) && !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
    
        dobFValue = document.employeeDetailsForm.leavingYearF.value+"-"+document.employeeDetailsForm.leavingMonthF.value+"-"+document.employeeDetailsForm.leavingDateF.value

        dobTValue = document.employeeDetailsForm.leavingYearT.value+"-"+document.employeeDetailsForm.leavingMonthT.value+"-"+document.employeeDetailsForm.leavingDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.allDetailsdocument.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) && !isEmpty(document.employeeDetailsForm.leavingMonthF.value) && !isEmpty(document.employeeDetailsForm.leavingDateF.value) && !isEmpty(document.employeeDetailsForm.leavingYearT.value) && !isEmpty(document.employeeDetailsForm.leavingMonthT.value) && !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
    
        dobFValue = document.employeeDetailsForm.leavingYearF.value+"-"+document.employeeDetailsForm.leavingMonthF.value+"-"+document.employeeDetailsForm.leavingDateF.value

        dobTValue = document.employeeDetailsForm.leavingYearT.value+"-"+document.employeeDetailsForm.leavingMonthT.value+"-"+document.employeeDetailsForm.leavingDateT.value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.allDetailsdocument.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
    //from date of leaving
    leavingDateFF = document.employeeDetailsForm.leavingYearF.value+'-'+document.employeeDetailsForm.leavingDateF.value+'-'+document.employeeDetailsForm.leavingMonthF.value;
    myQueryString3 += '&leavingDateF='+leavingDateFF;
  
    //to date of leaving
    leavingDateTT = document.employeeDetailsForm.leavingYearT.value+'-'+document.employeeDetailsForm.leavingDateT.value+'-'+document.employeeDetailsForm.leavingMonthT.value;
    myQueryString3 += '&leavingDateT='+leavingDateTT;

       
    //gender + qualification + Married + teachingEmployee
    myQueryString3 += '&genderRadio='+document.employeeDetailsForm.genderRadio.value;
    myQueryString3 += '&qualification='+document.employeeDetailsForm.qualification.value;
    myQueryString3 += '&isMarried='+document.employeeDetailsForm.isMarried.value;
    myQueryString3 += '&teachEmployee='+document.employeeDetailsForm.teachEmployee.value;
     
    //city
    totalCityId = document.employeeDetailsForm.elements['cityId[]'].length;
    selectedCity='';
    for(i=0;i<totalCityId;i++) {
        if (document.employeeDetailsForm.elements['cityId[]'][i].selected == true) {
            if (selectedCity != '') {
                selectedCity += ',';
            }
            selectedCity += document.employeeDetailsForm.elements['cityId[]'][i].value;
        }
    }
    myQueryString3 += '&cityId='+selectedCity;
    
    //state
    totalStateId = document.employeeDetailsForm.elements['stateId[]'].length;
    selectedState='';
    for(i=0;i<totalStateId;i++) {
        if (document.employeeDetailsForm.elements['stateId[]'][i].selected == true) {
            if (selectedState != '') {
                selectedState += ',';
            }
            selectedState += document.employeeDetailsForm.elements['stateId[]'][i].value;
        }
    }
    myQueryString3 += '&stateId='+selectedState;
    
    //country
    totalCountryId = document.employeeDetailsForm.elements['countryId[]'].length;
    selectedCountry='';
    for(i=0;i<totalCountryId;i++) {
        if (document.employeeDetailsForm.elements['countryId[]'][i].selected == true) {
            if (selectedCountry != '') {
                selectedCountry += ',';
            }
            selectedCountry += document.employeeDetailsForm.elements['countryId[]'][i].value;
        }
    }
    myQueryString3 += '&countryId='+selectedCountry;
    
    //institute
    totalInstituteId = document.employeeDetailsForm.elements['instituteId[]'].length;
    selectedInstitute='';
    for(i=0;i<totalInstituteId;i++) {
        if (document.employeeDetailsForm.elements['instituteId[]'][i].selected == true) {
            if (selectedInstitute != '') {
                selectedInstitute += ',';
            }
            selectedInstitute += document.employeeDetailsForm.elements['instituteId[]'][i].value;
        }
    } 
    myQueryString3 += '&instituteId='+selectedInstitute;

    //myQueryString3 += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    
    //showHide("hideAll");
    document.getElementById('emp_academic1').style.display='none';
    document.getElementById('emp_address1').style.display='none';
    document.getElementById('emp_miscEmployee1').style.display='none';
    document.getElementById('emp_miscEmployee2').style.display='none';
    
    document.getElementById('emp_academic').innerHTML='Show';
    document.getElementById('emp_address').innerHTML='Show';
    document.getElementById('emp_miscEmployee').innerHTML='Show';

	//clears the array
     var len=selEmployee.length;
     selEmployee.splice(0,len);
     document.getElementById('selectedEmp').value='';
    
    //show employee list
    showEmployeeList(myQueryString3);
    

          
    
}

//show employee list
function showEmployeeList(queryString){
    document.getElementById('totalEmployeesRecordsId').innerHTML='<b>0</b>';
    document.getElementById('sendToAllEmployeeChk').checked=false; 
    document.getElementById('sendToAllEmployeeChk').style.display='none'; 
    document.getElementById('employeeLabel').style.display='none'; 
   
    document.getElementById('employeeSummeryDiv').innerHTML='';
     
    getEmployeeList(queryString);
    

    if(j.employeeInfo.length>0){
     allEmployeeId=j.employeeInfo; //all employeeIds
     document.getElementById('sendToAllEmployeeChk').style.display=''; 
     document.getElementById('employeeLabel').style.display=''; 
     
     //clears the array
     var len=selEmployee.length;
     selEmployee.splice(0,len);
     document.getElementById('selectedEmp').value='';
     
     //add to the array
     var cnt= j.employeeInfo.length;
     for(i=0;i<cnt;i++){
      if(j.employeeInfo[i].empAssigned=='Yes'){   
        selEmployee.push(j.employeeInfo[i].employeeId);
      }
     }
     document.getElementById('selectedEmp').value=selEmployee; //assign to the hidden variable
     
     document.getElementById('employeeSummeryDiv').innerHTML=' [ Assigned to <b>'+selEmployee.length+'</b> employess]';
    }
    else{
       document.getElementById('employeeSummeryDiv').innerHTML='  [Assigned to <b>0</b> employees]';
   }
    
    var a=listObj2.totalRecords;
    document.getElementById('totalEmployeesRecordsId').innerHTML='<b>'+a+'</b>';  
    
    hide_div('showList2',1);
    if((document.listFrm2.employees.length - 2) > 0){
       document.getElementById('divButton3').style.display='block';
     }
    else{
           document.getElementById('divButton3').style.display='none';
     } 
}


function getEmployeeList(queryString){
  var url = '<?php echo HTTP_LIB_PATH;?>/ScAssignSurvey/scAjaxEmployeeList.php'; 
  
  var tableColumns = new Array(
                         new Array('srNo','#','width="1%"','',false),
                         new Array('emps','<input type=\"checkbox\" id=\"employeeList\" name=\"employeeList\" onclick=\"selectEmployees();\">','width="2%" align=\"left\"',false), 
                         new Array('employeeName','Name','width="14%"',true),
                         new Array('employeeCode','Emp. Code','width="7%"',true),
                         new Array('empAssigned','Assg.','width="5%"',true)
                         /* ,
                         new Array('designationName','Designation.','width="10%"',true),
                         new Array('branchCode','Branch','width="7%"',true),
                         new Array('roleName','Role','width="5%"',true),
                         new Array('qualification','Qual.','width="12%"',true),
                         new Array('dateOfJoining','DOJ','width="6%" align="left"',true)
                         */
                         );


 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'employeeDetailsForm','employeeName','ASC','employeeSearchDiv','','',true,'listObj2',tableColumns,'','','&surveyId='+document.getElementById('surveyId').value);
 sendRequest(url, listObj2, queryString,false); 
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


//master function for parent
var selParent=new Array();
function checkUncheckParent(value,state){
    var c1=selParent.length;  
    if(state==true){
      var xx = true;  
      for(var i = 0; i < c1; i++) 
       {
         if(value==selParent[i]){
             xx=false;
             break;
         }
     }
    if(xx == true){selParent.push(value);} 
   }
   else{
      for(var i = 0; i < c1; i++) 
       {
         if(value==selParent[i]){
             selParent.splice(i,1);
             break;
         }     
     }
        
   }
   document.getElementById('selectedParent').value=selParent; //assign to the hidden variable
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


//used when user clicks on "Assign to all parents"
function sdaParent(state){
    selParent.splice(0,selParent.length); //empty the array   
    if(state==true){
       var len=allParentId.length; 
       for(var k=0;k<len;k++){ 
        //push into array
        selParent.push(allParentId[k]['studentId']);   
      }
   } 
   document.getElementById('selectedParent').value=selParent; //assign to the hidden variable
   
    if(!chkObject2('parents')){
     document.listFrm1.parents.checked =state;
     return true;  
    }
    
    formx = document.listFrm1; 
    var l=formx.parents.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.parents[ i ].checked=state;

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







//fetch list of assigned surveys
function getAssignedSurveyList(value){
 if(value==''){
     return false;
 } 
  
  //clear summery divs and arrays
  document.getElementById('studentSummeryDiv').innerHTML='';    
  document.getElementById('selectedStudent').value='';
  selStudent.splice(0,selStudent.length);
  
  document.getElementById('parentSummeryDiv').innerHTML='';    
  document.getElementById('selectedParent').value='';
  selParent.splice(0,selParent.length);
  
  document.getElementById('employeeSummeryDiv').innerHTML='';
  document.getElementById('selectedEmp').value='';
  selEmployee.splice(0,selEmployee.length);
  
  //display assigned list
  showStudentList();  
  showParentList();  
  showEmployeeList();
 
}
function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//used to validate "selected students" 
function validateStudentForm() {
 if(document.getElementById('surveyId').value==''){
    messageBox("<?php echo SELECT_SURVEY; ?>");  
    document.getElementById('surveyId').focus();
    return false;
 }
if((document.listFrm.students.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }     
else{
    if(confirm('You have selected '+selStudent.length+' students \n Save now?'))
     assignSurveyStudent(); //assign the survey
     return false;
    }
}

//used to validate "selected parents"   
function validateParentForm() {
 if(document.getElementById('surveyId').value==''){
    messageBox("<?php echo SELECT_SURVEY; ?>");  
    document.getElementById('surveyId').focus();
    return false;
}
if((document.listFrm1.parents.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }     
else{
    if(confirm('You have selected '+selParent.length+' parents \n Save now?'))
     assignSurveyParent(); //assign the survey
     return false;
    }   
}


//used to validate "selected employees"
function validateEmployeeForm() {
 if(document.getElementById('surveyId').value==''){
    messageBox("<?php echo SELECT_SURVEY; ?>");  
    document.getElementById('surveyId').focus();
    return false;
 }

if((document.listFrm2.employees.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }     
else{
    if(confirm('You have selected '+selEmployee.length+' employees \n Save now?'))
     assignSurveyEmployee(); //assign the survey
     return false;
    }                                      
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO assign survey to students
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function assignSurveyStudent() {
         url = '<?php echo HTTP_LIB_PATH;?>/ScAssignSurvey/scAjaxAssignStudent.php';
         
         var student='';
         var m=selStudent.length;
         for(var k=0 ; k < m ; k++){ 
                if(student==""){
                    student= selStudent[ k ];
                }
               else{
                    student+="," + selStudent[ k ]; 
               }
         }
          

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {surveyId: (document.getElementById('surveyId').value), 
             student: (student)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                       flag = true;
                       document.getElementById('studentSummeryDiv').innerHTML=' [ Assigned to <b>'+m+'</b> students]';
                       messageBox("<?php echo SURVEY_ASSIGNED_OK; ?>");     
                     }
                     else if("<?php echo SURVEY_DEASSINED_STUDENT;?>" == trim(transport.responseText)) {                     
                       flag = true;
                       document.getElementById('studentSummeryDiv').innerHTML=' [ Assigned to <b>0</b> students]';
                       messageBox("<?php echo SURVEY_DEASSINED_STUDENT; ?>");     
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                    resetForm1(); //it is not called because there is paging
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO assign survey to parents
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function assignSurveyParent() {
         url = '<?php echo HTTP_LIB_PATH;?>/ScAssignSurvey/scAjaxAssignParent.php';
         
         var parent='';
         var m=selParent.length;
         for(var k=0 ; k < m ; k++){ 
                if(parent==""){
                    parent= selParent[ k ];
                }
               else{
                    parent+="," + selParent[ k ]; 
               }
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {surveyId: (document.getElementById('surveyId').value), 
             parent: (parent)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                       document.getElementById('parentSummeryDiv').innerHTML=' [ Assigned to <b>'+m+'</b> parents]';
                       messageBox("<?php echo SURVEY_ASSIGNED_PARENT_OK; ?>");     
                     } 
                     else if("<?php echo SURVEY_DEASSINED_PARENT;?>" == trim(transport.responseText)) {                     
                       document.getElementById('parentSummeryDiv').innerHTML=' [ Assigned to <b>0</b> parents]';
                       messageBox("<?php echo SURVEY_DEASSINED_PARENT; ?>");     
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                    resetForm2(); //it is not called because there is paging
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO assign survey to employees
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function assignSurveyEmployee() {
         url = '<?php echo HTTP_LIB_PATH;?>/ScAssignSurvey/scAjaxAssignEmployee.php';
         var employee='';
         var m=selEmployee.length;
         for(var k=0 ; k < m ; k++){ 
                if(employee==""){
                    employee= selEmployee[ k ];
                }
               else{
                    employee+="," + selEmployee[ k ]; 
               }
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {surveyId: (document.getElementById('surveyId').value), 
             employee: (employee)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                       document.getElementById('employeeSummeryDiv').innerHTML=' [ Assigned to <b>'+m+'</b> employees]';
                       messageBox("<?php echo SURVEY_ASSIGNED_EMPLOYEE_OK; ?>");     
                     } 
                     else if("<?php echo SURVEY_DEASSINED_EMPLOYEE;?>" == trim(transport.responseText)) {                     
                       document.getElementById('employeeSummeryDiv').innerHTML=' [ Assigned to <b>0</b> employees]';
                       messageBox("<?php echo SURVEY_DEASSINED_EMPLOYEE; ?>");     
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                    selEmployee.splice(0,m); //empty the array 
                    resetForm3(); //it is not called because there is paging
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:To reset form after data submission
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm1(){
 document.getElementById('divButton1').style.display='none';
 document.getElementById('studentSearchDiv').innerHTML="";
 hide_div('showList',2); 
}

function resetForm2(){
 document.getElementById('divButton2').style.display='none';
 document.getElementById('parentSearchDiv').innerHTML="";
 hide_div('showList1',2); 
}

function resetForm3(){
 document.getElementById('divButton3').style.display='none';
 document.getElementById('employeeSearchDiv').innerHTML="";
 hide_div('showList2',2); 
}

window.onload=function(){
    document.getElementById('surveyId').focus();
    var roll = document.getElementById("rollNo");
    autoSuggest(roll);
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/ScAssignSurvey/scListAssignSurveyContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listAssignSurvey.php $ 
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:49
//Updated in $/LeapCC/Interface
//Bug fixing.
//bug ids---
//00000256,00000257,00000259,00000261,00000263,00000264.
//00000266,00000269,00000262
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 23/06/09   Time: 14:46
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids----
//00000187,00000191,00000198,00000199,00000203,00000204,
//00000205,00000207,0000209,00000211
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 14:41
//Created in $/LeapCC/Interface
//Copied "Assign Survey" module from Leap to LeapCC
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/24/09    Time: 11:59a
//Updated in $/Leap/Source/Interface
//fixed bugs in survey assign for student, parent, employee can not
//delete the record if exist in another table
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 19/01/09   Time: 12:00
//Updated in $/Leap/Source/Interface
//Corrected Colspan and common filters
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 13/01/09   Time: 12:29
//Updated in $/Leap/Source/Interface
//Fixed bugs related to "Assign Survey" module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/01/09    Time: 18:34
//Updated in $/Leap/Source/Interface
//Corrected spelling mistake and javascript logic
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/01/09    Time: 13:26
//Updated in $/Leap/Source/Interface
//Crrected pagination related problem
//[maintained checkbox state in pagination]
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 5/01/09    Time: 18:12
//Updated in $/Leap/Source/Interface
//Corrected Access Codes
?>