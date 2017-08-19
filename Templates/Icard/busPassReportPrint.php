<?php 
    //This file is used as printing version for student ICard.
    //
    // Author :Parveen Sharma
    // Created on : 26-12-2008
    // Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/InstituteManager.inc.php");

    global $sessionHandler;    

    $cardView = $REQUEST_DATA['cardView'];          
    
    $instituteManager = InstituteManager::getInstance(); 
    $commonQueryManager = CommonQueryManager::getInstance();

    $studentReportsManager = StudentReportsManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'regNo';
    
    $orderBy = "$sortField $sortOrderBy";
    
    $student = $REQUEST_DATA['student'];
    $classId = $REQUEST_DATA['classId'];
    
    if($student=='') {
      $student=0;  
    }
    
    if($classId=='') {
      $classId=0;  
    }
    
    $conditions = "AND a.studentId IN ($student) AND a.classId IN ($classId) 
                   AND IFNULL(bpass.busStopId,0) != '0' AND IFNULL(bpass.busRouteId,0) != '0'";
                   
    $studentRecordArray = $studentReportsManager->getStudentICardDetails($conditions, $orderBy, $limit='');
    
    $cnt = count($studentRecordArray);
    if($cnt==0)  { 
?>    <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
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
        <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Student Bus Pass Report</th></tr>
        </table> <br> 
        <table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
        <tr>
            <td valign="top">
                <div class="dataFont" align="center"><b>No record found</b></div>
            </td>
        </tr>
       </table><br><br>     
       <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
       <tr>
        <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
    </tr>
    </table>
<?php       
       die;
    }
    else  {
      
        if($cardView==1) {     // Bus Pass
           require_once(TEMPLATES_PATH . "/Icard/busPassTemplate.php");   
        }
        
        for($i=0;$i<$cnt;$i++) {
           
            $busPassLogo = IMG_PATH."/BusPass/".$sessionHandler->getSessionVariable('BUS_PASS_LOGO');    
            if(file_exists($busPassLogo)) {
                $fileName = IMG_HTTP_PATH."/BusPass/".$sessionHandler->getSessionVariable('BUS_PASS_LOGO'); 
                $insLogo = "<img  src=\"".$fileName." \"height=\"20px\" \"width=\"65px\" valign=\"top\" >";
            }
            else {
                $insAddress  = $studentRecordArray[0]['instituteAddress1'].' '.$studentRecordArray[0]['instituteAddress2'].' '.$studentRecordArray[0]['pin'].'<br>'.$studentRecordArray[0]['insPhone'];
                $insWebSite  = $studentRecordArray[0]['instituteWebsite'];
                $insEmail   = $studentRecordArray[0]['instituteEmail'];   
                $fileName = IMG_PATH."/Institutes/".$studentRecordArray[0]['instituteLogo']; 
                $insLogo='';
                if($instRecordArray[0]['instituteLogo']=='') {
                   $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"20px\" width=\"65px\" \"valign=\"top\" >";  
                }
                else 
                if(file_exists($fileName)) {
                   $insLogo = "<img  src=\"".IMG_HTTP_PATH."/Institutes/".$studentRecordArray[0]['instituteLogo']." \"height=\"20px\" \"width=\"65px\" valign=\"top\" >";
                }
                else {
                   $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"20px\" width=\"65px\" \"valign=\"top\" >";  
                }
            }  

            $className = $studentRecordArray[$i]['className'];
            $icardData1 = $icardData;                                    
            
            // Bus Pass Serial No.
            //$busPassSrNo = str_pad($studentRecordArray[$i]['busPassId'],4,0,STR_PAD_LEFT);
            $busPassSrNo = str_pad($studentRecordArray[$i]['studentId'],4,0,STR_PAD_LEFT);
            $icardData1 = str_replace("<StudentId>","B".$busPassSrNo,$icardData1);      
            $icardData1 = str_replace("<HEADING1>",$heading1,$icardData1);      
            $icardData1 = str_replace("<HEADING2>",$heading2,$icardData1);
            $icardData1 = str_replace("<INSTLOGO>",$insLogo,$icardData1);
            $icardData1 = str_replace("<EMAILADDRESS>",$insEmail,$icardData1);
             
            $icardData1 = str_replace("<RECEIPTNO>",$studentRecordArray[$i]['receiptNo'],$icardData1);
            
            if($studentRecordArray[$i]['validUpto']=='' || $studentRecordArray[$i]['validUpto']==NOT_APPLICABLE_STRING)  {
              $icardData1 = str_replace("<VALIDITY>",NOT_APPLICABLE_STRING,$icardData1);  
            }
            else {
              $icardData1 = str_replace("<VALIDITY>",UtilityManager::formatDate($studentRecordArray[$i]['validUpto']),$icardData1);  
            }
            
            $icardData1 = str_replace("<StudentRegNo>",$studentRecordArray[$i]['regNo'],$icardData1);      
            
            $icardData1 = str_replace("<COLLEGEADDRESS>",$insAddress,$icardData1);
            
            global $bloodResults;
            $blood = $bloodResults[$studentRecordArray[$i]['studentBloodGroup']]; 
            if($blood=="") {
              $blood=NOT_APPLICABLE_STRING;  
            }
            
            $icardData1 = str_replace("<StudentBloodGroup>",$blood,$icardData1);   
            $icardData1 = str_replace("<StudentName>",$studentRecordArray[$i]['studentName'],$icardData1);
            //$icardData1 = str_replace("<StudentRollNo>",$studentRecordArray[$i]['rollNo'],$icardData1);
            $icardData1 = str_replace("<FatherName>",$studentRecordArray[$i]['fatherName'],$icardData1);
            $icardData1 = str_replace("<DOB>",$studentRecordArray[$i]['DOB'],$icardData1);
            $icardData1 = str_replace("<Course>",$className,$icardData1);
            $icardData1 = str_replace("<StudentContact>",$studentRecordArray[$i]['studentMobileNo'],$icardData1);
            $icardData1 = str_replace("<StudentAddress>",$studentRecordArray[$i]['corrAddress'],$icardData1);
            $icardData1 = str_replace("<StudentSession>",$studentRecordArray[$i]['studentSession'],$icardData1);
            $icardData1 = str_replace("<instituteName>",$studentRecordArray[$i]['instituteName'],$icardData1);
            if($studentRecordArray[$i]['DOB']!='') {
                $icardData1 = str_replace("<StudentDOB>",UtilityManager::formatDate($studentRecordArray[$i]['DOB']),$icardData1);
            }
            else {
                $icardData1 = str_replace("<StudentDOB>",NOT_APPLICABLE_STRING,$icardData1);
            }
            
            if($studentRecordArray[$i]['routeCode']!='') {
               $str = $studentRecordArray[$i]['routeCode'];
            }
            else {
               $str = NOT_APPLICABLE_STRING;
            }
            if($studentRecordArray[$i]['busNo']!='') {
              $str .= "&nbsp;(".$studentRecordArray[$i]['busNo'].")";     
            }
            
            $icardData1 = str_replace("<ROUTENO>",$str,$icardData1);
            
            
            if($studentRecordArray[$i]['stopName']!='') {
               $icardData1 = str_replace("<STOPPAGE>",$studentRecordArray[$i]['stopName'],$icardData1);     
            }
            else {
               $icardData1 = str_replace("<STOPPAGE>",NOT_APPLICABLE_STRING,$icardData1);     
            }
            
            
            
            if($studentRecordArray[$i]['rollNo']==NOT_APPLICABLE_STRING) {
               $icardData1 = str_replace("<StudentRollNoHead>","Reg. No.",$icardData1);      
               $icardData1 = str_replace("<StudentRollNo>",$studentRecordArray[$i]['regNo'],$icardData1);        
            }
            else {
               $icardData1 = str_replace("<StudentRollNoHead>","Roll No.",$icardData1);      
               $icardData1 = str_replace("<StudentRollNo>",$studentRecordArray[$i]['rollNo'],$icardData1);          
            }
            
            $img='';
            $fileName = IMG_PATH."/Student/".$studentRecordArray[$i]['Photo'];
            if(file_exists($fileName)) {
              $img = "<img class='bborder' src=\"".STUDENT_PHOTO_PATH."/".$studentRecordArray[$i]['Photo']."\"height=\"55px\" width=\"65px\"  valign=\"middle\" >";
            }
            else {
              $img = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"55px\" width=\"65px\" \"valign=\"middle\" >";                          } 
            $icardData1 = str_replace("<StudentPhoto>",$img,$icardData1);
            
               if($cardView==3) {  
                   if($td==0) { 
                      $str = "<tr><td width='320' height='190' valign='top' align='center'>".$icardData1."</td>";
                      $td=1;                                               
                   }
                   else {
                      $str = "<td width='320' height='190' valign='top' align='center' style='padding-left:50px'>".$icardData1."</td></tr><tr><td colspan='2' height='10px'>&nbsp;</td></tr>"; 
                      $td=0; 
                   }
                   echo $str;
                   if(($i+1)%8==0) {
                     $td=0;  
                     $str = "</table>
                                <br class='page'>
                                    <table border='0px' cellpadding='0px' cellspacing='0px'>";
                     echo $str;
                   }   
               }
               else {
                   echo $icardData1;
                   if(($i+1)%4==0) {
                     echo '<br class="page">';
                   }   
               }
          }    // end for loop 
      }    
