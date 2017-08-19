<?php
//No-time-limit imposed for execution 
set_time_limit(0); 

//The root path of the front end site.
$siteAddress = dirname(__FILE__);    
$siteAddress = substr($siteAddress,0,strlen(str_replace("MobileApi","",$siteAddress))-1);  

$url = $siteAddress.'/Library/common.inc.php';
require_once("$url"); //includes and evaluates the specified file during the execution of the script

//connection to database
$conn =mysql_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect:' . mysql_error());
mysql_select_db(DB_NAME,$conn) or die(mysql_error());


//-------------------------------------------------------
//Login Api
//Purpose:Login and generate authorisation key
//Authorisation Key is a unique LoginKey which will be used as a parameter in all further calls from mobile applications
// Author :Cheena Garg
// Created on : (18.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

if($_REQUEST['fn']== "login") {
    if($_REQUEST['uname']!="" && $_REQUEST['pw']!=""){ 
                $fieldName = " i.instituteId, i.instituteName, i.instituteCode, i.instituteLogo, u.userId, u.userName ";
        $userName = htmlentities(trim($_REQUEST['uname']));
        $userPassword = htmlentities(trim($_REQUEST['pw']));
        $condition = " u.instituteId = i.instituteId AND u.userName='".$userName."' and u.userPassword='".$userPassword."'";
        $res = mysql_query("SELECT $fieldName FROM `user` u, institute i WHERE $condition");
        $countres=mysql_num_rows($res);
        if($countres>0){
            if($row=mysql_fetch_array($res)){ 
                $userId = $row['userId'];
             }
            $count=1;
            $auth_key="";
            while($count==1) {  //to generate unique Loginkey(authKey) each time   
                $auth_key= rand();
                $key= mysql_query("SELECT authKey FROM mobile_user where authKey='".md5($auth_key)."'");
                $count=mysql_num_rows($key);
            }

            if($count==0) {
                $query=mysql_query("SELECT * FROM mobile_user where userId='".$userId."'");
                $countrows=mysql_num_rows($query);

                if($countrows>0){ //to delete authkey from table if already exists for logged in user

                    mysql_query("DELETE FROM mobile_user where userId='".$userId."'");
                }
                    mysql_query("INSERT INTO mobile_user(userId,authKey) VALUES(".$userId.",md5('".$auth_key."'))");
            } 
            else{
                echo mysql_error(); 
            }
           
            $akey=mysql_query("SELECT CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,mu.authKey  
                     FROM 
                           `student`s ,mobile_user mu

                    WHERE  
                            s.userId=mu.userId AND mu.userId=$userId
                            
                           ");

            $i=0;
            if($rows =mysql_fetch_array($akey)){ //extract data from resultant array , returning associative array( unique authorisation key & student name)
                $output[$i]= array("authkey"=>$rows['authKey'],
                "firstname"=>$rows['studentName']
                );
                $i++;       
            }

            echo json_encode($output);
            mysql_close($conn);

        } 
        else echo "null"; 

    }
        else echo "null"; // if username and password is null

}

//-------------------------------------------------------
//CHECKLOGIN API-to check if user is logged in
//Returns 1 if user is logged in else returns 0
// Author :Cheena Garg
// Created on : (18.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "checklogin"){   
        if($_REQUEST['authkey']!=""){ 
            $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res); 
                
                if($count>0){    
                    $out= array("checklogin"=>1);
                    echo json_encode($out);  //returns 1 if user is logged in( by checking if authkey exists in database)
                }

                else {
                    $output= array("checklogin"=>0);
                    echo json_encode($output); //else returns 0
                }  

                mysql_close($conn);
        }
        else{
             echo "ERROR : Enter authorisation key"; 
        }
}
//-------------------------------------------------------
//LOGOUT API
//deletes authkey from mobile_user table
// Author :Cheena Garg
// Created on : (18.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "logout"){    
        if($_REQUEST['authkey']!=""){
            $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
            if($count>0){    //deletes authorisation key from the table 
                $out=mysql_query("DELETE FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
                echo json_encode("logged out successfully");
            }

            mysql_close($conn);
        }
}

