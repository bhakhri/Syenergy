<?php
//  This File checks  whether record exists in Subject Form Table
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Subject');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 

require_once(MODEL_PATH . "/SubjectManager.inc.php");

    $errorMessage ='';
    if (!isset($REQUEST_DATA['subjectCode']) || trim($REQUEST_DATA['subjectCode']) == '') {
        $errorMessage .= ENTER_SUBJECT_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectName']) || trim($REQUEST_DATA['subjectName']) == '')) {
        $errorMessage .= ENTER_SUBJECT_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectTypeId']) || trim($REQUEST_DATA['subjectTypeId']) == '')) {
        $errorMessage .= 'Select Subject Type <br/>';
    }
    
    $subjectId = $REQUEST_DATA['subjectId'];
    if($subjectId=='') {
      $subjectId=0;  
    }

if (trim($errorMessage) == '') {

    $conditions  = ' FROM  '.TIME_TABLE_TABLE.'  tt, `subject` ss WHERE tt.toDate IS NULL AND tt.subjectId=ss.subjectId AND tt.subjectId='.$subjectId;
    $conditions .= ' AND ss.subjectTypeId != '.$REQUEST_DATA['subjectTypeId'];
    $foundArray = SubjectManager::getInstance()->getCheckSubject($conditions);
    if($foundArray[0]['cnt']>0) {
      echo "You cannot edit the field value of 'Subject Type' because record is associated to Time Table"; 
      die;
    }  
    	
    $foundArray = SubjectManager::getInstance()->getSubject(' AND UCASE(subjectCode)="'.add_slashes(strtoupper($REQUEST_DATA['subjectCode'])).'" AND s.subjectId!='.$REQUEST_DATA['subjectId']);
	if(trim($foundArray[0]['subjectCode'])=='') {

		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {

			$returnStatus = SubjectManager::getInstance()->editSubject($REQUEST_DATA['subjectId']);
			if($returnStatus == false) {
				echo FAILURE;
				die;
			}
			else {
				//delete old subject topic, add new subject topic.
				$subjectId = $REQUEST_DATA['subjectId'];
				require_once(MODEL_PATH . "/SubjectTopicManager.inc.php");
				$returnStatus = SubjectTopicManager::getInstance()->deleteSubjectTopicInTransaction($subjectId);

				$courseTopic = trim($REQUEST_DATA['courseTopic']);
				$courseTopic = add_slashes($courseTopic);
				$subjectAbbr = trim($REQUEST_DATA['subjectAbbr']);
				$subjectAbbr = add_slashes($subjectAbbr);

				$returnStatus = SubjectTopicManager::getInstance()->addSubjectTopicInTransaction($subjectId, $courseTopic, $subjectAbbr);
				if($returnStatus == false) {
					echo FAILURE;
					die;
				}
			}
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo SUCCESS;
			}
			else {
				echo FAILURE;
			}
		}
		else {
			echo FAILURE;
		}

	}
	else {
		echo SUBJECT_CODE_ALREADY_EXISTS;
	}
}
else {
	echo $errorMessage;
}



//$History: ajaxInitEdit.php $
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:31p
//Updated in $/LeapCC/Library/Subject
//done changes for new Session End Activities
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Library/Subject
//search & conditions updated
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:46p
//Updated in $/LeapCC/Library/Subject
//Gurkeerat: updated access defines
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/Subject
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/04/09    Time: 4:40p
//Updated in $/LeapCC/Library/Subject
//allowed special characters & subject abbr. blank
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/07/09    Time: 2:40p
//Updated in $/LeapCC/Library/Subject
//issue fix subject code space allow, sorting format setting
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Subject
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/09/08    Time: 7:24p
//Updated in $/Leap/Source/Library/Subject
//added common messages
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/11/08    Time: 1:26p
//Updated in $/Leap/Source/Library/Subject
//modified the paramter in getsubejct
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:24p
//Created in $/Leap/Source/Library/Subject
//Added new files
?>

