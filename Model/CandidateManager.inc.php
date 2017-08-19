<?php
//-------------------------------------------------------
//  This file is used to operate all operation related to Candidate
//
//
// Author :Vimal Sharma
// Created on : (05.02.2009 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class CandidateManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CandidateManager" CLASS
//
// Author : Vimal Sharma
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CandidateManager" CLASS
//
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
//-------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK AUTHENTICATION OF CANDIDATE 
//
// $fistName    :   First name of the Candidate
// $dob         :   Birth Date of Candidate
// $rollNo      :   AIEEE Roll No of Candidate
//$conditions :db clauses
// Author :Vimal Sharma 
// Created on : (12.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function authenticateCandidate ($firstName, $dob, $rollNo, $conditions = '') {
     
        $query = "SELECT candidateId, candidateName 
        FROM adm_application_form     
        WHERE candidateName LIKE '$firstName%' AND dateOfBirth = '$dob' AND AIEEERollNo = '$rollNo' 
        $conditions";
     echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING Candidate Details
//
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addCandidate($insertValues) {
		$query = "INSERT INTO adm_application_form SET ";
        $query .= $insertValues;
		return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query" );
	}
//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A Bank Branch
//
// Author :Rajeev Aggarwal
// Created on : 04-06-2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       	
	
    public function editCandidateDetail($id) {

        global $REQUEST_DATA;

		$dobArr = explode("-",$REQUEST_DATA['dob']);
		$dateOfBirth = $dobArr['2'].'-'.$dobArr['1'].'-'.$dobArr['0'];
        return SystemDatabaseManager::getInstance()->runAutoUpdate('adm_application_form', array('candidateName','dateOfBirth','fatherGuardianName','fatherGuardianMobileNo','relationWithCandidate','AIEEERollNo','AIEEERank','hostelFacility','quotaId','hpBonafied','candidateMobileNo','candidateEmail','candidateGender','formNo'), array($REQUEST_DATA['candidateName'], $dateOfBirth, $REQUEST_DATA['fatherGuardianName'], $REQUEST_DATA['fatherGuardianMobile'], $REQUEST_DATA['relationWithCandidate'], $REQUEST_DATA['AIEEERollNo'], $REQUEST_DATA['AIEEERank'], $REQUEST_DATA['hostelFacility'], $REQUEST_DATA['candidateCategory'], $REQUEST_DATA['hpBonafied'], $REQUEST_DATA['candidateMobile'], $REQUEST_DATA['candidateEmail'], $REQUEST_DATA['genderRadio'], $REQUEST_DATA['formNo']), "candidateId=$id" );
    }    

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING CANDIDATE DETAILS
//
//$id:candidate
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editCandidate($query) {
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query" ); 
    }   
 
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING CANDIDATE DETAILS IN TRANSACTION
//
//$id:candidate
// Author :Vimal Sharma 
// Created on : (12.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function editCandidateInTransaction($query) {
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    }      
    
