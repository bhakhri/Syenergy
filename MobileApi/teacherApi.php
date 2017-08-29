<?php

set_time_limit(0); //No time limit imposed for execution

//The root path of the front end site.
$siteAddress = dirname(__FILE__);    
$siteAddress = substr($siteAddress,0,strlen(str_replace("MobileApi","",$siteAddress))-1);  

$url = $siteAddress.'/Library/common.inc.php';
require_once("$url"); //includes and evaluates the specified file during the execution of the script


//connection to database
$conn =mysqli_connect(DB_HOST,DB_USER,DB_PASS) or die('Could not connect:' . mysqli_error($conn));
mysqli_select_db($conn,DB_NAME) or die(mysqli_error($conn));


//-------------------------------------------------------
//Login Api
//Purpose:Login and generate authorisation key
//Authorisation Key is a unique LoginKey which will be used as a parameter in all further calls 
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

if($_REQUEST['fn']== "login") {

    if($_REQUEST['uname']!="" && $_REQUEST['pw']!=""){ 
        
		$fieldName = " i.instituteId, i.instituteName, i.instituteCode, i.instituteLogo, u.userId, u.userName ";
        $userName = htmlentities(trim($_REQUEST['uname']));
        $userPassword = htmlentities(trim($_REQUEST['pw']));
        $condition = " u.instituteId = i.instituteId AND u.userName='".$userName."' and u.userPassword='".$userPassword."'";
        $res = mysqli_query($conn,"SELECT $fieldName FROM `user` u, institute i WHERE $condition");
        $countres=mysqli_num_rows($res);
        if($countres>0){
            if($row=mysqli_fetch_array($res)){ 
                $userId = $row['userId'];
             }
           
			$count=1;
            $auth_key="";
            while($count==1) {  //to generate unique Loginkey(authKey) each time   
                $auth_key= rand();
                $key= mysqli_query($conn,"SELECT authKey FROM nfc_user where authKey='".md5($auth_key)."'");
               
	        $count=mysqli_num_rows($key);
	     
            }
		

            if($count==0) {
   		
               $query=mysqli_query($conn,"SELECT * FROM nfc_user where userId='".$userId."'");
                $countrows=mysqli_num_rows($query);

	
                if($countrows>0){ //to delete authkey from table if already exists for logged in user

                    mysqli_query($conn,"DELETE FROM nfc_user where userId='".$userId."'");
                }

                   mysqli_query($conn,"INSERT INTO nfc_user(userId,authKey) VALUES(".$userId.",md5('".$auth_key."'))");
	
            } 
            else{
  
                echo mysqli_error($conn); 
             }
           
            $akey=mysqli_query($conn,"SELECT e.employeeName AS teacherName,mu.authKey  
                     FROM 
                           `employee`e ,nfc_user mu

                    WHERE  
                            e.userId=mu.userId AND mu.userId=$userId AND  e.userId=$userId
                            
                           ");

            $i=0;
            if($rows =mysqli_fetch_array($akey)){ //extract data from resultant array , returning associative array( unique authorisation key & student name)
                $output[$i]= array("authkey"=>$rows['authKey'],
                "firstname"=>$rows['teacherName']
                );
                $inaa++;       
            }

            echo json_encode($output);
            mysqli_close($conn);

        } 
        else echo "Invalid username or password"; 

    }
        else echo "Enter username and password"; // if username and password is null

}


//-------------------------------------------------------
//CHECKLOGIN API-to check if user is logged in
//Returns 1 if user is logged in else returns 0
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "checklogin"){   
        if($_REQUEST['authkey']!=""){ 
            $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysqli_num_rows($res); 
                
                if($count>0){    
                    $out= array("checklogin"=>1);
                    echo json_encode($out);  //returns 1 if user is logged in( by checking if authkey exists in database)
                }

                else {
                    $output= array("checklogin"=>0);
                    echo json_encode($output); //else returns 0
                }  

                mysqli_close($conn);
        }
        else{
             echo "ERROR : Enter authorisation key"; 
        }
}


