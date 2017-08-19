<?php
//-------------------------------------------------------------------------------
//
//EmployeeManager is used having all the Add, edit, delete function..
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class EmployeeManager {
	private static $instance = null;

	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}



//-------------------------------------------------------------------------------
// addBusPass() is used to add new record in database.
// Author : Parveen Sharma
// Created on : 12.06.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addBusPass($strValue='') {
       global $REQUEST_DATA;
       global $sessionHandler;

       $query = "INSERT INTO employee_bus_pass (busId,employeeId, busStopId, busRouteId, addUserId, validUpto, addedOnDate, status, instituteId)
                 $strValue ";

       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// editBusPass() is used to edit new record in database.
// Author : Parveen Sharma
// Created on : 12.06.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function editBusPass($id,$employeeId) {
        global $REQUEST_DATA;
        global $sessionHandler;

        $query = "UPDATE employee_bus_pass SET
                        cancelUserId = '".$sessionHandler->getSessionVariable('UserId')."',
                        cancelOnDate = '".date('Y-m-d')."', status='0'
                  WHERE busPassId='$id' AND employeeId='$employeeId' ";

        return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }


 //-------------------------------------------------------------------------------
//
//addUser() function is used for adding new user into the user table....
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function addUser() {
         global $REQUEST_DATA;
	     $userName = strtolower($REQUEST_DATA['userName']);
	     $userPassword = md5($REQUEST_DATA['userPassword']);
	     $roleName = $REQUEST_DATA['roleName'];
         global $sessionHandler;
	     //$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	     $instituteId = $REQUEST_DATA['defaultInstitute'];

	     $query = "	INSERT INTO user (userName,userPassword,roleId, instituteId)
					    VALUES ('$userName','$userPassword',$roleName, $instituteId)";
         return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//addEmployee() function is used for adding new employee into the employee table....
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addEmployee($userId) {
		global $REQUEST_DATA;
        global $sessionHandler;
        $dateOfBirth = $REQUEST_DATA['employeeYear']."-".$REQUEST_DATA['employeeMonth']."-".$REQUEST_DATA['employeeDate'];
        $dateOfMarriage = $REQUEST_DATA['marriageYear']."-".$REQUEST_DATA['marriageMonth']."-".$REQUEST_DATA['marriageDate'];
        $dateOfJoining = $REQUEST_DATA['joiningYear']."-".$REQUEST_DATA['joiningMonth']."-".$REQUEST_DATA['joiningDate'];
        $dateOfLeaving = $REQUEST_DATA['leavingYear']."-".$REQUEST_DATA['leavingMonth']."-".$REQUEST_DATA['leavingDate'];

		if($dateOfBirth == "--"){
			$dateOfBirth = "0000-00-00";
		}
		if($dateOfMarriage == "--"){
			$dateOfMarriage = "0000-00-00";
		}
		if($dateOfJoining == "--"){
			$dateOfJoining = "0000-00-00";
		}
		if($dateOfLeaving == "--"){
			$dateOfLeaving = "0000-00-00";
		}

		$title = addslashes($REQUEST_DATA['title']);
		$lastName = addslashes($REQUEST_DATA['lastName']);
		$employeeName = addslashes($REQUEST_DATA['employeeName']);
		$middleName = addslashes($REQUEST_DATA['middleName']);
		$employeeCode = addslashes(strtoupper($REQUEST_DATA['employeeCode']));
		$employeeAbbreviation = addslashes($REQUEST_DATA['employeeAbbreviation']);
		$isTeaching = $REQUEST_DATA['isTeaching'];
		$receiveSMS=$REQUEST_DATA['receiveSMS'];
		$designation = $REQUEST_DATA['designation'];
		$gender = $REQUEST_DATA['gender'];
		$branch = $REQUEST_DATA['branch'];
		$department = $REQUEST_DATA['department'];
		$panNo = $REQUEST_DATA['panNo'];
		$religion = $REQUEST_DATA['religion'];
		$caste = $REQUEST_DATA['caste'];
		$pfNo = $REQUEST_DATA['pfNo'];
		$bankName = $REQUEST_DATA['bankName'];
		$accountNo = $REQUEST_DATA['accountNo'];
		$branchName = $REQUEST_DATA['branchName'];
		$country = $country;
		$states = $states;
		$city = $city;
		$qualification = $REQUEST_DATA['qualification'];
		$isMarried = $REQUEST_DATA['isMarried'];
		$spouseName = addslashes($REQUEST_DATA['spouseName']);
		$fatherName = addslashes($REQUEST_DATA['fatherName']);
		$motherName = addslashes($REQUEST_DATA['motherName']);
		$contactNumber = $REQUEST_DATA['contactNumber'];
		$mobileNumber = $REQUEST_DATA['mobileNumber'];
		$email = $REQUEST_DATA['email'];
		$address1 = addslashes($REQUEST_DATA['address1']);
		$address2 = addslashes($REQUEST_DATA['address2']);
		$pin = $REQUEST_DATA['pin'];
		$dateOfBirth = $dateOfBirth;
		$dateOfMarriage = $dateOfMarriage;
		$dateOfJoining = $dateOfJoining;
		$dateOfLeaving = $dateOfLeaving;
		$userId = $userId;
		$instituteId = $REQUEST_DATA['defaultInstitute'];
		$isActive = $REQUEST_DATA['isActive'];
		$esiNumber = addslashes($REQUEST_DATA['esiNumber']);
		$bloodGroup = $REQUEST_DATA['bloodGroup'];
		if (is_null($bloodGroup)) {
			$bloodGroup = 0;
		}	
		$remarks = add_slashes(trim($REQUEST_DATA['remarks']));
		//Added by Sachin to accommodate remarks field in database
		
        $guestFaculty = 0;
		if($REQUEST_DATA['department']=='') {
			//echo 'country';
			$department='NULL';
		}
		else {
			$department=$REQUEST_DATA['department'];
		}

        if($REQUEST_DATA['country']=='') {
			$country='NULL';
		}
		else {
			$country=$REQUEST_DATA['country'];
		}
		if($REQUEST_DATA['states']=='') {
			$states='NULL';
		}
		else {
			$states=$REQUEST_DATA['states'];
		}
		if($REQUEST_DATA['city']=='') {
			$city='NULL';
		}
		else {
			$city=$REQUEST_DATA['city'];
		}

		$query = "	INSERT INTO employee (title,
										lastName,
										employeeName,
										middleName,
										employeeCode,
										employeeAbbreviation,
										isTeaching,
										designationId,
										gender,
										branchId,
										departmentId,
										countryId,
										stateId,
										cityId,
										qualification,
										isMarried,
										spouseName,
										fatherName,
										motherName,
										contactNumber,
										mobileNumber,
										emailAddress,
										address1,
										address2,
										panNo,
										religion,
										caste,
										providentFundNo,
										bankName,
										accountNo,
										branchName,
										pinCode,
										dateOfBirth,
										dateOfMarriage,
										dateOfJoining,
										dateOfLeaving,
										userId,
										instituteId,
										isActive,
										visibleToParent,
                                        esiNumber,
										bloodGroup,
                                        guestFaculty,
										remarks)
					VALUES ($title,
							'$lastName',
							'$employeeName',
							'$middleName',
							'$employeeCode',
							'$employeeAbbreviation',
							$isTeaching,
							$designation,
							'$gender',
							$branch,
							$department,
							$country,
							$states,
							$city,
							'$qualification',
							$isMarried,
							'$spouseName',
							'$fatherName',
							'$motherName',
							'$contactNumber',
							'$mobileNumber',
							'$email',
							'$address1',
							'$address2',
							'$panNo',
							'$religion',
							'$caste',
							'$pfNo',
							'$bankName',
							'$accountNo',
							'$branchName',
							'$pin',
							'$dateOfBirth',
							'$dateOfMarriage',
							'$dateOfJoining',
							'$dateOfLeaving',
							$userId,
							$instituteId,
							$isActive,
							$receiveSMS,
                            '$esiNumber',
							'$bloodGroup',
                            '$guestFaculty',
							'$remarks')";
		//Added by Sachin to accommodate remarks field in database
		
	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}


