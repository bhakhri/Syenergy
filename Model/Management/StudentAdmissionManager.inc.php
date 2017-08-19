<?php
//---------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "teacher" module
//
//
// Author :Rajeev Aggarwal 
// Created on : (12.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------
?>
<?php

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId 

class StudentAdmissionManager {

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

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctYear($conditions='',$limit = ' LIMIT 0,5', $orderBy=' dateOfAdmission DESC'){
	 
		$query = "SELECT 
				 DISTINCT(YEAR(dateOfAdmission))  as admissionYear
				 
				 FROM student 
				 $conditions
				 GROUP BY dateOfAdmission  
				 
				 ORDER BY $orderBy $limit 
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getCityAdmissionYear($conditions='',$limit = ' LIMIT 0,5', $orderBy=' dateOfAdmission DESC'){
	 
		$query = "SELECT 
		
				count(*) as countRecords ,YEAR(dateOfAdmission) as yearAdmission,cityName,corrCityId 
				
				FROM 
				student stu,city cty 
				
				WHERE 
				
				stu.corrCityId = cty.cityId 
				
				GROUP BY stu.corrCityId,YEAR(dateOfAdmission) 
				ORDER BY  countRecords  DESC
				 
				  
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years branch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctBranchYear($conditions='',$limit = ' LIMIT 0,5', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
        
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT 
				 DISTINCT(YEAR(dateOfAdmission))    as admissionYear
				 
				 FROM 
				 student stu, class cls, branch br 
				 
				 WHERE 
				 
				 stu.classId = cls.classId AND cls.branchId = br.branchId AND cls.instituteId=$instituteId
				 
				 GROUP BY br.branchName,dateOfAdmission  
				 
				 ORDER BY $orderBy $limit 
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years branch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctBranch($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
        
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT 
				 DISTINCT(br.branchCode)  
				 
				 FROM 
				 
				 student stu, class cls, branch br 
				 
				 WHERE 
				 stu.classId = cls.classId AND 
				 cls.branchId = br.branchId  AND cls.instituteId=$instituteId
				 
				 GROUP BY 
				 br.branchCode,dateOfAdmission 
				 
				 ORDER BY $orderBy $limit 
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years degree wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctDegreeYear($conditions='',$limit = ' LIMIT 0,5', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT 
				 DISTINCT(YEAR(dateOfAdmission))    as admissionYear
				 
				 FROM 
				 student stu, class cls, degree deg 
				 
				 WHERE 
				 
				 stu.classId = cls.classId AND deg.degreeId = cls.degreeId 
				 AND cls.instituteId=$instituteId
				 GROUP BY deg.degreeCode,dateOfAdmission  
				 
				 ORDER BY $orderBy $limit 
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years degree wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctDegree($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT 
				 DISTINCT(deg.degreeCode)  
				 
				 FROM 
				 
				 student stu, class cls, degree deg 
				 
				 WHERE 
				 stu.classId = cls.classId AND 
				 cls.degreeId = deg.degreeId 
				 AND cls.instituteId=$instituteId
				 GROUP BY 
				 deg.degreeCode,stu.dateOfAdmission 
				 
				 ORDER BY $orderBy $limit 
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years degree wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getCountDegreeYear($condition=''){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');  
		$query = "SELECT 
		
				 COUNT(*) as totalRecords 
				 
				 FROM 
				 student stu, class cls, degree deg 
				 
				 WHERE 
				 
				 stu.classId = cls.classId AND 
				 cls.degreeId = deg.degreeId 
                 AND cls.sessionId=$sessionId 
				 AND cls.instituteId=$instituteId
				 $condition 
				 
				 ORDER BY deg.degreeCode" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of student admission years branch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDegreeList($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');  
		$query = "SELECT 
				 stu.firstName,stu.lastName,stu.studentGender,if(stu.studentEmail='','--',stu.studentEmail) AS studentEmail,stu.studentMobileNo, IF(dateOfBirth,DATE_FORMAT( stu.dateOfBirth, '%d-%b-%Y' ),'--') AS dateOfBirth,stu.fatherTitle,stu.fatherName,SUBSTRING_INDEX(substring_index(className,'".CLASS_SEPRATOR."',5),'".CLASS_SEPRATOR."',-3) AS className
				 
				 FROM 
				 
				 student stu, class cls, degree deg
				 
				 WHERE 
				 stu.classId = cls.classId AND 
				 cls.degreeId = deg.degreeId AND
				 cls.instituteId = $instituteId
				  AND cls.sessionId=$sessionId 
				 $conditions
				 
				 
				 ORDER BY $orderBy $limit 
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years batch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctBatchYear($conditions='',$limit = ' LIMIT 0,5', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT 
				 DISTINCT(YEAR(dateOfAdmission)) as admissionYear
				 
				 FROM 
				 student stu, class cls, batch bat
				 
				 WHERE 
				 
				 stu.classId = cls.classId AND 
				 bat.batchId= cls.batchId AND cls.instituteId=$instituteId
				 $conditions
				 GROUP BY bat.batchName,stu.dateOfAdmission  
				 
				 ORDER BY $orderBy $limit 
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years degree wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctBatch($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){

		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	 
		$query = "SELECT 
				 DISTINCT(bat.batchName)  
				 
				 FROM 
				 
				 student stu, class cls, batch bat
				 
				 WHERE 
				 stu.classId = cls.classId AND 
				 bat.batchId= cls.batchId  AND cls.instituteId=$instituteId
				 
				 GROUP BY 
				 bat.batchName,stu.dateOfAdmission 
				 
				 ORDER BY $orderBy $limit 
			" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years degree wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getCountBatchYear($condition=''){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
        $query = "SELECT 
		
				 COUNT(*) as totalRecords 
				 
				 FROM 
				 student stu, class cls, batch bat
				 
				 WHERE 
				 
				 stu.classId = cls.classId AND 
				 bat.batchId= cls.batchId  AND 
                 cls.sessionId=$sessionId  
                 AND cls.instituteId=$instituteId
				 
				 $condition 
				 
				 GROUP BY bat.batchName" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years category wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctCategoryYear($conditions='',$limit = ' LIMIT 0,5', $orderBy=' dateOfAdmission DESC'){
	 
		
		$query = "SELECT 
				 DISTINCT(YEAR(dateOfAdmission)) as admissionYear
				 
				 FROM 
				 student stu, quota qut
				 
				 WHERE 
				 
				 stu.quotaId = qut.quotaId 
				 $conditions

				 GROUP BY qut.quotaAbbr,stu.dateOfAdmission  
				 
				 ORDER BY $orderBy $limit";
				 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT quota category wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctCategory($conditions='',$limit = '', $orderBy=' qta.quotaAbbr'){
	 
		$query = "SELECT 
				 DISTINCT(qta.quotaAbbr)  
				 
				 FROM 
				 
				 student stu, quota qta
				 
				WHERE 
				 
				 stu.quotaId = qta.quotaId 
				 $conditions
				 
				 GROUP BY 
				 qta.quotaAbbr 
				 
				 ORDER BY $orderBy $limit" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting count category year wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getCountCategoryYear($condition=''){
	 
	    global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');  
		$query = "SELECT 
		
				 COUNT(*) as totalRecords 
				                                                                                   
				 FROM 
				 student stu, quota qta, class cls
				 
				 WHERE 
				 stu.classId = cls.classId AND
				 cls.instituteId=$instituteId AND
                 cls.sessionId=$sessionId AND
				 stu.quotaId = qta.quotaId 
				 
				 $condition 
				 
				 GROUP BY qta.quotaAbbr" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of student admission years gender wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getGenderList($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId'); 
		$query = "SELECT 
				 stu.firstName,stu.lastName,stu.studentGender,if(stu.studentEmail='','--',stu.studentEmail) AS studentEmail,stu.studentMobileNo, IF(dateOfBirth,DATE_FORMAT( stu.dateOfBirth, '%d-%b-%Y' ),'--') AS dateOfBirth,stu.fatherTitle,stu.fatherName,SUBSTRING_INDEX(substring_index(className,'".CLASS_SEPRATOR."',5),'".CLASS_SEPRATOR."',-3) AS className
				 
				 FROM 
				 
				 student stu, class cls
				 
				 WHERE 
				 stu.classId = cls.classId   AND
				 cls.sessionId=$sessionId AND				 
				cls.instituteId = $instituteId AND
				 $conditions

				 ORDER BY $orderBy $limit" ;
				 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	
		//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of student admission years branch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getQuotaList($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$query = "SELECT 
				 stu.firstName,stu.lastName,stu.studentGender,if(stu.studentEmail='','--',stu.studentEmail) AS studentEmail,stu.studentMobileNo, IF(dateOfBirth,DATE_FORMAT( stu.dateOfBirth, '%d-%b-%Y' ),'--') AS dateOfBirth,stu.fatherTitle,stu.fatherName,SUBSTRING_INDEX(substring_index(className,'".CLASS_SEPRATOR."',5),'".CLASS_SEPRATOR."',-3) AS className
				 
				 FROM 
				 
				 student stu, quota qta, class cls
				 
				 WHERE 
				 stu.classId = cls.classId AND
				 qta.quotaId = stu.quotaId  AND
				 cls.instituteId = $instituteId AND
				 cls.sessionId=$sessionId
				 $conditions

				 ORDER BY $orderBy $limit" ;
				
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years category wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctHostelYear($conditions='',$limit = ' LIMIT 0,5', $orderBy=' dateOfAdmission DESC'){
	 
		$query = "SELECT 
				 DISTINCT(YEAR(dateOfAdmission)) as admissionYear
				 
				 FROM 
				 student stu, hostel hst
				 
				 WHERE 
				 
				 stu.hostelId = hst.hostelId 
				 $conditions

				 GROUP BY hst.hostelCode,stu.dateOfAdmission  
				 
				 ORDER BY $orderBy $limit";
				 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting count category year wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getCountHostelYear($condition=''){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');    
		$query = "SELECT 
		
				 COUNT(*) as totalRecords 
				 
				 FROM 
				 student stu, hostel hst,class cls

				 WHERE
		         stu.classId = cls.classId AND cls.instituteId=$instituteId
				 AND cls.sessionId=$sessionId
				 $condition   
				 
				 GROUP BY hst.hostelCode" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years gender wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getDistinctGenderYear($conditions='',$limit = ' LIMIT 0,5', $orderBy=' dateOfAdmission DESC'){
	 
		$query = "SELECT 
				 DISTINCT(YEAR(dateOfAdmission)) as admissionYear
				 
				 FROM 
				 student stu
				 
				 WHERE 
				 
				 stu.studentGender !='' 
				 $conditions

				 GROUP BY stu.studentGender,stu.dateOfAdmission  
				 
				 ORDER BY $orderBy $limit";
				 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting count gender year wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getCountGenderYear($condition=''){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId');   
		$query = "SELECT 
		
				 COUNT(*) as totalRecords 
				 
				 FROM 
				 student stu,class cls
				 
				 $condition AND stu.classId=cls.classId AND cls.instituteId=$instituteId
				 AND  cls.sessionId=$sessionId
				 GROUP BY stu.studentGender" ;   
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of student admission years branch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getBranchList($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$sessionId=$sessionHandler->getSessionVariable('SessionId'); 
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		$query = "SELECT 
				 stu.firstName,stu.lastName,stu.studentGender,if(stu.studentEmail='','--',stu.studentEmail) AS studentEmail,stu.studentMobileNo, IF(dateOfBirth,DATE_FORMAT( stu.dateOfBirth, '%d-%b-%Y' ),'--') AS dateOfBirth,stu.fatherTitle,stu.fatherName,SUBSTRING_INDEX(substring_index(className,'".CLASS_SEPRATOR."',5),'".CLASS_SEPRATOR."',-3) AS className
				 
				 FROM 
				 
				 student stu, class cls, branch br 
				 
				 WHERE 
				 stu.classId = cls.classId AND 
				 cls.branchId = br.branchId AND cls.instituteId=$instituteId 
				 AND cls.sessionId = $sessionId
				 $conditions
				 
				 
				 ORDER BY $orderBy $limit 
			" ;  
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of DISTINCT admission years branch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getCountBranchYear($condition=''){
	 
		global $sessionHandler;
        
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId=$sessionHandler->getSessionVariable('SessionId'); 
		$query = "SELECT 
		

				 COUNT(*) as totalRecords 
				 
				 FROM 
				 
				 student stu, class cls, branch br 

				 
				 WHERE 
				 stu.classId = cls.classId AND 
				 cls.branchId = br.branchId  AND cls.instituteId=$instituteId
                 AND cls.sessionId=$sessionId

				 
				 $condition 
				 
				 ORDER BY cls.className DESC" ;
	
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}
	

	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of student admission years branch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getBatchList($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT 
				 stu.firstName,stu.lastName,stu.studentGender,if(stu.studentEmail='','--',stu.studentEmail) AS studentEmail,stu.studentMobileNo, IF(dateOfBirth,DATE_FORMAT( stu.dateOfBirth, '%d-%b-%Y' ),'--') AS dateOfBirth,stu.fatherTitle,stu.fatherName,SUBSTRING_INDEX(substring_index(className,'".CLASS_SEPRATOR."',5),'".CLASS_SEPRATOR."',-3) AS className
				 
				 FROM 
				 
				 student stu, class cls, batch bat
				 
				 WHERE 
				 stu.classId = cls.classId AND 
				 cls.batchId = bat.batchId  AND
				 cls.instituteId = $instituteId
				 $conditions

				 ORDER BY $orderBy $limit" ;
				 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}


	//---------------------------------------------------------------------------------------------------------
	// THIS FUNCTION IS USED FOR getting list of student admission years branch wise
	//
	// $conditions :db clauses
	// Author :Rajeev Aggarwal 
	// Created on : (01.09.2008)
	// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
	//
	//---------------------------------------------------------------------------------------------  
	public function getHostelList($conditions='',$limit = '', $orderBy=' dateOfAdmission DESC'){
	 
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$query = "SELECT DISTINCT(studentId),
				 stu.firstName,stu.lastName,stu.studentGender,if(stu.studentEmail='','--',stu.studentEmail) AS studentEmail,stu.studentMobileNo, IF(dateOfBirth,DATE_FORMAT( stu.dateOfBirth, '%d-%b-%Y' ),'--') AS dateOfBirth,stu.fatherTitle,stu.fatherName,SUBSTRING_INDEX(substring_index(className,'".CLASS_SEPRATOR."',5),'".CLASS_SEPRATOR."',-3) AS className
				 
				 FROM 
				 
				 student stu, hostel hst, class cls
				 
				 WHERE 
				 stu.classId = cls.classId    AND
				 
				 cls.instituteId = $instituteId
				 
				 $conditions

				 ORDER BY $orderBy $limit" ;
				 
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

	
    
}
// $History: StudentAdmissionManager.inc.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-19   Time: 3:30p
//Updated in $/LeapCC/Model/Management
//Updated files with InstituteId values in queries
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/27/08   Time: 5:32p
//Created in $/LeapCC/Model/Management
//intial checkin
?>
