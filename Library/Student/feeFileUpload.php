<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student Roll No.
//
// Author : Jaineesh
// Created on : (08.10.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------

    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . "/FileUploadManager.inc.php");
    require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	 define('MODULE','FeeUpload');
	 define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    global $sessionHandler;

    if(trim($REQUEST_DATA['feeCycleId'])==''){
        echo ('<script type="text/javascript">alert("Plese select fee cycle");</script>');
        die;
    }
   /* if(trim($REQUEST_DATA['feeStudyPeriod'])==''){
        echo ('<script type="text/javascript">alert("Plese select study period");</script>');
        die;
    }*/
    
    $fileObj = FileUploadManager::getInstance('employeeInfoUploadFile');
    $filename = $fileObj->tmp;
    if ($filename == '') {
    	echo ('<script type="text/javascript">alert("Please Select a File to Upload");</script>');
        die;
    }

    if ($fileObj->fileExtension != 'xls') {
        $inconsistenciesArray[] = "Incorrect file format. Please read Notes.";
        $csvData = '';
        $i = 1;
        foreach($inconsistenciesArray as $key=>$record) {
            $csvData .= "$i $record\r\n";
            $i++;
        }
        $csvData = trim($csvData);
        $fileName = "FeeInfo_Inconsistencies.txt";
    
        UtilityManager::makeCSV($csvData,"$fileName",'textFile');   
        die;
    }
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    
    $studentManager = StudentManager::getInstance();
    require_once(BL_PATH . "/reader.php");
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read($filename);
    
    $m=0;
    $sheetNameArray = array();
    while(isset($data->boundsheets[$m]['name'])) {

		$sheetNameArray[] =  $data->boundsheets[$m]['name'];
        $m++;
    }

    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId = $sessionHandler->getSessionVariable('SessionId');

    $str = '';
    $totalRecordCounter = 0;
    $inconsistenciesArray = array();
	$totalArray = array();
    $successArray = array();
    $insertQueryArray = array();
    
    $roomNameBlockIdArray = array();
    $roomAbbrBlockIdArray = array();
    
    $totalEmployees = 0;
    function replaceChar($newChar) {
      $firstChar = substr($newChar,0,1);
      if(ord($firstChar) == 160) {
		
		return substr($newChar,1);
      }
      else{
        
		return $newChar; 
      }
    }

    //$getClassId = $REQUEST_DATA['classId'];
    $userId=$sessionHandler->getSessionVariable('UserId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $feeType=4;
    $studentCountFL=0;
    $serverDate=date('Y-m-d');
    $serverDateArray=explode('-',$serverDate);
    //converting date to gregorian format 
    $serverDt=gregoriantojd($serverDateArray[1],$serverDateArray[2],$serverDateArray[0]);

    if(SystemDatabaseManager::getInstance()->startTransaction()) {
        
		foreach($sheetNameArray as $sheetIndex=>$value) {

			for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
                if ($data->sheets[$sheetIndex]['cells'][1][1] != "Sno") {
					$inconsistenciesArray[] = "Data has not entered in given format";
                    continue;
                }
            }
			$feeCalArray = array();
            for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {

					$srNo        = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][1]));
					$rollNo      = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][2]));
					$rectNo      = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][3]));
					$paidDate    = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][4]));
					$dateGiven   = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][4]));
                    $dateArr     = explode('.',$paidDate);
                    $paidDate	 = $dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1];
					$paidDate1	 = $dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1];

					$paidFee      = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][5]));
					$paidFeeAmt   = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][5]));
                    $transId      = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][6]));
					$studentName1 = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][7]));
					$className    = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][8]));
					$semester     = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][9]));
                    
                    //correct date format check
                    if(!checkdate($dateArr[0],$dateArr[1],$dateArr[2])){

						$inconsistenciesArray[] = ",$rollNo,$rectNo,$dateGiven,$paidFee,$transId,$studentName1,$className,$semester,Incorrect date format at Sr. No.'$srNo'. It should be YYYY.MM.DD";
						continue;
                    }
                    $paidDt=gregoriantojd($dateArr[1],$dateArr[2],$dateArr[0]);
                    if(($serverDt-$paidDt)<0){
                        
						$inconsistenciesArray[] = ",$rollNo,$rectNo,$dateGiven,$paidFee,$transId,$studentName1,$className,$semester,Paid date cannot be grater than current date.Please correct it at Sr. No.'$srNo' ";
                        continue;
                    }
                    
                    if($srNo!=''){
                        
						$studentCountFL++;
                    }
                    else{
                        
						continue;
                    }
                    $paymentModeArray=array_keys($modeArr,$paymentMode);
                    if(count($paymentModeArray)>0){
                        
						$paymentMode=$paymentModeArray[0];
                    }
                    else{
                    }
                 
					if ($rollNo != '') {
							 
						 $conditions = " AND (LCASE(rollNo) = '".addslashes(strtolower($rollNo))."' OR  LCASE(universityRollNo) = '".addslashes(strtolower($rollNo))."' OR  LCASE(regNo) = '".addslashes(strtolower($rollNo))."')";

						 $studentFeesArray = $studentManager->getStudentDetailClass($conditions);
						 $studentId = $studentFeesArray[0]['studentId'];
                        
						 $studentName = $studentFeesArray[0]['firstName'].' '.$studentFeesArray[0]['lastName'];
						 if (empty($studentId)) {
							 $inconsistenciesArray[] = ",$rollNo,$rectNo,$dateGiven,$paidFee,$transId,$studentName1,$className,$semester,wrong student roll no.Kindly recheck Student Master";
							 continue;
						 }
						 else{

							$condition12 = "WHERE feeCycleId=".$REQUEST_DATA['feeCycleId']." AND studentId=".$studentFeesArray[0]['studentId'];
							$studentConcessionArray = $studentManager->getConcessionDetail($condition12);
							$cnt = count($studentConcessionArray);
							for($ik=0;$ik<$cnt;$ik++) {

							  $concessionArr[$studentConcessionArray[$ik]['feeHeadId']]=$studentConcessionArray[$ik]['concessionValue'].'~'.$studentConcessionArray[$ik]['concessionType'].'~'.$studentConcessionArray[$ik]['reason'];
							}
							$arrayIndex= $studentFeesArray[0]['instituteId']."~".$studentFeesArray[0]['universityId']."~".$studentFeesArray[0]['batchId']."~".$studentFeesArray[0]['degreeId']."~".$studentFeesArray[0]['branchId']."~".$studentFeesArray[0]['quotaId'];

 							$studentHeadFeesArray = $studentManager->getStudentFeeHeadDetailClass(trim($REQUEST_DATA['feeCycleId']),trim($studentFeesArray[0]['studyPeriodId']),$studentFeesArray[0]['instituteId'],$studentFeesArray[0]['universityId'],$studentFeesArray[0]['batchId'],$studentFeesArray[0]['degreeId'],$studentFeesArray[0]['branchId'],$studentFeesArray[0]['quotaId'],$isLeet);
							 
							$busCondition = "";
							if($studentFeesArray[0]['busStopId']!=0){
							
								$busCondition = " and bus.busStopId = ".$studentFeesArray[0]['busStopId'];
							} 
							$studentBusFeesArray = $studentManager->getStudentBusDetailClass($busCondition,$transportFacility);
							$hostelCondition = "";
							//echo $studentFeesArray[0]['hostelRoomId'];
							//die();
							if($studentFeesArray[0]['hostelRoomId']!=0){
							
								$hostelCondition = "  and hosroom.hostelRoomId = ".$studentFeesArray[0]['hostelRoomId'];
								$hostelFacility=0;
							}
							else{
								if($studentFeesArray[0]['hostelFacility']){
	
									$hostelFacility=1;
								}
								else{
								
									$hostelFacility=0;
								}
							}

							$studentHostelFeesArray = $studentManager->getStudentHostelDetailClass($hostelCondition,$hostelFacility);
							$studentFeesArray1 = array_merge ($studentHeadFeesArray,$studentBusFeesArray,$studentHostelFeesArray);
							
							$hostelFacility = $studentFeesArray[0]['hostelFacility'];
							$totalFees = 0;
							if($hostelFacility==1){
			
								$query = "Select  fhv.feeHeadAmount from fee_head feehead, fee_head_values fhv WHERE feehead.hostelHead = 1 AND feehead.feeHeadId=fhv.feeHeadId";
								$recordArr     = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
								$totalFees = $recordArr[0]['feeHeadAmount'];
							}

							$cnt = count($studentHeadFeesArray);
							$query     = "SELECT MAX(installmentCount) as installmentNo FROM `fee_receipt` WHERE studentId = ".$studentId." AND feeCycleId =".trim($REQUEST_DATA['feeCycleId'])." AND feeStudyPeriodId =".trim($studentFeesArray[0]['studyPeriodId'])." AND feeType=".$feeType;
							$recordArr     = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
							$installmentCount = $recordArr[0]['installmentNo'];
							$installmentCountAmt = 0;
							
							for($lm=0;$lm<$cnt;$lm++) {
								
								$totalFees += $studentHeadFeesArray[$lm]['feeHeadAmount'];
								if($installmentCount==0){

									$ConcessionArr1 = explode('~',$concessionArr[$studentFeesArray1[$lm]['feeHeadId']]);	 
									if($ConcessionArr1[1]==1){
									
										$totalCon += number_format((($studentHeadFeesArray[$lm]['feeHeadAmount']*$ConcessionArr1[0])/100),"2",".","");
									}
									else{
										
										$totalCon += number_format($ConcessionArr1[0],"2",".","");;
									}
								}
							}
							$totalFees=$totalFees-$totalCon;
							
							if($installmentCount>0){
							
								 $query = "SELECT SUM(totalAmountPaid) as toalInstallmentAmt FROM `fee_receipt` WHERE studentId = ".$studentId." AND feeCycleId =".trim($REQUEST_DATA['feeCycleId'])." AND feeStudyPeriodId=".trim($studentFeesArray[0]['studyPeriodId'])." AND feeType=".$feeType;

								 $recordArrAmt     = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
								 $installmentCountAmt = $recordArrAmt[0]['toalInstallmentAmt'];
								 $paidFee = $installmentCountAmt+$paidFee;
							}
							if($paidFee==$totalFees){
							   
							   $previousDues=0;
							   $previousOverPayment=0;
							}
							else{
								
								$diff=$totalFees-$paidFee;
								if($diff>0){
									
									$previousDues=$diff;
									$previousOverPayment=0;
								}
								else{
									
									$previousDues=0;
									$previousOverPayment=-$diff;
								}
							}
							if($rectNo==''){

								$query = "SELECT receiptNo FROM fee_receipt $condition ORDER BY feeReceiptId DESC LIMIT 0,1";
								$registrationArr = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query"); 
								if($registrationArr[0]['receiptNo']){
									
									$receiptNo =  $registrationArr[0]['receiptNo']+1;
								}
								else{
									
									$receiptNo = $sessionHandler->getSessionVariable('FEE_RECEIPT_START');
								}
							}
							else{
							
								$receiptNo = $rectNo;
							}
							$dicountedFeePayable=0;
							 //calculating installment count
							 
							$installmentCount = $installmentCount + 1;
							$ret=$studentManager->addStudentFeeRecipt($studentId,$instituteId,$installmentCount,trim($REQUEST_DATA['feeCycleId']),$receiptNo,$receiptNo,$paidDate,$studentFeesArray[0]['studyPeriodId'],trim($REQUEST_DATA['feeStudyPeriod']),$totalFees,($totalFees-$installmentCountAmt),$paymentMode,$chequeDraftNo,$paidDate,$previousDues,$previousOverPayment,$studentName,$userId,$feeType,$transId,$paidFeeAmt);
							if($ret==false){
								
								$inconsistenciesArray[] = ",$rollNo,$receiptNo,$paidDate1,$paidFee,$transId,$studentName1,$className,$semester,Data is not saved for student";
								//$inconsistenciesArray[] = "Data is not saved for student at Sr. No.'$srNo'";
								continue; 
							}
							$lastReceipt = SystemDatabaseManager::getInstance()->lastInsertId();
							
							for($ki=0;$ki<count($studentFeesArray1); $ki++){

							   $feeHeadAmt = $studentFeesArray1[$ki]['feeHeadAmount'];	
								   if($feeHeadAmt==''){
								   
										$feeHeadAmt=0;
								   }
								   $ret=$studentManager->addStudentFeeHead($lastReceipt,$studentId,$studentFeesArray1[$ki]['feeHeadId'],trim($REQUEST_DATA['feeCycleId']),$feeHeadAmt,$studentFeesArray[0]['studyPeriodId'],trim($REQUEST_DATA['feeStudyPeriod'])); 
                                   if($ret==false){
                                    
										$inconsistenciesArray[] = ",$rollNo,$receiptNo,$dateGiven,$paidFee,$transId,$studentName1,$className,$semester,Data is not saved for student";
										//$inconsistenciesArray[] = "Data is not saved for student at Sr. No.'$srNo'";
										continue; 
								   }
							}
							//}
						}
					}
					else{
						$inconsistenciesArray[] = "roll no doest not exists";
			        }
			}
		}
	}
	$inconsistenciesArray1 = implode( ',',$inconsistenciesArray);
	//if (count($inconsistenciesArray) == 0) {

    if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        /*$dataString = "Data saved successfully for ".$studentCountFL." students";
        $fileName = "FeeInfo.txt";
        ob_end_clean();
        header("Cache-Control: public, must-revalidate");
        header("Pragma: hack"); // WTF? oh well, it works...
        header("Content-Type: application/octet-stream");
        header("Content-Length: " .strlen($dataString));
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header("Content-Transfer-Encoding: text\n");
        echo $dataString;
        die;*/
    }
//}
//else {
    $csvData = '';
    $i = 0;
    foreach($inconsistenciesArray as $key=>$record) {
      $csvData .= ($i+1)." $record\r\n";
      $i++;
    }
    
    if($i!=0) {
	  $csvData1 = "Sno,Roll No/Registration No,Receipt no,Paid Date,Paid Fee,Trans_id,Student Name,Class,Semester,Reason\n";
	  $csvData = trim($csvData1.$csvData);
	  UtilityManager::makeCSV($csvData,"$fileName"); 
    }
	die;         
/*
    $csvData = trim($csvData);
    $fileName = "FeeInfo_Inconsistencies.txt";
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    header("Pragma: hack"); // WTF? oh well, it works...
    header("Content-Type: application/octet-stream");
    header("Content-Length: " .strlen($csvData));
    header('Content-Disposition: attachment; filename="'.$fileName.'"');
    header("Content-Transfer-Encoding: text\n");
*/
	
//}
?>