<?php
//No time limit imposed for execution
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
//Authorisation Key is a unique LoginKey which will be used as a parameter in all further calls 
// Author :Vritee Dhall
// Created on : 28-11-2011
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

function removePHPJS($input,$rep='',$other=''){

     /* return (str_ireplace(array('<?php','<?','?>','<script','</script>'),$rep,html_entity_decode($input))); */
     $str = (str_ireplace(array('<?php','<?','?>','<script','</script>'),$rep,html_entity_decode($input)));
     $str = strip_tags($str);
     $str = html_entity_decode($str);

     //it removes all the html tags
     if($other!='') {
       $str = preg_replace("/[[:punct:]]/", "", $str);
       $str = preg_replace("/[[:space:]]/", " ", $str);
     }

     return $str;
}

if($_REQUEST['fn']== "login") {

    if($_REQUEST['uname']!="" && $_REQUEST['pw']!=""){ 
        
		$fieldName = " i.instituteId, i.instituteName, i.instituteCode, i.instituteLogo, u.userId, u.userName, u.roleId ";
        $userName = htmlentities(trim($_REQUEST['uname']));
        $userPassword = htmlentities(trim($_REQUEST['pw']));
        $condition = " u.instituteId = i.instituteId AND u.userName='".$userName."' and u.userPassword='".$userPassword."'";
        $res = mysql_query("SELECT $fieldName FROM `user` u, institute i WHERE $condition");
        $countres=mysql_num_rows($res);
        if($countres>0){
            if($row=mysql_fetch_array($res)){ 
               $userId = $row['userId'];
               $roleId = $row['roleId'];
            }
            $count=1;
            $auth_key="";
            while($count==1) {  //to generate unique Loginkey(authKey) each time   
              $auth_key= rand();
              $key= mysql_query("SELECT authKey FROM nfc_user where authKey='".md5($auth_key)."'");
              $count=mysql_num_rows($key);
            }
            
            if($count==0) {
               $query=mysql_query("SELECT * FROM nfc_user where userId='".$userId."'");
               $countrows=mysql_num_rows($query);
                if($countrows>0){ //to delete authkey from table if already exists for logged in user
                    mysql_query("DELETE FROM nfc_user where userId='".$userId."'");
                }
                mysql_query("INSERT INTO nfc_user(userId,authKey) VALUES(".$userId.",md5('".$auth_key."'))");
            } 
            else{
                echo mysql_error(); 
            }
           
            if($roleId=='2') {
               $akey=mysql_query("SELECT 
                                    e.employeeName AS teacherName,mu.authKey  
                                FROM 
                                    `employee`e ,nfc_user mu
                                WHERE  
                                    e.userId=mu.userId AND mu.userId=$userId AND  e.userId=$userId");
            }
            else {
               $akey=mysql_query("SELECT 
                                    s.studentId, s.classId, CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, 
                                    c.className, IFNULL(s.rollNo,'') AS rollNo, IFNULL(s.fatherName,'') AS fatherName, 
                                    IFNULL(s.studentPhoto,'') AS studentPhoto, mu.authKey, s.userId,
                                    b.branchName, b.branchCode, sp.periodName, ii.instituteName, ii.instituteCode,
                                    bat.batchName, d.degreeName, d.degreeCode   
                                FROM 
                                    `student` s ,nfc_user mu, class c, branch b, 
                                    study_period sp, institute ii, batch bat, degree d
                                WHERE  
                                    c.branchId = b.branchId AND sp.studyPeriodId = c.studyPeriodId AND bat.batchId = c.batchId AND
                                    c.classId = s.classId AND s.userId=mu.userId AND ii.instituteId = c.instituteId AND
                                    d.degreeId = c.degreeId AND mu.userId=$userId AND s.userId=$userId"); 
            }

            $i=0;
            if($rows =mysql_fetch_array($akey)){ //extract data from resultant array , returning associative array( unique authorisation key & student name)
              if($roleId=='2') {
                 $output[$i]= array("LoggedIn"=>1,
                                    "Text"=> "true",
                                    "Message"=>'Successful', 
                                    "authkey"=>$rows['authKey'],
                                    "firstname"=>$rows['teacherName']
                 );
              }
              else {
                 $photo = "";
                 if(trim($rows['studentPhoto'])!='') {
                   $photo = STORAGE_HTTP_PATH.'/Images/Student/'.$rows['studentPhoto'];  
                 }
                 $output = array("LoggedIn"=>1,
                                 "Text"=> "true",
                                 "Message"=>'Successful', 
                                 "authkey"=>$rows['authKey'],
                                 "userId"=>$rows['userId'],  
                                 "roleId"=>4,  
                                 "studentId"=>$rows['studentId'], 
                                 "classId"=>$rows['classId'],
                                 "studentName"=>$rows['studentName'],
                                 "className"=>$rows['className'],
                                 "rollNo"=>$rows['rollNo'], 
                                 "fatherName"=>$rows['fatherName'],
                                 "studentPhoto"=>$photo,
                                 "branchName"=>$rows['branchName'], 
                                 "branchCode"=>$rows['branchCode'],
                                 "periodName"=>$rows['periodName'], 
                                 "instituteName"=>$rows['instituteName'],
                                 "instituteCode"=>$rows['instituteCode'],
                                 "batchName"=>$rows['batchName'],
                                 "degreeName"=>$rows['degreeName'],
                                 "degreeCode"=>$rows['degreeCode']
                                );
            }
            $inaa++;       
        }
        $data['studentData'] = $output;
        echo json_encode($data);
        mysql_close($conn);
    } 
     else { 
            $data['LoggedIn'] = 0; 
            $data['Text'] = "false";
            $data['Message'] = "Invalid username or password";
            echo json_encode($data);
        }
    }
    
      else { 
        $data['LoggedIn'] = 0; 
        $data['Text'] = "false";
        $data['Message'] = "Enter username and password";
        echo json_encode($data);
    }
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
            $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
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
        else  {
	       $data['LoggedIn'] = 0; 
	       $data['Text'] = "false";
	       $data['Message'] = "Enter authorisation key";
	       echo json_encode($data);
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
        $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
        $count= mysql_num_rows($res);
        if($count>0){    //deletes authorisation key from the table 
          $out=mysql_query("DELETE FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
          $data['LoggedIn'] = 1; 
          $data['Text'] = "true";
          $data['Message'] = "Logout";
          echo json_encode($data);
        }
        mysql_close($conn);
    }
    else {
	   $data['LoggedIn'] = 0; 
	   $data['Text'] = "false";
	   $data['Message'] = "Enter authorisation key";
	   echo json_encode($data);
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
                   $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
                        $count= mysql_num_rows($res);

             if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
            $res1 = mysql_query("SELECT employeeId FROM employee WHERE userId = '$userId'");
            if($rows=mysql_fetch_array($res1)){
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
		
                 $query1 =mysql_query($query);
                 $countTimeTable=mysql_num_rows($query1);
        if($countTimeTable>0){
                 $uniqueClassId = array();
                 $uniquePeriodId      = array();
                 $uniqueSubjectId     = array();
                 $uniqueGroupId = array();
                $i=0;
                while($rows =mysql_fetch_array($query1)){ 
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
             //echo json_encode($resultData); //returns student's current resource details
             $data['LoggedIn'] = 1; 
            $data['Text'] = "true";
            $data['Message'] = "Successful";
                    
            $resultArray = array_merge(array($data),$resultData);
             mysql_close($conn);
        }
    
    else {
	       $data['LoggedIn'] = 0; 
	       $data['Text'] = "false";
	       $data['Message'] = "Time table not found";
	       echo json_encode($data);
    }
   }
        else  {
	       $data['LoggedIn'] = 0; 
	       $data['Text'] = "false";
	       $data['Message'] = "Enter authorisation key";
	       echo json_encode($data);
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
 
                   $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
                   $count= mysql_num_rows($res);
                   if($count>0){  
                     if($row=mysql_fetch_array($res)) 
                        $userId = $row['userId'];
                        }
                        $res1 = mysql_query("SELECT employeeId FROM employee WHERE userId = '$userId'");
                        if($rows=mysql_fetch_array($res1)){
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
                        $query1 =mysql_query($query);
                  
                        $i=0;
                        while($rows =mysql_fetch_array($query1)) { 
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
                          
                       // echo json_encode($result); //returns student's current resource details
                        $data['LoggedIn'] = 1; 
			            $data['Text'] = "true";
			            $data['Message'] = "Successful";
						         
                        $resultArray = array_merge(array($data),$result);
                        mysql_close($conn);
                   } 
                   else {
				       $data['LoggedIn'] = 0; 
				       $data['Text'] = "false";
				       $data['Message'] = "Enter groupId";
				       echo json_encode($data);
			    }
                 }
                 else  {
				       $data['LoggedIn'] = 0; 
				       $data['Text'] = "false";
				       $data['Message'] = "Enter subjectId";
				       echo json_encode($data);
			    }
             }
             else   {
			       $data['LoggedIn'] = 0; 
			       $data['Text'] = "false";
			       $data['Message'] = "Enter classId";
			       echo json_encode($data);
		    }
        }
        else {
			       $data['LoggedIn'] = 0; 
			       $data['Text'] = "false";
			       $data['Message'] = "Enter authorisation key";
			       echo json_encode($data);
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
                   $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
                        $count= mysql_num_rows($res);
         if($count>0){  
                if($row=mysql_fetch_array($res)) 
                       $userId = $row['userId'];
            }
                           $res1 = mysql_query("SELECT employeeId FROM employee WHERE userId = '$userId'");


                         if($rows=mysql_fetch_array($res1))
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
 $query1 =mysql_query($query);


                $i=0;
                  while($rows =mysql_fetch_array($query1))
{ 
                      $result[$i]=array("studentId"=>$rows['studentId'],
				 "studentName"=>$rows['studentName'],
                                "rollNo"=>$rows['rollNo'],    
                             
                          
                                "universityRollNo"=>$rows['universityRollNo'],
				
                               
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
                        c.classId, c.className, c.instituteId,
                        c.holdAttendance, c.holdTestMarks, c.holdFinalResult, c.holdGrades 
                  FROM 
                        class c
                  WHERE 
                        c.classId = '$classId' "; 
         $query1 =mysql_query($query);
         if($rows =mysql_fetch_array($query1)){ 
           $instituteId=$rows['instituteId'];  
           $holdAttendance = $rows['holdAttendance'];   
         }
         $attendanceTableName = "attendance".$instituteId;
		
          if($holdAttendance==0) {
            //yy-mm-dd
		    $enddate=htmlentities(trim($_REQUEST['enddate']));
		    if($enddate=='') {
		      $enddate=date('Y-m-d');
		    }
		
            $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
            $count= mysql_num_rows($res);
                      
            if($count>0){  
              if($row=mysql_fetch_array($res)) 
               $userId = $row['userId'];   
            }
            $res1 = mysql_query("SELECT employeeId FROM employee WHERE userId = '$userId'");
            if($rows=mysql_fetch_array($res1)){
              $employeeId = $rows['employeeId'];    
            }
  
            // to get student's timetable details( periodNo, roomNo,teacherName,Subject)
            $query = "SELECT
                                CONCAT(s.firstName,' ',s.lastName) AS studentName,s.studentId,
                                IF(s.rollNo IS NULL OR s.rollNo='','---',s.rollNo) AS rollNo,
                                IF(s.universityRollNo IS NULL OR s.universityRollNo='','---',s.universityRollNo) AS universityRollNo,
                                su.subjectName, su.subjectCode,
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
                             
           $query1 =mysql_query($query);
           $countDisplayatt=mysql_num_rows($query1);
           if($countDisplayatt>0){
                $i=0;
                  while($rows =mysql_fetch_array($query1)){ 
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
                  //echo json_encode($result); //returns student's current resource details
                        $data['LoggedIn'] = 1; 
			            $data['Text'] = "true";
			            $data['Message'] = "Successful";
						         
                        $resultArray = array_merge(array($data),$result);
                      mysql_close($conn);
                      }
                       else  {
					       $data['LoggedIn'] = 0; 
					       $data['Text'] = "false";
					       $data['Message'] = "No data found";
					       echo json_encode($data);
				    }
                }
                else  {
                   $data['LoggedIn'] = 0; 
                   $data['Text'] = "false";
                   $data['Message'] = "No data found";
                   echo json_encode($data);  
                }
              } 
              else  {
				       $data['LoggedIn'] = 0; 
				       $data['Text'] = "false";
				       $data['Message'] = "Enter groupId";
				       echo json_encode($data);
				    }
             }
            else  {
			       $data['LoggedIn'] = 0; 
			       $data['Text'] = "false";
			       $data['Message'] = "Enter subjectId";
			       echo json_encode($data);
		    }
         }
          else  {
		       $data['LoggedIn'] = 0; 
		       $data['Text'] = "false";
		       $data['Message'] = "Enter classId";
		       echo json_encode($data);
		    }
        }
        else   {
		       $data['LoggedIn'] = 0; 
		       $data['Text'] = "false";
		       $data['Message'] = "Enter authorisation key";
		       echo json_encode($data);
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

 	      $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
          $count= mysql_num_rows($res);
         
          if($count>0){  
           if($row=mysql_fetch_array($res)) 
              $userId = $row['userId'];
          }
          
           $res1 = mysql_query("SELECT employeeId FROM employee WHERE userId = '$userId'");
           if($rows=mysql_fetch_array($res1)){
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
                        
                 $query1 =mysql_query($query);
                 if($rows =mysql_fetch_array($query1)){ 
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

 $query1 =mysql_query($query);

		$i=0;
                  while($rows =mysql_fetch_array($query1)){ 
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
            $query2 =mysql_query($query);
           $i=0;
                  while($rows =mysql_fetch_array($query2)){ 
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
                 $query1 =mysql_query($query);
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
                    $query1 =mysql_query($query);
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
                 //   mysql_close($conn);
        }
        else{
             echo "null";
        }

}
else if($_REQUEST['fn']== "updateattendance"){ 
 $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
          $count= mysql_num_rows($res);
         
          if($count>0){  
           if($row=mysql_fetch_array($res)) 
              $userId = $row['userId'];
          }
          
           $res1 = mysql_query("SELECT employeeId FROM employee WHERE userId = '$userId'");
           if($rows=mysql_fetch_array($res1)){
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
                        
                 $query1 =mysql_query($query);
                 if($rows =mysql_fetch_array($query1)){ 
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

 $query1 =mysql_query($query);

		$i=0;
                  while($rows =mysql_fetch_array($query1)){ 
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
            $query2 =mysql_query($query);
           $i=0;
                  while($rows =mysql_fetch_array($query2)){ 
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
                    $query1 =mysql_query($query);
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
                 //   mysql_close($conn);
}
else if($_REQUEST['fn']== "attPresent" || $_REQUEST['fn']== "attAbsent"){  
    
          $res = mysql_query("SELECT * FROM nfc_user where authKey='".$_REQUEST['authkey']."'");
          $count= mysql_num_rows($res);
         
          if($count>0){  
            if($row=mysql_fetch_array($res)) 
              $userId = $row['userId'];
          }
          
          $res1 = mysql_query("SELECT employeeId FROM employee WHERE userId = '$userId'");
          if($rows=mysql_fetch_array($res1)){
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
                 $query1 =mysql_query($query);
                 if($rows =mysql_fetch_array($query1)){ 
                   $instituteId=$rows['instituteId'];   
                 }
                 
                  
                 $query = "SELECT 
                                attendanceCodeId, attendanceCodePercentage 
                          FROM 
                                attendance_code 
                          WHERE 
                                instituteId = '$instituteId' "; 
                 $query1 =mysql_query($query);
                 
                 
                 while($rows =mysql_fetch_array($query1)) { 
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
                  $query2 =mysql_query($query);   
                  
                  $result = array();
                  $i=0;
                  while($rows =mysql_fetch_array($query2)){ 
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
                  $queryStudentList =mysql_query($queryStudent);
                 
                  $allStudentArray = array(); 
                  while($rowsStudent =mysql_fetch_array($queryStudentList)) { 
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
                     $query1 =mysql_query($query);       
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
                         
                         $query1 =mysql_query($query);    
                     }   
                     $msg = "Attendance Marked.";
                  }
                  
                  echo $msg;
                                    }
				                        else  { 
								            $data['LoggedIn'] = 0; 
								            $data['Text'] = "false";
								            $data['Message'] = "Enter studentId";
								            echo json_encode($data);
								        }
                                             }
					                           else   { 
										            $data['LoggedIn'] = 0; 
										            $data['Text'] = "false";
										            $data['Message'] = "Enter periodId";
										            echo json_encode($data);
										        }
                                                    }
				                                    else   { 
											            $data['LoggedIn'] = 0; 
											            $data['Text'] = "false";
											            $data['Message'] = "Enter groupId";
											            echo json_encode($data);
											        }
                                                           }
                                                        else  { 
												            $data['LoggedIn'] = 0; 
												            $data['Text'] = "false";
												            $data['Message'] = "Enter subjectId";
												            echo json_encode($data);
												        }
                                                                 }
                                                                 else  { 
														            $data['LoggedIn'] = 0; 
														            $data['Text'] = "false";
														            $data['Message'] = "Enter classId";
														            echo json_encode($data);
														        }
                                                                         }
																          else  { 
																            $data['LoggedIn'] = 0; 
																            $data['Text'] = "false";
																            $data['Message'] = "Enter authorisation key";
																            echo json_encode($data);
																        }
}          



 else if($_REQUEST['fn']== "studentMarks") {
 	
	  $authKey = htmlentities(trim($_REQUEST['authkey'])); 
      $userId = "";
      $studentId = ""; 
      $classId = ""; 
      $instituteId = "";
                  
      $res = mysql_query("SELECT 
                               s.studentId, s.classId, s.userId, nu.authKey, c.instituteId,
                               c.holdAttendance, c.holdTestMarks, c.holdFinalResult, c.holdGrades 
                          FROM 
                               student s, nfc_user nu, class c
                          WHERE
                               c.classId = s.classId AND nu.userId =  s.userId AND nu.authKey='".$authKey."'");
      $count= mysql_num_rows($res);
      if($count>0) {  
        if($row=mysql_fetch_array($res)) {
          $userId = $row['userId'];
          $studentId = $row['studentId']; 
          $classId = $row['classId']; 
          $instituteId = $row['instituteId'];  
          $holdTestMarks = $row['holdTestMarks'];
        }
      }
      
      if($userId=="" && $studentId =="" && $classId =="" && $instituteId =="") {
         $data['LoggedIn'] = 0; 
         $data['Text'] = "false";
         $data['Message'] = "Invalid Login";
         echo json_encode($data);
         die;
      }			

      $i=0;   
      if($holdTestMarks==0) {	
          $tableName1 = "test_marks".$instituteId;
          $tableName2 = "test".$instituteId;
          
          $query=mysql_query("SELECT
                                        s.studentId,
						                CONCAT(su.subjectName,' (',su.subjectCode,')') AS subjectName,
						                CONCAT(IF( ttc.examType = 'PC', 'Internal', 'External' ), ' (' , ttc.testTypeName, ')' ) AS examType,
						                ttc.testTypeName,t.testDate, emp.employeeName, CONCAT( t.testAbbr,'-',t.testIndex ) AS testName,
						                su.subjectCode, (tm.maxMarks) AS totalMarks1, (t.maxMarks) AS totalMarks,
						                ROUND(IF(tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A')),2)  AS obtainedMarks,
						                SUBSTRING_INDEX(cl.className,'-',-1) AS studyPeriod, gr.groupName, ttc.colorCode
				                FROM	
                                        test_type_category ttc, $tableName1 tm, student s,  $tableName2 t,
						                class cl, `group` gr, subject su,employee emp
				                WHERE	
                                        t.testTypeCategoryId = ttc.testTypeCategoryId
				                        AND		t.classId=cl.classId
				                        AND		emp.employeeId=t.employeeId
				                        AND		t.testId = tm.testId
				                        AND		t.groupId = gr.groupId
				                        AND		tm.studentId = s.studentId
				                        AND		tm.subjectId = su.subjectId
				                        AND		tm.studentId IN ( $studentId )
				                        AND     cl.classId IN ($classId) 
                                        AND su.hasMarks = 1
			                    ORDER BY  
                                        subjectName ASC
                                LIMIT 0,2000 "); 
						     
						                                                
            $i=0;   
            while($rows =mysql_fetch_array($query)){
                $result[$i]=array("studentId"=>$rows['studentId'],
                                  "subjectName"=>$rows['subjectName'],
                                  "subjectCode"=>$rows['subjectCode'], 
                                  "examType"=>$rows['examType'],
                                  "testTypeName"=>$rows['testTypeName'],
                                  "testDate"=>$rows['testDate'],
                                  "employeeName"=>$rows['employeeName'],
                                  "testName"=>$rows['testName'],
                                  "totalMarks1"=>$rows['totalMarks1'],
                                  "totalMarks"=>$rows['totalMarks'],
                                  "obtainedMarks"=>$rows['obtainedMarks'],
                                  "studyPeriod"=>$rows['studyPeriod'],
                                  "groupName"=>$rows['groupName']
                                 );
                $i++; 
            }
      }
        
      if($i=="0") {
           $data["dataAvailable"] = "0";
           $data["dataMessage"] = "Marks not found";
           echo json_encode($data);
           die;
      }
        
      $data = array("studentData"=>$result);
      echo json_encode($data);        
      mysql_close($conn);                 
}
 
 
  else if($_REQUEST['fn']== "studentAttendance") {
  	
	  $authKey = htmlentities(trim($_REQUEST['authkey'])); 
      $userId = "";
      $studentId = ""; 
      $classId = ""; 
      $instituteId ="";
                  
      $res = mysql_query("SELECT 
                               s.studentId, s.classId, s.userId, nu.authKey, c.className,
                               CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                               c.instituteId, c.holdAttendance, c.holdTestMarks, c.holdFinalResult, c.holdGrades 
                          FROM 
                               student s, nfc_user nu, class c
                          WHERE
                               s.classId = c.classId AND nu.userId =  s.userId AND nu.authKey='".$authKey."'");
      $count= mysql_num_rows($res);
      if($count>0) {  
        if($row=mysql_fetch_array($res)) {
          $userId = $row['userId'];
          $studentId = $row['studentId']; 
          $classId = $row['classId']; 
          $instituteId = $row['instituteId']; 
	      $holdAttendance = $row['holdAttendance']; 
          $studentName = $row['studentName'];   
          $className = $row['className'];   
        }
      }
    
      if($userId=="" && $studentId =="" && $classId =="" && $instituteId =="") {
         $data['LoggedIn'] = 0; 
         $data['Text'] = "false";
         $data['Message'] = "Parameter missing";
         echo json_encode($data);
         die;
      }			
     if($holdAttendance==0) {	   
      $tableName = "attendance".$instituteId."_12";
      
      $query=mysql_query("SELECT
                                tt.classId, tt.studentId, tt.subjectId, tt.subjectName, tt.subjectCode, 
                                tt.lectureAttended, tt.lectureDelivered, tt.fromDate, 
                                tt.toDate, tt.groupId, tt.groupName
                          FROM
                              (SELECT 
                                    att.classId, att.studentId, att.subjectId,
                                    ROUND(SUM(IF( att.isMemberOfClass =0, 0,
                                    IF(att.attendanceType =2,(ac.attendanceCodePercentage /100), att.lectureAttended ) ) ),0) AS lectureAttended ,
                                    SUM(IF(isMemberOfClass=0,0, lectureDelivered))  as lectureDelivered,
                                    MIN(att.fromDate) AS fromDate, MAX(att.toDate) AS toDate, 
                                    grp.groupId, grp.groupName , sub.subjectName, sub.subjectCode
                              FROM  
                                    `subject` sub, employee e, `group` grp,  $tableName att  
                                    LEFT JOIN attendance_code ac ON ac.attendanceCodeId = att.attendanceCodeId 
                              WHERE 
                                    att.subjectId = sub.subjectId AND
                                    e.employeeId = att.employeeId AND
                                    grp.classId = att.classId AND
                                    grp.groupId = att.groupId 
                                    AND att.studentId = $studentId AND att.classId = $classId       
                              GROUP BY 
                                  att.classId, att.studentId, att.subjectId  ,att.groupId) AS tt 
                          ORDER BY
                              studentId");                                            
      
            $result = array();
            $i=0;   
            while($rows =mysql_fetch_array($query)){
                if($rows['lectureDelivered']>0) {
                  $per = number_format($rows['lectureAttended'] / $rows['lectureDelivered']*100,2);
                }
                else {
                  $per ="0.00"; 
                }
                $result[$i]=array("classId"=>$rows['classId'],
                                  "studentId"=>$rows['studentId'],
                                  "studentName"=> $studentName, 
                                  "className"=> $className, 
                                  "subjectId"=>$rows['subjectId'],
                                  "groupId"=>$rows['groupId'],
                                  "lectureAttended"=>$rows['lectureAttended'],
                                  "lectureDelivered"=>$rows['lectureDelivered'],
                                  "fromDate"=>$rows['fromDate'], 
                                  "toDate"=>$rows['toDate'], 
                                  "groupName"=>$rows['groupName'], 
                                  "subjectName"=>$rows['subjectName'],
                                  "subjectCode"=>$rows['subjectCode'],
                                  "percentage"=>$per
                                 );
                $i++; 
            }
	}
            
            if($i=="0") {
               $data["dataAvailable"] = "0";
               $data["dataMessage"] = "Attendance not found";
               echo json_encode($data);
               die;
            }
            
            $data['studentData'] = $result;
            echo json_encode($data);
            mysql_close($conn);                
}
 
 
 else if($_REQUEST['fn']== "studentCourse") {
 		
 	  $authKey = htmlentities(trim($_REQUEST['authkey'])); 
      $userId = "";
      $studentId = ""; 
      $classId = ""; 
                  
      $res = mysql_query("SELECT 
                               s.studentId, s.classId, s.userId, nu.authKey 
                          FROM 
                               student s, nfc_user nu
                          WHERE
                               nu.userId =  s.userId AND nu.authKey='".$authKey."'");
      $count= mysql_num_rows($res);
      if($count>0) {  
        if($row=mysql_fetch_array($res)) {
          $userId = $row['userId'];
          $studentId = $row['studentId']; 
          $classId = $row['classId']; 
        }
      }
    
      if($userId=="" && $studentId =="" && $classId =="") {
         $data['LoggedIn'] = 0; 
         $data['Text'] = "false";
         $data['Message'] = "Enter username and password";
         echo json_encode($data);
         die;
      }			
 				
 			
 	  
       $query=mysql_query("SELECT
                                gr.groupName,gt.groupTypeName,gt.groupTypeCode,
                                SUBSTRING_INDEX(c.className,'-',-3) AS className,
                                SUBSTRING_INDEX(c.className,'-',-1) AS studyPeriod
                          FROM
                               `group` gr,student s,group_type gt,student_groups sg,`class` c
                          WHERE
                               s.studentId=$studentId
                               AND gr.classId=c.classId
                               AND s.studentId=sg.studentId
                               AND gr.groupId=sg.groupId
                               AND gr.groupTypeId=gt.groupTypeId
                               AND c.classId IN ($classId)
			               UNION
			               SELECT
                                gr.groupName,gt.groupTypeName,gt.groupTypeCode,
                                SUBSTRING_INDEX(c.className,'-',-3) AS className,
                                SUBSTRING_INDEX(c.className,'-',-1) AS studyPeriod
				            FROM
					            `group` gr,student s,group_type gt,`class` c,student_optional_subject sos
				            WHERE
				                s.studentId=$studentId
				                AND sos.classId = c.classId
				                AND sos.groupId = gr.groupId
				                AND sos.studentId = s.studentId
				                AND gr.groupTypeId=gt.groupTypeId
				                AND c.classId IN ($classId)
                           ORDER BY  studyPeriod ASC");   
                
           $i=0;   
            while($rows =mysql_fetch_array($query)){
                $result[$i]=array("groupName"=>$rows['groupName'],
                                  "groupTypeName"=>$rows['groupTypeName'],
                                  "groupTypeCode"=>$rows['groupTypeCode'],
                                  "className"=>$rows['className'],
                                  "studyPeriod"=>$rows['studyPeriod']
                                  );
                $i++; 
            }
            
            if($i=="0") {
               $data["dataAvailable"] = "0";
               $data["dataMessage"] = "Course detail not found";
               echo json_encode($data);
               die;
            }
            
           $data['studentData'] = $result;
            echo json_encode($data); 
            mysql_close($conn);                                              
}
 
else if($_REQUEST['fn']== "studentTimeTable") {

      $authKey = htmlentities(trim($_REQUEST['authkey'])); 
      $userId = "";
      $studentId = ""; 
      $classId = ""; 
                  
      $res = mysql_query("SELECT 
                               s.studentId, s.classId, s.userId, nu.authKey 
                          FROM 
                               student s, nfc_user nu
                          WHERE
                               nu.userId =  s.userId AND nu.authKey='".$authKey."'");
      $count= mysql_num_rows($res);
      if($count>0) {  
        if($row=mysql_fetch_array($res)) {
          $userId = $row['userId'];
          $studentId = $row['studentId']; 
          $classId = $row['classId']; 
        }
      }
    
      if($userId=="" && $studentId =="" && $classId =="") {
         $data['LoggedIn'] = 0; 
         $data['Text'] = "false";
         $data['Message'] = "Enter username and password";
         echo json_encode($data);
         die;
      }
       
      $data = "";
      $mon = array();  
      $tue = array();  
      $wed = array();  
      $thu = array();  
      $fri = array();  
      $sat = array();  
      $sun = array();  
      $j=0;            
      for($i=1;$i<=7;$i++)  {
          $result = array();
          $query= mysql_query("SELECT
                                    DISTINCT
                                            sg.studentId, p.periodSlotId, tt.periodId, tt.daysOfWeek, p.periodNumber,
                                            CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,gr.groupShort,
                                            SUBSTRING_INDEX(cl.className,'-',-3) as className,
                                            gr.classId, sub.subjectName,sub.subjectCode,
                                            r.roomName,concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation,
                                            emp.employeeName, gr.groupId, sub.subjectId, cl.classId, sub.hasMarks, sub.hasAttendance,
                                            emp.employeeId, tt.fromDate,
                                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                            IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                                            IF(IFNULL(s.universityRollNo,'')='','---',s.universityRollNo) AS universityRollNo,
                                            IF(IFNULL(s.fatherName,'')='','---',s.fatherName) AS fatherName,
                                            IF(IFNULL(s.motherName,'')='','---',s.motherName) AS motherName,
                                            IF(IFNULL(s.guardianName,'')='','---',s.guardianName) AS guardianName
                              FROM
                                    student s, student_groups sg, period p, `group` gr,  
                                    subject sub, employee emp, room r, class cl,
                                    time_table_labels ttl,time_table_12 tt, block b, building c  
                              WHERE
                                    tt.periodId = p.periodId
                                    AND sg.studentId = s.studentId
                                    AND sg.classId = cl.classId
                                    AND sg.groupId = tt.groupId
                                    AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                                    AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                                    AND tt.roomId = r.roomId
                                    AND tt.toDate IS NULL
                                    AND tt.timeTableLabelId=ttl.timeTableLabelId
                                    AND r.blockId = b.blockId AND b.buildingId = c.buildingId
                                    AND sg.studentId=$studentId AND cl.classId = $classId AND tt.daysOfWeek = $i 
                             UNION
                             SELECT
                                    DISTINCT
                                          sg.studentId, p.periodSlotId, tt.periodId, tt.daysOfWeek, p.periodNumber,
                                          CONCAT(p.startTime,p.startAmPm,'  ',endTime,endAmPm) AS pTime,gr.groupShort,
                                          SUBSTRING_INDEX(cl.className,'-',-3) as className,
                                          gr.classId, sub.subjectName,sub.subjectCode,
                                          r.roomName,concat(c.abbreviation, '-',b.abbreviation,'-',r.roomAbbreviation) as roomAbbreviation,
                                          emp.employeeName, gr.groupId, sub.subjectId, cl.classId, sub.hasMarks, sub.hasAttendance,
                                          emp.employeeId, tt.fromDate,
                                          CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                                          IF(IFNULL(s.rollNo,'')='','---',s.rollNo) AS rollNo,
                                          IF(IFNULL(s.universityRollNo,'')='','---',s.universityRollNo) AS universityRollNo,
                                          IF(IFNULL(s.fatherName,'')='','---',s.fatherName) AS fatherName,
                                          IF(IFNULL(s.motherName,'')='','---',s.motherName) AS motherName,
                                          IF(IFNULL(s.guardianName,'')='','---',s.guardianName) AS guardianName
                             FROM
                                    student s, student_optional_subject sg, period p, `group` gr,  subject sub, employee emp, room r, class cl,
                                    time_table_labels ttl,time_table_12 tt, block b, building c  
                             WHERE
                                    tt.periodId = p.periodId
                                    AND sg.studentId = s.studentId
                                    AND sg.classId = cl.classId
                                    AND sg.groupId = tt.groupId
                                    AND tt.groupId = gr.groupId AND gr.classId = cl.classId
                                    AND tt.subjectId=sub.subjectId AND tt.employeeId=emp.employeeId
                                    AND tt.roomId = r.roomId
                                    AND tt.toDate IS NULL
                                    AND tt.timeTableLabelId=ttl.timeTableLabelId
                                    AND r.blockId = b.blockId AND b.buildingId = c.buildingId
                                    AND sg.studentId=$studentId AND cl.classId = $classId AND tt.daysOfWeek = $i
                             ORDER BY 
                                    periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber,groupShort,subjectCode");  
          
                while($rows =mysql_fetch_array($query)){
                    $result[]=array("studentId"=>$rows['studentId'],
                                    "periodSlotId"=>$rows['periodSlotId'],
                                    "periodId"=>$rows['periodId'],
                                    "daysOfWeek"=>$rows['daysOfWeek'],
                                    "periodNumber"=>$rows['periodNumber'],
                                    "pTime"=>$rows['pTime'],
                                    "groupShort"=>$rows['groupShort'],
                                    "className"=>$rows['className'],
                                    "classId"=>$rows['classId'],
                                    "subjectName"=>$rows['subjectName'],
                                    "subjectCode"=>$rows['subjectCode'],
                                    "roomAbbreviation"=>$rows['roomAbbreviation'],
                                    "employeeName"=>$rows['employeeName'],
                                    "groupId"=>$rows['groupId'],
                                    "subjectId"=>$rows['subjectId']
                                   );
                    $j++; 
                }
                
                if($i=='1') {
                  $mon = $result; 
                }
                if($i=='2') {
                  $tue  = $result;      
                }
                if($i=='3') {
                  $wed = $result;      
                }
                if($i=='4') {
                  $thu = $result;      
                }
                if($i=='5') {
                  $fri = $result;      
                }
                if($i=='6') {
                  $sat = $result;      
                }
                if($i=='7') {
                  $sun = $result;      
                }
        }
        
        if($j=="0") {
           $data["dataAvailable"] = "0";
           $data["dataMessage"] = "Time Table not generated";
           echo json_encode($data);
           die;
        }
        $data = array_merge(array("mon"=>$mon),
                            array("tue"=>$tue),
                            array("wed"=>$wed),
                            array("thu"=>$thu),
                            array("fri"=>$fri),
                            array("sat"=>$sat),
                            array("sun"=>$sun));
        echo json_encode($data);  
        mysql_close($conn);       
}
 
else if($_REQUEST['fn']== "notices") {

      $authKey = htmlentities(trim($_REQUEST['authkey'])); 
      $userId = "";
      $studentId = ""; 
      $classId = ""; 
                  
      $res = mysql_query("SELECT 
                               s.studentId, s.classId, s.userId, nu.authKey,
                               c.sessionId, c.instituteId, c.branchId, c.degreeId, 
                               c.batchId, c.universityId, c.className
                          FROM 
                               class c, student s, nfc_user nu
                          WHERE
                              c.classId = s.classId AND nu.userId =  s.userId AND nu.authKey='".$authKey."'");
      $count= mysql_num_rows($res);
      if($count>0) {  
        if($row=mysql_fetch_array($res)) {
          $userId = $row['userId'];
          $studentId = $row['studentId']; 
          $classId = $row['classId']; 
          $branchId=$row['branchId'];
          $batchId=$row['batchId'];
          $degreeId=$row['degreeId'];
          $universityId=$row['universityId'];  
          $sessionId = $row['sessionId'];
          $instituteId = $row['instituteId']; 
          $className = $row['className'];   
        }
      }
    
      if($userId=="" && $studentId =="" && $classId =="") {
         $data['LoggedIn'] = 0; 
         $data['Text'] = "false";
         $data['Message'] = "Enter username and password";
         echo json_encode($data);
         die;
      }
      $curDate=date('Y-m-d');
    
      $query=mysql_query("SELECT
                             tt.noticeId, tt.noticeSubject, tt.noticeText, tt.visibleFromDate, tt.visibleToDate, tt.noticeAttachment
                          FROM               
                            (SELECT 
				    DISTINCT n.noticeId, 
				    n.noticeText,
				    n.noticeSubject,
				    n.visibleFromDate,
				    n.visibleToDate,
				    IF(IFNULL(n.noticeAttachment,'')='','',CONCAT('".STORAGE_HTTP_PATH."/Images/Notice/',n.noticeAttachment)) AS noticeAttachment,
				    n.downloadCount,
				    d.abbr,
				    d.departmentName ,
				    n.visibleMode
			    FROM    
				    department d, notice n INNER JOIN notice_visible_to_role nvr ON  (n.noticeId=nvr.noticeId  AND (
				                    (nvr.universityId IS NULL OR nvr.universityId='$universityId')
				                     AND
				                    (nvr.degreeId IS NULL OR nvr.degreeId='$degreeId')
				                     AND
				                    (nvr.branchId IS NULL OR nvr.branchId='$branchId')
				                   )
				                ) 
				    AND isClass = CASE WHEN '1' THEN (SELECT 
				                                        DISTINCT 1 FROM notice_visible_to_class c 
				                                  WHERE 
				                                        n.noticeId=c.noticeId AND c.classId='$classId' LIMIT 0,1)  ELSE '0' END
			    WHERE    
				    nvr.roleId=4          
				    AND nvr.instituteId='$instituteId' 
				    AND n.instituteId='$instituteId'  
				    AND nvr.sessionId='$sessionId'  
				    AND n.departmentId = d.departmentId 
				     
			    GROUP BY 
				    n.noticeId
			    UNION  
			    SELECT 
					DISTINCT n.noticeId, 
				    n.noticeText,
				    n.noticeSubject,
				    n.visibleFromDate,
				    n.visibleToDate,
				    IF(IFNULL(n.noticeAttachment,'')='','',CONCAT('".STORAGE_HTTP_PATH."/Images/Notice/',n.noticeAttachment)) AS noticeAttachment,  n.downloadCount,
				    d.abbr,
				    d.departmentName ,
				    n.visibleMode
				
			      FROM  
				    department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
			      WHERE        
				    nvr.noticeInstituteId='$instituteId' 
				    AND n.departmentId = d.departmentId 
				     
			      GROUP BY 
				    n.noticeId
			      ORDER BY 
				     visibleFromDate DESC  LIMIT 0,50 
		 ) AS tt
                          ORDER BY 
                             visibleFromDate DESC
                          LIMIT 0,50");
	
        $i=0;   
        while($rows =mysql_fetch_array($query)){
            $result[] = array("noticeid"=>$rows['noticeId'],
                              "subject"=>removePHPJS($rows['noticeSubject'],"",1),
                              "description"=>removePHPJS($rows['noticeText'],"",1),
                              "visiblefromdate"=>$rows['visibleFromDate'],
                              "visibletodate"=>$rows['visibleToDate'],
                              "attachment"=>$rows['noticeAttachment']);
                              
            $i++; 
        }

        if($i==0) {
          $data["dataAvailable"] = "0";
          $data["dataMessage"] = "Notice not found";
          echo json_encode($data);
          die;
        }
            
        $data = array("studentData"=>$result);
        echo json_encode($data);  
        mysql_close($conn);     
}

 
 
 
else if($_REQUEST['fn']== "studentFaculty") {
         
      $authKey = htmlentities(trim($_REQUEST['authkey'])); 
      $userId = "";
      $studentId = ""; 
      $classId = ""; 
                  
      $res = mysql_query("SELECT 
                               s.studentId, s.classId, s.userId, nu.authKey 
                          FROM 
                               student s, nfc_user nu
                          WHERE
                               nu.userId =  s.userId AND nu.authKey='".$authKey."'");
      $count= mysql_num_rows($res);
      if($count>0) {  
        if($row=mysql_fetch_array($res)) {
          $userId = $row['userId'];
          $studentId = $row['studentId']; 
          $classId = $row['classId']; 
        }
      }
    
      if($userId=="" && $studentId =="" && $classId =="") {
         $data['LoggedIn'] = 0; 
         $data['Text'] = "false";
         $data['Message'] = "Enter username and password";
         echo json_encode($data);
         die;
      }            
                 
       $query=mysql_query("SELECT
                               DISTINCT ttEmp.employeeId, ttEmp.employeeName, 
                                        ttEmp.subjectCode, ttEmp.subjectName, ttEmp.groupShort,
                                        ttEmp.subjectId, ttEmp.groupId, ttEmp.emailAddress 
                           FROM 
                             (SELECT
                                     DISTINCT emp.employeeId, emp.employeeName, sub.subjectCode, sub.subjectName, 
                                     gr.groupShort, sub.subjectId, gr.groupId, IFNULL(emp.emailAddress,'') AS emailAddress 
                              FROM
                                    `time_table_12` tt, `period` p, `student` s, `subject` sub, `employee` emp,
                                    `room` r, `block` bl, `student_groups` sg, `time_table_labels` ttl,
                                    `time_table_classes` ttc, `group` gr, class cl
                              WHERE
                                    tt.periodId = p.periodId
                                    AND  s.studentId=sg.studentId
                                    AND  tt.subjectId = sub.subjectId
                                    AND  sg.groupId = gr.groupId
                                    AND  tt.groupId = sg.groupId
                                    AND  tt.employeeId=emp.employeeId
                                    AND  r.blockId = bl.blockId
                                    AND  tt.roomId = r.roomId
                                    AND  tt.toDate IS NULL
                                    AND  tt.timeTableLabelId = ttl.timeTableLabelId
                                    AND  ttl.timeTableLabelId = ttc.timeTableLabelId
                                    AND  sg.classId = ttc.classId
                                    AND  sg.classId = cl.classId
                                    AND  sg.studentId=".$studentId."
                                    AND  sg.classId = ".$classId."
                              UNION
                              SELECT
                                     DISTINCT emp.employeeId, emp.employeeName, sub.subjectCode, sub.subjectName, 
                                     gr.groupShort, sub.subjectId, gr.groupId, IFNULL(emp.emailAddress,'') AS emailAddress 
                              FROM
                                    `time_table_12` tt, `period` p, `student` s, `subject` sub, `employee` emp,
                                    `room` r, `block` bl, `student_optional_subject` sg, `time_table_labels` ttl,
                                    `time_table_classes` ttc, `group` gr, class cl
                              WHERE
                                    tt.periodId = p.periodId
                                    AND s.studentId=sg.studentId
                                    AND tt.subjectId = sub.subjectId
                                    AND sg.groupId = gr.groupId
                                    AND tt.groupId = sg.groupId
                                    AND tt.employeeId=emp.employeeId
                                    AND r.blockId = bl.blockId
                                    AND tt.roomId = r.roomId
                                    AND tt.toDate IS NULL
                                    AND tt.timeTableLabelId = ttl.timeTableLabelId
                                    AND ttl.timeTableLabelId = ttc.timeTableLabelId
                                    AND sg.classId = ttc.classId
                                    AND sg.classId = cl.classId
                                    AND sg.studentId=".$studentId."
                                    AND tt.classId = ".$classId." ) AS ttEmp
                              ORDER BY 
                                    employeeName");   
                
           $i=0;   
           $result = array();
           $resultArray = array();
           while($rows =mysql_fetch_array($query)){
                $result[$i]=array("employeeId"=>$rows['employeeId'],
                                  "employeeName"=>$rows['employeeName'],
                                  "subjectId"=>$rows['subjectId'],
                                  "groupId"=>$rows['groupId'],
                                  "subjectCode"=>$rows['subjectCode'],
                                  "subjectName"=>$rows['subjectName'],
                                  "groupShort"=>$rows['groupShort'],
                                  "emailAddress"=>$rows['emailAddress']
                                  );
                
               $find='';
               for($j=0;$j<count($resultArray);$j++) {
                 if($resultArray[$j]['employeeName']==$rows['employeeName']) {
                   $find='1';
                   break;
                 }
               }
               if($find=='') {
                    $resultArray[]=array("employeeId"=>$rows['employeeId'],
                                      "employeeName"=>$rows['employeeName'],
                                      "emailAddress"=>$rows['emailAddress']
                                      );
               }  
               $i++; 
           }
            
            if($i=="0") {
               $data["dataAvailable"] = "0";
               $data["dataMessage"] = "Faculty detail not found";
               echo json_encode($data);
               die;
            }
            
            //$data['studentData'] = $result;
            $data['studentData'] = $resultArray;
            echo json_encode($data); 
            mysql_close($conn);                                              
} 
else if($_REQUEST['fn']== "suggestionAdd") {
         
      $authKey = htmlentities(trim($_REQUEST['authkey'])); 
      $msg = htmlentities(trim($_REQUEST['msg'])); 
    
      $userId = "";
      $studentId = ""; 
      $classId = ""; 
      $instituteId = "";            
      $res = mysql_query("SELECT 
                               s.studentId, s.classId, s.userId, nu.authKey, c.instituteId 
                          FROM 
                               class c, student s, nfc_user nu
                          WHERE
                               c.classId = s.classId AND nu.userId =  s.userId AND nu.authKey='".$authKey."'");
      $count= mysql_num_rows($res);
      if($count>0) {  
        if($row=mysql_fetch_array($res)) {
          $userId = $row['userId'];
          $studentId = $row['studentId']; 
          $classId = $row['classId']; 
          $instituteId = $row['instituteId']; 
        }
      }
    
      if($userId=="" && $studentId =="" && $classId =="" && $instituteId=="") {
         $data["dataAvailable"] = "0";
         $data["dataMessage"] = "Invalid Authentication, Please try agaian";
         echo json_encode($data);
         die;
      }  
      
      $curDate = date('Y-m-d');
      $query = "INSERT INTO suggestion 
                (userId, suggestionOn, suggestionSubjectId, suggestionText, instituteId)
                VALUES
                ('$userId','$curDate','10','$msg','$instituteId') ";
      mysql_query($query);                       
      $err = mysql_error();
      if($err!='') {
        $data["dataAvailable"] = "0";
        $data["dataMessage"] = "Suggestion message cannot be saved"; 
      }
      else {
        $data["dataAvailable"] = "1";
        $data["dataMessage"] = "Suggestion message saved successfully";  
      }
      echo json_encode($data);
      die;
}

else if($_REQUEST['fn']== "suggestionList") {
         
      $authKey = htmlentities(trim($_REQUEST['authkey'])); 
      
      $userId = "";
      $studentId = ""; 
      $classId = ""; 
      $instituteId = "";            
      $res = mysql_query("SELECT 
                               s.studentId, s.classId, s.userId, nu.authKey, c.instituteId 
                          FROM 
                               class c, student s, nfc_user nu
                          WHERE
                               c.classId = s.classId AND nu.userId =  s.userId AND nu.authKey='".$authKey."'");
      $count= mysql_num_rows($res);
      if($count>0) {  
        if($row=mysql_fetch_array($res)) {
          $userId = $row['userId'];
          $studentId = $row['studentId']; 
          $classId = $row['classId']; 
          $instituteId = $row['instituteId']; 
        }
      }
    
      if($userId=="" || $studentId =="" || $classId =="" || $instituteId=="") {
         $data["dataAvailable"] = "0";
         $data["dataMessage"] = "Invalid Authentication, Please try agaian";
         echo json_encode($data);
         die;
      }  
      
      $curDate = date('Y-m-d');
      $query = "SELECT  
                    s.userId, s.suggestionOn, s.suggestionSubjectId, s.suggestionText, s.instituteId  
                    DATE_FORMAT(suggestionOn,'%d-%b-%Y') AS suggestionDate,
                    CONCAT(IFNULL(stu.firstName,''),' ',IFNULL(stu.lastName,'')) AS studentName, 
                    stu.studentId, c.className, IFNULL(studentEmail,'') AS studentEmail, c.classId
                FROM
                    class c, suggestion s, user u, student stu
                WHERE
                    c.classId = stu.classId AND u.userId = s.userId AND stu.userId = u.userId AND
                    stu.studentId = '".$studentId."'
                ORDER BY     
                   s.suggestionOn DESC ";
       $i=0;   
       while($rows =mysql_fetch_array($query)){
            $result[$i]=array("userId"=>$rows['userId'],
                              "studentId"=>$rows['studentId'],
                              "classId"=>$rows['classId'],
                              "studentName"=>$rows['studentName'] ,
                              "className"=>$rows['className'],
                              "studentEmail"=>$rows['studentEmail'], 
                              "suggestionDate"=>$rows['suggestionDate'],
                              "suggestionText"=>$rows['suggestionText']
                              );
            $i++; 
       }
        
       if($i=="0") {
          $data["dataAvailable"] = "0";
          $data["dataMessage"] = "Suggestion detail not found";
          echo json_encode($data);
          die;
       }
        
       $data['studentData'] = $result;
       echo json_encode($data); 
       mysql_close($conn);       
}
?>

