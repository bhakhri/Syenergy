<?php

//--------------------------------------------------------  
// Purpose: This file contains validations and checks for the userName in Forgot Password
//
// Author:Arvind Singh Rawat
// Created on : (23.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------      


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//UtilityManager::ifLoggedIn();
require_once(BL_PATH . "/Index/initVerifyForgot.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Login </title>
<?php 
    echo UtilityManager::includeCSS("css.css");
    echo UtilityManager::includeCSS('winjs/default.css');
    echo UtilityManager::includeCSS('winjs/alphacube.css');    
    echo UtilityManager::includeJS("winjs/prototype.js"); 
    echo UtilityManager::includeJS("winjs/window.js"); 
    echo UtilityManager::includeJS("functions.js");  
?>
<script language="javascript">
/*
function validateLoginForm() {
    var fieldsArray = new Array(new Array("username","Enter username") );
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(document.getElementById(fieldsArray[i][0]).value) ) {
            winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            return false;
            break;
        }
        else {
            unsetAlertStyle(fieldsArray[i][0]);
        }
        
    }
}
*/
//document.getElementById('username').focus();
</script>
</head>
<body>
	<?php 
        require_once(TEMPLATES_PATH . "/Index/listVerifyForgot.php");
    ?>
</body>
</html>
<?php //$History: verifyCode.php $ 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/22/08   Time: 11:10a
//Created in $/LeapCC/Interface
//verifycode file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/15/08   Time: 12:34p
//Updated in $/Leap/Source/Interface
//Verification code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/13/08   Time: 5:28p
//Created in $/Leap/Source/Interface
//password forgot added
//
//*****************  Version 4  *****************
//User: Arvind       Date: 10/23/08   Time: 2:47p
//Updated in $/Leap/Source/Interface
//added focus on username
//
//*****************  Version 3  *****************
//User: Arvind       Date: 10/03/08   Time: 3:50p
//Updated in $/Leap/Source/Interface
//modify
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/24/08    Time: 12:54p
//Updated in $/Leap/Source/Interface
//added comments
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/24/08    Time: 12:51p
//Created in $/Leap/Source/Interface
//initial checkin
?>