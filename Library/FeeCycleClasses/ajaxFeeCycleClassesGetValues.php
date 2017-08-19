<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE QUOTA Seats LIST
//
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/FeeCycleClassesManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $feeCycleClassesManager = FeeCycleClassesManager::getInstance(); 
    
    $feeCycleId = trim($REQUEST_DATA['feeCycleId']);
     
    if($feeCycleId=='') {
      $feeCycleId=0;  
    }
   
     // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 10000;  
    $limit      = ' LIMIT '.$records.',10000';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    $sortField1= "classStatus, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3), studyPeriodId"; 
    $orderBy = " $sortField1 $sortOrderBy";
        
    $foundArray = $feeCycleClassesManager->getFeeCycleClasses($feeCycleId,'',$orderBy); 
    $cnt = count($foundArray);
    
    for($i=0;$i<$cnt;$i++) {
        $classId =  $foundArray[$i]['classId'];
        $feeCycleId = $foundArray[$i]['feeCycleClassId'];
        
        $str='';
        if($foundArray[$i]['mappedFeeCycle']!=NOT_APPLICABLE_STRING) {
           $str = explode('~',$foundArray[$i]['mappedFeeCycle']);
           $foundArray[$i]['mappedFeeCycle'] = $str[1];
           if($str[0]==trim($REQUEST_DATA['feeCycleId'])) {
             $str='';
           }
           else {
             $str=1;  
           }
        }
        
        $check="";
        if($foundArray[$i]['feeCycleClassId']!=-1) {
          $check="checked=checked";
        }
        
        //$checkall = NOT_APPLICABLE_STRING;
        //if($str == '' ) {
            $checkall = "<input type='checkbox' name='chb[]'  id='chk_classId_".$classId."' $check value='".$classId."'>
                        <input type='hidden' readonly='readonly' name='chb1[]' id='txt_feeCycleId_".$classId."' value='".$feeCycleId."'>
                        <input type='hidden' readonly='readonly' name='chb2[]' id='txt_classId_".$classId."' value='".$classId."'>";
        //}
       
       $valueArray = array_merge(array('checkAll' => $checkall,
                                       'srNo' => ($records+$i+1) ),$foundArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($foundArray).'","page":"'.$page.'","info" : ['.$json_val.']}';

?>


