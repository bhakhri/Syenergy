<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AppraisalTab');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Appraisal Tab Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('appraisalTabName','Appraisal Tab Name','','',true),
                                new Array('appraisalProofText','Proof Text','','',true), 
                                new Array('action','Action','width="2%"','align="center"',false)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Tab/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddAppraisalTab';   
editFormName   = 'EditAppraisalTab';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteAppraisalTab';
divResultName  = 'results';
page=1; //default page
sortField = 'appraisalTabName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
            new Array("tabName","<?php echo ENTER_APPRAISAL_TAB_NAME;?>"),
            new Array("tabProofText","Enter proof text")
        );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='tabName' ) {
                messageBox("<?php echo APPRAISAL_TAB_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
        }
     
    }
    if(act=='Add') {
        addAppraisalTab();
        return false;
    }
    else if(act=='Edit') {
        editAppraisalTab();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addAppraisalTab() {
         url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Tab/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 tabName      : trim(document.AddAppraisalTab.tabName.value),
                 tabProofText : trim(document.AddAppraisalTab.tabProofText.value)
             },
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
                             hiddenFloatingDiv('AddAppraisalTab');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo APPRAISAL_TAB_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo APPRAISAL_TAB_NAME_ALREADY_EXIST ;?>"); 
                         document.AddAppraisalTab.tabName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddAppraisalTab.tabName.focus(); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE A NEW CITY
// id=cityId
// Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function deleteAppraisalTab(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Tab/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 tabId: id
             },
             onCreate: function() {
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addAppraisalTab" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddAppraisalTab.reset();
   document.AddAppraisalTab.tabName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editAppraisalTab() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Tab/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 tabId        : (document.EditAppraisalTab.tabId.value), 
                 tabName      : trim(document.EditAppraisalTab.tabName.value),
                 tabProofText : trim(document.EditAppraisalTab.tabProofText.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditAppraisalTab');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo APPRAISAL_TAB_NAME_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo APPRAISAL_TAB_NAME_ALREADY_EXIST ;?>"); 
                         document.EditAppraisalTab.tabName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.EditAppraisalTab.tabName.focus();                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editAppraisalTab" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         document.EditAppraisalTab.reset();
         url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Tab/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 tabId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditAppraisalTab');
                        messageBox("<?php echo APPRAISAL_TAB_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   var j = eval('('+transport.responseText+')');
                   
                   document.EditAppraisalTab.tabName.value = j.appraisalTabName;
                   document.EditAppraisalTab.tabProofText.value = j.appraisalProofText;
                   document.EditAppraisalTab.tabId.value = j.appraisalTabId;
                   document.EditAppraisalTab.tabName.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listAppraisalTabPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"AppraisalTabReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listAppraisalTabCSV.php?'+qstr;
    window.location = path;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Appraisal/Tab/listAppraisalTabContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listAppraisalTab.php $ 
?>