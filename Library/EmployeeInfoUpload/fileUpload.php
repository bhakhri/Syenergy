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

define('MODULE','UploadEmployeeDetail');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
global $sessionHandler;
//alert(1);
$fileObj = FileUploadManager::getInstance('employeeInfoUploadFile');
$filename = $fileObj->tmp;
$filename2 = $_FILES['employeeInfoUploadFile']['name'];
if ($filename == '' and $filename2=='') {
	echo ('<script type="text/javascript">alert("Please Select a File to Upload");</script>');
	die;
}
else if($filename == '' and $filename2!=''){
    echo ('<script type="text/javascript">alert("Size of selected file is more than allowed size of '.round(MAXIMUM_FILE_SIZE/(1024*1024),2).' MB");</script>');
    die; 
}


if ($fileObj->fileExtension != 'xls') {
	/*$inconsistenciesArray[] = "Incorrect file format. Please read Notes.";
	$csvData = '';
	$i = 1;
	foreach($inconsistenciesArray as $key=>$record) {
		$csvData .= "$i $record\r\n";
		$i++;
	}
	$csvData = trim($csvData);
	$fileName = "EmployeeInfo_Inconsistencies.txt";
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header("Pragma: hack"); // WTF? oh well, it works...
	header("Content-Type: application/octet-stream");
	header("Content-Length: " .strlen($csvData));
	header('Content-Disposition: attachment; filename="'.$fileName.'"');
	header("Content-Transfer-Encoding: text\n");
	echo $csvData;*/
	echo ('<script type="text/javascript">alert("Please Select Excel File");</script>');
	die;
}

require_once(MODEL_PATH . "/EmployeeManager.inc.php");

$employeeManager = EmployeeManager::getInstance();

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
 
