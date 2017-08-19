<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 23-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentAlerts');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();
//require_once(BL_PATH . "/ScStudent/initAllAlerts.php");
require_once(BL_PATH . "/Parent/initData.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Alerts </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>

<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','align="left"',false), 
                               new Array('alert1','Alert','width="98%"','align="left"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitAllAlerts.php';
//listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxNoticesGetValues.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = '';   
editFormName   = '';
winLayerWidth  = 400; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = '';
divResultName  = 'results';
page=1; //default page
sortField = '';
sortOrderBy    = 'ASC';


// ajax search results ---end ///

function getAllAlerts(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitAllAlerts.php';
     class1 = document.getElementById('studyPeriod').value;
     page=1;
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&class1='+class1);
}
window.onload=function(){
   document.getElementById('studyPeriod').value = 0;   
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&class1=0');
}
</script>
</head>
<body>
  <?php 
     require_once(TEMPLATES_PATH . "/header.php");
     require_once(TEMPLATES_PATH . "/Parent/allAlertsContents.php");
     require_once(TEMPLATES_PATH . "/footer.php");
  ?>
</body>
</html>


<?php 
//$History: listAllAlerts.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Interface/Parent
//added access rights
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/18/09    Time: 6:22p
//Updated in $/LeapCC/Interface/Parent
//formating, validations & conditions updated
//

?>