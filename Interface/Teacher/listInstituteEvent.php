<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF institute events for teacher
//
// Author : Dipanjan Bhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InstituteEventList');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
//require_once(BL_PATH . "/Teacher/TeacherActivity/initInstituteEventList.php"); 
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

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','valign="top"',false), 
new Array('eventTitle','Event','width="10%"','valign="top"',true) , 
new Array('shortDescription','Short Description','width="20%"','valign="top"',true),  
new Array('startDate','Visible From','width="10%"','align="center" valign="top"',true),
new Array('endDate','Visible To','width="10%"','align="center" valign="top"',true),
new Array('details','Action','width="5%"','align="center" valign="top"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInstituteEventList.php';
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


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY Event Div
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function showEventDetails(id) {
    displayWindow('divEvent',300,200);
    populateEventValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateEventValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetEventDetails.php';
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

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listInstituteEventContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>

<?php 

//---------------------------------------------------------------------------------------------------------------  
//purpose: to trim a string and output str.. etc
//Author:Dipanjan Bhattcharjee
//Date:2.09.2008
//$str=input string,$maxlenght:no of characters to show,$rep:what to display in place of truncated string
//$mode=1 : no split after 30 chars,mode=2:split after 30 characters
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}



// $History: listInstituteEvent.php $ 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 3/09/09    Time: 11:37
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//00001407,00001408,00001409,
//00001419,00001420
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/27/09    Time: 11:54a
//Updated in $/LeapCC/Interface/Teacher
//Gurkeerat: resolved issue regardind issue nos. 1226,1227
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/20/09    Time: 1:26p
//Updated in $/LeapCC/Interface/Teacher
//Gurkeerat: resolved issue 1083
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/14/09    Time: 12:55p
//Updated in $/LeapCC/Interface/Teacher
//Gurkeerat: resolved issue 1082,1078,1081,1077,1079,1080
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:50p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/02/08    Time: 3:40p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/30/08    Time: 1:54p
//Created in $/Leap/Source/Interface/Teacher
?>