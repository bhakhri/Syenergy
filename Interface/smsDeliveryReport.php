<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SMSDeliveryReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: SMS Delivery Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period    room                               
var tableHeadArray = new Array(
                         new Array('srNo','#','width="2%"','',false),
                         new Array('smsFrom','Sent From','width="15%"','align="left"',true),
                         new Array('smsTo','Sent To','width="15%"','align="left"',false),
                         new Array('smsText','Message','width="40%"','align="left"',false), 
                         new Array('sentDate',"Sent On",'width="10%"','align="left"',true),
                         new Array('smsStatus','Status','width="18%"','align="left"',true)
                     );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/SMSReports/smsDeliveryReport.php';
searchFormName = 'smsDeliveryReport'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'sentDate';
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

function validateAddForm(frm) {

    var fieldsArray = new Array(new Array("fromDate","<?php echo EMPTY_FROM_DATE;?>"), new Array("toDate","<?php echo EMPTY_TO_DATE;?>"));

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
    form = document.smsDeliveryReport;
    
    msg=document.getElementById('messageType').options[document.getElementById('messageType').selectedIndex].text;
    //receiver=document.getElementById('receiverType').options[document.getElementById('receiverType').selectedIndex].text;
    str="&messageName="+msg;
    
    path='<?php echo UI_HTTP_PATH;?>/smsDeliveryReportPrint.php?fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&messageType='+form.messageType.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all payment history to csv*/
function printReportCSV() {

    form = document.smsDeliveryReport;
    
    msg=document.getElementById('messageType').options[document.getElementById('messageType').selectedIndex].text;
    str="&messageName="+msg;
    
    path='<?php echo UI_HTTP_PATH;?>/smsDeliveryReportPrintCSV.php?fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&messageType='+form.messageType.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str; 

    window.location=path;
    //document.getElementById('generateCSV').href=path;
    //document.getElementById('generateCSV1').href=path;
}

 
</script>
</head>
<body>
<?php   
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/SMSReports/smsDeliveryReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
// $History: smsDetailReport.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/21/09    Time: 6:00p
//Updated in $/SnS/Interface
//sorting formatting updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/05/09    Time: 5:30p
//Updated in $/SnS/Interface
//role permission & file name format updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 5/19/09    Time: 1:15p
//Updated in $/SnS/Interface
//Message List Report Heading & remove html tags in list
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/31/09    Time: 5:46p
//Updated in $/SnS/Interface
//formatting settings
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:08a
//Updated in $/SnS/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/01/09   Time: 12:11
//Created in $/SnS/Interface
//Added Sns System to VSS(Leap for Chitkara International School)
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