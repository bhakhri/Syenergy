<?php
//-------------------------------------------------------
// Purpose: To store the records of salary head in array from the database, pagination and search, delete
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (25.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentAssignment');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Student/StudentAssignmentManager.inc.php");
    $studentTeacherManager = StudentAssignmentManager::getInstance();

    /////////////////////////

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND ( message LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR message LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'assignedOn';

     $orderBy = " $sortField $sortOrderBy";

    ////////////

    $totalArray           = $studentTeacherManager->getTotalStudentTeacherAssignment($filter);
    $salaryHeadRecordArray = $studentTeacherManager->getStudentTeacherAssignmentList($filter,$limit,$orderBy);
	//print_r($salaryHeadRecordArray); die;
    $cnt = count($salaryHeadRecordArray);

    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface

		if($salaryHeadRecordArray[$i]['replyAttachmentFile']){

			$salaryHeadRecordArray[$i]['replyAttachmentFile'] ='<img src="'.IMG_HTTP_PATH.'/download.gif" title="Download File" onclick="download(\''.$salaryHeadRecordArray[$i]['replyAttachmentFile'].'\')" />';
		}
		else{

			$salaryHeadRecordArray[$i]['replyAttachmentFile'] = '--';

		}

		$readMore1 = '';
		if(strlen($salaryHeadRecordArray[$i]['topicTitle'])>60){

			$readMore1 = " ...";
		}
		$salaryHeadRecordArray[$i]['topicTitle'] = substr($salaryHeadRecordArray[$i]['topicTitle'],0,60).$readMore1;

		$readMore = '';
		if(strlen($salaryHeadRecordArray[$i]['topicDescription'])>125){

			$readMore = " ...";
		}
		$salaryHeadRecordArray[$i]['topicDescription'] = substr($salaryHeadRecordArray[$i]['topicDescription'],0,125).$readMore;

		//$salaryHeadRecordArray[$i]['tobeSubmittedOn'] = "<span class='redColor'>".$salaryHeadRecordArray[$i]['tobeSubmittedOn']."</span>";
		if($salaryHeadRecordArray[$i]['submitDate']=='-1'){

			$salaryHeadRecordArray[$i]['assignedOn'] = "<strong>".UtilityManager::formatDate($salaryHeadRecordArray[$i]['assignedOn'])."</strong>";

			if($salaryHeadRecordArray[$i]['tobeSubmittedOn']==date('Y-m-d'))
				$salaryHeadRecordArray[$i]['tobeSubmittedOn'] = "<strong class='redColor'>".UtilityManager::formatDate($salaryHeadRecordArray[$i]['tobeSubmittedOn'])."</strong>";
			else
				$salaryHeadRecordArray[$i]['tobeSubmittedOn'] = "<strong>".UtilityManager::formatDate($salaryHeadRecordArray[$i]['tobeSubmittedOn'])."</strong>";
			$salaryHeadRecordArray[$i]['messageSubject'] = "<strong>".$salaryHeadRecordArray[$i]['messageSubject']."</strong>";

			$salaryHeadRecordArray[$i]['topicTitle'] = "<strong>".$salaryHeadRecordArray[$i]['topicTitle']."</strong>";

			$salaryHeadRecordArray[$i]['topicTitle'] = substr($salaryHeadRecordArray[$i]['topicTitle'],0,60).$readMore1;

			$salaryHeadRecordArray[$i]['topicDescription'] = "<strong>". substr($salaryHeadRecordArray[$i]['topicDescription'],0,125).$readMore."</strong>";

			$actionStr='<a href="#" title="Details/Submission" style="font-size:11px; text-decoration:underline" 
            onclick="editWindow(\''.$salaryHeadRecordArray[$i]['assignmentDetailId'].'~1\',\'StudentTeacherActionDiv\');return false;">
            Details/Submission</a>';
        }
		else{

			$salaryHeadRecordArray[$i]['assignedOn'] = UtilityManager::formatDate($salaryHeadRecordArray[$i]['assignedOn']);

			if($salaryHeadRecordArray[$i]['tobeSubmittedOn']==date('Y-m-d'))
				$salaryHeadRecordArray[$i]['tobeSubmittedOn'] = "<span class='redColor'>".UtilityManager::formatDate($salaryHeadRecordArray[$i]['tobeSubmittedOn'])."</span>";
			else
				$salaryHeadRecordArray[$i]['tobeSubmittedOn'] = UtilityManager::formatDate($salaryHeadRecordArray[$i]['tobeSubmittedOn']);

			$actionStr='<a href="#" title="Details/Submission" style="font-size:11px; text-decoration:underline"
            onclick="editWindow(\''.$salaryHeadRecordArray[$i]['assignmentDetailId'].'~1\',\'StudentTeacherActionDiv\');return false;">
            Details/Submission</a>';
		}


		//if($salaryHeadRecordArray[$i]['readStatus'] == 1){



		//}
		//else{

		//	$actionStr=NOT_APPLICABLE_STRING;

		//}
        $valueArray = array_merge(array('action1' => $actionStr,'srNo' => ($records+$i+1) ),$salaryHeadRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: ajaxInitStudentAssigmentList.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-01   Time: 1:10p
//Updated in $/Leap/Source/Library/ScStudent
//Updated with session check
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-01   Time: 11:39a
//Updated in $/Leap/Source/Library/ScStudent
//implemented session check
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 2/06/09    Time: 6:40p
//Created in $/Leap/Source/Library/ScStudent
//Intial checkin
?>