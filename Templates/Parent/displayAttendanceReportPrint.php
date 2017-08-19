 <?php 
//This file is used as printing version for display attendance report in parent module.
//
// Author :Arvind Singh Rawat
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifParentNotLoggedIn();
    UtilityManager::headerNoCache();
    
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    $commonAttendanceArr = CommonQueryManager::getInstance();
     
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
        
    $orderBy = " $sortField $sortOrderBy";  

    $studentId=$sessionHandler->getSessionVariable('StudentId');    
    if($studentId=='') {
      $studentId=0;  
    }
    
    $studentName = $sessionHandler->getSessionVariable('StudentName');
    $className = $sessionHandler->getSessionVariable('ClassName');
    $fromDate = $REQUEST_DATA['startDate2'];
    $toDate = $REQUEST_DATA['endDate2'];
    
    $classId = $REQUEST_DATA['classId'];

    //$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
    //$classId = $classIdArray[0]['classId'];

    if($fromDate) {
        $where = " AND fromDate BETWEEN '$fromDate' AND '$toDate'";
    }
    if($toDate) {
        $where .= " AND toDate BETWEEN '$fromDate' AND '$toDate'";
    }


    if($where != "") {
      $where .= " AND su.hasAttendance = 1 ";
      if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countConsolidatedStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $recordArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"ORDER BY $orderBy");            
      }
     else{ //if group wise display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $recordArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
     } 
    }
    else {
      $where .= " AND su.hasAttendance = 1 ";
     if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required 
         $totalRecordArray = CommonQueryManager::getInstance()->countConsolidatedStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $recordArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"ORDER BY $orderBy");
     }
     else{//if group wise display is required 
         $totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $recordArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
     } 
    }    

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
    $reportManager->setReportInformation("For ".$studentName." of ".$className." ".$from." As On $formattedDate ");
    $reportManager->setReportHeading("Attendance Detail Report");