//-------------------------------------------------------
//LOGOUT API
//deletes authkey from mobile_user table
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "logout"){    
        if($_REQUEST['authkey']!=""){
            $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysqli_num_rows($res);
            if($count>0){    //deletes authorisation key from the table 
                $out=mysqli_query($conn,"DELETE FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
                echo json_encode("logged out successfully");
            }

            mysqli_close($conn);
        }
       else{
             echo "ERROR : Enter authorisation key";
             }
}



//-------------------------------------------------------
//Current timetable in grid format
//to get timetable details i.e coursecode,roomno,periodno,teacher name
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "timetable"){    

        if($_REQUEST['authkey']!=""){  
                   $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
                        $count= mysqli_num_rows($res);

             if($count>0){  
                if($row=mysqli_fetch_array($res)) 
                       $userId = $row['userId'];
            }
            $res1 = mysqli_query($conn,"SELECT employeeId FROM employee WHERE userId = '$userId'");
            if($rows=mysqli_fetch_array($res1)){
               $employeeId = $rows['employeeId'];   
			   $startDate = date('Y-m-d');
   			   $endDate = date('Y-m-d');
             }

             // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
             $query = "SELECT
		DISTINCT -1 AS adjustmentType, p.periodSlotId, tt.periodId, tt.daysOfWeek, p.periodNumber, 
		CONCAT( SUBSTRING(p.startTime,1,5), p.startAmPm,'  To  ', SUBSTRING(endTime,1,5), endAmPm ) AS pTime, gr.groupShort,
		SUBSTRING_INDEX( cl.className, '-', -3 ) AS className, gr.classId, sub.subjectName, 
		sub.subjectCode, cl.instituteId, cl.sessionId, r.roomName, 
		CONCAT( c.abbreviation, '-', b.abbreviation, '-', r.roomAbbreviation ) AS roomAbbreviation, 
		emp.employeeName, gr.groupId, sub.subjectId, cl.classId, sub.hasMarks, sub.hasAttendance, 
		(SELECT 
				DISTINCT e.employeeName
		 FROM 
				time_table_adjustment tta
				LEFT JOIN employee e ON e.employeeId = tta.employeeId
		 WHERE 
				tta.timeTableLabelId = ttl.timeTableLabelId
				AND tta.roomId = tt.roomId
				AND tta.oldEmployeeId = emp.employeeId
				AND tta.groupId = tt.groupId
				AND tta.daysOfWeek = tt.daysOfWeek
				AND tta.periodSlotId = tt.periodSlotId
				AND tta.periodId = tt.periodId
				AND tta.subjectId = tt.subjectId
				AND tta.timeTableLabelId = tt.timeTableLabelId
				AND tta.isActive =1) AS adjustEmpName, tt.fromDate
        FROM 
		        period p, `group` gr, `subject` sub, employee emp, room r, class cl,
		        time_table_labels ttl, time_table tt, block b, building c,  `session` ses
        WHERE 
		        ses.sessionId = cl.sessionId
                AND ses.active= 1
		        AND tt.periodId = p.periodId
		        AND tt.groupId = gr.groupId
		        AND gr.classId = cl.classId
		        AND tt.subjectId = sub.subjectId
		        AND tt.employeeId = emp.employeeId
		        AND tt.roomId = r.roomId
		        AND tt.toDate IS NULL
		        AND tt.timeTableLabelId = ttl.timeTableLabelId
		        AND r.blockId = b.blockId
		        AND b.buildingId = c.buildingId
		        AND tt.timeTableId NOT IN 
					        (SELECT 
							        tta.timeTableId 
					         FROM 
							        time_table_adjustment tta
					          WHERE  
							         ( (tta.fromDate BETWEEN '$startDate' AND '$endDate') OR (tta.toDate BETWEEN '$startDate' AND '$endDate')
								           OR 
								           (tta.fromDate <= '$startDate' AND tt.toDate >= '$endDate'))
		        AND tta.timeTableLabelId = ttl.timeTableLabelId
		        AND tta.isActive =1
)
AND tt.employeeId = $employeeId
AND cl.isActive = '1'
UNION ALL 
SELECT 
		DISTINCT tt.adjustmentType AS adjustmentType, p.periodSlotId, tt.periodId, tt.daysOfWeek, 
		p.periodNumber, CONCAT( SUBSTRING(p.startTime,1,5), p.startAmPm,'  To  ', SUBSTRING(endTime,1,5), endAmPm ) AS pTime, gr.groupShort,
		SUBSTRING_INDEX( cl.className, '-', -3 ) AS className, gr.classId, sub.subjectName, 
		sub.subjectCode, cl.instituteId, cl.sessionId, r.roomName, r.roomAbbreviation, 
		emp.employeeName, gr.groupId, sub.subjectId, cl.classId, sub.hasMarks, 
		sub.hasAttendance, '' AS adjustEmpName, tt.fromDate
FROM 
		time_table_adjustment tt, period p, `group` gr, `subject` sub, employee emp, room r, class cl, time_table_labels ttl, `session` ses
WHERE           ses.sessionId = cl.sessionId
                AND ses.active = 1
		AND tt.periodId = p.periodId
		AND tt.groupId = gr.groupId
		AND gr.classId = cl.classId
		AND tt.subjectId = sub.subjectId
		AND tt.employeeId = emp.employeeId
		AND tt.roomId = r.roomId
		AND tt.timeTableLabelId = ttl.timeTableLabelId
		AND tt.employeeId = $employeeId
		AND cl.isActive = '1'
		AND ( (tt.fromDate BETWEEN '$startDate' AND '$endDate') OR (tt.toDate BETWEEN '$startDate' AND '$endDate')
			   OR 
			   (tt.fromDate <= '$startDate' AND tt.toDate >= '$endDate'))
		AND tt.isActive =1
ORDER BY 
		periodSlotId, daysOfWeek, LENGTH( periodNumber ) +0, periodNumber, groupShort, subjectCode";
		
                 $query1 =mysqli_query($conn,$query);
                 $countTimeTable=mysqli_num_rows($query1);
        if($countTimeTable>0){
                 $uniqueClassId = array();
                 $uniquePeriodId      = array();
                 $uniqueSubjectId     = array();
                 $uniqueGroupId = array();
                $i=0;
                while($rows =mysqli_fetch_array($query1)){ 
				  $result[$i]=array("day"=>$rows['daysOfWeek'],
				  					
									"coursecode"=>$rows['subjectCode'],    
									"roomname"=>$rows['roomAbbreviation'],
									"subjectName"=>$rows['subjectName'],
									"groupShort"=>$rows['groupShort'],
									"className"=>$rows['className'],
									"periodno"=>$rows['periodNumber'],
                                    "classId"=>$rows['classId'],
									"periodId"=>$rows['periodId'],
									"groupId"=>$rows['groupId'],
									"subjectId"=>$rows['subjectId'],
									"periodSlotId"=>$rows['periodSlotId'],
                                    "pTime"=>$rows['pTime'] );
                   // Unique Class
                   $find='0';
                   $str = $rows['classId']."~".$rows['className']; 
                   for($j=0;$j<count($uniqueClassId);$j++) {
                     if($uniqueClassId[$j]==$str) {
                       $find='1';
                       break;
                     }
                   }
                   if($find=='0' && $str!='') {
                     $uniqueClassId[] = $str;  
                   }
                   
                   // Unique Subject
                   $find='0';
                   $str = $rows['subjectId']."~".$rows['subjectCode']; 
                   for($j=0;$j<count($uniqueSubjectId);$j++) {
                     if($uniqueSubjectId[$j]==$str) {
                       $find='1';
                       break;
                     }
                   }
                    if($find=='0' && $str!='') {   
                     $uniqueSubjectId[] = $str;  
                   }
                   
                   // Unique Period
                   $find='0';
                   $str = $rows['periodId']."~".$rows['periodNumber']; 
                   for($j=0;$j<count($uniquePeriodId);$j++) {
                     if($uniquePeriodId[$j]==$str) {
                       $find='1';
                       break;
                     }
                   }
                    if($find=='0' && $str!='') {   
                     $uniquePeriodId[] = $str;  
                   }
                   
                   // Unique Group
                   $find='0';
                   $str = $rows['groupId']."~".$rows['groupShort']; 
                   for($j=0;$j<count($uniqueGroupId);$j++) {
                     if($uniqueGroupId[$j]==$str) {
                       $find='1';
                       break;
                     }
                   }
                    if($find=='0' && $str!='') {   
                     $uniqueGroupId[] = $str;  
                   }
				  $i++;
              }
      
             
             $resultData = array('uniqueGroupId'=>$uniqueGroupId, 'uniquePeriodId'=>$uniquePeriodId, 
                                 'uniqueSubjectId'=>$uniqueSubjectId, 'uniqueClassId'=>$uniqueClassId, 
                                 'result' => $result);
             echo json_encode($resultData); //returns student's current resource details
             mysqli_close($conn);
        }
    
    else{
          echo "Time table not found";
          }
   }
        else{
             echo "ERROR : Enter authorisation key";
        }
}


