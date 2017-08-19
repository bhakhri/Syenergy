<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/CalendarManager.inc.php");
    $calendarManager = CalendarManager::getInstance();

    $conditionsArray = array();
    $qryString = "";
    
    define('MANAGEMENT_ACCESS',1);
    define('MODULE','AddEvent');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    
//to parse csv values    
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"'; 
 } 
 else {
 return $comments; 
 }
 
}

    //get the day component
    $day=explode("-",$REQUEST_DATA['sdate']);
    if($day[1]<10){
        $day[1]="0".intval($day[1]); //so that months become:08
    }
    
    if(trim($REQUEST_DATA[sdate])!=""){
     if(intval($day[2])!=0){   // for a specific day
       $filter = " AND ( '$REQUEST_DATA[sdate]' >=ev.startDate AND '$REQUEST_DATA[sdate]'<=ev.endDate) "; 
      }
     else{ //for the whole month
         $daysMonth=date("t",mktime(0,0,0,$m,1,$y));
         //build the query
         $filter="AND ( (ev.startDate BETWEEN '$day[0]-$day[1]-01' AND '$day[0]-$day[1]-$daysMonth')
                OR 
                (ev.endDate between '$day[0]-$day[1]-01' AND '$day[0]-$day[1]-$daysMonth')
                OR
                (ev.startDate <= '$day[0]-$day[1]-01' AND ev.endDate>= '$day[0]-$day[1]-$daysMonth') )";
                //echo "1";
         //$filter = " AND (ev.startDate LIKE '".$day[0]."-".$day[1]."-%' AND ev.endDate LIKE '".$day[0]."-".$day[1]."-%') ";     
      }
    }
    $search=trim(add_slashes($REQUEST_DATA['searchbox']));
	if($search!=''){
		$visibleDate = date('Y-m-d',strtotime($REQUEST_DATA['searchbox']));
        $filter .=" AND ( ev.eventTitle LIKE '%".$search."%' OR  ev.shortDescription LIKE '%".$search."%' OR ev.startDate LIKE '%".$visibleDate."%' OR  ev.endDate LIKE '%".$visibleDate."%' OR (select roleId from role where roleName like '%".$search."%' and FIND_IN_SET(roleId,(replace(ev.roleIds,'~',',')))))";
    }

   
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'startDate';

    //$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy=" ev.$sortField $sortOrderBy"; 

    $recordArray = $calendarManager->getEventList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    //get the role names     
    $query="SELECT roleId, roleName from role";
    $roleArr = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query ");
    $j=count($roleArr);
        
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $roleIds=explode("~",$recordArray[$i]['roleIds']);
        $recordArray[$i]['roleIds']="";
        $l=count($roleIds);
        $m=0;
        $roles=array();
        for($k=0 ; $k<$j ;$k++){
            for($m=0 ; $m < $l ; $m++){
                if($roleArr[$k]['roleId']==$roleIds[$m]){
                        if($recordArray[$i]['roleIds']){
                          $recordArray[$i]['roleIds'].=", ".$roleArr[$k]['roleName'];
                        }
                       else{
                           $recordArray[$i]['roleIds'].=$roleArr[$k]['roleName'];
                       } 
                }
            }
        }    
            
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1),'startCDate' => UtilityManager::formatDate($recordArray[$i]['startDate']),'endCDate' => UtilityManager::formatDate($recordArray[$i]['endDate']) ),$recordArray[$i]);
   }

	$csvData = "Search By : ".$search."\n";
    $csvData .= "#, Event Title, Short Description, Long Description, Start Date, End Date, Visible To \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['eventTitle']).','.parseCSVComments($record['shortDescription']).','.parseCSVComments($record['longDescription']).', '.parseCSVComments($record['startCDate']).', '.parseCSVComments($record['endCDate']).','.parseCSVComments($record['roleIds']);
		$csvData .= "\n";
	}
    
    if(count($valueArray)==0){
        $csvData .=",".NO_DATA_FOUND;
    }
	
    ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="events.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: calendarCSV.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/03/10    Time: 3:32p
//Updated in $/LeapCC/Templates/Calendar
//access permission updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/08/09   Time: 15:26
//Updated in $/LeapCC/Templates/Calendar
//Done bug fixing.
//bug ids---00001141,00001142
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/08/09    Time: 17:10
//Created in $/LeapCC/Templates/Calendar
//Corrected "Event Masters" bugs as pointed by Kanav Sir
?>