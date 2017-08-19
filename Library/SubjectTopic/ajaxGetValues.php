<?php
//-------------------------------------------------------
// Purpose: To get values of subject topic from the database
//
// Author : Parveen Sharma
// Created on : 15-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
$mod = $REQUEST_DATA['mod'];
define('MODULE',$mod);
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['subjectTopicId'] ) != '') {
    require_once(MODEL_PATH . "/SubjectTopicManager.inc.php");
    $foundArray = SubjectTopicManager::getInstance()->getSubjectTopic(' AND subjectTopicId="'.$REQUEST_DATA['subjectTopicId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0; // no record found
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/30/09    Time: 4:15p
//Updated in $/LeapCC/Library/SubjectTopic
//updated role permission
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Library/SubjectTopic
//added define variable for Role Permission
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/16/09    Time: 2:16p
//Created in $/LeapCC/Library/SubjectTopic
//subject topic file added
//

?>