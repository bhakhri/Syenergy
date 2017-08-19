 <?php 
//$reportManager file is used as printing version for display attendance report in parent module.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    require_once($FE . "/Library/HtmlFunctions.inc.php"); 
    require_once(BL_PATH . '/ReportManager.inc.php');   
    
    global $sessionHandler;    

    UtilityManager::ifStudentNotLoggedIn(true);
    UtilityManager::headerNoCache();
    define('MODULE','COMMON');
    define('ACCESS','view');
    
    $timeTableManager = TimeTableManager::getInstance();
    $htmlFunctionsManager = HtmlFunctions::getInstance();  
    $reportManager = ReportManager::getInstance();
    
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');
    $classId = $REQUEST_DATA['studyPeriodId'];
    if($classId=='') {
      $classId=0;  
    }
    
    $studentId = $sessionHandler->getSessionVariable('StudentId');    
    $studentName = $sessionHandler->getSessionVariable('StudentName');    
    
    if($studentId=='') {
      $studentId=0;  
    }
    
    $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";     

    $findTimeTable='';   
      
    
    if($classId=='0'  ) {
        $results = CommonQueryManager::getInstance()->getTimeTableLabel('');
        
        // Fetch All Classes    
        $classFetchArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
        $classRecordCount = count($classFetchArray);

        if($classRecordCount >0 && is_array($classFetchArray)) { 
           for($k=0;$k<$classRecordCount;$k++) {
               $classId = $classFetchArray[$k]['classId'];
               $className = $classFetchArray[$k]['periodName'];  
               if(isset($results) && is_array($results)) {
                   for($i=0; $i<count($results); $i++) {
                       $timeTableLabelId = $results[$i]['timeTableLabelId'];    
                        if($timeTableLabelId=='') {
                            $timeTableLabelId=0; 
                        } 
                
                       // Fetch Period Arrays
                       $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
                       $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                       $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
             
                       //Get the time table date according to class selected
                       for($ps=0; $ps < count($periodSlotArr); $ps++) {
                          $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
                    
                          $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
                          $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                          $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);  
                       
                          
                          $conditions  = " AND sg.studentId=".$studentId." AND cl.classId = $classId";
                          $conditions .= " AND tt.timeTableLabelId=".$timeTableLabelId;
                          
                          $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId'];
                          
                          
                          $fieldName="DISTINCT timeTableType";
                          $orderFrom = " ORDER BY timeTableType";
                          $studentRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                          $timeTableType=1;
                          if(count($studentRecordArray)>0) {
                               $timeTableType = $studentRecordArray[0]['timeTableType'];
                          }
                            
                          if($timeTableType==1) {
                               $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
                          }
                          else 
                          if($timeTableType==2) {
                               $orderBy = " ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber ";
                          }             
                            
                          if($timeTableType==2) {
                            // Date Format 
                            $fieldName = " DISTINCT tt.fromDate";
                            $orderFrom = " ORDER BY fromDate";
                            $timeTableDateArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                         }  
                          
                          $teacherRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderBy);
                          $recordCount = count($teacherRecordArray);
                          if($recordCount >0 && is_array($teacherRecordArray)) { 
                            $reportManager->setReportInformation("For ".$studentName." As On $formattedDate ");
                            $reportManager->setReportHeading("Student Time Table");
?>
    <table border='0' cellspacing='0' width="90%" align="center">
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
    <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
    <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
    </table> <br>
     
    <table border='0' cellspacing='0' width="90%" align="center">
    <tr>
        <td>
            <?php
                            echo "<b ".$reportManager->getReportHeadingStyle()."><span class='contenttab_internal_rows1'>".$className."</span></b><br>";
                            //echo "<b ".$reportManager->getReportHeadingStyle().">".$timeTableLabel."</b><br>";
                            if($timeTableType==1) {       
                                if($timetableFormat=='1') {
                                   echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                                   echo "<br>";
                                }
                               else 
                               if($timetableFormat=='2') {
                                 echo  $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                                 echo "<br>";
                               }
                            }
                            else
                            if($timeTableType==2) {       
                               echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                               echo "<br>";
                            }
                        echo '</td>
                                        </tr>
                                            </table>
                                                <br> <br class="page" />';
?>                                                
                    <table border='0' cellspacing='0' width="90%" align="center">
                        <tr>
                            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
                        </tr>
                    </table>
                <?php    
              }
            }
         }
       }
           }
        }
    }
    else {
        $findTimeTable='';
        $results = CommonQueryManager::getInstance()->getTimeTableLabel('');  
        if(isset($results) && is_array($results)) {
           for($i=0; $i<count($results); $i++) {
                //Get the time table date according to class selected
                $timeTableLabelId = $results[$i]['timeTableLabelId'];  
                if($timeTableLabelId=='') {
                  $timeTableLabelId=0; 
                }
     
                   $conditions  = " AND sg.studentId=".$studentId." AND cl.classId = $classId";
                   $conditions .= " AND tt.timeTableLabelId=".$timeTableLabelId;
                
                    // Fetch Period Arrays
                    $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
                    $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                    $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');   
                 
                    //Get the time table date according to class selected
                    for($ps=0; $ps < count($periodSlotArr); $ps++) {
                        $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];
                        
                        $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
                        $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                        $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList); 
                          
                        $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId'];
                        
                        $fieldName="DISTINCT timeTableType, SUBSTRING_INDEX(className,'".CLASS_SEPRATOR."',-1) AS className";   
                        $orderFrom = " ORDER BY timeTableType";
                        $studentRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                        $timeTableType=1;
                        if(count($studentRecordArray)>0) {
                            $timeTableType = $studentRecordArray[0]['timeTableType'];
                            $className = $studentRecordArray[0]['className'];   
                        }
                        
                        if($timeTableType==1) {
                            $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
                        }
                        else 
                        if($timeTableType==2) {
                            $orderBy =" ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber" ;
                        }             
                        
                        if($timeTableType==2) {
                            // Date Format 
                            $fieldName = " DISTINCT tt.fromDate";
                            $orderFrom = " ORDER BY fromDate";
                            $timeTableDateArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                        } 
                        
                          
                         $employeeName = $teacherRecordArray[0]['employeeName'];
                         
                         $teacherRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderBy);    
                         $recordCount = count($teacherRecordArray);
                         if($recordCount >0 && is_array($teacherRecordArray)) { 
                            $reportManager->setReportInformation("For ".$studentName." As On $formattedDate ");
                            $reportManager->setReportHeading("Student Time Table");
            ?>
                <table border='0' cellspacing='0' width="90%" align="center">
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
                <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
                <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
                </table> <br>
                 
                <table border='0' cellspacing='0' width="90%" align="center">
                <tr>
                    <td>
                        <?php
                                 
                                        echo "<b ".$reportManager->getReportHeadingStyle()."><span class='contenttab_internal_rows1'>".$className."</span></b><br>";
                                        if($timeTableType==1) {       
                                            if($timetableFormat=='1') {
                                               echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                                               echo "<br>";
                                            }
                                           else 
                                           if($timetableFormat=='2') {
                                             echo  $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                                             echo "<br>";
                                           }
                                        }
                                        else
                                        if($timeTableType==2) {       
                                           echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                                           echo "<br>";
                                        }
                                    echo '     </td>
                                                    </tr>
                                                        </table>
                                                            <br> <br class="page" />';
                        ?>
                <table border='0' cellspacing='0' width="90%" align="center">
                    <tr>
                        <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
                    </tr>
                </table>
<?php    
       }
                    }
           }
    }
  }
