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
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class FineReportManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "RoomAllocationManager" CLASS
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "RoomAllocationManager" CLASS
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
    
 	public function getTotalFineStudent($condition='',$having='') { 
       
  		global $REQUEST_DATA;
		global $sessionHandler;   
		
		$query = "SELECT
			            COUNT(*) AS totalRecords
 			      FROM
			         (SELECT
				            fs.studentId, SUM(fs.amount) AS totalAmount,
                            IFNULL((SELECT 
                                        SUM(frd.amount) 
                                    FROM 
                                        fine_receipt_detail frd 
                                    WHERE 
                                        frd.studentId = fs.studentId AND frd.isDelete = 0
                                    GROUP BY 
                                        frd.studentId),0) AS paidAmount,
                            CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                            s.rollNo, c.className
			           FROM
				            student s, class c,
				            fine_student fs 
			           WHERE
                            s.classId = c.classId AND
				            fs.studentId = s.studentId AND
				            fs.status = 1 
				            $condition
			           GROUP BY
				            fs.studentId
                       $having ) AS tt ";
	  
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
	}    

    public function getTotalFineStudentNew($condition='',$having='',$payCondition='') { 
       
  		global $REQUEST_DATA;
		global $sessionHandler;   
		
		$query = "SELECT 
				     COUNT(*) AS totalRecords 
			      FROM 
			         (SELECT
				           fs.studentId, 
                           CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, s.rollNo, c.className,
                           CONCAT(c.className,' (',i.instituteCode,')') AS instituteClassName,
                           IFNULL((SELECT SUM(ff.amount) FROM fine_student ff 
                             WHERE ff.studentId = s.studentId AND ff.status = 1 GROUP BY ff.studentId),0) AS totalAmount, 
                           IFNULL((SELECT SUM(frd.amount) FROM fine_receipt_detail frd 
                            WHERE frd.studentId = s.studentId AND frd.isDelete = 0 $payCondition GROUP BY frd.studentId),0) AS paidAmount
				       FROM
                           student s, class c, institute i, fine_student fs  
                       WHERE
                           i.instituteId = c.instituteId AND            
                           s.classId = c.classId AND fs.studentId = s.studentId AND fs.status = 1  
                       $condition 
                       GROUP BY 
                           fs.studentId 
				       $having) AS tt ";
	  
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
	}
      
    public function getFineStudentNew($condition='',$orderBy='studentName',$limit='',$having='',$payCondition='') { 
       
  		global $REQUEST_DATA;
		global $sessionHandler;   
		
			$query = "SELECT
                         tt.studentId, tt.studentName, tt.rollNo, tt.className, tt.instituteClassName,
                         IFNULL(tt.totalAmount,0) AS totalAmount, IFNULL(tt.paidAmount,0) AS paidAmount, 
                         IFNULL(tt.totalAmount,0) - IFNULL(tt.paidAmount,0) AS balanceAmount,tt.fineCategoryName
                      FROM
                         (SELECT
                               fs.studentId, 
                               CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName, s.rollNo, c.className,
                               CONCAT(c.className,' (',i.instituteCode,')') AS instituteClassName,
                               IFNULL((SELECT DISTINCT fineCategoryName FROM fine_category ffc 
                               WHERE ffc.fineCategoryId = fs.fineCategoryId ),'') AS fineCategoryName,
                               IFNULL((SELECT SUM(ff.amount) FROM fine_student ff 
                                 WHERE ff.studentId = s.studentId AND ff.status = 1 GROUP BY ff.studentId),0) AS totalAmount, 
                               IFNULL((SELECT SUM(frd.amount) FROM fine_receipt_detail frd 
                                WHERE frd.studentId = s.studentId AND frd.isDelete = 0 $payCondition GROUP BY frd.studentId),0) AS paidAmount
                          FROM
                               student s, class c, institute i, fine_student fs  
                          WHERE
                               i.instituteId = c.instituteId AND            
                               s.classId = c.classId AND fs.studentId = s.studentId AND fs.status = 1  
                          $condition     
                          GROUP BY 
                               fs.studentId 
		                  $having) AS tt
			          ORDER BY 
                          $orderBy $limit"; 
	
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
	}

	public function getStudentFineCategory($ttcondition){
		$query = "SELECT
					ffc.fineCategoryName
				  FROM
				  	`fine_student` fs, `student` s, `fine_category` ffc
				  WHERE
				  	fs.fineCategoryId = ffc.fineCategoryId
				  	AND s.studentId=fs.studentId
                    AND fs.status = 1
                    $ttcondition
				  	ORDER BY  fineDate ASC
				  	"; 
	
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  	
		
	}
    public function getFineStudent($condition='',$orderBy='studentName',$limit='',$having='') { 
       
  		global $REQUEST_DATA;
		global $sessionHandler;   
		
		$query = "SELECT
			         tt.studentId, tt.studentName, tt.totalAmount, 
			         tt.paidAmount, (IFNULL(tt.totalAmount,0)-IFNULL(tt.paidAmount,0)) AS balanceAmount,
				     tt.rollNo, tt.className, tt.instituteClassName 	
			      FROM
			        (SELECT
				        fs.studentId, SUM(fs.amount) AS totalAmount,
				        IFNULL((SELECT 
						            SUM(frd.amount) 
					            FROM 
						            fine_receipt_detail frd 
					            WHERE 
						            frd.studentId = fs.studentId AND frd.isDelete = 0
					            GROUP BY 
						            frd.studentId),0) AS paidAmount,
				        CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
                        s.rollNo, c.className,
                        CONCAT(c.className,' (',i.instituteCode,')') AS instituteClassName      
			         FROM
				        student s, class c, institute i,
				        fine_student fs 
			         WHERE
                        c.instituteId = i.instituteId AND
                        s.classId = c.classId AND
				        fs.studentId = s.studentId AND
				        fs.status = 1 
				        $condition
			         GROUP BY
				        fs.studentId
                     $having) AS tt 
			      ORDER BY 
                        $orderBy $limit"; 
	  
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
	}    
   
    public function getStudentFineClass($condition='') { 
       
        global $REQUEST_DATA;
        global $sessionHandler;   
	 

        $query = "SELECT 
                       DISTINCT c.classId
                  FROM
                       student s, class c 
                  WHERE                       
			c.isActive IN (1,2,3)
                  $condition"; 
 
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");  
    }    
      
}
?>