//-------------------------------------------------------
//User can see subjects/courses currently taken
// Author :Cheena Garg
// Created on : (19.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "subjects"){
        if($_REQUEST['authkey']!=""){  //to get userId
            $res = mysql_query("SELECT userId FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            if($row=mysql_fetch_array($res)){
                $userId = $row['userId'];
            }    
                    //to get student's current subject (subjectName,Code,Type,study Period,teacher name)
            $query= mysql_query(" 
                        SELECT
                            DISTINCT 
                                su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, 
                                st.subjectTypeName, e.employeeName,c.classId,ss.periodName, c.instituteId        
                        FROM
                                student_groups sg,  group_type gt, subject_type st, `subject` su, subject_to_class sc 
                                LEFT JOIN `class` c ON sc.classId = c.classId
                                LEFT JOIN `time_table` tt ON sc.subjectId = tt.subjectId AND tt.toDate IS NULL AND tt.classId = c.classId
                                LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId
                                LEFT JOIN `employee` e ON e.employeeId = tt.employeeId
                                LEFT JOIN study_period ss ON c.studyPeriodId = ss.studyPeriodId
                        WHERE
                                su.subjectId=sc.subjectId
                                AND st.subjectTypeId = su.subjectTypeId
                                AND su.hasAttendance = 1
                                AND c.isActive IN (1,3)
                                AND sg.classId= c.classId
                                AND sc.hasParentCategory=0
                                AND sg.groupId = g.groupId
                                AND sg.studentId = (SELECT studentId FROM student WHERE userId=$userId)
                                AND g.classId = c.classId   
                                AND g.groupId IS NOT NULL
                                AND su.subjectId IS NOT NULL
                                AND gt.groupTypeId = g.groupTypeId
                                AND c.classId = (SELECT classId FROM student WHERE userId=$userId)
                        UNION
                        SELECT
                            DISTINCT 
                                su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, 
                                st.subjectTypeName, e.employeeName,c.classId,ss.periodName, c.instituteId    
                        FROM
                                student_groups sg,group_type gt, subject_type st, `subject` su, student_optional_subject sc
                                LEFT JOIN `class` c ON sc.classId = c.classId
                                LEFT JOIN `time_table` tt ON sc.subjectId = tt.subjectId AND tt.toDate IS NULL AND tt.classId = c.classId
                                LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId
                                LEFT JOIN `employee` e ON e.employeeId = tt.employeeId
                                LEFT JOIN study_period ss ON c.studyPeriodId = ss.studyPeriodId    
                        WHERE
                                su.subjectId=sc.subjectId
                                AND st.subjectTypeId = su.subjectTypeId
                                AND su.hasAttendance = 1
                                AND c.isActive IN (1,3)
                                AND sg.classId= c.classId
                                AND sg.groupId = g.groupId
                                AND sg.studentId = (SELECT studentId FROM student WHERE userId=$userId)
                                AND g.classId = c.classId   
                                AND g.groupId IS NOT NULL
                                AND su.subjectId IS NOT NULL
                                AND gt.groupTypeId = g.groupTypeId
                                AND c.classId = (SELECT classId FROM student WHERE userId=$userId)

                        ORDER BY 
                                classId,  subjectTypeId, subjectCode, subjectId ASC
                    ");

                $i=0;

                while($rows =mysql_fetch_array($query)){    
                    $result[$i]=array("subjectname"=>$rows['subjectName'],
                              "subjectcode"=>$rows['subjectCode'],
                              "type"=>$rows['subjectTypeName'],
                              "teachername"=>$rows['employeeName'],
                                  "studyperiod"=>$rows['periodName']
                            );
                    $i++; 
                }

            echo json_encode($result); //returns associative array

            mysql_close($conn);
        }
        else{
            echo "ERROR : Enter authorisation key";
        }
}

//-------------------------------------------------------
//Notice Api
//User sees a list of notices on the screen
// Author :Cheena Garg
// Created on : (19.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "notices"){

	if($_REQUEST['authkey']!=""){
	$res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
	$count= mysql_num_rows($res);
        $curDate=date('Y')."-".date('m')."-".date('d');
                        if($count>0){ 
				 if($row=mysql_fetch_array($res)) 
		          	 $userId = $row['userId'];
			} 
        $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
        		   		if($rows=mysql_fetch_array($res1)){
						 $instituteId = $rows['instituteId'];	
			            		 $studentId = $rows['studentId'];	
					         $classId = $rows['classId'];
                                                 $sessionId = $rows['sessionId'];
                                                 $branchId=$rows['branchId'];
                                                 $degreeId=$rows['degreeId'];
                                                 $universityId=$rows['universityId'];
					} 
				
		if($count>0){	//to get notices
		$query=mysql_query("
					SELECT 
						n.noticeId,IF(IFNULL(n.noticeSubject,'')='','0',n.noticeSubject) AS noticeSubject, n.noticeText,
						n.visibleFromDate,n.visibleToDate,
						IF(IFNULL(n.noticeAttachment,'')='',0,CONCAT('".STORAGE_HTTP_PATH."/Images/Notice/',n.noticeAttachment)) AS 	 							noticeAttachment,nr.roleId
					FROM 
						department d,notice n INNER JOIN notice_visible_to_role nr ON ( n.noticeId=nr.noticeId ) AND (
                                                   (nr.universityId IS NULL OR nr.universityId=1)
                                                AND
                                                   (nr.degreeId IS NULL OR nr.degreeId=1)
                                                AND
                                                   (nr.branchId IS NULL OR nr.branchId=1)
                                            ) 
					WHERE
						
					        nr.roleId=4
                                                AND n.noticeId=nr.noticeId
                                                AND nr.sessionId='$sessionId'
                                                AND nr.instituteId='$instituteId'
                                                AND n.instituteId='$instituteId'
                                                AND n.departmentId=d.departmentId
                                                AND '$curDate' >= n.visibleFromDate
                                                AND '$curDate' <= n.visibleToDate
	                                GROUP BY n.noticeId        
					ORDER BY 
						n.visibleFromDate DESC
					limit 	0,100  ");

     
                
       
    
        $i=0;   
        while($rows =mysql_fetch_array($query)){
            $result[$i]=array("noticeid"=>$rows['noticeId'],
                      "subject"=>html_entity_decode($rows['noticeSubject']),
                      "description"=>html_entity_decode($rows['noticeText']),
                      "visiblefromdate"=>$rows['visibleFromDate'],
                      "visibletodate"=>$rows['visibleToDate'],
                      "attachment"=>$rows['noticeAttachment']);
            $i++; 
        }

        echo json_encode($result);// returns notice details 
        mysql_close($conn);
        }
        else{
            echo "ERROR : Enter valid authorisation key";
        }
       }
    else{
        echo "ERROR: enter key";
    }
}


