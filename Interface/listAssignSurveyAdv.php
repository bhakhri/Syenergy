<?php
//---------------------------------------------------------------------------
// THIS FILE is used for assigning survey to emps/student/parents
// Author : Dipanjan Bhattacharjee
// Created on : (13.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_AssignSurveyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

$blank_string='None';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Assign Survey(Advanced)</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//echo UtilityManager::includeCSS2(); 
//echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">
var topPos = 0;
var leftPos = 0;
</script>
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE ;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all student checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectCheckBoxes(id){
    var state=document.getElementById(id).checked;
    
    if(id=='employeeList'){
      targetDiv='employeeResultsDiv';
      chkName='employees';
    }
    else if(id=='parentList'){
       targetDiv='parentResultsDiv';
       chkName='parents'; 
    }
    else{
      targetDiv='studentResultsDiv';
      chkName='students';  
    }

    var c1 = document.getElementById(targetDiv).getElementsByTagName('INPUT');
    var l=c1.length;
    for(var i=0 ;i < l ; i++){
        if(c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name==chkName){
            c1[i].checked=state;
           //push into array
           if(chkName=='employees'){
             checkUncheckEmployee(c1[i].value,state);
           }
           else if(chkName=='parents'){
             checkUncheckParent(c1[i].value,state);  
           }
           else{
             checkUncheckStudent(c1[i].value,state);  
           }
        }
    }
}

//to determine whether any checkboxes are selected or not in the target div
function checkAtleastOneCheckBoxChecked(targetDiv,chkName){
    var c1 = document.getElementById(targetDiv).getElementsByTagName('INPUT');
    var l=c1.length;
    var chkFlag=0;
    for(var i=0 ;i < l ; i++){
        if(c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name==chkName && c1[i].checked==true){
            chkFlag=1;
            break;
        }
    }
    return chkFlag; 
}
//-----------------------------------------------------
//THIS FUNCTION IS USED FOR HELP
//
//Author :Mridula
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
	 if(document.getElementById('helpChk').checked == false) {
		 return false;
	 }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){
    if(mode==2){
     document.getElementById(id).style.display='none';
    }
    else{
        document.getElementById(id).style.display='';
    }
}

//This function Validates Form 
var myQueryString;
var allStudentId;

