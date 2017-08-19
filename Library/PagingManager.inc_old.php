<?php
/*
class for paging starting index and ending index


*/
define("RECORDS_PER_PAGE", 10);
class PagingManager extends paging {
	private static $instance = NULL;
	private static $pageM;
	private static $recordsPerPage;
	private static $startIndex;
	private static $endIndex;
	
	public function __construct($records=''){
		if($records == ""){
			self::$recordsPerPage = RECORDS_PER_PAGE;
		}else{
			self::$recordsPerPage = $records;
		}
	}
	/*
	function getInstance
	@param NULL
	returns instance of the class and creates any if not already created
	*/
	public static function getInstance($recordsPerPage){
		if(self::$instance == NULL){
			self::$instance = new PagingManager($recordsPerPage);
		}
		return self::$instance;
	}
	
	/*
	function getInstance
	@param $page='' current page number
	
	returns starting index of the page number data
	*/
	public static function  getStartIndex($page=''){
		if(($page) == ""){
			//currently on first page
			$page = 1;
		}
		$records = self::$recordsPerPage;
//		print $records;
		$page = $page - 1;
		$total = ($records * $page);
		if($total == 0){
			self::$startIndex = 0;
		}else{
			self::$startIndex = $total;
		}
		self::$pageM = $page;
		self::$endIndex = $records;
		return self::$startIndex;
		
	}
	
	/*
	function getInstance
	@param NULL
	
	returns ending index of the page number data
	*/
	
	public static function  getEndIndex(){
		self::$endIndex = self::$recordsPerPage;

		return self::$endIndex;
	}
}	
?>