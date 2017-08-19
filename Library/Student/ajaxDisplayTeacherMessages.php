<?php
//-------------------------------------------------------
// Purpose: To display the records of display Teacher Messages
//
// Author : Jaineesh
// Created on : 10-09-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH.'/HtmlFunctions.inc.php');
    define('MODULE','StudentTeacherComments');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn(true);
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
    
    $totalCommentArray = $studentInformationManager->getTotalComments();
	$studentRecordArray = $studentInformationManager->getCommentsListing($limit);         
	$cnt = count($studentRecordArray);
    function trim_output($str,$maxlength,$mode=1,$rep='...'){
		$ret=($mode==2?chunk_split($str,30):$str);

		if(strlen($ret) > $maxlength){
			$ret=substr($ret,0,$maxlength).$rep;
		}
		return $ret;
	}
    for($i=0;$i<$cnt;$i++) {
		$c = trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($studentRecordArray[$i]['comments'])),35);
		/*$fileMessage = IMG_PATH."/Teacher/".$studentRecordArray[$i]['commentAttachment'];
			if(file_exists($fileMessage) && ($studentRecordArray[$i]['commentAttachment']!="")){
					$fileMessage1 = IMG_HTTP_PATH."/Teacher/".$studentRecordArray[$i]['commentAttachment'];
					$fileMessage1= '<a href="'.$fileMessage1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a>';
				}
			else {
				$fileMessage1=NOT_APPLICABLE_STRING;
			}*/

        // add countryId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('teacherComment'=>$c,'Attachment'=>$fileMessage1,'Detail'=>'<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif"  border="0" width="15" onClick="editWindow('.$studentRecordArray[$i]['commentId'].',\'ViewTeacher\',400,400); return false;"/></a>','action' => $studentRecordArray[$i]['commentId'] , 'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
		}
       else {
		    $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalCommentArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>