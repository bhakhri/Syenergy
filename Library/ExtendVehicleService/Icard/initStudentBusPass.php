<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in all details report.
//
//
// Author :Parveen Sharma
// Created on : 16-06-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    
    $commonQueryManager = CommonQueryManager::getInstance();    
    $studentManager = StudentReportsManager::getInstance();
    
    $busRouteArray=$commonQueryManager->getBusRoute('busRouteId');
    $busCnt=count($busRouteArray);
    
    
    $conditions ='';
    
 // Search filter
 /*   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $conditions = ' AND (a.regNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                            CONCAT(IFNULL(a.firstName,"")," ",IFNULL(a.lastName,"")) LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                            a.fatherName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                            className LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                            studentMobileNo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                            routeCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                            stopName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" 
                           )';
    }
*/
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";
   
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    //$records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    $records    = ($page-1)* 1000;
    $limit      = ' LIMIT '.$records.','.'1000';

    //$conditions .= " AND (a.busStopId !='0' AND a.busRouteId !='0')";
    if($REQUEST_DATA['degree']!='') {
        $conditions .= " AND a.classId = '".$REQUEST_DATA['degree']."'";
    }
    
    if($REQUEST_DATA['sRollNo']!='') {
       $conditions .= " AND ( a.rollNo LIKE ('".$REQUEST_DATA['sRollNo']."%') OR a.regNo LIKE ('".$REQUEST_DATA['sRollNo']."%') ) ";   
    }
    
    if($REQUEST_DATA['sName']!='') {
       $conditions .= " AND CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) LIKE ('".$REQUEST_DATA['sName']."%')";    
    }

    
    $studentRecordArray = $studentManager->getStudentBusPassDetails($conditions, $orderBy, $limit);
    $cnt = count($studentRecordArray);


    for($i=0;$i<$cnt;$i++) {
        $id = strip_slashes($studentRecordArray[$i]['studentId']);
        $classId = strip_slashes($studentRecordArray[$i]['classId']);
        $checkall = "<input type='checkbox' name='chb[]' id='chb".$id."' onClick='checkDisable(".$id.")' value='".$id."'>";
        
        $busTotal = "<br><span id='divBusTotal".$id."' align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'></span>";
        $routeTotal = "<br><span id='divRouteTotal".$id."' align='right' style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:9px;font-weight:normal;color:red;'></span>";
         
        $busName = "<select size='1' style='width:160px;z-index:100;' class='selectfield' name='busRoute[]' onChange='autoPopulate(this.value,".$id.");' id='busRoute".$id."'>
                            <option value=''>Select</option>";
    
        $stoppage = "<select size='1' style='width:160px' class='selectfield' name='busStop[]'  id='busStop".$id."'>
                            <option value=''>Select</option>
                     </select>";
                     
        $busNo = "<select size='1' style='width:160px' class='selectfield' name='busNo[]' onChange='getBusCount(this.value,".$id.");' id='busNo".$id."'>
                    <option value=''>Select</option>
                 </select>".$busTotal;
    
        
        for($j=0; $j<$busCnt; $j++) {
           if($studentRecordArray[$i]['receiptNo']!="") {      
              if($busRouteArray[$j]['busRouteId']==$studentRecordArray[$i]['busRouteId']){
                $busName=$studentRecordArray[$i]['routeCode'];
                $stoppage=$studentRecordArray[$i]['stopName'];
                $busNo=$studentRecordArray[$i]['busNo'];
                break;
              }
              else{
                $busName .= '<option value="'.$busRouteArray[$j]['busRouteId'].'">'.$busRouteArray[$j]['routeCode'].'</option>';
              }
           }
           else{
             $busName .= '<option value="'.$busRouteArray[$j]['busRouteId'].'">'.$busRouteArray[$j]['routeCode'].'</option>';
           }
        }
        
        
        //if($j==$busCnt) {
        if($studentRecordArray[$i]['receiptNo']=="") {     
           $checkall .= "<input type='hidden' class='inputbox' name='sStudentId[]' id='sStudentId".$id."' value='Y' />";   
           $busName .= '</select>'.$routeTotal;
        }
        else {
           $checkall .= "<input type='hidden' class='inputbox' name='sStudentId[]' id='sStudentId".$id."' value='N' />";   
        }
        
        $actionStr = NOT_APPLICABLE_STRING;
        if($studentRecordArray[$i]['receiptNo']=="") {
           $receiptNo = "<input type='textbox' maxlength='20' class='inputbox'  style='width:90px' name='receiptNo[]' id='receiptNo".$id."' value='".strip_slashes($studentRecordArray[$i]['receiptNo'])."' />
                         <input type='hidden' class='inputbox'  style='width:90px' name='busClassId[]' id='busClassId".$id."' value='".$classId."' />";
                         
           $validUpto = "<input type='textbox' maxlength='8' class='inputbox' style='width:90px' name='validUpto[]' id='validUpto".$id."' value='".strip_slashes($studentRecordArray[$i]['validUpto'])."' />
                         <input type='hidden' class='inputbox'  style='width:90px' name='busStudentId[]' id='busStudentId".$id."' value='".$id."' /> ";
        }   
        else {
           $receiptNo = $studentRecordArray[$i]['receiptNo'];
           $validUpto = UtilityManager::formatDate($studentRecordArray[$i]['validUpto']); 
           //$actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$studentRecordArray[$i]['busPassId'].',\'AddBusPass\',340,250);return false;"></a>'; 
           //$actionStr = "<a href=\"#\" name=\"bubble\" onclick=\"editBusPass("+$studentRecordArray[$i]['busPassId']+"); return false;\" title='Cancel' style=\"color:red\">"+trim(document.getElementById('title').value)+"</a>";
           $actionStr = "<img src='".IMG_HTTP_PATH."/deactive.gif' border='0' alt='Cancel' title='Cancel' width='10' height='10' style='cursor:pointer' onclick='editBusPass(".$studentRecordArray[$i]['studentId'].",".$studentRecordArray[$i]['busPassId'].",".$studentRecordArray[$i]['classId']."); return false;' >";
        }
        
        
        $valueArray = array_merge(array('checkAll' => $checkall , 
                                        'rollNo' => $studentRecordArray[$i]['rollNo'],
                                        'studentName' => $studentRecordArray[$i]['studentName'],
                                        'busName' => $busName,
                                        'busNo' => $busNo,
                                        'stoppage' => $stoppage,
                                        'receiptNo' => $receiptNo,
                                        'validUpto' => $validUpto,
                                        'action1' => $actionStr,
                                        'srNo' => ($records+$i+1))
        );
        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);           
        }
    }

    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 
        
