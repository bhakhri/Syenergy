<?php

//-------------------------------------------------------
// Purpose: To List Fee Collection 
// Author : Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ConsolidatedFeeCollectionReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Consolidated Fee Collection Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script type="text/javascript" language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Fee/ConsolidateFeeCollectionReport/ajaxInitList.php';
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
sortField = 'instituteId';
sortOrderBy    = 'ASC';

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                   new Array('instituteAbbr','Institute Abbr.','width="12%"','',true),
                   new Array('concession','Concession','width="10%"','align="right"',true), 
                   new Array('fine','Fine','width="10%"','align="right"',true), 
                   new Array('cashAmount','Cash Amount','width="10%"','align="right"',true), 
                   new Array('DDAmount','DD Amount','width="13%"','align="right"',true),
                   new Array('checkAmount','Check Amount','width="10%"','align="right"',true), 
                   new Array('totalReceipt','Total Amount','width="10%"','align="right"',true));


function validateAddForm(frm) { 

	if(document.getElementById('fromDate').value=='' && document.getElementById('toDate').value!='') {
	       messageBox("Select From Date");
	       //eval("frm.fromDate.focus();");
	       return false;
    	}
    
    
   	if(document.getElementById('fromDate').value!='' && document.getElementById('toDate').value=='') {
	       messageBox("Select To Date");
	       //eval("frm.fromDate.focus();");
	       return false;
         }
    
	if(document.getElementById('fromDate').value!='' && document.getElementById('toDate').value!='') {
	  if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-') ) {
	     messageBox("<?php echo DATE_VALIDATION;?>");
		 //eval("frm.fromDate.focus();");
		 return false;
	  }
	}

	document.getElementById('resultsDiv').style.display='';
	document.getElementById('nameRow').style.display='';
	document.getElementById('cancelDiv1').style.display='';
	params = generateQueryString('allDetailsForm');
	
	sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	//showFeeCollection();
	return false;
}

function resetResult(){
	document.getElementById('resultsDiv').innerHTML='';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('resultsDiv').style.display='none';
	document.getElementById('cancelDiv1').style.display='none';
}

//populate list
window.onload=function(){
	 document.getElementById('instituteId').focus();
}

/* function to print city report*/
function printReport() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;  
    	path='<?php echo UI_HTTP_PATH;?>/Fee/consolidatedFeeCollectionReportPrint.php?'+qstr;
    
   	window.open(path,"DisplayRequisitionReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to export to excel */
function printReportCSV() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	var path='<?php echo UI_HTTP_PATH;?>/Fee/consolidatedFeeCollectionReportCSV.php?'+qstr;
	window.location = path;
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/ConsolidateFeeCollectionReport/listFeeCollectionContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                
