<?php
//-------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "OFFENSE" TABLE
// Author :Jaineesh 
// Created on : (22.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); 

class OffenseManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "OffenseManager" CLASS
//
// Author : Jaineesh 
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "OffenseManager" CLASS
//
// Author :Jaineesh 
// Created on : (22.12.2008)
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
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A  OFFENSE DETAIL
//
// Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addOffense() {
		global $REQUEST_DATA;
        global $sessionHandler;  

		return SystemDatabaseManager::getInstance()->runAutoInsert('offense', 
        array('offenseName','offenseAbbr','offenseDesc'), 
        array($REQUEST_DATA['offenseName'],strtoupper($REQUEST_DATA['offenseAbbr']),$REQUEST_DATA['offenseDesc']));	
	}

//--------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A OFFENSE DETAIL
//
//$id:offenseId
// Author : Jaineesh 
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------        
    public function editOffense($id) {
        global $REQUEST_DATA;
        global $sessionHandler;  
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('offense', 
        array('offenseName','offenseAbbr','offenseDesc'), 
        array($REQUEST_DATA['offenseName'],strtoupper($REQUEST_DATA['offenseAbbr']),$REQUEST_DATA['offenseDesc']),"offenseId=$id");
    }   
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING OFFENSE LIST
//
//$conditions :db clauses
// Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getOffense($conditions='') {
     
  $query = "	SELECT		o.offenseId,
							o.offenseName,
							o.offenseAbbr,
							o.offenseDesc
				FROM		offense o

        $conditions";
        
        //echo  $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//-------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK EXISTING OFFENSEID
//
//$conditions :db clauses
// Author : Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getOffenseExisting($conditions='') {
     
$query = "	SELECT	distinct(o.offenseId),
							o.offenseName,
							o.offenseAbbr,
							o.offenseDesc,
							sd.offenseId
				FROM		offense o, 
							student_discipline sd
				WHERE		o.offenseId = sd.offenseId

        $conditions";
        
        //echo  $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A PERIOD SLOT
//
//$periodSlotId :periodSlotId of the PEROD SLOT
// Author :Jaineesh
// Created on : (15.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteOffense($offenseId) {
     
        $query = "DELETE 
        FROM offense 
        WHERE offenseId=$offenseId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING OFFENSE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh 
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    

