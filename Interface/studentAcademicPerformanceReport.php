<?php

//-------------------------------------------------------
//  This File contains Validation and ajax function used in student performance report Form
//
//
// Author :Jaineesh
// Created on : 29-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAcademicPerformanceReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Academic Performance Report (Pre Transfer)</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

var tableHeadArray = new Array(	new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
								new Array('srNo','#','width="2%"','',false), 
								new Array('rollNo','Roll No.','width=20% align=left','align=left',true), 
								new Array('universityRollNo','University Roll No.','width="20%" align=left','align=left',true), 
								new Array('studentName','Student Name','width="50%" align="left"','align="left"',true));

 //This function Validates Form

var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentAcademicPerformanceReport.php';
var divResultName = 'resultsDiv';

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'studentPerformanceReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
	//hideResults();
    var form=document.studentPerformanceReportForm;
    if(form.timeTable.selectedIndex<1){
        messageBox("Please select a time table");
        form.timeTable.focus();
        return false;
    }
    if(form.degree.selectedIndex<1){
        messageBox("Please select a degree");
        form.degree.focus();
        return false;
    }
    
    if(document.studentPerformanceReportForm.incDetained[1].checked==false) {
          
          if(!isNumeric(form.incTheory.value)) {
            messageBox("Enter integer value for theory");
            form.incTheory.focus();
            return false;
          } 
          if(!isNumeric(form.incPractical.value)) {
            messageBox("Enter integer value for practical");
            form.incPractical.focus();
            return false;
          } 
          if(!isNumeric(form.incTraining.value)) {
            messageBox("Enter integer value for training");
            form.incTraining.focus();
            return false;
          }
          if(trim(form.incTheory.value)=="" ) {
            messageBox("Enter value for theory");
            form.incTheory.focus();
            return false;
          }
          if(trim(form.incPractical.value)==""){
            messageBox("Enter value for practical");
            form.incPractical.focus();
            return false;
          }
          if(trim(form.incTraining.value)==""){
            messageBox("Enter value for training");
            form.incTraining.focus();
            return false;
          }  
    }
    
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

	//showReport();
}


function getLabelClass(){

	form = document.studentPerformanceReportForm;
	var timeTable = form.timeTable.value;
    if(timeTable==''){
        return false;
    }
	 
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClass.php';
	var pars = 'timeTable='+timeTable;
	document.studentPerformanceReportForm.degree.length = 1; 
	//addOption(document.studentPerformanceReportForm.degree, '', '');
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
				var j = eval('(' + transport.responseText + ')');
				len = j.length;

				if (len > 0) {
					//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) {
					addOption(document.studentPerformanceReportForm.degree, j[i].classId, j[i].className);
				}
				// now select the value
				//document.studentPerformanceReportForm.degree.value = j[0].classId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}

function doAll() {
	
	formx = document.studentPerformanceReportForm;
	if(formx.checkbox2.checked){
		for(var i=1;i<formx.length;i++){
			if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {
				formx.elements[i].checked=true;
			}
		}
	}
	else {
		for(var i=1;i<formx.length;i++){
			if(formx.elements[i].type=="checkbox") {
				formx.elements[i].checked=false;
			}
		}
	}
}

function checkAll(){
    formx = document.studentPerformanceReportForm;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && formx.elements[i].checked==true) {
            return 1;
          }
    }
   return 0;     
}

function printReport() {
    form = document.studentPerformanceReportForm;
    if(form.timeTable.selectedIndex<1){
        messageBox("Please select a time table");
        form.timeTable.focus();
        return false;
    }
    
    if(form.degree.selectedIndex<0){
        messageBox("Please select a degree");
        form.degree.focus();
        return false;
    }
    
    if(!checkAll()){
        messageBox("Please select atleast one record!");
        form.checkbox2.focus();
        return false;
    }
	//var rollNo = form.rollNo.value;
	//var pars = 'rollNo='+rollNo;
	var pars = generateQueryString('studentPerformanceReportForm');

	path='<?php echo UI_HTTP_PATH;?>/studentAcademicPerformanceReportPrint.php?'+pars+'&timeTabelLabelName='+form.timeTable.options[form.timeTable.selectedIndex].text;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900");
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

window.onload=function(){
   checkDetainedList(); 
   getLabelClass();
}

function checkDetainedList(){
    
    document.getElementById("incTheory").value=<?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD'); ?>;  
    document.getElementById("incPractical").value=<?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>; 
    document.getElementById("incTraining").value=<?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>; 
    
    if(document.studentPerformanceReportForm.incDetained[1].checked==true) {
       document.getElementById("incTheory").disabled=true;  
       document.getElementById("incPractical").disabled=true; 
       document.getElementById("incTraining").disabled=true;       
    }
    else {
       document.getElementById("incTheory").disabled=false;  
       document.getElementById("incPractical").disabled=false; 
       document.getElementById("incTraining").disabled=false; 
    }
}


function setSignature() {
   document.getElementById('signatureContents').value='';
   document.getElementById('signatureHide').style.display='none'; 
   if(document.studentPerformanceReportForm.signatureChk[1].checked) {
     document.getElementById('signatureHide').style.display=''; 
   } 
}
</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentAcademicPerformanceReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php

////$History: studentAcademicPerformanceReport.php $
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 25/11/09   Time: 11:56
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002123,0002122
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 20/11/09   Time: 14:57
//Updated in $/LeapCC/Interface
//Modified "Student Academic Performance Report" and added "Detained
//Student" facility
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/19/09   Time: 5:31p
//Updated in $/LeapCC/Interface
//Detained Student checkbox added 
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 13/11/09   Time: 10:36
//Updated in $/LeapCC/Interface
//Fixed issue related to "sorting"
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 23/10/09   Time: 11:23
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001864
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/10/09    Time: 11:21
//Updated in $/LeapCC/Interface
//Added link for "Student Academic Performance Report" and added access
//parameters
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 28/08/09   Time: 19:06
//Updated in $/LeapCC/Interface
//Created  "Student Academic Performance Report" module
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/24/09    Time: 10:34a
//Created in $/LeapCC/Interface
//new file for student academic performance report
//
//
?>