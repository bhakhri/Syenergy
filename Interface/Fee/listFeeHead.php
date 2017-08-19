<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in "Fee Head" Form
// Author :Nishu Bindal 
// Created on : 2-feb-2011
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadsNew');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Head New</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('headName','Name','width="25%"','',true),
                               new Array('headAbbr','Abbr.','width="10%"','',true),
                               new Array('isRefundable','Refundable Security','width="18%" align="center"','align="center"',true), 
                               new Array('isConsessionable','Concessionable','width="14%" align="center"','align="center"',true),  
                               new Array('isSpecial','Miscellaneous','width="12%" align="center"','align="center"',true),
                               new Array('sortingOrder','Display Order','width="12%" align="right"','align="right"',true), 
                               new Array('action','Action','width="10%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHead/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeeHead';   
editFormName   = 'EditFeeHead';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteFeeHead';
divResultName  = 'results';
page=1; //default page
sortField = 'headName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

//This function Validates Form 
function validateAddForm(frm, act){
   
    var fieldsArray = new Array(new Array("headName","<?php echo ENTER_FEEHEAD_NAME;?>"),
                                new Array("headAbbr","<?php echo ENTER_FEEHEAD_ABBR;?>"),
                                new Array("sortOrder","<?php echo ENTER_FEEHEAD_ORDER;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
     
            if(fieldsArray[i][0]=='sortOrder' ) {
               if(!isNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                  messageBox ("Enter the numeric value");
                  eval("frm."+(fieldsArray[i][0])+".focus();");
                  return false;
                  break;
               }
               if(eval("frm."+(fieldsArray[i][0])+".value")<=0)  {
                  messageBox ("Display Order should not be zero ");
                  eval("frm."+(fieldsArray[i][0])+".focus();");
                  return false;
                  break;
               }
            }
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                messageBox ("<?php echo ENTER_ALPHABETS_NUMERIC;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
    }
    
    
    if(act=='Add') {
        addFeeHead();
        return false;
    }
    else if(act=='Edit') {
        editFeeHead();    
        return false;
    }
}

//This function adds form through ajax 


function addFeeHead() {
     var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHead/ajaxInitAdd.php';
     
     var isSpecial=0;
     var isRefundable=0;
     var isConsessionable=0;
     if(document.addFeeHead.isRefundable[0].checked==true) {
      isRefundable = 1;
     }
     if(document.addFeeHead.isSpecial[0].checked==true) {
       isSpecial = 1;
     } 
     if(document.addFeeHead.isConsessionable[0].checked==true) {
       isConsessionable = 1;
     } 
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {headName: trim(document.addFeeHead.headName.value), 
                      headAbbr: trim(document.addFeeHead.headAbbr.value),
                      isConsessionable: isConsessionable,
                      isRefundable: isRefundable,
                      isSpecial: isSpecial,
		      sortOrder: trim(document.addFeeHead.sortOrder.value)},
         onCreate: function() {
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
                         hiddenFloatingDiv('AddFeeHead');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                 } 
                 else {
                    messageBox(trim(transport.responseText)); 
                    if("<?php echo FEEHEAD_NAME_EXIST;?>" == trim(transport.responseText)) {  
                      document.addFeeHead.headName.focus(); 
                    }
                    else if("<?php echo FEEHEAD_ABBR_EXIST;?>" == trim(transport.responseText)) {  
                      document.addFeeHead.headAbbr.focus(); 
                    }
                    else if("<?php echo FEEHEAD_DISPLAY_ORDER_EXIST;?>" == trim(transport.responseText)) {  
                      document.addFeeHead.sortOrder.focus(); 
                    }
                 }
           
         },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function getHeadName(){
     groupUrl = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHead/ajaxInitFeeHeadName.php';
     new Ajax.Request(groupUrl,
     {
         method:'post',
         asynchronous :(false),
         parameters: {},
         onCreate: function(transport){
              showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                j = trim(transport.responseText).evalJSON();     
                len=j.info.length;
                document.addFeeHead.parentHeadId.length=null;
                addOption(document.addFeeHead.parentHeadId,'', 'Select');
                for(i=0;i<len;i++) { 
                   addOption(document.addFeeHead.parentHeadId, j.info[i].feeHeadId, j.info[i].headName);
                }
         },
         onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
      });
}

function blankValues() {
   document.addFeeHead.headName.value = '';
   document.addFeeHead.headAbbr.value = '';
   document.addFeeHead.isSpecial[1].checked=true;
   document.addFeeHead.isRefundable[1].checked=true;
   document.addFeeHead.isConsessionable[1].checked=true;  
   document.addFeeHead.sortOrder.value = '';
   document.addFeeHead.headName.focus();
  
}

//This function edit form through ajax 

function editFeeHead() {
     var url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHead/ajaxInitEdit.php';
     
     var isRefundable =0;
     var isSpecial=0;
     var isConsessionable=0;
     var abbr='';   
     if(document.editFeeHead.isRefundable[0].checked==true) {
       isRefundable = 1;
     }
     if(document.editFeeHead.isSpecial[0].checked==true) {
       isSpecial = 1;
     } 
     if(document.editFeeHead.isConsessionable[0].checked==true) {
       isConsessionable = 1;
     }
     abbr = trim(document.editFeeHead.headAbbr.value); 
     
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {feeHeadId: trim(document.editFeeHead.feeHeadId.value), 
                      headName: trim(document.editFeeHead.headName.value), 
                      headAbbr: abbr,
                      isConsessionable: isConsessionable, 
                      isRefundable: isRefundable,
                      isSpecial: isSpecial,
                      sortOrder: trim(document.editFeeHead.sortOrder.value)},
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){ 
                hideWaitDialog(true);
                 messageBox(trim(transport.responseText));
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditFeeHead');
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                     //location.reload();
                 }
                 else {
                    if("<?php echo FEEHEAD_NAME_EXIST;?>" == trim(transport.responseText)) {  
                      document.editFeeHead.headName.focus(); 
                    }
                    else if("<?php echo FEEHEAD_ABBR_EXIST;?>" == trim(transport.responseText)) {  
                      document.editFeeHead.headAbbr.focus(); 
                    }
                    else if("<?php echo FEEHEAD_DISPLAY_ORDER_EXIST;?>" == trim(transport.responseText)) {  
                      document.editFeeHead.sortOrder.focus(); 
                    }
                    else if("<?php echo FEEHEAD_PARENT_RELATION;?>" == trim(transport.responseText)) {  
                      document.editFeeHead.parentHeadId.focus(); 
                    }
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

//This function calls delete function through ajax

function deleteFeeHead(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHead/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeHeadId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
              
                     hideWaitDialog(true);
                   //  messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                       return false;
                     }
                     else {
                       messageBox(trim(transport.responseText));
                     } 
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         } 
}




//This function populates values in edit form through ajax 

function populateValues(id){ 
          url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeHead/ajaxGetValues.php';
          new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeHeadId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                hideWaitDialog(true);
                j = eval('('+transport.responseText+')');
                document.editFeeHead.feeHeadId.value = j.feeHeadId; 
                document.editFeeHead.headName.value = j.headName;
                document.editFeeHead.headAbbr.value = j.headAbbr;
                document.editFeeHead.isRefundable[1].checked=true;
                document.editFeeHead.isSpecial[1].checked=true;
                document.editFeeHead.isConsessionable[1].checked=true; 
                if(j.isRefundable==1) {
                  document.editFeeHead.isRefundable[0].checked=true;
                }
                if(j.isSpecial==1) {
                  document.editFeeHead.isSpecial[0].checked=true;
                }
                if(j.isConsessionable==1) {
                  document.editFeeHead.isConsessionable[0].checked=true;
                } 
		document.editFeeHead.sortOrder.value = j.sortingOrder;
                document.editFeeHead.headName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/listFeeHeadReport.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   window.open(path,"FeeHeadReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    path='<?php echo UI_HTTP_PATH;?>/listFeeHeadReportCSV.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.location = path;
}

</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/FeeHead/listFeeHeadContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>
</body>
</html>

