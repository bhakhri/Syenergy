<?php
//-------------------------------------------------------
// THIS FILE IS USED TO send message to students by admin
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
/*
* ////////////////////////////////////////////////////////////////
* $argv[1]=UserId
* $argv[2]=InstituteId
* $argv[3]=SessionId
* $argv[4]=>StudentIds
* $argv[5]=>MessageMedium
* $argv[6]=>Message Body
* $argv[7]=>Message Subject
* $argv[8]=>ADMIN_EMAIL
* $argv[9]=>DB_HOST
* $argv[10]=>DB_USER
* $argv[11]=>DB_PWD
* $argv[12]=>DB_NAME
* $argv[13]=SMS_MAX_LENGTH
* $argv[14]=SMS_GATEWAY_USER_VARIABLE
* $argv[15]=SMS_GATEWAY_PASS_VARIABLE
* $argv[16]=SMS_GATEWAY_NUMBER_VARIABLE
* $argv[17]=SMS_GATEWAY_MESSAGE_VARIABLE
* $argv[18]=SMS_GATEWAY_SNDR_VARIABLE
* $argv[19]=SMS_GATEWAY_SNDR_VALUE
* $argv[20]=SMS_GATEWAY_USERNAME
* $argv[21]=SMS_GATEWAY_PASSWORD
* $argv[22]=SMS_GATEWAY_URL
* $argv[23]=NUMBER OF LOOPS BEFORE A SLEEP
* $argv[24]= AMOUNT OF SLEEP
* $argv[25]= FROM DATE
* $argv[26]= TO DATE
* /////////////////////////////////////////////////////////////////
*/

if($argc < 24 ){ //if number of arguments is less than 24
    exit;
}



$connection=mysql_connect($argv[9],$argv[10],$argv[11]);
$selDb=mysql_select_db($argv[12],$connection);

// SMS variables & max length detail



$userId      =$argv[1];
$instituteId =$argv[2];
$sessionId   =$argv[3];



//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 function sendSMS($mobileNo, $message) {
    // $mobileNo = '9878425461,9855094422';
         global $argv;
        $postVars = $argv[14].'='.$argv[20].'&'.$argv[15].'='.$argv[21].'&'.$argv[16].'='.$mobileNo.'&'.$argv[18].'='.$argv[19].'&'.$argv[17].'='.$message;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $argv[22]); //set the url
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
        curl_setopt($ch, CURLOPT_POST, 1); //set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars); //set the POST variables
        $response = curl_exec($ch); //run the whole process and return the response
        curl_close($ch); //close the curl handle
        if(preg_match("/failure/i",$response)) {
            logError('SMS Response: '.$response);
            return false;
        }
        else {
            return true;
        }
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR sending email
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendEmail($from,$to,$msgSubject,$msgBody){
     $headers = 'From: '.$from.' '. "\r\n" ;
     $headers .= 'Content-type: text/html;';
     //return UtilityManager::sendMail($to, $msgSubject, $msgBody, $headers);
     return @mail($to, $msgSubject, $msgBody, $headers);
}


//fetch student email/+mobile nos
function getStudentEmailMobileNoList($conditions){
   global $connection;
   $query="SELECT studentId,concat(firstName,' ' ,lastName) as studentName , studentEmail , studentMobileNo
            FROM student
            $conditions
            ";
    $result = mysql_query($query, $connection);
    $rows = Array();
    while ($row = mysql_fetch_assoc($result)) {
           $rows[] = $row;
    }
    return $rows;
}
//this function is used to fetch the studentId angaist studentMob No.
function getStudentId($mobNo){
		$query ="SELCT		studentId
				FROM		student
				WHERE		studentMobileNo IN ($mobNo)";

		$result= mysql_query($query, $connection);
}
//this function is uded to fetch the last messageId from admin_message
function getLastDataFrmAdminMessages(){
		$query ="SELECT	messageId FROM admin_messages ORDER BY messageId DESC LIMIT 1";
		$result= mysql_query($query, $connection);
	}
