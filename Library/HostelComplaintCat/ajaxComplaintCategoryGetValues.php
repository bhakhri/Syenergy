<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE COMPLAINT CATEGORY LIST
//
//
// Author : Gurkeerat Sidhu
// Created on : (23.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ComplaintCategory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['complaintCategoryId'] ) != '') {
    require_once(MODEL_PATH . "/HostelComplaintCatManager.inc.php");
    $foundArray = ComplaintManager::getInstance()->getComplaintCategory(' WHERE complaintCategoryId="'.$REQUEST_DATA['complaintCategoryId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
		die();
    }
    else {
        echo 0;
    }
}

?>