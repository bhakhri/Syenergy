<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','AssociateTimeTableToClass');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timetableManager  = TimeTableManager::getInstance();
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
	$orderBy = " $sortField $sortOrderBy";
	
	$labelId = $REQUEST_DATA['labelId'];
	$filter	 = " AND ttc.timeTableLabelId=".$labelId;

	$labelActiveArray = $timetableManager->getLabelActive(" WHERE timeTableLabelId = ".$labelId);
	$labelActive = $labelActiveArray[0]['isActive'];
    
//	if($labelActive) {
    	$labelToClassArray = $timetableManager->getLabelToClassList($filter,$limit,$orderBy);
		$cnt = count($labelToClassArray);
        
		for($i=0;$i<$cnt;$i++) {
			$timeTableLabelId = $labelToClassArray[$i]['timeTableLabelId'];
            $classId=$labelToClassArray[$i]['classId']; 
            $timeTableClassId=$labelToClassArray[$i]['timeTableClassId'];  
            
			//$labelName  = $labelToClassArray[$i][labelName];
            if($labelId==$timeTableLabelId){
               $check="checked=checked";
			}
			else{
			   $check="";
			}
            $checkall = "<input type='checkbox' name='chb[]'  id='chk_classId_".$classId."' $check value='".$classId."'>
                         <input type='hidden' readonly='readonly' name='chb1[]' id='txt_timeTableClassId_".$classId."' value='".$timeTableClassId."'>
                         <input type='hidden' readonly='readonly' name='chb2[]' id='txt_classId_".$classId."' value='".$classId."'>";
            
			// add subjectId in actionId to populate edit/delete icons in User Interface   
			$valueArray = array_merge(array('checkAll' => $checkall , 'srNo' => ($records+$i+1) ),$labelToClassArray[$i]);
		 
		   if(trim($json_val)=='') {
				$json_val = json_encode($valueArray);
		   }
		   else {
				$json_val .= ','.json_encode($valueArray);           
		   }
		}
/*	}
	else {
	
		$labelToClassArray = $timetableManager->getInActiveLabelToClassList($filter,$limit,$orderBy);
		$cnt = count($labelToClassArray);
		if($cnt){

			for($i=0;$i<$cnt;$i++) {

				//$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($labelToClassArray[$i]['classId']).'" disabled checked>';
				$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($labelToClassArray[$i]['classId']).'" checked>';
				// add subjectId in actionId to populate edit/delete icons in User Interface   
				$valueArray = array_merge(array('checkAll' => $checkall , 'srNo' => ($records+$i+1) ),$labelToClassArray[$i]);
			 
			   if(trim($json_val)=='') {
					$json_val = json_encode($valueArray);
			   }
			   else {
					$json_val .= ','.json_encode($valueArray);           
			   }
			}
		}
		else{
		
			$labelToClassArray = $timetableManager->getLabelToClassList($filter,$limit,$orderBy);
			$cnt = count($labelToClassArray);
			for($i=0;$i<$cnt;$i++) {

				$timeTableLabelId = $labelToClassArray[$i][timeTableLabelId];
				//$labelName  = $labelToClassArray[$i][labelName];

				if($labelId==$timeTableLabelId){

					$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($labelToClassArray[$i]['classId']).'" checked>';
				}
				else{

					$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($labelToClassArray[$i]['classId']).'">';
				}

				// add subjectId in actionId to populate edit/delete icons in User Interface   
				$valueArray = array_merge(array('checkAll' => $checkall , 'srNo' => ($records+$i+1) ),$labelToClassArray[$i]);
			 
			   if(trim($json_val)=='') {
					$json_val = json_encode($valueArray);
			   }
			   else {
					$json_val .= ','.json_encode($valueArray);           
			   }
			}
		}
	
	}
*/    
    echo '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: scAjaxInitList.php $
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/05/09    Time: 6:03p
//Updated in $/LeapCC/Library/TimeTable
//Fixed Bug no  0000644
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/20/09    Time: 4:38p
//Updated in $/LeapCC/Library/TimeTable
//Fixed: 0000628: Associate Time Table to Class (Admin) > Associated
//classes should be display selected with previous “Time Table” also.
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/18/09    Time: 11:54a
//Updated in $/LeapCC/Library/TimeTable
//Modified module so that incactive time table labels cannot be
//associated with current class
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTable
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:47p
//Updated in $/Leap/Source/Library/TimeTable
//applied role level access
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/30/08    Time: 6:13p
//Created in $/Leap/Source/Library/TimeTable
//intial checkin
?>