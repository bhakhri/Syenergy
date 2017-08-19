<?php
//--------------------------------------------------------  
//It contains the time table
//
// Author :Parveen Sharma
// Created on : 07-08-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once($FE . "/Library/HtmlFunctions.inc.php"); 
    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");

    $studentManager = StudentManager::getInstance();
    $htmlFunctionsManager = HtmlFunctions::getInstance();  
    $timeTableManager = TimeTableManager::getInstance();

    
    $currentClassId = $REQUEST_DATA['currentClassId'];
    $studentId      =  $sessionHandler->getSessionVariable('StudentId');     
    $classId        = $REQUEST_DATA['rClassId'];
    
    $parentName = $sessionHandler->getSessionVariable('ParentName');
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $studentName = $sessionHandler->getSessionVariable('StudentName');                 
    $csvData = "For ".$studentName." As On $formattedDate ";     
    $csvData .= "\n";

    global $sessionHandler;
    $timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');

    $orderBy =($timetableFormat == 1) ? " daysOfWeek, length(periodNumber)+0,periodNumber" : " length(periodNumber)+0,periodNumber,daysOfWeek"; 

         if($classId == 0){
                $studentClassArray = $studentManager->getStudentAllClass($studentId);
                $recordCount = count($studentClassArray);
                if($recordCount >0 && is_array($studentClassArray)){
                    for($k=0;$k<$recordCount;$k++){
                        //echo $studentClassArray[$k]['classId'];
                        $fieldName="DISTINCT timeTableType";
                        $orderFrom="timeTableType"; 
                        $studentRecordArray = $studentManager->getStudentTimeTable($studentId,$studentClassArray[$k]['classId'],$currentClassId,$orderFrom,$fieldName);
                        $timeTableType=1;
                        if(count($studentRecordArray)>0) {
                           $timeTableType = $studentRecordArray[0]['timeTableType'];
                        }
                        
                        if($timeTableType==1) {
                             $orderBy =($timetableFormat == 1) ? " daysOfWeek, length(periodNumber)+0,periodNumber" : " length(periodNumber)+0,periodNumber,daysOfWeek"; 
                        }
                        else 
                        if($timeTableType==2) {
                           $orderBy = " fromDate, length(periodNumber)+0,periodNumber";
                        }             
                        
                        if($timeTableType==2) {
                            // Date Format 
                            $fieldName = " DISTINCT tt.fromDate";
                            $orderFrom = " fromDate";
                            $timeTableDateArray = $studentManager->getStudentTimeTable($studentId,$studentClassArray[$k]['classId'],$currentClassId,$orderFrom,$fieldName);
                        }    

                        $studentRecordArray = $studentManager->getStudentTimeTable($studentId,$studentClassArray[$k]['classId'],$currentClassId,$orderBy);
                        if($studentRecordArray[0]['timeTableLabelId']!='') {                 
                        $periodArray = $timeTableManager->getTimeTablePeriodList(' tt.timeTableLabelId = '.$studentRecordArray[0]['timeTableLabelId']);  
                        $findTimeTable='';
                        $recordCount1 = count($studentRecordArray);
                        if($recordCount1 >0 && is_array($studentRecordArray)) {     
                            $csvData .= $studentRecordArray[0]['className'].", \n";
                            if($timeTableType==1) {  
                                if($timetableFormat=='1') {
                                   $csvData .= $htmlFunctionsManager->showTimeTablePeriodsColumnsCSV($studentRecordArray,$periodArray);
                                   $csvData .= "\n\n\n";
                                }
                               else 
                               if($timetableFormat=='2') {
                                 $csvData .= $htmlFunctionsManager->showTimeTablePeriodsRowsCSV($studentRecordArray,$periodArray);
                                 $csvData .= "\n\n\n";
                               }
                            }
                            else
                            if($timeTableType==2) {  
                               $csvData .= $htmlFunctionsManager->showTimeTablePeriodsColumnsCSV($studentRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                               $csvData .= "\n\n\n";
                            }   
                            $findTimeTable='1';
                         }    
                      }
                    }
                }
            }
            else {
                $fieldName="DISTINCT timeTableType";
                $orderFrom = " timeTableType";
                $studentRecordArray = $studentManager->getStudentTimeTable($studentId,$classId,$currentClassId,$orderFrom,$fieldName);
                $timeTableType=1;
                if(count($studentRecordArray)>0) {
                   $timeTableType = $studentRecordArray[0]['timeTableType'];
                }
                
                if($timeTableType==1) {
                     $orderBy =($timetableFormat == 1) ? " daysOfWeek, length(periodNumber)+0,periodNumber" : " length(periodNumber)+0,periodNumber,daysOfWeek"; 
                }
                else 
                if($timeTableType==2) {
                   $orderBy = " fromDate, length(periodNumber)+0,periodNumber";
                }             
                
                if($timeTableType==2) {
                    // Date Format 
                    $fieldName = " DISTINCT tt.fromDate";
                    $orderFrom = " fromDate";
                    $timeTableDateArray = $studentManager->getStudentTimeTable($studentId,$classId,$currentClassId,$orderFrom,$fieldName);
                }  
                
                //Get the time table date according to class selected
                $studentRecordArray = $studentManager->getStudentTimeTable($studentId,$classId,$currentClassId,$orderBy);
                if($studentRecordArray[0]['timeTableLabelId']!='') {                 
                    $periodArray = $timeTableManager->getTimeTablePeriodList(' tt.timeTableLabelId = '.$studentRecordArray[0]['timeTableLabelId']);  
                    $findTimeTable='';
                    $recordCount = count($studentRecordArray);
                    if($recordCount >0 && is_array($studentRecordArray)) {     
                       $csvData .= $studentRecordArray[0]['className'].", \n";
                       if($timeTableType==1) {  
                           if($timetableFormat=='1') {
                               $csvData .= $htmlFunctionsManager->showTimeTablePeriodsColumnsCSV($studentRecordArray,$periodArray);
                               $csvData .= "\n\n\n";
                            }
                           else 
                           if($timetableFormat=='2') {
                             $csvData .= $htmlFunctionsManager->showTimeTablePeriodsRowsCSV($studentRecordArray,$periodArray);
                             $csvData .= "\n\n\n";
                           }
                        }
                        else
                        if($timeTableType==2) {  
                           $csvData .= $htmlFunctionsManager->showTimeTablePeriodsColumnsCSV($studentRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                           $csvData .= "\n\n\n";
                       }   
                       $findTimeTable='1';
                    }    
                }
            }
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;filename="TimeTableReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;
die;      

//$History : $
?>
