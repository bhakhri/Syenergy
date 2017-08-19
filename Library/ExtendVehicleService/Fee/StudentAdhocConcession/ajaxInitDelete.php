<?php
//-------------------------------------------------------
// Purpose: To delete Vehicle Route Allocation
// Author : Nishu Bindal
// Created on : (05.April.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

 global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','StudentAdhocConcessionNew');
	define('ACCESS','delete');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');


     global $sessionHandler;
    

    require_once(MODEL_PATH . "/Fee/StudentAdhocConcessionManager.inc.php");
    $studentAdhocConcessionManager = StudentAdhocConcessionManager::getInstance();
	


    if(!isset($REQUEST_DATA['rollNo']) || trim($REQUEST_DATA['classId']) == ''){
      $errorMessage = "Required Parameter is missing.";
      echo $errorMessage;
      die;   
    }
   $rollNo =$REQUEST_DATA['rollNo'];
   $classId = $REQUEST_DATA['classId'];
   
    // Fetch StudentId and ClassId
    $condition = " (st.rollNo LIKE '$rollNo' OR st.regNo LIKE '$rollNo'  OR st.universityRollNo LIKE '$rollNo') ";
	$getStudentArray = $studentAdhocConcessionManager->getStudentConcessionId($condition);
	
	$studentId = $getStudentArray[0]['studentId'];
	
    $ret1=$studentAdhocConcessionManager->deleteStudentConcession($studentId,$classId);
    
	if($ret1===true){
	$updateArray =	$studentAdhocConcessionManager->updateStudentFeeConcession($studentId,$classId);	
		if($updateArray===true){
			echo DELETE;			
		}else{
			echo FAILURE;
		}
	}
    
?>

