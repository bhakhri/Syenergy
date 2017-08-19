<?php
//-------------------------------------------------------
// Purpose: Perform Algorithem for selecting candidate and insert it into adm_candidate_status table.
//
// Author : Vimal Sharma
// Created on : 11-Feb-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AdmitStatus');
define('ACCESS','add');
UtilityManager::ifAdmissionNotLoggedIn();
UtilityManager::headerNoCache();

$errorMessage           =''; 
$programDetails         = array();

require_once(MODEL_PATH . "/Admission/CandidateManager.inc.php");   
require_once(MODEL_PATH . "/Admission/CandidateStatusManager.inc.php");

set_time_limit(0);
if (trim($errorMessage) == '') {
    $candidateManager       = CandidateManager::getInstance();
    $candidateStatusManager = CandidateStatusManager::getInstance();

    $candidateStatusManager->emptyStatus();
    $candidateRecordArray   = $candidateManager->getAllCandidate();
      
    $allProgramRecordArray  = $candidateManager->getAllProgram();
    if (is_array($allProgramRecordArray) && count($allProgramRecordArray) > 0 ) {
        $totalRecords = count($allProgramRecordArray);
        
        for($i = 0; $i < $totalRecords; $i++) {
             $allProgramRecordArray[$i]['total'] = 0;
             $programDetails[$allProgramRecordArray[$i]['programId']] = $allProgramRecordArray[$i] ;
        }
        
        $candidateRecordArray   = $candidateManager->getAllCandidate(); 
        if (is_array($candidateRecordArray) && count($candidateRecordArray) > 0 ) { 
            $totalCandidateRecords  = count($candidateRecordArray);
            for($i = 0; $i < $totalCandidateRecords; $i++) {
                $candidateId   = $candidateRecordArray[$i]['candidateId'];          
                $programType   = $candidateRecordArray[$i]['programType'];
                $condition     = " WHERE acs.candidateId = $candidateId";
                $candidateStatusCheck = $candidateRecordArray[$i]['candidateStatus'] ; 
                if ( $candidateStatusCheck == 'A') {
                    $programId          = $candidateRecordArray[$i]['programId'];
                    $candidateStatus    = "A";
                    $displayOrder       = $candidateStatusOrderArr[$candidateStatus];
                    $candidateStatusManager->addCandidateStatus($candidateId, $programId,"'$candidateStatus'", $displayOrder);
                    $programDetails[$programId]['total']  = $programDetails[$programId]['total'] + 1;
                } elseif ($candidateStatusCheck == 'R') {
                    $candidateStatusManager->addCandidateStatus($candidateId, 0,"'R'", 3);  
                } else {
                     //To check Candidate Status
                     $result        = $candidateStatusManager->getTotalCandidateStatus($condition);
                     $seatStatus    = false;  
                     if ($result[0]['totalRecords'] == 0 ) {
                         //Get all candidate preference
                         $candidateProgramPreference   = CandidateManager::getInstance()->getCandidateProgramPreference($candidateId); 
                         if(is_array($candidateProgramPreference) && count($candidateProgramPreference)>0 ) {  
                            $totalRecords = count($candidateProgramPreference);
                            for($j=0; $j < $totalRecords; $j++) {
                                $programId  = $candidateProgramPreference[$j]['programId'];
                                $preference = $candidateProgramPreference[$j]['preference'];
                               // echo "\n$candidateId-$programId-$preference-".$programDetails[$programId]['seats']."=".$programDetails[$programId]['total'];
                                if ($programDetails[$programId]['seats'] > $programDetails[$programId]['total']) {
                                    $candidateStatus    = "O";
                                    $displayOrder       = $candidateStatusOrderArr[$candidateStatus];
                                    $candidateStatusManager->addCandidateStatus($candidateId, $programId,"'$candidateStatus'", $displayOrder);
                                    $programDetails[$programId]['total']  = $programDetails[$programId]['total'] + 1;
                                    $seatStatus         = true;
                                    break;            
                                }
                            }
                            if($seatStatus == false) {
                                $candidateStatusManager->addCandidateStatus($candidateId, 0, "'S'", 2);
                            }                     
                        } else {
                            echo 'Error getting candidate preference';
                            die();
                        }
                    }
                }
            }
            echo SUCCESS;
        } else {
            echo 'Error getting candidate details';
            die();
        }        
    } else {
        echo 'Error getting all programs';
        die();
    }
} else {
    echo $errorMessage;  
}    
?>

<?php
 /*       
        for ($i=0; $i < $totalCandidateRecords; $i++) {
            

            
            $candidateRecordArray[$i]['candidateStatus']  = $candidateStatusArr[$candidateRecordArray[$i]['candidateStatus']];
            $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$candidateRecordArray[$i]);
            if(trim($json_val)=='') {
                $json_val = json_encode($valueArray);
            } else {
                $json_val .= ','.json_encode($valueArray);           
            }
    
    //require_once(MODEL_PATH . "/ScStudentManager.inc.php");
    //$studentManager = StudentManager::getInstance();
    $dateOfBirth = $REQUEST_DATA['candidateYear'] . "-" . $REQUEST_DATA['candidateMonth'] . "-" . $REQUEST_DATA['candidateDate'] . "-"; 
    $query = "INSERT INTO application_form SET 
      `candidateName` = " . $REQUEST_DATA['candidateName'] . ",
      `dateOfBirth` = " . $dateOfBirth . ",
      `fatherGuardianName` = " . $REQUEST_DATA['fatherGuardianName'] . ",
      `fatherGuardianMobileNo` = " . $REQUEST_DATA['fatherGuardianMobile'] . ",
      `relationWithCandidate` = " . $REQUEST_DATA['relationWithCandidate'] . ",
      `AIEEERollNo` = " . $REQUEST_DATA['AIEEERollNo'] . ",
      `AIEEERank` = " . $REQUEST_DATA['AIEEERank'] . ",
      `hostelFacility` = " . $REQUEST_DATA['hostelFacility'] . ",
      `quotaId` = " . $REQUEST_DATA['candidateCategory'] . ",
      `programType` = " . $REQUEST_DATA['programType'] . ",
      `candidateMobileNo` = " . $REQUEST_DATA['candidateMobile'] . ",
      `candidateEmail` = " . $REQUEST_DATA['candidateEmail'] . ",
      `candidateGender` = " . $REQUEST_DATA['candidateGender'] . ",
      `formNo` = " . $REQUEST_DATA['formNo'];
    echo $query;
    /*if($returnStatus === false){

        echo FAILURE;
    }
    else{

        echo SUCCESS;
    }       

     
  */ 
?>