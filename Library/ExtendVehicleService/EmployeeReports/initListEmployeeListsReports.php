<?php
//----------------------------------------------------------------------------------------------------
//This file creates a query for the "EmployeeListsReport" and generates an array of the selected fields 
//
// Author :Arvind Singh Rawat
// Created on : 08-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
  
	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/EmployeeReportsManager.inc.php");
    
    define('MODULE','EmployeeList');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    // $reportManager = EmployeeReportsManager::getInstance();
	// echo "<pre>";
    //   print_r($REQUEST_DATA);  
    // die;
    $countFields=count($REQUEST_DATA);
    $employeeReportsManager = EmployeeReportsManager::getInstance();

    //require_once(BL_PATH . '/ScReportManager.inc.php');
    //$reportManager = ReportManager::getInstance();
    
    foreach($REQUEST_DATA as $key => $values) {
        $$key = add_slashes($values);
    }
    $conditionsArray = array();
		$incAllInsitute  = add_slashes($REQUEST_DATA['incAllInsitute']);

    if($incAllInsitute=='') {
      $incAllInsitute=0;
    }
	//echo $incAllInsitute;die;
    
    $employeeRoleId='';     
    if(!empty($roleName)) { 
       $roleIdsArray = $employeeReportsManager->getEmployeeRoleId($roleName);
       for($i=0;$i<count($roleIdsArray);$i++) {
          if($employeeRoleId=='') {
            $employeeRoleId = $roleIdsArray[$i]['employeeId'];    
          } 
          else { 
            $employeeRoleId .=",".$roleIdsArray[$i]['employeeId'];  
          }
       }   
    }
   
    if (!empty($employeeCode)) {
        $conditionsArray[] = " e.employeeCode LIKE '$employeeCode%' ";
    }
    
    if (!empty($employeeName)) {
        $conditionsArray[] = " e.employeeName LIKE '%$employeeName%'";
    }

    if (!empty($branchId)) {
        $conditionsArray[] = " e.branchId IN ($branchId) ";
    }
    
    if (!empty($departmentId)) {
        $conditionsArray[] = " e.departmentId IN ($departmentId) ";
    }

    if (!empty($instituteId)) {
        $conditionsArray[] = " e.instituteId IN ($instituteId) ";
    }
    
    if (!empty($designationId)) {
        $conditionsArray[] = " e.designationId IN ($designationId) ";
    }
    
    
    // Date of Birth (From - To)
    
    $birthDateF = $REQUEST_DATA['birthDateF']; 
    $birthDateT = $REQUEST_DATA['birthDateT']; 
    
    $joiningDateF = $REQUEST_DATA['joiningDateF']; 
    $joiningDateT = $REQUEST_DATA['joiningDateT']; 
    
    $leavingDateF = $REQUEST_DATA['leavingDateF']; 
    $leavingDateT = $REQUEST_DATA['leavingDateT']; 
    
    if($birthDateF!='') {
      $dtArray = explode('-',$birthDateF);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
       $thisDate = $birthDateF;
        $conditionsArray[] =  " e.dateOfBirth >= '$thisDate' ";
      }
    }
    
    
    if($birthDateT!='') {
      $dtArray = explode('-',$birthDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = $birthDateT;
        $conditionsArray[] =  " e.dateOfBirth <= '$thisDate' ";
      }
    }
     
    if($joiningDateF!='') {
      $dtArray = explode('-',$joiningDateF);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = $joiningDateF;
        $conditionsArray[] =  " e.dateOfJoining >= '$thisDate' ";
      }
    }
    
    if($joiningDateT!='') {
      $dtArray = explode('-',$joiningDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = $joiningDateT;
        $conditionsArray[] =  " e.dateOfJoining <= '$thisDate' ";
      }
    }
        
    if($leavingDateF!='') {
      $dtArray = explode('-',$leavingDateF);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = $leavingDateF;
        $conditionsArray[] =  " e.dateOfLeaving >= '$thisDate' ";
      }
    }
    
    if($leavingDateT!='') {
      $dtArray = explode('-',$leavingDateT);      
      if(false !== checkdate($dtArray[1],$dtArray[2],$dtArray[0])) { 
        $thisDate = $leavingDateT;
        $conditionsArray[] =  " e.dateOfLeaving <= '$thisDate' ";
      }
    }
           
                          
    
    /*
    $birthDateF = $REQUEST_DATA['birthDateF'];
    $birthMonthF = $REQUEST_DATA['birthMonthF'];
    $birthYearF = $REQUEST_DATA['birthYearF'];
    if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {
        if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
            $thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $conditionsArray[] =  " e.dateOfBirth >= '$thisDate' ";
        }
    }
    
    $birthDateT = $REQUEST_DATA['birthDateT'];
    $birthMonthT = $REQUEST_DATA['birthMonthT'];
    $birthYearT = $REQUEST_DATA['birthYearT'];
    if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {
        if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
            $thisDate = $birthYearT.'-'.$birthMonthT.'-'.$birthDateT;
            $conditionsArray[] =  " (e.dateOfBirth > '0000-00-00' AND e.dateOfBirth <= '$thisDate') ";
        }
    }

    
    // Joining Date (From - To)
    $joiningDateF = $REQUEST_DATA['joiningDateF'];
    $joiningMonthF = $REQUEST_DATA['joiningMonthF'];
    $joiningYearF = $REQUEST_DATA['joiningYearF'];
    if (!empty($joiningDateF) && !empty($joiningMonthF) && !empty($joiningYearF)) {
        if (false !== checkdate($joiningMonthF, $joiningDateF, $joiningYearF)) {
            $thisDate = $joiningYearF.'-'.$joiningMonthF.'-'.$joiningDateF;
            $conditionsArray[] =  " e.dateOfJoining >=  '$thisDate' ";
        }
    }
    
    $joiningDateT = $REQUEST_DATA['joiningDateT'];
    $joiningMonthT = $REQUEST_DATA['joiningMonthT'];
    $joiningYearT = $REQUEST_DATA['joiningYearT'];
    if (!empty($joiningDateT) && !empty($joiningMonthT) && !empty($joiningYearT)) {
        if (false !== checkdate($joiningMonthT, $joiningDateT, $joiningYearT)) {
            $thisDate = $joiningYearT.'-'.$joiningMonthT.'-'.$joiningDateT;
            $conditionsArray[] =  " (e.dateOfJoining > '0000-00-00' AND e.dateOfJoining <=  '$thisDate') ";
        }
    }
    
    // Leaving Date  (From - To)
    $leavingDateF = $REQUEST_DATA['leavingDateF'];
    $leavingMonthF = $REQUEST_DATA['leavingMonthF'];
    $leavingYearF = $REQUEST_DATA['leavingYearF'];
    if (!empty($leavingDateF) && !empty($leavingMonthF) && !empty($leavingYearF)) {
        if (false !== checkdate($leavingMonthF, $leavingDateF, $leavingYearF)) {
            $thisDate = $leavingYearF.'-'.$leavingMonthF.'-'.$leavingDateF;
            $conditionsArray[] =  " e.dateOfLeaving >=  '$thisDate' ";   
        }
    }
    
    $leavingDateT = $REQUEST_DATA['leavingDateT'];
    $leavingMonthT = $REQUEST_DATA['leavingMonthT'];
    $leavingYearT = $REQUEST_DATA['leavingYearT'];
    if (!empty($leavingDateT) && !empty($leavingMonthT) && !empty($leavingYearT)) {
        if (false !== checkdate($leavingMonthT, $leavingDateT, $leavingYearT)) {
            $thisDate = $leavingYearT.'-'.$leavingMonthT.'-'.$leavingDateT;
            $conditionsArray[] =  " (e.dateOfLeaving > '0000-00-00' AND e.dateOfLeaving <=  '$thisDate') ";
        }
    }
    */
        
    if (!empty($genderRadio)) {
        $conditionsArray[] = " e.gender = '$genderRadio' ";
    }
    if (!empty($cityId)) {
        $conditionsArray[] = " e.cityId IN ($cityId) ";
    }
    if (!empty($stateId)) {
        $conditionsArray[] = " e.stateId IN ($stateId) ";
    }
    if (!empty($countryId)) {
        $conditionsArray[] = " e.countryId IN ($countryId) ";
    }
    
    if ($isMarried!='') {
        $conditionsArray[] = " e.isMarried IN ($isMarried) ";
    } 
    
    if ($teachEmployee!='') {
        $conditionsArray[] = " e.isTeaching IN ($teachEmployee) ";
    } 
    
    if ($qualification!='') {
        $conditionsArray[] = " e.qualification LIKE '$qualification%' ";
    } 
            
    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }
    
  //  $employeeRecordArray = $employeeReportsManager->getAllDetailsEmployeeList($conditions, $REQUEST_DATA['sortField'].' '.$REQUEST_DATA['sortOrderBy'], $limit);
  //  $cnt = count($employeeRecordArray);
  //  $valueArray = array();
  //  for($i=0;$i<$cnt;$i++) {
  //      $valueArray[] = array_merge(array('srNo' => $i+1),$employeeRecordArray[$i]);
  //  }

	global $sessionHandler;
	$systemDatabaseManager = SystemDatabaseManager::getInstance();
	$userId= $sessionHandler->getSessionVariable('UserId');
	$roleId = $sessionHandler->getSessionVariable('RoleId');
    
    if($userId=='') {
      $userId=0;  
    }
    
    if($roleId=='') {
      $roleId=0;  
    }
	
	$userRoleArray = $employeeReportsManager->getRoleUser($userId);
	$roleCount = $userRoleArray[0]['totalRecords'];
	
	if ($roleCount > 0) {
		$conditions .= " GROUP BY e.employeeId";
        $query = "	SELECT 
							distinct cvtr.classId 
					FROM	classes_visible_to_role cvtr
					WHERE	cvtr.userId = $userId
					AND		cvtr.roleId = $roleId";

	$result =  $systemDatabaseManager->executeQuery($query,"Query: $query");
		
	$count = count($result);
	$insertValue = "";
	for($i=0;$i<$count; $i++) {
		$querySeprator = '';
		if($insertValue!='') {
			$querySeprator = ",";
		}
		$insertValue .= "$querySeprator ('".$result[$i]['classId']."')";
	}
    
    
    $filter.=isset($REQUEST_DATA['empCode']) ? "IF(trim(employeeCode )='','".NOT_APPLICABLE_STRING."',employeeCode) AS `Employee Code` ," : '';
    
    $filter.=isset($REQUEST_DATA['Name']) ? "IF(trim(employeeName)='','".NOT_APPLICABLE_STRING."',employeeName) AS `Name` ," : '';
    
    $filter.=isset($REQUEST_DATA['chkDesignation']) ? "IF(IFNULL(e.designationId,'')='','".NOT_APPLICABLE_STRING."', 
                (SELECT designationName FROM designation desg WHERE desg.designationId=e.designationId)) AS `Designation` ," : "";    

    $filter.=isset($REQUEST_DATA['chkDepartment']) ? "IF(IFNULL(e.departmentId,'')='','".NOT_APPLICABLE_STRING."',
                (SELECT departmentName FROM department d WHERE  e.departmentId=d.departmentId )) AS Department, " : "";
                
    $filter.=isset($REQUEST_DATA['chkBranch']) ? "IF(IFNULL(e.branchId,'')='','".NOT_APPLICABLE_STRING."',
                (SELECT branchCode FROM branch br  WHERE  e.branchId=br.branchId )) AS branchCode, " : "";
                
    $filter.=isset($REQUEST_DATA['TeachingEmployee']) ? "IF(isTeaching=1,'Yes','No') AS `Teaching Employee` ," : "";
    
    /*$filter.=isset($REQUEST_DATA['employeeRole']) ? "IFNULL(IF(IFNULL(e.userId,'')='','".NOT_APPLICABLE_STRING."',
    CONCAT((SELECT roleName FROM `user` uu, role rr WHERE uu.roleId=rr.roleId AND uu.userId=e.userId),' ',
    IF(IFNULL(GROUP_CONCAT(DISTINCT roleName ORDER BY roleName SEPARATOR ', '),'')='','',(GROUP_CONCAT(DISTINCT roleName ORDER BY roleName SEPARATOR ', '))))),'".NOT_APPLICABLE_STRING."') AS Role, " : "";
    */
    $filter.=isset($REQUEST_DATA['employeeRole']) ? "IFNULL(GROUP_CONCAT(DISTINCT roleName ORDER BY roleName SEPARATOR ', '),'".NOT_APPLICABLE_STRING."') AS Role, " : "";     
  
    $filter.=isset($REQUEST_DATA['Qualification']) ? "IF(trim(qualification)='','".NOT_APPLICABLE_STRING."',qualification) AS `Qualification` ," : "";

    $filter.=isset($REQUEST_DATA['dateOfBirth']) ? "dateOfBirth AS DOB ," : "";
        
    $filter.=isset($REQUEST_DATA['Married']) ? "IF(isMarried=1,'Yes','No') AS `Married` ," : "";
    
    $filter.=isset($REQUEST_DATA['Gender']) ? "IF(trim(gender)='','".NOT_APPLICABLE_STRING."',gender) AS `Gender` ," : "";

    $filter.=isset($REQUEST_DATA['fatherName']) ? "IF(trim(fatherName)='','".NOT_APPLICABLE_STRING."',fatherName) AS `Father's Name` ," : "";
    $filter.=isset($REQUEST_DATA['motherName']) ? "IF(trim(motherName)='','".NOT_APPLICABLE_STRING."',motherName) AS `Mother's Name` ," : "";
    

    $filter.=isset($REQUEST_DATA['MobileNo']) ? "IF(trim(e.mobileNumber)='','".NOT_APPLICABLE_STRING."',e.mobileNumber) As  `Mobile Number` ," : '';
    $filter.=isset($REQUEST_DATA['LandlineNumber']) ? 
         "IF(trim(e.contactNumber)='','".NOT_APPLICABLE_STRING."',e.contactNumber) As `Landline Number`,": '';
                
    $filter.=isset($REQUEST_DATA['Address']) ? 'IF(e.address1 IS NULL OR e.address1="","",CONCAT(address1," ",IFNULL(address2,""),"<br/>",
         IF(e.cityId IS NULL OR e.cityId="","",(SELECT cityName from city where city.cityId=e.cityId))," ",
         IF(e.stateId IS NULL OR e.stateId="","",(SELECT stateName from states where states.stateId=e.stateId)),"<br/>",
         IF(e.countryId IS NULL OR e.countryId="","",(SELECT countryName from countries where countries.countryId=e.countryId)),
         IF(e.pinCode IS NULL OR e.pinCode="" ,"",CONCAT("-",e.pinCode)))) As `Address` ,' : '';
         
        
    
    $filter=substr(trim($filter),0,-1);
    
    $filter .= " FROM classes_visible_to_role cvtr,  ".TIME_TABLE_TABLE."  tt, `group` gr, employee e 
                 LEFT JOIN `user` u ON u.userId = e.userId 
                 LEFT JOIN `institute` ins ON ins.instituteId = u.instituteId
                 LEFT JOIN user_role ur ON ur.userId = e.userId 
                 LEFT JOIN role r ON ur.roleId = r.roleId 
                 LEFT JOIN employee_can_teach_in ec ON e.employeeId = ec.employeeId ";
    //$filter.= isset($REQUEST_DATA['Address']) ? ', city c, countries cn, states s ':'';
    //$filter.=isset($REQUEST_DATA['Designation']) ?  ", designation d " : "";
    $countFields=$countFields-14; 
    
        $filter.="  WHERE  e.isActive = 1  AND cvtr.groupId = tt.groupId  AND tt.employeeId = e.employeeId AND 
                    gr.classId = cvtr.classId AND gr.groupId = cvtr.groupId AND tt.toDate IS NULL AND 
                    gr.classId IN ($insertValue) AND cvtr.userId = $userId AND 
                    cvtr.roleId = ".$sessionHandler->getSessionVariable('RoleId').""; 
                    
        if($incAllInsitute==0) {
          $filter .= "  AND (e.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." OR  
                             ec.instituteId=".$sessionHandler->getSessionVariable('InstituteId').") ";
        }
        
        //$conditions .= isset($REQUEST_DATA['employeeRole']) ? " GROUP BY e.employeeId " : "";
        // $filter=substr(trim($filter),0,-1);
    }
	else {
	$filter.=isset($REQUEST_DATA['empCode']) ? "IF(trim(employeeCode )='','".NOT_APPLICABLE_STRING."',employeeCode) AS `Employee Code` ," : '';
    
	$filter.=isset($REQUEST_DATA['Name']) ? "IF(trim(employeeName)='','".NOT_APPLICABLE_STRING."',employeeName) AS `Name` ," : '';
    
    $filter.=isset($REQUEST_DATA['chkDesignation']) ? "IF(IFNULL(e.designationId,'')='','".NOT_APPLICABLE_STRING."', 
                (SELECT designationName FROM designation desg WHERE desg.designationId=e.designationId)) AS `Designation` ," : "";    

    $filter.=isset($REQUEST_DATA['chkDepartment']) ? "IF(IFNULL(e.departmentId,'')='','".NOT_APPLICABLE_STRING."',
                (SELECT departmentName FROM department d WHERE  e.departmentId=d.departmentId )) AS Department, " : "";
                
    $filter.=isset($REQUEST_DATA['chkBranch']) ? "IF(IFNULL(e.branchId,'')='','".NOT_APPLICABLE_STRING."',
                (SELECT branchCode FROM branch br  WHERE  e.branchId=br.branchId )) AS branchCode, " : "";
                
    $filter.=isset($REQUEST_DATA['TeachingEmployee']) ? "IF(isTeaching=1,'Yes','No') AS `Teaching Employee` ," : "";
    
    /*$filter.=isset($REQUEST_DATA['employeeRole']) ? "IFNULL(IF(IFNULL(e.userId,'')='','".NOT_APPLICABLE_STRING."',
    CONCAT((SELECT roleName FROM `user` uu, role rr WHERE uu.roleId=rr.roleId AND uu.userId=e.userId),' ',
    IF(IFNULL(GROUP_CONCAT(DISTINCT roleName ORDER BY roleName SEPARATOR ', '),'')='','',(GROUP_CONCAT(DISTINCT roleName ORDER BY roleName SEPARATOR ', '))))),'".NOT_APPLICABLE_STRING."') AS Role, " : "";
    */
    $filter.=isset($REQUEST_DATA['employeeRole']) ? "IFNULL(GROUP_CONCAT(DISTINCT roleName ORDER BY roleName SEPARATOR ', '),'".NOT_APPLICABLE_STRING."') AS Role, " : "";     
  
  
    $filter.=isset($REQUEST_DATA['Qualification']) ? "IF(trim(qualification)='','".NOT_APPLICABLE_STRING."',qualification) AS `Qualification` ," : "";

    $filter.=isset($REQUEST_DATA['dateOfBirth']) ? "dateOfBirth AS DOB ," : "";

	$filter.=isset($REQUEST_DATA['DOJ']) ? "dateOfJoining AS DOJ ," : "";

	$filter.=isset($REQUEST_DATA['DOL']) ? "dateOfLeaving AS DOL ," : "";

    $filter.=isset($REQUEST_DATA['employeeEmail']) ? "IF(trim(emailAddress )='','".NOT_APPLICABLE_STRING."',emailAddress) AS `Email` ," : '';
    
    $filter.=isset($REQUEST_DATA['instituteName']) ? "IF(trim(instituteCode )='','".NOT_APPLICABLE_STRING."',instituteCode) AS `Institute Name` ," : '';
    
    $filter.=isset($REQUEST_DATA['userName']) ? "IF(trim(userName )='','".NOT_APPLICABLE_STRING."',userName) AS `User Name` ," : '';
        
	$filter.=isset($REQUEST_DATA['Married']) ? "IF(isMarried=1,'Yes','No') AS `Married` ," : "";
    
    $filter.=isset($REQUEST_DATA['Gender']) ? "IF(trim(gender)='','".NOT_APPLICABLE_STRING."',gender) AS `Gender` ," : "";

    $filter.=isset($REQUEST_DATA['fatherName']) ? "IF(trim(fatherName)='','".NOT_APPLICABLE_STRING."',fatherName) AS `Father's Name` ," : "";
    $filter.=isset($REQUEST_DATA['motherName']) ? "IF(trim(motherName)='','".NOT_APPLICABLE_STRING."',motherName) AS `Mother's Name` ," : "";
	

    $filter.=isset($REQUEST_DATA['MobileNo']) ? "IF(trim(e.mobileNumber)='','".NOT_APPLICABLE_STRING."',e.mobileNumber) As  `Mobile Number` ," : '';
    $filter.=isset($REQUEST_DATA['LandlineNumber']) ? 
         "IF(trim(e.contactNumber)='','".NOT_APPLICABLE_STRING."',e.contactNumber) As `Landline Number`,": '';
                
    $filter.=isset($REQUEST_DATA['Address']) ? 'IF(e.address1 IS NULL OR e.address1="","",CONCAT(address1," ",IFNULL(address2,""),"<br/>",
         IF(e.cityId IS NULL OR e.cityId="","",(SELECT cityName from city where city.cityId=e.cityId))," ",
         IF(e.stateId IS NULL OR e.stateId="","",(SELECT stateName from states where states.stateId=e.stateId)),"<br/>",
         IF(e.countryId IS NULL OR e.countryId="","",(SELECT countryName from countries where countries.countryId=e.countryId)),
         IF(e.pinCode IS NULL OR e.pinCode="" ,"",CONCAT("-",e.pinCode)))) As `Address` ,' : '';
    
    
    $filter=substr(trim($filter),0,-1);
    
    $filter .= " FROM employee e 
                 LEFT JOIN `user` u ON u.userId = e.userId 
                 LEFT JOIN `institute` ins ON ins.instituteId = u.instituteId";
    $filter .= isset($REQUEST_DATA['employeeRole']) ? " LEFT JOIN user_role ur ON ur.userId = e.userId 
                                                        LEFT JOIN role r ON ur.roleId = r.roleId ": "";
    //$filter.= isset($REQUEST_DATA['Address']) ? ', city c, countries cn, states s ':'';
    //$filter.=isset($REQUEST_DATA['Designation']) ?  ", designation d " : "";
    
    $countFields=$countFields-14; 
	if($incAllInsitute==0) {
       $filter.=" WHERE  e.isActive = 1 AND e.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'";
    }
    else {
	    $filter.=" WHERE  e.isActive = 1 ";
    }
    $conditions .= isset($REQUEST_DATA['employeeRole']) ? " GROUP BY e.employeeId " : "";
  }

    if($employeeRoleId!='') {
      $filter .= " AND e.employeeId IN ($employeeRoleId) ";
    }

    //$filter.= isset($REQUEST_DATA['Address']) ? ' e.stateId = s.stateId AND e.cityId=c.cityId AND e.countryId = cn.countryId AND ':'';
    //$filter.= isset($REQUEST_DATA['Designation']) ? 'e.designationId = d.designationId AND ':'';
    
    $reportRecordArray = $employeeReportsManager->getScEmployeeListReportList($filter,$conditions);
    
//////// excel //////////   
	 if($REQUEST_DATA['act'] =='excel') {
		//$Records = implode(',',$reportRecordArray[0]);
		foreach($reportRecordArray[0] as $records => $value){	
			$Records.="$records,";
		}
		$countRows = count($reportRecordArray);
	    for($i=0;$i<$countRows;$i++) {
			$Records .="\r\n".implode(',',$reportRecordArray[$i]);
		}
     }	
///////////////////////
/// generate query string ///
	foreach($REQUEST_DATA AS $key => $value){
		if(trim($querystring=='')){
			$querystring="$key=$value";
		}
		else{
			$querystring.="&$key=$value";
		}
	}	 
	 
	 	