//-------------------------------------------------------
//Fetch students 
//to get timetable details i.e coursecode,roomno,periodno,teacher name
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "studentlist"){  
 
        if( $_REQUEST['authkey']!="") { 
           if($_REQUEST['classId']!=""){
             if($_REQUEST['subjectId']!=""){
                if($_REQUEST['groupId']!=""){

		           $subjectId = htmlentities(trim($_REQUEST['subjectId']));
                           $groupId = htmlentities(trim($_REQUEST['groupId'])); 
  		           $classId = htmlentities(trim($_REQUEST['classId']));
 
                   $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
                   $count= mysqli_num_rows($res);
                   if($count>0){  
                     if($row=mysqli_fetch_array($res)) 
                        $userId = $row['userId'];
                        }
                        $res1 = mysqli_query($conn,"SELECT employeeId FROM employee WHERE userId = '$userId'");
                        if($rows=mysqli_fetch_array($res1)){
                          $employeeId = $rows['employeeId'];    
                        }

                        // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
                        $query = "SELECT DISTINCT 
                                        IFNULL(studentPhoto,'') AS studentPhoto, 
                                        s.studentId, CONCAT( IFNULL( s.firstName, '' ) , ' ', IFNULL( s.lastName, '' ) ) AS studentName, 
                                        IF(IFNULL(s.rollNo,'') = '', '---', s.rollNo ) AS rollNo, 
                                        IF(IFNULL(s.universityRollNo,'' ) = '', '---', s.universityRollNo ) AS universityRollNo
                                    FROM 
                                          student s, class c, `group` g, subject_to_class sc, degree deg, 
                                          branch br, batch ba, student_groups sg, time_table_classes ttc
                                    WHERE 
                                        s.studentId = sg.studentId
                                        AND sg.classId = c.classId
                                        AND sg.groupId = g.groupId
                                        AND sc.classId = c.classId
                                        AND c.degreeId = deg.degreeId
                                        AND c.branchId = br.branchId
                                        AND c.batchId = ba.batchId
                                        AND c.classId = ttc.classId
                                        AND sc.subjectId =$subjectId
                                        AND g.groupId =$groupId
                                        AND c.classId =$classId
                                    GROUP BY 
                                        s.studentId
                                    ORDER BY 
                                        LENGTH( rollNo ) +0, rollNo ASC";
                        $query1 =mysqli_query($conn,$query);
                  
                        $i=0;
                        while($rows =mysqli_fetch_array($query1)) { 
                            $studentPhoto="";
                            if(trim($rows['studentPhoto'])!='') {
                              $studentPhoto = $rows['studentPhoto'];   
                            }
                            $result[$i]=array("studentId"=>$rows['studentId'],
				                              "studentName"=>$rows['studentName'],
                                              "rollNo"=>$rows['rollNo'],    
                                              "universityRollNo"=>$rows['universityRollNo'],
                                              "studentPhoto" => $studentPhoto
                                             );
                            $i++;
                        }
                          
                        echo json_encode($result); //returns student's current resource details
                        mysqli_close($conn);
                   } 
                   else {
                     echo "ERROR : Enter groupId";
                   }
                 }
                 else{
                   echo "ERROR : Enter subjectId";
                 }
             }
             else {  
                echo "ERROR : Enter classId ";
             }
        }
        else {
             echo "ERROR : Enter authorisation key";
        }
}




