<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','ChangeTestCategory');
	define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	$classId = $REQUEST_DATA['degree']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$groupId = $REQUEST_DATA['groupId']; //3
	$labelId = $REQUEST_DATA['labelId']; //3
	$testId = $REQUEST_DATA['testId']; //3
	$newTestTypeCategoryId = $REQUEST_DATA['newTestType']; //3
        $testIndexCheck=$REQUEST_DATA['testIndex'];
        


	if (empty($classId) or empty($subjectId) or empty($groupId) or empty($labelId) or empty($testId) or empty($newTestTypeCategoryId)) {
		echo REQUIRED_PARAMETERS_MISSING;
		die;
	}

	if (!is_numeric($classId) or !is_numeric($subjectId) or !is_numeric($groupId) or !is_numeric($labelId) or !is_numeric($testId) or !is_numeric($newTestTypeCategoryId)) {
		echo REQUIRED_PARAMETERS_MISSING;
		die;
	}

	$currentTestIdArray = $studentManager->getTests($classId, $subjectId, $groupId, " AND a.testId = $testId");
	if (!count($currentTestIdArray) or $currentTestIdArray[0]['testId']  == '') {
		echo INVALID_TEST_SELECTED;
		die;
	}

	$currentTestTypeCategoryId = $currentTestIdArray[0]['testTypeCategoryId'];

	$subjectTypeArray = $studentManager->getSubjectTypeId($subjectId);
	$subjectTypeId = $subjectTypeArray[0]['subjectTypeId'];

	$otherTestCategoryArray = $studentManager->getOtherTestCategory($currentTestTypeCategoryId, $subjectTypeId);
	$otherTestCategoryArray = explode(',', UtilityManager::makeCSList($otherTestCategoryArray, 'testTypeCategoryId'));

	if (!in_array($newTestTypeCategoryId, $otherTestCategoryArray)) {
		echo INVALID_TEST_TYPE_SELECTED;
		die;
	}
	else {
		$newTestIndexArray = $studentManager->getNewTestIndexNew($classId, $subjectId, $groupId, $newTestTypeCategoryId);
        $testIndex='0';
        for($i=0;$i<count($newTestIndexArray);$i++) {
           $ttTestIndex = trim($newTestIndexArray[$i]['testIndex']);  
           if($ttTestIndex > $testIndex) {
             $testIndex= $ttTestIndex; 
           }
        }
        $newTestIndex = $testIndex + 1; //new test index
        
        //$newTestIndexArray = $studentManager->getNewTestIndex($classId, $subjectId, $groupId, $newTestTypeCategoryId);
		//$testIndex = $newTestIndexArray[0]['testIndex'];
		if($newTestIndex != $testIndexCheck){
    	  echo "Mismatch of Test Index Value";
          die;
        }
		
		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$return = $studentManager->upNewTestIndexInTransaction($classId, $subjectId, $groupId, $testId, $newTestTypeCategoryId, $newTestIndex);
			if ($return == false) {
				echo FAILURE;
				die;
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo SUCCESS;
			}
			else {
				echo FAILURE;
			}
		}
		else {
			echo FAILURE;
		}
	}

?>
