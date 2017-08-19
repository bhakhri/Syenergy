<?php

//--------------------------------------------------------  
// Purpose: This file contains conditions and checks for the verification code user and send mail to appropriate user (T,S,P)
//
// Author:Parveen Sharma
// Created on : 15.12.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------      

$userCheckVariable='0';
$errorVariable='0';
$errorMessage = '';
$mail='0';
$mailAdd='';
    
    if (!isset($REQUEST_DATA['CC']) || trim($REQUEST_DATA['CC']) == '') {
        $errorMessage = "Verification code cannot be left empty";
        logError('The value of verification code in Index/init.php is empty');
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/LoginManager.inc.php");
        $loginManager = LoginManager::getInstance();
        $returnStatus = $loginManager->getVerifyEmail($REQUEST_DATA['CC']);
        // checks if array contains an email ID
        if($returnStatus){
            require_once(BL_PATH ."/UtilityManager.inc.php");
            $newPassword=UtilityManager::generatePassword('india');
            $returnStatus[0]['newPassword']=md5($newPassword);
            
            $body="Hi, <br/><br/> Your Password has been successfully changed <br/><br/> Your new Password : $newPassword";
            $subject="New Password ";
            $from = 'From: '.ADMIN_MSG_EMAIL. ";\r\n" ;    
            $from .= 'Content-type: text/html;'; 
            
            if($returnStatus[0]['roleId']=='2' || $returnStatus[0]['roleId']=='1'){
                if($returnStatus[0]['emailAddress']){
                    if(UtilityManager::sendMail($returnStatus[0]['emailAddress'],$subject,$body,$from)===true){
                        if($loginManager->insertPassword($returnStatus)){
                            $mail='1';
                            $mailAdd=$returnStatus[0]['emailAddress'];
                        }
                    }
                    else
                    {    
                        $errorVariable='1';
                    }
                }
            }
            else if($returnStatus[0]['roleId']=='3'){
                    if($returnStatus[0]['fatherEmail']){
                        if(UtilityManager::sendMail($returnStatus[0]['fatherEmail'],$subject,$body,$from) === true){
                            if($loginManager->insertPassword($returnStatus)){
                                $mail='1';
                                $mailAdd=$returnStatus[0]['fatherEmail'];
                            }
                        }
                        else
                        {
                            $errorVariable='1';
                        }
                    }
                    if($returnStatus[0]['motherEmail']){
                        if(UtilityManager::sendMail($returnStatus[0]['motherEmail'],$subject,$body,$from)===true){
                            if($loginManager->insertPassword($returnStatus)){
                                $mail='1';
                                $mailAdd=$returnStatus[0]['motherEmail'];
                            }
                        }
                        else
                        {
                            $errorVariable='1';
                        }
                    }
                    if($returnStatus[0]['guardianEmail']){
                        if(UtilityManager::sendMail($returnStatus[0]['guardianEmail'],$subject,$body,$from)===true){
                            if($loginManager->insertPassword($returnStatus)){
                                $mail='1';
                                $mailAdd=$returnStatus[0]['guardianEmail'];
                            }
                        }
                        else
                        {
                            $errorVariable='1';
                        }
                    }
            }
            else if($returnStatus[0]['roleId']=='4'){
                if($returnStatus[0]['studentEmail']){
                        if(UtilityManager::sendMail($returnStatus[0]['studentEmail'],$subject,$body,$from)===true){
                            if($loginManager->insertPassword($returnStatus)){
                                $mail='1';
                                $mailAdd=$returnStatus[0]['studentEmail'];
                            }
                        }
                        else
                        {
                            $errorVariable='1';
                        }
                }
                
            }
            
        } 
        else{
                    $userCheckVariable='1';
            }
    }   
    
    
//$History: initVerifyForgot.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/15/08   Time: 2:33p
//Updated in $/LeapCC/Library/Index
//verification code update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/15/08   Time: 12:50p
//Created in $/LeapCC/Library/Index
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/15/08   Time: 12:23p
//Created in $/Leap/Source/Library/Index
//file added
//


?>