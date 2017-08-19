<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A UNIVERSITY
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementFollowUpsMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['companyId']) || trim($REQUEST_DATA['companyId']) == '')) {
        $errorMessage .= SELECT_PLACEMENT_COMPANY_NAME."\n";
    }
    if (!isset($REQUEST_DATA['contactedPerson']) || trim($REQUEST_DATA['contactedPerson']) == '') {
        $errorMessage .= ENTER_FOLLOWUP_CONTACT_PERSON."\n";     
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designation']) || trim($REQUEST_DATA['designation']) == '')) {
        $errorMessage .= ENTER_FOLLOWUP_PERSON_DESIGNATION."\n";     
    }
   // if ($errorMessage == '' && (!isset($REQUEST_DATA['comments']) || trim($REQUEST_DATA['comments']) == '')) {
    //    $errorMessage .= ENTER_FOLLOWUP_COMMENTS."\n";     
  //  }
    
    if(trim($REQUEST_DATA['followUp'])==1){
        if(trim($REQUEST_DATA['followUpBy'])==1){
            if(trim($REQUEST_DATA['followUpMethod'])==''){
                die('Enter email id');
            }
        }
        else{
            if(trim($REQUEST_DATA['followUpMethod'])==''){
                die('Enter mobile no.');
            }
        }
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Placement/FollowUpManager.inc.php");
        $returnStatus = FollowUpManager::getInstance()->editFollowUp(trim($REQUEST_DATA['followUpId']));
        if($returnStatus == false) {
               die(FAILURE);
        }
        die(SUCCESS);
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
?>