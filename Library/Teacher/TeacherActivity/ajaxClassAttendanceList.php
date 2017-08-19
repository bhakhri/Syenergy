<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of class wise attendance
//
// Author : Dipanjan Bbhattacharjee
// Created on : (07.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ClassWiseAttendanceList');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    
//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Dipanjan Bhattacharjee
//Date:19.09.2008
//-----------------------------------------------------------------------------------
function parseName($value){
    $name=explode(' ',$value);
    $genName="";
    $len= count($name);
    if($len > 0){
      for($i=0;$i<$len;$i++){
          if(trim($name[$i])!=""){
              if($genName!=""){
                  $genName =$genName ." ".$name[$i];
              }
             else{
                 $genName =$name[$i];
             } 
          }
      }
    }
  if($genName!=""){
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".add_slashes($genName)."%'";
  }  
  
  return $genName;
}

    /////////////////////////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";  
    
    //creates the condition
    //$conditions=($REQUEST_DATA['studentRollNo']!="" ? " AND s.rollNo='".$REQUEST_DATA['studentRollNo']."'" : " AND c.classId=".$REQUEST_DATA['class']." AND su.subjectId=".$REQUEST_DATA['subject']." AND g.groupId=".$REQUEST_DATA['group']);
    $conditions= " AND c.classId= '".$REQUEST_DATA['class']."' AND su.subjectId = '".$REQUEST_DATA['subject']."'";
    if(trim($REQUEST_DATA['group'])!='' and trim($REQUEST_DATA['group'])!=-1){
        $conditions .=" AND g.groupId= '".$REQUEST_DATA['group']."'";
    }
    
    if(trim($REQUEST_DATA['studentRollNo'])!=''){
        $conditions .=' AND ( s.rollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%" OR s.universityRollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%" )';
    }
    
    if(trim($REQUEST_DATA['studentName'])!=""){
        $parsedName=parseName(trim($REQUEST_DATA['studentName']));    //parse the name for compatibality
        $conditions .=" AND (
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($REQUEST_DATA['studentName']))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($REQUEST_DATA['studentName']))."%'
                                  $parsedName
                               )"; 
    }
    
    $conditions .=" AND att.fromDate >='".$REQUEST_DATA['fromDate']."' AND att.toDate <='".$REQUEST_DATA['toDate']."'";
    
    if(trim($REQUEST_DATA['reportType'])==1){
        $conditions .=" AND att.employeeId='".$sessionHandler->getSessionVariable('EmployeeId')."'";
    }

    ////////////
    
    
    $classAttendanceRecordArray = $teacherManager->getClassWiseAttendanceList($conditions,$orderBy);
    $cnt = count($classAttendanceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {

       if($classAttendanceRecordArray[$i]['percentage']==''){
         $classAttendanceRecordArray[$i]['percentage']='0.00'; 
       }
       
       if($classAttendanceRecordArray[$i]['shortAttendance']==-1){
           $classAttendanceRecordArray[$i]['shortAttendance']='<img src="'.IMG_HTTP_PATH.'/attendance_red.gif" title="Below Attendance Threshold" />';
       } 
       else{
           $classAttendanceRecordArray[$i]['shortAttendance']='<img src="'.IMG_HTTP_PATH.'/attendance_green.gif" title="Above or equal to Attendance Threshold" />';
       }
       $valueArray = array_merge(array('srNo' => ($records+$i+1)), $classAttendanceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
// for VSS
// $History: ajaxClassAttendanceList.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 12/04/10   Time: 18:58
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Updated "Display Attendance" report
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 5/09/09    Time: 18:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//bug ids---
//00001449,00001445
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 29/06/09   Time: 11:32
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added name & roll no wise search in display attendance and marks
//display in teacher login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/06/09   Time: 10:37
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done GNIMT enhancements as on 26.06.2009
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/14/08    Time: 5:49p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/08/08    Time: 11:45a
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>
