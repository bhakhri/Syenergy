
<?php 
    //This file is used as printing version for student ICard.
    //
    // Author :Parveen Sharma
    // Created on : 26-12-2008
    // Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
?>
<?php        
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentGradeCardReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();  

    $ones = array("", "First", "Second", "Third", "Four", "Five", "Six", 
                      "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
                      "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen"); 

    $yyFormat = array("","january", "february", "march", "April", "May", "June", 
                         "July", "August", "september", "october", "november", "december"); 

    
    require_once(TEMPLATES_PATH . "/GradeCardReport/gradeCardTemplate2.php"); 
        
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/GradeCardRepotManagerSecond.inc.php");
    $gradeCardRepotManager = GradeCardRepotManagerSecond::getInstance();
    
    global $sessionHandler;   

    function parseOutput($data){
       return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
    }

    
    $degreeId = add_slashes($REQUEST_DATA['degreeId']);
    $branchId = add_slashes($REQUEST_DATA['branchId']);     
    $batchId =  add_slashes($REQUEST_DATA['batchId']);     
   
    $authorized =  add_slashes($REQUEST_DATA['authorized']);
    $designation =  add_slashes($REQUEST_DATA['designation']);
    
    $gradeDate  =  add_slashes($REQUEST_DATA['gradeDate']);   
    
    $showPageHeader =  add_slashes($REQUEST_DATA['showHeader']);     
    $placeCity =  add_slashes($REQUEST_DATA['placeCity']); 
    
    if($showPageHeader=='') {
      $showPageHeader='0';  
    }
    
   
    $conditions = " AND e.branchId = '$branchId' AND b.batchId = '$batchId' AND d.degreeId = '$degreeId' ";
   
    // Student Information Fetch    
    $classId = '';
    $studentId = add_slashes($REQUEST_DATA['studentId']);     
    $studentArray = explode(",",$studentId); 
    $cnt=count($studentArray);
    
    
    $recordStatus ='0';          
    
    $allSemester = add_slashes($REQUEST_DATA['allSemester']); 
    $allSemesterArr = explode(",",$allSemester); 
    
    $semester = add_slashes($REQUEST_DATA['semester']); 
    $semesterArr = explode(",",$semester); 
    
    
    $studentClassId = array();
    $studentSemester = "";
    $j=0;
    for($i=0; $i< count($allSemesterArr); $i++) {
      $studentClassId[$i] = $allSemesterArr[$i];   
      if($i==0) 
        $studentSemester = $studentClassId[$i] ;
      else
        $studentSemester .= ",".$studentClassId[$i] ;
      if($semesterArr[$j]==$allSemesterArr[$i]) {  
        $j++;      
      }
      if(($j==count($semesterArr))) {
        break;
      }
    }
    
    
    
    if($cnt==0)  { 
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(800); 
        $reportManager->setReportHeading('Student Grade Card Report');
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
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getFooterStyle();?>>No data found</th></tr>
        </table>  
        
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $reportManager->reportWidth ?>">
            <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> ><?php echo $reportManager->showFooter(); ?></td>
                <td height="30px"></td>
            </tr>
        </table>        
<?php
    }
    else {
       for($i=0;$i<$cnt;$i++) {
            $conditionStudentInfo = " AND ttm.studentId = '".$studentArray[$i]."' AND sp.studyPeriodId IN (".$semester.")"; 
            $semesterArray =  $gradeCardRepotManager->getStudentStudyPeriodWiseInfo($conditionStudentInfo);
            
            for($j=0;$j<count($semesterArray); $j++) {
                $studentInformation ='';
                $ttStudyPeriodId =  $semesterArray[$j]['studyPeriodId'];
                $ttStudentId =  $semesterArray[$j]['studentId'];
                
                if($j!=0) {
                  if($ttPage=='') {  
                    echo "<br class='brpage'>";             
                  }
                }
                
                $univLogo = $semesterArray[$j]['universityLogo'];                
                $fileName = IMG_PATH."/University/".$univLogo;    
                if(file_exists($fileName)) {
                    $fileNameLogo = IMG_HTTP_PATH."/University/".$univLogo."?uu=".rand(0,1000);    
                    $univLogoPrint = "<img  src=\"".$fileNameLogo." \"height=\"130px\" \"width=\"170px\" valign=\"middle\" >";
                }
                else {
                   $univLogoPrint = "<img class='bborder' src=\"".IMG_HTTP_PATH."/notfound.jpg \"valign=\"middle\"  width='170px' height='130px' style='border:1px solid #cccccc' >";  
                }
                
                $univName = $semesterArray[$j]['universityName'];
                $univAddress = $semesterArray[$j]['univAddress'];
                $instName = $semesterArray[$j]['instituteName'];
                $degreeName = $semesterArray[$j]['degreeName'];
                $univCityName = $semesterArray[$j]['univCityName'];
                $currBranchName = $semesterArray[$j]['currBranchName'];
                
                
                $periodValue = $semesterArray[$j]['periodValue'];
                $periodicityName =$semesterArray[$j]['periodicityName'];  
                $showPeriodValue =  strtoupper(trim($ones[$periodValue])." ".trim($periodicityName));
                
                $endDate = explode('-',$semesterArray[$j]['endDate']);
                $showTime = trim($yyFormat[$endDate[1]]." ".$endDate[0]);    
                
                $showPeriodDate = strtoupper($showPeriodValue." ".$showTime);
                 
                if($degreeName!=$currBranchName && $currBranchName != '' ) {
                  $currBranchName = "Discipline&nbsp;:&nbsp;$currBranchName";  
                }
                else {
                   $currBranchName =''; 
                }
                 
               
                if($showPageHeader=='1') {     
                  $gradeCardContents = $contentHead;   
                }
                else {
                  $gradeCardContents = $contentNoHeader;   
                }
                
                $gradeCardContents = str_replace("<UniversityLogo>",$univLogoPrint,$gradeCardContents);
                $gradeCardContents = str_replace("<UniversityName>",$univName,$gradeCardContents);
                $gradeCardContents = str_replace("<UniversityAddress>",$univAddress,$gradeCardContents);  
                $gradeCardContents = str_replace("<InstituteName>",$instName,$gradeCardContents);
                $gradeCardContents = str_replace("<DegreeName>",$degreeName,$gradeCardContents);
                $gradeCardContents = str_replace("<ShowDateTime>","<br>".$showPeriodDate,$gradeCardContents);
                $gradeCardContents = str_replace("<BranchName>",$currBranchName,$gradeCardContents);
                $studentInformation .= $gradeCardContents;
                
                $gradeCardContents = $contentStudent;
                $gradeCardContents = str_replace("<STUDENTNAME>",$semesterArray[$j]['studentName'],$gradeCardContents);
                $gradeCardContents = str_replace("<ROLLNO>",$semesterArray[$j]['rollNo'],$gradeCardContents);
                $gradeCardContents = str_replace("<FATHERNAME>",$semesterArray[$j]['fatherName'],$gradeCardContents);  
                $gradeCardContents = str_replace("<RollNoAbbr>",ROLL_NO,$gradeCardContents);  
                $gradeCardContents = str_replace("<instituteName>",$instName,$gradeCardContents);   
                $studentInformation .= $gradeCardContents;  
                
                $studentInformation .= $contentsCourse1;       
                $condition = " AND ttm.studentId = '$ttStudentId' AND cls.studyPeriodId = '$ttStudyPeriodId' ";
                $recordArray = $gradeCardRepotManager->getStudentGradeCardInfo($condition);    
                for($xx=0;$xx<count($recordArray);$xx++) {
                   $gradeCardContents = $contentsCourse2;
                   
                   $gradeCardContents = str_replace("<CourseCode>",$recordArray[$xx]['subjectCode'],$gradeCardContents);
                   $gradeCardContents = str_replace("<CourseName>",$recordArray[$xx]['subjectName'],$gradeCardContents);
                   $gradeCardContents = str_replace("<Credits>",$recordArray[$xx]['credits'],$gradeCardContents);
                   $gradeCardContents = str_replace("<Grade>",$recordArray[$xx]['gradeLabel'],$gradeCardContents);
                   
                   $studentInformation .= $gradeCardContents;
                }
                $studentInformation .= $contentsCourse3;   
                
                $currentCredit = ''; 
                $currentGradePoint = '';    

                $lessCredit =   ''; 
                $lessGradePoint =   ''; 

                $previousCredit =  ''; 
                $previousGradePoint = ''; 

                $netCredit =  ''; 
                $netGradePoint =  ''; 

                $sgpa = ''; 
                $cgpa = ''; 
                
                $condition=" AND c.studyPeriodId = '$ttStudyPeriodId' AND s.studentId = '$ttStudentId' ";
                $studentCGPARecordArray = $gradeCardRepotManager->getStudentClasswiseCGPA($condition);
                $gradeCardContents = $contentResult;
                if(count($studentCGPARecordArray) >0 ) {
                   $currentCredit =  number_format($studentCGPARecordArray[0]['currentCredit'],2);
                   $currentGradePoint =   number_format($studentCGPARecordArray[0]['currentGradePoint'],2);   
                      
                   $lessCredit =   number_format($studentCGPARecordArray[0]['lessCredit'],2);     
                   $lessGradePoint =   number_format($studentCGPARecordArray[0]['lessGradePoint'],2);   
                      
                   $previousCredit =   number_format($studentCGPARecordArray[0]['previousCredit'],2);   
                   $previousGradePoint =   number_format($studentCGPARecordArray[0]['previousGradePoint'],2);   
                      
                   $netCredit =  number_format($studentCGPARecordArray[0]['netCredit'],2); 
                   $netGradePoint =  number_format($studentCGPARecordArray[0]['netGradePoint'],2);
                      
                   $sgpa = UtilityManager::decimalRoundUp($studentCGPARecordArray[0]['gpa']);  
                   $cgpa = UtilityManager::decimalRoundUp($studentCGPARecordArray[0]['cgpa']);  
                }
                
                if($currentCredit=='') {
                  $currentCredit = NOT_APPLICABLE_STRING; 
                }
                if($currentGradePoint=='') {
                  $currentGradePoint = NOT_APPLICABLE_STRING; 
                }
                if($currentCreditEarned=='') {
                  $currentCreditEarned = NOT_APPLICABLE_STRING; 
                }
                
                if($previousCredit=='') {
                  $previousCredit = NOT_APPLICABLE_STRING;   
                }
                
                if($previousGradePoint=='') {
                  $previousGradePoint = NOT_APPLICABLE_STRING;   
                }
                
                if($lessCredit=='') {
                  $lessCredit = NOT_APPLICABLE_STRING; 
                }
                if($lessGradePoint=='') {
                  $lessGradePoint = NOT_APPLICABLE_STRING; 
                }
                
                if($netCredit=='') {
                  $netCredit = NOT_APPLICABLE_STRING; 
                }
                if($netGradePoint=='') {
                  $netGradePoint = NOT_APPLICABLE_STRING; 
                }
               
                if($sgpa=='') {
                  $sgpa = NOT_APPLICABLE_STRING; 
                }
                
                if($cgpa=='') {
                  $cgpa = NOT_APPLICABLE_STRING; 
                }
                
                
                $gradeCardContents = str_replace("<CURRENTCREDIT>",$currentCredit,$gradeCardContents);
                $gradeCardContents = str_replace("<CURRENTGRADEPOINT>",$currentGradePoint,$gradeCardContents);
                $gradeCardContents = str_replace("<GRADEPOINT>",$sgpa,$gradeCardContents);  
                
                $gradeCardContents = str_replace("<PreviousCredit>",$previousCredit,$gradeCardContents);  
                $gradeCardContents = str_replace("<PreviousPoint>",$previousGradePoint,$gradeCardContents);  
                
                
                $gradeCardContents = str_replace("<LessCredit>",$lessCredit,$gradeCardContents);  
                $gradeCardContents = str_replace("<LessGrade>",$lessGradePoint,$gradeCardContents);  
                
                $gradeCardContents = str_replace("<TotalCredit>",$netCredit,$gradeCardContents);  
                $gradeCardContents = str_replace("<TotalGrade>",$netGradePoint,$gradeCardContents);  
                
                $gradeCardContents = str_replace("<CumulativeGrade>",$cgpa,$gradeCardContents);  
                
                $studentInformation .= $gradeCardContents;  
                
                $gradeCardContents = $contentsApprove;
                
                $gradeCardContents = str_replace("<UnivCityName>", strtoupper($placeCity),$gradeCardContents);   
                $gradeCardContents = str_replace("<Authorized>", strtoupper($authorized),$gradeCardContents);   
                $gradeCardContents = str_replace("<GradeDated>", UtilityManager::formatDate($gradeDate),$gradeCardContents);
                $gradeCardContents = str_replace("<Designation>",strtoupper($designation),$gradeCardContents);   
                $studentInformation .= $gradeCardContents; 
                
                echo $contentMain1.$studentInformation.$contentMain2;
                $recordStatus='1';
            }
       }     
    } // else Statement 

   if($recordStatus == '0') {
        $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
        $reportManager->setReportWidth(800); 
        $reportManager->setReportHeading('Student Grade Card Report');
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
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
            <tr><th colspan="3"  <?php echo $reportManager->getFooterStyle();?>>No data found</th></tr>
        </table>  
        
        <table border='0' cellspacing='0' cellpadding='0' width="<?php echo $reportManager->reportWidth ?>">
            <tr>
                <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?> ><?php echo $reportManager->showFooter(); ?></td>
                <td height="30px"></td>
            </tr>
        </table>        
<?php    
}
?>  
