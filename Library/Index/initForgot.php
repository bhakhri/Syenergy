<?php
//--------------------------------------------------------  
// Purpose: This file contains conditions and checks for the user and send mail to appropriate user (T,S,P)
//
// Author:Parveen Sharma
// Created on : 15.12.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------      

$userCheckVariable='0';
$errorVariable='0';
$errorMessage = '';
$mail='0';
$mailAdd='';
if(isset($REQUEST_DATA['imgSubmit_x']) ) {
    
    if (!isset($REQUEST_DATA['username']) || trim($REQUEST_DATA['username']) == '') {
        $errorMessage = "Username cannot be left empty";
        logError('The value of $REQUEST_DATA["username"] in Index/init.php is empty');
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/LoginManager.inc.php");
        $loginManager = LoginManager::getInstance();
        $returnStatus = $loginManager->getEmail($REQUEST_DATA['username']);
        
        // checks if array contains an email ID            1
        if($returnStatus){
            require_once(BL_PATH ."/UtilityManager.inc.php");
            $verifyCode  = '';
            $verifyCode  = UtilityManager::generatePassword(mktime(0, 0, 0, 3, 0, 2000));
            $verifyCode .= UtilityManager::generatePassword('india');
            //$returnStatus[0]['$verifyCode']=md5($verifyCode);
            $returnStatus[0]['verifyCode']=$verifyCode;
            $urlLink = UI_HTTP_PATH."/verifyCode.php?CC=".$verifyCode;
            
            $body  = "Dear ".$returnStatus[0]['name'].", <br/><br/>";  
            $body .= EMAIL_VERIFICATION." <a href=$urlLink>$urlLink</a><br><br><br>";
            $body .= "Regards<br><br>".SITE_NAME."<br>".HTTP_PATH;


      /*    $body  = "Dear ".$returnStatus[0]['name'].", <br/><br/>";  
            $body .= "To retrieve your new password, please click the below link <br><br>";
            $body .= "<a href=$urlLink>$urlLink</a><br><br>OR<br><br>";
            $body .= "alternatively you could also copy the above link and paste it in the address bar of a new browser window.<br><br><br>";
            $body .= "Regards<br><br>Team<br>Customer Care, ChalkPad";
       */
            
            $subject="Verification Code ";
            $from = 'From: '.ADMIN_MSG_EMAIL. ";\r\n" ;    
            $from .= 'Content-type: text/html;'; 
            
          if($returnStatus[0]['roleId']=='2' || $returnStatus[0]['roleId']=='1'){
                if($returnStatus[0]['emailAddress']){
                    if(UtilityManager::sendMail($returnStatus[0]['emailAddress'],$subject,$body,$from)===true){
                        if($loginManager->verifcationPassword($returnStatus)){
                            $mail='1';
                            $mailAdd=$returnStatus[0]['emailAddress'];
                        }
                    }
                    else
                    {    
                        $errorVariable='1';
                    }
                }
                else {
                   $errorVariable='1';
                }
            }
            else if($returnStatus[0]['roleId']=='3'){
                    if($returnStatus[0]['fatherEmail']){
                        if(UtilityManager::sendMail($returnStatus[0]['fatherEmail'],$subject,$body,$from) === true){
                            if($loginManager->verifcationPassword($returnStatus)){
                                $mail='1';
                                $mailAdd=$returnStatus[0]['fatherEmail'];
                            }
                        }
                        else
                        {
                            $errorVariable='1';
                        }
                    }
                    else {
                        $errorVariable='1';
                    }
                    
                    if($returnStatus[0]['motherEmail']){
                        if(UtilityManager::sendMail($returnStatus[0]['motherEmail'],$subject,$body,$from)===true){
                            if($loginManager->verifcationPassword($returnStatus)){
                                $mail='1';
                                $mailAdd=$returnStatus[0]['motherEmail'];
                            }
                        }
                        else
                        {
                            $errorVariable='1';
                        }
                    }
                    else {
                        $errorVariable='1';
                    }

                    if($returnStatus[0]['guardianEmail']){
                        if(UtilityManager::sendMail($returnStatus[0]['guardianEmail'],$subject,$body,$from)===true){
                            if($loginManager->verifcationPassword($returnStatus)){
                                $mail='1';
                                $mailAdd=$returnStatus[0]['guardianEmail'];
                            }
                        }
                        else
                        {
                            $errorVariable='1';
                        }
                    }
                    else {
                        $errorVariable='1';
                    }
            }
            else if($returnStatus[0]['roleId']=='4'){
                if($returnStatus[0]['studentEmail']){
                        if(UtilityManager::sendMail($returnStatus[0]['studentEmail'],$subject,$body,$from)===true){
                            if($loginManager->verifcationPassword($returnStatus)){
                                $mail='1';
                                $mailAdd=$returnStatus[0]['studentEmail'];
                            }
                        }
                        else
                        {
                            $errorVariable='1';
                        }
                }
                else {
                    $errorVariable='1';
                }
            }
        } 
        else{
                    $userCheckVariable='1';
            }
    }    
    
}    
	
//$History: initForgot.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 7/03/09    Time: 4:20p
//Updated in $/LeapCC/Library/Index
//message updated (email send message)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/25/08   Time: 4:01p
//Updated in $/LeapCC/Library/Index
//spelling correct
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/25/08   Time: 3:54p
//Updated in $/LeapCC/Library/Index
//modify message
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/16/08   Time: 5:15p
//Updated in $/LeapCC/Library/Index
//admin checks
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/15/08   Time: 2:50p
//Updated in $/LeapCC/Library/Index
//message update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/15/08   Time: 2:33p
//Updated in $/LeapCC/Library/Index
//verification code update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Index
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 11/07/08   Time: 11:37a
//Updated in $/Leap/Source/Library/Index
//added ADMIN_MSG_EMAIL in $from variable so that it can be changed
//through common.inc
//
//*****************  Version 5  *****************
//User: Arvind       Date: 10/25/08   Time: 3:41p
//Updated in $/Leap/Source/Library/Index
//added mailAddress Parameter to stor send mail address
//
//*****************  Version 4  *****************
//User: Arvind       Date: 10/23/08   Time: 2:46p
//Updated in $/Leap/Source/Library/Index
//modified the checks
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/28/08    Time: 4:44p
//Updated in $/Leap/Source/Library/Index
//added alert for invalid username
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/24/08    Time: 2:38p
//Updated in $/Leap/Source/Library/Index
//added a condition for admin role
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/24/08    Time: 12:51p
//Created in $/Leap/Source/Library/Index
//initial checkin

?>