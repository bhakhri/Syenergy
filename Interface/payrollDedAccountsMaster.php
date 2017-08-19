<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Payroll Deduction Account Master
//
//
// Author :Abhiraj Malhotra
// Created on : 14-April-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Deduction Accounts Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>                                                 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), 
                                new Array('accountName','Account Name','width="45%"','',true) , 
                                new Array('accountNumber','Account Number','width="40%"','',true), 
                                new Array('action','Action','width="10%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitDedAccountList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddAccount';   
editFormName   = 'EditAccount';
winLayerWidth  = 270; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteAccount';
divResultName  = 'results';
page=1; //default page
sortField = 'accountName';
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
    var fieldsArray = new Array(new Array("accountName","<?php echo ENTER_ACCOUNT_NAME;?>","accountNumber","<?php echo ENTER_ACCOUNT_NUMBER;?>"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    if(act=='Add') {
        addAccount();
        return false;
    }
    else if(act=='Edit') {
        editAccount();  
        return false;
    }
}

function addAccount() {
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitAddDedAccount.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {accountName: (document.addAccount.accountName.value), accountNumber: (document.addAccount.accountNumber.value)},
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
                         hiddenFloatingDiv('AddAccount');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                     }
                 } 
                 else {
                    messageBox(trim(transport.responseText));
                    if (trim(transport.responseText)=='<?php echo ENTER_ACCOUNT_NAME ?>'){
                        document.addAccount.accountName.focus();
                    } 
                    else if(trim(transport.responseText)=='<?php echo ENTER_ACCOUNT_NUMBER ?>'){
                        document.addAccount.accountNumber.focus();
                    }
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addAccount.accountName.value = '';
   document.addAccount.accountNumber.value = '';
   document.addAccount.accountName.focus();
}
function editAccount() {
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitEditDedAccount.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {accountName: (document.editAccount.accountName.value), accountNumber: (document.editAccount.accountNumber.value), dedAccountId:(document.editAccount.dedAccountId.value)},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditAccount');
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                     //location.reload();
                 }
                 else {
                     messageBox(trim(transport.responseText));
                     if (trim(transport.responseText)=='<?php echo ENTER_ACCOUNT_NAME ?>' || trim(transport.responseText)=='<?php echo ACCOUNT_NAME_ALREADY_EXISTS ?>'){
                        document.editAccount.accountName.focus();
                    }
                    else {
                        document.editAccount.accountNumber.focus(); 
                    }
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteAccount(id) {  
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitDeleteDedAccount.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {dedAccountId: id},
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
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetDedAccountValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {dedAccountId: id},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    
                   document.editAccount.dedAccountId.value = id;
                   document.editAccount.accountName.value = j.accountName;
                   document.editAccount.accountNumber.value = j.accountNumber;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/displayDedAccountsReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"PayrollDeductionAccountsReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayDedAccountsCSV.php?'+qstr;
    window.location = path;
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Payroll/listDedAccounts.php"); 
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

</body>
</html>