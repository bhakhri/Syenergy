<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AppraisalReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Appraisal/AppraisalReportManager.inc.php");
    $appManager = AppraisalReportManager::getInstance();
    
    $sortBy=trim($REQUEST_DATA['sortBy']);
    $sortOrder=trim($REQUEST_DATA['sortOrder']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    
    if($sortBy==''){
        $sortBy='employeeName';
    }
    if($sortOrder==''){
        $sortOrder='ASC';
    }
    
    $orderBy =$sortBy.' '.$sortOrder;
     
    $conditions='';
    if($employeeId!='-1'){
        $conditions='AND e.employeeId='.$employeeId;
    }
    
    //fetch distinct appraisal tabs 
    $tabsArray=$appManager->getAppraisalTabs();
	$mainTabsArray = array();
	foreach ($tabsArray as $record) {
		$mainTabsArray[] = $record['appraisalTabName'];
	}

    $tCnt=count($tabsArray);
    
    
    $appArray=$appManager->showAppraisalReport($conditions,$orderBy);
    $reviewrArray=$appManager->showReviewerReport($conditions);
    $cnt=count($appArray);
    
    $rCnt=count($reviewrArray);
    $revArray=array();
    for($i=0;$i<$rCnt;$i++){
       $revArray[$reviewrArray[$i]['employeeId']]=$reviewrArray[$i]['rev'];
    }
    
    $empArray=array();
    $empAgrArray=array(); 
    $empDeptArray=array();
    if($cnt>0){
        $total=0;
        for($i=0;$i<$cnt;$i++){
          $empArray[$appArray[$i]['employeeId']][$appArray[$i]['employeeName']][$appArray[$i]['appraisalTabName']]=$appArray[$i]['hod'];
          $empDeptArray[$appArray[$i]['employeeId']]=$appArray[$i]['abbr'];
          $empAgrArray[$appArray[$i]['employeeId']] +=$appArray[$i]['hod'];
        } 
    }

    foreach($empAgrArray as $key=>$val){
         $empAgrArray[$key]=$revArray[$key]+$val;
    }
    
    $tableHeader='<table border="0" cellpadding="1" cellspacing="1" width="100%">';
    $tableHeader .='<tr class="rowheading">
                        <td class="searchhead_text" width="2%">#</td>
                        <td class="searchhead_text" width="15%">Employee</td>
                        <td class="searchhead_text" width="5%">Department</td>';
    
    $width=round(68/($tCnt+5));                    
    for($i=0;$i<$tCnt;$i++){
      $tableHeader .='<td class="searchhead_text" width="'.$width.'%" align="right" style="padding-right:3px;">'.$tabsArray[$i]['appraisalTabName'].'</td>';
    }
    $tableHeader .='<td class="searchhead_text" width="'.$width.'%" align="right" style="padding-right:3px;">Reviewer</td>';
    $tableHeader .='<td class="searchhead_text" width="'.$width.'%" align="right" style="padding-right:3px;">Aggregate</td>';
    $tableHeader .='</tr>';
    if($tCnt==0){
        echo $tableHeader.'<tr class="row"><td align="center" colspan="4" class="padding_top">'.NO_DATA_FOUND.'</td></tr></table>';
        die;
    }
    
    if($cnt==0){
       echo $tableHeader.'<tr class="row"><td align="center" colspan="'.($tCnt+4).'" class="padding_top">'.NO_DATA_FOUND.'</td></tr></table>';
       die; 
    }
    
    $lc=1;
    $tableBody='';
	foreach($empArray as $empId => $empNameArray) {
		foreach ($empNameArray as $empName => $tabArray) {
            $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
			$tableBody.= "<tr $bg>
                      <td class='padding_top'>$lc</td>
                      <td class='padding_top'>$empName</td>
                      <td class='padding_top'>".$empDeptArray[$empId]."</td>";
			foreach ($mainTabsArray as $tabName) {
                $value=$tabArray[$tabName];
                if($value==''){
                    $value=0;
                }
				$tableBody .="<td class='padding_top' align='right' style='padding-right:3px;'>".$value."</td>";
			}
            $revVal=$revArray[$empId];
            if($revVal==''){
                $revVal=0;
            }
            $tableBody .= "<td class='padding_top' align='right' style='padding-right:3px;'>".$revVal."</td>";
            $tableBody .= "<td class='padding_top' align='right' style='padding-right:3px;'>".$empAgrArray[$empId]."</td>";
			$tableBody .= "</tr>";
		}
        $lc++;
	}
   $tableBody .="</table>";
   
   echo $tableHeader.$tableBody;
?>