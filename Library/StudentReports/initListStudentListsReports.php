<?php
//----------------------------------------------------------------------------------------------------
//This file creates a query for the "StudentListsReport" and generates an array of the selected fields
//
// Author :Arvind Singh Rawat
// Created on : 08-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
    set_time_limit(0);

    define('MODULE','StudentList');
    define('ACCESS','view');
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
   // $reportManager = StudentReportsManager::getInstance();
    //echo "<pre>";
     $countFields=count($REQUEST_DATA);
     $studentReportsManager = StudentReportsManager::getInstance();
    //require_once(BL_PATH . '/ScReportManager.inc.php');
    //$reportManager = ReportManager::getInstance();
    foreach($REQUEST_DATA as $key => $values) {
        $key = add_slashes($values);
    }
    $conditionsArray = array();

	


    $blankColumnCond='';
    $incAll  = add_slashes($REQUEST_DATA['incAll']);

    if($incAll=='') {
      $incAll=0;
    }
	$incAllInsitute  = add_slashes($REQUEST_DATA['incAllInsitute']);

    if($incAllInsitute=='') {
      $incAllInsitute=0;
    }

    $rollNo = add_slashes($REQUEST_DATA['rollNo']);
    if (!empty($rollNo)) {
        $conditionsArray[] = " a.rollNo LIKE '$rollNo%' ";
    }

    $studentName = add_slashes($REQUEST_DATA['studentName']);
    if (!empty($studentName)) {
        $conditionsArray[] = " CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) LIKE '%$studentName%' ";
    }

    $degreeId = $REQUEST_DATA['degreeId'];
    if (!empty($degreeId)) {
        $conditionsArray[] = " b.degreeId in ($degreeId) ";
    }

    $branchId = $REQUEST_DATA['branchId'];
    if (!empty($branchId)) {
        $conditionsArray[] = " b.branchId in ($branchId) ";
    }

    $periodicityId = $REQUEST_DATA['periodicityId'];
    if (!empty($periodicityId)) {
        $conditionsArray[] = " b.studyPeriodId IN ($periodicityId) ";
    }

    $groupId = $REQUEST_DATA['groupId'];
    if (!empty($groupId)) {
        $conditionsArray[] = "  ( (a.studentId IN (SELECT DISTINCT(studentId) FROM student_groups sg WHERE
                                   sg.classId = a.classId AND sg.studentId = a.studentId AND sg.groupId IN ($groupId)))
                                  OR
                                  (a.studentId IN (SELECT DISTINCT(studentId) FROM student_optional_subject sg1 WHERE
                                   sg1.classId = a.classId AND sg1.studentId = a.studentId AND sg1.groupId IN ($groupId))))
                             ";
    }

    $fromDateA = $REQUEST_DATA['fromDateA'];
    if (!empty($fromDateA) and $fromDateA != '--') {
        $fromDateArr = explode('-',$fromDateA);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " a.dateOfAdmission >= '$thisDate' ";
        }
    }

    $toDateA = $REQUEST_DATA['toDateA'];
    if (!empty($toDateA) and $toDateA != '--') {
        $toDateArr = explode('-',$toDateA);
        $toDateAM = intval($toDateArr[0]);
        $toDateAD = intval($toDateArr[1]);
        $toDateAY = intval($toDateArr[2]);
        if (false !== checkdate($toDateAM, $toDateAD, $toDateAY)) {
            $thisDate = $toDateAY.'-'.$toDateAM.'-'.$toDateAD;
            $conditionsArray[] = " a.dateOfAdmission <= '$thisDate' ";
        }
    }

    $fromDateD = $REQUEST_DATA['fromDateD'];
    if (!empty($fromDateD) and $fromDateD != '--') {
        $fromDateArr = explode('-',$fromDateD);
        $fromDateDM = intval($fromDateArr[0]);
        $fromDateDD = intval($fromDateArr[1]);
        $fromDateDY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateDM, $fromDateDD, $fromDateDY)) {
            $thisDate = $fromDateDY.'-'.$fromDateDM.'-'.$fromDateDD;
            $conditionsArray[] = " a.dateOfBirth >= '$thisDate' ";
        }
    }

    $toDateD = $REQUEST_DATA['toDateD'];
    if (!empty($toDateD) and $toDateD != '--') {
        $toDateArr = explode('-',$toDateD);
        $toDateDM = intval($toDateArr[0]);
        $toDateDD = intval($toDateArr[1]);
        $toDateDY = intval($toDateArr[2]);
        if (false !== checkdate($toDateDM, $toDateDD, $toDateDY)) {
            $thisDate = $toDateDY.'-'.$toDateDM.'-'.$toDateDD;
            $conditionsArray[] = " a.dateOfBirth <= '$thisDate' ";
        }
    }

    $gender = $REQUEST_DATA['gender'];
    if (!empty($gender)) {
        $conditionsArray[] = " a.studentGender = '$gender' ";
    }

    $categoryId = $REQUEST_DATA['$categoryId'];
    if (!empty($categoryId)) {
        $conditionsArray[] = " a.managementCategory = $categoryId ";
    }

    $quotaId = $REQUEST_DATA['quotaId'];
    if (!empty($quotaId)) {
        $conditionsArray[] = " a.quotaId IN ($quotaId) ";
    }

    $hostelId = $REQUEST_DATA['hostelId'];
    if (!empty($hostelId)) {
        $conditionsArray[] = " a.hostelId IN ('$hostelId') ";
    }

    $busStopId = $REQUEST_DATA['busStopId'];
    if (!empty($busStopId)) {
        $conditionsArray[] = " a.busStopId IN ('$busStopId') ";
    }

    $busRouteId = $REQUEST_DATA['busRouteId'];
    if (!empty($busRouteId)) {
        $conditionsArray[] = " a.busRouteId IN ($busRouteId) ";
    }

    $cityId = $REQUEST_DATA['cityId'];
   //city
    if (!empty($cityId)) {
        //$conditionsArray[] = " a.corrCityId IN ($citys) ";
        $conditionsArray[] = " (a.corrCityId IN ($cityId)) ";
    }

    $stateId = $REQUEST_DATA['stateId'];
    //states
    if (!empty($stateId)) {
        //$conditionsArray[] = " a.corrStateId IN ($states) ";
        $conditionsArray[] = " (a.corrStateId IN ($stateId) OR a.permStateId IN ($stateId)) ";
    }

    $countryId = $REQUEST_DATA['countryId'];
    //country
    if (!empty($countryId)) {
        //$conditionsArray[] = " a.corrCountryId IN ($cnts) ";
        $conditionsArray[] = " (a.corrCountryId IN ($countryId) OR a.permCountryId IN ($countryId)) ";
    }


    $universityId = $REQUEST_DATA['universityId'];
    if (!empty($universityId)) {
        $conditionsArray[] = " b.universityId IN ($universityId) ";
    }

    $instituteId = $REQUEST_DATA['instituteId'];
    if (!empty($instituteId)) {
        $conditionsArray[] = " b.instituteId IN ($instituteId) ";
    }

    $courseId = $REQUEST_DATA['courseId'];
    if (!empty($courseId)) {
        $conditionsArray[] = " ( (a.classId IN (SELECT DISTINCT(classId) FROM subject_to_class s WHERE s.subjectId IN ($courseId)))
                                 OR
                                 (a.classId IN (SELECT DISTINCT(classId) FROM student_optional_subject opt WHERE opt.subjectId IN ($courseId))) )";
    }

    $bloodGroup = $REQUEST_DATA['bloodGroup'];
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " a.studentBloodGroup IN ($bloodGroup) ";
    }

     //   ############ FOR ATTENDANCE FROM / TO ##############################
     $attendanceFrom = $REQUEST_DATA['attendanceFrom'];
     if (!empty($attendanceFrom) or !empty($attendanceTo)) {
         require_once(MODEL_PATH . "/StudentManager.inc.php");
         $studentManager = StudentManager::getInstance();

         $having = "";
         if (!empty($attendanceFrom)) {
              $having = " having percentage >= $attendanceFrom";
              $qryString .= "&attendanceFrom=$attendanceFrom";
         }
         $attendanceTo = $REQUEST_DATA['attendanceTo'];
         if (!empty($attendanceTo)) {
              if ($having != '') {
                    $having .= " and ";
              }
              else {
                  $having .= " having ";
              }
              $having .= " percentage <= $attendanceTo";
              $qryString .= "&attendanceTo=$attendanceTo";
         }

         $studentListArray = $studentManager->getShortAttendanceStudents($having);
         $studentIdList = UtilityManager::makeCSList($studentListArray, 'studentId');
         if($studentIdList != '') {
            $conditionsArray[] = " a.studentId IN ($studentIdList) ";
         }
         else {
            $conditionsArray[] = " s.studentId IN (0) ";
         }
     }
     //   ########################################################################


    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }



  //  $studentRecordArray = $studentReportsManager->getAllDetailsStudentList($conditions, $REQUEST_DATA['sortField'].' '.$REQUEST_DATA['sortOrderBy'], $limit);

  //  $cnt = count($studentRecordArray);
  //  $valueArray = array();
  //  for($i=0;$i<$cnt;$i++) {
  //      $valueArray[] = array_merge(array('srNo' => $i+1),$studentRecordArray[$i]);
  //  }
