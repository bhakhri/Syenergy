<?php
//-------------------------------------------------------
// THIS FILE IS USED TO update student Detail
//
// Author : Himani Jaswal
// Created on : (9.11.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------

set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . "/FileUploadManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	//require_once(TEMPLATES_PATH . '/StudentDetailUpload/listStudentDetailUpload.php');
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
		echo ('<script type="text/javascript">alert("Please Select Excel File");</script>');
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
					
				}
			}
	
		$rollNoArray = array();
		$univRollNoArray = array();
    
    
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
                        $inconsistenciesArray[] = "Duplicate Roll No. for selected class at Sr. No.'$srNo'";
                      
                    }
                }

                if($univRollNo != '') {
                    if(count($univRollNoArray)!=count(array_unique($univRollNoArray))){
                        $inconsistenciesArray[] = "Duplicate University Roll No. for selected class at Sr. No.'$srNo'";
                       
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
				$alternateEmailAddress = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][41]));
				$dateOfAdmission = trim($data->sheets[$sheetIndex]['cells'][$i][42]);
				$registrationNo = trim($data->sheets[$sheetIndex]['cells'][$i][43]);
				//$status = trim($data->sheets[$sheetIndex]['cells'][$i][42]);
                
                
                /* LOGIC FOR THIS SCRIPT.
                
                1. check whether the roll no. is valid or not. if not, then put it in $inconsistenciesArray.
                2. check whether all the fields are entered properly or not.
                3. if inconsitency array is blank, then update record for that roll no.
                */
                if($rollNo == '')
                {
                    $inconsistenciesArray[] = "Please enter Roll No.'";  
                }
                 if($univRollNo == '')
                {
                    $inconsistenciesArray[] = "Please enter University Roll No.'";  
                }
                
                   //$totalClassStudents++;
				
			
				if($getClassId != '') {
					$conditions = "WHERE cl.classId = ".$getClassId;
					$classArray = $studentManager->getClassInfo($conditions);
					$className = $classArray[0]['className'];
					$classId = $classArray[0]['classId'];
				}
				
				if (empty($firstName)) {
					$inconsistenciesArray[] = "Please mention First Name of Student for selected class at Sr. No.'$srNo'";
				
				}

				if (empty($fatherName)) {
					$inconsistenciesArray[] = "Please mention Father Name of Student for selected class at Sr. No.'$srNo'";
				
				}
				if (empty($gender)) {
					$inconsistenciesArray[] = "Please mention Gender of Student for selected class at Sr. No.'$srNo'";
					
				}
				
                 if(!empty($busFacility)) {
					if(strtolower($busFacility) != 'yes'){
						if(strtolower($busFacility) != 'no')
						{
							$inconsistenciesArray[] = "Invalid data in bus Facility at Sr. No.'$srNo'";
						}
					}
				 }
				if(!empty($hostelFacility)) {
					if(strtolower($hostelFacility) != 'yes'){
						if(strtolower($hostelFacility) != 'no')
						{
							$inconsistenciesArray[] = "Invalid data in hostel facility at Sr. No.'$srNo'";
						}
					}
				}
                
				if($gender !='Male')
				{
				if($gender !='Female')
				{
					$inconsistenciesArray[] = "Please mention correct Gender";
					continue;
				}
				}
				if(!empty($isLeet)) {
					if(strtolower($isLeet) != 'yes'){
						if(strtolower($isLeet) != 'no')
						{
							$inconsistenciesArray[] = "Invalid data in isLeet at Sr. No.'$srNo'";
						}
					}
				}
                
			

				if (empty($nationality)) {
					$inconsistenciesArray[] = "Please mention Nationality of Student for selected class at Sr. No.'$srNo'";
					
				}

				if (empty($quota)) {
					$inconsistenciesArray[] = "Please mention Quota of Student for selected class at Sr. No.'$srNo'";
					continue;
				}

				if($dateOfBirth != '') {
					$cnt = substr_count($dateOfBirth, '.');
					if($cnt == 0 ) {
						$inconsistenciesArray[] = "Date of Birth not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
					
					}
					
					$dateOfBirthDate = explode('.',$dateOfBirth);
				
					$dateOfBirthYear = $dateOfBirthDate[0];
					$dateOfBirthMonth = $dateOfBirthDate[1];
					$dateOfBirthDay = $dateOfBirthDate[2];
					
					$checkBirthDate = checkdate($dateOfBirthMonth,$dateOfBirthDay,$dateOfBirthYear);
					if($checkBirthDate){
						$dateOfBirth = $dateOfBirthYear.'-'.$dateOfBirthMonth.'-'.$dateOfBirthDay;
					}
					else {
						$inconsistenciesArray[] = "Invalid Birth Date at Sr. No.'$srNo'";
						
					}
				}

				if($dateOfAdmission != '') {
					$cnt = substr_count($dateOfAdmission, '.');
					if($cnt == 0 ) {
						$inconsistenciesArray[] = "Date of Admission not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
				
					}

					$dateOfAdmissionDate = explode('.',$dateOfAdmission);
				
					$dateOfAdmissionYear = $dateOfAdmissionDate[0];
					$dateOfAdmissionMonth = $dateOfAdmissionDate[1];
					$dateOfAdmissionDay = $dateOfAdmissionDate[2];


					$checkDateOfAdmissionDate = checkdate($dateOfAdmissionMonth,$dateOfAdmissionDay,$dateOfAdmissionYear);

					if($checkDateOfAdmissionDate) {
						$dateOfAdmission = $dateOfAdmissionYear.'-'.$dateOfAdmissionMonth.'-'.$dateOfAdmissionDay;
					}
					else {
						$inconsistenciesArray[] = "Invalid Date of Admission at Sr. No.'$srNo'";
					
					}  
				}
                
                
                
                    $dateOfBirthDate = explode('-',$dateOfBirth);
                
                    $dateOfBirthYear = $dateOfBirthDate[0];
                    $dateOfBirthMonth = $dateOfBirthDate[1];
                    $dateOfBirthDay = $dateOfBirthDate[2];
                $dateOfAdmissionDate = explode('-',$dateOfAdmission);
                
                    $dateOfAdmissionYear = $dateOfAdmissionDate[0];
                    $dateOfAdmissionMonth = $dateOfAdmissionDate[1];
                    $dateOfAdmissionDay = $dateOfAdmissionDate[2];
                  
                  if($dateOfAdmissionYear <= $dateOfBirthYear) {
                  
					   // die(' '.__LINE__.$dateOfAdmissionYear.$dateOfBirthYear);
                          $inconsistenciesArray[] = "Date of admission should be greater than Date of Birth at Sr. No.'$srNo'";
                 
                    }
				
				if($firstName != '' && $fatherName != '') {
					
				//$conditions = "WHERE s.firstName LIKE '".addslashes(strtolower($firstName))."' AND s.classId = ".$getClassId." AND s.rollNo = '".$rollNo."'";
				$conditions = "WHERE firstName = '".addslashes(strtolower($firstName))."' AND fatherName = '".addslashes(strtolower($fatherName))."' AND dateOfBirth = '".addslashes($dateOfBirth)."'";
				$studentArray = $studentManager->getStudent($conditions);
				}
					
					if ($corrCountry != '') {
					$conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($corrCountry))."'";
					$countryArray = $studentManager->getCountry($conditions);
					$corrCountryId = $countryArray[0]['countryId'];
					if (empty($corrCountryId)) {
						$inconsistenciesArray[] = "Correspondence Country is not existed at Sr. No.'$srNo'";
					
					}
				}
				else {
					$corrCountryId = 'NULL';
				}

				if ($corrState != '' && $corrCountry != '') {
					$conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($corrState))."' AND st.countryId = ".$corrCountryId;
					$stateArray = $studentManager->getState($conditions);
					$corrStateId = $stateArray[0]['stateId'];
					if (empty($corrStateId)) {
						$inconsistenciesArray[] = "Correspondence State is not existed at Sr. No.'$srNo'";
				
					}
				}
				else {
					$corrStateId = 'NULL';
				}


				if ($corrCity != '' && $corrState != '') {
					$conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($corrCity))."' AND ct.stateId = ".$corrStateId;
					$cityArray = $studentManager->getCity($conditions);
					$corrCityId = $cityArray[0]['cityId'];
					if (empty($corrCityId)) {
						$inconsistenciesArray[] = "Correspondence City is not existed at Sr. No.'$srNo'";
						
					}
				}
				else {
					$corrCityId = 'NULL';
				}

			
				if ($permCountry != '') {
					$conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($permCountry))."'";
					$countryArray = $studentManager->getCountry($conditions);
					$permCountryId = $countryArray[0]['countryId'];
					if (empty($permCountryId)) {
						$inconsistenciesArray[] = "Permanent Country is not existed at Sr. No.'$srNo'";
						
					}
				}
				else {
					$permCountryId = 'NULL';
				}

				if ($permState != '' && $permCountry != '') {
					$conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($permState))."' AND st.countryId = ".$permCountryId;
					$stateArray = $studentManager->getState($conditions);
					$permStateId = $stateArray[0]['stateId'];
					if (empty($permStateId)) {
						$inconsistenciesArray[] = "Permanent State is not existed at Sr. No.'$srNo'";
					
					}
				}
				else {
					$permStateId = 'NULL';
				}


				if ($permCity != '' && $permState != '') {
					$conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($permCity))."' AND ct.stateId = ".$permStateId;
					$cityArray = $studentManager->getCity($conditions);
					$permCityId = $cityArray[0]['cityId'];
					if (empty($permCityId)) {
						$inconsistenciesArray[] = "Permanent City is not existed at Sr. No.'$srNo'";
					
					}
				}
				else {
					$permCityId = 'NULL';
				}

				if($fatherCountry != '') {
					$conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($fatherCountry))."'";
					$countryArray = $studentManager->getCountry($conditions);
					$fatherCountryId = $countryArray[0]['countryId'];
					if (empty($fatherCountryId)) {
						$inconsistenciesArray[] = "Father Country is not existed at Sr. No.'$srNo'";
					
					}
				}
				else {
					$fatherCountryId = 'NULL';
				}


				if($fatherState != '' && $fatherCountry != '') {
					$conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($fatherState))."' AND st.countryId = ".$fatherCountryId;
					$stateArray = $studentManager->getState($conditions);
					$fatherStateId = $stateArray[0]['stateId'];
					if (empty($fatherStateId)) {
						$inconsistenciesArray[] = "Father State is not existed at Sr. No.'$srNo'";
					
					}
				}
				else {
					$fatherStateId = 'NULL';
				}

				if($fatherCity != '' && $fatherState != '') {
					$conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($fatherCity))."' AND ct.stateId = ".$fatherStateId;
					$cityArray = $studentManager->getCity($conditions);
					$fatherCityId = $cityArray[0]['cityId'];
					if (empty($fatherCityId)) {
						$inconsistenciesArray[] = "Father City is not existed at Sr. No.'$srNo'";
					
					}
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
						
					}
				}
				else {
					$domicileId = 'NULL';
				}
				if($studentStatus != '') {
					if((strtolower($studentStatus)) == 'yes') {
						$studentStatus = 1;
					}
					else {
						$studentStatus = 0;
					}
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
				if(!empty($busFacility)) {
					
						if((strtolower($busFacility)) == 'yes') {
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
					
					}
				}
				

				if($quota!='') {
					$conditions = "WHERE LCASE(qt.quotaName) = '".addslashes(strtolower($quota))."'";
					$quotaArray = $studentManager->getQuota($conditions);
					$quotaId = $quotaArray[0]['quotaId'];
					if (empty($quotaId)) {
						$inconsistenciesArray[] = "Quota is not existed at Sr. No.'$srNo'";
						
					}
				}
				if(ereg('[^A-Za-z ]', $firstName))  {
						$inconsistenciesArray[] = "Please enter alphabets in first Name of Student for selected class at Sr. No.'$srNo'"; 
									
					}
				//	} 
				if(ereg('[^A-Za-z ]', $fatherName))  {
						$inconsistenciesArray[] = "Please enter alphabets in father Name of Student for selected class at Sr. No.'$srNo'"; 
										
					}
				if(!empty($motherName)) {
					if(ereg('[^A-Za-z ]', $motherName))  {
						$inconsistenciesArray[] = "Please enter alphabets in mother Name of Student for selected class at Sr. No.'$srNo'"; 
											
					}
				}
				if(!empty($contactNo)) {
					if(ereg('[^0-9]', $contactNo))  {
						$inconsistenciesArray[] = "Please enter digits in contact no at Sr. No.'$srNo'"; 
										
					}
				}
				if(!empty($fatherMobile)) {
					if(ereg('[^0-9]',$fatherMobile))  {
						$inconsistenciesArray[] = "Please enter digits in father's mobile no at Sr. No.'$srNo'"; 
											
					}
				}
				if(!empty($studentMobile)) {
					if(ereg('[^0-9]',$studentMobile))  {																						
						$inconsistenciesArray[] = "Please enter digits in student's mobile no at Sr. No.'$srNo'"; 
										
					}
				}
				if(!empty($fatherOccupation)) {
					if(ereg('[^0-9A-Za-z ]',$fatherOccupation))  {																						
						$inconsistenciesArray[] = "Please enter correct father's occupation at Sr. No.'$srNo'"; 
										
					}
				}
				if(!empty($correspondencePhone)) {
					if(ereg('[^0-9]',$correspondencePhone))  {																						
						$inconsistenciesArray[] = "Please enter digits in correspondance phone no at Sr. No.'$srNo'"; 
									
					}
				}
				if(!empty($permanentPhone)) {
					if(ereg('[^0-9]',$permanentPhone))  {																						
						$inconsistenciesArray[] = "Please enter digits in permanent phone no at Sr. No.'$srNo'"; 
										
					}
				}
				if(!empty($permPinCode)) {
					if(ereg('[^A-Za-z0-9]',$permPinCode))  {																						
						$inconsistenciesArray[] = "Please enter correct permanent pin code  at Sr. No.'$srNo'"; 
						continue;					
					}
				}	
				if(!empty($corrPinCode)) {
					if(ereg('[^A-Za-z0-9]',$corrPinCode))  {																						
						$inconsistenciesArray[] = "Please enter correct correspondance pin code at Sr. No.'$srNo'"; 
									
					}
				}	
                
             //   echo $studentArray[0]['totalRecords'];
              //  die(' '.__LINE__);
              		//ADD SaLLCLASS IN STUDENT TABLE
				 $studentClassStatus = $studentManager->updateStudentAllClasses($classId);
				 if($studentClassStatus===false){
	               $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
	               echo FAILURE;
	            } 
				 $allClass ='';
				 for($i=0;$i<count($studentClassStatus);$i++){			 
				 if($allClass !=''){
				 	$allClass .='~';				
				 }	
					$allClass .=$studentClassStatus[$i]['classId'];	
				 }
	            $sAllClass ="~".$allClass."~";	
					
					if($rollNo != '') {    
						$updateRecordWithUnivRoll = $studentManager->updateRecordWithUnivRoll($univRollNo,$firstName,$rollNo,$isLeet,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo,$sAllClass);
							if ($updateRecordWithUnivRoll == false) {
								$inconsistenciesArray[] = "Error while saving student detail";
							}
					
                            }
	}
        }
    }
    else
    {
    echo "Failure";
    } 

	
	if (count($inconsistenciesArray) == 0) {					
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		$successArray[] = "Data Updated successfully for students of Class: '$className'";
		$csvData = '';
		$i = 1;
		foreach($successArray as $key=>$record) {
			$csvData .= "$i. $record\r\n";
			$i++;
		}
		$csvData = trim($csvData);
		$fileName = "Update Student Information.txt";
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
				
?>