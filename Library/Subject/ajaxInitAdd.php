<?php
//  This File calls addFunction used in adding Subject Records
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
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    $errorMessage ='';
    if (!isset($REQUEST_DATA['subjectCode']) || trim($REQUEST_DATA['subjectCode']) == '') {
        $errorMessage .= ENTER_SUBJECT_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectName']) || trim($REQUEST_DATA['subjectName']) == '')) {
        $errorMessage .= ENTER_SUBJECT_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectTypeId']) || trim($REQUEST_DATA['subjectTypeId']) == '')) {
        $errorMessage .=  SELECT_SUBJECT_TYPE."\n";
    }
    

	if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/SubjectManager.inc.php");

		$foundArray = SubjectManager::getInstance()->getSubject(' AND UCASE(subjectCode)="'.add_slashes(strtoupper($REQUEST_DATA['subjectCode'])).'"');
		if(trim($foundArray[0]['subjectCode'])=='') {

			require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		   if(SystemDatabaseManager::getInstance()->startTransaction()) {

				$returnStatus = SubjectManager::getInstance()->addSubject();
				if($returnStatus == false) {
					echo FAILURE;
					die;
				}
				else { 
                    $courseTopic = "All Topic";
                    $courseTopic = add_slashes($courseTopic);
                    $subjectAbbr = "AT";
                    $subjectAbbr = add_slashes($subjectAbbr);   
                    $newSubjectId = SystemDatabaseManager::getInstance()->lastInsertId();
                    require_once(MODEL_PATH . "/SubjectTopicManager.inc.php");
                    $returnStatus = SubjectTopicManager::getInstance()->addSubjectTopicInTransaction($newSubjectId,$courseTopic, $subjectAbbr);
                    if($returnStatus == false) {
                        echo FAILURE;
                        die;
                    }
					/*if (isset($REQUEST_DATA['courseTopic']) and trim($REQUEST_DATA['courseTopic']) != '') {
						$courseTopic = trim($REQUEST_DATA['courseTopic']);
						$courseTopic = add_slashes($courseTopic);
						$subjectAbbr = trim($REQUEST_DATA['subjectAbbr']);
						$subjectAbbr = add_slashes($subjectAbbr);
					}*/
		       }  
				if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					echo SUCCESS
                    ;
                    
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

//$History: ajaxInitAdd.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:31p
//Updated in $/LeapCC/Library/Subject
//done changes for new Session End Activities
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Library/Subject
//search & conditions updated
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:46p
//Updated in $/LeapCC/Library/Subject
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/Subject
//duplicate values & Dependency checks, formatting & conditions updated
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/04/09    Time: 4:40p
//Updated in $/LeapCC/Library/Subject
//allowed special characters & subject abbr. blank
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Subject
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/09/08    Time: 7:24p
//Updated in $/Leap/Source/Library/Subject
//added common messages
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/05/08    Time: 6:49p
//Updated in $/Leap/Source/Library/Subject
//MODIFY
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/11/08    Time: 12:33p
//Updated in $/Leap/Source/Library/Subject
//modified the paramter in getsubject()
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:24p
//Created in $/Leap/Source/Library/Subject
//Added new files
?>

