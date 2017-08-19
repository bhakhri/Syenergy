<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in Branch Form
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BranchMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Branch/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Branch Report Print </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

</head>
<body>
    <?php 
  //  require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Branch/branchPrint.php");
 //   require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 

////$History: listBranchPrint.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 2:37p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/02/09    Time: 2:56p
//Updated in $/LeapCC/Interface
//formatting & validations, conditions updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/27/09    Time: 3:29p
//Updated in $/LeapCC/Interface
//corrected caption name for print window
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/15/08   Time: 5:58p
//Created in $/Leap/Source/Interface

?>