//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET CANDIDATE DETAIL
//
//$conditions :db clauses
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getCandidate($conditions='') {
     
        $query = "SELECT * 
        FROM adm_application_form
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET CANDIDATE DETAIL
//
//$conditions :db clauses
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getCandidateAllDetail($conditions='') {
     
        $query = "SELECT *,ap.programFullName 
        FROM adm_application_form apf,adm_program ap,adm_fee_receipt afr

		WHERE

		apf.candidateId = afr.candidateId
		AND apf.programId = ap.programId
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE CANDIDATE
//
//$candidateId :candidateId of candidate
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteCandidate($candidateId) {
     
        $query = "DELETE 
        FROM adm_application_form 
        WHERE candidateId=$candidateId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET CANDIDATE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getCandidateList($conditions='', $limit = '', $orderBy=' candidateName') {
     
        $query = "SELECT * FROM adm_application_form  $conditions 
        ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET TOTAL NUMBERS OF CANDIDATE
//
//$conditions :db clauses
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalCandidate($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM adm_application_form
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
 

//-------------------------------------------------------
// THIS FUNCTION IS USED TO GET ALL CANDIDATES TO ASSIGN PROGRAM
//
//$conditions   :db clauses
// Author       :Vimal Sharma 
// Created on   : (11.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
public function getAllCandidate($conditions='') {
    $query = "SELECT candidateId, programType,candidateStatus, programId FROM adm_application_form
            $conditions ORDER BY candidateStatus, AIEEERank";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}
 
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET LAST CANDIDATE ID
//
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getLastId() {
        return SystemDatabaseManager::getInstance()->lastInsertId();
    }  

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE ALL CNADIDATE RELATED DATA
//
//$conditions : Additional Criteria
// Author :Vimal Sharma 
// Created on : (09.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function emptyData($table, $conditions) {
    
        $query = "TRUNCATE TABLE $table";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET QuotaId of Quota Category
//
//$abbr : Quota Abbr
// Author :Vimal Sharma 
// Created on : (09.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getQuotaId($abbr) {
    
        $query = "SELECT quotaId FROM 
        quota WHERE quotaAbbr = '$abbr'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ALL PROGRAM
//
//$conditions :db clauses
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getProgramPreference($programType) {
    
        $query = "SELECT programId, programName FROM 
        adm_program WHERE programType = '$programType'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET PROGRAMID OF PROGRAM
//
//$programName : Name of program
//$conditions :db clauses
// Author :Vimal Sharma 
// Created on : (16.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getProgramId($programName, $conditions) {
    
        $query = "SELECT programId FROM 
        adm_program WHERE programName = '$programName' $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ALL PROGRAM
//
//$conditions :db clauses
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getAllProgram($conditions = '') {
    
        $query = "SELECT programId, programName,seats FROM 
        adm_program $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ALL PROGRAM PREFERENCES OF A CANDIDATE
//
//$candidateId : unique id of Candidate
// Author :Vimal Sharma 
// Created on : (06.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getCandidateProgramPreference($candidateId) {
    
        $query = "SELECT preference, programId, programName FROM
        adm_candidate_program_preference
        INNER JOIN adm_program USING (programId)
        WHERE candidateId = $candidateId
        ORDER BY preference";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
        

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING CANDIDATE PREFERENCES
//
// Author :Vimal Sharma 
// Created on : (05.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addCandidatePreferences($insertValues) {
        $query = "INSERT INTO adm_candidate_program_preference VALUES ";
        $query .= $insertValues;  
       
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query" );
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ALL PROGRAM PREFERENCES OF A CANDIDATE
//
//$candidateId : unique id of Candidate
// Author :Vimal Sharma 
// Created on : (15.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getCandidateProgramPreferenceList($candidateId) {
    
        $query = "SELECT preference, programId, programName FROM
        adm_candidate_program_preference
        INNER JOIN adm_program USING (programId)
        WHERE candidateId = $candidateId
        ORDER BY preference";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }       
 
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ALL PROGRAM OF A CANDIDATE BASED ON CANDIDATE CATEGORY
//
//$candidateId : unique id of Candidate
// Author :Rajeev Aggarwal 
// Created on : (15.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getCandidateProgramList($hpStatus) {
    
		if($hpStatus=='Y'){
		
			$query = "SELECT programId, programName,seats FROM adm_program";
		}
		if($hpStatus=='N'){
		
			$query = "SELECT programId, programName,seats FROM adm_program ";
		}
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
       /* $query = "SELECT preference, programId, programName FROM
        adm_candidate_program_preference
        INNER JOIN adm_program USING (programId)
        WHERE candidateId = $candidateId
        ORDER BY preference";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");*/
    }       
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO GET ALL PROGRAM OF A CANDIDATE BASED ON CANDIDATE CATEGORY
//
//$candidateId : unique id of Candidate
// Author :Rajeev Aggarwal 
// Created on : (15.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getReceiptNo() {
    
		$query = "SELECT receiptNo FROM adm_fee_receipt ORDER BY receiptNo DESC ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        
    }       
    	
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING CANDIDATE DETAILS IN TRANSACTION
//
// Author :Vimal Sharma 
// Created on : (16.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function editCandidateDetailsInTransaction($candidateStatus, $programId, $history, $conditions = '') {
        $query = "UPDATE adm_application_form SET candidateStatus = '$candidateStatus', programId = $programId, history = $history $conditions"; 
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    }  
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING PROGRAM STATUS IN TRANSACTION
//
// Author :Vimal Sharma 
// Created on : (16.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function editProgramDetailsInTransaction($queryStr) {
       $query = "UPDATE adm_program SET $queryStr";
       return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    } 
    
//-------------------------------------------------------
// THIS FUNCTION IS USED INSERT ALL SEAT AVAILABLE STATUS AT THE  TIME OF ALLOTING A SEAT TO A CANDIDATE
//
// Author :Vimal Sharma 
// Created on : (16.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function insertProgramAllotmentStatusInTransaction($insertValues) {
        $query = 'INSERT INTO adm_candidate_allotment_status VALUES ' . $queryStr; 
        $query .= $insertValues;    
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    } 
    
//-------------------------------------------------------
// THIS FUNCTION IS USED TO UPDATE ALL SEAT AVAILABLE STATUS AT THE  TIME OF ALLOTING A SEAT TO A CANDIDATE
//
// Author :Vimal Sharma 
// Created on : (18.04.2009)    
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
// $conditions : Additional Criteria 
//
//--------------------------------------------------------     
    public function editProgramAllotmentStatusInTransaction($conditions = '') {
        $query = "UPDATE adm_candidate_allotment_status SET isActive = 0 $conditions"; 
        $query .= $insertValues;    
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED INSERT FEE DETAIL IN TRANSACTION
//
// Author :Vimal Sharma 
// Created on : (07.06.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function insertFeeDetailsInTransaction($insertValues) {
        $query = 'INSERT INTO adm_fee_receipt SET ' . $queryStr; 
        $query .= $insertValues;    
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query" ); 
    }      
}






?>