?>                 

<?php    
// $History: busPassReportPrint.php $
//
//*****************  Version 13  *****************
//User: Parveen      Date: 2/01/10    Time: 2:19p
//Updated in $/LeapCC/Templates/Icard
//look & feel updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 12/24/09   Time: 5:20p
//Updated in $/LeapCC/Templates/Icard
//look & feel updated 
//
//*****************  Version 11  *****************
//User: Parveen      Date: 12/19/09   Time: 2:24p
//Updated in $/LeapCC/Templates/Icard
//date format setting
//
//*****************  Version 10  *****************
//User: Parveen      Date: 8/31/09    Time: 2:24p
//Updated in $/LeapCC/Templates/Icard
//busPassLogo format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/06/09    Time: 2:40p
//Updated in $/LeapCC/Templates/Icard
//format & new enhancement updated (blood group added) 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/06/09    Time: 10:31a
//Updated in $/LeapCC/Templates/Icard
//busPass bloodgroup & config base setting updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/23/09    Time: 3:12p
//Updated in $/LeapCC/Templates/Icard
//format update
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/22/09    Time: 4:13p
//Updated in $/LeapCC/Templates/Icard
//issue fix Format, validation added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/22/09    Time: 2:29p
//Updated in $/LeapCC/Templates/Icard
//formating, validations, messages, conditions  changes 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/16/09    Time: 6:23p
//Updated in $/LeapCC/Templates/Icard
//condition update routeCode
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/16/09    Time: 3:10p
//Updated in $/LeapCC/Templates/Icard
//date format & class name formatting
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/15/09    Time: 12:34p
//Updated in $/LeapCC/Templates/Icard
//validation, conditions & formatting updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/15/09    Time: 11:21a
//Created in $/LeapCC/Templates/Icard
//file added
//

?>