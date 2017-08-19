<?php
//-------------------------------------------------------
//  This File contains Bussiness Logic of the "Payroll" Module
//
//
// Author :Abhiraj Malhotra
// Created on : 04-April-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class PayrollManager {
	private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "PayrollManager" CLASS
//
// Author :Abhiraj Malhotra 
// Created on : 04-April-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

	
	private function __construct() {
	}
	

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "PayrollManager" CLASS
//
// Author :Abhiraj 
// Created on : 04-April-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
	
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A deduction Account
//
// Author :Abhiraj Malhotra 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    
    public function addAccount() {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoInsert('deduction_account', array('accountName','accountNumber'), array($REQUEST_DATA['accountName'],$REQUEST_DATA['accountNumber']));
    }
    
    
    
//-------------------------------------------------------------------------------

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING heads
//
// Author :Abhiraj 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    
    public function addHead() {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoInsert('salary_head', array('headName','headType','dedAccountId','headDesc','headAbbr'), array($REQUEST_DATA['headName'],$REQUEST_DATA['headType'],$REQUEST_DATA['dedAccountId'],$REQUEST_DATA['headDesc'],$REQUEST_DATA['headAbbr']));
    }
    
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED to inactive all previous head mappings before adding a new one for the same employee
//
// Author :Abhiraj 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     
     public function inactivePrevHeadMappings($empId)
     {
         SystemDatabaseManager::getInstance()->runAutoUpdate('employee_salary_breakup', 
         array('active'), array(0), "employeeId=$empId" );
     }
     
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR Saving mapped heads
//
// Author :Abhiraj 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
     public function saveHeadsMapping($headDataArray,$empId,$wefDate)
     {
         return SystemDatabaseManager::getInstance()->runAutoInsert('employee_salary_breakup', array('employeeId','withEffectFrom','headId'         ,'headValue'), array($empId,$wefDate,
         $headDataArray['headId'],$headDataArray['amount']));        
     }
    
    
//-------------------------------------------------------------------------------

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED to inactive all previous head mappings before adding a new one for the same employee
//
// Author :Abhiraj 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     
     public function updateGenerateBit($month,$year)
     {
         $query="update employee_salary_breakup set generated=1 where substring(MONTHNAME(withEffectFrom),1,3) like '".$month."' and YEAR(withEffectFrom) like '".$year."'"; 
         logError($query);
         return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");  
     }
     
//-------------------------------------------------------------------------------

// THIS FUNCTION IS USED FOR EDITING deduction account
//
// Author :Abhiraj  
// Created on : 1-May-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           
    
    
    public function editAccount($id) {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('deduction_account', array('accountName','accountNumber'), array($REQUEST_DATA['accountName'],$REQUEST_DATA['accountNumber']), "dedAccountId=$id" );
    }    
    
    
//-------------------------------------------------------------------------------

// THIS FUNCTION IS USED FOR EDITING A head
//
// Author :Abhiraj 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           
    
    
    public function editHead($id) {
        global $REQUEST_DATA;
        return SystemDatabaseManager::getInstance()->runAutoUpdate('salary_head', array('headName','headType','dedAccountId','headDesc','headAbbr'), array($REQUEST_DATA['headName'],$REQUEST_DATA['headType'],$REQUEST_DATA['dedAccountId'],$REQUEST_DATA['headDesc'],$REQUEST_DATA['headAbbr']), "headId=$id" );
    }    

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A deduction account
//
// Author :Abhiraj
// Created on : 10-Apr-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function deleteAccount($dedAccountId) {
     
        $query = "DELETE 
        FROM deduction_account 
        WHERE dedAccountId=$dedAccountId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------

