<?php
//-------------------------------------------------------
// This File contains Validation and ajax function used in student performance report Form
// Author :Dipanjan
// Created on : 28.04.2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAttendancePerformanceReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Attendance Performance Report </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentAcademicPerformanceReport.php';
var divResultName = 'resultsDiv';

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

function validateForm() {
	//hideResults();
    var form=document.studentPerformanceReportForm;
    if(form.timeTable.value==''){
        messageBox("Please select a time table");
        form.timeTable.focus();
        return false;
    }
    if(form.degree.value==''){
        messageBox("Please select a degree");
        form.degree.focus();
        return false;
    }
    if(form.subjectId.value==''){
        messageBox("Please select a subject");
        form.subjectId.focus();
        return false;
    }
    
    if(trim(form.rangeText.value)==''){
        messageBox("Enter range");
        form.rangeText.focus();
        return false;
    }
    
    var rangeText=trim(document.getElementById('rangeText').value);
    var tR=rangeText.split(',');
    var len1=tR.length;
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_ATTENDANCE_RANGE; ?>");
            document.getElementById('rangeText').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isDecimal(trim(tRange[k]))){
               messageBox("<?php echo ENTER_NUMERIC_VALUE_FOR_ATTENDANCE_RANGE; ?>");
               document.getElementById('rangeText').focus();
               return false;
            }
        }
    }

    
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	
    var pars = 'timeTable='+form.timeTable.value+'&classId='+form.degree.value+'&subjectId='+form.subjectId.value+'&attendanceRange='+trim(form.rangeText.value);
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxAttendancePerformanceReport.php';
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
            document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
    
    
}

function getLabelClass(){

	var form = document.studentPerformanceReportForm;
    document.studentPerformanceReportForm.degree.length = 1; 
	var timeTable = form.timeTable.value;
    if(timeTable==''){
        return false;
    }
	 
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClass.php';
	var pars = 'timeTable='+timeTable;
	
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
				len = j.length;
				for(i=0;i<len;i++) {
					addOption(document.studentPerformanceReportForm.degree, j[i].classId, j[i].className);
				}
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function getSubjects(){

    var form = document.studentPerformanceReportForm;
    var timeTable = form.timeTable.value;
    var classId  = form.degree.value;
    form.subjectId.options.length=1;
    if(timeTable=='' || classId=='' ){
        return false;
    }
     
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassAttendanceSubjects.php';
    var pars = 'timeTable='+timeTable+'&degree='+classId;
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
                var len = j.length;
                for(i=0;i<len;i++) {
                    addOption(form.subjectId, j[i].subjectId, j[i].subjectCode);
                }
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}


/* function to print Test Marks Distribution report*/
function printReport() {
    
   //hideResults();
    var form=document.studentPerformanceReportForm;
    if(form.timeTable.value==''){
        messageBox("Please select a time table");
        form.timeTable.focus();
        return false;
    }
    if(form.degree.value==''){
        messageBox("Please select a degree");
        form.degree.focus();
        return false;
    }
    if(form.subjectId.value==''){
        messageBox("Please select a subject");
        form.subjectId.focus();
        return false;
    }
    
    if(trim(form.rangeText.value)==''){
        messageBox("Enter range");
        form.rangeText.focus();
        return false;
    }
    
    var rangeText=trim(document.getElementById('rangeText').value);
    var tR=rangeText.split(',');
    var len1=tR.length;
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        var len2=tRange.length;
        if(len2!=2){
            messageBox("<?php echo INVALID_ATTENDANCE_RANGE; ?>");
            document.getElementById('rangeText').focus();
            return false;
        }
        for(var k=0;k<len2;k++){
            if(!isDecimal(trim(tRange[k]))){
               messageBox("<?php echo ENTER_NUMERIC_VALUE_FOR_ATTENDANCE_RANGE; ?>");
               document.getElementById('rangeText').focus();
               return false;
            }
        }
    }
    
    var timeTableName=form.timeTable.options[form.timeTable.selectedIndex].text;
    var className=form.degree.options[form.degree.selectedIndex].text;
    var subjectName=form.subjectId.options[form.subjectId.selectedIndex].text;
    var qstr = 'timeTable='+form.timeTable.value+'&classId='+form.degree.value+'&subjectId='+form.subjectId.value+'&attendanceRange='+trim(form.rangeText.value);
    var path='<?php echo UI_HTTP_PATH;?>/attendancePerformanceReportPrint.php?'+qstr+'&timeTableName='+timeTableName+'&subjectName='+subjectName+'&className='+className;
    window.open(path,"AttendancePerformanceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

window.onload=function(){
   getLabelClass();
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentAttendancePerformanceReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
////$History: studentAcademicPerformanceReport.php $
?>