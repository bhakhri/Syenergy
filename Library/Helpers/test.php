<?php
global $FE;

require_once($FE . "/Library/common.inc.php");

//error_reporting(E_ALL);
require_once(BL_PATH . "/Helpers/EmailManager.inc.php");

$emailManager=EmailManager::getInstance();

$emailManager->sendRegistrationMail("pdas47@gmail.com", "Prasenjit", "http://yahoo.com/");

?>