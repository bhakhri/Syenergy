<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "quota" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class QuotaManager {
	private static $instance = null;
	
//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "QuotaManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
	private function __construct() {
	}

//----------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "QuotaManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------    
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A QUOTA
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
	public function addQuota() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('quota', array('quotaAbbr','quotaName','parentQuotaId'), array(strtoupper(trim($REQUEST_DATA['quotaAbbr'])),trim($REQUEST_DATA['quotaName']),$REQUEST_DATA['parentQuotaId']) );
	}

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A QUOTA
//
//$id:quotaId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------     
    public function editQuota($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('quota', array('quotaAbbr','quotaName','parentQuotaId'), array(strtoupper(trim($REQUEST_DATA['quotaAbbr'])),trim($REQUEST_DATA['quotaName']),$REQUEST_DATA['parentQuotaId']), "quotaId=$id" );
    } 
       
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING QUOTA LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function getQuota($conditions='') {
     
        $query = "SELECT quotaId,quotaName,quotaAbbr,parentQuotaId
        FROM quota
        $conditions
        ORDER BY quotaName";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//------------------------------------------------------------------------------------------------------- 
//Purpose:To check whether the quota is parent of some other quota or not
//quota id is also in student but that check will be performed by datebase
//Author: Dipanjan Bhattacharjee
//Date : 17.07.008
//------------------------------------------------------------------------------------------------------- 
public function checkInQuota($quotaId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM quota 
        WHERE parentQuotaId=$quotaId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------------- 
//Purpose:To check whether the quota is Used in Fee 
//Author: Nishu Bindal
//Date : 7-May-12
//------------------------------------------------------------------------------------------------------- 
public function checkInFeeHeadValues($quotaId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM `fee_head_values_new`
        WHERE quotaId='$quotaId'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A QUOTA
//
//$cityId :quotaid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------         
    public function deleteQuota($quotaId) {
     
        $query = "DELETE 
        FROM quota
        WHERE quotaId=$quotaId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING QUOTA LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    public function getQuotaList($conditions='', $limit = '', $orderBy=' qt.quotaName') {
     
        $query = "SELECT qt.quotaId, qt.quotaName AS quotaName, 
        (if( p.quotaName IS NULL , '', p.quotaName )) AS parentQuota,qt.quotaAbbr,qt.parentQuotaId
        FROM `quota` qt 
        LEFT JOIN `quota` p ON qt.parentQuotaId = p.quotaId
        $conditions 
        ORDER BY $orderBy $limit";
        
        //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF QUOTAS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------         
    public function getTotalQuota($conditions='') {
    
        $query = "SELECT 
						 qt.quotaId,
	                     (if( p.quotaName IS NULL , '', p.quotaName )) AS parentQuota 
		         FROM 
					     `quota` qt 
	             LEFT JOIN `quota` p ON qt.parentQuotaId = p.quotaId
				 $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL Seats intakes OF QUOTAS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------         
    public function getSeatList($conditions='') {
        
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
    
        $query = "SELECT 
                       DISTINCT cc1.classId, cc2.quotaId, c.className 
                  FROM
                      `class` c, `class_quota_allocation` cc1, `class_quota_allocation_details` cc2    
                  WHERE
                      c.classId = cc1.classId AND  
                      cc1.instituteId = $instituteId AND 
                      cc1.sessionId = $sessionId 
                  $conditions ";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");   
    }    
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL Seats intakes OF QUOTAS
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------         
    public function getSeatIntakeList($conditions='') {
        
        global $sessionHandler;
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    
        $query = "SELECT 
                         qt.quotaId, qs.classId, qs.seats,       
                         qt.quotaName, qt.quotaAbbr, 
                         IFNULL((SELECT 
                                       MAX(cc2.classAllocationId) 
                                 FROM 
                                      `class_quota_allocation` cc1, `class_quota_allocation_details` cc2
                                 WHERE 
                                       cc1.instituteId = qs.instituteId AND
                                       cc1.sessionId  = qs.sessionId  AND
                                       cc1.classAllocationId = cc2.classAllocationId AND
                                       cc1.classId=qs.classId AND cc2.quotaId=qs.quotaId  
                                 GROUP BY
                                       cc1.classId, cc2.quotaId),-1) AS classAllocationId,
                        IFNULL((SELECT 
                                       MAX(cc2.seatsAllocated) 
                                 FROM 
                                      `class_quota_allocation` cc1, `class_quota_allocation_details` cc2
                                 WHERE 
                                       cc1.instituteId = qs.instituteId AND
                                       cc1.sessionId  = qs.sessionId  AND
                                       cc1.classAllocationId = cc2.classAllocationId AND
                                       cc1.classId=qs.classId AND cc2.quotaId=qs.quotaId  
                                 GROUP BY
                                       cc1.classId, cc2.quotaId),-1) AS seatsAllocated                     
                  FROM 
                         `quota` qt, class_quota_seats qs  
                         LEFT JOIN class_quota_allocation qa ON qs.classId=qa.classId AND qa.instituteId=qs.instituteId AND qs.sessionId=qa.sessionId 
                         LEFT JOIN class_quota_allocation_details qad ON qad.classAllocationId=qa.classAllocationId AND qs.quotaId=qad.quotaId   
                  WHERE
                        qt.quotaId=qs.quotaId  AND
                        qs.instituteId = $instituteId AND 
                        qs.sessionId = $sessionId       
                  $conditions    
                  GROUP BY
                        qs.classId, qs.quotaId
                  ORDER BY
                        qs.classSeatId   ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
   
 //--------------------------------------------------------------
//  THIS FUNCTION IS Delete Seats Intake quota FORM  ADD/EDIT
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function deleteSeatIntakes($condition='') {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE FROM class_quota_seats WHERE instituteId = $instituteId AND sessionId = $sessionId  $condition ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
   
//--------------------------------------------------------------
//  THIS FUNCTION IS Update Total Seats Intake of class
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function updateClassSeatIntakes($totalSeats,$classId) {
        global $sessionHandler;
        
        $query = "UPDATE `class` SET `totalSeats` = $totalSeats WHERE classId = $classId";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//--------------------------------------------------------------
//  THIS FUNCTION IS Add Total Seats Intake of class
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function addSeatIntakes($fieldValue) {
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "INSERT INTO `class_quota_seats` (`classId`, `quotaId`, `seats`,`instituteId`,`sessionId`) VALUES $fieldValue ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//--------------------------------------------------------------
//  THIS FUNCTION IS Add Copy seat intakes
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------       
    
    public function addCopySeatIntakes($mainClassId='',$classId='',$condition='') {
        global $sessionHandler;
        
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        
        $query = "INSERT INTO `class_quota_seats` 
                        (classId, quotaId, seats, instituteId, sessionId) 
                  SELECT 
                        $classId, qs.quotaId, qs.seats, qs.instituteId, qs.sessionId 
                  FROM 
                        `class_quota_seats` qs       
                  WHERE 
                        qs.classId = $mainClassId AND qs.instituteId=$instituteId AND qs.sessionId=$sessionId   
                  $condition";
                  
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Class wise Round 
//
//$conditions :db clauses
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------         
    public function getClasswiseRoundList($conditions='', $orderBy=' cr.roundId') {
        
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                        DISTINCT ca.classId, ca.classAllocationId, ca.roundId, cr.roundName
                  FROM
                        `class_quota_allocation` ca, `counselling_rounds` cr   
                  WHERE 
                        cr.roundId = ca.roundId AND
                        ca.instituteId=$instituteId AND
                        ca.sessionId=$sessionId 
                  $conditions 
                  ORDER BY  $orderBy";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }     
    
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING Class wise Quota
//
//$conditions :db clauses
// Author :Parveen Sharma
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------         
    public function getClasswiseQuotaList($conditionsDate='',$conditions='', $classId='', $orderBy=' q.quotaId', $limit='') {
        
        global $sessionHandler;
        
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT
                           IFNULL(qa.classAllocationId,'') AS classAllocationId, q.quotaId, qs.seats AS totalSeats, 
                           IF(q.parentQuotaId=0,quotaName,CONCAT((SELECT qq.quotaName FROM `quota` qq  WHERE qq.quotaId = q.parentQuotaId),'-',q.quotaName)) AS quotaName,
                           IFNULL((SELECT 
                                   MAX(cc2.seatsAllocated) 
                             FROM 
                                  `class_quota_allocation` cc1, `class_quota_allocation_details` cc2
                             WHERE 
                                   cc1.instituteId = qs.instituteId AND
                                   cc1.sessionId  = qs.sessionId  AND
                                   cc1.classAllocationId = cc2.classAllocationId AND
                                   cc1.classId=qs.classId AND cc2.quotaId=qs.quotaId  $conditionsDate
                             GROUP BY
                                   cc1.classId, cc2.quotaId),0) AS seatsAllocated,  
                        IF(IFNULL(qad.seatsAllocated,'')='',0,qad.seatsAllocated) AS newSeatsAllocation
                   FROM
                          `quota` q, class_quota_seats qs  
                          LEFT JOIN  `class_quota_allocation` qa ON qs.classId = qa.classId  AND qs.instituteId = qa.instituteId AND qs.sessionId = qa.sessionId  $conditions
                          LEFT JOIN  `class_quota_allocation_details` qad ON qa.classAllocationId = qad.classAllocationId AND qad.quotaId = qs.quotaId     
                   WHERE
                          q.quotaId = qs.quotaId  AND
                          qs.instituteId=$instituteId AND
                          qs.sessionId=$sessionId 
                          $classId
                  ORDER BY  $orderBy $limit ";
                  
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
          
    //--------------------------------------------------------------
    //  THIS FUNCTION IS Add class_quota_allocation
    //
    // Author :Parveen Sharma
    // Created on : (29-May-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------      
    public function addClassQuotaAllocation($classId, $roundId, $allocationDate) {
       
        global $sessionHandler; 
        $userId = $sessionHandler->getSessionVariable('UserId');  
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
       
        $query ="INSERT `class_quota_allocation` SET  
                     classId        = '$classId', 
                     roundId        = '$roundId', 
                     allocationDate = '$allocationDate',  
                     userId         = '$userId', 
                     sessionId      = '$sessionId',
                     instituteId    = '$instituteId'
                 $condition ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }   
    
    //--------------------------------------------------------------
    //  THIS FUNCTION IS Add class_quota_allocation_details
    //
    // Author :Parveen Sharma
    // Created on : (29-May-2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------------      
    public function addClassQuotaAllocationDetails($id, $quotaId, $seatsAllocated ) {
       
       global $sessionHandler; 
        
       $query = "INSERT `class_quota_allocation_details` SET  
                 classAllocationId = '$id' , 
                 quotaId = '$quotaId', 
                 seatsAllocated = '$seatsAllocated'  
                 $condition ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }   
  
//--------------------------------------------------------------
//  THIS FUNCTION IS Delete class_quota_allocation / class_quota_allocation_details 
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function deleteQuotaAllocation($tableName='', $condition='') {
        
        global $sessionHandler; 
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "DELETE FROM $tableName WHERE $condition ";
        
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    
//--------------------------------------------------------------
//  THIS FUNCTION IS Get Class Quota Allocation
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getClassQuotaAllocation($condition='',$condition1='') {
        
        global $sessionHandler; 
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       cc2.quotaId, IFNULL(MAX(cc2.seatsAllocated),0)  AS seatsAllocated
                  FROM 
                      class_quota_seats qs 
                      LEFT JOIN `class_quota_allocation` cc1 ON qs.classId = cc1.classId  $condition
                      LEFT JOIN `class_quota_allocation_details` cc2 ON qs.quotaId = cc2.quotaId AND cc1.classAllocationId = cc2.classAllocationId  
                      $condition1
                  WHERE 
                       cc1.sessionId = $sessionId AND
                       cc1.instituteId = $instituteId
                  GROUP BY
                       qs.classId, qs.quotaId";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }
    
     
//--------------------------------------------------------------
//  THIS FUNCTION IS Get Class Quota Allocation
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------      
    public function getClassQuotaAllocationList($condition='',$conditionDate='',$allocatedDate='',$conditionMain='',$orderBy=' branchName',$limit='') {
       
        global $sessionHandler; 
        $sessionId = $sessionHandler->getSessionVariable('SessionId');
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        $query = "SELECT 
                       DISTINCT c.classId, c.className, 
                       CONCAT(d.degreeName,' (',b.branchName,')-',sp.periodName) AS branchName, q.quotaId,
                       IF(q.parentQuotaId=0,quotaName,CONCAT((SELECT qq.quotaName FROM `quota` qq  WHERE qq.quotaId = q.parentQuotaId),'-',q.quotaName)) AS quotaName,
                        qs.seats AS totalSeats,  IFNULL(MAX(cc2.seatsAllocated),0)  AS allotedSeats,
                       IFNULL((SELECT 
                                     COUNT(s.studentId) 
                               FROM 
                                     student s 
                               WHERE s.classId = qs.classId AND s.quotaId=qs.quotaId AND s.dateOfAdmission <= '$allocatedDate'),0) AS reportedSeats
                  FROM 
                        study_period sp, degree d, branch b, `quota` q,  class c, 
                        class_quota_seats qs  
                        LEFT JOIN `class_quota_allocation` cc1 ON qs.classId = cc1.classId AND qs.sessionId = cc1.sessionId AND qs.instituteId = cc1.instituteId $conditionDate
                        LEFT JOIN `class_quota_allocation_details` cc2 ON cc1.classAllocationId = cc2.classAllocationId AND cc2.quotaId = qs.quotaId $condition
                  WHERE
                       sp.studyPeriodId = c.studyPeriodId AND 
                       d.degreeId = c.degreeId AND
                       b.branchId = c.branchId AND 
                       q.quotaId = qs.quotaId AND
                       c.classId = qs.classId  AND
                       qs.sessionId = $sessionId AND
                       qs.instituteId = $instituteId
                  $conditionMain            
                  GROUP BY
                       qs.classId, qs.quotaId 
                  ORDER BY branchName, quotaName, $orderBy $limit";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }   
    
}
?>