/*//-------------------------------------------------------
//Fetch students 
//to get timetable details i.e coursecode,roomno,periodno,teacher name
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

else if($_REQUEST['fn']== "studentlist"){  


        if( $_REQUEST['authkey']!="" && $_REQUEST['subjectId']!=""  && $_REQUEST['classId']!=""  && $_REQUEST['groupId']!="" ){ 

		$subjectId = htmlentities(trim($_REQUEST['subjectId']));
        	$groupId = htmlentities(trim($_REQUEST['groupId'])); 
		$classId = htmlentities(trim($_REQUEST['classId']));
		$records=$_REQUEST['page'];

		if($records=='') {
		  $records='5';	
		}
                   $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
                        $count= mysqli_num_rows($res);
         if($count>0){  
                if($row=mysqli_fetch_array($res)) 
                       $userId = $row['userId'];
            }
                           $res1 = mysqli_query($conn,"SELECT employeeId FROM employee WHERE userId = '$userId'");


                         if($rows=mysqli_fetch_array($res1))
{
                        
                        $employeeId = $rows['employeeId'];    
                       

                    }
 $limit= 'LIMIT  0,'.$records;
                       
                        // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
                        $query = "SELECT DISTINCT s.studentId, CONCAT( IFNULL( s.firstName, '' ) , ' ', IFNULL( s.lastName, '' ) ) AS studentName, IF( IFNULL( s.rollNo, '' ) = '', '---', s.rollNo ) AS rollNo, IF( IFNULL( s.universityRollNo, '' ) = '', '---', s.universityRollNo ) AS universityRollNo
FROM student s, class c, `group` g, subject_to_class sc, degree deg, branch br, batch ba, student_groups sg, time_table_classes ttc
WHERE s.studentId = sg.studentId
AND sg.classId = c.classId
AND sg.groupId = g.groupId
AND sc.classId = c.classId
AND c.degreeId = deg.degreeId
AND c.branchId = br.branchId
AND c.batchId = ba.batchId
AND c.classId = ttc.classId
AND sc.subjectId =$subjectId
AND g.groupId =$groupId
AND c.classId =$classId
GROUP BY s.studentId
ORDER BY LENGTH( rollNo ) +0, rollNo ASC $limit";
 $query1 =mysqli_query($conn,$query);


                $i=0;
                  while($rows =mysqli_fetch_array($query1))
{ 
                      $result[$i]=array("studentId"=>$rows['studentId'],
				 "studentName"=>$rows['studentName'],
                                "rollNo"=>$rows['rollNo'],    
                             
                          
                                "universityRollNo"=>$rows['universityRollNo'],
				
                               
                                   );
                    $i++;
                 }


               
            
                  echo json_encode($result); //returns student's current resource details
                      mysqli_close($conn);
        }
        else{
             echo "null";
        }
}
*/
//-------------------------------------------------------
//Display student's attendance
//to get timetable details i.e coursecode,roomno,periodno,teacher name
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "displayattendance"){ 

//&&   && $_REQUEST['classId']!=""  &&  
        if( $_REQUEST['authkey']!="" ){
        	if($_REQUEST['classId']!=""){
        	   if($_REQUEST['subjectId']!=""){
        	   	  if($_REQUEST['groupId']!="" ){



		$subjectId= htmlentities(trim($_REQUEST['subjectId']));
        $groupId = htmlentities(trim($_REQUEST['groupId'])); 
		$classId = htmlentities(trim($_REQUEST['classId']));
		$startdate = htmlentities(trim($_REQUEST['startdate']));
		if($startdate==''){
		  $startdate=date('Y-m-d');
		}
        
        $query = "SELECT 
                        c.classId, c.className, c.instituteId
                  FROM 
                        class c
                  WHERE 
                        c.classId = '$classId' "; 
         $query1 =mysqli_query($conn,$query);
         if($rows =mysqli_fetch_array($query1)){ 
           $instituteId=$rows['instituteId'];   
         }
         $attendanceTableName = "attendance".$instituteId;
		
		//yy-mm-dd
		$enddate=htmlentities(trim($_REQUEST['enddate']));
		if($enddate=='')
		{
		$enddate=date('Y-m-d');
		}
		
        
         
		
                  $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
                        $count= mysqli_num_rows($res);
                      
         if($count>0){  
                if($row=mysqli_fetch_array($res)) 
                      $userId = $row['userId'];   
                       
            }
           
                           $res1 = mysqli_query($conn,"SELECT employeeId FROM employee WHERE userId = '$userId'");

                         if($rows=mysqli_fetch_array($res1)){
                           $employeeId = $rows['employeeId'];    
                          }
  			
                 

                       
            // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
            $query = "SELECT
                         CONCAT(s.firstName,' ',s.lastName) AS studentName,s.studentId,
                         IF(s.rollNo IS NULL OR s.rollNo='','---',s.rollNo) AS rollNo,
                         IF(s.universityRollNo IS NULL OR s.universityRollNo='','---',s.universityRollNo) AS universityRollNo,
                    su.subjectName,
                    su.subjectCode,
                    ROUND(FORMAT( SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) ) , 1 ),0) AS attended,
                    SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) ) AS delivered,
                    ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )/SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) )*100,2)
                    AS percentage,
                    IF(ROUND(SUM( IF( att.isMemberOfClass =0, 0, IF( att.attendanceType =2, (ac.attendanceCodePercentage /100), att.lectureAttended ) ) )/SUM( IF( att.isMemberOfClass =0, 0, att.lectureDelivered ) )*100,2) < 75,-1,0)
                    AS shortAttendance
            FROM
                   student s
                   LEFT JOIN $attendanceTableName att ON att.studentId = s.studentId
                   INNER JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId)
                   INNER JOIN `subject` su ON su.subjectId = att.subjectId
                   INNER JOIN `group` grp ON grp.groupId IN ($groupId)
                   INNER JOIN class c ON c.classId = grp.classId
                   INNER JOIN time_table_classes ttc ON ttc.classId=c.classId
                        AND su.subjectId='$subjectId' AND att.groupId='$groupId' AND c.classId='$classId' 
                        AND (att.fromDate <= '$startdate' AND att.toDate <= '$enddate')
                        AND att.employeeId='$employeeId'  
           GROUP BY 
                  att.subjectId, att.studentId
           ORDER BY  
                  LENGTH(rollNo)+0,rollNo ASC"; 
                 
           $query1 =mysqli_query($conn,$query);
           $countDisplayatt=mysqli_num_rows($query1);
        if($countDisplayatt>0){


                $i=0;
                  while($rows =mysqli_fetch_array($query1)){ 
                      $result[$i]=array("studentName"=>$rows['studentName'],
                      			"studentId"=>$rows['studentId'],
				                "universityRollNo"=>$rows['universityRollNo'],
                                "rollNo"=>$rows['rollNo'],    
                                "subjectName"=>$rows['subjectName'],
				                "subjectCode"=>$rows['subjectCode'],
				                "attended"=>$rows['attended'],
				                "delivered"=>$rows['delivered'],
				                "percentage"=>$rows['percentage'],
				                "shortAttendance"=>$rows['shortAttendance'],
				
                               
                                  );
                    $i++;
                 }


               
            
                  echo json_encode($result); //returns student's current resource details
                      mysqli_close($conn);
                      }
                       else{
	                         echo "ERROR : No data found";
                           }
        
              }   
                 else{
        	       echo "ERROR : Enter groupId";
                     }
             }
            else{
              echo "ERROR : Enter subjectId";
                }
         }
          else{
		  	echo "ERROR : Enter classId ";
		     }
          }
        else{
             echo "ERROR : Enter authorisation key";
        }
}

