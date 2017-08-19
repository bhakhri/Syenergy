<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax functions used in Fees Collected
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
require_once(BL_PATH . "/Management/collectedInitList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fees Collected Details</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),new Array('receiptNo','Receipt','width="12%"','align="left"',true),new Array('receiptDate','Date','width="8%"','align="left"',true), new Array('fullName','Name','width="12%"','',true) , new Array('rollNo','Roll No','width="7%"','',true), new Array('cycleName','Fee Cycle','width="9%"','',true),  new Array('discountedFeePayable','Payable(Rs)','width="10%"','align="right"',true), new Array('totalAmountPaid','Paid(Rs)','width="8%"','align="right"',true), new Array('outstanding','Outstanding(Rs)','width="13%"','align="right"',true), new Array('retStatus','Status','width="8%"','align="center"',false) );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxCollectedInitList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'receiptNo';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 var flag = false;
//This function Displays Div Window
</script>

</head>
<body>
<?php 
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Management/listCollectedContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: listCollectedFees.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:00p
//Updated in $/LeapCC/Interface/Management
//Updated as per CC functionality
?>