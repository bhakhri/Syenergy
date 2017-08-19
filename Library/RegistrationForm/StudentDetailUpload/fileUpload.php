<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student Detail
//
// Author : Jaineesh
// Created on : (14.11.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . "/FileUploadManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	define('MODULE','UploadStudentDetail');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	global $sessionHandler;

    $fileObj = FileUploadManager::getInstance('studentDetailUploadFile');
	$filename = $fileObj->tmp;

	if ($filename == '') {
		echo ('<script type="text/javascript">alert("Please Select File");</script>');
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
		$fileName = "StudentDetail_Inconsistencies.txt";
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

	require_once(MODEL_PATH . "/ScStudentManager.inc.php");
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
	$successArray = array();
	$insertQueryArray = array();

	$roomNameBlockIdArray = array();
	$roomAbbrBlockIdArray = array();

	$totalClassStudents = 0;

	$getClassId = $REQUEST_DATA['degree'];

	if ($getClassId == '') {
		echo ('<script type="text/javascript">alert("Please select class");</script>');
		die;
	}

	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		foreach($sheetNameArray as $sheetIndex=>$value) {


			for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
				if ($data->sheets[$sheetIndex]['cells'][1][1] != "[Sr.No.]") {
					$inconsistenciesArray[] = "Data has not entered in given format";
					continue;
				}
			}
			$rollNoArray = array();
			$univRollNoArray = array();
				//echo($data->sheets[$sheetIndex]['numRows']);
			for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {

				//for ($j = 1; $j <= $data->sheets[$sheetIndex]['numCols']; $j++) {
					//echo($data->sheets[$sheetIndex]['numRows']);
					//echo ($data->sheets[$sheetIndex]['numCols']);
					//die('line'.__LINE__);
				//if ($data->sheets[$sheetIndex]['cells'][$i][3] != "") {

					//echo($data->sheets[$sheetIndex]['cells'][$i][4]);
					//die('line'.__LINE__);

				 $srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
				 $rollNo = $data->sheets[$sheetIndex]['cells'][$i][2];
				 $univRollNo = $data->sheets[$sheetIndex]['cells'][$i][3];
				 $rollNoArray[] = $rollNo;
				 $univRollNoArray[] = $univRollNo;
				//}
				if($rollNo != '') {
					if(count($rollNoArray)!=count(array_unique($rollNoArray))){
						$inconsistenciesArray[] = "Duplicate Roll No for selected class at Sr. No.'$srNo'";
						continue;
					}
				}

				if($univRollNo != '') {
					if(count($univRollNoArray)!=count(array_unique($univRollNoArray))){
						$inconsistenciesArray[] = "Duplicate University Roll No for selected class at Sr. No.'$srNo'";
						continue;
					}
				}
			}



			for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
				//if ($data->sheets[$sheetIndex]['cells'][$i][3] != "") {
					//die('here');

				$srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
				$srNo = trim($srNo);
				if (empty($srNo)) {
					break;
				}
				$rollNo = $data->sheets[$sheetIndex]['cells'][$i][2];
				$univRollNo = $data->sheets[$sheetIndex]['cells'][$i][3];
				$isLeet = $data->sheets[$sheetIndex]['cells'][$i][4];
				$firstName = trim($data->sheets[$sheetIndex]['cells'][$i][5]);
				$lastName = trim($data->sheets[$sheetIndex]['cells'][$i][6]);
				$fatherName = trim($data->sheets[$sheetIndex]['cells'][$i][7]);
				$fatherOccupation = trim($data->sheets[$sheetIndex]['cells'][$i][8]);
				$fatherMobile = trim($data->sheets[$sheetIndex]['cells'][$i][9]);
				$fatherAddress1 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][10]));
				$fatherAddress2 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][11]));
				$fatherCountry = trim($data->sheets[$sheetIndex]['cells'][$i][12]);
				$fatherState = trim($data->sheets[$sheetIndex]['cells'][$i][13]);
				$fatherCity = trim($data->sheets[$sheetIndex]['cells'][$i][14]);
				$motherName = trim($data->sheets[$sheetIndex]['cells'][$i][15]);
				$dateOfBirth = trim($data->sheets[$sheetIndex]['cells'][$i][16]);
				$corrAddress1 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][17]));
				$corrAddress2 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][18]));
				$corrPinCode = trim($data->sheets[$sheetIndex]['cells'][$i][19]);
				$corrCountry = trim($data->sheets[$sheetIndex]['cells'][$i][20]);
				$corrState = trim($data->sheets[$sheetIndex]['cells'][$i][21]);
				$corrCity = trim($data->sheets[$sheetIndex]['cells'][$i][22]);
				$permAddress1 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][23]));
				$permAddress2 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][24]));
				$permPinCode = trim($data->sheets[$sheetIndex]['cells'][$i][25]);
				$permCountry = trim($data->sheets[$sheetIndex]['cells'][$i][26]);
				$permState = trim($data->sheets[$sheetIndex]['cells'][$i][27]);
				$permCity = trim($data->sheets[$sheetIndex]['cells'][$i][28]);
				$studentMobile = trim($data->sheets[$sheetIndex]['cells'][$i][29]);
				$domicile = trim($data->sheets[$sheetIndex]['cells'][$i][30]);
				$hostelFacility = trim($data->sheets[$sheetIndex]['cells'][$i][31]);
				$busFacility= trim($data->sheets[$sheetIndex]['cells'][$i][32]);
				$correspondencePhone = trim($data->sheets[$sheetIndex]['cells'][$i][33]);
				$permanentPhone = trim($data->sheets[$sheetIndex]['cells'][$i][34]);
				$studentStatus = trim($data->sheets[$sheetIndex]['cells'][$i][35]);
				$gender = trim($data->sheets[$sheetIndex]['cells'][$i][36]);
				$nationality = trim($data->sheets[$sheetIndex]['cells'][$i][37]);
				$quota = trim($data->sheets[$sheetIndex]['cells'][$i][38]);
				$contactNo = trim($data->sheets[$sheetIndex]['cells'][$i][39]);
				$emailAddress = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][40]));
				//$alternateEmailAddress = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][41]));
				$dateOfAdmission = trim($data->sheets[$sheetIndex]['cells'][$i][41]);
				$registrationNo = trim($data->sheets[$sheetIndex]['cells'][$i][42]);
				//$status = trim($data->sheets[$sheetIndex]['cells'][$i][42]);

                $totalClassStudents++;

				if($getClassId != '') {
					$conditions = "WHERE cl.classId = ".$getClassId;
					$classArray = $studentManager->getClassInfo($conditions);
					$className = $classArray[0]['className'];
				}

				if (empty($firstName)) {
					$inconsistenciesArray[] = "Please mention First Name of Student for selected class at Sr. No.'$srNo'";
					continue;
				}

				if (empty($fatherName)) {
					$inconsistenciesArray[] = "Please mention Father Name of Student for selected class at Sr. No.'$srNo'";
					continue;
				}

				/*if (empty($motherName)) {
					$inconsistenciesArray[] = "Please mention Mother Name of Student for selected class at Sr. No.'$srNo'";
					continue;
				}*/
                /*
				if (empty($dateOfBirth)) {
					$inconsistenciesArray[] = "Please mention Date of Birth of Student for selected class at Sr. No.'$srNo'";
					continue;
				}
                */

				if (empty($gender)) {
					$inconsistenciesArray[] = "Please mention Gender of Student for selected class at Sr. No.'$srNo'";
					continue;
				}

				if (empty($nationality)) {
					$inconsistenciesArray[] = "Please mention Nationality of Student for selected class at Sr. No.'$srNo'";
					continue;
				}

				if (empty($quota)) {
					$inconsistenciesArray[] = "Please mention Quota of Student for selected class at Sr. No.'$srNo'";
					continue;
				}

				if($dateOfBirth != '') {
					$cnt = substr_count($dateOfBirth, '.');
					if($cnt == 0 ) {
						$inconsistenciesArray[] = "Date of Birth not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
						continue;
					}

					$dateOfBirthDate = explode('.',$dateOfBirth);

					$dateOfBirthYear = $dateOfBirthDate[0];
					$dateOfBirthMonth = $dateOfBirthDate[1];
					$dateOfBirthDay = $dateOfBirthDate[2];

					//$checkBirthDate = checkdate($dateOfBirthMonth,$dateOfBirthDay,$dateOfBirthYear);
					//if($checkBirthDate){
						$dateOfBirth = $dateOfBirthYear.'-'.$dateOfBirthMonth.'-'.$dateOfBirthDay;
					//}
					//else {
					//	$inconsistenciesArray[] = "Invalid Birth Date at Sr. No.'$srNo'";
					//	continue;
					//}
				}

				if($dateOfAdmission != '') {
					$cnt = substr_count($dateOfAdmission, '.');
					if($cnt == 0 ) {
						$inconsistenciesArray[] = "Date of Admission not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
						continue;
					}

					$dateOfAdmissionDate = explode('.',$dateOfAdmission);

					$dateOfAdmissionYear = $dateOfAdmissionDate[0];
					$dateOfAdmissionMonth = $dateOfAdmissionDate[1];
					$dateOfAdmissionDay = $dateOfAdmissionDate[2];

					//$checkDateOfAdmissionDate = checkdate($dateOfAdmissionMonth,$dateOfAdmissionDay,$dateOfAdmissionYear);

					//if($checkDateOfAdmissionDate) {
						$dateOfAdmission = $dateOfAdmissionYear.'-'.$dateOfAdmissionMonth.'-'.$dateOfAdmissionDay;
					//}
					//else {
					//	$inconsistenciesArray[] = "Invalid Date of Admission at Sr. No.'$srNo'";
					//	continue;
					//}

				}


				if($firstName != '' && $fatherName != '') {

				//$conditions = "WHERE s.firstName LIKE '".addslashes(strtolower($firstName))."' AND s.classId = ".$getClassId." AND s.rollNo = '".$rollNo."'";
				$conditions = "WHERE firstName like '%".addslashes(strtolower($firstName))."%' AND fatherName like '%".addslashes(strtolower($fatherName))."%' AND dateOfBirth = '".addslashes($dateOfBirth)."'";
				$studentArray = $studentManager->getStudent($conditions);
				//print_r($studentArray);
				//die;
					/*if(count($studentArray) == 1 && is_array($studentArray)) {
						if ($rollNo != '') {
							$conditions = "WHERE rollNo = '".$rollNo."'";
							$studentRollArray = $studentManager->getStudentRollNo($conditions);
							$studentRollNo = $studentRollArray[0]['rollNo'];
							if ($studentRollNo == $rollNo) {
							$conditions = "WHERE rollNo = '".$rollNo."'";
							$studentGetRollArray = $studentManager->getStudentRollNo($conditions);
							$studentGetRollNo = $studentGetRollArray[0]['rollNo'];
							if ($studentGetRollNo != '' ) {
								$inconsistenciesArray[] = "Roll No. of Student cannot duplicate at Sr. No.'$srNo'";
								continue;
							}
						}
						}
					}*/
				}


				/*if ($rollNo != '') {

				$conditions = "WHERE s.firstName LIKE '".addslashes(strtolower($firstName))."' AND s.classId = ".$getClassId." AND s.rollNo = '".$rollNo."'";
				$studentArray = $studentManager->getStudentDetailInfo($conditions);
					if(count($studentArray) == 1 && is_array($studentArray)) {
						$studentClassId = $studentArray[0]['classId'];
						if($studentClassId != $getClassId) {
								$inconsistenciesArray[] = "Student is not valid for selected class at Sr. No.'$srNo'";
								continue;
						}
					}

				/*$conditions = "WHERE firstName = '".addslashes(strtolower($firstName))."' AND fatherName = '".addslashes(strtolower($fatherName))."' AND dateOfBirth = '".addslashes($dateOfBirth)."'";
					$studentExistanceArray = $studentManager->getStudent($conditions);
					$count = $studentExistanceArray[0]['totalRecords'];
					if($count > 0 ) {
						$inconsistenciesArray[] = "Related Data has already in Database. Pl. make the status yes at Sr. No.'$srNo'";
						continue;
					}

				$conditions = "WHERE rollNo = '".$rollNo."'";
				$studentRollArray = $studentManager->getStudentRollNo($conditions);
				$studentRollNo = $studentRollArray[0]['rollNo'];

				if($studentRollNo != '') {
					$inconsistenciesArray[] = "Roll No. of Student cannot duplicate at Sr. No.'$srNo'";
					continue;
				}

				$conditions = "WHERE firstName = '".addslashes(strtolower($firstName))."' AND fatherName = '".addslashes(strtolower($fatherName))."' AND dateOfBirth = '".addslashes($dateOfBirth)."'";
				$studentExistanceArray = $studentManager->getStudent($conditions);
				if(count($studentExistanceArray) == 1 && is_array($studentExistanceArray)) {
					$conditions = "WHERE rollNo = '".$rollNo."'";
					$studentRollArray = $studentManager->getStudentRollNo($conditions);
					$studentRollNo = $studentRollArray[0]['rollNo'];
					if ($studentRollNo == $rollNo) {
						$conditions = "WHERE rollNo = '".$rollNo."'";
						$studentGetRollArray = $studentManager->getStudentRollNo($conditions);
						$studentGetRollNo = $studentGetRollArray[0]['rollNo'];
						if ($studentGetRollNo != '' ) {
							$inconsistenciesArray[] = "Roll No. of Student cannot duplicate at Sr. No.'$srNo'";
							continue;
						}
					}
				  }

				}*/


				if ($corrCountry != '') {
					$conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($corrCountry))."'";
					$countryArray = $studentManager->getCountry($conditions);
					$corrCountryId = $countryArray[0]['countryId'];
                    if(empty($corrCountryId)) {
                       $corrCountryId = 'NULL';
                    }
                    /*
					if (empty($corrCountryId)) {
					  $inconsistenciesArray[] = "Correspondence Country is not existed at Sr. No.'$srNo'";
					  continue;
					}
                    */
				}
				else {
					$corrCountryId = 'NULL';
				}

				if ($corrState != '' && $corrCountry != '') {
					$conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($corrState))."' AND st.countryId = ".$corrCountryId;
					$stateArray = $studentManager->getState($conditions);
					$corrStateId = $stateArray[0]['stateId'];
					if(empty($corrStateId)) {
                       $corrStateId = 'NULL';
                    }
                    /*
                    if (empty($corrStateId)) {
						$inconsistenciesArray[] = "Correspondence State is not existed at Sr. No.'$srNo'";
						continue;
					}
                    */
				}
				else {
					$corrStateId = 'NULL';
				}


				if ($corrCity != '' && $corrState != '') {
					$conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($corrCity))."' AND ct.stateId = ".$corrStateId;
					$cityArray = $studentManager->getCity($conditions);
					$corrCityId = $cityArray[0]['cityId'];
                    if(empty($corrCityId)) {
                       $corrCityId = 'NULL';
                    }
                    /*
                    if (empty($corrCityId)) {
						$inconsistenciesArray[] = "Correspondence City is not existed at Sr. No.'$srNo'";
						continue;
					}
                    */
				}
				else {
					$corrCityId = 'NULL';
				}


				if ($permCountry != '') {
					$conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($permCountry))."'";
					$countryArray = $studentManager->getCountry($conditions);
					$permCountryId = $countryArray[0]['countryId'];
					if(empty($permCountryId)) {
                       $permCountryId = 'NULL';
                    }
                    /*if (empty($permCountryId)) {
						$inconsistenciesArray[] = "Permanent Country is not existed at Sr. No.'$srNo'";
						continue;
					}
                    */
				}
				else {
					$permCountryId = 'NULL';
				}

				if ($permState != '' && $permCountry != '') {
					$conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($permState))."' AND st.countryId = ".$permCountryId;
					$stateArray = $studentManager->getState($conditions);
					$permStateId = $stateArray[0]['stateId'];
                    if(empty($permStateId)) {
                       $permStateId = 'NULL';
                    }
					/*if (empty($permStateId)) {
						$inconsistenciesArray[] = "Permanent State is not existed at Sr. No.'$srNo'";
						continue;
					}*/
				}
				else {
					$permStateId = 'NULL';
				}


				if ($permCity != '' && $permState != '') {
					$conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($permCity))."' AND ct.stateId = ".$permStateId;
					$cityArray = $studentManager->getCity($conditions);
					$permCityId = $cityArray[0]['cityId'];
                    if(empty($permCityId)) {
                       $permCityId = 'NULL';
                    }
                    /*if (empty($permCityId)) {
						$inconsistenciesArray[] = "Permanent City is not existed at Sr. No.'$srNo'";
						continue;
					}
                    */
				}
				else {
					$permCityId = 'NULL';
				}

				if($fatherCountry != '') {
					$conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($fatherCountry))."'";
					$countryArray = $studentManager->getCountry($conditions);
					$fatherCountryId = $countryArray[0]['countryId'];
                    if(empty($fatherCountryId)) {
                       $fatherCountryId = 'NULL';
                    }
					/*if (empty($fatherCountryId)) {
						$inconsistenciesArray[] = "Father Country is not existed at Sr. No.'$srNo'";
						continue;
					} */
				}
				else {
					$fatherCountryId = 'NULL';
				}


				if($fatherState != '' && $fatherCountry != '') {
					$conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($fatherState))."' AND st.countryId = ".$fatherCountryId;
					$stateArray = $studentManager->getState($conditions);
					$fatherStateId = $stateArray[0]['stateId'];
                    if(empty($fatherStateId)) {
                       $fatherStateId = 'NULL';
                    }
					/*if (empty($fatherStateId)) {
						$inconsistenciesArray[] = "Father State is not existed at Sr. No.'$srNo'";
						continue;
					}*/
				}
				else {
					$fatherStateId = 'NULL';
				}

				if($fatherCity != '' && $fatherState != '') {
					$conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($fatherCity))."' AND ct.stateId = ".$fatherStateId;
					$cityArray = $studentManager->getCity($conditions);
					$fatherCityId = $cityArray[0]['cityId'];
                     if(empty($fatherCityId)) {
                       $fatherCityId = 'NULL';
                    }
					/*if (empty($fatherCityId)) {
						$inconsistenciesArray[] = "Father City is not existed at Sr. No.'$srNo'";
						continue;
					}*/
				}
				else {
					$fatherCityId = 'NULL';
				}

				if($domicile != '') {
					$conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($domicile))."'";
					$stateArray = $studentManager->getState($conditions);
					$domicileId = $stateArray[0]['stateId'];
					if (empty($domicileId)) {
						$inconsistenciesArray[] = "Domicile is not existed at Sr. No.'$srNo'";
						continue;
					}
				}
				else {
					$domicileId = 'NULL';
				}

				if($isLeet != '') {
					if(addslashes(strtolower($isLeet)) == 'yes') {
						$isLeet = 1;
					}
					else {
						$isLeet = 0;
					}
				}

				if ($gender != '') {
					if(addslashes(strtolower($gender)) == 'male') {
						$gender = 'M';
					}
					else {
						$gender = 'F';
					}
				}

				if($studentStatus != '') {
					if(addslashes(strtolower($studentStatus)) == 'yes') {
						$studentStatus = 1;
					}
					else {
						$studentStatus = 0;
					}
				}

				if($hostelFacility != '') {
					if(addslashes(strtolower($hostelFacility)) == 'yes') {
						$hostelFacility = 1;
					}
					else {
						$hostelFacility = 0;
					}
				}

				if($busFacility != '') {
					if(addslashes(strtolower($busFacility)) == 'yes') {
						$busFacility = 1;
					}
					else {
						$busFacility = 0;
					}
				}

				if($nationality!='') {
					$conditions = "WHERE LCASE(c.nationalityName) = '".addslashes(strtolower($nationality))."'";
					$nationalityArray = $studentManager->getCountry($conditions);
					$nationalityId = $nationalityArray[0]['countryId'];
					if (empty($nationalityId)) {
						$inconsistenciesArray[] = "Nationality is not existed at Sr. No.'$srNo'";
						continue;
					}
				}


				if($quota!='') {
					$conditions = "WHERE LCASE(qt.quotaName) = '".addslashes(strtolower($quota))."'";
					$quotaArray = $studentManager->getQuota($conditions);
					$quotaId = $quotaArray[0]['quotaId'];
					if (empty($quotaId)) {
						$inconsistenciesArray[] = "Quota is not existed at Sr. No.'$srNo'";
						continue;
					}
				}


				/*if($contactNo != '') {
					$contactNo = $contactNo;
				}
				else {
					$contactNo = $contactNo;
				}*/


				/*if (!empty($str)) {
					$str .= ',';
				}*/


				if($studentArray[0]['totalRecords'] == 1) {
					if($rollNo == '') {
						$returnStudentWithoutRollNoInfo = $studentManager->updateStudentWithoutRollNoInfoInTransaction($univRollNo,$firstName,$isLeet,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$dateOfAdmission,$registrationNo,$studentRollNo);
							if ($returnStudentWithoutRollNoInfo == false) {
								$inconsistenciesArray[] = "Error while saving student detail";
							}
					}
					else if ($rollNo != '' || $univRollNo != '') {
						//echo 'r u there';
						$conditions = "WHERE rollNo = '".$rollNo."'";
						$studentRollArray = $studentManager->getStudentRollNo($conditions);
						$studentRollNo = $studentRollArray[0]['rollNo'];

						$conditions = "WHERE universityRollNo = '".$univRollNo."'";
						$studentUniversityRollArray = $studentManager->getStudentRollNo($conditions);
						$studentUniversityRollNo = $studentUniversityRollArray[0]['universityRollNo'];
						/*if($studentUniversityRollNo != '') {
							$inconsistenciesArray[] = "Duplicate University Roll No. for selected class at Sr. No.'$srNo'";
							continue;
						}*/

						if($studentRollNo == '' || $studentUniversityRollNo == '') {
							//echo 'hire';
							if ($rollNo == '') {
								$rollNo = "NULL";
							}
							else {
								$rollNo = "'$rollNo'";
							}
							if ($studentRollNo == '') {
								$studentRollNo = "NULL";
							}
							else {
								$studentRollNo = "'$studentRollNo'";
							}
							if ($univRollNo == '') {
								$univRollNo = "NULL";
							}
							else {
								$univRollNo = "'$univRollNo'";
							}
							$returnStudentWithoutRollNoInfo = $studentManager->updateStudentRollNoInfoInTransaction($univRollNo,$firstName,$rollNo,$isLeet,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$dateOfAdmission,$registrationNo,$studentRollNo);
							if ($returnStudentWithoutRollNoInfo == false) {
								$inconsistenciesArray[] = "Error while saving student detail";
							}
						}
						else {
							//echo 'hi';
							$returnStudentInfo = $studentManager->updateStudentInfoInTransaction($isLeet,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$dateOfAdmission,$registrationNo,$studentRollNo);
							if ($returnStudentInfo == false) {
								$inconsistenciesArray[] = "Error while saving student detail";
							}
						  }
						}
					}

				else {

					if ($rollNo == '') {
						$rollNo = "NULL";
					}
					else {
						$rollNo = "'$rollNo'";
					}
					if ($univRollNo == '') {
						$univRollNo = "NULL";
					}
					else {
						$univRollNo = "'$univRollNo'";
					}
					$return = $studentManager->addStudentInfoInTransaction($getClassId,$rollNo,$univRollNo,$isLeet,$firstName,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$dateOfAdmission,$registrationNo);
					if ($return == false) {
						$inconsistenciesArray[] = "Error while saving student detail";
					}
				}

				//$str .= "('$getClassId','$rollNo','$firstName','$lastName','$fatherName','$motherName','$dateOfBirth','$corrAddress1','$corrAddress2','$corrPinCode','$corrCountryId','$corrStateId','$corrCityId','$permAddress1','$permAddress2','$permPinCode','$permCountryId','$permStateId','$permCityId','$gender','$nationalityId','$quotaId','$contactNo','$emailAddress')";
				//$insertQueryArray[] = $str;
				//$str = '';
				//}
			}

		}
	}
	else {
		echo FAILURE;
	}

