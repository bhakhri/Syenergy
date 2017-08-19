 <?php 
//This file is used as printing version for display attendance report in parent module.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<script language="javascript">
   var pars = window.opener.generateQueryString('allDetailsForm');
   //alert(pars);   
</script>
<?php
    set_time_limit(0);  
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

    require_once(MODEL_PATH . "/InstituteManager.inc.php");       
    $instituteManager = InstituteManager::getInstance();  
    
    // Institute Informations 

    $condition = " AND instituteId=".$sessionHandler->getSessionVariable('InstituteId');
    $instRecordArray = $instituteManager->getInstituteList($condition); 
    
    $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"valign=\"middle\" >";    
    if(count($instRecordArray) > 0) {
       $fileName = IMG_PATH."/Institutes/".$instRecordArray[0]['instituteLogo']; 
       if(file_exists($fileName)) {
          $insLogo = '<img name="logo" src="'.IMG_HTTP_PATH.'/Institutes/'.$instRecordArray[0]['instituteLogo'].'" border="0" width="170px" />';
       }                                                                                                                                
    }
    
    $fIds = add_slashes($REQUEST_DATA['fIds']);
    $mIds = add_slashes($REQUEST_DATA['mIds']);
    $gIds = add_slashes($REQUEST_DATA['gIds']);
    
    $checkValue=add_slashes($REQUEST_DATA['checkValue']);                                            
    $check=add_slashes($REQUEST_DATA['check1']);

    $studentIdNotPassword = add_slashes($REQUEST_DATA['studentNotIds']);  
    
    $fcheck = add_slashes($REQUEST_DATA['fcheckbox']);
    $mcheck = add_slashes($REQUEST_DATA['mcheckbox']);
    $gcheck = add_slashes($REQUEST_DATA['gcheckbox']);
    
    $authorizedName = add_slashes($REQUEST_DATA['authorizedName']);
    $designation = add_slashes($REQUEST_DATA['designation']);

 // Sorting Order
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy1'])) ? $REQUEST_DATA['sortOrderBy1'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField1'])) ? $REQUEST_DATA['sortField1'] : 'firstName';
    $orderBy=" $sortField $sortOrderBy"; 
   
   
   // Student not login create
   if($studentIdNotPassword!="") {
       $conditions = " AND a.studentId IN (".$studentIdNotPassword.")";
       $foundArray = $studentManager->getStudentList($conditions,'',$orderBy);
      
       if(count($foundArray)>0 ){
          for($i=0; $i<count($foundArray); $i++) {   
              $foundArray[$i]['srNo'] = ($i+1);
              if($foundArray[$i]['rollNo']=="") {
                 $rollNo = NOT_APPLICABLE_STRING;
                 $foundArray[$i]['missingFormat'] = "Roll No. doesn't exist";
              }
              if($foundArray[$i]['DOB']=='0000-00-00') {
                 $dob = NOT_APPLICABLE_STRING; 
                 $foundArray[$i]['missingFormat'] = "Incorrect date format";
              }
              if($foundArray[$i]['fatherName']==NOT_APPLICABLE_STRING) {
                 $fatherName = NOT_APPLICABLE_STRING; 
                 $foundArray[$i]['missingFormat'] = "Father's Name does not exist";
              }
              if($foundArray[$i]['motherName']==NOT_APPLICABLE_STRING) {
                 $motherName= NOT_APPLICABLE_STRING; 
                 $foundArray[$i]['missingFormat'] = "Mother's Name does not exist";
              }
              if($foundArray[$i]['guardianName']==NOT_APPLICABLE_STRING) {
                 $guardianName =NOT_APPLICABLE_STRING; 
                 $foundArray[$i]['missingFormat'] = "Guardian's Name does not exist";
              }
          }
          $reportManager->setReportWidth(800);
          $reportManager->setReportHeading("Login for Parents of following students could not be generated due to missing information");
        //$reportManager->setReportInformation("SearchBy: $search");

            $reportTableHead                     =    array();
            //associated key                  col.label,            col. width,      data align    
            $reportTableHead['srNo']             =   array('#','width="3%"', "align='center'");
            $reportTableHead['rollNo']           =   array('Roll No.','width=10% align="left"','align="left"'); 
            $reportTableHead['firstName']        =   array('Student Name','width=20% align="left"','align="left"');
            $reportTableHead['className']        =   array('Class','width=20% align="left"','align="left"');
            $reportTableHead['missingFormat']    =   array('Missing Information','width=20% align="left"','align="left"');
            
            $reportManager->setRecordsPerPage(30);
            $reportManager->setReportData($reportTableHead, $foundArray);
            $reportManager->showReport();
            echo "<br class='page'>";
      }
   } 
   
      
  
    require_once(TEMPLATES_PATH . "/CreateParentLogin/parentAddressTemplate.php");         
    $contents = $contentHead;

    $temp = 0;
    
    if($fcheck==1) {
       if($fIds!='') { 
           $conditions = " AND a.studentId IN (".$fIds.")";
           $foundArray = $studentManager->getStudentList($conditions,'');
           if(count($foundArray) > 0) {
             $temp = 1;  
             $br="";
             for($i=0; $i<count($foundArray); $i++) {
               $contents = $contentHead;    
               if($i>0) {
                 $br = "<br class='page'>";  
               }
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
               $contents = str_replace("<CollegeLogo>",$insLogo,$contents); 
               $contents = str_replace("<ParentsName>",$foundArray[$i]['fatherName'],$contents); 
               $contents = str_replace("<Address>",$foundArray[$i]['corrAddress1'],$contents); 
               $contents = str_replace("<username>",$foundArray[$i]['fatherUserName'],$contents); 
               $contents = str_replace("<password>",$pass,$contents); 
               $contents = str_replace("<rollno>",$foundArray[$i]['rollNo'],$contents); 
               $contents = str_replace("<studentName>",$foundArray[$i]['firstName'],$contents); 
               $contents = str_replace("<className>",$foundArray[$i]['className'],$contents); 
               $contents = str_replace("<loginDetail>","Father's Login Details",$contents); 
               $contents = str_replace("<authorizedName>",$authorizedName,$contents); 
               $contents = str_replace("<designation>",$designation,$contents); 
               
               echo $contents.$br;
             }
          }
       }
    }


    if($mcheck==1) {
       if($mIds!='') {
           $conditions = " AND a.studentId IN (".$mIds.")";
           $foundArray = $studentManager->getStudentList($conditions,'');
           if(count($foundArray) > 0) {
             $temp = 1;    
             $br="";
             for($i=0; $i<count($foundArray); $i++) {
               $contents = $contentHead;       
               if($i>0) {
                 $br = "<br class='page'>";  
               }
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
               $contents = str_replace("<CollegeLogo>",$insLogo,$contents);   
               $contents = str_replace("<ParentsName>",$foundArray[$i]['motherName'],$contents); 
               $contents = str_replace("<Address>",$foundArray[$i]['corrAddress1'],$contents); 
               $contents = str_replace("<username>",$foundArray[$i]['motherUserName'],$contents); 
               $contents = str_replace("<password>",$pass,$contents); 
               $contents = str_replace("<rollno>",$foundArray[$i]['rollNo'],$contents); 
               $contents = str_replace("<studentName>",$foundArray[$i]['firstName'],$contents); 
               $contents = str_replace("<className>",$foundArray[$i]['className'],$contents); 
               $contents = str_replace("<loginDetail>","Mother's Login Details",$contents); 
              
               $contents = str_replace("<authorizedName>",$authorizedName,$contents); 
               $contents = str_replace("<designation>",$designation,$contents); 
               echo $contents.$br;
             }
          }
       }
    }

    if($gcheck==1) {
      if($gIds!='') {  
           $conditions = " AND a.studentId IN (".$gIds.")";
           $foundArray = $studentManager->getStudentList($conditions,'');
           if(count($foundArray) > 0) {
             $temp = 1;    
             $br="";
             for($i=0; $i<count($foundArray); $i++) {
               $contents = $contentHead;       
               if($i>0) {
                 $br = "<br class='page'>";  
               }
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
               $contents = str_replace("<CollegeLogo>",$insLogo,$contents);   
               $contents = str_replace("<ParentsName>",$foundArray[$i]['guardianName'],$contents); 
               $contents = str_replace("<Address>",$foundArray[$i]['corrAddress1'],$contents); 
               $contents = str_replace("<username>",$foundArray[$i]['guardianUserName'],$contents); 
               $contents = str_replace("<password>",$pass,$contents); 
               $contents = str_replace("<rollno>",$foundArray[$i]['rollNo'],$contents); 
               $contents = str_replace("<studentName>",$foundArray[$i]['firstName'],$contents); 
               $contents = str_replace("<className>",$foundArray[$i]['className'],$contents); 
               $contents = str_replace("<loginDetail>","Guardian's Login Details",$contents); 
              
               $contents = str_replace("<authorizedName>",$authorizedName,$contents); 
               $contents = str_replace("<designation>",$designation,$contents); 
               echo $contents.$br;  
             }
           }
       }
    }
 
    if($temp==0) {
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(780); 
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
    
?>