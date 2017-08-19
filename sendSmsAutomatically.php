<?php
    // set_time_limit(0); 
    set_time_limit(0); 
    $FE = dirname(__FILE__);
    
    $serviceProvider = 'CHALKPAD';
    
    $url = $FE.'/Library/common.inc.php';
    require_once("$url");    
    
    $conn =mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect:' . mysql_error());
    mysql_select_db(DB_NAME,$conn) or die(mysql_error());
    
  
    //Sole purpose of this variable is testing .On local its value will be '0' and on live its value will be'1'. 
    $testingSms=1;
    

    $flagCheck=0;
     
    $query = "SELECT COUNT(*) AS cnt FROM `periodic_attendance_alerts` ";
    $queryForCheckDate=mysql_query($query);
    if($rowDateCheck=mysql_fetch_array($queryForCheckDate)) {
      if($rowDateCheck['cnt']==0) {
	     $current=date('Y-m-d');
         $sendLastAlertDate= "INSERT INTO `periodic_attendance_alerts` (alertDate) VALUES ('$current') ";
	     mysql_query($sendLastAlertDate);
      } 
    }
    
    
    $queryForLastAlert="SELECT alertDate from `periodic_attendance_alerts` ORDER BY alertId DESC LIMIT 0,1"; 
    $queryForLastAlertResult=mysql_query($queryForLastAlert);
    if($rowAlertDate=mysql_fetch_array($queryForLastAlertResult)){
      $lastDate=$rowAlertDate['alertDate'];
    }
    
    //Fetching selected classes from config
    $query = "SELECT  
		            DISTINCT `value`, instituteId  
		      FROM 
		            config 
		      WHERE 
		 	        param='SMS_CLASSES' AND IFNULL(`value`,'') != '' ";
    $queryForSmsClasses=mysql_query($query);
    
    
        
    while($row=mysql_fetch_array($queryForSmsClasses)) { 
	         //Fetching selected student 
	        $gettinId="SELECT 
			          s.studentId,s.classId,IFNULL(s.fatherMobileNo,'') AS fatherMobileNo, rollNo,
			          s.fatherName, c.instituteId 
			    FROM 
			          student s, class c 
			    WHERE 
			          c.classId = s.classId AND	
			          s.classId IN (".$row['value'].") AND IFNULL(s.fatherMobileNo,'') <> '' ";
	       $resulId=mysql_query($gettinId);
        
           
	       //Fetching students Details For Selected classes
	       while($rowId=mysql_fetch_array($resulId)) {
		       
               // Fetch Student Detail
               $studentId=$rowId['studentId'];
               $classId=$rowId['classId'];
               $rollNo=$rowId['rollNo'];  
               $instituteId = $rowId['instituteId'];    
               $tableName = "attendance".$instituteId;
               $fatherMobileNo=$rowId['fatherMobileNo'];
		       $fatherName=$rowId['fatherName'];
               
               //echo $instituteId." =====  ".$studentId." =====  ".$fatherMobileNo." =====  ".$classId."<br><br>";
              
               $forInterval=1;
               $message1='';
		       $queryForInterval="SELECT `value` FROM config WHERE  param='MESSAGE_SENDING_INTERVAL' AND instituteId = '".$instituteId."'";
		       $queryForIntervalResult=mysql_query($queryForInterval);
		       if($rowInterval=mysql_fetch_array($queryForIntervalResult)) {
		         $forInterval=$rowInterval['value'];
		       }
		       $message1="Dear, ".$fatherName." Your ward's Attendance as on ".date("Y-m-d ")." is \n\r";
              
		       // Fetch Student Attendance
	           $queryGettingAttendance = getAttendanceSendSMS($studentId,$classId,$tableName,$instituteId);
  	           $gettingAttendanceResult=mysql_query($queryGettingAttendance );
             
	           $flag=0;
	           if($gettingAttendanceResult) {
		          while($row1=mysql_fetch_array($gettingAttendanceResult)) {
		             $flag=1;
		             $message1.=$row1['subjectCode']." ".$row1['attended']."/".$row1['delivered']."\n\r";						
	  	          }
	           } 
              
		       if($testingSms==0) {
		  	        if($message1!='') {
		                 $startMsg=0;  
		                 $endMsg=160;  
		                 do {
		                        $msg = substr($message1,$startMsg,$endMsg);     
		                        //studentSendSMS(trim($fatherMobileNo), $msg, $serviceProvider);//Sending Message after selected Days
		                        $startMsg +=(160-1);
		                        $endMsg+=160;
		                        echo "sucessfully $fatherMobileNo  $fatherName  $rollNo  $msg  \n\r";
		                        $msg = substr($message1,$startMsg,$endMsg);  
		                    } while($msg!='');
		              }
		       }
		       else {
                    $checkLastDate = date("Y-m-d", strtotime($lastDate ."+{$forInterval}"." days"));  
                    if($message1!='') {
                        if(date("Y-m-d ") >= $checkLastDate) {
		  		           if(trim($fatherMobileNo)!='' && strlen(trim($fatherMobileNo))==10 && $message1 !='') {	
					            $startMsg=0;  
					            $endMsg=160;  
                                do {
					                 $msg = substr($message1,$startMsg,$endMsg);
					                 studentSendSMS(trim($fatherMobileNo), $msg, $serviceProvider);  //Sending Message after selected Days
                                     $startMsg +=(160-1);
					                 $endMsg+=160;
                                     echo "Sucessfully $fatherMobileNo  $fatherName  $rollNo  $msg  \n\r";    
					                 $msg = substr($message1,$startMsg,$endMsg); 
                                     $flagCheck=1;
					            } while($msg!='');
				           }
				        } // If condition End (Date check)
			        }
	           }	
  	      }//while end(Getting students details)
   }//Main while end(selected classes for attendance messages)
   
   if($flagCheck==1){                                      
      $current=date('Y-m-d');
      $sendLastAlertDate= "UPDATE `periodic_attendance_alerts` SET alertDate='$current'";
      mysql_query($sendLastAlertDate);
   }



