<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Hostel Room Detail
// Author :Harpreet
// Created on : 16.07.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelOccupancyReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Hostel Occupancy Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

                              
//This function Validates Form 
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'HostelDetailForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'hostelName';
sortOrderBy    = 'ASC';

function validateAddForm(frm) {
    
    var url='<?php echo HTTP_LIB_PATH;?>/HostelOccupancyReport/initHostelOccupancyReportList.php';  
  
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	
    queryString = 'fromDate='+document.getElementById("fromDate").value;
         
    new Ajax.Request(url,
    {
      method:'post',
      asynchronous:false,
      parameters: queryString, 
      onCreate: function() {
          showWaitDialog(true);
      },
      onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==false) {
            messageBox("<?php echo INCORRECT_FORMAT?>");  
         }
         else {
           document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
         }
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     });
    
}


function printReport() {
	form = document.HostelDetailForm;
	path='<?php echo UI_HTTP_PATH;?>/hostelDetailReportsPrint.php?fromDate='+form.fromDate.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	a = window.open(path,"HostelRoomTypeReport","status=1,menubar=1,scrollbars=1, width=900");
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/HostelOccupancyReport/listHostelOccupancyReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
