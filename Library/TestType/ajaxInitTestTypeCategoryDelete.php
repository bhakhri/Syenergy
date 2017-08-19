<?php
//-------------------------------------------------------
// Purpose: To delete testtype detail
//
// Author : 
// Created on : (19.02.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
require_once(MODEL_PATH . "/TestTypeManager.inc.php");

$commonQueryManager = CommonQueryManager::getInstance();
 $testTypeManager =  TestTypeManager::getInstance();
define('MODULE','TestTypeCategoryMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


global $sessionHandler;
$queryDescription ='';

    if (!isset($REQUEST_DATA['testTypeCategoryId']) || trim($REQUEST_DATA['testTypeCategoryId']) == '') {
        echo 'Invalid TestType';
	die;
    }
		$checkTestTypeCategory = $testTypeManager -> checkTestTypeCategory('WHERE testTypeCategoryId="'.$REQUEST_DATA['testTypeCategoryId'].'"');
		if ($checkTestTypeCategory[0]['foundRecord'] > 0 ){
			echo DEPENDENCY_CONSTRAINT;
			die;
		}

		$checkTestCategory = $testTypeManager -> checkTestCategory('WHERE testTypeCategoryId="'.$REQUEST_DATA['testTypeCategoryId'].'"');
		if( $checkTestCategory[0]['foundRecord'] > 0){
			echo DEPENDENCY_CONSTRAINT;
			die;
		}

		else {
       

				
				if($testTypeManager->deleteTestTypeCategory($REQUEST_DATA['testTypeCategoryId'])) {
				
				########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
				$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');  
				$testTypeCategoryId = $REQUEST_DATA['testTypeCategoryId'];
				$result = TestTypeManager::getInstance()->getTestTypeCategoryName($testTypeCategoryId);
				$testTypeName = $result[0]['testTypeName'];
				$auditTrialDescription = "Following Test Type Category is deleted : $testTypeName " ;
				$type =TEST_TYPE_CATEGORY_IS_DELETED; //Test type category is deleted 
				$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription,$queryDescription);
				if($returnStatus == false) {
					echo  ERROR_WHILE_SAVING_DATA_IN_AUDIT_TRAIL;
					die;
				}
		        ########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
					echo DELETE;
				}
}
   
    
// $History: ajaxInitTestTypeCategoryDelete.php $    
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/17/09    Time: 4:52p
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/24/09    Time: 11:32a
//Created in $/Leap/Source/Library/TestType
//new ajax files for test type category
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:10a
//Updated in $/Leap/Source/Library/TestType
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:32p
//Updated in $/Leap/Source/Library/TestType
//Added dependency constraint check
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/09/08    Time: 7:18p
//Updated in $/Leap/Source/Library/TestType
//Add `Select` as default selected value in dropdowns of University,
//Degree, Branch, Study Period, Evaluation Criteria, subject and subject
//type.
//and made modifications so that data is  being populated in study period
//dropdown
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/25/08    Time: 7:08p
//Updated in $/Leap/Source/Library/TestType
//Added AjaxEnabled Delete functionality
//
?>

