<?php
//It contains the time table
//
// Author :Diapnajn BHattacharjee
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
        
    $teacherManager = TeacherManager::getInstance();
    
    $teacherRecordArray = $teacherManager->getTeacherTimeTable();   

 //$History: initTimeTable.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:26p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>
