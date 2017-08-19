<?php 

//---------------------------------------------------------------------------------------
// 
//THIS FILE CONTAINS VALIDATION AND AJAX FUNCTION USED IN "FeeHeadAllocation" module
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
//
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FundAllocationMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeeFundAllocation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fund Allocation Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
 // ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('allocationEntity','Allocation Entity','width="50%"','',true), 
                               new Array('entityType','Abbr.','width="20%"','',true), 
                               new Array('action','Action','width="5%"','align="center"',false));
                                                                    
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeeFundAllocation/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeeFundAllocation';   
editFormName   = 'EditFeeFundAllocation';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteFeeFundAllocation';
divResultName  = 'results';
page=1; //default page
sortField = 'allocationEntity';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

//This function Validates Form 


function validateAddForm(frm, act) {
        
    var fieldsArray = new Array(
                                 new Array("allocationEntity","<?php echo ENTER_FEEFUNDALLOCATION_ENTITY;?>"),
                                 new Array("entityType","<?php echo ENTER_FEEFUNDALLOCATION_TYPE;?>")
                               );
    var len = fieldsArray.length;     
    
   //allocationEntity
   //entityType
     
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
        addFeeFundAllocation();
        return false;
    }
    else if(act=='Edit') {
        editFeeFundAllocation();    
        return false;
    }
}

//This function adds form through ajax 


function addFeeFundAllocation() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeFundAllocation/ajaxInitAdd.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {allocationEntity: trim(document.addFeeFundAllocation.allocationEntity.value), 
                          entityType: trim(document.addFeeFundAllocation.entityType.value)},
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
                             hiddenFloatingDiv('AddFeeFundAllocation');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else if("<?php echo ENTER_FEEFUNDALLOCATION_ENTITY;?>" == trim(transport.responseText)) {                     
                        messageBox ("<?php echo ENTER_FEEFUNDALLOCATION_ENTITY;?>");
                        document.addFeeFundAllocation.allocationEntity.focus();
                     }
                     else if("<?php echo ENTER_FEEFUNDALLOCATION_TYPE;?>" == trim(transport.responseText)) {                     
                        messageBox ("<?php echo ENTER_FEEFUNDALLOCATION_TYPE;?>");
                        document.addFeeFundAllocation.entityType.focus();
                     }
                     else if("<?php echo FEEFUNDALLOCATION_ENTITY_EXIST;?>" == trim(transport.responseText)) {                     
                        messageBox ("<?php echo FEEFUNDALLOCATION_ENTITY_EXIST;?>");
                        document.addFeeFundAllocation.allocationEntity.focus();
                     }
                     else if("<?php echo FEEFUNDALLOCATION_TYPE_EXIST;?>" == trim(transport.responseText)) {                     
                        messageBox ("<?php echo FEEFUNDALLOCATION_TYPE_EXIST;?>");
                        document.addFeeFundAllocation.entityType.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               
             },
              onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addFeeFundAllocation.allocationEntity.value = '';
   document.addFeeFundAllocation.entityType.value = '';
   
   
   document.addFeeFundAllocation.allocationEntity.focus();
}

//This function edit form through ajax 

