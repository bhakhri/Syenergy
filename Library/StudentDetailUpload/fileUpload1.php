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
    $addressValid  = $REQUEST_DATA['addressValid'];
    $quotaValid    = $REQUEST_DATA['quotaValid'];
    $domicileValid  = $REQUEST_DATA['domicileValid'];
    
    $ignoreDuplicateRollUniv = $REQUEST_DATA['ignoreDuplicateRollUniv'];
    
    
    $ignoreDuplicateRollUnivSrNo = array();
    $totalExcelSheetRecord='0';
    $totalSaveRecord='0';
 
 
        if($addressValid=='') {
         $addressValid='0';
        }

        if($quotaValid ==''){
           $quotaValid ='0';
          }

      if($domicileValid  =='') {
         $domicileValid  ='0';
         }

    if ($getClassId == '') {
        echo ('<script type="text/javascript">alert("Please select class");</script>');
        die;
    }

   $fontStart = "<font color='red'>";
   $fontEnd = "</font>";
   $fontStart1 = "<font color='green'>";
   $fontEnd1 = "</font>";
   $fontStart2 = "<font color='blue'>";
   $fontEnd2 = "</font>";
   

    $tableHeadArray = array();
    $valueArray= array();
    $showHead='';
    
    function clearSpecialChar($text) {
       if($text!="") {
         $text=strtolower($text);
         $code_entities_match = array(' ');
         $code_entities_replace = array('');
         $text = str_replace($code_entities_match, $code_entities_replace, $text);
       }
       return $text;
    } 
    
    
    if(SystemDatabaseManager::getInstance()->startTransaction()) {    
            foreach($sheetNameArray as $sheetIndex=>$value) {
                $rollNoArray = array();
                $univRollNoArray = array();
                $srNoArray = array();
                $registrationNoArray=array();
                
                if($showHead=='') {
                    for($j = 1; $j<= 43; $j++) {
                       $str = trim($data->sheets[$sheetIndex]['cells'][1][$j]);
                       $tableHeadArray[$j]['headName']=$str;
                       $tableHeadArray[$j]['headName11']=$str;
                       $tableHeadArray[$j]['showError']='N';
                    }
                   $showHead='1';
                }
                
	            for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
                    $isError='';
                    $srNo = trim(clearSpecialChar($data->sheets[$sheetIndex]['cells'][$i][1]));
                    $rollNo = trim(clearSpecialChar($data->sheets[$sheetIndex]['cells'][$i][2]));
                    $univRollNo = trim(clearSpecialChar($data->sheets[$sheetIndex]['cells'][$i][3]));
                    $isLeet = trim($data->sheets[$sheetIndex]['cells'][$i][4]);
                    $firstName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][5]));
                    $lastName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][6]));
                    $fatherName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][7]));
                    $fatherOccupation = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][8]));
                    
                    $fatherMobile = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][9]));
                    $fatherMobile = str_replace(" ","",$fatherMobile);     
                    $fatherMobile = str_replace("-","",$fatherMobile);     
                    $fatherMobile = str_replace("/","",$fatherMobile);     
                    
                    $fatherAddress1 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][10]));
                    $fatherAddress2 = addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][11]));
                 
                    $fatherAddress1 = str_replace("'","`",$fatherAddress1);
                    $fatherAddress1 = str_replace('"',"`",$fatherAddress1);
                    
                    $fatherAddress2 = str_replace("'","`",$fatherAddress2);
                    $fatherAddress2 = str_replace('"',"`",$fatherAddress2);
                    
                    $fatherCountry = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][12]));
                    $fatherState = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][13]));
                    $fatherCity = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][14]));
                    $motherName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][15]));
                    $dateOfBirth = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][16]));
					
				
                    $corrAddress1 = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][17])));
                    $corrAddress2 = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][18])));
                    
                    $corrAddress1 = str_replace("'","`",$corrAddress1);
                    $corrAddress1 = str_replace('"',"`",$corrAddress1);
                    
                    $corrAddress2 = str_replace("'","`",$corrAddress2);
                    $corrAddress2 = str_replace('"',"`",$corrAddress2);
                    
                    $corrPinCode = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][19]));
                    $corrCountry = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][20]));
                    $corrState = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][21]));
                    $corrCity = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][22]));
                    $permAddress1 = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][23])));
                    $permAddress2 = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][24])));
                    
                    $permAddress1 = str_replace("'","`",$permAddress1);
                    $permAddress1 = str_replace('"',"`",$permAddress1);
                    
                    $permAddress2 = str_replace("'","`",$permAddress2);
                    $permAddress2 = str_replace('"',"`",$permAddress2);
                    
                    $permPinCode = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][25]));
                    $permCountry = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][26]));
                    $permState = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][27]));
                    $permCity = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][28]));

                    $studentMobile = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][29]));
                    $studentMobile = str_replace(" ","",$studentMobile);     
                    $studentMobile = str_replace("-","",$studentMobile);     
                    $studentMobile = str_replace("/","",$studentMobile); 
                    
                    $domicile = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][30]));
                    $hostelFacility = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][31]));
                    $busFacility= htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][32]));

                    $correspondencePhone = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][33]));
                    $correspondencePhone = str_replace(" ","",$correspondencePhone);     
                    $correspondencePhone = str_replace("-","",$correspondencePhone);     
                    $correspondencePhone = str_replace("/","",$correspondencePhone); 

                    $permanentPhone = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][34]));
                    $permanentPhone = str_replace(" ","",$permanentPhone);     
                    $permanentPhone = str_replace("-","",$permanentPhone);     
                    $permanentPhone = str_replace("/","",$permanentPhone); 
                                          
                    $studentStatus = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][35])); 
  
                    $gender = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][36]));
					if($gender=='Male'){
						
					$gender='M';	
						
					}else{
					$gender='F';	
					}
					
                    $nationality = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][37]));
                    $quota = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][38]));
                    
                    $contactNo = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][39]));
                    $contactNo = str_replace(" ","",$contactNo);     
                    $contactNo = str_replace("-","",$contactNo);     
                    $contactNo = str_replace("/","",$contactNo);     
                    
                    $emailAddress = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][40])));
                    $emailAddress = str_replace(" ","",$emailAddress);     
                    
                    $alternateEmailAddress = htmlentities(addslashes(trim($data->sheets[$sheetIndex]['cells'][$i][41])));
                    $alternateEmailAddress = str_replace(" ","",$alternateEmailAddress);  
                    
                    $dateOfAdmission = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][42]));
                    $registrationNo = htmlentities(trim(clearSpecialChar($data->sheets[$sheetIndex]['cells'][$i][43])));
                    
                     if($srNo=='') {
                       continue;   
                     } 
       		     $nationalityId='';
                    
               if ($nationality != '') {
                 $conditions = "WHERE LCASE(c.nationalityName) = '".addslashes(strtolower($nationality))."'";
                 $nationalityArray = $studentManager->getCountry($conditions);
                 $nationalityId = $nationalityArray[0]['countryId'];  
               }
                    
                           
                    if(strtolower($studentStatus) == 'yes') {
                       $studentStatus='1'; 
                    }
                    else {
                      $studentStatus='0';  
                    }
                  
                    if(strtolower($busFacility) == 'yes'){
                       $busFacility='1';   
                    }
                    else {
                        $busFacility='0';    
                    }
                    
                    if(strtolower($hostelFacility) == 'yes'){
                       $hostelFacility='1';   
                    }
                    else {
                        $hostelFacility='0';    
                    }
                    
                    if(strtolower($isLeet) == 'yes'){
                      $isLeet='1';   
                    }
                    else {
                      $isLeet='0';    
                    }
                     
                     $totalExcelSheetRecord++; 
                     
                     $continueRecord='';
                     
                     $foundArray=array();
                     $isError=''; 
                     for ($j = 2; $j<= 43; $j++) {
                          $str = trim($data->sheets[$sheetIndex]['cells'][$i][$j]);
                          $foundArray[0][1]= $srNo; 
                          
                          if($j==2 || $j==3 || $j==43) {
                            $str = trim(clearSpecialChar($str));  
                          }
                          $foundArray[0][$j]=$str; 
                          
                          //check for missing values
                          if($str=='' && ($j==2 || $j==3 || $j==5 || $j==7 || $j==16 || $j==36 || $j==42 || $j==43)) {
                             $tableHeadArray[$j]['showError']='Y';      
                             $foundArray[0][$j]='M!~~!'.$str;
                             $isError='1'; 
                          }

                          // Check For Duplicate
                          if($j==2) {
                            if(!in_array($str, $rollNoArray)) {
                              $rollNoArray[]=$str;
                            } 
                            else{
                               if($ignoreDuplicateRollUniv=='') {
                                 $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='D!~~!'.$str;
                                 $isError='1'; 
                               }
                               else {
                                   $continueRecord='1';
                                   break;
                               }
                            }
                          }
                          
                          if($j==3) {
                            if(!in_array($str, $univRollNoArray)) {
			                  $univRollNoArray[]=$str;
	  	                    }
 		                    else{
		                      $tableHeadArray[$j]['showError']='Y';      
                              $foundArray[0][$j]='D!~~!'.$str;
                              $isError='1'; 
		                    }
		                  }
                          
                          if($j==43) {
		                      if(!in_array($str, $registrationNoArray)) {
                                $registrationNoArray[]=$str;
		                      }
 		                      else{
		                        $tableHeadArray[$j]['showError']='Y';      
                                         $foundArray[0][$j]='D!~~!'.$str;
                                         $isError='1'; 
		                      }
		                  }
                          if($str!='' && $j==2){
                             $conditions =  " WHERE LCASE(rollNo) = '".addslashes(strtolower($str))."'";
                             $countRollno =  $studentManager->getStudent_Delete($conditions);
                             if($ignoreDuplicateRollUniv=='') {  
                               if ($countRollno[0]['totalRecords'] != 0) {
                                $tableHeadArray[$j]['showError']='Y';      
                                $foundArray[0][$j]='D!~~!'.$str;
                                $isError='1';
                               }
                            }
                            else {
                                   $continueRecord='1';
                                   break;
                            }
                          }
                          
                          if ($str!='' && $j==3)  {
                             $conditions =  " WHERE LCASE(universityRollNo) = '".addslashes(strtolower($str))."'";
                             $countUnivRollno =  $studentManager->getStudent_Delete($conditions);
                             if($ignoreDuplicateRollUniv=='') {  
                                 if ($countUnivRollno[0]['totalRecords'] != 0) {
                                   $tableHeadArray[$j]['showError']='Y';      
                                   $foundArray[0][$j]='D!~~!'.$str;
                                   $isError='1';  
                                 }
                             }
                             else {
                                   $continueRecord='1';
                                   break;
                             }
                          }	
                          
                          if ($str!='' && $j==43)  {
                             $conditions =  " WHERE LCASE(regNo) = '".addslashes(strtolower($str))."'";
                             $countUnivRollno =  $studentManager->getStudent_Delete($conditions);
                             if($ignoreDuplicateRollUniv=='') {  
                                 if ($countUnivRollno[0]['totalRecords'] != 0) {
                                   $tableHeadArray[$j]['showError']='Y';      
                                   $foundArray[0][$j]='D!~~!'.$str;
                                   $isError='1';  
                                 }
                             }
                             else {
                                   $continueRecord='1';
                                   break;
                             }
                          }    

                       // Check for MisMatchFormat
                       //check for valid email 
                       $pattern = "^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9]+(\.[a-z0-9]+)*(\.[a-z]{2,3})$";
                       if($j==40) {
                         if(!empty($str)) {
                            if (!eregi($pattern,$str)){
                               $tableHeadArray[$j]['showError']='Y';      
                               $foundArray[0][$j]='MS!~~!'.$str;
                               $isError='1'; 
                            }
                         }
                       }
                       if($j==41){
                         if(!empty($str)) {
                           if (!eregi($pattern,$str)){
                             $tableHeadArray[$j]['showError']='Y';      
                             $foundArray[0][$j]='MS!~~!'.$str;
                             $isError='1'; 
                           }
                         }
	                   }
                 		
                     //check for date of birth  
                     if($j==16 && $dateOfBirth !='0000.00.00'){
                        if($dateOfBirth != '') {
                           $cnt = substr_count($dateOfBirth, '.');
                           if($cnt == 0 ) {
                             $dateOfBirth = $fontStart1.$dateOfBirth.$fontEnd1;
                              $isError='1'; 
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
                              $tableHeadArray[$j]['showError']='Y';      
                              $foundArray[0][$j]='MS!~~!'.$str;
                              $isError='1';    
                           }
                        }
                     }
                    
                    //check for date of addmission
                    if($j==42 && $dateOfAdmission !='0000.00.00' ){
                        if($dateOfAdmission != '') {
                            $cnt = substr_count($dateOfAdmission, '.');
                            if($cnt == 0 ) {
                                $str = $fontStart1.$dateOfAdmission.$fontEnd1;
                               $isError='1';
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
                              $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                        }
                    }
                    
                    
                    
                  
                    //check for correct country
                    if($j==20){
                        if ($corrCountry != '') {
                            $conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($corrCountry))."'";
                            $countryArray = $studentManager->getCountry($conditions);
                            $corrCountryId = $countryArray[0]['countryId'];
                       if($addressValid=='0') {	
                            if (empty($corrCountryId)) {
                                 $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                        }
                        else  if($addressValid=='1') {
                               if (empty($corrCountryId)) {
		                 $corrCountryId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $corrCountryId = 'NULL';
                        }
                    }
              //check for correct state
                    if($j==21){
                        if ($corrState != '' && $corrCountryId != '') {
                         $conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($corrState))."' AND st.countryId = '".$corrCountryId."'";
                            $stateArray = $studentManager->getState($conditions);
                            $corrStateId = $stateArray[0]['stateId'];
                        if($addressValid=='0') {	
                            if (empty($corrStateId)) {
                               $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                        }
                   else  if($addressValid=='1') {
                               if (empty($corrStateId)) {
		                 $corrStateId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $corrStateId = 'NULL';
                        }
                    }
                    //check for correct city
                    if($j==22){
                        if ($corrCity != '' && $corrStateId != '') {
                            $conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($corrCity))."' AND ct.stateId = '".$corrStateId."'";
                            $cityArray = $studentManager->getCity($conditions);
                            $corrCityId = $cityArray[0]['cityId'];
                        if($addressValid=='0') {
                            if (empty($corrCityId)) {
                                $tableHeadArray[$j]['showError']='Y';      
                                $foundArray[0][$j]='MS!~~!'.$str;
                                $isError='1';
                            }
                        }
                     else  if($addressValid=='1') {
                               if (empty($corrCityId)) {
		                 $corrCityId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $corrCityId = 'NULL';
                        }
                    }
                   //check for perm country
                    if($j==26){
                        if ($permCountry != '') {
                            $conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($permCountry))."'";
                            $countryArray = $studentManager->getCountry($conditions);
                            $permCountryId = $countryArray[0]['countryId'];
                         if($addressValid=='0'){
                            if (empty($permCountryId)) {
                                 $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                        }
                     else  if($addressValid=='1') {
                               if (empty($permCountryId)) {
		                 $permCountryId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $permCountryId = 'NULL';
                        }
                    }
                  //check for perm state
                    if($j==27){
                        if ($permState != '' && $permCountryId != '') {
                            $conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($permState))."' AND st.countryId = '".$permCountryId."'";
                            $stateArray = $studentManager->getState($conditions);
                            $permStateId = $stateArray[0]['stateId'];
                         if($addressValid=='0') {
                            if (empty($permStateId)) {
                                $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                        }
                    else  if($addressValid=='1') {
                               if (empty($permStateId)) {
		                 $permStateId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $permStateId = 'NULL';
                        }
                    }
                    //check for perm city
                    if($j==28){
                        if ($permCity != '' && $permStateId != '') {
                            $conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($permCity))."' AND ct.stateId = '".$permStateId."'";
                            $cityArray = $studentManager->getCity($conditions);
                            $permCityId = $cityArray[0]['cityId'];
                     if($addressValid=='0') {
                            if (empty($permCityId)) {
                                $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                        }
                       else  if($addressValid=='1') {
                               if (empty($permCityId)) {
		                 $permCityId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $permCityId = 'NULL';
                        }
                    }
                    //check for father country
                    if($j==12){
                        if($fatherCountry != '') {
                            $conditions = "WHERE LCASE(c.countryName) = '".addslashes(strtolower($fatherCountry))."'";
                            $countryArray = $studentManager->getCountry($conditions);
                            $fatherCountryId = $countryArray[0]['countryId'];
                         if($addressValid=='0') {
                            if (empty($fatherCountryId)) {
                                 $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                        }
                      else  if($addressValid=='1') {
                               if (empty($fatherCountryId)) {
		                  $fatherCountryId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $fatherCountryId = 'NULL';
                        }
                    }
                    //check for father state
                    if($j==13){
                            if($fatherState != '' && $fatherCountry != '')  {
              $conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($fatherState))."' AND st.countryId = '".$fatherCountryId."'";
              $stateArray = $studentManager->getState($conditions);
              $fatherStateId = $stateArray[0]['stateId'];
                           if($addressValid=='0') {
                                if (empty($fatherStateId)) {
                                     $tableHeadArray[$j]['showError']='Y';      
                                     $foundArray[0][$j]='MS!~~!'.$str;
                                     $isError='1';
                                }
                        }
                        else  if($addressValid=='1') {
                               if (empty($fatherStateId)) {
		                  $fatherStateId = 'NULL';	
                               } 
		            }	
                      }
                            else {
                                $fatherStateId = 'NULL';
                            }
                    }
                    //check for father city
                    if($j==14){
                        if($fatherCity != '' && $fatherStateId != '') {
                            $conditions = "WHERE LCASE(ct.cityName) = '".addslashes(strtolower($fatherCity))."' AND ct.stateId = '".$fatherStateId."'";
                            $cityArray = $studentManager->getCity($conditions);
                            $fatherCityId = $cityArray[0]['cityId'];
                         if($addressValid=='0') {
                            if (empty($fatherCityId)) {
                                 $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                    }
                      else  if($addressValid=='1') {
                              if (empty($fatherCityId)) {
		                  $fatherCityId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $fatherCityId = 'NULL';
                     }
                 }
                    //check for domicile 
                    if($j==30){
                        if($domicile != '') {
                            $conditions = "WHERE LCASE(st.stateName) = '".addslashes(strtolower($domicile))."'";
                            $stateArray = $studentManager->getState($conditions);
                            $domicileId = $stateArray[0]['stateId'];
                         if($domicileValid=='0') {
                            if (empty($domicileId)) {
                                 $tableHeadArray[$j]['showError']='Y';      
                                 $foundArray[0][$j]='MS!~~!'.$str;
                                 $isError='1';
                            }
                     }
                        else  if($domicileValid=='1') {
                              if (empty($domicileId)) {
		                  $domicileId = 'NULL';	
                               } 
		            }	
                      }
                        else {
                            $domicileId = 'NULL';
                        }
                    }
                    //check for contact no 
                    if($j==39){
                    $contactNo = str_replace(" ","",$contactNo);
                    $contactNo = str_replace("-","",$contactNo);
                    if(!empty($contactNo)) {
                    if(ereg('[^0-9,-]', $contactNo))  {
	                     $tableHeadArray[$j]['showError']='Y';      
                                     $foundArray[0][$j]='MS!~~!'.$str;
                                     $isError='1';
			                    }
		                    }

                       }
                    //check for father mobile
                     if($j==9){
                      $fatherMobile = str_replace(" ","",$fatherMobile);
                      $fatherMobile = str_replace("-","",$fatherMobile);
                      $fatherMobile = str_replace("_","",$fatherMobile);
                     if(!empty($fatherMobile)) {
                    if(ereg('[^0-9,]',$fatherMobile))  {
	                      $tableHeadArray[$j]['showError']='Y';      
                                      $foundArray[0][$j]='MS!~~!'.$str;
                                       $isError='1';
                       }
                          }
                    }
                    //check for student mobile
                    if($j==29){
                      $studentMobile = str_replace(" ","",$studentMobile);
                      $studentMobile = str_replace("-","",$studentMobile);
                      $studentMobile = str_replace("_","",$studentMobile);
                      if(!empty($studentMobile)) {
	                    if(ereg('[^0-9,]',$studentMobile))  {
		                     $tableHeadArray[$j]['showError']='Y';      
                                         $foundArray[0][$j]='MS!~~!'.$str;
                                         $isError='1';
                                         }
                                  }
                          }

                    //check for correspodence phone
                    if($j==33){
                      $correspondencePhone = str_replace(" ","",$correspondencePhone);
                      $correspondencePhone = str_replace("-","",$correspondencePhone);
                           if(!empty($correspondencePhone)) {
	                    if(ereg('[^0-9,-]',$correspondencePhone))  {
		                           $tableHeadArray[$j]['showError']='Y';      
                                           $foundArray[0][$j]='MS!~~!'.$str;
                                           $isError='1';
			                    }
	                    }

                    }
                    //check for permanent phone no
                    if($j==34){
                      $permanentPhone = str_replace(" ","",$permanentPhone);
                      $permanentPhone = str_replace("-","",$permanentPhone);
                      if(!empty($permanentPhone)) {
	                    if(ereg('[^0-9,-]',$permanentPhone))  {
		                         $tableHeadArray[$j]['showError']='Y';      
                                         $foundArray[0][$j]='MS!~~!'.$str;
                                         $isError='1';
	                      }
                    }
                    }   
                //check for quota
                  if($j==38){
                      if($quota!='') {
                        $conditions = "WHERE LCASE(qt.quotaName) = '".addslashes(strtolower($quota))."'";
                        $quotaArray = $studentManager->getQuota($conditions);
                        $quotaId = $quotaArray[0]['quotaId'];
                if($quotaValid=='0'){
			if (empty($quotaId)) {
				     $tableHeadArray[$j]['showError']='Y';      
                                     $foundArray[0][$j]='MS!~~!'.$str;
                                     $isError='1';
					}
		       }
                     else if($quotaValid=='1') {
                              if (empty($quotaId)) {
                            $quotaId  = 'NULL';
                              }
                         }
                   }
                     else{
                        $quotaId = 'NULL';
                       }
                }  
        }  
        
           
        
        if($continueRecord=='1') {
          $ignoreDuplicateRollUnivSrNo[] = $i;    
          continue;
        }
		
             	 	//ADD SaLLCLASS IN STUDENT TABLE
			 $studentClassStatus = $studentManager->updateStudentAllClasses($getClassId);
			 if($studentClassStatus===false){
               $sessionHandler->setSessionVariable('ErrorMsg',FAILURE);  
               echo FAILURE;
            } 
			 $allClass ='';
			 for($xx=0;$xx<count($studentClassStatus);$xx++){			 
			 if($allClass !=''){
			 	$allClass .='~';				
			 }	
				$allClass .=$studentClassStatus[$xx]['classId'];	
			 }
            $sAllClass ="~".$allClass."~";	 
 
                   
                 if($isError=='1') {
                    $valueArray[] = array_merge(array($foundArray[0]));    
                 }else if($isError=='') {
  				$returnStatus = $studentManager->addStudentInfoInTransaction($getClassId,$rollNo,$univRollNo,$isLeet,$firstName,$lastName,$fatherName,$fatherOccupation,$fatherMobile,$fatherAddress1,$fatherAddress2,$fatherCountryId,$fatherStateId,$fatherCityId,$motherName,$dateOfBirth,$corrAddress1,$corrAddress2,$corrPinCode,$corrCountryId,$corrStateId,$corrCityId,$permAddress1,$permAddress2,$permPinCode,$permCountryId,$permStateId,$permCityId,$studentMobile,$domicileId,$hostelFacility,$busFacility,$correspondencePhone,$permanentPhone,$studentStatus,$gender,$nationalityId,$quotaId,$contactNo,$emailAddress,$alternateEmailAddress,$dateOfAdmission,$registrationNo,$sAllClass); 
                  
                    if($returnStatus===false) {
                      echo FAILURE;
                      die;
                    }
                    $totalSaveRecord++;
                 }
              }
         } 
         
        if(count($valueArray) >0 ) {
         
            // Table Head
            $tableRow ='';
            $tableData .= "<br><b>Following Data inconsistent please check color coding</b><br>";
            $tableData .= "<table width='100%' border='0' cellspacing='2px' cellpadding='0px'>";
            $tableData .= "<tr class='rowheading'>";
            for($i=1;$i<=count($tableHeadArray);$i++) {
               if($tableHeadArray[$i]['showError']=='Y') {
                  $str = $tableHeadArray[$i]['headName'];      
                  $tableRow .= "<td width='2px' align='left'>$str</td>";           
               }   
            }
            if($tableRow!='') {   
              $tableData .="<td width='2px' align='left'>Sr. No.</td>";
            }
            $tableData .= $tableRow;
            $tableData .= "</tr>";  
            
                // Table Error Rows Print
                if($tableRow!='') {
                   for($j=0;$j<count($valueArray);$j++) {
                       $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                       $tableData .= "<tr class='$bg'>"; 
                       $str  = $valueArray[$j][0][1];
                       $tableData .= "<td width='2px' align='left'>$str</td>";      
                       for($i=2;$i<=43;$i++) {
                          if($tableHeadArray[$i]['showError']=='Y') { 
                            $str  = $valueArray[$j][0][$i];
                            $ret=explode('!~~!',$str);
                            $str1='';
                            if($ret[0]=='M') {
                              $str1 = $fontStart."Missing".$fontEnd;  
                            }
                            else if($ret[0]=='D') {
                              $str1 = $fontStart2.$ret[1].$fontEnd2;  
                            }
                            else if($ret[0]=='MS') {
                              $str1 = $fontStart1.$ret[1].$fontEnd1;  
                            }
                            if($str1=='') {
                              $tableData .= "<td width='2px' align='left'>".NOT_APPLICABLE_STRING."</td>";      
                            }
                            else {
                               $tableData .= "<td width='2px' align='left'>$str1</td>";        
                            }
                          }       
                       }
                       $tableData .= "</tr>"; 
                   }
                }
                $tableData .= '</table>';
                
                
                // On Screen Error Message ==> Report
                if($tableRow != '') {
                   echo '<script> parent.getShowTableData("'.$tableData.'"); </script>';        
                }
                die;
            }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {   
                   
                   // Table Head
                   $tableRow  ='';
                   $tableData  = "<br><b>Following Data uploaded</b><br>";  
                   $tableData .= "Total Record: ".$totalExcelSheetRecord."<br>";
                   $tableData .= "Save Record: ".$totalSaveRecord."<br>";
                   if(count($ignoreDuplicateRollUnivSrNo) > 0) {
                       $tableData .= "<br>Following records are not saved because they are duplicate:<br> ";
                       $tableData .= "<table width='100%' border='0' cellspacing='2px' cellpadding='0px'>";
                       $tableData .= "<tr class='rowheading'>";
                           $tableData .= "<td width='2px' align='left'>Sr. No.</td>";           
                           $tableData .= "<td width='2px' align='left'>Roll No.</td>";           
                           $tableData .= "<td width='2px' align='left'>Univ Roll No.</td>";           
                           $tableData .= "<td width='2px' align='left'>Reg No.</td>";           
                           $tableData .= "<td width='2px' align='left'>Student Name</td>";
                       $tableData .= "</tr>";
                       for($i=0;$i<count($ignoreDuplicateRollUnivSrNo);$i++) {
                            $j = $ignoreDuplicateRollUnivSrNo[$i];
                            $srNo = trim($data->sheets[0]['cells'][$j][1]);
                            $rollNo = trim($data->sheets[0]['cells'][$j][2]);
                            $univRollNo = trim($data->sheets[0]['cells'][$j][3]);
                            $regNo = trim($data->sheets[0]['cells'][$j][43]);  
                            $firstName = trim($data->sheets[0]['cells'][$j][5]);
                            $lastName = trim($data->sheets[0]['cells'][$j][6]);
                            $studentName = $firstName." ".$lastName;
                            $tableData .= "<tr>";
                            $tableData .= "<td width='2px' align='left'>".$srNo."</td>";   
                            $tableData .= "<td width='2px' align='left'>".$rollNo."</td>";   
                            $tableData .= "<td width='2px' align='left'>".$univRollNo."</td>";   
                            $tableData .= "<td width='2px' align='left'>".$regNo."</td>";  
                            $tableData .= "<td width='2px' align='left'>".$studentName."</td>";   
                            $tableData .= "</tr>";
                       }
                       $tableData .= "</table>";
                   }
                   echo '<script> parent.getShowTableData("'.$tableData.'"); </script>';       
 //echo "parent.document.getElementById('confirmResultDiv').innerHTML =parent.document.getElementById('confirmResultDiv').innerHTML+\"".$tableData."\"; "; 
//      echo "  parent.confirmMessageWindow(); </script>";
    }
    }
     die;     
?>
