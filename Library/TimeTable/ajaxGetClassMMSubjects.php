<?php
//--------------------------------------------------------
//This file returns the array of class, based on time table label Id
//
// Author :Parveen Sharma
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId = $sessionHandler->getSessionVariable('SessionId');

    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();


    $timeTableLabelId = $REQUEST_DATA['timeTabelId'];
    $classId = $REQUEST_DATA['classId'];

    if($timeTableLabelId == '') {
      $timeTableLabelId = 0;
    }

    if($classId=='') {
      $classId = 0;
    }



       $tableName = " subject_to_class a, subject b";
       $fieldsName =" a.subjectId, b.subjectCode";
       $condition = " WHERE a.subjectId = b.subjectId and a.optional = 1 and a.hasParentCategory = 1 and a.hasParentCategory = 1 and a.offered = 1 and a.classId = $classId ORDER BY b.subjectCode";
       $foundArray = $studentManager->getSingleField($tableName, $fieldsName, $condition);

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