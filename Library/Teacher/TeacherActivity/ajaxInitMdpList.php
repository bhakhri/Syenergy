<?php
//-------------------------------------------------------
// Purpose: To store the records of training in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/EmployeeManager.inc.php");
     $empManager = EmployeeManager::getInstance();
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
	//search filter 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $condition = ' AND (em.mdpName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.sessionsAttended LIKE  "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.hoursAttended LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.venue LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.description LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"OR em.startDate LIKE  "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR em.endDate LIKE  "'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
       
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'mdpName';
    
    $orderBy = " $sortField $sortOrderBy";         
    $condition .= " AND em.employeeId = '".add_slashes($REQUEST_DATA['employeeId'])."'";
     
    ////////////
    $totalArray = $empManager->getTotalMdp($condition);
    $mdpRecordArray = $empManager->getMdpList($condition,$orderBy,$limit);
    $cnt = count($mdpRecordArray);

	$mdpTypeArray = array(1=>'ICTP', 2=>'EDP', 3=>'FDP', 4=>'Seminar', 5=>'Workshop', 6=>'PDP');
	$mdpSelectArray = array(0=>'Conducted' , 1=>'Attended');
   
    for($i=0;$i<$cnt;$i++) {
	   $mdpType = $mdpRecordArray[$i]['mdpType'];
	   
	   $mdpArray = explode(',', $mdpType);
	   $str = '';
	   foreach ($mdpArray as $rec) {
		   if (!empty($str)) {
			   $str .= ',';
		   }
		   $str .= $mdpTypeArray[$rec];
	   }
	   $mdpRecordArray[$i]['mdpType'] = $str;
	   
	   if($mdpRecordArray[$i]['mdpType'] == '') {
           $mdpRecordArray[$i]['mdpType'] = NOT_APPLICABLE_STRING;
       }
       
	   if($mdpRecordArray[$i]['mdp'] == '') {
           $mdpRecordArray[$i]['mdp'] = NOT_APPLICABLE_STRING;
       }
	   
       $mdpSelect = $mdpRecordArray[$i]['mdp'];
	   $mdpArray2 = explode(',',$mdpSelect);
       $str2 = '';
	   foreach ($mdpArray2 as $rec2) {
		   if (!empty($str2)) {
			   $str2 .= ',';
		   }
		   $str2 .= $mdpSelectArray[$rec2];
	   }
	   $mdpRecordArray[$i]['mdp'] = $str2;
	   
       if($mdpRecordArray[$i]['startDate']=='0000-00-00') {
          $mdpRecordArray[$i]['startDate'] = NOT_APPLICABLE_STRING;
       }
       else {
          $mdpRecordArray[$i]['startDate'] = UtilityManager::formatDate($mdpRecordArray[$i]['startDate']);
       }
        
       if($mdpRecordArray[$i]['endDate']=='0000-00-00') {
          $mdpRecordArray[$i]['endDate'] = NOT_APPLICABLE_STRING;
       }
       else {
          $mdpRecordArray[$i]['endDate'] = UtilityManager::formatDate($mdpRecordArray[$i]['endDate']);
       }
       
       $id = $mdpRecordArray[$i]['mdpId'];
       
       if($mdpRecordArray[$i]['sessionsAttended']==0 || $mdpRecordArray[$i]['sessionsAttended']=="") {
           $mdpRecordArray[$i]['sessionsAttended'] = 0;
       } 
        
       if($mdpRecordArray[$i]['hoursAttended']==0 || $mdpRecordArray[$i]['hoursAttended']=="") {
           $mdpRecordArray[$i]['hoursAttended'] = 0;
       }
	   
       if(strlen($mdpRecordArray[$i]['mdpName'])>=25) {
         $mdpRecordArray[$i]['mdpName'] = substr($mdpRecordArray[$i]['mdpName'],0,25)."....";  
       }
       
       if(strlen($mdpRecordArray[$i]['venue'])>=35) {
         $mdpRecordArray[$i]['venue'] = substr($mdpRecordArray[$i]['venue'],0,35)."....";  
       }
       
       if(strlen($mdpRecordArray[$i]['description'])>=35) {
         $mdpRecordArray[$i]['descripton'] = substr($mdpRecordArray[$i]['description'],0,35)."....";  
       }
       
       //$seminarRecordArray[$i]['organisedBy'] = '<a href="" name="bubble" onclick="showSeminarDetails('.$id.',\'divSeminarInfo\',505,350);return false;" title="Berif Information" >'.stripslashes(strip_tags(substr($seminarRecordArray[$i]['organisedBy'],0,25))).'...</a>';  
       //$seminarRecordArray[$i]['seminarPlace'] = '<a href="" name="bubble" onclick="showSeminarDetails('.$id.',\'divSeminarInfo\',505,350);return false;" title="Berif Information" >'.stripslashes(strip_tags(substr($seminarRecordArray[$i]['seminarPlace'],0,25))).'...</a>';  
       //$seminarRecordArray[$i]['topic'] = '<a href="" name="bubble" onclick="showSeminarDetails('.$id.',\'divSeminarInfo\',505,350);return false;" title="Berif Information" >'.stripslashes(strip_tags(substr($seminarRecordArray[$i]['topic'],0,25))).'...</a>';  
       
       
       $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit " onclick="mdpEditWindow('.$mdpRecordArray[$i]['mdpId'].',\'MdpActionDiv\',700,600);return false;" border="0"></a>&nbsp;
                   <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" alt="Delete" onclick="deleteMdp('.$mdpRecordArray[$i]['mdpId'].');return false;" border="0"></a>&nbsp;
                   <a href="#" title="Brief Description"><img src="'.IMG_HTTP_PATH.'/zoom.gif" alt="Brief Description" onclick="showmdpDetails('.$id.',\'divMdpInfo\',505,350);return false;" border="0"></a>';
        
       
       $valueArray = array_merge(array('action1' => $actionStr , 
                                        'srNo' => ($records+$i+1)),$mdpRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
	}
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
	?>