// by SaTInder
 public function getOffenseName($offenseId) {
	
     
  $query = "	SELECT		offenseId,
							offenseName
							
				FROM		offense 
		
				WHERE  offenseId = $offenseId";
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

///////////////



    public function getOffenseDetail($conditions='', $limit = '', $orderBy=' offenseName') {
        global $sessionHandler;  
        
  /*  $query = "	SELECT 
							o.offenseId,
							o.offenseName,
							o.offenseAbbr,
							o.offenseDesc,
							COUNT(sd.offenseId) as StudentCount
				FROM		offense o LEFT JOIN 
							student_discipline sd
				ON			o.offenseId = sd.offenseId
				
							$conditions
				GROUP BY	o.offenseName


							 ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

		
    }    */
$query = "	SELECT 
							o.offenseId,
							o.offenseName,
							o.offenseAbbr,
							o.offenseDesc,(
							
							SELECT COUNT(sd.offenseId)  
							FROM		student_discipline sd
							WHERE       o.offenseId = sd.offenseId
							)as studentCount
							
			FROM
				
					offense o 					
				
							$conditions
							 ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

		
    }
	

	public function getStudentOffenseDetail($offenceId) {
		$query = "SELECT sd.studentId, sd.classId, sd.offenseDate, stu.rollNo, cls.className, stu.studentMobileNo, stu.studentEmail from student_discipline sd, student stu, class cls where sd.offenseId = $offenceId and sd.studentId = stu.studentId and sd.classId = cls.classId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF OFFENSE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalOffenseDetail($conditions='') {
        global $sessionHandler;
        
        $query = "	SELECT	COUNT(*) AS totalRecords 
					FROM	offense $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF OFFENSE DETAIL
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalOffenseReportDetail($noOfOffenseValue,$instances,$condition='',$filter,$orderBy) {
        global $sessionHandler;
        if ($noOfOffenseValue == 1) {
		$query = "	SELECT	scs.rollNo, 
							CONCAT( scs.firstName, ' ', scs.lastName ) AS studentName, 
							cl.className, 
							scs.studentMobileNo, 
							scs.studentEmail, 
							sd.studentId, 
							sd.classId, 
							sd.offenseId,
							COUNT(sd.offenseId) AS totalOffenses
					FROM	student scs, 
							class cl, 
							student_discipline sd,
							offense o
					WHERE	sd.studentId = scs.studentId
					AND		sd.classId = cl.classId
					AND		sd.offenseId = o.offenseId
							$condition
							GROUP BY sd.studentId
							HAVING totalOffenses <= $instances
							ORDER BY $orderBy
							$conditions ";
		}
		else if ($noOfOffenseValue == 2) {
	 $query = "	SELECT	scs.rollNo,
							CONCAT(scs.firstName,' ',scs.lastName) AS studentName,
							cl.className,
							scs.studentMobileNo,
							scs.studentEmail,
							sd.studentId,
							sd.classId,
							sd.offenseId,
							count(sd.offenseId) AS totalOffenses
					FROM	student scs, 
							class cl, 
							student_discipline sd,
							offense o
					WHERE	sd.studentId = scs.studentId
					AND		sd.classId = cl.classId
					AND		sd.offenseId = o.offenseId
							$condition
							GROUP BY sd.studentId
							HAVING totalOffenses >= $instances
							ORDER BY $orderBy
							$conditions ";
		}
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF OFFENSE DETAIL
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getOffenseReportDetail($noOfOffenseValue,$instances,$condition='',$filter,$limit,$orderBy) {
        global $sessionHandler;
        if ($noOfOffenseValue == 1) {
		$query = "	SELECT	scs.rollNo, 
							CONCAT( scs.firstName, ' ', scs.lastName ) AS studentName, 
							cl.className, 
							scs.studentMobileNo, 
							scs.studentEmail, 
							sd.studentId, 
							sd.classId, 
							sd.offenseId,
							COUNT(sd.offenseId) AS totalOffenses
					FROM	student scs, 
							class cl, 
							student_discipline sd,
							offense o
					WHERE	sd.studentId = scs.studentId
					AND		sd.classId = cl.classId
					AND		sd.offenseId = o.offenseId
							$condition
							GROUP BY sd.studentId
							HAVING totalOffenses <= $instances
							ORDER BY $orderBy
							$limit
							$conditions ";
		}
		else if ($noOfOffenseValue == 2) {
	$query = "	SELECT	scs.rollNo,
							CONCAT(scs.firstName,' ',scs.lastName) AS studentName,
							cl.className,
							scs.studentMobileNo,
							scs.studentEmail,
							sd.studentId,
							sd.classId,
							sd.offenseId,
							count(sd.offenseId) AS totalOffenses
					FROM	student scs,
							class cl,
							student_discipline sd,
							offense o
					WHERE	sd.studentId = scs.studentId
					AND		sd.classId = cl.classId
					AND		sd.offenseId = o.offenseId
							$condition
							GROUP BY sd.studentId
							HAVING totalOffenses >= $instances
							ORDER BY $orderBy
							$limit
							$conditions ";
		}
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

		//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF OFFENSE DETAIL
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getOffenseListPrint($conditions,$orderBy) {
        global $sessionHandler;

		$query = "	SELECT	scs.rollNo, 
							CONCAT(scs.firstName,' ',scs.lastName) AS studentName,
							cl.className,
							sd.studentId,
							sd.classId,
							sd.offenseId,
							sd.offenseDate,
							sd.reportedBy,
							sd.remarks,
							o.offenseName
					FROM	student scs,
							class cl,
							student_discipline sd,
							offense o
					WHERE	sd.studentId = scs.studentId
					AND		sd.classId = cl.classId
					AND		sd.offenseId = o.offenseId
							$conditions
							ORDER BY $orderBy
							 ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

}

// $History: OffenseManager.inc.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/02/09    Time: 6:07p
//Updated in $/LeapCC/Model
//put new functions for offense report
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/26/08   Time: 12:55p
//Updated in $/LeapCC/Model
//modified in query of getTotalOffenseDetail() 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/25/08   Time: 12:37p
//Updated in $/LeapCC/Model
//modified for data constraint
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:42p
//Created in $/LeapCC/Model
//get all the queries for add, edit or delete
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/22/08   Time: 5:14p
//Created in $/Leap/Source/Model
//to get queries of offense add, edit, delete
//


?>
