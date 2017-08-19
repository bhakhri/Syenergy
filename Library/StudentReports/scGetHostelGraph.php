<?php
//This file sends the data, creates the image on runtime
//
// Author :Rajeev Aggarwal
// Created on : 17-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$reportManager = StudentReportsManager::getInstance();

$reportType = $REQUEST_DATA['reportType'];

if($reportType == 1){
	
	$foundArray = $reportManager->getProgrammeWiseHostel($condition);		
	$cnt = count($foundArray);
	$strList ='';
	$dataValue = 0;
	if($cnt){

        //$degreeIdsArray= array_unique(explode(',',UtilityManager::makeCSList($foundArray,'degreeId')));
		$strList ="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$strList .="<series>\n\t";
		$femaleArr = array();
		$maleArr = array();
        $degreeArray=array();
        $degreeStr='';
        $maleStr='';
        $femaleStr='';
        $k=0;
        $male = 0;
        $female = 0;
		$maleCnt=0;
		$femaleCnt=0;


        for($i=0;$i<$cnt;$i++) {
	   $id = $foundArray[$i]['branchId']."~".$foundArray[$i]['degreeId']; 	
           if(!in_array($id ,$degreeArray)){
             $degreeArray[]=$id;
             $degreeStr .="<value xid='".$k."'>".$foundArray[$i]['degreeCode']." - ".$foundArray[$i]['branchCode']."</value>\n\t";
             $k++;
           }
           if($foundArray[$i]['studentGender']=='M'){
              $maleStr .="\n\t\t<value xid='".($k-1)."' url='javascript:showData(\"".$foundArray[$i]['hostelId']."\",\"".$foundArray[$i]['degreeId']."\",\"".$foundArray[$i]['branchId']."\",\"M\")'>".$foundArray[$i]['totalRecords']."</value>";
               $male++;
	       $maleCnt += $foundArray[$i]['totalRecords'];
            }
            if($foundArray[$i]['studentGender']=='F'){
               $femaleStr .="\n\t\t<value xid='".($k-1)."' url='javascript:showData(\"".$foundArray[$i]['hostelId']."\",\"".$foundArray[$i]['degreeId']."\",\"".$foundArray[$i]['branchId']."\",\"F\")'>".$foundArray[$i]['totalRecords']."</value>"; 
               $female++;
	       $femaleCnt += $foundArray[$i]['totalRecords'];
            }
        }
	
        //fill up remaing string
        for(;$male<$k;$male++){
           $maleStr .="\n\t\t<value xid='".$male."'>0</value>"; 
        }
        //fill up remaing string
        for(;$female<$k;$female++){
           $femaleStr .="\n\t\t<value xid='".$female."'>0</value>"; 
        }
        
        $xmlString=$strList.$degreeStr."\n</series>\n<graphs>\n\t<graph gid='Male' title='Male(Total : ".$maleCnt.")'>".$maleStr."\n\t</graph>\n\n\t<graph gid='Female' title='Female(Total : ".$femaleCnt.")'>".$femaleStr."\n\t</graph>\n\n</graphs>\n</chart>";
    }
    else{
        $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
        $dataValue = 1;
    }
  
    
        /*
		for($i=0;$i<$cnt;$i++) {
			$strList .="<value xid='".$i."'>".$foundArray[$i]['degreeCode'].'-'.$foundArray[$i]['branchCode']."</value>\n\t";
			if($foundArray[$i]['studentGender']=='F'){
			  $femaleArr[$foundArray[$i]['degreeId'].'~'.$foundArray[$i]['branchId']]=$foundArray[$i]['totalRecords'];
			}
			if($foundArray[$i]['studentGender']=='M'){
  			  $maleArr[$foundArray[$i]['degreeId'].'~'.$foundArray[$i]['branchId']]=$foundArray[$i]['totalRecords'];
			}
		} 
		$strList .="\n</series>\n<graphs>";
		
		 
		$strList .="\n\t<graph gid='Male' title='Male'>";
		for($i=0;$i<$cnt;$i++) {
			$maleVariable = $foundArray[$i]['degreeId'].'~'.$foundArray[$i]['branchId'];
			if($maleArr[$maleVariable]){
				$strList .= "\n\t\t<value xid='".$i."' url='javascript:showData(\"".$foundArray[$i]['hostelId']."\",\"".$foundArray[$i]['degreeId']."\",\"".$foundArray[$i]['branchId']."\",\"M\")'>".$maleArr[$maleVariable]."</value>";
			}else{
				$strList .= "\n\t\t<value xid='".$i."' url='javascript:showData(\"".$foundArray[$i]['hostelId']."\",\"".$foundArray[$i]['degreeId']."\",\"".$foundArray[$i]['branchId']."\",\"M\")'>0</value>";
			}
		} 
		$strList .="\n\t</graph>\n";
		

		$strList .="\n\t<graph gid='Female' title='Female'>";
		for($i=0;$i<$cnt;$i++) {
			$femaleVariable = $foundArray[$i]['degreeId'].'~'.$foundArray[$i]['branchId'];
			if($femaleArr[$femaleVariable]){
				$strList .= "\n\t\t<value xid='".$i."' url='javascript:showData(\"".$foundArray[$i]['hostelId']."\",\"".$foundArray[$i]['degreeId']."\",\"".$foundArray[$i]['branchId']."\",\"F\")'>".$femaleArr[$femaleVariable]."</value>";
			}else{
				$strList .= "\n\t\t<value xid='".$i."' url='javascript:showData(\"".$foundArray[$i]['hostelId']."\",\"".$foundArray[$i]['degreeId']."\",\"".$foundArray[$i]['branchId']."\",\"F\")'>0</value>";
			}
		} 
		$strList .="\n\t</graph>\n";

		$strList .="\n</graphs>\n</chart>";
	}
	else{
	
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
		$dataValue = 1;
	}
    */
	$xmlFilePath = TEMPLATES_PATH."/Xml/hostelStackData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $xmlString) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open user activity data xml file");
	}
	echo SUCCESS.'~'.$dataValue;
}
//$History: scGetHostelGraph.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/21/09    Time: 3:43p
//Updated in $/LeapCC/Library/StudentReports
//called UtilityManager file.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/04/09    Time: 5:06p
//Created in $/LeapCC/Library/StudentReports
//intial checkin for programme wise gender wise hostel detail
?>
