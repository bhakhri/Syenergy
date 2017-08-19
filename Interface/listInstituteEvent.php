<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF institute events for teacher
//
// Author : Rajeev Aggarwal
// Created on : (13.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH . "/Index/initInstituteEventList.php"); 
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

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
new Array('eventTitle','Event','width="20%"','',true) , 
new Array('shortDescription','Short Desc.','width="55%"','',true), 
new Array('startDate','From','width="7%"','',true),
new Array('endDate','To','width="7%"','',true),
new Array('act','Action','width="5%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxInstituteEventList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'startDate';
sortOrderBy    = 'DESC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
   
    //displayWindow(dv,w,h);
	height=screen.height/5;
	width=screen.width/3;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateValues(id);   
}
//This function populates values in View Deatil form through ajax 

function populateValues(id) {
 url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxEventGetValues.php';
 
 new Ajax.Request(url,
   {      
	 method:'post',
	 parameters: {eventId: id},
	  onCreate: function() {
		showWaitDialog(true);
	 },
	 onSuccess: function(transport){
		
		hideWaitDialog(true);
		j = eval('('+transport.responseText+')');
	    document.getElementById("innerEvent").innerHTML = j.eventTitle;
		document.getElementById("innerEventDesc").innerHTML = j.longDescription;
	 },
	 onFailure: function(){ alert('Something went wrong...') }
   });
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Index/listInstituteEventContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listInstituteEvent.php $ 
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
//*****************  Version 5  *****************
//User: Rajeev       Date: 10/07/08   Time: 10:43a
//Updated in $/Leap/Source/Interface
//updated width of start and end date
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/22/08    Time: 6:23p
//Updated in $/Leap/Source/Interface
//updated path
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/04/08    Time: 2:12p
//Updated in $/Leap/Source/Interface
//updated formatting for ajax based list
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/04/08    Time: 12:57p
//Updated in $/Leap/Source/Interface
//updated the formatting and made floating div for event description
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:36p
//Created in $/Leap/Source/Interface
//intial checkin
?>