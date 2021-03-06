<?php 

//-------------------------------------------------------
//  This File outputs student userName and password
//
// Author :Gurkeerat Sidhu
// Created on : 03.11.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UpdatePasswordReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Report Print CSV </title>
<?php 
echo UtilityManager::includeCSS("css.css");
?>
<link rel="stylesheet" type="text/css" media="print" title="" href="<?php echo CSS_PATH;?>/css.css" />

<style>
BR.page { page-break-after: always }

</style>
<script type="text/javascript">
function printout()
{
    document.getElementById('printing').style.display='none';
    window.print();
}
</script>
</head>
<body>
<?php 
    //require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/UpdatePassword/studentListCSV.php");
    //require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
// $History: studentListPrintCSV.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/02/09   Time: 4:25p
//Updated in $/LeapCC/Interface
//added access define
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 11/06/09   Time: 1:50p
//Created in $/LeapCC/Interface
//Added file 'studentListPrintCSV.php' in LeapCC related to module
//'generate student login'
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/05/09    Time: 12:31p
//Created in $/LeapCC/Interface
//add new files for print & expor to exel
//
//
?>
