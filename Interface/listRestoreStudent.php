<?php
//---------------------------------------------------------------------------
//  THIS FILE used for restore students
//
// Author : Dipanjan Bhattacharjee
// Created on : (06.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RestoreStudentMaster');  
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Restore Students</title>
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

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="2%"','',false),
 new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%"','align=\"left\"',false), 
 new Array('studentName','Name','width="15%"','',true),
 new Array('rollNo','Roll No.','width="10%"','',true) ,
 new Array('regNo','Regn. No.','width="10%"','',true) ,
 new Array('universityRollNo','Univ. Roll No.','width="12%"','',true) ,
 new Array('className','Class','width="15%"','',true) ,
 new Array('dateOfBirth','DOB','width="8%"','align="center"',true),
 //new Array('fatherName','Father','width="20%"','',true)
 new Array('restoreClass','Restore To Class','width="20%"','',false)
 //new Array('degreeAbbr','Degree','width="8%"','',true) ,
 //new Array('branchCode','Branch','width="8%"','',true) ,
 //new Array('periodName','Study Period','width="8%"','',true)
 );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/RestoreStudent/ajaxStudentList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
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
  obj = document.listFrm.elements[id];
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
     return true;  
    }
    formx = document.listFrm; 
    var l=formx.students.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.students[ i ].checked=state;
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



//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (06.11.2008)
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

