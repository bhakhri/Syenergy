<?php
//-------------------------------------------------------
//  This File contains logic for vouchers
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
define('MODULE','Voucher');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::ifCompanyNotSelected();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Ledger Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH;?>/autosuggest.css" />
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<script type="text/javascript" src="<?php echo JS_PATH;?>/json.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>/zxml.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>/autosuggest.js"></script>

<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
								new Array('srNo','#','width="10%"','',false), 
								new Array('ledgerName','Ledger Name','width="30%"','',true) , 
								new Array('groupName','Group','width="30%"','',true), 
								new Array('openingBalance','Opening Balance','width="30%"','',false), 
								new Array('action','Action','width="10%"','align="right"',false)
							);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Accounts/Voucher/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'Ledger';   
editFormName   = 'Ledger';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVoucher';
divResultName  = 'results';
page=1; //default page
sortField = 'ledgerName';
sortOrderBy    = 'ASC';
actionUrl = '<?php echo HTTP_LIB_PATH;?>/Accounts/Voucher/ajaxInitAction.php';
// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   	displayWindow(dv,w,h);
    populateValues(id);   
}

var mode = '';
var debitLedgerArray;
var creditLedgerArray;
function setMode(str) {
	mode = str;
}

function validateAddForm(frm) {

	form = document.voucherForm;
	mode = 'Add';
	if (form.voucherId.value != '') {
		mode = 'Edit';
	}
    
    if(mode=='Add') {
        addVoucher();
        return false;
    }
    else if(mode=='Edit') {
        editVoucher();  
        return false;
    }
}
function addVoucher() {

		form = document.voucherForm;
		if (form.drcr_0.value == 'dr') {
			if (form.debit_0.value == '') {
				form.debit_0.focus();
				return false;
			}
		}
		else if (form.drcr_0.value == 'cr') {
			if (form.credit_0.value == '') {
				form.credit_0.focus();
				return false;
			}
		}
         
		 var pars = generateQueryString('voucherForm');
		 pars += '&mode=add';

		 new Ajax.Request(actionUrl,
           {
             method:'post',
             parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
					 flag = true;
					 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
						 blankValues();
						 setVoucherType(form.voucherType.value);
					 }
					 else {
						 blankValues();
					 }
				 } 
				 else {
					messageBox(trim(transport.responseText)); 
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
	form = document.voucherForm;
	form.voucherNo.value = '';

	voucherEntriesOnPage = <?php echo VOUCHER_ENTRIES_ON_PAGE;?>;
	var i = 0;
	totalDebit = 0;
	totalCredit = 0;
	while(i < voucherEntriesOnPage) {
		eval('form.voucherLedgers_'+i+'.value=""');
		eval('form.drcr_'+i+'.value=""');
		eval('form.debit_'+i+'.value=""');
		eval('form.credit_'+i+'.value=""');
		i++;
	}
	document.getElementById("debitTotalSpan").innerHTML = '0.00';
	document.getElementById("creditTotalSpan").innerHTML = '0.00';
	form.narration.value = '';
	
}
function editVoucher() {
        
		 var pars = generateQueryString('voucherForm');
		 pars += '&mode=Edit';
       
         new Ajax.Request(actionUrl,
           {
             method:'post',
             parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 messageBox(trim(transport.responseText));
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Accounts/Ledger/ajaxGetValues.php';
		 var pars = 'ledgerId='+id+'&mode='+mode;
		 var form = document.ledgerForm; 

         new Ajax.Request(url,
           {
             method:'post',
             parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    
                   form.openingBalance.value = '';
				   form.drcr.value = 'dr';
				   form.ledgerId.value = id;
                   form.ledgerName.value = j.ledgerName;
                   form.parentGroup.value = j.groupName;
				   var opDrAmount = j.opDrAmount;
				   var opCrAmount = j.opCrAmount;
				   if (parseInt(opDrAmount) != 0) {
					   form.openingBalance.value = opDrAmount;
					   form.drcr.value = 'dr';
				   }
				   else if (parseInt(opCrAmount) != 0) {
					   form.openingBalance.value = opCrAmount;
					   form.drcr.value = 'cr';
				   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function setVoucherType(str) {
	var form = document.voucherForm;
	form.voucherType.value = str;
	if (str == "<?php echo RECEIPT;?>") {
		document.getElementById("voucherTypeSpan").innerHTML = 'Receipt Voucher';
	}
	else if (str == "<?php echo PAYMENT;?>") {
		document.getElementById("voucherTypeSpan").innerHTML = 'Payment Voucher';
	}
	else if (str == "<?php echo JOURNAL;?>") {
		document.getElementById("voucherTypeSpan").innerHTML = 'Journal Voucher';
	}
	else if (str == "<?php echo CONTRA;?>") {
		document.getElementById("voucherTypeSpan").innerHTML = 'Contra Voucher';
	}
	getVoucherNo(str);
}

function getVoucherNo(str) {
	var voucherId = querySt('id');
	var form = document.voucherForm;


	if (typeof voucherId !== 'undefined') {
		ctr = 0;
		var debitDisabled = eval('form.debit_'+ctr+'.disabled');
		var creditDisabled = eval('form.credit_'+ctr+'.disabled');
		if (debitDisabled == true) {
			eleName = 'credit_'+ctr;
		}
		else {
			eleName = 'debit_'+ctr;
		}
		eval('form.'+eleName+'.focus()');
	}


	var form = document.voucherForm;
	 var voucherNoUrl = '<?php echo HTTP_LIB_PATH;?>/Accounts/Voucher/ajaxInitGetVoucherNo.php';
	 pars = 'voucherType='+str;
   
	 new Ajax.Request(voucherNoUrl,
	   {
		 method:'post',
		 parameters: pars,
		 asynchronous:false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 form.voucherNo.value = trim(transport.responseText);
			 form.voucherNo.focus();
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   fetchDebitCreditLedgers();
}

function fetchDebitCreditLedgers(){
	voucherType = document.voucherForm.voucherType.value;
	if (voucherType == <?php echo RECEIPT;?>) {
		debitLedgerParam = 'receiptVoucherDr';
		creditLedgerParam =  'receiptVoucherCr';
	}
	else if (voucherType == <?php echo PAYMENT;?>) {
		debitLedgerParam = 'paymentVoucherDr';
		creditLedgerParam =  'paymentVoucherCr';
	}
	else if (voucherType == <?php echo JOURNAL;?>) {
		debitLedgerParam = 'journalVoucherDr';
		creditLedgerParam =  'journalVoucherDr';
	}
	else if (voucherType == <?php echo CONTRA;?>) {
		debitLedgerParam = 'contraVoucherDr';
		creditLedgerParam =  'contraVoucherCr';
	}
	else {
		messageBox("<?php echo INVALID_VOUCHER_TYPE;?>");
		return false;
	}


	pars = 'requesting='+debitLedgerParam;
	ledgerUrl = '<?php echo HTTP_LIB_PATH;?>/suggestions.php';
         new Ajax.Request(ledgerUrl,
           {
             method:'post',
             parameters: pars,
			 asynchronous:false,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 debitLedgerArray = eval('('+trim(transport.responseText)+')');
				 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });


	pars = 'requesting='+creditLedgerParam;
	ledgerUrl = '<?php echo HTTP_LIB_PATH;?>/suggestions.php';
         new Ajax.Request(ledgerUrl,
           {
             method:'post',
             parameters: pars,
			 asynchronous:false,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 creditLedgerArray = eval('('+trim(transport.responseText)+')');
				 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function checkTotals(thisCounter) {
	var form = document.voucherForm;
	voucherEntriesOnPage = <?php echo VOUCHER_ENTRIES_ON_PAGE;?>;
	var i = 0;
	totalDebit = 0;
	totalCredit = 0;
	while(i < voucherEntriesOnPage) {
		voucherLedger = eval('form.voucherLedgers_'+i+'.value');
		entryDrCr = eval('form.drcr_'+i+'.value');
		if (trim(voucherLedger) != '') {
			if (entryDrCr == 'dr') {
				totalDebit += parseFloat(eval('form.debit_'+i+'.value')*100)/100;
			}
			else if (entryDrCr == 'cr') {
				totalCredit += parseFloat(eval('form.credit_'+i+'.value')*100)/100;
			}
		}
		i++;
	}
	fixedDebit = totalDebit.toFixed(2);
	fixedCredit = totalCredit.toFixed(2);
	document.getElementById("debitTotalSpan").innerHTML = fixedDebit;
	document.getElementById("creditTotalSpan").innerHTML = fixedCredit;
	if (parseInt(fixedDebit) != 0 && thisCounter >= 1) {
		if (fixedDebit == fixedCredit) {
			form.narration.className = 'selectfieldBottomBorder2';
			form.narration.focus();
			return false;
		}
	}
}

function fillBalance(entryStatus,ele) {
	totalDebit =  parseFloat(document.getElementById("debitTotalSpan").innerHTML).toFixed(2);
	totalCredit = parseFloat(document.getElementById("creditTotalSpan").innerHTML).toFixed(2);
	var balance;
	if (totalDebit > totalCredit) {
		balance = totalDebit - totalCredit;
		balance = balance.toFixed(2);
		if (entryStatus == "credit") {
			ele.value = balance;
		}
	}
	else if (totalCredit > totalDebit) {
		balance = totalCredit - totalDebit;
		balance = balance.toFixed(2);
		if (entryStatus == "debit") {
			ele.value = balance;
		}
	}
}

function setAutoSuggest(eleName, ctr) {
	var form = document.voucherForm;
	var voucherType = form.voucherType.value;
	var entryDrCr = eval('form.drcr_'+ctr+'.value');
	if (entryDrCr == 'dr') {
		eval('form.credit_'+ctr+'.disabled=true;');
		eval('form.debit_'+ctr+'.disabled=false;');
	}
	else if (entryDrCr == 'cr') {
		eval('form.credit_'+ctr+'.disabled=false;');
		eval('form.debit_'+ctr+'.disabled=true;');
	}
	var autoSuggestParam = '';
	if (voucherType == <?php echo RECEIPT;?>) {
		if (entryDrCr == 'dr') {
			autoSuggestParam = 'receiptVoucherDr';
		}
		else if (entryDrCr == 'cr') {
			autoSuggestParam = 'receiptVoucherCr';
		}
	}
	else if (voucherType == <?php echo PAYMENT;?>) {
		if (entryDrCr == 'dr') {
			autoSuggestParam =  'paymentVoucherDr';
		}
		else if (entryDrCr == 'cr') {
			autoSuggestParam =  'paymentVoucherCr';
		}
	}
	else if (voucherType == <?php echo JOURNAL;?>) {
		if (entryDrCr == 'dr') {
			autoSuggestParam =  'journalVoucherDr';
		}
		else if (entryDrCr == 'cr') {
			autoSuggestParam =  'journalVoucherCr';
		}
	}
	else if (voucherType == <?php echo CONTRA;?>) {
		if (entryDrCr == 'dr') {
			autoSuggestParam =  'contraVoucherDr';
		}
		else if (entryDrCr == 'cr') {
			autoSuggestParam =  'contraVoucherCr';
		}
	}
	else {
		messageBox("<?php INVALID_VOUCHER_DETAILS;?>");
		return false;
	}
	var oTextbox = new AutoSuggestControl(document.getElementById(eleName), new SuggestionProvider(), autoSuggestParam);
}

function sendKeys(eleName, e,ctr) {
	var ev = e||window.event;
	thisKeyCode = ev.keyCode;
	if (thisKeyCode == '13') {
		var form = document.voucherForm;

		if (eleName != 'imageFieldSave') {
			if (eval('form.'+eleName)) {
				eval('form.'+eleName+'.focus()');
			}
		}
		else {
			validateAddForm(document.voucherForm);	
		}
		return false;
	}
}



function printReport() {
	form = document.voucherForm;
	var pars = generateQueryString('voucherForm');
	path='<?php echo UI_HTTP_ACCOUNTS_PATH;?>/voucherReportPrint.php';
	var voucherPrintWindow = window.open(path,"VoucherReport","status=1,menubar=1,scrollbars=1, width=900");
}

window.onload = function() {
	var voucherId = querySt('id');
	if (typeof voucherId != 'undefined') {
		parent.document.getElementById('innerIframe').style.height='580px';
		document.voucherForm.voucherNo.focus();
	}
}


</script>
</head>
<body>
	<?php 
	if (!isset($REQUEST_DATA['id']) or $REQUEST_DATA['id'] == '') {
		require_once(TEMPLATES_PATH . "/header.php");
	}
	require_once(TEMPLATES_PATH . "/Accounts/Voucher/listVoucherContents.php");
	if (!isset($REQUEST_DATA['id']) or $REQUEST_DATA['id'] == '') {
		require_once(TEMPLATES_PATH . "/footer.php");
	}
    ?>
<SCRIPT LANGUAGE="JavaScript">
if(document.voucherForm.voucherType.value == '') {
	setVoucherType(<?php echo RECEIPT;?>);
}
else {
	fetchDebitCreditLedgers();
}
</SCRIPT>
</body>
</html>
<?php 
// $History: listVoucher.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:08p
//Created in $/LeapCC/Interface/Accounts
//file added
//



?>