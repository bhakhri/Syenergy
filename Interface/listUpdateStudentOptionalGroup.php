<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used for group change
//
//
// Author :Jaineesh
// Created on : 07-Mar-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UpdateStudentOptionalGroups');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: List Students </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");  
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>

<script type="text/javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

tabsShown  = false;
function validateAddForm(frm) {

	form = document.assignGroup;
    var fieldsArray = new Array(new Array("rollNo","<?php echo ENTER_ROLLNO;?>"));
    
	var len = fieldsArray.length;
    
	for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value")==0) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	showOptionalGroupList();
}

function showOptionalGroupList() {

form = document.assignOptionalGroup;
rollNo = form.rollNo.value;

var url = '<?php echo HTTP_LIB_PATH;?>/Student/listOptionalGroupReport.php';
var pars = generateQueryString('assignOptionalGroup');
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = trim(transport.responseText);
			// now select the value
			document.getElementById('resultRow').style.display = '';
			document.getElementById('resultsDiv').innerHTML = trim(transport.responseText);
			//document.getElementById('resultDiv').innerHTML = trim(transport.responseText);
			//document.getElementById('subjectId').value = j[0].subjectId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	
}

window.onload=function(){
 //document.feeForm.studentRoll.focus();
 var roll = document.getElementById("rollNo");
 autoSuggest(roll);
}
</script>	
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/updateStudentOptionalGroup.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
//$History: listUpdateStudentOptionalGroup.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/10    Time: 12:55p
//Created in $/LeapCC/Interface
//new file for student optional group change
//
?>