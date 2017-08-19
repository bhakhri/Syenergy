 <?php 
//This file is used as CSV version of Employee Info.
//
// Author :Gurkeerat Sidhu
// Created on : 10.Nov.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();
    define('MODULE','UploadEmployeeDetail');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    //to parse csv values    
    function parseCSVComments($comments) {
     //$comments = str_replace('"', '""', $comments);
     //$comments = str_ireplace('<br/>', "\n", $comments);
     if(eregi(",", $comments) or eregi("\n", $comments)) {
       return '"'.$comments.'"'; 
     }
     else {
       return chr(160).$comments; 
     }
    }


    
      //  $conditions = "WHERE s.classId=".$classId." AND s.classId = cl.classId";
        $employeeRecordArray = $employeeManager->getEmployeeInfo();

        /*echo "<pre>";
        print_r($employeeRecordArray);
        die;*/
        $recordCount = count($employeeRecordArray);
        //echo $recordCount;
        //die; 
        $valueArray = array();

        $csvData ='';
        $csvData="Sr.No.,Employee Id,User Name,Title,Last Name, First Name, Middle Name, Employee Code,Employee Abbr.,Teaching Employee(yes/no),Designation,Gender(M/F),Department,Pan No.,Religion,Caste,PF No., Bank Name, Bank Account No.,Bank Branch Name,ESI Number,Branch,Role Name,Marital Status(yes/no),Spouse Name,Father Name,Mother Name,Contact Number,Email,Mobile Number,Address1,Address2,City,State,Country,Pin,Date of Birth(yyyy.mm.dd),Date of Marriage(yyyy.mm.dd),Date of Joining(yyyy.mm.dd),Date of Leaving(yyyy.mm.dd),Blood Group,Status(yes/no)";
        
        $csvData .="\n";
        /*echo"<pre>";
        print_r($employeeRecordArray);
        die;*/
        
        for($i=0;$i<$recordCount;$i++) {
            if ($employeeRecordArray[$i]['dateOfBirth']=="0000-00-00"){
               // $j= $employeeRecordArray[$i]['dateOfMarriage'];
                  $birth= '';
            }
            else{
				$birth = explode('-',$employeeRecordArray[$i]['dateOfBirth']);
				$birthYear = $birth[0];
				$birthMonth = $birth[1];
				$birthDate = $birth[2];
				$birth = $birthYear.'.'.$birthMonth.'.'.$birthDate;
                //$birth= $employeeRecordArray[$i]['dateOfBirth'];
            }  
            if ($employeeRecordArray[$i]['dateOfMarriage']=="0000-00-00"){
                  $marriage= '';
            }
            else{
                $marriage = explode('-',$employeeRecordArray[$i]['dateOfMarriage']);
				$marriageYear = $marriage[0];
				$marriageMonth = $marriage[1];
				$marriageDate = $marriage[2];
				$marriage = $marriageYear.'.'.$marriageMonth.'.'.$marriageDate;
				//$marriage= $employeeRecordArray[$i]['dateOfMarriage'];
            } 
            if ($employeeRecordArray[$i]['dateOfJoining']=="0000-00-00"){
               // $j= $employeeRecordArray[$i]['dateOfMarriage'];
                  $joining= '';
            }
            else{
                $joining = explode('-',$employeeRecordArray[$i]['dateOfJoining']);
				$joiningYear = $joining[0];
				$joiningMonth = $joining[1];
				$joiningDate = $joining[2];
				$joining = $joiningYear.'.'.$joiningMonth.'.'.$joiningDate;
				//$joining= $employeeRecordArray[$i]['dateOfJoining'];
            } 
            if ($employeeRecordArray[$i]['dateOfLeaving']=="0000-00-00"){
               // $j= $employeeRecordArray[$i]['dateOfMarriage'];
                  $leaving= '';
            }
            else {
				$leaving = explode('-',$employeeRecordArray[$i]['dateOfLeaving']);
				$leavingYear = $leaving[0];
				$leavingMonth = $leaving[1];
				$leavingDate = $leaving[2];
				$leaving = $leavingYear.'.'.$leavingMonth.'.'.$leavingDate;
                //$leaving= $employeeRecordArray[$i]['dateOfLeaving'];
            }

			if ($employeeRecordArray[$i]['title'] == 1){
                  $employeeRecordArray[$i]['title'] = "Mr";
            }
			if ($employeeRecordArray[$i]['title'] == 2){
                  $employeeRecordArray[$i]['title'] = "Mrs";
            }
			if ($employeeRecordArray[$i]['title'] == 3){
                  $employeeRecordArray[$i]['title'] = "Miss";
            }
			if ($employeeRecordArray[$i]['title'] == 4){
                  $employeeRecordArray[$i]['title'] = "Dr.";
            }

			if ($employeeRecordArray[$i]['bloodGroup'] == 0){
                  $employeeRecordArray[$i]['bloodGroup'] = '';
            }
			if ($employeeRecordArray[$i]['bloodGroup'] != 0){
				$employeeRecordArray[$i]['bloodGroup'] = $bloodResults[$employeeRecordArray[$i]['bloodGroup']];
			}
           

              $csvData .= ($i+1).',';
               $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['employeeId'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['userName'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['title'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['lastName'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['employeeName'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['middleName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['employeeCode'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['employeeAbbreviation'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['isTeaching'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['designationName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['gender'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['departmentName'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['panNo'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['religion'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['caste'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['providentFundNo'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['bankName'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['accountNo'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['branchName'])).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['esiNumber'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['branchCode'])).',';
              //$csvData .= trim(parseCSVComments($employeeRecordArray[$i]['qualification'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['roleName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['isMarried'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['spouseName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['fatherName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['motherName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['contactNumber'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['emailAddress'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['mobileNumber'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['address1'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['address2'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['cityName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['stateName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['countryName'])).',';
              $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['pinCode'])).',';
              $csvData .= trim(parseCSVComments($birth)).',';
              $csvData .= trim(parseCSVComments($marriage)).',';
              $csvData .= trim(parseCSVComments($joining)).',';
              $csvData .= trim(parseCSVComments($leaving)).',';
			  $csvData .= trim(parseCSVComments($employeeRecordArray[$i]['bloodGroup'])).',';
              $csvData .= 'No';
              $csvData .= "\n ";
      }
    

   

 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'EmployeeInfoReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;
//$History : $
?>