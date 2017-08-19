<?php 
//-------------------------------------------------------
//  This File contains Download Images
//
// Author :Parveen Sharma
// Created on : 03-01-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
     //print_r($REQUEST_DATA);
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

        $genName=" OR CONCAT(TRIM(a.firstName),' ',TRIM(a.lastName)) LIKE '".add_slashes($genName)."%'";
    }  
  
    return $genName;
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /* START: search filter */
    foreach($REQUEST_DATA as $key => $values) {
        $$key = trim($values);
    }
    
    $showAlumnniStudent=trim($REQUEST_DATA['alumniStudent']);
    if($showAlumnniStudent==''){
        $showAlumnniStudent=1;
    }

    $conditionsArray = array();
    
    $qryString = "";
    global $sessionHandler;

    $userId= $sessionHandler->getSessionVariable('UserId');
    
    //Roll Number
    $rollNo = trim($REQUEST_DATA['rollNo']);
    if (!empty($rollNo)) {
        $conditionsArray[] = " (a.rollNo LIKE '$rollNo%' OR a.regNo LIKE '$rollNo%' OR a.universityRollNo LIKE '$rollNo%')";
        $qryString.= "&rollNo=$rollNo";
    }

    //Student Name
    $studentName = $REQUEST_DATA['studentName'];
    if (!empty($studentName)) {
        //$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
        $parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $conditionsArray[] = " (
                                  TRIM(a.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(a.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
        $qryString.= "&studentName=$studentName";
    }
    
   
    //Student Gender
    $gender = $REQUEST_DATA['gender'];
    if (!empty($gender)) {
        $conditionsArray[] = " a.studentGender = '$gender' ";
        $qryString .= "&gender=$gender";
    }

    //From Date of birth
    $birthDateF = $REQUEST_DATA['birthDateF'];
    $birthMonthF = $REQUEST_DATA['birthMonthF'];
    $birthYearF = $REQUEST_DATA['birthYearF'];

    if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {

        if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
            $thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $conditionsArray[] = " a.dateOfBirth >= '$thisDate' ";
        }
        $qryString.= "&birthDateF=$birthDateF&birthMonthF=$birthMonthF&birthYearF=$birthYearF";
    }

    //To Date of birth
    $birthDateT = $REQUEST_DATA['birthDateT'];
    $birthMonthT = $REQUEST_DATA['birthMonthT'];
    $birthYearT = $REQUEST_DATA['birthYearT'];

    if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {

        if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
            $thisDate = $birthYearT.'-'.$birthMonthT.'-'.$birthDateT;
            $conditionsArray[] = " a.dateOfBirth <= '$thisDate' ";
        }
        $qryString.= "&birthDateT=$birthDateT&birthMonthT=$birthMonthT&birthYearT=$birthYearT";
    }

    //fee receipt Number
    $feeReceiptNo = $REQUEST_DATA['feeReceiptNo'];
    if (!empty($feeReceiptNo)) {
        $conditionsArray[] = " a.feeReceiptNo LIKE '$feeReceiptNo%' ";
        $qryString.= "&feeReceiptNo=$feeReceiptNo";
    }

    //registration Number
    $instRegNo = $REQUEST_DATA['regNo'];
    if (!empty($instRegNo)) {
        $conditionsArray[] = " a.regNo LIKE '$instRegNo%' ";
        $qryString.= "&regNo=$instRegNo";
    }

    //degree
    $degs = $REQUEST_DATA['degreeId'];
    $degsText = $REQUEST_DATA['degsText'];
    if (!empty($degs)) {
        $conditionsArray[] = " b.degreeId in ($degs) ";
        $qryString.= "&degreeId=$degs";
        $qryString.= "&degsText=$degsText";
    }
    else{
        $qryString.= "&degsText=ALL";
         
    }
    
    //$searchCrieria .="&nbsp;<b>Degree:</b>$degsText";

    //branch
    $brans = $REQUEST_DATA['branchId'];
    $bransText = $REQUEST_DATA['branText'];
    if (!empty($brans)) {
        $conditionsArray[] = " b.branchId in ($brans) ";
        $qryString.= "&branchId=$brans";
        $qryString.= "&branText=$branText";
    }
    else{
        $qryString.= "&branText=ALL";
         
    }

    //periodicity
    $periods = $REQUEST_DATA['periodicityId'];
    $periodsText = $REQUEST_DATA['periodsText'];
    if (!empty($periods)) {
        $conditionsArray[] = " b.studyPeriodId IN ($periods) ";
        $qryString.= "&periodicityId=$periods";
        $qryString.= "&periodsText=$periodsText";
    }
    else{
        $qryString.= "&periodsText=ALL";
         
    }

    //course
    $course = $REQUEST_DATA['courseId'];
    
    if (!empty($course)) {
        $conditionsArray[] = " a.studentId IN (SELECT DISTINCT(studentId) FROM student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
        $qryString.= "&courseId=$course";
    } 
    
    //groups
    $groupId = $REQUEST_DATA['groupId'];
    if (!empty($groupId)) {
        $conditionsArray[] = " a.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
        $qryString .= "&groupId=$groupId";
    }
    //university
    $univs = $REQUEST_DATA['universityId'];
    $univsText = $REQUEST_DATA['univsText'];
    if (!empty($univs)) {
        $conditionsArray[] = " b.universityId IN ($univs) ";
        $qryString .= "&universityId=$univs";
        $qryString.= "&univsText=$univsText";
    }
    else{
        $qryString.= "&univsText=ALL";
         
    }

    //city
    $citys = $REQUEST_DATA['cityId'];
    $citysText = $REQUEST_DATA['citysText'];
    if (!empty($citys)) {
        $conditionsArray[] = " (a.corrCityId IN ($citys) OR  a.permCityId IN ($citys)) ";
        $qryString .= "&cityId=$citys";
        $qryString.= "&citysText=$citysText";
    }
    else{
        $qryString.= "&citysText=ALL";
         
    }

    //states
    $states = $REQUEST_DATA['stateId'];
    $statesText = $REQUEST_DATA['statesText'];
    if (!empty($states)) {
        $conditionsArray[] = " (a.corrStateId IN ($states) OR a.permStateId IN ($states)) ";
        $qryString .= "&stateId=$states";
        $qryString.= "&statesText=$statesText";
    }
    else{
        $qryString.= "&statesText=ALL";
         
    }

    //country
    $cnts = $REQUEST_DATA['countryId'];
    $cntsText = $REQUEST_DATA['cntsText'];
    if (!empty($cnts)) {
        $conditionsArray[] = " (a.corrCountryId IN ($cnts) OR a.permCountryId IN ($cnts)) ";
        $qryString .= "&countryId=$cnts";
        $qryString.= "&cntsText=$cntsText";
    }
    else{
        $qryString.= "&cntsText=ALL";
         
    }

    //management category
    $categoryId = $REQUEST_DATA['categoryId'];
    if ($categoryId!='') {
        $conditionsArray[] = " a.managementCategory = $categoryId ";
        $qryString .= "&categoryId=$categoryId";
    }

    //From Admission Date
    $admissionDateF = $REQUEST_DATA['admissionDateF'];
    $admissionMonthF = $REQUEST_DATA['admissionMonthF'];
    $admissionYearF = $REQUEST_DATA['admissionYearF'];

    if (!empty($admissionDateF) && !empty($admissionMonthF) && !empty($admissionYearF)) {

        if (false !== checkdate($admissionMonthF, $admissionDateF, $admissionYearF)) {
            $thisDate = $admissionYearF.'-'.$admissionMonthF.'-'.$admissionDateF;
            $conditionsArray[] = " a.dateOfAdmission >= '$thisDate' ";
        }
        $qryString.= "&admissionDateF=$admissionDateF&admissionMonthF=$admissionMonthF&admissionYearF=$admissionYearF";
    }

    //To Admission Date
    $admissionDateT = $REQUEST_DATA['admissionDateT'];
    $admissionMonthT = $REQUEST_DATA['admissionMonthT'];
    $admissionYearT = $REQUEST_DATA['admissionYearT'];

    if (!empty($admissionDateT) && !empty($admissionMonthT) && !empty($admissionYearT)) {

        if (false !== checkdate($admissionMonthT, $admissionDateT, $admissionYearT)) {
            $thisDate = $admissionYearT.'-'.$admissionMonthT.'-'.$admissionDateT;
            $conditionsArray[] = " a.dateOfAdmission <= '$thisDate' ";
        }
        $qryString.= "&admissionDateT=$admissionDateT&admissionMonthT=$admissionMonthT&admissionYearT=$admissionYearT";
    }

    //hostel
    $hostels = $REQUEST_DATA['hostels'];
    $hostelsText = $REQUEST_DATA['hostelsText'];
    if (!empty($hostels)) {
        $conditionsArray[] = " a.hostelId IN ('$hostels') ";
        $qryString .= "&hostelId=$hostels";
        $qryString.= "&hostelsText=$hostelsText";
    }
    else{
        $qryString.= "&hostelsText=ALL";
         
    }

    //bus stop
    $buss = $REQUEST_DATA['buss'];
    $bussText = $REQUEST_DATA['bussText'];
    if (!empty($buss)) {
        $conditionsArray[] = " a.busStopId IN ('$buss') ";
        $qryString .= "&busStopId=$buss";
        $qryString.= "&bussText=$bussText";
    }
    else{
        $qryString.= "&bussText=ALL";
         
    }

    //bus route
    $routs = $REQUEST_DATA['routs'];
    $routsText = $REQUEST_DATA['routsText'];
    if (!empty($routs)) {
        $conditionsArray[] = " a.busRouteId IN ($routs) ";
        $qryString .= "&busRouteId=$routs";
        $qryString.= "&routsText=$routsText";
    }
    else{
        $qryString.= "&routsText=ALL";
         
    }
    
    //quota
    $quotaId = $REQUEST_DATA['quotaId'];
    $quotaText = $REQUEST_DATA['quotaText'];
    if (!empty($quotaId)) {
        $conditionsArray[] = " a.quotaId IN ($quotaId) ";
        $qryString .= "&quotaId=$quotaId";
        $qryString.= "&quotaText=$quotaText";
    }
    else{
        $qryString.= "&quotaText=ALL";
         
    }
    
    $bloodGroup = $REQUEST_DATA['bloodGroup'];
    $bloodGroupText = $REQUEST_DATA['bloodGroupText'];
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " a.studentBloodGroup = $bloodGroup";
        $qryString .= "&bloodGroup=$bloodGroup";
        $qryString.= "&bloodGroupText=$bloodGroupText";
    }
    else{
        $qryString.= "&bloodGroupText=ALL";
         
    }

    //############ FOR ATTENDANCE FROM / TO ##############################
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
            echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
            die;
         }
     }
     //########################################################################

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'firstName';

    $orderBy=" $sortField $sortOrderBy"; 

    if($sortField=="studyPeriod")
        $orderBy= "b.studyPeriodId $sortOrderBy"; 

    if($sortField=="groupId")
        $orderBy= "sg.groupId $sortOrderBy"; 

    if($sortField=="className")
        $orderBy= "b.className $sortOrderBy"; 

    /* END: search filter */

    $userRoleArray = $studentManager->getRoleUser($userId);
    $roleCount = $userRoleArray[0]['totalRecords'];
    $showAlumnniStudent=1;
    if ($roleCount > 0) {
        $totalArray = $studentManager->getTotalRoleStudent($conditions,$userId,$showAlumnniStudent);
        $studentRecordArray = $studentManager->getRoleStudentList($conditions,$limit,$orderBy,$userId,$showAlumnniStudent);
    }
    else {

        $totalArray = $studentManager->getTotalStudent($conditions,$showAlumnniStudent);
        $studentRecordArray = $studentManager->getStudentList($conditions,$limit,$orderBy,$showAlumnniStudent);
    }
    
    $cnt = count($studentRecordArray);
   
    
    for($i=0;$i<$cnt;$i++) {
        $imgSrc = "";
        $upload = "";
        $checkall = "";
        $studentId = trim($studentRecordArray[$i]['studentId']);
        $studentNames = trim($studentRecordArray[$i]['studentPhoto']);
        
        // Student Photo    --Start--        
        if($studentRecordArray[$i]['studentPhoto'] != ''){ 
            $File = STORAGE_PATH."/Images/Student/".$studentRecordArray[$i]['studentPhoto'];
            if(file_exists($File)){
               $imgSrc= IMG_HTTP_PATH.'/Student/'.$studentRecordArray[$i]['studentPhoto'].'?x='.rand(0,150)*rand(0,150);
               $checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($studentRecordArray[$i]['studentId']).'">';
            }
            else{
               $imgSrc= IMG_HTTP_PATH."/notfound.jpg?y=".rand(0,150)*rand(0,150);
               $checkall = NOT_APPLICABLE_STRING;
            }
        }
        else{
          $imgSrc= IMG_HTTP_PATH."/notfound.jpg?y=".rand(0,150)*rand(0,150);
          $checkall = NOT_APPLICABLE_STRING;
        }

        if($checkall==NOT_APPLICABLE_STRING) {
           $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='studentImageId' class='imgLinkRemove11' />";
        }
        else {
           $imgSrc = "<img src='".$imgSrc."' width='20' height='20' id='studentImageId' class='imgLinkRemove11' />&nbsp;
                      <a onclick='deleteStudentImage(".$studentId."); return false;'>
           <img src='".IMG_HTTP_PATH."/delete.gif?rand=".rand(0,1000)."' style='margin-bottom-4px' alt='Delete Photo' title='Delete Photo' >
                      </a>"; 
        }
        $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
        // Student Photo    --END--
        
        $upload = '<input type="file" class="inputbox" name="fileId[]" id="fileId" style="width:200px">
                   <input readonly type="hidden" name="sStudentId[]" id="sStudentId" value="'.$studentId.'">
                   <input readonly type="hidden"  name="studentNames[]" id="studentNames" value="'.$studentNames.'">';        
        
   $studentRecordArray[$i]['regNo'] = $studentRecordArray[$i]['regNo'] == '' ? '--' : $studentRecordArray[$i]['regNo'] ;  
   $studentRecordArray[$i]['rollNo'] = $studentRecordArray[$i]['rollNo'] == '' ? '--' : $studentRecordArray[$i]['rollNo'] ;
   $studentRecordArray[$i]['universityRollNo'] = $studentRecordArray[$i]['universityRollNo'] == '' ? '--' : $studentRecordArray[$i]['universityRollNo'] ;
        
        $valueArray = array_merge(array('checkAll' =>  $checkall, 
                                        'upload' =>  $upload, 
                                        'imgSrc' =>  $imgSrc, 
                                        'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

/*
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);

require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentReport = StudentReportsManager::getInstance();    

global $sessionHandler;

$instituteId = $sessionHandler->getSessionVariable('InstituteId');
$sessionId = $sessionHandler->getSessionVariable('SessionId');
    
if(trim($REQUEST_DATA['degree'] ) != '' && trim($REQUEST_DATA['studyPeriodId']) != '') {
    $arr = explode('-',add_slashes($REQUEST_DATA['degree']));
   
    $universityId = $arr[0];
    $degreeId = $arr[1];
    $branchId = $arr[2];
    $studyPeriodId = add_slashes($REQUEST_DATA['studyPeriodId']);
    $groupId = add_slashes($REQUEST_DATA['groupId']);
    
      // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.',20';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " ORDER BY $sortField $sortOrderBy";
    
    
    $tableName  = " student s LEFT JOIN class c ON s.classId = c.classId ";
    $tableName .= " LEFT JOIN `group` grp ON grp.classId = s.classId ";
    $tableName .= " LEFT JOIN student_groups sg ON sg.classId = s.classId AND s.studentId = sg.studentId AND sg.groupId = grp.groupId";
    $tableName .= " AND sg.instituteId = '".$instituteId."' AND sg.sessionId = '".$sessionId."'"; 
    
    

    $condition = " WHERE c.universityId ='".$arr[0]."' AND c.degreeId='".$arr[1]."' AND c.branchId='".$arr[2]."'"; 
    $condition .= " AND c.studyPeriodId='".$studyPeriodId."' AND c.instituteId = '".$instituteId."' AND c.sessionId = '".$sessionId."'";
    $condition .= " AND s.studentStatus = 1 ";
    
    if($groupId!='all' && $groupId != '') {
       $condition .= " AND grp.groupId='".$groupId."'";
    }
    $condition .= " GROUP BY s.studentId ";


    
    $fieldName = " COUNT(DISTINCT t.studentId) AS cnt
                   FROM 
                   (SELECT
                            DISTINCT s.studentId ";
    $cond = $condition.") AS t ";
    $totalArray = $studentReport->getSingleField($tableName, $fieldName, $cond);
    $cnt=$totalArray[0]['cnt'];
    

    
    $fieldName = " DISTINCT 
                            s.studentId, 
                            IFNULL(s.rollNo,'".NOT_APPLICABLE_STRING."') AS rollNo, 
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            IFNULL(s.fatherName,'".NOT_APPLICABLE_STRING."') AS fatherName,  
                            IFNULL(grp.groupName,'".NOT_APPLICABLE_STRING."') AS groupName,
                            IFNULL(studentPhoto,'') AS studentPhoto ";
    
    $condition .= $orderBy." ".$limit;
    $studentRecordArray = $studentReport->getSingleField($tableName, $fieldName, $condition);
    
    for($i=0;$i<count($studentRecordArray);$i++) {

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
        
        $imgSrc = "<img src='".$imgSrc."' width='30' height='30' id='studentImageId'/>";
        $studentRecordArray[$i]['imgSrc'] =  $imgSrc;
        
        $valueArray = array_merge(array('checkAll' =>  $checkall, 
                                        'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);   
        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);           
        }
    }
}  

    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 
*/     
    
?>