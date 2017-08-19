<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in attendanceMissedReport Form
//
//
// Author :Ajinder Singh
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceStatusReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Last Attendance Taken Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetAttendanceStatusReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'attendanceMissedForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'employeeName';
sortOrderBy    = 'Asc';
 //This function Validates Form 
queryString1 = "";

 function refreshLectureData() {
      queryString1 = ""; 
      var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetAttendanceStatusReport.php';
  
      var dateCheck=0;
      if(document.attendanceMissedForm.showTodayAttendance.checked) {
         dateCheck =1;
      }
      if(dateCheck==0)  {  
          tableColumns = new Array(new Array('srNo','#', 'width="2%"  align="left"',false), 
                                   new Array('employeeName','Teacher','width="20%" align="left"',true),   
                                   new Array('beHalfEmployeeName','On behalf of','width="20%" align="left"',true),   
                                   new Array('className','Class','width="12%" align="left"',true), 
                                   new Array('subjectCode','Subject','width="20%" align="left"',true),
                                   new Array('groupShort','Group','width="20%" align="left"',true),
                                   new Array('tillDate','Till Date','width="20%" align="center"',true));
      }   
      else {
          tableColumns = new Array(new Array('srNo','#', 'width="2%"  align="left"',false), 
                                   new Array('employeeName','Teacher','width="20%" align="left"',true),   
                                   new Array('beHalfEmployeeName','On behalf of','width="20%" align="left"',true), 
                                   new Array('className','Class','width="12%" align="left"',true), 
                                   new Array('subjectCode','Subject','width="20%" align="left"',true),
                                   new Array('groupShort','Group','width="20%" align="left"',true),
                                   new Array('periodName','Period','width="20%" align="center"',true));
      }
      document.getElementById("nameRow").style.display='';
      document.getElementById("nameRow2").style.display='';
      document.getElementById("resultRow").style.display='';
      
      queryString1 = generateQueryString('attendanceMissedForm');

      //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
      listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'attendanceMissedForm','employeeName','ASC','resultsDiv','','',true,'listObj4',tableColumns,'','');
      sendRequest(url, listObj4, '',true);
} 

function validateAddForm(frm) {
   
    //var fieldsArray = new Array(new Array("degree","<?php echo SELECT_CLASS;?>"),new Array("subjectId","<?php echo SELECT_SUBJECT;?>"));
    hideDetails(); 
    var fieldsArray = new Array(new Array("labelId","<?php echo SELECT_TIMETABLE;?>"),
                                new Array("degree","<?php echo SELECT_CLASS;?>"));

    if (frm.degree.value != 'all') {
		var len = fieldsArray.length;
		for(i=0;i<len;i++) {
			if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
				messageBox(fieldsArray[i][1]);
				eval("frm."+(fieldsArray[i][0])+".focus();");
				return false;
				break;
			}
		}
	}
    page=1;
    refreshLectureData();
    return false;
}

function resetSubject() {
	hideDetails();
	if(document.attendanceMissedForm.degree.value != "") {
		document.attendanceMissedForm.subjectId.length = null;
		addOption(document.attendanceMissedForm.subjectId, 'all', 'All');
		getSubjects();
		document.attendanceMissedForm.subjectId.selectedIndex = 0;
	}
}

function hideDetails() {
    document.getElementById("resultsDiv").innerHTML='';  
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function printReport() {
	form = document.attendanceMissedForm;
	path='<?php echo UI_HTTP_PATH;?>/attendanceStatusReportPrint.php?'+queryString1+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"MissedAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    form = document.attendanceMissedForm;
	path='<?php echo UI_HTTP_PATH;?>/attendanceStatusReportCSV.php?'+queryString1+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

function getSubjects() {
	hideDetails();
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassTTSubjects.php';
	frm = document.attendanceMissedForm;
	var pars = generateQueryString('attendanceMissedForm');
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
				hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				document.attendanceMissedForm.subjectId.length = null;
				addOption(document.attendanceMissedForm.subjectId, '', 'Select');
//				addOption(document.attendanceMissedForm.subjectId, '', 'Select');
				if (len > 0) {
					addOption(document.attendanceMissedForm.subjectId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
				  addOption(document.attendanceMissedForm.subjectId, j[i].subjectId, j[i].subjectCode);
				}
				// now select the value
				//document.attendanceMissedForm.subjectId.value = j[0].subjectId;
		 },
		 onFailure: function(){messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function getTimeTableClasses() {
	hideDetails();
	form = document.attendanceMissedForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
	var pars = 'labelId='+form.labelId.value;

	if (form.labelId.value=='') {
		form.degree.length = null;
		addOption(form.degree, '', 'Select');
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
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.degree.length = null;
			addOption(form.degree, '', 'Select');
			if (len > 0) {
				addOption(form.degree, 'all', 'All');
			}
			for(i=0;i<len;i++) {
				addOption(form.degree, j[i].classId, j[i].className);
			}
			// now select the value
			//form.degree.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

window.onload=function(){
   document.getElementById('labelId').focus();
   getPopulate('T');
}

function getShowDate() {
    
   document.getElementById('showDate').style.display="none";
   if(document.attendanceMissedForm.showTodayAttendance.checked) {
     document.getElementById('showDate').style.display="";
   } 
}

function getPopulate(str) {

	 var isTimeTableCheck=0;
      if(document.attendanceMissedForm.timeTableCheck.checked) {
         isTimeTableCheck =1;
      }
  

    var url ='<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxPopulateValues.php';
  
    var strAll=''; 
    form = document.attendanceMissedForm;    
    
    if(str=='T') {
      form.degree.length = null;
      addOption(form.degree, '', 'Select');
      strAll='C';
    }
   
    if(str=='C' || strAll=='C') {
      form.subjectId.length = null;
      addOption(form.subjectId, '', 'Select');
      strAll='S';
    }
    
    if(str=='S' || strAll=='S') {
       form.employeeId.length = null;
       addOption(form.employeeId, '', 'Select');
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 labelId    : form.labelId.value,
                 degreeId   : form.degree.value,  
                 subjectId  : form.subjectId.value,
                 employeeId : form.employeeId.value,
		isTimeTableCheck: isTimeTableCheck,
                 val: str
             },
             onCreate: function(transport){
                 showWaitDialog();
             },   
             onSuccess: function(transport){
                hideWaitDialog();
                var ret=trim(transport.responseText).split('!~~!');
                
                var j0 = eval(ret[0]);
                var j1 = eval(ret[1]);
                var j2= eval(ret[2]);
                
                if(str=='T') {
                  if(j0.length>0) {  
                    form.degree.length = null;
                    addOption(form.degree, '', 'Select');   
                    for(i=0;i<j0.length;i++) { 
                      addOption(form.degree, j0[i].classId, j0[i].className);
                    }
                    str='C';
                  }
                }
                
                if(str=='C') {
                  if(j1.length>0) {    
                    form.subjectId.length = null;
                    addOption(form.subjectId, 'all', 'All');  
                    for(i=0;i<j1.length;i++) { 
                      addOption(form.subjectId, j1[i].subjectId, j1[i].subjectCode);
                    }
                    str='S';
                  }   
                }
                
                if(str=='S') {
                  if(j2.length>0) {      
                    form.employeeId.length = null;
                    addOption(form.employeeId, 'all', 'All');    
                    for(i=0;i<j2.length;i++) { 
                      addOption(form.employeeId, j2[i].employeeId, j2[i].employeeName);
                    }
                  }
                }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });    
    
}


</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listAttendanceStatusReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
