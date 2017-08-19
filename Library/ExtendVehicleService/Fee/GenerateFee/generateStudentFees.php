<?php 
//  This File calls addFunction used in adding FeeHead Records
// Author :Nishu Bindal
// Created on : 2-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
$feeHeadManager = FeeHeadManager::getInstance();
define('MODULE','GenerateStudentFees');     
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache(); 


global $sessionHandler;
$queryDescription =''; 
$instituteId = $sessionHandler->getSessionVariable('InstituteId');
$userId = $sessionHandler->getSessionVariable('UserId');
    $errorMessage ='';

	
	if(!isset($REQUEST_DATA['degreeId']) || trim($REQUEST_DATA['degreeId']) == '') {
		$errorMessage .= SELECT_DEGREE."\n";
	}
	if($errorMessage == '' && (!isset($REQUEST_DATA['branchId']) || trim($REQUEST_DATA['branchId']) == '')) {
		$errorMessage .= SELECT_BRANCH."\n";
	}
	if($errorMessage == '' && (!isset($REQUEST_DATA['batchId']) || trim($REQUEST_DATA['batchId']) == '')) {
		$errorMessage .= SELECT_BATCH."\n";
	}
	if(!isset($REQUEST_DATA['classId']) || trim($REQUEST_DATA['classId']) == '') {
		$errorMessage .= SELECT_CLASS."\n";
	}
	if(!isset($REQUEST_DATA['feeCycleId']) || trim($REQUEST_DATA['feeCycleId']) == '') {
		$errorMessage .= SELECT_FEECYCLE."\n";
	}
	
	$feeClassId  = trim($REQUEST_DATA['classId']); 
	$feeCycleId  = trim($REQUEST_DATA['feeCycleId']); 
	
	if(trim($errorMessage) == ''){
		if(SystemDatabaseManager::getInstance()->startTransaction()){
			require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
			$generateFeeManager = GenerateFeeManager::getInstance(); 
			// to fetch Current class of student
			$classArray = $generateFeeManager->getClass($feeClassId);
		
			
			if(count($classArray) == 0){
				echo "Class Not Found";
				die;
			}
			$classes = '';
			foreach($classArray as $key => $value){
				if($classes == ''){
					$classes = $value['classId'];
				}
				else{
					$classes .= ",".$value['classId'];
				}
			} 
			//$currentClass = $classArray[0]['currentClassId'];
			$feeStudyPeriodId = $classArray[0]['feeStudyPeriodId'];
	
			// check for already FEE Generated for this class
			$alreadyGenerated = $generateFeeManager->checkForAlreadyGenerated($feeClassId,$classes,$feeCycleId);
			if($alreadyGenerated[0]['cnt']> 0){
				echo "Fee is Already Generated For This Class!!!";
				die;
			}
	
			// to Get Student Details
			$studentDataArray = $generateFeeManager->getStudentDetails($classes);
			if(count($studentDataArray) == 0 || !is_array($studentDataArray)) {
			      echo "Students Not Found";  
			      die;
			}
	
			$currentClass = $studentDataArray[0]['classId'];
			$instituteAbbArray = $sessionHandler->getSessionVariable('InstituteAbbArray');
			$instituteId = $sessionHandler->getSessionVariable('InstituteId');
			$instituteAbbr =  $instituteAbbArray[$instituteId];
			$instituteBankAccountNo = $_SESSION['INSTITUTE_ACCOUNT_NO'];
			$instituteBankId = $_SESSION['INSTITUTE_BANK_NAME'];
	
			if($instituteBankId !=''){
				$bankNameArray=$generateFeeManager->getInstituteBankName($instituteBankId);
				$instituteBankName = $bankNameArray[0]['bankAbbr'];
	       		}
	       		$j=1;
	       		foreach($studentDataArray as $key =>$studentArr){
	       			$concession = '';
	       			$hostelFees='';
	       			$transportFees='';
	       			$busRouteId   = '';
				$busStopId = '';
				$feeReceiptId = '';
				$totalAcademicFee =0;
				$hostelSecurity = 0;
				 // to get Student Concession       			
	       			 $adhocCondition =" AND acm.feeClassId = '".$feeClassId."' AND acm.studentId = '".$studentArr['studentId']."'"; 
				 $adhocArray=$generateFeeManager->getStudentAdhocConcession($adhocCondition);
				 $concession = $adhocArray[0]['adhocAmount']; // concession Amount
				 $isMigration ='-1';
				 if($studentArr['isMigration'] == 1){
				 	$isMigration = 3;
				 }
				 // to get Student Fee Heads
				 $foundArray = $generateFeeManager->getStudentFeeHeadDetail($feeClassId,$studentArr['quotaId'],$studentArr['isLeet'],$studentArr['studentId'],$isMigration);
				
				 if(count($foundArray) == 0){
				 	echo FEE_HEAD_NOT_DEFINE;
				 	die;
				 }
				  $feeArray = array();
				  $applicableHeadId = array();
				  $index = array();
			    
				  // code to find only applicable Head Value 
				       foreach($foundArray as $key =>$subArray){
				       		if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
	       						$flag1 = true; // used for filtering purpose
	       					}  
				       		$flag= true;
				       		foreach($foundArray as $key1 =>$subArray1){
				       			if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 3)&& (($subArray['quotaId'] == $studentArr['quotaId']) && $subArray['isLeet'] == $isMigration)){
				       				$flag = true;
				       				foreach($applicableHeadId as $key2 => $value){
				       					if($value == $subArray['feeHeadId']){
				       						$applicableHeadId[$key2] = $subArray['feeHeadId'];
				       						$index[$key2] = $key;
				       						$flag= false;
				       					}	
				       				}
				       				if($flag){
				       					$applicableHeadId[] = $subArray['feeHeadId'];
				       					$index[] = $key;
				       				}
				       				break;
				       			}
				       			else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 1) && (($subArray['quotaId'] == $studentArr['quotaId']) && ($subArray['isLeet'] == $studentArr['isLeet']))){ 
				       				$flag = true;
				       				foreach($applicableHeadId as $key2 => $value){
				       					if($value == $subArray['feeHeadId']){
				       						$applicableHeadId[$key2] = $subArray['feeHeadId'];
				       						$index[$key2] = $key;
				       						$flag= false;
				       					}	
				       				}
				       				if($flag){
				       					$applicableHeadId[] = $subArray['feeHeadId'];
				       					$index[] = $key;
				       				}
				       				break;
				       			}
				       			else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $flag1 == true) && (($subArray['quotaId'] == 0) && $subArray['isLeet'] == $isMigration)){ 
				       				$flag = true;
				       				foreach($applicableHeadId as $key2 => $value){
				       					if($value == $subArray['feeHeadId']){
				       						$applicableHeadId[$key2] = $subArray['feeHeadId'];
				       						$index[$key2] = $key;
				       						$flag= false;
				       					}	
				       				}
				       				if($flag){
				       					$applicableHeadId[] = $subArray['feeHeadId'];
				       					$index[] = $key;
				       				}
				       				break;
				       			}
				       			else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && !in_array($subArray['feeHeadId'],$applicableHeadId)) && (($subArray['quotaId'] == $studentArr['quotaId']) || (($subArray['isLeet'] == $studentArr['isLeet']) || $subArray['isLeet'] == $isMigration))){ 
				       				$applicableHeadId[] = $subArray['feeHeadId'];
				       				$index[] = $key;
				       				break;
				       			
				       			}
				       		}      		
				      }
		
				      $applicableHeadId = array_unique($applicableHeadId); 
					
					// to put other heads 
				      foreach($foundArray as $key =>$subArray){
			      			if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
			      				$feeArray[$key] = $foundArray[$key];
			      			}
				      }
					// to insert aplicable head at there place
				      $index = array_unique($index);
				      foreach($index as $key =>$value){
				      	$feeArray[$value] = $foundArray[$value];
				      }
			       // this is done to mantain the order of fee it stores the key
			     	$indexArr = array();
			     	foreach($feeArray as $key =>$value){
			     		$indexArr[] = $key;
			     	}
				sort($indexArr); // to sort the index
		
				if((($studentArr['hostelId'] != '') && ($studentArr['hostelRoomId'] !='')) && ($feeStudyPeriodId !='')){
					$hostelFeeArray = $generateFeeManager->getStudentHostelFee($studentArr['studentId'],$feeClassId);
					$hostelFees = $hostelFeeArray[0]['roomRent'];
					$hostelSecurity = $hostelFeeArray[0]['securityAmount'];
				}
		
				if($studentArr['busRouteStopMappingId'] !=''){
					$transportFeeArr = $generateFeeManager->getStudentTransportFee($studentArr['studentId'],$feeClassId,$studentArr['busRouteStopMappingId']);
					$transportFees = $transportFeeArr[0]['transportFee'];
					$busRouteId   = $transportFeeArr[0]['busRouteId'];
					$busStopId   = $transportFeeArr[0]['busStopId'];
				}
		
				//feeReceiptId,bankId,instituteBankAccountNo,studentId,currentClassId,feeClassId,feeCycleId,concession,
				//hostelFees,hostelId,hostelRoomId,transportFees,busRouteId,busStopId,status,userId,instituteId,hostelFeeStatus,transportFeeStatus
				//,dated,receiptGeneratedDate 
				$studentId = $studentArr['studentId'];
				$values =  "('','$instituteBankId','$instituteBankAccountNo','$studentId','$currentClass','$feeClassId','$feeCycleId','$concession',
				'$hostelFees','".$studentArr['hostelId']."','".$studentArr['hostelRoomId']."','$transportFees','$busRouteId',
				'$busStopId',1,'$userId','$instituteId',0,0,now(),'$hostelSecurity','0000-00-00 00:00:00')";
				
				$status = $generateFeeManager->insertIntoFeeReceiptMaster($values);
				if($status === FALSE){
					echo FALIURE;
					die;
				}
		
				$feeReceiptId=SystemDatabaseManager::getInstance()->lastInsertId();
				$cnt = count($indexArr);
				$instrumentValues = '';
				for($i=0;$i<$cnt; $i++){
					//feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount,feeStatus
                    if($feeArray[$indexArr[$i]]['feeHeadAmt'] > 0) {     
					    if($instrumentValues != ''){
						    $instrumentValues .=", ";
					    }
                        $instrumentValues .="('','$feeReceiptId','$studentId','$feeClassId','".$feeArray[$indexArr[$i]]['feeHeadId']."','".ucwords($feeArray[$indexArr[$i]]['headName'])."','".$feeArray[$indexArr[$i]]['feeHeadAmt']."',0)";
					    $totalAcademicFee += floatval($feeArray[$indexArr[$i]]['feeHeadAmt']);
					    $totalAcademicFee = " ".$totalAcademicFee;
                    }
				}
		
				$status1 = $generateFeeManager->insertIntoReceiptInstrument($instrumentValues);
				if($status1 === FALSE){
					echo FALIURE;
					die;
				}
				if($transportFees == ''){
					$transportFees ="---";
				}
				if($hostelFees == ''){
					$hostelFees = "---";
				}
				if($totalAcademicFee == ''){
					$totalAcademicFee ="---";
				}
				if($concession == ''){
					$concession ="---";
				}
				if($hostelSecurity == '' || $hostelSecurity == '0.00'){
					$hostelSecurity ="---";
				}
				feeTypeValueFormat($j,$totalAcademicFee,$concession,$transportFees,$hostelFees,$studentArr['regNo'],$studentArr['studentName'],$hostelSecurity);
				$j++;
	       		}
		
		
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				echo '{"feeInfo" :['.$feePaid_val.']}';
			}
			else {
				echo FAILURE;
				die; 
			}
		}
		else {
			echo FAILURE;
			die;
		}
	}
	else{
		echo $errorMessage;
	}
	 
	 // function to make associative array
	function feeTypeValueFormat($srNo,$totalAcademicFee,$concession,$transportFees,$hostelFees,$regNo,$studentName,$hostelSecurity) {
			global $feePaid_val;
			$valueArray1 = array_merge(array('srNo' => $srNo,
			'regNo' =>"$regNo",
			'studentName' => "$studentName",
			'academicFee' =>"$totalAcademicFee",
			'concession' => "$concession",
			'transportFee' =>"$transportFees",
			'hostelFee' =>"$hostelFees",
			'hostelSecurity' =>"$hostelSecurity"));
			   
			if(trim($feePaid_val)=='') {
				$feePaid_val = json_encode($valueArray1);
			}
			else {
				$feePaid_val .= ','.json_encode($valueArray1);           
			}                                    
		} 
 
?>
