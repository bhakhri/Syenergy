<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "Document" table
// Author :Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class MdpManager {
    private static $instance = null;

//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TestTypeManager" CLASS
//
// Author :Jaineesh
// Created on : (28.02.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DocumentManager" CLASS
//
// Author :Jaineesh
// Created on : (28.02.2008)
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
// THIS FUNCTION IS USED FOR ADDING A DOCUMENT
//
// Author :Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
     public function addMdp($employeeId)  {
	     global $REQUEST_DATA;
         $mdpType    = $REQUEST_DATA['mdpType'];
	     $newmdpType = implode(",", $mdpType);
         $query      ="INSERT INTO employee_mdp (employeeId,mdpName,startDate,endDate,mdp,sessionsAttended,hoursAttended,venue,mdpType,description)

		 VALUES($employeeId,

		'".addslashes($REQUEST_DATA['mdpName'])."',
		'".$REQUEST_DATA['mdpstartDate']."',
		'".$REQUEST_DATA['mdpendDate']."',
		'".$REQUEST_DATA['mdpSelectId']."',
		'".$REQUEST_DATA['mdpSessionAttended']."',
		'".$REQUEST_DATA['mdpHours']."',
		'".addslashes($REQUEST_DATA['mdpVenue'])."',
		'".$newmdpType."',
		'".addslashes($REQUEST_DATA['mdpDescription'])."')";
      return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A MDP
//
// Author :Parveen Sharma
// Created on : (28.02.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   public function editMdp($id) {
        global $REQUEST_DATA;

        $query = "UPDATE employee_mdp SET  mdpName    = '".addslashes($REQUEST_DATA['mdpName'])."',
                                    startDate         = '".$REQUEST_DATA['mdpstartDate']."',
                                    endDate           = '".$REQUEST_DATA['mdpendDate']."',
                                    mdp               = '".$REQUEST_DATA['mdpSelectId']."',
                                    sessionsAttended  = '".addslashes($REQUEST_DATA['mdpSessionAttended'])."',
                                    hoursAttended     = '".$REQUEST_DATA['mdpHours']."',
                                    employeeId        = '".$REQUEST_DATA['employeeId']."',
                                    venue             = '".addslashes($REQUEST_DATA['mdpVenue'])."',
									mdpType           = '".addslashes($REQUEST_DATA['mdpType'])."',
                                    description       = '".addslashes($REQUEST_DATA['mdpDescription'])."'
                WHERE mdpId=".$id;
       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING MDP
//
// Author :Gagan Gill
// Created on : (05.3.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteMdp($Id) {

        $query = "DELETE FROM employee_mdp
                  WHERE mdpId=$Id ";

        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF Mdp
//
// Author :Gagan Gill
// Created on : (28.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
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
// THIS FUNCTION IS USED FOR GETTING Mdp LIST
//
// Author :Parveen Sharma
// Created on : (19.02.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

       public function getMdpList($filter='', $orderBy='',$limit = '') {

        $query = "SELECT
                        em.mdpId, em.mdpName, em.startDate, em.endDate, em.mdp, em.sessionsAttended, em.hoursAttended, em.venue, em.mdpType, em.description,

                        em.employeeId, e.employeeName , e.employeeCode
                  FROM
                       employee_mdp em , employee e
                  WHERE
                        em.employeeId = e.employeeId
                  $filter
                  ORDER BY $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }




//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR EXISTANCE OF DOCUMENT IN ANOTHER MODULE
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getEmployee($code='') {

        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

      $query = "SELECT	employeeId,
						employeeCode,
						employeeName
				FROM	employee
				WHERE	employee.employeeCode = '$code'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING EMPLOYEE DETAIL
//
// $conditions :db clauses
// Author :Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getEmployeeDetail($conditions='') {

        global $sessionHandler;

        $query = " SELECT	emp.employeeId,
							emp.employeeCode,
							emp.employeeName,
							desg.designationName
					FROM	employee emp LEFT JOIN designation desg ON emp.designationId = desg.designationId
					$conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
 }
?>