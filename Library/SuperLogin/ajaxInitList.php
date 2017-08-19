<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','StudentInfo');
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
		$$key = $values;
	}

	$conditionsArray = array();
	
	$qryString = "";
	global $sessionHandler;

	$userId= $sessionHandler->getSessionVariable('UserId');
	
	//Roll Number
	$rollNo = $REQUEST_DATA['rollNo'];
	if (!empty($rollNo)) {
		$conditionsArray[] = " a.rollNo LIKE '$rollNo%' ";
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

	$orderBy="a.$sortField $sortOrderBy"; 

	if($sortField=="studyPeriod")
		$orderBy= "b.studyPeriodId $sortOrderBy"; 

	if($sortField=="groupId")
		$orderBy= "sg.groupId $sortOrderBy"; 

	if($sortField=="className")
		$orderBy= "b.className $sortOrderBy"; 

	/* END: search filter */

	$userRoleArray = $studentManager->getRoleUser($userId);

	$roleCount = $userRoleArray[0]['totalRecords'];
	if ($roleCount > 0) {
		$totalArray = $studentManager->getTotalRoleStudent($conditions,$userId);
		$studentRecordArray = $studentManager->getRoleStudentList($conditions,$limit,$orderBy,$userId);
	}
	else {

		$totalArray = $studentManager->getTotalStudent($conditions);
		$studentRecordArray = $studentManager->getStudentList($conditions,$limit,$orderBy);
	}
    
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {

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
        
		$studentRecordArray[$i]['rollNo'] = $studentRecordArray[$i]['rollNo'] == '' ? '--' : $studentRecordArray[$i]['rollNo'] ;

		$studentRecordArray[$i]['universityRollNo'] = $studentRecordArray[$i]['universityRollNo'] == '' ? '--' : $studentRecordArray[$i]['universityRollNo'] ;
		
		//$showlink = "<a href='studentDetail.php?id=".$studentRecordArray[$i]['studentId'].$qryString."&classId=".$studentRecordArray[$i]['class_id']."&page=".$page."&sortField=".$sortField."&sortOrderBy=".$sortOrderBy."' alt='Detail' title='Detail'><img src='".IMG_HTTP_PATH."/zoom.gif' border='0' /></a>&nbsp;&nbsp;<a href='#' onClick='printStudentReport(".$studentRecordArray[$i]['studentId'].",".$studentRecordArray[$i]['class_id'].")' title='Print'><img src='".IMG_HTTP_PATH."/print1.gif' border='0' /></a>";

		if(trim($studentRecordArray[$i]['firstName'])==''){
           $studentRecordArray[$i]['firstName']=NOT_APPLICABLE_STRING;
        }
        if(trim($studentRecordArray[$i]['fatherName'])==''){
           $studentRecordArray[$i]['fatherName']=NOT_APPLICABLE_STRING;
        }
        if(trim($studentRecordArray[$i]['motherName'])==''){
           $studentRecordArray[$i]['motherName']=NOT_APPLICABLE_STRING;
        }
        if(trim($studentRecordArray[$i]['guardianName'])==''){
           $studentRecordArray[$i]['guardianName']=NOT_APPLICABLE_STRING;
        }
        
        if(trim($studentRecordArray[$i]['userId'])!=''){
            $studentRecordArray[$i]['firstName']='<a class="whiteText" href="Javascript:void(0);" onclick="checkAndRedirect('.trim($studentRecordArray[$i]['userId']).');" title="Click to go to student login" >'.trim($studentRecordArray[$i]['firstName']).'</a>';
        }
        if(trim($studentRecordArray[$i]['fatherUserId'])!=''){
            $studentRecordArray[$i]['fatherName']='<a class="whiteText" href="Javascript:void(0);" onclick="checkAndRedirect('.trim($studentRecordArray[$i]['fatherUserId']).');" title="Click to go to parent login" >'.trim($studentRecordArray[$i]['fatherName']).'</a>';
        }
        if(trim($studentRecordArray[$i]['motherUserId'])!=''){
            $studentRecordArray[$i]['motherName']='<a class="whiteText" href="Javascript:void(0);" onclick="checkAndRedirect('.trim($studentRecordArray[$i]['motherUserId']).');" title="Click to go to parent login" >'.trim($studentRecordArray[$i]['motherName']).'</a>';
        }
        if(trim($studentRecordArray[$i]['guardianUserId'])!=''){
            $studentRecordArray[$i]['guardianName']='<a class="whiteText" href="Javascript:void(0);" onclick="checkAndRedirect('.trim($studentRecordArray[$i]['guardianUserId']).');" title="Click to go to parent login" >'.trim($studentRecordArray[$i]['guardianName']).'</a>';
        }

        $valueArray = array_merge(array('act' =>  $showlink, 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 14  *****************
//User: Gurkeerat    Date: 12/08/09   Time: 6:13p
//Updated in $/LeapCC/Library/Student
//resolved issue 0002209,0002208,0002206,0002169,0002148,0002147,0002151,
//0002219,0002095
//
//*****************  Version 13  *****************
//User: Parveen      Date: 12/05/09   Time: 11:40a
//Updated in $/LeapCC/Library/Student
//student Photo added
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Library/Student
//worked on role to class
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 09-08-21   Time: 12:50p
//Updated in $/LeapCC/Library/Student
//Added ACCESS right DEFINE in these modules
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 7/22/09    Time: 3:40p
//Updated in $/LeapCC/Library/Student
//added Registration No. and Fee receipt no in student filter
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 6/24/09    Time: 1:36p
//Updated in $/LeapCC/Library/Student
//0000188: Find Student (Admin-CC) > Data is not displaying in correct
//order on “student list report print” window 
//
//0000183: Find Student - Admin > Search is not working properly in IE
//browser 
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/09/09    Time: 4:15p
//Updated in $/LeapCC/Library/Student
//Updated issues sent by Sachin sir dated 9thjune
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Library/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Library/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/14/09    Time: 12:25p
//Updated in $/LeapCC/Library/Student
//Updated search filter with permanent cityid, stateId and countryid
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/23/08   Time: 11:16a
//Updated in $/LeapCC/Library/Student
//Added group filter in student search
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Library/Student
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 9/17/08    Time: 12:01p
//Updated in $/Leap/Source/Library/Student
//updated back button with class
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Library/Student
//updated as respect to subject centric
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 9/03/08    Time: 3:10p
//Updated in $/Leap/Source/Library/Student
//updated formatting and spacing
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/22/08    Time: 5:48p
//Updated in $/Leap/Source/Library/Student
//updated print reports
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Library/Student
//updated formatting and print reports
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/11/08    Time: 10:59a
//Updated in $/Leap/Source/Library/Student
//updated the formatting and other issues
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/09/08    Time: 3:09p
//Updated in $/Leap/Source/Library/Student
//changed label text
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:19p
//Updated in $/Leap/Source/Library/Student
//made ajax based
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:44p
//Updated in $/Leap/Source/Library/Student
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/08    Time: 11:20a
//Created in $/Leap/Source/Library/Student
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:56p
//Created in $/Leap/Source/Library/SubjectToClass
//intial checkin

?>
