<script language="javascript"> 
    function getLogoutRedirectLink(str) {
       if(!isUrl(str)) {
         window.location = "http://"+str;   
         return false;
       } 
       window.location = str;
       return false;
    }
    function isUrl(s) {
      var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
      return regexp.test(s);
    }
</script>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . '/SessionManager.inc.php');

global $sessionHandler;
$urlLink = trim($sessionHandler->getSessionVariable('LOGOUT_HOME_LINK')); 

if($sessionHandler->getSessionVariable('UserId')) {
  require_once(MODEL_PATH . "/LoginManager.inc.php");
  LoginManager::getInstance()->updateUserLogTimeOut();
}
$sessionHandler->destroySession();
if($urlLink=='') {
   redirectBrowser(HTTP_PATH); 
}
else {
   echo "<script>getLogoutRedirectLink('$urlLink');</script>";
}
?>