function validateCommonStudentList() {
   
    myQueryString = '';
    if(!validateHeaderPart()){
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
    showHide("hideAll");
    var len=selStudent.length;
    selStudent.splice(0,len);
    document.getElementById('selectedStudent').value='';
    if(feedBackTypeFlag==4){
     document.getElementById('selectedStudent2').value='';
    }
    //show student list
    showStudentList(myQueryString);
    
    
}

function validateSubjectStudentList() {
    if(!validateHeaderPart()){
           return false;
    }
    
    if(document.getElementById('selectedClassId').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('selectedClassId').focus();
        return false;
    }
    
    if(document.getElementById('selectedSubjectId').value==''){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('selectedSubjectId').focus();
        return false;
    }
    
    var len=selStudent.length;
    selStudent.splice(0,len);
    document.getElementById('selectedStudent').value='';
    if(feedBackTypeFlag==4){
     document.getElementById('selectedStudent2').value='';
     closeTargetDiv('studentSubjectD1','studentSubjectContainerDiv');
    }
    //show student list
    showStudentList(myQueryString);
}

//show student list
var allStudentListArray=new Array();
function showStudentList(queryString){
    document.getElementById('totalStudentsRecordsId').innerHTML='<b>0</b>';
    document.getElementById('sendToAllStudentChk').checked=false; 
    document.getElementById('sendToAllStudentChk').style.display='none'; 
    document.getElementById('studentLabel').style.display='none'; 
    document.getElementById('studentSummeryDiv').innerHTML='';
    document.getElementById('studentDivButton').style.display='none';
    
    allStudentListArray.splice(0,allStudentListArray.length);
    
    getStudentList(queryString);
     
    if(j.studentInfo.length>0){
     allStudentId=j.studentInfo; //all studentIds
     document.getElementById('sendToAllStudentChk').style.display=''; 
     document.getElementById('studentLabel').style.display=''; 
     document.getElementById('studentDivButton').style.display='';
     
     //clears the array
     var len=selStudent.length;
     selStudent.splice(0,len);
     document.getElementById('selectedStudent').value='';
     if(feedBackTypeFlag==4){
      document.getElementById('selectedStudent2').value='';
     }
     
     //add to the array
     var cnt= j.studentInfo.length;
     for(i=0;i<cnt;i++){
      if(j.studentInfo[i].studentAssigned=='1'){   
        selStudent.push(j.studentInfo[i].userId);
      }
      allStudentListArray.push(j.studentInfo[i].userId);
     }
     document.getElementById('selectedStudent').value=selStudent; //assign to the hidden variable
     if(feedBackTypeFlag==4){
      document.getElementById('selectedStudent2').value=selStudent;
     }
     
      document.getElementById('studentSummeryDiv').innerHTML=' [ Assigned to <b>'+selStudent.length+'</b>  students]';
    }
    else{
      document.getElementById('studentSummeryDiv').innerHTML='  [Assigned to <b>0</b> students]';
   }
    
    var a=listObj.totalRecords;
    document.getElementById('totalStudentsRecordsId').innerHTML='<b>'+a+'</b>'; 
    
   
    hide_div('studentDisplayRowId',1);
    hide_div('studentShowList',1);
    /*
    if((document.listFrm.students.length - 2) > 0){
       document.getElementById('divButton1').style.display='block';
     }
    else{
           document.getElementById('divButton1').style.display='none';
     }
    */  
}

function getStudentList(queryString){
  var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAssignedSurveyList.php'; 
  
  var tableColumns = new Array(
                         new Array('srNo','#','width="1%"','',false),
                         new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectCheckBoxes(this.id);\">','width="3%" align="left"',false), 
                         new Array('studentName','Name','width="25%"',true),
                         //new Array('studentAssigned','Assg.','width="5%"',true),
                         new Array('rollNo','Roll No.','width="15%"',true) ,
                         new Array('degreeAbbr','Degree','width="12%"',true) ,
                         new Array('branchCode','Branch','width="12%"',true) ,
                         new Array('periodName','Study Period','width="12%"',true) 
                      );
                      
  var timeTableLabelId=document.getElementById('timeTableLabelId').value;
  var labelId=document.getElementById('labelId').value;
  var catId=document.getElementById('catId').value;
  var questionSetId=document.getElementById('questionSetId').value;
  var fbFlag=feedBackTypeFlag;
  
  var qstr='&timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&catId='+catId+'&questionSetId='+questionSetId+'&fbFlag='+fbFlag;
  
  var frmName='allDetailsForm';
  if(fbFlag==4){
      var frmName='studentSubjectDetailsForm';
  }
  

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,frmName,'studentName','ASC','studentResultsDiv','','',true,'listObj',tableColumns,'','',qstr+'&type=1');
 sendRequest(url, listObj, queryString,false); 
}



/*This function Validates Form*/ 
var myQueryString3;
var allEmployeeId;

