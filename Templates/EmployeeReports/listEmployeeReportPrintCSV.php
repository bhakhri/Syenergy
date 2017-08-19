<?php 
//This file creates output for "ListEmployeeReports " Module and provides the option for "export to CSV" and "Printout"
//
//--------------------------------------------------------

    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    $valueArray = array();

//--------------------------------------------------------       
//Purpose:To escape any newline or comma present in data
//Author: Dipanjan Bhattacharee
//Date: 31.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------   
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
}

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
        $genName=" OR CONCAT(IFNULL(a.firstName,''), ' ',IFNULL(a.lastName,'')) LIKE '".$genName."%'";
    }  
  
    return $genName;
    }

//code
      if (!empty($employeeCode)) {
        $conditionsArray[] = " b.employeeCode in ($employeeCode) ";
        $qryString.= "&employeeCode=$employeeCode";
          $searchCrieria .=" Employee Code:$employeeCode";
}
   //Name
    $employeeName = $REQUEST_DATA['employeeName'];
    if (!empty($employeeName)) {

         
        //$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
        $parsedName=parseName(trim($employeeName));    //parse the name for compatibality
        $conditionsArray[] = " (
                                  CONCAT(IFNULL(a.firstName,''), ' ',IFNULL(a.lastName,'')) LIKE '%$employeeName%' 
                                  $parsedName
                               )";
        $qryString.= "&employeeName=$employeeName";
        $searchCrieria .="Employee Name:$employeeName ";
    }
//Student Gender
   $gender = $REQUEST_DATA['genderRadio'];
    if (!empty($gender)) {
        $conditionsArray[] = " a.gender = '$gender' ";
        $qryString .= "&gender=$gender";
        $gender1 = $gender=='M' ? "Male" : "Female";

        $searchCrieria .="Gender:$gender1 ";
    }
//Role Name
    $roleName = $REQUEST_DATA['roleName'];
    $roleText = $REQUEST_DATA['roleText'];
    if (!empty($roleName)) {
        $conditionsArray[] = " (a.roleName IN ($roleName) ";
        $qryString .= "&roleName=$roleName";
	 $searchCrieria .=" Role Name: $roleText";
    }
   
    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
	//From Date of birth
    /*
	$birthDateF = $REQUEST_DATA['birthDateF'];
	$birthMonthF = $REQUEST_DATA['birthMonthF'];
	$birthYearF = $REQUEST_DATA['birthYearF'];

	if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {

		if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
			$thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
			$conditionsArray[] = " e.dateOfBirth >= '$thisDate' ";
		}
		$qryString.= "&birthDateF=$birthDateF&birthMonthF=$birthMonthF&birthYearF=$birthYearF";
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
	}

    */


//department
    $department = $REQUEST_DATA['department'];
    $departmentText = $REQUEST_DATA['departmentText'];
    if (!empty($department)) {
        $conditionsArray[] = " (a.department IN ($department) ";
        $qryString .= "&departmentId=$department";
        $searchCrieria .=" Department: $departmentText";
    }
    
    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
 //designation
    $designation = $REQUEST_DATA['designation'];
    $designationText = $REQUEST_DATA['designationText'];
    if (!empty($designation)) {
        $conditionsArray[] = " (a.designation IN ($designation) ";
        $qryString .= "&designationId=$designation";
         $searchCrieria .=" Designation: $designationText";
    }
   
 //qualification
   $qualification = $REQUEST_DATA['qualification'];
    if (!empty($qualification)) {
        $conditionsArray[] = " a.qualification = '$qualification' ";
        $qryString .= "&qualification=$qualification";
        $searchCrieria .="Qualification:$qualification";
    }
  
  //city
    $citys = $REQUEST_DATA['citys'];
    $citysText = $REQUEST_DATA['citysText'];
    if (!empty($citys)) {
        $conditionsArray[] = " (a.corrCityId IN ($citys) OR  a.permCityId IN ($citys)) ";
        $qryString .= "&cityId=$citys";
        $searchCrieria .=" City: $citysText";   
    }
    
