<?php

//  This File calls Edit Function used in adding Notice Records
//
// Author :Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	function sendSMS($mobileNo,$message){
		return (UtilityManager::sendSMS($mobileNo, $message));
	}

    $errorMessage ='';
	if (!isset($REQUEST_DATA['noticeSubject']) || trim($REQUEST_DATA['noticeSubject']) == '') {
        $errorMessage .= ENTER_NOTICE_SUBJECT."\n";
    }
   if (!isset($REQUEST_DATA['noticeText']) || trim($REQUEST_DATA['noticeText']) == '') {
        $errorMessage .= ENTER_NOTICE_TEXT."\n";
    }
	if (!isset($REQUEST_DATA['visibleFromDate']) || trim($REQUEST_DATA['visibleFromDate']) == '') {
        $errorMessage .= EMPTY_FROM_DATE."\n";
    }
	if (!isset($REQUEST_DATA['visibleToDate']) || trim($REQUEST_DATA['visibleToDate']) == '') {
        $errorMessage .= EMPTY_TO_DATE."\n";
    }
	if (!isset($REQUEST_DATA['universityId']) || trim($REQUEST_DATA['universityId']) == '') {
        $errorMessage .= SELECT_UNIVERSITY."\n";
    }
	if (!isset($REQUEST_DATA['degreeId']) || trim($REQUEST_DATA['degreeId']) == '') {
        $errorMessage .= SELECT_DEGREE."\n";
    }
	//if (!isset($REQUEST_DATA['branchId']) || trim($REQUEST_DATA['branchId']) == '') {
       // $errorMessage .= SELECT_BRANCH."\n";
    //}

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/NoticeManager.inc.php");
		$noticeManager = NoticeManager::getInstance();
 /*       $foundArray = NoticeManager::getInstance()->getNotice(' WHERE noticeId!='.$REQUEST_DATA['noticeId']);
        if(trim($foundArray[0]['countryCode'])=='') {  //DUPLICATE CHECK
 */           $returnStatus = $noticeManager->editNotice($REQUEST_DATA['noticeId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                //Id to upload logo
                $sessionHandler->setSessionVariable('IdToFileUpload',$REQUEST_DATA['noticeId']);
                $sessionHandler->setSessionVariable('OperationMode',2);
                //Stores file upload info
                $sessionHandler->setSessionVariable('HiddenFile',$REQUEST_DATA['hiddenFile']);
                echo SUCCESS;
            }

	if($REQUEST_DATA['smsStatus'] == 1) {
		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		$systemDatabaseManage=SystemDatabaseManager::getInstance();
		$lastInsertNotice = $systemDatabaseManage->lastInsertId();
		$roleIdsArray = $noticeManager->getRoleIds($lastInsertNotice);
		$roleIdList =UtilityManager::makeCSList($roleIdsArray,'roleId');
		$insituteIdList = UtilityManager::makeCSList($roleIdsArray,'instituteId');
		$getUserIdsArray = $noticeManager->getUserIds($roleIdList,$insituteIdList);
		$userIdList = UtilityManager::makeCSList($getUserIdsArray ,'userId');
		$mobileNoArray = $noticeManager->getMobileNumber($userIdList);
		$cnt = count($mobileNoArray);


		if($cnt > 0 and is_array($mobileNoArray)){
           copyHODSendSMS($REQUEST_DATA['smsText']);
 
           for($i=0; $i < $cnt ; $i++){
                if(trim($mobileNoArray[$i]['mobileNumber'])!="" and trim($mobileNoArray[$i]['mobileNumber'])!='NA' and strlen(trim($mobileNoArray[$i]['mobileNumber']))>=1){
                  $ret=sendSMS($mobileNoArray[$i]['mobileNumber'],strip_tags(trim($REQUEST_DATA['smsText'])));
                if($ret){
					$sms=1;
				}
                else{
					$sms=0;$smsNotSentArray[]=$mobileNoArray[$i]['mobileNumber'];
				}
               }
              else{
                 $smsNotSentArray[]=$mobileNoArray[$i]['mobileNumber'];// this array contain all the mobile numbers which do not have receve sms this array can be used for making sms report in future
              }
           }
		}

   }
        }
/*        else {
            echo 'The country code you entered already exists.';
        }
    }
*/   else {
        echo $errorMessage;
    }
//$History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/02/09    Time: 2:14p
//Updated in $/LeapCC/Library/Notice
//file attachment validation format updated
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Notice
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/Notice
//Define Module, Access  Added
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 11/03/08   Time: 11:50a
//Updated in $/Leap/Source/Library/Notice
//Added "MANAGEMENT_ACCESS" variable as these files are used in admin as
//well as in management role
//
//*****************  Version 3  *****************
//User: Arvind       Date: 10/03/08   Time: 12:50p
//Updated in $/Leap/Source/Library/Notice
//added notice download option
//
//*****************  Version 2  *****************
//User: Arvind       Date: 9/09/08    Time: 7:20p
//Updated in $/Leap/Source/Library/Notice
//added common messages
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/07/08    Time: 4:51p
//Created in $/Leap/Source/Library/Notice
//Added a new module   "Notice" files

?>