function getAttendanceSendSMS($studentId='',$classId='',$tableName='',$instituteId='') {

	//Fetching Attendance For Selected Students
	$str = "SELECT
			 t.studentId, t.classId, t.subjectId, t.subjectCode, t.subjectName, t.className, t.studentName,
			 IFNULL(t.employeeName,'---') AS employeeName,t.subjectTypeId, t.subjectTypeName, 
			 t.rollNo, t.universityRollNo,CONCAT(t.subjectName,' (',t.subjectCode,')') AS subjectName1, 
             t.periodName,t.fromDate, t.toDate, t.lectureAttended AS attended, t.lectureDelivered AS delivered, 
             IFNULL(t.leaveTaken,0) AS leaveTaken,  IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100) AS per,
			 IF(t.lectureDelivered=0,0,(t.lectureAttended/t.lectureDelivered)*100) AS per1
		 FROM
			(SELECT

				     tt.studentId, tt.classId, tt.subjectId, tt.subjectCode, tt.subjectName, tt.className, 											     
                     tt.studentName,tt.rollNo, tt.universityRollNo, MIN(tt.fromDate) AS fromDate, 
				     MAX(tt.toDate) AS toDate,  tt.periodName,
				     GROUP_CONCAT(DISTINCT tt.employeeName SEPARATOR ', ')  AS employeeName,
				     tt.subjectTypeId, tt.subjectTypeName,
				     IFNULL(SUM(tt.lectureAttended),0) AS lectureAttended, IFNULL(SUM(tt.lectureDelivered),0) AS lectureDelivered,
				     IFNULL(SUM(tt.leaveTaken),0) AS leaveTaken 
			FROM
				(SELECT
					att.classId, att.subjectId, att.groupId, att.studentId, su.subjectCode, 											
                    su.subjectName, c.className,
					CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
					IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
					IF(IFNULL(s.universityRollNo,'')='','---',s.universityRollNo) AS universityRollNo,
					st.subjectTypeId, st.subjectTypeName,
					IF(IFNULL(att.periodId,'')='','-1',att.periodId) AS periodId, gt.groupTypeId, 												
                    grp.groupName,att.fromDate, att.toDate,
					IF(IFNULL(p.periodNumber,'')='','',p.periodNumber) AS periodNumber, 											       
                     IF(att.isMemberOfClass=0, -1, 1) AS isMemberOfClass,
					IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2,			
					(ac.attendanceCodePercentage/100),att.lectureAttended)) AS lectureAttended,
					IF(att.isMemberOfClass=0, '', att.lectureDelivered) AS lectureDelivered, sp.periodName,
				   
					  (SELECT
						GROUP_CONCAT(DISTINCT e.employeeName,' (',e.employeeCode,')' SEPARATOR ', ')
					   FROM
						employee e, time_table tt
					   WHERE
					      	e.employeeId = tt.employeeId AND tt.employeeId = att.employeeId AND
					     	tt.classId=att.classId  AND tt.subjectId = att.subjectId AND
					      	tt.groupId=att.groupId AND tt.toDate IS NULL)  AS employeeName,
				      	      	IFNULL(IF(att.isMemberOfClass=0, '', IF(att.attendanceType =2, 
						IF((ac.attendanceCodePercentage/100)=0,
						 (SELECT
							  DISTINCT IF(IFNULL(dl.dutyLeaveId,'')='','',1)
						  FROM
							 duty_leave dl
						  WHERE
							   dl.studentId = att.studentId AND
							   dl.classId   = att.classId   AND
							   dl.subjectId = att.subjectId AND
							   dl.groupId   = att.groupId   AND
							   dl.periodId  = att.periodId  AND
							   att.fromDate = dl.dutyDate   AND
							   att.toDate   = dl.dutyDate   AND
							   dl.rejected  = 1),''),'')),'') AS leaveTaken
				FROM
				      group_type gt, `group` grp, class c, study_period sp, subject_type st, `subject` su,
				      student s INNER JOIN ".$tableName." att ON att.studentId = s.studentId
				      LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND ac.instituteId = $instituteId)
				      LEFT JOIN period p ON att.periodId = p.periodId
				WHERE
				      sp.studyPeriodId = c.studyPeriodId AND
				      gt.groupTypeId = grp.groupTypeId  AND
				      att.groupId   = grp.groupId       AND
				      att.subjectId = su.subjectId      AND
				      st.subjectTypeId = su.subjectTypeId AND
				      att.classId   = c.classId AND
				      att.studentId = '$studentId' AND
				      att.classId = '$classId') AS tt
		 
		 GROUP BY
			tt.studentId, tt.classId, tt.subjectId ) AS t
		 ORDER BY
			subjectCode ASC";

	return $str;
}
// Function To Send Sms
 function studentSendSMS($mobileNo, $message, $sender='CHALKPAD') {
        $message =str_ireplace('&amp;','&',$message);
        $message =urlencode(preg_replace("/&#?[a-z0-9]+;/i","",$message));
        
        if(defined('CLIENT_NAME') && CLIENT_NAME=='SGI') {                
            $postVars = 'data=<?xml version="1.0" encoding="ISO-8859-1"?><!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" ><MESSAGE VER="1.2"><USER      USERNAME="'.SMS_GATEWAY_USERNAME.'" PASSWORD="'.SMS_GATEWAY_PASSWORD.'"/><SMS  UDH="0" CODING="1" TEXT="'.$message.'sgiw" PROPERTY="0" ID="1"><ADDRESS FROM="'.SMS_GATEWAY_SNDR_VALUE.'" TO="'.$mobileNo.'" SEQ="1" TAG="" /></SMS></MESSAGE>&action=send';
        }
        else {    
    		// $mobileNo = '9878425461,9855094422';
            $postVars = SMS_GATEWAY_USER_VARIABLE.'='.SMS_GATEWAY_USERNAME.'&'.SMS_GATEWAY_PASS_VARIABLE.'='.SMS_GATEWAY_PASSWORD.'&'.SMS_GATEWAY_NUMBER_VARIABLE.'='. $mobileNo.'&'.SMS_GATEWAY_SNDR_VARIABLE.'='.$sender.'&'.SMS_GATEWAY_MESSAGE_VARIABLE.'='.$message;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SMS_GATEWAY_URL); //set the url
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
        curl_setopt($ch, CURLOPT_POST, 1); //set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars); //set the POST variables
        $response = curl_exec($ch); //run the whole process and return the response
        curl_close($ch); //close the curl handle
        if(preg_match("/failure/i",$response)) {
            logError('SMS Response: '.$response);
            return false;
        }
        else if(preg_match("/ERROR/i",$response)) {
            logError('SMS Response: '.$response);
            return false;
        }
        else {
            return true;
        }
 }
?>



