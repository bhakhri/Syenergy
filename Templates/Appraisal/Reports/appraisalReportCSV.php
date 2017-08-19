<?php 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TestTypeManager.inc.php");
    $testTypeManager = TestTypeManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    

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
 
 function generateCSV($dataString){
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    header('Content-type: application/octet-stream; charset=utf-8');
    header("Content-Length: " .strlen($dataString) );
    header('Content-Disposition: attachment;  filename="appraisalReport.csv"');
    header("Content-Transfer-Encoding: binary\n");
    echo $dataString;
 }

    require_once(MODEL_PATH . "/Appraisal/AppraisalReportManager.inc.php");
    $appManager = AppraisalReportManager::getInstance();
    
    $empSuperArray=array();
    
    $sortBy=trim($REQUEST_DATA['sortBy']);
    $sortOrder=trim($REQUEST_DATA['sortOrder']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $employeeName=trim($REQUEST_DATA['employeeName']);
    
    
    $searchBy="Search By: Employee : ".$employeeName."\n";
    
    $csvHeader="#,Employee,Department";
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
    $tCnt=count($tabsArray);
    $width=round(68/($tCnt+5));
    foreach ($tabsArray as $record) {
        $mainTabsArray[] = $record['appraisalTabName'];
        $csvHeader .=" , ".$record['appraisalTabName'];
    }
    $csvHeader .=", Reviewer, Aggregate\n";
    
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
    if($tCnt==0){
        generateCSV($searchBy.$csvHeader."\n".NO_DATA_FOUND);
        die;
    }
    
    if($cnt==0){
       generateCSV($searchBy.$csvHeader.NO_DATA_FOUND);
       die; 
    }
    
    $lc=1;
    $tableBody='';
    foreach($empArray as $empId => $empNameArray) {
        foreach ($empNameArray as $empName => $tabArray) {
            $empSuperArray[$lc-1]['srNo']=$lc;
            $empSuperArray[$lc-1]['employeeName']=$empName;
            $empSuperArray[$lc-1]['abbr']=$empDeptArray[$empId];
            foreach ($mainTabsArray as $tabName) {
                $value=$tabArray[$tabName];
                if($value==''){
                    $value=0;
                }
                $empSuperArray[$lc-1][$tabName]=$value;
            }
            $revVal=$revArray[$empId];
            if($revVal==''){
                $revVal=0;
            }
            $empSuperArray[$lc-1]['Reviewer']=$revVal;
            $empSuperArray[$lc-1]['Aggregate']=$empAgrArray[$empId];
        }
        $lc++;
    }

    $csvData=$searchBy.$csvHeader;;
	foreach($empSuperArray as $record) {
        foreach($record as $key=>$value){
            $csvData .=parseCSVComments($value)." , ";
        }
		$csvData .="\n";
	}
 	generateCSV($csvData);
	die;   
?>