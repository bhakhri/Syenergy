<?php
//  This File checks  whether record exists in Subject Form Table
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Subject');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
        
if(trim($REQUEST_DATA['subjectId']) != '') {
    require_once(MODEL_PATH . "/SubjectManager.inc.php");
    $foundArray = SubjectManager::getInstance()->getSubject(' AND s.subjectId="'.$REQUEST_DATA['subjectId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
//$History: ajaxGetValues.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:31p
//Updated in $/LeapCC/Library/Subject
//done changes for new Session End Activities
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/11/09    Time: 2:30p
//Updated in $/LeapCC/Library/Subject
// issue fix 1012, 1011
//validation updated
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:46p
//Updated in $/LeapCC/Library/Subject
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/Subject
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Subject
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/09/08    Time: 6:37p
//Updated in $/Leap/Source/Library/Subject
//modified the filter
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/30/08    Time: 4:44p
//Updated in $/Leap/Source/Library/Subject
//modified echo function
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:24p
//Created in $/Leap/Source/Library/Subject
//Added new files
?>

