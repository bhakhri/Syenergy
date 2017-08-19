<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Parveen Sharma
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifParentNotLoggedIn();         
require_once(BL_PATH . "/Parent/initInstituteEvents.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Institute Notices </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="6%"','style="padding-left:15px"',false), 
                               new Array('instituteEvents',' Title','width="45%"','',false),
                               new Array('sd',' Short Desription','width="40%"','',false), 
                               new Array('Action','Action','width="4%"','align="center" style="padding-right:50px"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxDisplayEvents.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'eventTitle';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

//This function Displays Div Window

function editWindow(id,dv,w,h) {
   
    //displayWindow(dv,w,h);
	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateValues(id);   
}


//This function populates values in View Deatil form through ajax 

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxEventsGetValues.php';
		 new Ajax.Request(url,
           {      
             method:'post',
             parameters: {eventId: id},
				 
              onCreate: function() {
			 	showWaitDialog();
			 },
			 onSuccess: function(transport){
			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
	              document.getElementById("innerNotice").innerHTML = j.eventTitle;
				  document.getElementById("innerDescription").innerHTML = j.shortDescription;
				  document.getElementById("longDescription").innerHTML = j.longDescription;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>
<?php
	function trim_output($str,$maxlength,$mode=1,$rep='...'){
		$ret=($mode==2?chunk_split($str,30):$str);
		if(strlen($ret) > $maxlength){
			$ret=substr($ret,0,$maxlength).$rep;
		}
		return $ret;
	}
?>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/instituteEventContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 
//$History: listInstituteEvents.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/01/09    Time: 7:00p
//Updated in $/LeapCC/Interface/Parent
//issue fix formatting & functionality updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/01/09    Time: 6:18p
//Created in $/LeapCC/Interface/Parent
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/01/09    Time: 6:16p
//Created in $/SnS/Interface/Parent
//file added
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 20/01/09   Time: 12:11
//Created in $/SnS/Interface/Student
//Added Sns System to VSS(Leap for Chitkara International School)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:01p
//Updated in $/Leap/Source/Interface/Student
//modified
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/17/08   Time: 4:31p
//Updated in $/Leap/Source/Interface/Student
//remove the html tags through strip_tags function
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:10p
//Updated in $/Leap/Source/Interface/Student
//remove the spaces

?>