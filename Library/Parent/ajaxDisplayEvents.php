<?php
//-------------------------------------------------------
// Purpose: To display the records of display Events of Institute
//
// Author : Jaineesh
// Created on : 10-09-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifParentNotLoggedIn(); 
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
	
    //////
    /// Search filter /////  
 //   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
   //    $filter = ' AND (n.noticeSubject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'")';         
    //}
//    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
  //  $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'noticeSubject';
    
   //  $orderBy = " n.$sortField $sortOrderBy";         

    ////////////
    
    $totalEventsArray = $studentInformationManager->getTotalEvents();
	$instituteRecordArray = $studentInformationManager->getEventList('','e.endDate desc',$limit);         
    $cnt = count($instituteRecordArray);
	
	

	function trim_output($str,$maxlength,$mode=1,$rep='...'){
		$ret=($mode==2?chunk_split($str,30):$str);

		if(strlen($ret) > $maxlength){
			$ret=substr($ret,0,$maxlength).$rep;
		}
		return $ret;
	}

   
    for($i=0;$i<$cnt;$i++) {
		$instituteEvent = trim_output(strip_slashes($instituteRecordArray[$i]['eventTitle']),35);
		$shortDescriptions = trim_output(strip_slashes($instituteRecordArray[$i]['shortDescription']),35);

        // add countryId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('instituteEvents'=>$instituteEvent,'sd'=>$shortDescriptions,'Action'=>'<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/detail.gif"  border="0" width="15" onClick="editWindow('.$instituteRecordArray[$i]['eventId'].',\'ViewEvents\',600,600); return false;"/></a>','action' => $instituteRecordArray[$i]['eventId'], 'srNo' => ($records+$i+1) ),$instituteRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
			
       }
       else {
            $json_val .= ','.json_encode($valueArray); 
			
		
			
       }
    }
	
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalEventsArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>