//-------------------------------------------------------
//Display student's attendance
//to mark the attendance of the student
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
else if($_REQUEST['fn']== "markattendance"){  

 	      $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
          $count= mysqli_num_rows($res);
         
          if($count>0){  
           if($row=mysqli_fetch_array($res)) 
              $userId = $row['userId'];
          }
          
           $res1 = mysqli_query($conn,"SELECT employeeId FROM employee WHERE userId = '$userId'");
           if($rows=mysqli_fetch_array($res1)){
	         $employeeId = $rows['employeeId'];    
           }

 	   $checkout=0;
           $check=1;//by default check 1 so that if student in class check will turned to 0
	   //$date=date('Y-m-d');
            if( $_REQUEST['authkey']!="" && $_REQUEST['subjectId']!=""  && $_REQUEST['classId']!=""  && $_REQUEST['groupId']!="" && $_REQUEST['studentId']!=""&& $_REQUEST['periodId']!=""  ){ 
                $classId1 = htmlentities(trim($_REQUEST['classId']));
                $periodId1 = htmlentities(trim($_REQUEST['periodId']));
                $subjectId1 = htmlentities(trim($_REQUEST['subjectId']));
                $groupId1 = htmlentities(trim($_REQUEST['groupId'])); 
                $date= htmlentities(trim($_REQUEST['date']));
                $studentId = htmlentities(trim($_REQUEST['studentId']));

                 $query = "SELECT 
                        c.classId, c.className, c.instituteId
                  FROM 
                        class c
                  WHERE 
                        c.classId = '$classId1' "; 
                        
                 $query1 =mysqli_query($conn,$query);
                 if($rows =mysqli_fetch_array($query1)){ 
                   $instituteId=$rows['instituteId'];   
                 }
                 $attendanceTableName = "attendance".$instituteId;
                
               if($date==''){
		         echo "null";
                         die;
		       }
		$query = "SELECT DISTINCT s.studentId, CONCAT( IFNULL( s.firstName, '' ) , ' ', IFNULL( s.lastName, '' ) ) AS studentName, IF( IFNULL( s.rollNo, '' ) = '', '---', s.rollNo ) AS rollNo, IF( IFNULL( s.universityRollNo, '' ) = '', '---', s.universityRollNo ) AS universityRollNo
FROM student s, class c, `group` g, subject_to_class sc, degree deg, branch br, batch ba, student_groups sg, time_table_classes ttc
WHERE s.studentId = sg.studentId
AND sg.classId = c.classId
AND sg.groupId = g.groupId
AND sc.classId = c.classId
AND c.degreeId = deg.degreeId
AND c.branchId = br.branchId
AND c.batchId = ba.batchId
AND c.classId = ttc.classId
AND sc.subjectId =$subjectId1
AND g.groupId =$groupId1
AND c.classId =$classId1 
GROUP BY s.studentId
ORDER BY LENGTH( rollNo ) +0, rollNo ASC"; 

 $query1 =mysqli_query($conn,$query);

		$i=0;
                  while($rows =mysqli_fetch_array($query1)){ 
                      $result[$i]=array("studentId"=>$rows['studentId'],
                                  );
                    $i++;
                   }
                  for($i=0;$i<count($result);$i++){
                    if($studentId==$result[$i]['studentId']){
                    $checkout=1;
                    }
                  }
                 if($checkout==1){
            $query="SELECT 
                           Count(attendanceId) AS COUNT 
                    FROM   $attendanceTableName 
                    WHERE  studentId=$studentId 
                    AND    fromDate='$date' 
                    AND    toDate='$date' 
                    AND    classId=$classId1 
                    AND    subjectId=$subjectId1 
                    AND    groupId=$groupId1";
            $query2 =mysqli_query($conn,$query);
           $i=0;
                  while($rows =mysqli_fetch_array($query2)){ 
                      $result1[$i]=array("COUNT"=>$rows['COUNT'],
                                  );               
                      $i++;
                   }
   
   
            if($result1[0]['COUNT']==0){
               for($i=0;$i<count($result);$i++){
		         $studentId=$result[$i]['studentId'];
                 $query="INSERT INTO $attendanceTableName 
                        (classId,groupId,studentId,subjectId,employeeId,attendanceType,attendanceCodeId,
                         periodId,fromDate,toDate,isMemberOfClass,lectureDelivered,lectureAttended,userId,topicsTaughtId)
                         VALUES  
                         ($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentId,$REQUEST_DATA[subjectId],
                          $employeeId,2,'2',$periodId1,'".$date."','".$date."',1,1,0,$userId,1)";
                 $query1 =mysqli_query($conn,$query);
               }
             }
         
            for($i=0;$i<count($result);$i++){
             if($studentId==$result[$i]['studentId']){
                    $periodId = htmlentities(trim($_REQUEST['periodId']));
                    $studentId = htmlentities(trim($_REQUEST['studentId']));
                    $subjectId = htmlentities(trim($_REQUEST['subjectId']));
                    $groupId = htmlentities(trim($_REQUEST['groupId'])); 
                    $classId = htmlentities(trim($_REQUEST['classId']));
                    //$startdate = htmlentities(trim($_REQUEST['startdate']));//yy-mm-dd
                    //$enddate= htmlentities(trim($_REQUEST['enddate']));
                    // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
                   $query = "UPDATE 
                                    $attendanceTableName 
                             SET    attendanceCodeId=1  
                             WHERE  attendanceCodeId=2 
                             AND    classId=$REQUEST_DATA[classId] 
                             AND    groupId=$REQUEST_DATA[groupId] 
                             AND    studentId=$studentId 
                             AND    subjectId=$REQUEST_DATA[subjectId] 
                             AND    employeeId=$employeeId 
                             AND    fromDate='$date' 
                             AND    toDate='$date' 
                             AND    userId=$userId
                                                      ";
               //check date check comp
                    $query1 =mysqli_query($conn,$query);
                    echo "Attendance Marked.";
		    $check=0;
              }
             } 
                     
                }     
                //if the student is not in class then display message 
		if($check==1 || $checkout==0){
                  echo "Student Not in Class";
                  die;   
		}            
                 // echo json_encode($result); //returns student's current resource details
                 //   mysqli_close($conn);
        }
        else{
             echo "null";
        }

}
else if($_REQUEST['fn']== "updateattendance"){ 
 $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
          $count= mysqli_num_rows($res);
         
          if($count>0){  
           if($row=mysqli_fetch_array($res)) 
              $userId = $row['userId'];
          }
          
           $res1 = mysqli_query($conn,"SELECT employeeId FROM employee WHERE userId = '$userId'");
           if($rows=mysqli_fetch_array($res1)){
	      $employeeId = $rows['employeeId'];    
           }
 	   $checkout=0;
           $check=1;//by default check 1 so that if student in class check will turned to 0
		
	   //$date=date('Y-m-d');
            if( $_REQUEST['authkey']!="" && $_REQUEST['subjectId']!=""  && $_REQUEST['classId']!=""  && $_REQUEST['groupId']!="" && $_REQUEST['studentId']!=""&& $_REQUEST['periodId']!=""  ){ 
		$classId1 = htmlentities(trim($_REQUEST['classId']));
		$periodId1 = htmlentities(trim($_REQUEST['periodId']));
		$subjectId1 = htmlentities(trim($_REQUEST['subjectId']));
        	$groupId1 = htmlentities(trim($_REQUEST['groupId']));
		$date = htmlentities(trim($_REQUEST['date'])); 
		$studentId = htmlentities(trim($_REQUEST['studentId']));
		if($date==''){
		  echo "null";
                  die;
		    }                        
            
             $query = "SELECT 
                        c.classId, c.className, c.instituteId
                  FROM 
                        class c
                  WHERE 
                        c.classId = '$classId1' "; 
                        
                 $query1 =mysqli_query($conn,$query);
                 if($rows =mysqli_fetch_array($query1)){ 
                   $instituteId=$rows['instituteId'];   
                 }
                 $attendanceTableName = "attendance".$instituteId;
            
		$query = "SELECT DISTINCT s.studentId, CONCAT( IFNULL( s.firstName, '' ) , ' ', IFNULL( s.lastName, '' ) ) AS studentName, IF( IFNULL( s.rollNo, '' ) = '', '---', s.rollNo ) AS rollNo, IF( IFNULL( s.universityRollNo, '' ) = '', '---', s.universityRollNo ) AS universityRollNo
FROM student s, class c, `group` g, subject_to_class sc, degree deg, branch br, batch ba, student_groups sg, time_table_classes ttc
WHERE s.studentId = sg.studentId
AND sg.classId = c.classId
AND sg.groupId = g.groupId
AND sc.classId = c.classId
AND c.degreeId = deg.degreeId
AND c.branchId = br.branchId
AND c.batchId = ba.batchId
AND c.classId = ttc.classId
AND sc.subjectId =$subjectId1
AND g.groupId =$groupId1
AND c.classId =$classId1 
GROUP BY s.studentId
ORDER BY LENGTH( rollNo ) +0, rollNo ASC"; 

 $query1 =mysqli_query($conn,$query);

		$i=0;
                  while($rows =mysqli_fetch_array($query1)){ 
                      $result[$i]=array("studentId"=>$rows['studentId'],
                                  );
                    $i++;
                   }
                  for($i=0;$i<count($result);$i++){
                    if($studentId==$result[$i]['studentId']){
                    $checkout=1;
                    }
                  }
                 if($checkout==1){
            $query="SELECT 
                          COUNT(attendanceId) AS COUNT FROM $attendanceTableName 
                    WHERE 
                          studentId=$studentId AND fromDate='$date' AND toDate='$date' AND classId=$classId1 AND 
                          subjectId=$subjectId1 AND groupId=$groupId1";
            $query2 =mysqli_query($conn,$query);
           $i=0;
                  while($rows =mysqli_fetch_array($query2)){ 
                      $result1[$i]=array("COUNT"=>$rows['COUNT'],
                                  );               
                      $i++;
                   }
   
   
            if($result1[0]['COUNT']==0){
                echo "Student Not Found";
                  die;   
                }
             }
         
            for($i=0;$i<count($result);$i++){
             if($studentId==$result[$i]['studentId']){
	 	   $periodId = htmlentities(trim($_REQUEST['periodId']));
		   $studentId = htmlentities(trim($_REQUEST['studentId']));
		   $subjectId = htmlentities(trim($_REQUEST['subjectId']));
        	   $groupId = htmlentities(trim($_REQUEST['groupId'])); 
		   $classId = htmlentities(trim($_REQUEST['classId']));
		   $date = htmlentities(trim($_REQUEST['date'])); 
	           $attendanceCode = htmlentities(trim($_REQUEST['attendanceCode']));
                   if($date==''){
		      $date=date('Y-m-d');
		         }

		  if($attendanceCode=="present"){
                     $query = "UPDATE  
                                      $attendanceTableName 
                               SET    attendanceCodeId=1  
                               WHERE  attendanceCodeId=2 
                               AND    classId=$REQUEST_DATA[classId] 
                               AND    groupId=$REQUEST_DATA[groupId] 
                               AND    studentId=$studentId 
                               AND    subjectId=$REQUEST_DATA[subjectId] 
                               AND    employeeId=$employeeId 
                               AND    fromDate='$date' 
                               AND    toDate='$date' 
                               AND    userId=$userId
                                                       ";
                             }

                  elseif($attendanceCode=="absent"){

		   //$startdate = htmlentities(trim($_REQUEST['startdate']));//yy-mm-dd
		   //$enddate= htmlentities(trim($_REQUEST['enddate']));
                   // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
                   $query = "UPDATE  $attendanceTableName
                             SET     attendanceCodeId=2  
                             WHERE   attendanceCodeId=1 
                             AND     classId=$REQUEST_DATA[classId] 
                             AND     groupId=$REQUEST_DATA[groupId] 
                             AND     studentId=$studentId 
                             AND     subjectId=$REQUEST_DATA[subjectId]
                             AND     employeeId=$employeeId 
                             AND     fromDate='$date' 
                             AND     toDate='$date' 
                             AND     userId=$userId
                                                    ";

                                  }
                   else{
			echo "Wrong attendance code entered.";
			die;
			      }
                    //check date check comp
                    $query1 =mysqli_query($conn,$query);
                    echo "Attendance Modified.";
		    $check=0;
              }
             } 
                     
                }
else{
             echo "null";
        }     
                //if the student is not in class then display message 
		if($check==1 || $checkout==0){
                  echo "Student Not in Class";
                  die;   
		}            
                 // echo json_encode($result); //returns student's current resource details
                 //   mysqli_close($conn);
}
else if($_REQUEST['fn']== "attPresent" || $_REQUEST['fn']== "attAbsent"){  
    
          $res = mysqli_query($conn,"SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
          $count= mysqli_num_rows($res);
         
          if($count>0){  
            if($row=mysqli_fetch_array($res)) 
              $userId = $row['userId'];
          }
          
          $res1 = mysqli_query($conn,"SELECT employeeId FROM employee WHERE userId = '$userId'");
          if($rows=mysqli_fetch_array($res1)){
              $employeeId = $rows['employeeId'];    
          }

          $checkout=0;
          $check=1;//by default check 1 so that if student in class check will turned to 0
           //&&  &&  && && 
          if($_REQUEST['authkey']!="") {
          	 if($_REQUEST['classId']!=""){
          	 	if($_REQUEST['subjectId']!=""){
          	 	   if($_REQUEST['groupId']!=""){
          	 	      if($_REQUEST['periodId']!=""){
          	 	      	 if($_REQUEST['studentId']!=""){
                $classId1 = htmlentities(trim($_REQUEST['classId']));
                $periodId1 = htmlentities(trim($_REQUEST['periodId']));
                $subjectId1 = htmlentities(trim($_REQUEST['subjectId']));
                $groupId1 = htmlentities(trim($_REQUEST['groupId'])); 
                $date= htmlentities(trim($_REQUEST['date']));
                $studentId = htmlentities(trim($_REQUEST['studentId']));
                
                if($studentId=='') {
                  $studentId='0';  
                }
                
                $studentArray = array();
                if($studentId!='') {
                  $studentArray = explode(',',$studentId);  
                }

                if($date==''){
                  echo "Enter date";
                  die;
                }
                
                $query = "SELECT 
                                c.classId, c.className, c.instituteId
                          FROM 
                                class c
                          WHERE 
                                c.classId = '$classId1' "; 
                 $query1 =mysqli_query($conn,$query);
                 if($rows =mysqli_fetch_array($query1)){ 
                   $instituteId=$rows['instituteId'];   
                 }
                 
                  
                 $query = "SELECT 
                                attendanceCodeId, attendanceCodePercentage 
                          FROM 
                                attendance_code 
                          WHERE 
                                instituteId = '$instituteId' "; 
                 $query1 =mysqli_query($conn,$query);
                 
                 
                 while($rows =mysqli_fetch_array($query1)) { 
                    if($rows['attendanceCodePercentage'] == '0') {
                      $attendanceCodeAId = $rows['attendanceCodeId'];   
                    }
                    else {
                      $attendanceCodePId = $rows['attendanceCodeId'];     
                    }
                 }
                 $attendanceTableName = "attendance".$instituteId;
                 
                 if($_REQUEST['fn']== "attPresent") {
                   $attendanceCodeId = $attendanceCodePId;
                   $swapCodeId = $attendanceCodeAId;
                   if($attendanceCodeId=='') {
                     $attendanceCodeId='1';  
                   }
                   $swapCodeId = '2';
                 }
                 else {
                    $attendanceCodeId = $attendanceCodeAId;
                    $swapCodeId = $attendanceCodePId;
                    if($attendanceCodeId=='') {
                      $attendanceCodeId='0';  
                    }
                    $swapCodeId = '1';
                 }
                 
                 // Student Prensent Attendance action pick
                 $query="SELECT 
                                studentId, COUNT(attendanceId) AS cnt 
                         FROM   
                                $attendanceTableName 
                         WHERE  
                                fromDate='$date' 
                                AND toDate='$date' 
                                AND classId='$classId1' 
                                AND subjectId='$subjectId1' 
                                AND groupId='$groupId1'
                         GROUP BY
                                studentId";
                  $query2 =mysqli_query($conn,$query);   
                  
                  $result = array();
                  $i=0;
                  while($rows =mysqli_fetch_array($query2)){ 
                     $result[$i]['studentId'] = $rows['studentId'];
                     $result[$i]['cnt'] = $rows['cnt'];
                     $i++;
                  }
                  $msg = "Attendance Not Marked";  
                  
                  // Findout All Student List
                  $queryStudent = "SELECT 
                                            DISTINCT s.studentId, CONCAT( IFNULL( s.firstName, '' ) , ' ', IFNULL( s.lastName, '' ) ) AS studentName, 
                                            IF( IFNULL( s.rollNo, '' ) = '', '---', s.rollNo ) AS rollNo, 
                                            IF( IFNULL( s.universityRollNo, '' ) = '', '---', s.universityRollNo ) AS universityRollNo
                                    FROM 
                                            student s, class c, `group` g, subject_to_class sc, degree deg, branch br, 
                                            batch ba, student_groups sg, time_table_classes ttc
                                    WHERE 
                                            s.studentId = sg.studentId
                                            AND sg.classId = c.classId
                                            AND sg.groupId = g.groupId
                                            AND sc.classId = c.classId
                                            AND c.degreeId = deg.degreeId
                                            AND c.branchId = br.branchId
                                            AND c.batchId = ba.batchId
                                            AND c.classId = ttc.classId
                                            AND sc.subjectId =$subjectId1
                                            AND g.groupId =$groupId1
                                            AND c.classId =$classId1
                                            GROUP BY s.studentId
                                            ORDER BY LENGTH( rollNo ) +0, rollNo ASC";
                  $queryStudentList =mysqli_query($conn,$queryStudent);
                 
                  $allStudentArray = array(); 
                  while($rowsStudent =mysqli_fetch_array($queryStudentList)) { 
                    $allStudentId =  $rowsStudent['studentId']; 
                    $allStudentArray[$allStudentId]['resultStatus'] = 'N';
                  } 
                  
                 for($i=0;$i<count($studentArray);$i++){
                     $studentId=$studentArray[$i];
                     
                     // 1=> Insert, 2=> Update
                     $recordStatus='1';
                     for($j=0;$j<count($result);$j++) {
                        if($result[$j]['studentId']==$studentId) {
                           if($result[$j]['cnt'] >0) { 
                             $recordStatus='2';
                           }
                           break;  
                        }
                     }
                     
                     if($recordStatus=='1') {
                          $query="INSERT INTO $attendanceTableName
                                  (classId,groupId,studentId,subjectId,employeeId,attendanceType,attendanceCodeId,
                                   periodId,fromDate,toDate,isMemberOfClass,lectureDelivered,lectureAttended,userId,topicsTaughtId)
                                   VALUES  
                                  ($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentId,$REQUEST_DATA[subjectId],
                                   $employeeId,2,'$attendanceCodeId',$periodId1,'".$date."','".$date."',1,1,0,$userId,1)";
                          $allStudentArray[$studentId]['resultStatus'] = 'Y';          
                     }
                     else {   
                         $query = "UPDATE 
                                         $attendanceTableName 
                                   SET   
                                         attendanceCodeId = '$attendanceCodeId'
                                   WHERE  
                                         attendanceType=2 
                                         AND  classId= $REQUEST_DATA[classId] 
                                         AND  groupId= $REQUEST_DATA[groupId] 
                                         AND  studentId= $studentId 
                                         AND  subjectId=$REQUEST_DATA[subjectId] 
                                         AND  employeeId=$employeeId 
                                         AND  fromDate='$date' 
                                         AND  toDate='$date' 
                                         AND  userId=$userId";
                          $allStudentArray[$studentId]['resultStatus'] = 'Y';                          
                     }
                     $query1 =mysqli_query($conn,$query);       
                     $msg = "Attendance Marked.";
                  }
                  
                  
               
                  
                  // Balance Record
                  foreach($allStudentArray as $key=>$value) {    
                     if($value['resultStatus']=='N') {
                         $studentId=$key;
                         
                         // 1=> Insert, 2=> Update
                         $recordStatus='1';
                         for($j=0;$j<count($result);$j++) {
                            if($result[$j]['studentId']==$studentId) {
                               if($result[$j]['cnt'] >0) { 
                                 $recordStatus='2';
                               }
                               break;  
                            }
                         }
                         
                         if($recordStatus=='1') {
                              $query="INSERT INTO $attendanceTableName
                                      (classId,groupId,studentId,subjectId,employeeId,attendanceType,attendanceCodeId,
                                       periodId,fromDate,toDate,isMemberOfClass,lectureDelivered,lectureAttended,userId,topicsTaughtId)
                                       VALUES  
                                      ($REQUEST_DATA[classId],$REQUEST_DATA[groupId],$studentId,$REQUEST_DATA[subjectId],
                                       $employeeId,2,'$swapCodeId',$periodId1,'".$date."','".$date."',1,1,0,$userId,1)";
                              $allStudentArray[$studentId]['resultStatus'] = 'Y';          
                         }
                         else {   
                             /*
                             $query = "UPDATE 
                                             $attendanceTableName 
                                       SET   
                                             attendanceCodeId = '$swapCodeId'
                                       WHERE  
                                             attendanceType=2 
                                             AND  classId= $REQUEST_DATA[classId] 
                                             AND  groupId= $REQUEST_DATA[groupId] 
                                             AND  studentId= $studentId 
                                             AND  subjectId=$REQUEST_DATA[subjectId] 
                                             AND  employeeId=$employeeId 
                                             AND  fromDate='$date' 
                                             AND  toDate='$date' 
                                             AND  userId=$userId";
                              */               
                              $allStudentArray[$studentId]['resultStatus'] = 'Y';                          
                         } 
                         
                         $query1 =mysqli_query($conn,$query);    
                     }   
                     $msg = "Attendance Marked.";
                  }
                  
                  echo $msg;
                                    }
				                        else{
					                      echo "ERROR : Enter studentId";
				                           }
                                             }
					                           else{
						                         echo "ERROR : Enter periodId";
					                              }
                                                    }
				                                    else{
					                                 echo "ERROR : Enter groupId";
				                                       }
                                                           }
                                                        else{
	                                                      echo "ERROR : Enter subjectId";
                                                             }
                                                                 }
                                                                 else{
               	                                                     echo "ERROR : Enter classId ";
               	                                                     }
                                                                         }
          else{
             echo "ERROR : Enter authorisation key";
          } 
}          


?>