//state
    $state = $REQUEST_DATA['state'];
    $stateText = $REQUEST_DATA['stateText'];
    if (!empty($state)) {
        $conditionsArray[] = " (a.corrstateId IN ($state) OR  a.permstateId IN ($state)) ";
        $qryString .= "&stateId=$state";
        $searchCrieria .=" State: $stateText";  
    }
    
//country
    $country = $REQUEST_DATA['country'];
    $countryText = $REQUEST_DATA['countryText'];
    if (!empty($country)) {
        $conditionsArray[] = " (a.corrcountryId IN ($country) OR  a.permcountryId IN ($country)) ";
        $qryString .= "&countryId=$country";
        $searchCrieria .=" Country: $countryText";  
    }
    
//Married
   $isMarried = $REQUEST_DATA['isMarried'];
    if (!empty($isMarried)) {
        $conditionsArray[] = " a.isMarried = '$isMarried' ";
        $qryString .= "&isMarried=$isMarried";
       $isMarried1 = $isMarried=='1' ? "Married" : "Unmarried";
        $searchCrieria .="Marital Status:$isMarried1";
    }
//Teaching
   $teachEmployee = $REQUEST_DATA['teachEmployee'];
    if (!empty($teachEmployee)) {
        $conditionsArray[] = " a.teachEmployee = '$teachEmployee' ";
        $qryString .= "&teachEmployee=$teachEmployee";
        $teachEmployee1 = $teachEmployee=='1' ? "Yes" : "No";
        $searchCrieria .="Teaching Employee:$teachEmployee1";
    }