if(SystemDatabaseManager::getInstance()->startTransaction()) {
	foreach($sheetNameArray as $sheetIndex=>$value) {
	  
		for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {

			if ($data->sheets[$sheetIndex]['cells'][1][1] != "Sr.No.") {
				$inconsistenciesArray[] = "Data has not entered in given format";
				continue;
			}
		}

		
		$employeeCodeArray = array();
		$employeeAbbreviationArray = array();
		$employeePanNoArray = array();
		$employeeIdArray = array();

		for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
			//echo($data->sheets[$sheetIndex]['numRows']);
			//die('line'.__LINE__);
			$srNo = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][1]));
			$employeeId = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][2]));
			$employeeCode = trim(replaceChar(strtolower($data->sheets[$sheetIndex]['cells'][$i][8])));
			$employeeAbbreviation = trim(replaceChar(strtolower($data->sheets[$sheetIndex]['cells'][$i][9])));
			$panNo = trim(replaceChar(strtolower($data->sheets[$sheetIndex]['cells'][$i][14])));
			$status = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][41]));
			$status = addslashes(strtolower($status));
			if ($status == 'yes') {
				$employeeIdArray[] = $employeeId;
				$employeeCodeArray[] = $employeeCode;
				$employeeAbbreviationArray[] = $employeeAbbreviation;
				$employeePanNoArray[] = $panNo;
				
				if($employeeId != '') {
					if(count($employeeIdArray)!=count(array_unique($employeeIdArray))) {
						$inconsistenciesArray[] = "Duplicate EmployeeId at Sr. No.'$srNo'";
						continue;
					}
				}
				
				if($employeeCode != '') {
					if(count($employeeCodeArray)!=count(array_unique($employeeCodeArray))) {
						$inconsistenciesArray[] = "Duplicate Employee Code at Sr. No.'$srNo'";
						continue;
					}
				}

				if($employeeAbbreviation != '') {
					if(count($employeeAbbreviationArray)!=count(array_unique($employeeAbbreviationArray))) {
						$inconsistenciesArray[] = "Duplicate Employee Abbr. at Sr. No.'$srNo'";
						continue;
					}
				}

				if($panNo != '') {
					if(count($employeePanNoArray)!=count(array_unique($employeePanNoArray))) {
						$inconsistenciesArray[] = "Duplicate Pan No. at Sr. No.'$srNo'";
						continue;
					}
				}
			}
		}
	
		for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
			//echo($data->sheets[$sheetIndex]['numRows']);
			//die('line'.__LINE__);
			$srNo = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][1]));
			if (empty($srNo)) {
				break;
			}

			$empId = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][2]));
			$userName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][3]));
			$title = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][4]));
			$lastName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][5]));
			$employeeName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][6]));
			$middleName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][7]));
			$employeeCode = trim($data->sheets[$sheetIndex]['cells'][$i][8]);
			$lastChar = ord(substr($employeeCode,-1));
			if ($lastChar == 160) {
				$employeeCode = substr($employeeCode,0,-1);
			}
			//$employeeCode = ltrim($employeeCode);
			$employeeAbbreviation = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][9]));
			$isTeaching = trim(replaceChar(strtolower($data->sheets[$sheetIndex]['cells'][$i][10])));
			$designationName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][11]));
			$gender = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][12]));
			$departmentName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][13]));
			$panNo = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][14]));
			$religion = addslashes(trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][15])));
			$caste = addslashes(trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][16])));
			$pfNo = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][17]));
			$bankName = addslashes(trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][18])));
			$bankAccountNo = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][19]));
			$bankBranchName = addslashes(trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][20])));
			$ESINo = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][21]));
			$branchCode = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][22]));
			//$qualification = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][12]));
			$role = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][23]));
			$isMarried = trim(replaceChar(strtolower($data->sheets[$sheetIndex]['cells'][$i][24])));
			$spouseName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][25]));
			$fatherName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][26]));
			$motherName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][27]));
			$contactNumber = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][28]));
			$emailAddress = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][29]));
			$mobileNumber = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][30]));
			$address1 = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][31]));
			$address2 = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][32]));
			$cityName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][33]));
			$stateName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][34]));
			$countryName = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][35]));
			$pinCode = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][36]));
			$dateOfBirth = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][37]));
			$dateOfMarriage = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][38]));
			$dateOfJoining = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][39]));
			$dateOfLeaving = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][40]));
			$bloodGroup = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][41]));
			$status = trim(replaceChar($data->sheets[$sheetIndex]['cells'][$i][42]));
			$status = addslashes(strtolower($status));
			
			if ($status == 'yes') {
				$totalEmployees++;
				if (empty($srNo)) {
					$inconsistenciesArray[] = "Please mention Sr. No. for row with code $employeeCode and abbr. $employeeAbbreviation";
					continue;
				}
				if (empty($title)) {
					$inconsistenciesArray[] = "Please mention Title of an Employee at Sr. No. $srNo";
					continue;
				}
				if (empty($employeeName)) {
					$inconsistenciesArray[] = "Please mention Name of an Employee at Sr. No. $srNo";
					continue;
				}
				if (empty($employeeCode)) {
					$inconsistenciesArray[] = "Please mention Employee Code of an Employee at Sr. No. $srNo";
					continue;
				}
				if (empty($employeeAbbreviation)) {
					$inconsistenciesArray[] = "Please mention Employee Abbr. of an Employee at Sr. No. $srNo";
					continue;
				}
				if (empty($designationName)) {
					$inconsistenciesArray[] = "Please mention Designation of an Employee at Sr. No. $srNo";
					continue;
				}
				if (empty($branchCode)) {
					$inconsistenciesArray[] = "Please mention Branch of an Employee at Sr. No. $srNo";
					continue;
				}
				/*if (empty($departmentName)) {
					$inconsistenciesArray[] = "Please mention Department of an Employee at Sr. No. $srNo";
					continue;
				}*/
				if (empty($isTeaching)) {
					$inconsistenciesArray[] = "Please mention Teaching Status of an Employee at Sr. No. $srNo";
					continue;
				}
				if (empty($isMarried)) {
					$inconsistenciesArray[] = "Please mention Marital Status of an Employee at Sr. No. $srNo";
					continue;
				}

				if (empty($gender)) {
					$inconsistenciesArray[] = "Please mention Gender of an Employee at Sr. No. $srNo";
					continue;
				}
				if(empty($userName)) {                    
					if (!empty($role)) {
						$inconsistenciesArray[] = "Please mention User Name of an Employee at Sr. No. $srNo";
						continue;
					}
				}
				if(empty($role)) {                    
					if (!empty($userName)) {
						$inconsistenciesArray[] = "Please mention Role of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($empId != '') {
					 $conditions = "WHERE LCASE(emp.employeeId) = '".trim(addslashes(strtolower($empId)))."'";
					 $codeArray = $employeeManager->getEmpCode($conditions);
					if (trim($codeArray[0]['employeeId'])=='') {
						 $inconsistenciesArray[] = "Invalid Employee Id at Sr. No.'$srNo'";
						 continue;
					 }
				}
				
				if($empId != '') {
					if (ord(substr($employeeCode,0,1)) == 160) {
						$employeeCode = substr($employeeCode,1);
					}
					$employeeCode = trim($employeeCode);
					$conditions = "AND emp.employeeCode LIKE '%".$employeeCode."%'";
					$studentArray = $employeeManager->getEmployeeInfo($conditions);
					$employeeId = $studentArray[0]['employeeId']; 
					$userId = $studentArray[0]['userId'];
					$roleId = $studentArray[0]['roleId'];
					if($employeeId == '') {
						$inconsistenciesArray[] = "Invalid Employee Id at Sr. No.'$srNo'";
						continue;
					}
				}
				else {
					$employeeId = '';
					$userId = '';
				}
				if($title != '') {
					global $titleResults;
					foreach($titleResults as $key=>$value) {
						if(strtolower($value) == strtolower($title)) {
							$title =$key;
						}
					}
					if($title != 1 AND $title != 2 AND $title != 3 AND $title != 4) {
						$inconsistenciesArray[] = "Title of an Employee would be 'Mr, Mrs, Miss or Dr.' at Sr. No. $srNo and please read the instructions";
						continue;
					}
				}
		
				if($lastName != '') {
					if(is_numeric($lastName)) {
						$inconsistenciesArray[] = "Please enter valid Last Name of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($employeeName != '') {
					if(is_numeric($employeeName)) {
						$inconsistenciesArray[] = "Please enter valid First Name of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($middleName != '') {
					if(is_numeric($middleName)) {
						 $inconsistenciesArray[] = "Please enter valid Middle Name of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($stateName != '') {
					if(empty($countryName)){
						$inconsistenciesArray[] = "Please mention Country of Employee at Sr. No. $srNo";
						continue;
						}
				}
				
				if($panNo != '') {
					$panNoCount = strlen($panNo);
					if($panNoCount > 20) {
						$inconsistenciesArray[] = "The length of Pan No. should not more than 20 Sr. No. $srNo";
						continue;
					}
				}

				if($religion != '') {
					$religionCount = strlen($religion);
					if($religionCount > 20) {
						$inconsistenciesArray[] = "The length of Religion should not more than 20 Sr. No. $srNo";
						continue;
					}
				}

				if($caste != '') {
					$casteCount = strlen($caste);
					if($casteCount > 20) {
						$inconsistenciesArray[] = "The length of Caste should not more than 20 Sr. No. $srNo";
						continue;
					}
				}

				if($pfNo != '') {
					$pfNoCount = strlen($pfNo);
					if($pfNoCount > 20) {
						$inconsistenciesArray[] = "The length of PF No. should not more than 20 Sr. No. $srNo";
						continue;
					}
				}

				if($bankName != '') {
					$bankNameCount = strlen($bankName);
					if($bankNameCount > 50) {
						$inconsistenciesArray[] = "The length of Bank Name should not more than 50 Sr. No. $srNo";
						continue;
					}
				}

				if($bankAccountNo != '') {
					$bankAccountNoCount = strlen($bankAccountNo);
					if($bankAccountNoCount > 20) {
						$inconsistenciesArray[] = "The length of Bank Account No. should not more than 20 Sr. No. $srNo";
						continue;
					}
				}

				if($bankBranchName != '') {
					$bankBranchNameCount = strlen($bankBranchName);
					if($bankBranchNameCount > 20) {
						$inconsistenciesArray[] = "The length of Bank Branch Name should not more than 20 Sr. No. $srNo";
						continue;
					}
				}

				if($religion != '') {
					if(is_numeric($religion)) {
						 $inconsistenciesArray[] = "Please enter valid Religion of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($spouseName != '') {
					if(is_numeric($spouseName)) {
						 $inconsistenciesArray[] = "Please enter valid Spouse Name of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($fatherName != '') {
					if(is_numeric($fatherName)) {
						 $inconsistenciesArray[] = "Please enter valid Father Name of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($motherName != '') {
					if(is_numeric($motherName)) {
						 $inconsistenciesArray[] = "Please enter valid Mother Name of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($bloodGroup != '') {
					if(is_numeric($bloodGroup)) {
						 $inconsistenciesArray[] = "Please enter valid Blood Group of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($bloodGroup != '') {
					global $bloodResults;
					foreach($bloodResults as $key=>$value) {
						if(strtolower($value) == strtolower($bloodGroup)) {
							$bloodGroup = $key;
						}
					}
					if($bloodGroup != 1 AND $bloodGroup != 2 AND $bloodGroup != 3 AND $bloodGroup != 4 AND $bloodGroup != 5 AND $bloodGroup != 6 AND $bloodGroup != 7 AND $bloodGroup != 8 AND $bloodGroup != 9 AND $bloodGroup != 10 AND $bloodGroup != 11 AND $bloodGroup != 12 AND $bloodGroup != 13 AND $bloodGroup != 14 AND $bloodGroup != 15 AND $bloodGroup != 16 AND $bloodGroup != 17 AND $bloodGroup != 18 AND $bloodGroup != 19 AND $bloodGroup != 20 ) {
						$inconsistenciesArray[] = "Invalid Blood Group of an Employee at Sr. No. $srNo";
						continue;
					}
				}

				if($ESINo != '') {
					$ESINoCount = strlen($ESINo);
					if($bankBranchNameCount > 30) {
						$inconsistenciesArray[] = "The length of ESI No. should not more than 30 Sr. No. $srNo";
						continue;
					}
				}
				if($cityName != '') {
					if(empty($stateName)){
						$inconsistenciesArray[] = "Please mention State of Employee at Sr. No. $srNo";
						continue;
					}
				}
				if ($countryName != '') {
					 $conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($countryName))."'";
					 $countryArray = $employeeManager->getCountry($conditions);
					 $countryId = $countryArray[0]['countryId'];
					 if (empty($countryId)) {
						 $inconsistenciesArray[] = "Country entered at Sr. No.'$srNo' does not exist.Kindly recheck Country Master";
						 continue;
					 }
				}
				else {
					 $countryId = $countryName;
				}

			   // echo $countryId;
			   // die;
			
				if ($stateName != '' && $countryName != '') {
					$conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($stateName))."' AND st.countryId = ".$countryId;
					$stateArray = $employeeManager->getState($conditions);
					$stateId = $stateArray[0]['stateId'];
					if (empty($stateId)) {
						$inconsistenciesArray[] = "State entered at Sr. No.'$srNo' does not exist.Kindly recheck State Master";
						continue;
					}
				}
				else {
					$stateId = $stateName;
				}


				if ($cityName != '' && $stateName != '') {
					$conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($cityName))."' AND ct.stateId = ".$stateId;
					$cityArray = $employeeManager->getCity($conditions);
					$cityId = $cityArray[0]['cityId'];
					if (empty($cityId)) {
						$inconsistenciesArray[] = "City entered at Sr. No.'$srNo' does not exist.Kindly recheck City Master";
						continue;
					}
				}
				else {
					$cityId = $cityName;
				}
				if ($designationName != '') {
					 $conditions = "WHERE LCASE(desg.designationName) = '".addslashes(strtolower($designationName))."'";
					 $designationArray = $employeeManager->getDesignation($conditions);
					 $designationId = $designationArray[0]['designationId'];
					 if (empty($designationId)) {
						 $inconsistenciesArray[] = "Designation entered at Sr. No.'$srNo' does not exist.Kindly recheck Designation Master";
						 continue;
					 }
				}
				else {
					 $designationId = $designationName;
				}
				 if ($departmentName != '') {
					 $conditions = "WHERE LCASE(dept.departmentName) = '".addslashes(strtolower($departmentName))."'";
					 $departmentArray = $employeeManager->getDepartment($conditions);
					 $departmentId = $departmentArray[0]['departmentId'];
					 if (empty($departmentId)) {
						 $inconsistenciesArray[] = "Department entered at Sr. No.'$srNo' does not exists.Kindly recheck Department Master";
						 continue;
					 }
				}
				else {
					 $departmentId = $departmentName;
				}
				if ($branchCode != '') {
					 $conditions = "WHERE LCASE(br.branchCode) = '".addslashes(strtolower($branchCode))."'";
					 $branchArray = $employeeManager->getBranch($conditions);
					 $branchId = $branchArray[0]['branchId'];
					 if (empty($branchId)) {
						 $inconsistenciesArray[] = "Branch entered at Sr. No.'$srNo' does not exists.Kindly recheck Branch Master";
						 continue;
					 }
				}
				else {
					 $branchId = $branchCode;
				}

				if ($panNo != '') {
					 $conditions = "WHERE LCASE(panNo) = '".addslashes(strtolower($panNo))."'";
					 $panArray = $employeeManager->getPanNo($conditions);
					 $employeePanNo = $panArray[0]['panNo'];
					 if (!empty($employeePanNo)) {
						 $inconsistenciesArray[] = "Pan No. already exists at Sr. No.'$srNo'.";
						 continue;
					 }
				}
				
				if($userName != '') {
					if ($role != '') {
						 $conditions = "WHERE LCASE(r.roleName) = '".addslashes(strtolower($role))."'";
						 $roleArray = $employeeManager->getRole($conditions);
						 $roleId = $roleArray[0]['roleId'];
						 if (empty($roleId)) {
							 $inconsistenciesArray[] = "Role entered at Sr. No.'$srNo' does not exists.Kindly recheck Role Master";
							 continue;
						 }
					}
					else {
						 $roleId = $role;
					}
				}
				
				if ($employeeCode != '') {
					if($empId == '') {
						$conditions = "WHERE LCASE(emp.employeeCode) = '".trim(addslashes(strtolower($employeeCode)))."'";
					}
					else{
					 $conditions = "WHERE LCASE(emp.employeeCode) = '".trim(addslashes(strtolower($employeeCode)))."' AND emp.employeeId != ".$empId; 
					}
					 $codeArray = $employeeManager->getEmpCode($conditions);
					 if (trim($codeArray[0]['employeeCode'])!='') {
						 $inconsistenciesArray[] = "Employee Code entered already exists at Sr. No.'$srNo'";
						 continue;
					 }
				}
				
				if ($employeeAbbreviation != '') {
					if($empId == ''){
						$conditions = "WHERE LCASE(emp.employeeAbbreviation) = '".addslashes(strtolower($employeeAbbreviation))."'";
					}
					else{
					 $conditions = "WHERE LCASE(emp.employeeAbbreviation) = '".addslashes(strtolower($employeeAbbreviation))."' AND emp.employeeId != ".$empId; 
					}
					 $abbrArray = $employeeManager->getEmpCode($conditions);
					 if (trim($abbrArray[0]['employeeAbbreviation'])!='') {
						 $inconsistenciesArray[] = "Employee Abbreviation entered already exists at Sr. No.'$srNo'";
						 continue;
					 }
				}

				if($contactNumber != '') {
					if(!is_numeric($contactNumber)) {
						$inconsistenciesArray[] = "Please enter valid data for 'Contact No.' field at Sr. No.'$srNo'";
						 continue;
					}
				}

				if($mobileNumber != '') {
					if(!is_numeric($mobileNumber)) {
						$inconsistenciesArray[] = "Please enter valid data for 'Mobile No.' field at Sr. No.'$srNo'";
						continue;
					}
				}

				if($isTeaching != '') {
					if(strtolower($isTeaching) != 'yes' AND strtolower($isTeaching) != 'no') {
						$inconsistenciesArray[] = "Please enter valid data for 'Teaching Employee' field at Sr. No.'$srNo'";
						continue;
					}
				}

				if($isMarried != '') {
					if(strtolower($isMarried) != 'yes' AND strtolower($isMarried) != 'no') {
						$inconsistenciesArray[] = "Please enter valid data for 'Is Married' field at Sr. No.'$srNo'";
						continue;
					}
				}

				if($gender != '') {
					if(strtolower($gender) != 'm' AND strtolower($gender) != 'f') {
						$inconsistenciesArray[] = "Please enter valid data for 'Gender' field at Sr. No.'$srNo'";
						continue;
					}
				}
				
				if($isMarried != '') {
					if(strtolower($isMarried) == 'no') {
						if($spouseName != '') {
							$inconsistenciesArray[] = "Spouse Name will be blank at the time of Unmarried at Sr. No.'$srNo'";
							continue;
						}
					}
				}

				if($isMarried != '') {
					if(strtolower($isMarried) == 'no') {
						if($dateOfMarriage != '') {
							$inconsistenciesArray[] = "Date of Marriage will not be accepted at the time of Unmarried at Sr. No.'$srNo'";
							continue;
						}
					}
				}
				
				if($dateOfBirth != '') {
					$cnt = substr_count($dateOfBirth, '.');
					if($cnt == 0 ) {
						$inconsistenciesArray[] = "Date of Birth not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
						continue;
					}

					$dateOfBirthEmp = explode('.',$dateOfBirth);
					$birthYear = $dateOfBirthEmp[0];
					$birthMonth = $dateOfBirthEmp[1];
					$birthDate = $dateOfBirthEmp[2];

						$ii = date('Y')-70;
						if($birthYear < date('Y')-70) {
							$inconsistenciesArray[] = "Birth Year cannot less than $ii at Sr. No.'$srNo'";
							continue;
						}
						if($birthMonth > 12) {
							$inconsistenciesArray[] = "Invalid Birth Month at Sr. No.'$srNo'";
							continue;
						}
						
						$checkBirthDate = checkdate($birthMonth,$birthDate,$birthYear);
						if($checkBirthDate){
							$dateOfBirth = $birthYear.'-'.$birthMonth.'-'.$birthDate;
						}
						else {
							$inconsistenciesArray[] = "Invalid Birth Date at Sr. No.'$srNo'";
							continue;
						}
					
				}

				
				if($dateOfMarriage != '') {
					$cnt = substr_count($dateOfMarriage, '.');
					if($cnt == 0 ) {
						$inconsistenciesArray[] = "Date of Marriage not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
						continue;
					}
					$dateOfMarriageEmp = explode('.',$dateOfMarriage);
					$marriageYear = $dateOfMarriageEmp[0];
					$marriageMonth = $dateOfMarriageEmp[1];
					$marriageDate = $dateOfMarriageEmp[2];
						$ii = date('Y')-70;
						if($marriageYear < date('Y')-70) {
							$inconsistenciesArray[] = "Marriage Year cannot less than $ii at Sr. No.'$srNo'";
							continue;
						}
						if($marriageMonth > 12) {
							$inconsistenciesArray[] = "Invalid Marriage Month at Sr. No.'$srNo'";
							continue;
						}

						$checkMarriageDate = checkdate($marriageMonth,$marriageDate,$marriageYear);
						if($checkMarriageDate){
							$dateOfMarriage = $marriageYear.'-'.$marriageMonth.'-'.$marriageDate;
						}
						else {
							$inconsistenciesArray[] = "Invalid Marriage Date at Sr. No.'$srNo'";
							continue;
						}
				}

				if($dateOfJoining != '') {
					$cnt = substr_count($dateOfJoining, '.');
					if($cnt == 0 ) {
						$inconsistenciesArray[] = "Date of Joining not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
						continue;
					}
					$dateOfJoiningEmp = explode('.',$dateOfJoining);
					$joiningYear = $dateOfJoiningEmp[0];
					$joiningMonth = $dateOfJoiningEmp[1];
					$joiningDate = $dateOfJoiningEmp[2];
						$ii = date('Y')-50;
						if($joiningYear < date('Y')-50) {
							$inconsistenciesArray[] = "Joining Year cannot less than $ii at Sr. No.'$srNo'";
							continue;
						}

						if($joiningMonth > 12) {
							$inconsistenciesArray[] = "Invalid Joining Month at Sr. No.'$srNo'";
							continue;
						}

						$checkJoiningDate = checkdate($joiningMonth,$joiningDate,$joiningYear);
						if($checkJoiningDate){
							$dateOfJoining = $joiningYear.'-'.$joiningMonth.'-'.$joiningDate;
						}
						else {
							$inconsistenciesArray[] = "Invalid Joining Date at Sr. No.'$srNo'";
							continue;
						}

				}

				if($dateOfLeaving != '') {
					$cnt = substr_count($dateOfLeaving, '.');
					if($cnt == 0 ) {
						$inconsistenciesArray[] = "Date of Leaving not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
						continue;
					}
					$dateOfLeavingEmp = explode('.',$dateOfLeaving);
					$leavingYear = $dateOfLeavingEmp[0];
					$leavingMonth = $dateOfLeavingEmp[1];
					$leavingDate = $dateOfLeavingEmp[2];
						$ii = date('Y')-50;
						if($leavingYear < date('Y')-50) {
							$inconsistenciesArray[] = "Leaving Year cannot less than $ii at Sr. No.'$srNo'";
							continue;
						}
						if($leavingMonth > 12) {
							$inconsistenciesArray[] = "Invalid Leaving Month at Sr. No.'$srNo'";
							continue;
						}
						
						$checkJoiningDate = checkdate($leavingMonth,$leavingDate,$leavingYear);
						if($checkJoiningDate){
							$dateOfLeaving = $leavingYear.'-'.$leavingMonth.'-'.$leavingDate;
						}
						else {
							$inconsistenciesArray[] = "Invalid Leaving Date at Sr. No.'$srNo'";
							continue;
						}
					
				}
				

				if($emailAddress != '') {
					require_once(BL_PATH . "/HtmlFunctions.inc.php");
					$htmlFunctions = HtmlFunctions::getInstance();
					$getEmail = $htmlFunctions->isEmail($emailAddress);
					if($getEmail == 0) {
						$inconsistenciesArray[] = "Email is not valid at Sr. No.'$srNo'";
						 continue;
					}
				}

				$isTeaching == 'yes'? $isTeaching=1 : $isTeaching= 0;
				$isMarried == 'yes'? $isMarried = 1 : $isMarried = 0;
				 
				
				$checkCondition = "WHERE employeeId = ".$employeeId;
				
				if($employeeId == '' AND $userId == '') {
					if ($userName != '' AND $role != '') {
						$checkUserName = $employeeManager->checkUserName($userName,$condition);
						if($checkUserName[0]['userName'] == '') {
							$pass = $userName;
								$return1 = $employeeManager->insertUserData($roleId,$userName,md5($pass));
									if ($return1 == true){
										$userId=SystemDatabaseManager::getInstance()->lastInsertId();    
											$returnEmployeeInfo = $employeeManager->addEmployeeInfoInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$userId,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup);
											
											if ($returnEmployeeInfo == false) {
												$inconsistenciesArray[] = "Error while saving employee Info";
											}

											if ($returnEmployeeInfo == true) {
												$employeeId=SystemDatabaseManager::getInstance()->lastInsertId();
												$insertEmployeeCanTeachIn  = $employeeManager->addUploadEmployeeCanTeachIn($employeeId);
											}

											if ($insertEmployeeCanTeachIn == false) {
												$inconsistenciesArray[] = "Error while saving employee Info";
											}

											$returnUserRoleInfo = $employeeManager->addUploadUserRole($userId,$roleId);
											if ($returnUserRoleInfo == false) {
												$inconsistenciesArray[] = "Error while saving employee Info";
											}
									}
							}
							else {
								$inconsistenciesArray[] = "User Name is already existing at Sr. No.'$srNo'";
							}
						}
						else {
							$insertWithoutUser = $employeeManager->addEmployeeWithoutUserInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup);

							if ($insertWithoutUser == false) {
								$inconsistenciesArray[] = "Error while saving employee Info";
							}
							
							if ($insertWithoutUser == true) {
								$employeeId=SystemDatabaseManager::getInstance()->lastInsertId();
								$insertEmployeeCanTeachIn  = $employeeManager->addUploadEmployeeCanTeachIn($employeeId);
								//print_r($insertEmployeeCanTeachIn);

							}
							
							if ($insertEmployeeCanTeachIn == false) {
								$inconsistenciesArray[] = "Error while saving employee Info";
							}
						
						}
				}
				else if ($employeeId != '' AND $userId == 0 AND $roleId == '') {
					$returnUpdateEmployeeInfo = $employeeManager->updateEmployeeInfoInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup,$checkCondition);
						if ($returnUpdateEmployeeInfo == false) {
							$inconsistenciesArray[] = "Error while saving employee Info";
						}
				}
				else if ($employeeId != '' AND $userId != 0 AND $roleId != '') {
					 $returnUpdateWithoutUserEmployeeInfo = $employeeManager->updateEmployeeWithUserInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup,$checkCondition);
						if ($returnUpdateWithoutUserEmployeeInfo == false) {
							$inconsistenciesArray[] = "Error while saving employee Info";
						}
					 if ($userName != '') {
						$condition = "AND userId != $userId";
						$checkUserName = $employeeManager->checkUserName($userName,$condition);
						if($checkUserName[0]['userName'] == '') {
							 $returnUpdateWithUserEmployeeInfo = $employeeManager->updateUserInTransaction($userName,$userId,$roleId);
								if ($returnUpdateWithUserEmployeeInfo == false) {
									$inconsistenciesArray[] = "Error while saving employee Info";
								}
						}
						else {
							$inconsistenciesArray[] = "User Name is already existing at Sr. No.'$srNo'";
						}
					 }
				  }

				  else if ($employeeId != '' AND $userId == 0 AND $roleId != '') {
					  if ($userName != '' AND $role != '') {
						  $condition = "AND userId != $userId";
						  $checkUserName = $employeeManager->checkUserName($userName,$condition);
							if($checkUserName[0]['userName'] == '') {
								$pass = $userName;
								$return2 = $employeeManager->insertUserData($roleId,$userName,md5($pass));
								$userId=SystemDatabaseManager::getInstance()->lastInsertId();
									if ($return2 == true){
										$returnUpdateWithUserNameEmployeeInfo = $employeeManager->updateEmployeeWithUserNameInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup,$userId,$checkCondition);
										if ($returnUpdateWithUserNameEmployeeInfo == false) {
											$inconsistenciesArray[] = "Error while saving employee Info";
										}
									$returnUserRoleInfo = $employeeManager->addUploadUserRole($userId,$roleId);
									if ($returnUserRoleInfo == false) {
										$inconsistenciesArray[] = "Error while saving employee Info";
									}

								 }
							}
							else {
								$inconsistenciesArray[] = "User Name is already existing at Sr. No.'$srNo'";
							}
						}
				   }
			    }
		}
	}
}
else {
	echo FAILURE;
}

/*echo '<pre>';
print_r($inconsistenciesArray);
die('line'.__LINE__);*/

if (count($inconsistenciesArray) == 0) {
	
if(SystemDatabaseManager::getInstance()->commitTransaction()) {
	$successArray[] = "Data saved successfully for $totalEmployees employees.";
	$csvData = '';
	$i = 1;
	foreach($successArray as $key=>$record) {
		$csvData .= "$i. $record\r\n";
		$i++;
	}
	$csvData = trim($csvData);
	$fileName = "Upload Employee Info.txt";
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
else {
$csvData = '';
$i = 1;
foreach($inconsistenciesArray as $key=>$record) {
	$csvData .= "$i $record\r\n";
	$i++;
}
$csvData = trim($csvData);
$fileName = "Upload Employee Info.txt";
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

//$History: fileUpload.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 11/26/09   Time: 1:04p
//Created in $/LeapCC/Library/EmployeeInfoUpload
//added files related to 'employee export/import' module
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/04/09   Time: 11:48a
//Created in $/LeapCC/Library/StudentRollNoUpload
//new files for student roll no. uploading
//
?>
