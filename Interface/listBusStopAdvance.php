<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopRouteMapping');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Bus Route Stop Mapping</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period    room                               
var tableHeadArray = new Array(
                         new Array('srNo',           '#','width="2%"','',false),
                         new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                         new Array('stopName','Stop Name','width="10%"','',true), 
                         new Array('stopAbbr','Abbr.','width="10%"','',true),
                         new Array('transportCharges','Charges','width="10%"','align="right"',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxInitStopListAdvance.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'stopName';
sortOrderBy  = 'ASC';


function validateAddForm() {
    
    hideResults();
    if(document.getElementById("labelId").value=='') {
       messageBox ("<?php echo SELECT_TIME_TABLE;?>");
       document.getElementById("labelId").focus();
       return false;  
    }
    
    if(document.getElementById("routeId").value=='') {
       messageBox ("<?php echo SELECT_BUS_ROUTE_CODE;?>");
       document.getElementById("routeId").focus();
       return false;   
    }
    
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    document.getElementById("saveDiv2").style.display='';
    document.getElementById("saveDiv1").style.display='';
    document.getElementById("showData").style.display='';
    document.getElementById("showTitle").style.display='';
    //sendReq(listURL,divResultName,'listForm','');
    return false;
}

function hideResults() {
   document.getElementById("saveDiv2").style.display='none';
   document.getElementById("saveDiv1").style.display='none';
   document.getElementById("showData").style.display='none';
   document.getElementById("showTitle").style.display='none';
}


function doAll(){

   formx = document.listForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox"){
                formx.elements[i].checked=false;
            }
        }
    }
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
    require_once(TEMPLATES_PATH . "/BusStop/listBusStopContentsAdvance.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>

