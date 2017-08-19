<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 30-Mar-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CourseResourceMaster');
define('ACCESS','add');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
	$teacherManager  = TeacherManager::getInstance();
	$subjectId = $REQUEST_DATA['subjectId'];
	//fetching subject data only if any one class is selected

    if(trim($subjectId)==''){
        echo 'Required Parameters Missing';
        die;
    }
    
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	$groupsArray = $teacherManager->getSubjectGroups($subjectId);
	echo json_encode($groupsArray);
?>