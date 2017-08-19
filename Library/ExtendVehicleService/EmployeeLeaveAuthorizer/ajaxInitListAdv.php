<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeLeaveAuthorizerAdv');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeLeaveAuthorizerManager.inc.php");
    $empMgr= EmployeeLeaveAuthorizerManager::getInstance();

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    // get Active session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId'];
    }

    if($leaveSessionId=='') {
      $leaveSessionId=0;
    }

    $condition = " AND lsa.leaveSessionId = $leaveSessionId";
    //finding emps from authorizer table
    $empArray=$empMgr->getEmpsFromAuthrozerTable($condition);
    if(count($empArray)>0 and is_array($empArray)) {
       echo json_encode($empArray);
       die;
    }
    else{
       echo 0;
       die;
    }

// $History: ajaxGetValues.php $
?>