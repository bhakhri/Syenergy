<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE notice div
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (16.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

require_once(MODEL_PATH . "/RegistrationForm/MenteesManager.inc.php");
$teacherManager = MenteesManager::getInstance();

define('MODULE','COMMON');
define('ACCESS','view');

global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    
if(trim($REQUEST_DATA['mentorshipId'] ) != '') {
	$mentorshipId = trim($REQUEST_DATA['mentorshipId']);
	$mentorshipId = add_slashes($mentorshipId);
	
    $totalRecordArray = $teacherManager->getMentorshipDetail($mentorshipId);
    $mentorshipRecordArray = $teacherManager->getStudentMentorshipListDetail($mentorshipId);
    $cnt = count($mentorshipRecordArray);
    
   for($i=0;$i<$cnt;$i++) {

	   $actionStr='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteMentorshipComment('.$mentorshipRecordArray[$i]['mentorshipCommentId'].')"></a>';
   
        // add stateId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
                                   'comments' => strip_slashes($mentorshipRecordArray[$i]['comments']),
                                   'commentDate'=> UtilityManager::formatDate(strip_slashes($mentorshipRecordArray[$i]['commentDate'])),
                                   'employeeNameCode'=> strip_slashes($mentorshipRecordArray[$i]['employeeNameCode']),
								   'action1'=> $actionStr
                                  )
                                );

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
}
 echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecordArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: scAjaxGetNoticeDetails.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 22/09/09   Time: 17:42
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Fixed bug found during self testing
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/09/09    Time: 14:34
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Updated code to take care of html encoded values in notice and events
//module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>