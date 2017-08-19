<?php 
//This file is used as printing version for testwise marks report.
//
// Author :Ajinder Singh
// Created on : 14-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentRegistrationReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    //UtilityManager::ifNotLoggedIn();
   // UtilityManager::headerNoCache();
   
     require_once(MODEL_PATH . "/Mentees/RegistrationManager.inc.php");
    $studentRegistrationDetailManager  = StudentRegistrationManager::getInstance();


	require_once(BL_PATH . '/ScReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

$mentorName  =  $REQUEST_DATA['mentorName'];
$rollNo	     =  $REQUEST_DATA['rollNo'];
$sortField   =	$REQUEST_DATA['sortField'];
$sortOrderBy =  $REQUEST_DATA['sortOrderBy'];

$conditions='';
 if($mentorName!=''){
   $conditions=$mentorName;
     } 
  if($rollNo!=''){
      $conditions=$rollno;;
      }


   $studentRegistrationDetailArray=$studentRegistrationDetailManager->getstudentRegistrationDetails($conditions);

//Fetch Report Header Details
  

     $search = 'For <B>Mentor Name:</B>'.$mentorName ;

	 

	if($rollNo!='') {
      $search .= ',&nbsp;<B>Roll No. :</B>'.$rollNo;
    }
	
    

	$search .= ',&nbsp;<B>Sort By :</B>'.$sortField;
//Code End(Print Report Header Details)
$tableData = "<table width='100%' border='1' cellspacing='1' cellpadding='2'  class='reportTableBorder'>
                     <tr>
                      <td width='2%' valign='middle' rowspan='2'><b>&nbsp;#</b></td>
                      <td valign='middle' align='left' rowspan='2' width='8%' class = 'headingFont'><b>Date of Registration</b></td>
		      <td valign='middle' align='left' rowspan='2' width='8%' class = 'headingFont'><b>Roll No.</b></td>
                      <td valign='middle' align='left' rowspan='2' width='8%' class = 'headingFont'><b>Student Name</b></td>
                      <td valign='middle' align='left' rowspan='2' width='8%' class = 'headingFont'><b>Father's Name</b></td>
                      <td valign='middle' align='left' rowspan='2' width='8%' class = 'headingFont'><b> Student's Mobile No.</b></td>
                      <td valign='middle' align='left' rowspan='2' width='15%' class = 'headingFont'><b>Mentor Name</b></td></tr></table>";
                                     

reportGenerate($tableData,$search);


die;
   // Report generate
    function reportGenerate($value,$heading) {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('Student Registration Detail Report');
        $reportManager->setReportInformation("$heading");     
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
            <tr>
            <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
            <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
            <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                </tr>
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                </tr>
            </table>
            </td>
            </tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>  align="center"><?php echo $reportManager->getReportInformation(); ?></th></tr>
            </table> <br>
            <table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
            <tr>
            <td valign="top">
            <?php
	        for($i=0;$i<count($value);$i++) {
	           echo trim($value[$i]); 
	        }
	    ?>        
            </td>
            </tr> 
            </table>       
            <br>
            <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <br class='page'> 
        </div>    
<?php        
    }
?>





	
	

?>
