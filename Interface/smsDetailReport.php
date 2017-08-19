<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MessagesList');
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
                         new Array('dated',          'Date Sent','width="8%"','align="center"',true),
                         new Array('userName',       'Sender','width="8%"','align="left"',true),
                         new Array('cnt',            'No. of Messages','width="12%"','align="right"',true), 
                         new Array('subject',        "Subject",'width="14%"','align="left"',true),
                         new Array('messageType',    'Type of Messges','width="12%"','align="left"',true),
                         new Array('receiverType',   'Receiver','width="8%"','align="left"',true),
                         new Array('message',        'Brief Description','width="15%"','align="left"',true),
                         new Array('action1',        'Action','width="2%"','align="center"',false)
                     );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/SMSReports/smsDetailReport.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'dated';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h){
    displayWindow(dv,w,h);
    populateValues(id);
}

function showMessageDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
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
                document.getElementById('message').innerHTML= j.message;  
             },
             onFailure: function(){ alert('Something went wrong...') }
           });
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
    //sendReq(listURL,divResultName,'listForm','');
    return false;
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
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
    
    path='<?php echo UI_HTTP_PATH;?>/smsDetailReportPrint.php?fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&messageType='+form.messageType.value+'&receiverType='+form.receiverType.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str;
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
    
    path='<?php echo UI_HTTP_PATH;?>/smsDetailReportPrintCSV.php?fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&messageType='+form.messageType.value+'&receiverType='+form.receiverType.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str; 

    window.location=path;
    //document.getElementById('generateCSV').href=path;
    //document.getElementById('generateCSV1').href=path;
}

 
</script>
</head>
<body>
<?php   
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/SMSReports/smsDetailReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
// $History: smsDetailReport.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/02/09   Time: 3:40p
//Updated in $/LeapCC/Interface
//meta tag format updated (utf-8)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/24/09   Time: 4:47p
//Updated in $/LeapCC/Interface
//sorting format updated (message detials)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/21/09    Time: 3:58p
//Updated in $/LeapCC/Interface
//role permission & removePHPJS  function updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/20/09    Time: 4:14p
//Updated in $/LeapCC/Interface
//new enhancement added Action button perform Berif Description added 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/12/09    Time: 1:16p
//Updated in $/LeapCC/Interface
//file name changes (lowercase)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 5/19/09    Time: 2:36p
//Updated in $/LeapCC/Interface
//code update search for & condition update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/31/09    Time: 5:46p
//Updated in $/LeapCC/Interface
//formatting settings
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
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Interface
//list and report formatting
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/28/08   Time: 12:21p
//Updated in $/Leap/Source/Interface
//list formatting
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/28/08   Time: 10:45a
//Updated in $/Leap/Source/Interface
//changed lists view format
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Interface
//add fields messages
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/26/08   Time: 5:06p
//Updated in $/Leap/Source/Interface
//sms details report added
//


?>
