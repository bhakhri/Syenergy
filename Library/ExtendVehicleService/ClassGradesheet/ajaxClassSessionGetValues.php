<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Reappear/ Re-exam Flow
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/ClassSessionUpdateManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $ClassUpdateManager = ClassUpdateManager::getInstance(); 
    

	

  //sortField=className& batchId=MBA09 & degreeId=Masters of Business Administration & branchId=Business Administration
    $degreeId = trim($REQUEST_DATA['degreeId']);
    $branchId = trim($REQUEST_DATA['branchId']); 
    $batchId = trim($REQUEST_DATA['batchId']); 

   
    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 10000;  
    $limit      = ' LIMIT '.$records.',10000';
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
 
	//$sortField1= "classStatus, SUBSTRING_INDEX(c.className,'".CLASS_SEPRATOR."',-3), studyPeriodId"; 
    $orderBy = " $sortField $sortOrderBy";
        
    $condition='';
   // if($batchId!='') {
   //   $condition = " AND b.batchYear IN ($batchId)";   
   // }
        
    $foundArray = $ClassUpdateManager->getSessionClasses($batchId,$branchId,$degreeId,$condition,$orderBy); 
	
    $cnt = count($foundArray);
    
    for($i=0;$i<$cnt;$i++) {
        $classId =  $foundArray[$i]['classId'];
        $titleName =  $foundArray[$i]['sessionTitleName'];
        $displayOrder =  $foundArray[$i]['displayOrder'];
		$intrnlMarks =  $foundArray[$i]['internalPassMarks'];
		$extrnlMarks =  $foundArray[$i]['externalPassMarks'];
		//echo $intrnlMarks."bbbbbb".$extrnlMarks;die;
       $studyPeroid=  $foundArray[$i]['studyPeriodId'];
        $titleName = "<input type='text' class='htmlElement' name='chb[]' id='chb_".$classId."'      value='".$titleName."'>
        		      <input type='hidden'  class='inputbox3' name='chb1[]'  id='chb1_".$classId."' value='".$classId."'>";
                      
        $showOrder = "<input type='text' class='htmlElement' name='chbOrder[]' id='chbOrder_".$classId."' value='".$displayOrder."'>";
		
		$showIntrnl = "<input type='text' class='htmlElement' name='chbIntrnl[]' id='chbIntrnl_".$classId."' value='".$intrnlMarks."'>";
		
		$showExtrnl = "<input type='text' class='htmlElement' name='chbExtrnl[]' id='chbExtrnl_".$classId."' value='".$extrnlMarks."'>";
     //  echo $showExtrnl."BBBBBBAAAAAAA".$showIntrnl;die;
        $valueArray = array_merge(array('showOrder' => $showOrder,
                                        'titleName' => $titleName,
                                        'internalMarks' => $showIntrnl,
                                        'externalMarks' => $showExtrnl,
                                        'srNo' => ($records+$i+1),
                                        'studyPeriodName'=>$studyPeroid ),$foundArray[$i]);
        if(trim($json_val)=='') {
             $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($foundArray).'","page":"'.$page.'","info" : ['.$json_val.']}';
?>
