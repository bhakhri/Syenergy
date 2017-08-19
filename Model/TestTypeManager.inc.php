<?php
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "testtype" table
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class TestTypeManager {
    private static $instance = null;
    
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "TestTypeManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
    private function __construct() {
    }

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "TestTypeManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
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
// THIS FUNCTION IS USED FOR ADDING A TESTTYPE
//
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addTestType() {
        global $REQUEST_DATA;
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        
        /*
        return SystemDatabaseManager::getInstance()->runAutoInsert('test_type', 
        array('testTypeCode','testTypeName','testTypeAbbr','universityId','degreeId','branchId','weightageAmount',
        'weightagePercentage','subjectId','studyPeriodId','evaluationCriteriaId','cnt','sortOrder','subjectTypeId','conductingAuthority'), 
        array(strtoupper($REQUEST_DATA['testtypeCode']),$REQUEST_DATA['testtypeName'],$REQUEST_DATA['testtypeAbbr'],
        $REQUEST_DATA['universityId'],$REQUEST_DATA['degreeId'],$REQUEST_DATA['branchId'],$REQUEST_DATA['weightageAmount'],
        $REQUEST_DATA['weightagePercentage'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['studyPeriodId'],$REQUEST_DATA['evaluationCriteriaId'],
        $REQUEST_DATA['cnt'],$REQUEST_DATA['sortOrder'],$REQUEST_DATA['subjectTypeId'],$REQUEST_DATA['conductingAuthority'])
       );
       */
      $query="INSERT INTO test_type 
               (
                 testTypeName,testTypeCode,testTypeAbbr,universityId,degreeId,branchId,weightageAmount,
                 weightagePercentage,subjectId,studyPeriodId,evaluationCriteriaId,cnt,sortOrder,subjectTypeId,
                 conductingAuthority,testTypeCategoryId,timeTableLabelId, instituteId
               ) 
              VALUES
                   ('".add_slashes(trim($REQUEST_DATA['testtypeName']))."','".strtoupper(add_slashes(trim($REQUEST_DATA['testtypeCode'])))."','"
                      .add_slashes(trim($REQUEST_DATA['testtypeAbbr']))."',"
                      .$REQUEST_DATA['universityId'].",".$REQUEST_DATA['degreeId'].","
                      .$REQUEST_DATA['branchId'].",'".add_slashes(trim($REQUEST_DATA['weightageAmount']))."','"
                      .add_slashes(trim($REQUEST_DATA['weightagePercentage']))."',".$REQUEST_DATA['subjectId'].","
                      .$REQUEST_DATA['studyPeriodId'].",".$REQUEST_DATA['evaluationCriteriaId'].","
                      .add_slashes(trim($REQUEST_DATA['cnt'])).",".add_slashes(trim($REQUEST_DATA['sortOrder'])).",".$REQUEST_DATA['subjectTypeId'].","
                      .$REQUEST_DATA['conductingAuthority'].",".$REQUEST_DATA['testType'].","
                      .$REQUEST_DATA['labelId'].", $instituteId
                   )"; 
      

      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A TESTTYPE
//
//$id:cityId
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTestType($id) {
        global $REQUEST_DATA;
        
        /*
        return SystemDatabaseManager::getInstance()->runAutoUpdate('test_type', 
        array('testTypeCode','testTypeName','testTypeAbbr','universityId','degreeId','branchId','weightageAmount',
        'weightagePercentage','subjectId','studyPeriodId','evaluationCriteriaId','cnt','sortOrder','subjectTypeId','conductingAuthority'), 
        array(strtoupper($REQUEST_DATA['testtypeCode']),$REQUEST_DATA['testtypeName'],$REQUEST_DATA['testtypeAbbr'],
        $REQUEST_DATA['universityId'],$REQUEST_DATA['degreeId'],$REQUEST_DATA['branchId'],$REQUEST_DATA['weightageAmount'],
        $REQUEST_DATA['weightagePercentage'],$REQUEST_DATA['subjectId'],$REQUEST_DATA['studyPeriodId'],$REQUEST_DATA['evaluationCriteriaId'],
        $REQUEST_DATA['cnt'],$REQUEST_DATA['sortOrder'],$REQUEST_DATA['subjectTypeId'],$REQUEST_DATA['conductingAuthority']), "testTypeId=$id" );
       */ 
       $query="UPDATE test_type 
               SET 
                 testTypeName ='".add_slashes(trim($REQUEST_DATA['testtypeName']))."',
                 testTypeCode='".strtoupper(add_slashes(trim($REQUEST_DATA['testtypeCode'])))."',
                 testTypeAbbr='".add_slashes(trim($REQUEST_DATA['testtypeAbbr']))."',
                 universityId=".$REQUEST_DATA['universityId']
                 .",degreeId=".$REQUEST_DATA['degreeId']
                 .",branchId=".$REQUEST_DATA['branchId'].",
                  weightageAmount='".add_slashes(trim($REQUEST_DATA['weightageAmount']))
                 ."',weightagePercentage='".add_slashes(trim($REQUEST_DATA['weightagePercentage']))
                 ."',subjectId=".$REQUEST_DATA['subjectId']
                 .",studyPeriodId=".$REQUEST_DATA['studyPeriodId']
                 .",evaluationCriteriaId=".$REQUEST_DATA['evaluationCriteriaId']
                 .",cnt=".add_slashes(trim($REQUEST_DATA['cnt']))
                 .",sortOrder=".add_slashes(trim($REQUEST_DATA['sortOrder']))
                 .",subjectTypeId=".$REQUEST_DATA['subjectTypeId']
                 .",conductingAuthority=".add_slashes(trim($REQUEST_DATA['conductingAuthority']))
                 .",testTypeCategoryId=".$REQUEST_DATA['testType']
                 .",timeTableLabelId=".$REQUEST_DATA['labelId']
                 ." WHERE   testTypeId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    }   
    
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TESTTYPE LIST
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTestType($conditions='') {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
        $query = "	SELECT 
							testTypeId,
							testTypeCode,
							testTypeName,
							testTypeAbbr,
							universityId,
							degreeId,
							branchId,
							weightageAmount,
							weightagePercentage,
							subjectId,
							studyPeriodId,
							evaluationCriteriaId,
							cnt,
							sortOrder,
							subjectTypeId,
							conductingAuthority,
							testTypeCategoryId,
							timeTableLabelId
					FROM	test_type
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DETERMING WHETHE THIS TESTTYPEID EXISTS IN TEST_TRANSFERRED_MARKS TABLE OR NOT(DELETE CHECK)
//
//$testtypeId :testtypeId   of testtype
// Author :Dipanjan Bhattacharjee 
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------             
    public function checkInTest($testTypeId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM ".TEST_TRANSFERRED_MARKS_TABLE."
        WHERE testTypeId=$testTypeId";  //echo $query;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A TESTTYPE
//
//$testtypeId :testtypeId   of testtype
// Author :Dipanjan Bhattacharjee 
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteTestType($testTypeid) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query = "DELETE 
        FROM test_type
        WHERE testTypeid=$testTypeid and instituteId = $instituteId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TESTTYPE LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
    public function getTestTypeList($conditions='', $limit = '', $orderBy=' tt.testtypeName') {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and tt.instituteId = $instituteId";
		}
		else {
			$conditions .= " where tt.instituteId = $instituteId";
		}
        
        $query = "SELECT tt.testTypeId, tt.testTypeCode, tt.testTypeName, tt.testTypeAbbr, 
        if(un.universityName<>'',un.universityName,'---') as universityName, 
        if(deg.degreeName<>'',deg.degreeName,'---') as degreeName, 
        if(deg.degreeCode<>'',deg.degreeCode,'---') as degreeCode, 
        if(br.branchName<>'',br.branchName,'---') as branchName,
        tt.weightageAmount, tt.weightagePercentage, 
        if(s.subjectName<>'',s.subjectName,'---') as subjectName, 
        if(eve.evaluationCriteriaName<>'',eve.evaluationCriteriaName,'---') as evaluationCriteriaName, 
        tt.cnt, tt.sortOrder, sub.subjectTypeName, tt.conductingAuthority
        FROM test_type tt
        LEFT JOIN university un ON ( tt.universityId = un.universityId )
        LEFT JOIN degree deg ON ( tt.degreeId = deg.degreeId )
        LEFT JOIN evaluation_criteria eve ON ( tt.evaluationCriteriaId = eve.evaluationCriteriaId )
        LEFT JOIN subject_type sub ON ( tt.subjectTypeId = sub.subjectTypeId )
        LEFT JOIN subject s ON ( tt.subjectId = s.subjectId )
        LEFT JOIN branch br ON ( tt.branchId = br.branchId ) 
        $conditions 
        ORDER BY $orderBy $limit";
        //echo $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TESTTYPES
//
//$conditions :db clauses
// Author :Dipanjan Bhattacharjee 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalTestType($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and tt.instituteId = $instituteId";
		}
		else {
			$conditions .= " where tt.instituteId = $instituteId";
		}
                  
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM test_type tt
        LEFT JOIN university un ON ( tt.universityId = un.universityId )
        LEFT JOIN degree deg ON ( tt.degreeId = deg.degreeId )
        LEFT JOIN evaluation_criteria eve ON ( tt.evaluationCriteriaId = eve.evaluationCriteriaId )
        LEFT JOIN subject_type sub ON ( tt.subjectTypeId = sub.subjectTypeId )
        LEFT JOIN subject s ON ( tt.subjectId = s.subjectId )
        LEFT JOIN branch br ON ( tt.branchId = br.branchId ) 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A TESTTYPECATEGORY
//
// Author :Jaineesh
// Created on : (19.2.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------    
    public function addTestTypeCategory() {
        global $REQUEST_DATA;
        
     $query="INSERT INTO test_type_category (testTypeName,testTypeAbbr,examType,subjectTypeId,showCategory,isAttendanceCategory, colorCode) 
      VALUES('".$REQUEST_DATA['testTypeCategoryName']."','".$REQUEST_DATA['testTypeCategoryAbbr']."','".$REQUEST_DATA['examType']."','".$REQUEST_DATA['subjectType']."',".$REQUEST_DATA['showName'].",".$REQUEST_DATA['attendanceCategory'].",'".$REQUEST_DATA['colorCode']."')"; 
      
      return SystemDatabaseManager::getInstance()->executeUpdate($query);     
        
    }

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A TEST TYPE CATEGORY
//
//$id:cityId
// Author :Jaineesh 
// Created on : (19.2.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------        
    public function editTestTypeCategory($id) {
        global $REQUEST_DATA;
        
    $query="UPDATE test_type_category SET testTypeName ='".$REQUEST_DATA['testTypeCategoryName']."', testTypeAbbr='".$REQUEST_DATA['testTypeCategoryAbbr']."',  examType = '".$REQUEST_DATA['examType']."', subjectTypeId = '".$REQUEST_DATA['subjectType']."', isAttendanceCategory = ".$REQUEST_DATA['attendanceCategory'].", showCategory=".$REQUEST_DATA['showName'].", colorCode='".$REQUEST_DATA['colorCode']."'
        WHERE   testTypeCategoryId=".$id;
       
       return SystemDatabaseManager::getInstance()->executeUpdate($query); 
    } 


	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TESTTYPE LIST
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------         
    public function getTestTypeCategory($conditions='') {
        $query = "	SELECT	ttc.*, 
							st.subjectTypeName 
					FROM	test_type_category ttc,
							subject_type st
					WHERE	ttc.subjectTypeId = st.subjectTypeId
        $conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TEST TYPES CATEGORY
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.2.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTotalTestTypeCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
     $query = "	SELECT	COUNT(*) AS totalRecords
					FROM	test_type_category ttc,
							subject_type st, university u
					WHERE	ttc.subjectTypeId = st.subjectTypeId
					AND	st.universityId = u.universityId
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
	public function getTestTypeCategoryName($testTypeCategoryId) {
		  $query = "	SELECT *
					FROM	test_type_category
					WHERE	testTypeCategoryId = $testTypeCategoryId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
					
//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TESTTYPE CATEGORY LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (19.02.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------       
    
       public function getTestTypeCategoryList($conditions='', $limit = '', $orderBy=' testTypeName') {
     
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
        
     $query = "	SELECT 
							ttc.testTypeCategoryId,
							ttc.testTypeName,
							ttc.testTypeAbbr,
							ttc.examType,
							concat(u.universityCode,'-',st.subjectTypeName) as subjectTypeName,
							if(ttc.showCategory = 1, 'Yes', 'No') as showName,
							if(ttc.isAttendanceCategory=1,'Yes','No') as isAttendanceCategory,
							ttc.colorCode
					FROM	test_type_category ttc,
							subject_type st, university u
					WHERE	ttc.subjectTypeId = st.subjectTypeId
					AND	st.universityId = u.universityId
        $conditions  
							ORDER BY $orderBy $limit";
        //echo $query;
        
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  

	

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TEST TYPES CATEGORY
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.2.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function checkTestTypeCategory($conditions='') {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
                  
      $query = "SELECT count(testTypeCategoryId) AS foundRecord 
        FROM  test_type 
        $conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	
  public function checkTestCategory($conditions='') {
	
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');

		if ($conditions != '') {
			$conditions .= " and instituteId = $instituteId";
		}
		else {
			$conditions .= " where instituteId = $instituteId";
		}
    
       		 $query = " SELECT COUNT(testTypeCategoryId) AS foundRecord  
					FROM ".TEST_TABLE." $conditions ";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A TESTTYPECATEGORY
//
//$testtypeId :testTypeCategoryId  of testtypecategory
// Author :Dipanjan Bhattacharjee 
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------      
    public function deleteTestTypeCategory($id) {
     
        $query = "DELETE 
        FROM test_type_category
        WHERE testTypeCategoryid=$id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }
	
	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TEST TYPES CATEGORY
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.2.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getTimeTableSubjectSelect($id) {
         
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
      $query = "	SELECT	distinct(s.subjectCode), 
							s.subjectId
					FROM	subject s, 
							time_table_classes ttc,
							subject_to_class stc,
							time_table_labels ttl
					WHERE	ttc.timeTableLabelId = ttl.timeTableLabelId
					AND		stc.subjectId = s.subjectId
					AND		ttc.classId = stc.classId
					AND		ttl.timeTableLabelId = $id
							ORDER BY s.subjectCode" ;
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
   
   //---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK EXAM TYPE
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.2.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getCheckExam($conditions='') {
			
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
       $query = "	SELECT	count(examType) as examCount
					FROM	test_type_category 
							$conditions	";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK ATTENDANCE CATEGORY
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.2.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getCheckAttendance($conditions='') {
			
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
     $query = "	SELECT	count(isAttendanceCategory) as attendanceCount
					FROM	test_type_category 
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK ATTENDANCE CATEGORY
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.2.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getCheckEditAttendance($conditions='') {
			
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
    $query = "	SELECT	isAttendanceCategory
					FROM	test_type_category 
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO CHECK ATTENDANCE CATEGORY
//
//$conditions :db clauses
// Author :Jaineesh 
// Created on : (19.2.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------      
    public function getCheckEditExam($conditions='') {
			
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing
                  
    $query = "	SELECT	examType
					FROM	test_type_category 
							$conditions";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
  
}
// $History: TestTypeManager.inc.php $
//
//*****************  Version 16  *****************
//User: Ajinder      Date: 3/31/10    Time: 4:45p
//Updated in $/LeapCC/Model
//added university wise subject types. FCNS No.1506
//
//*****************  Version 15  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/Model
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:34a
//Updated in $/LeapCC/Model
//add new field degree in lecture percent and fixed bugs
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Model
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:35p
//Updated in $/LeapCC/Model
//added multiple table defines.
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 8/13/09    Time: 3:00p
//Updated in $/LeapCC/Model
//changed queries to add instituteId
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Model
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 6/12/09    Time: 2:54p
//Updated in $/LeapCC/Model
//fixed bug nos.0000040,0000051,0000052,0000053
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/03/09    Time: 6:03p
//Updated in $/LeapCC/Model
//add new filed test type category abbr.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 7/05/09    Time: 16:28
//Updated in $/LeapCC/Model
//Corrected checkInTest() function's internal query
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:30p
//Updated in $/LeapCC/Model
//modified for test type category
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/26/09    Time: 4:39p
//Updated in $/LeapCC/Model
//add new fields in test type category
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Model
//modified for test type & put test type category
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/01/09    Time: 15:21
//Updated in $/LeapCC/Model
//Corrected deletion criteria
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:01
//Updated in $/LeapCC/Model
//Showing "weightage amount,weightage percentage and evaluation criteria"
//in list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:01p
//Created in $/LeapCC/Model
//
//*****************  Version 13  *****************
//User: Arvind       Date: 10/07/08   Time: 2:46p
//Updated in $/Leap/Source/Model
//corrected the name of table in query of checkInTest()
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/12/08    Time: 1:20p
//Updated in $/Leap/Source/Model
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 7/24/08    Time: 4:59p
//Updated in $/Leap/Source/Model
//Modified so that
//university,degree,branch,subject,study period and evaluation criteria
//becomes optional
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 7/14/08    Time: 4:46p
//Updated in $/Leap/Source/Model
//changed db function 'executeUpdate' to 'executeDelete' in delete
//function
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 7/09/08    Time: 7:18p
//Updated in $/Leap/Source/Model
//Add `Select` as default selected value in dropdowns of University,
//Degree, Branch, Study Period, Evaluation Criteria, subject and subject
//type.
//and made modifications so that data is  being populated in study period
//dropdown
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 7/03/08    Time: 8:13p
//Updated in $/Leap/Source/Model
//Modify table name to have underscore
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/01/08    Time: 1:04p
//Updated in $/Leap/Source/Model
//Modified DataBase Column names
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/25/08    Time: 7:08p
//Updated in $/Leap/Source/Model
//Added AjaxEnabled Delete functionality
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/19/08    Time: 3:01p
//Updated in $/Leap/Source/Model
//Adding extra fields done
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:29a
//Updated in $/Leap/Source/Model
//Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:41p
//Created in $/Leap/Source/Model
//Initial checkin
?>
