<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
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
define('MODULE','streamWiseAttendanceReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Stream Wise Attendance Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/AttendancePercent/initStreamWiseAttendanceReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'studentAttendanceForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'fromDate';
sortOrderBy    = 'ASC';
queryString ='';



function validateAddForm(frm) {
    
    hideResults();

    queryString = '';
    
    var url='<?php echo HTTP_LIB_PATH;?>/AttendancePercent/initStreamWiseAttendanceReport.php';   

    var form = document.studentAttendanceForm; 
    
    var timeTable = form.labelId.value;   
    var rval=timeTable.split('~');
    var timeTableLabelId = rval[0]; 
    if(timeTable=='') {
       messageBox("<?php echo SELECT_TIMETABLE;?>");
       return false;  
    }
   
    if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-') ) {
       messageBox ("<?php echo PH_CORRECT_DATE;?>");
       eval("frm.fromDate.focus();");
       return false;
    } 
    
    queryString = 'labelId='+timeTableLabelId+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value;
    
    new Ajax.Request(url,
    {
          method:'post',
          asynchronous:false,
          parameters: queryString, 
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
               document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
               document.getElementById("nameRow").style.display='';
               document.getElementById("nameRow2").style.display='';
               document.getElementById("resultRow").style.display='';
             }
    },
    onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}


function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/streamWiseAttendanceReportPrint.php?'+queryString;
	a = window.open(path,"StudentAttendanceReport","status=1,menubar=1,scrollbars=1, width=900");

}

function printCSV() {
   var path='<?php echo UI_HTTP_PATH;?>/streamWiseAttendanceReportPrintCSV.php?'+queryString;
   window.location = path;
}

function hideResults() {
   document.getElementById("resultRow").style.display='none';
   document.getElementById('nameRow').style.display='none';
   document.getElementById('nameRow2').style.display='none';
}

function getTimeTableClasses() {
	form = document.studentAttendanceForm;
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
		asynchronous:false,
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
			for(i=0;i<len;i++) {
				addOption(form.degree, j[i].classId, j[i].className);
			}
			// now select the value
			form.degree.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


window.onload=function(){
   document.getElementById('labelId').focus();
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/AttendancePercent/streamWiseAttendanceReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


