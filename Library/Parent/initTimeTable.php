<?php

//It contains the time table
//
// Author :Jaineesh
// Created on : 22-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    

    $studentId= $sessionHandler->getSessionVariable('StudentId');

    $classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
    $classId = $classIdArray[count($classIdArray)-1]['classId'];
        
?>

<?php 

//$History: initTimeTable.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/24/08   Time: 1:23p
//Updated in $/LeapCC/Library/Parent
//issue fix
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/25/08    Time: 6:04p
//Created in $/Leap/Source/Library/Parent
//initial checkin
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:41p
//Created in $/Leap/Source/Library/Student
//contain the data base function to run the queries
//
//


?>
