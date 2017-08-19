<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Head Form
//
//
// Author :Abhiraj Malhotra
// Created on : 06-April-2010
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
<title><?php echo SITE_NAME;?>: Payroll Heads Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>                                                 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tempBankBranchId='';
var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                                new Array('headName','Head Name','width="25%"','',true) , 
                                new Array('headAbbr','Head Abbr.','width="20%"','',false) , 
                                new Array('headTypeText','Head Type','width="20%"','',false),
                                new Array('dedAccountIdText','Deduction Account','width="15%"','',false),
                                new Array('desc','Desc.','width="10%"','align="center"',false), 
                                new Array('action','Action','width="10%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitHeadList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddHead';   
editFormName   = 'EditHead';
winLayerWidth  = 270; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteHead';
divResultName  = 'results';
page=1; //default page
sortField = 'headName';
sortOrderBy    = 'Asc';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   
    displayWindow(dv,w,h);
    populateValues(id);
    
}

// This function validates the form and opens add or edit window accordingly
function validateAddForm(frm, act) {
    
    //messageBox (act)
    var fieldsArray = new Array(new Array("headName","<?php echo ENTER_HEAD_NAME;?>"),new Array("headType","<?php echo SELECT_HEAD_TYPE;?>"),new Array("headAbbr","<?php echo ENTER_HEAD_ABBR;?>"));
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
        addHead();
        return false;
    }
    else if(act=='Edit') {
        editHead();  
        return false;
    }
}

// For adding a new head
function addHead() {
        if(document.addHead.headDesc.value.length>60)
         {
             alert("<?php echo DESCRIPTION_EXCEEDS_LIMIT;?>");
             return;
         }
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitAddHead.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {headName: (document.addHead.headName.value), headType: (document.addHead.headType.value), dedAccountId: (document.addHead.dedAccountId.value), headDesc: (document.addHead.headDesc.value), headAbbr: (document.addHead.headAbbr.value)},
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
                         hiddenFloatingDiv('AddHead');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                     }
                 } 
                 else {
                    messageBox(trim(transport.responseText));
                    //alert(transport.responseText);
                    if (trim(transport.responseText)=='<?php echo ENTER_HEAD_NAME ?>'){
                        document.addHead.headName.focus();
                    }
                    
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//For clearing the values
function blankValues() {
   document.addHead.headName.value = '';
   document.addHead.headAbbr.value = '';
   document.addHead.headType.value = '';
   document.addHead.dedAccountId.value = '';
   document.addHead.headDesc.value = '';
   document.addHead.headName.focus();
}

//Edit existing salary head
function editHead() {
         
         if(document.editHead.headDesc.value.length>60)
         {
             alert("<?php echo DESCRIPTION_EXCEEDS_LIMIT;?>");
             return;
         }
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitEditHead.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {headName: (document.editHead.headName.value), headType: (document.editHead.headType.value), dedAccountId: (document.editHead.dedAccountId.value), headDesc: (document.editHead.headDesc.value), headId:(document.editHead.headId.value), headAbbr: (document.editHead.headAbbr.value)},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditHead');
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                     //location.reload();
                 }
                 else {
                     messageBox(trim(transport.responseText));
                     if (trim(transport.responseText)=='<?php echo HEAD_NAME_ALREADY_EXIST; ?>'){
                        document.editHead.headName.focus();
                    }
                    else if(trim(transport.responseText)=='<?php echo SELECT_DEDUCTION_ACCOUNT; ?>') {
                       document.editHead.dedAccountId.focus();
                    }
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//Delete existing salary head
function deleteHead(id) {  
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxInitDeleteHead.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {headId: id},
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

//To enable/disable deduction accounts dropdown
function getDeductionAccounts(opt,frm)
{ 
    if(opt==0 && frm=='add')
    {
      document.addHead.dedAccountId.value="";
      document.addHead.dedAccountId.disabled=true;  
    }
    else if(opt==0 && frm=='edit')
    {
      document.editHead.dedAccountId.value="";
      document.editHead.dedAccountId.disabled=true;  
    }
    if(opt==1 && frm=='add')
    {
        document.addHead.dedAccountId.disabled=false;
           
    }
    else if(opt==1 && frm=='edit')
    {
      document.editHead.dedAccountId.disabled=false;  
    }
}

// Getting vealues of a specific head for edit
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetHeadValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {headId: id},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    document.editHead.headId.value=id;
                    document.editHead.headName.value = j.headName;
                    document.editHead.headAbbr.value = j.headAbbr;
                    document.editHead.headType.value = j.headType;
                    document.editHead.dedAccountId.value = j.dedAccountId;
                    document.editHead.headDesc.value = j.headDesc;
                    document.editHead.headName.focus();
                    //alert(document.editHead.headType.value);
                    if(j.headType==1)
                    {
                        document.editHead.dedAccountId.disabled=false;
                    }
                    else
                    {
                        document.editHead.dedAccountId.value=''; 
                        document.editHead.dedAccountId.disabled=true;
                    }   
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function populateHeadDescValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Payroll/ajaxGetHeadDescValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {headId: id},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    
                   document.headDescForm.headName.value = j.headName;
                   document.headDescForm.headDesc.value = j.headDesc;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/displayHeadsReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"SalaryHeadReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayHeadsCSV.php?'+qstr;
    window.location = path;
}




//This function Displays Div Window
function head_desc(id) {  
    displayWindow('ViewHeadDesc',370,350);
    populateHeadDescValues(id);   
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Payroll/listHead.php");   
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

</body>
</html>
