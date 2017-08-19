<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(INVENTORY_MODEL_PATH . "/OrderManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $orderManager = OrderManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $orderId =add_slashes(trim($REQUEST_DATA['orderId']));
    if($orderId==''){
        $orderId=-1;
    }
    
    $recordArray = $orderManager->getOrderDetails(' AND io.orderId="'.add_slashes(trim($REQUEST_DATA['orderId'])).'"' );
    if(is_array($recordArray) and count($recordArray)>0){
     //get the supplier name
	 $supplierArray=CommonQueryManager::getInstance()->getSupplier(' supplierCode',' WHERE supplierId='.$recordArray[0]['supplierId']);
     if(is_array($supplierArray) and count($supplierArray)>0){
       $supplierCode=$supplierArray[0]['supplierCode'];
     }
     else{
         $supplierCode=NOT_APPLICABLE_STRING;
     }
    }

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }
    
    if($recordArray[0]['dispatched']==1){
        $dispatched='Yes';
        $dispatchDate=UtilityManager::formatDate($recordArray[0]['dispatchDate']);
    }
    else{
        $dispatched='No';
        $dispatchDate=NOT_APPLICABLE_STRING;
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Order Detail ' );
	$customTable =  '<table border="0" cellspacing="0" class="reportTableBorder" width="665">
                     <tr>
                       <td '.$reportManager->getReportDataStyle().'><b>Order No.</b></td>
                       <td '.$reportManager->getReportDataStyle().'>:</td>
                       <td '.$reportManager->getReportDataStyle().'>'.$recordArray[0]['orderNo'].'</td>
                     </tr>  
                     <tr>
                      <td '.$reportManager->getReportDataStyle().'><b>Order Date</b></td>
                      <td '.$reportManager->getReportDataStyle().'>:</td>
                      <td '.$reportManager->getReportDataStyle().'>'.UtilityManager::formatDate($recordArray[0]['orderDate']).'</td>
                      <td '.$reportManager->getReportDataStyle().'><b>Dispatched</b></td>
                      <td '.$reportManager->getReportDataStyle().'>:</td>
                      <td '.$reportManager->getReportDataStyle().'>'.$dispatched.'</td>
                      </tr>
                      <tr>
                      <td '.$reportManager->getReportDataStyle().'><b>Supplier</b></td>
                      <td '.$reportManager->getReportDataStyle().'>:</td>
                      <td '.$reportManager->getReportDataStyle().'>'.$supplierCode.'</td>
                      <td '.$reportManager->getReportDataStyle().'><b>Dispatch Date</b></td>
                      <td '.$reportManager->getReportDataStyle().'>:</td>
                      <td '.$reportManager->getReportDataStyle().'>'.$dispatchDate.'</td>
                      </tr>
                     </table>'; 
    
    $reportManager->setCustomTable($customTable);
	$reportTableHead						=	array();
	$reportTableHead['srNo']                =   array('#','width="1%" align="left"', "align='left' ");
    $reportTableHead['itemCode']            =   array('Item Code','width=15% align="left"', 'align="left"');
    $reportTableHead['itemName']            =   array('Item Name','width=20% align="left"', 'align="left"');
    $reportTableHead['quantity']            =   array('Quantity','width="12%" align="right" ','align="right"');
    $reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showInventoryReport();

// $History: orderPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/09/09   Time: 10:56
//Updated in $/Leap/Source/Templates/INVENTORY/OrderMaster
//Corrected add/edit code during order no entry and corrected interface
//path in print file
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 3/09/09    Time: 12:40
//Created in $/Leap/Source/Templates/INVENTORY/OrderMaster
//Moved Inventory Management Files to INVENTORY folder
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/09/09    Time: 18:47
//Created in $/Leap/Source/Templates/OrderMaster
//Added files for "Order Master" module

?>