function editFeeFundAllocation() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeFundAllocation/ajaxInitEdit.php';
           
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeFundAllocationId: trim(document.editFeeFundAllocation.feeFundAllocationId.value),
                          allocationEntity: trim(document.editFeeFundAllocation.allocationEntity.value), 
                          entityType: trim(document.editFeeFundAllocation.entityType.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
              
                     hideWaitDialog(true);
               //      messageBox(trim(transport.responseText));
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeeFundAllocation');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                     else if("<?php echo ENTER_FEEFUNDALLOCATION_ENTITY;?>" == trim(transport.responseText)) {                     
                        messageBox ("<?php echo ENTER_FEEFUNDALLOCATION_ENTITY;?>");
                        document.editFeeFundAllocation.allocationEntity.focus();
                     }
                     else if("<?php echo ENTER_FEEFUNDALLOCATION_TYPE;?>" == trim(transport.responseText)) {                     
                        messageBox ("<?php echo ENTER_FEEFUNDALLOCATION_TYPE;?>");
                        document.editFeeFundAllocation.entityType.focus();
                     }
                     else if("<?php echo FEEFUNDALLOCATION_ENTITY_EXIST;?>" == trim(transport.responseText)) {                     
                        messageBox ("<?php echo FEEFUNDALLOCATION_ENTITY_EXIST;?>");
                        document.editFeeFundAllocation.allocationEntity.focus();
                     }
                     else if("<?php echo FEEFUNDALLOCATION_TYPE_EXIST;?>" == trim(transport.responseText)) {                     
                        messageBox ("<?php echo FEEFUNDALLOCATION_TYPE_EXIST;?>");
                        document.editFeeFundAllocation.entityType.focus();
                     }
                     else {
                         messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//This function calls delete function through ajax

function deleteFeeFundAllocation(id) {
     if(false===confirm("Do you want to delete this record?")) {
         return false;
     }
     else {   
         url = '<?php echo HTTP_LIB_PATH;?>/FeeFundAllocation/ajaxInitDelete.php';
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {feeFundAllocationId: id},
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

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeFundAllocation/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeFundAllocationId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
              
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                   document.editFeeFundAllocation.feeFundAllocationId.value = j.feeFundAllocationId;  
                   document.editFeeFundAllocation.allocationEntity.value = j.allocationEntity;
                   document.editFeeFundAllocation.entityType.value = j.entityType;
                   document.editFeeFundAllocation.allocationEntity.focus();
              
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
 function printReport() {  
    var qstr="searchbox="+(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listFeeFundAllocationPrint.php?'+qstr;
    window.open(path,"DisplayFeeFundAllocationList","status=1,menubar=1,scrollbars=1, width=900");
}

 function printReportCSV() {  
    var qstr="searchbox="+(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listFeeFundAllocationCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeeFundAllocation/listFeeFundAllocationContents.php");
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

//$History: listFeeFundAllocation.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/13/09    Time: 10:49a
//Updated in $/LeapCC/Interface
//search condition & CSV format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/10/09    Time: 6:03p
//Updated in $/SnS/Interface
//formating & conditions updated
//issue fix 537, 535, 530, 528, 525, 524
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/08/09    Time: 17:02
//Updated in $/SnS/Interface
//Corrected breadcrumb and titles in SNS's fee modules
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/06/09    Time: 5:24p
//Updated in $/SnS/Interface
//bug fix 526-527, 532-534, 536 duplicate checks & formatting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/22/09    Time: 3:52p
//Updated in $/SnS/Interface
//condition & formatting, required parameter checks updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:08a
//Updated in $/SnS/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 12:49
//Updated in $/SnS/Interface
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/01/09   Time: 12:11
//Created in $/SnS/Interface
//Added Sns System to VSS(Leap for Chitkara International School)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/22/08   Time: 5:13p
//Updated in $/LeapCC/Interface
//print sorting order set
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 10  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Interface
//Define Module, Access  Added
//
//*****************  Version 9  *****************
//User: Arvind       Date: 10/17/08   Time: 12:49p
//Updated in $/Leap/Source/Interface
//added print functionality
//
//*****************  Version 8  *****************
//User: Arvind       Date: 9/06/08    Time: 3:36p
//Updated in $/Leap/Source/Interface
//removed unsortable class
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/20/08    Time: 2:42p
//Updated in $/Leap/Source/Interface
//REPLACED VALIDATION MESSAGES WITH COMMON MESSAGES
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/07/08    Time: 3:28p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/01/08    Time: 6:35p
//Updated in $/Leap/Source/Interface
//added validation
//
//*****************  Version 4  *****************
//User: Arvind       Date: 8/01/08    Time: 11:27a
//Updated in $/Leap/Source/Interface
//added oncreate function 
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/18/08    Time: 2:26p
//Updated in $/Leap/Source/Interface
//added messageBox in place of alert 
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/12/08    Time: 11:00a
//Updated in $/Leap/Source/Interface
//Removed a validation
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:14a
//Created in $/Leap/Source/Interface
//added a new module file


?>
