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

    require_once(MODEL_PATH . "/CollectFeesManager.inc.php");   
    $collectFeesManager = CollectFeesManager::getInstance(); 
    
    require_once(MODEL_PATH . "/StudentConcessionManager.inc.php");
    $studentConcessionManager = StudentConcessionManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
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
    
    
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    
    if($sortField=='undefined') {
       $sortField='studentName'; 
    }
    if($sortOrderBy=='undefined') {
       $sortOrderBy='ASC';
    }
    
    $orderBy = " $sortField $sortOrderBy";      

    ////////////
	$userRoleArray = $studentConcessionManager->getRoleUser($userId);
	if(is_array($userRoleArray) and count($userRoleArray)>0){
        $classIdCondition=' AND c.classId IN ('.UtilityManager::makeCSList($userRoleArray,'classId').')';
        $groupIdCondition=' AND g.groupId IN ('.UtilityManager::makeCSList($userRoleArray,'groupId').')';
        $conditions .=$classIdCondition.$groupIdCondition;
    }
    
/*  
    if(trim($REQUEST_DATA['feeCycle'])==''){
        echo 'Required Parameters Missing';
        die;
    }
*/  
    
    if(trim($REQUEST_DATA['feeHead'])==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    if(trim($REQUEST_DATA['quota'])==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    if(trim($REQUEST_DATA['leet'])==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    if(trim($REQUEST_DATA['feeClassId'])==''){
        echo 'Required Parameters Missing';
        die;
    }
    
//  $feeCycleId=trim($REQUEST_DATA['feeCycle']);	
	$feeHeadId=trim($REQUEST_DATA['feeHead']);
    $quotaId=trim($REQUEST_DATA['quota']);    
    $leetId=trim($REQUEST_DATA['leet']);
    $isLeet =  $leetId;
    
    $tquotaId=$quotaId;
    if($quotaId=='all') {
      $quotaId='';
      $tquotaId='';
    }
    
    $feeCondition = '';
    if(strtolower($quotaId)!='') {
      $conditions .= " AND s.quotaId = $quotaId";
      $feeCondition .= " AND fh.quotaId = $quotaId";
    }
    
    if($leetId!='3') {
      if($leetId==2) {  
        $conditions .= " AND s.isLeet = 0";
        $feeCondition .= " AND fh.isLeet = 0";
      }
      else {
        $conditions .= " AND s.isLeet = $leetId";
        $feeCondition .= " AND fh.isLeet = $leetId";
      }
    }
    
    $feeClassId = $REQUEST_DATA['feeClassId'];
	$totalArray = $studentConcessionManager->getStudentList($conditions,'',$orderBy,$feeHeadId,$feeCondition,$feeClassId); 
    
	$studentRecordArray = $studentConcessionManager->getStudentList($conditions,$limit,$orderBy,$feeHeadId,$feeCondition,$feeClassId);
    $cnt = count($studentRecordArray);
    
   for($i=0;$i<$cnt;$i++) {
       $studentId=$studentRecordArray[$i]['studentId']; 
       $classId=$studentRecordArray[$i]['feeClassId'];
       $tIsLeet=$studentRecordArray[$i]['isLeet']; 
       
       if($tIsLeet==0) {
         $tIsLeet= 2; 
       }
       else if($tIsLeet==1) {
         $tIsLeet= 1; 
       }
       else {
         $tIsLeet=3;  
       }                     
                    
       if($tquotaId=='') {
         $quotaId = $studentRecordArray[$i]['quotaId'];  
       }
                     
       $feeHeadAmount = "0"; 
        // Quota wise Validation start
       $feeId = "-1";
       $havingConditon = " COUNT(fhv.feeHeadId) = 1 AND fhv.feeHeadId = $feeHeadId"; 
       $foundArray = $collectFeesManager->getCountFeeHead($studentId,$feeClassId,$quotaId,$isLeet,'',$havingConditon);
       for($j=0; $j<count($foundArray); $j++) {
         $feeId .=",".$foundArray[$j]['feeId'];  
       }
       
       $havingConditon = " COUNT(fhv.feeHeadId) >= 2 AND fhv.feeHeadId = $feeHeadId"; 
       $foundArray = $collectFeesManager->getCountFeeHead($studentId,$feeClassId,$quotaId,$isLeet,'',$havingConditon);
       for($j=0; $j<count($foundArray); $j++) {
           $tFeeHeadId = $foundArray[$j]['feeHeadId']; 
           
           if($quotaId!='') {
              $feeHeadCondition = " quotaId = $quotaId AND feeHeadId = $tFeeHeadId AND isLeet=$tIsLeet AND classId = $feeClassId ";
              $quotaFoundArray = $collectFeesManager->getConcessionFeeHead($feeHeadCondition);  
              if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                $feeId .=",".$quotaFoundArray[0]['feeHeadValueId'];  
                $studentRecordArray[$i]['feeHeadAmount'] = $quotaFoundArray[0]['feeHeadAmount']; 
              }
              else {
                $feeHeadCondition = " IFNULL(quotaId,'')='' AND feeHeadId = $tFeeHeadId AND isLeet = $tIsLeet AND classId = $feeClassId ";
                $quotaFoundArray = $collectFeesManager->getConcessionFeeHead($feeHeadCondition);  
                if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                  $feeId .=",".$quotaFoundArray[0]['feeHeadValueId'];  
                  $studentRecordArray[$i]['feeHeadAmount'] = $quotaFoundArray[0]['feeHeadAmount']; 
                }
                else {
                   $feeHeadCondition = " AND IFNULL(fhv.quotaId,'')='' AND fhv.feeHeadId = $tFeeHeadId";  
                   $quotaFoundArray = $collectFeesManager->getCountFeeHeadNew($feeClassId,$quotaId,$tIsLeet,'',$feeHeadCondition);  
                   if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
                     $feeId .=",".$quotaFoundArray[0]['feeId'];  
                   }
                }
              }
           }
           else {
             $feeHeadCondition = " IFNULL(quotaId,'')='' AND feeHeadId = $tFeeHeadId AND isLeet = $tIsLeet AND classId = $feeClassId ";
             $quotaFoundArray = $collectFeesManager->getConcessionFeeHead($feeHeadCondition);   
             if(is_array($quotaFoundArray) && count($quotaFoundArray)>0 ) { 
               $feeId .=",".$quotaFoundArray[0]['feeHeadValueId']; 
               $studentRecordArray[$i]['feeHeadAmount'] = $quotaFoundArray[0]['feeHeadAmount'];    
             } 
           }
       }      
       if($feeId=='') {
         $feeId = "-1"; 
       }
       // Quota wise Validation end
        /* 
           $foundArray = $collectFeesManager->getStudentFeeHeadDetail($studentId,$feeClassId,$quotaId,$isLeet,'',$feeId); 
           if(is_array($foundArray) && count($foundArray)>0 ) { 
             $feeHeadAmount = $foundArray[0]['feeHeadAmt1']; 
           }
           if($feeHeadAmount=='' || $feeHeadAmount=='-1' ) {
             $feeHeadAmount = "0"; 
           }
           $studentRecordArray[$i]['feeHeadAmount'] = $feeHeadAmount;
       */ 
       $totalFees1 = $studentRecordArray[$i]['feeHeadAmount'];
       
       
       $totalConce = '';
	   //print_r($studentTotalConcessionArr);
       
       $concessionType=$studentRecordArray[$i]['concessionType'];   
       $concessionValue=$studentRecordArray[$i]['concessionValue'];
	   $discValue=$studentRecordArray[$i]['discountValue'];
       $reason=$studentRecordArray[$i]['reason'];
	   $rollNo=$studentRecordArray[$i]['rollNo'];
       
       if($concessionType==1) {
          $studentRecordArray[$i]['totalConce'] = $totalFees1 * $concessionValue/100.0;
          $discValue=$totalFees1-$studentRecordArray[$i]['totalConce']; 
       }
       else if($concessionType==2) {
          $studentRecordArray[$i]['totalConce'] = $concessionValue;
          $discValue=$totalFees1-$studentRecordArray[$i]['totalConce'];  
       }
       
       //if($studentRecordArray[$i]['concessionId']!=-1) {
       //  $totalConce = $totalFees1 - $studentRecordArray[$i]['discountValue'];    
       //}
       
       
       $percentageSelected='';
       $valueSelected='';
       if($concessionType==1){
           $percentageSelected='selected="selected"';
       }
       else if($concessionType==2){
           $valueSelected='selected="selected"';
       }
       $studentRecordArray[$i]['concessionType']='<select name="concessionType" id="concessionType_'.$studentId.'_'.$classId.'" class="inputbox" style="width:120px;" onchange="checkMaxPossibleValueForSelect(this,this.value);">
                                                  <option value="1" '.$percentageSelected.'>Percentage</option>
                                                  <option value="2" '.$valueSelected.'>Amount</option>
                                                  </select>';
       $studentRecordArray[$i]['concessionValue']='<input type="text" name="concessionValue" id="concessionValue_'.$studentId.'_'.$classId.'" class="inputbox" style="width:50px;" value="'.$concessionValue.'" alt="'.$totalFees1.'" onblur="checkMaxPossibleValue(this,this.value,this.alt);" />';
       
	   $studentRecordArray[$i]['discValue']='<input type="text" name="discValue" id="discValue_'.$studentId.'_'.$classId.'" class="inputbox" value="'.$discValue.'" maxlength="10" style="width:60px;" disabled/>&nbsp;';

       $studentRecordArray[$i]['reason']='<input type="text" name="reason" id="reason_'.$studentId.'_'.$classId.'" class="inputbox" value="'.$reason.'" maxlength="240" style="width:120px;"/>&nbsp;';

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