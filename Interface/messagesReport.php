<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MessagesReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Messages List</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period    room                               
var tableHeadArray = new Array(
                         new Array('srNo',           '#','width="2%"','',false),
                         new Array('dated',          'Date','width="8%"','align="center"',true),
                         new Array('userName',       'Sender','width="8%"','align="left"',true),
                         //new Array('cnt',            'No. of Messages','width="12%"','align="right"',true), 
                         new Array('subject',        "Subject",'width="14%"','align="left"',true),
                         new Array('messageType',    'Type ','width="12%"','align="left"',true),
						 new Array('receiverType',        "Receiver",'width="14%"','align="left"',true),
                        // new Array('receiverIds',   'Receiver','width="8%"','align="left"',true),
                         new Array('message',        'Brief Description','width="15%"','align="left"',true),
                         new Array('action1',        'Status','width="2%"','align="center"',false)
                     );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/MessagesReport/ajaxMessagesReportList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'dated';
sortOrderBy = 'DESC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var messageId = '';
//var listObj;
function showUndeliveredMessages(id,receverType,dv,w,h) {
   messageId = id;
   populateUndeliveredMessages(id,receverType);
}

function showDeliveredMessages(id,receverType,dv,w,h){
    messageId = id;
	populateDeliveredMessages(id,receverType);
}


function populateDeliveredMessages(id,rcvrType) {
	 var url = '<?php echo HTTP_LIB_PATH;?>/MessagesReport/ajaxDeliveredMessagesList.php';   
	 var tableHeadArray3 = new Array(
									 new Array('srNo','#','width="1%"','',false),
									 new Array('userName','User Name ','width="33%" align="left"',true),
									 new Array('role','Role','width="33%" align="left"',true),
									 new Array('name','Name','width="43%" align="left"',true)
								 );
	
	 listObj1 = new 
	 initPage(url,recordsPerPage,linksPerPage,1,'','deliverdMessagesForm','ASC','divDeliveredMessages','','',true,'listObj1',tableHeadArray3,'','','messageId='+id+'&receverType='+rcvrType);
	 sendRequest(url, listObj1,'',false);
	 displayWindow('deliverdMessages','500','500');
	 return false;
}
function showMessageDetails(id) {
    //displayWindow('divMessage',600,600);
    displayWindow('briefDescription','500','500');
    populateMessageValues(id);
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Parveen Sharma
// Created on : (27.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateMessageValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/SMSReports/ajaxGetMessageDetails.php';   
         new Ajax.Request(url,
         {      
             method:'post',
             parameters: {messageId: id},
              onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                hideWaitDialog();
                j = eval('('+transport.responseText+')');
                document.getElementById('briefmessage').innerHTML= j.message;  
             },
             onFailure: function(){ alert('Something went wrong...') }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Parveen Sharma
// Created on : (27.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateUndeliveredMessages(id,rcvrType) {

	var url = '<?php echo HTTP_LIB_PATH;?>/MessagesReport/ajaxUndeliveredMessagesList.php';
	 var tableHeadArray2 = new Array(
									 new Array('srNo','#','width="1%"','',false),
									 new Array('userName','User Name ','width="33%" align="left"',true),
									 new Array('role','Role','width="33%" align="left"',true),
									 new Array('name','Name','width="43%" align="left"',true)
								 );
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','MessageForm','ASC','message','','',true,'listObj',tableHeadArray2,'','','messageId='+id+'&receverType='+rcvrType);
 sendRequest(url, listObj, '',true );

	 //sendRequest(url, listObj,'',false);
	 displayWindow('divMessage','500','500');
	 return false;
}

function validateAddForm(frm) {

    var fieldsArray = new Array(new Array("fromDate","<?php echo EMPTY_FROM_DATE;?>"), 
                                new Array("toDate","<?php echo EMPTY_TO_DATE;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
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
    
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    return false;
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function printDeliverdReport() {
    path='<?php echo UI_HTTP_PATH;?>/deliveredMessagesReportPrint.php?messageId='+messageId+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

function printDeliveredReportCSV(){
	  path='<?php echo UI_HTTP_PATH;?>/deliveredMessagesReportPrintCSV.php?messageId='+messageId+'&sortOrderBy='+listObj1.sortOrderBy+'&sortField='+listObj1.sortField; 
	  window.location=path;
}
function printUndeliveredReport() {
    path='<?php echo UI_HTTP_PATH;?>/undeliveredMessagesReportPrint.php?messageId='+messageId+'&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

function printUndeliveredReportCSV() {
    path='<?php echo UI_HTTP_PATH;?>/undeliveredMessagesReportPrintCSV.php?messageId='+messageId+'&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

function printReport() {
	if(document.listForm.searchOrder[0].checked==true) {   
		   searchOrder= 1;  
		}
		else {
		   searchOrder= 2;  
		}
    form = document.listForm;
    msg=document.getElementById('messageType').options[document.getElementById('messageType').selectedIndex].text;
    receiver=document.getElementById('receiverType').options[document.getElementById('receiverType').selectedIndex].text;
    txtSearch=document.getElementById('txtSearch').value;
    str="&messageName="+msg+"&receiverName="+receiver+"&txtSearch="+txtSearch+"&searchOrder="+searchOrder;
    
    path='<?php echo UI_HTTP_PATH;?>/messagesReportPrint.php?fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&messageType='+form.messageType.value+'&receiverType='+form.receiverType.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str;

    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all payment history to csv*/
function printReportCSV() {
	if(document.listForm.searchOrder[0].checked==true) {   
		   searchOrder= 1;  
		}
		else {
		   searchOrder= 2;  
		}
    form = document.listForm;
    msg=document.getElementById('messageType').options[document.getElementById('messageType').selectedIndex].text;
    receiver=document.getElementById('receiverType').options[document.getElementById('receiverType').selectedIndex].text;
    txtSearch=document.getElementById('txtSearch').value;
    str="&messageName="+msg+"&receiverName="+receiver+"&txtSearch="+txtSearch+"&searchOrder="+searchOrder;
    
    path='<?php echo UI_HTTP_PATH;?>/messagesReportPrintCSV.php?fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&messageType='+form.messageType.value+'&receiverType='+form.receiverType.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str; 
    window.location=path;
    //document.getElementById('generateCSV').href=path;
    //document.getElementById('generateCSV1').href=path;
}
</script>
</head>
<body>
<?php   
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/MessagesReport/messagesReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