//This function Validates Form 
function validateEmployeeList(frm) {
    myQueryString3 = '';
    
    if(!validateHeaderPart()){
       return false;
    }

    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) || !isEmpty(document.employeeDetailsForm.birthMonthF.value) || !isEmpty(document.employeeDetailsForm.birthDateF.value)){
        if(isEmpty(document.employeeDetailsForm.birthYearF.value)){
           messageBox("Please select date of birth year");
           document.employeeDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthMonthF.value)){
           messageBox("Please select date of birth month");
           document.employeeDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthDateF.value)){
           messageBox("Please select date of birth date");
           document.employeeDetailsForm.birthDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.birthYearT.value) || !isEmpty(document.employeeDetailsForm.birthMonthT.value) || !isEmpty(document.employeeDetailsForm.birthDateT.value)){
        if(isEmpty(document.employeeDetailsForm.birthYearT.value)){
           messageBox("Please select date of birth year");
           document.employeeDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthMonthT.value)){
           messageBox("Please select date of birth month");
           document.employeeDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.birthDateT.value)){
           messageBox("Please select date of birth date");
           document.employeeDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) && !isEmpty(document.employeeDetailsForm.birthMonthF.value) && !isEmpty(document.employeeDetailsForm.birthDateF.value) && !isEmpty(document.employeeDetailsForm.birthYearT.value) && !isEmpty(document.employeeDetailsForm.birthMonthT.value) && !isEmpty(document.employeeDetailsForm.birthDateT.value)){
        dobFValue = document.employeeDetailsForm.birthYearF.value+"-"+document.employeeDetailsForm.birthMonthF.value+"-"+document.employeeDetailsForm.birthDateF.value
        dobTValue = document.employeeDetailsForm.birthYearT.value+"-"+document.employeeDetailsForm.birthMonthT.value+"-"+document.employeeDetailsForm.birthDateT.value
        if(dateCompare(dobFValue,dobTValue)==1){
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.employeeDetailsForm.birthYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.birthYearF.value) && !isEmpty(document.employeeDetailsForm.birthMonthF.value) && !isEmpty(document.employeeDetailsForm.birthDateF.value) && !isEmpty(document.employeeDetailsForm.birthYearT.value) && !isEmpty(document.employeeDetailsForm.birthMonthT.value) && !isEmpty(document.employeeDetailsForm.birthDateT.value)){
        dobFValue = document.employeeDetailsForm.birthYearF.value+"-"+document.employeeDetailsForm.birthMonthF.value+"-"+document.employeeDetailsForm.birthDateF.value
        dobTValue = document.employeeDetailsForm.birthYearT.value+"-"+document.employeeDetailsForm.birthMonthT.value+"-"+document.employeeDetailsForm.birthDateT.value
        if(dateCompare(dobFValue,dobTValue)==1){
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.employeeDetailsForm.birthYearF.focus();
           return false;
        }
    }
    // Joining Date                                
    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) || !isEmpty(document.employeeDetailsForm.joiningMonthF.value) || !isEmpty(document.employeeDetailsForm.joiningDateF.value)){
        if(isEmpty(document.employeeDetailsForm.joiningYearF.value)){
           messageBox("Please select date of joining year");
           document.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningMonthF.value)){
           messageBox("Please select date of joining month");
           document.employeeDetailsForm.joiningMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningDateF.value)){
           messageBox("Please select date of joining date");
           document.employeeDetailsForm.joiningDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.joiningYearT.value) || !isEmpty(document.employeeDetailsForm.joiningMonthT.value) || !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
        if(isEmpty(document.employeeDetailsForm.joiningYearT.value)){
           messageBox("Please select date of joining year");
           document.employeeDetailsForm.joiningYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningMonthT.value)){
           messageBox("Please select date of joining month");
           document.employeeDetailsForm.joiningMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.joiningDateT.value)){
           messageBox("Please select date of joining date");
           document.employeeDetailsForm.joiningDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) && !isEmpty(document.employeeDetailsForm.joiningMonthF.value) && !isEmpty(document.employeeDetailsForm.joiningDateF.value) && !isEmpty(document.employeeDetailsForm.joiningYearT.value) && !isEmpty(document.employeeDetailsForm.joiningMonthT.value) && !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
        dobFValue = document.employeeDetailsForm.joiningYearF.value+"-"+document.employeeDetailsForm.joiningMonthF.value+"-"+document.employeeDetailsForm.joiningDateF.value
        dobTValue = document.employeeDetailsForm.joiningYearT.value+"-"+document.employeeDetailsForm.joiningMonthT.value+"-"+document.employeeDetailsForm.joiningDateT.value
        if(dateCompare(dobFValue,dobTValue)==1){
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.joiningYearF.value) && !isEmpty(document.employeeDetailsForm.joiningMonthF.value) && !isEmpty(document.employeeDetailsForm.joiningDateF.value) && !isEmpty(document.employeeDetailsForm.joiningYearT.value) && !isEmpty(document.employeeDetailsForm.joiningMonthT.value) && !isEmpty(document.employeeDetailsForm.joiningDateT.value)){
        dobFValue = document.employeeDetailsForm.joiningYearF.value+"-"+document.employeeDetailsForm.joiningMonthF.value+"-"+document.employeeDetailsForm.joiningDateF.value
        dobTValue = document.employeeDetailsForm.joiningYearT.value+"-"+document.employeeDetailsForm.joiningMonthT.value+"-"+document.employeeDetailsForm.joiningDateT.value
        if(dateCompare(dobFValue,dobTValue)==1){
           messageBox("From Date of joining cannot be greater than To Date of joining");
           document.employeeDetailsForm.joiningYearF.focus();
           return false;
        }
    }
    
    // Leaving Date                                
    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) || !isEmpty(document.employeeDetailsForm.leavingMonthF.value) || !isEmpty(document.employeeDetailsForm.leavingDateF.value)){
        if(isEmpty(document.employeeDetailsForm.leavingYearF.value)){
           messageBox("Please select date of leaving year");
           document.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingMonthF.value)){
           messageBox("Please select date of leaving month");
           document.employeeDetailsForm.leavingMonthF.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingDateF.value)){
           messageBox("Please select date of leaving date");
           document.employeeDetailsForm.leavingDateF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.leavingYearT.value) || !isEmpty(document.employeeDetailsForm.leavingMonthT.value) || !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
        if(isEmpty(document.employeeDetailsForm.leavingYearT.value)){
           messageBox("Please select date of leaving year");
           document.employeeDetailsForm.leavingYearT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingMonthT.value)){
           messageBox("Please select date of leaving month");
           document.employeeDetailsForm.leavingMonthT.focus();
           return false;
        }
        if(isEmpty(document.employeeDetailsForm.leavingDateT.value)){
           messageBox("Please select date of leaving date");
           document.employeeDetailsForm.leavingDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) && !isEmpty(document.employeeDetailsForm.leavingMonthF.value) && !isEmpty(document.employeeDetailsForm.leavingDateF.value) && !isEmpty(document.employeeDetailsForm.leavingYearT.value) && !isEmpty(document.employeeDetailsForm.leavingMonthT.value) && !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
        dobFValue = document.employeeDetailsForm.leavingYearF.value+"-"+document.employeeDetailsForm.leavingMonthF.value+"-"+document.employeeDetailsForm.leavingDateF.value
        dobTValue = document.employeeDetailsForm.leavingYearT.value+"-"+document.employeeDetailsForm.leavingMonthT.value+"-"+document.employeeDetailsForm.leavingDateT.value
        if(dateCompare(dobFValue,dobTValue)==1){
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
    if(!isEmpty(document.employeeDetailsForm.leavingYearF.value) && !isEmpty(document.employeeDetailsForm.leavingMonthF.value) && !isEmpty(document.employeeDetailsForm.leavingDateF.value) && !isEmpty(document.employeeDetailsForm.leavingYearT.value) && !isEmpty(document.employeeDetailsForm.leavingMonthT.value) && !isEmpty(document.employeeDetailsForm.leavingDateT.value)){
        dobFValue = document.employeeDetailsForm.leavingYearF.value+"-"+document.employeeDetailsForm.leavingMonthF.value+"-"+document.employeeDetailsForm.leavingDateF.value
        dobTValue = document.employeeDetailsForm.leavingYearT.value+"-"+document.employeeDetailsForm.leavingMonthT.value+"-"+document.employeeDetailsForm.leavingDateT.value
        if(dateCompare(dobFValue,dobTValue)==1){
           messageBox("From Date of leaving cannot be greater than To Date of leaving");
           document.employeeDetailsForm.leavingYearF.focus();
           return false;
        }
    }
    
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
    document.getElementById('empDivButton').style.display='none';
    

    getEmployeeList(queryString);

    if(j.employeeInfo.length>0){
     allEmployeeId=j.employeeInfo; //all employeeIds
     document.getElementById('sendToAllEmployeeChk').style.display=''; 
     document.getElementById('employeeLabel').style.display='';
     document.getElementById('empDivButton').style.display='';
     
     //clears the array
     var len=selEmployee.length;
     selEmployee.splice(0,len);
     document.getElementById('selectedEmp').value='';
     
     //add to the array
     var cnt= j.employeeInfo.length;
     for(i=0;i<cnt;i++){
      if(j.employeeInfo[i].empAssigned=='1'){   
        selEmployee.push(j.employeeInfo[i].userId);
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
    
    hide_div('employeeDisplayRowId',1);
    hide_div('empShowList',1);
    /*
    if(listObj2.totalRecords > 0){
       document.getElementById('divButton3').style.display='block';
     }
    else{
           document.getElementById('divButton3').style.display='none';
     }
     */  
}


function getEmployeeList(queryString){
  var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAssignedSurveyList.php'; 
  var tableColumns = new Array(
                         new Array('srNo','#','width="1%"','',false),
                         new Array('emps','<input type="checkbox" id="employeeList" name="employeeList" onclick="selectCheckBoxes(this.id);\">','width="2%" align=\"left\"',false), 
                         new Array('employeeName','Name','width="50%"',true),
                         new Array('employeeCode','Emp. Code','width="7%"',true),
                         new Array('designationName','Designation','width="12%"',true)
                         //,new Array('empAssigned','Assg.','width="5%"',true)
                         );
  var timeTableLabelId=document.getElementById('timeTableLabelId').value;
  var labelId=document.getElementById('labelId').value;
  var catId=document.getElementById('catId').value;
  var questionSetId=document.getElementById('questionSetId').value;
  
  var qstr='&timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&catId='+catId+'&questionSetId='+questionSetId;
  
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'employeeDetailsForm','employeeName','ASC','employeeResultsDiv','','',true,'listObj2',tableColumns,'','',qstr+'&type=3');
 sendRequest(url, listObj2,' ',false); 
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
   if(feedBackTypeFlag==4){
      document.getElementById('selectedStudent2').value=selStudent;
   }
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
        selStudent.push(allStudentId[k]['userId']);   
      }
   } 
   document.getElementById('selectedStudent').value=selStudent; //assign to the hidden variable
   if(feedBackTypeFlag==4){
      document.getElementById('selectedStudent2').value=selStudent;
   }
   
    var c1 = document.getElementById('studentResultsDiv').getElementsByTagName('INPUT');
    var l=c1.length;
    for(var i=0 ;i < l ; i++){
        if(c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name=='students'){
            c1[i].checked=state;
        }
    }
    
}