//this function is used to insert the detail's of msg which is not send to the student
function insertIntoAdminMsgsFailed($lastMsgId,$curDate,$smsNotSendStudentId){
		$query="INSERT INTO
						admin_messages_failed (messageId,receiverType,dated,receiverIds,messageSubject,message,messageType,senderId,instituteId,sessionId)
										VALUES('$lastMsgId','Student','$curDate','$smsNotSendStudentId','".htmlentities($argv[7])."','".htmlentities($argv[6])."','SMS','$userId','$instituteId','$sessionId')";
		$result= mysql_query($query, $connection);
}

//--------------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting sms/email records sent sms/email to students/+employees
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (19.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
 function adminMessageEmailSMSRecord($conditions='') {

	global $connection;
    $query="INSERT INTO admin_messages (receiverIds,receiverType,dated,subject,message,messageType,visibleFromDate,visibleToDate,senderId,instituteId,
    sessionId) VALUES
    $conditions ";
    $result= mysql_query($query, $connection);
 }

//-------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR cal culating no of sms based on sms max lengthsending SMS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee
// Created on : (21.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------
$smsArr=array();  //will contain smss(each of sms_max_length or less)
function smsCalculation($value,$limit){
 $temp1=$value;
 $nos=1;
 global $smsArr;
 $smsArr[0]=substr($value,0,$limit);
 while(strlen($temp1) > $limit){
     $temp1=substr($temp1,$limit);
     $smsArr[$nos]=substr($temp1,0,$limit);
     $nos=$nos+1;
 }
 return $nos;
}


