<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in FEE HEAD VALUES Form
//
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadValues');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeeHeadValues/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Head Values Report Print </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
 
function changeNull($null){
    if(strtoupper($null) == NULL){
        return All;
    }
    else{
        return $null;
    }
}
?> 
</head>
<body>
    <?php 
//    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeeHeadValues/feeHeadValuesPrint.php");
//    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: listFeeHeadValuesPrint.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 2:37p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/06/09    Time: 3:05p
//Updated in $/LeapCC/Interface
//correct the heading 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/10/09    Time: 11:02a
//Updated in $/LeapCC/Interface
//title name change (fee head values list print)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Arvind       Date: 10/15/08   Time: 5:57p
//Created in $/Leap/Source/Interface
?>
