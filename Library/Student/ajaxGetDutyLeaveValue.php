<?php 
	set_time_limit(0); 
    global $FE;  
    require_once($FE . "/Library/common.inc.php"); //for studentId 
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
    $commonQueryManager = CommonQueryManager::getInstance(); 

    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;           
     
    if($sessionHandler->getSessionVariable('RoleId')==3) {
      UtilityManager::ifParentNotLoggedIn(true);  
      $studentId= $sessionHandler->getSessionVariable('StudentId');          
    }
    else if($sessionHandler->getSessionVariable('RoleId')==4) {
      UtilityManager::ifStudentNotLoggedIn(true);   
      $studentId= $sessionHandler->getSessionVariable('StudentId');          
    }
    else {
       UtilityManager::ifNotLoggedIn(true);  
    }
    UtilityManager::headerNoCache();
    
    $id = $REQUEST_DATA['id'];
    
    $idArray = explode('~',$id);
    
	$studentId= $idArray[0];   
	$classId= $idArray[1];
    $subjectId= $idArray[2];
	$groupId= $idArray[3];
	
	 
    if($studentId=='') {
      $studentId='0';
    }  
      
    if($classId=='') {
      $classId='0';
    }  

    if($subjectId=='') {
      $subjectId='0';
    }  
    
    if($groupId=='') {
      $groupId='0';
    }  
    
    $condition='';
    if($REQUEST_DATA['consolidatedView']=='1') {
      $condition="AND dl.studentId='$studentId' AND
      			  dl.classId='$classId' AND
      			  dl.subjectId='$subjectId'";
    }  
    else if($REQUEST_DATA['consolidatedView']=='0'){
    	$condition="AND dl.studentId='$studentId' AND
      			  dl.classId='$classId' AND
      			  dl.subjectId='$subjectId' AND 
      			  dl.groupId='$groupId'";
    } 
    $orderBY= "";
    $foundArray = $commonQueryManager->getDutyLeaveValue($condition, $orderBY);
    
    $tableData=""; 
    
   if(is_array($foundArray) && count($foundArray)>0 ) {
        $tableData ="<table width='100%' border='0' cellspacing='2' cellpadding='1'>
                        <tr class='rowheading'>
                         <td width='5%' class='searchhead_text'>#</td>
                         <td width='15%' class='searchhead_text' align='center'>Date</td>
                         <td width='25%' class='searchhead_text'>Event</td>
                         <td width='30%' class='searchhead_text'>Subject</td>
                         <td width='15%' class='searchhead_text'>Subject Code</td>
                         <td width='10%' class='searchhead_text' align='center'>Period</td>
                       </tr>";
            for($i=0,$j=0;$i<count($foundArray);$i++) {
                $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
                if($foundArray[$i]['nonConflictedApproved']==1){
                	$tableData .= "<tr class='$bg'>
                                 <td class='padding_top'>".($j+1)."</td>
                                 <td class='padding_top' align='center'>". UtilityManager::formatDate($foundArray[$i]['dutyDate'])."</td>
                                 <td class='padding_top'>".$foundArray[$i]['eventTitle']."</td>
                                 <td class='padding_top'>".$foundArray[$i]['subjectName']."</td>
                                 <td class='padding_top'>".$foundArray[$i]['subjectCode']."</td>
                                 <td class='padding_top' align='center'>".$foundArray[$i]['periodId']."</td>
                                </tr>"; 
                    $j++;
                }
             }
         $tableData.= "</table>";
         echo $tableData."!~!".json_encode($foundArray);
         die;
    }

?>