$insQuery="";
//$curDate=date('Y')."-".date('m')."-".date('d');
$curDate=date('Y-m-d h:i:s');
$currentDate= date('Y-m-d');
$sms=0;$email=0;
$smsNotSentArray=array();
$errorMessage='';
$sl=0;
if (trim($errorMessage) == '') {
       $studentEmailMobilesArr=getStudentEmailMobileNoList(" WHERE studentId IN(".$argv[4].")");

       $cnt=count($studentEmailMobilesArr);
       $msgMedium=split("," , $argv[5]);
       if($msgMedium[0]==1){
        //calculate and prepare smses based on sms_max_length
        $smsNo=smsCalculation(strip_tags($argv[6]),$argv[13]);
       }
       $mCnt=count($msgMedium);
       //$errorMessage = SUCCESS;
       if($cnt > 0 and is_array($studentEmailMobilesArr)){
           global $sessionHandler;
           $smsInstituteId = $sessionHandler->getSessionVariable('InstituteId');
           if($smsInstituteId=='17') {
             $ret=sendSMS("9501119649",strip_tags($smsArr[0])); 
             $ret=sendSMS("9501119650",strip_tags($smsArr[0]));
           }
           if($sl==$argv[23]){
               $sl=0;
               sleep($argv[24]);
           }
           else{
               $sl++;
           }
           for($i=0; $i < $cnt ; $i++){
             $sms=0;$email=0;
             if($mCnt > 0 and is_array($msgMedium)){
               if($msgMedium[0]==1){ //if sms
                if(trim($studentEmailMobilesArr[$i]['studentMobileNo'])!==""){
                $ret=false;
                for($s=0 ; $s < $smsNo ;$s++){
                  $ret=sendSMS($studentEmailMobilesArr[$i]['studentMobileNo'],strip_tags($smsArr[$s]));
				   if($ret == false){
					 $smsNotSentArray[] = $studentEmailMobilesArr[$i]['studentMobileNo'];
				   }
                 }
                if($ret){$sms=1;}
                else{$sms=0;}
                $visibleFromDate=$curDate;$visibleToDate=$curDate;
               }
              else{
                 $sms=0;$visibleFromDate=$curDate;$visibleToDate=$curDate;
              }
             }

            if($msgMedium[1]==1){ //if email
               if(trim($studentEmailMobilesArr[$i]['studentEmail'])!==""){
               sendEmail($argv[8],$studentEmailMobilesArr[$i]['studentEmail'],$argv[7],"Dear ".ucwords($studentEmailMobilesArr[$i]['studentName']).",\r\n".$argv[6]);
               $email=1;$visibleFromDate=$curDate;$visibleToDate=$curDate;
              }
             else{
                 $email=0;$visibleFromDate=$curDate;$visibleToDate=$curDate;
              }
            }
        }
    }  //end of forloop
    //run the query
    $smsNotSent=implode(",",$smsNotSentArray);
    $receiverIds="~".str_replace(",","~",$argv[4])."~";
    //build the insert query
     if($msgMedium[1]==1){  //if email
      $insQuery="('".$receiverIds."','Student','".$curDate."','".htmlentities(($argv[7]))."','".htmlentities(($argv[6]))."','Email','".$currentDate."','".$currentDate."',$userId,$instituteId,$sessionId)";
     }

    if($msgMedium[2]==1){  //if dashboard
     if($insQuery==""){
      $insQuery="('".$receiverIds."','Student','".$curDate."','".htmlentities(($argv[7]))."','".htmlentities(($argv[6]))."','Dashboard','".$argv[25]."','".$argv[26]."',$userId,$instituteId,$sessionId)";
      }
     else{
         $insQuery =$insQuery." ,('".$receiverIds."','Student','".$curDate."','".htmlentities(($argv[7]))."','".htmlentities(($argv[6]))."','Dashboard','".$argv[25]."','".$argv[26]."',$userId,$instituteId,$sessionId)";
      }
    }

     if($msgMedium[0]==1){
     //for multiple smss(started from 1 to leave the common record)
     for($s=0 ; $s < $smsNo ;$s++){
        if($insQuery==""){
            $insQuery="('".$receiverIds."','Student','".$curDate."',' ','".htmlentities(($smsArr[$s]))."','SMS','".$currentDate."','".$currentDate."',$userId,$instituteId,$sessionId)";
         }
       else{
            $insQuery =$insQuery." ,('".$receiverIds."','Student','".$curDate."',' ','".htmlentities(($smsArr[$s]))."','SMS','".$currentDate."','".$currentDate."',$userId,$instituteId,$sessionId)";
         }
       }
     }
	 echo $insQuery;
    //echo   $insQuery;
    if($insQuery!=""){
       $returnStatus= adminMessageEmailSMSRecord($insQuery); //add the record in database
    }
    if($returnStatus === false) {
      $errorMessage = FAILURE;
    }
	$lastMsgIdArray=getLastDataFrmAdminMessages();
	if($lastMsgIdArray === false){
		$errorMessage = FAILURE;
    }
	$lastMsgId = $lastMsgIdArray[0][messageId];
	if($smsNotSent !=''){
		$smsNotSendStudentIdArray = getStudentId($smsNotSent);
		if($smsNotSendStudentIdArray === false){
			$errorMessage = FAILURE;
        }
	$smsNotSendStudentId=implode(",",$smsNotSendStudentIdArray);
	$result = insertIntoAdminMsgsFailed($lastMsgId,$curDate,$smsNotSendStudentId);
	if($result === false){
			$errorMessage = FAILURE;
        }
	}

  }
 echo $errorMessage;
}

//sleep(30);

// $History: bulkMessageProcess.php $
//
//*****************  Version 4  *****************
//User: Administrator Date: 11/06/09   Time: 15:18
//Updated in $/LeapCC/Library/AdminMessage
//Corrected sms sending process
//
//*****************  Version 3  *****************
//User: Administrator Date: 1/06/09    Time: 13:09
//Updated in $/LeapCC/Library/AdminMessage
//Corrected bugs------bug2_30-05-09.doc
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/LeapCC/Library/AdminMessage
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/03/08   Time: 5:17p
//Created in $/LeapCC/Library/AdminMessage
//Create Send Message to All
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/29/08   Time: 11:18a
//Created in $/Leap/Source/Library/ScAdminMessage
//Added BackGround Process Capability
?>