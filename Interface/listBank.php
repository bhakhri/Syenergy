<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Bank Form
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
define('MODULE','BankMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Bank/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bank Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>                                                 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tempBankBranchId='';

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                                new Array('bankName','Bank Name','width="35%"','',true) , 
                                new Array('bankAbbr','Abbr.','width="30%"','',true),
                                new Array('bankAddress','Address','width="30%"','',true),
                                new Array('addBranch','Add Branch','width="15%"','align="center"',false),
                                new Array('viewBranch','View/Edit Branch','width="15%"','align="center"',false), 
                                new Array('action','Action','width="2%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Bank/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBank';   
editFormName   = 'EditBank';
winLayerWidth  = 270; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBank';
divResultName  = 'results';
page=1; //default page
sortField = 'bankName';
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
    var fieldsArray = new Array(new Array("bankName","<?php echo ENTER_BANK_NAME;?>"), new Array("bankAbbr","<?php echo ENTER_BANK_ABBR;?>"));
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
        addBank();
        return false;
    }
    else if(act=='Edit') {
        editBank();  
        return false;
    }
}

function addBank() {
         url = '<?php echo HTTP_LIB_PATH;?>/Bank/ajaxInitAdd.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankName: (document.addBank.bankName.value), 
                          bankAbbr: (document.addBank.bankAbbr.value),
                          bankAddress: (document.addBank.bankAddress.value)
                         },
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
                         hiddenFloatingDiv('AddBank');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                     }
                 } 
                 else {
                    messageBox(trim(transport.responseText));
                    if (trim(transport.responseText)=='<?php echo BANK_NAME_ALREADY_EXIST ?>'){
                        document.addBank.bankName.focus();
                    }
                    else {
                        document.addBank.bankAbbr.focus();
                    }
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addBank.bankName.value = '';
   document.addBank.bankAbbr.value = '';
   document.addBank.bankName.focus();
}
function editBank() {
         url = '<?php echo HTTP_LIB_PATH;?>/Bank/ajaxInitEdit.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankName: (document.editBank.bankName.value), 
                          bankAbbr: (document.editBank.bankAbbr.value),
                          bankAddress: (document.editBank.bankAddress.value),
                          bankId:   (document.editBank.bankId.value)},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) { alert(transport.responseText);
                     hiddenFloatingDiv('EditBank');
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                     //location.reload();
                 }
                 else {
                     messageBox(trim(transport.responseText));
                     if (trim(transport.responseText)=='<?php echo BANK_NAME_ALREADY_EXIST ?>'){
                        document.editBank.bankName.focus();
                    }
                    else {
                        document.editBank.bankAbbr.focus();
                    }
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteBank(id) {  
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Bank/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankId: id},
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
         url = '<?php echo HTTP_LIB_PATH;?>/Bank/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankId: id},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    
                   document.editBank.bankId.value = id;
                   document.editBank.bankName.value = j.bankName;
                   document.editBank.bankAbbr.value = j.bankAbbr;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/displayBankReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"BankReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayBankCSV.php?'+qstr;
    window.location = path;
}


// Bank Branch Start

