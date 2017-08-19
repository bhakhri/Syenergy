<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "degree" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class DegreeManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "DegreeManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DegreeManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
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
// THIS FUNCTION IS USED FOR ADDING A DEGREE
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addDegree() {
        global $REQUEST_DATA;

        return SystemDatabaseManager::getInstance()->runAutoInsert('degree', array('degreeCode','degreeName','degreeAbbr'), array(strtoupper($REQUEST_DATA['degreeCode']),$REQUEST_DATA['degreeName'],$REQUEST_DATA['degreeAbbr']) );
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A DEGREE
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editDegree($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('degree', array('degreeCode','degreeName','degreeAbbr'), array(strtoupper($REQUEST_DATA['degreeCode']),$REQUEST_DATA['degreeName'],$REQUEST_DATA['degreeAbbr']), "degreeId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DEGREE LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
   
 public function getDegree($conditions='') {
     
        $query = "SELECT degreeId,degreeCode,degreeName,degreeAbbr
        FROM degree
        $conditions";
        return
		SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

   }
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS DEGREEID EXISTS IN TESTTYPE TABLE OR NOT(DELETE CHECK)
//
//$cityId :degreeid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInTestType($degreeId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $query = "SELECT COUNT(*) AS found 
        FROM test_type
        WHERE degreeId=$degreeId and instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS DEGREEID EXISTS IN TESTTYPE TABLE OR NOT(DELETE CHECK)
//
//$cityId :degreeid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInClass($degreeId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM class
        WHERE degreeId=$degreeId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A DEGREE
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteDegree($degreeId) {
     
        $query = "DELETE 
        FROM degree 
        WHERE degreeId=$degreeId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING DEGREE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getDegreeList($conditions='', $limit = '', $orderBy=' dg.degreeName') {
     
      /* $query = "SELECT dg.degreeId, dg.degreeCode, dg.degreeName, dg.degreeAbbr
        FROM degree dg
        $conditions 
        ORDER BY $orderBy $limit";*/
		$query =  "SELECT dg.degreeId,dg.degreeCode,dg.degreeName,dg.degreeAbbr,(select count(s.studentId) FROM student s, class c where s.classId  = c.classId and c.degreeId = dg.degreeId) AS studentId
	    FROM degree dg
	    having 1=1 $conditions                   
        ORDER BY $orderBy $limit ";



     // $conditions
	 // GROUP BY deg.degreeId
	 // ORDER BY $orderBy $limit";
								
	   
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
        }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF DEGREES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalDegree($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM degree dg
        $conditions ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
   
  
}
?>
<?php
// $History: DegreeManager.inc.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 16/07/09   Time: 11:16
//Updated in $/LeapCC/Model
//Added the check : degree cannot be deleted if it used in class table
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:25p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/09/08    Time: 6:00p
//Updated in $/Leap/Source/Model
//Rename testtype table to test_type table
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/25/08    Time: 2:23p
//Updated in $/Leap/Source/Model
//Adding AjaxEnabled Delete functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 7:24p
//Updated in $/Leap/Source/Model
//Removing degreeDuratioin Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:34a
//Updated in $/Leap/Source/Model
//Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/13/08    Time: 10:06a
//Created in $/Leap/Source/Model
//Initial Checkin
?>
