<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BroadcastMessage');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/BroadcastMessage/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Broadcast Message</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('messageDate','Date','width="10%"','align="center"',true) , 
                               new Array('messageText','Message Text','width="86%"','',true), 
                               new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BroadcastMessage/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBroadcastMessage';   
editFormName   = 'EditBroadcastMessage';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBroadcastMessage';
divResultName  = 'results';
page=1; //default page
sortField = 'messageDate';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

var serverDate="<?php echo date('Y-m-d');?>";
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("msgText","<?php echo ENTER_MESSAGE_TEXT;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
     }
     
    if(act=='Add') {
        if(!dateDifference(serverDate,document.getElementById('msgDate1').value,'-')){
            messageBox("<?php echo MESSAGE_DATE_RESTRICTION?>");
            document.getElementById('msgDate1').focus();
            return false;
        }
        addBroadcastMessage();
        return false;
    }
    else if(act=='Edit') {
        if(!dateDifference(serverDate,document.getElementById('msgDate2').value,'-')){
            messageBox("<?php echo MESSAGE_DATE_RESTRICTION?>");
            document.getElementById('msgDate2').focus();
            return false;
        }
        editBroadcastMessage();
        return false;
    }
}

function addBroadcastMessage() {
         var url = '<?php echo HTTP_LIB_PATH;?>/BroadcastMessage/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 msgDate: (document.getElementById('msgDate1').value), 
                 msgText: trim(document.AddBroadcastMessage.msgText.value)
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
                             hiddenFloatingDiv('AddBroadcastMessage');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.getElementById('msgDate1').focus(); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


function deleteBroadcastMessage(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/BroadcastMessage/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else{
                         messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}

function blankValues() {
   document.AddBroadcastMessage.reset();
   document.getElementById('msgDate1').value = serverDate;
   document.AddBroadcastMessage.msgText.focus();
}


function editBroadcastMessage() {
         var url = '<?php echo HTTP_LIB_PATH;?>/BroadcastMessage/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 msgId: (document.EditBroadcastMessage.msgId.value), 
                 msgDate: (document.getElementById('msgDate2').value), 
                 msgText: trim(document.EditBroadcastMessage.msgText.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBroadcastMessage');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.getElementById('msgDate2').focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}


function populateValues(id) {
         document.EditBroadcastMessage.reset();
         var url = '<?php echo HTTP_LIB_PATH;?>/BroadcastMessage/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBroadcastMessage');
                        messageBox("<?php echo MESSAGE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   
                   document.EditBroadcastMessage.msgText.value = j.messageText;
                   document.getElementById('msgDate2').value = j.messageDate;
                   document.EditBroadcastMessage.msgId.value = j.messageId;
                   document.EditBroadcastMessage.msgText.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/BroadcastMessage/listBroadcastMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>