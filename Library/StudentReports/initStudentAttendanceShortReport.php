<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all short attendance details report.
//
//
// Author :Parveen Sharma
// Created on : 13-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


    set_time_limit(0); //to overcome the time taken for fetching attendance
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentAttendanceShortReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();
    
    
    $timeTableLabelId = add_slashes(trim($REQUEST_DATA['labelId']));
    $classId = add_slashes(trim($REQUEST_DATA['classId']));
    $rollNo = add_slashes(trim($REQUEST_DATA['rollno']));
    $percentage = add_slashes(trim($REQUEST_DATA['percentage']));
     

    global $sessionHandler;          
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');

    $dutyLeave  = add_slashes(trim($REQUEST_DATA['dutyLeave']));   
    $medicalLeave = add_slashes(trim($REQUEST_DATA['medicalLeave']));   
    
    if($dutyLeave=='') {
      $dutyLeave=0;  
    }
    
    if($medicalLeave=='') {
      $medicalLeave=0;  
    }
    
    
     // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
                                                                                                  
    
    // Search filter 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    if($sortField=="rollNo"){
        $sortField1 =" IF(IFNULL(rollNo,'')='',sg.studentId,rollNo)";
    }
    else if($sortField=="studentName"){
        $sortField1 =" IF(IFNULL(studentName,'')='',sg.studentId,studentName)";
    }
    else if($sortField=="fatherName"){
        $sortField1 =" IF(IFNULL(fatherName,'')='' ,sg.studentId,fatherName)";
    }
    else if($sortField=="universityRollNo"){
           $sortField1=" IF(IFNULL(universityRollNo,'')='' ,sg.studentId,universityRollNo)";
    }
    else if($sortField=="studentMobileNo"){
        $sortField1 =" IF(IFNULL(studentMobileNo,'')='' ,sg.studentId,studentMobileNo)";
    }
    else {
        $sortField1 =" IF(IFNULL(rollNo,'')='',sg.studentId,rollNo)";
        $sortField=="rollNo";
    }    
    
    $orderBy = " $sortField1 $sortOrderBy";    
    
    
    if($timeTableLabelId=='') {
      $timeTableLabelId = 0;
    }
    
    if($classId=='') {
       $classId = 0;
    }
    
    if($percentage == "") {
       $percentage = 0;    
    }      
  
  $consolidated=1;
     // Attendance List
        
        $condition1 = " AND att.classId = $classId "; 
        if($rollNo!='') {
          $condition1 .= " AND s.rollNo LIKE '$rollNo%' "; 
        }
        //$condition1 .= " AND CONCAT(att.subjectId,'#',att.classId) IN ($concatStr) ";
       // $condition3    = "  WHERE (IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100)) < $percentage ";  
        $studentAttendance = CommonQueryManager::getInstance()->getStudentAttendanceReport($condition1,'',$consolidated);  
       
        $studentIds=0;
        for($i=0; $i<count($studentAttendance); $i++) {
            $attended = $studentAttendance[$i]['attended'];
            $delivered = $studentAttendance[$i]['delivered'];
            $leaveTaken = $studentAttendance[$i]['leaveTaken'];
            $medicalLeaveTaken = $studentAttendance[$i]['medicalLeaveTaken'];
            $per = 0;
            if($delivered>0) {
              if($dutyLeave=='1') {  
                if($leaveTaken>0) {  
                  $attended =  $attended + $leaveTaken; 
                }
              }
              if($medicalLeave=='1') {  
                if($medicalLeaveTaken>0) {    
                  $attended =  $attended + $medicalLeaveTaken;
                }
              }
              $per = ($attended/$delivered)*100;
            }
            if($per<$percentage){
	          $studentIds .=",".$studentAttendance[$i]['studentId'];  
            }
        }
       
     // Student List             
        $field = " DISTINCT 
                            sg.studentId, sg.classId, 
                            CONCAT(IFNULL(corrAddress1,''),' ',IFNULL(corrAddress2,''),'<br>',(SELECT cityName from city where city.cityId=stu.corrCityId),' ',(SELECT stateName from states where states.stateId=stu.corrStateId),' ',(SELECT countryName from countries where countries.countryId=stu.corrCountryId),IF(stu.corrPinCode IS NULL OR stu.corrPinCode='','',CONCAT('-',stu.corrPinCode))) AS corrAdd, 
                            CONCAT(IFNULL(permAddress1,''),' ',IFNULL(permAddress2,''),'<br>',(SELECT cityName from city where city.cityId=stu.permCityId),' ',(SELECT stateName from states where states.stateId=stu.permStateId),' ',(SELECT countryName from countries where countries.countryId=stu.permCountryId),IF(stu.permPinCode IS NULL OR stu.permPinCode='','',CONCAT('-',stu.permPinCode))) AS permAdd, 
                            CONCAT(IFNULL(firstName,''),' ',IFNULL(lastName,'')) AS studentName,
                            IFNULL(fatherName,'".NOT_APPLICABLE_STRING."') AS fatherName,   
                            IFNULL(rollNo,'".NOT_APPLICABLE_STRING."') AS  rollNo,  
                            IFNULL(studentPhoto,'') AS studentPhoto,
                            IFNULL(universityRollNo,'".NOT_APPLICABLE_STRING."') AS universityRollNo, 
                            IF(stu.studentMobileNo='','".NOT_APPLICABLE_STRING."',stu.studentMobileNo) AS studentMobileNo ,   
                            SUBSTRING_INDEX(cls.classname,'".CLASS_SEPRATOR."',-4)  AS className";
        $table = "student stu, class cls, student_groups sg";
        $cond = "WHERE 
                        sg.studentId = stu.studentId  AND
                        sg.classId = cls.classId      AND
                        cls.classId = $classId        AND
                        stu.studentId IN ($studentIds) 
                 ORDER BY $orderBy";                  
        
        $studentRecordArray = $studentManager->getSingleField($table, $field, $cond);         
        $cnt = count($studentRecordArray);
   
        for($i=0;$i<$cnt;$i++) {
            $studentId = $studentRecordArray[$i]['studentId'];
            if($studentRecordArray[$i]['studentPhoto'] != ''){ 
                $File = STORAGE_PATH."/Images/Student/".$studentRecordArray[$i]['studentPhoto'];
                if(file_exists($File)){
                   $imgSrc= IMG_HTTP_PATH.'/Student/'.$studentRecordArray[$i]['studentPhoto'];
                   $checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($studentRecordArray[$i]['studentId']).'">';
                }
                else{
                   $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
                   $checkall = NOT_APPLICABLE_STRING;
                }
            }
            else{
              $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
              $checkall = NOT_APPLICABLE_STRING;
            }
            
            $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='studentImageId' class='imgLinkRemove' />";
            $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
            
            $checkall = '<input type="checkbox" name="chb[]" id="chb" value="'.strip_slashes($studentRecordArray[$i]['studentId']).'">';
            if($studentRecordArray[$i]['permAdd'] == '' && $studentRecordArray[$i]['permAdd'] == null) {
               $studentRecordArray[$i]['permAdd'] = NOT_APPLICABLE_STRING; 
            }
            
            if($studentRecordArray[$i]['corrAdd'] == '' && $studentRecordArray[$i]['corrAdd'] == null) {
               $studentRecordArray[$i]['corrAdd'] = NOT_APPLICABLE_STRING; 
            }
            
            $valueArray = array_merge(array(                                        
                                  'checkAll' => $checkall,
                                  'srNo' => ($records+$i+1)),$studentRecordArray[$i]);
            if(trim($json_val)=='') {
                $json_val = json_encode($valueArray);
            }
            else {
                $json_val .= ','.json_encode($valueArray);           
            }
        }
        echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$totalRecords.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 
 
?>

<?php    
//$History: initStudentAttendanceShortReport.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/25/10    Time: 12:02p
//Updated in $/LeapCC/Library/StudentReports
//format & validation updated 
//

?>
