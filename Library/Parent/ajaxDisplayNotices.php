<?php
//-------------------------------------------------------
// Purpose: To display the records of display Notices in Parents in array from the database, pagination and search  functionality
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

     require_once(BL_PATH . "/HtmlFunctions.inc.php");
    $htmlFunctions = HtmlFunctions::getInstance();
    

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
    
    $totalNoticesArray = $studentInformationManager->getTotalNotices();
    $studentRecordArray = $studentInformationManager->getInstituteNotices($filter,$limit);      
    $cnt = count($studentRecordArray);
	

	function trim_output($str,$maxlength,$mode=1,$rep='...'){
		$ret=($mode==2?chunk_split($str,30):$str);

		if(strlen($ret) > $maxlength){
			$ret=substr($ret,0,$maxlength).$rep;
		}
		return $ret;
	}

   
    for($i=0;$i<$cnt;$i++) {


		$instituteNotices = $htmlFunctions->removePHPJS(html_entity_decode($studentRecordArray[$i]['noticeSubject']));
		$instituteText = $htmlFunctions->removePHPJS(html_entity_decode($studentRecordArray[$i]['noticeText']));

		if(strlen($instituteNotices)> 80) {
		$instituteNotices = substr($instituteNotices,0,80)."...";  
		}

		if(strlen($instituteText)>80) {
		$instituteText = substr($instituteText,0,80)."...";  
		}
		
		$studentRecordArray[$i]['noticeSubject'] = $instituteNotices;
		$studentRecordArray[$i]['noticeText'] = $instituteText;


		$fileName = IMG_PATH."/Notice/".$studentRecordArray[$i]['noticeAttachment'];
			if(file_exists($fileName) && ($studentRecordArray[$i]['noticeAttachment']!="")){
					$fileName1 = IMG_HTTP_PATH."/Notice/".$studentRecordArray[$i]['noticeAttachment'];
					 $fileName1 = '<a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a>';
			}
			else {
				$fileName1=NOT_APPLICABLE_STRING;			
			}

        // add countryId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('instituteNotices'=>$instituteNotices,'instituteText'=>$instituteText,'Attachment'=>$fileName1,'Detail'=>'<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/detail.gif"  border="0" width="15" onClick="editWindow('.$studentRecordArray[$i]['noticeId'].',\'ViewNotices\',600,670); return false;"/></a>','action' => $studentRecordArray[$i]['noticeId'] , 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray); 
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalNoticesArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>
