<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class BookClassMappingManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "BookClassMappingManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "BookClassMappingManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
    
         
    public function checkBookIssuedOrNot($classBookIds,$conditions='') {
     
        $query = "SELECT 
                        DISTINCT bookId 
                  FROM 
                        books_issue
                  WHERE
                       CONCAT(classId,'~',bookId) IN ($classBookIds)
                       $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    public function getBooksList($bookIds,$conditions='') {
     
        $query = "SELECT 
                        bookId,bookNo 
                  FROM 
                        books_master
                  WHERE
                       bookId IN ($bookIds)
                       $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function deleteBookClassMapping($classId) {
     
        $query = "DELETE 
                  FROM 
                        book_class_mapping
                  WHERE 
                   classId=$classId";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }
    
    public function insertMappingData($insertString) {
     
        $query = "
                  INSERT INTO 
                        book_class_mapping(classId,bookId,mappedByUserId)
                  VALUES $insertString
                  ";
        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

    public function getBookMappingList($conditions='', $limit = '', $orderBy=' b.bookNo') {
     
        $query = "SELECT 
                        b.*,
                        IF(m.bookId IS NULL,0,1) AS isMapped 
                  FROM 
                        books_master b 
                        LEFT JOIN book_class_mapping m ON ( m.bookId=b.bookId $conditions)
                  ORDER BY $orderBy 
                  $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    

}
?>