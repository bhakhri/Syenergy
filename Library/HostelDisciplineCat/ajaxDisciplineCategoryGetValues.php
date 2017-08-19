<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DISCIPLINE CATEGORY LIST
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
define('MODULE','DisciplineCategory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['disciplineCategoryId'] ) != '') {
    require_once(MODEL_PATH . "/HostelDisciplineCatManager.inc.php");
    $foundArray = DisciplineManager::getInstance()->getDisciplineCategory(' WHERE disciplineCategoryId="'.$REQUEST_DATA['disciplineCategoryId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
		die();
    }
    else {
        echo 0;
    }
}

?>