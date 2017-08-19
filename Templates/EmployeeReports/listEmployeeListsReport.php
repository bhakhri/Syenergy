<?php 
//This file creates output for "ListEmployeeReports " Module and provides the option for "export to CSV" and "Printout"
//
// Author :Arvind Singh Rawat
// Created on : 8-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	 $countHeader=count($REQUEST_DATA);
	 $countHeader= $countHeader-13;
 
?>

    <?php
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
   // require_once(MODEL_PATH . "/ScEmployeeReportsManager.inc.php");
   // $employeeReportManager = EmployeeReportsManager::getInstance();  
   $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);             
    $employeeCode = $REQUEST_DATA['employeeCode'];
    if (!empty($employeeCode)) {
        $conditionsArray[] = " a.employeeCode LIKE '$employeeCode%' ";
        $qryString.= "&employeeCode=$employeeCode";
        $searchCrieria .="<b>Employee Code</b>:$employeeCode&nbsp;";
    }

    //Student Name
    $employeeName = $REQUEST_DATA['employeeName'];
    if (!empty($employeeName)) {
        $conditionsArray[] = " a.employeeName LIKE '$employeeName%' ";
        $qryString.= "&employeeName=$employeeName";
        $searchCrieria .="<b>Employee Name</b>:$employeeName&nbsp;";
    }

    //Student Gender
    $gender = $REQUEST_DATA['genderRadio'];
    if (!empty($gender)) {
        $conditionsArray[] = " a.gender = '$gender' ";
        $qryString .= "&gender=$gender";
        $gender1 = $gender=='M' ? "Male" : "Female";

        $searchCrieria .="<b>Gender</b>:$gender1&nbsp;";
    }

	$isMarried = $REQUEST_DATA['isMarried'];
    if (!empty($isMarried)) {
        $conditionsArray[] = " a.isMarried = '$isMarried' ";
        $qryString .= "&isMarried=$isMarried";
		$isMarried1 = $isMarried=='1' ? "Married" : "Unmarried";
        $searchCrieria .="<b>Marital Status</b>:$isMarried1&nbsp;";
    }

	$teachEmployee = $REQUEST_DATA['teachEmployee'];
    if (!empty($teachEmployee)) {
        $conditionsArray[] = " a.isTeaching = '$teachEmployee' ";
        $qryString .= "&teachEmployee=$teachEmployee";
		$teachEmployee1 = $teachEmployee=='1' ? "Yes" : "No";
        $searchCrieria .="<b>Teaching Employee</b>:$teachEmployee1&nbsp;";
    }
    
    $birthDateF = $REQUEST_DATA['birthDateF']; 
    $birthDateT = $REQUEST_DATA['birthDateT']; 
    
    $joiningDateF = $REQUEST_DATA['joiningDateF']; 
    $joiningDateT = $REQUEST_DATA['joiningDateT']; 
    
    $leavingDateF = $REQUEST_DATA['leavingDateF']; 
    $leavingDateT = $REQUEST_DATA['leavingDateT']; 
    
    if($birthDateF!='') {
      $dtArray = explode('-',$birthDateF);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($birthDateF);
        $searchCrieria .="<b>Birth Date  From</b>:$thisDate&nbsp;"; 
      }
    }
    
    if($birthDateT!='') {
      $dtArray = explode('-',$birthDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($birthDateT);
        $searchCrieria .="<b>Birth Date To</b>:$thisDate&nbsp;"; 
      }
    }
    
    if($joiningDateF!='') {
      $dtArray = explode('-',$joiningDateF);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($joiningDateF);
        $searchCrieria .="<b>Date Of Joining From</b>:$thisDate&nbsp;"; 
      }
    }
    
    if($joiningDateT!='') {
      $dtArray = explode('-',$joiningDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($joiningDateT);
        $searchCrieria .="<b>Date Of Joining To</b>:$thisDate&nbsp;"; 
      }
    }
    
     if($leavingDateF!='') {
      $dtArray = explode('-',$leavingDateF);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($leavingDateF);
        $searchCrieria .="<b>Date Of Leaving From</b>:$thisDate&nbsp;"; 
      }
    }
    
    if($leavingDateT!='') {
      $dtArray = explode('-',$leavingDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($leavingDateT);
        $searchCrieria .="<b>Date Of Leaving To</b>:$thisDate&nbsp;"; 
      }
    }
    
    /*
    //From Date of birth
    $birthDateF = $REQUEST_DATA['birthDateF'];
    $birthMonthF = $REQUEST_DATA['birthMonthF'];
    $birthYearF = $REQUEST_DATA['birthYearF'];

    if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {

        if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
            $thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $thisDate1 = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $conditionsArray[] = " e.dateOfBirth >= '$thisDate' ";
        }
        $qryString.= "&birthDateF=$birthDateF&birthMonthF=$birthMonthF&birthYearF=$birthYearF";
        $searchCrieria .="<b>Date Of Birth</b>:$thisDate";
    }

    //To Date of birth
    $birthDateT = $REQUEST_DATA['birthDateT'];
    $birthMonthT = $REQUEST_DATA['birthMonthT'];
    $birthYearT = $REQUEST_DATA['birthYearT'];
    if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {

        if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
            $thisDate = $birthYearT.'-'.$birthMonthT.'-'.$birthDateT;
            $conditionsArray[] = " e.dateOfBirth <= '$thisDate' ";
        }
        $qryString.= "&birthDateT=$birthDateT&birthMonthT=$birthMonthT&birthYearT=$birthYearT";
         
        if($thisDate1)
            $toDate = " To: ";
        else
            $toDate ="";
        $searchCrieria .="<b>$toDate</b>$thisDate";
    }
    */

    //city
    $city = $REQUEST_DATA['citys'];

    $cityText = $REQUEST_DATA['citysText'];
    if (!empty($city)) {
        $conditionsArray[] = " (a.cityId IN ($city)) ";
        $qryString .= "&cityId=$city";
        $searchCrieria .="&nbsp;<b>City:</b>$cityText";
    }
  

    //states
    $state = $REQUEST_DATA['state'];
    $stateText = $REQUEST_DATA['stateText'];
    if (!empty($state)) {
        $conditionsArray[] = " (a.corrStateId IN ($state) OR a.permStateId IN ($state)) ";
        $qryString .= "&stateId=$state";
        $searchCrieria .="&nbsp;<b>State:</b>$stateText";
    }
    

    //country
    $country = $REQUEST_DATA['country'];
    $countryText = $REQUEST_DATA['countryText'];
    if (!empty($country)) {
        $conditionsArray[] = " (a.corrCountryId IN ($country) OR a.permCountryId IN ($country)) ";
        $qryString .= "&countryId=$country";
        $searchCrieria .="&nbsp;<b>Country:</b>$countryText";
    }
    