/*echo '<pre>';
print_r($inconsistenciesArray);
die('i m here');*/

if (count($inconsistenciesArray) == 0) {

	/*if(SystemDatabaseManager::getInstance()->startTransaction()) {
		foreach($insertQueryArray as $insertQuery) {
			if ($studentRollNo == '') {
				$return = $studentManager->addStudentInfoInTransaction($insertQuery);
				if ($return == false) {
					$inconsistenciesArray[] = "Error while saving student detail";
				}
			}
			else {
				$return = $studentManager->updateStudentInfoInTransaction($insertQuery);
				if ($return == false) {
					$inconsistenciesArray[] = "Error while saving student detail";
				}

			}
		}
	}*/

	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		$successArray[] = "Data saved successfully for $totalClassStudents  students of Class: '$className'";
		$csvData = '';
		$i = 1;
		foreach($successArray as $key=>$record) {
			$csvData .= "$i. $record\r\n";
			$i++;
		}
		$csvData = trim($csvData);
		$fileName = "Upload Student Information.txt";
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
	$fileName = "Inconsistencies_Student_Information.txt";
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
//*****************  Version 15  *****************
//User: Jaineesh     Date: 2/06/10    Time: 2:06p
//Updated in $/LeapCC/Library/StudentDetailUpload
//modification in code for student detail upload
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 1/22/10    Time: 1:42p
//Updated in $/LeapCC/Library/StudentDetailUpload
//remove the comments
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 1/19/10    Time: 4:51p
//Updated in $/LeapCC/Library/StudentDetailUpload
//comment one line
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 12/29/09   Time: 1:06p
//Updated in $/LeapCC/Library/StudentDetailUpload
//remove check for domicile
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 12/28/09   Time: 4:38p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put new field university roll no.
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 12/19/09   Time: 12:35p
//Updated in $/LeapCC/Library/StudentDetailUpload
//modified in message
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 12/08/09   Time: 5:09p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put 14 new fields during student uploading and modification in checks
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/01/09   Time: 3:32p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put addslashes during corrAddress, PermAddress
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 12/01/09   Time: 1:35p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put new field registration No. during uploading student detail
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/01/09   Time: 12:44p
//Updated in $/LeapCC/Library/StudentDetailUpload
//modified in query to check state, city, country for NULL
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/01/09   Time: 12:36p
//Updated in $/LeapCC/Library/StudentDetailUpload
//check NULL for state, country, city
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/01/09   Time: 12:28p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put new field date of admission
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/01/09   Time: 11:43a
//Updated in $/LeapCC/Library/StudentDetailUpload
//modified in student upload format
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/19/09   Time: 10:53a
//Updated in $/LeapCC/Library/StudentDetailUpload
//put check if user can upload file in given format
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/18/09   Time: 6:40p
//Created in $/LeapCC/Library/StudentDetailUpload
//new ajax file for student detail upload
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/04/09   Time: 11:48a
//Created in $/LeapCC/Library/StudentRollNoUpload
//new files for student roll no. uploading
//
?>