<?php 
//-------------------------------------------------------
//  This File contains the template file and data base file
//
// Author :Rajeev Aggarwal
// Created on : 06.01.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GeneralFeedbackReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: General Survey Feed Back Details  </title>
<?php 
    require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>

<script language="javascript">
function getTeacherFeedBackData() {
 
  document.getElementById('results').innerHTML='';
  url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/scAjaxInitGeneralFeedBackList.php';
  new Ajax.Request(url,
  {
     method:'post',
     parameters: {
  
		 feedbackSurveyId: (document.getElementById('feedbackSurveyId').value)
     },
			  asynchronous: false,
     onCreate: function() {
     
		 showWaitDialog(true);
     },
     onSuccess: function(transport){
         
		 hideWaitDialog(true);
         document.getElementById('results').innerHTML=trim(transport.responseText);
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
     
}

function printReport() {

    path='<?php echo UI_HTTP_PATH;?>/scGeneralFeedBackReportPrint.php?feedbackSurveyId='+document.getElementById('feedbackSurveyId').value;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

/* function to print all fee collection to csv*/
function printReportCSV() {

     path='<?php echo UI_HTTP_PATH;?>/scGeneralFeedBackReportPrintCSV.php?feedbackSurveyId='+document.getElementById('feedbackSurveyId').value;  
    //document.getElementById('generateCSV').href=path;
	window.location=path;
}
function printStatistics(text,val,quest) {

	//alert(id);
	 
	var name = document.getElementById('feedbackSurveyId');
	 
    path='<?php echo UI_HTTP_PATH;?>/scGeneralFeedBackStatisticsPrint.php?text='+text+'&val='+val+'&quest='+quest+'&surveyName='+name.options[name.selectedIndex].text+'&feedbackSurveyId='+document.getElementById('feedbackSurveyId').value ;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=560, top=150,left=150");
}
function getTeacherClear() {

	document.getElementById('results').innerHTML='';
}

</script> 
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeReports/scGeneralFeedbackReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
//History: $
?>