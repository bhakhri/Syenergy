<?php 
//------------------------------------------------------------------------
//  This File to create a document file (Send Message Details) 
//
// Author :Parveen Sharma
// Created on : 04-06-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//define('MANAGEMENT_ACCESS',1);
define('MANAGEMENT_ACCESS',1);
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
header("Content-Type: application/msword; name='word'");
header("Content-Disposition: attachment; filename=SendMessageDetails.doc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Send Message Details Document </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />
<style>
    .page { page-break-after:always; }
</style>
<script type="text/javascript">
function printout(){
	document.getElementById('printing').style.display='none';
	window.print();
}
</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/AdminMessage/adminSendMessageDetailsDocument.php");
?>
</body>
</html>
<?php 
// $History: detailsAdminMessageDocument.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/27/10    Time: 4:21p
//Updated in $/LeapCC/Interface
//role permission updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/05/09    Time: 1:54p
//Created in $/LeapCC/Interface
//file added
//
//

?>