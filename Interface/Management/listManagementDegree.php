<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DEGREES ALONG WITH SEARCH AND PAGING OPTIONS
//
// Author : Rajeev Aggarwal
// Created on : (15.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
require_once(BL_PATH . "/Management/initDegreeList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Degree Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), new Array('degreeName','Degree Name','"width=72%"','',true) , new Array('degreeCode','Degree Code','width="15%"','',true), new Array('degreeAbbr','Abbr','width="10%"','',true));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Management/ajaxDegreeInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddDegree';   
editFormName   = 'EditDegree';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteDegree';
divResultName  = 'results';
page=1; //default page
sortField = 'degreeName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Management/listDegreeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listManagementDegree.php $ 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:00p
//Updated in $/LeapCC/Interface/Management
//Updated as per CC functionality
?>