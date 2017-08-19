<?php 
//-------------------------------------------------------------------
// This File contains the show details of Student Internal Subject Re-appear detail report  
// Author :Parveen Sharma
// Created on : 19-01-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayStudentReappearReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Display Student Internal Re-appear Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>

<script language="javascript">

//This function Validates Form 
var tableHeadArray = new Array(
     new Array('srNo','#','width="2%"','',false), 
     new Array('studentName','Student Name','width="12%"','align="left" valign="middle" ',true),  
     new Array('currentClassName','Current Class Name','width="20%"','align="left" valign="middle" ',true),  
     new Array('rollNo','Roll No.','width="8%"','align="left" valign="middle" ',true),  
     new Array('universityRollNo','Univ. Roll No.','width="12%"','align="left" valign="middle" ',true),  
     new Array('reappearClassName','Re-appear Class Name','width="20%','align="left" valign="middle" ',true),  
     new Array('subjects','Re-appear<br>Subject Code/Status','width="15%','align="left" valign="middle" ',false));


var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxStudentInternalReappear.php'; 
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'Name';
sortOrderBy    = 'Asc';

queryString1 = "";
queryString = "";
studentIdds = "";

var initialTextForMultiDropDowns='Click to select multiple ';
var selectTextForMultiDropDowns='items';
window.onload=function(){
   //document.listForm.subject.value='';
   makeDDHide('reappearClassId','d2','d3');
   makeDDHide('subjectId','d22','d33');
   makeDDHide('reappearStatusId','d222','d333');
   //getSubjectList();
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function validateAddForm() {

   studentIdds = ""; 
   queryString = "";    
   queryString1 = ""; 
   
   closeTargetDiv('d1','containerDiv');
   closeTargetDiv('d11','containerDiv');
   closeTargetDiv('d111','containerDiv');   
   
   if(trim(document.getElementById('reappearClassId').value)=="") {
      messageBox("<?php echo SELECT_CLASS ?>");
      //document.getElementById('reappearClassId').focus();
      popupMultiSelectDiv('reappearClassId','d1','containerDiv','d3');
      return false;
   }
   
   if(!isEmpty(document.getElementById('startDate').value)) {
       if(isEmpty(document.getElementById('endDate').value)) {
        messageBox("<?php echo EMPTY_DATE_TO;?>");  
        document.getElementById('endDate').focus();
        return false;
       }  
   }    
   
    if(!isEmpty(document.getElementById('endDate').value)) {
       if(isEmpty(document.getElementById('startDate').value)) {
        messageBox("<?php echo EMPTY_DATE_FROM;?>");  
        document.getElementById('endDate').focus();
        return false;
       }   
    }  
   
   if(!isEmpty(document.getElementById('startDate').value) && !isEmpty(document.getElementById('endDate').value)) { 
     if(!dateDifference(eval("document.getElementById('startDate').value"),eval("document.getElementById('endDate').value"),'-') ) {
        messageBox ("<?php echo DATE_CONDITION;?>");
        eval("document.getElementById('startDate').focus();");
        return false;
     } 
   }
      
   hideResults();   
   //queryString  = "&classId="+document.getElementById('reappearClassId').value+"&rollNo="+document.getElementById('rollNo').value;
   //queryString += "&startDate="+document.getElementById('startDate').value+"&endDate="+document.getElementById('endDate').value;
   //queryString += "&subjectId="+document.getElementById('subjectId').value;
   queryString = generateQueryString('allDetailsForm');
   
   if(document.getElementById('attendanceChk').checked==true) {
     queryString1 = "&attendanceChk="+document.getElementById('attendanceChk').value
   }
   if(document.getElementById('midSemesterChk').checked==true) {
     queryString1 = "&midSemesterChk="+document.getElementById('midSemesterChk').value    
   }
   if(document.getElementById('assignmentChk').checked==true) {
     queryString1 = "&assignmentChk="+document.getElementById('assignmentChk').value    
   }
          
   closeTargetDiv('d1','containerDiv');
   closeTargetDiv('d11','containerDiv');
   closeTargetDiv('d111','containerDiv');   
   
   
   sendReq(listURL,divResultName,searchFormName,queryString1,true);
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
    
   return false;
}


function getSubjectList() {
     groupUrl = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxInitReappearSubject.php';

     document.allDetailsForm.subjectId.length = null;
     //addOption(document.allDetailsForm.subjectId, '', 'Select');
     
     if(document.getElementById('reappearClassId').value=='') {
        //messageBox("<?php echo EMPTY_DATE_FROM;?>");  
        return false;
     }  
     
     pars = generateQueryString('allDetailsForm');
     
     new Ajax.Request(groupUrl,
     {
       method:'post',
       asynchronous:false,
       parameters: pars,
       onCreate: function(){
         showWaitDialog(true);
       },
       onSuccess: function(transport){
          hideWaitDialog(true);
          j = eval('('+ transport.responseText+')');
          len = j.length;
          document.allDetailsForm.subjectId.length = null;
          //addOption(document.allDetailsForm.subjectId, '', 'Select');
          for(i=0;i<len;i++) { 
            addOption(document.allDetailsForm.subjectId, j[i].subjectId, j[i].subjectCode);
          }
       },
       onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
       });
}

function printReport() {
   
   if(queryString=='')  {
     messageBox("<?php echo SELECT_CLASS ?>");   
     return false;  
   }
   var path='<?php echo UI_HTTP_PATH;?>/displayReappearReport.php?'+queryString+queryString1;
   var a=window.open(path,"StudentInternalReappearReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {

   if(queryString=='')  {
     messageBox("<?php echo SELECT_CLASS ?>");    
     return false;  
   }
   var path='<?php echo UI_HTTP_PATH;?>/displayReappearReportCSV.php?'+queryString+queryString1; 
   window.location = path;
}
</script> 
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentReappearReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
//History: displayStudentInternalReappear.php $
//
//

?>