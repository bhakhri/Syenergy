<?php

//------------------------------------------------
// Purpose: This File contains Audit Trail Reports
// Author :Kavish Manjkhola
// Created on : 22-Oct-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//-----------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AuditTrailReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Audit Trail Report </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
							   new Array('auditType','Audit Trail Type','width=8%','',true),
							   new Array('description','Audit Description','width="22%"','align="left"',true),
							   new Array('auditDateTime','Audit Date Time','width="10%"','align="left"',true),
							   new Array('userName','Audit User','width="10%"','align="left"',true),
							   new Array('roleName','User Role','width="10%"','align="left"',true),
							   new Array('userip','IP Address','width="10%"','align="center"',true)
							  );
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initAuditTrailReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'auditTrailReport'; // name of the form which will be used for search
winLayerWidth  = 315;
winLayerHeight = 250;
page=1; //default page
sortField = 'auditType';
sortOrderBy = 'ASC';

function validateAddForm(frm) {
	var fieldsArray = new Array(new Array("fromDate","<?php echo EMPTY_FROM_DATE;?>"),
								new Array("toDate","<?php echo EMPTY_TO_DATE;?>"));

	var len = fieldsArray.length;
	for(i=0;i<len;i++) {
		if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
			messageBox(fieldsArray[i][1]);
			eval("frm."+(fieldsArray[i][0])+".focus();");
			return false;
			break;
		}
		if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-') ) {
			messageBox ("<?php echo DATE_CONDITION;?>");
			eval("frm.fromDate.focus();");
			return false;
			break;
		}
	}

	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}
function printReport() {
	form = document.auditTrailReport;
	path='<?php echo UI_HTTP_PATH;?>/auditTrailReportPrint.php?auditType='+form.auditType.value+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	a = window.open(path,"AuditTrailReport","status=1,menubar=1,scrollbars=1, width=900");

}

function printReportCSV() {
	form = document.auditTrailReport;
	var path='<?php echo UI_HTTP_PATH;?>/auditTrailReportPrintCSV.php?auditType='+form.auditType.value+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}


</script>
</head>
<body>
	<?php
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/StudentReports/auditTrailReportContent.php");
		require_once(TEMPLATES_PATH . "/footer.php");
	?>
</body>
</html>