// Create fields based on the checkbox selected
    //if($REQUEST_DATA['groupId']==""){
    //    $filter=' DISTINCT(student.studentId) ,';
    //}
    global $sessionHandler;
    $userId= $sessionHandler->getSessionVariable('UserId');
    $roleId = $sessionHandler->getSessionVariable('RoleId');

    $userRoleArray = $studentReportsManager->getRoleUser($userId);
    $roleCount = $userRoleArray[0]['totalRecords'];

	
    if ($roleCount > 0) {

    $filter=isset($REQUEST_DATA['groupIdForm']) ?  ' IFNULL(CONCAT(GROUP_CONCAT(DISTINCT groupName ORDER BY groupName SEPARATOR ", ")),"'.NOT_APPLICABLE_STRING.'")  AS `Group`, a.studentId ,' : '';
    $filter.=isset($REQUEST_DATA['universityRollNo']) ?  'IF(IFNULL(universityRollNo,"")="","'.NOT_APPLICABLE_STRING.'",universityRollNo)  AS `University Roll No.` ,' : '';
    $filter.=isset($REQUEST_DATA['rollNoForm']) ?  'IF(IFNULL(rollNo,"")="","'.NOT_APPLICABLE_STRING.'",rollNo)   AS `College Roll No.` ,' : '';
    $filter.=isset($REQUEST_DATA['regNoForm']) ?  'IF(IFNULL(regNo,"")="","'.NOT_APPLICABLE_STRING.'",regNo)  AS `Reg. No.` ,' : '';
    $filter.=isset($REQUEST_DATA['classNameForm']) ?  "SUBSTRING_INDEX( b.className, '-' , -3 ) AS `Class Name` ," : "";
    $filter.=isset($REQUEST_DATA['firstName']) ?  "CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS `Name`  ," : '';
    $filter.=isset($REQUEST_DATA['fatherName']) ?  "IF(IFNULL(fatherName,'')='','".NOT_APPLICABLE_STRING."',fatherName)  AS `Father's Name` ," : "";
    $filter.=isset($REQUEST_DATA['motherName']) ?  "IF(IFNULL(motherName,'')='','".NOT_APPLICABLE_STRING."',motherName)  AS `Mother's Name` ," : "";
    $filter.=isset($REQUEST_DATA['guardianName']) ?  "IF(IFNULL(guardianName,'')='','".NOT_APPLICABLE_STRING."',guardianName) As `Guardian's Name` ," : '';
    $filter.=isset($REQUEST_DATA['bloodGroupForm']) ? "IF(IFNULL(studentBloodGroup,'0')='0','".NOT_APPLICABLE_STRING."',studentBloodGroup) AS `Blood Group` ," : "";
    $filter.=isset($REQUEST_DATA['studentEmail']) ?  "IF(IFNULL(studentEmail,'')='','".NOT_APPLICABLE_STRING."',studentEmail) As `Email` ," : "";
    $filter.=isset($REQUEST_DATA['dateOfBirth']) ?  "IFNULL(dateOfBirth,'".NOT_APPLICABLE_STRING."') AS DOB ," : "";

    $filter.=isset($REQUEST_DATA['studentGender']) ?  'IF(IFNULL(studentGender,"")="","'.NOT_APPLICABLE_STRING.'",studentGender) AS Gender ,' : '';

    //$filter.=isset($REQUEST_DATA['stateName']) ?  's1.stateName AS Domicile ,' : '';

    $filter.=isset($REQUEST_DATA['stateName']) ?  "IF(a.domicileId IS NULL OR a.domicileId='','".NOT_APPLICABLE_STRING."',(SELECT stateName from states where states.stateId=a.domicileId)) AS Domicile ," :"";
    $filter.=isset($REQUEST_DATA['quotaName']) ?  'IF(IFNULL(quotaName,"")="","'.NOT_APPLICABLE_STRING.'",quotaName) AS Quota ,' : '';

    $filter.=isset($REQUEST_DATA['isLeet']) ?  'isLeet AS IsLeet ,' : '';
    $filter.=isset($REQUEST_DATA['corrCityId']) ?  "IF(IFNULL(cityName,'')='','".NOT_APPLICABLE_STRING."',cityName) AS City ," : " ";
    $filter.=isset($REQUEST_DATA['countryName']) ?  "IF(IFNULL(countryName,'')='','".NOT_APPLICABLE_STRING."',countryName) AS Nationality ," : '';
    $filter.=isset($REQUEST_DATA['managementReference']) ?  "IF(IFNULL(managementReference,'')='','".NOT_APPLICABLE_STRING."',managementReference)  AS `Management Reference` ," : "";
    $filter.=isset($REQUEST_DATA['studentRemarks']) ?  'IF(IFNULL(studentRemarks,"")="","'.NOT_APPLICABLE_STRING.'",studentRemarks) AS Remarks ,' : '';


    //address
    $filter.=isset($REQUEST_DATA['corrAddress1']) ?  'IF(a.corrAddress1 IS NULL OR corrAddress1="","",CONCAT(corrAddress1,IFNULL(corrAddress2,""),"<br/>",
                                                      IF(a.corrCityId IS NULL OR a.corrCityId="","",(SELECT cityName from city where city.cityId=a.corrCityId))," ",
                                                      IF(a.corrStateId IS NULL OR a.corrStateId="","",(SELECT stateName from states where states.stateId=a.corrStateId)),"<br/>",
                                                      IF(a.corrCountryId IS NULL OR a.corrCountryId="","",(SELECT countryName from countries where countries.countryId=a.corrCountryId)),
                                                      IF(a.corrPinCode IS NULL OR a.corrPinCode="" ,"",CONCAT("-",a.corrPinCode)))) As `Correspondence Address` ,' : '';
    //IF(a.corrAddress2 IS NULL OR a.corrAddress2="","",(CONCAT("<br/>",corrAddress2,"<br/>",(SELECT cityName from city where city.cityId=a.corrCityId),",",(SELECT stateName from states where states.stateId=a.corrStateId),"<br/>",(SELECT countryName from countries where countries.countryId=a.corrCountryId ),IF(a.corrPinCode IS NULL OR a.corrPinCode="" ,"",CONCAT("-",a.corrPinCode)) ) ) )) )  As `Correspondence Address` ,' : '';
    //$filter.=isset($REQUEST_DATA['permAddress1']) ?  'IF(permAddress1 IS NULL OR permAddress1="","",CONCAT(permAddress1,"<br/>",IFNULL((SELECT cityName from city where city.cityId=a.permCityId),""),",",(SELECT stateName from states where states.stateId=a.permStateId),"<br/>",(SELECT countryName from countries where countries.countryId=a.permCountryId ),IF(a.permPinCode IS NULL OR a.permPinCode="" ,"",CONCAT("-",a.permPinCode)),IF(a.permAddress2 IS NULL OR a.permAddress2="","",(CONCAT("<br/>",permAddress2,"<br/>",(SELECT cityName from city where city.cityId=a.permCityId),",",(SELECT stateName from states where states.stateId=a.permStateId),"<br/>",(SELECT countryName from countries where countries.countryId=a.permCountryId ),IF(a.permPinCode IS NULL OR a.permPinCode="" ,"",CONCAT("-",a.permPinCode)) ) ) )) )  As `Permanent Address` ,' : '';
    $filter.=isset($REQUEST_DATA['permAddress1']) ?  'IF(a.permAddress1 IS NULL OR permAddress1="","",CONCAT(permAddress1,IFNULL(permAddress2,""),"<br/>",
                                                      IF(a.permCityId IS NULL OR a.permCityId="","",(SELECT cityName from city where city.cityId=a.permCityId))," ",
                                                      IF(a.permStateId IS NULL OR a.permStateId="","",(SELECT stateName from states where states.stateId=a.permStateId)),"<br/>",
                                                      IF(a.permCountryId IS NULL OR a.permCountryId="","",(SELECT countryName from countries where countries.countryId=a.permCountryId)),
                                                      IF(a.permPinCode IS NULL OR a.permPinCode="" ,"",CONCAT("-",a.permPinCode))))  As `Permanent Address` ,' : '';

    $filter.=isset($REQUEST_DATA['studentInActive']) ? 'IF(studentStatus=1,"Active","InActive") AS `Student Status`,' : '';

    $filter.=isset($REQUEST_DATA['feeReceiptNo']) ?  "IF(a.feeReceiptNo IS NULL OR a.feeReceiptNo='','".NOT_APPLICABLE_STRING."', a.feeReceiptNo) AS `Fee Receipt No.` ," : "";

    $filter.=isset($REQUEST_DATA['dateOfAdmission']) ?  'IF(IFNULL(dateOfAdmission,"")="","'.NOT_APPLICABLE_STRING.'",dateOfAdmission) AS `Date of Admission` ,' : '';
	
    $filter.=isset($REQUEST_DATA['studentUserName']) ?  'IF(IFNULL(userId,0)=0,"'.NOT_APPLICABLE_STRING.'",(SELECT userName FROM `user` WHERE userId=a.userId)) AS `Student User Name` ,' : ''; 
    $filter.=isset($REQUEST_DATA['fatherUserName']) ?  'IF(IFNULL(userId,0)=0,"'.NOT_APPLICABLE_STRING.'",(SELECT userName FROM `user` WHERE userId=a.fatherUserId)) AS `Father User Name` ,' : '';
    $filter.=isset($REQUEST_DATA['motherUserName']) ?  'IF(IFNULL(userId,0)=0,"'.NOT_APPLICABLE_STRING.'",(SELECT userName FROM `user` WHERE userId=a.motherUserId)) AS `Mother User Name` ,' : '';
    $filter.=isset($REQUEST_DATA['guardianUserName']) ?  'IF(IFNULL(userId,0)=0,"'.NOT_APPLICABLE_STRING.'",(SELECT userName FROM `user` WHERE userId=a.guardianUserId)) AS `Guardian User Name` ,' : '';
	$filter.=isset($REQUEST_DATA['instituteName']) ?  'ins.instituteCode AS `Institute Name` ,' : '';

    // Pre Admission   ---- START ----
    if($REQUEST_DATA['includePreAdmission']=='1') {
      $showPreAdmissionField = "DISTINCT CONCAT(IFNULL(ac.previousRollNo,''),'!~!!~!',IFNULL(ac.previousSession,''),'!~!!~!',
                                       IFNULL(ac.previousInstitute,''),'!~!!~!',IFNULL(ac.previousBoard,''),'!~!!~!',
                                       IFNULL(ac.previousMarks,''),'!~!!~!',IFNULL(ac.previousMaxMarks,''),'!~!!~!',        
                                       IFNULL(ac.previousPercentage,''),'!~!!~!',IFNULL(ac.previousEducationStream,''))  ";
    }
    else {
      $showPreAdmissionField = "DISTINCT ac.previousPercentage"; 
    } 
    
    
    $filter.=isset($REQUEST_DATA['mks_1']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=1 AND ac.studentId = a.studentId ),'".NOT_APPLICABLE_STRING."') AS `Marks in 10th` ," : "";

     $filter.=isset($REQUEST_DATA['mks_2']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=2 AND ac.studentId = a.studentId ),'".NOT_APPLICABLE_STRING."') AS `Marks in 12th` ," : "";

    $filter.=isset($REQUEST_DATA['mks_3']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=3 AND ac.studentId = a.studentId ),'".NOT_APPLICABLE_STRING."') AS `Marks in Graduation` ," : "";

    $filter.=isset($REQUEST_DATA['mks_4']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=4 AND ac.studentId = a.studentId ),'".NOT_APPLICABLE_STRING."') AS `PG (if any)` ," : "";

     $filter.=isset($REQUEST_DATA['mks_5']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=5 AND ac.studentId = a.studentId),'".NOT_APPLICABLE_STRING."') AS `Any Diploma` ," : "";

    $filter.=isset($REQUEST_DATA['compRollNo']) ?  "IF(a.compExamRollNo IS NULL OR a.compExamRollNo='','".NOT_APPLICABLE_STRING."', a.compExamRollNo) AS `Comp. Exam. Roll No.` ," : "";

     $filter.=isset($REQUEST_DATA['compExamBy']) ?  "IF(a.compExamBy IS NULL OR a.compExamBy='','".NOT_APPLICABLE_STRING."', a.compExamBy) AS `Comp. Exam. By` ," : "";

    $filter.=isset($REQUEST_DATA['compRank']) ?  "IF(a.compExamRank IS NULL OR a.compExamRank='','".NOT_APPLICABLE_STRING."', a.compExamRank) AS `Rank` ," : "";
    // Pre Admission   ---- END ----

    //$filter.=isset($REQUEST_DATA['permAddress1']) ?  "permAddress1 AS `Permanent Address` ," : '';
    //mob no
    $filter.=isset($REQUEST_DATA['studentMobileNo']) ?  'IFNULL(studentMobileNo,"'.NOT_APPLICABLE_STRING.'") As  `Student Mobile No.` ,' : '';
    $filter.=isset($REQUEST_DATA['guardianMobileNo']) ?  'IFNULL(guardianMobileNo,"'.NOT_APPLICABLE_STRING.'")  As `Guardian Mobile No.` ,' : '';
    $filter.=isset($REQUEST_DATA['fatherMobileNo']) ?  'IFNULL(fatherMobileNo,"'.NOT_APPLICABLE_STRING.'")  As `Father Mobile No.` ,' : '';
    $filter.=isset($REQUEST_DATA['motherMobileNo']) ?  'IFNULL(motherMobileNo,"'.NOT_APPLICABLE_STRING.'")  As `Mother Mobile No.` ,' : '';

    $filter.=isset($REQUEST_DATA['studentEmailId']) ?  "IFNULL(studentEmail,'".NOT_APPLICABLE_STRING."') As  `Student's Email` ," : '';
    $filter.=isset($REQUEST_DATA['fatherEmailId']) ?  "IFNULL(fatherEmail,'".NOT_APPLICABLE_STRING."')  As `Father's Email` ," : '';
    $filter.=isset($REQUEST_DATA['motherEmailId']) ?  "IFNULL(motherEmail,'".NOT_APPLICABLE_STRING."')  As `Mother's Email` ," : '';
    $filter.=isset($REQUEST_DATA['guardianEmailId']) ?  "IFNULL(guardianEmail,'".NOT_APPLICABLE_STRING."')  As `Guardian's Email` ," : '';

  
    $filter.=isset($REQUEST_DATA['fatherAddress1']) ?  "CONCAT(fatherAddress1,fatherAddress2) AS `Father's Address` ," : '';
    $filter.=isset($REQUEST_DATA['motherAddress1']) ?  "CONCAT(motherAddress1,motherAddress2) AS `Mother's Address` ," : '';
    $filter.=isset($REQUEST_DATA['guardianAddress1']) ?  "CONCAT(guardianAddress1,guardianAddress2) AS `Guardian's Address` ," : '';

    $filter.=isset($REQUEST_DATA['busStopIdForm']) ?  'IF(IFNULL(stopAbbr,"")="","'.NOT_APPLICABLE_STRING.'",stopAbbr)  AS `Bus Stop` ,' : '';
    $filter.=isset($REQUEST_DATA['busRouteForm']) ?   'IF(IFNULL(routeCode,"")="","'.NOT_APPLICABLE_STRING.'",routeCode)  AS `Bus Route` ,' : '';

    $filter.=isset($REQUEST_DATA['hostelNameForm']) ?  'IF(IFNULL(hostelCode,"")="","'.NOT_APPLICABLE_STRING.'",hostelCode)  AS `Hostel Name`,' : '';
    $filter.=isset($REQUEST_DATA['hostelRoomNoForm']) ?  'IF(IFNULL(roomName,"")="","'.NOT_APPLICABLE_STRING.'",roomName)  AS `Hostel Room No.`,' : '';

    $filter.=isset($REQUEST_DATA['studentPhoto']) ?  'studentPhoto AS Photo ,' : '';


    $filter=substr(trim($filter),0,-1);

    $filter.= " FROM  classes_visible_to_role cvtr, student a
                      LEFT JOIN class b ON a.classId = b.classId
                      LEFT JOIN institute ins ON ins.instituteId = b.instituteId
                      LEFT JOIN student_groups sg ON  a.classId = sg.classId  AND a.studentId = sg.studentId
                      LEFT JOIN student_optional_subject sos ON  a.classId = sos.classId  AND a.studentId = sos.studentId
                      LEFT JOIN `group` gr ON (gr.groupId = sg.groupId OR gr.groupId = sos.groupId) ";


   $filter.=isset($REQUEST_DATA['hostelNameForm'])   ?  ' LEFT JOIN hostel      ON  hostel.hostelId=a.hostelId   ' : '';
   $filter.=isset($REQUEST_DATA['hostelRoomNoForm']) ?  ' LEFT JOIN hostel_room ON  hostel_room.hostelRoomId=a.hostelRoomId  ' : '';
   $filter.=isset($REQUEST_DATA['busStopIdForm'])    ?  ' LEFT JOIN bus_stop    ON  bus_stop.busStopId=a.busStopId  ' : '';
   $filter.=isset($REQUEST_DATA['busRouteForm'])     ?  ' LEFT JOIN bus_route   ON  bus_route.busRouteId=a.busRouteId ' : '';
   $filter.=isset($REQUEST_DATA['quotaName'])        ?  ' LEFT JOIN quota       ON a.quotaId=quota.quotaId ' : '';
   $filter.=isset($REQUEST_DATA['corrCityId'])  ? ' LEFT JOIN city      ON (a.corrCityId = city.cityId) ' : '';
   $filter.=isset($REQUEST_DATA['stateName'])   ? ' LEFT JOIN states    ON (a.corrStateId = states.stateId AND a.permStateId = states.stateId)' : '';
   $filter.=isset($REQUEST_DATA['countryName']) ? ' LEFT JOIN countries ON (a.corrCountryId = countries.countryId AND a.permCountryId = countries.countryId)' : '';

   //$filter.=isset($REQUEST_DATA['groupIdForm']) ?  ',`group`, student_groups scs ' : '';
   $countFields=$countFields-22;

   $filter.=" WHERE
                    cvtr.classId = a.classId AND cvtr.classId = b.classId AND cvtr.groupId = gr.groupId AND
                    cvtr.classId = gr.classId AND gr.classId = b.classId AND sg.studentId = a.studentId AND
                    sg.classId = b.classId AND sg.groupId = gr.groupId AND sg.classId = cvtr.classId AND
                    sg.classId = gr.classId AND cvtr.userId = $userId AND cvtr.roleId = $roleId ";
    if($incAllInsitute==0) {
       $filter .= " AND  b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'";  	 
    }
    
    if($REQUEST_DATA['studentStatusId']!=4) {	
     $filter  .= " AND studentStatus = '".$REQUEST_DATA['studentStatusId']."'";	
   }
   else {
      $filter  .= " AND  b.isActive > '".$REQUEST_DATA['studentStatusId']."'";	
   }

    //$conditions.=isset($REQUEST_DATA['groupIdForm']) ?  ' ORDER By scs.studentId ': ' ';

        if($REQUEST_DATA['start']!='') {
            if($REQUEST_DATA['start']=='0') {
              $s = 0;
            }
            else {
              $s = (($REQUEST_DATA['start'])-1);
            }
            $limit = ' LIMIT '.(($REQUEST_DATA['start'])-1).','.$REQUEST_DATA['end'];
        }
        else {
            $limit = '';
         }
    }
    else {

    $filter=isset($REQUEST_DATA['groupIdForm']) ?  ' IFNULL(CONCAT(GROUP_CONCAT(DISTINCT groupName ORDER BY groupName SEPARATOR ", ")),"'.NOT_APPLICABLE_STRING.'") AS `Group`, a.studentId ,' : '';
    $filter.=isset($REQUEST_DATA['universityRollNo']) ?  'IF(IFNULL(universityRollNo,"")="","'.NOT_APPLICABLE_STRING.'",universityRollNo)  AS `University Roll No.` ,' : '';
    $filter.=isset($REQUEST_DATA['rollNoForm']) ?  'IF(IFNULL(rollNo,"")="","'.NOT_APPLICABLE_STRING.'",rollNo)   AS `College Roll No.` ,' : '';
    $filter.=isset($REQUEST_DATA['regNoForm']) ?  'IF(IFNULL(regNo,"")="","'.NOT_APPLICABLE_STRING.'",regNo)  AS `Reg. No.` ,' : '';
    $filter.=isset($REQUEST_DATA['classNameForm']) ?  "SUBSTRING_INDEX( b.className, '-' , -3 ) AS `Class Name` ," : "";
    $filter.=isset($REQUEST_DATA['firstName']) ?  "CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS `Name`  ," : '';
    $filter.=isset($REQUEST_DATA['fatherName']) ?  "IF(IFNULL(fatherName,'')='','".NOT_APPLICABLE_STRING."',fatherName)  AS `Father's Name` ," : "";
    $filter.=isset($REQUEST_DATA['motherName']) ?  "IF(IFNULL(motherName,'')='','".NOT_APPLICABLE_STRING."',motherName)  AS `Mother's Name` ," : "";
    $filter.=isset($REQUEST_DATA['guardianName']) ?  "IF(IFNULL(guardianName,'')='','".NOT_APPLICABLE_STRING."',guardianName) As `Guardian's Name` ," : '';
    $filter.=isset($REQUEST_DATA['bloodGroupForm']) ? "IF(IFNULL(studentBloodGroup,'0')='0','".NOT_APPLICABLE_STRING."',studentBloodGroup) AS `Blood Group` ," : "";
    $filter.=isset($REQUEST_DATA['studentEmail']) ?  "IF(IFNULL(studentEmail,'')='','".NOT_APPLICABLE_STRING."',studentEmail) As `Email` ," : "";
    $filter.=isset($REQUEST_DATA['dateOfBirth']) ?  "IFNULL(dateOfBirth,'".NOT_APPLICABLE_STRING."') AS DOB ," : "";

    $filter.=isset($REQUEST_DATA['studentGender']) ?  'IF(IFNULL(studentGender,"")="","'.NOT_APPLICABLE_STRING.'",studentGender) AS Gender ,' : '';

    //$filter.=isset($REQUEST_DATA['stateName']) ?  's1.stateName AS Domicile ,' : '';

    $filter.=isset($REQUEST_DATA['stateName']) ?  "IF(a.domicileId IS NULL OR a.domicileId='','".NOT_APPLICABLE_STRING."',(SELECT stateName from states where states.stateId=a.domicileId)) AS Domicile ," :"";
    $filter.=isset($REQUEST_DATA['quotaName']) ?  'IF(IFNULL(quotaName,"")="","'.NOT_APPLICABLE_STRING.'",quotaName) AS Quota ,' : '';

    $filter.=isset($REQUEST_DATA['isLeet']) ?  'isLeet AS IsLeet ,' : '';
    $filter.=isset($REQUEST_DATA['corrCityId']) ?  "IF(IFNULL(cityName,'')='','".NOT_APPLICABLE_STRING."',cityName) AS City ," : " ";
    $filter.=isset($REQUEST_DATA['countryName']) ?  "IF(IFNULL(countryName,'')='','".NOT_APPLICABLE_STRING."',countryName) AS Nationality ," : '';
    $filter.=isset($REQUEST_DATA['managementReference']) ?  "IF(IFNULL(managementReference,'')='','".NOT_APPLICABLE_STRING."',managementReference)  AS `Management Reference` ," : "";
    $filter.=isset($REQUEST_DATA['studentRemarks']) ?  'IF(IFNULL(studentRemarks,"")="","'.NOT_APPLICABLE_STRING.'",studentRemarks) AS Remarks ,' : '';

    //address
    $filter.=isset($REQUEST_DATA['corrAddress1']) ?  'IF(a.corrAddress1 IS NULL OR corrAddress1="","",CONCAT(corrAddress1,IFNULL(corrAddress2,""),"<br/>",
                                                      IF(a.corrCityId IS NULL OR a.corrCityId="","",(SELECT cityName from city where city.cityId=a.corrCityId))," ",
                                                      IF(a.corrStateId IS NULL OR a.corrStateId="","",(SELECT stateName from states where states.stateId=a.corrStateId)),"<br/>",
                                                      IF(a.corrCountryId IS NULL OR a.corrCountryId="","",(SELECT countryName from countries where countries.countryId=a.corrCountryId)),
                                                      IF(a.corrPinCode IS NULL OR a.corrPinCode="" ,"",CONCAT("-",a.corrPinCode)))) As `Correspondence Address` ,' : '';
    //IF(a.corrAddress2 IS NULL OR a.corrAddress2="","",(CONCAT("<br/>",corrAddress2,"<br/>",(SELECT cityName from city where city.cityId=a.corrCityId),",",(SELECT stateName from states where states.stateId=a.corrStateId),"<br/>",(SELECT countryName from countries where countries.countryId=a.corrCountryId ),IF(a.corrPinCode IS NULL OR a.corrPinCode="" ,"",CONCAT("-",a.corrPinCode)) ) ) )) )  As `Correspondence Address` ,' : '';
    //$filter.=isset($REQUEST_DATA['permAddress1']) ?  'IF(permAddress1 IS NULL OR permAddress1="","",CONCAT(permAddress1,"<br/>",IFNULL((SELECT cityName from city where city.cityId=a.permCityId),""),",",(SELECT stateName from states where states.stateId=a.permStateId),"<br/>",(SELECT countryName from countries where countries.countryId=a.permCountryId ),IF(a.permPinCode IS NULL OR a.permPinCode="" ,"",CONCAT("-",a.permPinCode)),IF(a.permAddress2 IS NULL OR a.permAddress2="","",(CONCAT("<br/>",permAddress2,"<br/>",(SELECT cityName from city where city.cityId=a.permCityId),",",(SELECT stateName from states where states.stateId=a.permStateId),"<br/>",(SELECT countryName from countries where countries.countryId=a.permCountryId ),IF(a.permPinCode IS NULL OR a.permPinCode="" ,"",CONCAT("-",a.permPinCode)) ) ) )) )  As `Permanent Address` ,' : '';
    $filter.=isset($REQUEST_DATA['permAddress1']) ?  'IF(a.permAddress1 IS NULL OR permAddress1="","",CONCAT(permAddress1,IFNULL(permAddress2,""),"<br/>",
                                                      IF(a.permCityId IS NULL OR a.permCityId="","",(SELECT cityName from city where city.cityId=a.permCityId))," ",
                                                      IF(a.permStateId IS NULL OR a.permStateId="","",(SELECT stateName from states where states.stateId=a.permStateId)),"<br/>",
                                                      IF(a.permCountryId IS NULL OR a.permCountryId="","",(SELECT countryName from countries where countries.countryId=a.permCountryId)),
                                                      IF(a.permPinCode IS NULL OR a.permPinCode="" ,"",CONCAT("-",a.permPinCode))))  As `Permanent Address` ,' : '';

    $filter.=isset($REQUEST_DATA['studentInActive']) ? 'IF(studentStatus=1,"Active","InActive") AS `Student Status`,' : '';

    $filter.=isset($REQUEST_DATA['feeReceiptNo']) ?  "IF(a.feeReceiptNo IS NULL OR a.feeReceiptNo='','".NOT_APPLICABLE_STRING."', a.feeReceiptNo) AS `Fee Receipt No.` ," : "";
	$filter.=isset($REQUEST_DATA['dateOfAdmission']) ?  'IF(IFNULL(dateOfAdmission,"")="","'.NOT_APPLICABLE_STRING.'",dateOfAdmission) AS `Date of Admission` ,' : '';

    $filter.=isset($REQUEST_DATA['studentUserName']) ?  'IF(IFNULL(userId,0)=0,"'.NOT_APPLICABLE_STRING.'",(SELECT userName FROM `user` WHERE userId=a.userId)) AS `Student User Name` ,' : ''; 
    $filter.=isset($REQUEST_DATA['fatherUserName']) ?  'IF(IFNULL(userId,0)=0,"'.NOT_APPLICABLE_STRING.'",(SELECT userName FROM `user` WHERE userId=a.fatherUserId)) AS `Father User Name` ,' : '';
    $filter.=isset($REQUEST_DATA['motherUserName']) ?  'IF(IFNULL(userId,0)=0,"'.NOT_APPLICABLE_STRING.'",(SELECT userName FROM `user` WHERE userId=a.motherUserId)) AS `Mother User Name` ,' : '';
    $filter.=isset($REQUEST_DATA['guardianUserName']) ?  'IF(IFNULL(userId,0)=0,"'.NOT_APPLICABLE_STRING.'",(SELECT userName FROM `user` WHERE userId=a.guardianUserId)) AS `Guardian User Name` ,' : '';
	$filter.=isset($REQUEST_DATA['instituteName']) ?  'ins.instituteCode AS `Institute Name` ,' : '';
    // Pre Admission   ---- START ----
    if($REQUEST_DATA['includePreAdmission']=='1') {
      $showPreAdmissionField = "DISTINCT CONCAT(IFNULL(ac.previousRollNo,''),'!~!!~!',IFNULL(ac.previousSession,''),'!~!!~!',
                                       IFNULL(ac.previousInstitute,''),'!~!!~!',IFNULL(ac.previousBoard,''),'!~!!~!',
                                       IFNULL(ac.previousMarks,''),'!~!!~!',IFNULL(ac.previousMaxMarks,''),'!~!!~!',        
                                       IFNULL(ac.previousPercentage,''),'!~!!~!',IFNULL(ac.previousEducationStream,''))  ";
    }
    else {
      $showPreAdmissionField = "DISTINCT ac.previousPercentage"; 
    } 
     
    $filter.=isset($REQUEST_DATA['mks_1']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=1 AND ac.studentId = a.studentId ),'".NOT_APPLICABLE_STRING."') AS `Marks in 10th` ," : "";

     $filter.=isset($REQUEST_DATA['mks_2']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=2 AND ac.studentId = a.studentId ),'".NOT_APPLICABLE_STRING."') AS `Marks in 12th` ," : "";

    $filter.=isset($REQUEST_DATA['mks_3']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=3 AND ac.studentId = a.studentId ),'".NOT_APPLICABLE_STRING."') AS `Marks in Graduation` ," : "";

    $filter.=isset($REQUEST_DATA['mks_4']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=4 AND ac.studentId = a.studentId ),'".NOT_APPLICABLE_STRING."') AS `PG (if any)` ," : "";

     $filter.=isset($REQUEST_DATA['mks_5']) ?  " IFNULL((SELECT $showPreAdmissionField FROM student_academic ac WHERE ac.previousClassId=5 AND ac.studentId = a.studentId),'".NOT_APPLICABLE_STRING."') AS `Any Diploma` ," : "";

    $filter.=isset($REQUEST_DATA['compRollNo']) ?  "IF(a.compExamRollNo IS NULL OR a.compExamRollNo='','".NOT_APPLICABLE_STRING."', a.compExamRollNo) AS `Comp. Exam. Roll No.` ," : "";

    $filter.=isset($REQUEST_DATA['compExamBy']) ?  "IF(a.compExamBy IS NULL OR a.compExamBy='','".NOT_APPLICABLE_STRING."', a.compExamBy) AS `Comp. Exam. By` ," : "";

    $filter.=isset($REQUEST_DATA['compRank']) ?  "IF(a.compExamRank IS NULL OR a.compExamRank='','".NOT_APPLICABLE_STRING."', a.compExamRank) AS `Rank` ," : "";
    // Pre Admission   ---- END ----


    //$filter.=isset($REQUEST_DATA['permAddress1']) ?  "permAddress1 AS `Permanent Address` ," : '';
    //mob no
    $filter.=isset($REQUEST_DATA['studentMobileNo']) ?  'IFNULL(studentMobileNo,"'.NOT_APPLICABLE_STRING.'") As  `Student Mobile No.` ,' : '';
    $filter.=isset($REQUEST_DATA['guardianMobileNo']) ?  'IFNULL(guardianMobileNo,"'.NOT_APPLICABLE_STRING.'")  As `Guardian Mobile No.` ,' : '';
    $filter.=isset($REQUEST_DATA['fatherMobileNo']) ?  'IFNULL(fatherMobileNo,"'.NOT_APPLICABLE_STRING.'")  As `Father Mobile No.` ,' : '';
    $filter.=isset($REQUEST_DATA['motherMobileNo']) ?  'IFNULL(motherMobileNo,"'.NOT_APPLICABLE_STRING.'")  As `Mother Mobile No.` ,' : '';
    


    $filter.=isset($REQUEST_DATA['fatherAddress1']) ?  "CONCAT(fatherAddress1,fatherAddress2) AS `Father's Address` ," : '';
    $filter.=isset($REQUEST_DATA['motherAddress1']) ?  "CONCAT(motherAddress1,motherAddress2) AS `Mother's Address` ," : '';
    $filter.=isset($REQUEST_DATA['guardianAddress1']) ?  "CONCAT(guardianAddress1,guardianAddress2) AS `Guardian's Address` ," : '';

    $filter.=isset($REQUEST_DATA['studentEmailId']) ?  "IFNULL(studentEmail,'".NOT_APPLICABLE_STRING."') As  `Student's Email` ," : '';
    $filter.=isset($REQUEST_DATA['fatherEmailId']) ?  "IFNULL(fatherEmail,'".NOT_APPLICABLE_STRING."')  As `Father's Email` ," : '';
    $filter.=isset($REQUEST_DATA['motherEmailId']) ?  "IFNULL(motherEmail,'".NOT_APPLICABLE_STRING."')  As `Mother's Email` ," : '';
    $filter.=isset($REQUEST_DATA['guardianEmailId']) ?  "IFNULL(guardianEmail,'".NOT_APPLICABLE_STRING."')  As `Guardian's Email` ," : '';

    
    $filter.=isset($REQUEST_DATA['busStopIdForm']) ?  'IF(IFNULL(stopAbbr,"")="","'.NOT_APPLICABLE_STRING.'",stopAbbr)  AS `Bus Stop` ,' : '';
    $filter.=isset($REQUEST_DATA['busRouteForm']) ?   'IF(IFNULL(routeCode,"")="","'.NOT_APPLICABLE_STRING.'",routeCode)  AS `Bus Route` ,' : '';

    $filter.=isset($REQUEST_DATA['hostelNameForm']) ?  'IF(IFNULL(hostelCode,"")="","'.NOT_APPLICABLE_STRING.'",hostelCode)  AS `Hostel Name`,' : '';
    $filter.=isset($REQUEST_DATA['hostelRoomNoForm']) ?  'IF(IFNULL(roomName,"")="","'.NOT_APPLICABLE_STRING.'",roomName)  AS `Hostel Room No.`,' : '';

    $filter.=isset($REQUEST_DATA['studentPhoto']) ?  'studentPhoto AS Photo ,' : '';

    $filter=substr(trim($filter),0,-1);
   //$filter.= "FROM student ";
   //if(isset($REQUEST_DATA['groupIdForm'])) {
     $filter.=  " FROM
                        student a
                          LEFT JOIN class b ON a.classId = b.classId
                          LEFT JOIN institute ins ON ins.instituteId = b.instituteId
                          LEFT JOIN student_groups scs ON  a.classId = scs.classId  AND a.studentId = scs.studentId
                          LEFT JOIN student_optional_subject sos ON  a.classId = sos.classId AND a.studentId = sos.studentId
                          LEFT JOIN `group` ON (group.groupId = scs.groupId OR group.groupId = sos.groupId) ";


   //}
   //else {
   //   $filter.= " FROM class b, student a ";
   //}

   $filter.=isset($REQUEST_DATA['hostelNameForm'])   ?  ' LEFT JOIN hostel      ON  hostel.hostelId=a.hostelId   ' : '';
   $filter.=isset($REQUEST_DATA['hostelRoomNoForm']) ?  ' LEFT JOIN hostel_room ON  hostel_room.hostelRoomId=a.hostelRoomId  ' : '';
   $filter.=isset($REQUEST_DATA['busStopIdForm'])    ?  ' LEFT JOIN bus_stop    ON  bus_stop.busStopId=a.busStopId  ' : '';
   $filter.=isset($REQUEST_DATA['busRouteForm'])     ?  ' LEFT JOIN bus_route   ON  bus_route.busRouteId=a.busRouteId ' : '';
   $filter.=isset($REQUEST_DATA['quotaName'])        ?  ' LEFT JOIN quota       ON a.quotaId=quota.quotaId ' : '';
   $filter.=isset($REQUEST_DATA['corrCityId'])  ? ' LEFT JOIN city      ON (a.corrCityId = city.cityId ) ' : '';
   $filter.=isset($REQUEST_DATA['stateName'])   ? ' LEFT JOIN states    ON (a.corrStateId = states.stateId AND a.permStateId = states.stateId)' : '';
   $filter.=isset($REQUEST_DATA['countryName']) ? ' LEFT JOIN countries ON (a.corrCountryId = countries.countryId AND a.permCountryId = countries.countryId)' : '';

   $countFields=$countFields-32;

   //$filter.=" WHERE  a.classId = b.classId AND ";
   if($REQUEST_DATA['studentStatusId']!=4) {	
     $filter .= " WHERE studentStatus = '".$REQUEST_DATA['studentStatusId']."'";	
   }
   else {
      $filter  .= " WHERE  b.isActive > '".$REQUEST_DATA['studentStatusId']."'";	
   }	

   // creates WHERE condition of the query by using joins among the fields of the table
   
   //$filter.=isset($REQUEST_DATA['studentInActive']) ?  'studentStatus = 0 AND ' : 'studentStatus = 1 AND ';
   if($incAllInsitute==0)
   {
   $filter .= "AND b.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."' ";
    }           // AND b.sessionId= '".$sessionHandler->getSessionVariable('SessionId')."'";

 //$filter.=' $conditions ';
