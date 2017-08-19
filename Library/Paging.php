<?php
//-------------------------------------------------------
// Purpose: This file will be used for pagination.
//
// Author : Pushpender Kumar Chauhan
// Created on : (09.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

/*
// test code 

define('RECORDS_PER_PAGE',20);
define('LINKS_PER_PAGE',5);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        foreach ($_POST as $key => $value) {
                if (!isset($_GET[$key])) {
                        logError("URL parameter '$key=$value' found in POST but not in GET request", WARNING_SEVERITY);
                        $_GET[$key] = $value;
                }
        }
        $REQUEST_DATA = &$_GET;
} else {
        $REQUEST_DATA = &$_POST;
}
*/
class Paging {

      private static $linksPerPage;
      private static $recordsPerPage;
      private static $instance = NULL;
      private static $page;
      private static $queryString=NULL; // it contains all URL variables
      private static $totalRecords;

       private function __construct($records='',$total='',$links=''){
                if(empty($records)){
                        self::$recordsPerPage = RECORDS_PER_PAGE;
                }else{
                        self::$recordsPerPage = $records;
                }
                if(empty($total)){
                        self::$totalRecords = NULL;
                }else{
                        self::$totalRecords = $total;
						
                }
                if(empty($links)){
                        self::$linksPerPage = LINKS_PER_PAGE;
                }else{
                        self::$linksPerPage = $links;
                }
        }
        public static function getInstance($recordsPerPage='',$totalRecords='',$linksPerPage='') {
                if(self::$instance == NULL){
                        $class = __CLASS__;
                        self::$instance = new $class($recordsPerPage,$totalRecords,$linksPerPage);
                }
                return self::$instance;
        }
        // ajax pagination     
		public function ajaxGetTotalRecords($textCSS ='fontText', $linkCSS='pagingLinks'){
			  $totRecords = self::$totalRecords ;
			  return $totRecords;
		}

