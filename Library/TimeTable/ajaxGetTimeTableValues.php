<?php
//--------------------------------------------------------
//This file returns the array of class, based on time table label Id
//
// Author :Parveen Sharma
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId = $sessionHandler->getSessionVariable('SessionId');

    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();


    $timeTableLabelId = $REQUEST_DATA['timeTabelId'];
    $timeTableType = $REQUEST_DATA['timeTableType'];
    $typeFormat = $REQUEST_DATA['typeFormat'];

    if($timeTableLabelId=='') {
      $timeTableLabelId =0;
    }

    if($timeTableType=='') {
      $timeTableType=0;
    }


    if($typeFormat=='cs') {      // Subject + Class wise
       $foundArray = AdminTasksManager::getInstance()->getTimeTableClassesWithSubjects(" AND cls.instituteId=$instituteId AND cls.sessionId=$sessionId  AND ttl.timeTableType=$timeTableType AND ttl.timeTableLabelId=$timeTableLabelId"," className");
    }
    elseif($typeFormat=='c') {      // Class wise
       $foundArray = AdminTasksManager::getInstance()->getTimeTableClasses(" AND cls.instituteId=$instituteId AND cls.sessionId=$sessionId  AND ttl.timeTableType=$timeTableType AND ttl.timeTableLabelId=$timeTableLabelId"," className");
    }
    else if($typeFormat=='r') {      // Room wise
       $tableName = " room_institute ri, room r, block b, building c,  ".TIME_TABLE_TABLE." tt, time_table_labels ttl";
       $fieldsName =" DISTINCT r.roomId, CONCAT(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) AS roomName";
       $condition = " WHERE r.roomId = ri.roomId AND ri.instituteId=$instituteId AND r.blockId = b.blockId AND
                            b.buildingId = c.buildingId AND tt.roomId=r.roomId AND ttl.timeTableType=$timeTableType AND
                            ttl.timeTableLabelId=$timeTableLabelId AND tt.instituteId=$instituteId AND tt.sessionId=$sessionId ORDER BY roomName";
       $foundArray = $studentManager->getSingleField($tableName, $fieldsName, $condition);
    }
    else if($typeFormat=='t') {      // Teacher wise
       $fieldsName =" DISTINCT e.employeeId, CONCAT(employeeName,' (',employeeCode,')') AS employeeName";
       $tableName = " employee e,  ".TIME_TABLE_TABLE." tt, time_table_labels ttl";
       $condition = " WHERE tt.employeeId=e.employeeId AND ttl.timeTableType=$timeTableType AND ttl.timeTableLabelId=$timeTableLabelId AND 
                            tt.instituteId=$instituteId AND tt.sessionId=$sessionId ORDER BY employeeName ASC ";
       $foundArray = $studentManager->getSingleField($tableName, $fieldsName, $condition);
    }
	 else if ($typeFormat=='mapped') {
       $foundArray = AdminTasksManager::getInstance()->getMappedTimeTableClasses($timeTableLabelId);
	 }
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }

// $History: ajaxGetTimeTableClass.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/29/10    Time: 4:18p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/16/10    Time: 12:01p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//



?>