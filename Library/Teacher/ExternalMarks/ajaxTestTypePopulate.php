<?php
//-----------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test type [subject centric]
//
//
// Author : Jaineesh 
// Created on : (04.04.09)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH."/CommonQueryManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();

$classId=trim($REQUEST_DATA['classId']); 
$subjectId=trim($REQUEST_DATA['subjectId']);
$conditions="AND conductingAuthority = 2 AND subjectId = '$subjectId' AND classId='$classId'";
$results = CommonQueryManager::getInstance()->getTestType('testTypeName',$conditions);
if(is_array($results) && count($results)>0 ) {  
        echo json_encode($results);
    }
    else {
        echo 0;
    }

?>
