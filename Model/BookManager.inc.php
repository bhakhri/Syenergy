<?php 

//-------------------------------------------------------
//  This File contains Business Logic of the Book Module
//
// Author :Nancy Puri
// Created on : 04-Oct-2010
//
//--------------------------------------------------------

require_once(DA_PATH . '/SystemDatabaseManager.inc.php');



class BookManager {
	private static $instance = null;
	
	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}
	public function addBook() {
		global $REQUEST_DATA;
		return SystemDatabaseManager::getInstance()->runAutoInsert('books_master', array('bookName','bookAuthor','uniqueCode',          
        'instituteBookCode','isbnCode','noOfBooks'), array($REQUEST_DATA['bookName'],$REQUEST_DATA['bookAuthor'],$REQUEST_DATA['uniqueCode'], $REQUEST_DATA['instituteBookCode'],$REQUEST_DATA['isbnCode'],$REQUEST_DATA['noOfBooks']));

	}
    //Gets generated bookNo   
  public function updBookNo($bookNo,$id) {
       global $REQUEST_DATA;
       return SystemDatabaseManager::getInstance()->runAutoUpdate('books_master', array('bookNo'), array($bookNo), "bookId=$id" );
    }       
      
    public function editBook($id) {
        global $REQUEST_DATA;
        
        return SystemDatabaseManager::getInstance()->runAutoUpdate('books_master', array('bookName','bookAuthor','uniqueCode',
        'instituteBookCode','isbnCode','noOfBooks'), array($REQUEST_DATA['bookName'],$REQUEST_DATA['bookAuthor'],$REQUEST_DATA['uniqueCode'],                 $REQUEST_DATA['instituteBookCode'],$REQUEST_DATA['isbnCode'],$REQUEST_DATA['noOfBooks']), "bookId=$id" );     
    }    
    public function getBook($conditions='') {
       
        $query = "SELECT bookId,bookNo,bookName,bookAuthor,uniqueCode,instituteBookCode,isbnCode,noOfBooks
        FROM books_master
        $conditions";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }  
    //Gets the books_master table fields
      
    public function getBookList($conditions='', $limit = '', $orderBy=' bookName') {
     
        $query = "SELECT bookId,bookNo,bookName,bookAuthor,uniqueCode,instituteBookCode,isbnCode, noOfBooks
        FROM books_master      
        $conditions                  
        ORDER BY $orderBy $limit";
             
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    // checks dependency constraint
     public function checkInMapping($bookId) {
     
        $query = "SELECT COUNT(*) AS found 
        FROM book_class_mapping
        WHERE bookId=$bookId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }   
       
    public function getTotalBook($conditions='') {
    
        $query = "SELECT COUNT(*) AS totalRecords 
        FROM books_master  
        $conditions ";
               
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 
    // deletes the book
     public function deleteBook($bookId) {
     
        $query = "DELETE 
        FROM books_master 
        WHERE bookId=$bookId";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }   
}
?>
