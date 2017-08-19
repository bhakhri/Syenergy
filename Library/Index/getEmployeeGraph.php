<?php
//This file sends the data, creates the image on runtime
//
// Author :Gurkeerat Sidhu
// Created on : 23-11-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/EmployeeManager.inc.php");
$employeeManager = EmployeeManager::getInstance();
//define('MODULE','UploadEmployeeDetail');
//define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);


/* START: function to fetch employee teaching data */

    $strList = "";
    $teachingRecordArray = $employeeManager->getEmployeeTeachingList(" AND isTeaching =1 ");
    $cnt = count($teachingRecordArray);
    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

        $strList .= "<slice title='Teaching Employee' description='1~Teaching'>".$teachingRecordArray[$i]['totalCount']."</slice>\n";
    } 

    $teachingRecordArray = $employeeManager->getEmployeeTeachingList(" AND isTeaching!=1 ");
    $cnt = count($teachingRecordArray);
    for($i=0;$i<$cnt;$i++) {

        $strList .= "<slice title='Non-Teaching Employee' description='0~Teaching'>".$teachingRecordArray[$i]['totalCount']."</slice>\n";
    } 
    $strList .="</pie>";
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/employeeTeachingData.xml";
   UtilityManager::writeXML($strList, $xmlFilePath); 

/* END: function to fetch employee teaching data */



/* START: function to fetch employee city data */


    $strList ="";
    $employeeCityRecordArray = $employeeManager->getEmployeeCityList();
    $cnt = count($employeeCityRecordArray);
    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

        $strList .= "<slice title='".$employeeCityRecordArray[$i]['cityName'].'('.$employeeCityRecordArray[$i]['cityCode'].")' description='".$employeeCityRecordArray[$i]['cityId']."~city'>".$employeeCityRecordArray[$i]['totalCount']."</slice>\n";
    } 
    $strList .="</pie>";
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/employeeCityData.xml";
 UtilityManager::writeXML($strList, $xmlFilePath); 

/* END: function to fetch employee city data */



/* START: function to fetch employee designation data */

    $strList ="";
    $designationRecordArray = $employeeManager->getEmployeeDesignationList();
    $cnt = count($designationRecordArray);
    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

        $strList .= "<slice title='".$designationRecordArray[$i]['designationName']."' description='".$designationRecordArray[$i]['designationId']."~designation'>".$designationRecordArray[$i]['totalCount']."</slice>\n";
    } 
    $strList .="</pie>";
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/employeeDesignationData.xml";
UtilityManager::writeXML($strList, $xmlFilePath); 

/* END: function to fetch employee designation data */

/* START: function to fetch employee branch  data */


    $strList ="";
    $branchRecordArray = $employeeManager->getEmployeeBranchList();
    $cnt = count($branchRecordArray);
    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

        $strList .= "<slice title='".$branchRecordArray[$i]['branchCode'].'-'.$branchRecordArray[$i]['branchName']."' description='".$branchRecordArray[$i]['branchId']."~branch'>".$branchRecordArray[$i]['totalCount']."</slice>\n";
    } 
    $strList .="</pie>";
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/employeeBranchData.xml";
  UtilityManager::writeXML($strList, $xmlFilePath); 

/* END: function to fetch employee branch  data */

/* START: function to fetch employee gender  data */

     
    $strList = "";
    $genderRecordArray = $employeeManager->getEmployeeGenderList(" AND gender ='M'");
    $cnt = count($genderRecordArray);
    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

        $strList .= "<slice title='Male'  description='M~gender'>".$genderRecordArray[$i]['totalCount']."</slice>\n";
    } 

    $genderRecordArray = $employeeManager->getEmployeeGenderList(" AND gender ='F'");
    $cnt = count($genderRecordArray);
    for($i=0;$i<$cnt;$i++) {

        $strList .= "<slice title='Female' description='F~gender'>".$genderRecordArray[$i]['totalCount']."</slice>\n";
    } 

    $strList .="</pie>";
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/employeeGenderData.xml";
 UtilityManager::writeXML($strList, $xmlFilePath); 

/* END: function to fetch employee gender data */
//$History: getEmployeeGraph.php $
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 11/26/09   Time: 1:04p
//Created in $/LeapCC/Library/Index
//added file related to 'employee export/import' module
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>