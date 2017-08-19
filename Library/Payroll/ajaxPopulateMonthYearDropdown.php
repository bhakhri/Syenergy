<?php
////  This File populates month and year dropdowns for payroll report
//
// Author :Abhiraj Malhotra
// Created on : 07-May-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['empId'] ) != '' && trim($REQUEST_DATA['txtField'])!="") {
      require_once(MODEL_PATH . "/PayrollManager.inc.php");
      require_once(BL_PATH.'/HtmlFunctions.inc.php');
      $conditions="and employeeCode like '".trim($REQUEST_DATA['empId'] )."'";
      $foundArray = CommonQueryManager::getInstance()->getEmployee('employeeName',$conditions);
      $monthArray=PayrollManager::getInstance()->getMonth('where employeeId='.$foundArray[0]['employeeId']);
      $monthCount=count($monthArray);
      $yearArray=PayrollManager::getInstance()->getYear('where employeeId='.$foundArray[0]['employeeId']);
      $yearCount=count($yearArray);
      $str_year_box='<select name="year" id="year" >';
      $str_year_box .= HtmlFunctions::getInstance()->makeSelectBox($yearArray,'year','year');
      $str_year_box .= '</select>';
      $str_month_box='<select name="month" id="month">';
      $str_month_box .=HtmlFunctions::getInstance()->makeSelectBox($monthArray,'month','month'); 
      $str_month_box .='</select>';
      $valueArray=array('monthHTML'=>$str_month_box,'monthCount'=>$monthCount,'yearHTML'=>$str_year_box,'yearCount'=>$yearCount);
        echo json_encode($valueArray);
	
}
elseif(trim($REQUEST_DATA['empId'] ) == '' && trim($REQUEST_DATA['txtField'])=="")
{
      require_once(MODEL_PATH . "/PayrollManager.inc.php");
      require_once(BL_PATH.'/HtmlFunctions.inc.php');
      $monthArray=PayrollManager::getInstance()->getMonth();
      $monthCount=count($monthArray);
      $yearArray=PayrollManager::getInstance()->getYear();
      $yearCount=count($yearArray);
      $str_year_box='<select name="year" id="year">';
      $str_year_box .= HtmlFunctions::getInstance()->makeSelectBox($yearArray,'year','year');
      $str_year_box .= '</select>';
      $str_month_box='<select name="month" id="month">';
      $str_month_box .=HtmlFunctions::getInstance()->makeSelectBox($monthArray,'month','month'); 
      $str_month_box .='</select>';
      $valueArray=array('monthHTML'=>$str_month_box,'monthCount'=>$monthCount,'yearHTML'=>$str_year_box,'yearCount'=>$yearCount);
    echo json_encode($valueArray);   
}
elseif(trim($REQUEST_DATA['empId'] ) == '' && trim($REQUEST_DATA['txtField'])!="")
{
   echo 0; 
}
else
{
    echo 0;
}

?>