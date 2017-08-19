<?php
//--------------------------------------------------------  
//It contains the time table 
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayTimeTableReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['timeTableLabelId'] ) != '') {   
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    
    
    // Query Format
    
        // classwise
        $filter= "DISTINCT  cl.classId, SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) AS className  ";
        $condition = ' AND tt.timeTableLabelId='.trim($REQUEST_DATA['timeTableLabelId']);
        $orderBy = ' ORDER BY className';
        $classArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
    
    
        // Subjectwise
        $filter= "DISTINCT  sub.subjectId, sub.subjectCode AS subjectName ";
        $condition = ' AND tt.timeTableLabelId='.trim($REQUEST_DATA['timeTableLabelId']);
        $orderBy = ' ORDER BY sub.subjectCode';
        $subjectArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
        
        // Employeewise
        $filter= "DISTINCT  emp.employeeId, emp.employeeName ";
        $condition = ' AND tt.timeTableLabelId='.trim($REQUEST_DATA['timeTableLabelId']);  
        $orderBy = ' ORDER BY  emp.employeeName';
        $employeeArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
        
        // Roomwise
        $filter= "DISTINCT  r.roomId, r.roomName AS roomName1, 
                   concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) AS roomName ";
        $condition = ' AND tt.timeTableLabelId='.trim($REQUEST_DATA['timeTableLabelId']);  
        $orderBy = ' ORDER BY  r.blockId, r.roomName';
        $roomArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
        
        // Groupwise
        $filter= "DISTINCT  gr.groupId, gr.groupShort ";
        $condition = ' AND tt.timeTableLabelId='.trim($REQUEST_DATA['timeTableLabelId']);  
        $orderBy = ' ORDER BY  gr.groupShort';
        $groupArray = TimeTableManager::getInstance()->getTimeTableData($filter,$condition,$orderBy);
        
        echo json_encode($classArray).'~~'.json_encode($subjectArray).'~~'.json_encode($employeeArray).'~~'.json_encode($roomArray).'~~'.json_encode($groupArray);
}
?>
<?php
//$History: ajaxGetTimeTableDetails.php $
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 10-04-10   Time: 4:47p
//Updated in $/LeapCC/Library/TimeTable
//added multiple utility time table in management login 
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 4/05/10    Time: 6:38p
//Updated in $/LeapCC/Library/TimeTable
//bug fixing. FCNS No.1524
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/22/10    Time: 3:31p
//Updated in $/LeapCC/Library/TimeTable
//abbreviation updated roomwise function
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/22/10    Time: 3:12p
//Updated in $/LeapCC/Library/TimeTable
//Roomwise function updated (blockName show)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/19/09    Time: 4:14p
//Updated in $/LeapCC/Library/TimeTable
//dayswise, classwise  time table show & Print & CSV file checks
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/18/09    Time: 5:35p
//Updated in $/LeapCC/Library/TimeTable
//classwise time table show
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/15/09    Time: 4:43p
//Updated in $/LeapCC/Library/TimeTable
//group array argument updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/15/09    Time: 3:53p
//Created in $/LeapCC/Library/TimeTable
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/06/09    Time: 2:22p
//Updated in $/Leap/Source/Library/ScTimeTable
//function change
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/04/09    Time: 7:09p
//Created in $/Leap/Source/Library/ScTimeTable
//file added
//

?>