// print_r($filter);
//   $arr=explode(CLASS_SEPRATOR,$REQUEST_DATA['degree']);
/*   $classFilter=" WHERE universityId ='".$arr[0]."' AND degreeId='".$arr[1]."' AND branchId='".$arr[2]."' AND studyPeriodId='".$REQUEST_DATA['periodicityId']."' AND batchId='".$REQUEST_DATA['batchId']."' ";
    $classIdArray= $reportManager->getScClassId($classFilter);

      $filter.="student.classId='".$classIdArray[0]['classId']."'";
*/    //  echo $conditions;
     //$conditions.=  isset($REQUEST_DATA['groupIdForm']) ?  ' GROUP BY scs.studentId ' : '';
   /*  $conditions.=  ' GROUP BY a.studentId ';
     $conditions.=" ORDER BY a.rollNo "; */

    //$conditions.=isset($REQUEST_DATA['groupIdForm']) ?  ' ORDER By scs.studentId ': ' ';

        if($REQUEST_DATA['start']!='') {
            if($REQUEST_DATA['start']=='0') {
              $s = 0;
            }
            else {
              $s = (($REQUEST_DATA['start'])-1);
            }
            $limit = ' LIMIT '.(($REQUEST_DATA['start'])-1).','.$REQUEST_DATA['end'];
        }
        else {
            $limit = '';
        }
    }



    // Blank column condition  START
    $blankConditions='';
    if($incAll==1) {
        $blankColumnCond=array();

        if($REQUEST_DATA['groupIdForm']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['groupIdForm']) ?  " IFNULL('Group','".NOT_APPLICABLE_STRING."')='".NOT_APPLICABLE_STRING."' " : '';
        }

        if($REQUEST_DATA['universityRollNo']!='') {
            $blankColumnCond[]=isset($REQUEST_DATA['universityRollNo']) ? " IFNULL(universityRollNo,'')='' " : '';
        }

        if($REQUEST_DATA['rollNoForm']!='') {
           $blankColumnCond[]=isset($REQUEST_DATA['rollNoForm']) ? " IFNULL(rollNo,'')='' " : '';
        }

        if($REQUEST_DATA['regNoForm']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['regNoForm']) ? " IFNULL(regNo,'')='' " : '';
        }

        if($REQUEST_DATA['classNameForm']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['classNameForm']) ? ' IFNULL(b.className,"")="" ' : '';
        }

        if($REQUEST_DATA['firstName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['firstName']) ? ' IFNULL(CONCAT(IFNULL(firstName,""),IFNULL(lastName,"")),"")="" ' : '';
        }

        if($REQUEST_DATA['fatherName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['fatherName']) ? ' IFNULL(fatherName,"")="" ' : '';
        }

        if($REQUEST_DATA['motherName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['motherName']) ? ' IFNULL(motherName,"")="" ' : '';
        }

        if($REQUEST_DATA['guardianName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['guardianName']) ? ' IFNULL(guardianName,"")="" ' : '';
        }

        if($REQUEST_DATA['bloodGroupForm']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['bloodGroupForm']) ? ' IFNULL(studentBloodGroup,"0")="0" ' : '';
        }

        if($REQUEST_DATA['studentEmail']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['studentEmail']) ? ' IFNULL(studentEmail,"")="" ' : '';
        }

        if($REQUEST_DATA['dateOfBirth']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['dateOfBirth']) ? '  dateOfBirth="0000-00-00" ' : '';
        }

        if($REQUEST_DATA['studentGender']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['studentGender']) ? ' IFNULL(studentGender,"")="" ' : '';
        }

        if($REQUEST_DATA['stateName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['stateName']) ? ' IFNULL(a.domicileId,"")="" ' : '';
        }

        if($REQUEST_DATA['quotaName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['quotaName']) ? ' IFNULL(quotaName,"")="" ' : '';
        }

        if($REQUEST_DATA['isLeet']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['isLeet']) ? ' IFNULL(isLeet,"")="" ' : '';
        }

        if($REQUEST_DATA['corrCityId']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['corrCityId']) ? ' IFNULL(cityName,"")="" ' : '';
        }

        if($REQUEST_DATA['countryName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['countryName']) ? ' IFNULL(countryName,"")="" ' : '';
        }

        if($REQUEST_DATA['managementReference']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['managementReference']) ? ' IFNULL(managementReference,"'.NOT_APPLICABLE_STRING.'")="'.NOT_APPLICABLE_STRING.'" ' : '';
        }

        if($REQUEST_DATA['studentRemarks']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['studentRemarks']) ? ' IFNULL(studentRemarks,"")="" ' : '';
        }
		
        if($REQUEST_DATA['studentUserName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['studentUserName']) ? " (IFNULL(a.userId,'')='' OR IFNULL(a.userId,'')=0) " : '';
        }
		
		if($REQUEST_DATA['fatherUserName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['fatherUserName']) ? " (IFNULL(a.fatherUserId,'')='' OR IFNULL(a.fatherUserId,'')=0) " : '';
        }
		if($REQUEST_DATA['motherUserName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['motherUserName']) ? " (IFNULL(a.motherUserId,'')='' OR IFNULL(a.motherUserId,'')=0) " : '';
        }
		if($REQUEST_DATA['guardianUserName']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['guardianUserName']) ? " (IFNULL(a.guardianId,'')='' OR IFNULL(a.guardianId,'')=0) " : '';
        }
        if($REQUEST_DATA['corrAddress1']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['corrAddress1']) ? " IFNULL('Correspondence Address','')='' " : '';
        }

        if($REQUEST_DATA['permAddress1']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['permAddress1']) ? " IFNULL('Permanent Address','')='' " : '';
        }

        if($REQUEST_DATA['feeReceiptNo']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['feeReceiptNo']) ? " IFNULL('Fee Receipt No.','".NOT_APPLICABLE_STRING."')='".NOT_APPLICABLE_STRING."' " : '';
        }

        if($REQUEST_DATA['studentMobileNo']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['studentMobileNo']) ? ' IFNULL(studentMobileNo,"")="" ' : '';
        }

        if($REQUEST_DATA['guardianMobileNo']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['guardianMobileNo']) ? ' IFNULL(guardianMobileNo,"")="" ' : '';
        }

        if($REQUEST_DATA['fatherMobileNo']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['fatherMobileNo']) ? ' IFNULL(fatherMobileNo,"")="" ' : '';
        }

        if($REQUEST_DATA['motherMobileNo']!='') {
         $blankColumnCond[]=isset($REQUEST_DATA['motherMobileNo']) ? ' IFNULL(motherMobileNo,"")="" ' : '';
        }


        if($REQUEST_DATA['fatherAddress1']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['fatherAddress1']) ? " IFNULL(CONCAT(fatherAddress1,fatherAddress2),'')='' " : '';
        }

        if($REQUEST_DATA['motherAddress1']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['motherAddress1']) ? " IFNULL(CONCAT(motherAddress1,motherAddress2),'')='' " : '';
        }

        if($REQUEST_DATA['guardianAddress1']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['guardianAddress1']) ? " IFNULL(CONCAT(guardianAddress1,guardianAddress2),'')='' " : '';
        }

        if($REQUEST_DATA['mks_1']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['mks_1']) ? " IFNULL((SELECT DISTINCT ac.previousPercentage FROM student_academic ac
          WHERE ac.previousClassId=1 AND ac.studentId = a.studentId),'')='' " : '';
        }

        if($REQUEST_DATA['mks_2']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['mks_2']) ? " IFNULL((SELECT DISTINCT ac.previousPercentage FROM student_academic ac
          WHERE ac.previousClassId=2 AND ac.studentId = a.studentId),'')='' " : '';
        }

        if($REQUEST_DATA['mks_3']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['mks_3']) ? " IFNULL((SELECT DISTINCT ac.previousPercentage FROM student_academic ac
          WHERE ac.previousClassId=3 AND ac.studentId = a.studentId),'')='' " : '';
        }

        if($REQUEST_DATA['mks_4']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['mks_4']) ? " IFNULL((SELECT DISTINCT ac.previousPercentage FROM student_academic ac
          WHERE ac.previousClassId=4 AND ac.studentId = a.studentId),'')='' " : '';
        }

        if($REQUEST_DATA['mks_5']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['mks_5']) ? " IFNULL((SELECT DISTINCT ac.previousPercentage FROM student_academic ac
          WHERE ac.previousClassId=5 AND ac.studentId = a.studentId),'')='' " : '';
        }


        if($REQUEST_DATA['compExamRank']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['compExamRank']) ? ' IFNULL(compExamRank,"")="" ' : '';
        }

        if($REQUEST_DATA['compExamRollNo']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['compExamRollNo']) ? ' IFNULL(compExamRollNo,"")="" ' : '';
        }

        if($REQUEST_DATA['compExamBy']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['compExamBy']) ? ' IFNULL(compExamBy,"")="" ' : '';
        }

        if($REQUEST_DATA['busStopIdForm']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['busStopIdForm'])    ? ' stopAbbr="'.NOT_APPLICABLE_STRING.'"    ' : '';
        }

        if($REQUEST_DATA['busRouteForm']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['busRouteForm'])     ? ' routeCode="'.NOT_APPLICABLE_STRING.'"   ' : '';
        }

        if($REQUEST_DATA['hostelNameForm']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['hostelNameForm'])   ? ' hostelCode="'.NOT_APPLICABLE_STRING.'"  ' : '';
        }
        if($REQUEST_DATA['hostelRoomNoForm']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['hostelRoomNoForm']) ? ' roomName="'.NOT_APPLICABLE_STRING.'"    ' : '';
        }

        if($REQUEST_DATA['studentPhoto']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['studentPhoto']) ? ' IFNULL(studentPhoto,"")="" ' : '';
        }

        if($REQUEST_DATA['studentInActive']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['studentInActive']) ? ' IFNULL(studentStatus,"")="" ' : '';
        }
        
        if($REQUEST_DATA['studentEmailId']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['studentEmailId']) ? " IFNULL(studentEmail,'')='' " : '';
        }
        
        if($REQUEST_DATA['fatherEmailId']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['fatherEmailId']) ? " IFNULL(fatherEmail,'')='' " : '';
        }
        
        if($REQUEST_DATA['motherEmailId']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['motherEmailId']) ? " IFNULL(motherEmail,'')='' " : '';
        }
        
        if($REQUEST_DATA['guardianEmailId']!='') {
          $blankColumnCond[]=isset($REQUEST_DATA['guardianEmailId']) ? " IFNULL(guardianEmail,'')='' " : '';
        }
        

      }
			$order ='';
	if(isset($REQUEST_DATA['universityRollNo'])) {
		$order = "`University Roll No.`";
	}
	elseif(isset($REQUEST_DATA['rollNoForm'])) {
		$order = "`College Roll No.`";
	}
	elseif(isset($REQUEST_DATA['regNoForm'])) {
		$order = "`Reg. No.`";
	}
	elseif(isset($REQUEST_DATA['classNameForm'])) {
		$order = "`Class Name`";
	}
	elseif(isset($REQUEST_DATA['firstName'])) {
		$order = "`Name`";
	}
	elseif(isset($REQUEST_DATA['fatherName'])) {
		$order = "`Father's Name`";
	}
	elseif(isset($REQUEST_DATA['motherName'])) {
		$order = "`Mother's Name`";
	}
	elseif(isset($REQUEST_DATA['guardianName'])) {
		$order = "`Guardian's Name`";
	}
	elseif(isset($REQUEST_DATA['bloodGroupForm'])) {
		$order = "`Blood Group`";
	}
	elseif(isset($REQUEST_DATA['studentEmail'])) {
		$order = "`Email`";
	}
	elseif(isset($REQUEST_DATA['dateOfBirth'])) {
		$order = "`DOB`";
	}
	elseif(isset($REQUEST_DATA['studentGender'])) {
		$order = "`Gender`";
	}
	elseif(isset($REQUEST_DATA['stateName'])) {
		$order = "`Domicile`";
	}
	elseif(isset($REQUEST_DATA['quotaName'])) {
		$order = "`Quota`";
	}
	elseif(isset($REQUEST_DATA['isLeet'])) {
		$order = "`IsLeet`";
	}
	elseif(isset($REQUEST_DATA['corrCityId'])) {
		$order = "`City`";
	}
	elseif(isset($REQUEST_DATA['countryName'])) {
		$order = "`Nationality`";
	}
	elseif(isset($REQUEST_DATA['managementReference'])) {
		$order = "`Management Reference`";
	}
	elseif(isset($REQUEST_DATA['studentRemarks'])) {
		$order = "`Remarks`";
	}
	elseif(isset($REQUEST_DATA['corrAddress1'])) {
		$order = "`Correspondence Address`";
	}
	elseif(isset($REQUEST_DATA['permAddress1'])) {
		$order = "`Permanent Address`";
	}
	elseif(isset($REQUEST_DATA['studentInActive'])) {
		$order = "`Student Status`";
	}
	elseif(isset($REQUEST_DATA['feeReceiptNo'])) {
		$order = "`Fee Receipt No.`";
	}
	elseif(isset($REQUEST_DATA['dateOfAdmission'])) {
		$order = "`Date of Admission`";
	}
	elseif(isset($REQUEST_DATA['studentUserName'])) {
		$order = "`Student User Name`";
	}
	elseif(isset($REQUEST_DATA['fatherUserName'])) {
		$order = "`Father User Name`";
	}
	elseif(isset($REQUEST_DATA['motherUserName'])) {
		$order = "`Mother User Name`";
	}
	elseif(isset($REQUEST_DATA['guardianUserName'])) {
		$order = "`Guardian User Name`";
	}
	//elseif(isset($REQUEST_DATA['groupIdForm'])) {
	//	$order = "`Group`";
	//}
	
  elseif(isset($REQUEST_DATA['mks_1'])) {
		$order = "`Marks in 10th`";
	}
	elseif(isset($REQUEST_DATA['mks_2'])) {
		$order = "`Marks in 12th`";
	} 
	elseif(isset($REQUEST_DATA['mks_3'])) {
		$order = "`Marks in Graduation`";
	}
	elseif(isset($REQUEST_DATA['mks_4'])) {
		$order = "`PG (if any)`";
	}
	elseif(isset($REQUEST_DATA['mks_5'])) {
		$order = "`Any Diploma`";
	}  
	elseif(isset($REQUEST_DATA['compRollNo'])) {
		$order = "`Comp. Exam. Roll No.`";
	} 
	elseif(isset($REQUEST_DATA['compExamBy'])) {
		$order = "`Comp. Exam. By`";
	} 
	elseif(isset($REQUEST_DATA['compRank'])) {
		$order = "`Rank`";
	} 
	elseif(isset($REQUEST_DATA['studentMobileNo'])) {
		$order = "`Student Mobile No.`";
	} 
	elseif(isset($REQUEST_DATA['guardianMobileNo'])) {
		$order = "`Guardian Mobile No.`";
	} 
	elseif(isset($REQUEST_DATA['fatherMobileNo'])) {
		$order = "`Father Mobile No.`";
	} 
	elseif(isset($REQUEST_DATA['motherMobileNo'])) {
		$order = "`Mother Mobile No.`";
	} 
	elseif(isset($REQUEST_DATA['fatherAddress1'])) {
		$order = "`Father's Address`";
	} 
	elseif(isset($REQUEST_DATA['motherAddress1'])) {
		$order = "`Mother's Address`";
	}
	elseif(isset($REQUEST_DATA['guardianAddress1'])) {
		$order = "`Guardian's Address`";
	}
	elseif(isset($REQUEST_DATA['busStopIdForm'])) {
		$order = "`Bus Stop`";
	}
	elseif(isset($REQUEST_DATA['busRouteForm'])) {
		$order = "`Bus Route`";
	}
	elseif(isset($REQUEST_DATA['hostelNameForm'])) {
		$order = "`Hostel Name`";
	}
	elseif(isset($REQUEST_DATA['hostelRoomNoForm'])) {
		$order = "`Hostel Room No.`";
	} 
      // Blank column condition  END
      $blankConditions = '';

      if($incAll==1)  {
          if(count($blankColumnCond) > 0) {
            $blankConditions = implode(' OR ',$blankColumnCond);
          }
          if($blankConditions!='') {
            $filter .= " AND ($blankConditions)";
          }
      }
	    $conditions .=  ' GROUP BY a.studentId ';
	if($order != ""){
    	 $conditions.= " ORDER BY trim($order) asc $limit";
	}

    $reportRecordArray = $studentReportsManager->getStudentListReportList($filter,$conditions);
