<?php
//-------------------------------------------------------
//  This File is used for fetching classes for a subject
//
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();   
   
    $conditionEmployee = '';     

    
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    $employeeReportsManager = EmployeeReportsManager::getInstance(); 
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportManager = StudentReportsManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance(); 
    
    
	$timeTableLabelId = trim(add_slashes($REQUEST_DATA['timeTableLabelId'])); 
    $classId = trim(add_slashes($REQUEST_DATA['classId']));

    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;  
    }
    
    if($classId=='') {
      $classId = 0;  
    }
  
   
    $cond = " AND c.classId = $classId ".$conditionEmployee;   
    $filter= " DISTINCT su.hasAttendance, su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
    $groupBy = "";
    $orderSubjectBy = " classId,  subjectTypeId, subjectCode, subjectId";
    $subjectArray =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy, $orderSubjectBy);  
    
    $cond = " AND c.classId = $classId AND tt.timeTableLabelId = $timeTableLabelId $conditionEmployee";   
    $filter= " DISTINCT c.classId, g.groupId, g.groupName ";
    $groupBy = "";
    $orderSubjectBy = " classId, groupId, groupName";
    $groupArray =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy, $orderSubjectBy);  

    
    $subjectIds="0";
    for($i=0;$i<count($subjectArray);$i++) { 
      $subjectIds .=",".$subjectArray[$i]['subjectId'];  
    }
    
    
    // Fetch Subject List
       $employeeName='';
       $tableName = "employee emp, subject su,  ".TIME_TABLE_TABLE."  tt, `group` g ";
       $fieldsName ="su.subjectId, GROUP_CONCAT(DISTINCT CONCAT(emp.employeeName,' (',emp.employeeCode,')') ORDER BY emp.employeeName SEPARATOR ', ') AS employeeName";
       $empCondition = " WHERE 
                                tt.timeTableLabelId=$timeTableLabelId AND
                                tt.toDate IS NULL AND    
                                g.groupId = tt.groupId AND
                                tt.subjectId = su.subjectId AND
                                tt.subjectId IN ($subjectIds) AND
                                emp.employeeId = tt.employeeId AND
                                g.classId = $classId  
                                $showGroupG 
                                $conditionsEmp
                                $conditionEmployee
                          GROUP BY 
                                su.subjectId
                          ORDER BY
                                su.subjectTypeId, su.subjectCode  ";  
       $employeeArray = $studentManager->getSingleField($tableName, $fieldsName, $empCondition);
    
       $employeeList1 = '';
       if(count($subjectArray)>0) {
           $employeeList1 = "<table width='100%' border='1' class='reportTableBorder'>
                                  <tr>
                                     <td width='2%' class='dataFont'><b>#</b></td> 
                                     <td width='10%' class='dataFont'><b>Subject Code</b></td>
                                     <td width='25%' class='dataFont'><b>Subject Name</b></td>
                                     <td width='65%' class='dataFont'><b>Teacher</b></td>
                                  </tr>";

           $j=0;                                                         
           for($i=0;$i<count($subjectArray);$i++) {
              $employeeList1 .= "<tr>
                                 <td class='dataFont'>".($i+1)."</nobr></td> 
                                 <td class='dataFont'>".$subjectArray[$i]['subjectCode']."</td>
                                 <td class='dataFont'>".$subjectArray[$i]['subjectName']."</td>";
              $employeeName ='';
              $subjectId = $subjectArray[$i]['subjectId'];
              if($employeeArray[$j]['subjectId']==$subjectId) {
                $employeeList1 .= "<td width='98%' class='dataFont'>".$employeeArray[$j]['employeeName']."</td>";
                $j++;                 
              }
              else {
                $employeeList1 .= "<td width='98%' class='dataFont'>".NOT_APPLICABLE_STRING."</td>";  
              }
              $employeeList1 .= "</tr>";
           }
           $employeeList1 .= "</table>";
       }
       
    echo json_encode($subjectArray).'!~!~!'.json_encode($groupArray).'!~!~!'.$employeeList1; 
?>