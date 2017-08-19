<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
$studentId = trim($REQUEST_DATA['studentId']);
define('MODULE','PreAdmissionMaster');
if($studentId=='') {
  define('ACCESS','add');
}
else {
  define('ACCESS','edit');  
}
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/PreAdmissionManager.inc.php");
	$preAdmissionManager = PreAdmissionManager::getInstance();
    
    global $sessionHandler;  
    
    // Duplicate Check
    $admissionNumber = htmlentities(add_slashes(trim($REQUEST_DATA['admissionNumber'])));
    $studentId = trim($REQUEST_DATA['studentId']);
    
    if($admissionNumber=='') {
      $admissionNumber='';  
    } 
    
    
    
    $condition = " AND sp.admissionNumber LIKE '$admissionNumber' ";
    if($studentId!='') {
      $condition = " AND sp.studentId <> '$studentId' ";  
    }
    $returnArray = $preAdmissionManager->getTotalPreAdmission($condition);
    if(is_array($returnArray) && count($foundArray)>0 ) { 
       if($returnArray[0]['totalRecords']>0) {
          echo "Admission No. already exist";
          die; 
       } 
    }
    
		    
	//******************************STRAT TRANSCATION*********************************
	if(SystemDatabaseManager::getInstance()->startTransaction()) { 

        if($studentId=='') {    
          $returnStatus = $preAdmissionManager->intAddEdit("INSERT");
        }
        else {
          $returnStatus = $preAdmissionManager->intAddEdit("UPDATE",$studentId);  
        }
        if($returnStatus === false) {
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
	else{
		echo FAILURE;
		die;
	}
    //******************************END TRANSCATION*********************************
?>