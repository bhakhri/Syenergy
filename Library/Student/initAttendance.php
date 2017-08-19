<?php

//The file contains data base functions work on dashboard
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    require_once($FE . "/Library/common.inc.php"); //for studentId 
    
    global $sessionHandler;
    
        
    //if ($REQUEST_DATA['startDate']=="" && $REQUEST_DATA['toDate']==""){
        $studentId= $sessionHandler->getSessionVariable('StudentId');
		$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
		$classId = $classIdArray[count($classIdArray)-1]['classId'];
        //$studentInformationArray = CommonQueryManager::getInstance()->getAttendance("WHERE att.studentId=$studentId");
    //}
    //else {
	  //  $studentId= $sessionHandler->getSessionVariable('StudentId');
       // $studentInformationArray = CommonQueryManager::getInstance()->getAttendance(' AND att.fromDate BETWEEN '.$REQUEST_DATA['startDate'].' AND att.toDate BETWEEN '.$REQUEST_DATA['toDate'],'',$studentId);
   // }
	
          
 
?>

<?php 

//$History: initAttendance.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:31p
//Updated in $/LeapCC/Library/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:33p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/31/08    Time: 7:50p
//Updated in $/Leap/Source/Library/Student
//modification for getAttendace function
//
//*****************  Version 2  *****************
//User: Administrator Date: 7/28/08    Time: 7:08p
//Updated in $/Leap/Source/Library/Student
//modified for show data by specified dates
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 1:05p
//Created in $/Leap/Source/Library/Student
//contain the data base function through data base query
//

?>