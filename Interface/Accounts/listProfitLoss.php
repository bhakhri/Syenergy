<?php
//-------------------------------------------------------
//  This File contains logic for profit loss
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ProfitLoss');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::ifCompanyNotSelected();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Balance Sheet </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Accounts/BalanceSheet/initGetBalanceSheet.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'profitLossForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'Asc';
 //This function Validates Form 


function validateAddForm(frm, tillDate) {
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	showProfitLoss(tillDate);

//	openStudentLists(frm.name,'class','Asc');    
}


function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function printReport() {
	form = document.profitLossForm;
	path='<?php echo UI_HTTP_ACCOUNTS_PATH;?>/profitLossReportPrint.php?tillDate='+form.tillDate.value;
	window.open(path,"ProfitLossReport","status=1,menubar=1,scrollbars=1, width=900");
}


function showProfitLoss(tillDate) {
	var url = '<?php echo HTTP_LIB_PATH;?>/Accounts/ProfitLoss/initProfitLoss.php';
	frm = document.profitLossForm;
	var pars = 'tillDate='+tillDate;
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
				hideWaitDialog(true);
				var j = transport.responseText;
				document.getElementById("resultsDiv").innerHTML = j;
		 },
		 onFailure: function(){messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

window.onload = function() {
	var tillDate = querySt('date');
	if (typeof tillDate != 'undefined') {
		validateAddForm(document.profitLossForm, tillDate)
	}
}

</script>

</head>
<body>
    <?php 
	echo '<script type="text/javascript">imagePathURL = "'.IMG_HTTP_PATH.'";</script>'; 
	// set global variable for css path;
	echo '<script type="text/javascript">cssPathURL = "'.CSS_PATH.'";</script>'; 
	// set global variable for js path;
	echo '<script type="text/javascript">jsPathURL = "'.JS_PATH.'";</script>'; 
	// set global variable for change pref file path;
	echo '<script type="text/javascript">themeFilePathURL = "'.HTTP_LIB_PATH.'";</script>'; 
	// set global variable for current themeId;
	echo '<script type="text/javascript">currentThemeId = "'.$sessionHandler->getSessionVariable('UserThemeId').'";</script>'; 

	require_once(TEMPLATES_PATH . "/Accounts/ProfitLoss/listProfitLossReport.php");

	echo '<script language="javascript">changeColor(currentThemeId);</script>';
    ?>
</body>
</html>
<?php 
// $History: listProfitLoss.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:08p
//Created in $/LeapCC/Interface/Accounts
//file added
//



?>