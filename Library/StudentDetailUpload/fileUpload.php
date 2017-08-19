<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student Detail
//
// Author : Jaineesh
// Created on : (14.11.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
    $totalStudentsAdded ='';

    $getClassId = $REQUEST_DATA['degree'];

    if ($getClassId == '') {
        echo ('<script type="text/javascript">alert("Please select class");</script>');
        die;
    }

    foreach($sheetNameArray as $sheetIndex=>$value) {

            $rollNoArray = array();
            $univRollNoArray = array();
			$srNoArray = array();
            //echo($data->sheets[$sheetIndex]['numRows']);
            for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {

                    $recordStatus='';
                    
                    $srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
                    $rollNo = $data->sheets[$sheetIndex]['cells'][$i][2];
                    $univRollNo = $data->sheets[$sheetIndex]['cells'][$i][3];
                    $isLeet = $data->sheets[$sheetIndex]['cells'][$i][4];
                    $firstName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][5]));
                    $lastName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][6]));
                    $fatherName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][7]));
                    $fatherOccupation = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][8]));
                    $fatherMobile = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][9]));
                    $fatherAddress1 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][10]));
                    $fatherAddress2 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][11]));
                    $fatherCountry = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][12]));
                    $fatherState = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][13]));
                    $fatherCity = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][14]));
                    $motherName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][15]));
                    $dateOfBirth = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][16]));
                    $corrAddress1 = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][17])));
                    $corrAddress2 = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][18])));
                    $corrPinCode = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][19]));
                    $corrCountry = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][20]));
                    $corrState = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][21]));
                    $corrCity = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][22]));
                    $permAddress1 = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][23])));
                    $permAddress2 = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][24])));
                    $permPinCode = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][25]));
                    $permCountry = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][26]));
                    $permState = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][27]));
                    $permCity = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][28]));
                    $studentMobile = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][29]));
                    $domicile = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][30]));
                    $hostelFacility = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][31]));
                    $busFacility= htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][32]));
                    $correspondencePhone = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][33]));
                    $permanentPhone = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][34]));
                    $studentStatus = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][35]));
                    $gender = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][36]));
                    $nationality = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][37]));
                    $quota = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][38]));
                    $contactNo = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][39]));
                    $emailAddress = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][40])));
                    $alternateEmailAddress = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][41])));
                    $dateOfAdmission = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][42]));
                    $registrationNo = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][43]));
                    //$status = trim($data->sheets[$sheetIndex]['cells'][$i][42]);  
                  
                    if(empty($srNo)) {
                       break;
                    }
              
				    $allowedArray = range(97,122);
				    foreach ($allowedArray as $allowedValue) {
					    $allowedAsciiArray[] = $allowedValue;
				    }
				    $allowedArray = range(48,57);
				    foreach ($allowedArray as $allowedValue) {
					    $allowedAsciiArray[] = $allowedValue;
				    }
				    $allowedArray = range(65,90);
				    foreach ($allowedArray as $allowedValue) {
					    $allowedAsciiArray[] = $allowedValue;
				    }

				    $allowedAsciiArray[] = 40;
				    $allowedAsciiArray[] = 41;
				    $allowedAsciiArray[] = 45;
				    $allowedAsciiArray[] = 46;
				    $allowedAsciiArray[] = 47;
				    $allowedAsciiArray[] = 92;
				    $allowedAsciiArray[] = 95;
                    
				    if($rollNo != ''){
					    $start = 0;
					    $total = strlen($rollNo);
					    $newRollNo = '';
					    $wrongValue= '';
					    while ($start < $total) {
						    $char = substr($rollNo, $start, 1);
						    $ascii = ord($char);
						    if (in_array($ascii, $allowedAsciiArray)) {
						     $newRollNo .= $char;
						    }
						    else{
						    $wrongValue .= $char;
						    }
					    $start++;
					    }
					    if($newRollNo != '' && $wrongValue == ''){
					      $rollNo = $newRollNo;
					    }
					    else{
					        $inconsistenciesArray[] = "Incorrrect Rollno. at Sr. No.'$srNo'";
					     }
                   }
			       else{                                                                       
                       $inconsistenciesArray[] = "Please enter Roll No. at Sr. No.'".$srNo."'";
                       $recordStatus='1'; 
                   }

			       if(!in_array($rollNo, $rollNoArray)) {
                     $rollNoArray[] = $rollNo;
                   }
                   else{
                     $inconsistenciesArray[] = "Duplicate Roll No. for selected class at Sr. No.'".$srNo."'";
                     $recordStatus='1'; 
                   }
                   
				   if($univRollNo != ''){
					    $start = 0;
					    $total = strlen($univRollNo);
					    $newUnivRollNo = '';
					    $wrongVal= '';
					    while ($start < $total) {
						    $char = substr($univRollNo, $start, 1);
						    $ascii = ord($char);
						    if (in_array($ascii, $allowedAsciiArray)) {
						     $newUnivRollNo .= $char;
						    }
						    else{
						    $wrongVal .= $char;
						    }
					    $start++;
					    }
					    if($newUnivRollNo != '' && $wrongVal == '' ){
					    $univRollNo = $newUnivRollNo;
					    }
					    else{
					        $inconsistenciesArray[] = "Incorrrect University Roll No. at Sr. No.'".$srNo."'";
                            $recordStatus='1'; 
				       }
				  }
				  if(!in_array($srNo,$srNoArray)) {
					$srNoArray[] = $srNo;
				  } 
                  else {    
					$inconsistenciesArray[] = "Duplicate Sr. No. for selected class at Sr. No.'".$srNo."'";
                    $recordStatus='1'; 
				  }

                 if (!in_array($univRollNo, $univRollNoArray)) {
                     $univRollNoArray[] = $univRollNo;
                 }
                 else {
                    $inconsistenciesArray[] = "Duplicate University Roll No. for selected class at Sr. No.'".$srNo."'";
                    $recordStatus='1'; 
                 }
                 
                 
                 if ($rollNo !='')  {
                     $conditions =  " WHERE LCASE(rollNo) = '".addslashes(strtolower($rollNo))."'";
                     $countRollno =  $studentManager->getStudent($conditions);
                     if ($countRollno[0]['totalRecords'] != 0) {
                        $inconsistenciesArray[] = "Roll no at Sr. No.'$srNo' already exists";
                        $recordStatus='1';    
                    }
                 }
                 else {
                     $inconsistenciesArray[] = "Please enter Roll no at Sr. No.'$srNo' ";
                     $recordStatus='1';    
                 } 

                 if ($univRollNo !='')  {
                     $conditions =  " WHERE LCASE(universityRollNo) = '".addslashes(strtolower($univRollNo))."'";
                     $countUnivRollno =  $studentManager->getStudent($conditions);

                     if ($countUnivRollno[0]['totalRecords'] != 0) {
                        $inconsistenciesArray[] = "University Roll No. at Sr. No.'$srNo' already exists";
                        $recordStatus='1';  
                    }
                 }
                 else {
                    $inconsistenciesArray[] = "Please enter University Roll No. at Sr. No.'".$srNo."'";
                    $recordStatus='1';  
                }

                if($registrationNo != ''){
					$start = 0;
					$total = strlen($univRollNo);
					$newRegistrationlNo = '';
					$wrongValues= '';
					while ($start < $total) {
						$char = substr($registrationNo, $start, 1);
						$ascii = ord($char);
						if (in_array($ascii, $allowedAsciiArray)) {
						  $newRegistrationlNo .= $char;
						}
						else{
						  $wrongValues .= $char;
						}
					$start++;
					}
					if($newRegistrationlNo != '' && $wrongValues == '' ){
					  $registrationNo = $newRegistrationlNo;
					}
					else{
					  $inconsistenciesArray[] = "Incorrrect Registration No. at Sr. No.'".$srNo."'";
                      $recordStatus='1';  
					}
				}

                if($getClassId != '') {
                   $conditions = "WHERE cl.classId = ".$getClassId;
                   $classArray = $studentManager->getClassInfo($conditions);
                   $className = $classArray[0]['className'];
                }

                if(empty($firstName)) {
                   $inconsistenciesArray[] = "Please mention First Name of Student for selected class at Sr. No.'".$srNo."'";
                   $recordStatus='1'; 
				}
					//$len = strLen($firstName);
				//	for($i=0;$i<$len;$i++) {
				if(ereg('[^A-Za-z ]', $firstName))  {
				   $inconsistenciesArray[] = "Please enter alphabets in first Name of Student for selected class at Sr. No.'".$srNo."'";
                   $recordStatus='1'; 
				}
				
				if(ereg('[^A-Za-z ]', $fatherName))  {
				   $inconsistenciesArray[] = "Please enter alphabets in father Name of Student for selected class at Sr. No.'".$srNo."'";
                   $recordStatus='1'; 
				}
				if(!empty($motherName)) {
					if(ereg('[^A-Za-z ]', $motherName))  {
						$inconsistenciesArray[] = "Please enter alphabets in mother Name of Student for selected class at Sr. No.'".$srNo."'";
                        $recordStatus='1'; 
					}
				}
				if(!empty($contactNo)) {
					if(ereg('[^0-9]', $contactNo))  {
						$inconsistenciesArray[] = "Please enter digits in contact no. at Sr. No.'".$srNo."'";
                        $recordStatus='1'; 
					}
				}
				if(!empty($fatherMobile)) {
					if(ereg('[^0-9]',$fatherMobile))  {
						$inconsistenciesArray[] = "Please enter digits in father's mobile no. at Sr. No.'".$srNo."'";
                        $recordStatus='1'; 
					}
				}
				if(!empty($studentMobile)) {
					if(ereg('[^0-9]',$studentMobile))  {
						$inconsistenciesArray[] = "Please enter digits in student's mobile no. at Sr. No.'$srNo'";
                        $recordStatus='1'; 
					}
				}
				if(!empty($fatherOccupation)) {
					if(ereg('[^0-9A-Za-z ]',$fatherOccupation))  {
						$inconsistenciesArray[] = "Please enter correct father's occupation at Sr. No.'$srNo'";
                        $recordStatus='1'; 
					}
				}
				if(!empty($correspondencePhone)) {
					if(ereg('[^0-9]',$correspondencePhone))  {
						$inconsistenciesArray[] = "Please enter digits in correspondance phone no. at Sr. No.'$srNo'";
                        $recordStatus='1'; 
					}
				}
				if(!empty($permanentPhone)) {
					if(ereg('[^0-9]',$permanentPhone))  {
					   $inconsistenciesArray[] = "Please enter digits in permanent phone no. at Sr. No.'$srNo'";
                       $recordStatus='1'; 
					}
				}
				if(!empty($permPinCode)) {
					if(ereg('[^A-Za-z0-9]',$permPinCode))  {
						$inconsistenciesArray[] = "Please enter correct permanent pin code  at Sr. No.'$srNo'";
                        $recordStatus='1'; 
					}
				}
				if(!empty($corrPinCode)) {
					if(ereg('[^A-Za-z0-9]',$corrPinCode))  {
						$inconsistenciesArray[] = "Please enter correct correspondance pin code at Sr. No.'$srNo'";
                        $recordStatus='1'; 
					}
				}
				$pattern = "^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9]+(\.[a-z0-9]+)*(\.[a-z]{2,3})$";
				if(!empty($emailAddress)) {
					if (!eregi($pattern,$emailAddress)){
					   $inconsistenciesArray[] = "Please enter valid email address at Sr. No.'$srNo'";
                       $recordStatus='1'; 
					}
				}
				if(!empty($alternateEmailAddress)) {
					if (!eregi($pattern,$alternateEmailAddress)){
						$inconsistenciesArray[] = "Please enter valid alternate email address at Sr. No.'$srNo'";
                        $recordStatus='1'; 
					}
				}

                if (empty($fatherName)) {
                    $inconsistenciesArray[] = "Please mention Father Name of Student for selected class at Sr. No.'$srNo'";
                    $recordStatus='1'; 
                }

                /*if (empty($motherName)) {
                    $inconsistenciesArray[] = "Please mention Mother Name of Student for selected class at Sr. No.'$srNo'";
                    continue;
                }

                if (empty($dateOfBirth)) {
                    $inconsistenciesArray[] = "Please mention Date of Birth of Student for selected class at Sr. No.'$srNo'";
                    continue;
                }*/

              

                if(!empty($busFacility)) {
					if(strtolower($busFacility) != 'yes'){
						if(strtolower($busFacility) != 'no')  {
						   $inconsistenciesArray[] = "Invalid data in bus Facility at Sr. No.'$srNo' ";
                           $recordStatus='1';   
						}
					}
				}
				if(!empty($hostelFacility)) {
					if(strtolower($hostelFacility) != 'yes'){
						if(strtolower($hostelFacility) != 'no') {
							$inconsistenciesArray[] = "Invalid data in hostel facility at Sr. No.'$srNo'";
                            $recordStatus='1';   
						}
					}
				}

                if (empty($gender)) {
                    $inconsistenciesArray[] = "Please mention Gender of Student for selected class at Sr. No.'$srNo'";
                    $recordStatus='1';   
                }

                if(strtolower($gender) !='male') {
                  if(strtolower($gender) !='female') {
                    $inconsistenciesArray[] = "Please mention correct Gender at Sr. No.'$srno'";
                    $recordStatus='1';                       
                  }
                }

                if(!empty($isLeet)) {
                  if(strtolower($isLeet) != 'yes'){
                    if(strtolower($isLeet) != 'no') {
                      $inconsistenciesArray[] = "Invalid data in isLeet at Sr. No.'$srNo'";
                      $recordStatus='1';    
                    }
				  }
				}
				 if(!empty($studentStatus)) {
					if(strtolower($studentStatus) != 'yes'){
						if(strtolower($studentStatus) != 'no') {
							$inconsistenciesArray[] = "Invalid data in student status at Sr. No.'$srNo'";
                            $recordStatus='1';    
						}
					}
                 }
                 if(!empty($fatherCity)){
                    if(empty($fatherState)){
                       $inconsistenciesArray[] = "Please mention the father state at Sr. No.'$srNo'";
                       $recordStatus='1';    
                    }
                    if(empty($fatherCountry)){
                       $inconsistenciesArray[] = "Please mention the father country at Sr. No.'$srNo'";
                       $recordStatus='1';    
                    }
                }

                if(!empty($fatherState)){
                   if(empty($fatherCountry)){
                     $inconsistenciesArray[] = "Please mention the father country at Sr. No.'$srNo'";
                     $recordStatus='1';    
                   }
                }

                if(empty($nationality)) {
                   $inconsistenciesArray[] = "Please mention Nationality of Student for selected class at Sr. No.'$srNo'";
                   $recordStatus='1';    
                }

                if(empty($quota)) {
                   $inconsistenciesArray[] = "Please mention Quota of Student for selected class at Sr. No.'$srNo'";
                   $recordStatus='1';    
                }

                if($dateOfBirth != '') {
                    $cnt = substr_count($dateOfBirth, '.');
                    if($cnt == 0 ) {
                      $inconsistenciesArray[] = "Date of Birth not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
                      $recordStatus='1';    
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
                        $recordStatus='1';       
                    }
                }

                if($dateOfAdmission != '') {
                    $cnt = substr_count($dateOfAdmission, '.');
                    if($cnt == 0 ) {
                        $inconsistenciesArray[] = "Date of Admission not in a 'yyyy.mm.dd' format at Sr. No.'$srNo'";
                        $recordStatus='1';  
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
                        $recordStatus='1';  
                    }
                }

                $dateOfBirthDateArray[] = explode('.',$dateOfBirth);
                $dateOfBirthYear = $dateOfBirthDate[0];
                $dateOfBirthMonth = $dateOfBirthDate[1];
                $dateOfBirthDay = $dateOfBirthDate[2];

                $dateOfAdmissionDateArray[] = explode('.',$dateOfAdmission);

                $dateOfAdmissionYear = $dateOfAdmissionDate[0];
                $dateOfAdmissionMonth = $dateOfAdmissionDate[1];
                $dateOfAdmissionDay = $dateOfAdmissionDate[2];

                  if($dateOfAdmissionYear !='' && $dateOfBirthYear !=''){
					if($dateOfAdmissionYear < $dateOfBirthYear){
						// die(' '.__LINE__.$dateOfAdmissionYear.$dateOfBirthYear);
						$inconsistenciesArray[] = "Date of birth cannot be greater than Date of addmission at Sr. No. '$srNo'";
                        $recordStatus='1';  
                    }
				  }
           


                if ($corrCountry != '') {
                    $conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($corrCountry))."'";
                    $countryArray = $studentManager->getCountry($conditions);
                    $corrCountryId = $countryArray[0]['countryId'];
                    if (empty($corrCountryId)) {
                       $inconsistenciesArray[] = "Correspondence Country is not existed at Sr. No.'$srNo'";
                       $recordStatus='1';  
                    }
                }
                else {
                    $corrCountryId = 'NULL';
                }

                if ($corrState != '' && $corrCountryId != '') {
                    $conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($corrState))."' AND st.countryId = '".$corrCountryId."'";
                    $stateArray = $studentManager->getState($conditions);
                    $corrStateId = $stateArray[0]['stateId'];
                    if (empty($corrStateId)) {
                       $inconsistenciesArray[] = "Correspondence State is not existed at Sr. No.'$srNo'";
                       $recordStatus='1';  
                    }
                }
                else {
                    $corrStateId = 'NULL';
                }


                if ($corrCity != '' && $corrStateId != '') {
                    $conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($corrCity))."' AND ct.stateId = '".$corrStateId."'";
                    $cityArray = $studentManager->getCity($conditions);
                    $corrCityId = $cityArray[0]['cityId'];
                    if (empty($corrCityId)) {
                       $inconsistenciesArray[] = "Correspondence City is not existed at Sr. No.'$srNo'";
                       $recordStatus='1';  
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
                       $recordStatus='1';  
                    }
                }
                else {
                    $permCountryId = 'NULL';
                }

                if ($permState != '' && $permCountryId != '') {
                    $conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($permState))."' AND st.countryId = '".$permCountryId."'";
                    $stateArray = $studentManager->getState($conditions);
                    $permStateId = $stateArray[0]['stateId'];
                    if (empty($permStateId)) {
                       $inconsistenciesArray[] = "Permanent State is not existed at Sr. No.'$srNo'";
                       $recordStatus='1';  
                    }
                }
                else {
                    $permStateId = 'NULL';
                }


                if ($permCity != '' && $permStateId != '') {
                    $conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($permCity))."' AND ct.stateId = '".$permStateId."'";
                    $cityArray = $studentManager->getCity($conditions);
                    $permCityId = $cityArray[0]['cityId'];
                    if (empty($permCityId)) {
                        $inconsistenciesArray[] = "Permanent City is not existed at Sr. No.'$srNo'";
                        $recordStatus='1';  
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
                        $recordStatus='1';  
                    }
                }
                else {
                    $fatherCountryId = 'NULL';
                }

                if($fatherState != '' && $fatherCountry != '')  {
                    $conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($fatherState))."' AND st.countryId = '".$fatherCountryId."'";
                    $stateArray = $studentManager->getState($conditions);
                    $fatherStateId = $stateArray[0]['stateId'];
                    if (empty($fatherStateId)) {
                        $inconsistenciesArray[] = "Father State is not existed corresponding to father country at Sr. No.'$srNo'";
                        $recordStatus='1';  
                    }
                }
                else {
                    $fatherStateId = 'NULL';
                }

                if($fatherCity != '' && $fatherStateId != '') {
                    $conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($fatherCity))."' AND ct.stateId = '".$fatherStateId."'";
                    $cityArray = $studentManager->getCity($conditions);
                    $fatherCityId = $cityArray[0]['cityId'];
                    if (empty($fatherCityId)) {
                        $inconsistenciesArray[] = "Father City is not existed corresponding to father state at Sr. No.'$srNo'";
                        $recordStatus='1';  
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
                        $recordStatus='1';  
                    }
                }
                else {
                    $domicileId = 'NULL';
                }

               if(addslashes(strtolower($isLeet)) == 'yes') {
                 $isLeet = 1;
               }
               else {
                 $isLeet = 0;
               }

               if ($gender != '') {
                    if(addslashes(strtolower($gender)) == 'male') {
                        $gender = 'M';
                    }
                    else {
                        $gender = 'F';
                    }
                }

                if(addslashes(strtolower($studentStatus)) == 'yes') {
                  $studentStatus = 1;
                }
                else {
                  $studentStatus = 0;
                }
               
                
                if(addslashes(strtolower($hostelFacility)) == 'yes') {
                    $hostelFacility = 1;
                }
                else {
                    $hostelFacility = 0;
                }
                
                if(addslashes(strtolower($busFacility)) == 'yes') {
                    $busFacility = 1;
                }
                else {
                    $busFacility = 0;
                }
                


                if($nationality!='') {
                    $conditions = "WHERE LCASE(c.nationalityName) = '".addslashes(strtolower($nationality))."'";
                    $nationalityArray = $studentManager->getCountry($conditions);
                    $nationalityId = $nationalityArray[0]['countryId'];
                    if (empty($nationalityId)) {
                        $inconsistenciesArray[] = "Nationality is not existed at Sr. No.'$srNo'";
                        $recordStatus='1';  
                    }
                }


                if($quota!='') {
                    $conditions = "WHERE LCASE(qt.quotaName) = '".addslashes(strtolower($quota))."'";
                    $quotaArray = $studentManager->getQuota($conditions);
                    $quotaId = $quotaArray[0]['quotaId'];
                    if (empty($quotaId)) {
                       $inconsistenciesArray[] = "Quota is not existed at Sr. No.'$srNo'";
                       $recordStatus='1';  
                    }
                }

                if($recordStatus=='') {
                    if(SystemDatabaseManager::getInstance()->startTransaction()) {    
                        $return = $studentManager->addStudentInfoInTransaction($getClassId,$rollNo,$univRollNo,$isLeet,$firstName,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo);
                        if($return === false) {
                           $inconsistenciesArray[] = "Error while saving student detail Sr. No.'$srNo'";
                        }
                        if(SystemDatabaseManager::getInstance()->commitTransaction()) {   
                          if($return === false) {
                            $inconsistenciesArray[] = "Error while saving student detail Sr. No.'$srNo'";
                          }
                          else {
                            if($totalStudentsAdded!='') {  
                              $totalStudentsAdded .=", ";
                            }
                            $totalStudentsAdded .="$srNo";  
                            
                            $totalClassStudents++;   
                          }
                       } // Transaction committed
                    }
                }
                $recordStatus='';
        } // End For Loop
    } // End for Sheet         
    



    if (count($inconsistenciesArray) == 0) {

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
    else {
        $csvData = '';
        if($totalStudentsAdded!='') {
          $csvData .= "Data for $totalClassStudents Student(s) at following Sr. No. has been uploaded:\n $totalStudentsAdded"; 
          $csvData .= "\n(Note: Next time upload the data after removing the students whose data has been uploaded and correcting the inconsistencies for the remaining students)\n";
          $csvData .= "\nThe reason for data not getting uploaded for remaining students is given against Sr. No. of each.";
        }
        $csvData .= "\n";
        
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