//-------------------------------------------------------------------------------
// addEmployee() function is used for adding new employee into the employee table....
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------

  public function getTotalPublication($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM publication_type
        $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

 public function getPublication($conditions='') {
     
        $query = "SELECT  
	     				publicationId,
    	                publicationName
                    FROM
							publication_type

        $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------------------------------
//
// addEmployee() function is used for adding new employee into the employee table....
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addShortEmployee($userId) {
		global $REQUEST_DATA;
        global $sessionHandler;


		$title = addslashes($REQUEST_DATA['title']);
		$lastName = addslashes($REQUEST_DATA['lastName']);
		$employeeName = addslashes($REQUEST_DATA['employeeName']);
		$middleName = addslashes($REQUEST_DATA['middleName']);
		$employeeCode = addslashes(strtoupper($REQUEST_DATA['employeeCode']));
		$employeeAbbreviation = addslashes($REQUEST_DATA['employeeAbbreviation']);
		$isTeaching = $REQUEST_DATA['isTeaching'];
		$designation = $REQUEST_DATA['designation'];
		$gender = $REQUEST_DATA['gender'];
		$branch = $REQUEST_DATA['branch'];
		$department = $REQUEST_DATA['department'];
		$contactNumber = $REQUEST_DATA['contactNumber'];
		$mobileNumber = $REQUEST_DATA['mobileNumber'];
		$email = $REQUEST_DATA['email'];
		$address1 = addslashes($REQUEST_DATA['address1']);
		$address2 = addslashes($REQUEST_DATA['address2']);
		$userId = $userId;
		$instituteId = $REQUEST_DATA['defaultInstitute'];
        $guestFaculty = 1;
		if($REQUEST_DATA['department']=='') {
			$department='NULL';
		}
		else {
			$department=$REQUEST_DATA['department'];
		}

		$query = "	INSERT INTO employee (title,
										lastName,
										employeeName,
										middleName,
										employeeCode,
										employeeAbbreviation,
										isTeaching,
										designationId,
										gender,
										branchId,
										departmentId,
										contactNumber,
										mobileNumber,
										emailAddress,
										address1,
										address2,
										userId,
										instituteId,
                                        guestFaculty)
					VALUES ($title,
							'$lastName',
							'$employeeName',
							'$middleName',
							'$employeeCode',
							'$employeeAbbreviation',
							$isTeaching,
							$designation,
							'$gender',
							$branch,
							$department,
							'$contactNumber',
							'$mobileNumber',
							'$email',
							'$address1',
							'$address2',
							$userId,
							$instituteId,
                            $guestFaculty)";
	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}


	//-------------------------------------------------------------------------------
//
//addEmployee() function is used for adding new employee into the employee table....
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addEmployeeWithoutUser() {
		global $REQUEST_DATA;
        global $sessionHandler;
        $dateOfBirth = $REQUEST_DATA['employeeYear']."-".$REQUEST_DATA['employeeMonth']."-".$REQUEST_DATA['employeeDate'];
        $dateOfMarriage = $REQUEST_DATA['marriageYear']."-".$REQUEST_DATA['marriageMonth']."-".$REQUEST_DATA['marriageDate'];
        $dateOfJoining = $REQUEST_DATA['joiningYear']."-".$REQUEST_DATA['joiningMonth']."-".$REQUEST_DATA['joiningDate'];
        $dateOfLeaving = $REQUEST_DATA['leavingYear']."-".$REQUEST_DATA['leavingMonth']."-".$REQUEST_DATA['leavingDate'];

		if($dateOfBirth == "--"){
			$dateOfBirth = "0000-00-00";
		}
		if($dateOfMarriage == "--"){
			$dateOfMarriage = "0000-00-00";
		}
		if($dateOfJoining == "--"){
			$dateOfJoining = "0000-00-00";
		}
		if($dateOfLeaving == "--"){
			$dateOfLeaving = "0000-00-00";
		}

		$title = addslashes($REQUEST_DATA['title']);
		$lastName = addslashes($REQUEST_DATA['lastName']);
		$employeeName = addslashes($REQUEST_DATA['employeeName']);
		$middleName = addslashes($REQUEST_DATA['middleName']);
		$employeeCode = addslashes(strtoupper($REQUEST_DATA['employeeCode']));
		$employeeAbbreviation = addslashes($REQUEST_DATA['employeeAbbreviation']);
		$isTeaching = $REQUEST_DATA['isTeaching'];
		$receiveSMS=$REQUEST_DATA['receiveSMS'];
		$designation = $REQUEST_DATA['designation'];
		$gender = $REQUEST_DATA['gender'];
		$branch = $REQUEST_DATA['branch'];
		$department = $REQUEST_DATA['department'];
		$panNo = $REQUEST_DATA['panNo'];
		$religion = $REQUEST_DATA['religion'];
		$caste = $REQUEST_DATA['caste'];
		$pfNo = $REQUEST_DATA['pfNo'];
		$bankName = $REQUEST_DATA['bankName'];
		$accountNo = $REQUEST_DATA['accountNo'];
		$branchName = $REQUEST_DATA['branchName'];
		$country = $country;
		$states = $states;
		$city = $city;
		$qualification = $REQUEST_DATA['qualification'];
		$isMarried = $REQUEST_DATA['isMarried'];
		$spouseName = addslashes($REQUEST_DATA['spouseName']);
		$fatherName = addslashes($REQUEST_DATA['fatherName']);
		$motherName = addslashes($REQUEST_DATA['motherName']);
		$contactNumber = $REQUEST_DATA['contactNumber'];
		$mobileNumber = $REQUEST_DATA['mobileNumber'];
		$email = $REQUEST_DATA['email'];
		$address1 = addslashes($REQUEST_DATA['address1']);
		$address2 = addslashes($REQUEST_DATA['address2']);
		$pin = $REQUEST_DATA['pin'];
		$dateOfBirth = $dateOfBirth;
		$dateOfMarriage = $dateOfMarriage;
		$dateOfJoining = $dateOfJoining;
		$dateOfLeaving = $dateOfLeaving;
		$instituteId = $REQUEST_DATA['defaultInstitute'];
		$isActive = $REQUEST_DATA['isActive'];
        $esiNumber = addslashes($REQUEST_DATA['esiNumber']);
		$bloodGroup = $REQUEST_DATA['bloodGroup'];
		if (is_null($bloodGroup)) {
			$bloodGroup = 0;
		}
		$remarks = add_slashes(trim($REQUEST_DATA['remarks']));
		//Added by Sachin to accommodate remarks field in database
		
		if($REQUEST_DATA['department']=='') {
			$department='NULL';
		}
		else {
			$department=$REQUEST_DATA['department'];
		}

        if($REQUEST_DATA['country']=='') {
			$country='NULL';
		}
		else {
			$country=$REQUEST_DATA['country'];
		}
		if($REQUEST_DATA['states']=='') {
			$states='NULL';
		}
		else {
			$states=$REQUEST_DATA['states'];
		}
		if($REQUEST_DATA['city']=='') {
			$city='NULL';
		}
		else {
			$city=$REQUEST_DATA['city'];
		}

		$query = "	INSERT INTO employee (title,
										lastName,
										employeeName,
										middleName,
										employeeCode,
										employeeAbbreviation,
										isTeaching,
										designationId,
										gender,
										branchId,
										departmentId,
										countryId,
										stateId,
										cityId,
										qualification,
										isMarried,
										spouseName,
										fatherName,
										motherName,
										contactNumber,
										mobileNumber,
										emailAddress,
										address1,
										address2,
										panNo,
										religion,
										caste,
										providentFundNo,
										bankName,
										accountNo,
										branchName,
										pinCode,
										dateOfBirth,
										dateOfMarriage,
										dateOfJoining,
										dateOfLeaving,
										userId,
										instituteId,
										isActive,
										visibleToParent,
                                        esiNumber,
										bloodGroup,
                    remarks                    )
					VALUES ($title,
							'$lastName',
							'$employeeName',
							'$middleName',
							'$employeeCode',
							'$employeeAbbreviation',
							$isTeaching,
							$designation,
							'$gender',
							$branch,
							$department,
							$country,
							$states,
							$city,
							'$qualification',
							$isMarried,
							'$spouseName',
							'$fatherName',
							'$motherName',
							'$contactNumber',
							'$mobileNumber',
							'$email',
							'$address1',
							'$address2',
							'$panNo',
							'$religion',
							'$caste',
							'$pfNo',
							'$bankName',
							'$accountNo',
							'$branchName',
							'$pin',
							'$dateOfBirth',
							'$dateOfMarriage',
							'$dateOfJoining',
							'$dateOfLeaving',
							'$userId',
							$instituteId,
							$isActive,
							$receiveSMS,
                            '$esiNumber',
							'$bloodGroup',
              '$remarks'              )";
		//Added by Sachin to accommodate remarks field in database
		
	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

 //-------------------------------------------------------------------------------
//
//editUser() function is used for edit the existing user into the user table....
// $userid is used as the unique identification of the existing user data
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
   public function editUser($userid){
        global $REQUEST_DATA;
		$roleId = $REQUEST_DATA['roleName'];
		$isActive = $REQUEST_DATA['isActive'];
		$instituteId = $REQUEST_DATA['defaultInstitute'];
        if ($REQUEST_DATA['userPassword']!='********') {
            $password= $REQUEST_DATA['userPassword'];
            $password = md5($password);
			 $query = "	UPDATE user SET userPassword = '$password',
										roleId = $roleId,
										userStatus = $isActive,
										instituteId = $instituteId
										WHERE userId= $userid";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
        }
        else {
			$query = "	UPDATE user SET roleId = $roleId,
										userStatus = $isActive,
										instituteId = $instituteId
										WHERE userId= $userid";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
        }
   }


   //-------------------------------------------------------------------------------
//
//editShortEmployeeUser() function is used for edit the existing user into the user table....
// $userid is used as the unique identification of the existing user data
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
   public function editShortEmployeeUser($userid){
        global $REQUEST_DATA;
		$roleId = $REQUEST_DATA['roleName'];
		$isActive = $REQUEST_DATA['isActive'];
		$instituteId = $REQUEST_DATA['defaultInstitute'];
        if ($REQUEST_DATA['userPassword']!='********') {
            $password= $REQUEST_DATA['userPassword'];
            $password = md5($password);
			 $query = "	UPDATE user SET userPassword = '$password',
										roleId = $roleId,
										instituteId = $instituteId
										WHERE userId= $userid";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
        }
        else {
			$query = "	UPDATE user SET roleId = $roleId,
										instituteId = $instituteId
										WHERE userId= $userid";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
        }
   }


//-------------------------------------------------------------------------------
//
//editEmployee() function is used for edit existing employee into the employee table....
// $id is used as the unique identification of the existing employee data
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editEmployee($id,$conditions='') {
        global $REQUEST_DATA;
        $dateOfBirth = $REQUEST_DATA['employeeYear']."-".$REQUEST_DATA['employeeMonth']."-".$REQUEST_DATA['employeeDate'];
        $dateOfMarriage = $REQUEST_DATA['marriageYear']."-".$REQUEST_DATA['marriageMonth']."-".$REQUEST_DATA['marriageDate'];
        $dateOfJoining = $REQUEST_DATA['joiningYear']."-".$REQUEST_DATA['joiningMonth']."-".$REQUEST_DATA['joiningDate'];
        $dateOfLeaving = $REQUEST_DATA['leavingYear']."-".$REQUEST_DATA['leavingMonth']."-".$REQUEST_DATA['leavingDate'];

		$title = addslashes($REQUEST_DATA['title']);
		$lastName = addslashes($REQUEST_DATA['lastName']);
		$employeeName = addslashes($REQUEST_DATA['employeeName']);
		$middleName = addslashes($REQUEST_DATA['middleName']);
		$employeeCode = addslashes(strtoupper($REQUEST_DATA['employeeCode']));
		$employeeAbbreviation = addslashes($REQUEST_DATA['employeeAbbreviation']);
		$isTeaching = $REQUEST_DATA['isTeaching'];
		$designation = $REQUEST_DATA['designation'];
		$gender = $REQUEST_DATA['gender'];
		$branch = $REQUEST_DATA['branch'];
		$department = $REQUEST_DATA['department'];
		$panNo = $REQUEST_DATA['panNo'];
		$religion = $REQUEST_DATA['religion'];
		$caste = $REQUEST_DATA['caste'];
		$pfNo = $REQUEST_DATA['pfNo'];
		$bankName = $REQUEST_DATA['bankName'];
		$accountNo = $REQUEST_DATA['accountNo'];
		$branchName = $REQUEST_DATA['branchName'];
		$country = $country;
		$states = $states;
		$city = $city;
		$qualification = $REQUEST_DATA['qualification'];
		$isMarried = $REQUEST_DATA['isMarried'];
		$spouseName = addslashes($REQUEST_DATA['spouseName']);
		$fatherName = addslashes($REQUEST_DATA['fatherName']);
		$motherName = addslashes($REQUEST_DATA['motherName']);
		$contactNumber = $REQUEST_DATA['contactNumber'];
		$mobileNumber = $REQUEST_DATA['mobileNumber'];
		$email = $REQUEST_DATA['email'];
		$address1 = addslashes($REQUEST_DATA['address1']);
		$address2 = addslashes($REQUEST_DATA['address2']);
		$pin = $REQUEST_DATA['pin'];
		$dateOfBirth = $dateOfBirth;
		$dateOfMarriage = $dateOfMarriage;
		$dateOfJoining = $dateOfJoining;
		$dateOfLeaving = $dateOfLeaving;
		$instituteId = $REQUEST_DATA['defaultInstitute'];
		$isActive = $REQUEST_DATA['isActive'];
		$receiveSMS=$REQUEST_DATA['receiveSMS'];
        $esiNumber = addslashes($REQUEST_DATA['esiNumber']);
		logError("xxxxx".$esiNumber);
		$bloodGroup = $REQUEST_DATA['bloodGroup'];
		if (is_null($bloodGroup)) {
			$bloodGroup = 0;
		}
		$remarks = add_slashes(trim($REQUEST_DATA['remarks']));
		//Added by Sachin to accommodate remarks field in database
		if($REQUEST_DATA['department']=='') {
			//echo 'country';
			$department='NULL';
		}
		else {
			$department=$REQUEST_DATA['department'];
		}
		if($REQUEST_DATA['country']=='') {
			//echo 'country';
			$country='NULL';
		}
		else {
			$country=$REQUEST_DATA['country'];
		}
		if($REQUEST_DATA['states']=='') {
			//echo 'states';
			$states='NULL';
		}
		else {
			$states=$REQUEST_DATA['states'];
		}
		if($REQUEST_DATA['city']=='') {
			//echo 'city';
			$city='NULL';
		}
		else {
			$city=$REQUEST_DATA['city'];
		}

		if($dateOfBirth == "--"){
			$dateOfBirth = "0000-00-00";
		}
		if($dateOfMarriage == "--"){
			$dateOfMarriage = "0000-00-00";
		}
		if($dateOfJoining == "--"){
			$dateOfJoining = "0000-00-00";
		}
		if($dateOfLeaving == "--"){
			$dateOfLeaving = "0000-00-00";
		}

		 $query = "	UPDATE employee SET title = '$title',
										lastName = '$lastName',
										employeeName = '$employeeName',
										middleName = '$middleName',
										employeeCode = '$employeeCode',
										employeeAbbreviation = '$employeeAbbreviation',
										isTeaching = $isTeaching,
										instituteId = $instituteId,
										designationId = $designation,
										gender = '$gender',
										branchId = $branch,
										departmentId = $department,
										countryId = $country,
										stateId = $states,
										cityId = $city,
										qualification = '$qualification',
										isMarried = $isMarried,
										spouseName = '$spouseName',
										fatherName = '$fatherName',
										motherName = '$motherName',
										contactNumber = '$contactNumber',
										mobileNumber = '$mobileNumber',
										emailAddress = '$email',
										address1 = '$address1',
										address2 = '$address2',
										panNo = '$panNo',
										religion = '$religion',
										caste = '$caste',
										providentFundNo = '$pfNo',
										bankName = '$bankName',
										accountNo = '$accountNo',
										branchName = '$branchName',
										pinCode = '$pin',
										dateOfBirth = '$dateOfBirth',
										dateOfMarriage = '$dateOfMarriage',
										dateOfJoining = '$dateOfJoining',
										dateOfLeaving = '$dateOfLeaving',
										isActive = $isActive,
										visibleToParent=$receiveSMS,				
					                                        esiNumber='$esiNumber',
																									remarks='$remarks',
										bloodGroup='$bloodGroup'
										$conditions
										WHERE employeeId = $id";
//remarks column Added by Sachin to accommodate remarks field in database
		//echo $query;
		//die;
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//editShortEmployee() function is used for edit existing short employee into the employee table....
// $id is used as the unique identification of the existing employee data
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function editShortEmployee($id,$conditions='') {
        global $REQUEST_DATA;
			//echo($REQUEST_DATA['country']);

		$title = addslashes($REQUEST_DATA['title']);
		$lastName = addslashes($REQUEST_DATA['lastName']);
		$employeeName = addslashes($REQUEST_DATA['employeeName']);
		$middleName = addslashes($REQUEST_DATA['middleName']);
		$employeeCode = addslashes(strtoupper($REQUEST_DATA['employeeCode']));
		$employeeAbbreviation = addslashes($REQUEST_DATA['employeeAbbreviation']);
		$isTeaching = $REQUEST_DATA['isTeaching'];
		$designation = $REQUEST_DATA['designation'];
		$gender = $REQUEST_DATA['gender'];
		$branch = $REQUEST_DATA['branch'];
		$department = $REQUEST_DATA['department'];
		$contactNumber = $REQUEST_DATA['contactNumber'];
		$mobileNumber = $REQUEST_DATA['mobileNumber'];
		$email = $REQUEST_DATA['email'];
		$address1 = addslashes($REQUEST_DATA['address1']);
		$address2 = addslashes($REQUEST_DATA['address2']);
		$instituteId = $REQUEST_DATA['defaultInstitute'];
		if($REQUEST_DATA['department']=='') {
			$department='NULL';
		}
		else {
			$department=$REQUEST_DATA['department'];
		}

		 $query = "	UPDATE employee SET title = '$title',
										lastName = '$lastName',
										employeeName = '$employeeName',
										middleName = '$middleName',
										employeeCode = '$employeeCode',
										employeeAbbreviation = '$employeeAbbreviation',
										isTeaching = $isTeaching,
										instituteId = $instituteId,
										designationId = $designation,
										gender = '$gender',
										branchId = $branch,
										departmentId = $department,
										contactNumber = '$contactNumber',
										mobileNumber = '$mobileNumber',
										emailAddress = '$email',
										address1 = '$address1',
										address2 = '$address2'
										WHERE employeeId = $id
										$conditions";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }

    public function getUser($employeeid)
    {
        $query="SELECT userId
        from employee
        WHERE employeeId='$employeeid'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

    }

//-------------------------------------------------------------------------------
//
//getEmployee() function is used for getting the value of employee table....
//
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getEmployee($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

     $query = "SELECT
						emp.employeeId,
						emp.instituteId,
						emp.userId,
						emp.title,
						emp.lastName,
						emp.employeeName,
						emp.middleName,
						emp.employeeCode,
						emp.employeeAbbreviation,
						emp.isTeaching,
						desg.designationId,
						desg.designationName,
						emp.gender,
						br.branchId,
						if(emp.departmentId IS NULL,'',emp.departmentId) as departmentId,
						br.branchCode,
						st.stateName,
						emp.pinCode,
						emp.qualification,
						emp.isMarried,
						emp.spouseName,
						emp.fatherName,
						emp.motherName,
						emp.contactNumber,
						emp.emailAddress,
						emp.mobileNumber,
						emp.address1,
						emp.address2,
						emp.panNo,
						emp.religion,
						emp.caste,
						emp.providentFundNo,
						emp.bankName,
						emp.accountNo,
                        emp.esiNumber,
						emp.branchName,
						if(emp.employeeImage IS NULL OR emp.employeeImage='',-1,emp.employeeImage) as employeeImage,
						if(emp.thumbImage IS NULL OR emp.thumbImage='',-1,emp.thumbImage) as thumbImage,
						ct.cityName,
						cn.countryName,
						if(ct.cityId IS NULL,'',ct.cityId) AS cityId,
						if(cn.countryId IS NULL,'',cn.countryId) AS countryId,
						if(st.stateId IS NULL,'',st.stateId) AS stateId,
						us.userName,
						us.userPassword,
						us.roleId,
						SUBSTRING(dateOfBirth,1,4) as employeeYear, SUBSTRING(dateOfBirth,6,2) as employeeMonth, SUBSTRING(dateOfBirth,9,2) as employeeDate, SUBSTRING(dateOfMarriage,1,4) as marriageYear, SUBSTRING(dateOfMarriage,6,2) as marriageMonth, SUBSTRING(dateOfMarriage,9,2) as marriageDate, SUBSTRING(dateOfJoining,1,4) as joiningYear, SUBSTRING(dateOfJoining,6,2) as joiningMonth, SUBSTRING(dateOfJoining,9,2) as joiningDate, SUBSTRING(dateOfLeaving,1,4) as leavingYear, SUBSTRING(dateOfLeaving,6,2) as leavingMonth, SUBSTRING(dateOfLeaving,9,2) as leavingDate, emp.isActive,emp.visibleToParent, ect.instituteId,
						emp.bloodGroup,
						emp.remarks
			FROM		designation desg,
						branch br,
						employee emp
						LEFT JOIN employee_can_teach_in ect ON (emp.employeeId = ect.employeeId)
						LEFT JOIN states st ON (emp.stateId = st.stateId)
						LEFT JOIN city ct ON (emp.cityId = ct.cityId)
						LEFT JOIN countries cn ON (emp.countryId = cn.countryId)
						LEFT JOIN user us ON (emp.userId = us.userId)
						LEFT JOIN role r ON (us.roleId = r.roleId)
			WHERE		emp.designationId = desg.designationId
			AND			emp.branchId = br.branchId
						$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------------------
//
//getPreviousRoleArray() function is used for getting the previous roleId
// Created on : 17.02.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getPreviousRoleArray($userId,$teachingininstitutes){
		$query = "SELECT			defaultRoleId
						FROM		`user_role`
						WHERE		userId=$userId
						AND			instituteId IN ($teachingininstitutes)
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
//-------------------------------------------------------------------------------
//
//selectUserRoleExistance() function is used for checking whether the role exists or not
// Created on : 17.02.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function selectUserRoleExistance($employeeRoleId,$teachingininstitutes,$userId){
        
        if($teachingininstitutes=='') {
          $teachingininstitutes='0';  
        }
        
        
		$query = "
					SELECT			userId
						FROM		`user_role`
						WHERE		userId = '$userId'
						AND			roleId = '$employeeRoleId'
						AND			instituteId IN($teachingininstitutes)
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}
//-------------------------------------------------------------------------------
//
//selectInsituteId() function is used for geting the insitute ID
// Created on : 17.02.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function selectInsituteId($userId,$employeeRoleId,$instituteId){
		$query ="
				SELECT			instituteId
					FROM		`user_role`
					WHERE		userId='$userId'
					AND			roleId = '$employeeRoleId'
					AND			instituteId = '$instituteId'
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
	//-------------------------------------------------------------------------------
//
//insertUserRole() function is used for inserting the user role
// Created on : 17.02.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function insertIntoUserRole($employeeId,$employeeRoleId,$teachingininstitutes,$userId){

		$query = "INSERT
						INTO		`user_role` (userId,roleId,defaultRoleId,instituteId)
										VALUES	($userId,$employeeRoleId,$employeeRoleId,$teachingininstitutes)
				";

		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");

	}
		//-------------------------------------------------------------------------------
//
//deleteFromUserRole() function is used for deleting the user role
// Created on : 17.02.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function deleteFromUserRole($teachingininstitutes,$userId){
		$query = "DELETE	FROM `user_role`
							WHERE	userId = '$userId'
							AND		instituteId NOT IN($teachingininstitutes)";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}
//-------------------------------------------------------------------------------
//
//updateUserRole() function is used for updating the user role
// Created on : 17.02.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function updateUserRole($employeeId,$employeeRoleId,$teachingininstitutes,$previousRoleId,$userId){
		$query = "
					update `user_role`
							SET		roleId = '$employeeRoleId'
							WHERE	roleId = '$previousRoleId'
							AND		userId = '$userId'
							AND		instituteId IN($teachingininstitutes)
				";
				//echo $query; die;
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}
//-------------------------------------------------------------------------------
//
//updateDefaultRoleId() function is used for updating the defaultRole ID
// Created on : 17.02.11
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function updateDefaultRoleId($employeeId,$employeeRoleId,$userId,$previousRoleId){
		$query = "
					UPDATE `user_role`
							SET		defaultRoleId = '$employeeRoleId'
							WHERE	userId = '$userId'
				";
		return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
//
//getEmployee() function is used for getting the value of employee table....
//
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getShortEmployee($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

		 $query = "SELECT
						emp.employeeId,
						emp.instituteId,
						emp.userId,
						emp.title,
						emp.lastName,
						emp.employeeName,
						emp.middleName,
						emp.employeeCode,
						emp.employeeAbbreviation,
						emp.isTeaching,
						desg.designationId,
						desg.designationName,
						emp.gender,
						br.branchId,
						if(emp.departmentId IS NULL,'',emp.departmentId) as departmentId,
						br.branchCode,
						emp.pinCode,
						emp.contactNumber,
						emp.emailAddress,
						emp.mobileNumber,
						emp.address1,
						emp.address2,
						us.userName,
						us.userPassword,
						us.roleId,
						ect.instituteId
			FROM		designation desg,
						branch br,
						employee emp
						LEFT JOIN employee_can_teach_in ect ON (emp.employeeId = ect.employeeId)
						LEFT JOIN user us ON (emp.userId = us.userId)
						LEFT JOIN role r ON (us.roleId = r.roleId)
			WHERE		emp.designationId = desg.designationId
			AND			emp.branchId = br.branchId
						$conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//deleteEmployee() function is used to delete records from Employee....
// $employeeId - used to generate the unique id of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteEmployee($employeeId) {

        $query = "DELETE
        FROM employee
        WHERE employeeId=$employeeId";

		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//deleteUser() function is used to delete records from user table
// $userId - used to generate the unique id of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteUser($resultId){
       $query = "DELETE
        FROM user
        WHERE userId=$resultId";

		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//deleteEmployeeCanTeachIn() function is used to delete records from employee_can_teach_in table
//
// Author : Jaineesh
// Created on : 15.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteEmployeeCanTeachIn($employeeId){
     $query = "		DELETE
					FROM	employee_can_teach_in
					WHERE	employeeId=$employeeId";

		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


//-------------------------------------------------------------------------------


//
//deleteUserLog() function is used to delete records from user_log table
//
// Author : Jaineesh
// Created on : 10.08.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteUserLog($resultId){
   $query = "		DELETE
					FROM	user_log
					WHERE	userId=$resultId";

		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//deleteUserPref() function is used to delete records from user_prefs table
//
// Author : Jaineesh
// Created on : 10.08.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteUserPrefs($resultId){
     $query = "		DELETE
					FROM	user_prefs
					WHERE	userId=$resultId";

		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//deleteUserRole() function is used to delete records from user_role table
//
// Author : Jaineesh
// Created on : 10.09.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteUserRole($resultId){
     $query = "		DELETE
					FROM	user_role
					WHERE	userId=$resultId";

		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//deleteQueryLog() function is used to delete records from user_prefs table
//
// Author : Jaineesh
// Created on : 10.08.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function deleteQueryLog($resultId){
   $query = "		DELETE
					FROM	query_log
					WHERE	userId=$resultId";

		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getEmployeeList() function is used to list the records of Employee table
// $condtions - used to check condition while selecting the records
// $limit - used to check the limit of showing records in list
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//IF(br.branchCode=\" \",'---', br.branchCode) as branchCode,
//-------------------------------------------------------------------------------


    public function getEmployeeList($conditions='', $limit = '', $orderBy=' emp.employeeName') {
         global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "SELECT
                          DISTINCT (emp.employeeId),
						  emp.userId,
						  CONCAT(emp.employeeName,' ',emp.middleName,' ',emp.lastName) AS employeeName,
						  emp.employeeCode,
						  emp.employeeAbbreviation,
                          IF(emp.isTeaching=1, 'Yes', 'No') as isTeaching,
						  desg.designationName,
						  emp.gender,
                          IF(br.branchCode=\" \",'---', br.branchCode) as branchCode,
						  st.stateName,
						  emp.pinCode,
						  emp.qualification,
                          emp.isMarried,
						  emp.spouseName,
						  emp.fatherName,
						  emp.motherName,
                          IF(emp.contactNumber=\" \",'---',emp.contactNumber) as contactNumber,
                          IF(emp.emailAddress=\" \", '---', emp.emailAddress) as emailAddress,
						  IF(emp.mobileNumber=\" \",'---',emp.mobileNumber) as mobileNumber,
						  emp.address1,
						  emp.address2,
						  ct.cityName,
						  cn.countryName,
						  emp.employeeImage,
                          emp.isActive,emp.visibleToParent,
						  emp.dateOfBirth,
						  emp.dateOfMarriage,
						  emp.dateOfJoining,
						  emp.dateOfLeaving,
                          emp.employeePhoto,
                          emp.guestFaculty,
						  emp.departmentId,
                          IF(emp.guestFaculty=1, 'Yes', 'No') as guestFacultyDisplay,
						  (SELECT abbr FROM department d WHERE  emp.departmentId=d.departmentId ) AS departmentAbbr,
                          IF(IFNULL(emp.departmentId,'')='','".NOT_APPLICABLE_STRING."',
                                    (SELECT abbr FROM department d WHERE  emp.departmentId=d.departmentId )) AS departmentAbbr,
                          (SELECT
                                    GROUP_CONCAT(DISTINCT ii.instituteCode)
                           FROM
                                    employee_can_teach_in et, institute ii
                           WHERE
                                    et.instituteId = ii.instituteId AND et.employeeId=emp.employeeId)  AS teachingInstitutes,
                          us.userName, r.roleName
                  FROM
                          designation desg, branch br, employee emp
                          LEFT JOIN employee_can_teach_in ect ON (emp.employeeId = ect.employeeId)
                          LEFT JOIN states st ON (emp.stateId = st.stateId)
                          LEFT JOIN city ct ON (emp.cityId = ct.cityId)
                          LEFT JOIN countries cn ON (emp.countryId = cn.countryId)
						  LEFT JOIN user us ON (emp.userId = us.userId)
						  LEFT JOIN role r ON (us.roleId = r.roleId)
                  WHERE
							emp.designationId = desg.designationId
							AND	emp.branchId=br.branchId
							AND emp.instituteId= $instituteId
							$conditions
                  ORDER BY
                          $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalEmployee() function returns the total no. of records
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getTotalEmployee($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT COUNT(distinct emp.employeeId) AS totalRecords
        FROM
                          designation desg, branch br, employee emp
                          LEFT JOIN employee_can_teach_in ect ON (emp.employeeId = ect.employeeId)
                          LEFT JOIN states st ON (emp.stateId = st.stateId)
                          LEFT JOIN city ct ON (emp.cityId = ct.cityId)
                          LEFT JOIN countries cn ON (emp.countryId = cn.countryId)
						  LEFT JOIN user us ON (emp.userId = us.userId)
						  LEFT JOIN role r ON (us.roleId = r.roleId)
                  WHERE
							emp.designationId = desg.designationId
							AND	emp.branchId=br.branchId
							AND emp.instituteId= $instituteId $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getEmployeeList() function is used to list the records of Employee table
// $condtions - used to check condition while selecting the records
// $limit - used to check the limit of showing records in list
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


    public function getShortEmployeeList($conditions='', $limit = '', $orderBy=' emp.employeeName') {
         global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

         $query = "SELECT
                          DISTINCT (emp.employeeId),
						  emp.userId,
						  CONCAT(emp.employeeName,' ',emp.middleName,' ',emp.lastName) AS employeeName,
						  emp.employeeCode,
						  emp.employeeAbbreviation,
                          IF(emp.isTeaching=1, 'Yes', 'No') as isTeaching,
						  desg.designationName,
						  emp.gender,
                          IF(br.branchCode=\" \",'---', br.branchCode) as branchCode,
                          IF(emp.contactNumber=\" \",'---',emp.contactNumber) as contactNumber,
                          IF(emp.emailAddress=\" \", '---', emp.emailAddress) as emailAddress,
						  IF(emp.mobileNumber=\" \",'---',emp.mobileNumber) as mobileNumber,
						  emp.address1,
						  emp.address2,
						  emp.departmentId,
                          IF(emp.guestFaculty=1, 'Yes', 'No') as guestFaculty,
                          IFNULL(d.abbr,'".NOT_APPLICABLE_STRING."') AS departmentAbbr,
                          (SELECT
                                    GROUP_CONCAT(DISTINCT ii.instituteCode)
                           FROM
                                    employee_can_teach_in et, institute ii
                           WHERE
                                    et.instituteId = ii.instituteId AND et.employeeId=emp.employeeId)  AS teachingInstitutes,
                          us.userName, r.roleName
                  FROM
                          designation desg, branch br, employee emp
                          LEFT JOIN employee_can_teach_in ect ON (emp.employeeId = ect.employeeId)
						  LEFT JOIN user us ON (emp.userId = us.userId)
						  LEFT JOIN role r ON (us.roleId = r.roleId)
                          LEFT JOIN department d ON d.departmentId=emp.departmentId
                  WHERE
							emp.designationId = desg.designationId
							AND	emp.branchId=br.branchId
							AND emp.instituteId= $instituteId
							$conditions
                  ORDER BY
                          $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//getTotalEmployee() function returns the total no. of records
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getTotalShortEmployee($conditions='') {
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "	SELECT	COUNT(distinct emp.employeeId) AS totalRecords
					FROM	designation desg, branch br, employee emp
							LEFT JOIN employee_can_teach_in ect ON (emp.employeeId = ect.employeeId)
							LEFT JOIN user us ON (emp.userId = us.userId)
							LEFT JOIN role r ON (us.roleId = r.roleId)
                            LEFT JOIN department d ON d.departmentId=emp.departmentId
					WHERE
							emp.designationId = desg.designationId
							AND	emp.branchId=br.branchId
							AND emp.instituteId= $instituteId $conditions";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getUserName() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getUserName($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT COUNT(*) as found FROM user $conditions ";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//getUserDetailEmployee() function returns user role
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 28.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getUserDetailEmployee($userId) {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
        $query = "SELECT * FROM user WHERE userId = $userId";

        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//UserRole() function is used to add values in user role
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 28.06.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function addEmployeeUserRole($employeeUserId,$employeeRoleId,$teachingininstitutes) {

		if ($teachingininstitutes != '' ) {
			$teachingInstituteId = explode(',', $teachingininstitutes);
			$length = count($teachingInstituteId);
			$intStr = "";
			for ($i=0; $i<$length; $i++) {
				if ($intStr =="") {
				$intStr = "INSERT INTO user_role (userId,roleId,defaultRoleId,instituteId) VALUES ($employeeUserId,$employeeRoleId,$employeeRoleId,".$teachingInstituteId[$i].")";
				}
				else {
					$intStr = $intStr.", ($employeeUserId,$employeeRoleId,$employeeRoleId,".$teachingInstituteId[$i].")";
				}
			}
		}
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($intStr,"Query: $query");

}

//-------------------------------------------------------------------------------
//
//getUserName() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 15.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function addEmployeeCanTeachIn($employeeCanTeachId,$teachingininstitutes) {

		if ($teachingininstitutes != '' ) {

			$teachingInstituteId = explode(',', $teachingininstitutes);
			$length = count($teachingInstituteId);
			$intStr = "";
			for ($i=0; $i<$length; $i++) {
				if ($intStr =="") {
				$intStr = "INSERT INTO employee_can_teach_in (employeeId, instituteId) VALUES ($employeeCanTeachId,".$teachingInstituteId[$i].")";
				}
				else {
					$intStr = $intStr.", ($employeeCanTeachId,".$teachingInstituteId[$i].")";
				}
			}
		}
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($intStr,"Query: $query");

}


//-------------------------------------------------------------------------------
//
//getUserName() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 15.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function addUploadEmployeeCanTeachIn($employeeId) {

	global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');

	 $query = "INSERT INTO employee_can_teach_in (employeeId, instituteId) VALUES ($employeeId, $instituteId)";

	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");

}

//-------------------------------------------------------------------------------
//
//addUploadUserRole() function used to INSERT into User ROle Table
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 15.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function addUploadUserRole($userId,$roleId) {

	global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');

	 $query = "INSERT INTO user_role (userId, roleId, defaultRoleId, instituteId) VALUES ($userId, $roleId, $roleId, $instituteId)";

	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");

}

//-------------------------------------------------------------------------------
//
//updateEmployee() function update isActive
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 15.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

public function updateEmployee($employeeCanTeachId) {

			$query = "	UPDATE	employee
						SET		isActive = 0
						WHERE	employeeId = $employeeCanTeachId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
}

//-------------------------------------------------------------------------------
//
//updateUser() function update isActive
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 15.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

public function updateUser($userId) {

			$query = "	UPDATE	user
						SET		userStatus = 0
						WHERE	userId = $userId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
}

//-------------------------------------------------------------------------------
//
//updateUserSatus() function update user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 21.07.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

public function updateUserStatus($userId) {

			$query = "	UPDATE	user
						SET		userStatus = 0
						WHERE	userId = $userId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
}

//-------------------------------------------------------------------------------
//
//updateEmployee() function update isActive
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 15.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

public function updateEmployeeActive($employeeCanTeachId) {

			$query = "	UPDATE	employee
						SET		isActive = 1
						WHERE	employeeId = $employeeCanTeachId";

  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");

}

//-------------------------------------------------------------------------------
//
//updateUserActive() function update userStatus of user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 21.07.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

public function updateUserActive($userId) {

			$query = "	UPDATE	user
						SET		userStatus = 1
						WHERE	userId = $userId";

  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");

}

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET employee id IS USING IN ANOTHER TABLE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (24.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------

    public function getCheckEmployee($conditions='') {
	  global $sessionHandler;
      $instituteId = $sessionHandler->getSessionVariable('InstituteId');
	  $sessionId = $sessionHandler->getSessionVariable('SessionId');

	  $query = "    SELECT  count(emp.employeeId) as employeeId
                    FROM    employee emp,
                             ".TIME_TABLE_TABLE."  tt
                    WHERE   emp.employeeId = tt.employeeId
					AND		tt.instituteId = $instituteId
					AND		tt.sessionId = $sessionId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET employee id IS USING WITH INSTITUTE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (24.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------

    public function getCheckEmployeeWithInstitute($conditions='') {

    $query = "    SELECT    count(emp.employeeId) as employeeId
                    FROM    employee emp,
                            institute ins
                    WHERE   emp.employeeId = ins.employeeId
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET employee id IS USING WITH INSTITUTE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (24.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------

    public function getCheckEmployeeWithExperience($conditions='') {

    $query = "    SELECT    count(employeeId) as employeeId
                    FROM    employee_experience
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET employee id IS USING WITH INSTITUTE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (24.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------

    public function getCheckEmployeeWithQualification($conditions='') {

    $query = "    SELECT    count(employeeId) as employeeId
                    FROM    employee_qualification
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

// Publishing Information    Start

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Publishing
//
// Author :Parveen Sharma
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function addPublishing($employeeId) {
        global $REQUEST_DATA;

      $query="INSERT INTO publishing (type,scopeId,publishOn,publishedBy,description,employeeId)
      VALUES('".$REQUEST_DATA['type']."','".$REQUEST_DATA['scopeId']."','".$REQUEST_DATA['publishOn']."','".$REQUEST_DATA['publishedBy']."','".$REQUEST_DATA['description']."','".$employeeId."')";

      return SystemDatabaseManager::getInstance()->executeUpdate($query);

    }

// purpose: To update filename(for file) in 'publisher' table
// author: Parveen Sharma
// Params: Id (publisher ID), filename (name of the file)
// returns: boolean value

    public function updateFilenameInPublisher($id, $fileName) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('publishing',
        array('attachmentFile'),
        array($fileName), "publishId = $id");
    }


// purpose: To update Attachment Acceptation Letter(for file name) in 'publisher' table
// author: Parveen Sharma
// Params: Id (publisher ID), filename (name of the file)
// returns: boolean value

    public function updateAccpLetFilenameInPublisher($id, $fileName) {
        return SystemDatabaseManager::getInstance()->runAutoUpdate('publishing',
        array('attachmentAcceptationLetter'),
        array($fileName), "publishId = $id");
    }


    //--------------------------------------------------------------------------------------------------------------
    // THIS FUNCTION IS USED FOR delete deletePublisherFailedUpload when file not upload
    // Author :Parveen Sharma
    // Created on : (17.08.2009)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //---------------------------------------------------------------------------------------------------------------
    public function deletePublisherFailedUpload($id) {
        global $REQUEST_DATA;

        $query="DELETE FROM publishing WHERE publishId=$id ";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Publishing
//
// Author :Parveen Sharma
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editPublishing($id) {
        global $REQUEST_DATA;

        $query="UPDATE publishing SET
                                    type ='".$REQUEST_DATA['type']."',
                                    scopeId ='".$REQUEST_DATA['scopeId']."',
                                    publishOn ='".$REQUEST_DATA['publishOn']."',
                                    publishedBy = '".$REQUEST_DATA['publishedBy']."',
                                    description = '".$REQUEST_DATA['description']."'
                WHERE publishId=".$id;

       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Publishing
//
// Author :Parveen Sharma
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deletePublishing($Id) {

        $query = "DELETE
        FROM publishing
        WHERE publishId='$Id'";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Publishing
//
// Author :Parveen Sharma
// Created on : (05.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getPublishing($conditions='') {

      $query = "    SELECT
                            p.publishId,  p.type,  p.publishOn,  p.publishedBy, p.description, p.scopeId,
                            e.employeeName,  e.employeeCode, d.designationName, e.employeeId,
                            IFNULL(p.attachmentFile,'') AS attachmentFile, IFNULL(p.attachmentAcceptationLetter,'') AS attachmentAcceptationLetter
                    FROM
                            publishing p,
                            employee e LEFT JOIN designation d ON e.designationId = d.designationId
                    WHERE
                            p.employeeId = e.employeeId
                    $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Publishing List
//
// Author :Parveen Sharma
// Created on : (19.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getPublishingList($filter='', $orderBy='',$limit = '') {

        $query = "SELECT
                          p.publishId,  p.type,  p.publishOn,  p.publishedBy, p.description, pt.publicationName,
                          IFNULL(p.scopeId,'') AS scopeId, e.employeeName,  e.employeeCode, e.employeeId,
                          IFNULL(p.attachmentFile,'') AS attachmentFile, IFNULL(p.attachmentAcceptationLetter,'') AS attachmentAcceptationLetter
                  FROM    publishing p,
                          employee e,
						  publication_type pt
                  WHERE   p.employeeId = e.employeeId
				  AND     p.scopeId = pt.publicationId
                          $filter
                  ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Publishing
//
// Author :Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalPublishing($filter='') {

       $query = "    SELECT    COUNT(*) AS totalRecords
                    FROM    publishing p,
                            employee e
                    WHERE    p.employeeId = e.employeeId
                            $filter  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE DETAIL
//
// Author :Parveen Sharma
// Created on : (04.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getEmployeeDetail($conditions='') {

        global $sessionHandler;

        $query = " SELECT   emp.employeeId,
                            emp.employeeCode,
                            emp.employeeName,
                            desg.designationName
                    FROM    employee emp LEFT JOIN designation desg ON emp.designationId = desg.designationId
                    $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

// Publishing Information    End



// Consulting Information   Start

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Consulting
//
// Author :Parveen Sharma
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function addConsulting($employeeId) {
      global $REQUEST_DATA;

      $query="INSERT INTO consulting (projectName,sponsorName,startDate,endDate,amountFunding,remarks,employeeId)
      VALUES('".$REQUEST_DATA['projectName']."','".$REQUEST_DATA['sponsorName']."','".$REQUEST_DATA['startDate']."','".$REQUEST_DATA['endDate']."','".$REQUEST_DATA['amountFunding']."','".$REQUEST_DATA['remarks']."','".$employeeId."')";

      return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Consulting
//
// Author :Parveen Sharma
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editConsulting($id) {
        global $REQUEST_DATA;

        $query="UPDATE consulting SET    projectName ='".$REQUEST_DATA['projectName']."',
                                        sponsorName ='".$REQUEST_DATA['sponsorName']."',
                                        startDate = '".$REQUEST_DATA['startDate']."',
                                        endDate = '".$REQUEST_DATA['endDate']."',
                                        amountFunding = '".$REQUEST_DATA['amountFunding']."',
                                        remarks = '".$REQUEST_DATA['remarks']."',
                                        employeeId = '".$REQUEST_DATA['employeeId']."'
                WHERE consultId=".$id;
       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Consulting
//
// Author :Parveen Sharma
// Created on : (05.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteConsulting($Id) {

        $query = "DELETE FROM consulting
                  WHERE consultId=$Id ";

        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Consulting
//
// Author :Parveen Sharma
// Created on : (05.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getConsulting($conditions='') {

      $query = "SELECT    c.consultId, c.projectName, c.sponsorName, c.startDate,c.endDate, c.amountFunding, c.remarks, c.employeeId,
                        e.employeeName, e.employeeCode,    d.designationName
                FROM    consulting c,
                        employee e LEFT JOIN designation d ON e.designationId = d.designationId
                WHERE    c.employeeId = e.employeeId
                $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Consulting LIST
//
// Author :Parveen Sharma
// Created on : (19.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getConsultingList($filter='', $orderBy='',$limit = '') {

        $query = "    SELECT
                            c.consultId, c.projectName, c.sponsorName, c.startDate,c.endDate, c.amountFunding, c.remarks, c.employeeId,
                            e.employeeName, e.employeeCode
                    FROM    consulting c, employee e
                    WHERE    c.employeeId = e.employeeId
                            $filter
                    ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Consulting
//
// Author :Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalConsulting($filter='') {

       $query = "    SELECT    COUNT(*) AS totalRecords
                    FROM    consulting c,
                            employee e
                    WHERE    c.employeeId = e.employeeId
                            $filter  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
// Consulting Information   End



// Seminars Information     Start

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Seminars
//
// Author :Parveen Sharma
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function addSeminars($employeeId) {
        global $REQUEST_DATA;

      $query="INSERT INTO seminar (organisedBy,topic,description,startDate,endDate,seminarPlace,employeeId,fee,participationId)
      VALUES('".$REQUEST_DATA['seminarOrganisedBy']."','".$REQUEST_DATA['seminarTopic']."','".$REQUEST_DATA['seminarDescription']."','".$REQUEST_DATA['startDate']."','".$REQUEST_DATA['endDate']."','".$REQUEST_DATA['seminarPlace']."','".$employeeId."','".$REQUEST_DATA['fee']."','".$REQUEST_DATA['participationId']."')";

      return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Seminars
//
// Author :Parveen Sharma
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editSeminars($id) {
        global $REQUEST_DATA;

        $query="UPDATE seminar SET    organisedBy ='".$REQUEST_DATA['seminarOrganisedBy']."',
                                    topic ='".$REQUEST_DATA['seminarTopic']."',
                                    description = '".$REQUEST_DATA['seminarDescription']."',
                                    startDate = '".$REQUEST_DATA['startDate']."',
                                    endDate = '".$REQUEST_DATA['endDate']."',
                                    seminarPlace = '".$REQUEST_DATA['seminarPlace']."',
                                    employeeId = '".$REQUEST_DATA['employeeId']."',
                                    fee = '".$REQUEST_DATA['fee']."',
                                    participationId = '".$REQUEST_DATA['participationId']."'
                WHERE seminarId=".$id;

       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Seminars
//
// Author :Parveen Sharma
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteSeminars($Id) {

        $query = "DELETE FROM seminar
                  WHERE seminarId=$Id ";

        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Seminars
//
// Author :Parveen Sharma
// Created on : (05.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getSeminars($conditions='') {

      $query = "SELECT
                        s.seminarId, s.organisedBy,s.topic,s.description,s.startDate,s.endDate,s.seminarPlace,s.employeeId,
                        e.employeeName, e.employeeCode, d.designationName, IFNULL(s.participationId,'') AS participationId, s.fee
                FROM
                        seminar s,
                        employee e LEFT JOIN designation d ON e.designationId = d.designationId
                WHERE
                        s.employeeId = e.employeeId
                $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Seminars LIST
//
// Author :Parveen Sharma
// Created on : (19.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getSeminarsList($filter='', $orderBy='',$limit = '') {

        $query = "SELECT
                        s.seminarId, s.organisedBy, s.topic, s.description, IFNULL(s.participationId,'') AS participationId, s.fee,
                        s.startDate, s.endDate, s.seminarPlace, s.employeeId, e.employeeName, e.employeeCode
                  FROM
                        seminar s, employee e
                  WHERE
                        s.employeeId = e.employeeId
                  $filter
                  ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Seminars
//
// Author :Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalSeminars($filter='') {

       $query = "SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        seminar s,
                        employee e
                 WHERE  s.employeeId = e.employeeId
                 $filter  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
// Seminars Information     End





//MDP Information  Start

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Mdp LIST
//
// Author :Parveen Sharma
// Created on : (19.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    public function getMdpList($filter='', $orderBy='',$limit = '') {

        $query = "SELECT
                        em.mdpId,
						em.mdpName,
						em.startDate,
						em.endDate,
						em.mdp,
						em.sessionsAttended,
						em.hoursAttended,
						em.venue,
						em.mdpType,
						em.description,
                        em.employeeId,
						e.employeeName,
						e.employeeCode
                  FROM
                       employee_mdp em , employee e
                  WHERE
                        em.employeeId = e.employeeId
                  $filter
                  ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Mdp
// Author :Gagan Gill
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------
   public function getTotalMdp($filter='') {

       $query = "SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        employee_mdp em,
                        employee e
                 WHERE  em.employeeId = e.employeeId
                 $filter  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Mdp DOCUMENT
// Author :Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
     public function addMdp($employeeId)  {
	    global $REQUEST_DATA;
        $mdpType = $REQUEST_DATA['mdpType'];
		$newmdpType = implode(",", $mdpType);
        $query="INSERT INTO employee_mdp (mdpName,startDate,endDate,mdp,sessionsAttended,hoursAttended,venue,mdpType,description)
		VALUES('".addslashes($REQUEST_DATA['mdpName'])."','".$REQUEST_DATA['mdpstartDate']."','".$REQUEST_DATA['mdpendDate']."','".$REQUEST_DATA['mdpSelectId']."','".$REQUEST_DATA['mdpSessionAttended']."','".$REQUEST_DATA['mdpHours']."','".addslashes($REQUEST_DATA['mdpVenue'])."','".$newmdpType."','".addslashes($REQUEST_DATA['mdpDescription'])."')";

      return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Mdp
// Author :Parveen Sharma
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
   public function editMdp($id) {
        global $REQUEST_DATA;

        $query="UPDATE employee_mdp SET  mdpName       = '".addslashes($REQUEST_DATA['mdpName'])."',
                                    startDate          = '".$REQUEST_DATA['mdpstartDate']."',
                                    endDate            = '".$REQUEST_DATA['mdpendDate']."',
                                    mdp                = '".$REQUEST_DATA['mdpSelectId']."',
                                    sessionsAttended   = '".$REQUEST_DATA['mdpSessionAttended']."',
                                    hoursAttended      = '".$REQUEST_DATA['mdpHours']."',
                                    venue              = '".addslashes($REQUEST_DATA['mdpVenue'])."',
									mdpType            = '".implode(',',$REQUEST_DATA['mdpType'])."',
                                    description        = '".addslashes($REQUEST_DATA['mdpDescription'])."'
                WHERE mdpId=".$id;

      // echo  $query;
       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Mdp
//
// Author :Gagan Gill
// Created on : (05.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function getMdp($conditions='') {

     $query = "SELECT
                        em.mdpId, em.mdpName,  em.startDate, em.endDate, em.mdp,
						em.sessionsAttended,   em.hoursAttended,   em.venue, em.mdpType,  em.description,
                        em.employeeId, e.employeeName , e.employeeCode ,  d.designationName
                  FROM
                        employee_mdp em,
                        employee e LEFT JOIN designation d ON e.designationId = d.designationId
                WHERE
                        em.employeeId = e.employeeId
                $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Mdp
//
// Author :Gagan Gill
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------
    public function deleteMdp($Id) {

        $query = "DELETE FROM employee_mdp
                  WHERE mdpId=$Id ";

        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
//--------------------------------------------------------------------------------------------------------
	public function getEmployeeMDPInfo($employeeId) {
		$query = "select * from employee_mdp order by mdpId";
		return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
	}

// Workshop Information     Start

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A Workshop
//
// Author :Parveen Sharma
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function addWorkshop($employeeId) {
        global $REQUEST_DATA;

      $query="INSERT INTO workshop (topic,startDate,endDate,sponsored,sponsoredDetail,location,otherSpeakers,audience,attendees,employeeId)
      VALUES('".$REQUEST_DATA['topic']."','".$REQUEST_DATA['startDate']."','".$REQUEST_DATA['endDate']."','".$REQUEST_DATA['sponsored']."','".$REQUEST_DATA['sponsoredDetail']."','".$REQUEST_DATA['location']."','".$REQUEST_DATA['otherSpeakers']."','".$REQUEST_DATA['audience']."','".$REQUEST_DATA['attendees']."','".$employeeId."')";

      return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Workshop
//
// Author :Parveen Sharma
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editWorkshop($id) {
        global $REQUEST_DATA;

        $query="UPDATE workshop SET  topic ='".$REQUEST_DATA['topic']."',
                                    startDate = '".$REQUEST_DATA['startDate']."',
                                    endDate = '".$REQUEST_DATA['endDate']."',
                                    sponsored = '".$REQUEST_DATA['sponsored']."',
                                    sponsoredDetail = '".$REQUEST_DATA['sponsoredDetail']."',
                                    location = '".$REQUEST_DATA['location']."',
                                    otherSpeakers = '".$REQUEST_DATA['otherSpeakers']."',
                                    audience = '".$REQUEST_DATA['audience']."',
                                    attendees = '".$REQUEST_DATA['attendees']."',
                                    employeeId = '".$REQUEST_DATA['employeeId']."'
                WHERE workshopId=".$id;

       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Workshop
//
// Author :Parveen Sharma
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteWorkshop($Id) {

        $query = "DELETE FROM workshop
                  WHERE workshopId=$Id ";

        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Workshop
//
// Author :Parveen Sharma
// Created on : (05.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getWorkshop($conditions='') {

      $query = "SELECT
                        w.topic,w.startDate,w.endDate,w.sponsored,w.sponsoredDetail,w.location,w.otherSpeakers,w.audience,
                        w.attendees,w.employeeId,e.employeeName, e.employeeCode, d.designationName, w.workshopId
                FROM
                        workshop w,
                        employee e LEFT JOIN designation d ON e.designationId = d.designationId
                WHERE
                        w.employeeId = e.employeeId
                $conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Workshop LIST
//
// Author :Parveen Sharma
// Created on : (19.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getWorkshopList($filter='', $orderBy='',$limit = '') {

        $query = " SELECT
                           w.topic,w.startDate,w.endDate,w.sponsored,w.sponsoredDetail,w.location,w.otherSpeakers,w.audience,
                           w.attendees,w.employeeId, e.employeeName, e.employeeCode, d.designationName, w.workshopId
                   FROM
                           workshop w,
                           employee e LEFT JOIN designation d ON e.designationId = d.designationId
                   WHERE
                           w.employeeId = e.employeeId
                           $filter
                   ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Workshop
//
// Author :Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalWorkshop($filter='') {

       $query = "SELECT
                        COUNT(*) AS totalRecords
                 FROM
                        workshop w,
                        employee e
                 WHERE
                        w.employeeId = e.employeeId
                 $filter  ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Employee Image
//
// Author :Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	public function updateEmployeeImage($id, $fileName) {
        if($fileName!=''){
          $query="UPDATE employee SET employeeImage='".$fileName."' WHERE employeeId=".$id;
        }
        else{
            $query="UPDATE employee SET employeeImage=NULL WHERE employeeId=".$id;
        }

        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Employee Thumb Image
//
// Author :Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	public function updateThumbImage($id, $fileName1) {
        if($fileName1!=''){
          $query="UPDATE employee SET thumbImage='".$fileName1."' WHERE employeeId=".$id;
        }
        else{
          $query="UPDATE employee SET thumbImage=NULL WHERE employeeId=".$id;
        }

        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE IMAGE DETAIL
//
// Author :Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	public function getEmployeeImageDetail($condition) {
        $query = "SELECT employeeImage FROM employee $condition";

		return SystemDatabaseManager::getInstance()->executeQuery($query);
    }


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE IMAGE DETAIL
//
// Author :Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	public function getEmployeeThumbImageDetail($condition) {
        $query = "SELECT thumbImage FROM employee $condition";

		return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Employee Image
//
// Author :Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	public function deleteEmployeeImage($id) {
		$query="UPDATE employee SET employeeImage=NULL WHERE employeeId=".$id;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Employee Image
//
// Author :Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
	public function deleteEmployeeThumbImage($id) {
		$query="UPDATE employee SET thumbImage = NULL WHERE employeeId=".$id;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

// Workshop Information     End

 //-------------------------------------------------------------------------------
//
//getEmployeeInfo() function is used for getting the value of employee table....
//
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getEmployeeInfo($conditions='') {

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

   $query = "SELECT
                        emp.employeeId,
                        emp.userId,
						emp.title,
						emp.lastName,
                        emp.employeeName,
						emp.middleName,
                        emp.employeeCode,
                        emp.employeeAbbreviation,
                        IF(emp.isTeaching=1,'Yes','No') AS isTeaching,
                        desg.designationId,
                        desg.designationName,
                        emp.gender,
                        br.branchCode,
                        emp.departmentId,
                        dept.departmentName,
                        br.branchCode,
                        st.stateName,
                        emp.pinCode,
                        emp.qualification,
                        IF(emp.isMarried=1,'Yes','No') AS isMarried,
                        emp.spouseName,
                        emp.fatherName,
                        emp.motherName,
                        emp.contactNumber,
                        emp.emailAddress,
                        emp.mobileNumber,
                        emp.address1,
                        emp.address2,
                        if(emp.employeeImage IS NULL OR emp.employeeImage='',-1,emp.employeeImage) as employeeImage,
                        ct.cityName,
                        cn.countryName,
                        ct.cityId,
                        cn.countryId,
                        st.stateId,
                        us.userName,
                        us.userPassword,
                        us.roleId,
                        r.roleName,
                        emp.dateOfBirth,
                        IFNULL(dateOfMarriage,'') AS dateOfMarriage,
                        emp.dateOfJoining,
                        emp.dateOfLeaving,
						emp.bloodGroup,
                        IF(emp.isActive=1,'Yes','No') AS isActive
        FROM designation desg,branch br, employee emp
        LEFT JOIN states st ON (emp.stateId = st.stateId)
        LEFT JOIN city ct ON (emp.cityId = ct.cityId)
        LEFT JOIN countries cn ON (emp.countryId = cn.countryId)
        LEFT JOIN department dept ON (emp.departmentId = dept.departmentId)
		LEFT JOIN user us ON (emp.userId = us.userId)
		LEFT JOIN role r ON (us.roleId = r.roleId)
        WHERE emp.designationId = desg.designationId AND emp.branchId = br.branchId AND emp.instituteId= $instituteId AND isActive = 1
		$conditions
        ORDER BY emp.employeeId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------------------
//
//getEmployeeInfo() function is used for getting the value of employee table....
//
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateEmployeeInfoInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup,$checkCondition='') {
        if($dateOfBirth == "NA"){
            $dateOfBirth = "0000-00-00";
        }
        if($dateOfMarriage == "NA"){
            $dateOfMarriage = "0000-00-00";
        }
        if($dateOfJoining == "NA"){
            $dateOfJoining = "0000-00-00";
        }
         if($dateOfLeaving == "NA"){
            $dateOfLeaving = "0000-00-00";
        }

		if($countryId == '') {
			$countryId = 'NULL';
		}
		else {
			$countryId = $countryId;
		}
		if($stateId == '') {
			$stateId = 'NULL';
		}
		else {
			$stateId = $stateId;
		}
		if($cityId == '') {
			$cityId = 'NULL';
		}
		else {
			$cityId = $cityId;
		}

		if($departmentId == '') {
			$departmentId = 'NULL';
		}

       $query = "    UPDATE `employee`
                    SET     title = $title,
							lastName = '$lastName',
							employeeName = '$employeeName',
							middleName = '$middleName',
                            employeeCode = '$employeeCode',
                            employeeAbbreviation = '$employeeAbbreviation',
                            isTeaching = '$isTeaching',
                            designationId = '$designationId',
                            gender = '$gender',
                            departmentId = $departmentId,
                            branchId = '$branchId',
                            qualification = '$qualification',
                            isMarried = '$isMarried',
                            spouseName = '$spouseName',
                            fatherName = '$fatherName',
                            motherName = '$motherName',
                            contactNumber = '$contactNumber',
                            emailAddress = '$emailAddress',
                            mobileNumber = '$mobileNumber',
                            address1 = '$address1',
                            address2 = '$address2',
                            cityId = $cityId,
                            stateId = $stateId,
                            countryId = $countryId,
                            pinCode = '$pinCode',
                            dateOfBirth = '$dateOfBirth',
                            dateOfMarriage = '$dateOfMarriage',
                            dateOfJoining = '$dateOfJoining',
                            dateOfLeaving = '$dateOfLeaving',
							panNo = '$panNo',
							religion = '$religion',
							caste = '$caste',
							providentFundNo = '$pfNo',
							bankName = '$bankName',
							accountNo = '$bankAccountNo',
							branchName = '$bankBranchName',
							esiNumber = '$ESINo',
							bloodGroup = '$bloodGroup'
                            $checkCondition";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


//-------------------------------------------------------------------------------
//
//getEmployeeInfo() function is used for getting the value of employee table....
//
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateEmployeeWithUserInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup,$checkCondition='') {
        if($dateOfBirth == "NA"){
            $dateOfBirth = "0000-00-00";
        }
        if($dateOfMarriage == "NA"){
            $dateOfMarriage = "0000-00-00";
        }
        if($dateOfJoining == "NA"){
            $dateOfJoining = "0000-00-00";
        }
         if($dateOfLeaving == "NA"){
            $dateOfLeaving = "0000-00-00";
        }

		if($countryId == '') {
			$countryId = 'NULL';
		}
		else {
			$countryId = $countryId;
		}
		if($stateId == '') {
			$stateId = 'NULL';
		}
		else {
			$stateId = $stateId;
		}
		if($cityId == '') {
			$cityId = 'NULL';
		}
		else {
			$cityId = $cityId;
		}

		if($departmentId == '') {
			$departmentId = 'NULL';
		}

     $query = "    UPDATE `employee`
                    SET     title = $title,
							lastName = '$lastName',
							employeeName = '$employeeName',
							middleName = '$middleName',
                            employeeCode = '$employeeCode',
                            employeeAbbreviation = '$employeeAbbreviation',
                            isTeaching = '$isTeaching',
                            designationId = '$designationId',
                            gender = '$gender',
                            departmentId = $departmentId,
                            branchId = '$branchId',
                            qualification = '$qualification',
                            isMarried = '$isMarried',
                            spouseName = '$spouseName',
                            fatherName = '$fatherName',
                            motherName = '$motherName',
                            contactNumber = '$contactNumber',
                            emailAddress = '$emailAddress',
                            mobileNumber = '$mobileNumber',
                            address1 = '$address1',
                            address2 = '$address2',
                            cityId = $cityId,
                            stateId = $stateId,
                            countryId = $countryId,
                            pinCode = '$pinCode',
                            dateOfBirth = '$dateOfBirth',
                            dateOfMarriage = '$dateOfMarriage',
                            dateOfJoining = '$dateOfJoining',
                            dateOfLeaving = '$dateOfLeaving',
							panNo = '$panNo',
							religion = '$religion',
							caste = '$caste',
							providentFundNo = '$pfNo',
							bankName = '$bankName',
							accountNo = '$bankAccountNo',
							branchName = '$bankBranchName',
							esiNumber = '$ESINo',
							bloodGroup = '$bloodGroup'
                            $checkCondition";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


//-------------------------------------------------------------------------------
//
//getEmployeeInfo() function is used for getting the value of employee table....
//
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateEmployeeWithUserNameInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup,$userId,$checkCondition='') {
        if($dateOfBirth == "NA"){
            $dateOfBirth = "0000-00-00";
        }
        if($dateOfMarriage == "NA"){
            $dateOfMarriage = "0000-00-00";
        }
        if($dateOfJoining == "NA"){
            $dateOfJoining = "0000-00-00";
        }
         if($dateOfLeaving == "NA"){
            $dateOfLeaving = "0000-00-00";
        }

		if($countryId == '') {
			$countryId = 'NULL';
		}
		else {
			$countryId = $countryId;
		}
		if($stateId == '') {
			$stateId = 'NULL';
		}
		else {
			$stateId = $stateId;
		}
		if($cityId == '') {
			$cityId = 'NULL';
		}
		else {
			$cityId = $cityId;
		}

		if($departmentId == '') {
			$departmentId = 'NULL';
		}

     $query = "    UPDATE `employee`
                    SET     title = $title,
							lastName = '$lastName',
							employeeName = '$employeeName',
							middleName = '$middleName',
                            employeeCode = '$employeeCode',
                            employeeAbbreviation = '$employeeAbbreviation',
                            isTeaching = '$isTeaching',
                            designationId = $designationId,
                            gender = '$gender',
                            departmentId = '$departmentId',
                            branchId = '$branchId',
                            qualification = '$qualification',
                            isMarried = '$isMarried',
                            spouseName = '$spouseName',
                            fatherName = '$fatherName',
                            motherName = '$motherName',
                            contactNumber = '$contactNumber',
                            emailAddress = '$emailAddress',
                            mobileNumber = '$mobileNumber',
                            address1 = '$address1',
                            address2 = '$address2',
                            cityId = $cityId,
                            stateId = $stateId,
                            countryId = $countryId,
                            pinCode = '$pinCode',
                            dateOfBirth = '$dateOfBirth',
                            dateOfMarriage = '$dateOfMarriage',
                            dateOfJoining = '$dateOfJoining',
                            dateOfLeaving = '$dateOfLeaving',
							panNo = '$panNo',
							religion = '$religion',
							caste = '$caste',
							providentFundNo = '$pfNo',
							bankName = '$bankName',
							accountNo = '$bankAccountNo',
							branchName = '$bankBranchName',
							esiNumber = '$ESINo',
							userId = $userId,
							bloodGroup = '$bloodGroup'
                            $checkCondition";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


//-------------------------------------------------------------------------------
//
//getEmployeeInfo() function is used for getting the value of employee table....
//
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function updateUserInTransaction($userName,$userId,$roleId) {

        $query = "	UPDATE	`user`
					SET		userName = '".$userName."',
							roleId = ".$roleId."
					WHERE	userId = $userId
                            $checkCondition";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//-------------------------------------------------------------------------------
//
//getEmployeeInfo() function is used for getting the value of employee table....
//
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addEmployeeInfoInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$userId,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup) {
        global $sessionHandler;
        $instituteId   = $sessionHandler->getSessionVariable('InstituteId');
        if($dateOfBirth == "NA"){
            $dateOfBirth = "0000-00-00";
        }
        if($dateOfMarriage == "NA"){
            $dateOfMarriage = "0000-00-00";
        }
        if($dateOfJoining == "NA"){
            $dateOfJoining = "0000-00-00";
        }
        if($dateOfLeaving == "NA"){
            $dateOfLeaving = "0000-00-00";
        }

		if($countryId == '') {
			$countryId = 'NULL';
		}
		else {
			$countryId = $countryId;
		}
		if($stateId == '') {
			$stateId = 'NULL';
		}
		else {
			$stateId = $stateId;
		}
		if($cityId == '') {
			$cityId = 'NULL';
		}
		else {
			$cityId = $cityId;
		}

		if($departmentId == '') {
			$departmentId = 'NULL';
		}

        $query = "INSERT INTO `employee` (title,lastName,employeeName,middleName,employeeCode,employeeAbbreviation,isTeaching,designationId,gender,departmentId,branchId,qualification,isMarried,spouseName,fatherName,motherName,contactNumber,emailAddress,mobileNumber,address1,address2,cityId,stateId,countryId,pinCode,dateOfBirth,dateOfMarriage,dateOfJoining,dateOfLeaving,userId,InstituteId,panNo,religion,caste,providentFundNo,bankName,accountNo,branchName,esiNumber,bloodGroup) VALUES ($title,'$lastName','$employeeName','$middleName','$employeeCode','$employeeAbbreviation','$isTeaching','$designationId','$gender',$departmentId,'$branchId','$qualification','$isMarried','$spouseName','$fatherName','$motherName','$contactNumber','$emailAddress','$mobileNumber','$address1','$address2',$cityId,$stateId,$countryId,'$pinCode','$dateOfBirth','$dateOfMarriage','$dateOfJoining','$dateOfLeaving','$userId','$instituteId','$panNo','$religion','$caste','$pfNo','$bankName','$bankAccountNo','$bankBranchName','$ESINo','$bloodGroup')";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }


	//-------------------------------------------------------------------------------
//
//getEmployeeInfo() function is used for getting the value of employee table....
//
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function addEmployeeWithoutUserInTransaction($title,$lastName,$employeeName,$middleName,$employeeCode,$employeeAbbreviation,$isTeaching,$designationId,$gender,$departmentId,$branchId,$qualification,$isMarried,$spouseName,$fatherName,$motherName,$contactNumber,$emailAddress,$mobileNumber,$address1,$address2,$cityId,$stateId,$countryId,$pinCode,$dateOfBirth,$dateOfMarriage,$dateOfJoining,$dateOfLeaving,$panNo,$religion,$caste,$pfNo,$bankName,$bankAccountNo,$bankBranchName,$ESINo,$bloodGroup) {
        global $sessionHandler;
        $instituteId   = $sessionHandler->getSessionVariable('InstituteId');
        if($dateOfBirth == "NA"){
            $dateOfBirth = "0000-00-00";
        }
        if($dateOfMarriage == "NA"){
            $dateOfMarriage = "0000-00-00";
        }
        if($dateOfJoining == "NA"){
            $dateOfJoining = "0000-00-00";
        }
        if($dateOfLeaving == "NA"){
            $dateOfLeaving = "0000-00-00";
        }
		if($countryId == '') {
			$countryId = 'NULL';
		}
		else {
			$countryId = $countryId;
		}
		if($stateId == '') {
			$stateId = 'NULL';
		}
		else {
			$stateId = $stateId;
		}
		if($cityId == '') {
			$cityId = 'NULL';
		}
		else {
			$cityId = $cityId;
		}
		if($departmentId == '') {
			$departmentId = 'NULL';
		}

        $query = "INSERT INTO `employee` (title,lastName,employeeName,middleName,employeeCode,employeeAbbreviation,isTeaching,designationId,gender,departmentId,branchId,qualification,isMarried,spouseName,fatherName,motherName,contactNumber,emailAddress,mobileNumber,address1,address2,cityId,stateId,countryId,pinCode,dateOfBirth,dateOfMarriage,dateOfJoining,dateOfLeaving,userId,InstituteId,panNo,religion,caste,providentFundNo,bankName,accountNo,branchName,esiNumber,bloodGroup) VALUES ($title,'$lastName','$employeeName','$middleName','$employeeCode','$employeeAbbreviation','$isTeaching','$designationId','$gender',$departmentId,'$branchId','$qualification','$isMarried','$spouseName','$fatherName','$motherName','$contactNumber','$emailAddress','$mobileNumber','$address1','$address2',$cityId,$stateId,$countryId,'$pinCode','$dateOfBirth','$dateOfMarriage','$dateOfJoining','$dateOfLeaving','$userId','$instituteId','$panNo','$religion','$caste','$pfNo','$bankName','$bankAccountNo','$bankBranchName','$ESINo','$bloodGroup')";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    //-------------------------------------------------------------------------------
//
//getCountry() function used to SELECT COUNTY
// $condition - used to check the condition of the table
// Author : Gurkeerat Sidhu
// Created on : 14.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getCountry($conditions='') {
        $query = "    SELECT * FROM countries c
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

//-------------------------------------------------------------------------------
//
//getState() function used to SELECT States
// $condition - used to check the condition of the table
// Author : Gurkeerat Sidhu
// Created on : 14.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getState($conditions='') {
        $query = "    SELECT * FROM  states st
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

//-------------------------------------------------------------------------------
//
//getCity() function used to SELECT City
// $condition - used to check the condition of the table
// Author : Gurkeerat Sidhu
// Created on : 14.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getCity($conditions='') {
        $query = "    SELECT * FROM  city ct
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

 //-------------------------------------------------------------------------------
//
//getCity() function used to SELECT City
// $condition - used to check the condition of the table
// Author : Gurkeerat Sidhu
// Created on : 14.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getDesignation($conditions='') {
        $query = "    SELECT * FROM  designation desg
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
//-------------------------------------------------------------------------------
//
//getCity() function used to SELECT City
// $condition - used to check the condition of the table
// Author : Gurkeerat Sidhu
// Created on : 14.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getDepartment($conditions='') {
        $query = "    SELECT * FROM  department dept
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
//-------------------------------------------------------------------------------
//
//getCity() function used to SELECT City
// $condition - used to check the condition of the table
// Author : Gurkeerat Sidhu
// Created on : 14.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getBranch($conditions='') {
        $query = "    SELECT * FROM  branch br
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }

//-------------------------------------------------------------------------------
//
//getPanNo() function used to SELECT PAN NO.
// $condition - used to check the condition of the table
// Author : Jaineesh
// Created on : 23.07.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getPanNo($conditions='') {
        $query = "	SELECT	panNo
					FROM	employee
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
//-------------------------------------------------------------------------------
//
//getCity() function used to SELECT City
// $condition - used to check the condition of the table
// Author : Gurkeerat Sidhu
// Created on : 14.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getRole($conditions='') {
        $query = "    SELECT * FROM  role r
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
//-------------------------------------------------------------------------------
//
//getCity() function used to SELECT City
// $condition - used to check the condition of the table
// Author : Gurkeerat Sidhu
// Created on : 14.11.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getEmpCode($conditions='') {
     $query = "    SELECT * FROM  employee emp
                    $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
//-------------------------------------------------------
    //  THIS FUNCTION IS USED TO fetch student roll no, username
    //
    // Author :Gurkeerat Sidhu
    // Created on : (29-May-2009)
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    public function insertUserData($roleId,$userName,$pass) {
        global $sessionHandler;
        $instituteId   = $sessionHandler->getSessionVariable('InstituteId');

        $query = "INSERT INTO `user` (userName,userPassword,roleId,instituteId) VALUES ('$userName','$pass',$roleId,$instituteId)";

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee role wise for pie chart
//
//$conditions :db clauses
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getEmployeeTeachingList($conditions='') {

       global $sessionHandler;
        $query = "SELECT count(*) as totalCount
                 FROM
                 `employee`
                 WHERE
                 instituteId = ".$sessionHandler->getSessionVariable('InstituteId');
        if($conditions)
            $query .=" $conditions";

        //$query .=" GROUP BY roleName";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee city for pie chart
//
//$conditions :db clauses
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getEmployeeCityList($conditions='') {

       global $sessionHandler;
        $query = "SELECT
                 COUNT(*) as totalCount,cty.cityId,cty.cityCode,cty.cityName
                 FROM `employee` emp, `city` cty
                 WHERE
                 emp.cityId = cty.cityId AND
                 instituteId = ".$sessionHandler->getSessionVariable('InstituteId');

        if($conditions)
            $query .=" $conditions";

        $query .=" GROUP BY emp.cityId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee designation list for pie chart
//
//$conditions :db clauses
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getEmployeeDesignationList($conditions='') {

       global $sessionHandler;
       $query = "SELECT count( * ) as totalCount, des.designationId, des.designationName, des.designationCode
                  FROM `employee` emp, `designation` des
                  WHERE
                  emp.designationId = des.designationId AND
                  instituteId =".$sessionHandler->getSessionVariable('InstituteId');
        if($conditions)
            $query .=" $conditions";

        $query .=" GROUP BY emp.designationId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the branch for employee list for pie chart
//
//$conditions :db clauses
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getEmployeeBranchList($conditions='') {

       global $sessionHandler;
       $query = "SELECT count( * ) as totalCount, br.branchId, br.branchCode, br.branchName
                  FROM employee emp, branch br
                  WHERE

                  emp.branchId = br.branchId AND
                  $conditions
                  emp.instituteId =".$sessionHandler->getSessionVariable('InstituteId');

        $query .=" GROUP BY br.branchId ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee Gender list for pie chart
//
//$conditions :db clauses
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getEmployeeGenderList($conditions='') {

       global $sessionHandler;
       $query = "SELECT count( * ) as totalCount
                  FROM `employee`
                  WHERE
                  instituteId =".$sessionHandler->getSessionVariable('InstituteId');
        if($conditions)
            $query .=" $conditions";

        $query .=" GROUP BY gender ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee role wise for pie chart
//
//$conditions :db clauses
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getEmployeeRoleList($conditions='') {

       global $sessionHandler;
        $query = "SELECT count(*) as totalCount,rol.roleId ,roleName
                 FROM
                 `employee` emp, `user` usr, role rol
                 WHERE
                 emp.userId = usr.userId AND usr.roleId = rol.roleId AND
                 $conditions
                 emp.instituteId = ".$sessionHandler->getSessionVariable('InstituteId');

        $query .=" GROUP BY roleName";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee marital status for pie chart
//
//$conditions :db clauses
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getEmployeeMaritalList($conditions='') {

       global $sessionHandler;
        $query = "SELECT
                 COUNT(*) as totalCount,if(isMarried=0,'Un-Married','Married') as maritalStatus,isMarried
                 FROM `employee`
                 WHERE
                 instituteId = ".$sessionHandler->getSessionVariable('InstituteId');

        $query .=" GROUP BY isMarried";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FOR all the employee state for pie chart
//
//$conditions :db clauses
// Author : Gurkeerat Sidhu
// Created on : 16.11.09
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    public function getEmployeeStateList($conditions='') {

       global $sessionHandler;
        $query = "SELECT
                 COUNT(*) as totalCount,sta.stateId,sta.stateCode,sta.stateName
                 FROM `employee` emp, `states` sta
                 WHERE
                 emp.stateId = sta.stateId AND
                 instituteId = ".$sessionHandler->getSessionVariable('InstituteId');

        if($conditions)
            $query .=" $conditions";

        $query .=" GROUP BY sta.stateId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// editIcardIssueDate() is used to edit new record in database.
// Author : Parveen Sharma
// Created on : 12.06.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function editIcardIssueDate($issueDate,$employeeId) {
        global $REQUEST_DATA;
        global $sessionHandler;

        $query = "UPDATE employee SET issueDate='".$issueDate."' WHERE employeeId IN ($employeeId) ";

        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
// used to check condition while selecting the employee records
// Author : Parveen Sharma
// Created on : 09.09.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getIcardEmployeeList($conditions='', $limit = '', $orderBy='emp.employeeName') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $cond ="";
        if($conditions=='') {
           $cond = " WHERE emp.instituteId = $instituteId";
        }
        else {
            $cond = $conditions." AND emp.instituteId = $instituteId";
        }

       $query = "SELECT
                          DISTINCT (emp.employeeId), emp.userId, emp.employeeName,emp.employeeCode,  emp.gender, emp.qualification,
                          emp.employeeAbbreviation, IF(emp.isTeaching=1, 'Yes', 'No') AS isTeaching,
                          emp.isMarried, emp.spouseName, emp.fatherName, emp.motherName,
                          IF(IFNULL(emp.designationId,'')='','".NOT_APPLICABLE_STRING."',
                            (SELECT designationName FROM designation desg WHERE desg.designationId=emp.designationId)) AS designationName,
                          IF(IFNULL(emp.contactNumber,'')='','".NOT_APPLICABLE_STRING."',emp.contactNumber) AS contactNumber,
                          IF(IFNULL(emp.emailAddress,'')='','".NOT_APPLICABLE_STRING."',emp.emailAddress) AS emailAddress,
                          IF(IFNULL(emp.mobileNumber,'')='','".NOT_APPLICABLE_STRING."',emp.mobileNumber) AS mobileNumber,
                          emp.dateOfBirth, emp.dateOfJoining, emp.employeePhoto,emp.employeeImage,emp.dateOfMarriage,
                          emp.dateOfLeaving, emp.isActive,
                          IF(IFNULL(emp.bloodGroup,0)=0,'".NOT_APPLICABLE_STRING."',emp.bloodGroup) AS bloodGroup,
                          IF(IFNULL(emp.departmentId,'')='','".NOT_APPLICABLE_STRING."',
                                    (SELECT abbr FROM department d WHERE  emp.departmentId=d.departmentId )) AS departmentAbbr,
                          IF(IFNULL(emp.branchId,'')='','".NOT_APPLICABLE_STRING."',
                                    (SELECT branchCode FROM branch br  WHERE  emp.branchId=br.branchId )) AS branchCode,
                          IF(emp.address1 IS NULL OR emp.address1='','', CONCAT(emp.address1,' ',IFNULL(emp.address2,''),'<br>',
                            (SELECT cityName from city where city.cityId=emp.cityId),' ',(SELECT stateName from states where states.stateId=emp.stateId),' ',
                            (SELECT countryName from countries where countries.countryId=emp.countryId),IF(emp.pinCode IS NULL OR emp.pinCode='','',CONCAT('-',emp.pinCode)))) AS permAddress,
                          emp.issueDate
                   FROM
                          employee emp 
                   $cond
                   ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// used to check condition while selecting the employee records count
// Author : Parveen Sharma
// Created on : 09.09.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getTotalIcardEmployeeList($conditions='') {
       global $sessionHandler;

       $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $cond ="";
        if($conditions=='') {
           $cond = " WHERE emp.instituteId = $instituteId";
        }
        else {
            $cond = $conditions." AND emp.instituteId = $instituteId";
        }

       $query = "SELECT
                       COUNT(DISTINCT emp.employeeId) AS totalRecords
                 FROM
                       employee emp
                 $cond ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
// used to check condition while selecting the employee records
// Author : Parveen Sharma
// Created on : 09.09.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getEmployeeBusPassList($conditions='', $limit = '', $orderBy='emp.employeeName',$conditions1='') {

        global $sessionHandler;

        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $cond ="";
        if($conditions=='') {
           $cond = " WHERE emp.instituteId = $instituteId";
        }
        else {
            $cond = $conditions." AND emp.instituteId = $instituteId";
        }

       $query = "SELECT
                          DISTINCT (emp.employeeId), emp.userId, emp.employeeName,emp.employeeCode,  emp.gender, emp.qualification,
                          emp.employeeAbbreviation, IF(emp.isTeaching=1, 'Yes', 'No') AS isTeaching,
                          emp.isMarried, emp.spouseName, emp.fatherName, emp.motherName,
                          IF(IFNULL(emp.designationId,'')='','".NOT_APPLICABLE_STRING."',
                            (SELECT designationName FROM designation desg WHERE desg.designationId=emp.designationId)) AS designationName,
                          IFNULL(emp.contactNumber,'".NOT_APPLICABLE_STRING."') AS contactNumber,
                          IFNULL(emp.emailAddress,'".NOT_APPLICABLE_STRING."') AS emailAddress,
                          IFNULL(emp.mobileNumber,'".NOT_APPLICABLE_STRING."') AS mobileNumber,
                          emp.dateOfBirth, emp.dateOfJoining, emp.employeePhoto,emp.employeeImage,emp.dateOfMarriage,
                          emp.dateOfLeaving, emp.isActive,
                          IF(IFNULL(emp.bloodGroup,0)=0,'".NOT_APPLICABLE_STRING."',emp.bloodGroup) AS bloodGroup,
                          IF(IFNULL(emp.departmentId,'')='','".NOT_APPLICABLE_STRING."',
                                    (SELECT abbr FROM department d WHERE  emp.departmentId=d.departmentId )) AS departmentAbbr,
                          IF(IFNULL(emp.branchId,'')='','".NOT_APPLICABLE_STRING."',
                                    (SELECT branchCode FROM branch br  WHERE  emp.branchId=br.branchId )) AS branchCode,

                          IF(emp.address1 IS NULL OR emp.address1='','', CONCAT(emp.address1,' ',IFNULL(emp.address2,''),'<br>',
                            (SELECT cityName from city where city.cityId=emp.cityId),' ',(SELECT stateName from states where states.stateId=emp.stateId),' ',
                            (SELECT countryName from countries where countries.countryId=emp.countryId),IF(emp.pinCode IS NULL OR emp.pinCode='','',CONCAT('-',emp.pinCode)))) AS permAddress,
                          IFNULL(empBus.receiptNo,'') AS receiptNo, IFNULL(empBus.validUpto,'') AS validUpto,
                          IFNULL((SELECT routeCode FROM bus_route broute  WHERE broute.busRouteId=empBus.busRouteId),'') AS routeCode,
                          IFNULL((SELECT stopName  FROM bus_stop bstop   WHERE bstop.busStopId=empBus.busStopId),'') AS stopName,
                          IFNULL(empBus.busPassId,'') AS busPassId,  empBus.busRouteId, empBus.busStopId,
                          IFNULL(empBus.status,'') AS status, IFNULL(empBus.addUserId,'') AS addUserId, IFNULL(empBus.cancelUserId,'') AS cancelUserId,
                          IFNULL(b.busNo,'') AS busNo, b.busId
                   FROM
                          employee emp 
                          LEFT JOIN employee_bus_pass empBus ON emp.employeeId = empBus.employeeId AND empBus.instituteId = $instituteId $conditions1
                          LEFT JOIN bus b ON empBus.busId =b.busId 
                   $cond
                   ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
// used to check condition while selecting the employee records count
// Author : Parveen Sharma
// Created on : 09.09.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getCountEmployeeBusPassList($conditions='',$conditions1='') {
       global $sessionHandler;

       $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $cond ="";
        if($conditions=='') {
           $cond = " WHERE emp.instituteId = $instituteId";
        }
        else {
            $cond = $conditions." AND emp.instituteId = $instituteId";
        }

       $query = "SELECT
                       COUNT(DISTINCT tt.employeeId) AS totalRecords
                 FROM
                     (SELECT
                              DISTINCT (emp.employeeId), emp.userId, emp.employeeName,emp.employeeCode,  emp.gender, emp.qualification,
                              emp.employeeAbbreviation, IF(emp.isTeaching=1, 'Yes', 'No') AS isTeaching,
                              emp.isMarried, emp.spouseName, emp.fatherName, emp.motherName,
                              IF(IFNULL(emp.designationId,'')='','".NOT_APPLICABLE_STRING."',
                                (SELECT designationName FROM designation desg WHERE desg.designationId=emp.designationId)) AS designationName,
                              IFNULL(emp.contactNumber,'".NOT_APPLICABLE_STRING."') AS contactNumber,
                              IFNULL(emp.emailAddress,'".NOT_APPLICABLE_STRING."') AS emailAddress,
                              IFNULL(emp.mobileNumber,'".NOT_APPLICABLE_STRING."') AS mobileNumber,
                              emp.dateOfBirth, emp.dateOfJoining, emp.employeePhoto,emp.employeeImage,emp.dateOfMarriage,
                              emp.dateOfLeaving, emp.isActive,
                              IF(IFNULL(emp.bloodGroup,0)=0,'".NOT_APPLICABLE_STRING."',emp.bloodGroup) AS bloodGroup,
                              IF(IFNULL(emp.departmentId,'')='','".NOT_APPLICABLE_STRING."',
                                        (SELECT abbr FROM department d WHERE  emp.departmentId=d.departmentId )) AS departmentAbbr,
                              IF(IFNULL(emp.branchId,'')='','".NOT_APPLICABLE_STRING."',
                                        (SELECT branchCode FROM branch br  WHERE  emp.branchId=br.branchId )) AS branchCode,

                              IF(emp.address1 IS NULL OR emp.address1='','', CONCAT(emp.address1,' ',IFNULL(emp.address2,''),'<br>',
                                (SELECT cityName from city where city.cityId=emp.cityId),' ',(SELECT stateName from states where states.stateId=emp.stateId),' ',
                                (SELECT countryName from countries where countries.countryId=emp.countryId),IF(emp.pinCode IS NULL OR emp.pinCode='','',CONCAT('-',emp.pinCode)))) AS permAddress

                      FROM
                             employee emp LEFT JOIN employee_bus_pass empBus ON emp.employeeId = empBus.employeeId AND empBus.instituteId = $instituteId $conditions1
                      $cond) AS tt";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
// used to Add Qualification of an employee
// Author : Jaineesh
// Created on : 25.03.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function addQualification($str) {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	INSERT INTO employee_qualification (employeeId,UGDegree,PGDegree,highestQualification,otherQualification)
					VALUES $str";
	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
// used to Add Qualification of an employee
// Author : Jaineesh
// Created on : 25.03.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function getEmployeeQualification($employeeId) {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	SELECT	*
					FROM	employee_qualification
					WHERE	employeeId = ".$employeeId."";

	     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
// used to Delete Qualification of an employee
// Author : Jaineesh
// Created on : 25.03.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function deleteEmployeeQualification($employeeId) {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	DELETE
					FROM	employee_qualification
					WHERE	employeeId = ".$employeeId."";

	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
// used to Delete Qualification of an employee
// Author : Jaineesh
// Created on : 25.03.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function deleteEmployeeExperience($employeeId) {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	DELETE
					FROM	employee_experience
					WHERE	employeeId = ".$employeeId."";

	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
// used to Add Qualification of an employee
// Author : Jaineesh
// Created on : 25.03.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function addExperience($str) {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	INSERT INTO employee_experience (employeeId,fromDate,toDate,organisation,designation,experience,expCertificateAvailable)
					VALUES $str";
	     return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
// used to Add Qualification of an employee
// Author : Jaineesh
// Created on : 25.03.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function getEmployeeExperience($employeeId) {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	SELECT	*
					FROM	employee_experience
					WHERE	employeeId = ".$employeeId."";

	     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//-------------------------------------------------------------------------------
// used to Add Qualification of an employee
// Author : Jaineesh
// Created on : 25.03.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function getExcelUserName($userId) {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	SELECT	u.userId,
							emp.userId
					FROM	employee emp,
							user u
					WHERE	emp.userId = $userId
					AND		u.userId = $userId
							$condition";

	     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//-------------------------------------------------------------------------------
// used to Get User Name of an employee
// Author : Jaineesh
// Created on : 11.05.10
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function checkUserName($userName,$condition='') {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	SELECT	userName
					FROM	user
					WHERE	userName = '$userName'
							$condition";

	     return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


//-------------------------------------------------------------------------------
// used to get financial info of an employee
// Author : Abhiraj
// Created on : 29.04.10
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    public function getEmployeeFinancialInfo($employeeId) {
       $query="	SELECT	if(providentFundNo='','---', providentFundNo) AS providentFundNo,
						if(panNo='','---',panNo) AS panNo,
						if(esiNumber='','---',esiNumber) AS esiNumber,
						if(bankName='','---',bankName) AS bankName,
						if(accountNo='','---',accountNo) AS accountNo,
						if(branchName='','---',branchName) AS branchName
				FROM	employee
				WHERE	employeeId=$employeeId";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CURRENT TIME TABLE LABEL
//
// Author :Jaineesh
// Created on : (16.06.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTimeTableLabel() {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');

		$query = "	SELECT
							timeTableLabelId,
							timeTableType
					FROM
							time_table_labels
					WHERE
							instituteId = $instituteId
					AND		sessionId = $sessionId
					AND		isActive = 1";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	  //this function is used to generate new Employee Codes
   public function generateEmployeeCode(){

       $str="SELECT IFNULL(MAX(ABS(SUBSTRING(employeeCode,length('".EMPLOYEE_CODE_PREFIX."' ) +1, LENGTH(employeeCode) ) ) ),0)+1 AS employeeCode FROM employee ";
       $iCode=SystemDatabaseManager::getInstance()->executeQuery($str,"Query: $str");

        //generate new itemCode code
       $gCode=EMPLOYEE_CODE_PREFIX.str_pad($iCode[0]['employeeCode'],abs(EMPLOYEE_CODE_LENGTH-strlen(EMPLOYEE_CODE_PREFIX)-strlen($iCode[0]['employeeCode']))+1,'0',STR_PAD_LEFT);
       return $gCode;
   }

	//this function is used to generate new User Name
   public function generateUserCode(){

       $str="SELECT IFNULL(MAX(ABS(SUBSTRING(userName,length('".USER_CODE_PREFIX."' ) +1, LENGTH(userName) ) ) ),0)+1 AS userName FROM user";
       $uCode=SystemDatabaseManager::getInstance()->executeQuery($str,"Query: $str");

        //generate new itemCode code
       $gUCode=USER_CODE_PREFIX.str_pad($uCode[0]['userName'],abs(USER_CODE_LENGTH-strlen(USER_CODE_PREFIX)-strlen($uCode[0]['userName']))+1,'0',STR_PAD_LEFT);
       return $gUCode;
   }


//-------------------------------------------------------------------------------
// used to Delete Classes visible to role
// Author : Jaineesh
// Created on : 16 Aug 2010
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

	public function deleteEmployeeVisibleToRole($resultId) {
		global $REQUEST_DATA;
        global $sessionHandler;

		$query = "	DELETE
					FROM	classes_visible_to_role
					WHERE	userId = ".$resultId."";

	    return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
	}


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED GET employee id IS USING WITH INSTITUTE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (24.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------

    public function getEmployeeVisibleToRole($conditions='') {

    $query = "    SELECT    count(userId) as userId
                    FROM    classes_visible_to_role
                            $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    public function getEmployeeRoleId($roleId='') {
     
       $query = "SELECT 
                      DISTINCT emp.employeeId 
                 FROM 
                      employee emp 
                      LEFT JOIN `user` u ON emp.userId = u.userId 
                      LEFT JOIN `role` r ON r.roleId = u.roleId                
                 WHERE
                      r.roleId = '$roleId' ";
       
       return SystemDatabaseManager::getInstance()->executeQuery($query);
    }
}
?>
<?php
// $History: EmployeeManager.inc.php $
//
//*****************  Version 44  *****************
//User: Jaineesh     Date: 4/07/10    Time: 12:07p
//Updated in $/LeapCC/Model
//Fixed error during delete of an employee, taking too much time to
//execute.
//
//*****************  Version 43  *****************
//User: Jaineesh     Date: 4/06/10    Time: 7:27p
//Updated in $/LeapCC/Model
//issue resolved No. 0003219
//
//*****************  Version 42  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Model
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 41  *****************
//User: Jaineesh     Date: 3/31/10    Time: 11:31a
//Updated in $/LeapCC/Model
//fixed bug no.3163
//
//*****************  Version 40  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:29p
//Updated in $/LeapCC/Model
//changes for gap analysis in employee master
//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 2/17/10    Time: 12:35p
//Updated in $/LeapCC/Model
//provide the facility to change institute of an employee
//
//*****************  Version 38  *****************
//User: Jaineesh     Date: 2/01/10    Time: 10:07a
//Updated in $/LeapCC/Model
//fixed bug no. 0002737
//
//*****************  Version 37  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:30p
//Updated in $/LeapCC/Model
//fixed bug no.0002326
//
//*****************  Version 36  *****************
//User: Jaineesh     Date: 12/18/09   Time: 4:07p
//Updated in $/LeapCC/Model
//show selected default institute of employee
//
//*****************  Version 35  *****************
//User: Gurkeerat    Date: 11/26/09   Time: 1:12p
//Updated in $/LeapCC/Model
//added functions related to 'employee export/import' module
//
//*****************  Version 34  *****************
//User: Parveen      Date: 11/25/09   Time: 3:33p
//Updated in $/LeapCC/Model
//function getIcardEmployeeList, getTotalIcardEmployeeList insituteId
//checks updated
//
//*****************  Version 33  *****************
//User: Parveen      Date: 10/20/09   Time: 5:41p
//Updated in $/LeapCC/Model
//getEmployeeList function updated
//
//*****************  Version 32  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Model
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 31  *****************
//User: Parveen      Date: 9/25/09    Time: 2:54p
//Updated in $/LeapCC/Model
//getEmployeeList (department column added)
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 9/22/09    Time: 6:43p
//Updated in $/LeapCC/Model
//change breadcrumb & put department in employee
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 9/18/09    Time: 7:16p
//Updated in $/LeapCC/Model
//fixed bug during self testing
//
//*****************  Version 28  *****************
//User: Parveen      Date: 9/16/09    Time: 12:20p
//Updated in $/LeapCC/Model
//getEmployeeList (employeeImage field added)
//
//*****************  Version 27  *****************
//User: Jaineesh     Date: 9/10/09    Time: 12:39p
//Updated in $/LeapCC/Model
//put new function deleteUserRole() to delete user from user role
//
//*****************  Version 26  *****************
//User: Parveen      Date: 9/10/09    Time: 12:36p
//Updated in $/LeapCC/Model
//getIcardEmployeeList filed added (employeeImage)
//
//*****************  Version 25  *****************
//User: Parveen      Date: 9/10/09    Time: 10:55a
//Updated in $/LeapCC/Model
//getIcardEmployeeList, getTotalIcardEmployeeList function modify (fields
//name added)
//
//*****************  Version 24  *****************
//User: Parveen      Date: 9/09/09    Time: 5:09p
//Updated in $/LeapCC/Model
//getIcardEmployeeList function added
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 8/31/09    Time: 7:33p
//Updated in $/LeapCC/Model
//fixed bug nos. 0001366, 0001358, 0001305, 0001304, 0001282
//
//*****************  Version 22  *****************
//User: Parveen      Date: 8/31/09    Time: 12:45p
//Updated in $/LeapCC/Model
//deletePublisherFailedUpload  function added
//
//*****************  Version 21  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 8/10/09    Time: 4:36p
//Updated in $/LeapCC/Model
//delete user from user_log & user_prefs
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 7/22/09    Time: 10:50a
//Updated in $/LeapCC/Model
//update user status in user table
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 7/21/09    Time: 3:10p
//Updated in $/LeapCC/Model
//fixed bug no.0000613
//
//*****************  Version 17  *****************
//User: Parveen      Date: 7/21/09    Time: 12:42p
//Updated in $/LeapCC/Model
//addPublisher, editPublisher, listPublisher function updated (added
//"attachmentAcceptationLetter" fields)
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 7/17/09    Time: 6:11p
//Updated in $/LeapCC/Model
//update in query of editUser
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 7/17/09    Time: 6:04p
//Updated in $/LeapCC/Model
//modification in query during addEmployee
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 7/17/09    Time: 4:05p
//Updated in $/LeapCC/Model
//put transactions
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 7/17/09    Time: 11:27a
//Updated in $/LeapCC/Model
//modification in query add null while not selecting country,state,city
//
//*****************  Version 12  *****************
//User: Parveen      Date: 7/16/09    Time: 5:14p
//Updated in $/LeapCC/Model
//new enhancements added (publisher, workshop, consulting, seminar)
//functions
//
//*****************  Version 11  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Model
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 6/30/09    Time: 12:01p
//Updated in $/LeapCC/Model
//Make the correction in employee code should be unique
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/29/09    Time: 12:19p
//Updated in $/LeapCC/Model
//getEmployeeList function updated
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Model
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/24/09    Time: 2:19p
//Updated in $/LeapCC/Model
//getEmployeeList query (dateofbirth, dateOfJoining, employeePhoto fields
//added)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/24/09    Time: 2:05p
//Updated in $/LeapCC/Model
//getEmployeeList function motherName added
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/24/09    Time: 2:04p
//Updated in $/LeapCC/Model
//to show only those institutes where employee does not work
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/27/09    Time: 7:34p
//Updated in $/LeapCC/Model
//fixed bugs & enhancement No.1071,1072,1073,1074,1075,1076,1077,1079
//issues of Issues [25-May-09]Build# cc0006.doc
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/10/09    Time: 1:10p
//Updated in $/LeapCC/Model
//modified in query to show employee data where it exists in
//emplyee_can_teach_in or not
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:58p
//Updated in $/LeapCC/Model
//modified for Teaching in institute field
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 11/25/08   Time: 5:29p
//Updated in $/Leap/Source/Model
//modified in query getTotalEmployee()
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 11/19/08   Time: 5:30p
//Updated in $/Leap/Source/Model
//add new field status (active or deactive)
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 10/06/08   Time: 6:11p
//Updated in $/Leap/Source/Model
//modification in employee manager during edit of an employee
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/29/08    Time: 3:37p
//Updated in $/Leap/Source/Model
//modified in query
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/02/08    Time: 11:01a
//Updated in $/Leap/Source/Model
//modification for instituteId
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/16/08    Time: 4:41p
//Updated in $/Leap/Source/Model
//modification in validation or check for insertion data
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:52p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/14/08    Time: 4:50p
//Updated in $/Leap/Source/Model
//Add new 4 date fields
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:28p
//Updated in $/Leap/Source/Model
//modification in employee in templates & functions & make new function
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/10/08    Time: 3:09p
//Updated in $/Leap/Source/Model
//modified in tab indexing and edit
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/04/08    Time: 11:07a
//Updated in $/Leap/Source/Model
//modified for role name
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:56p
//Created in $/Leap/Source/Model
//Used for add, delete, update the files
?>
