<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherAttendanceReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Teacher Attendance Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                               new Array('srNo','#','width="2%"','',false), 
                               new Array('className','Class','width="20%"','',true) , 
                               new Array('groupName','Group','width="8%"','',true) , 
                               new Array('subjectCode','Subject Code','width="10%"','',true), 
                               new Array('subjectName','Subject Name','width="15%"','',true), 
                               new Array('employeeName','Teacher','width="14%"','',true) , 
                               new Array('attendanceTaken','Delivered','width="6%"','align="right"',true),
                               new Array('adjustmentTaken','Adjustment','width="6%"','align="right"',true),
                               new Array('totalDelivered','TotDelivered','width="6%"','align="right"',true)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxTeacherAttendanceReport.php';
searchFormName = 'teacherAttendanceReportForm'; // name of the form which will be used for search
/*
  addFormName    = 'AddCity';   
  editFormName   = 'EditCity';
  winLayerWidth  = 315; //  add/edit form width
  winLayerHeight = 250; // add/edit form height
  deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var serverDate="<?php echo date('Y-m-d');?>";

function validateData(){
   if(document.getElementById('labelId').value==''){
     messageBox("<?php echo SELECT_TIME_TABLE_LABEL;?>");
     document.getElementById('labelId').focus();
     return false;
   }
   if(document.getElementById('classId').value==''){
     messageBox("<?php echo SELECT_CLASS;?>");
     document.getElementById('classId').focus();
     return false;
   }
   if(document.getElementById('employeeId').value==''){
     messageBox("Select one teacher or all");
     document.getElementById('employeeId').focus();
     return false;
   }
   if(!dateDifference(document.getElementById('fromDate').value,serverDate,'-')){
       messageBox("From date cannot be greater than current date");
       document.getElementById('fromDate').focus();
       return false;
   }
   if(!dateDifference(document.getElementById('toDate').value,serverDate,'-')){
       messageBox("To date cannot be greater than current date");
       document.getElementById('toDate').focus();
       return false;
   }
   if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-')){
       messageBox("To date cannot be greater than from date");
       document.getElementById('toDate').focus();
       return false;
   }
   
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
    document.getElementById('printRowId').style.display='';
   
}

function hideResults(){
    document.getElementById('results').innerHTML='';
    document.getElementById('printRowId').style.display='none';
}

function getTimeTableClasses() {
    hideResults();
    var form = document.teacherAttendanceReportForm;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
    var pars = 'labelId='+form.labelId.value;
    
    form.classId.options.length = 1;
    form.employeeId.options.length = 1;
    getTimeTableDates(); 
    
    document.getElementById('showHierarchy').style.display='none';  
    var chkHierarchy =0;
    if(form.groupId.value!='-1') {
      document.getElementById('showHierarchy').style.display='';    
      if(document.teacherAttendanceReportForm.chkHierarchy.checked) {
        chkHierarchy =1;
      }
    }
    
    if (form.labelId.value=='') {
        return false;
    }
    
    
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            
            for(i=0;i<len;i++) {
                addOption(form.classId, j[i].classId, j[i].className);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


function getTimeTableDates() {
    var form = document.teacherAttendanceReportForm;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxGetTimeTableDates.php';
    var pars = 'labelId='+form.labelId.value;
    if (form.labelId.value=='') {
        return false;
    }
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
        asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            
            if(j.startDate!=''){ 
             document.getElementById('fromDate').value=j.startDate;
            }
            /*
            if(j.endDate!=''){ 
             document.getElementById('toDate').value=j.endDate;
            }
            */
            document.getElementById('toDate').value=serverDate;
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


function getClassTeacher() {
    hideResults();
    var form = document.teacherAttendanceReportForm;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxGetTimeTableClassTeachers.php';
    
    document.getElementById('showHierarchy').style.display='none';  
    var chkHierarchy =0;
    if(form.groupId.value!='-1') {
      document.getElementById('showHierarchy').style.display='';    
      if(document.teacherAttendanceReportForm.chkHierarchy.checked) {
        chkHierarchy =1;
      }
    }
    
    var pars = 'labelId='+form.labelId.value+'&classId='+form.classId.value+'&groupId='+form.groupId.value+'&chkHierarchy='+chkHierarchy;
    form.employeeId.options.length = 1;
    if (form.labelId.value=='' || form.classId.value=='' || form.groupId.value=='') {
        return false;
    }
    
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:false,  
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            
            for(i=0;i<len;i++) {
                addOption(form.employeeId, j[i].employeeId, j[i].employeeName);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


function getGroups() {
    hideResults();
   
    var form = document.teacherAttendanceReportForm;    
    
    var chkHierarchy =0;
    if(form.groupId.value!='-1') {
      if(document.teacherAttendanceReportForm.chkHierarchy.checked) {
        chkHierarchy =1;
      }
    }
   
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassAttendanceGroups.php';
    var pars = 'timeTable='+form.labelId.value+'&degree='+form.classId.value+'&employeeId='+form.employeeId.value+'&subjectId=-1&chkHierarchy='+chkHierarchy;
    form.groupId.options.length = 1;
    if (form.labelId.value=='' || form.classId.value=='') {
        return false;
    }
    
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:false,  
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            
            for(i=0;i<len;i++) {
                addOption(form.groupId, j[i].groupId, j[i].groupName);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
    
}



function printReport() {
    
    var chkHierarchy =0;
    if(document.teacherAttendanceReportForm.groupId.value!='-1') {
      if(document.teacherAttendanceReportForm.chkHierarchy.checked) {
        chkHierarchy =1;
      }
    }
    
    var path='<?php echo UI_HTTP_PATH;?>/teacherAttendanceReportPrint.php?labelId='+document.getElementById('labelId').value+'&classId='+document.getElementById('classId').value+'&employeeId='+document.getElementById('employeeId').value+'&fromDate='+document.getElementById('fromDate').value+'&toDate='+document.getElementById('toDate').value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path +='&labelName='+document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text;
    path +='&className='+document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    path +='&employeeName='+document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text;
    path +='&groupName='+document.getElementById('groupId').options[document.getElementById('groupId').selectedIndex].text;
    path +='&groupId='+document.getElementById('groupId').value;
    path +='&chkHierarchy='+chkHierarchy;
    window.open(path,"TeacherAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var chkHierarchy =0;
    if(document.teacherAttendanceReportForm.groupId.value!='-1') {
      if(document.teacherAttendanceReportForm.chkHierarchy.checked) {
        chkHierarchy =1;
      }
    }
    var path='<?php echo UI_HTTP_PATH;?>/teacherAttendanceReportCSV.php?labelId='+document.getElementById('labelId').value+'&classId='+document.getElementById('classId').value+'&employeeId='+document.getElementById('employeeId').value+'&fromDate='+document.getElementById('fromDate').value+'&toDate='+document.getElementById('toDate').value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&groupId='+document.getElementById('groupId').value+'&chkHierarchy='+chkHierarchy;
    window.location = path;
}

window.onload=function(){
  getTimeTableClasses();  
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/StudentReports/listTeacherAttendanceReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listTeacherAttendanceReport.php $ 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/04/10   Time: 10:21
//Created in $/LeapCC/Interface
//Created "Teacher Attendance Report".This report is used to see total
//lectured delivered by a teacher for a subject within a specified date
//interval.
?>