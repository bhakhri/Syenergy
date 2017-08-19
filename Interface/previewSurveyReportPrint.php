<?php 
//-------------------------------------------------------
//  This File prints teacher survery feedback report printing
//
// Author :Rajeev Aggarwal
// Created on : 02-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PreviewSurvey');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Feedback Survey Report </title>
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
    require_once(TEMPLATES_PATH . "/FeedBack/previewSurveyPrint.php");
    //require_once(TEMPLATES_PATH . "/ffooter.php");
?>
</body>
</html>
<?php 
//$History: previewSurveyReportPrint.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/24/09    Time: 11:26a
//Updated in $/LeapCC/Interface
//0000272: Preview Survey -Admin > Title of page is not correct; and
//�Report� keyword must be added in heading part.
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:35p
//Created in $/LeapCC/Interface
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/19/09    Time: 4:51p
//Created in $/Leap/Source/Interface
//Added Preview survey related function.
?>