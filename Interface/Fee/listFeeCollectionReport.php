<?php
//-------------------------------------------------------
// Purpose: To List Fee Collection 
// Author : Nishu Bindal
// Created on : 16-April-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCollectionReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Fee Collection Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?> 
<script type="text/javascript" language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Fee/FeeCollectionReport/ajaxInitList.php';
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
sortField = 'rollNo';
sortOrderBy    = 'ASC';

var ttQueryString="";

var dtArray=new Array();  
var dtInstitute='';

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                                new Array('receiptNo','Rcpt No.','width="7%"','',true), 
                                new Array('studentName','Student Name','width="11%"','',true), 
                                new Array('rollNo','Roll No.','width="10%"','',true), 
                                new Array('regNo','Registration No.','width="13%"','',true),
                                new Array('branchName','Branch','width="9%"','',true), 
                                new Array('periodName','Semester','width="7%"','',true),
                                new Array('feeTypeOf','Pay Fee Of','width="11%"','',true),
                                new Array('cashAmount','Cash Amt.','width="9%"','align="right"',true),
                                new Array('DDAmount','DD Amt.','width="8%"','align="right"',true),
                                new Array('checkAmount','Cheque Amt.','width="10%"','align="right"',true),
                                new Array('totalReceipt','Total Rcpt','width="10%"','align="right"',true));


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


/* function to print city report*/
function printReport() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/Fee/listFeeCollectionReportPrint.php?'+qstr;

	window.open(path,"FeeCollectionReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to export to excel */
	function printReportCSV() {
	var params = generateQueryString('allDetailsForm');
	var qstr=params+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	var path='<?php echo UI_HTTP_PATH;?>/Fee/listFeeCollectionReportCSV.php?'+qstr;
	window.location = path;
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/FeeCollectionReport/listFeeCollectionContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
                
