<?php 
//--------------------------------------------------------------------------
//  This File calls addFunction used in adding FEE HEAD VALUES Records
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadValues');  
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
  
	if (!isset($REQUEST_DATA['feeCycleId']) || trim($REQUEST_DATA['feeCycleId']) == '') {
        $errorMessage .= SELECT_FEECYCLE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['feeHeadId']) || trim($REQUEST_DATA['feeHeadId']) == '')) {
        $errorMessage .= SELECT_FEEHEAD."\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['feeFundAllocationId']) || trim($REQUEST_DATA['feeFundAllocationId']) == '')) {
        $errorMessage .= SELECT_FEEFUNDALLOCATION."\n";
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['feeHeadAmount']) || trim($REQUEST_DATA['feeHeadId']) == '')) {
        $errorMessage .= ENTER_FEEHEADVALUES_AMOUNT."\n";
    }
	
    if($REQUEST_DATA['quotaId']!='') {
      $condition = ' AND quotaId="'.add_slashes($REQUEST_DATA['quotaId']).'"';
    }
    
	if($REQUEST_DATA['universityId']!='') {
      $condition .= ' AND universityId="'.add_slashes($REQUEST_DATA['universityId']).'"';
    }
    
    if($REQUEST_DATA['degreeId']!='') {
      $condition .= ' AND degreeId="'.add_slashes($REQUEST_DATA['degreeId']).'"';
    }
    
    if($REQUEST_DATA['branchId']!='') {
      $condition .= ' AND branchId="'.add_slashes($REQUEST_DATA['branchId']).'"';
    }
    
    if($REQUEST_DATA['batchId']!='') {
      $condition .= ' AND batchId="'.add_slashes($REQUEST_DATA['batchId']).'"';
    }
    
    if($REQUEST_DATA['studyPeriodId']!='') {
      $condition .= ' AND batchId="'.add_slashes($REQUEST_DATA['studyPeriodId']).'"';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");

            $foundArray = FeeHeadValuesManager::getInstance()->getFeeHeadValues(' WHERE feeCycleId="'.add_slashes($REQUEST_DATA['feeCycleId']).'" AND feeHeadId="'.add_slashes($REQUEST_DATA['feeHeadId']).'" AND feeFundAllocationId="'.add_slashes($REQUEST_DATA['feeFundAllocationId']).'" '.$condition);
            
         if(trim($foundArray[0]['feeHeadId'])=='') { 
			$returnStatus = FeeHeadValuesManager::getInstance()->addFeeHeadValues();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo 'Fee head value entered already exists.';
        }
    }
    else {
        echo $errorMessage;
    }
 

//$History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/01/09    Time: 5:38p
//Updated in $/LeapCC/Library/FeeHeadValues
//quotawise & all condition update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeHeadValues
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeHeadValues
//Define Module, Access  Added
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/09/08    Time: 7:17p
//Updated in $/Leap/Source/Library/FeeHeadValues
//added common messages
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/29/08    Time: 12:56p
//Updated in $/Leap/Source/Library/FeeHeadValues
//removed validation for quotaId
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/25/08    Time: 12:19p
//Updated in $/Leap/Source/Library/FeeHeadValues
//added validation for quota
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/21/08    Time: 5:19p
//Updated in $/Leap/Source/Library/FeeHeadValues
//added validations
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/19/08    Time: 12:45p
//Created in $/Leap/Source/Library/FeeHeadValues
//initial checkin

?>
