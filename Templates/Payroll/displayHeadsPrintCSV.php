 <?php 
//This file is used to download salary heads as csv.
//
// Author :Abhiraj
// Created on : 13-April-2010
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $payrollManager = PayrollManager::getInstance();
    
   $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE headName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';
    
     $orderBy = " $sortField $sortOrderBy";

	$headsRecordArray = $payrollManager->getHeadList($filter,'',$orderBy);
    
	$recordCount = count($headsRecordArray);

    $valueArray = array();

    $csvData ='';
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox']) && $recordCount>0)
    {
       $csvData .= "Search By : ".$REQUEST_DATA['searchbox'];
       $csvData .="\n"; 
    }
    $csvData .="Sr No.,Head Name,Head Abbr.,Head Type,Deduction Account,Description";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
		  $csvData .= ($i+1).",";
          $dedAccountId=$headsRecordArray[$i]['dedAccountId'];
          $dedAccount=$payrollManager->getDedAccount('where dedAccountId='.$dedAccountId);
          if($dedAccount[0]['accountName']=="")
          {
              $dedAccountArray=array('deductionAccountName'=>'--');
          }
          else
          {
              $dedAccountArray=array('deductionAccountName'=>$dedAccount[0]['accountName'].'('.$dedAccount[0]['accountNumber'].')');
          }
		  $csvData .= $headsRecordArray[$i]['headName'].",";
          $csvData .= $headsRecordArray[$i]['headAbbr'].",";
          if($headsRecordArray[$i]['headType']==1)
          {
              $str="Deduction";
          }
          else
          {
              $str="Earning";
          }
          $csvData .= $str.",";
          $csvData .= $dedAccountArray['deductionAccountName'].",";
		  $csvData .= $headsRecordArray[$i]['headDesc'].","; 
		  $csvData .= "\n";
  }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called SalaryHeadsReport.csv
header('Content-Disposition: attachment;  filename="'.'SalaryHeadsReport.csv'.'"');
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>