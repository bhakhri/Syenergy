
<?php 

////  This File checks  whether record exists in Country Form Table
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
 
define('MODULE','SubjectTypesMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();   
   
if(trim($REQUEST_DATA['subjectTypeId'] ) != '') {
    require_once(MODEL_PATH . "/SubjectTypeManager.inc.php");
    $foundArray = SubjectTypeManager::getInstance()->getSubjectType(' WHERE subjectTypeId="'.$REQUEST_DATA['subjectTypeId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}


//$History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/SubjectType
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SubjectType
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/30/08    Time: 4:46p
//Updated in $/Leap/Source/Library/SubjectType
//modiofied echo function
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:25p
//Created in $/Leap/Source/Library/SubjectType
//new files added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Country
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Country
//New Files Added in Country Folder

?>
