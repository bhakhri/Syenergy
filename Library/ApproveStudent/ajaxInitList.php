<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendMessageToStudents');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/ApproveStudentManager.inc.php");
$studentManager = ApproveStudentManager::getInstance();
   
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_ADMIN_MESSAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_ADMIN_MESSAGE;

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";      

    $totalArray         = $studentManager->getTotalAdmAppl($filter);
    $studentRecordArray = $studentManager->getAdmApplList($filter,$limit,$orderBy);
    
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       if($studentRecordArray[$i]['isApproved']=='1'){
          $actionString='<b>Approved</b>'; 
       }
       else if($studentRecordArray[$i]['isApproved']=='0'){
          $actionString='<b>Rejected</b>'; 
       }
       else{
          $actionString='<select name="studentStatus" id="'.$studentRecordArray[$i]['studentId'].'" class="inputbox" style="width:80px;">
                         <option value="2" selected="selected">Pending</option>
                         <option value="1">Approved</option>
                         <option value="0">Rejected</option>
                         </select>';  
       }
       $valueArray = array_merge(array('srNo' => ($records+$i+1),'actionString'=>$actionString)
        , $studentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.'],"studentInfo" : '.json_encode($totalArray).'}';
?>