//used when user clicks on "Assign to all parents"
function sdaParent(state){
    selParent.splice(0,selParent.length); //empty the array   
    if(state==true){
       var len=allParentId.length; 
       for(var k=0;k<len;k++){ 
        //push into array
        selParent.push(allParentId[k]['userId']);   
      }
   } 
   document.getElementById('selectedParent').value=selParent; //assign to the hidden variable
   
   var c1 = document.getElementById('parentResultsDiv').getElementsByTagName('INPUT');
   var l=c1.length;
   for(var i=0 ;i < l ; i++){
        if(c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name=='parents'){
            c1[i].checked=state;
        }
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
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

//used to validate "selected students" 
function validateStudentForm() {
 if(!validateHeaderPart()){
       return false;
 }
 
 if(feedBackTypeFlag==4){
     if(document.getElementById('selectedClassId').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('selectedClassId').focus();
        return false;
    }
    
    if(document.getElementById('selectedSubjectId').value==''){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('selectedSubjectId').focus();
        return false;
    }
 }

 if(confirm('You have selected '+selStudent.length+' students \n Save now?')){
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO assign survey to students
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function assignSurveyStudent() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAssignStudent.php';
         
         var student='';
         var m=selStudent.length;
         for(var k=0 ; k < m ; k++){
            if(student!=''){
                student +=',';
            } 
            student +=selStudent[ k ]; 
         }
         
         var selectedClassId=-1;
         var selectedSubjectIds=-1;
         if(feedBackTypeFlag==4){
             selectedClassId=document.getElementById('selectedClassId').value;
             var subjectId=document.getElementById('selectedSubjectId');
             var len=subjectId.options.length;
             var subjectString='';
             for(var i=0;i<len;i++){
                 if(subjectId.options[i].selected==true){
                     if(subjectString!=''){
                      subjectString +=' , ';
                     }
                     subjectString +=subjectId.options[i].value;
                 }
             }
            selectedSubjectIds=subjectString; 
         }
         
         var allStudentString='';
         var aCnt=allStudentListArray.length;
         for(var x=0;x<aCnt;x++){
             if(allStudentString!=''){
                 allStudentString +=',';
             }
             allStudentString +=allStudentListArray[x];
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId    : document.getElementById('timeTableLabelId').value,
                 labelId             : document.getElementById('labelId').value, 
                 catId               : document.getElementById('catId').value,
                 questionSetId       : document.getElementById('questionSetId').value,
                 student             : (student),
                 feedBackTypeFlag    : feedBackTypeFlag,
                 selectedClassId     : selectedClassId,
                 selectedSubjectIds  : selectedSubjectIds,
                 allStudentString    : allStudentString
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                       //flag = true;
                       document.getElementById('studentSummeryDiv').innerHTML=' [ Assigned to <b>'+m+'</b> students]';
                       messageBox("<?php echo SURVEY_ASSIGNED_OK; ?>");
                       selStudent.splice(0,m); //empty the array
                     }
                     else if("<?php echo SURVEY_DEASSINED_STUDENT;?>" == trim(transport.responseText)) {                     
                       //flag = true;
                       document.getElementById('studentSummeryDiv').innerHTML=' [ Assigned to <b>0</b> students]';
                       messageBox("<?php echo SURVEY_DEASSINED_STUDENT; ?>");
                       selStudent.splice(0,m); //empty the array
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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



//used when user clicks on "Assign to all employees"
function sdaEmployee(state){
    selEmployee.splice(0,selEmployee.length); //empty the array   
    if(state==true){
       var len=allEmployeeId.length; 
       for(var k=0;k<len;k++){ 
        //push into array
        selEmployee.push(allEmployeeId[k]['userId']);   
      }
   } 
   document.getElementById('selectedEmp').value=selEmployee; //assign to the hidden variable
   
   var c1 = document.getElementById('employeeResultsDiv').getElementsByTagName('INPUT');
   var l=c1.length;
   for(var i=0 ;i < l ; i++){
        if(c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name=='employees'){
            c1[i].checked=state;
        }
    }    
    
}

//used to validate "selected employees"
function validateEmployeeForm() {

 if(!validateHeaderPart()){
       return false;
 }

 if(confirm('You have selected '+selEmployee.length+' employees \n Save now?')){
  assignSurveyEmployee(); //assign the survey
  return false;
 }
 
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO assign survey to employees
//
//Author : Dipanjan Bhattacharjee
// Created on : (30.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function assignSurveyEmployee() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAssignEmployee.php';
         var employee='';
         var m=selEmployee.length;
         for(var k=0 ; k < m ; k++){
            if(employee!=''){
                employee +=',';
            } 
            employee +=selEmployee[ k ]; 
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : document.getElementById('timeTableLabelId').value,
                 labelId          : document.getElementById('labelId').value, 
                 catId            : document.getElementById('catId').value,
                 questionSetId    : document.getElementById('questionSetId').value,
                 employee         : (employee)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                       document.getElementById('employeeSummeryDiv').innerHTML=' [ Assigned to <b>'+m+'</b> employees]';
                       messageBox("<?php echo SURVEY_ASSIGNED_EMPLOYEE_OK; ?>");     
                       selEmployee.splice(0,m); //empty the array 
                     } 
                     else if("<?php echo SURVEY_DEASSINED_EMPLOYEE;?>" == trim(transport.responseText)) {                     
                       document.getElementById('employeeSummeryDiv').innerHTML=' [ Assigned to <b>0</b> employees]';
                       messageBox("<?php echo SURVEY_DEASSINED_EMPLOYEE; ?>");
                       selEmployee.splice(0,m); //empty the array 
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                    
                    resetForm3(); //it is not called because there is paging
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:To reset form after data submission
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm1(){
 hide_div('studentDisplayRowId',2);
 hide_div('studentShowList',2);
}

function resetForm2(){
 document.getElementById('divButton2').style.display='none';
 document.getElementById('parentSearchDiv').innerHTML="";
 hide_div('showList1',2); 
}

function resetForm3(){
 hide_div('employeeDisplayRowId',2);
 hide_div('empShowList',2); 
}

window.onload=function(){
   //makeDDHide('selectedSubjectId','studentSubjectD2','studentSubjectD3');
}



var applicableToFlag=0;
var feedBackTypeFlag=0;
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCity" DIV
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function fetchMappedSurveyLabels(val) {
         applicableToFlag=0;
         feedBackTypeFlag=0;
         var labelId=document.getElementById('labelId');
         labelId.options.length=1;
         document.getElementById('catId').options.length=1;
         document.getElementById('questionSetId').options.length=1;
         
         document.getElementById('applicableToTDId').innerHTML="<?php echo $blank_string;?>";
         document.getElementById('relationshipToTDId').innerHTML="<?php echo $blank_string;?>";
         vanishData();
         
         if(val==''){
             return false;
         } 
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetMappedSurveyLabels.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: val
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   
                   for(var i=0;i<len;i++){
                       addOption(labelId,j[i].feedbackSurveyId,j[i].feedbackSurveyLabel);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function fetchMappedCategories(val1,val2) {
         applicableToFlag=0;
         feedBackTypeFlag=0;
         var cat=document.getElementById('catId');
         cat.options.length=1;
         document.getElementById('questionSetId').options.length=1;
         document.getElementById('applicableToTDId').innerHTML="<?php echo $blank_string;?>";
         document.getElementById('relationshipToTDId').innerHTML="<?php echo $blank_string;?>";
         vanishData();
         
         if(val1=='' || val2==''){
             return false;
         } 
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetMappedSurveyCategories.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : val1,
                 labelId: val2
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   if(len>0){
                       document.getElementById('applicableToTDId').innerHTML='<u>'+j[0]['roleId']+'</u>';
                       applicableToFlag=j[0]['applicableTo'];
                   }
                   for(var i=0;i<len;i++){
                       addOption(cat,j[i].feedbackCategoryId,j[i].feedbackCategoryName);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getCategoryRelation(val) {
         
         document.getElementById('relationshipToTDId').innerHTML="<?php echo $blank_string;?>";
         vanishData();
         
         if(val==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetCategoryRelation.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 catId: val
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        return false;
                   }
                   var ret=trim(transport.responseText).split('!~!~!');
                   document.getElementById('relationshipToTDId').innerHTML='<u>'+ret[0]+'</u>';
                   feedBackTypeFlag=ret[1];
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function fetchMappedQuestionSet(val1,val2,val3) {
         var questionSet=document.getElementById('questionSetId');
         questionSet.options.length=1;
         vanishData();
         
         if(val1=='' || val2=='' || val3==''){
             return false;
         } 
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetMappedQuestionSet.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : val1,
                 labelId: val2,
                 catId :  val3
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   for(var i=0;i<len;i++){
                       addOption(questionSet,j[i].feedbackQuestionSetId,j[i].feedbackQuestionSetName);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function fetchSubjectTypeClass(val1,val2) {
         var classId=document.getElementById('selectedClassId');
         classId.options.length=1;
         document.getElementById('selectedSubjectId').options.length=0;
         
         //to make it show "Click to show...."
         totalSelected('selectedSubjectId','studentSubjectD3');
         closeTargetDiv('studentSubjectD1','studentSubjectContainerDiv');

         if(val1=='' || val2==''){
             return false;
         } 
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetSubjectTypeClass.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : val1,
                 labelId: document.getElementById('labelId').value,   
                 catId :  val2,
                 feedBackTypeFlag: feedBackTypeFlag
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   for(var i=0;i<len;i++){
                       addOption(classId,j[i].classId,j[i].className);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function fetchSubjectTypeSubjects(val1,val2,val3) {
         var subjectId=document.getElementById('selectedSubjectId');
         subjectId.options.length=0;
         
         //to make it show "Click to show...."
         totalSelected('selectedSubjectId','studentSubjectD3');
         closeTargetDiv('studentSubjectD1','studentSubjectContainerDiv');
         
         document.getElementById('studentDisplayRowId').style.display='none';
         document.getElementById('studentShowList').style.display='none';
         
         if(val1=='' || val2=='' || val3==''){
             return false;
         } 
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetSubjectTypeSubject.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : val1,
                 catId :  val2,
                 classId : val3
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   for(var i=0;i<len;i++){
                       addOption(subjectId,j[i].subjectId,j[i].subjectCode);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function validateHeaderPart(){
   if(document.getElementById('timeTableLabelId').value==''){
       messageBox("<?php echo SELECT_TIME_TABLE;?>");
       document.getElementById('timeTableLabelId').focus();
       return false;
   }
   if(document.getElementById('labelId').value==''){
       //messageBox("<?php echo SELECT_ADV_LABEL_NAME;?>");
       messageBox("<?php echo SELECT_ADV_LABEL_NAME2;?>");
       document.getElementById('labelId').focus();
       return false;
   }
   if(document.getElementById('catId').value==''){
       messageBox("<?php echo SELECT_ADV_CAT_NAME;?>");
       document.getElementById('catId').focus();
       return false;
   }
   
   if(document.getElementById('questionSetId').value==''){
       messageBox("<?php echo SELECT_ADV_QUESTION_SET_NAME;?>");
       document.getElementById('questionSetId').focus();
       return false;
   }
   
   return true;
}

function getFilters(value){
   if(value!==''){
      getAssigningFilters(); 
   } 
   else{
    return false;
   }
}
function getAssigningFilters(){

   vanishData();
   
   if(!validateHeaderPart()){
       return false;
   }
   
   if(applicableToFlag==2){
     document.getElementById('employeeSearchFilterRowId').style.display='';
     document.getElementById('employeeDisplayRowId').style.display='';
   } 
   else if(applicableToFlag==3){
     //document.getElementById('parentSearchFilterRowId').style.display='';
   }
   else if(applicableToFlag==4){
      if(feedBackTypeFlag==1){//General
        document.getElementById('studentSearchFilterRowId').style.display='';
        document.getElementById('studentDisplayRowId').style.display='';
      }
      else if(feedBackTypeFlag==2){//Hostel
        //document.getElementById('studentHostelSearchFilterRowId').style.display='';
        //document.getElementById('studentDisplayHostelRowId').style.display='';
        document.getElementById('studentSearchFilterRowId').style.display='';
        document.getElementById('studentDisplayRowId').style.display='';
        showHide('misc',1);
      }
      else if(feedBackTypeFlag==3){//Transport
        //document.getElementById('studentTransportSearchFilterRowId').style.display='';
        //document.getElementById('studentDisplayTransportRowId').style.display='';
        document.getElementById('studentSearchFilterRowId').style.display='';
        document.getElementById('studentDisplayRowId').style.display='';
        showHide('misc',1);
      }
      else if(feedBackTypeFlag==4){//Subject
        document.getElementById('studentSubjectSearchFilterRowId').style.display='';
        document.getElementById('studentDisplayRowId').style.display='';
        
        /*FETCH CLASSES CORRESPONDING TO CATEGORY RELATION*/
         fetchSubjectTypeClass(document.getElementById('timeTableLabelId').value,document.getElementById('catId').value);
        
         makeDDHide('selectedSubjectId','studentSubjectD2','studentSubjectD3');
      }
      else{
          //document.getElementById('studentSearchFilterRowId').style.display='';
      }
   }
   else{
       messageBox("<?php echo INVALID_APPLICABLE_TO; ?>");
   }
}

function vanishData(){
    document.getElementById('empDivButton').style.display='';
    document.getElementById('studentDivButton').style.display='';
    
    document.getElementById('employeeSearchFilterRowId').style.display='none'; 
    document.getElementById('employeeDisplayRowId').style.display='none'; 
    document.getElementById('empShowList').style.display='none'; 
    document.employeeDetailsForm.reset();
   
   //document.getElementById('parentSearchFilterRowId').style.display='none';
   //document.parentDetailsForm.reset();
   
   //student common part
   document.getElementById('studentSearchFilterRowId').style.display='none';
   document.allDetailsForm.reset();
   
   /*
   //student hostel part
   document.getElementById('studentHostelSearchFilterRowId').style.display='none';
   document.studentHostelDetailsForm.reset();

   //student transport part
   document.getElementById('studentTransportSearchFilterRowId').style.display='none';
   document.studentTransportDetailsForm.reset();
   */
   
   //student subject part
   document.getElementById('studentSubjectSearchFilterRowId').style.display='none';
   document.studentSubjectDetailsForm.reset();
   
   //document.studentDetailsForm.reset();   
   document.getElementById('studentDisplayRowId').style.display='none';
   document.getElementById('studentShowList').style.display='none';
}

//overriding functions for multi-select dds
//to select all checkboxes+dropdowns in popuped div
function selectAllCheckBoxes(state,targetDiv,src,topDiv){
   var chkElements = document.getElementById(targetDiv).getElementsByTagName('INPUT');
   var len=chkElements.length;
   for(var i=0;i<len;i++){
    if (chkElements[i].type.toUpperCase()=='CHECKBOX' && chkElements[i].name=='multiSpecialChk'){
       chkElements[i].checked=state;
       document.getElementById(src).options[chkElements[i].value].selected=state;
    }
   }
   totalSelected(src,topDiv,initialTextForMultiDropDowns);//to update the top div
   
   document.getElementById('studentDisplayRowId').style.display='none';
   document.getElementById('studentShowList').style.display='none';
  }
  
  //to select dropdowns in multiselect dds
  function selectDDS(state,index,src,topDiv){
   document.getElementById(src).options[index].selected=state;
   totalSelected(src,topDiv,initialTextForMultiDropDowns);//to update the top div
   
   document.getElementById('studentDisplayRowId').style.display='none';
   document.getElementById('studentShowList').style.display='none';
  }

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listAssignSurveyContentsAdv.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php                              
// $History: listAssignSurveyAdv.php $ 
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/02/10    Time: 13:38
//Updated in $/LeapCC/Interface
//Corrected case of "Library" path
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Interface
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/01/10   Time: 17:06
//Updated in $/LeapCC/Interface
//Made UI changes and modified images
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:22p
//Updated in $/LeapCC/Interface
//Updated breadcrumbs and titles
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:04
//Created in $/LeapCC/Interface
//Created "Assign Survey (Adv)" module
?>