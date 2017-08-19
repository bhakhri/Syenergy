<?php
//-------------------------------------------------------
//  This File contains logic for ledger display
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
define('MODULE','Ledgers');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::ifCompanyNotSelected();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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


<script type="text/javascript">
window.onload = function () {
	var oTextbox = new AutoSuggestControl(document.getElementById("parentGroup"), new SuggestionProvider(), 'ledgerGroups');
}
</script>

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
listURL = '<?php echo HTTP_LIB_PATH;?>/Accounts/Ledger/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'Ledger';   
editFormName   = 'Ledger';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteLedger';
divResultName  = 'results';
page=1; //default page
sortField = 'ledgerName';
sortOrderBy    = 'ASC';
actionUrl = '<?php echo HTTP_LIB_PATH;?>/Accounts/Ledger/ajaxInitAction.php';
// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   	displayWindow(dv,w,h);
    populateValues(id);   
}

var mode = '';
	var ledgerGroupArray;

function setMode(str) {
	mode = str;
}

function validateAddForm(frm) {

	form = document.ledgerForm;
	if (form.ledgerId.value != '') {
		mode = 'Edit';
	}
    



    //messageBox (act)
    var fieldsArray = new Array(new Array("ledgerName","<?php echo ENTER_LEDGER_NAME;?>"), new Array("parentGroup","<?php echo ENTER_PARENT_GROUP;?>"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		/*
		else {
            if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
		}
		*/
    }
    if(mode=='Add') {
        addLedger();
        return false;
    }
    else if(mode=='Edit') {
        editLedger();  
        return false;
    }
}
function addLedger() {
         
		 var pars = generateQueryString('ledgerForm');
		 pars += '&mode='+mode;

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
					 }
					 else {
						 hiddenFloatingDiv('Ledger');
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 //location.reload();
						 return false;
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
	form = document.ledgerForm;
	form.ledgerId.value = '';
	form.ledgerName.value = '';
	form.parentGroup.value = '';
	form.openingBalance.value = '';
	form.ledgerName.focus();
}
function editLedger() {
         
		 var pars = generateQueryString('ledgerForm');
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
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('Ledger');
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					 return false;
					 //location.reload();
				 }
				 else {
					 messageBox(trim(transport.responseText));
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteLedger(id) {
		 messageBox("Ledger Deletion stopped");
		 return false;
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         var pars = 'ledgerId='+id+'&mode=Delete';
         new Ajax.Request(actionUrl,
           {
             method:'post',
             parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					 return false;
				 }
				 else {
					 messageBox(trim(transport.responseText));
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
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

	function fetchAllLedgerGroups() {
		pars = 'requesting=ledgerGroups';
		url = '<?php echo HTTP_LIB_PATH;?>/suggestions.php';
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
				 ledgerGroupArray = eval('('+trim(transport.responseText)+')');
				 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
		   
         }

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Accounts/Ledger/listLedgerContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	fetchAllLedgerGroups();

 
</SCRIPT>
</body>
</html>
<?php 
// $History: listLedger.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:08p
//Created in $/LeapCC/Interface/Accounts
//file added
//



?>