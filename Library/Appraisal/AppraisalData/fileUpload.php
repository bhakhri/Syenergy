
<?php
//-------------------------------------------------------------------
// THIS FILE IS USED TO upload student photo from student login 
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UploadAppraisalDetail');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);

require_once(BL_PATH . "/FileUploadManager.inc.php");

global $sessionHandler;  

$sessionHandler->setSessionVariable('Hostel_UploadStudentDetails','');

$instituteId=$sessionHandler->getSessionVariable('InstituteId');
$sessionId=$sessionHandler->getSessionVariable('SessionId');

require_once(MODEL_PATH . '/Appraisal/AppraisalDataManager.inc.php');
$appDataManager = AppraisalDataManager::getInstance();


$fileObj = FileUploadManager::getInstance('appraisalDetailUploadFile');
$fileName = $fileObj->tmp;
/*
if($fileName==''){
	echo '<script language="javascript">parent.fileUploadError("'.SELECT_FILE_FOR_UPLOAD.'")</script>';
	die;
}*/
if ($fileObj->fileExtension != 'xls') {
        
        echo ('<script type="text/javascript">alert("Please Select Excel File");</script>');
        die;
    }

if($fileObj->fileExtension=='xls') {
	require_once(BL_PATH . "/reader.php");
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('CP1251');
	$data->read($fileName); 

	$m=0;
	$sheetNameArray = array();
	$employeeCodeArray =array();
	$inconsistenciesArray = array();
	
	while(isset($data->boundsheets[$m]['name'])) {
		$sheetNameArray[] =  $data->boundsheets[$m]['name'];
		$m++;
	}
	
	$appraisalMasterArray=array();
	$lc=0;
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()){
		foreach($sheetNameArray as $sheetIndex=>$value) {
			
			for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
				if($data->sheets[$sheetIndex]['cells'][1][1] != "[Sr.No.]" && $data->sheets[$sheetIndex]['cells'][1][2] != "[employeeCode]" && $data->sheets[$sheetIndex]['cells'][1][3] != "[scoreGained]" && $data->sheets[$sheetIndex]['cells'][1][4] != "[dutiesWeekend]"  && $data->sheets[$sheetIndex]['cells'][1][5] != "[extSupreintendent]" && $data->sheets[$sheetIndex]['cells'][1][6] != "[copyChecked]" &&
				$data->sheets[$sheetIndex]['cells'][1][7] != "[dutiesExternal]" && $data->sheets[$sheetIndex]['cells'][1][8] != "[dutiesInternal]") {
					$inconsistenciesArray[] = "Data has not been entered in given format";
					continue;
				}
			}
			if(count($inconsistenciesArray)!=0){
				break;
			}
			
			$sr=0;
			for($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
				$sr++;
				$srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
				$srNo = trim($srNo);
				if(empty($srNo)) {
						break;
				}
				$employeeCode = $data->sheets[$sheetIndex]['cells'][$i][2];
				$scoreGained = $data->sheets[$sheetIndex]['cells'][$i][3];
				$dutiesWeekend = $data->sheets[$sheetIndex]['cells'][$i][4];
				$extSupreintendent = $data->sheets[$sheetIndex]['cells'][$i][5];
				$copyChecked = $data->sheets[$sheetIndex]['cells'][$i][6];
				$dutiesExternal = $data->sheets[$sheetIndex]['cells'][$i][7];
				$dutiesInternal = $data->sheets[$sheetIndex]['cells'][$i][8];
				$employeeCode = preg_replace('/\s+/','',$employeeCode);
				if(!in_array($employeeCode,$employeeCodeArray)) {
					$employeeCodeArray[] = $employeeCode;
				}
				else{
					$inconsistenciesArray[] = "Duplicate Employee Code at Sr.No. $sr";
					continue;
				}


				if($employeeCode==''){
					$inconsistenciesArray[] = "Employee Code can not be blank at Sr No $sr";
					continue;
				}
				if(!is_numeric($scoreGained)) {
					$inconsistenciesArray[] = "Score gained Should be Numeric at Sr No $sr";
					continue;
					
				}

				if($scoreGained==''){
					$inconsistenciesArray[] = "Score gained can not be blank at Sr No $sr";
					continue;
				}
				
				if(!is_numeric($dutiesWeekend)){
					$inconsistenciesArray[] = "Duties Weekend Should be Numeric at Sr No $sr";
					continue;
				}
				if($dutiesWeekend==''){
					$inconsistenciesArray[] = "Duties Weekend can not be blank at Sr No $sr";
					continue;
				}

				if(!is_numeric($extSupreintendent)) {
					$inconsistenciesArray[] = "External Supreintendent Should be Numeric at Sr No $sr";
					continue;
				}
				
				if($extSupreintendent==''){
					$inconsistenciesArray[] = "External Supreintendent can not be blank at Sr No $sr";
					continue;
				}
				if(!is_numeric($copyChecked)){
					$inconsistenciesArray[] = "Copy Checked Should be Numeric at Sr No $sr";
					continue;
				}

				if($copyChecked==''){
					$inconsistenciesArray[] = "Copy Checked can not be blank at Sr No $sr";
					continue;
				}
				
				if(!is_numeric($dutiesExternal)){
					$inconsistenciesArray[] = "Duties External Should be Numeric at Sr No $sr";
					continue;
				}

				if($dutiesExternal==''){
					$inconsistenciesArray[] = "Duties External can not be blank at Sr No $sr";
					continue;
				}
				if(!is_numeric($dutiesInternal)) {
					$inconsistenciesArray[] = "Duties Internal Should be Numeric at Sr No $sr";
					continue;
				}
				if($dutiesInternal==''){
					$inconsistenciesArray[] = "Duties Internal can not be blank at Sr No $sr";
					continue;
				}

				$employeeTobeAppraisedArray = $appDataManager->getEmployee($employeeCode);
				$employeeId = $employeeTobeAppraisedArray[0]['employeeId'];
				if ($employeeId =='') {
					$inconsistenciesArray[] = "Invalid Employee Code at SrNo $sr";
					continue;
				}
				
				$foundArray =  $appDataManager->getCompatibilityData($employeeId,$sessionId);
				if($foundArray[0]['cnt']>0){
					$ret=$appDataManager->updateAppraisalCompatibilityData($employeeId,$scoreGained,$dutiesWeekend,$extSupreintendent,
																	 $copyChecked,$dutiesExternal,$dutiesInternal);
				}
				else{
					$ret = $appDataManager->insertAppraisalCompatibilityData($employeeId,$scoreGained,$dutiesWeekend,$extSupreintendent,
																	 $copyChecked,$dutiesExternal,$dutiesInternal);
				}
				if($ret==false){
					$inconsistenciesArray[] = "Error while saving student detail";
					continue;
				}
				$lc++;
			}
		}
		if(count($inconsistenciesArray) == 0){
			if(SystemDatabaseManager::getInstance()->commitTransaction()) {
				$successArray[] = "Data saved successfully for $lc employees(s)";
				$csvData = '';
				$i = 1;
				foreach($successArray as $key=>$record) {
					$csvData .= "$i. $record\r\n";
					$i++;
				}
				$csvData = trim($csvData);
				$fileName = "Upload Appraisal Information.txt";
			}
		}
		else{
				$csvData = '';
				$i = 1;

				foreach($inconsistenciesArray as $key=>$record) {
					$csvData .= "$i $record\r\n";
					$i++;
			}
		}
		$csvData = trim($csvData);
		$fileName = "Inconsistencies_Appraisal_Information.txt";
		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		header("Pragma: hack"); // WTF? oh well, it works...
		header("Content-Type: application/octet-stream");
		header("Content-Length: " .strlen($csvData));
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header("Content-Transfer-Encoding: text\n");
		echo $csvData;
		die;
	}
}
else{
	echo '<script language="javascript">alert("Data could not be saved successfully");</script>';
	die;
}


?>