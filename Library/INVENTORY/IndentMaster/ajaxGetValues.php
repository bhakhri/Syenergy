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
require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
define('MODULE','OrderMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['indentId'] ) != '' and trim($REQUEST_DATA['indentId'])!='') {
    
    $indentManager =  IndentManager::getInstance();
    
    //check whether this record is issues or not
    //if issued then it can not be edited
    $recordArray=$indentManager->checkIssueedIndent(add_slashes(trim($REQUEST_DATA['indentId'])));
    if($recordArray[0]['found']>0){
        echo INDENT_EDIT_RESTRICTION;
        die;
    }
        
    $foundArray = $indentManager->getIndentDetails(' AND ir.indentId="'.add_slashes(trim($REQUEST_DATA['indentId'])).'"' );
    if(is_array($foundArray) && count($foundArray)>0 ) {  
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
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:22
//Created in $/Leap/Source/Library/INVENTORY/IndentMaster
//Created  "Indent Master" module under "Inventory Management"
?>