<?php
//-------------------------------------------------------
// Purpose: To store the records of group in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (07.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();

    
    if ($REQUEST_DATA['rollNo']!='' )
    {
    
    
    $studentArray = $studentManager->getSingleStudentList('AND UCASE(s.rollNo)="'.add_slashes(strtoupper($REQUEST_DATA['rollNo'])).'"');
    
    }
    else
    {
    $studentArray = $studentManager->getAllStudentList();
    }
    
       

// for VSS
// $History: initList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:26p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: Updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/StudentReports
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/18/08    Time: 11:12a
//Created in $/Leap/Source/Library/StudentReports
//give the query for student reports
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/10/08    Time: 5:18p
//Updated in $/Leap/Source/Library/StudentReport
//modification for print report
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/10/08    Time: 10:56a
//Created in $/Leap/Source/Library/StudentReport
//contain the class function to interact with database

?>