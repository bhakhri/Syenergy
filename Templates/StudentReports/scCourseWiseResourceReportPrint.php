<?php
//This file is used as printing version for coursewise resource report.
//
// Author :Parveen Sharma
// Created on : 22-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CoursewiseResourceReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
    
    function trim_output($str,$maxlength='250',$rep='...'){
       $ret=chunk_split($str,60);
       if(strlen($ret) > $maxlength){
         $ret=substr($ret,0,$maxlength).$rep; 
       }
       return $ret;  
    }
    
    $subjectId  = $REQUEST_DATA['subjectId'];
    $sortOrderBy =  $REQUEST_DATA['sortOrderBy'];
    $sortField   =  $REQUEST_DATA['sortField'];
    
   // Findout Subject
    if($subjectId!='') {
        $subCode = 'All';
        if ($subjectId != 'All') {
            $subCodeArray = $studentReportsManager->getSingleField(" `subject` sub, subject_type st ", 
                                                                   " sub.subjectName, sub.subjectCode, st.subjectTypeName ", 
                                                                   " WHERE st.subjectTypeId = sub.subjectTypeId AND subjectId = $subjectId");
            $subType = $subCodeArray[0]['subjectTypeName'];
            $subCode = $subCodeArray[0]['subjectCode'];
            $subName = $subCodeArray[0]['subjectName'];
        }
    } 
    
    $headingReport = "<b>Course:</b>&nbsp;".$subName." (".$subCode.")";
    
    $orderBy = "$sortField $sortOrderBy";         

    $totalArray          = $studentManager->getTotalCourseResource($subjectId);
    $resourceRecordArray = $studentManager->getCourseResourceList($subjectId,$orderBy,'');

    $cnt = count($resourceRecordArray);

    for($i=0;$i<$cnt;$i++) {
       $fileStr=(strip_slashes($resourceRecordArray[$i]['attachmentFile'])==-1 ? 'No' : 'Yes');    
       $urlStr=(strip_slashes($resourceRecordArray[$i]['resourceUrl'])=='-1' ? 
                NOT_APPLICABLE_STRING : 
                strip_slashes($resourceRecordArray[$i]['resourceUrl']));
       $resourceRecordArray[$i]['postedDate']=UtilityManager::formatDate($resourceRecordArray[$i]['postedDate']);
       $valueArray[] = array('srNo' => $i+1 ,
                             'subject'  => $resourceRecordArray[$i]['subject'] ,
                             'description'  => $resourceRecordArray[$i]['description'],
                             'resourceName'  => $resourceRecordArray[$i]['resourceName'],
                             'postedDate'  => $resourceRecordArray[$i]['postedDate'],
                             'resourceLink'=>$urlStr,
                             'attachmentLink'=>$fileStr,
                             'employeeName' => $resourceRecordArray[$i]['employeeName']
                           );                                        
     }
                                  
   // print_r($valueArray);
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Coursewise Resource Report');
    $reportManager->setReportInformation($headingReport);

    $reportTableHead                 = array();
             //associated key                  col.label,      col. width,                   data align
    $reportTableHead['srNo']           = array('#',            'width=4%    align="left"',    'align="left"');
    $reportTableHead['subject']        = array('Course',       'width=12%    align="left"',   'align="left"');
    $reportTableHead['description']    = array('Description',  'width="20%"  align="left" ',  'align="left"');
    $reportTableHead['resourceName']   = array('Type',         'width="8%"  align="left"',   'align="left"');
    $reportTableHead['postedDate']     = array('Date',         'width="8%"   align="center"',   'align="center"');
    $reportTableHead['resourceLink']   = array('Link',         'width="14%"   align="left"',   'align="left"');
    $reportTableHead['attachmentLink'] = array('Attachment',   'width="8%" align="left"',     'align="left"');
    $reportTableHead['employeeName']   = array('Teacher Name', 'width="12%"  align="left"',   'align="left"');
   

    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();

 
//$History: scCourseWiseResourceReportPrint.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 5:18p
//Updated in $/LeapCC/Templates/StudentReports
//RESOLVED ISSUES 2196,2195,2194,2191,2192
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 12:30p
//Updated in $/LeapCC/Templates/StudentReports
//role permission added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/17/09    Time: 12:10p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/01/09   Time: 15:39
//Created in $/SnS/Templates/StudentReports
//Added "Coursewise resource report" module
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/04/08   Time: 12:48p
//Updated in $/Leap/Source/Templates/ScStudentReports
//condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/04/08   Time: 11:33a
//Updated in $/Leap/Source/Templates/ScStudentReports
//sorting format setting
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/08   Time: 4:12p
//Created in $/Leap/Source/Templates/ScStudentReports
//coursewise resource file added 
//
//


?>   
