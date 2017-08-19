<?php
//-------------------------------------------------------
// Purpose: To generate student fee due detail
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student fee due detail</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
// receiptNo,receiptDate
  	  	  	  	  	   
var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),new Array('receiptNo','Receipt','width="12%"','align="left"',true),new Array('receiptDate','Date','width="8%"','align="left"',true), new Array('fullName','Name','width="12%"','',true) , new Array('rollNo','Roll No','width="7%"','',true), new Array('cycleName','Fee Cycle','width="9%"','',true),  new Array('discountedFeePayable','Payable(Rs)','width="10%"','align="right"',true), new Array('previousDues','Amount Due(Rs)','width="12%"','align="right"',true));

  	  	
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxFeesDueList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'firstName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}

function validatetStatus() {
 
	 updateStatus();
	 return false;
}

function printReport() {
	form = document.listForm;
	path='<?php echo UI_HTTP_PATH;?>/feeDueReportPrint.php?degree='+form.degree.value+'&batch='+form.batch.value+'	&studyperiod='+form.studyperiod.value+'&studentName='+form.studentName.value+'&studentRoll='+form.studentRoll.value+'&feeCycle='+form.feeCycle.value+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&fromAmount='+form.fromAmount.value+'&toAmount='+form.toAmount.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}


function checkSelect(){

	var selected=0;
	formx = document.listForm;
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].type=="checkbox"){

			if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
				selected++;
			}
		}
	}
	if(selected==0){

		alert("Please select atleast 1 record to delete!");
		return false;
	}
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/feeDueContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: feeDueReport.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/08/08    Time: 3:37p
//Created in $/Leap/Source/Interface
//intial checkin
?>
