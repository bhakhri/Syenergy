<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE salary head
//
// Author : Rajeev Aggarwal
// Created on : (24.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//define('MODULE','LeaveTypeMaster');
//define('ACCESS','view');
//UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
global $suggestionArr;
if(trim($REQUEST_DATA['suggestionId'] ) != '') {

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
	DashBoardManager::getInstance()->readSuggestion($REQUEST_DATA['suggestionId']);

    $foundArray = DashBoardManager::getInstance()->getSuggestionDetail(" AND suggestionId =".$REQUEST_DATA['suggestionId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  

		$foundArray[0]['suggestionOn']=UtilityManager::formatDate($foundArray[0]['suggestionOn']);
		$foundArray[0]['suggestionSubjectId']=$suggestionArr[$foundArray[0]['suggestionSubjectId']];
		$foundArray[0]['suggestionText']=nl2br($foundArray[0]['suggestionText']);
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetSuggestionValues.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:44a
//Created in $/LeapCC/Library/Index
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/02/09    Time: 1:02p
//Created in $/SnS/Library/Index
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/11/09    Time: 2:54p
//Created in $/Leap/Source/Library/ScIndex
//Intial checkin
?>