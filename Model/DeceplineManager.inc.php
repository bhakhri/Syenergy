<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class DeceplineManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "DeceplineManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "DeceplineManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
// THIS FUNCTION IS USED FOR ADDING A CITY
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
	public function addDecepline() {
		global $REQUEST_DATA;

		return SystemDatabaseManager::getInstance()->runAutoInsert('student_decepline', 
        array('studentId','classId','offenseId','offenseDate','remarks'), 
        array($REQUEST_DATA['studentId'],$REQUEST_DATA['classId'],$REQUEST_DATA['offenseId'],$REQUEST_DATA['offenseDate'],add_slashes($REQUEST_DATA['remarks']))
        );
	}


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A CITY
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editDecepline($id) {
        global $REQUEST_DATA;
     
        return SystemDatabaseManager::getInstance()->runAutoUpdate('student_decepline', 
        array('studentId','classId','offenseId','offenseDate','remarks'), 
        array($REQUEST_DATA['studentId'],$REQUEST_DATA['classId'],$REQUEST_DATA['offenseId'],$REQUEST_DATA['offenseDate'],add_slashes($REQUEST_DATA['remarks']))
        , "deceplineId=$id" );
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING decepline LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getDecepline($conditions='') {
     
        $query = "SELECT 
                    deceplineId,sc.studentId,sc.classId,offenseId,offenseDate,remarks, 
                    CONCAT(sc.firstName,' ',sc.lastName) AS studentName,
                    sc.rollNo
        FROM 
            student_decepline,student sc
        WHERE
            student_decepline.studentId = sc.studentId
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A CITY
//
//$cityId :cityid of the City
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteDecepline($deceplineId) {
     
        $query = "DELETE 
        FROM student_decepline 
        WHERE deceplineId=$deceplineId";
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getDeceplineList($conditions='', $limit = '', $orderBy=' studentName') {
     
        $query = "SELECT 
                    std.deceplineId,
                    DATE_FORMAT(std.offenseDate,'%d-%b-%Y') AS offenseDate, 
                    std.remarks,
                    CONCAT(st.firstName,' ',st.lastName) AS studentName, 
                    st.rollNo,
                    off.offenseAbbr,
                    SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-3) AS className
                 FROM 
                  student_decepline std,student st,class cl,offense off
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalDecepline($conditions='') {
     
        $query = "SELECT 
                    COUNT(*) AS totalRecords
                 FROM 
                  student_decepline std,student st,class cl,offense off
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------          
    public function getStudentDetail($conditions='') {
      global $REQUEST_DATA;
        $query = "SELECT 
                    st.studentId,
                    CONCAT(st.firstName,' ',st.lastName) AS studentName,
                    st.rollNo,
                    st.classId
                 FROM 
                  student st
                $conditions
               ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }       
   
  
}
// $History: DeceplineManager.inc.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/04/09   Time: 13:16
//Created in $/LeapCC/Model
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/12/08   Time: 12:06
//Created in $/LeapCC/Model
//Created module 'Decepline'
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/12/08   Time: 18:28
//Created in $/Leap/Source/Model
//Created module 'Decepline'
?>
