<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();

    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    
    
   /* foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    $conditionsArray = array();
    
    if (!empty($rollNo)) {
        $conditionsArray[] = " a.rollNo = '$rollNo' ";
    }
    if (!empty($studentName)) {
        $conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
    }
    if (!empty($degreeId)) {
        $conditionsArray[] = " b.degreeId in ($degreeId) ";
    }
    if (!empty($branchId)) {
        $conditionsArray[] = " b.branchId in ($branchId) ";
    }
    if (!empty($periodicityId)) {
        $conditionsArray[] = " b.studyPeriodId IN ($periodicityId) ";
    }

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

    if (!empty($gender)) {
        $conditionsArray[] = " a.studentGender = '$gender' ";
    }
    if (!empty($categoryId)) {
        $conditionsArray[] = " a.managementCategory = $categoryId ";
    }
    if (!empty($quotaId)) {
        $conditionsArray[] = " a.quotaId IN ($quotaId) ";
    }
    if (!empty($hostelId)) {
        $conditionsArray[] = " a.hostelId IN ('$hostelId') ";
    }
    if (!empty($busStopId)) {
        $conditionsArray[] = " a.busStopId IN ('$busStopId') ";
    }
    if (!empty($busRouteId)) {
        $conditionsArray[] = " a.busRouteId IN ($busRouteId) ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " a.permCityId IN ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " a.permStateId IN ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " a.permCountryId IN ($countryId) ";
    }
    if (!empty($courseId)) {
        $conditionsArray[] = " a.studentId IN (SELECT studentId from ".ATTENDANCE_TABLE." WHERE subjectId IN ($courseId)) ";
    }
    if (!empty($groupId)) {
        $conditionsArray[] = " a.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
    }
    if (!empty($universityId)) {
        $conditionsArray[] = " b.universityId IN ($universityId) ";
    }
    if (!empty($instituteId)) {
        $conditionsArray[] = " b.instituteId IN ($instituteId) ";
    }

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    */
    
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

        $genName=" OR CONCAT(TRIM(a.firstName),' ',TRIM(a.lastName)) LIKE '".$genName."%'";
    }  
  
    return $genName;
    }

    /* START: search filter */
    foreach($REQUEST_DATA as $key => $values) {
        $$key = add_slashes($values);
    }

    $conditionsArray = array();
    
    $qryString = "";

    //Roll Number
    $rollNo = add_slashes($REQUEST_DATA['rollNo']);
    if (!empty($rollNo)) {
        $conditionsArray[] = " a.rollNo LIKE '$rollNo%' ";
        $qryString.= "&rollNo=$rollNo";
    }

    //Student Name
    $studentName = add_slashes($REQUEST_DATA['studentName']);
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
    $gender = add_slashes($REQUEST_DATA['gender']);
    if (!empty($gender)) {
        $conditionsArray[] = " a.studentGender = '$gender' ";
        $qryString .= "&gender=$gender";
    }

    //From Date of birth
    $birthDateF = add_slashes($REQUEST_DATA['birthDateF']);
    $birthMonthF = add_slashes($REQUEST_DATA['birthMonthF']);
    $birthYearF = add_slashes($REQUEST_DATA['birthYearF']);

    if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {

        if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
            $thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $conditionsArray[] = " a.dateOfBirth >= '$thisDate' ";
        }
        $qryString.= "&birthDateF=$birthDateF&birthMonthF=$birthMonthF&birthYearF=$birthYearF";
    }

    //To Date of birth
    $birthDateT = add_slashes($REQUEST_DATA['birthDateT']);
    $birthMonthT = add_slashes($REQUEST_DATA['birthMonthT']);
    $birthYearT = add_slashes($REQUEST_DATA['birthYearT']);

    if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {

        if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
            $thisDate = $birthYearT.'-'.$birthMonthT.'-'.$birthDateT;
            $conditionsArray[] = " a.dateOfBirth <= '$thisDate' ";
        }
        $qryString.= "&birthDateT=$birthDateT&birthMonthT=$birthMonthT&birthYearT=$birthYearT";
    }

    //fee receipt Number
    $feeReceiptNo = add_slashes($REQUEST_DATA['feeReceiptNo']);
    if (!empty($feeReceiptNo)) {
        $conditionsArray[] = " a.feeReceiptNo LIKE '$feeReceiptNo%' ";
        $qryString.= "&feeReceiptNo=$feeReceiptNo";
    }

    //registration Number
    $instRegNo = add_slashes($REQUEST_DATA['regNo']);
    if (!empty($instRegNo)) {
        $conditionsArray[] = " a.regNo LIKE '$instRegNo%' ";
        $qryString.= "&regNo=$instRegNo";
    }

    //degree
    $degs = add_slashes($REQUEST_DATA['degreeId']);
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
    $brans = add_slashes($REQUEST_DATA['branchId']);
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
    $periods = add_slashes($REQUEST_DATA['periodicityId']);
    $periodsText = add_slashes($REQUEST_DATA['periodsText']);
    if (!empty($periods)) {
        $conditionsArray[] = " b.studyPeriodId IN ($periods) ";
        $qryString.= "&periodicityId=$periods";
        $qryString.= "&periodsText=$periodsText";
    }
    else{
        $qryString.= "&periodsText=ALL";
         
    }

    //course
    $course = add_slashes($REQUEST_DATA['courseId']);
    
    if (!empty($course)) {
        $conditionsArray[] = " a.studentId IN (SELECT DISTINCT(studentId) FROM student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
        $qryString.= "&courseId=$course";
    } 
    
    //groups
    $groupId = add_slashes($REQUEST_DATA['groupId']);
    if (!empty($groupId)) {
        $conditionsArray[] = " a.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
        $qryString .= "&groupId=$groupId";
    }
    //university
    $univs = add_slashes($REQUEST_DATA['universityId']);
    $univsText = add_slashes($REQUEST_DATA['univsText']);
    if (!empty($univs)) {
        $conditionsArray[] = " b.universityId IN ($univs) ";
        $qryString .= "&universityId=$univs";
        $qryString.= "&univsText=$univsText";
    }
    else{
        $qryString.= "&univsText=ALL";
         
    }

    //city
    $citys = add_slashes($REQUEST_DATA['cityId']);
    $citysText = $REQUEST_DATA['citysText'];
    if (!empty($citys)) {
        $conditionsArray[] = " (a.corrCityId IN ($citys) OR  a.permCityId IN ($citys)) ";
        //$conditionsArray[] = " (a.permCityId IN ($citys)) ";
        $qryString .= "&cityId=$citys";
        $qryString.= "&citysText=$citysText";
    }
    else{
        $qryString.= "&citysText=ALL";
    }
    
    

    //states
    $states = add_slashes($REQUEST_DATA['stateId']);
    $statesText = $REQUEST_DATA['statesText'];
    if (!empty($states)) {
        $conditionsArray[] = " (a.corrStateId IN ($states) OR a.permStateId IN ($states)) ";
        //$conditionsArray[] = " (a.permStateId IN ($states)) ";
        $qryString .= "&stateId=$states";
        $qryString.= "&statesText=$statesText";
    }
    else{
        $qryString.= "&statesText=ALL";
         
    }

    //country
    $cnts = add_slashes($REQUEST_DATA['countryId']);
    $cntsText = $REQUEST_DATA['cntsText'];
    if (!empty($cnts)) {
        $conditionsArray[] = " (a.corrCountryId IN ($cnts) OR a.permCountryId IN ($cnts)) ";
        //$conditionsArray[] = " (a.permCountryId IN ($cnts)) ";
        $qryString .= "&countryId=$cnts";
        $qryString.= "&cntsText=$cntsText";
    }
    else{
        $qryString.= "&cntsText=ALL";
         
    }

    //management category
    $categoryId = add_slashes($REQUEST_DATA['categoryId']);
    if ($categoryId!='') {
        $conditionsArray[] = " a.managementCategory = $categoryId ";
        $qryString .= "&categoryId=$categoryId";
    }

    //From Admission Date
    $admissionDateF = add_slashes($REQUEST_DATA['admissionDateF']);
    $admissionMonthF = add_slashes($REQUEST_DATA['admissionMonthF']);
    $admissionYearF = add_slashes($REQUEST_DATA['admissionYearF']);

    if (!empty($admissionDateF) && !empty($admissionMonthF) && !empty($admissionYearF)) {

        if (false !== checkdate($admissionMonthF, $admissionDateF, $admissionYearF)) {
            $thisDate = $admissionYearF.'-'.$admissionMonthF.'-'.$admissionDateF;
            $conditionsArray[] = " a.dateOfAdmission >= '$thisDate' ";
        }
        $qryString.= "&admissionDateF=$admissionDateF&admissionMonthF=$admissionMonthF&admissionYearF=$admissionYearF";
    }

    //To Admission Date
    $admissionDateT = add_slashes($REQUEST_DATA['admissionDateT']);
    $admissionMonthT = add_slashes($REQUEST_DATA['admissionMonthT']);
    $admissionYearT = add_slashes($REQUEST_DATA['admissionYearT']);

    if (!empty($admissionDateT) && !empty($admissionMonthT) && !empty($admissionYearT)) {

        if (false !== checkdate($admissionMonthT, $admissionDateT, $admissionYearT)) {
            $thisDate = $admissionYearT.'-'.$admissionMonthT.'-'.$admissionDateT;
            $conditionsArray[] = " a.dateOfAdmission <= '$thisDate' ";
        }
        $qryString.= "&admissionDateT=$admissionDateT&admissionMonthT=$admissionMonthT&admissionYearT=$admissionYearT";
    }

    //hostel
    $hostels = add_slashes($REQUEST_DATA['hostels']);
    $hostelsText = add_slashes($REQUEST_DATA['hostelsText']);
    if (!empty($hostels)) {
        $conditionsArray[] = " a.hostelId IN ('$hostels') ";
        $qryString .= "&hostelId=$hostels";
        $qryString.= "&hostelsText=$hostelsText";
    }
    else{
        $qryString.= "&hostelsText=ALL";
         
    }

    //bus stop
    $buss = add_slashes($REQUEST_DATA['buss']);
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
    $routs = add_slashes($REQUEST_DATA['routs']);
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
    $quotaId = add_slashes($REQUEST_DATA['quotaId']);
    $quotaText = $REQUEST_DATA['quotaText'];
    if (!empty($quotaId)) {
        $conditionsArray[] = " a.quotaId IN ($quotaId) ";
        $qryString .= "&quotaId=$quotaId";
        $qryString.= "&quotaText=$quotaText";
    }
    else{
        $qryString.= "&quotaText=ALL";
         
    }
    
    $bloodGroup = add_slashes($REQUEST_DATA['bloodGroup']);
    $bloodGroupText = $REQUEST_DATA['bloodGroupText'];
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " a.studentBloodGroup = $bloodGroup";
        $qryString .= "&bloodGroup=$bloodGroup";
        $qryString.= "&bloodGroupText=$bloodGroupText";
    }
    else{
        $qryString.= "&bloodGroupText=ALL";
         
    }

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    $cardView = $REQUEST_DATA['cardView'];
    if($cardView==1) {
        $conditions .= ' AND a.busStopId IS NOT NULL AND a.busRouteId IS NOT NULL';
    }
    
    if($cardView==4) {
       $conditions .= ' AND a.busStopId IS NOT NULL AND a.busRouteId IS NOT NULL';
    }
    

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 48;
    $limit      = ' LIMIT '.$records.',48';


    if(add_slashes($REQUEST_DATA['sortField'])!='') {
        if(add_slashes($REQUEST_DATA['sortField'])=='DOB') {
          $sortField2="IF(DOB='0000-00-00',a.studentId,DOB)";
          $sortBy = $sortField2.' '.add_slashes($REQUEST_DATA['sortOrderBy']);  
        }
        else {
          $sortField2="IF(".$REQUEST_DATA['sortField']."='".NOT_APPLICABLE_STRING."',a.studentId, IF(IFNULL(".$REQUEST_DATA['sortField'].",'')='',a.studentId,".$REQUEST_DATA['sortField']."))";
          $sortBy = $sortField2.' '.add_slashes($REQUEST_DATA['sortOrderBy']);  
        }
    }
    else {
      $sortBy = " rollNo";
    }

    
    $totalRecordsArray = $studentManager->getStudentICardCount($conditions);
    $totalRecords = $totalRecordsArray[0]['cnt'];
  
    $studentRecordArray = $studentManager->getStudentICardDetails($conditions, $sortBy, $limit);
    $cnt = count($studentRecordArray);
    
    //'students' => "<input type=\"checkbox\" name=\"studentList\" id=\"studentList\" value=\"".$studentRecordArray[$i]['studentId'] ."\">",
    for($i=0;$i<$cnt;$i++) {
        $checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($studentRecordArray[$i]['studentId']).'">';
        
        $perm='';
        if($studentRecordArray[$i]['tpermAddress1']!= NOT_APPLICABLE_STRING && trim($studentRecordArray[$i]['tpermAddress1']) !='' ) {
          $perm .= $studentRecordArray[$i]['tpermAddress1']." &nbsp;";
        }
        else {
          $perm .= "<font color='red' size=1'><b>Missing Address&nbsp;</b></font>"; 
        }
        
        if($studentRecordArray[$i]['tpermAddress2']!= NOT_APPLICABLE_STRING) {
          $perm .= $studentRecordArray[$i]['tpermAddress2']." &nbsp;"; 
        }
        
        if($studentRecordArray[$i]['tpermCity']!= NOT_APPLICABLE_STRING) {
          $perm .= $studentRecordArray[$i]['tpermCity']." &nbsp;"; 
        }
        else {
          $perm .= "<font color='red' size=1'><b>Missing City&nbsp;</b></font>"; 
        }
        
        if($studentRecordArray[$i]['tpermState']!= NOT_APPLICABLE_STRING) {
          $perm .= $studentRecordArray[$i]['tpermState']." &nbsp;"; 
        }
        else {
          $perm .= "<font color='red' size=1'><b>Missing State&nbsp;</b></font>"; 
        }
        
        if($studentRecordArray[$i]['tpermCountry']!= NOT_APPLICABLE_STRING) {
          $perm .= $studentRecordArray[$i]['tpermCountry']." &nbsp;"; 
        }
        else {
          $perm .= "<font color='red' size=1'><b>Missing Country&nbsp;</b></font>"; 
        }
        
        if($studentRecordArray[$i]['tpermPinCode']!= NOT_APPLICABLE_STRING) {
          $perm .= $studentRecordArray[$i]['tpermPinCode']." &nbsp;"; 
        }
  
        if($cardView==3) {     
           $className = $studentRecordArray[$i]['programme'].'-'.$studentRecordArray[$i]['periodName'];
        }
        else {
           $className = $studentRecordArray[$i]['programme'];
        }  
        
        if($studentRecordArray[$i]['DOB']=='0000-00-00')  {
           $dateOfBirth = $studentRecordArray[$i]['DOB'] =  NOT_APPLICABLE_STRING;
        }
        else {
           $dateOfBirth = UtilityManager::formatDate($studentRecordArray[$i]['DOB']);
        }
        
        $valueArray = array_merge(array(
                              'checkAll' => $checkall,
                              'srNo' => ($records+$i+1), 
                              'studentName' => strip_slashes($studentRecordArray[$i]['studentName']),
                              'rollNo' => strip_slashes($studentRecordArray[$i]['rollNo']),
                              'fatherName' => strip_slashes($studentRecordArray[$i]['fatherName']),
                              'DOB' => $dateOfBirth,
                              'programme' => $className,
                              'studentMobileNo' => strip_slashes($studentRecordArray[$i]['studentMobileNo']),    
                              'permAddress' => $perm,
                              'Photo' => strip_slashes($studentRecordArray[$i]['Photo'])));
        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$totalRecords.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 
//$History: initStudentICardReport.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 10/21/09   Time: 11:28a
//Updated in $/LeapCC/Library/Icard
//sorting order updated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 10/06/09   Time: 5:38p
//Updated in $/LeapCC/Library/Icard
//date format condition updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 10/06/09   Time: 3:59p
//Updated in $/LeapCC/Library/Icard
//icard display record limit increased and updated look & feel
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/05/09   Time: 5:15p
//Updated in $/LeapCC/Library/Icard
//corr & perm base address check updated (city, state, country)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/05/09   Time: 2:00p
//Updated in $/LeapCC/Library/Icard
//validation format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/05/09   Time: 10:25a
//Updated in $/LeapCC/Library/Icard
//missing address checks updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Library/Icard
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/LeapCC/Library/Icard
//issue fix format & conditions & alignment updated
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Library/Icard
//added code for multiple tables.
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 1:16p
//Updated in $/LeapCC/Library/Icard
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 4/28/09    Time: 12:01p
//Updated in $/LeapCC/Library/Icard
//bus pass condition update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/12/09    Time: 3:49p
//Created in $/LeapCC/Library/Icard
//Icard added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/27/08   Time: 4:26p
//Updated in $/Leap/Source/Library/ScICard
//checkbox added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/26/08   Time: 4:29p
//Created in $/Leap/Source/Library/ScICard
//initial checkin
//

?>