function validateAddForm() {
   

    myQueryString = '';
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
    myQueryString += '&rollNo='+form.rollNo.value;
    myQueryString += '&studentName='+form.studentName.value;


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

    myQueryString += '&degreeId='+selectedDegrees;

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

    myQueryString += '&branchId='+selectedBranches;

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

    myQueryString += '&periodicityId='+selectedPeriodicity;
    
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

    myQueryString += '&subjectId='+selectedSubjectId;

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

    myQueryString += '&groupId='+selectedGroupId;

    //from date of admission
    fromDateA = form.admissionMonthF.value+'-'+form.admissionDateF.value+'-'+form.admissionYearF.value;
    myQueryString += '&fromDateA='+fromDateA;

    
    //to date of admission
    toDateA = form.admissionMonthT.value+'-'+form.admissionDateT.value+'-'+form.admissionYearT.value;
    myQueryString += '&toDateA='+toDateA;

    //from date of birth
    fromDateD = form.birthMonthF.value+'-'+form.birthDateF.value+'-'+form.birthYearF.value;
    myQueryString += '&fromDateD='+fromDateD;

    //to date of birth
    toDateD = form.birthMonthT.value+'-'+form.birthDateT.value+'-'+form.birthYearT.value;
    myQueryString += '&toDateD='+toDateD;

    //gender + mgmt. category + quota
    myQueryString += '&gender='+form.gender.value;
    myQueryString += '&categoryId='+form.categoryId.value;
    myQueryString += '&quotaId='+form.quotaId.value;


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

    myQueryString += '&hostelId='+selectedHostel;

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

    myQueryString += '&busStopId='+selectedBusStop;


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
    myQueryString += '&busRouteId='+selectedBusRoute;


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
    myQueryString += '&cityId='+selectedCity;

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
    myQueryString += '&stateId='+selectedState;

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
    myQueryString += '&countryId='+selectedCountry;
    
   
    
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
    myQueryString += '&universityId='+selectedUniversity;

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
    myQueryString += '&attendanceFrom='+document.getElementById('attendanceFrom').value;
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
    myQueryString += '&attendanceTo='+document.getElementById('attendanceTo').value;

	////////////////////////////////////////////////////////////////////////////



    queryString = 'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&fromDateA='+fromDateA+'&toDateA='+toDateA+'&fromDateD='+fromDateD+'&toDateD='+toDateD;
    myQueryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    showHide("hideAll");

    sendReq(listURL,divResultName,searchFormName, queryString,false);
    hide_div('showList',1);
    if((document.listFrm.students.length - 2) > 0){
       document.getElementById('divButton').style.display='block';
       document.getElementById('printTr').style.display='';
     }
    else{
           document.getElementById('divButton').style.display='none';
           document.getElementById('printTr').style.display='none';
     } 
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
// Created on : (06.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateForm() {

if((document.listFrm.students.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }     
else if(!(checkStudents())){  //checkes whether any student/parent checkboxes selected or not
     alert("<?php echo RESTORE_STUDENT_SELECT_ALERT; ?>");      
     document.getElementById('studentList').focus();
     return false;
  } 
else{
     restoreStudents(); //sends the message
     return false;
  }  
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function restoreStudents() {
         var url = '<?php echo HTTP_LIB_PATH;?>/RestoreStudent/ajaxRestoreStudent.php';
         
         if(false===confirm("<?php echo RESTORE_CONFIRM;?>")) {
             return false;
         }
         
         //determines which student and parents are selected and their studentIds
         formx = document.listFrm; 
         var student="";  //get studentIds when student checkboxes are selected
         
        if((document.listFrm.students.length - 2)<=1){
           student=(document.listFrm.students[2].checked ? document.listFrm.students[2].value+'~'+document.getElementById('restoreClass_'+document.listFrm.students[2].value).value : "0" );   
         }
        else{ 
         var m=formx.students.length;
         for(var k=2 ; k < m ; k++){ //started from 2 for two dummy fields.
            if(formx.students[ k ].checked==true){
                if(student==""){
                    student= formx.students[ k ].value+'~'+document.getElementById('restoreClass_'+formx.students[ k ].value).value;
                }
               else{
                    student+="," + formx.students[ k ].value+'~'+document.getElementById('restoreClass_'+formx.students[ k ].value).value; 
               } 
            }
         }
        }  

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
             studentIds: (student)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                       messageBox("<?php echo RESTORE_OK; ?>\nYou need to re-assign groups to these student(s).");     
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                    resetForm(); //it is not called because there is paging
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
function resetForm(){
 document.getElementById('divButton').style.display='none';
 document.getElementById('results').innerHTML="";
 document.getElementById('printTr').style.display='none';
 document.getElementById('rollNo').focus();   
 }
 
/* function to print Restore Student report*/
function printReport() { 
    var form = document.allDetailsForm;
    var qstr=generateQueryString('allDetailsForm');
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    qstr += '&rollNo='+form.rollNo.value;
    qstr += '&studentName='+form.studentName.value;
    qstr += '&gender='+form.gender.options[form.gender.selectedIndex].text;
    if(form.degreeId.selectedIndex>0){
     qstr += '&degsText='+form.degreeId.options[form.degreeId.selectedIndex].text;
    }
    if(form.branchId.selectedIndex>0){
     qstr += '&branText='+form.branchId.options[form.branchId.selectedIndex].text;
    }
    if(form.courseId.selectedIndex>0){
     qstr += '&courseText='+form.courseId.options[form.courseId.selectedIndex].text;
    }
    if(form.groupId.selectedIndex>0){
     qstr += '&groupText='+form.groupId.options[form.groupId.selectedIndex].text;
    }
    
    var path='<?php echo UI_HTTP_PATH;?>/restoreStudentReportPrint.php?'+qstr;
    window.open(path,"RestoreStudentReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr=generateQueryString('allDetailsForm');
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    qstr += '&rollNo='+form.rollNo.value;
    qstr += '&studentName='+form.studentName.value;
    qstr += '&gender='+form.gender.options[form.gender.selectedIndex].text;
    if(form.degreeId.selectedIndex>0){
     qstr += '&degsText='+form.degreeId.options[form.degreeId.selectedIndex].text;
    }
    if(form.branchId.selectedIndex>0){
     qstr += '&branText='+form.branchId.options[form.branchId.selectedIndex].text;
    }
    if(form.courseId.selectedIndex>0){
     qstr += '&courseText='+form.courseId.options[form.courseId.selectedIndex].text;
    }
    if(form.groupId.selectedIndex>0){
     qstr += '&groupText='+form.groupId.options[form.groupId.selectedIndex].text;
    }
    window.location='restoreStudentReportCSV.php?'+qstr;
} 

window.onload=function(){
 document.getElementById('rollNo').focus();  
 var roll = document.getElementById("rollNo");
 autoSuggest(roll); 
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/RestoreStudent/listRestoreStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listRestoreStudent.php $ 
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 27/08/09   Time: 11:34
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//00001283,00001294,00001297
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:15
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000739,0000740,0000746,0000747,0000748,0000752
//
//*****************  Version 4  *****************
//User: Administrator Date: 24/07/09   Time: 14:57
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids----0000648,0000650,0000667,0000651,0000676,0000649,0000652
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 23/12/08   Time: 11:47
//Updated in $/LeapCC/Interface
//Added subject and group dropdown in student filter
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/03/08   Time: 6:46p
//Created in $/LeapCC/Interface
//Created quarantine/delete and restore students modules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/21/08   Time: 10:24a
//Updated in $/Leap/Source/Interface
//Added Course wise search functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:41p
//Created in $/Leap/Source/Interface
//Created "Restore Student" Module
?>