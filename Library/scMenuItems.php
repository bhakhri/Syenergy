<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Menu
//
//
// Author :Ajinder Singh
// Created on : 06-Nov-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
		$setupMenu = Array();
		$setupMenu[] = Array(SET_MENU_HEADING, "Setup");
		$setupMenu[] = Array(MAKE_SINGLE_MENU, "ConfigMaster", Array('ConfigMaster','Config Master','listConfigs.php'));
		$setupMenu[] = Array(MAKE_MENU, "Address Masters", Array(
																		Array('CountryMaster','Country Master','listCountry.php'), 
																		Array('StateMaster','State Master','listState.php'),
																		Array('CityMaster','City Master','listCity.php')
																));

		$setupMenu[] = Array(MAKE_MENU, "Administrative Masters", Array(
																		Array('UniversityMaster','University Master','listUniversity.php'), 
																		Array('InstituteMaster','Institute Master','listInstitute.php'),
																		Array('RoomsMaster','Rooms Master','listRoom.php'),
																		Array('QuotaMaster','Quota Master','listQuota.php'),
																		Array('EmployeeMaster','Employee Master','listEmployee.php'),
																		Array('DesignationMaster','Designation Master','listDesignation.php'),
																		Array('DepartmentMaster','Department Master','listDepartment.php')
																));
		$setupMenu[] = Array(MAKE_MENU, "Exam Masters", Array(
																		Array('TestTypesMaster','Test Types Master','listTestType.php'), 
																		Array('SlabsMaster','Slabs Master','listSlabs.php'),
																		Array('EvaluationCrieteria','Evaluation Crieteria','listEvaluationCriteria.php'),
																		Array('RangeLevelMaster','Range Level Master','listRangeLevel.php'),
																));
		$setupMenu[] = Array(MAKE_MENU, "Class Masters", Array(
																		Array('DegreeMaster','Degree Master','listDegree.php'), 
																		Array('BranchMaster','Branch Master','listBranch.php'),
																		Array('BatchMaster','Batch Master','listBatch.php'),
																		Array('SessionMaster','Session Master','listSession.php'),
																		Array('PeriodicityMaster','Periodicity Master','listPeriodicity.php'),
																		Array('StudyPeriodMaster','Study Period Master','listStudyPeriod.php'),
																		Array('SectionMaster','Section Master','scListSection.php'),
																		Array('PeriodsMaster','Periods Master','listPeriods.php')
																));
		$setupMenu[] = Array(MAKE_MENU, "Grade Masters", Array(
																		Array('GradeMaster','Grade Master','listGrade.php'),
																		Array('GradingLabelMaster','Grading Label Master','scListGradingLabels.php'),
																		Array('GradingScaleMaster','Grading Scale Master','scListGradingScales.php')
																));
		$setupMenu[] = Array(MAKE_MENU, "Histogram Masters", Array(
																		Array('HistogramLabelMaster','Histogram Label Master','listHistogramLables.php'), 
																		Array('HistogramScaleMaster','Histogram Scale Master','listHistogramScale.php')
																));
		$setupMenu[] = Array(MAKE_MENU, "Academics Masters", Array(
																		Array('CourseTypesMaster',		'Course Types Master',			'scListCourseType.php'), 
																		Array('CourseCourse',			'Course Master',				'scListCourse.php'),
																		Array('LectureTypeMaster',		'Lecture Type Master',			'listLectureType.php'),
																		Array('AttendanceCodesMaster',	'Attendance Codes Master',		'listAttendanceCode.php'),
																));

		$setupMenu[] = Array(MAKE_MENU, "Bus Masters", Array(
																		Array('BusRouteMaster',		'Bus Route Master',			'listBusRoute.php'), 
																		Array('BusStopCourse',			'Bus Stop Master',			'listBusStop.php'),
																));
		$setupMenu[] = Array(MAKE_MENU, "Building Masters", Array(
																		Array('BuildingMaster',		'Building Master',			'listBuilding.php'), 
																		Array('BlockCourse',			'Block Master',			'listBlock.php'),
																));
		$setupMenu[] = Array(MAKE_MENU, "Hostel Masters", Array(
																		Array('HostelMaster',		'Hostel Master',			'listHostel.php'), 
																		Array('HostelRoomCourse',	'Hostel Room Master',		'listHostelRoom.php'),
																));

		$setupMenu[] = Array(MAKE_MENU, "Fee Masters", Array(
																		Array('FeeHeads',				'Fee Heads',			'listFeeHead.php'), 
																		Array('FeeHeadValues',			'Fee Head Values',		'listFeeHeadValues.php'), 
																		Array('FeeCycleFines',			'Fee Cycle Fines',		'listFeeCycleFine.php'), 
																		Array('FeeCycleMaster',			'Fee Cycle Master',		'listFeeCycle.php'), 
																		Array('FundAllocationMaster',	'Fund Allocation Master','listFeeFundAllocation.php'), 
																		Array('BankMaster',				'Bank Master',			'listBank.php'), 
																		Array('BankBranchMaster',		'Bank Branch Master',	'listBankBranch.php'),
																));

		$setupMenu[] = Array(MAKE_MENU, "Institute Setup", Array(
																		Array('CreateClass',						'Create Class',			'listClasses.php'), 
																		Array('AssignSectionsToStudents',			'Assign Sections to Students',		'scAssignSection.php'), 
																		Array('TransferMarks',						'Transfer Marks',		'scTransferMarks.php'), 
																		Array('ApplyGrade',						'Apply Grade',		'scApplyGrade.php'), 
																		Array('PromoteStudents',				'Promote Students',		'scPromoteStudents.php'), 
																		Array('AssignCourseToClass',				'Assign Course to Class',		'scAssignCourseToClass.php'), 
																		Array('AssignOptionalSubjectToStudents',	'Assign Optional Subject to Students',		'studentToOptionalSubjectMapping.php'), 
																		Array('CreateTimeTableLabels',				'Create Time Table Labels','listTimeTableLabel.php'), 
																		Array('AssociateTimeTableToClass',			'Associate Time Table to Class',			'assignTimeTableToClass.php'), 
																		Array('CreateTimeTable',					'Create Time Table',	'scCreateTimeTable.php'),
																		Array('CreateCalendar',						'Create Calendar',	'listCalendar.php'),
																		Array('ManageUsers',						'Manage Users',	'listManageUser.php'),
																		Array('RoleMaster',							'Role Master',	'listRole.php'),
																		Array('RolePermissions',					'Role Permissions',	'scRolePermission.php'),
																));
		$setupMenu[] = Array(MAKE_MENU, "Student Setup", Array(
																		Array('Admit',								         'Admit',	    'scAdmitStudent.php'), 
																		Array('AssignRollNumbers',			    'Assign Roll Numbers',	    'scAssignRollNo.php'), 
                                                                        Array('QuarantineStudentMaster',            'Delete Students',      'scListQuarantineStudent.php'), 
                                                                        Array('RestoreStudentMaster',               'Restore Students',     'scListRestoreStudent.php'), 
																));
	   
	   $setupMenu[] = Array(MAKE_MENU, "FeedBack Masters", Array(
																		Array('CreateFeedBackLabels',								         'Label Master',	    'listFeedBackLabel.php'), 
																		Array('FeedBackCategoryMaster',			    'Category Master',	    'listFeedBackCategory.php'), 
                                                                        Array('FeedBackGradesMaster',            'Grade Master',      'listFeedBackGrades.php'), 
                                                                        Array('FeedBackQuestionsMaster',               'Question Master',     'listFeedBackQuestions.php'), 
																));

			
		$studentInfoMenu = Array();
		$studentInfoMenu[] = Array(MAKE_HEADING_MENU, "StudentInfo, Student Info, scSearchStudent.php");
			
		
		$schedulingMenu = Array();
		$schedulingMenu[] = Array(SET_MENU_HEADING, "Scheduling");
		$schedulingMenu[] = Array(MAKE_SINGLE_MENU, "ManageTimeTable", Array('ManageTimeTable', 'Manage Time Table', 'scCreateTimeTable.php'));
		$schedulingMenu[] = Array(MAKE_SINGLE_MENU, "DisplayTeacherTimeTable", Array('DisplayTeacherTimeTable', 'Display Teacher Time Table', 'scDisplayTeacherTimeTable.php'));
		$schedulingMenu[] = Array(MAKE_SINGLE_MENU, "DisplayMasterTimeTable", Array('DisplayMasterTimeTable', 'Display Master Time Table', 'scDisplayMasterTimeTable.php'));


		$instituteMenu = Array();
		$instituteMenu[] = Array(SET_MENU_HEADING, "Institute Notices");
		$instituteMenu[] = Array(MAKE_SINGLE_MENU, "AddEvent", Array('AddEvent', 'Add Event', 'listCalendar.php'));
		$instituteMenu[] = Array(MAKE_SINGLE_MENU, "AddNotices", Array('AddNotices', 'Add Notices', 'listNotice.php'));

		$messagingMenu = Array();
		$messagingMenu[] = Array(SET_MENU_HEADING, "Messaging");
		$messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToStudents", Array('SendMessageToStudents', 'Send Message to Students', 'scListAdminStudentMessage.php'));
		//$messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendSmsToStudent", Array('SendSmsToStudent', 'Send SMS to Student', 'scListAdminStudentSMS.php'));
		$messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToEmployees", Array('SendMessageToEmployees', 'Send Message to Employees', 'scListAdminEmployeeMessage.php'));
		//$messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendSmsToEmployee", Array('SendSmsToEmployee', 'Send SMS to Employee', 'scListAdminEmployeeSMS.php'));


		$studentFeeMenu = Array();
		$studentFeeMenu[] = Array(SET_MENU_HEADING, "Student Fees");
		$studentFeeMenu[] = Array(MAKE_SINGLE_MENU, "CalculateFees", Array('CalculateFees', 'Calculate Fees', 'scCalculateFees.php'));
		$studentFeeMenu[] = Array(MAKE_SINGLE_MENU, "DisplayFeePaymentHistory", Array('DisplayFeePaymentHistory', 'Display Fee payment history', 'scPaymentHistory.php'));
		$studentFeeMenu[] = Array(MAKE_SINGLE_MENU, "CollectFees", Array('CollectFees', 'Collect Fees', 'scFeeReceipt.php'));
		$studentFeeMenu[] = Array(MAKE_SINGLE_MENU, "FeeReceiptStatus", Array('FeeReceiptStatus', 'Fee Receipt Status', 'scFeeReceiptStatus.php'));


		$reportsMenu[] = Array(SET_MENU_HEADING, "Reports");
        
        $reportsMenu[] = Array(MAKE_MENU, "Messages", Array(
                         Array('MessagesList',            'Messages List',             'SMSDetailReport.php'), 
                         Array('MessagesCountList',    'Messages Count List',          'SMSFullDetailReport.php') 
));
        
        
		$reportsMenu[] = Array(MAKE_SINGLE_MENU, "AdvancedStudentFilter", Array('AdvancedStudentFilter','Advanced Student Filter','scAllDetailsReport.php'));
		$reportsMenu[] = Array(MAKE_MENU, "Student", Array(
																		Array('StudentList','Student List','scListStudentLists.php'), 
																		//Array('StudentPhotoLists',		'Student Photo Lists',			'listStudentInformationReport.php'), 
																		//Array('BusPass',		'Bus Pass',			'listStudentInformationReport.php'), 
																		//Array('HostelCard',		'Hostel Card',			'listStudentInformationReport.php'), 
																		//Array('ICard',		'I-Card',			'listStudentInformationReport.php'), 
																		//Array('IdentityReports',		'Identity Reports',			'listStudentInformationReport.php'), 
																		//Array('Label',		'Label',			'studentLabels.php'), 
																		//Array('LibraryCard',		'Library Card',			'listStudentInformationReport.php'), 
															 ));

		$reportsMenu[] = Array(MAKE_MENU, "Employee", Array(
																		Array('EmployeeList',        'Employee List',            'scListEmployeeLists.php'), 
															 ));
		$reportsMenu[] = Array(MAKE_MENU, "Attendance", Array(
																		Array('StudentAttendance',								'Student Attendance',			'scStudentAttendanceReport.php'), 
																		//Array('AssignRollNumbers',			'Attendance Short Report',		'scAssignRollNo.php'), 
																		Array('AttendanceNotEnteredReport',			'Attendance not entered report',		'scAttendanceMissedReport.php'), 
																		Array('PercentageWiseAttendanceReport',	'Percentage Wise Attendance Report',	'scPercentageWiseReport.php'), 
															 ));
	
		$reportsMenu[] = Array(MAKE_MENU, "Examination Reports", Array(
																		Array('MarksNotEntered',			'Marks Not Entered',		'scMarksNotEnteredReport.php'), 
																		Array('MarksDistribution',			'Marks Distribution',		'marksDistributionReport.php'), 
																		Array('TestWiseMarksReport',	'Test wise Marks Report',	'scTestWiseMarksReport.php'), 
																		Array('TransferredMarksReport',	'Transferred Marks Report',	'scTransferredMarksReport.php'),      
																		Array('DateWiseTestReport',    'Date Wise Test Report',    'scTestTimePeriod.php'),
																		Array('CourseMarksTransferredReport',    'Course Marks Transferred Report',    'scCourseMarksTransferredGraph.php'),
																		Array('StudentGradeReport',    'Student Grade Report',    'scStudentGradeReport.php'),
																		Array('TotalMarksReport',    'Total Marks Report',    'scTotalMarksReport.php'),
																		/*Array('FinalInternalAwardsReport',	'Final Internal Awards Report',	'finalAwardsReport.php'), 
																		Array('FinalPerformanceReport',	'Final Performance Report',	'finalPerformanceReport.php'), 
																		Array('ClassPerformanceGraph',	'Class Performance Graph',	'classWiseConsolidatedReport.php'), 
																		Array('ResultAnalysisGraph',	'Result Analysis Graph',	'resultAnalysisGraph.php'),
																		Array('SessionPerformance',	'Session Performance',	'sessionPerformance.php'),
																		Array('SubjectWiseGraph',	'Subject wise Graph',	'subjectWiseConsolidatedReport.php'),
																		Array('StudentAcademicPerformanceReport',	'Student Academic Performance Report',	'studentAcademicPerformanceReport.php'),
																		Array('StudentPerformanceReport',	'Student Performance Report',	'studentPerformanceReport.php'),
																		Array('InternalAwardsReport',	'Internal Awards Report',	''),*/
															));

		$reportsMenu[] = Array(MAKE_MENU, "Fee collection reports", Array(
																		Array('InstallmentDetailOfStudents',			'Installment Detail of Students',		'scInstallmentDetail.php'), 
																		//Array('FeesDueReport',			'Fees Due Report',		'scFeeDueReport.php'), 
																		Array('FeeCollection',	'Fee Collection',	'scFeeCollection.php'), 
																		/*Array('HeadWiseFeeCollectionReport',	'Head wise fee collection Report',	'scFeeHeadWise.php'), 
																		Array('DiscountsGivenToStudents',	'Discounts given to students',	'scDiscountStudent.php'), */
															));
		$reportsMenu[] = Array(MAKE_SINGLE_MENU, "TimeTableReport", Array('TimeTableReport','TimeTable History','scTimeTableHistory.php'));

		$dashboardMenu = Array();
		$dashboardMenu[] = Array(SET_MENU_HEADING, "DashBoard");
		$dashboardMenu[] = Array(MAKE_SINGLE_MENU, "StudentDemographics", Array('StudentDemographics','Student Demographics','studentDemographics.php'));
        
        /*
		$reportsMenu[] = Array(MAKE_MENU, "Feedback", Array(
                         Array('FeedbackSurvey',       'Teacher Survey Feedback',      'scFeedbackTeacherReport.php')));
		*/

		$allMenus = Array($setupMenu, $studentInfoMenu, $schedulingMenu, $instituteMenu, $messagingMenu, $studentFeeMenu, $reportsMenu,$dashboardMenu);


