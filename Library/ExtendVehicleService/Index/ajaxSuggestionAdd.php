<?php
/*
  This File calls addFunction used in adding Bank Branch Records

 Author :Rajeev Aggarwal
 Created on : 09-02-2009
 Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.

--------------------------------------------------------
*/
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

	UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['suggestionSubject']) || trim($REQUEST_DATA['suggestionSubject']) == '')) {
        $errorMessage .= SELECT_SUGGESTION_SUBJECT;
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['suggestionText']) || trim($REQUEST_DATA['suggestionText']) == '')) {
        $errorMessage .= SELECT_SUGGESTION_TEXT;
    }
    if (trim($errorMessage) == '') {
        
		require_once(MODEL_PATH . "/DashBoardManager.inc.php");

		$emailArr = DashBoardManager::getInstance()->getEmailAddress();
		$emailAddress = split("~", $emailArr[0]['email']);
		if($emailAddress[0]){
            if(trim($emailAddress[0])=="-" or trim($emailAddress[0])==""){
                $emailAddress[0]=ADMIN_MSG_EMAIL;
            }
            if(trim($emailAddress[1])==""){
                $emailAddress[1]=$sessionHandler->getSessionVariable('UserName');
            }
			$todayDate = date('Y-m-d');
			$todayDate = UtilityManager::formatDate($todayDate);
			$suggestionText = $REQUEST_DATA['suggestionText'];
			$suggestionText = str_replace("\n", "<br>", $suggestionText);
			$body .= '
					<html>
					<head>
					  <title>Suggestion Box</title>
					  <style>
						.txt{
							font-family:verdana;
							font-size: 11px;
						}

					  </style>
					</head>
					<body>
					  <table class="txt" cellspacing="1" bgcolor="#ECECEC">
					  <tr bgcolor="#FFFFFF">
						<td colspan="2" ><img src="'.IMG_HTTP_PATH.'/logo_cp.gif"></td>
					  </tr>
					  <tr bgcolor="#FFFFFF">
						<td colspan="2"><B>Dear Administrator,</B> <br><br>You Have received a new suggestion</td>
					  </tr>
						<tr bgcolor="#FFFFFF">
						  <td class="txt" bgcolor="#FFFFFF"><b>Subject</b></td><td>'.$suggestionArr[$REQUEST_DATA['suggestionSubject']].'</td>
						</tr>
						<tr bgcolor="#FFFFFF">
						  <td bgcolor="#FFFFFF"><b>Sent On</b></td><td>'.$todayDate.'</td>
						</tr>
						<tr bgcolor="#FFFFFF">
						  <td valign="top" bgcolor="#FFFFFF"><b>Comments</b></td><td valign="top">'.$suggestionText.'</td>
						</tr>
						<tr bgcolor="#FFFFFF">
						<td colspan="2" bgcolor="#FFFFFF"><B>Regards</B> <br><br>'.$emailAddress[1].'</td>
					  </tr>
					  </table>
					</body>
					</html>
					';

			 
             
            $emailFrom = $emailAddress[0];
			if($emailFrom=='-')
				$emailFrom = "admin.chalkpad.com";
			$subject="New Suggestion";
			$from = 'From: '.$emailFrom. ";\r\n" ;    
     		$from .= 'Content-type: text/html;'; 
			
			if(UtilityManager::sendMail("kabir@chalkpad.in",$subject,$body,$from)===true){
				
				//echo "sent";

			}
			else{
			
				//echo "not sent";
			}
		}

		$returnStatus = DashBoardManager::getInstance()->addSuggestion();
		if($returnStatus === false) {
			$errorMessage = FAILURE;
		}
		else {
			echo SUCCESS;           
		}
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxSuggestionAdd.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:44a
//Created in $/LeapCC/Library/Index
//Intial checkin
?>