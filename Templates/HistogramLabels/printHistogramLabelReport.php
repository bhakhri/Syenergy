 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/HistogramLabelManager.inc.php");
    $histogramLabelManager = HistogramLabelManager::getInstance();

	$histogramLabelRecordArray = $histogramLabelManager->getHistogramLabelList(); 
    
                           
                            $recordCount = count($histogramLabelRecordArray);
                            
                            $histogramLabelPrintArray[] =  Array();
                            if($recordCount >0 && is_array($histogramLabelRecordArray) ) { 
                                
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                   
                                    $j=$i+1;
                                    
                                    $histogramLabelPrintArray[$i]['srNo']   =$j;
                                    $histogramLabelPrintArray[$i]['histogramLabel']=$histogramLabelRecordArray[$i]['histogramLabel'];
								
                                }
                            }
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                'width="4%" align="center"', "align='center'");
    $reportTableHead['histogramLabel']		=    array('Histrogram Label ',         ' width=15% align="center" ','align="left" ');
  

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $histogramLabelPrintArray);
    $reportManager->showReport(); 

//$History : $
?>
