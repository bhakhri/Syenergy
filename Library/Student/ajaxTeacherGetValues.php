
<?php 

////  Get the data from teacher table 
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentTeacherComments');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['commentId']) != '') {
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $foundArray = StudentInformationManager::getInstance()->getCommentsListing('AND tc.commentId="'.$REQUEST_DATA['commentId'].'"');
		
    if(is_array($foundArray) && count($foundArray)>0 ) {  
		$foundArray[0][comments]=html_entity_decode(strip_slashes($foundArray[0][comments]));
		echo json_encode($foundArray[0]);
		
    }
    else {
        echo 0;
    }
}


//$History: ajaxTeacherGetValues.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/21/08   Time: 6:13p
//Updated in $/Leap/Source/Library/Student
//modified
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/04/08    Time: 11:07a
//Created in $/Leap/Source/Library/Student
//ajax function to show student comments
//
?>