//$History: scMenuItems.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library
//
//*****************  Version 19  *****************
//User: Ajinder      Date: 12/02/08   Time: 1:17p
//Updated in $/Leap/Source/Library
//commented feedback report properly
//
//*****************  Version 18  *****************
//User: Ajinder      Date: 12/02/08   Time: 1:13p
//Updated in $/Leap/Source/Library
//commented link for scFeedbackTeacherReport.php
//
//*****************  Version 17  *****************
//User: Parveen      Date: 12/01/08   Time: 10:30a
//Updated in $/Leap/Source/Library
//
//*****************  Version 16  *****************
//User: Ajinder      Date: 11/28/08   Time: 1:35p
//Updated in $/Leap/Source/Library
//added link for TotalMarksReport
//
//*****************  Version 15  *****************
//User: Parveen      Date: 11/28/08   Time: 10:54a
//Updated in $/Leap/Source/Library
//report menu change name Messages Count List
//
//*****************  Version 14  *****************
//User: Parveen      Date: 11/27/08   Time: 2:44p
//Updated in $/Leap/Source/Library
//Report => Messages => modify message name
//
//*****************  Version 13  *****************
//User: Parveen      Date: 11/26/08   Time: 12:13p
//Updated in $/Leap/Source/Library
//Mesages Link added in Report Menu Bar
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 11/21/08   Time: 10:10a
//Updated in $/Leap/Source/Library
//make new link for department
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:46p
//Updated in $/Leap/Source/Library
//Added FeedBack Masters menus
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 11/13/08   Time: 5:41p
//Updated in $/Leap/Source/Library
//added time table history in menu
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 11/12/08   Time: 12:36p
//Updated in $/Leap/Source/Library
//added link for student grade report.
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 11/07/08   Time: 4:03p
//Updated in $/Leap/Source/Library
//added link for menu permissions
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 11/07/08   Time: 4:00p
//Updated in $/Leap/Source/Library
//added dashboard and other pie reports on student demographics
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:52p
//Updated in $/Leap/Source/Library
//Added Links for "Quarantine(delete) Students" and "Restore Students"
//modules
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 1:47p
//Updated in $/Leap/Source/Library
//Comment out SendSmsToStudent and SendSmsToEmployee module links
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 11/06/08   Time: 10:47a
//Updated in $/Leap/Source/Library
//added code for VSS.
//

?>