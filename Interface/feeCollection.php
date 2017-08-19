<?php
//-------------------------------------------------------
// Purpose: To generate fee collection list
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (15.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCollection');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
$showTitle = "none";
$showData  = "none";
$showPrint = "none";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Collection</title>
<?php 
	require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 	  	  	  	  	   
var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
                               new Array('cycleName','Fee Cycle','width="55%"','align="left"',false),
                               new Array('cashPayment','Cash(Rs)','width="8%"','align="right"',false),
                               new Array('chequePayment','Cheque(Rs)','width="12%"','align="right" ',false),
                               new Array('draftPayment','Draft(Rs)','width="8%"','align="right"',false),
                               new Array('totalAmount','Total(Rs)','width="8%"','align="right"',false) );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxFeesCollectionList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'cycleName';
sortOrderBy    = 'ASC';

function getData(){

	fromDate = document.allDetailsForm.fromDate.value;
	toDate = document.allDetailsForm.toDate.value;
	 
	if(document.allDetailsForm.feeCycle.value==''){
	
		messageBox("<?php echo FS_FEE_CYCLE?>");
		document.allDetailsForm.feeCycle.focus();
		return false;
	}
	if(dateCompare(fromDate,toDate)==1){

		 messageBox("<?php echo FS_CORRECT_DATE?>");
		 document.allDetailsForm.toDate.select();
		 return false;
	}
	 
   //this function is used to build search criteria
   document.getElementById('showTitle').style.display='';	 	
   document.getElementById('showData').style.display='';
   document.getElementById('saveDiv').style.display='';
   var univ="", 
   univ = getCommaSeprated("feeUniversity");
   document.allDetailsForm.univId.value=univ;
   
   var insts="", 
   insts = getCommaSeprated("feeInstitute");
   document.allDetailsForm.instsId.value=insts;
   
   var degs="", 
   degs = getCommaSeprated("feeDegree");
   document.allDetailsForm.degsId.value=degs;

   var brans="", 
   brans = getCommaSeprated("feeBranch");
   document.allDetailsForm.bransId.value=brans;

   var sems="", 
   sems = getCommaSeprated("studyperiod");
   document.allDetailsForm.semsId.value=sems;

   var fees="", 
   fees = getCommaSeprated("feeCycle");
   document.allDetailsForm.feesId.value=fees;
   
   sendReq(listURL,divResultName,searchFormName,'',false);
	return false;
}
function getCommaSeprated(elmentName)
{
	var commValue ='';
	var c=eval("document.getElementById('"+elmentName+"').length");
	for(i=0;i<c;i++){
		if(eval("document.allDetailsForm."+elmentName+"[i].selected")){

			if(eval("document.allDetailsForm."+elmentName+"[i].value")!=""){  
				if(commValue==""){
					commValue= eval("document.allDetailsForm."+elmentName+"[i].value");
				}
			else{
				commValue=commValue + "," + eval("document.allDetailsForm."+elmentName+"[i].value");
			}
		}  
		}  
	}
	return commValue;
}
 
function printReport() {

	form = document.allDetailsForm;
	if(document.allDetailsForm.feeCycle.value==''){
	
		messageBox("<?php echo FS_FEE_CYCLE?>");
		document.allDetailsForm.feeCycle.focus();
		return false;
	}
	path='<?php echo UI_HTTP_PATH;?>/feeCollectionReportPrint.php?univId='+form.univId.value+'&instsId='+form.instsId.value+'	&degsId='+form.degsId.value+'&bransId='+form.bransId.value+'&semsId='+form.semsId.value+'&feesId='+form.feesId.value+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

/* function to print all fee collection to csv*/
function printFeeCollectionCSV() {

	form = document.allDetailsForm;
	if(document.allDetailsForm.feeCycle.value==''){
	
		messageBox("<?php echo FS_FEE_CYCLE?>");
		document.allDetailsForm.feeCycle.focus();
		return false;
	}
	path='<?php echo UI_HTTP_PATH;?>/feeCollectionCSV.php?univId='+form.univId.value+'&instsId='+form.instsId.value+'&degsId='+form.degsId.value+'&bransId='+form.bransId.value+'&semsId='+form.semsId.value+'&feesId='+form.feesId.value+'&fromDate='+form.fromDate.value+'&toDate='+form.toDate.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

	window.location=path;
}
 
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/feeCollectionContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: feeCollection.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Interface
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/07/09    Time: 6:04p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//Updated with Required field, centralized message, left align
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 12:57p
//Updated in $/LeapCC/Interface
//updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/16/08    Time: 11:28a
//Created in $/Leap/Source/Interface
//intial checkin 
?>