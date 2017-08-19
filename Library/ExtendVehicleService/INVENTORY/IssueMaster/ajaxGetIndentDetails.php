<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(INVENTORY_MODEL_PATH . "/IssueManager.inc.php");
define('MODULE','IssueMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['indentNo'] ) != '' and trim($REQUEST_DATA['indentNo'])!='') {
    
    $issueManager =  IssueManager::getInstance();
    
    $foundArray = $issueManager->getIndentDetails(' AND iri.indentNo="'.add_slashes(trim($REQUEST_DATA['indentNo'])).'"' );
    if(is_array($foundArray) && count($foundArray)>0 ) {
        foreach($foundArray as &$val){
         $val['dated']    = UtilityManager::formatDate($val['dated']);
        }
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
else{
    echo 0;
    die;
}
// $History: ajaxGetIndentDetails.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/09/09   Time: 18:53
//Created in $/Leap/Source/Library/INVENTORY/IssueMaster
//Created "Issue Master" under inventory management in leap
?>