//-------------------------------------------------------
//Attendance API
//User selects the subject and then sees the consolidated attendance for that 
//list lecture attended,delivered ,percentage
// Author :Cheena Garg
// Created on : (20.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "attendance"){   
        if($_REQUEST['authkey']!=""){                 
                   $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
             if($count>0){ 
                 if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
                           //to get studentId,classId and instituteId
                       $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
                           if($rows=mysql_fetch_array($res1)){
                         $instituteId = $rows['instituteId'];    
                                 $studentId = $rows['studentId'];    
                             $classId = $rows['classId'];
				$holdAttendance = $rows['holdAttendance'];
                    }    

                           $tableName = "attendance".$instituteId; //to get subjectwise attendance details(subjectname,lecture attended,deliverd,percentage)
				if($holdAttendance==0){
                     $query=mysql_query("
                                SELECT
                                            t.studentId, t.classId, t.subjectId, t.subjectCode, t.subjectName, t.className, t.studentName,
                                     IFNULL(t.employeeName,'---') AS employeeName,t.subjectTypeId, t.subjectTypeName, 
                                     t.rollNo, t.universityRollNo,CONCAT(t.subjectName,' (',t.subjectCode,')') AS subjectName1,                                          t.periodName,t.fromDate, t.toDate, t.lectureAttended AS attended, t.lectureDelivered AS delivered,                                          IFNULL(t.leaveTaken,0) AS leaveTaken,
                                     IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100) AS per,
                                     IF(t.lectureDelivered=0,0,(t.lectureAttended/t.lectureDelivered)*100) AS per1
                                  FROM
                                         (SELECT
            
                                             tt.studentId, tt.classId, tt.subjectId, tt.subjectCode, tt.subjectName, tt.className,                                                  tt.studentName,tt.rollNo, tt.universityRollNo, MIN(tt.fromDate) AS fromDate, 
                                             MAX(tt.toDate) AS toDate,  tt.periodName,
                                             GROUP_CONCAT(DISTINCT tt.employeeName SEPARATOR ', ')  AS employeeName,
                                             tt.subjectTypeId, tt.subjectTypeName,
                                             IFNULL(SUM(tt.lectureAttended),0) AS lectureAttended, IFNULL(SUM(tt.lectureDelivered),0)                                                  AS lectureDelivered,
                                             IFNULL(SUM(tt.leaveTaken),0) AS leaveTaken 
                                      FROM
                                              (SELECT
                                            att.classId, att.subjectId, att.groupId, att.studentId, su.subjectCode,                                             su.subjectName, c.className,
                                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                            IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                                            IF(IFNULL(s.universityRollNo,'')='','---',s.universityRollNo) AS universityRollNo,
                                            st.subjectTypeId, st.subjectTypeName,
                                            IF(IFNULL(att.periodId,'')='','-1',att.periodId) AS periodId, gt.groupTypeId,                                                 grp.groupName,att.fromDate, att.toDate,
                                            IF(IFNULL(p.periodNumber,'')='','',p.periodNumber) AS periodNumber,                                                     IF(att.isMemberOfClass=0, -1, 1) AS isMemberOfClass,
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
                                                  LEFT JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  AND                                                   ac.instituteId = $instituteId)
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
                                           subjectCode ASC
                            ");

    
                    $i=0;
                    while($rows =mysql_fetch_array($query)){   
                        $result[$i]=array("subjectcode"=>$rows['subjectCode'],
                                  "subjectname"=>$rows['subjectName'],
                                  "teachername"=>$rows['employeeName'],
                                  "lectureattended"=>$rows['attended'],
                                  "lecturedelivered"=>$rows['delivered'],
                                      "percent"=>$rows['per']
                                );

                        $i++; 
                    }

                    echo json_encode($result); //returns associative array(subjectWise attendance details)
                    mysql_close($conn);
			die;
		}
		 echo "null";
        }

        else{
            echo "null";
        }
}
//-------------------------------------------------------
//User can see the list of faculty that is teaching them currently
//To Get faculty list and their email address
// Author :Cheena Garg
// Created on : (21.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "faculty"){    
        if($_REQUEST['authkey']!=""){     
            $res = mysql_query("SELECT userId FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
                if($row=mysql_fetch_array($res)){
                    $userId = $row['userId'];    
                }
                
                //According to timetable,to get list of teachers and their email address
                $resulset= mysql_query("
                            SELECT
                                DISTINCT 
                                    e.employeeName,e.emailAddress        
                            FROM
                                    student_groups sg,  group_type gt, subject_type st, `subject` su, subject_to_class sc 
                                    LEFT JOIN `class` c ON sc.classId = c.classId
                                    LEFT JOIN `time_table` tt ON sc.subjectId = tt.subjectId AND tt.toDate IS NULL AND tt.classId =                                     c.classId
                                    LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId
                                    LEFT JOIN `employee` e ON e.employeeId = tt.employeeId

                            WHERE
                                    su.subjectId=sc.subjectId
                                    AND st.subjectTypeId = su.subjectTypeId
                                    AND su.hasAttendance = 1
                                    AND c.isActive IN (1,3)
                                    AND sg.classId= c.classId
                                    AND sc.hasParentCategory=0
                                    AND sg.groupId = g.groupId
                                    AND sg.studentId = (SELECT studentId FROM student WHERE userId=$userId)
                                    AND g.classId = c.classId   
                                    AND g.groupId IS NOT NULL
                                    AND su.subjectId IS NOT NULL
                                    AND gt.groupTypeId = g.groupTypeId
                                    AND c.classId = (SELECT classId FROM student WHERE userId=$userId)
                            UNION
                            SELECT
                                DISTINCT 
                                    e.employeeName,e.emailAddress    
                            FROM
                                    student_groups sg,group_type gt, subject_type st, `subject` su, student_optional_subject sc
                                    LEFT JOIN `class` c ON sc.classId = c.classId
                                    LEFT JOIN `time_table` tt ON sc.subjectId = tt.subjectId AND tt.toDate IS NULL AND tt.classId =                                     c.classId
                                    LEFT JOIN `group` g ON g.classId=c.classId AND tt.groupId=g.groupId
                                    LEFT JOIN `employee` e ON e.employeeId = tt.employeeId
                            WHERE
                                    su.subjectId=sc.subjectId
                                    AND st.subjectTypeId = su.subjectTypeId
                                    AND su.hasAttendance = 1
                                    AND c.isActive IN (1,3)
                                    AND sg.classId= c.classId
                                    AND sg.groupId = g.groupId
                                    AND sg.studentId = (SELECT studentId FROM student WHERE userId=$userId)
                                    AND g.classId = c.classId   
                                    AND g.groupId IS NOT NULL
                                    AND su.subjectId IS NOT NULL
                                    AND gt.groupTypeId = g.groupTypeId
                                    AND c.classId = (SELECT classId FROM student WHERE userId=$userId)
                        ");

                $i=0;
                while($rows =mysql_fetch_array($resulset)){    
                    $result[$i]=array("teachername"=>$rows['employeeName'],
                              "teacheremail"=>$rows['emailAddress']);
                    $i++;
                 }

                echo json_encode($result); //returns teacher name ,email address
                mysql_close($conn);
        }
        else{
            echo "ERROR : Enter key";    
        }
}
//-------------------------------------------------------
//attendance (calender,grid) format
//User can see the attendance in grid format
// Author :Cheena Garg
// Created on : (22.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "attendance2"){   
        if($_REQUEST['authkey']!=""){  
            $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
            if($count>0){
                if($row=mysql_fetch_array($res)){ 
                    $userId = $row['userId'];
            }    }

            $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
            if($rows=mysql_fetch_array($res1)){
                 $instituteId = $rows['instituteId'];    
                 $studentId = $rows['studentId'];    
                 $classId = $rows['classId'];
            }    
    
            $tableName = "attendance".$instituteId; 
            $subjectCode =$_REQUEST['subjectcode'] ;
            $fromDate =$_REQUEST['date'];  
        // to show attendance in grid format,returns 100 if student is present else 0
            $query=mysql_query("
                        SELECT
                            IF(att.isMemberOfClass=0,'N/A',IF(att.attendanceType =2,ac.attendanceCodePercentage,'?')) AS attend,
                            att.fromDate, p.periodNumber     
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
                            att.studentId= '$studentId' AND
                            att.classId = '$classId' AND
                            su.subjectCode = '$subjectCode' AND
                            DATE_FORMAT(att.fromDate,'%b-%y') = '$fromDate'
                        ORDER BY
                            att.fromDate, att.periodId ASC
                     ");

            // extract data from results, returning an associative array
            $i=0;
            while($rows =mysql_fetch_array($query)){
                $result[$i]=array("attendance"=>$rows['attend'],
                          "date"=>$rows['fromDate'],
                          "periodNo"=>$rows['periodNumber']
                        );

                 $i++; 
            }
            echo json_encode($result); 
            mysql_close($conn);

        }
        else{
            echo "null";
        }
}
//-------------------------------------------------------
//User selects the subject and then sees the marks for the different test components
// Author :Cheena Garg
// Created on : (25.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "testmarks"){    
        if($_REQUEST['authkey']!=""){  
            $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
             if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
            $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
            if($rows=mysql_fetch_array($res1)){ 
                $instituteId = $rows['instituteId'];    
                $studentId = $rows['studentId'];    
                $classId = $rows['classId'];
                $sessionId=$rows['sessionId']; 
                $holdTestMarks =$rows['holdTestMarks']; 
            }

            if($holdTestMarks==0) {
                    $tableName = "test".$instituteId;
                    $tableName1 = "test_marks".$instituteId;
                    $subjectCode =$_REQUEST['subjectcode'] ;     
                    //to get student's test details based on subject
                    $resulset=mysql_query("
                                SELECT
                                    CONCAT(su.subjectName,' (',su.subjectCode,')') AS subject,
                                    CONCAT(IF( ttc.examType = 'PC', 'Internal', 'External' ), ' (' , ttc.testTypeName, ')' ) AS examType,
                                    ttc.testTypeName,DATE_FORMAT(t.testDate,'%d-%b-%Y') AS testDate,
                                    emp.employeeName,
                                    CONCAT( t.testAbbr, t.testIndex ) AS testName,
                                    su.subjectCode,
                                    (tm.maxMarks) AS totalMarks,
                                    ROUND(IF(tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A')),1)  AS obtained,
                                    SUBSTRING_INDEX(cl.className,'-',-1) AS periodName,
                                    gr.groupName, cl.classId
                                FROM         
                                    test_type_category ttc,
                                    ".$tableName1." tm,
                                    student s,
                                    subject su,
                                    employee emp,
                                    ".$tableName." t,
                                    class cl,
                                    `group` gr

                                WHERE            
                                    t.testTypeCategoryId = ttc.testTypeCategoryId
                                    AND    t.classId=cl.classId
                                    AND    emp.employeeId=t.employeeId
                                    AND    t.testId = tm.testId
                                    AND    t.groupId = gr.groupId
                                    AND    tm.studentId = s.studentId
                                    AND    tm.subjectId = su.subjectId
                                    AND    tm.studentId = '$studentId'
                                    AND    cl.sessionId = '$sessionId'
                                    AND    cl.instituteId = '$instituteId'
                                    AND     cl.classId ='$classId'
                                    AND     su.subjectCode = '$subjectCode' 

                                 ORDER BY       
                                    t.testDate DESC 
                            ");

                    $i=0;
                      while($rows =mysql_fetch_array($resulset)){   
                        $result[$i]=array("testtype"=>$rows['examType'],
                                         "testdate"=>$rows['testDate'],    
                                                     "teachername"=>$rows['employeeName'],
                                      "marksobtained"=>$rows['obtained'],
                                          "totalmarks"=>$rows['totalMarks']
                                         );
                                         $i++; 
                    }
                    echo json_encode($result); //returns student's test marks details
                    mysql_close($conn);
                    die;
             }
             echo "null";  
        }
        else{
          echo "null";
        }
}

//-------------------------------------------------------
//Grades Api
//User selects the study period and sees the grades
// Author :Cheena Garg
// Created on : (25.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "grades"){    
        if($_REQUEST['authkey']!=""){  
        $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
        $count= mysql_num_rows($res);
             if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
                //to get studentid,classId,institute and sessionId
                   $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
                       if($rows=mysql_fetch_array($res1)){ 
                    $instituteId = $rows['instituteId'];    
                    $studentId = $rows['studentId'];    
                $classId = $rows['classId'];
                $sessionId=$rows['sessionId'];  
		        $holdGrades=$rows['holdGrades'];  
                $holdTestMarks =$rows['holdTestMarks']; 
            }
    
            $tableName = "test".$instituteId;
            $tableName1 = "test_marks".$instituteId;
            $periodName=$_REQUEST['periodname'];
    	if($holdGrades==0 && $holdTestMarks==0){
            // to get grade card details
            $query="
                SELECT 
                    DISTINCT
                        su.subjectName,
                        su.subjectCode,
                        SUBSTRING_INDEX(cl.className,'-',-1) AS periodName,
                        gr.groupName, cl.classId

                FROM           test_type_category ttc,
                         ".$tableName1." tm,
                        student s,
                        subject su,
                        employee emp,
                        ".$tableName." t,
                        class cl,
                        `group` gr

                WHERE            t.testTypeCategoryId = ttc.testTypeCategoryId
                AND        t.classId=cl.classId
                AND        emp.employeeId=t.employeeId
                AND        t.testId = tm.testId
                AND        t.groupId = gr.groupId
                AND        tm.studentId = s.studentId
                AND        tm.subjectId = su.subjectId
                AND        tm.studentId = (SELECT studentId FROM student WHERE userId=$userId)
                AND        cl.instituteId = '$instituteId'
                AND             cl.classId =(SELECT 
                                   DISTINCT c.classId 
                                 FROM 
                                   student_groups sg, class c, study_period sp  
                                 WHERE 
                                   sg.classId = c.classId AND
                                       c.studyPeriodId = sp.studyPeriodId AND
                                   sg.studentId = $studentId AND
                                   c.studyPeriodId IN     
                                   (SELECT DISTINCT studyPeriodId FROM study_period WHERE periodName = '$periodName'))

             ";

              $query1=mysql_query($query);
        
            $i=0;
              while($rows =mysql_fetch_array($query1)){ 
                $result1[$i]=array("subjectname"=>$rows['subjectName'],    
                                       "subjectcode"=>$rows['subjectCode'],
                           "credit"=>0,
                           "grade"=>0
                          );
                $i++; 
            }
            echo json_encode($result1);   //returns associative aaray
            mysql_close($conn);
		die;
	  }    
		 echo "null";    
	}
        else{
             echo "null";
        }
} 

//-------------------------------------------------------
//Semester Api
//list student's semester till date
// Author :Cheena Garg
// Created on : (25.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "semester"){
        if($_REQUEST['authkey']!=""){  
                $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
             if($count>0){ 
                 if($row=mysql_fetch_array($res)) 
                        $userId = $row['userId'];
            }
            $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
                if($rows=mysql_fetch_array($res1)){
                    $instituteId = $rows['instituteId'];    
                    $studentId = $rows['studentId'];    
                    $classId = $rows['classId'];
                }

                // to get student's semester(periodName) till date
                    $query1 = mysql_query("    
                            SELECT         
                                DISTINCT
                                    (sg.classId),substring_index(className,'-',-1) as periodName,
                                                                 className AS className1, c.isActive                
                             FROM            
                                    `student_groups` sg,class c
                              WHERE
                                                     sg.classId= c.classId  AND    
                                    c.instituteId = '$instituteId'  AND    
                                                                sg.studentId= '$studentId'
                            
                         
                        UNION
                                       SELECT
                                                DISTINCT
                                    (sg.classId),substring_index(className,'-',-1) as periodName,
                                                     className AS className1, c.isActive
                                        FROM        
                                    `student_optional_subject` sg, class c
                                        WHERE        
                                                      sg.classId= c.classId  AND    
                                     c.instituteId = '$instituteId'  AND    
                                                         sg.studentId= '$studentId'
                                          
                                        ");

                $i=0; 
                while ($row = mysql_fetch_array($query1)) {
                    $result[$i] = array("studyperiod"=>$row['periodName']);
                    $i++;
                 }
                
                echo json_encode($result); 
                mysql_close($conn);

        }
        else{
             echo "null";
        }
}

//-------------------------------------------------------
//to check fees payment history
//fees detail, returns total fess paid=cash amount+instrument amount
// Author :Cheena Garg
// Created on : (26.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "fees"){    
        if($_REQUEST['authkey']!=""){  
            $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
             if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
                
            $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
                       if($rows=mysql_fetch_array($res1)){
                    $instituteId = $rows['instituteId'];    
                    $studentId = $rows['studentId'];    
                    $classId = $rows['classId'];
                    $sessionId=$rows['sessionId'];    
                }
            
                 //to get fees details
                    $res1 =mysql_query("SELECT 
                            IF(IFNULL(SUM(cashAmount),'')='',0,SUM(cashAmount)) AS cashAmount
                            
                             FROM 
                             fee_receipt 
                             WHERE 
                            studentId = '$studentId'  AND receiptStatus NOT IN (3,4) AND classId = '$classId'
                           ");

                $res2=mysql_query("SELECT 
                                            IF(IFNULL(SUM(instrumentAmount),'')='',0,SUM(instrumentAmount)) AS instrumentAmount
                
                                FROM 
                            fee_receipt fr, fee_payment_detail fp 
                             WHERE 
                             fp.feeReceiptId = fr.feeReceiptId AND
                            fr.studentId =  '$studentId' AND fr.receiptStatus NOT IN (3,4) AND 
                            fr.classId = '$classId'
                        ");

            $result = Array();
            while ($row = mysql_fetch_assoc($res1)) {
                $result[] = $row;
            }

            $result1 = Array();
            while ($rows = mysql_fetch_assoc($res2)) {
                $result1[] = $rows;
            }
     
            $res = array_merge((array)$result, (array)$result1);
             echo json_encode($res); // returns total fees paid(cash amount + instrumental amount if any)
            mysql_close($conn);
        }
        else{
             echo "null";
        }
}


//-------------------------------------------------------
//Current timetable in grid format
//to get timetable details i.e coursecode,roomno,periodno,teacher name
// Author :Cheena Garg
// Created on : (25.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "timetable"){    
        if($_REQUEST['authkey']!=""){  
                   $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
             if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
                           $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
                           if($rows=mysql_fetch_array($res1)){
                        $instituteId = $rows['instituteId'];    
                        $studentId = $rows['studentId'];    
                        $classId = $rows['classId'];
                        $sessionId=$rows['sessionId'];
                    }

                       
                        // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
                        $query = "SELECT
                                  DISTINCT 
                                         tt.daysOfWeek, tt.roomAbbreviation, tt.subjectCode, tt.periodNumber,
                                         GROUP_CONCAT(DISTINCT tt.employeeName) AS employeeName,
                                         GROUP_CONCAT(DISTINCT tt.groupName) AS groupName
                             FROM    
                                (SELECT
                                                DISTINCT
                                        sg.studentId, p.periodSlotId, tt.periodId, tt.daysOfWeek, p.periodNumber,
                                        CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,gr.groupShort,
                                        SUBSTRING_INDEX(cl.className,'-',-3) as className,
                                        gr.groupName,sub.subjectName,sub.subjectCode,
                                        r.roomName,concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as                                         roomAbbreviation,
                                        emp.employeeName, gr.groupId, sub.subjectId, cl.classId, sub.hasMarks, sub.hasAttendance,
                                        emp.employeeId, tt.fromDate,
                                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName
                                 FROM
                                                    student s, student_groups sg,  `group` gr,  subject sub, employee emp, room r, 
                                        class cl,time_table_labels ttl, block b, building c , 
                                        classes_visible_to_role cvtr, time_table tt
                                         LEFT JOIN period p ON tt.periodId = p.periodId
                                           WHERE
                                        
                                         sg.studentId = s.studentId
                                        AND sg.classId = cl.classId
                                        AND sg.groupId = tt.groupId
                                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                                        AND tt.roomId = r.roomId
                                        AND tt.toDate IS NULL
                                        AND cl.instituteId = '$instituteId'
                                        AND tt.sessionId='$sessionId'
                                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                                        AND r.blockId = b.blockId AND b.buildingId = c.buildingId
                                        AND sg.studentId='$studentId' 
                                        AND cl.classId = '$classId'
    
                                      UNION
                                      SELECT
                                            DISTINCT
                                        sg.studentId, p.periodSlotId, tt.periodId, tt.daysOfWeek, p.periodNumber,
                                        CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,gr.groupShort,
                                        SUBSTRING_INDEX(cl.className,'-',-3) as className,
                                         sub.subjectName,sub.subjectCode,r.roomName,
                                        concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation,
                                        emp.employeeName,gr.groupName, gr.groupId, sub.subjectId, cl.classId, sub.hasMarks,                                             sub.hasAttendance,
                                        emp.employeeId, tt.fromDate,
                                        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName
                                         FROM
                                            student s, student_optional_subject sg,  `group` gr,  subject sub, employee emp,
                                        room r, class cl,
                                            time_table_labels ttl, block b, building c , classes_visible_to_role cvtr, time_table tt
                                        LEFT JOIN period p ON tt.periodId = p.periodId
                                       WHERE
                                        
                                         sg.studentId = s.studentId
                                        AND sg.classId = cl.classId
                                        AND sg.groupId = tt.groupId
                                        AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                                        AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                                        AND tt.roomId = r.roomId
                                        AND tt.toDate IS NULL
                                        AND cl.instituteId = '$instituteId'
                                        AND tt.sessionId='$sessionId'
                                        AND tt.timeTableLabelId=ttl.timeTableLabelId
                                        AND r.blockId = b.blockId AND b.buildingId = c.buildingId
                                        AND sg.studentId='$studentId' 
                                        AND cl.classId = '$classId') AS tt
                             GROUP BY
                                tt.daysOfWeek, periodNumber
                                           ORDER BY  
                                daysOfWeek,periodNumber    
                                   ";

                 $query1 =mysql_query($query);

                $i=0;
                  while($rows =mysql_fetch_array($query1)){ 
                      $result[$i]=array( "day"=>$rows['daysOfWeek'],
                                "coursecode"=>$rows['subjectCode'],    
                                "roomname"=>$rows['roomAbbreviation'],
                                 "teachername"=>$rows['employeeName'],
                                "groupname"=>$rows['groupName'],
                                "periodno"=>$rows['periodNumber']
                                   );
                    $i++;
                 }


                $resultArray = array();
                            
                for($j=0;$j<count($result);$j++){  
                    $resultArray[$result[$j]['day']][$result[$j]['periodno']] = $result[$j];
                    
                }
            
                  echo json_encode($resultArray); //returns student's current timetable details
                      mysql_close($conn);
        }
        else{
             echo "null";
        }
}

//------------------------------------------------------
// to get resource details of student
// Author: Raghav salotra
// created On: (21/11/2011)
// copyright 2011-2012- Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------

else if($_REQUEST['fn']== "resourcedetails"){    
        if($_REQUEST['authkey']!=""){  
                   $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
             if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
                           $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
                           if($rows=mysql_fetch_array($res1)){
                        $instituteId = $rows['instituteId'];    
                        $studentId = $rows['studentId'];    
                        $classId = $rows['classId'];
                        $sessionId=$rows['sessionId'];
                    }
                    if($_REQUEST['subjectcode']){
                      $subjectcode=$_REQUEST['subjectcode'];
                      $condition="AND s.subjectCode='$subjectcode'";
                     }
                       
                        // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
                        $query = "select a.*, e.employeeName,r.resourceName as resourceName,s.subjectCode,IF(IFNULL(attachmentFile,'')='',0,CONCAT('".IMG_HTTP_PATH."/CourseResource/',attachmentFile)) AS	resourceAttachment
		FROM     course_resources a, subject_to_class b,employee e,resource_category r,subject s where a.instituteId = $instituteId
		AND       a.subjectId= s.subjectId
                $condition
		AND       a.sessionId = $sessionId
		AND       a.subjectId = b.subjectId
		AND       b.classId = $classId
		AND	  a.employeeId=e.employeeId
		AND       a.resourceTypeId=r.resourceTypeId 
              union
			   select a.*,e.employeeName,r.resourceName as resourceName,s.subjectCode,IF(IFNULL(attachmentFile,'')='',0,CONCAT('".IMG_HTTP_PATH."/CourseResource/',attachmentFile)) AS	resourceAttachment
		FROM   course_resources a, subject_to_class b, employee e, student_groups c,resource_category r ,subject s
		WHERE  a.instituteId = $instituteId
		AND    a.subjectId= s.subjectId
                $condition
                AND    a.sessionId = $sessionId
		AND    a.subjectId = b.subjectId
		AND    b.classId = $classId
                AND    a.employeeId=e.employeeId
                AND    b.classId = c.classId
		AND    r.instituteId=$instituteId
		AND    a.resourceTypeId=r.resourceTypeId
                AND    find_in_set(c.groupId, a.groupId) != 0
		ORDER BY  subjectCode ASC     
                                   ";

                 $query1 =mysql_query($query);

                $i=0;
                  while($rows =mysql_fetch_array($query1)){ 
                      $result[$i]=array( "subjectcode"=>$rows['subjectCode'],
                                "description"=>$rows['description'],    
                                "employeename"=>$rows['employeeName'],
                                "resourceurl"=>$rows['resourceUrl'],
                                "attachmentfile"=>$rows['attachmentFile'],
                                "attachmentaddress"=>$rows['resourceAttachment'],
                                "postedDate"=>$rows['postedDate']
                                   );
                    $i++;
                 }


               
            
                  echo json_encode($result); //returns student's current resource details
                      mysql_close($conn);
        }
        else{
             echo "null";
        }
}

//-------------------------------------------------------
//to get feedback,suggestion from user
// Author :Cheena Garg
// Created on : (27.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "feedback"){
        if($_REQUEST['authkey']!=""){  
                   $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
             if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
            
             $type =$_REQUEST['type'] ;
             $area =$_REQUEST['area'] ;
             $criticalityLevel =$_REQUEST['criticalitylevel'] ;
             $description =$_REQUEST['description'] ;
            //stores feedback details to database
            $query="INSERT
                     INTO mobile_feedback(userId,feedbackType,feedbackArea,criticalityLevel,description)
                 VALUES($userId,'$type','$area','$criticalityLevel','$description')
                ";
         
            $res=mysql_query($query);
            echo "Thanks for the feedback !";
            mysql_close($conn);
        }
        else{
            echo "null";
        }
}
//----------------------------------
// to get complete info of Student
// Auhtor : Raghav salotra
// created on : (22/11/2011)
//----------------------------------

else if($_REQUEST['fn']== "completeinfo"){    
        if($_REQUEST['authkey']!=""){  
                   $res = mysql_query("SELECT * FROM mobile_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
             if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
                           $res1 = mysql_query("SELECT * FROM student s, class c where s.classId=c.classId AND s.userId='".$userId."'");
                           if($rows=mysql_fetch_array($res1)){
                        $instituteId = $rows['instituteId'];    
                        $studentId = $rows['studentId'];    
                        $classId = $rows['classId'];
                        $sessionId=$rows['sessionId'];
                    }

                       
                        // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
                        $query = "SELECT
								s.*,
                                sa.*,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
								c.countryName,
								c.nationalityName,
								st.stateName,
								q.quotaName,
								u.userName,
								uf.userName as fatherUserName,
								um.userName as motherUserName,
								ug.userName as guardianUserName,
								un.universityName,
								deg.degreeName,
								br.branchName,
								bt.batchName,
								sp.periodName,
								icardNumber,
								if(managementCategory=1, managementReference,''),
								hs.hostelName,
								hr.roomName,
								bsr.routeName,
								bs.stopName,
								cc.countryName as corrCountry,
								cs.stateName as corrState,
								cct.cityName as corrCity,
                                cc.countryId as corrCountryId,
                                cs.stateId as corrStateId,
                                cct.cityId as corrCityId,
								permcn.countryName as permCountry,
								permst.stateName as permState,
								permct.cityName as permCity,
                                permcn.countryId as permCountryId,
                                permst.stateId as permStateId,
                                permct.cityId as permCityId,
								fatcn.countryName as fatherCountry,
								fatst.stateName as fatherState,
								fatct.cityName as fatherCity,
								motcn.countryName as motherCountry,
								motst.stateName as motherState,
								motct.cityName as motherCity,
								gudcn.countryName as guardCountry,
								gudst.stateName as guardState,
								gudct.CityName as guardCity,
                                hst.possibleDateOfCheckOut,
                                s.highestEducationalQualification,
                                IFNULL(spf.programFeeId,'') AS programFeeId,IF(IFNULL(s.studentPhoto,'')='',0,CONCAT('".STORAGE_HTTP_PATH."/Images/Student/',s.studentPhoto)) AS 	 							studentPhoto
					FROM		class cl,
								degree deg,
								branch br,
								batch bt,
								study_period sp,
								university un,
								student s
                    LEFT JOIN student_program_fee spf ON spf.programFeeId=s.programFeeId
					LEFT JOIN	countries c ON ( s.nationalityId = c.countryId )
					LEFT JOIN	states st ON ( s.domicileId = st.stateId )
					LEFT JOIN	quota q ON ( s.quotaId = q.quotaId )
					LEFT JOIN	user u ON ( s.userId = u.userId )
					LEFT JOIN	countries cc ON ( s.corrCountryId=cc.countryId )
					LEFT JOIN	states cs ON ( s.corrStateId=cs.stateId )
					LEFT JOIN	city cct ON ( s.corrCityId = cct.cityId )
					LEFT JOIN	countries permcn ON ( s.permCountryId = permcn.countryId )
					LEFT JOIN	states permst ON ( s.permStateId = permst.stateId )
					LEFT JOIN	city permct ON ( s.permCityId=permct.cityId )
					LEFT JOIN	countries fatcn ON ( s.fatherCountryId=fatcn.countryId )
					LEFT JOIN	states fatst ON ( s.fatherStateId=fatst.stateId )
					LEFT JOIN	city fatct ON ( s.fatherCityId=fatct.cityId )
					LEFT JOIN	countries motcn ON ( s.motherCountryId=motcn.countryId )
					LEFT JOIN	states motst ON ( s.motherStateId=motst.stateId )
					LEFT JOIN	city motct ON ( s.motherCityId=motct.cityId )
					LEFT JOIN	countries gudcn ON ( s.guardianCountryId=gudcn.countryId )
					LEFT JOIN	states gudst ON ( s.guardianStateId=gudst.stateId )
					LEFT JOIN	city gudct ON ( s.guardianCityId=gudct.cityId )
					LEFT JOIN	user uf ON ( s.fatherUserId=uf.userId )
					LEFT JOIN	user um ON ( s.motherUserId=um.userId )
					LEFT JOIN	user ug ON ( s.guardianUserId=ug.userId )
					LEFT JOIN	hostel hs ON ( s.hostelId=hs.hostelId )
					LEFT JOIN	hostel_room hr ON (s.hostelRoomId = hr.hostelRoomId)
                    LEFT JOIN   hostel_students hst ON (s.studentId = hst.studentId)
					LEFT JOIN	bus_route bsr ON (s.busRouteId=bsr.busRouteId)
					LEFT JOIN	bus_stop bs ON (s.busStopId=bs.busStopId)
                    LEFT JOIN   student_ailment sa ON s.studentId = sa.studentId

					WHERE		s.studentId=$studentId
					AND			s.classId=cl.classId
					AND			cl.universityId=un.universityId
					AND			cl.degreeId=deg.degreeId
					AND			cl.branchId=br.branchId
					AND			cl.batchId=bt.batchId
					AND			cl.studyPeriodId=sp.studyPeriodId
					AND			cl.instituteId=$instituteId
					AND			cl.sessionId=$sessionId    
                                   ";

                 $query1 =mysql_query($query);

                $i=0;
                  while($rows =mysql_fetch_array($query1)){ 
                      $result[$i]=array( "studentName"=>$rows['studentName'],
                                "branchName"=>$rows['branchName'],    
                                "batchName"=>$rows['batchName'],
                                "periodName"=>$rows['periodName'],
                                "hostelName"=>$rows['hostelName'],
                                "roomName"=>$rows['roomName'],
                                "routeName"=>$rows['routeName'],
                                "stopName"=>$rows['stopName'],
                                "fathername"=>$rows['fatherName'],
                                "mothername"=>$rows['motherName'],
                                "dateofbirth"=>$rows['dateOfBirth'],
                                "dateofjoining"=>$rows['dateOfAdmission'],
                                "permaddress"=>$rows['permAddress1'],
                                "corraddress"=>$rows['corrAddress1'],
                                "studentemail"=>$rows['studentEmail'],
                                "studentmobileno"=>$rows['studentMobileNo'],
                                "studentphoto"=>$rows['studentPhoto']
                                
                                   );
                    $i++;
                 }


                
                            
                
            
                  echo json_encode($result); //returns student's current Complete info
                      mysql_close($conn);
        }
        else{
             echo "null";
        }
}



//-------------------------------------------------------
//to get institute logo
// Author :Cheena Garg
// Created on : (27.07.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "logo"){
    $url = STORAGE_HTTP_PATH."/Images/logo.gif?xx=".rand(0,1000);  
    echo $url;
}
?>
