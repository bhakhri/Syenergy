<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentInstituteEvents');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
//require_once(BL_PATH . "/Student/initInstituteEvents.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Institute Events </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
               new Array('eventTitle',' Title','width="25%"','',true),
               new Array('shortDescription',' Short Description','width="40%"','',true),
               new Array('startDate',' Visible From','width="10%"','align="center"',true),
               new Array('endDate',' Visible To','width="10%"','align="center"',true), 
               new Array('Action','Action','width="4%" align="center"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxDisplayEvents.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = 'startDate';
sortOrderBy    = 'DESC';

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
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxEventGetValues.php';
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
                  document.getElementById("visibleFromDate").innerHTML = customParseDate(j.startDate,"-");
                  document.getElementById("visibleToDate").innerHTML = customParseDate(j.endDate,"-");
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
    require_once(TEMPLATES_PATH . "/Student/instituteEventContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
    //-->
    </SCRIPT>
</body>
</html>


<?php 
//$History: listInstituteEvents.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:27p
//Updated in $/LeapCC/Interface/Student
//added access defines
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/03/09    Time: 7:33p
//Updated in $/LeapCC/Interface/Student
//fixed bug nos.0001440, 0001433, 0001432, 0001423, 0001239, 0001406,
//0001405, 0001404
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/20/09    Time: 10:21a
//Updated in $/LeapCC/Interface/Student
//fixed bug nos.0001145,  0001127, 0001126, 0001125, 0001119, 0001101,
//0001110
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/12/09    Time: 5:19p
//Updated in $/LeapCC/Interface/Student
//Gurkeerat: fixed issue 1036, 1030
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