//This function Displays Div Window
function editWindow1(id,dv,w,h) {
   
    displayWindow(dv,w,h);
    populateValues1(id);   
}
function validateAddForm1(frm, act) {
    
    //messageBox (act)
    var fieldsArray = new Array(new Array("bankId","<?php echo SELECT_BANK;?>"), new Array("branchName","<?php echo ENTER_BRANCH_NAME;?>"), new Array("branchAbbr","<?php echo ENTER_BRANCH_ABBR;?>"), new Array("accountType","<?php echo ENTER_ACCOUNT_TYPE;?>"));
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

function checkDisable(id) {
   
       if(eval("document.getElementById('chb"+(id)+"').checked==true")) {
          eval("document.getElementById('branchName"+(id)+"').disabled = false");
          eval("document.getElementById('branchAbbr"+(id)+"').disabled = false");
          eval("document.getElementById('accountType"+(id)+"').disabled = false");
          eval("document.getElementById('accountNumber"+(id)+"').disabled = false");
          eval("document.getElementById('operator"+(id)+"').disabled = false");
          eval("document.getElementById('status"+(id)+"').value = 'Y'");
      }
       else {
          eval("document.getElementById('branchName"+(id)+"').disabled= true");
          eval("document.getElementById('branchAbbr"+(id)+"').disabled= true");
          eval("document.getElementById('accountType"+(id)+"').disabled= true");
          eval("document.getElementById('accountNumber"+(id)+"').disabled= true");
          eval("document.getElementById('operator"+(id)+"').disabled = true");
          eval("document.getElementById('status"+(id)+"').value = 'N'");
       }   
}
function doAll(){
   
   var formx = document.divForm11;
   
  
   if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {  
              formx.elements[i].checked=true;
              id=formx.elements[i].value;
              //route = "bankBranchId"+id;
              
                 route = "branchName"+id;
                 document.getElementById(route).disabled=false;
                
                 
                 route = "branchAbbr"+id;
                 document.getElementById(route).disabled=false;
                 
                 
                 route = "accountType"+id;
                 document.getElementById(route).disabled=false;
                 
                 
                 route = "accountNumber"+id;
                 document.getElementById(route).disabled=false;
                 
                 route = "operator"+id;
                 document.getElementById(route).disabled=false;

                 route = "status"+id;
                 document.getElementById(route).value = 'Y';
           }
        }  // end for loop
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {  
              formx.elements[i].checked=false;
              id=formx.elements[i].value;
              //route = "bankBranchId"+id;
             
                 route = "branchName"+id;
                 document.getElementById(route).disabled=true;
                 
                 
                 route = "branchAbbr"+id;
                 document.getElementById(route).disabled=true;
                 
                 
                 route = "accountType"+id;
                 document.getElementById(route).disabled=true;
                 
                 
                 route = "accountNumber"+id;
                 document.getElementById(route).disabled=true;
                 
                 route = "operator"+id;
                 document.getElementById(route).disabled=true;
                 
                 route = "status"+id;
                 document.getElementById(route).value = 'N';
           } 
        }  // end for loop
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
                       document.addBankBranch.branchName.value = '';
                       document.addBankBranch.branchAbbr.value = '';
                       document.addBankBranch.accountType.value = '';
                       document.addBankBranch.accountNumber.value = '';
                       document.addBankBranch.operator.value = '';
                       document.addBankBranch.branchName.focus();
                     }
                     else { 
                         hiddenFloatingDiv('AddBankBranch');
                         //listBankBranchWindow(tempBankBranchId); 
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
function blankValues1() {
    
   //document.addBankBranch.bankId.value = '';
   /*
   document.addBankBranch.branchName.value = '';
   document.addBankBranch.branchAbbr.value = '';
   document.addBankBranch.accountType.value = '';
   document.addBankBranch.accountNumber.value = '';
   document.addBankBranch.operator.value = '';
   document.addBankBranch.bankId.focus();
   */
}
function editBranch() {
        
    formx = document.divForm11;
     
    var selected=0;
    studentCheck='';
    
    var formx = document.divForm11;
   
    for(var i=1;i<formx.length;i++){
       
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                }             
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        } 
    }
    if(selected==0)    { 
       alert("Please select atleast one record!");
       return false;
    }
      
      
     // Check Validations         
     for(var i=1;i<formx.length;i++) {
         if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && (formx.elements[i].checked) ){
              id=formx.elements[i].value;
                 
                    route= "branchName"+formx.elements[i].value;
                    routeValue = document.getElementById(route).value;
                    if(routeValue==''){
                      messageBox("<?php echo ENTER_BRANCH_NAME?>");
                      document.getElementById(route).focus(); 
                      return false;
                    }
                   
                    route= "branchAbbr"+formx.elements[i].value;
                    routeValue = document.getElementById(route).value;
                    if(routeValue==''){
                      messageBox("<?php echo ENTER_BRANCH_ABBR?>");
                      document.getElementById(route).focus(); 
                      return false;
                    }
                   
                    route= "accountType"+formx.elements[i].value;
                    routeValue = document.getElementById(route).value;
                    if(routeValue==''){
                      messageBox("<?php echo ENTER_ACCOUNT_TYPE; ?>");      
                      document.getElementById(route).focus();
                      return false;
                    } 
                   
                   
                    /*route= "accountNumber"+formx.elements[i].value;
                    routeValue = document.getElementById(route).value;
                    if(routeValue==''){
                        messageBox("<?php echo ENTER_ACCOUNT_NUMBER; ?>");      
                        document.getElementById(route).focus();
                        return false;
                    }*/
              }
       }
       
     url = '<?php echo HTTP_LIB_PATH;?>/Bank/ajaxInitBankBranchEdit.php';
      
       var pars = generateQueryString('divForm11');
       new Ajax.Request(url,
       {
         method:'post',
         parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     //hiddenFloatingDiv('resultInfo');
                     messageBox(trim(transport.responseText)); 
                     listBankBranchWindow(tempBankBranchId); 
                     doAll();
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

function editBankBranch() {
         url = '<?php echo HTTP_LIB_PATH;?>/BankBranch/ajaxInitEdit.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {bankId: (document.editBankBranch.bankId.value), 
                          bankBranchId: (document.editBankBranch.bankBranchId.value), 
                          branchName: trim(document.editBankBranch.branchName.value), 
                          branchAbbr: trim(document.editBankBranch.branchAbbr.value), 
                          accountType: trim(document.editBankBranch.accountType.value), 
                          accountNumber: trim(document.editBankBranch.accountNumber.value), 
                          operator:trim(document.editBankBranch.operator.value)},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditBankBranch');
                     listBankBranchWindow(tempBankBranchId);
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
                    listBankBranchWindow(tempBankBranchId);
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

function populateValues1(id) {
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
                   document.editBankBranch.bankId.disabled = true;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getBankData(id) {
        
       var url = '<?php echo HTTP_LIB_PATH;?>/BankBranch/initBankName.php';
       document.addBankBranch.bankId.length = null;
       new Ajax.Request(url,
       {
         method:'post',
         parameters: {bankId: id },
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;
                document.addBankBranch.bankId.length = null;
               // document.addBankBranch.bankId.value = id;
                addOption(document.addBankBranch.bankId, '', 'Select');
                for(i=0;i<len;i++) { 
                    addOption(document.addBankBranch.bankId, j[i].bankId, j[i].bankAbbr);
                    
                }
                document.addBankBranch.bankId.value = id;
                document.addBankBranch.bankId.disabled = true;  
                document.addBankBranch.branchName.value = '';
                document.addBankBranch.branchAbbr.value = '';
                document.addBankBranch.accountType.value = '';
                document.addBankBranch.accountNumber.value = '';
                document.addBankBranch.operator.value = '';
                document.addBankBranch.branchName.focus();
         },
         onFailure: function(){messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function addBankBranchWindow(id) {
     dv='AddBankBranch';
     w=320;
     h=250;  
     //alert(dv+'  '+w+ ' '+h);
     getBankData(id);
     displayWindow(dv,w,h);
     //populateValues2(id);  
}

function populateValues2(id) {
    //alert(id);
    document.addBankBranch.bankId.value = id;
   document.addBankBranch.bankId.disabled = false;  
   document.addBankBranch.branchName.value = '';
   document.addBankBranch.branchAbbr.value = '';
   document.addBankBranch.accountType.value = '';
   document.addBankBranch.accountNumber.value = '';
   document.addBankBranch.operator.value = '';
   document.addBankBranch.branchName.focus();
}

function listBankBranchWindow(id) {
     //return false;
     dv = 'divBankBranchInformation';
     w=810;
     h=400; 
      
     url = '<?php echo HTTP_LIB_PATH;?>/Bank/ajaxInitBankBranchList.php';  
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {id: id},
         onCreate: function() {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==0) {
            //hiddenFloatingDiv('resultInfo');
           //messageBox("<?php echo "Bank Branch List not present"; ?>");
           //return false;
         }
         tempBankBranchId = id;
         j = trim(transport.responseText);
         document.getElementById('resultInfo').innerHTML= j;    
         displayWindow(dv,w,h);
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}


// Bank Branch End

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Bank/listBankContents.php");
    
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

</body>
</html>
<?php 
// $History: listBank.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Interface
//updated with all the fees enhancements
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 12:58p
//Updated in $/LeapCC/Interface
//Merged Bank & BankBranch module in single module
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/30/09    Time: 10:33a
//Created in $/LeapCC/Interface
//file added
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/20/09    Time: 10:21a
//Updated in $/LeapCC/Interface
//fixed bug nos.0001145,  0001127, 0001126, 0001125, 0001119, 0001101,
//0001110
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/11/09    Time: 12:28p
//Updated in $/LeapCC/Interface
//remove validations
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/08/09    Time: 1:16p
//Updated in $/LeapCC/Interface
//fixed issues nos.0000356,0000357,0000444,0000445
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
//User: Parveen      Date: 11/10/08   Time: 11:56a
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
//User: Ajinder      Date: 7/23/08    Time: 6:16p
//Updated in $/Leap/Source/Interface
//Fixed minor designing issue found during self-testing
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:40p
//Created in $/Leap/Source/Interface
//File created for Bank Master
?>
