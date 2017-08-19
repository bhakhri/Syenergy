<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Student Menu
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
  require_once("common.inc.php");
require_once(BL_PATH . "/MenuCreationClassManager.inc.php");
$menuCreationManager = MenuCreationClassManager::getInstance();	
$managementStudentMenuArray = Array(
                                                 'moduleName'  => 'ManagementStudentInfo',
                                                 'moduleLabel' => 'Student Info',
                                                 'moduleLink'  => UI_HTTP_MANAGEMENT_PATH.'/allDetailsReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
$menuCreationManager->makeHeadingMenu($managementStudentMenuArray);         
$managementEmployeeMenuArray = Array(
                                                 'moduleName'  => 'ManagementEmployeeInfo',
                                                 'moduleLabel' => 'Employee Info',
                                                 'moduleLink'  => UI_HTTP_MANAGEMENT_PATH.'/allEmployeeDetailsReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
$menuCreationManager->makeHeadingMenu($managementEmployeeMenuArray);         
$managementFeesMenuArray = Array(
                                                 'moduleName'  => 'ManagementFeesInfo',
                                                 'moduleLabel' => 'Fee info',
                                                 'moduleLink'  => UI_HTTP_MANAGEMENT_PATH.'/allFeesDetailsReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
$menuCreationManager->makeHeadingMenu($managementFeesMenuArray);   	
$managementNoticeMenuArray = Array(
                                                 'moduleName'  => 'ManagementNoticeInfo',
                                                 'moduleLabel' => 'Notice Info',
                                                 'moduleLink'  => UI_HTTP_MANAGEMENT_PATH.'/allNoticeDetailsReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
$menuCreationManager->makeHeadingMenu($managementNoticeMenuArray);   	
$managementEventMenuArray  = Array(
                                                 'moduleName'  => 'ManagementEventInfo',
                                                 'moduleLabel' => 'Event Info',
                                                 'moduleLink'  => UI_HTTP_MANAGEMENT_PATH.'/allEventDetailsReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
$menuCreationManager->makeHeadingMenu($managementEventMenuArray);   	
$managementAdmissionMenuArray  = Array(
                                                 'moduleName'  => 'ManagementAdmissionInfo',
                                                 'moduleLabel' => 'Admission Info',
                                                 'moduleLink'  => UI_HTTP_MANAGEMENT_PATH.'/admissionDetailsReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
$menuCreationManager->makeHeadingMenu($managementAdmissionMenuArray);   	
$managementNoticeDisplayMenu = Array();
$menuCreationManager->addToAllMenus($managementNoticeDisplayMenu);
$menuCreationManager->setMenuHeading("Institute Notices");

$managementInstituteNoticesArray   =  Array(
                                            'moduleName'  => 'AddNotices',
                                            'moduleLabel' => 'Manage Notices',
                                            'moduleLink'  => UI_HTTP_PATH.'/listNotice.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($managementInstituteNoticesArray);   
$managementInstituteEventsArray   =  Array(
                                            'moduleName'  => 'ManagementInstituteEvents',
                                            'moduleLabel' => 'Manage Events',
                                            'moduleLink'  => UI_HTTP_PATH.'/listCalendar.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($managementInstituteEventsArray);   
  $managementMessagingMenu = Array();
$menuCreationManager->addToAllMenus(  $managementMessagingMenu);
$menuCreationManager->setMenuHeading("Messaging");
$sendMessageToStudentsArray   =  Array(
                                            'moduleName'  => 'SendMessageToStudents',
                                            'moduleLabel' => 'Send Message to Students',
                                            'moduleLink'  => UI_HTTP_PATH . '/listAdminStudentMessage.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',


                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($sendMessageToStudentsArray );   
 $sendMessageToParentsArray   =  Array(
                                            'moduleName'  => 'SendMessageToParents',
                                            'moduleLabel' => 'Send Message to Parents',
                                            'moduleLink'  => UI_HTTP_PATH . '/listAdminParentMessage.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu( $sendMessageToParentsArray);   
 $sendMessageToEmployeesArray   =  Array(
                                            'moduleName'  => 'SendMessageToEmployees',
                                            'moduleLabel' => 'Send Message to Employees',
                                            'moduleLink'  => UI_HTTP_PATH . '/listAdminEmployeeMessage.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($sendMessageToEmployeesArray);   
	 $sendMessageParentMailBoxArray   =  Array(
                                            'moduleName'  => 'SendMessageParentMailBox',
                                            'moduleLabel' => 'Send Message to Parents Mail Box',
                                            'moduleLink'  => UI_HTTP_PATH . '/listParentMailBox.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu( $sendMessageParentMailBoxArray);  
$managementReportsMenu = Array();
$menuCreationManager->addToAllMenus( $managementReportsMenu);
$menuCreationManager->setMenuHeading("Reports");
$messagesListArray   =  Array(
                                            'moduleName'  => 'MessagesList',
                                            'moduleLabel' => 'Messages List',
                                            'moduleLink'  => UI_HTTP_PATH . '/smsDetailReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$messagesCountListArray   =  Array(
                                            'moduleName'  => 'MessagesCountList',
                                            'moduleLabel' => 'Messages Count List',
                                            'moduleLink'  => UI_HTTP_PATH . '/smsFullDetailReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$messagesArray = array();
$messagesArray[] = $messagesListArray;
$messagesArray[] = $messagesCountListArray ;
$menuCreationManager->makeMenu("Messages",$messagesArray );
$hostelListArray   =  Array(
                                            'moduleName'  => 'HostelList',
                                            'moduleLabel' => 'Hostel Detail Report',
                                            'moduleLink'  => UI_HTTP_PATH . '/hostelDetailReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$cleaningHistoryMasterArray   =  Array(
                                            'moduleName'  => 'CleaningHistoryMaster',
                                            'moduleLabel' => 'Cleaning History Report',
                                            'moduleLink'  => UI_HTTP_PATH . '/listCleaningHistory.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$hostelArray = array();
$hostelArray[] = $hostelListArray ;
$hostelArray[] = $cleaningHistoryMasterArray  ;
$menuCreationManager->makeMenu("Hostel",$hostelArray);
$studentListArray   =  Array(
                                            'moduleName'  => 'StudentList',
                                            'moduleLabel' => 'Student List',
                                            'moduleLink'  => UI_HTTP_PATH . '/listStudentLists.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($studentListArray);  
 $employeeListArray   =  Array(
                                            'moduleName'  => 'EmployeeList',
                                            'moduleLabel' => 'Employee List',
                                            'moduleLink'  => UI_HTTP_PATH . '/listEmployeeLists.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($employeeListArray);  
$roleWiseListArray   =  Array(
                                            'moduleName'  => 'RoleWiseList',
                                            'moduleLabel' => 'Role Wise User Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/roleWiseUserReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$roleArray = array();
$roleArray[] =$roleWiseListArray ;
$menuCreationManager->makeMenu("Role",$roleArray);
$studentAttendanceArray   =  Array(
                                            'moduleName'  => 'StudentAttendance',
                                            'moduleLabel' => 'Student Attendance Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/studentAttendanceReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$percentageWiseAttendanceReportArray   =  Array(
                                            'moduleName'  => 'PercentageWiseAttendanceReport',
                                            'moduleLabel' => 'Percentage Wise Attendance Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/studentPercentageWiseReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$attendanceStatusReportArray   =  Array(
                                            'moduleName'  => 'AttendanceStatusReport',
                                            'moduleLabel' => 'Last Attendance Taken Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/attendanceStatusReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$attendanceArray = array();
$attendanceArray[] = $studentAttendanceArray ;
$attendanceArray[] = $percentageWiseAttendanceReportArray  ;
$attendanceArray[] = $attendanceStatusReportArray;
$menuCreationManager->makeMenu("Attendence",$attendanceArray);
$testWiseMarksReportArray   =  Array(
                                            'moduleName'  => 'TestWiseMarksReport',
                                            'moduleLabel' => 'Test wise Marks Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/testWiseMarksReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$finalInternalReportArray   =  Array(
                                            'moduleName'  => 'FinalInternalReport',
                                            'moduleLabel' => 'Final Internal Marks Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/finalInternalReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$dateWiseTestReportArray   =  Array(
                                            'moduleName'  => 'DateWiseTestReport',
                                            'moduleLabel' => 'Date Wise Test Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/datewiseTestReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$studentRankArray   =  Array(
                                            'moduleName'  => 'StudentRank',
                                            'moduleLabel' => 'Student Exam Rankwise Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/studentRankWiseReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$marksStatusReportArray   =  Array(
                                            'moduleName'  => 'MarksStatusReport',
                                            'moduleLabel' => 'Marks Status Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/marksStatusReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$externalMarksReportArray   =  Array(
                                            'moduleName'  => 'ExternalMarksReport',
                                            'moduleLabel' => 'Student External Marks Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/studentExternalMarksReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$internalMarksFoxproReportArray   =  Array(
                                            'moduleName'  => 'InternalMarksFoxproReport',
                                            'moduleLabel' => 'Student Internal Marks Foxpro Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/internalMarksFoxproReport.php',
                                            'accessArray' => ARRAY(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$transferredMarksReportArray   =  Array(
                                            'moduleName'  => 'TransferredMarksReport',
                                            'moduleLabel' => 'Transferred Marks Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/tranferredMarksReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
/*$studentAcademicReportArray   =  Array(
                                            'moduleName'  => 'StudentAcademicReport',
                                            'moduleLabel' => 'Student Academic Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/academicPerformanceReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
*/
$studentAcademicPerformanceReportArray   =  Array(
                                            'moduleName'  => 'StudentAcademicPerformanceReport',
                                            'moduleLabel' => 'Student Academic Performance Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/studentAcademicPerformanceReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$testTypeDistributionReportArray   =  Array(
                                            'moduleName'  => 'TestTypeDistributionReport',
                                            'moduleLabel' => 'Test Type Distribution Consolidated Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/testTypeConsolidatedReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$testWiseMarksConsolidatedReportArray   =  Array(
                                            'moduleName'  => 'TestWiseMarksConsolidatedReport',
                                            'moduleLabel' => 'Test Type Category wise Detailed Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/testWiseMarksConsolidatedReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$subjectWisePerformanceComparisonReportArray   =  Array(
                                                           'moduleName'  => 'SubjectWisePerformanceComparisonReport',
                                                            'moduleLabel' => 'Subject Wise Performance Report (After Transfer)',
                                                             'moduleLink'  => UI_HTTP_PATH .'/Teacher/listSubjectWisePerformance.php',
                                                             'accessArray' => ARRAY(VIEW),
                                                             'description' => '',
                                                              'helpUrl'     => '',
                                                               'videoHelpUrl'=> '',
                                                               'showHelpBar' => true,
                                                               'showSearch' => false

                            );
$examinationReportsArray = array();
$examinationReportsArray[] = $testWiseMarksReportArray  ;
$examinationReportsArray[] = $finalInternalReportArray  ;
$examinationReportsArray[] = $dateWiseTestReportArray;
$examinationReportsArray[] = $studentRankArray ;
$examinationReportsArray[] = $marksStatusReportArray;
$examinationReportsArray[] = $externalMarksReportArray;
$examinationReportsArray[] = $internalMarksFoxproReportArray;
$examinationReportsArray[] = $transferredMarksReportArray;
//$examinationReportsArray[] = $studentAcademicReportArray ;
$examinationReportsArray[] = $studentAcademicPerformanceReportArray ;
$examinationReportsArray[] = $testTypeDistributionReportArray;
$examinationReportsArray[] = $testWiseMarksConsolidatedReportArray ;
$examinationReportsArray[] = $subjectWisePerformanceComparisonReportArray ; 
$menuCreationManager->makeMenu("Examination Reports",$examinationReportsArray);
$installmentDetailOfStudentsArray   =  Array(
                                            'moduleName'  => 'InstallmentDetailOfStudents',
                                            'moduleLabel' => 'Installment Detail of Students',
                                            'moduleLink'  => UI_HTTP_PATH .'/installmentDetail.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$feeCollectionArray   =  Array(
                                            'moduleName'  => 'FeeCollection',
                                            'moduleLabel' => 'Fee Collection',
                                            'moduleLink'  => UI_HTTP_PATH .'/feeCollection.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$displayFeePaymentHistoryArray   =  Array(
                                            'moduleName'  => 'DisplayFeePaymentHistory',
                                            'moduleLabel' => 'Display Fee payment history',
                                            'moduleLink'  => UI_HTTP_PATH .'/paymentHistory.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$feeCollectionReportsArray = array();
$feeCollectionReportsArray[] = $installmentDetailOfStudentsArray ;
$feeCollectionReportsArray[] = $feeCollectionArray  ;
$feeCollectionReportsArray[] = $displayFeePaymentHistoryArray;
$menuCreationManager->makeMenu("Fee collection reports",$feeCollectionReportsArray);
$fineCollectionReportArray   =  Array(
                                            'moduleName'  => 'FineCollectionReport',
                                            'moduleLabel' => 'Category Wise Fine Collection Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/listFineCollectionReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
 $studentWiseFineCollectionReportArray   =  Array(
                                            'moduleName'  => 'StudentWiseFineCollectionReport',
                                            'moduleLabel' => 'Student Wise Fine Collection Summary Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/listStudentWiseFineCollectionReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
 $studentDetailFineCollectionReportArray   =  Array(
                                            'moduleName'  => 'StudentDetailFineCollectionReport',
                                            'moduleLabel' => 'Student Detail Fine Collection Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/listStudentDetailFineCollectionReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$studentFineHistoryReportArray   =  Array(
                                            'moduleName'  => 'StudentFineHistoryReport',
                                            'moduleLabel' => 'Fine Payment History Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/fineHistory.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
	$fineArray = array();
	$fineArray[] = $fineCollectionReportArray;
	$fineArray[] = $studentWiseFineCollectionReportArray   ;
	$fineArray[] = $studentDetailFineCollectionReportArray;
	$fineArray[] = $studentFineHistoryReportArray;
$menuCreationManager->makeMenu("Fine",$fineArray);
$employeeFeedbackReportArray   =  Array(
                                            'moduleName'  => 'EmployeeFeedbackReport',
                                            'moduleLabel' => 'Teacher Survey Feedback',
                                            'moduleLink'  => UI_HTTP_PATH .'/teacherFeedbackReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$generalFeedbackReportArray   =  Array(
                                            'moduleName'  => 'GeneralFeedbackReport',
                                            'moduleLabel' => 'General Survey Feedback',
                                            'moduleLink'  => UI_HTTP_PATH .'/generalFeedbackReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$feedbackReportsArray = array();
	$feedbackReportsArray[] = $employeeFeedbackReportArray ;
	$feedbackReportsArray[] = $generalFeedbackReportArray ;
$menuCreationManager->makeMenu("Feedback Reports",$feedbackReportsArray);
$courseWiseResourceReportArray   =  Array(
                                            'moduleName'  => 'CoursewiseResourceReport',
                                            'moduleLabel' => 'Coursewise Resources Report',
                                            'moduleLink'  => UI_HTTP_PATH.'/scCourseWiseResourceReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($courseWiseResourceReportArray);  
$studentConsolidatedReportArray   =  Array(
                                            'moduleName'  => 'StudentConsolidatedReport',
                                            'moduleLabel' => 'Student Consolidated Report',
                                            'moduleLink'  => UI_HTTP_PATH.'/studentConsolidatedReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false
                            );
$menuCreationManager->makeSingleMenu($studentConsolidatedReportArray );  
$teacherConsolidatedReportArray   =  Array(
                                            'moduleName'  => 'TeacherConsolidatedReport',
                                            'moduleLabel' => 'Teacher Consolidated Report',
                                            'moduleLink'  => UI_HTTP_PATH.'/teacherWiseConsolidatedReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($teacherConsolidatedReportArray );  
$teacherWiseTopicTaughtArray   =  Array(
                                            'moduleName'  => 'TeacherWiseTopicTaught',
                                            'moduleLabel' => 'Teacher Topic Taught Report',
                                            'moduleLink'  => UI_HTTP_PATH.'/teacherTopicCoveredReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($teacherWiseTopicTaughtArray);  
$offenseReportArray   =  Array(
                                            'moduleName'  => 'OffenseReport',
                                            'moduleLabel' => 'Offense Report',
                                            'moduleLink'  => UI_HTTP_PATH.'/listOffenseReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($offenseReportArray);  
$insuranceDueReportArray   =  Array(
                                            'moduleName'  => 'InsuranceDueReport',
                                            'moduleLabel' => 'Insurance Due Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/listInsuranceDueReport.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$fuelUsageArray   =  Array(
                                            'moduleName'  => 'FuelUsageReport',
                                            'moduleLabel' => 'Fuel Usage Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/listFuelReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$busRepairCostArray   =  Array(
                                            'moduleName'  => 'BusRepairCost',
                                            'moduleLabel' => 'Bus Repair Cost Report',
                                            'moduleLink'  => UI_HTTP_PATH .'/listRepairCostReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$busReportsArray = array();
$busReportsArray[] = $insuranceDueReportArray  ;
$busReportsArray[] = $fuelUsageArray  ;
$busReportsArray[] = $busRepairCostArray;
$menuCreationManager->makeMenu("Fleet Management Report",$busReportsArray);
$consolidatedReportArray   =  Array(
                                            'moduleName'  => 'ConsolidatedReport',
                                            'moduleLabel' => 'Consolidated Report',
                                            'moduleLink'  => UI_HTTP_PATH.'/listConsolidatedDataReport.php',
                                            'accessArray' =>  Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($consolidatedReportArray);  
$displayStudentReappearReportArray   =  Array(
                                            'moduleName'  => 'DisplayStudentReappearReport',
                                            'moduleLabel' => 'Display Student Re-appear Report',
                                            'moduleLink'  => UI_HTTP_PATH.'/displayStudentInternalReappearReport.php',
                                            'accessArray' =>  Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($displayStudentReappearReportArray );
$displayBusPassReportArray   =  Array(
                                            'moduleName'  => 'DisplayBusPassReport',
                                            'moduleLabel' => 'Display Bus Pass Report',
                                            'moduleLink'  => UI_HTTP_PATH.'/displayBusPassReport.php',
                                            'accessArray' =>  Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($displayBusPassReportArray);
$managementPasswordMenuArray   =  Array(
                                            'moduleName'  => 'ManagementChangePassword',
                                            'moduleLabel' => 'Change Password',
                                            'moduleLink'  => UI_HTTP_PATH.'/changePassword.php',
                                            'accessArray' =>  Array(EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeHeadingMenu($managementPasswordMenuArray);
 $allManagementMenus = $menuCreationManager->getAllMenus(); 
 
  $allModuleLabelArray=array();
     
     
     //Added for autosuggest to work
     foreach($allManagementMenus as $independentMenu) {
    foreach($independentMenu as $menuItemArray) {
        if ($menuItemArray[0] == SET_MENU_HEADING) {
        }
        elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
             $moduleLabel = $menuItemArray[2][1];
             $moduleLink = $menuItemArray[2][2];
             $allModuleLabelArray[] = array('menuLabel'=>$moduleLabel,'menuLink'=>$moduleLink);
        }
        elseif($menuItemArray[0] == MAKE_MENU) {
            //$subInnerMenuCounter = 0;
            foreach($menuItemArray[2] as $moduleMenuItem) {
                $moduleLabel = $moduleMenuItem[1];
                $moduleLink = $moduleMenuItem[2];
                $allModuleLabelArray[] = array('menuLabel'=>$moduleLabel,'menuLink'=>$moduleLink);
            }
        }
        elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
            $moduleArray = $menuItemArray[1];
            $subMenuCounter = 0;
            list($moduleName, $menuLabel,$menuLink,$description) = explode(',',$moduleArray);
            $allModuleNameArray[] = $moduleName;
            $allModuleLabelArray[] = array('menuLabel'=>$menuLabel,'menuLink'=>$menuLink);
        }
    }
}
global $sessionHandler;
$sessionHandler->setSessionVariable("allModuleLabelArray",$allModuleLabelArray); 
 
 /*if ($sessionHandler->getSessionVariable('hasBreadCrumbs') == '') {
	$mainMenuCounter = 0;
	$subInnerMenuCounter = 0;
	
	$breadCrumbArray = array();
	$setMenuHeading = '';
	$makeSingleMenu = '';
	$makeHeadingMenu = '';
	$makeMenu = '';
	$menuText = '';
    $allModuleLabelArray=array(); 
    
	foreach($allManagementMenus as $independentMenu) {
		foreach($independentMenu as $menuItemArray) {
			if ($menuItemArray[0] == SET_MENU_HEADING) {
				$moduleLabel = $menuItemArray[1];
				$subMenuCounter = 0;
				$includeHeading = false;
				$mainMenuCounter++;
				$setMenuHeading = $moduleLabel;
			}
			elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
				$moduleName = $menuItemArray[2][0];
				$moduleLabel = $menuItemArray[2][1];
				$sessionModule = $sessionHandler->getSessionVariable($moduleName);
				if ((!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
					//continue;
				}
				else {
					if ($includeHeading == false) {
						$includeHeading = true;
					}
				}
				$moduleLink = $menuItemArray[2][2];
				$helpUrl = $menuItemArray[2][5];
				$videoHelpUrl = $menuItemArray[2][6];
				$subMenuCounter++;
				$makeSingleMenu = $moduleLabel;
				$menuText = $setMenuHeading .' &raquo; '.$makeSingleMenu;
				$breadCrumbArray[$moduleName] = $menuText;
			}
			elseif($menuItemArray[0] == MAKE_MENU) {
				$moduleHeadLabel = $menuItemArray[1];
				$includeSubHeading = false;
				$makeSingleMenu = $moduleHeadLabel;

				$subInnerMenuCounter = 0;
				foreach($menuItemArray[2] as $moduleMenuItem) {
					$moduleName = $moduleMenuItem[0];
					$sessionModule = $sessionHandler->getSessionVariable($moduleName);
					if ((!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
						continue;
					}
					else {
						if ($includeHeading == false) {
							$includeHeading = true;
						}
						if ($includeSubHeading == false) {
							$subMenuCounter++;
							$includeSubHeading = true;
						}
					}
					$moduleLabel = strip_tags($moduleMenuItem[1]);
					$moduleLink = $moduleMenuItem[2];
					$textHelpUrl = $moduleMenuItem[5];
					$videoHelpUrl = $moduleMenuItem[6];
					$subInnerMenuCounter++;
					$makeMenu = $moduleLabel;
					$menuText = $setMenuHeading .' &raquo; '.$makeSingleMenu . ' &raquo; '.$makeMenu;
					$breadCrumbArray[$moduleName] = $menuText;
				}
			}
			elseif($menuItemArray[0] == MAKE_HEADING_MENU) {
				$moduleArray = $menuItemArray[1];
				$subMenuCounter = 0;
				list($moduleName, $menuLabel,$menuLink) = explode(',',$moduleArray);
				$sessionModule = $sessionHandler->getSessionVariable($moduleName);
				if ((!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
					//continue;
				}
				$mainMenuCounter++;
				$setMenuHeading = $menuLabel;
				$breadCrumbArray[trim($moduleName)] = $setMenuHeading;
			}
		}
	}
	$_SESSION['breadCrumbArray'] = $breadCrumbArray;
	$sessionHandler->setSessionVariable('hasBreadCrumbs', true);
}

/* function getBreadCrumb() {
	return "<font color='black'>".$_SESSION['breadCrumbArray'][MODULE]."</font>";
}
 				   

function getHelpLinks() {
	global $menuCreationManager;
	$helpUrl = $menuCreationManager->getHelpUrl(MODULE);
	if ($helpUrl != '') {
		$helpUrl = "<a href='javascript:showHelp(\"$helpUrl\",1);' class='redLink2'>Help for this module</a>";
	}
	$videoHelpUrl = $menuCreationManager->getVideoHelpUrl(MODULE);
	if ($videoHelpUrl != '' and $helpUrl != '') {
		$helpUrl .= "&nbsp;||&nbsp;&nbsp;<a href='javascript:showHelp(\"$videoHelpUrl\",2);' class='redLink2'>How to use this module</a>";
	}
	else if($videoHelpUrl != ''  and $helpUrl == ''){
		$helpUrl .= "&nbsp;&nbsp;<a href='javascript:showHelp(\"$videoHelpUrl\",2);' class='redLink2'>How to use this module</a>";
	}
	return $helpUrl;
}
*/

	 
/*	$managementStudentMenu = Array();
	$managementStudentMenu[] = Array(MAKE_HEADING_MENU, "ManagementStudentInfo, Student Info, ".UI_HTTP_MANAGEMENT_PATH."/allDetailsReport.php",Array(VIEW));
 
	$managementEmployeeMenu = Array();
	$managementEmployeeMenu[] = Array(MAKE_HEADING_MENU, "ManagementEmployeeInfo, Employee Info, ".UI_HTTP_MANAGEMENT_PATH."/allEmployeeDetailsReport.php",Array(VIEW));

	$managementFeesMenu = Array();
	$managementFeesMenu[] = Array(MAKE_HEADING_MENU, "ManagementFeesInfo, Fees Info, ".UI_HTTP_MANAGEMENT_PATH."/allFeesDetailsReport.php",Array(VIEW));
	
	$managementNoticeMenu = Array();
	$managementNoticeMenu[] = Array(MAKE_HEADING_MENU, "ManagementNoticeInfo, Notice Info, ".UI_HTTP_MANAGEMENT_PATH."/allNoticeDetailsReport.php",Array(VIEW)); 
 
	$managementEventMenu = Array();
	$managementEventMenu[] = Array(MAKE_HEADING_MENU, "ManagementEventInfo, Event Info, ".UI_HTTP_MANAGEMENT_PATH."/allEventDetailsReport.php",Array(VIEW));

	$managementAdmissionMenu = Array();
	$managementAdmissionMenu[] = Array(MAKE_HEADING_MENU, "ManagementAdmissionInfo, Admission Info, ".UI_HTTP_MANAGEMENT_PATH."/admissionDetailsReport.php",Array(VIEW));

	$managementNoticeDisplayMenu = Array();
	$managementNoticeDisplayMenu[] = Array(SET_MENU_HEADING, "Institute Notices");
	$managementNoticeDisplayMenu[] = Array(MAKE_SINGLE_MENU, ">ManagementInstituteNotices", Array('ManagementInstituteNotices', 'Manage Events', UI_HTTP_PATH.'/listCalendar.php'));
	$managementNoticeDisplayMenu[] = Array(MAKE_SINGLE_MENU, ">ManagementInstituteEvents", Array('ManagementInstituteEvents', 'Manage Notices', UI_HTTP_PATH.'/listNotice.php')); 

    $managementMessagingMenu = Array();
    $managementMessagingMenu[] = Array(SET_MENU_HEADING, "Messaging");
    $managementMessagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToStudents", Array('SendMessageToStudents', 'Send Message to Students', UI_HTTP_PATH . '/listAdminStudentMessage.php'));
    $managementMessagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToParents", Array('SendMessageToParents', 'Send Message to Parents', UI_HTTP_PATH . '/listAdminParentMessage.php'));
    $managementMessagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToEmployees", Array('SendMessageToEmployees', 'Send Message to Employees', UI_HTTP_PATH . '/listAdminEmployeeMessage.php'));
    $managementMessagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageParentMailBox", Array('SendMessageParentMailBox', 'Send Message to Parents Mail Box', UI_HTTP_PATH . '/listParentMailBox.php'));
    
    $managementReportsMenu = Array();
    $managementReportsMenu[] = Array(SET_MENU_HEADING, "Reports");
    $managementReportsMenu[] = Array(MAKE_MENU, "Messages", Array(
                                                            Array('MessagesList','Messages List',UI_HTTP_PATH . '/smsDetailReport.php'), 
                                                            Array('MessagesCountList','Messages Count List',UI_HTTP_PATH . '/smsFullDetailReport.php')));
    $managementReportsMenu[] = Array(MAKE_MENU, "Hostel", Array(
                                                          Array('HostelList','Hostel Detail Report',UI_HTTP_PATH . '/hostelDetailReport.php'),
                                                          Array('CleaningHistoryMaster','Cleaning History Report',UI_HTTP_PATH . '/listCleaningHistory.php')));     
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "StudentList", Array('StudentList','Student List',UI_HTTP_PATH . '/listStudentLists.php'),ARRAY(VIEW));   
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "EmployeeList", Array('EmployeeList','Employee List',UI_HTTP_PATH . '/listEmployeeLists.php'),ARRAY(VIEW));   
    $managementReportsMenu[] = Array(MAKE_MENU, "Role", Array(
                                                        Array('RoleWiseList','Role Wise User Report',UI_HTTP_PATH . '/roleWiseUserReport.php',ARRAY(VIEW)),
                                                            ));                                            
    $managementReportsMenu[] = Array(MAKE_MENU, "Attendance", Array(
                                                              Array('StudentAttendance','Student Attendance Report',UI_HTTP_PATH . '/studentAttendanceReport.php'), 
                                                              Array('PercentageWiseAttendanceReport','Percentage Wise Attendance Report',UI_HTTP_PATH . '/studentPercentageWiseReport.php',ARRAY(VIEW)), 
                                                              Array('AttendanceStatusReport','Last Attendance Taken Report',UI_HTTP_PATH . '/attendanceStatusReport.php',ARRAY(VIEW)),
                                                             ));
    $managementReportsMenu[] = Array(MAKE_MENU, "Examination Reports", Array(
                                                                        Array('TestWiseMarksReport',                'Test wise Marks Report',        UI_HTTP_PATH . '/testWiseMarksReport.php',ARRAY(VIEW)), 
                                                                        Array('FinalInternalReport',        'Final Internal Marks Report',        UI_HTTP_PATH . '/finalInternalReport.php',ARRAY(VIEW)), 
                                                                        Array('DateWiseTestReport',                 'Date Wise Test Report',        UI_HTTP_PATH . '/datewiseTestReport.php',ARRAY(VIEW)),
                                                                        Array('StudentRank','Student Exam Rankwise Report','studentRankWiseReport.php',Array(VIEW))  ,
                                                                        Array('MarksStatusReport','Marks Status Report',UI_HTTP_PATH . '/marksStatusReport.php',ARRAY(VIEW)),
                                                                        Array('ExternalMarksReport','Student External Marks Report',UI_HTTP_PATH . '/studentExternalMarksReport.php',ARRAY(VIEW)),
                                                                        Array('InternalMarksFoxproReport','Student Internal Marks Foxpro Report',UI_HTTP_PATH . '/internalMarksFoxproReport.php',ARRAY(VIEW)),
                                                                        Array('TransferredMarksReport','Transferred Marks Report',UI_HTTP_PATH . '/tranferredMarksReport.php'),
                                                                        Array('StudentAcademicReport','Student Academic Report',UI_HTTP_PATH . '/academicPerformanceReport.php'),
                                                                        Array('StudentAcademicPerformanceReport', 'Student Academic Performance Report',        UI_HTTP_PATH . '/studentAcademicPerformanceReport.php',ARRAY(VIEW)),
                                                                        Array('TestTypeDistributionReport','Test Type Distribution Consolidated Report',UI_HTTP_PATH . '/testTypeConsolidatedReport.php'),
                                                                        Array('TestWiseMarksConsolidatedReport', 'Test Type Category wise Detailed Report',        UI_HTTP_PATH . '/testWiseMarksConsolidatedReport.php',ARRAY(VIEW)),
                                                                        Array('SubjectWisePerformanceComparisonReport',        'Subject Wise Performance Report (After Transfer)',        UI_HTTP_PATH . '/Teacher/listSubjectWisePerformance.php',ARRAY(VIEW)) 
                                                                        ));
    $managementReportsMenu[] = Array(MAKE_MENU, "Fee collection reports", Array(
                                                                        Array('InstallmentDetailOfStudents',            'Installment Detail of Students',        UI_HTTP_PATH . '/installmentDetail.php'), 
                                                                        Array('FeeCollection',    'Fee Collection',    UI_HTTP_PATH . '/feeCollection.php'), 
                                                                        Array('DisplayFeePaymentHistory', 'Display Fee payment history', UI_HTTP_PATH . '/paymentHistory.php')
                                                                        ));
    $managementReportsMenu[] = Array(MAKE_MENU, "Fine", Array(
                                                                        Array('FineCollectionReport', 'Category Wise Fine Collection Report', UI_HTTP_PATH .'/listFineCollectionReport.php',Array(VIEW)),
                                                                        Array('StudentWiseFineCollectionReport', 'Student Wise Fine Collection Summary Report', UI_HTTP_PATH .'/listStudentWiseFineCollectionReport.php',Array(VIEW)),
                                                                        Array('StudentDetailFineCollectionReport', 'Student Detail Fine Collection Report', UI_HTTP_PATH .'/listStudentDetailFineCollectionReport.php',Array(VIEW)),
                                                                        Array('StudentFineHistoryReport', 'Fine Payment History Report', UI_HTTP_PATH .'/fineHistory.php',Array(VIEW)),
                                                                        
                                                            ));                                                
    $managementReportsMenu[] = Array(MAKE_MENU, "Feedback Reports", Array(
                                                                        Array('EmployeeFeedbackReport',            'Teacher Survey Feedback',        UI_HTTP_PATH . '/teacherFeedbackReport.php',Array(VIEW)), 
                                                                        Array('GeneralFeedbackReport',    'General Survey Feedback',    UI_HTTP_PATH . '/generalFeedbackReport.php',Array(VIEW)), 
                                                            ));
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "CourseWiseResourceReport", Array('CoursewiseResourceReport','Coursewise Resources Report',UI_HTTP_PATH . '/scCourseWiseResourceReport.php'),ARRAY(VIEW));
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "StudentConsolidatedReport", Array('StudentConsolidatedReport','Student Consolidated Report',UI_HTTP_PATH . '/studentConsolidatedReport.php')); 
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "TeacherConsolidatedReport", Array('TeacherConsolidatedReport','Teacher Consolidated Report',UI_HTTP_PATH . '/teacherWiseConsolidatedReport.php')); 
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "TeacherWiseTopicTaught", Array('TeacherWiseTopicTaught','Teacher Topic Taught Report',UI_HTTP_PATH . '/teacherTopicCoveredReport.php',Array(VIEW))); 
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "OffenseReport", Array('OffenseReport','Offense Report',UI_HTTP_PATH . '/listOffenseReport.php',Array(VIEW)));    
    $managementReportsMenu[] = Array(MAKE_MENU, "Bus Reports", Array(
                                                                        Array('InsuranceDueReport','Insurance Due Report',         UI_HTTP_PATH . '/listInsuranceDueReport.php'), 
                                                                        Array('FuelUsage','Fuel Usage Report',                   UI_HTTP_PATH . '/listFuelReport.php',Array(VIEW)), 
                                                                        Array('BusRepairCost','Bus Repair Cost Report',         UI_HTTP_PATH . '/listRepairCostReport.php',Array(VIEW))

                                                                        
                                                            ));    
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "ConsolidatedReport", Array('ConsolidatedReport','Consolidated Report',UI_HTTP_PATH . '/listConsolidatedDataReport.php',Array(VIEW)));
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "DisplayStudentReappearReport", Array('DisplayStudentReappearReport','Display Student Re-appear Report',UI_HTTP_PATH . '/displayStudentInternalReappearReport.php',Array(VIEW)));
    $managementReportsMenu[] = Array(MAKE_SINGLE_MENU, "DisplayBusPassReport", Array('DisplayBusPassReport','Display Bus Pass Report',UI_HTTP_PATH . '/displayBusPassReport.php',Array(VIEW)));
        
    $managementPasswordMenu = Array();
	$managementPasswordMenu[] = Array(MAKE_HEADING_MENU, "ManagementChangePassword, Change Password, ".UI_HTTP_PATH."/changePassword.php",Array(EDIT));

	$allManagementMenus = Array($managementStudentMenu,$managementEmployeeMenu,$managementFeesMenu,$managementNoticeMenu,$managementEventMenu,$managementAdmissionMenu,$managementNoticeDisplayMenu,$managementMessagingMenu,$managementReportsMenu,$managementPasswordMenu); */
 
 //$History: managementMenuItems.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library
//Updated menu according to the present menu
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:02a
//Created in $/LeapCC/Library
//Intial Checkin to implement 'Teacher,Parent,student and management'
//role permission
?>
