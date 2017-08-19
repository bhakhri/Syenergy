<?php
//It contains the time table
//
// Author :Diapnajn BHattacharjee
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
        
    $teacherManager = ScTeacherManager::getInstance();
    
    $teacherRecordArray = $teacherManager->getTeacherTimeTable();   

 //$History: scInitTimeTable.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>
