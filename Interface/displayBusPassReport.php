<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Bus Pass Report Form 
//
// Author :Parveen Sharma
// Created on : 12-June-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayBusPassReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Display Bus Pass Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>
 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var studentIds="";                 

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','align="center"',false), 
                               new Array('studentName','Name','width="15%"','',true), 
                               new Array('className','Current Class','width="14%"','',true), 
                               new Array('rollNo','Roll No.','width="10%"','',true),       
                               new Array('routeCode','Bus Route','width="12%"','',true),
                               new Array('stopName','Bus Stoppage','width="12%"','',true),
                               new Array('receiptNo','Receipt No.','width="11%"','',true),
                               new Array('addedOnDate','Issue Date','width="11%"','align="center"',true),
                               new Array('validUpto','Expiry Date','width="11%"','align="center"',true));

//This function Validates Form 
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL='<?php echo HTTP_LIB_PATH;?>/Icard/initBusPassReportList.php';    
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBusPass';   
editFormName   = 'AddBusPass';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBusPass';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

queryString='';
flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      
//This function Displays Div Window
// ajax search results ---end ///
//This function Displays Div Window

function hideResults() {
    
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


function setLabel() {
   if(document.getElementById('dateFormat').value==1) {
      document.getElementById('setDateFormat').innerHTML='Bus Pass Expiry Date From';  
   }
   else {
      document.getElementById('setDateFormat').innerHTML='Bus Pass Issue Date From';    
   }
}


function validateAddForm1(frm,act) {
   
   queryString = ''; 
   page=1;
   
   if(!isEmpty(document.getElementById('startDate').value)) {
     if(isEmpty(document.getElementById('endDate').value)) {
       messageBox("<?php echo EMPTY_TO_DATE;?>");  
       document.getElementById('endDate').focus();
       return false;
     }  
   }    
      
   if(!isEmpty(document.getElementById('endDate').value)) {
     if(isEmpty(document.getElementById('startDate').value)) {
        messageBox("<?php echo EMPTY_FROM_DATE;?>");  
        document.getElementById('endDate').focus();
        return false;
     }   
   }  
   
   if(!isEmpty(document.getElementById('startDate').value) && !isEmpty(document.getElementById('endDate').value)) { 
     if(!dateDifference(eval("document.getElementById('startDate').value"),eval("document.getElementById('endDate').value"),'-') ) {
        messageBox ("<?php echo DATE_CONDITION1;?>");
        eval("document.getElementById('startDate').focus();");
        return false;
     } 
   } 
   
   queryString = generateQueryString('searchForm');
   
   hideResults();
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false); 
   document.getElementById("nameRow").style.display='';
   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   
}

window.onload=function(){
    setLabel();
    var roll = document.getElementById("sRollNo");
    autoSuggest(roll);
}

function printReport() {

    qstr=queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    
    path='<?php echo UI_HTTP_PATH;?>/displayBusPassReportPrint.php?'+qstr;
    window.open(path,"DisplayBusPassReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
   
    qstr=queryString+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    
    path='<?php echo UI_HTTP_PATH;?>/displayBusPassReportCSV.php?'+qstr;
    window.location = path;  
}


</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Icard/listBusPassReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: displayBusPassReport.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/02/10    Time: 4:41p
//Updated in $/LeapCC/Interface
//report fieldHeading Name updated (Current Class)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/02/10    Time: 3:56p
//Created in $/LeapCC/Interface
//initial checkin
//

?>