// THIS FUNCTION IS USED FOR DELETING A head
//
// Author :Abhiraj 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------     

    public function deleteHead($headId) {
     
        $query = "DELETE 
        FROM salary_head 
        WHERE headId=$headId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------------------------------

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING deduction account list
//
// Author :Abhiraj 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    public function getDedAccount($conditions='') {
     
        $query = "SELECT dedAccountId, accountName, accountNumber 
        FROM deduction_account 
        $conditions";
       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//-------------------------------------------------------------------------------


// THIS FUNCTION IS USED FOR GETTING Bank LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
   	
	
    public function getYearList($conditions='', $limit = '', $orderBy=' startDate') {
        $query = "SELECT financialYearId, startYear, endYear  
		FROM financial_year $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
	
//-------------------------------------------------------------------------------


// THIS FUNCTION IS USED FOR GETTING Bank LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
       
    
    public function getHeadList($conditions='', $limit = '', $orderBy=' headName') {
        $query = "SELECT headId, headName, headType, headDesc, dedAccountId, headAbbr  
        FROM salary_head $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
// THIS FUNCTION IS USED FOR Checking whether there is any previous salary not yet generated 
//
// Author :Abhiraj Malhotra
// Created on : 11-June-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
       
    
    public function countSalariesMapped($wef) {
        $query = "SELECT count(*) as recordCount from employee_salary_breakup where withEffectFrom<'".$wef."' and generated=1";
        logError("query is: ".$query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
      
 
// THIS FUNCTION IS USED FOR Checking whether there is any salary 
//
// Author :Abhiraj Malhotra
// Created on : 11-June-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
       
    
    public function countSalariesMappedCurrent($wef) {
        $query = "SELECT count(*) as recordCount from employee_salary_breakup where withEffectFrom='".$wef."' and generated=0";
        logError("query is: ".$query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
// THIS FUNCTION IS USED FOR Checking Previous Months's salary has been generated or not 
//
// Author :Abhiraj Malhotra
// Created on : 11-June-2009
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
       
    
    public function checkPrevGeneratedSalary($month) {
        $query = "SELECT count(distinct withEffectFrom) as recordCount from employee_salary_breakup where substring(MONTHNAME(withEffectFrom),1,3)='".$month."' and generated=1";
        logError($query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }      
    
//-------------------------------------------------------------------------------    
//-------------------------------------------------------------------------------
 // THIS FUNCTION IS USED FOR GETTING Bank LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------- 
public function truncateTable($tableName)
{
   $query="delete from $tableName";
   return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
}

 public function updateTempHeadMapping($headId)
 {
    global $sessionHandler;
    $query_1 = "SELECT count(*) as found from salary_head_temp
                where headId=".$headId;
    $foundArray=SystemDatabaseManager::getInstance()->executeQuery($query_1,"Query: $query");
    //logError($foundArray[0]['found']);
    if($foundArray[0]['found']==0)
    {       
       return SystemDatabaseManager::getInstance()->runAutoInsert('salary_head_temp', 
       array('userId','headId','amount'), array($sessionHandler->getSessionVariable('UserId'),$headId,''));
    }
    //else
    //{
       //logError("Else");
       // return 2;
    //}
       
 }
 
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
       
    
    public function getAccountList($conditions='', $limit = '', $orderBy=' accountName Asc') {
        $query = "SELECT dedAccountId, accountName, accountNumber  
        FROM deduction_account $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    public function getSalariedEmployee($conditions='',$orderBy='employeeName',$limit='') {
        global $sessionHandler;
        $query = "SELECT * from employee where employeeId in(select distinct employeeId from employee_salary_breakup 
        $conditions) and instituteId=".$sessionHandler->getSessionVariable("InstituteId")." order By $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
    

// THIS FUNCTION IS USED FOR GETTING Bank LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
       
    
    public function getHead($conditions='', $limit = '', $orderBy=' headName') {
        $query = "SELECT headId, headName, headType, headDesc, dedAccountId, headAbbr  
        FROM salary_head $conditions ORDER BY $orderBy $limit ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    
//-------------------------------------------------------------------------------

// THIS FUNCTION IS USED FOR GETTING Bank LIST 
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
    public function getAssignedHeads($conditions='') {
        $query = "SELECT *  
        FROM employee_salary_breakup $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    public function getTempHead($conditions='') { 
        $query = "SELECT * from salary_head_temp  
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
    public function updateAmount($headId,$val) {
        global $REQUEST_DATA;
        global $sessionHandler;
        $query='update salary_head_temp set amount='.$val.' where
        headId= '.$headId.' and userId= '.$sessionHandler->getSessionVariable('UserId');
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    } 
    
    public function checkHeadOverwrite($wefDate, $employeeId)
    {
      $query="select count(*) as found from employee_salary_breakup
      where withEffectFrom like '".$wefDate."' and employeeId=$employeeId";
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
    public function removeHeads($wefDate, $employeeId)
    {
      $query="delete from employee_salary_breakup
      where withEffectFrom like '".$wefDate."' and employeeId=$employeeId";
      return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");  
    } 
    
//-------------------------------------------------------------------------------

// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Bank" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
		
	 
    public function getTotalYears($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM financial_year ";
		if ($conditions != '') {
			$query .= " $conditions ";
		}
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------

// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Bank" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
        
     
    public function getTotalHeads($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM salary_head ";
        if ($conditions != '') {
            $query .= " $conditions ";
        }
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //-------------------------------------------------------------------------------
    
    //-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Bank" TABLE
//
// Author :Ajinder Singh 
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
        
     
    public function getTotalAccounts($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM deduction_account ";
        if ($conditions != '') {
            $query .= " $conditions ";
        }
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
    
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Fee Receipt" TABLE
//
// Author :Jaineesh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	 
    public function checkInSalaryBreakup($yearId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM employee_salary_breakup
        WHERE financialYearId=$yearId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
//-------------------------------------------------------------------------------
    
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Fee Receipt" TABLE
//
// Author :Jaineesh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    public function checkHeadInSalaryBreakup($headId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM employee_salary_breakup
        WHERE headId=$headId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//general function created for one or more fields from one or more table with option of conditions

// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------
	public function getSingleField($table, $field, $conditions='') {
		$query = "SELECT $field FROM $table $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
    public function getHeadAbbr($conditions='') {
        $query = "SELECT headAbbr FROM salary_head $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
    public function getHeadType($conditions='') {
        $query = "SELECT headType FROM salary_head $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Bank LIST 
//
// Author :Abhiraj malhotra 
// Created on : 20-April-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------          
       
    
    public function getDedAccountList() {
        $query = "SELECT dedAccountId, accountName, accountNumber  
        FROM deduction_account ORDER BY accountName";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
    

//-------------------------------------------------------------------------------
//-------------------------------------------------------------------------------     
// THIS FUNCTION IS USED FOR COUNTING RECORDS IN "Fee Receipt" TABLE
//
// Author :Jaineesh
// Created on : 19-July-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
     
    public function checkInSalaryHead($dedAccountId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM salary_head
        WHERE dedAccountId=$dedAccountId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//For payroll reports  

     public function getEmployeeId($userId)
     {
         $query = "SELECT employeeId 
        FROM employee where userId=$userId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }   
     
     public function getMonth($conditions='') {
     
        $query = "SELECT distinct(MONTHNAME(withEffectFrom)) AS month 
        FROM employee_salary_breakup $conditions";
        logError($query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getYear($conditions='') {
     
        $query = "SELECT distinct(YEAR(withEffectFrom)) AS year 
        FROM employee_salary_breakup $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------     
// THIS FUNCTION IS USED FOR CHECKING WHETHER SALARY IS GENERATED FOR A MONTH AND YEAR COMBINATION
//
// Author :Abhiraj
// Created on : 07-May-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------         
     public function checkSalaryGenerated() {
     
        $query = "SELECT distinct month, year 
        FROM salary_generated_history";
        logError($query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
//-------------------------------------------------------------------------------

//-------------------------------------------------------------------------------     
// THIS FUNCTION IS USED FOR CHECKING WHETHER SALARY IS GENERATED FOR A MONTH AND YEAR COMBINATION
//
// Author :Abhiraj
// Created on : 11-June-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------         
    /* public function checkPreviousMonthGeneration() {
     
        $query = "SELECT distinct MONTHNAME(, year 
        FROM salary_generated_history";
        logError($query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } */
//-------------------------------------------------------------------------------      
// THIS FUNCTION IS USED FOR CLEARING RECORDS WHILE REGENERATING SALARY
//
// Author :Abhiraj
// Created on : 07-May-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------         
     public function clearSalaryGenerated($empId,$month,$year) {
     
        $query = "delete from employee_salary_breakup where employeeId in(select employeeId from
        salary_generated_history where employeeId=$empId and month='$month' and year='$year' and status=0) and SUBSTRING(MONTHNAME(withEffectFrom),1,3)='$month' and
        YEAR(withEffectFrom)='$year'";
        logError($query) ;
        SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
        $query="delete from salary_generated_history where month='$month' and year='$year' and employeeId=$empId";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");  
    }
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A deduction Account
//
// Author :Abhiraj Malhotra 
// Created on : 10-Apr-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

    
    public function recordSalaryGenerated($empId,$month,$year,$status) {
        return SystemDatabaseManager::getInstance()->runAutoInsert('salary_generated_history', array('employeeId','month','year','status'), array($empId,$month,$year,$status));
    }
    
    
    
//-------------------------------------------------------------------------------     
// THIS FUNCTION IS USED FOR getting salary history details of an employee for a month year combination
//
// Author :Abhiraj
// Created on : 10-May-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------         
     public function getEmpSalaryHistory($empId,$month,$year) {
     
        $query = "select * from salary_generated_history where month='$month' and year='$year' and
        employeeId='$empId'";
        logError("Thea query is: ".$query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
//-------------------------------------------------------------------------------

//-------------------------------------------------------------------------------     
// THIS FUNCTION IS USED FOR getting details from salary stop table
//
// Author :Abhiraj
// Created on : 10-May-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------         
     public function getSalaryHoldDetails($empId,$month,$year) {
     
        $query = "select * from stop_salary where month='$month' and year='$year' and
        employeeId='$empId' and active=1 order by takenon desc limit 0,1";
        logError("The query is:".$query);
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
//-------------------------------------------------------------------------------

//-------------------------------------------------------------------------------     
// THIS FUNCTION IS USED FOR getting all details from salary stop table
//
// Author :Abhiraj
// Created on : 10-May-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------         
     public function getAllSalaryHoldDetails($empId) {
     
        $query = "select * from stop_salary where employeeId='$empId' order by takenon desc";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
//-------------------------------------------------------------------------------

//-------------------------------------------------------------------------------     
// THIS FUNCTION IS USED FOR inserting record in hold table
//
// Author :Abhiraj
// Created on : 10-May-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------         
     public function holdUnholdSalary($empId,$month,$year,$reason,$actionBy,$status) {
        return SystemDatabaseManager::getInstance()->runAutoInsert('stop_salary', array('employeeId','month','year','reason','actionby'
        ,'status','active','takenon'), array($empId,$month,$year,$reason,$actionBy,$status,1,date('Y-m-d')));  
    }
//-------------------------------------------------------------------------------
//-------------------------------------------------------------------------------     
// THIS FUNCTION IS USED FOR inserting record in hold table
//
// Author :Abhiraj
// Created on : 10-May-2010
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------         
     public function clearPrevHolds($empId,$month,$year) {
        $query="update stop_salary set active=0 where employeeId=$empId and month='$month' and 
        year='$year'";
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");  
    }
//-------------------------------------------------------------------------------
	
}
?>