<?php 
//-------------------------------------------------------
//  This File contains html code for Student Detail Upload
//
// Author :Jaineesh
// Created on : 14-Nov-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UploadAppraisalDetail');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload Student Detail</title>
<?php 
	require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
var  valShow=0; 
function submitForm(act) {
	
		document.addForm.action = "<?php echo HTTP_LIB_PATH;?>/Appraisal/AppraisalData/fileUpload.php";
		document.addForm.submit();
}

window.onload = function () {
   getShowDetail(); 
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
    require_once(TEMPLATES_PATH . "/Appraisal/AppraisalData/listAppraisalDetailUpload.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
