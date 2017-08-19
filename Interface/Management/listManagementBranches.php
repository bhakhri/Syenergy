<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax functions used in Branch Form
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
require_once(BL_PATH . "/Management/branchInitList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Branch Details</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), new Array('branchName',' Name','width="85%"','',true) , new Array('branchCode','Abbr','width="12%"','',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxBranchInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBranch';   
editFormName   = 'EditBranch';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteBranch';
divResultName  = 'results';
page=1; //default page
sortField = 'branchName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 var flag = false;
//This function Displays Div Window
</script>

</head>
<body>
<?php 
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Management/listBranchContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php 
//$History: listManagementBranches.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:00p
//Updated in $/LeapCC/Interface/Management
//Updated as per CC functionality
?>