        public function ajaxPrintLinks($textCSS ='fontText', $linkCSS='pagingLinks') {
               global $REQUEST_DATA;
               // creation of page no
               self::$page  = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
                              
                // creation of query string
                if(is_array($REQUEST_DATA)) {
                   foreach($REQUEST_DATA as $key => $value) {
                          if(strtolower($key)!='page') {
                             self::$queryString .= "&$key=$value";
                           }
                   }
                }
				
    

               $totalPages = ceil(self::$totalRecords/self::$recordsPerPage);
               // in case if someone try to enter page no than total pages
               if(self::$page>$totalPages)
                  self::$page = $totalPages;
		
               //show paging links when total records are more than records per page
               if(self::$totalRecords > self::$recordsPerPage ) {
				
                  $printLink = "<span class=\"$textCSS\"><b>Pages:</b> $totalPages&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                  if(self::$page>1) {
		
                    $printLink .= " <img src=\"".IMG_HTTP_PATH."/first.gif\" border=\"0\" title=\"First\" align=\"absmiddle\"   onClick=\"sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy='+sortOrderBy+'&sortField='+sortField);return false;\" />&nbsp;&nbsp;";

                    $printLink .= " <img src=\"".IMG_HTTP_PATH."/back.gif\" border=\"0\"  title=\"Previous\" align=\"absmiddle\" onClick=\"sendReq(listURL,divResultName,searchFormName,'page=".(self::$page-1)."&sortOrderBy='+sortOrderBy+'&sortField='+sortField);return false;\" />&nbsp;&nbsp;";
                    }

                   $half = floor(self::$linksPerPage/2);
                   if(self::$page>$half) {
                     $start = self::$page - $half;
                     $limit = self::$page + $half;
                     if($limit > $totalPages)
                         $limit = $totalPages;
                   }
                   else {
                     $start = 1;
                     $limit = self::$linksPerPage;
                     if($limit>$totalPages)
                         $limit = $totalPages;
					
                   }

                    for($link=$start;$link<=$limit;$link++) {
                         if($link==self::$page) {
                             $printLink .= " <b>".$link."</b>";
                         }
                         else {
                             $printLink .= " <a onClick=\"sendReq(listURL,divResultName,searchFormName,'page=".$link."&sortOrderBy='+sortOrderBy+'&sortField='+sortField);return false;\" href=\"#\" class=\"$linkCSS\"><u>$link</u></a>";
                       
						 }

                    }


                   if(self::$totalRecords>((self::$page)*self::$recordsPerPage)) {
              	$printlink.= $tot;
					  $printLink .= "&nbsp;&nbsp;<img src=\"".IMG_HTTP_PATH."/next.gif\" border=\"0\"  title=\"Next\" align=\"absmiddle\" onClick=\"sendReq(listURL,divResultName,searchFormName,'page=".(self::$page+1)."&sortOrderBy='+sortOrderBy+'&sortField='+sortField);return false;\" />&nbsp;";
                      $printLink .=  "<img src=\"".IMG_HTTP_PATH."/last.gif\" border=\"0\"  title=\"Last\" align=\"absmiddle\" onClick=\"sendReq(listURL,divResultName,searchFormName,'page=".$totalPages."&sortOrderBy='+sortOrderBy+'&sortField='+sortField);return false;\" /></span>";
               
					}
                }
               return $printLink;

        }
        // for server side paging
      public function printLinks($textCSS ='fontText', $linkCSS='pagingLinks') {
               global $REQUEST_DATA;
               // creation of page no
               self::$page  = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
                              
                // creation of query string
                if(is_array($REQUEST_DATA)) {
                   foreach($REQUEST_DATA as $key => $value) {
                          if(strtolower($key)!='page') {
                             self::$queryString .= "&$key=$value";
                           }
                   }
                }
    //  echo '=='.self::$totalRecords.'='.self::$recordsPerPage ;
               $totalPages = ceil(self::$totalRecords/self::$recordsPerPage);
               // in case if someone try to enter page no than total pages
               if(self::$page>$totalPages)
                  self::$page = $totalPages;
               //show paging links when total records are more than records per page
               if(self::$totalRecords > self::$recordsPerPage ) {

                 $printLink = "<span class=\"$textCSS\"><b>Pages:</b> $totalPages&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";

                  if(self::$page>1) {
                    $printLink .= " <a href=\"".$_SERVER['PHP_SELF']."?page=1".self::$queryString."\" class=\"$linkCSS\"><img src=\"".IMG_HTTP_PATH."/first.gif\" border=\"0\" title=\"First\" align=\"absmiddle\" /></a>&nbsp;&nbsp;";

                    $printLink .= " <a href=\"".$_SERVER['PHP_SELF']."?page=".(self::$page-1).self::$queryString."\" class=\"$linkCSS\"><img src=\"".IMG_HTTP_PATH."/back.gif\" border=\"0\"  title=\"Previous\" align=\"absmiddle\" /></a>&nbsp;&nbsp;";
                    }


                   $half = floor(self::$linksPerPage/2);
                   if(self::$page>$half) {
                     $start = self::$page - $half;
                     $limit = self::$page + $half;
                     if($limit > $totalPages)
                         $limit = $totalPages;
                   }
                   else {
                     $start = 1;
                     $limit = self::$linksPerPage;
                     if($limit>$totalPages)
                         $limit = $totalPages;
                   }

                    for($link=$start;$link<=$limit;$link++) {
                         if($link==self::$page) {
                   
							$printLink .= " <b>$link</b>";
                         }
                         else {
                             $printLink .= " <a href=\"".$_SERVER['PHP_SELF']."?page=".($link).self::$queryString."\" class=\"$linkCSS\"><u>$link</u></a>";
                         }

                    }


                   if(self::$totalRecords>((self::$page)*self::$recordsPerPage)) {
                      $printLink .= "&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?page=".(self::$page+1).self::$queryString."\" class=\"$linkCSS\"><img src=\"".IMG_HTTP_PATH."/next.gif\" border=\"0\"  title=\"Next\" align=\"absmiddle\" /></a>&nbsp;";
                      $printLink .=  "<a href=\"".$_SERVER['PHP_SELF']."?page=$totalPages".self::$queryString."\" class=\"$linkCSS\"><img src=\"".IMG_HTTP_PATH."/last.gif\" border=\"0\"  title=\"Last\" align=\"absmiddle\" /></a></span>";
                    }
                }
               return $printLink;

        } 

}
/* test code
$recordsPerPage = 15;
$totalRecords = 200;

Paging::getInstance($recordsPerPage,$totalRecords);
$page = (!is_numeric($REQUEST_DATA['page']) || empty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
$records = ($page-1)* $recordsPerPage;
$queryStringArray = array('dat' => '2003-02-23');
echo Paging::printLinks($page,$queryStringArray);

 */
// $History: Paging.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 6/30/08    Time: 1:16p
//Updated in $/Leap/Source/Library
//Removed Anchor link from first, next, previous, last links, just placed
//OnClick action from Anchor tag to Img tag
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 6/27/08    Time: 3:52p
//Updated in $/Leap/Source/Library
//added ajaxPrintLinks function
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 6/27/08    Time: 10:49a
//Updated in $/Leap/Source/Library
//made a new function ajaxPrintLinks for ajax pagination and uncommented
//the printLinks
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 6/26/08    Time: 8:04p
//Updated in $/Leap/Source/Library
//added ajax pagination 
//
//*****************  Version 4  *****************
//User: Administrator Date: 6/23/08    Time: 3:19p
//Updated in $/Leap/Source/Library
//Refined pagination links, placed First page link under condition(if
//(self::$page>1) as per QA defects list - Pushpender
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/14/08    Time: 1:25p
//Updated in $/Leap/Source/Library
//Added comments header and footer

 
?>