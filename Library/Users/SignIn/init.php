<?php
$errorMessage = '';
if(isset($REQUEST_DATA['cmdSubmit']) && trim($REQUEST_DATA['cmdSubmit']) != '') {
	if (!isset($REQUEST_DATA['userID']) || trim($REQUEST_DATA['userID']) == '') {
		$errorMessage = "UserID cannot be left empty";
	}
	
	if ($errorMessage == '' && (!isset($REQUEST_DATA['password']) || trim($REQUEST_DATA['password']) == '')) {
		$errorMessage = "Password cannot be left empty";
	}

	if (trim($errorMessage) == '') {
		global $FE; require_once($FE . "/Library/Users/SignIn/SignInManager.inc.php");
		$signInManager = SignInManager::getInstance();
		$returnStatus = $signInManager->authenticateMember($REQUEST_DATA['userID'], $REQUEST_DATA['password']);
		
		if (!is_array($returnStatus) || count($returnStatus) != 1) {
			$errorMessage = "That username and/or password has not been recognised";
			logError("user " . $REQUEST_DATA['userID'] . " trying to login", WARNING_SEVERITY);
		}
		else {			
			global $FE; require_once($FE . "/Library/SessionManager.inc.php");
			$sessionManager = SessionManager::getInstance();
			$sessionManager->setSessionVariable('userID', $REQUEST_DATA['userID']);
			$sessionManager->setSessionVariable('databaseUserId', $returnStatus[0]['id']);
			$signInManager->updateLastLoginTime($returnStatus[0]['id']);
			redirectBrowser(HTTP_PATH . "/Publish/index.php");
			exit;
		}
	}


} 
?>