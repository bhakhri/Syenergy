<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifLoggedIn();
require_once(BL_PATH . "/Index/initForgot.php");
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
//document.getElementById('username').focus();
</script><style>
.text_loginpanel{font-family: Arial;
        font-size: 12px;
 font-weight:bold;

    font-size: 12px;
    color: #fff;
}

A.redLink{
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px; 
    font-weight: bold;
    color: #bb0000 !important;
    text-decoration: none; 
}
A.redLink:hover{
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px; 
    font-weight: bold;
    color: #bb0000;    
    text-decoration: underline; 
}

.text_menu_login {
    font-family: verdana, Helvetica, sans-serif;
    font-size: 12px;
     
    color: #FFFFFF;
    }
</style>
</head>
<body class="login_body">
	<?php require_once(TEMPLATES_PATH . "/Index/listForgot.php");?>
</body>
<script type="text/javascript" language="javascript">
if(document.getElementById) document.getElementById('username').focus();
</script>   
</html>

