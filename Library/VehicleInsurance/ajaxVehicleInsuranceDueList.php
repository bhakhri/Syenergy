<?php
//--------------------------------------------------------------------------------------------
//Purpose: This file stores the list of vehicle whose 'insuranceDueDate' is less than 10 days.
//Author: Kavish Manjkhola
//Created On: 31/04/2011
//Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(BL_PATH.'/HtmlFunctions.inc.php');
define('MODULE','InsuranceVehicleAutopay');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/InsuranceVehicleManager.inc.php");
$insuranceVehicleManager = InsuranceVehicleManager::getInstance();

//Array which will store the list of vehicle whose "insuranceDueDate" is pending.
$getVehicleInsuranceDueListArray = array();	

//Array which will store the list of vehicle whose "insuranceDueDate" is less than or equal to10 days
$finalVehicleInsuranceList = array();

//Defines what action is to be taken per row when clicked on the 'action' field of the list shown in the grid.
$actionStr = '';

//This variable is used to accept the search term and futher passed to the "InsuranceVehicleManager.inc.php" file to get added with the query.
$filter = '';

//This variable is used to control the "finalVehicleInsuranceList" indexing.
$ctr = 0;

//This variable is used to control the 'No Of Days' period for a notification to be shown.
$dayLimit = $sessionHandler->getSessionVariable('DAY_LIMIT_FOR_NOTIFICATION');

//Helps you to show the time in 12hr format. You choose '24' hr format by replacing '12' by '24'.
$timeFormat = '12'; 

// to limit records per page    
$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
$records    = ($page-1)* RECORDS_PER_PAGE;
$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

/// Search filter /////  
if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
   $filter = ' WHERE (busNo LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR lastInsuranceDate LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR insuranceDueDate LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"OR insuringCompanyId LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"OR policyNo LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR valueInsured LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"OR insurancePremium LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"OR branchName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"OR agentName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';
}

$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'busId';

$orderBy = " bi.$sortField $sortOrderBy";

$getVehicleInsuranceDueListCount = $insuranceVehicleManager->getInsuranceDueDateListCount($filter, $limit, $orderBy, $dayLimit);
$count = count($getVehicleInsuranceDueListCount);

$getVehicleInsuranceDueListArray = $insuranceVehicleManager->getInsuranceDueDateListDetails($filter, $limit, $orderBy, $dayLimit);
$insuranceCount = count($getVehicleInsuranceDueListArray);

for($i=0; $i<$insuranceCount; $i++) {
	$finalVehicleInsuranceList[$ctr]['busId'] = $getVehicleInsuranceDueListArray[$i]['busId'];
	$id = $finalVehicleInsuranceList[$ctr]['busId'];

	$finalVehicleInsuranceList[$ctr]['busNo'] = $getVehicleInsuranceDueListArray[$i]['busNo'];

	$insuranceDate = explode(" ", UtilityManager::formatDate($getVehicleInsuranceDueListArray[$i]['lastInsuranceDate'],true,$timeFormat));
	$finalVehicleInsuranceList[$ctr]['lastInsuranceDate'] = $insuranceDate[0];

	$dueDate = explode(" ", UtilityManager::formatDate($getVehicleInsuranceDueListArray[$i]['insuranceDueDate'],true,$timeFormat));
	$finalVehicleInsuranceList[$ctr]['insuranceDueDate'] = $dueDate[0];

	$finalVehicleInsuranceList[$ctr]['insuringCompanyId'] = $getVehicleInsuranceDueListArray[$i]['insuringCompanyId'];
	$finalVehicleInsuranceList[$ctr]['policyNo'] = $getVehicleInsuranceDueListArray[$i]['policyNo'];

	$finalVehicleInsuranceList[$ctr]['valueInsured'] = $getVehicleInsuranceDueListArray[$i]['valueInsured'];
	$finalVehicleInsuranceList[$ctr]['insurancePremium'] = $getVehicleInsuranceDueListArray[$i]['insurancePremium'];

	$finalVehicleInsuranceList[$ctr]['branchName'] = $getVehicleInsuranceDueListArray[$i]['branchName'];
	$finalVehicleInsuranceList[$ctr]['agentName'] = $getVehicleInsuranceDueListArray[$i]['agentName'];

	//Calculating next 'insuranceDueDate' by adding 1 year to the current 'insuranceDueDate'
	$date = new DateTime($finalVehicleInsuranceList[$ctr]['lastInsuranceDate']);
	$date->modify("+1 Year");
	$dueDate =  $date->format("d-m-Y");
	$hiddenFieldName = "dueDate".$id;

	$fieldName = "insCalendar".$id;
	$value = date('Y-m-d');

	//Calculating next 'insuranceDueDate' by adding 1 year to the current 'insuranceDueDate'
	$date = new DateTime($value);
	$date->modify("+1 Year");
	$dueDate =  $date->format("d-m-Y");
	$hiddenFieldName = "dueDate".$id;

	$insCalendar = "<input type=\"text\" id=\"$fieldName\" name=\"$fieldName\" class=\"inputBox\" readonly=\"true\" value=\"$value\" style='width:75px;'						size=\"8\" /><input type=\"image\" id=\"calImg$id\" name=\"calImg$id\" title=\"Select Date\" src=\"".IMG_HTTP_PATH."/calendar.gif\"							$showFunctionName onClick=\"return showCalendar('$fieldName', '%Y-%m-%d', '24', true);  \">
					<input type='hidden' value='$dueDate' id='$hiddenFieldName' name='$hiddenFieldName'>
				  ";
                   
	$actionStr = "<a title='Pay'><input type='image' src='".IMG_HTTP_PATH."/pay.gif' border='0' style='position:relative; top:2px' alt='Pay' onclick='getPayInfo(\"".$id."\",\"divPayInsurance\",315,250);return false;'></a>&nbsp;";

	
	$valueArray = array_merge(array('action1' => $actionStr, 'srNo' => ($records+$i+1), 'insCalendar' => $insCalendar),
							  $finalVehicleInsuranceList[$ctr]);
	if(trim($json_val)=='') {
		$json_val = json_encode($valueArray);
	}
	else {
		$json_val .= ','.json_encode($valueArray);    
	}
	$ctr++;
}
echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';
?>