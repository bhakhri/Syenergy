<?php 

//---------------------------------------------------------------------------------------
// 
//THIS FILE CONTAINS THE CODE FOR PRINTING THE FEE FUND ALLOCATION LISTS
//
// Author :Arvind Singh Rawat
// Created on : 17-October-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
//
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FundAllocationMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeeFundAllocation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fund Allocation Report Print </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
</script>

</head>
<body>
    <?php 
   // require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeeFundAllocation/feeFundAllocationPrint.php");
   // require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: listFeeFundAllocationPrint.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/30/09    Time: 5:07p
//Updated in $/LeapCC/Interface
//role permission and formatting updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/22/09    Time: 3:52p
//Updated in $/LeapCC/Interface
//condition & formatting, required parameter checks updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/17/08   Time: 12:50p
//Created in $/Leap/Source/Interface
//initial checkin
?>