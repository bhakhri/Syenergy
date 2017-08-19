<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch student list
// Author : Dipanjan Bbhattacharjee
// Created on : (07.05.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MANAGEMENT_ACCESS',1);
    define('MODULE','StudentConcession');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentConcessionManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentConcessionManager = StudentConcessionManager::getInstance();
    $studentManager = StudentManager::getInstance();

    /////////////////////////
    
    
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
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".$genName."%'";
  }  
  
  return $genName;
}

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_ADMIN_MESSAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_ADMIN_MESSAGE;

    //////
	global $sessionHandler;

	$userId= $sessionHandler->getSessionVariable('UserId');

    foreach($REQUEST_DATA as $key => $values) {
        $$key = trim($values);
    }
    $conditionsArray = array();
    
    if (!empty($rollNo)) {
        $conditionsArray[] = " s.rollNo LIKE '".add_slashes($rollNo)."%' ";
    }
    if (!empty($studentName)) {
         $parsedName=parseName(trim($studentName));    //parse the name for compatibality
        //$conditionsArray[] = " CONCAT(s.firstName, ' ', s.lastName) like '%$studentName%' ";
        $conditionsArray[] = " (
                                  TRIM(s.firstName) LIKE '".add_slashes(trim($studentName))."%' 
                                  OR 
                                  TRIM(s.lastName) LIKE '".add_slashes(trim($studentName))."%'
                                  $parsedName
                               )";
        
    }
    if (!empty($degreeId)) {
        $conditionsArray[] = " cl.degreeId in ($degreeId) ";
    }
    if (!empty($branchId)) {
        $conditionsArray[] = " cl.branchId in ($branchId) ";
    }
    if (!empty($periodicityId)) {
        $conditionsArray[] = " cl.studyPeriodId IN ($periodicityId) ";
    }
    $course = $REQUEST_DATA['courseId'];
    if (!empty($course)) {
     $conditionsArray[] = " s.studentId IN (SELECT DISTINCT(studentId) FROM student WHERE classId IN (SELECT DISTINCT(classId) FROM subject_to_class WHERE subjectId IN($course))) ";
     
    }
    if (!empty($groupId)) {
        $conditionsArray[] = " s.studentId IN (SELECT studentId from student_groups WHERE groupId IN ($groupId)) ";
    }

    $fromDateA=$REQUEST_DATA['admissionMonthF'].'-'.$REQUEST_DATA['admissionDateF'].'-'.$REQUEST_DATA['admissionYearF'];
    if (!empty($fromDateA) and $fromDateA != '--') {
        $fromDateArr = explode('-',$fromDateA);
        $fromDateAM = intval($fromDateArr[0]);
        $fromDateAD = intval($fromDateArr[1]);
        $fromDateAY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateAM, $fromDateAD, $fromDateAY)) {
            $thisDate = $fromDateAY.'-'.$fromDateAM.'-'.$fromDateAD;
            $conditionsArray[] = " s.dateOfAdmission >= '$thisDate' ";
        }
    }
    $toDateA=$REQUEST_DATA['admissionMonthT'].'-'.$REQUEST_DATA['admissionDateT'].'-'.$REQUEST_DATA['admissionYearT'];
    if (!empty($toDateA) and $toDateA != '--') {
        $toDateArr = explode('-',$toDateA);
        $toDateAM = intval($toDateArr[0]);
        $toDateAD = intval($toDateArr[1]);
        $toDateAY = intval($toDateArr[2]);
        if (false !== checkdate($toDateAM, $toDateAD, $toDateAY)) {
            $thisDate = $toDateAY.'-'.$toDateAM.'-'.$toDateAD;
            $conditionsArray[] = " s.dateOfAdmission <= '$thisDate' ";
        }
    }

    $fromDateD=$REQUEST_DATA['birthMonthF'].'-'.$REQUEST_DATA['birthDateF'].'-'.$REQUEST_DATA['birthYearF'];
    if (!empty($fromDateD) and $fromDateD != '--') {
        $fromDateArr = explode('-',$fromDateD);
        $fromDateDM = intval($fromDateArr[0]);
        $fromDateDD = intval($fromDateArr[1]);
        $fromDateDY = intval($fromDateArr[2]);
        if (false !== checkdate($fromDateDM, $fromDateDD, $fromDateDY)) {
            $thisDate = $fromDateDY.'-'.$fromDateDM.'-'.$fromDateDD;
            $conditionsArray[] = " s.dateOfBirth >= '$thisDate' ";
        }
    }

    $toDateD=$REQUEST_DATA['birthMonthT'].'-'.$REQUEST_DATA['birthDateT'].'-'.$REQUEST_DATA['birthYearT'];
    if (!empty($toDateD) and $toDateD != '--') {
        $toDateArr = explode('-',$toDateD);
        $toDateDM = intval($toDateArr[0]);
        $toDateDD = intval($toDateArr[1]);
        $toDateDY = intval($toDateArr[2]);
        if (false !== checkdate($toDateDM, $toDateDD, $toDateDY)) {
            $thisDate = $toDateDY.'-'.$toDateDM.'-'.$toDateDD;
            $conditionsArray[] = " s.dateOfBirth <= '$thisDate' ";
        }
    }
    if (!empty($gender)) {
        $conditionsArray[] = " s.studentGender = '$gender' ";
    }
    if ($categoryId!='') {
        $conditionsArray[] = " s.managementCategory = $categoryId ";
    }
    if (!empty($quotaId)) {
        $conditionsArray[] = " s.quotaId IN ($quotaId) ";
    }
    if (!empty($hostelId)) {
        $conditionsArray[] = " s.hostelId IN ('$hostelId') ";
    }
    if (!empty($busStopId)) {
        $conditionsArray[] = " s.busStopId IN ('$busStopId') ";
    }
    if (!empty($busRouteId)) {
        $conditionsArray[] = " s.busRouteId IN ($busRouteId) ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " s.permCityId IN ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " s.permStateId IN ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " s.permCountryId IN ($countryId) ";
    }
    if (!empty($universityId)) {
        $conditionsArray[] = " cl.universityId IN ($universityId) ";
    }
    if (!empty($instituteId)) {
        $conditionsArray[] = " cl.instituteId IN ($instituteId) ";
    }
    
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " s.studentBloodGroup = $bloodGroup";
    }
    
    //fee receipt Number
    $feeReceiptNo = add_slashes(trim($REQUEST_DATA['feeReceiptNo']));
    if (!empty($feeReceiptNo)) {
        $conditionsArray[] = " s.feeReceiptNo LIKE '$feeReceiptNo%' ";
        $qryString.= "&feeReceiptNo=$feeReceiptNo";
    }

    //registration Number
    $instRegNo =add_slashes(trim($REQUEST_DATA['regNo']));
    if (!empty($instRegNo)) {
        $conditionsArray[] = " s.regNo LIKE '$instRegNo%' ";
        $qryString.= "&regNo=$instRegNo";
    }

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'firstName';
    
     $orderBy = " $sortField $sortOrderBy";      

    ////////////
	$userRoleArray = $studentConcessionManager->getRoleUser($userId);
	if(is_array($userRoleArray) and count($userRoleArray)>0){
        $classIdCondition=' AND c.classId IN ('.UtilityManager::makeCSList($userRoleArray,'classId').')';
        $groupIdCondition=' AND g.groupId IN ('.UtilityManager::makeCSList($userRoleArray,'groupId').')';
        $conditions .=$classIdCondition.$groupIdCondition;
    }
    
    if(trim($REQUEST_DATA['feeCycle'])==''){
        echo 'Required Parameters Missing';
        die;
    }
    $feeCycleId=trim($REQUEST_DATA['feeCycle']);	
	$feeHeadId=trim($REQUEST_DATA['feeHead']);
	$totalArray = $studentConcessionManager->getTotalStudent($conditions);
	$studentRecordArray = $studentConcessionManager->getStudentList($conditions,$limit,$orderBy,$feeCycleId,$feeHeadId);
    $cnt = count($studentRecordArray);
    
	
	for($i=0;$i<$cnt;$i++) {
       $studentId=$studentRecordArray[$i]['studentId']; 
       $classId=$studentRecordArray[$i]['classId']; 
       //now fetch total fees of this student
	   $studentTotalConcessionArr = $studentConcessionManager->getTotalConcession(' WHERE studentId='.$studentId.' AND classId='.$classId.' AND feeCycleId='.$feeCycleId);
	
		//print_r($studentTotalConcessionArr);
	   $totalConce = $studentTotalConcessionArr[0]['totalConcession'];	
	   //die();
       $studentFeesArray = $studentManager->getStudentDetailClass(' AND stu.studentId='.$studentId.' AND cls.classId='.$classId);
       $totalFees = 0;
	   $hostelFacility = 0;
	   $transportFacility = 0;
       if(is_array($studentFeesArray) and count($studentFeesArray)>0){
           $studentHeadFeesArray = $studentManager->getStudentFeeHeadDetailClass($feeCycleId,$studentFeesArray[0]['studyPeriodId'],$studentFeesArray[0]['instituteId'],$studentFeesArray[0]['universityId'],$studentFeesArray[0]['batchId'],$studentFeesArray[0]['degreeId'],$studentFeesArray[0]['branchId'],$studentFeesArray[0]['quotaId'],$studentFeesArray[0]['isLeet']);
           $feeCnt = count($studentHeadFeesArray);
           for($lm=0;$lm<$feeCnt;$lm++) {
                $totalFees += $studentHeadFeesArray[$lm]['feeHeadAmount'];
          }
       }
		 
	    $hostelFacility = $studentRecordArray[$i]['hostelFacility'];
	    $transportFacility = $studentRecordArray[$i]['transportFacility'];
		
		$busCondition = "";
		if($studentRecordArray[$i]['busStopId']!=0){
		
			$busCondition = " and bus.busStopId = ".$studentRecordArray[$i]['busStopId'];
		} 
		$studentBusFeesArray = $studentManager->getStudentBusDetailClass($busCondition,$transportFacility);

		$hostelCondition = "";
		if($studentRecordArray[$i]['hostelRoomId']!=0){
		
			$hostelCondition = "  and hosroom.hostelRoomId = ".$studentRecordArray[$i]['hostelRoomId'];
		} 
		$studentHostelFeesArray = $studentManager->getStudentHostelDetailClass($hostelCondition,$hostelFacility);

		$totalFees+=$studentBusFeesArray[0]['feeHeadAmount'];
		$totalFees+=$studentHostelFeesArray[0]['feeHeadAmount'];

       $studentRecordArray[$i]['totalFees']=$totalFees;

	   $totalFees1 = 0; 	
	   if(is_array($studentFeesArray) and count($studentFeesArray)>0){
           $studentHeadFeesArray = $studentManager->getStudentFeeHeadClass($feeCycleId,$studentFeesArray[0]['studyPeriodId'],$studentFeesArray[0]['instituteId'],$studentFeesArray[0]['universityId'],$studentFeesArray[0]['batchId'],$studentFeesArray[0]['degreeId'],$studentFeesArray[0]['branchId'],$studentFeesArray[0]['quotaId'],$studentFeesArray[0]['isLeet'],$REQUEST_DATA['feeHead']);
           $feeCnt = count($studentHeadFeesArray);
           for($lm=0;$lm<$feeCnt;$lm++) {
                $totalFees1 += $studentHeadFeesArray[$lm]['feeHeadAmount'];
          }
       }
       $totalFees1 = $totalFees1+$studentBusFeesArray[0]['feeHeadAmount']+$studentHostelFeesArray[0]['feeHeadAmount'];
       $studentRecordArray[$i]['totalFees1']=$totalFees1;

       $concessionType=$studentRecordArray[$i]['concessionType']; 
       $concessionValue=$studentRecordArray[$i]['concessionValue'];
	   $discValue=$studentRecordArray[$i]['discountValue'];
       $reason=$studentRecordArray[$i]['reason'];
	   $rollNo=$studentRecordArray[$i]['rollNo'];
       
       $percentageSelected='';
       $valueSelected='';
       if($concessionType==1){
           $percentageSelected='selected="selected"';
       }
       else if($concessionType==2){
           $valueSelected='selected="selected"';
       }
       $studentRecordArray[$i]['concessionType']='<select name="concessionType" id="concessionType_'.$studentId.'_'.$classId.'" class="inputbox" style="width:80px;" onchange="checkMaxPossibleValueForSelect(this,this.value);">
                                                  <option value="1" '.$percentageSelected.'>Percentage</option>
                                                  <option value="2" '.$valueSelected.'>Amount</option>
                                                  </select>';
       $studentRecordArray[$i]['concessionValue']='<input type="text" name="concessionValue" id="concessionValue_'.$studentId.'_'.$classId.'" class="inputbox" style="width:50px;" value="'.$concessionValue.'" alt="'.$totalFees1.'" onblur="checkMaxPossibleValue(this,this.value,this.alt);" />';
       
	   $studentRecordArray[$i]['discValue']='<input type="text" name="discValue" id="discValue_'.$studentId.'_'.$classId.'" class="inputbox" value="'.$discValue.'" maxlength="240" style="width:60px;" disabled/>';

       $studentRecordArray[$i]['reason']='<input type="text" name="reason" id="reason_'.$studentId.'_'.$classId.'" class="inputbox" value="'.$reason.'" maxlength="240" style="width:180px;"/>';

	   $printAction = "<a href='javascript:void(0)' onClick='editDetailReceipt(\"".$rollNo."\",".$studentId.",".$classId.",".$feeCycleId.",".$studentFeesArray[0]['studyPeriodId'].")' title='Edit'><img src=".IMG_HTTP_PATH."/edit.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>";

/*	   $printAction = "<a href='javascript:void(0)' onClick='editDetailReceipt(\"".$rollNo."\",".$studentId.",".$classId.",".$feeCycleId.")' title='Edit'><img src=".IMG_HTTP_PATH."/edit.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>|<a href='javascript:void(0)' onClick='printReceipt()' title='Print'><img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Receipt Print' title='Receipt Print' hspace='4'></a>";*/
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1),'printAction'=>$printAction,'totalConce'=>$totalConce,"students" => "<input type=\"checkbox\" name=\"students\" id=\"students\" value=\"".$studentRecordArray[$i]['studentId'] ."\">")
        , $studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.'],"studentInfo" : '.json_encode($totalArray).'}';
    
// for VSS
// $History: ajaxAdminStudentMessageList.php $
?>