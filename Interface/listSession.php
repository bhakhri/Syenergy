<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Session Form
//
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SessionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Session/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Session Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo',           '#',              'width="5%"','',false), 
                                new Array('sessionName',    'Session Name ',  'width="20%"','',true) , 
                                new Array('sessionYear',    'Session Year',   'width="15%"','align="center"',true), 
                                new Array('abbreviation',   'Abbr.',   'width="15%"','',true), 
                                new Array('startDate',      'Start Date',     'width="15%"','align="center"',true), 
                                new Array('endDate',        'End Date',       'width="15%"','align="center"',true), 
                                new Array('active',         'Active',         'width="10%"','align="center"',true), 
                                new Array('action',         'Action',         'width="10%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Session/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSession';   
editFormName   = 'EditSession';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSession';
divResultName  = 'results';
page=1; //default page
sortField = 'sessionYear';
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
        new Array("sessionName","<?php echo ENTER_SESSION_NAME;?>"), 
        new Array("fromDate","Select Session Start Date"),
        new Array("toDate","Select Session End Date"));
    }
    else if(act=='Edit') {
        var fieldsArray = new Array(
        new Array("sessionName","<?php echo ENTER_SESSION_NAME;?>"), 
        new Array("fromDate1","Select Session Start Date"),
        new Array("toDate1","Select Session End Date"));
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
        else {
            includeChars = " -()";
            if(!isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value"), includeChars) && fieldsArray[i][0]!='sessionYear') {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("All alphabetic, numeric and only "+includeChars+" special characters are allowed");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }

        if(act=='Add') {
              if(eval("frm.fromDate.value")=="0000-00-00") {
                messageBox ("<?php echo SESSION_FROM_DATE;?>");
                eval("frm.fromDate.focus();");
                return false;
                break; 
              }
              if(eval("frm.toDate.value")=="0000-00-00") {
                messageBox ("<?php echo SESSION_TO_DATE;?>");
                eval("frm.toDate.focus();");
                return false;
                break; 
              }
              if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-') ) {
                 messageBox ("<?php echo DATE_CONDITION;?>");
                 eval("frm.fromDate.focus();");
                 return false;
                 break;
              } 
        }
        
        if(act=='Edit') {
            if(eval("frm.fromDate1.value")=="0000-00-00") {
                messageBox ("<?php echo SESSION_FROM_DATE;?>");
                eval("frm.fromDate1.focus();");
                return false;
                break; 
            }
            if(eval("frm.toDate1.value")=="0000-00-00") {
                messageBox ("<?php echo SESSION_TO_DATE;?>");
                eval("frm.toDate1.focus();");
                return false;
                break; 
            }
            if(!dateDifference(eval("frm.fromDate1.value"),eval("frm.toDate1.value"),'-') ) {
                messageBox ("<?php echo DATE_CONDITION;?>");
                eval("frm.fromDate1.focus();");
                return false;
                break;
            } 
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
         url = '<?php echo HTTP_LIB_PATH;?>/Session/ajaxInitAdd.php';
         
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
         url = '<?php echo HTTP_LIB_PATH;?>/Session/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {sessionName: (document.editSession.sessionName.value), 
                          fromDate: (document.editSession.fromDate1.value), 
                          toDate: (document.editSession.toDate1.value), 
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
        
         url = '<?php echo HTTP_LIB_PATH;?>/Session/ajaxInitDelete.php';
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
         url = '<?php echo HTTP_LIB_PATH;?>/Session/ajaxGetValues.php';
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
                    
                   document.editSession.sessionId.value = id;
                   document.editSession.sessionName.value = j.sessionName;
                   document.editSession.fromDate1.value = j.startDate;
                   document.editSession.toDate1.value = j.endDate;
                   document.editSession.Active[0].checked = (j.active=="1" ? true : false) ;
                   document.editSession.Active[1].checked = (j.active=="1" ? false : true) ;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/displaySessionReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplaySessionReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displaySessionCSV.php?'+qstr;
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
    require_once(TEMPLATES_PATH . "/Session/listSessionContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>    
</body>
</html>