//'students' => "<input type=\"checkbox\" name=\"studentList\" id=\"studentList\" value=\"".$studentRecordArray[$i]['studentId'] ."\">",
 /*   
    for($i=0;$i<$cnt;$i++) {
        $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$studentRecordArray[$i]['busPassId'].',\'AddBusPass\',340,250);return false;"></a>';
        $checkall = '<input type="checkbox" name="chb[]"  value="'.strip_slashes($studentRecordArray[$i]['studentId']).'">';
        $valueArray = array_merge(array('checkAll' => $checkall , 
                                        'action1' => $actionStr,
                                        'action' => $studentRecordArray[$i]['busPassId'],
                                        'srNo' => ($records+$i+1) ),$studentRecordArray[$i]);
        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$totalRecords.'","page":"'.$REQUEST_DATA['page'].'","info" : ['.$json_val.']}'; 
*/
    
//$History: initStudentBusPass.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 2/10/10    Time: 11:40a
//Updated in $/LeapCC/Library/Icard
//receipt No check udpated
//
//*****************  Version 11  *****************
//User: Parveen      Date: 6/29/09    Time: 12:17p
//Updated in $/LeapCC/Library/Icard
//regNo wise condition updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 6/23/09    Time: 6:42p
//Updated in $/LeapCC/Library/Icard
//formatting & deactive code update
//
//*****************  Version 9  *****************
//User: Parveen      Date: 6/23/09    Time: 10:29a
//Updated in $/LeapCC/Library/Icard
//date formatting settings
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/22/09    Time: 4:13p
//Updated in $/LeapCC/Library/Icard
//issue fix Format, validation added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/22/09    Time: 2:29p
//Updated in $/LeapCC/Library/Icard
//formating, validations, messages, conditions  changes 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/17/09    Time: 6:09p
//Updated in $/LeapCC/Library/Icard
//rollno by search condition update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/16/09    Time: 6:30p
//Updated in $/LeapCC/Library/Icard
//validation update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/16/09    Time: 4:02p
//Updated in $/LeapCC/Library/Icard
//search condition update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/13/09    Time: 3:18p
//Updated in $/LeapCC/Library/Icard
//edit filter added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/13/09    Time: 3:08p
//Updated in $/LeapCC/Library/Icard
//search condition update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/13/09    Time: 2:53p
//Created in $/LeapCC/Library/Icard
//initial checkin
//

?>