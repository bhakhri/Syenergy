<script>
    //alert(location.search);
    var str=unescape(location.search);
    var strArray=str.split('icardTitle=');
    var len=strArray.length;
    var icardTitle=strArray[1];

    var str=unescape(location.search);
    var strArray=str.split('&heading1=');
    var len=strArray.length;
    var str=str.split('&heading2=');
    var heading1=str[0];
    var heading2=str[1];    
    var str=heading1.split('&heading1=');     
    var heading1=str[1];
    
</script>
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
    require_once(BL_PATH . '/ReportManager.inc.php');
    require_once(MODEL_PATH . "/InstituteManager.inc.php");

    define('MODULE','StudentIcardReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();


    global $sessionHandler;    

    $cardView = $REQUEST_DATA['cardView'];          
    $showId = $REQUEST_DATA['showId']; 
    
   
    if($showId=='') {
      $showId=1;  
    } 
    
    $reportManager = ReportManager::getInstance();
    $instituteManager = InstituteManager::getInstance(); 
    $commonQueryManager = CommonQueryManager::getInstance();
    $studentReportsManager = StudentReportsManager::getInstance();
    
   
    

//    print_r($_REQUEST);
//    die('here...');

    foreach($REQUEST_DATA as $key => $values) {
        $$key = $values;
    }
    
    //$titleIcard = urlencode($REQUEST_DATA['icardTitle']);
   
/*  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $sortBy = $REQUEST_DATA['sortField'].' '.$REQUEST_DATA['sortOrderBy'];   
*/ 

    if(add_slashes($REQUEST_DATA['sortField'])!='') {
        if(add_slashes($REQUEST_DATA['sortField'])=='DOB') {
          $sortField2="IF(DOB='0000-00-00',a.studentId,DOB)";
          $sortBy = $sortField2.' '.add_slashes($REQUEST_DATA['sortOrderBy']);  
        }
        else {
          $sortField2="IF(".$REQUEST_DATA['sortField']."='".NOT_APPLICABLE_STRING."',a.studentId, IF(IFNULL(".$REQUEST_DATA['sortField'].",'')='',a.studentId,".$REQUEST_DATA['sortField']."))";
          $sortBy = $sortField2.' '.add_slashes($REQUEST_DATA['sortOrderBy']);  
        }
    }
    else {
      $sortBy = " rollNo";
    }
   
    $conditions = " AND a.studentId IN ($student)";
    $studentRecordArray = $studentReportsManager->getStudentICardDetails($conditions, $sortBy);
    
    $cnt = count($studentRecordArray);
    if($cnt==0)  { 
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(800); 
        $reportManager->setReportHeading("Student I-Card's Report");
        //$reportManager->setReportInformation("For ".$studentName." As On $formattedDate ");
   ?>
        <table border="0" cellspacing="0" cellpadding="0" width="<?php echo $reportManager->reportWidth?>">
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
        
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $reportManager->reportWidth ?>">
            <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> ><?php echo $reportManager->showFooter(); ?></td>
                <td height="30px"></td>
            </tr>
        </table> 
<?php        
    }
    else  {
        $icardLogo = IMG_PATH."/Icard/".$sessionHandler->getSessionVariable('I_CARD_LOGO');    
        if(file_exists($icardLogo)) {
            $icardLogo = IMG_HTTP_PATH."/Icard/".$sessionHandler->getSessionVariable('I_CARD_LOGO')."?zz=".rand(0,1000);    
            $insLogo = "<img  src=\"".$icardLogo." \"height=\"40px\" \"width=\"85px\" valign=\"middle\" >";
        }
        else {
            $filter = "AND instituteId=".$sessionHandler->getSessionVariable('InstituteId');
            $instRecordArray = $instituteManager->getInstituteList($filter,''); 
            $insAddress  = $instRecordArray[0]['instituteAddress1'].' '.$instRecordArray[0]['instituteAddress2'].' '.$instRecordArray[0]['pin'].'<br>'.$instRecordArray[0]['employeePhone'];
            $insWebSite  = $instRecordArray[0]['instituteWebsite'];
            $insEmail   = $instRecordArray[0]['instituteEmail'];   
            $fileName = IMG_PATH."/Institutes/".$instRecordArray[0]['instituteLogo']; 
            $insLogo='';
            
            if($instRecordArray[0]['instituteLogo']=='') {
               $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"45px\" width=\"45px\" \"valign=\"middle\" >";  
            }
            else 
            if(file_exists($fileName)) {
               $icardLogo = IMG_HTTP_PATH."/Institutes/".$instRecordArray[0]['instituteLogo']."?yy=".rand(0,1000); 
               $insLogo = "<img  src=\"".$icardLogo." \"height=\"45px\" valign=\"middle\" >";
            }
            else {
               $insLogo = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"45px\" width=\"45px\" \"valign=\"middle\" >";  
            }
        }

               
        if($cardView==1) {     // Create Bus Pass
           require_once(TEMPLATES_PATH . "/Icard/busPassTemplate.php");   
        }
        else if($cardView==2) {     // Icard
           //require_once(TEMPLATES_PATH . "/Icard/icardTemplate.php"); 
           $tid=$sessionHandler->getSessionVariable('I_CARD_TEMPLATE');
           if($tid=='') {
             $tid=2;  
           }
           if($tid==1) {
              require_once(TEMPLATES_PATH . "/Icard/icardTemplate.php");
           }
           else {
              require_once(TEMPLATES_PATH . "/Icard/icardTemplate".$tId.".php");
           }
        }
        else if($cardView==3) {     // Admit Card
           require_once(TEMPLATES_PATH . "/Icard/admitCardTemplate.php");             
           $td=0;
           $str = "<table border='0px' cellpadding='0px' cellspacing='0px'>";
           //$heading1 = html_entity_decode($REQUEST_DATA['heading1']);
           //$heading2 = html_entity_decode($REQUEST_DATA['heading2']);
           echo $str;
        }  
        else if($cardView==4) {     // Bus Pass Report
           require_once(TEMPLATES_PATH . "/Icard/busPassReportTemplate.php"); 
           $td=0;
           $str = "<table border='0px' cellpadding='0px' cellspacing='0px'>";
           echo $str;
        }

        for($i=0;$i<$cnt;$i++) {
            
            if($cardView==4) {
               $className = $studentRecordArray[$i]['className'];
            }
            
            if($cardView==3) {     
               $className = $studentRecordArray[$i]['programme'].'-'.$studentRecordArray[$i]['periodName'];
            }
            else {
               $className = $studentRecordArray[$i]['programme'];
            }
            
            $icardData1 = $icardData;    
            $icardData1 = str_replace("<icardTitle>","<script>unescape(document.write(icardTitle));</script>",$icardData1); 
            //$icardData1 = str_replace("<icardTitle>",$titleIcard,$icardData1);
            //$icardData1 = str_replace("<HEADING1>",$heading1,$icardData1);      
            //$icardData1 = str_replace("<HEADING2>",$heading2,$icardData1);
            $icardData1 = str_replace("<HEADING1>","<script>unescape(document.write(heading1));</script>",$icardData1);
            $icardData1 = str_replace("<HEADING2>","<script>unescape(document.write(heading2));</script>",$icardData1);
            
            
            $icardData1 = str_replace("<INSTLOGO>",$insLogo,$icardData1);
            $icardData1 = str_replace("<EMAILADDRESS>",$insWebSite,$icardData1);
             
            $icardData1 = str_replace("<RECEIPTNO>",$studentRecordArray[$i]['receiptNo'],$icardData1);
            
            if($studentRecordArray[$i]['validUpto']=='' || $studentRecordArray[$i]['validUpto']=="'".NOT_APPLICABLE_STRING."'")  {
              $icardData1 = str_replace("<VALIDITY>",NOT_APPLICABLE_STRING,$icardData1);  
            }
            else {
              $icardData1 = str_replace("<VALIDITY>",UtilityManager::formatDate($studentRecordArray[$i]['validUpto']),$icardData1);  
            }
            //$icardData1 = str_replace("<VALIDITY>",$studentRecordArray[$i]['validUpto'],$icardData1);  
            $icardData1 = str_replace("<StudentRegNo>",$studentRecordArray[$i]['regNo'],$icardData1);      
           
           
            global $bloodResults;
            $blood = $bloodResults[$studentRecordArray[$i]['studentBloodGroup']]; 
            if($blood=="") {
              $blood=NOT_APPLICABLE_STRING;  
            }
            
            if($sessionHandler->getSessionVariable('EMPLOYEE_I_CARD_FOUND')!='') {
               $insAddress= $sessionHandler->getSessionVariable('EMPLOYEE_I_CARD_FOUND'); 
            }
           
            $icardData1 = str_replace("<StudentBloodGroup>",$blood,$icardData1);  
            $icardData1 = str_replace("<COLLEGEADDRESS>",$insAddress,$icardData1);
            $icardData1 = str_replace("<StudentId>",$studentRecordArray[$i]['studentId'],$icardData1);   
            $icardData1 = str_replace("<StudentName>",$studentRecordArray[$i]['studentName'],$icardData1);
            $icardData1 = str_replace("<FatherName>",$studentRecordArray[$i]['fatherName'],$icardData1);
            $icardData1 = str_replace("<DOB>",$studentRecordArray[$i]['DOB'],$icardData1);
            $icardData1 = str_replace("<Course>",$className,$icardData1);
            $icardData1 = str_replace("<StudentContact>",$studentRecordArray[$i]['studentMobileNo'],$icardData1);
            $icardData1 = str_replace("<StudentAddress>",$studentRecordArray[$i]['permAddress'],$icardData1);
            $icardData1 = str_replace("<StudentSession>",$studentRecordArray[$i]['studentSession'],$icardData1);
            $icardData1 = str_replace("<instituteName>",$studentRecordArray[$i]['instituteName'],$icardData1); 
            
            if($cardView==2) {
               if($showId==1) { 
                 $icardData1 = str_replace("<StudentRollNo1>","Roll No.",$icardData1);    
                 $icardData1 = str_replace("<StudentRollNo>",$studentRecordArray[$i]['rollNo'],$icardData1);
               }
               else if($showId==2) { 
                 $icardData1 = str_replace("<StudentRollNo1>","Univ. No.",$icardData1);    
                 $icardData1 = str_replace("<StudentRollNo>",$studentRecordArray[$i]['universityRollNo'],$icardData1);
               }
               else {
                 $icardData1 = str_replace("<StudentRollNo1>","Reg. No.",$icardData1);     
                 $icardData1 = str_replace("<StudentRollNo>",$studentRecordArray[$i]['regNo'],$icardData1);
               }
            }
            else {
              $icardData1 = str_replace("<StudentRollNo1>","Roll No.",$icardData1);    
              $icardData1 = str_replace("<StudentRollNo>",$studentRecordArray[$i]['rollNo'],$icardData1);  
            }
            $icardData1 = str_replace("<UnivRollNo>",$studentRecordArray[$i]['universityRollNo'],$icardData1);
            
            if($studentRecordArray[$i]['DOB']!='') {
                $icardData1 = str_replace("<StudentDOB>",UtilityManager::formatDate($studentRecordArray[$i]['DOB']),$icardData1);
            }
            else {
                $icardData1 = str_replace("<StudentDOB>",NOT_APPLICABLE_STRING,$icardData1);
            }
          
            if($studentRecordArray[$i]['routeCode']!='') {
               $icardData1 = str_replace("<ROUTENO>",$studentRecordArray[$i]['routeCode'],$icardData1);  
            }
            else {
               $icardData1 = str_replace("<ROUTENO>",NOT_APPLICABLE_STRING,$icardData1);        
            }
            if($studentRecordArray[$i]['stopName']!='') {
                $icardData1 = str_replace("<STOPPAGE>",$studentRecordArray[$i]['stopName'],$icardData1);     
            }
            else {
                $icardData1 = str_replace("<STOPPAGE>",NOT_APPLICABLE_STRING,$icardData1);     
            }
            
            $img='';
            $fileName = IMG_PATH."/Student/".$studentRecordArray[$i]['Photo'];
            if(file_exists($fileName)) {
              $studentLogo = STUDENT_PHOTO_PATH."/".$studentRecordArray[$i]['Photo']."?ss=".rand(0,1000);
              $img = "<img class='bborder' src=\"".$studentLogo."\"height=\"65px\" width=\"75px\"  valign=\"middle\" >";
            }
            else {
              $img = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"height=\"65px\" width=\"75px\" \"valign=\"middle\" >";                          } 
            $icardData1 = str_replace("<StudentPhoto>",$img,$icardData1);
            
               if($cardView==3) {     
                   if($td==0) { 
                      $str = "<tr><td width='320' height='190' valign='top' align='center'>".$icardData1."</td>";
                      $td=1;                                               
                   }
                   else {
                      $str = "<td width='320' height='190' valign='top' align='center' style='padding-left:50px'>".$icardData1."</td></tr><tr><td colspan='2' height='40px'>&nbsp;</td></tr>"; 
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
               else if($cardView==4) {     
                   if($td==0) { 
                      $str = "<tr><td width='320' height='140' valign='top' align='center'>".$icardData1."</td>";
                      $td=1;                                               
                   }
                   else {
                      $str = "<td width='320' height='140' valign='top' align='center' style='padding-left:50px'>".$icardData1."</td></tr>
                      <tr><td colspan='2' height='5px'>&nbsp;</td></tr>"; 
                      $td=0; 
                   }
                   echo $str;
                   if(($i+1)%10==0) {
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
// $History: icardReportPrint.php $
//
//*****************  Version 20  *****************
//User: Parveen      Date: 2/09/10    Time: 10:19a
//Updated in $/LeapCC/Templates/Icard
//icard format updated
//
//*****************  Version 19  *****************
//User: Parveen      Date: 2/04/10    Time: 12:02p
//Updated in $/LeapCC/Templates/Icard
//student bloodGroup added
//
//*****************  Version 18  *****************
//User: Parveen      Date: 1/18/10    Time: 1:40p
//Updated in $/LeapCC/Templates/Icard
//title case format updated
//
//*****************  Version 17  *****************
//User: Parveen      Date: 1/18/10    Time: 12:31p
//Updated in $/LeapCC/Templates/Icard
//icard title update
//
//*****************  Version 16  *****************
//User: Parveen      Date: 12/23/09   Time: 10:33a
//Updated in $/LeapCC/Templates/Icard
//webaddress base format updated (Student Generate I-Card Report)
//
//*****************  Version 15  *****************
//User: Parveen      Date: 12/21/09   Time: 6:45p
//Updated in $/LeapCC/Templates/Icard
//urlencode function added
//
//*****************  Version 14  *****************
//User: Parveen      Date: 12/19/09   Time: 2:26p
//Updated in $/LeapCC/Templates/Icard
//date format updated
//
//*****************  Version 13  *****************
//User: Parveen      Date: 10/20/09   Time: 3:34p
//Updated in $/LeapCC/Templates/Icard
//sorting order updated (bug no. 1696)
//
//*****************  Version 12  *****************
//User: Parveen      Date: 10/06/09   Time: 3:59p
//Updated in $/LeapCC/Templates/Icard
//icard display record limit increased and updated look & feel
//
//*****************  Version 11  *****************
//User: Parveen      Date: 10/05/09   Time: 2:02p
//Updated in $/LeapCC/Templates/Icard
//sorting condition updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 10/01/09   Time: 4:59p
//Updated in $/LeapCC/Templates/Icard
//icard title input box added
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Templates/Icard
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/28/09    Time: 5:31p
//Updated in $/LeapCC/Templates/Icard
//route Code update 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/28/09    Time: 5:03p
//Updated in $/LeapCC/Templates/Icard
//issue fix format & conditions & alignment updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/13/09    Time: 2:54p
//Updated in $/LeapCC/Templates/Icard
//bus pass report update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/11/09    Time: 5:23p
//Updated in $/LeapCC/Templates/Icard
//conditions, validation & formatting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 5/26/09    Time: 10:48a
//Updated in $/LeapCC/Templates/Icard
//format setting 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/23/09    Time: 2:48p
//Updated in $/LeapCC/Templates/Icard
//formatting update (admitCard, Buspass, Icard)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/12/09    Time: 4:40p
//Updated in $/LeapCC/Templates/Icard
//inital checkin
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/12/09    Time: 3:48p
//Created in $/LeapCC/Templates/Icard
//Icard added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/07/09    Time: 4:45p
//Updated in $/Leap/Source/Templates/ScICard
//template base code settings
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/05/09    Time: 5:48p
//Updated in $/Leap/Source/Templates/ScICard
//template base settings
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/27/08   Time: 4:26p
//Updated in $/Leap/Source/Templates/ScICard
//checkbox added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/26/08   Time: 4:29p
//Created in $/Leap/Source/Templates/ScICard
//initial checkin
//
//

?>