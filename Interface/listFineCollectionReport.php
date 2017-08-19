<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Fine Collection Report
//
//
// Author :Jaineesh
// Created on : 06.07.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineCollectionReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Category Wise Fine Collection Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false),
								new Array('fineCategoryName','Fine Category','width="10%"','align=left',true),
								new Array('count','Count','width="8%"','align=right',true),
								new Array('amount','Amount','width="8%"','align=right',true),
								new Array('viewDetail','Action','width="10%"','align=center',false)
							);

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Fine/initFineCollectionReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'FineCollectionForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'fineCategoryName';
sortOrderBy    = 'ASC';

function validateAddForm(frm) {
	var fieldsArray = new Array(new Array("startDate","<?php echo SELECT_DATE;?>"),
								new Array("toDate","<?php echo SELECT_TODATE;?>")
							);
	var serverDate="<?php echo date('Y-m-d'); ?>";

	if(!dateDifference(document.getElementById('startDate').value,serverDate,'-')){
	     messageBox("From date can not be greater than current date");
	     document.getElementById('startDate').focus();
	     return false;
	 }
	 if(!dateDifference(document.getElementById('toDate').value,serverDate,'-')){
	     messageBox("To date can not be greater than current date");
	     document.getElementById('toDate').focus();
	     return false;
	 }
	 if(!dateDifference(document.getElementById('startDate').value,document.getElementById('toDate').value,'-')){
	     messageBox("<?php echo DATE_VALIDATION; ?>");
	     document.getElementById('toDate').focus();
	     return false;
	 } 
	var len = fieldsArray.length;
	for(i=0;i<len;i++) {
	if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
	    messageBox(fieldsArray[i][1]);
	    eval("frm."+(fieldsArray[i][0])+".focus();");
	    return false;
	    break;
	}
	}
	//openStudentLists(frm.name,'rollNo','Asc');    
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}


function printReport(fineCategoryId,startDate,toDate) {
	form = document.FineCollectionForm;
	path='<?php echo UI_HTTP_PATH;?>/fineCollectionReportPrint.php?fineCategoryId='+fineCategoryId+'&startDate='+form.startDate.value+'&toDate='+form.toDate.value;
	a = window.open(path,"FineCollectionReport","status=1,menubar=1,scrollbars=1, width=900");
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
    require_once(TEMPLATES_PATH . "/Fine/listCollectionFineContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: listFineCollectionReport.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/08/09   Time: 6:49p
//Updated in $/LeapCC/Interface
//fixed bug nos.0002210, 0002184, 0002207, 0002205
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Interface
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/07/09    Time: 7:10p
//Updated in $/LeapCC/Interface
//some modification in code & put approveByUserId in fine_student table
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/07/09    Time: 4:02p
//Updated in $/LeapCC/Interface
//modification in query & files
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/07/09    Time: 10:22a
//Created in $/LeapCC/Interface
//new file to show fine collection report
//
?>
