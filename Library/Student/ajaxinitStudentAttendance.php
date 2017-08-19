<?php

//The file contains data base functions work on dashboard
//
// Author :Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    
    require_once($FE . "/Library/common.inc.php"); //for studentId 
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    
    global $sessionHandler;
    
    $fromDate = $REQUEST_DATA['startDate'];
    $toDate = $REQUEST_DATA['toDate'];
    $studentId= $sessionHandler->getSessionVariable('StudentId'); 
    
    if($fromDate)
  $where = "WHERE s.studentId = $studentId AND fromDate BETWEEN '$fromDate' AND '$toDate'";
 
 if($toDate)
  $where .= " AND toDate BETWEEN '$fromDate' AND '$toDate'";
    
    //if ($REQUEST_DATA['startDate'] !== "" && $REQUEST_DATA['toDate'] !== ""){
        //$studentId= $sessionHandler->getSessionVariable('StudentId');
        //$studentInformationArray = CommonQueryManager::getInstance()->getAttendance('','GROUP BY att.subjectId, att.studentId',$studentId);
    //}
    //else {
    
    
    
	    
        $studentInformationArray = CommonQueryManager::getInstance()->getAttendance( $where);
        
       
       $cnt = count($studentInformationArray);
    
    
    for($i=0;$i<$cnt;$i++) {
		$studentInformationArray[$i]['date']=date('d-M-Y',strtotime(strip_slashes($studentInformationArray[$i]['toDate'])));
		$studentInformationArray[$i]['Percentage']=number_format((($studentInformationArray[$i]['attended'] /  $studentInformationArray[$i]['delivered'])*100),2,'.','');
		// add subjectId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentInformationArray[$i]);
        
         if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    //print_r($valueArray);
   echo '{"info" : ['.$json_val.']}'; 
	
          
 
?>

<?php 

//$History: ajaxinitStudentAttendance.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:29p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/13/08    Time: 12:02p
//Updated in $/Leap/Source/Library/Student
//modify for dates
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/14/08    Time: 3:52p
//Updated in $/Leap/Source/Library/Student
//modified for print
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/31/08    Time: 7:50p
//Updated in $/Leap/Source/Library/Student
//modification for getAttendace function
//
//*****************  Version 1  *****************
//User: Administrator Date: 7/28/08    Time: 7:13p
//Created in $/Leap/Source/Library/Student
//to give ajax functions to give the list of student attendance
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 1:05p
//Created in $/Leap/Source/Library/Student
//contain the data base function through data base query
//

?>