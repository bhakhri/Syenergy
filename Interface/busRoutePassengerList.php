<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
//
//
// Author :Jaineesh
// Created on : 12-July-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusRoutePassengerReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bus Report Passenger Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false), 
								new Array('routeCode','Route Code','width=15%','',true), 
								new Array('routeName','Route Name','width="15%"','',true), 
								new Array('passengerCount','Passengers','width="10%"','align="right"',false),
								new Array('actionPrint','Action','width="5%"','align="center"',false));

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/BusRoute/initBusRoutePassengerReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'busRouteForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'routeCode';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
    var fieldsArray = new Array	(
									new Array("busRouteId","<?php echo SELECT_BUS_ROUTE;?>")
								);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}


function printReport(busRouteId) {
	form = document.busRouteForm;
	path='<?php echo UI_HTTP_PATH;?>/busRoutePassengerReportPrint.php?busRouteId='+busRouteId;
	a = window.open(path,"BusRoutePassengerReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV(busRouteId) {
	form = document.timeTableForm;
    //var qstr="searchbox="+trim(document.searchBox1.searchbox.value);
    //qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/busRoutePassengerReportPrintCSV.php?busRouteId='+busRouteId;
	window.location = path;
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}


window.onload=function(){
	//document.getElementById('labelId').focus();
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/BusRoute/listBusRoutePassengerReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History:  $
//
?>