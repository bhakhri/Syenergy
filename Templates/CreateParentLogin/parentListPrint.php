 <?php 
//This file is used as printing version for display attendance report in parent module.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

/*
<script language="javascript">
   var pars = window.opener.generateQueryString('allDetailsForm');
   alert(pars);   
</script>
*/
?>

<?php

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CreateParentLogin');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

 
    $fIds = add_slashes($REQUEST_DATA['fIds']);
    $mIds = add_slashes($REQUEST_DATA['mIds']);
    $gIds = add_slashes($REQUEST_DATA['gIds']);
    
    $checkValue=add_slashes($REQUEST_DATA['checkValue']);                                            
    $check=add_slashes($REQUEST_DATA['check']);

    $fcheck = add_slashes($REQUEST_DATA['fcheckbox']);
    $mcheck = add_slashes($REQUEST_DATA['mcheckbox']);
    $gcheck = add_slashes($REQUEST_DATA['gcheckbox']);
 
    $temp=0;
 
 
 // Sorting Order
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy1'])) ? $REQUEST_DATA['sortOrderBy1'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField1'])) ? $REQUEST_DATA['sortField1'] : 'firstName';
    $orderBy=" $sortField $sortOrderBy"; 
   
   
  
    require_once(TEMPLATES_PATH . "/CreateParentLogin/parentListTemplate.php");         
    $tableData1 = $tableData;

        
    $chk="0";
    $td=0;
    if($fcheck==1) {
       $conditions = " AND fatherUserId IN (".$fIds.")";
       $foundArray = $studentManager->getStudentList($conditions,'');
       if(count($foundArray) > 0) {
         
         $str ="<h3 align='center'>Father's Login List</h3>
                <table border='0px' cellpadding='0px' cellspacing='3px' align='center' >";  
         $temp = 1;
         $td = 0;
         for($i=0; $i<count($foundArray); $i++) {
           $chk="0";  
           $tableData1 = $tableData;  
           if($checkValue==1) {
              $f = $foundArray[$i]['fatherName']; 
              $yr = trim(substr($foundArray[$i]['DOB'],2,2)); 
              $userPass1 = substr($f,0,stripos($f," "));
              if($userPass1!="") {
                 $f = $userPass1;  
              }
              $pass = strtolower(trim($f).trim($yr));
           }
           else {
             $pass = $check;  
           }  
           $tableData1 = str_replace("<username>",$foundArray[$i]['fatherUserName'],$tableData1); 
           $tableData1 = str_replace("<password>",$pass,$tableData1); 
           $tableData1 = str_replace("<rollno>",$foundArray[$i]['rollNo'],$tableData1); 
           $tableData1 = str_replace("<studentName>",$foundArray[$i]['firstName'],$tableData1); 
           $tableData1 = str_replace("<className>",$foundArray[$i]['className'],$tableData1); 
           
           if($td==0) { 
              $str .= "<tr><td width='250' valign='top' align='left'>".$tableData1."</td>";
              $td=1;                                               
           }
           else {
              $str .= "<td width='250' valign='top' align='left' style='padding-left:10px;'>".$tableData1."</td></tr>
                      <tr><td width='250' valign='top' align='left' >&nbsp;</td>
                      <td width='250' valign='top' align='left' ></td></tr>"; 
              $td=0; 
           }
           echo $str;
           $str="";
           
           if(($i+1)%12==0) {
             $td=0;  
             $str = "</table>
                        <br class='page'>
                            <h3 align='center'>Father's Login List</h3>
                            <table border='0px' cellpadding='0px' cellspacing='3px' align='center' >";  
             $chk="";               
             //echo $str;
           }   
         }
      }
    }

    if($chk != "") {
      echo "<td width='350'>&nbsp;</td></tr></table><br class='page'>";  
    }
    
    
    $chk="0";
    $td=0;
    if($mcheck==1) {
       $conditions = " AND motherUserId IN (".$mIds.")";
       $foundArray = $studentManager->getStudentList($conditions,'');
       if(count($foundArray) > 0) {
         
         $str ="<h3 align='center'>Mother's Login List</h3>
                <table border='0px' cellpadding='0px' cellspacing='3px' align='center' >";  
         $temp = 1;
         $td = 0;
         for($i=0; $i<count($foundArray); $i++) {
           $chk="0";  
           $tableData1 = $tableData;  
           if($checkValue==1) {
              $f = $foundArray[$i]['motherName']; 
              $yr = trim(substr($foundArray[$i]['DOB'],2,2)); 
              $userPass1 = substr($f,0,stripos($f," "));
              if($userPass1!="") {
                 $f = $userPass1;  
              }
              $pass = strtolower(trim($f).trim($yr));
           }
           else {
             $pass = $check;  
           }  
           $tableData1 = str_replace("<username>",$foundArray[$i]['motherUserName'],$tableData1); 
           $tableData1 = str_replace("<password>",$pass,$tableData1); 
           $tableData1 = str_replace("<rollno>",$foundArray[$i]['rollNo'],$tableData1); 
           $tableData1 = str_replace("<studentName>",$foundArray[$i]['firstName'],$tableData1); 
           $tableData1 = str_replace("<className>",$foundArray[$i]['className'],$tableData1); 
           
           if($td==0) { 
              $str .= "<tr><td width='250' valign='top' align='left'>".$tableData1."</td>";
              $td=1;                                               
           }
           else {
              $str .= "<td width='250' valign='top' align='left' style='padding-left:10px;'>".$tableData1."</td></tr>
                      <tr><td width='250' valign='top' align='left' >&nbsp;</td>
                      <td width='250' valign='top' align='left' ></td></tr>"; 
              $td=0; 
           }
           echo $str;
           $str="";
           
           if(($i+1)%12==0) {
             $td=0;  
             $str = "</table>
                        <br class='page'>
                            <h3 align='center'>Mother's Login List</h3>
                            <table border='0px' cellpadding='0px' cellspacing='3px' align='center' >";  
             $chk="";               
             //echo $str;
           }   
         }
      }
    }

    if($chk != "") {
      echo "<td width='350'>&nbsp;</td></tr></table><br class='page'>";  
    }
    
 
    $chk="0";
    $td=0;
    if($gcheck==1) {
       $conditions = " AND guaridanUserId IN (".$gIds.")";
       $foundArray = $studentManager->getStudentList($conditions,'');
       if(count($foundArray) > 0) {
         $str ="<h3 align='center'>Guaridan's Login List</h3>
                <table border='0px' cellpadding='0px' cellspacing='3px' align='center' >";  
         $temp = 1;
         $td = 0;
         for($i=0; $i<count($foundArray); $i++) {
           $chk="0";  
           $tableData1 = $tableData;  
           if($checkValue==1) {
              $f = $foundArray[$i]['guardianName']; 
              $yr = trim(substr($foundArray[$i]['DOB'],2,2)); 
              $userPass1 = substr($f,0,stripos($f," "));
              if($userPass1!="") {
                 $f = $userPass1;  
              }
              $pass = strtolower(trim($f).trim($yr));
           }
           else {
             $pass = $check;  
           }  
           $tableData1 = str_replace("<username>",$foundArray[$i]['guardianUserName'],$tableData1); 
           $tableData1 = str_replace("<password>",$pass,$tableData1); 
           $tableData1 = str_replace("<rollno>",$foundArray[$i]['rollNo'],$tableData1); 
           $tableData1 = str_replace("<studentName>",$foundArray[$i]['firstName'],$tableData1); 
           $tableData1 = str_replace("<className>",$foundArray[$i]['className'],$tableData1); 
           
           if($td==0) { 
              $str .= "<tr><td width='250' valign='top' align='left'>".$tableData1."</td>";
              $td=1;                                               
           }
           else {
              $str .= "<td width='250' valign='top' align='left' style='padding-left:10px;'>".$tableData1."</td></tr>
                      <tr><td width='250' valign='top' align='left' >&nbsp;</td>
                      <td width='250' valign='top' align='left' ></td></tr>"; 
              $td=0; 
           }
           echo $str;
           $str="";
           
           if(($i+1)%12==0) {
             $td=0;  
             $str = "</table>
                        <br class='page'>
                            <h3 align='center'>Guardian's Login List</h3>
                            <table border='0px' cellpadding='0px' cellspacing='3px' align='center' >";  
             $chk="";               
             //echo $str;
           }   
         }
      }
    }

    if($chk != "") {
      echo "<td width='350'>&nbsp;</td></tr></table><br class='page'>";  
    }
    
 
    if($temp==0) {
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(800); 
        $reportManager->setReportHeading('Parent Login List');
        //$reportManager->setReportInformation("For ".$studentName." As On $formattedDate ");
   ?>
        <table border="0" align="center" cellspacing="0" cellpadding="0" width="<?php echo $reportManager->reportWidth?>">
            <tr>
                <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
                <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
                <td align="right" colspan="1" width="25%" class="">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo $formattedDate ;?></td>
                        </tr>
                        <tr>
                            <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><th colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
            <tr><th colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>>No record found</th></tr>
        </table>  
        
        <table border='0' align="center" cellspacing='0' cellpadding='0' width="<?php echo $reportManager->reportWidth ?>">
            <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> ><?php echo $reportManager->showFooter(); ?></td>
                <td height="30px"></td>
            </tr>
        </table>        
<?php
    }
?>
<?php 
//$History: parentListPrint.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/21/09    Time: 1:15p
//Updated in $/LeapCC/Templates/CreateParentLogin
//Resolved the sorting, conditions, alignment issues updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/18/09    Time: 10:53a
//Updated in $/LeapCC/Templates/CreateParentLogin
//sorting & validations updated & CSV file created
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/17/09    Time: 11:26a
//Created in $/LeapCC/Templates/CreateParentLogin
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/31/09    Time: 6:41p
//Updated in $/Leap/Source/Templates/CreateParentLogin
//template layout update (parent letterwise format update)
//
?>