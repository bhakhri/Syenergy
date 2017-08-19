<?php 
//-------------------------------------------------------
//  This File contains starting code for employee Info uploading and Pie Charts
//
//
// Author :Gurkeerat Sidhu
// Created on : 17-Nov-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeUpload');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Import Fees Data</title>
<?php 
require_once(BL_PATH . "/Index/getEmployeeGraph.php");
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js"); 
$flashPath = IMG_HTTP_PATH."/ampie.swf"; 
?> 
<script language="javascript">
var topPosHistory = 0;
var leftPosHistory = 0;
var  valShow=0; 
function showInstructions() {
   
    displayFloatingDiv('divInstructionsInfo', 'Fee Uploading Instructions', 300, 150, leftPosHistory, topPosHistory,1);
    
    leftPosHistory = document.getElementById('divInstructionsInfo').style.left;
    topPosHistory = document.getElementById('divInstructionsInfo').style.top;
    
    document.getElementById('instruction').style.display='';
              
}    

 window.onload=function(){
	getShowDetail(); 
     document.addForm.reset();
 }
function getShowDetail() {

   document.getElementById("idSubjects").innerHTML="Expand Sample Format for .xls file and instructions"; 
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   document.getElementById("showSubjectEmployeeList11").style.display='none';
   if(valShow==1) {
     document.getElementById("showSubjectEmployeeList11").style.display='';
     document.getElementById("idSubjects").innerHTML="Collapse Sample Format for .xls file and instructions";
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
     valShow=0;
   }
   else {
     valShow=1;  
   }
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/feeUploadContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
//$History: listFeeUpload.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10-04-08   Time: 5:55p
//Created in $/LeapCC/Interface
//Added functions for Import Fees data
?>
