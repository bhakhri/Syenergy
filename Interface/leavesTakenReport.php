<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeLeavesTakenReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Leaves Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                   new Array('srNo','#','width="2%"','',false), 
                                   new Array('employeeCode','Employee Code','width="10%"','',true) , 
                                   new Array('employeeName','Employee Name','width="15%"','',true), 
                                   new Array('leaveTypeName','Leave Type','width="10%"','',true) , 
                                   new Array('leaveFromDate','From','width="10%"','align="center"',true) , 
                                   new Array('leaveToDate','To','width="10%"','align="center"',true) , 
                                   new Array('noOfDays','Days','width="5%"','align="right"',true),
                                   new Array('leaveStatus','Status','width="10%"','align="left"',true)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/LeaveReports/ajaxLeavesTakenList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'employeeCode';
sortOrderBy    = 'ASC';

function inputValidataion(){
    if(document.getElementById('fromDate').value!='' && document.getElementById('toDate').value==''){
     messageBox("Enter to date");
     document.getElementById('toDate').focus();
     return false;
  }
  if(document.getElementById('toDate').value!='' && document.getElementById('fromDate').value==''){
     messageBox("Enter from date");
     document.getElementById('fromDate').focus();
     return false;
  }
  if(document.getElementById('leaveSessionId').value!=-1){
     if(document.getElementById('fromDate').value!=''){
         var y=document.getElementById('fromDate').value.split('-')[0];
         if(y!=document.getElementById('leaveSessionId').value){
             messageBox("from date and to date should exist in leave session year");
             document.getElementById('fromDate').focus();
             return false;
         }
     }
     if(document.getElementById('toDate').value!=''){
         var y=document.getElementById('toDate').value.split('-')[0];
         if(y!=document.getElementById('leaveSessionId').value){
             messageBox("from date and to date should exist in leave session year");
             document.getElementById('toDate').focus();
             return false;
         }
     }
  }
  if(document.getElementById('fromDate').value!='' && document.getElementById('toDate').value!=''){
      if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-')){
          messageBox("To date can not be smaller than from date");
          document.getElementById('toDate').focus();
          return false;
      }
  }
  return true;
}

function generateReport(){
  
  if(!inputValidataion()){
      return false;
  }
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
  document.getElementById('printTrId').style.display='';
}


function vanishData(){
    document.getElementById('results').innerHTML='';
    document.getElementById('printTrId').style.display='none';
}

function togglePendingStatus(val){
    if(val==1){
        document.getElementById('pendingStatus').value=-1;
        document.getElementById('pendingStatus').disabled=true;
    }
    else{
        document.getElementById('pendingStatus').value=-1;
        document.getElementById('pendingStatus').disabled=false; 
    }
    vanishData();
}


function printReport() {
    if(!inputValidataion()){
      return false;
    }
    var yearName=document.getElementById('leaveSessionId').options[document.getElementById('leaveSessionId').selectedIndex].text;
    var employeeName=document.getElementById('employeeDD').options[document.getElementById('employeeDD').selectedIndex].text;
    var leaveStatus=document.searchForm.leaveStatus[0].checked==true?1:0;
    
    var str ='';
    if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {
       var pendingStatus=document.getElementById('pendingStatus').value;
       var pendingStatusName=document.getElementById('pendingStatus').options[document.getElementById('pendingStatus').selectedIndex].text;
       str = '&pendingStatusName='+pendingStatusName+'&pendingStatus='+pendingStatus;
    }
    
    var fromDate=document.getElementById('fromDate').value;
    var toDate=document.getElementById('toDate').value;
    var path='<?php echo UI_HTTP_PATH;?>/leavesTakenReportPrint.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&leaveSessionId='+document.getElementById('leaveSessionId').value+'&employeeDD='+document.getElementById('employeeDD').value+'&yearName='+yearName+'&employeeName='+employeeName+'&leaveStatus='+leaveStatus+'&fromDate='+fromDate+'&toDate='+toDate+str;
    window.open(path,"LeaveTakenReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    if(!inputValidataion()){
      return false;
    }
    var yearName=document.getElementById('leaveSessionId').options[document.getElementById('leaveSessionId').selectedIndex].text;
    var employeeName=document.getElementById('employeeDD').options[document.getElementById('employeeDD').selectedIndex].text;
    var leaveStatus=document.searchForm.leaveStatus[0].checked==true?1:0;
    
    var str ='';
    if(document.getElementById('hiddenLeaveAuthorizersId').value==2) {
       var pendingStatus=document.getElementById('pendingStatus').value;
       var pendingStatusName=document.getElementById('pendingStatus').options[document.getElementById('pendingStatus').selectedIndex].text;
       str = '&pendingStatusName='+pendingStatusName+'&pendingStatus='+pendingStatus;
    }
   
    var fromDate=document.getElementById('fromDate').value;
    var toDate=document.getElementById('toDate').value;
    var path='<?php echo UI_HTTP_PATH;?>/leavesTakenReportCSV.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&leaveSessionId='+document.getElementById('leaveSessionId').value+'&employeeDD='+document.getElementById('employeeDD').value+'&yearName='+yearName+'&employeeName='+employeeName+'&leaveStatus='+leaveStatus+'&fromDate='+fromDate+'&toDate='+toDate+str;
    window.location = path;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/LeaveReports/leavesTakenReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listCity.php $ 
?>