//From Date of joining
/*
    $joiningDateF = $REQUEST_DATA['joiningDateF'];
    $joiningMonthF = $REQUEST_DATA['joiningMonthF'];
    $joiningYearF = $REQUEST_DATA['joiningYearF'];

    if (!empty($joiningDateF) && !empty($joiningMonthF) && !empty($joiningYearF)) {

        if (false !== checkdate($joiningMonthF, $joiningDateF, $joiningYearF)) {
            $thisDate = $joiningYearF.'-'.$joiningMonthF.'-'.$joiningDateF;
            $thisDate1 = $joiningYearF.'-'.$joiningMonthF.'-'.$joiningDateF;
            $conditionsArray[] = " a.dateOfJoining >= '$thisDate' ";
        }
        $qryString.= "&joiningDateF=$joiningDateF&joiningMonthF=$joiningMonthF&joiningYearF=$joiningYearF";
        $searchCrieria .="Date Of Joining:$thisDate";
    }
//To Date of joining
    $joiningDateT = $REQUEST_DATA['joiningDateT'];
    $joiningMonthT = $REQUEST_DATA['joiningMonthT'];
    $joiningYearT = $REQUEST_DATA['joiningYearT'];

    if (!empty($joiningDateT) && !empty($joiningMonthT) && !empty($joiningYearT)) {

        if (false !== checkdate($joiningMonthT, $joiningDateT, $joiningYearT)) {
            $thisDate = $joiningYearT.'-'.$joiningMonthT.'-'.$joiningDateT;
            $thisDate1 = $joiningYearT.'-'.$joiningMonthT.'-'.$joiningDateT;
            $conditionsArray[] = " a.dateOfJoining >= '$thisDate' ";
        }
        $qryString.= "&joiningDateT=$joiningDateT&joiningMonthT=$joiningMonthT&joiningYearT=$joiningYearT";
        $searchCrieria .="Date Of Joining:$thisDate";
    }
//From Date of LEAVING
    $leavingDateF = $REQUEST_DATA['leavingDateF'];
    $leavingMonthF = $REQUEST_DATA['leavingMonthF'];
    $leavingYearF = $REQUEST_DATA['leavingYearF'];

    if (!empty($leavingDateF) && !empty($leavingMonthF) && !empty($leavingYearF)) {

        if (false !== checkdate($leavingMonthF, $leavingDateF, $leavingYearF)) {
            $thisDate = $leavingYearF.'-'.$leavingMonthF.'-'.$leavingDateF;
            $thisDate1 = $leavingYearF.'-'.$leavingMonthF.'-'.$leavingDateF;
            $conditionsArray[] = " a.dateOfJoining >= '$thisDate' ";
        }
        $qryString.= "&leavingDateF=$leavingDateF&leavingMonthF=$leavingMonthF&leavingYearF=$leavingYearF";
        $searchCrieria .="Date Of Leaving:$thisDate";
    }
//To Date of LEAVING
    $leavingDateT = $REQUEST_DATA['leavingDateT'];
    $leavingMonthT = $REQUEST_DATA['leavingMonthT'];
    $leavingYearT = $REQUEST_DATA['leavingYearT'];

    if (!empty($leavingDateT) && !empty($leavingMonthT) && !empty($leavingYearT)) {

        if (false !== checkdate($leavingMonthT, $leavingDateT, $leavingYearT)) {
            $thisDate = $leavingYearT.'-'.$leavingMonthT.'-'.$leavingDateT;
            $thisDate1 = $leavingYearT.'-'.$leavingMonthT.'-'.$leavingDateT;
            $conditionsArray[] = " a.dateOfJoining >= '$thisDate' ";
        }
        $qryString.= "&leavingDateT=$leavingDateT&leavingMonthT=$leavingMonthT&leavingYearT=$leavingYearT";
        $searchCrieria .="Date Of Leaving:$thisDate";
    }
    */
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
        $searchCrieria .="Birth Date From:$thisDate "; 
      }
    }
    
    if($birthDateT!='') {
      $dtArray = explode('-',$birthDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($birthDateT);
        $searchCrieria .="Birth Date To:$thisDate "; 
      }
    }
    
    if($joiningDateF!='') {
      $dtArray = explode('-',$joiningDateF);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($joiningDateF);
        $searchCrieria .="Date Of Joining From:$thisDate "; 
      }
    }
    
    if($joiningDateT!='') {
      $dtArray = explode('-',$joiningDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($joiningDateT);
        $searchCrieria .="Date Of Joining To:$thisDate "; 
      }
    }
         
     if($leavingDateF!='') {
      $dtArray = explode('-',$leavingDateF);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($leavingDateF);
        $searchCrieria .="Date Of Leaving From:$thisDate "; 
      }
    }
    
    if($leavingDateT!='') {
      $dtArray = explode('-',$leavingDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = UtilityManager::formatDate($leavingDateT);
        $searchCrieria .="Date Of Leaving To:$thisDate "; 
      }
    }
  $cnter=0;      
    $countRows = count($reportRecordArray);
    $reportTableHead    =    array();         
        if($countRows>0) {
                 $reportTableHead['srNo']                =    array('#',                    'width="3%"', "align='left' "); 
            foreach($reportRecordArray[0] AS $listRecords => $listRecordvalue){
                      
                    if($listRecords=='Photo'){
                    /*$cnter++;
                    $reportTableHead[$listRecords]                =    array($listRecords,  'width="4%" ', "align='center' ");*/
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
                    $reportTableHead[$listRecords]                =    array($listRecords,  'width="4%" ', "align='left' ");
                    }
                    
            }  
           
        for($i=0;$i<$countRows;$i++)
            {
                       // add stateId in actionId to populate edit/delete icons in User Interface   
               // if(trim($reportRecordArray[$i]['Photo'])!=''){
                $reportRecordArray[$i]['Photo']='';
                //    $reportRecordArray[$i]['Photo']="<img src=\"".EMPLOYEE_PHOTO_PATH."/".stripslashes($reportRecordArray[$i]['Photo'])."\" height=\"64px\" width=\"64px\" valign=\"middle\" >";
                    
              //  }
               
            /*    if(trim($reportRecordArray[$i]['Hostel'])=='1'){
                    $reportRecordArray[$i]['Hostel']="Availed";
                }
                else {        
                    $reportRecordArray[$i]['Hostel']="Not Availed";
                }
                if(trim($reportRecordArray[$i]['Bus'])=='1'){
                    $reportRecordArray[$i]['Bus']="Availed";
                }                                                   
                else {        
                    $reportRecordArray[$i]['Bus']="Not Availed";
                }      */
                if($reportRecordArray[$i]['Address'] != '') {
                    $address = $reportRecordArray[$i]['Address'];
                    $address = str_replace("<br>","",$address);
                    $address = str_replace("<br/>","",$address);
                    $address = str_replace("<br />","",$address);
                    $reportRecordArray[$i]['Address'] = $address;
                }
				if($reportRecordArray[$i]['DOB'] != '') {
					if($reportRecordArray[$i]['DOB']=='0000-00-00') {
					   $reportRecordArray[$i]['DOB']=NOT_APPLICABLE_STRING;
					}
					else {
					   $reportRecordArray[$i]['DOB']=UtilityManager::formatDate($reportRecordArray[$i]['DOB']);
					}
				}
				if($reportRecordArray[$i]['DOJ'] != '') {
					if($reportRecordArray[$i]['DOJ']=='0000-00-00') {
					   $reportRecordArray[$i]['DOJ']=NOT_APPLICABLE_STRING;
					}
					else {
					   $reportRecordArray[$i]['DOJ']=UtilityManager::formatDate($reportRecordArray[$i]['DOJ']);
					}
				}
				if($reportRecordArray[$i]['DOL'] != '') {
					if($reportRecordArray[$i]['DOL']=='0000-00-00') {
					   $reportRecordArray[$i]['DOL']=NOT_APPLICABLE_STRING;
					}
					else {
					   $reportRecordArray[$i]['DOL']=UtilityManager::formatDate($reportRecordArray[$i]['DOL']);
					}
				}

                $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$reportRecordArray[$i]) ;  
            }
           // print_r($reportRecordArray);
            if(isset($REQUEST_DATA['sectionIdForm'])){
            $z=-1;
            $oldId="";
            $chk=0;
            for($i=0;$i<$countRows;$i++){
                if($oldId == $valueArray[$i]['employeeId']){
                    $chk++;
                    if($chk>1){
                        //$z=$z-1;
                    }
                    $newValueArray[$z]['Section'].= " ; ".$valueArray[$i]['Section'];
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

    $formattedDate1 = date('d-M-y');
    $search="";
    if($searchCrieria!='') {
      $search .= parseCSVComments("For ".$searchCrieria);  
  $csvData .= "\n"; 
    }
    $csvData  = "Employee List Report \n";
     
    $csvData .= $search;     

    $csvData .= " As On ".$formattedDate1;
    $csvData .= "\n";     
    
    if(count($reportTableHead) >0 ) {
                foreach($reportTableHead as $head => $record){
                    if($head=='srNo') {
                        $csvData .= '#,';
                    }
                    else if($head=='DOB') {
                        $csvData .= 'Date of Birth,';
                    }
		            else if ($head=='DOJ') {
			            $csvData .= 'Date of Joining,';
		            }
		            else if ($head=='DOL') {
			            $csvData .= 'Date of Leaving,';
		            }
                    else { 
                     $csvData .= $head.',';
                    }
                }
                $csvData=substr(trim($csvData),0,-1);
                $csvData .="\n";
                if(isset($REQUEST_DATA['sectionIdForm'])){
                    $cnt=count($newValueArray);
	            if($cnt > 0 && is_array($valueArray)) {
                    for($i=0;$i<$cnt;$i++){
                        foreach($newValueArray[$i] as $head => $record) {
                           $csvData .= parseCSVComments($record).','; 
                        }
                        $csvData .= "\n";   
                    }
	            }
            else
            {
            $csvData .= "No Data Found";
            }
                }
                else{
                    $cnt=count($valueArray);
	            if($cnt > 0 && is_array($valueArray)) {
                    for($i=0;$i<$cnt;$i++){
                        foreach($valueArray[$i] as $head => $record) {
                             $csvData .= parseCSVComments($record).',';
                        }
                            $csvData .= "\n";   
                    }
	            }
            else
            {
            $csvData .= "No Data Found";
            }
        } 
    }
    else {
      $csvData .= "No Data Found";   
    }
    
         
UtilityManager::makeCSV($csvData,'EmployeeListsReport.csv');      
die;         
?>
