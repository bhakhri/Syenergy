<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentDisplayTimeTable');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
require_once(BL_PATH . "/Student/initTimeTable.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Time Table </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
function getStudentTimeTable(value) {
 
	url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitStudentTimeTable.php';

	 new Ajax.Request(url,
   {
     method:'post',
     asynchronous: false, 
     parameters: {
         semesterDetail: (document.getElementById('semesterDetail').value)
         },
     onCreate: function() {
         showWaitDialog();
     },
     onSuccess: function(transport){
		 hideWaitDialog();
         document.getElementById('results').innerHTML=trim(transport.responseText);
         changeColor(currentThemeId); 
      },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
	
}
window.onload = function(){
		getStudentTimeTable(document.getElementById('semesterDetail').value);
}
function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/Student/displayStudentTimeTableReport.php?studyPeriodId='+document.getElementById('semesterDetail').value;
    window.open(path,"DisplayTimeTable","status=1,menubar=1,scrollbars=1, width=900");
}
</script>

<?php
function createBlankTD($i,$str=''){
	if(empty($str)) {
		$str = '<td  valign="middle" align="center" class="timtd">'.NOT_APPLICABLE_STRING.'</td>';
	}
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/timeTableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 
//$History: listTimeTable.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:27p
//Updated in $/LeapCC/Interface/Student
//added access defines
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Interface/Student
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:16p
//Updated in $/LeapCC/Interface/Student
//modification code for cc student time table
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Student
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:10p
//Updated in $/Leap/Source/Interface/Student
//remove the spaces


?>