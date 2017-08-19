 <?php 
//This file is used to download salary sheet as csv.
//
// Author :Abhiraj
// Created on : 05-May-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    $payrollManager = PayrollManager::getInstance();
    
   //$search = $REQUEST_DATA['searchbox'];
    //$conditions = ''; 
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    $valueArray = array();
    $month=trim($REQUEST_DATA['month']);
    $year=trim($REQUEST_DATA['year']);   
    $cnt=count($foundArray);
    global $sessionHandler;
    $headsArray=$sessionHandler->getSessionVariable('selectedHeads');
    $valueArray=$sessionHandler->getSessionVariable('dataArray');
    $valueArray[]=$sessionHandler->getSessionVariable('total');;
    $csvData ='';
    $csvData .=$sessionHandler->getSessionVariable('InstituteName');
    $csvData .="\n";
    $csvData .="Salary Sheet For $month $year";
    $csvData .="\n";
     $csvData .="[+]: Earnings  [-]: Deductions";
    $csvData .="\n\n";
    $csvData .="Sr No.,Name,Code,Department,Designation";
    for($i=0;$i<count($headsArray);$i++)
    {                
        $headAbbr=PayrollManager::getInstance()->getHeadAbbr("where headName like'".$headsArray[$i]."'");
        $headType=PayrollManager::getInstance()->getHeadType("where headName like'".$headsArray[$i]."'");
        if($headType[0]['headType']==0)
        {
           $csvData .=",".trim($headAbbr[0]['headAbbr'])."[+]"; 
        }
        else
        {
           $csvData .=",".trim($headAbbr[0]['headAbbr'])."[-]";    
        }           
    }
    
    $csvData .=",Net";
    $csvData .="\n";
    $recordCount=count($valueArray);
    $headsCount=count($headsArray);
    for($i=0;$i<$recordCount;$i++) {
          $csvData .=  $valueArray[$i]['srNo'].",".$valueArray[$i]['empName'].",".$valueArray[$i]['empCode'].",".$valueArray[$i]['empDepartment'].",".$valueArray[$i]['empDesignation'];
          
          for($j=0;$j<$headsCount+1;$j++)
          {
            $csvData .=",".$valueArray[$i][$j];
          }
           
		  $csvData .= "\n";
  }      
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called SalaryHeadsReport.csv
header('Content-Disposition: attachment;  filename="'.'SalarySheet-'.$month.''.$year.'.csv'.'"');
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
?>