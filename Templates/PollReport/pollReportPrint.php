<?php 
	set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
   
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'total';
    $orderBy = " $sortField $sortOrderBy";

    
    $condition='';
    $foundArray = $studentManager->pollReport($condition,$orderBy);
    $cnt = count($foundArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add countryId in actionId to populate edit/delete icons in User Interface
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1)),
                                        $foundArray[$i]);
    }
    
    $formattedDate = date('d-M-y'); 
    
    
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading("Teacher's Poll Report");
    $reportManager->setReportInformation("As on $formattedDate");
	 
	$reportTableHead = array();
	//associated key col.label, col.width, data align	
	$reportTableHead['srNo'] = array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['employeeNameCode'] = array('Teacher','width=25% align="left"', 'align="left"');
	$reportTableHead['q1'] = array('Adorable<br>Teacher','width=15% align="center"', 'align="center"');
	$reportTableHead['q2'] = array('Dedicated<br>Teacher','width="15%" align="center" ', 'align="center"');
    $reportTableHead['q3'] = array('Interactive<br>Teacher','width="10%" align="center" ', 'align="center"');
    $reportTableHead['q4'] = array('Ever-smiling<br>Teacher','width=15% align="center"', 'align="center"');
    $reportTableHead['q5'] = array('Charismatic Teacher<br>(based on personality)','width=12% align="center"', 'align="center"'); 
    $reportTableHead['total'] = array('Total','width=18% align="center"', 'align="center"'); 
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>