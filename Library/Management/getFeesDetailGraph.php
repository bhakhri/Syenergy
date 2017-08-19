<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 13-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(MODEL_PATH . "/Management/FeesReportManager.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ManagementFeesInfo');
define('ACCESS','view');
UtilityManager::ifManagementNotLoggedIn();
$feeReportManager = FeeReportManager::getInstance();

$searchStudent = $REQUEST_DATA['searchStudent'];
$graphType = $REQUEST_DATA['graphType'];

/* START: function to fetch fee cycle pie data */
if($searchStudent=='FeeCycle' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeCycleTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount =number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');

		$strList .= "<slice title='".$feecycleRecordArray[$i]['cycleName']."' description='".$feecycleRecordArray[$i]['feeCycleId']."~feecycle'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feecycleTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "FeeCyclePie";
}	
/* END: function to fetch fee cycle pie data */

/* START: fee cycle bar graph*/
if($searchStudent=='FeeCycle' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeCycleTotal();
	$cnt = count($feecycleRecordArray); 
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['cycleName']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feecycleTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "FeeCycleBar";
}	
/* END: fee cycle bar graph*/

/* START: function to fetch fee class data */
if($searchStudent=='ClassWise' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeClassTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount =number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');

		$strList .= "<slice title='".$feecycleRecordArray[$i]['className']."' description='".$feecycleRecordArray[$i]['classId']."~feeclass'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeClassTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "ClassWisePie";
}	

if($searchStudent=='ClassWise' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeClassTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['className']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeclassTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "ClassWiseBar";
}
/* END: function to fetch fee class data */

/* START: function to fetch fee batch data */
if($searchStudent=='BatchWise' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeBatchTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount =number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');

		$strList .= "<slice title='".$feecycleRecordArray[$i]['batchName']."' description='".$feecycleRecordArray[$i]['batchId']."~feeBatch'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeBatchTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "BatchWisePie";
}	

if($searchStudent=='BatchWise' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeBatchTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['batchName']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeBatchTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "BatchWiseBar";
}
/* END: function to fetch fee batch data */

/* START: function to fetch fee study period data */
if($searchStudent=='StudyPeriod' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeStudyPeriodTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount =number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');

		$strList .= "<slice title='".$feecycleRecordArray[$i]['periodName']."' description='".$feecycleRecordArray[$i]['studyPeriodId']."~feeStudyPeriod'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeStudyPeriodTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "StudyPeriodPie";
}	

if($searchStudent=='StudyPeriod' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeStudyPeriodTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['periodName']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeStudyPeriodTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "StudyPeriodBar";
}
/* END: function to fetch fee study period data */

/* START: function to fetch fee Hostel data */
if($searchStudent=='Hostel' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeHostelTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount =number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');

		$strList .= "<slice title='".$feecycleRecordArray[$i]['hostelName']."' description='".$feecycleRecordArray[$i]['hostelId']."~feeHostel'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeHostelTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "HostelPie";
}	

if($searchStudent=='Hostel' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeHostelTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['hostelName']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeHostelTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "HostelBar";
}
/* END: function to fetch fee Hostel data */

/* START: function to fetch fee Transport data */
if($searchStudent=='Transport' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeTransportTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount =number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');

		$strList .= "<slice title='".$feecycleRecordArray[$i]['routeCode']."' description='".$feecycleRecordArray[$i]['busRouteId']."~feeTransport'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeTransportTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "TransportPie";
}	

if($searchStudent=='Transport' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeTransportTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['routeCode']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeTransportTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "TransportBar";
}
/* END: function to fetch fee Hostel data */

/* START: function to fetch fee Gender data */
if($searchStudent=='Gender' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeGenderTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount =number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');

		$strList .= "<slice title='".$feecycleRecordArray[$i]['studentGender']."' description='".$feecycleRecordArray[$i]['studentGender']."~feeGender'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeGenderTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "GenderPie";
}	

if($searchStudent=='Gender' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeGenderTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['studentGender']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeGenderTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "GenderBar";
}
/* END: function to fetch fee Gender data */

/* START: function to fetch fee Payment Type data */
if($searchStudent=='PaymentType' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeInstrumentTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount = number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');
		$paymentInstrument = $modeArr[$feecycleRecordArray[$i]['paymentInstrument']];
		$strList .= "<slice title='".$paymentInstrument."' description='".$feecycleRecordArray[$i]['paymentInstrument']."~feeInstrument'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeInstrumentTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "PaymentTypePie";
}	

if($searchStudent=='PaymentType' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeInstrumentTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {
		$paymentInstrument = $modeArr[$feecycleRecordArray[$i]['paymentInstrument']];

		$strList .= "<value xid='".$i."'>".$paymentInstrument."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeInstrumentTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "PaymentTypeBar";
}
/* END: function to fetch fee Payment Type data */

/* START: function to fetch fee Category data */
if($searchStudent=='Category' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeCategoryTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount = number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');
		 
		$strList .= "<slice title='".$feecycleRecordArray[$i]['quotaAbbr']."' description='".$feecycleRecordArray[$i]['quotaId']."~feeCategory'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeCategoryTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "CategoryPie";
}	

if($searchStudent=='Category' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeCategoryTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {
		 

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['quotaAbbr']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeCategoryTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "CategoryBar";
}
/* END: function to fetch fee Category data */

/* START: function to fetch fee City data */
if($searchStudent=='City' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeCityTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount = number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');
		 
		$strList .= "<slice title='".$feecycleRecordArray[$i]['cityCode']."' description='".$feecycleRecordArray[$i]['corrCityId']."~feeCity'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeCityTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "CityPie";
}	

if($searchStudent=='City' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeCityTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {
		 

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['cityCode']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeCityTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "CityBar";
}
/* END: function to fetch fee City data */

/* START: function to fetch fee State data */
if($searchStudent=='State' && $graphType=='PieChart'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeStateTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$totalAmount = number_format($feecycleRecordArray[$i]['totalAmount'], 2, '.', '');
		 
		$strList .= "<slice title='".$feecycleRecordArray[$i]['stateCode']."' description='".$feecycleRecordArray[$i]['corrStateId']."~feeState'>".$totalAmount."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/feeStateTotal.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "StatePie";
}	

if($searchStudent=='State' && $graphType=='BarGraph'){

	$strList = "";     
	$feecycleRecordArray = $feeReportManager->getFeeStateTotal();
	$cnt = count($feecycleRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {
		 

		$strList .= "<value xid='".$i."'>".$feecycleRecordArray[$i]['stateCode']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$feecycleRecordArray[$i]['totalAmount']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/feeStateTotalBarData.xml";
	UtilityManager::writeXML($strList, $xmlFilePath);
	echo "StateBar";
}
/* END: function to fetch fee State data */


//$History: getFeesDetailGraph.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Management
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>