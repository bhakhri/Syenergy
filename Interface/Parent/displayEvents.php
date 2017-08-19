<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in "displayEvents" Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentDisplayInstituteEvents');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();
//require_once(BL_PATH . "/Parent/initDisplayEvents.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Events</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                            new Array('srNo','#','width="3%"','',false), 
                            new Array('eventTitle',' Event','width="17%"','',true),
                            new Array('shortDescription',' Short Description','width="20%"','',true),
                            new Array('startDate', 'Visible From','width="15%"','align="center"',true),
                            new Array('endDate',   'Visible To','width="15%"','align="center"',true),
                            new Array('Edit','Action','width="10%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitDisplayEvents.php';
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
    height=screen.height/7;
    width=screen.width/3.5;
    displayFloatingDiv(dv,'', w, h, width,height);
    populateValues(id);   
}

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
                j = eval('('+transport.responseText+')');
                document.getElementById("titleEvents").innerHTML = j.eventTitle;  
                document.getElementById("innerEvents").innerHTML = j.longDescription;
                document.getElementById("innerShortDescription").innerHTML = j.shortDescription;
                document.getElementById('startDate').innerHTML=customParseDate(j.startDate,"-");
                document.getElementById('endDate').innerHTML=customParseDate(j.endDate,"-");
             },
             onFailure: function(){ alert('Something went wrong...') }
           });
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/displayEventsContents.php");
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
//History: displayEvents.php $

?>