?>
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
    <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?>><?php echo $reportManager->reportHeading; ?></th></tr>
    <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
    </table> <br>
      
        <table border='1' cellspacing='0' class="reportTableBorder" width="90%" align="center">
       <?php
       if($REQUEST_DATA['consolidatedView']==1){ //if group wise display is required
       ?> 
        <tr>
            <td align="left" <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;#</b>
            <td valign="middle" align="left" width="35%" <?php echo $reportManager->getReportDataStyle()?>><b>Subject</b></td>
            <td valign="middle" width="15%" <?php echo $reportManager->getReportDataStyle()?>><b>Study Period</b></td>
            <td valign="middle" <?php echo $reportManager->getReportDataStyle()?>><b>Group</b></td>
            <td valign="middle" <?php echo $reportManager->getReportDataStyle()?>><b>Teacher</b></td>
            <td valign="middle" align="center" <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;From</b></td>
            <td valign="middle" align="center" <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;To</b></td>
            <td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Del.</b></td>
            <td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Att.</b></td>
            <td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>DL</b></td>
            <td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>%age</b></td>
        </tr>
        <?php
       }
       else{//if consolidated display is required
           ?>
         <tr>
            <td align="left" <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;#</b>
            <td valign="middle" align="left" width="30%" <?php echo $reportManager->getReportDataStyle()?>><b>Subject</b></td>
            <td valign="middle" width="15%" <?php echo $reportManager->getReportDataStyle()?>><b>Study Period</b></td>
            <td valign="middle" align="center" <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;From</b></td>
            <td valign="middle" align="center" <?php echo $reportManager->getReportDataStyle()?>><b>&nbsp;To</b></td>
            <td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Delivered</b></td>
            <td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Attended</b></td>
            <td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Leaves Taken</b></td>
            <td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Percentage</b></td>
            
        </tr>  
           
        <?php  
       }
            $recordCount = count($recordArray);
            if($recordCount >0 && is_array($recordArray) ) { 
                
                for($i=0; $i<$recordCount; $i++ ) {

                    if ($recordArray[$i]['studentName'] != '-1') {
                        $marksObtained = "0.00";
                    }
                    else {
                        $marksObtained = NOT_APPLICABLE_STRING;
                    }


                    if($recordArray[$i]['delivered'] > 0 ) {
                        if ($recordArray[$i]['dutyLeave'] != '') {
                            $recordArray[$i]['attended1'] = "".$recordArray[$i]['attended'] + $recordArray[$i]['dutyLeave']."";
                            $marksObtained =  ($recordArray[$i]['attended1']/$recordArray[$i]['delivered'])*100;
                            $marksObtained = number_format($marksObtained, 2, '.', ' ');
                        }
                        else {
                            $marksObtained =  ($recordArray[$i]['attended']/$recordArray[$i]['delivered'])*100;
                            $marksObtained = number_format($marksObtained, 2, '.', ' ');
                        }
                    }

                    if ($recordArray[$i]['dutyLeave'] == 'null' || $recordArray[$i]['dutyLeave'] == '') {
                        $recordArray[$i]['dutyLeave'] = NOT_APPLICABLE_STRING;
                    }

                    if($recordArray[$i]['studentName'] != '-1') {
                        $recordArray[$i]['fromDate'] = UtilityManager::formatDate($recordArray[$i]['fromDate']);    
                        $recordArray[$i]['toDate'] = UtilityManager::formatDate($recordArray[$i]['toDate']);
                    }
                    
                  if($REQUEST_DATA['consolidatedView']==1){ //if group wise display is required  
                    echo "<tr><td ".$reportManager->getReportDataStyle().">".($i+1)."</td>
                              <td ".$reportManager->getReportDataStyle().">".$recordArray[$i]['subject']."</td>
                              <td".$reportManager->getReportDataStyle().">".$recordArray[$i]['periodName']."</td>     
                              <td".$reportManager->getReportDataStyle().">".$recordArray[$i]['groupName']."</td>     
                              <td align='left'".$reportManager->getReportDataStyle()." nowrap>".$recordArray[$i]['employeeName']."</td>     
                              <td align='center'".$reportManager->getReportDataStyle()." nowrap>".$recordArray[$i]['fromDate']."</td>     
                              <td align='center'".$reportManager->getReportDataStyle()." nowrap>".$recordArray[$i]['toDate']."</td>     
                              <td align='right'".$reportManager->getReportDataStyle().">".$recordArray[$i]['delivered']."</td>     
                              <td align='right'".$reportManager->getReportDataStyle().">".$recordArray[$i]['attended']."</td>
                              <td align='right'".$reportManager->getReportDataStyle().">".$recordArray[$i]['dutyLeave']."</td>
                              <td align='right'".$reportManager->getReportDataStyle().">".$marksObtained."</td>      
                          </tr>";
                  }
                 else {
                   echo "<tr><td ".$reportManager->getReportDataStyle().">".($i+1)."</td>
                              <td ".$reportManager->getReportDataStyle().">".$recordArray[$i]['subject']."</td>
                              <td".$reportManager->getReportDataStyle().">".$recordArray[$i]['periodName']."</td>
                              <td align='center'".$reportManager->getReportDataStyle()." nowrap>".$recordArray[$i]['fromDate']."</td>
                              <td align='center'".$reportManager->getReportDataStyle()." nowrap>".$recordArray[$i]['toDate']."</td>
                              <td align='right'".$reportManager->getReportDataStyle().">".$recordArray[$i]['delivered']."</td>
                              <td align='right'".$reportManager->getReportDataStyle().">".$recordArray[$i]['attended']."</td>
                              <td align='right'".$reportManager->getReportDataStyle().">".$recordArray[$i]['dutyLeave']."</td>
                              <td align='right'".$reportManager->getReportDataStyle().">".$marksObtained."</td>
                          </tr>";  
                 } 
                }  
            }
            else {
                echo '<tr><td colspan="11" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>';
            }
            echo  '</tr>';
            ?>          
        </table> <br>
        <table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
        <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
        </tr>
        </table>
