<?php
//---------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR fees report module
//
//
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId 

class FeeReportManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "CityManager" CLASS
//
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "CityManager" CLASS
//
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
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

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee cycle wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeCycleTotal($conditions='', $orderBy=' fr.feeCycleId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 fr.feeCycleId,fc.cycleName,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,fee_cycle fc,student sct,class cls 
			 
			 WHERE 
			 fr.feeCycleId = fc.feeCycleId AND 
			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId
			 $conditions 

			 GROUP BY fr.feeCycleId 
			 ORDER BY $orderBy" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee class wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeClassTotal($conditions='', $orderBy=' cls.classId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 cls.classId,cls.className,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,student sct,class cls 
			 
			 WHERE 
			 
			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId
			 $conditions 

			 GROUP BY cls.classId 
			 ORDER BY $orderBy" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee class wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeBatchTotal($conditions='', $orderBy=' ba.batchId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 ba.batchId,ba.batchName,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,batch ba,student sct,class cls 
			 
			 WHERE 

			 ba.batchId = cls.batchId AND 
			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId
			 $conditions 

			 GROUP BY ba.batchId 
			 ORDER BY $orderBy" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee study period wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeStudyPeriodTotal($conditions='', $orderBy=' sp.studyPeriodId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 sp.studyPeriodId,sp.periodName,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,study_period sp,student sct,class cls 
			 
			 WHERE 

			 sp.studyPeriodId = cls.studyPeriodId AND 
			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId
			 $conditions 

			 GROUP BY sp.studyPeriodId 
			 ORDER BY $orderBy" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee hostel wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeHostelTotal($conditions='', $orderBy=' sct.hostelId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 sct.hostelId,hs.hostelName,hr.hostelRoomId,hr.roomName,SUM(hr.roomRent) as totalAmount
			 
			 FROM 
			 fee_receipt fr,hostel_room hr,student sct,class cls,hostel hs 
			 
			 WHERE 
			 sct.hostelId = hs.hostelId AND
			 sct.hostelRoomId = hr.hostelRoomId AND 
			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId
			 $conditions 

			 GROUP BY sct.hostelId 
			 ORDER BY $orderBy" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee transport wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeTransportTotal($conditions='', $orderBy=' sct.hostelId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 sct.busRouteId,br.routeCode,bs.busStopId,SUM(bs.transportCharges) as totalAmount
			 
			 FROM 
			 fee_receipt fr,bus_stop bs,student sct,class cls,bus_route br
			 
			 WHERE 
			 sct.busStopId = bs.busStopId AND
			 sct.busRouteId = br.busRouteId AND 

			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId
			 $conditions 

			 GROUP BY sct.busRouteId 
			 ORDER BY $orderBy" ;   
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee Gender wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeGenderTotal($conditions='', $orderBy=' sct.studentGender'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 IF(sct.studentGender='F','Female','Male') as studentGender,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,student sct,class cls 
			 
			 WHERE 

			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId
			 $conditions

			 GROUP BY sct.studentGender
			 ORDER BY $orderBy" ;  
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee instrument wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeInstrumentTotal($conditions='', $orderBy=' fr.paymentInstrument'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 fr.paymentInstrument,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,student sct,class cls 
			 
			 WHERE 

			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId
			 $conditions

			 GROUP BY fr.paymentInstrument 
			 ORDER BY $orderBy" ;  
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee quota wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeCategoryTotal($conditions='', $orderBy=' sct.quotaId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 sct.quotaId,qt.quotaAbbr,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,student sct,class cls, quota qt 
			 
			 WHERE 
			 sct.quotaId = qt.quotaId AND
			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId

			 GROUP BY sct.quotaId 
			 ORDER BY $orderBy" ;  
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee city wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeCityTotal($conditions='', $orderBy=' sct.corrCityId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 sct.corrCityId,cty.cityCode,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,student sct,class cls, city cty 
			 
			 WHERE 
			 sct.corrCityId = cty.cityId AND
			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId

			 GROUP BY sct.corrCityId 
			 ORDER BY $orderBy" ;  
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

//---------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting list of fee state wise fees
//
// $conditions :db clauses
// Author :Rajeev Aggarwal 
// Created on : (20.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------  
public function getFeeStateTotal($conditions='', $orderBy=' sct.corrStateId'){

	global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId   = $sessionHandler->getSessionVariable('SessionId');
    
	$query	="SELECT 
			 sct.corrStateId,sta.stateCode,SUM(fr.totalAmountPaid) as totalAmount
			 
			 FROM 
			 fee_receipt fr,student sct,class cls, states sta 
			 
			 WHERE 
			 sct.corrStateId = sta.stateId AND
			 sct.studentId = fr.studentId AND 
			 sct.classId = cls.classId AND
			 cls.sessionId = $sessionId AND
			 cls.instituteId = $instituteId

			 GROUP BY sct.corrStateId 
			 ORDER BY $orderBy" ;  
		 
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");    
	}

}
// $History: FeesReportManager.inc.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Model/Management
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 11/20/08   Time: 3:26p
//Created in $/Leap/Source/Model/Management
//intial checkin
?>