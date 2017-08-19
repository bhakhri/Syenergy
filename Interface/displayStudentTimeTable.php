<?php 
//-------------------------------------------------------
//  This File contains the template file and data base file for student
// Author :Rajeev Aggarwal
// Created on : 10-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayStudentTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Student Time Table </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 

//------------------------------------------------------------------------------------------------
// This Function  creates blank TDs
//
// Author : Dipanjan Bhattacharjee
// Created on : 31.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------   
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
echo UtilityManager::includeCSS2(); 

?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>

<script language="javascript">

queryString='';
timeTableType=1;
//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO get time table date for a class
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.082008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function getTimeTableData() {
 
     if(trim(document.getElementById('rollNo').value)==""){
       messageBox("Please enter student Roll No./User Name");
       document.getElementById('rollNo').focus();
       return false;
     }   
     
     queryString = "rollNo="+trim(document.getElementById('rollNo').value)+"&timeTableType="+timeTableType;
     if(timeTableType==2) {   
        var fromDate = document.getElementById('fromDate').value;
        var toDate = document.getElementById('toDate').value;
        queryString =queryString+ "&fromDate="+fromDate+"&toDate="+toDate;
     }

     document.getElementById('results').innerHTML="";
     
     url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxStudentTimeTable.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: queryString,
         asynchronous: false,
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             document.getElementById('results').innerHTML=trim(transport.responseText);
             changeColor(currentThemeId);
          },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
       changeColor(currentThemeId);
}

function printReport() {

	path='<?php echo UI_HTTP_PATH;?>/studentTimeTableReportPrint.php?'+queryString;
	//alert(path);
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=440, top=150,left=150");
}

function clearText() {
   
    document.getElementById("timeTableCheck").style.display='none';  
    if(document.allDetailsForm.timeFormat[1].checked==true) {
       document.getElementById("timeTableCheck").style.display='';
       timeTableType=2;    
    }
    else {
      timeTableType=1;   
    }
}

window.onload=function(){
     //document.feeForm.studentRoll.focus();
     clearText(); 
     var roll = document.getElementById("rollNo");
     autoSuggest(roll);
}
</script> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/listStudentTimeTableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
//History: $
?>