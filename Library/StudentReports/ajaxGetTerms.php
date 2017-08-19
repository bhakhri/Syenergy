<?php
//--------------------------------------------------------  
//It contains the time table according findout terms
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH.'/CommonQueryManager.inc.php');
    $commonQueryManager = CommonQueryManager::getInstance(); 
    
    $tclassId = $REQUEST_DATA['classId'];
    if($tclassId=='') {
      $tclassId=0;  
    }
    $condition = " AND c.classId = ".$tclassId;
    $foundArray = $commonQueryManager->getDegreeName($condition);
    
    $condition='';
    $condition = " AND c.branchId = '".$foundArray[0]['branchId']."' AND c.batchId = '".$foundArray[0]['batchId']."'";
    $regDegreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE');  
    if($foundArray[0]['degreeCode']!=$regDegreeCode) {
      $recordCount = 0;
    }
    else {
      $foundArray1 = $commonQueryManager->getRegistrationDegreeList($condition);
    }
    
    
    echo json_encode($foundArray1);     
?>
<?php
//$History: ajaxGetTerms.php $
//


?>