//qualification
    $qualification = $REQUEST_DATA['qualification'];
    if (!empty($qualification)) {
        $conditionsArray[] = " a.qualification = '$qualification' ";
        $qryString .= "&qualification=$qualification";
        $searchCrieria .="<b>Qualification</b>:$qualification&nbsp;";
    }


    //degree
    $designation = $REQUEST_DATA['designation'];
    $designationText = $REQUEST_DATA['designationText'];
    if (!empty($designation)) {
        $conditionsArray[] = " a.designationId in ($designation) ";
        $qryString.= "&designationId=$designation";
        $searchCrieria .="&nbsp;<b>Designation:</b>$designationText";
    }
    
    /*
	 //insitute
    $institute = $REQUEST_DATA['institute'];
    $instituteText = $REQUEST_DATA['instituteText'];
    if (!empty($institute)) {
        $conditionsArray[] = " (a.instituteId IN ($institute)) ";
        $qryString .= "&instituteId=$institute";
    }
    $searchCrieria .="&nbsp;<b>Institute:</b>$instituteText";*/
	
    
     //Department
    $department = $REQUEST_DATA['department'];
    $departmentText = $REQUEST_DATA['departmentText'];
    if (!empty($department)) {
        $conditionsArray[] = " (a.department IN ($department) ";
        $qryString .= "&departmentId=$department";
        $searchCrieria .=" &nbsp;<b>Department: </b>$departmentText";
    }

//Role Name
    $roleName = $REQUEST_DATA['roleName'];
    $roleText = $REQUEST_DATA['roleText'];
    
    if (!empty($roleName)) {
        $conditionsArray[] = " (a.roleName IN ($roleName) ";
        $qryString .= "&roleName=$roleName";
        $searchCrieria .="&nbsp;<b>Role Name:</b> $roleText";  
    }
    
    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }

    $valueArray = array();
	
