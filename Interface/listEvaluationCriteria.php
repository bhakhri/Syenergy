<?php
//-------------------------------------------------------
// Purpose: To generate the list of evaluation criteria from the database
//
// Author : Rajeev Aggarwal
// Created on : (05.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EvaluationCrieteria');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Evaluation/initList.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Evaluation Criteria Master </title>
<?php 
	require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="Javascript">

function printReport() {

	path='<?php echo UI_HTTP_PATH;?>/listEvaluationCriteriaPrint.php';
	window.open(path,"evaluationList","status=1,menubar=1,scrollbars=1, width=700, height=400, top=150,left=150");
}

function printReportCSV() {

	path='<?php echo UI_HTTP_PATH;?>/listEvaluationCriteriaCSV.php';
	window.location=path;
	//document.getElementById('generateCSV').href=path;
}
</script>
</head>
<body>
<?php 
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Evaluation/listEvaluationcContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
// $History: listEvaluationCriteria.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/27/09    Time: 6:14p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1273,1275
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/10/09    Time: 12:27p
//Updated in $/LeapCC/Interface
//Added print and export to CSV functionality 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Interface
//added access right "define paramater"
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/08    Time: 2:28p
//Updated in $/Leap/Source/Interface
//updated the file
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/05/08    Time: 1:55p
//Updated in $/Leap/Source/Interface
//updated according to new pattern
?>
