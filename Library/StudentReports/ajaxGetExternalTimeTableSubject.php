<?php
//--------------------------------------------------------
//This file returns the array of class, based on time table label Id
//
// Author :Parveen Sharma
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");
  require_once(MODEL_PATH . "/AdminTasksManager.inc.php");  
  
  require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
  $studentReportManager = StudentReportsManager::getInstance();

  require_once(MODEL_PATH . "/StudentManager.inc.php");
  $studentManager = StudentManager::getInstance();

  define('MODULE','COMMON');
  define('ACCESS','view');
  UtilityManager::ifNotLoggedIn(true);
  UtilityManager::headerNoCache();


  $timeTableLabelId =  trim($REQUEST_DATA['timeTableLabelId']);
  $classId =  trim($REQUEST_DATA['classId']);  
  
  if($timeTableLabelId=='') {
    $timeTableLabelId=0;  
  }
  
  if($classId=='') {
    $classId=0;  
  }

  
// Fetch Subject List 
    $subjectIds =0;                                 
    $cond = " AND c.classId=$classId ";
    $filter= " DISTINCT su.hasAttendance, su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
    $groupBy = "";
    $orderSubjectBy = " classId,  subjectTypeId, subjectCode, subjectId";
    $recordArray =  $studentReportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy,  $orderSubjectBy );   
    for($i=0;$i<count($recordArray);$i++) {
       $subjectIds .=",".$recordArray[$i]['subjectId']; 
    }
    
   $employeeName='';
   $tableName = "employee emp, subject su,  ".TIME_TABLE_TABLE." tt, `group` g ";
   $fieldsName ="su.subjectId, GROUP_CONCAT(DISTINCT CONCAT(emp.employeeName,' (',emp.employeeCode,')') ORDER BY emp.employeeName SEPARATOR ', ') AS employeeName";
   $empCondition = " WHERE                               
                            tt.toDate IS NULL AND
                            g.groupId = tt.groupId AND
                            tt.subjectId = su.subjectId AND
                            tt.subjectId IN ($subjectIds) AND
                            emp.employeeId = tt.employeeId AND 
                            g.classId=$classId AND 
                            tt.timeTableLabelId=$timeTableLabelId
                      GROUP BY 
                            su.subjectId
                      ORDER BY
                            su.subjectTypeId, su.subjectCode  ";  
   $employeeArray = $studentManager->getSingleField($tableName, $fieldsName, $empCondition);

   $employeeList1 = '';
   if(count($recordArray)>0) {
       $employeeList1 = "<table width='98%' cellspacing='0px' cellpadding='2px' border='1' class='reportTableBorder'>
                              <tr>
                                 <td width='2%' class='dataFont'><b>#</b></td> 
                                 <td width='10%' class='dataFont'><b>Subject Code</b></td>
                                 <td width='25%' class='dataFont'><b>Subject Name</b></td>
                                 <td width='65%' class='dataFont'><b>Teacher</b></td>
                              </tr>";

       $j=0;                                                         
       for($i=0;$i<count($recordArray);$i++) {
          $employeeList1 .= "<tr>
                             <td class='dataFont'>".($i+1)."</nobr></td> 
                             <td class='dataFont'>".$recordArray[$i]['subjectCode']."</td>
                             <td class='dataFont'>".$recordArray[$i]['subjectName']."</td>";
          $employeeName ='';
          $subjectId = $recordArray[$i]['subjectId'];
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
  
   echo $employeeList1; 


// $History: ajaxGetExternalTimeTableClass.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/21/10    Time: 1:06p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//

?>