/*    for($i=0;$i<$cnt;$i++) {
		
        // add stateId in actionId to populate edit/delete icons in User Interface  
		if(trim($reportRecordArray[$i]['Photo'])=='Photo'){
		echo "hello";
		}
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$reportRecordArray[$i]);
   }
   print_r($valueArray[0]); */
    //print_r($reportRecordArray);
    $cnter=0;      
    $countRows = count($reportRecordArray);
    $reportTableHead    =    array();         
        if($countRows>0) {
                 $reportTableHead['srNo']                =    array('#',                    'width="2%" align="left" ', "align='left' "); 
            foreach($reportRecordArray[0] AS $listRecords => $listRecordvalue){
              		
					if($listRecords=='Photo'){
					$cnter++;
					$reportTableHead[$listRecords]                =    array($listRecords,  'width="4%"  align="center" ', "align='center' ");
			        }
					elseif($listRecords=='employeeId'){
					}
					elseif($listRecords=='DOB'){
						$cnter++;
					    $reportTableHead[$listRecords]           =    array('Date of Birth',  'width="4%"  align="center" ', "align='center' ");
					}
					elseif($listRecords=='DOJ'){
						$cnter++;
					    $reportTableHead[$listRecords]           =    array('Date of Joining',  'width="4%"  align="center" ', "align='center' ");
					}
					elseif($listRecords=='DOL'){
						$cnter++;
					    $reportTableHead[$listRecords]           =    array('Date of Leaving',  'width="4%"  align="center" ', "align='center' ");
					}
					else{
					    $cnter++;
					    $reportTableHead[$listRecords]           =    array($listRecords,  'width="4%"  align="left" ', "align='left' ");
			        }
					
            }  
           
        for($i=0;$i<$countRows;$i++)
        {
            // add stateId in actionId to populate edit/delete icons in User Interface   
			if(trim($reportRecordArray[$i]['Photo'])!=''){
				$reportRecordArray[$i]['Photo']="<img src=\"".EMPLOYEE_PHOTO_PATH."/".stripslashes($reportRecordArray[$i]['Photo'])."\" height=\"64px\" width=\"64px\" valign=\"middle\" >";
			}
			if($reportRecordArray[$i]['DOB']=='0000-00-00') {
               $reportRecordArray[$i]['DOB']="<center>".NOT_APPLICABLE_STRING."</center>";
            }
            else {
               $reportRecordArray[$i]['DOB']="<center>".UtilityManager::formatDate($reportRecordArray[$i]['DOB'])."</center>";
            }

			if($reportRecordArray[$i]['DOJ']=='0000-00-00') {
               $reportRecordArray[$i]['DOJ']="<center>".NOT_APPLICABLE_STRING."</center>";
            }
            else {
               $reportRecordArray[$i]['DOJ']="<center>".UtilityManager::formatDate($reportRecordArray[$i]['DOJ'])."</center>";
            }

			if($reportRecordArray[$i]['DOL']=='0000-00-00') {
               $reportRecordArray[$i]['DOL']="<center>".NOT_APPLICABLE_STRING."</center>";
            }
            else {
               $reportRecordArray[$i]['DOL']="<center>".UtilityManager::formatDate($reportRecordArray[$i]['DOL'])."</center>";
            }

			$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$reportRecordArray[$i]);
        }
           if(isset($REQUEST_DATA['sectionId'])){
			$z=-1;
			$oldId="";
			$chk=0;
			for($i=0;$i<$countRows;$i++){
				if($oldId == $valueArray[$i]['employeeId']){
					$chk++;
					if($chk>1){
						//$z=$z-1;
					}
					$newValueArray[$z]['Section'].= ",".$valueArray[$i]['Section'];
				}
				else{	
					$z++;
					$chk=0;	
						foreach($valueArray[$i] AS $head => $value){
							$serialNo=$z+1;
							if($head == "employeeId"){
								
							}
							elseif($head==trim("srNo")){
								$newValueArray[$z]['srNo']=$serialNo;
							}
							else{
								$newValueArray[$z][$head]=$value;
							}//echo $value;
						}
						
					}
				$oldId = $valueArray[$i]['employeeId'];
              }
	  	}
     }
	
//	$classId=$classIdArray[0]['classId'];  
//    $classNameArray = $employeeReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "where classId  = $classId");
//    $className = $classNameArray[0]['className'];
//    $className2 = str_replace(CLASS_SEPRATOR,' ',$className); 
    $width=800;  
        
     if($cnter == 1 || $cnter==2){
          $width=500;
     } 
     if($cnter ==3 || $cnter==4){
          $width=600;
     }   
    $reportManager->setReportWidth($width);
    $reportManager->setReportHeading('Employee Lists Report');
    
    $search='';
    if($searchCrieria!='') {
      $search = "For ".$searchCrieria." <br>";  
    }
    $reportManager->setReportInformation($search ."As On $formattedDate ");

    $reportManager->setRecordsPerPage(20);
    if(isset($REQUEST_DATA['sectionId'])){
		$reportManager->setReportData($reportTableHead, $newValueArray);
	}
	else{
		$reportManager->setReportData($reportTableHead, $valueArray);
	}
	$reportManager->showReport();    
    ?>
    




	
	

