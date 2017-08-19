<?php 

//This file is used for blocking student
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BlockStudent');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BlockStudentManager.inc.php");
    $blockStudentManager =BlockStudentManager::getInstance();
    
    require_once(BL_PATH.'/HtmlFunctions.inc.php');   
    $htmlFunctions =HtmlFunctions::getInstance();  

    global $sessionHandler;

        $dt = date('Y-m-d');
        $message = htmlentities(add_slashes($REQUEST_DATA['blkmessage']));
        $userId = $sessionHandler->getSessionVariable('UserId');


        if(trim($REQUEST_DATA['rollNo']) == '') {
           echo "Enter Roll No.";
        }
        if(trim($REQUEST_DATA['blkmessage']) == '') {
           echo "ENTER_MESSAGE_TEXT";
        }


        $rollNo=($REQUEST_DATA['rollNo']);
        $rollNoArray=explode(",",$rollNo);
        
        
        $stdName="";
        $str="";    
        $valueArray=array();  
        $j=0;      
        for($i=0;$i<count($rollNoArray);$i++){
	      if(trim($rollNoArray[$i])!='') {  
             // find student exist
             $condition = " WHERE rollNo IN ('".trim($rollNoArray[$i])."')";
             $foundArray = $blockStudentManager->getStudent($condition);
	         if(count($foundArray)>0){
               $studentId = $foundArray[0]['studentId']; 
               $fatherEmail = $foundArray[0]['fatherEmail']; 
               $studentEmail = $foundArray[0]['studentEmail']; 
               $studentName = $foundArray[0]['studentName']; 
               $fatherName = $foundArray[0]['fatherName']; 
               if($str!='') {
                 $str .=",";
               }
               $str .= "($studentId,1,'".$message."',".$userId.",'".$dt."')";
               $valueArray[$j]['fatherEmail']= $fatherEmail;
               $valueArray[$j]['studentEmail']= $studentEmail;
               $valueArray[$j]['studentName']= $studentName;
               $valueArray[$j]['fatherName']= $fatherName;
               $j++;
	         }
	         else{
               if($stdName!='') {
                  $stdName .=",";
               }   
	           $stdName.="$rollNoArray[$i]";	
	         }
           }
        }
        
	    if($stdName!=""){
  	      echo "Invalid RollNo. ".$stdName; 
          die();
	    }


       if($str!='') { 
	      //****************************************************************************************************************    
          //***********************************************START TRANSCATION************************************************
          //****************************************************************************************************************
          if(SystemDatabaseManager::getInstance()->startTransaction()) {
	         $returnStatus = $blockStudentManager->addStudent($str);
	         if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	           if($returnStatus === false) {
		          echo FAILURE;
		       }
		       else {
		         if($REQUEST_DATA['mail_check']==1){
                    $msg=$REQUEST_DATA['blkmessage'];
                    
                    $parentMsgBody="This is to inform you that your ward chalkpad is blocked due to $msg \n\n\n. Regards \n Admin.";
                    $studentMsgBody="This is to inform you that your chalkpad is blocked due to $msg \n\n\n. Regards \n Admin.";
                    
                    $msgSubject="Chalkpad Blocked";
                    $headers = 'From: webmaster@chalkpad.com' . "\r\n" ;    
                    $headers = 'From: '.$from.' '. "\r\n" ;    
                    $headers .= 'Content-type: text/html;';  
                    for($i=0;$i<count($valueArray);$i++) {
                        // Findout Parent Mail Ids
                         $fatherEmail = $valueArray[$i]['fatherEmail']; 
                         $studentEmail = $valueArray[$i]['studentEmail']; 
                         $studentName = $valueArray[$i]['studentName']; 
                         $fatherName = $valueArray[$i]['fatherName']; 
                         
                         if(trim($fatherEmail)!="" and $htmlFunctions->isEmail(trim($fatherEmail))!= 0 ){   
                           $to=$fatherEmail;
                           UtilityManager::sendMail($to, $parentMsgBody, $msgBody, $headers);
                         }
	  	                 
                         if(trim($studentEmail)!="" and $htmlFunctions->isEmail(trim($studentEmail))!= 0 ){   
                           $to=$studentEmail;
	  	                   UtilityManager::sendMail($to, $studentMsgBody, $msgBody, $headers);
                         }
	  	             } // Email Loop End
	  	          } // Mail Check End
		          echo SUCCESS;           
		       }
	         }
	      } 
	      //****************************************************************************************************************    
          //***********************************************END TRANSCATION************************************************
          //****************************************************************************************************************
       }
       else {
          echo "Data Not Found";
       } 	
?>
