<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT CANDIDATE STATUS
//
//
// Author : Vimal Sharma
// Created on : (19.02.2009 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php"); 
define('MODULE','CandidateMaster');
define('ACCESS','Edit');
UtilityManager::ifAdmissionNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['candidateStatusType']) || trim($REQUEST_DATA['candidateStatusType']) == '') {
        $errorMessage .=  "SELECT_CANDIDATE_STATUS" ."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['programAlloted']) || trim($REQUEST_DATA['programAlloted']) == '')) {
        $errorMessage .= "SELECT_PROGRAM" ."\n";  
    }
    if (trim($errorMessage) == '') {
        
        require_once(MODEL_PATH . "/Admission/CandidateManager.inc.php");
        require_once(MODEL_PATH . "/Admission/CandidateStatusManager.inc.php");  
        if(SystemDatabaseManager::getInstance()->startTransaction()) { 
            $query  = "UPDATE adm_application_form SET candidateStatus = '" .$REQUEST_DATA['candidateStatusType'] . "', programId = " .$REQUEST_DATA['programAlloted']  . " WHERE candidateId = " . $REQUEST_DATA['candidateId']; 
            $query1 = "UPDATE adm_candidate_status SET candidateStatus = '" .$REQUEST_DATA['candidateStatusType'] . "', programId = " .$REQUEST_DATA['programAlloted']  . " WHERE candidateId = " . $REQUEST_DATA['candidateId']; 
            $returnStatus = CandidateManager::getInstance()->editCandidateInTransaction($query);
            $returnStatus1 = CandidateManager::getInstance()->editCandidateInTransaction($query1); 
            if($returnStatus === false && $returnStatus1 === false) {
                echo FAILURE;
            } else {
                if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                    echo SUCCESS;        
                } else {
                    echo FAILURE; 
                }   
            }           
        } else {
            echo FAILURE;    
        }
        
                

        
    } else {
        echo $errorMessage;
    }
?>
