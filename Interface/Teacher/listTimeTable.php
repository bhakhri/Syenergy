<?php 
//-------------------------------------------------------
//  This File contains the template file and data base file
// Author :Dipanjan Bhattacharjee
// Created on : 30-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherTimeTableDisplay');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
require_once(BL_PATH . "/Teacher/TeacherActivity/initTimeTable.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Time Table </title>
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
?>

<script language="javascript">

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO get time table date for a class
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.082008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO get time table date for a class
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.082008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function getTimeTableData() {
    
    var name = document.getElementById('timeTableLabelId');  
    var timeTableLabel = name.options[name.selectedIndex].text;
  
    url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTimeTable.php';
    hideDetails(); 
    new Ajax.Request(url,
    {
      method:'post',
      asynchronous: false, 
      parameters: {
            timeTableLabelId: document.timeTableForm.timeTableLabelId.value
            // timeTableLabel :  timeTableLabel 
         },
      onCreate: function() {
         showWaitDialog(true);
      },
      onSuccess: function(transport){
         hideWaitDialog(true);
         document.getElementById("resultRow").style.display='';
         document.getElementById('resultRow').innerHTML=trim(transport.responseText);
         changeColor(currentThemeId);
      },
      onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}


var topPos = 0;
var leftPos = 0;
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

function printReport() {
    var timeTableLabelId = document.getElementById('timeTableLabelId').value;  
    var name = document.getElementById('timeTableLabelId');  
    var timeTableLabel = name.options[name.selectedIndex].text;     
    
    path='<?php echo UI_HTTP_PATH;?>/Teacher/teacherTimeTableReportPrint.php?timeTableLabelId='+timeTableLabelId+'&timeTableLabel='+timeTableLabel;
    window.open(path,"TimeTableReport","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
}

window.onload=function(){
   getTimeTableData();
}

</script> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listTimeTableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
//History: $
?>