<?php
//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentAllAlerts');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
require_once(BL_PATH . "/Student/initAllAlerts.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Institute Notices </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>

<script language="javascript">
function getAllAlerts(id)  {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitAllAlerts.php';
		 classId = document.getElementById('semesterDetail').value;
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {class1:classId},

              onCreate: function() {
			 	showWaitDialog();
			 },
			 onSuccess: function(transport){
			      hideWaitDialog();
				  document.getElementById('results').innerHTML=trim(transport.responseText);
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printFeeReceipt(payFee){
	window.open("<?php echo UI_HTTP_PATH;?>/Fee/studentFeesPrint.php?fee="+payFee,"StuidentFeeReceiptPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

window.onload = function(){
	getAllAlerts(document.getElementById('semesterDetail').value);

}
</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/allAlertsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php
//$History: listAllAlerts.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:36p
//Updated in $/LeapCC/Interface/Student
//modified for all alerts
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/17/08   Time: 3:01p
//Created in $/Leap/Source/Interface/Student
//show all alerts on next page
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:10p
//Updated in $/Leap/Source/Interface/Student
//remove the spaces


?>
