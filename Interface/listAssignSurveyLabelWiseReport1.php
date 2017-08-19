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
define('MODULE','ADVFB_AssignSurveyMasterLabelWiseReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

$blank_string='None';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Label Wise Survey Report (Advanced)</title>
<style type="text/css">
a.whiteClass:hover{
    color:#FFFFFF;
}
</style>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//echo UtilityManager::includeCSS2(); 
//echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE ;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


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
   
    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE;?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    
    if(document.getElementById('labelId').value==''){
        messageBox("Select label");
        document.getElementById('labelId').focus();
        return false;
    }
    
    myQueryString = '';
   
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
    //show student list
    getStudentList(myQueryString,'studentName','ASC',1);
    
}



//This function Validates Form 
function validateEmployeeForm(frm) {
    myQueryString3 = '';
    
    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE;?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    
    if(document.getElementById('labelId').value==''){
        messageBox("Select label");
        document.getElementById('labelId').focus();
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
    
    document.getElementById('emp_academic').innerHTML='Expand';
    document.getElementById('emp_address').innerHTML='Expand';
    document.getElementById('emp_miscEmployee').innerHTML='Expand';

    //show employee list
    showEmployeeList(myQueryString3);
}

function getStudentList(queryString,mySortBy,mySortOrder,myPage){
  document.getElementById('printRowId1').style.display='none';  
  var studentListUrl = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxStudentFeedbackLabelWiseReport1.php'; 
  var tableColumns = new Array(
                         new Array('srNo','#','width="1%"','',false),
                         //new Array('feedbackSurveyLabel','Label','width="4%"',true),
                         new Array('studentName','Name','width="12%"',true),
                         new Array('rollNo','Roll No.','width="6%"',true) ,
                         new Array('universityRollNo','Univ. Roll No.','width="7%"',true) ,
                         new Array('className','Class','width="15%"',true),
                         new Array('isCompleted','Completed','width="5%"',true),
                         new Array('dateOver','Date Over','width="6%" align="center"',true),
                         new Array('visibleFrom','Visible From','width="8%" align="center"',true),
                         new Array('visibleTo','Visible To','width="8%" align="center"',true),
                         new Array('extendTo','Extend To','width="8%" align="center"',true),
                         new Array('currentStatus','Status','width="8%"',true),
                         new Array('actionString','Action','width="5%" align="center"',false)
                      );
                      
  var frmName='allDetailsForm';
  if(mySortBy=='' && mySortOrder=='' && myPage==''){
      mySortOrder='ASC';
      mySortBy='studentName';
      myPage=1;
  }

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(studentListUrl,recordsPerPage,linksPerPage,myPage,frmName,mySortBy,mySortOrder,'studentResultsDiv','','',true,'listObj',tableColumns,'','','&timeTableLabelId='+document.getElementById('timeTableLabelId').value+'&labelId='+document.getElementById('labelId').value+'&type=1'+'&typeOf='+document.getElementById('comptype').value);
 sendRequest(studentListUrl, listObj, queryString+'&page='+myPage+'&sortField='+mySortBy+'&sortOrderBy='+mySortOrder,false);
 document.getElementById('printRowId1').style.display='';
 

   if(listObj.totalRecords>0){
     document.getElementById('blockAllTdId').style.display='';
   }
}


function showEmployeeList(queryString){
  document.getElementById('printRowId2').style.display='none';  
  var empListUrl = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxStudentFeedbackLabelWiseReport1.php'; 
  
  var tableColumns = new Array(
                         new Array('srNo','#','width="1%"','',false),
                         new Array('feedbackSurveyLabel','Label','width="4%"',true),
                         new Array('employeeCode','Emp. Code','width="10%"',true),
                         new Array('employeeName','Name','width="20%"',true) ,
                         new Array('isCompleted','Completed','width="5%"',true)
                      );
                      
  var frmName='employeeDetailsForm';

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(empListUrl,recordsPerPage,linksPerPage,page,frmName,'employeeCode','ASC','employeeResultsDiv','','',true,'listObj2',tableColumns,'','','&timeTableLabelId='+document.getElementById('timeTableLabelId').value+'&labelId='+document.getElementById('labelId').value+'&type=2');
 sendRequest(empListUrl, listObj2, queryString,false);
 document.getElementById('printRowId2').style.display='';
}


function fetchMappedSurveyLabels(val) {
         vanishData();
         var labelId=document.getElementById('labelId');
         labelId.options.length=1;
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

var applicableToFlag=0;
function fetchMappedCategories(val1,val2) {
         applicableToFlag=0;
         document.getElementById('applicableToTDId').innerHTML="<?php echo $blank_string;?>";
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
                       if(applicableToFlag==2){
                          document.getElementById('employeeTRId').style.display=''; 
                       }
                       else if(applicableToFlag==4){
                          document.getElementById('studentTRId').style.display='';     
                       }
                       else{
                           messageBox("This label is not supported");
                           return false;
                       }
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function vanishData(){
  document.getElementById('printRowId1').style.display='none';
  document.getElementById('printRowId2').style.display='none';
  document.getElementById('studentTRId').style.display='none';
  document.getElementById('employeeTRId').style.display='none';
  document.getElementById('studentResultsDiv').innerHTML='';
  document.getElementById('employeeResultsDiv').innerHTML='';
  document.getElementById('blockAllTdId').style.display='none';
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

function changeStatus(userId,mode){
   if(userId.toString()=='' || mode.toString()==''){
       messageBox('Invalid operation'+userId+'--'+mode);
       return false;
   }
   if(mode=="<?php echo FEEDBACK_STUDENT_FORCED_UNBLOCKED;?>"){
       //open div for reason
       document.unblockFrm.userId.value=userId;
       document.unblockFrm.mode.value=mode;
       document.unblockFrm.reason.value='';
       displayWindow('studentUnBlockDiv',320,250);
       document.unblockFrm.reason.focus();
   }
   else{
      doChangeStatus(userId,mode,''); 
   }
}

function validateUnblockForm(){
    if(trim(document.unblockFrm.reason.value)==''){
        messageBox("Enter reason for unblocking");
        document.unblockFrm.reason.focus();
        return false;
    }
    doChangeStatus(document.unblockFrm.userId.value,document.unblockFrm.mode.value,trim(document.unblockFrm.reason.value));
}

function doChangeStatus(userId,mode,reason){
    if(userId.toString()=='' || mode.toString()==''){
       messageBox('Invalid operation');
       return false;
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/changeStudentStatus1.php';
    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 userId  :  userId, 
                 mode    :  mode,
                 reason  :  reason ,
                 labelId : document.getElementById('labelId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                       if(mode=="<?php echo FEEDBACK_STUDENT_FORCED_UNBLOCKED;?>"){  
                         hiddenFloatingDiv('studentUnBlockDiv'); 
                         messageBox("This student is unblocked now");
                       }
                       else{
                         messageBox("This student is blocked now");   
                       }
                       getStudentList('',listObj.sortField,listObj.sortOrderBy,listObj.page);
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

function blockAllStudents(){
    
    if(!confirm("Block All Students.\nAre you sure?")){
      return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/blockAllStudents1.php';
    new Ajax.Request(url,
           {
             method:'post',
             parameters: generateQueryString('allDetailsForm')+'&timeTableLabelId='+document.getElementById('timeTableLabelId').value+'&labelId='+document.getElementById('labelId').value+'&type=1',
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                    messageBox("Students are blocked");   
                    getStudentList('',listObj.sortField,listObj.sortOrderBy,listObj.page);
                 }
                 else {
                    messageBox(trim(transport.responseText));
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    }); 
}


function unBlockStudent(){
    document.allUnblockFrm.reason.value='';
    displayWindow('studentAllUnBlockDiv',320,250);
    document.allUnblockFrm.reason.focus();
}
function unBlockAllStudents(){
   
    if(!confirm("All Students will be unblocked.\nAre you sure?")){
      return false;
    }
    
    var reason=trim(document.allUnblockFrm.reason.value);
    if(reason==''){
        messageBox("Enter reason for unblocking");
        document.allUnblockFrm.reason.focus();
        return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/unBlockAllStudents1.php';
    new Ajax.Request(url,
           {
             method:'post',
             parameters: generateQueryString('allDetailsForm')+'&timeTableLabelId='+document.getElementById('timeTableLabelId').value+'&labelId='+document.getElementById('labelId').value+'&type=1'+'&reason='+reason,
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                    messageBox("All Students are unblocked");
                    hiddenFloatingDiv('studentAllUnBlockDiv');
                    getStudentList('',listObj.sortField,listObj.sortOrderBy,listObj.page);
                 }
                 else {
                    messageBox(trim(transport.responseText));
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    }); 
}

/* function to print report*/
function printReport() {
   
   if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE;?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    
   if(document.getElementById('labelId').value==''){
        messageBox("Select label");
        document.getElementById('labelId').focus();
        return false;
   }
     
   if(applicableToFlag==4){ 
    var qstr=generateQueryString('allDetailsForm');
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    var type=1;
   }
   else if(applicableToFlag==2){
      var qstr=generateQueryString('employeeDetailsForm');
      qstr=qstr+"&sortOrderBy="+listObj2.sortOrderBy+"&sortField="+listObj2.sortField;
      var type=2;
   }
   else{
     messageBox("This label is not supported");
     return false;
   }
   
   path='<?php echo UI_HTTP_PATH;?>/assignSurveyLabelWiseReportPrint1.php?'+qstr+'&timeTableLabelId='+document.getElementById('timeTableLabelId').value+'&labelId='+document.getElementById('labelId').value+'&type='+type+'&typeOf='+document.getElementById('comptype').value;
   window.open(path,"AssignSurveyReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE;?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    
   if(document.getElementById('labelId').value==''){
        messageBox("Select label");
        document.getElementById('labelId').focus();
        return false;
   }
     
   if(applicableToFlag==4){ 
    var qstr=generateQueryString('allDetailsForm');
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    var type=1;
   }
   else if(applicableToFlag==2){
      var qstr=generateQueryString('employeeDetailsForm');
      qstr=qstr+"&sortOrderBy="+listObj2.sortOrderBy+"&sortField="+listObj2.sortField;
      var type=2;
   }
   else{
     messageBox("This label is not supported");
     return false;
   }
   
   window.location='assignSurveyLabelWiseReportCSV1.php?'+qstr+'&timeTableLabelId='+document.getElementById('timeTableLabelId').value+'&labelId='+document.getElementById('labelId').value+'&type='+type+'&typeOf='+document.getElementById('comptype').value;
}


window.onload=function(){
   //makeDDHide('selectedSubjectId','studentSubjectD2','studentSubjectD3');
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listAssignSurveyLabelWiseReportsContents1.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php                              
// $History: listAssignSurveyReport.php $ 
?>
