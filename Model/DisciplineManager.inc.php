<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class DisciplineManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "DisciplineManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DisciplineManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addDiscipline() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('student_discipline', 
        array('studentId','classId','offenseId','offenseDate','remarks','reportedBy'), 
        array($REQUEST_DATA['studentId'],$REQUEST_DATA['classId'],$REQUEST_DATA['offenseId'],$REQUEST_DATA['offenseDate'],htmlentities($REQUEST_DATA['remarks']),
			htmlentities($REQUEST_DATA['reportedBy']))
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editDiscipline($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('student_discipline', 
        array('studentId','classId','offenseId','offenseDate','remarks','reportedBy'), 
        array($REQUEST_DATA['studentId'],$REQUEST_DATA['classId'],$REQUEST_DATA['offenseId'],$REQUEST_DATA['offenseDate'],htmlentities($REQUEST_DATA['remarks']),
		  htmlentities($REQUEST_DATA['reportedBy']))
        , "disciplineId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING discipline LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getDiscipline($conditions='') {
     
        $query = "SELECT 
                    disciplineId,sc.studentId,sc.classId,offenseId,offenseDate,remarks, 
                    CONCAT(sc.firstName,' ',sc.lastName) AS studentName,reportedBy,
                    sc.rollNo,
                    SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) AS className
        FROM 
            student_discipline,student sc,class cl
        WHERE
            student_discipline.studentId = sc.studentId
            AND cl.classId=sc.classId
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteDiscipline($disciplineId) {
     
        $query = "DELETE 
        FROM student_discipline 
        WHERE disciplineId=$disciplineId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING CITY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getDisciplineList($conditions='', $limit = '', $orderBy=' studentName') {
     
        $query = "SELECT 
                    std.disciplineId,
                    std.offenseDate, 
                    std.remarks,
                    CONCAT(st.firstName,' ',st.lastName) AS studentName, 
                    st.rollNo,
                    IF(st.universityRollNo IS NULL OR st.universityRollNo='','".NOT_APPLICABLE_STRING."',st.universityRollNo) AS universityRollNo,
                    off.offenseAbbr,std.reportedBy,
                    SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) AS className
                 FROM 
                  student_discipline std,student st,class cl,offense off
                WHERE 
                std.studentId=st.studentId
                AND std.classId=cl.classId
                AND cl.classId=st.classId
                AND std.offenseId=off.offenseId
                $conditions
                ORDER BY $orderBy 
                $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF CITIES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalDiscipline($conditions='') {
     
        $query = "SELECT 
                    COUNT(*) AS totalRecords
                 FROM 
                  student_discipline std,student st,class cl,offense off
                WHERE 
                std.studentId=st.studentId
                AND std.classId=cl.classId
                AND cl.classId=st.classId
                AND std.offenseId=off.offenseId
                $conditions
               ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING student detail
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------          
    public function getStudentDetail($conditions='') {
      global $REQUEST_DATA;
        $query = "SELECT 
                    st.studentId,
                    CONCAT(st.firstName,' ',st.lastName) AS studentName,
                    st.rollNo,
                    st.classId,
                    SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) AS className
                 FROM 
                  student st,class cl
                 WHERE 
                   st.classId=cl.classId
                $conditions
               ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }       
   
  
}
// $History: DisciplineManager.inc.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/25/10    Time: 3:46p
//Updated in $/LeapCC/Model
//added university roll no.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 25/06/09   Time: 14:24
//Updated in $/LeapCC/Model
//Corrected query
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Model
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/05/09    Time: 11:34a
//Updated in $/LeapCC/Model
//added reported by in student discipline
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:22
//Updated in $/LeapCC/Model
//Corrected spelling mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:06
//Created in $/LeapCC/Model
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/08   Time: 18:25
//Updated in $/Leap/Source/Model
//Corrected Speling Mistake
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Model
//Created module 'Discipline'
?>
