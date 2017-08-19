<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Bank Branch Form
//
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BankBranchMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/BankBranch/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bank Branch Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="4%"','',false), new Array('bankName','Bank','width="20%"','',true),new Array('branchName','Branch','width="20%"','',true), new Array('branchAbbr','Abbr.','width="10%"','',true), new Array('accountType','Account Type','width="15%"','',true), new Array('accountNumber','Account Number','width="18%"','',true), new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BankBranch/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBankBranch';
editFormName   = 'EditBankBranch';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBankBranch';
divResultName  = 'results';
page=1; //default page
sortField = 'branchName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   
	displayWindow(dv,w,h);
    populateValues(id);   
}

function validateAddForm(frm, act) {
    
    //messageBox (act)
    var fieldsArray = new Array(new Array("bankId","<?php echo SELECT_BANK;?>"), new Array("branchName","<?php echo ENTER_BRANCH_NAME;?>"), new Array("branchAbbr","<?php echo ENTER_BRANCH_ABBR;?>"), new Array("accountType","<?php echo ENTER_ACCOUNT_TYPE;?>"), new Array("accountNumber","<?php echo ENTER_ACCOUNT_NUMBER;?>"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		/*else {
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                //messageBox("<?php echo ENTER_ALPHABETS;?>");
				messageBox("<?php echo ENTER_NUMERIC_ALPHABETS;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
		}*/
    }
    if(act=='Add') {
        addBankBranch();
        return false;
    }
    else if(act=='Edit') {
        editBankBranch();  
        return false;
    }
}
function addBankBranch() {
         url = '<?php echo HTTP_LIB_PATH;?>/BankBranch/ajaxInitAdd.php';

		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankId: (document.addBankBranch.bankId.value), branchName: (document.addBankBranch.branchName.value), branchAbbr: (document.addBankBranch.branchAbbr.value), accountType: (document.addBankBranch.accountType.value), accountNumber: (document.addBankBranch.accountNumber.value), operator: (document.addBankBranch.operator.value)},
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
						 hiddenFloatingDiv('AddBankBranch');
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
   document.addBankBranch.bankId.value = '';
   document.addBankBranch.branchName.value = '';
   document.addBankBranch.branchAbbr.value = '';
   document.addBankBranch.accountType.value = '';
   document.addBankBranch.accountNumber.value = '';
   document.addBankBranch.operator.value = '';
   document.addBankBranch.bankId.focus();
}
function editBankBranch() {
         url = '<?php echo HTTP_LIB_PATH;?>/BankBranch/ajaxInitEdit.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankId: (document.editBankBranch.bankId.value), bankBranchId: (document.editBankBranch.bankBranchId.value), branchName: (document.editBankBranch.branchName.value), branchAbbr: (document.editBankBranch.branchAbbr.value), accountType: (document.editBankBranch.accountType.value), accountNumber: (document.editBankBranch.accountNumber.value), operator:(document.editBankBranch.operator.value)},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('EditBankBranch');
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

function deleteBankBranch(id) {  
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/BankBranch/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankBranchId: id},
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
         url = '<?php echo HTTP_LIB_PATH;?>/BankBranch/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankBranchId: id},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    
                   document.editBankBranch.bankBranchId.value = id;
                   document.editBankBranch.bankId.value = j.bankId;
                   document.editBankBranch.branchName.value = j.branchName;
                   document.editBankBranch.branchAbbr.value = j.branchAbbr;
                   document.editBankBranch.accountType.value = j.accountType;
                   document.editBankBranch.accountNumber.value = j.accountNumber;
                   document.editBankBranch.operator.value = j.operator;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	var path='<?php echo UI_HTTP_PATH;?>/displayBankBranchReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"BankBranchReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayBankBranchCSV.php?'+qstr;
	window.location = path;
}


</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/BankBranch/listBankBranchContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	//-->
	</SCRIPT>
</body>
</html>
<?php 
// $History: listBankBranch.php $
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/24/09    Time: 10:39a
//Updated in $/LeapCC/Interface
//fixed bugs
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/21/09    Time: 10:39a
//Updated in $/LeapCC/Interface
//fixed issues nos.0000511,  0001157, 0001154 , 0001153, 0001150
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/19/09    Time: 6:44p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1158,1149,1156
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/11/09    Time: 12:28p
//Updated in $/LeapCC/Interface
//remove validations
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/09/09    Time: 4:37p
//Updated in $/LeapCC/Interface
//make all field alphanumeric values
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/08/09    Time: 11:19a
//Updated in $/LeapCC/Interface
//fixed bug no.0000445
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/10/08   Time: 11:58a
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/25/08    Time: 12:13p
//Updated in $/Leap/Source/Interface
//applied code for following:
//1. checking for special characters.
//2. messageBox for error messages returned from server.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/19/08    Time: 4:24p
//Updated in $/Leap/Source/Interface
//fixed minor issue, found during self testing
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/09/08    Time: 10:47a
//Updated in $/Leap/Source/Interface
//updated the code of ajax request, and changed messages
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/28/08    Time: 3:07p
//Updated in $/Leap/Source/Interface
//Fixed bugs given by Pushpender sir
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/23/08    Time: 6:22p
//Updated in $/Leap/Source/Interface
//Fixed minor designing issue found during self-testing

?>