//////// excel //////////
     if($REQUEST_DATA['act'] =='excel') {
        //$Records = implode(',',$reportRecordArray[0]);
        foreach($reportRecordArray[0] as $records => $value){
            $Records.="$records,";
        }
        $countRows = count($reportRecordArray);
        for($i=0;$i<$countRows;$i++) {
            $Records .="\r\n".implode(',',parseCSVComments($reportRecordArray[$i]));
        }
     }
///////////////////////
/// generate query string ///
    foreach($REQUEST_DATA AS $key => $value){
        if(trim($querystring=='')){
            $querystring="$key=$value";
        }
        else{
            $querystring.="&$key=$value";
        }
    }


//$History: initListStudentListsReports.php $
//
//*****************  Version 24  *****************
//User: Parveen      Date: 3/27/10    Time: 1:45p
//Updated in $/LeapCC/Library/StudentReports
//Optional Groups are displaying code updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 12/24/09   Time: 10:51a
//Updated in $/LeapCC/Library/StudentReports
//add_slashes added
//
//*****************  Version 22  *****************
//User: Parveen      Date: 12/23/09   Time: 6:39p
//Updated in $/LeapCC/Library/StudentReports
//role permission check & format updated
//
//*****************  Version 21  *****************
//User: Parveen      Date: 12/02/09   Time: 1:20p
//Updated in $/LeapCC/Library/StudentReports
//student Status field checks updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 12/01/09   Time: 5:42p
//Updated in $/LeapCC/Library/StudentReports
//condition format updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 11/23/09   Time: 4:28p
//Updated in $/LeapCC/Library/StudentReports
//blood group check updated
//
//*****************  Version 17  *****************
//User: Parveen      Date: 11/14/09   Time: 5:42p
//Updated in $/LeapCC/Library/StudentReports
//hostel, bus table left join format updated
//
//*****************  Version 16  *****************
//User: Parveen      Date: 11/10/09   Time: 11:27a
//Updated in $/LeapCC/Library/StudentReports
//student_optional_subject checks updated
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Library/StudentReports
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 10/01/09   Time: 6:51p
//Updated in $/LeapCC/Library/StudentReports
//changed queries and flow in send message to student, student report
//list according to HOD role and make new role advisory, modified in
//queries according to this role
//
//*****************  Version 13  *****************
//User: Parveen      Date: 9/01/09    Time: 1:21p
//Updated in $/LeapCC/Library/StudentReports
//group & domicile validation updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/LeapCC/Library/StudentReports
//issue fix format & conditions & alignment updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 8/12/09    Time: 2:48p
//Updated in $/LeapCC/Library/StudentReports
//issue fix (email, dateofbirth format updated)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 7/13/09    Time: 11:40a
//Updated in $/LeapCC/Library/StudentReports
//Report Heading Name Updated (Fee Receipt No.)
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/13/09    Time: 11:22a
//Updated in $/LeapCC/Library/StudentReports
//new enhancements Fee receipt number & alignment updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/11/09    Time: 12:13p
//Updated in $/LeapCC/Library/StudentReports
//new enhacments added (bloodGroup, regNo, className added)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 5/28/09    Time: 3:19p
//Updated in $/LeapCC/Library/StudentReports
//conditions update with record limits added
//
//*****************  Version 6  *****************
//User: Parveen      Date: 3/16/09    Time: 2:19p
//Updated in $/LeapCC/Library/StudentReports
//order by condition update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 3/16/09    Time: 2:14p
//Updated in $/LeapCC/Library/StudentReports
//issue fix
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/23/09    Time: 12:38p
//Updated in $/LeapCC/Library/StudentReports
//issue fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/21/09    Time: 5:32p
//Updated in $/LeapCC/Library/StudentReports
//bug fix
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/21/09    Time: 2:43p
//Updated in $/LeapCC/Library/StudentReports
//update formatting
//

?>
