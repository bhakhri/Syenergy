<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Teacher Menu
//
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


require_once("common.inc.php");
require_once(BL_PATH . "/MenuCreationClassManager.inc.php");
$menuCreationManager = MenuCreationClassManager::getInstance();


$myInfoMenu = Array();
$menuCreationManager->addToAllMenus($myInfoMenu);
$menuCreationManager->setMenuHeading("My Info");

$employeeInfoMenu   =  Array(
                                            'moduleName'  => 'EmployeeInformation',
                                            'moduleLabel' => 'Employee Info',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/employeeInfo.php',
                                            'accessArray' => Array(VIEW,ADD,EDIT,DELETE),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($employeeInfoMenu);
$teacherChangePasswordArray =   Array(
                                            'moduleName'  => 'TeacherChangePassword',
                                            'moduleLabel' => 'Change Password',
                                            'moduleLink'  =>  UI_HTTP_TEACHER_PATH . '/changePassword.php',
                                            'accessArray' => Array(EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
 $menuCreationManager->makeSingleMenu($teacherChangePasswordArray);
 $studentInfoArray = Array(
                                                 'moduleName'  => 'SearchStudentDisplay',
                                                 'moduleLabel' => 'Student Info',
                                                 'moduleLink'  => UI_HTTP_TEACHER_PATH.'/searchStudent.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($studentInfoArray);

$marksMenu = Array();
$menuCreationManager->addToAllMenus($marksMenu);
$menuCreationManager->setMenuHeading("Marks & Attendance");

$dailyAttendanceArray = Array(
                                            'moduleName'  => 'DailyAttendance',
                                            'moduleLabel' => 'Daily Attendance',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listDailyAttendance.php',
                                            'accessArray' => Array(VIEW,EDIT,DELETE),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($dailyAttendanceArray);

$enterAssignmentMarksMasterArray = Array(
                                            'moduleName'  => 'EnterAssignmentMarksMaster',
                                            'moduleLabel' => 'Test Marks',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listEnterAssignmentMarks.php',
                                            'accessArray' => Array(VIEW,ADD,EDIT,DELETE),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($enterAssignmentMarksMasterArray);

$teacherBulkSubjectTopicArray = Array(
                                            'moduleName'  => 'TeacherBulkSubjectTopic',
                                            'moduleLabel' => 'Bulk Subject Topic Master',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH .'/bulkListSubjectTopic.php',
                                            'accessArray' => Array(VIEW,ADD,EDIT,DELETE),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($teacherBulkSubjectTopicArray);
$classWiseAttendanceListArray = Array(
                                            'moduleName'  => 'ClassWiseAttendanceList',
                                            'moduleLabel' => 'Display Attendance',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listClasswiseAttendance.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($classWiseAttendanceListArray);
$bulkAttendanceArray = Array(
                                            'moduleName'  => 'BulkAttendance',
                                            'moduleLabel' => 'Bulk Attendance',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listBulkAttendance.php',
                                            'accessArray' => Array(VIEW,EDIT,DELETE),
                                            'description' => '',
                                            'helpUrl'     => 'teacher-display-attendance.jpg' ,
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($bulkAttendanceArray);
$copyAttendanceArray =  Array(
                                            'moduleName'  => 'CopyAttendance',
                                            'moduleLabel' => 'Copy Attendance',
                                            'moduleLink'  =>UI_HTTP_TEACHER_PATH . '/listCopyAttendance.php',
                                            'accessArray' =>Array(VIEW,EDIT,DELETE),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false
                                            );
 $menuCreationManager->makeSingleMenu($copyAttendanceArray);
 /*
$dutyLeavesAdvancedArray = Array(
                                            'moduleName'  => 'DutyLeavesAdvanced',
                                            'moduleLabel' => 'Student Duty Leaves Entry',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/dutyLeaveEntryAdvanced.php',
                                            'accessArray' => Array(VIEW,EDIT,DELETE),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
*/
//$menuCreationManager->makeSingleMenu($dutyLeavesAdvancedArray);

$graceMarksEntryArray = Array(
                                            'moduleName'  => 'GraceMarksEntry',
                                            'moduleLabel' => 'Grace Marks',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/graceMarks.php',
                                            'accessArray' => Array(VIEW,EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($graceMarksEntryArray);
$classWiseGradeListArray = Array(
                                            'moduleName'  => 'ClassWiseGradeList',
                                            'moduleLabel' => 'Display Marks',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listClasswiseGrade.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($classWiseGradeListArray);
 $displayFinalInternalReportArray = Array(
                                            'moduleName'  => 'DisplayFinalInternalReport',
                                            'moduleLabel' => 'Display Final Internal Marks Report',
                                            'moduleLink'  => UI_HTTP_PATH . '/finalInternalReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($displayFinalInternalReportArray);
/* $finalMarksFoxproReportArray =  Array(
                                            'moduleName'  => 'FinalMarksFoxproReport',
                                            'moduleLabel' => 'Display Consolidated Internal Marks Report',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/teacherInternalFinalMarksReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false
                                            );
 $menuCreationManager->makeSingleMenu($finalMarksFoxproReportArray);    */
 $subjectWiseTopicTaughtArray = Array(
                                            'moduleName'  => 'SubjectWiseTopicTaught',
                                            'moduleLabel' => 'Display Subject Wise Topic Taught Report',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/teacherTopicCoveredReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($subjectWiseTopicTaughtArray);
 /* $consolidatedInternalMarksReportArray =  Array(
                                            'moduleName'  => 'ConsolidatedInternalMarksReport',
                                            'moduleLabel' => 'Display Consolidated Internal Marks Report',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/teacherInternalFinalMarksReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false
                                            );
 $menuCreationManager->makeSingleMenu($consolidatedInternalMarksReportArray);       */
$percentageWiseAttendanceReportArray = Array(
                                                 'moduleName' => 'PercentageWiseAttendanceReport',
                                                 'moduleLabel' => 'Percentage Wise Attendance Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentPercentageWiseReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you check students who are falling above or below a certain student threshold',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                            );

$menuCreationManager->makeSingleMenu($percentageWiseAttendanceReportArray);


$uploadStudentExternalMarksArray = Array(
                                                 'moduleName' => 'UploadStudentExternalMarks',
                                                 'moduleLabel' => 'Upload External Marks',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentMarksUpload.php',  
                                                 'accessArray' => '',
                                                 'description' => 'Lets you enter the external( typically the final university exam ) marks for students',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                            );
$menuCreationManager->makeSingleMenu($uploadStudentExternalMarksArray);


// new
$enterStudentExternalMarksArray = Array(
                                                 'moduleName' => 'MannualExternalMarks',
                                                 'moduleLabel' => 'Enter External Marks',
                                                 'moduleLink' => UI_HTTP_TEACHER_PATH . '/listEnterExternalMarks1.php',  
                                                 'accessArray' => '',
                                                 'description' => 'Lets you enter the external( typically the final university exam ) marks for students',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                            );
$menuCreationManager->makeSingleMenu($enterStudentExternalMarksArray);
//new


$communicationMenu = Array();
$menuCreationManager->addToAllMenus($communicationMenu);
$menuCreationManager->setMenuHeading("Communication");

 $sendMessageToStudentsArray = Array(
                                            'moduleName'  => 'SendMessageToStudents',
                                            'moduleLabel' => 'Send Message to Students',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listStudentMessage.php',
                                            'accessArray' => Array(VIEW,EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($sendMessageToStudentsArray);
 $sendMessageToParentsArray = Array(
                                            'moduleName'  => 'SendMessageToParents',
                                            'moduleLabel' => 'Send Message to Parents',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listParentMessage.php',
                                            'accessArray' => Array(VIEW,EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );


$menuCreationManager->makeSingleMenu($sendMessageToParentsArray);
$employeeMessageMasterArray = Array(
                                            'moduleName'  => 'EmployeeMessageMaster',
                                            'moduleLabel' => 'Send Message to Colleagues',
                                            'moduleLink'  =>   UI_HTTP_TEACHER_PATH . '/listEmployeeMessage.php',
                                            'accessArray' => Array(VIEW,EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false
                                            );
 $menuCreationManager->makeSingleMenu($employeeMessageMasterArray);
$adminMessageListArray = Array(
                                            'moduleName'  => 'AdminMessageList',
                                            'moduleLabel' => 'Admin Messages',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listAdminMessages.php',
                                            'accessArray' => Array(VIEW,ADD,EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($adminMessageListArray);
$courseResourceMasterArray = Array(
                                            'moduleName'  => 'CourseResourceMaster',
                                            'moduleLabel' => 'Upload Resource',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listCourseResource.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($courseResourceMasterArray);
$sendMessageParentMailBoxArray = Array(
                                            'moduleName'  => 'SendMessageParentMailBox',
                                            'moduleLabel' => 'Send Message to Parents Mail Box',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listParentMailBox.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($sendMessageParentMailBoxArray);
$instituteNoticeListArray = Array(
                                            'moduleName'  => 'InstituteNoticeList',
                                            'moduleLabel' => 'Display Institute Notices',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listInstituteNotice.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($instituteNoticeListArray);
$instituteEventListArray = Array(
                                            'moduleName'  => 'InstituteEventList',
                                            'moduleLabel' => 'Display Institute Events',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listInstituteEvent.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($instituteEventListArray);
$listTeacherCommentMasterArray = Array(
                                            'moduleName'  => 'ListTeacherCommentMaster',
                                            'moduleLabel' => 'Display Teacher Comments',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listTeacherComment.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($listTeacherCommentMasterArray);

$registrationReportArray = Array(
                                                 'moduleName' => 'StudentRegistrationReport',                                        //not there
                                                 'moduleLabel' => 'Student Registration Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/RegistrationForm/registrationReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                );        
$menuCreationManager->makeSingleMenu($registrationReportArray);                                

$teacherTimeTableMenu = Array(
                                                 'moduleName'  => 'TeacherTimeTableDisplay',
                                                 'moduleLabel' => 'Timetable',
                                                 'moduleLink'  => UI_HTTP_TEACHER_PATH.'/listTimeTable.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($teacherTimeTableMenu);

$teacherFineMenu = Array();
$menuCreationManager->addToAllMenus($teacherFineMenu);
$menuCreationManager->setMenuHeading("Fine");

$fineStudentMasterArray = Array(
                                                 'moduleName' => 'FineStudentMaster',                            //not there
                                                 'moduleLabel' =>'Student Fine/Activity Master',
                                                 'moduleLink' => UI_HTTP_PATH . '/listFineStudent.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($fineStudentMasterArray);

$bulkFineStudentMasterArray = Array(
                                                 'moduleName' => 'BulkFineStudentMaster',                        //not there
                                                 'moduleLabel' =>'Student Bulk Fine Master',
                                                 'moduleLink' => UI_HTTP_PATH . '/bulkFineStudent.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($bulkFineStudentMasterArray);

/*
$fineStudentMasterArray = Array(
                                            'moduleName'  => 'FineStudentMaster',
                                            'moduleLabel' => 'Student Fine',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listFineStudent.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true
                            );
$menuCreationManager->makeSingleMenu($fineStudentMasterArray);

$bulkFineStudentMasterArray = Array(
                                            'moduleName'  => 'BulkFineStudentMaster',
                                            'moduleLabel' => 'Student Bulk Fine Master',
                                            'moduleLink'  => UI_HTTP_PATH . '/bulkFineStudent.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($bulkFineStudentMasterArray);
*/


$teacherReportsMenu = Array();
$menuCreationManager->addToAllMenus($teacherReportsMenu);
$menuCreationManager->setMenuHeading("Reports");

$subjectWisePerformanceReportArray = Array(
                                            'moduleName'  => 'SubjectWisePerformanceReport',
                                            'moduleLabel' => 'Test wise performance report',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listSubjectPerformance.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($subjectWisePerformanceReportArray);
$testWisePerformanceComparisonReportArray = Array(
                                            'moduleName'  => 'TestWisePerformanceComparisonReport',
                                            'moduleLabel' => 'Test wise performance comparison report',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listSubjectPerformanceComparison.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($testWisePerformanceComparisonReportArray);
$groupWisePerformanceReportArray = Array(
                                            'moduleName'  => 'GroupWisePerformanceReport',
                                            'moduleLabel' => 'Group wise performance report',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listGroupWisePerformance.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($groupWisePerformanceReportArray);
$subjectWisePerformanceComparisonReportArray = Array(
                                            'moduleName'  => 'SubjectWisePerformanceComparisonReport',
                                            'moduleLabel' => 'Subject wise performance report',
                                            'moduleLink'  =>  UI_HTTP_TEACHER_PATH . '/listSubjectWisePerformance.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$menuCreationManager->makeSingleMenu($subjectWisePerformanceComparisonReportArray);

$teacherAttendanceRegisterArray = Array(
                                                 'moduleName' => 'AttendanceRegister',
                                                 'moduleLabel' => 'Attendance Register',
                                                 'moduleLink' => UI_HTTP_PATH.'/attendanceRegister.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you view  the attendance for a class in the attendance register format',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                 );

$teacherAttendanceRegisterArray = Array(
                                            'moduleName'  => 'TeacherAttendanceRegister',
                                            'moduleLabel' => 'Attendance Register',
                                            'moduleLink'  =>  UI_HTTP_TEACHER_PATH . '/attendanceRegister.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false
                            );


$menuCreationManager->makeSingleMenu($teacherAttendanceRegisterArray);
$assignmentReportArray = Array(
                                            'moduleName'  => 'AssignmentReport',
                                            'moduleLabel' => 'Assignment Report',
                                            'moduleLink'  =>  UI_HTTP_PATH . '/assignmentReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($assignmentReportArray);
$feedbackTeacherDetailedGPAReportAdvancedArray = Array(
                                            'moduleName'  => 'FeedbackTeacherDetailedGPAReportAdvanced',
                                            'moduleLabel' => 'Feedback Teacher Detailed GPA Report (Advanced)',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listFeedbackTeacherDetailedGpaReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($feedbackTeacherDetailedGPAReportAdvancedArray);
$feedbackTeacherFinalReportAdvancedArray = Array(
                                            'moduleName'  => 'FeedbackTeacherFinalReportAdvanced',
                                            'moduleLabel' => 'Feedback Teacher Final Report (Advanced)',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listFeedbackTeacherFinalReport.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );

$menuCreationManager->makeSingleMenu($feedbackTeacherFinalReportAdvancedArray);





  $teacherFeedbackMenu = Array(
                                                 'moduleName'  => 'ADVFB_ProvideFeedBack',
                                                 'moduleLabel' => 'Feedback',
                                                 'moduleLink'  => UI_HTTP_PATH.'/provideFeedbackAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
	$menuCreationManager->makeHeadingMenu($teacherFeedbackMenu);

  $teacherGeneralSurveyMenu = Array(
                                                 'moduleName'  => 'ProvideGeneralSurvey',
                                                 'moduleLabel' => 'General Survey',
                                                 'moduleLink'  => UI_HTTP_TEACHER_PATH . '/scGeneralFeedBack.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($teacherGeneralSurveyMenu);

	if (defined('INCLUDE_LEAVE') and INCLUDE_LEAVE == true) {

          $teacherLeaveMenu = Array();
          $menuCreationManager->addToAllMenus($teacherLeaveMenu);
          $menuCreationManager->setMenuHeading("Leaves");
          $applyEmployeeLeaveArray = Array(
                                            'moduleName'  => 'ApplyEmployeeLeave',
                                            'moduleLabel' => 'Apply Leaves',
                                            'moduleLink'  => UI_HTTP_PATH . '/applyEmployeeLeave.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
          $menuCreationManager->makeSingleMenu($applyEmployeeLeaveArray);
          $authorizeEmployeeLeaveArray = Array(
                                            'moduleName'  => 'AuthorizeEmployeeLeave',
                                            'moduleLabel' => 'Authorize Leaves',
                                            'moduleLink'  => UI_HTTP_PATH . '/authorizeEmployeeLeave.php',
                                            'accessArray' => Array(VIEW,EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
          $menuCreationManager->makeSingleMenu($authorizeEmployeeLeaveArray);
	}

    if(defined('SHOW_EMPLOYEE_APPRAISAL_FORM') and SHOW_EMPLOYEE_APPRAISAL_FORM == 1) {
        $teacherAppraisalMenu = Array(
                                                 'moduleName'  => 'AppraisalForm',
                                                 'moduleLabel' => 'Appraisal',
                                                 'moduleLink'  => UI_HTTP_PATH.'/appraisalForm.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

        $menuCreationManager->makeHeadingMenu($teacherAppraisalMenu);
    }
    $teacherAssignmentMenu = Array(
                                                 'moduleName'  => 'AllocateAssignment',
                                                 'moduleLabel' => 'Give Assignment',
                                                 'moduleLink'  => UI_HTTP_PATH.'/Teacher/allocateAssignment.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($teacherAssignmentMenu);

    $moodleMenu   = Array();
    $moodleMenu[] = Array(MAKE_HEADING_MENU, "ShowMoodle, Moodle, ".UI_HTTP_MOODLE_PATH."/index.php",Array(VIEW));


    //$allTeacherMenus = array();
    $allTeacherMenus = $menuCreationManager->getAllMenus();
	 $allTeacherMenus[] = $moodleMenu;
     $allModuleLabelArray=array();


     //Added for autosuggest to work
     foreach($allTeacherMenus as $independentMenu) {
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
// Code for autosuggest ends


	 /*

	$allTeacherMenus[] = $employeeInfoMenu;
    //$allTeacherMenus[] = $myInfoMenu;
	$allTeacherMenus[] = $studentInfoMenu;
    $allTeacherMenus[] = $marksMenu;
    // $allTeacherMenus[] = $communicationMenu;
	$allTeacherMenus[] = $teacherMessageMenu;
	//$allTeacherMenus[] = $teacherNoticeMenu;
	$allTeacherMenus[] = $teacherTimeTableMenu;
	$allTeacherMenus[] = $teacherFineMenu;
	$allTeacherMenus[] = $teacherReportsMenu;
	$allTeacherMenus[] = $teacherFeedbackMenu;


	if (defined('INCLUDE_LEAVE') and INCLUDE_LEAVE == true) {
		$allTeacherMenus[] = $teacherLeaveMenu;
	}


    if (defined('SHOW_EMPLOYEE_APPRAISAL_FORM') and SHOW_EMPLOYEE_APPRAISAL_FORM == 1) {
        $allTeacherMenus[] = $teacherAppraisalMenu;
    }
    */

	//$allTeacherMenus[] = $teacherAssignmentMenu;
	//$allTeacherMenus[] = $teacherPasswordMenu;



 //$History: teacherMenuItems.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 3/17/10    Time: 12:19p
//Updated in $/LeapCC/Library
//attendanceRegister menu added in report
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library
//Updated menu according to the present menu
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/23/10    Time: 3:12p
//Updated in $/LeapCC/Library
//append UI_HTTP_TEACHER_PATH with file name
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 5:16p
//Updated in $/LeapCC/Library
//updated menu names
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:02a
//Created in $/LeapCC/Library
//Intial Checkin to implement 'Teacher,Parent,student and management'
//role permission
?>
