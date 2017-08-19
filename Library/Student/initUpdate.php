<?php
$errorMessage = '';
if(isset($REQUEST_DATA['imgSubmit_x']) ) {
    
	/*if (!isset($REQUEST_DATA['username']) || trim($REQUEST_DATA['username']) == '') {
		$errorMessage = "Username cannot be left empty";
		logError('The value of $REQUEST_DATA["username"] in Index/init.php is empty');
	}
	
	if ($errorMessage == '' && (!isset($REQUEST_DATA['password']) || trim($REQUEST_DATA['password']) == '')) {
		$errorMessage = "Password cannot be left empty";
		logError('The value of $REQUEST_DATA["password"] in Index/init.php is empty');
	}*/

	if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/StudentManager.inc.php");
		$studentManager = StudentManager::getInstance();

		echo "<pre>";
		print_r($REQUEST_DATA);
		die();
		/*$concatDegreeId = $REQUEST_DATA['degree'];
		$concatArr		= explode("-", $concatDegreeId);
		$universityID	= $concatArr[0];
		$degreeID		= $concatArr[1];
		$branchID		= $concatArr[2];
		$batchId		= $REQUEST_DATA['batch'];
		$studyperiodId	= $REQUEST_DATA['studyperiod'];
		 
		$classIDArr = $studentManager->getClass($universityID,$degreeID,$branchID,$batchId,$studyperiodId);
		*/
		 
		 
	}
}
?>