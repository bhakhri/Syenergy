<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Leave Session Form
//
//
// Author :Parveen Sharma
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LeaveSessionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Leave Session Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo',               '#',              'width="5%"','',false), 
                                new Array('sessionName',        'Session Name ',  'width="20%"','',true) , 
                                new Array('sessionStartDate',   'Start Date',     'width="15%"','align="center"',true), 
                                new Array('sessionEndDate',     'End Date',       'width="15%"','align="center"',true), 
                                new Array('active',             'Active',         'width="10%"','align="center"',true), 
                                new Array('action',             'Action',         'width="10%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/LeaveSession/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSession';   
editFormName   = 'EditSession';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSession';
divResultName  = 'results';
page=1; //default page
sortField = 'sessionName';
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
    if(act=='Add') {
        var fieldsArray = new Array(
        new Array("sessionName","<?php echo ENTER_LEAVE_SESSION_NAME;?>"), 
        new Array("fromDate","<?php echo LEAVE_SESSION_START_DATE;?>"),
        new Array("toDate","<?php echo LEAVE_SESSION_END_DATE;?>"));  
    }
    else if(act=='Edit') {
        var fieldsArray = new Array(
        new Array("sessionName","<?php echo ENTER_LEAVE_SESSION_NAME;?>"), 
        new Array("fromDate1","<?php echo LEAVE_SESSION_START_DATE;?>"),
        new Array("toDate1","<?php echo LEAVE_SESSION_END_DATE;?>"));  
    }

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
      if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-') ) {
        messageBox ("<?php echo DATE_CONDITION;?>");
        eval("frm.fromDate.focus();");
        return false;
      } 
    }

    if(act=='Edit') {
      if(!dateDifference(eval("frm.fromDate1.value"),eval("frm.toDate1.value"),'-') ) {
        messageBox ("<?php echo DATE_CONDITION;?>");
        eval("frm.fromDate1.focus();");
        return false;
      } 
    }   
        
    if(act=='Add') {
        addSession();
        return false;
    }
    else if(act=='Edit') {
        editSession();  
        return false;
    }
}
function addSession() {
         url = '<?php echo HTTP_LIB_PATH;?>/LeaveSession/ajaxInitAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {sessionName: (document.addSession.sessionName.value), 
                          fromDate: (document.addSession.fromDate.value),  
                          toDate: (document.addSession.toDate.value),  
                          Active: (document.addSession.Active[0].checked ? 1 : 0)
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
                             hiddenFloatingDiv('AddSession');
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
   document.addSession.sessionName.value = '';
   document.addSession.fromDate.value = '';
   document.addSession.toDate.value = '';
   document.addSession.Active[0].checked = true;
   document.addSession.sessionName.focus();
}

function editSession() {
         url = '<?php echo HTTP_LIB_PATH;?>/LeaveSession/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {sessionName: (document.editSession.sessionName.value), 
                          fromDate1: (document.editSession.fromDate1.value), 
                          toDate1: (document.editSession.toDate1.value), 
                          sessionId:(document.editSession.sessionId.value), 
                          Active: (document.editSession.Active[0].checked ? 1 : 0 )
             },
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditSession');
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

function deleteSession(id) {  
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/LeaveSession/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {sessionId: id},
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
         url = '<?php echo HTTP_LIB_PATH;?>/LeaveSession/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {sessionId: id},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   j = eval('('+trim(transport.responseText)+')');
                    
                   document.editSession.sessionId.value = j.leaveSessionId;
                   document.editSession.sessionName.value = j.sessionName;
                   document.editSession.fromDate1.value = j.sessionStartDate;
                   document.editSession.toDate1.value = j.sessionEndDate;
                   document.editSession.Active[0].checked = (j.active=="1" ? true : false) ;
                   document.editSession.Active[1].checked = (j.active=="1" ? false : true) ;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/listLeaveSessionPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplaySessionReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listLeaveSessionCSV.php?'+qstr;
	window.location = path;
}
function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.addSession;
 }
 else{
     var form = document.editSession;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/LeaveSession/listSessionContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>    
</body>
</html>