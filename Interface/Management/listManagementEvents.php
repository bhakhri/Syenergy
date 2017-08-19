<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF institute events for management
//
// Author : Rajeev Aggarwal
// Created on : (15.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
require_once(BL_PATH . "/Management/initInstituteEventList.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Institute Events </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','valign="middle"',false),new Array('eventTitle','Event','width="10%"','valign="middle"',true),new Array('shortDescription','Short Desc.','width="25%"','valign="middle"',true),new Array('longDescription','Long Desc.','width="35%"','valign="middle"',true),new Array('startDate','From','width="7%"','valign="middle"',true),new Array('endDate','To','width="7%"','valign="middle"',true),new Array('details','Detail','width="5%"','align="right" valign="middle"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxInstituteEventList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'eventTitle';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function showEventDetails(id,dv,w,h) {
     
	displayFloatingDiv(dv,'', w, h, 200, 190)
    populateEventValues(id);
}
//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "EVENT" DIV
//
// Author : Rajeev Aggarwal
// Created on : (15.10.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateEventValues(id) {
 url = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxGetEventDetails.php';
 new Ajax.Request(url,
   {
	 method:'post',
	 parameters: {eventId: id},
	 onCreate: function(){
		 showWaitDialog();
	 },
	 onSuccess: function(transport){
		
		hideWaitDialog();
		if(trim(transport.responseText)==0) {
			hiddenFloatingDiv('divEvent');
			messageBox("This Event Record Doen Not Exists");
		}
		j = eval('('+trim(transport.responseText)+')');
	   
	    document.getElementById('eventTitle').innerHTML = trim(j.eventTitle);
	    document.getElementById('shortDescription').innerHTML = trim(j.shortDescription);
	    document.getElementById('longDescription').innerHTML = trim(j.longDescription);
	    document.getElementById('startDate').innerHTML = customParseDate(j.startDate,"-");
	    document.getElementById('endDate').innerHTML = customParseDate(j.endDate,"-");
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}
</script>
</head>
<?php 
//----------------------------------------------------------------------------------------------------------  
// purpose: to trim a string and output str.. etc
// Author:Rajeev Aggarwal
// Date:15.10.2008
// $str=input string,$maxlenght:no of characters to show,$rep:what to display in place of truncated string
// $mode=1 : no split after 30 chars,mode=2:split after 30 characters
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------  
function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
?>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Management/listInstituteEventContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listManagementEvents.php $ 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:00p
//Updated in $/LeapCC/Interface/Management
//Updated as per CC functionality
?>