<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TyreRetreadingReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TyreRetreadingManager.inc.php");
    $tyreRetreadingManager = TyreRetreadingManager::getInstance();

    /////////////////////////
    function trim_output($str,$maxlength,$mode=1,$rep='...'){
		$ret=($mode==2?chunk_split($str,30):$str);

		if(strlen($ret) > $maxlength){
			$ret=substr($ret,0,$maxlength).$rep;
		}
		return $ret;
	}
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    if(isset($REQUEST_DATA['tyreNo']) and $REQUEST_DATA['tyreNo']!=''){
     $filter = ' AND tm.tyreNumber = "'.$REQUEST_DATA['tyreNo'].'"';
    }
    /*else{
      $filter = ' WHERE ( insuranceDueDate BETWEEN "'.$REQUEST_DATA['fromDate'].'" AND "'.$REQUEST_DATA['toDate'].'" )';
    }*/
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busNo';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
   
    //$totalArray = $busManager->getTotalBus($filter);
    $tyreRecordArray = $tyreRetreadingManager->getTyreRetreadingReport($filter,$limit,$orderBy);
	//print_r($tyreRecordArray);
	//die;
    $cnt = count($tyreRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        
        if($tyreRecordArray[$i]['retreadingDate']!='0000-00-00'){
         $tyreRecordArray[$i]['retreadingDate']=UtilityManager::formatDate($tyreRecordArray[$i]['retreadingDate']);
        }
        else{
            $tyreRecordArray[$i]['insuranceDueDate']=NOT_APPLICABLE_STRING;
        }

		$tyreRecordArray[$i]['reason'] = trim_output(strip_slashes($tyreRecordArray[$i]['reason']),35);

        $valueArray = array_merge(array('detail'=>'<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif"  border="0" width="15" onClick="editWindow('.$tyreRecordArray[$i]['retreadingId'].',\'ViewReason\',600,600); return false;"/></a>', 'srNo' => ($records+$i+1) ),$tyreRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxGetVehicleTyreRetreadingList.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/02/10    Time: 5:17p
//Created in $/Leap/Source/Library/TyreRetreading
//new ajax files for tyre retreading
//
?>