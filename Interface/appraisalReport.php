<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AppraisalReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Appraisal Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
function showReport() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Reports/appraisalReport.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: generateQueryString('reportForm'),
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     document.getElementById('printTrId').style.display='';
                     document.getElementById('results').innerHTML=trim(transport.responseText);
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}
function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/appraisalReportPrint.php?'+generateQueryString('reportForm')+'&employeeName='+document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text;
    window.open(path,"AppraisalReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printReportCSV() {
    var path='<?php echo UI_HTTP_PATH;?>/appraisalReportCSV.php?'+generateQueryString('reportForm')+'&employeeName='+document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text;
    window.location = path;
}

function vanishData(){
    document.getElementById('printTrId').style.display='none';
    document.getElementById('results').innerHTML='';
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Appraisal/Reports/appraisalReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>