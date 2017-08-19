<?php
//-------------------------------------------------------
// This file is used to get values of selected candidate
//
//
// Author : Vimal Sharma
// Created on : (06.02.2009 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CandidateMaster');
define('ACCESS','view');
UtilityManager::ifAdmissionNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['candidateId'] ) != '') {
    require_once(MODEL_PATH . "/Admission/CandidateStatusManager.inc.php");
    $foundArray     = CandidateStatusManager::getInstance()->getCandidateStatusList(' WHERE acs.candidateId=' . $REQUEST_DATA['candidateId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        $json_candidate = json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
    echo '{"candidateInfo" : [' . $json_candidate . ']}';     
}



?>
