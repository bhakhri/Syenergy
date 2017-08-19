<?php
//--------------------------------------------------------  
//It contains the time table according findout subjects
//
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    $classId = trim($REQUEST_DATA['classId']);
    
    // Subject
    $condition = 0;
	if($classId != 'all' ) {   
		if($classId != '' ) {   
			$condition = $classId;
		}
		else {
		$condition = '';
		}
	}
				
    $subjectArray = $studentReportsManager->getSubjectList($condition);
    
    // Group
    $condition = "";
	//WHEN ALL CLASS SELECT FROM DROP DOWN THEN NO GROUP TO DISPLAY
	if($classId == 'all' ){
	
		 $condition = " AND sg.classId IN (0)";
	
	}
	else if($classId != '') {  
        $condition = " AND sg.classId = ".$classId;
    }
	
    if($subjectId!='' && $subjectId!='all') {     
      $condition .= " AND sc.subjectId = ".$subjectId;  
    }
    
    $groupArray = $studentReportsManager->getSubjectwiseGroups($condition);     
    
		echo json_encode($subjectArray).'!~!~!'.json_encode($groupArray);
	
?>
<?php
//$History: ajaxGetSubject.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/14/09   Time: 3:25p
//Updated in $/LeapCC/Library/StudentReports
//class base format updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/26/09    Time: 11:57a
//Created in $/LeapCC/Library/StudentReports
//file added
//

?>