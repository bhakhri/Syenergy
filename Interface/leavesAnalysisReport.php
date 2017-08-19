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
define('MODULE','EmployeeLeavesAnalysisReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Leaves Analysis Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript" src="<?php echo JS_PATH;?>/swfobject.js"></script>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                   new Array('srNo','#','width="2%"','',false), 
                                   new Array('employeeCode','Employee Code','width="10%"','',true) , 
                                   new Array('employeeName','Employee Name','width="15%"','',true) , 
                                   new Array('leaveTypeName','Leave Type','width="10%"','',true) , 
                                   new Array('noOfDays','Days','width="5%"','align="right"',true)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/LeaveReports/ajaxLeavesAnalysisList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'employeeCode';
sortOrderBy    = 'ASC';

function inputValidation(){
    if(document.getElementById('leaveType').value==''){
        messageBox("<?php echo SELECT_LEAVE_TYPE; ?>");
        document.getElementById('leaveType').focus();
        return false;
    }
    if(trim(document.getElementById('criteriaText').value)==''){
        messageBox("<?php echo ENTER_CRITERIA_VALUE; ?>");
        document.getElementById('criteriaText').focus();
        return false;
    }
    if(!isInteger(trim(document.getElementById('criteriaText').value))){
        messageBox("<?php echo ENTER_CRITERIA_VALUE_IN_INTERGER; ?>");
        document.getElementById('criteriaText').focus();
        return false;
    }
    if(trim(document.getElementById('criteriaText').value)<0){
        messageBox("<?php echo ENTER_CRITERIA_VALUE_POSITIVE; ?>");
        document.getElementById('criteriaText').focus();
        return false;
    }
    return true;
}

function generateReport(){
  
  if(!inputValidation()){
      return false;
  }
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
  document.getElementById('printTrId').style.display='';
}


function vanishData(){
    document.getElementById('results').innerHTML='';
    document.getElementById('printTrId').style.display='none';
}


function printReport() {
    if(!inputValidation()){
      return false;
    }
    
    var yearName=document.getElementById('leaveSessionId').options[document.getElementById('leaveSessionId').selectedIndex].text;
    var leaveTypeName=document.getElementById('leaveType').options[document.getElementById('leaveType').selectedIndex].text;
    var criteriaName=document.getElementById('criteriaType').options[document.getElementById('criteriaType').selectedIndex].text;
    var criteriaValue=trim(document.getElementById('criteriaText').value);
    var path='<?php echo UI_HTTP_PATH;?>/leavesAnalysisReportPrint.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&leaveSessionId='+document.getElementById('leaveSessionId').value+'&leaveType='+document.getElementById('leaveType').value+'&yearName='+yearName+'&leaveTypeName='+leaveTypeName+'&criteriaType='+document.getElementById('criteriaType').value+'&criteriaName='+criteriaName+'&criteriaText='+criteriaValue;
    window.open(path,"LeavesAnalysisReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    if(!inputValidation()){
      return false;
    }
    
    var yearName=document.getElementById('leaveSessionId').options[document.getElementById('leaveSessionId').selectedIndex].text;
    var leaveTypeName=document.getElementById('leaveType').options[document.getElementById('leaveType').selectedIndex].text;
    var criteriaName=document.getElementById('criteriaType').options[document.getElementById('criteriaType').selectedIndex].text;
    var criteriaValue=trim(document.getElementById('criteriaText').value);
    var path='<?php echo UI_HTTP_PATH;?>/leavesAnalysisReportCSV.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&leaveSessionId='+document.getElementById('leaveSessionId').value+'&leaveType='+document.getElementById('leaveType').value+'&yearName='+yearName+'&leaveTypeName='+leaveTypeName+'&criteriaType='+document.getElementById('criteriaType').value+'&criteriaName='+criteriaName+'&criteriaText='+criteriaValue;
  
    window.location = path;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/LeaveReports/leavesAnalysisReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listCity.php $ 
?>