<?php
/*
  This File calls addFunction used in adding Bank Records

 Author :Ajinder Singh
 Created on : 23-July-2008
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','StudentConcession');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
$errorMessage ='';

if (trim($errorMessage) == '') {

	require_once(MODEL_PATH . "/StudentConcessionManager.inc.php");
	$studentConcessionManager = StudentConcessionManager::getInstance();

	$feeHeadIdArr = $REQUEST_DATA['feeHeadId'];
	$chbArr		  = $REQUEST_DATA['chb'];
	$chb1Arr	  = $REQUEST_DATA['chb1'];
	$studentId    = $REQUEST_DATA['studentId'];
	$classId	  = $REQUEST_DATA['classId'];
	$feeCycleId   = $REQUEST_DATA['feeCycleId'];
	$reason		  = $REQUEST_DATA['concessionReason'];
	$userId       = $sessionHandler->getSessionVariable('UserId');
	$dated		  = date('Y-m-d h:i:s');
                                                      
	for($i=0;$i<count($REQUEST_DATA['feeHeadId']);$i++){

		$deleteString ="'".$feeCycleId.'_'.$studentId.'_'.$classId.'_'.$feeHeadIdArr[$i]."'";
		$ret=$studentConcessionManager->deleteStudentConcession($deleteString);
		if($ret==false){
		
			 echo FAILURE;
			 die;
		 }
		 if($insertString!=''){
			
			$insertString .=',';
		 }
		 $discValue = $chb1Arr[$i]-$chbArr[$i];
		 $insertString .= "($feeCycleId,$feeHeadIdArr[$i],$studentId,$classId,$chb1Arr[$i],2,$chbArr[$i],$discValue,'".$reason."',$userId,'".$dated."' )";
	}
	//now make fresh insert
    $ret=$studentConcessionManager->insertStudentConcession($insertString);
    if($ret==false){
     
		 echo FAILURE;
         die;
    }else{
	
			echo CONCESSION_SUCCESS;
        die;
	}
	//echo $insertString;
}
else {
	echo $errorMessage;
}
?>

