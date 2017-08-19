<?php
//-------------------------------------------------------
// Purpose: To generate student marks report
// functionality 
//
// Author : Dipanjan Bhattacharjee
// Created on : (31.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
if(isset($REQUEST_DATA['id']) and $REQUEST_DATA['id'] > 0){
 require_once(BL_PATH . "/Teacher/StudentActivity/initList.php"); 
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Marks</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("ajax.js");  
echo UtilityManager::includeJS("tab-view.js"); 


//pareses input and returns 0 if the input is blank
//Author: Dipanjan Bhatacharjee
//Date:14.7.2008
function parseInput($input) {
    return ( (trim($input)!="" ? $input : 0 ) );
}

//pareses input and returns "-" if the input is blank
//Author: Dipanjan Bhatacharjee
//Date:14.7.2008
function parseOutput($data){
    
     return ( (trim($data)!="" ? $data : "---" ) );  
    
}
?> 

<script language="javascript">

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/StudentActivity/studentDetailMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: studentDetailMarks.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:26p
//Created in $/Leap/Source/Interface/Teacher
?>
