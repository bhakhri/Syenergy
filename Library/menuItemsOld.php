<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Menu
//
//
// Author :Ajinder Singh
// Created on : 22-Dec-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
//echo INCLUDE_LEAVE;
		$setupMenu = Array();
		$setupMenu[] = Array(SET_MENU_HEADING, "Setup");
		$setupMenu[] = Array(MAKE_SINGLE_MENU, "ConfigMaster", Array('ConfigMaster','Config Master',UI_HTTP_PATH . '/configManagement.php',Array(EDIT)));
        //$setupMenu[] = Array(MAKE_SINGLE_MENU, "ThoughtsMaster", Array('ThoughtsMaster','Thoughts',UI_HTTP_PATH . '/listThoughts.php'));
		$setupMenu[] = Array(MAKE_MENU, "Address Masters", Array(
																		Array('CountryMaster','Country Master',UI_HTTP_PATH . '/listCountry.php'), 
																		Array('StateMaster','State Master',UI_HTTP_PATH . '/listState.php'),
																		Array('CityMaster','City Master',UI_HTTP_PATH . '/listCity.php')
																));

		$setupMenu[] = Array(MAKE_MENU, "Administrative Masters", Array(
																		Array('UniversityMaster','University Master',UI_HTTP_PATH . '/listUniversity.php'), 
																		//Array('InstituteMaster','Institute Master',UI_HTTP_PATH . '/listInstitute.php'),
																		Array('QuotaMaster','Quota Master',UI_HTTP_PATH . '/listQuota.php'),
                                                      Array('QuotaSeatIntake','Quota Seat Intake',UI_HTTP_PATH . '/listQuotaSeatIntake.php'),
                                                      Array('ClasswiseQuotaAllocation','Class wise Quota Allocation',UI_HTTP_PATH . '/listClasswiseQuotaAllocation.php'), 
																		Array('EmployeeMaster','Employee Master',UI_HTTP_PATH . '/listEmployee.php'),
																		Array('ShortEmployeeMaster','Employee Master (Guest Faculty)',UI_HTTP_PATH . '/listEmployeeShort.php'),
                                                      Array('UploadEmployeeDetail','Upload/Export Employee Detail',UI_HTTP_PATH . '/listEmployeeInfoUpload.php',ARRAY(ADD)),
																		Array('DesignationMaster','Designation Master',UI_HTTP_PATH . '/listDesignation.php'),
																		Array('DepartmentMaster','Department Master',UI_HTTP_PATH . '/listDepartment.php'),
																		Array('OffenseMaster','Offense Master',UI_HTTP_PATH . '/listOffense.php')
                                                                        
																));
		$setupMenu[] = Array(MAKE_MENU, "Exam Masters", Array(
																		Array('TestTypesMaster','Test Type Master',UI_HTTP_PATH . '/listTestType.php'), 
																		Array('TestTypeCategoryMaster','Test Type Category Master',UI_HTTP_PATH . '/listTestTypeCategory.php'),
																		//Array('SlabsMaster','Slabs Master',UI_HTTP_PATH . '/listSlabs.php'),
																		Array('EvaluationCrieteria','Evaluation Criteria',UI_HTTP_PATH . '/listEvaluationCriteria.php'),
																		//Array('RangeLevelMaster','Range Level Master',UI_HTTP_PATH . '/listRangeLevel.php'),
																));
		$setupMenu[] = Array(MAKE_MENU, "Class Masters", Array(
																		Array('DegreeMaster','Degree Master',UI_HTTP_PATH . '/listDegree.php'), 
																		Array('BranchMaster','Branch Master',UI_HTTP_PATH . '/listBranch.php'),
																		Array('BatchMaster','Batch Master',UI_HTTP_PATH . '/listBatch.php'),
																		Array('SessionMaster','Session Master',UI_HTTP_PATH . '/listSession.php'),
																		Array('PeriodicityMaster','Periodicity Master',UI_HTTP_PATH . '/listPeriodicity.php'),
																		Array('StudyPeriodMaster','Study Period Master',UI_HTTP_PATH . '/listStudyPeriod.php'),
																		//Array('SectionMaster','Section Master','scListSection.php'),
                                                                        Array('GroupTypeMaster','Group Type Master',UI_HTTP_PATH . '/listGroupType.php'),
																		Array('GroupMaster','Group Master',UI_HTTP_PATH . '/listGroup.php'),
																		Array('GroupCopy','Copy Groups',UI_HTTP_PATH . '/copyGroups.php'),
					                                                    //Array('PeriodsMaster','Periods Master','listPeriods.php')
                                                      Array('PeriodSlotMaster','Period Slot Master',UI_HTTP_PATH . '/listPeriodSlot.php'),
                                                       Array('PeriodsMaster','Periods Master',UI_HTTP_PATH . '/listPeriods.php'),
                                                       Array('CreateClass','Create Class',UI_HTTP_PATH . '/listClasses.php'),
                                                       Array('AssignCourseToClass','Assign Subjects to Class',UI_HTTP_PATH . '/assignSubjectToClass.php'),
                                                       Array('AttendanceSetMaster','Attendance Set Master',UI_HTTP_PATH . '/listAttendanceSet.php')
																));
/*        $setupMenu[] = Array(MAKE_MENU, "Grade Masters", Array(
                                                                        Array('GradeMaster','Grade Master','listGrade.php'),
                                                                        Array('GradingLabelMaster','Grading Label Master','scListGradingLabels.php'),
                                                                        Array('GradingScaleMaster','Grading Scale Master','scListGradingScales.php')
                                                                ));
        $setupMenu[] = Array(MAKE_MENU, "Histogram Masters", Array(
                                                                        Array('HistogramLabelMaster','Histogram Label Master','listHistogramLables.php'), 
                                                                        Array('HistogramScaleMaster','Histogram Scale Master','listHistogramScale.php')
                                                                ));
*/                                                                      
   
		$setupMenu[] = Array(MAKE_MENU, "Academic Masters", Array(     Array('SubjectCategory',        'Subject Category Master',      UI_HTTP_PATH . '/listSubjectCategory.php'), 
																		Array('SubjectTypesMaster',		'Subject Type Master',			UI_HTTP_PATH . '/listSubjectType.php'), 
																		Array('Subject',			    'Subject Master',				UI_HTTP_PATH . '/listSubject.php'),
                                                                        Array('SubjectTopic',           'Subject Topic Master',         UI_HTTP_PATH . '/listSubjectTopic.php'),  
																		Array('Topic',           'Upload Topic Detail',         UI_HTTP_PATH . '/subjectTopicUpload.php'),  
                                                                        Array('BulkSubjectTopic',           'Bulk Subject Topic Master',    UI_HTTP_PATH . '/bulkListSubjectTopic.php'),   
                                                                        //Array('LectureTypeMaster',		'Lecture Type Master',			UI_HTTP_PATH . '/listLectureType.php'),
																		Array('AttendanceCodesMaster',	'Attendance Code Master',		UI_HTTP_PATH . '/listAttendanceCode.php'),
                                                                        Array('ResourceCategory',        'Subject Resource Category Master',            UI_HTTP_PATH . '/listResourceCategory.php'),
																));

		/*$setupMenu[] = Array(MAKE_MENU, "Bus Masters", Array(
																		Array('BusCourse',           'Bus  Master',               UI_HTTP_PATH . '/listBus.php'),
                                                                        Array('BusRouteMaster',		 'Bus Route Master',		  UI_HTTP_PATH . '/listBusRoute.php'), 
																		Array('BusStopCourse',		 'Bus Stop Master',		      UI_HTTP_PATH . '/listBusStop.php'),
                                                                        
                                                                        Array('TransportStuffMaster', 'Transport Staff Master',   UI_HTTP_PATH . '/listTransportStuff.php'),
                                                                        
                                                                       
																));*/

		$setupMenu[] = Array(MAKE_MENU, "Grade Masters", Array(
                                                      Array('GradeSetMaster','Grade Set Master',UI_HTTP_PATH . '/listGradeSet.php'),
																		Array('GradeMaster','Grade Master',UI_HTTP_PATH . '/listGrade.php'),
																		//Array('ApplyGrade',						'Apply Grade',		UI_HTTP_PATH . '/scApplyGrade.php',array(ADD)), 
																		Array('ApplyGrades',						'Apply Grade(Advanced)',		UI_HTTP_PATH . '/applyGrades.php',array(ADD)), 
																		//Array('GradingLabelMaster','Grading Label Master',UI_HTTP_PATH . '/scListGradingLabels.php'),
																		//Array('GradingScaleMaster','Grading Scale Master',UI_HTTP_PATH . '/scListGradingScale.php')
																));
		$setupMenu[] = Array(MAKE_MENU, "Fleet Management", Array(

																		Array('VehicleTypeMaster', 'Vehicle Type Master', UI_HTTP_PATH .'/listVehicleType.php'),
																		Array('VehicleInsuranceMaster', 'Insurance Master', UI_HTTP_PATH .'/listVehicleInsurance.php'),
																		Array('Vehicle',           'Vehicle  Master',             UI_HTTP_PATH .'/listVehicle.php'),
																		Array('FuelMaster',           'Fuel Master',            UI_HTTP_PATH .'/listFuel.php'),
																		Array('VehicleTyreMaster', 'Purchase/Replace Tyre', UI_HTTP_PATH .'/listVehicleTyre.php'),
																		Array('TyreRetreading', 'Tyre Retreading', UI_HTTP_PATH .'/listTyreRetreading.php'),
																		Array('InsuranceVehicle', 'Vehicle Insurance', UI_HTTP_PATH .'/listInsuranceVehicle.php'),
																		Array('InsuranceClaim', 'Vehicle Insurance Claim', UI_HTTP_PATH .'/listInsuranceClaim.php'),
																		Array('VehicleAccident', 'Vehicle Accident', UI_HTTP_PATH .'/listVehicleAccident.php'),
																		//Array('BusRepairCourse',      'Vehicle Repair Master',      UI_HTTP_PATH .'/listBusRepair.php'),
																		//Array('VehicleService', 'Vehicle Service', UI_HTTP_PATH .'/listVehicleService.php'),
																		Array('VehicleServiceRepair', 'Vehicle Service cum Repair', UI_HTTP_PATH .'/listVehicleServiceRepair.php'),
																		Array('VehicleBattery', 'Vehicle Battery', UI_HTTP_PATH .'/listVehicleBattery.php'),
																		//Array('ReplaceTyre', 'Interchange Tyre', UI_HTTP_PATH .'/listTyreHistory.php'),
																		Array('VehicleTax', 'Vehicle Tax', UI_HTTP_PATH .'/listVehicleTax.php'),
																		Array('BusRouteMaster',         'Vehicle Route Master',     UI_HTTP_PATH .'/listBusRoute.php'), 
																		Array('BusStopCourse',         'Vehicle Stop Master',       UI_HTTP_PATH .'/listBusStop.php'),
																		Array('TransportStaffMaster', 'Transport Staff Master', UI_HTTP_PATH .'/listTransportStaff.php'),

                                                                        /*Array('InsuranceDueReport','Insurance Due Report',         UI_HTTP_PATH.'/listInsuranceDueReport.php',array(VIEW)), 
                                                                        Array('FuelMaster','Fuel Usage Report',         UI_HTTP_PATH.'/listFuelReport.php',array(VIEW)), 
                                                                        Array('BusRepairCourse','Bus Repair Cost Report',         UI_HTTP_PATH.'/listRepairCostReport.php',array(VIEW)),*/ 
																));
                                                                
		$setupMenu[] = Array(MAKE_MENU, "Building Masters", Array(
																		Array('BuildingMaster',		'Building Master',			UI_HTTP_PATH . '/listBuilding.php'), 
																		Array('BlockCourse',			'Block Master',			UI_HTTP_PATH . '/listBlock.php'),
																		Array('RoomTypeMaster',			'Room Type Master',			UI_HTTP_PATH . '/listRoomType.php'),
																		Array('RoomsMaster','Rooms Master',UI_HTTP_PATH . '/listRoom.php'),																));
                                                                
		$setupMenu[] = Array(MAKE_MENU, "Hostel Masters", Array(
																		Array('HostelMaster',		'Hostel Master',			UI_HTTP_PATH . '/listHostel.php'),
																		Array('HostelRoomType',	'Hostel Room Type Master',		UI_HTTP_PATH . '/listHostelRoomType.php'),
																		Array('HostelRoomTypeDetail',	'Hostel Room Type Detail Master',		UI_HTTP_PATH . '/listHostelRoomTypeDetail.php'),
																		Array('HostelRoomCourse',		'Hostel Room Master',	UI_HTTP_PATH . '/listHostelRoom.php'),
                                                                      
																		Array('ComplaintCategory',	'Complaint Master',		UI_HTTP_PATH . '/listHostelComplaintCat.php'),
																		Array('DisciplineCategory',	'Discipline Master',		UI_HTTP_PATH . '/listHostelDisciplineCat.php'),
																		Array('HostelVisitor',	'Visitor Master',		UI_HTTP_PATH . '/listHostelVisitor.php'),
																		Array('TemporaryDesignationMaster',	'Temporary Designation Master',		UI_HTTP_PATH . '/listDesignationTemp.php'),
                                                                        Array('CleaningMaster','Cleaning Master',UI_HTTP_PATH . '/listCleaningRecord.php'),
																		Array('TemporaryEmployee',	'Temporary Employee Master',		UI_HTTP_PATH . '/listEmployeeTemp.php'),
																		
																		
																	
																));

		$setupMenu[] = Array(MAKE_MENU, "Fee Masters", Array(
																		Array('FeeHeads',				'Fee Head',			UI_HTTP_PATH . '/listFeeHead.php'), 
																		Array('FeeHeadValues',			'Fee Head Values',		UI_HTTP_PATH . '/listFeeHeadValues.php'), 
																		Array('FeeCycleFines',			'Fee Cycle Fine Master',		UI_HTTP_PATH . '/listFeeCycleFine.php'), 
																		Array('FeeCycleMaster',			'Fee Cycle Master',		UI_HTTP_PATH . '/listFeeCycle.php'), 
																		Array('FundAllocationMaster',	'Fund Allocation Master',UI_HTTP_PATH . '/listFeeFundAllocation.php'), 
																		Array('BankMaster',				'Bank Master',			UI_HTTP_PATH . '/listBank.php'), 
																		//Array('BankBranchMaster',		'Bank Branch Master',	UI_HTTP_PATH . '/listBankBranch.php'),
																));
		$setupMenu[] = Array(MAKE_MENU, "Time Table Masters", Array(
																		Array('CreateTimeTableLabels',				'Time Table Label Master',UI_HTTP_PATH . '/listTimeTableLabel.php'), 
																		Array('AssociateTimeTableToClass',			'Associate Time Table to Class',			UI_HTTP_PATH . '/assignTimeTableToClass.php'), 
																		//Array('CreateTimeTable',					'Create Time Table',	'scCreateTimeTable.php'),
                                                                        //Array('CreateTimeTable',                    'Create Time Table',    UI_HTTP_PATH . '/createTimeTable.php'),
																));
/*
		$setupMenu[] = Array(MAKE_MENU, "Institute Setup", Array(
																		 
//																		Array('AssignGroupsToStudents',			'Assign Group to Students',		UI_HTTP_PATH . '/assignGroup.php'), 
																		 
																		
																		 
																		//Array('UpdateStudentSection',			'Update Student Section',		'scUpdateSection.php'), 
																		 
																		 
																		//Array('UpdateTotalMarks',					'Update Total Marks',		UI_HTTP_PATH . '/updateTotalMarks.php'), 
																		//Array('ApplyGrade',						'Apply Grade',		'scApplyGrade.php'), 
																		 
																		 
                                                                       
																		
																		 
																		//Array('AssignOptionalSubjectToStudents',	'Assign Optional Subject to Students',		UI_HTTP_PATH . '/studentToOptionalSubjectMapping.php'), 
																		
																		
																		
																		
                                                                        
																		
																));
																*/
		$setupMenu[] = Array(MAKE_MENU, "User Management", Array(
																		Array('RoleMaster',							'Role Master',	UI_HTTP_PATH . '/listRole.php'),
																		Array('RolePermissions',					'Role Permissions',	UI_HTTP_PATH . '/rolePermission.php',Array(EDIT)),
																		Array('TeacherRolePermissions',					'Teacher Permissions',	UI_HTTP_PATH . '/teacherPermission.php',Array(VIEW,EDIT)),
																		Array('StudentRolePermissions',					'Student Permissions',	UI_HTTP_PATH . '/studentPermission.php',Array(VIEW,EDIT)),
																		Array('ParentRolePermissions',					'Parent Permissions',	UI_HTTP_PATH . '/parentPermission.php',Array(VIEW,EDIT)),
																		Array('ManagementRolePermissions',					'Management Permissions',	UI_HTTP_PATH . '/managementPermission.php',Array(VIEW,EDIT)),
                                                                        Array('ManageUsers',                        'Manage Users',    UI_HTTP_PATH . '/listManageUser.php'),
                                                                        Array('RoleToClass',                            'Academic Head Privileges ',    UI_HTTP_PATH . '/roleToClass.php'), 
																		
																));															
		$setupMenu[] = Array(MAKE_MENU, "Student Setup", Array(
																		Array('Admit',								         'Admit Student',	    UI_HTTP_PATH . '/admitStudent.php',ARRAY(ADD)),
																		Array('UpdatePasswordReport',               'Generate Student Login',     UI_HTTP_PATH . '/updatePassword.php',ARRAY(ADD)),
																		Array('AssignRollNumbers',			    'Assign Roll Numbers',	    UI_HTTP_PATH . '/assignRollNo.php'), 
																		Array('UploadStudentGroup',			    'Upload Student Group',	    UI_HTTP_PATH . '/studentGroupUpload.php'),
																		Array('UploadStudentRollNo',			    'Upload/Download Student Roll No./Univ. Roll No.',	    UI_HTTP_PATH . '/studentRollNoUpload.php'),
                                                                        Array('UploadStudentDetail',                'Upload Student Detail',        UI_HTTP_PATH . '/studentDetailUpload.php'),
                                                                        Array('QuarantineStudentMaster',            'Delete Students',      UI_HTTP_PATH . '/listQuarantineStudent.php'), 
                                                                        Array('RestoreStudentMaster',               'Restore Students',     UI_HTTP_PATH . '/listRestoreStudent.php'), 
																		Array('StudentClassRollNo',               'Update Student Class/Roll No.',     UI_HTTP_PATH . '/updateStudentClassRollNo.php',ARRAY(VIEW,ADD)),
                                                                        Array('CreateParentLogin',               'Generate Parent Logins',     UI_HTTP_PATH . '/createParentLogin.php'),
                                                                        Array('AssignGroupAdvanced',            'Assign Group to Students (Advanced)',        UI_HTTP_PATH . '/assignGroupAdvanced.php'),
                                                                        Array('AssignOptionalSubjects',            'Assign Optional Subjects to Students',        UI_HTTP_PATH . '/assignOptionalGroup.php', Array(ADD)),
                                                                        Array('UpdateStudentGroups',                'Update Student Groups',        UI_HTTP_PATH . '/listUpdateStudentGroup.php', Array(EDIT)),
                                                                        Array('ChangeStudentBranch',                'Change Student Branch',        UI_HTTP_PATH . '/changeStudentBranch.php', Array(EDIT)),
																		Array('GraceMarks',               'Grace Marks',     UI_HTTP_PATH . '/graceMarks.php'),
                                                                        Array('StudentConcession',               'Student Concession',     UI_HTTP_PATH . '/listStudentConcession.php'),
											
																));
          if(defined('SHOW_EMPLOYEE_APPRAISAL_FORM') and SHOW_EMPLOYEE_APPRAISAL_FORM == 1) {
             $setupMenu[] = Array(MAKE_MENU, "Appraisal Masters", Array(
                                                                        Array('AppraisalTab', 'Appraisal Tab Master', UI_HTTP_PATH . '/listAppraisalTab.php'), 
                                                                        Array('AppraisalTitle', 'Appraisal Title Master', UI_HTTP_PATH . '/listAppraisalTitle.php'),
                                                                        Array('AppraisalQuestionMaster', 'Appraisal Question Master', UI_HTTP_PATH . '/listAppraisalMaster.php'),
                                                                )); 
          }
//Added for payroll module

        /*
		  $setupMenu[] = Array(MAKE_MENU, "Payroll Setup", Array(
                                                                         
                                                                        Array('CreateDeductionAccounts',                'Deduction Accounts Master',UI_HTTP_PATH . '/payrollDedAccountsMaster.php'),
																		Array('CreateHeads',                'Heads Master',UI_HTTP_PATH . '/payrollHeadsMaster.php'),																		
                                                                        Array('AssignHeads',                'Assign Heads',UI_HTTP_PATH . '/payrollAssignHeads.php'),                                            
                                                                        Array('GenerateSalary',                'Generate Salary',UI_HTTP_PATH . '/payrollGenerateSalary.php'),
                                                                        Array('HoldSalary',                'Hold Salary',UI_HTTP_PATH . '/payrollHoldSalary.php'),
                                                                ));
																					 */
																					 
                                                                      
	   

		$studentInfoMenu = Array();
		$studentInfoMenu[] = Array(MAKE_HEADING_MENU, "StudentInfo, Find Student, searchStudent.php");
			
		
		$schedulingMenu = Array();
		$schedulingMenu[] = Array(SET_MENU_HEADING, "Time Table");
		//$schedulingMenu[] = Array(MAKE_SINGLE_MENU, "CreateTimeTable", Array('CreateTimeTable', 'Manage Time Table', UI_HTTP_PATH . '/createTimeTable.php'));
        $schedulingMenu[] = Array(MAKE_MENU, "Manage Time Table", Array(
                                                                        Array('CreateTimeTableAdvanced','Class Wise (Weekly)',UI_HTTP_PATH . '/createTimeTableAdvanced.php',Array(EDIT)),  
                                                                        Array('CreateTimeTableAdvancedDateWise','Class Wise Date Wise (Daily)',UI_HTTP_PATH . '/createTimeTableAdvancedDayWise.php',Array(EDIT)),  
                                                                        Array('CreateTimeTableClassWiseDayWise','Class and Day Wise (Weekly)',UI_HTTP_PATH . '/createTimeTableClassWiseDayWise.php',Array(EDIT)),
                                                                        Array('CreateTimeTableClassWiseDayWiseRoomWise','Class,Day and Room Wise (Weekly)',UI_HTTP_PATH . '/createTimeTableClassWiseDayWiseRoomWise.php',Array(EDIT)),       
                                                                        ));
       $schedulingMenu[] = Array(MAKE_MENU, "Display Time Table", Array(
                                                                        Array('DisplayTeacherTimeTable','Teacher',UI_HTTP_PATH . '/displayTeacherTimeTable.php'),  
                                                                        Array('DisplayStudentTimeTable','Student',UI_HTTP_PATH . '/displayStudentTimeTable.php'),
                                                                        Array('DisplayClassTimeTable','Class',UI_HTTP_PATH . '/displayClassTimeTable.php'), 
                                                                        Array('DisplayRoomTimeTable','Room',UI_HTTP_PATH . '/displayRoomTimeTable.php'),      
                                                                        ));
        $schedulingMenu[] = Array(MAKE_SINGLE_MENU, "DisplayLoadTeacherTimeTable", Array('DisplayLoadTeacherTimeTable', 'Display Teacher Load', UI_HTTP_PATH . '/displayLoadTeacherTimeTable.php'));
        $schedulingMenu[] = Array(MAKE_SINGLE_MENU, "TeacherSubstitutions", Array('TeacherSubstitutions', 'Display Teacher Substitutions', UI_HTTP_PATH . '/listTeacherSubstitutions.php'));
        $schedulingMenu[] = Array(MAKE_SINGLE_MENU, "DisplayTimeTableReport", Array('DisplayTimeTableReport', 'Display Multi Utility Time Table', UI_HTTP_PATH . '/displayTimeTable.php'));
        //$schedulingMenu[] = Array(MAKE_SINGLE_MENU, "SwapTeacherTimeTable", Array('SwapTeacherTimeTable', 'Adjust Time Table', UI_HTTP_PATH . '/swapTimeTable.php',ARRAY(EDIT)));
		//$schedulingMenu[] = Array(MAKE_SINGLE_MENU, "MoveTeacherTimeTable", Array('MoveTeacherTimeTable', 'Move/copy Teacher Time Table', UI_HTTP_PATH . '/moveTimeTable.php',ARRAY(EDIT)));
		$schedulingMenu[] = Array(MAKE_SINGLE_MENU, "ExtraClassesTimeTable", Array('ExtraClassesTimeTable', 'Create Extra Classes Time Table', UI_HTTP_PATH . '/extraClassesTimeTable.php',ARRAY(EDIT)));
		
		if (defined('INCLUDE_LEAVE') and INCLUDE_LEAVE == true) {
			$leaveMenu = Array();
			$leaveMenu[] = Array(SET_MENU_HEADING, "Leaves");
            $leaveMenu[] = Array(MAKE_SINGLE_MENU, "LeaveSessionMaster", Array('LeaveSessionMaster', 'Leave Session Master', UI_HTTP_PATH . '/listLeaveSession.php'));
			$leaveMenu[] = Array(MAKE_SINGLE_MENU, "LeaveMaster", Array('LeaveMaster', 'Leave Type Master', UI_HTTP_PATH . '/listLeaveType.php'));
			$leaveMenu[] = Array(MAKE_SINGLE_MENU, "LeaveSetMaster", Array('LeaveSetMaster', 'Leave Set Master', UI_HTTP_PATH . '/listLeaveSet.php'));
			$leaveMenu[] = Array(MAKE_SINGLE_MENU, "LeaveSetMapping", Array('LeaveSetMapping', 'Leave Set Mapping', UI_HTTP_PATH . '/listLeaveSetMapping.php'));
			$leaveMenu[] = Array(MAKE_SINGLE_MENU, "EmployeeEmployeeLeaveSetMapping", Array('EmployeeEmployeeLeaveSetMapping', 'Employee Leave Set Mapping', UI_HTTP_PATH . '/listEmployeeLeaveSet.php'));
            $leaveMenu[] = Array(MAKE_SINGLE_MENU, "EmployeeEmployeeLeaveSetMappingAdv", Array('EmployeeEmployeeLeaveSetMappingAdv', 'Employee Leave Set Mapping(Advanced)', UI_HTTP_PATH . '/listEmployeeLeaveSetAdv.php'));
			$leaveMenu[] = Array(MAKE_SINGLE_MENU, "EmployeeLeaveAuthorizer", Array('EmployeeLeaveAuthorizer', 'Employee Leave Authorizer', UI_HTTP_PATH . '/listEmployeeLeaveAuthorizer.php'));
            $leaveMenu[] = Array(MAKE_SINGLE_MENU, "EmployeeLeaveAuthorizer", Array('EmployeeLeaveAuthorizerAdv', 'Employee Leave Authorizer(Advanced)', UI_HTTP_PATH . '/listEmployeeLeaveAuthorizerAdv.php'));
			$leaveMenu[] = Array(MAKE_SINGLE_MENU, "ApplyEmployeeLeave", Array('ApplyEmployeeLeave', 'Apply Leaves', UI_HTTP_PATH . '/applyEmployeeLeave.php'));
			$leaveMenu[] = Array(MAKE_SINGLE_MENU, "AuthorizeEmployeeLeave", Array('AuthorizeEmployeeLeave', 'Authorize Employee Leaves', UI_HTTP_PATH . '/authorizeEmployeeLeave.php',Array(VIEW,EDIT)));
            $leaveMenu[] = Array(MAKE_SINGLE_MENU, "EmployeeLeaveCarryForward", Array('EmployeeLeaveCarryForward', 'Employee Leave Carry Forward', UI_HTTP_PATH . '/listEmployeeLeaveCarryForward.php'));
		}
		
          
          
		
		$instituteMenu = Array();
		$instituteMenu[] = Array(SET_MENU_HEADING, "Notices");
		$instituteMenu[] = Array(MAKE_SINGLE_MENU, "AddEvent", Array('AddEvent', 'Manage Events', UI_HTTP_PATH . '/listCalendar.php'));
		$instituteMenu[] = Array(MAKE_SINGLE_MENU, "AddNotices", Array('AddNotices', 'Manage Notices', UI_HTTP_PATH . '/listNotice.php'));
        //$instituteMenu[] = Array(MAKE_SINGLE_MENU, "CreateCalendar", Array('CreateCalendar', 'Manage Calendar', UI_HTTP_PATH . '/listCalendar.php'));

		$messagingMenu = Array();
		$messagingMenu[] = Array(SET_MENU_HEADING, "Messaging");
		$messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToStudents", Array('SendMessageToStudents', 'Send Message to Students', UI_HTTP_PATH . '/listAdminStudentMessage.php'));
        $messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToParents", Array('SendMessageToParents', 'Send Message to Parents', UI_HTTP_PATH . '/listAdminParentMessage.php'));
		//$messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendSmsToStudent", Array('SendSmsToStudent', 'Send SMS to Student', 'scListAdminStudentSMS.php'));
		$messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToEmployees", Array('SendMessageToEmployees', 'Send Message to Employees', UI_HTTP_PATH . '/listAdminEmployeeMessage.php'));
		//$messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendSmsToEmployee", Array('SendSmsToEmployee', 'Send SMS to Employee', 'scListAdminEmployeeSMS.php'));
        $messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageToNumbers", Array('SendMessageToNumbers', 'Send SMS', UI_HTTP_PATH . '/listSendAdminMessage.php'));
        $messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendStudentPerformanceMessageToParents", Array('SendStudentPerformanceMessageToParents', 'Send Student Performance Message to Parents', UI_HTTP_PATH . '/listStudentPerformanceMessage.php'));
        $messagingMenu[] = Array(MAKE_SINGLE_MENU, "SendMessageParentMailBox", Array('SendMessageParentMailBox', 'Send Message to Parents Mail Box', UI_HTTP_PATH . '/listParentMailBox.php'));
         
        


		$studentFeeMenu = Array();
		$studentFeeMenu[] = Array(SET_MENU_HEADING, "Fee");
		//$studentFeeMenu[] = Array(MAKE_SINGLE_MENU, "CalculateFees", Array('CalculateFees', 'Calculate Fees', UI_HTTP_PATH . '/calculateFees.php'));
		
		$studentFeeMenu[] = Array(MAKE_SINGLE_MENU, "CollectFees", Array('CollectFees', 'Collect Fees', UI_HTTP_PATH . '/feeReceipt.php'));
		$studentFeeMenu[] = Array(MAKE_SINGLE_MENU, "FeeUpload", Array('FeeUpload', 'Import Fee', UI_HTTP_PATH . '/listFeeUpload.php'));
		$studentFeeMenu[] = Array(MAKE_SINGLE_MENU, "FeeReceiptStatus", Array('FeeReceiptStatus', 'Fee Receipt Status', UI_HTTP_PATH . '/feeReceiptStatus.php'));
        
        $fineMenu = Array();
        $fineMenu[] = Array(SET_MENU_HEADING, "Fine");
        $fineMenu[] = Array(MAKE_SINGLE_MENU, "FineCategoryMaster", Array('FineCategoryMaster', 'Fine Category Master', 'listFineCategory.php'));
        $fineMenu[] = Array(MAKE_SINGLE_MENU, "AssignFinetoRoles", Array('AssignFinetoRoles', 'Role to Fines Mapping Master', 'assignFineToRole.php'));
		$fineMenu[] = Array(MAKE_SINGLE_MENU, "FineStudentMaster", Array('FineStudentMaster', 'Student Fine Master', 'listFineStudent.php'));
		$fineMenu[] = Array(MAKE_SINGLE_MENU, "FineList", Array('FineList', 'Student Fine Approval', 'fineReport.php',ARRAY(VIEW,ADD)));
		//Array('FineList', 'Student Fine Report', UI_HTTP_PATH .'/fineReport.php'),
		$fineMenu[] = Array(MAKE_SINGLE_MENU, "CollectFine", Array('CollectFine', 'Collect Fine', 'fineReceipt.php'));
        $fineMenu[] = Array(MAKE_SINGLE_MENU, "BulkFineStudentMaster", Array('BulkFineStudentMaster', 'Student Bulk Fine Master', 'bulkFineStudent.php'));

        
        
        
		$activityMenu = Array();
        $activityMenu[] = Array(SET_MENU_HEADING, "Activities");
        $activityMenu[] = Array(MAKE_MENU, "Exam Activities", Array(
                                                                       // Array('TransferInternalMarks', 'Transfer Internal Marks', UI_HTTP_PATH . '/transferInternalMarks.php', Array(ADD)), 
                                                                        Array('TransferInternalMarksAdvanced', 'Transfer Internal Marks (Advanced)', UI_HTTP_PATH . '/transferInternalMarksAdvanced.php', Array(ADD)), 
                                                                        //Array('UploadExternalMarks', 'Upload External Marks', UI_HTTP_PATH . '/uploadExternalMarks.php'), 
																		Array('UploadStudentExternalMarks',                'Upload External Marks', UI_HTTP_PATH . '/studentMarksUpload.php'),
                                                                        Array('AttendancePercent', 'Attendance Marks Percent', UI_HTTP_PATH . '/listAttendanceMarksPercent.php'),
                                                                        Array('LecturePercent', 'Attendance Marks Slabs', UI_HTTP_PATH . '/listLecturePercent.php'),
                                                                        Array('PromoteStudents',                'Promote Students',        UI_HTTP_PATH . '/promoteStudents.php'),
                                                                        Array('PromoteStudentsAdvanced',                'Promote Students (Advanced)',        UI_HTTP_PATH . '/promoteStudentsAdvanced.php',Array(ADD)),
                                                                        Array('FrozenTimeTableToClass', 'Freeze/Backup Data', UI_HTTP_PATH . '/frozenTimeTableToClass.php',array(VIEW,ADD))
                                                            ));
        $activityMenu[] = Array(MAKE_MENU, "Marks and Attendance", Array(
                                                                        Array('BulkAttendance','Bulk Attendance',UI_HTTP_PATH . '/listBulkAttendance.php'), 
                                                                        Array('DailyAttendance','Daily Attendance',UI_HTTP_PATH . '/Teacher/listDailyAttendance.php'), 
                                                                       // Array('DutyLeaves','Student Duty Leaves',UI_HTTP_PATH . '/dutyLeaveEntry.php'), 
                                                                        Array('DutyLeaveEvents',      'Duty Leave Events Master',        UI_HTTP_PATH . '/listDutyLeaveEvents.php'),
                                                                        Array('DutyLeaveUpload',      'Upload Duty Leave Entries',        UI_HTTP_PATH . '/dutyLeaveUpload.php'),
                                                                        Array('DutyLeaveConflictReport',      'Duty Leave Conflict Report',        UI_HTTP_PATH . '/dutyLeaveConflictReport.php',Array(VIEW,EDIT)),
                                                                        Array('DutyLeavesAdvanced',      'Student Duty Leaves',        UI_HTTP_PATH . '/dutyLeaveEntryAdvanced.php'),
                                                                        Array('DeleteAttendance',                            'Delete Attendance ',    UI_HTTP_PATH . '/deleteAttendance.php'),
                                                                        Array('TestMarks',               'Test Marks',     UI_HTTP_PATH . '/listEnterAssignmentMarks.php'), 
                                                                        Array('ChangeTestCategory',      'Change Test Category',     UI_HTTP_PATH . '/changeTestCategory.php'), 
                                                            ));
        $activityMenu[] = Array(MAKE_MENU, "ID Generation", Array(
                                                                        Array('EmployeeIcardReport','Employee I-Card',UI_HTTP_PATH . '/employeeIcard.php',ARRAY(VIEW)), 
                                                                        Array('EmployeeBusPass','Employee Bus Pass',UI_HTTP_PATH . '/employeeBusPass.php'), 
                                                                        Array('StudentIcardReport','Student I-Card',UI_HTTP_PATH . '/icard.php',ARRAY(VIEW)),
                                                                        Array('StudentBusPass','Student Bus Pass',UI_HTTP_PATH . '/createBusPass.php') 
                                                            ));
         $activityMenu[] = Array(MAKE_MENU, "Feedback Advanced", Array(
                                                                        Array('ADVFB_AnswerSet','Answer Set',UI_HTTP_PATH . '/listFeedbackAnswerSetAdv.php'), 
                                                                        Array('ADVFB_Options','Answer Set Options',UI_HTTP_PATH . '/listFeedbackOptionsAdv.php'), 
                                                                        Array('ADVFB_QuestionSet','Feedback Question Set Master (Advanced)',UI_HTTP_PATH . '/listFeedbackQuestionSetAdv.php'),
                                                                        Array('ADVFB_Questions','Feedback Questions Master (Advanced)',UI_HTTP_PATH . '/listFeedbackQuestionsAdv.php'), 
                                                                        Array('ADVFB_Labels','Feedback Label Master (Advanced)',UI_HTTP_PATH . '/listFeedBackLabelAdv.php'), 
                                                                        Array('ADVFB_CategoryMaster','Feedback Category Master (Advanced)',UI_HTTP_PATH . '/listFeedbackCategoryAdv.php'),
                                                                        Array('ADVFB_QuestionMappingMaster','Feedback Questions Mapping(Advanced)',UI_HTTP_PATH . '/listFeedbackQuestionMappingAdv.php'), 
                                                                        Array('ADVFB_TeacherMapping','Teacher Mapping',UI_HTTP_PATH . '/listFeedbackTeacherMapping.php'), 
                                                                        Array('ADVFB_AssignSurveyMaster','Feedback Assign Survey (Advanced)',UI_HTTP_PATH . '/listAssignSurveyAdv.php'),
                                                                        Array('ADVFB_AssignSurveyMasterReport','Feedback Assign Survey Report (Advanced)',UI_HTTP_PATH . '/listAssignSurveyReport.php'),
                                                                        Array('ADVFB_AssignSurveyMasterLabelWiseReport','Feedback Label Wise Survey Report (Advanced)',UI_HTTP_PATH . '/listAssignSurveyLabelWiseReport.php'),
                                                                        Array('ADVFB_CollegeGpaReport','Feedback College GPA Report (Advanced)',UI_HTTP_PATH . '/listFeedbackCollegeGpaReport.php'),
                                                                        Array('ADVFB_ClassFinalReport',' Feedback Class Final Report (Advanced)',UI_HTTP_PATH . '/listFeedbackClassFinalReport.php'),
                                                                        //Array('ADVFB_TeacherGpaReport','Feedback Teacher GPA Report (Advanced)',UI_HTTP_PATH . '/listFeedbackTeacherGpaReport.php'),
                                                                        Array('ADVFB_TeacherDetailedGpaReport','Feedback Teacher Detailed GPA Report (Advanced)',UI_HTTP_PATH . '/listFeedbackTeacherDetailedGpaReport.php'),
                                                                        Array('ADVFB_TeacherFinalReport','Feedback Teacher Final Report (Advanced)',UI_HTTP_PATH . '/listFeedbackTeacherFinalReport.php'),
                                                                        Array('ADVFB_CommentsReport','Feedback Comments Report (Advanced)',UI_HTTP_PATH . '/listFeedbackCommentsReport.php'),
                                                                        Array('ADVFB_TeacherCategoryResponseReport','Feedback Category Response Report (Advanced)',UI_HTTP_PATH . '/listFeedbackCategoryResponseReport.php'),
                                                                        Array('ADVFB_EmployeeGPAReport','Feedback Employee GPA Report (Advanced)',UI_HTTP_PATH . '/listFeedbackEmployeeGPAReport.php'),
                                                            ));                                                    
        $activityMenu[] = Array(MAKE_MENU, "Survey and Polls", Array(
                                                                        Array('CreateFeedBackLabels','Label Master',        UI_HTTP_PATH . '/listFeedBackLabel.php'), 
                                                                        Array('FeedBackCategoryMaster','Category Master',        UI_HTTP_PATH . '/listFeedBackCategory.php'), 
                                                                        Array('FeedBackGradesMaster','Grade Master',      UI_HTTP_PATH . '/listFeedBackGrades.php'), 
                                                                        Array('FeedBackQuestionsMaster','Question Master',     UI_HTTP_PATH . '/listFeedBackQuestions.php'),
                                                                        Array('AssignSurveyMaster','Assign Survey ',UI_HTTP_PATH . '/listAssignSurvey.php'), 
                                                                        Array('CopySurveyMaster','Copy Questions Master',UI_HTTP_PATH . '/copySurvey.php'), 
                                                                        Array('PreviewSurvey','Preview Survey',UI_HTTP_PATH . '/previewSurvey.php'),                                                                  
                                                                            
                                                                        
                                                            ));
       //$activityMenu[] = Array(MAKE_SINGLE_MENU, "UploadStudentGroup", Array('UploadStudentGroup','Upload Student Group',UI_HTTP_PATH . '/studentGroupUpload.php'));  
	   $activityMenu[] = Array(MAKE_SINGLE_MENU, "DisciplineMaster", Array('DisciplineMaster','Add Disciplinary Record',UI_HTTP_PATH . '/listDiscipline.php'));
	     
	   //$activityMenu[] = Array(MAKE_SINGLE_MENU, "DutyLeaves", Array('DutyLeaves','Student Duty Leaves',UI_HTTP_PATH . '/dutyLeaveEntry.php'));     
       $activityMenu[] = Array(MAKE_SINGLE_MENU, "DownloadImagesReport", Array('DownloadImagesReport','Upload & Download Images',UI_HTTP_PATH . '/uploadDownloadImages.php',Array(VIEW)));
       
       $activityMenu[] = Array(MAKE_SINGLE_MENU, "DisplayStudentReappear", Array('DisplayStudentReappear','Display Student Internal Re-appear',UI_HTTP_PATH . '/displayStudentInternalReappear.php',Array(VIEW,EDIT)));
       
       $activityMenu[] = Array(MAKE_SINGLE_MENU, "SuperLogin", Array('SuperLogin','Super Login',UI_HTTP_PATH . '/superLoginList.php',Array(VIEW)));
       
       //$activityMenu[] = Array(MAKE_SINGLE_MENU, "SiteMap", Array('SiteMap','Site Map',UI_HTTP_PATH . '/siteMap.php',Array(VIEW)));
       $activityMenu[] = Array(MAKE_SINGLE_MENU, "ChangePassword", Array('ChangePassword','Change Password',UI_HTTP_PATH . '/changePassword.php'));
        
        
 $reportsMenu[] = Array(SET_MENU_HEADING, "Reports");
        
        $reportsMenu[] = Array(MAKE_MENU, "Messages", Array(
                         Array('MessagesList',         'Messages List',             UI_HTTP_PATH . '/smsDetailReport.php'), 
                         Array('MessagesCountList',    'Messages Count List',          UI_HTTP_PATH . '/smsFullDetailReport.php') 
));
        
        $reportsMenu[] = Array(MAKE_MENU, "Hostel", Array(
                         Array('HostelList',         'Hostel Detail Report',             UI_HTTP_PATH . '/hostelDetailReport.php'),
                         Array('CleaningHistoryMaster',            'Cleaning History Report',        UI_HTTP_PATH . '/listCleaningHistory.php')));     

        //$reportsMenu[] = Array(MAKE_SINGLE_MENU, "AdvancedStudentFilter", Array('AdvancedStudentFilter','Advanced Student Filter',UI_HTTP_PATH . '/allDetailsReport.php'));
        //$reportsMenu[] = Array(MAKE_MENU, "Student", Array(
                                                                        //Array('StudentList','Student List',UI_HTTP_PATH . '/listStudentLists.php',ARRAY(VIEW)),
                                                                        //Array('StudentList','Student List','scListStudentLists.php'), 
                                                                        //Array('StudentPhotoLists',        'Student Photo Lists',            'listStudentInformationReport.php'), 
                                                                        //Array('BusPass',        'Bus Pass',            'listStudentInformationReport.php'), 
                                                                        //Array('HostelCard',        'Hostel Card',            'listStudentInformationReport.php'), 
                                                                        //Array('ICard',        'I-Card',            'listStudentInformationReport.php'), 
                                                                        //Array('IdentityReports',        'Identity Reports',            'listStudentInformationReport.php'), 
                                                                        //Array('Label',        'Label',            'studentLabels.php'), 
                                                                        //Array('LibraryCard',        'Library Card',            'listStudentInformationReport.php'), 
                                                        //     ));
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "SeatAllocationReport", Array('SeatAllocationReport','Seat Allocation Report',UI_HTTP_PATH . '/seatAllocationReport.php'),ARRAY(VIEW));
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "AdmittedStudentReport", Array('AdmittedStudentReport','Admitted Student Report',UI_HTTP_PATH . '/admittedStudentReport.php'),ARRAY(VIEW));
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "StudentList", Array('StudentList','Student List',UI_HTTP_PATH . '/listStudentLists.php'),ARRAY(VIEW));   
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "EmployeeList", Array('EmployeeList','Employee List',UI_HTTP_PATH . '/listEmployeeLists.php'),ARRAY(VIEW));   
                                                             
                                                             
        
        

        $reportsMenu[] = Array(MAKE_MENU, "Role", Array(
                                                                        Array('RoleWiseList',        'Role Wise User Report',            UI_HTTP_PATH . '/roleWiseUserReport.php',ARRAY(VIEW)),
                                                            ));                                            
                                                             
                                                             
        $reportsMenu[] = Array(MAKE_MENU, "Attendance", Array(
                                                                        Array('StudentAttendance',                'Student Attendance Report',                UI_HTTP_PATH . '/studentAttendanceReport.php'), 
                                                                        //Array('AssignRollNumbers',            'Attendance Short Report',            'scAssignRollNo.php'), 
                                                                        //Array('AttendanceNotEnteredReport',        'Attendance not entered report',    UI_HTTP_PATH . '/attendanceMissedReport.php'), 
                                                                        Array('PercentageWiseAttendanceReport',    'Percentage Wise Attendance Report',UI_HTTP_PATH . '/studentPercentageWiseReport.php',ARRAY(VIEW)), 
                                                                        Array('AttendanceStatusReport','Last Attendance Taken Report',UI_HTTP_PATH . '/attendanceStatusReport.php',ARRAY(VIEW)),
                                                                        Array('AttendanceRegister','Attendance Register',UI_HTTP_PATH . '/attendanceRegister.php',ARRAY(VIEW)),
                                                                        Array('StudentAttendanceShortReport','Student Attendance Short Report',UI_HTTP_PATH . '/studentAttendanceShorts.php',ARRAY(VIEW)),
                                                                        Array('StudentAttendancePerformanceReport','Student Attendance Performance Report',UI_HTTP_PATH . '/studentAttendancePerformanceReport.php',ARRAY(VIEW)),
                                                                        Array('TeacherAttendanceReport','Teacher Attendance Report',UI_HTTP_PATH . '/listTeacherAttendanceReport.php',ARRAY(VIEW)),
                                                             ));
                                                
        $reportsMenu[] = Array(MAKE_MENU, "Examination Reports", Array(
                                                                        //Array('MarksNotEntered',                    'Marks Entered Report',            UI_HTTP_PATH . '/marksNotEnteredReport.php'), 
                                                                        //Array('MarksDistribution',                    'Marks Distribution Report',            UI_HTTP_PATH . '/marksDistributionReport.php'), 
                                                                        Array('TestWiseMarksReport',                '<font color="blue">Test wise Marks Report (Pre Transfer)</font>',        UI_HTTP_PATH . '/testWiseMarksReport.php',ARRAY(VIEW)), 
                                                                        Array('TestWiseMarksConsolidatedReport', '<font color="blue">Test Type Category wise Detailed Report (Pre Transfer)</font>',        UI_HTTP_PATH . '/testWiseMarksConsolidatedReport.php',ARRAY(VIEW)),
                                                                        Array('DateWiseTestReport',                 '<font color="blue">Date Wise Test Report (Pre Transfer)</font>',        UI_HTTP_PATH . '/datewiseTestReport.php',ARRAY(VIEW)),
                                                                        Array('StudentRank','<font color="blue">Student Exam Rankwise Report (Pre Transfer)</font>','studentRankWiseReport.php ',Array(VIEW))  ,
                                                                        Array('StudentTestWiseMarksReport',                '<font color="blue">Student Test Wise Marks Report (Pre Transfer)</font>',        UI_HTTP_PATH . '/studentTestWiseMarksReport.php',ARRAY(VIEW)), 
                                                                        Array('MarksStatusReport','<font color="blue">Marks Status Report (Pre Transfer)</font>',UI_HTTP_PATH . '/marksStatusReport.php',ARRAY(VIEW)),
                                                                        Array('StudentAcademicReport','<font color="blue">Student Academic Report (Pre Transfer)</font>',UI_HTTP_PATH . '/academicPerformanceReport.php'),
                                                                        Array('StudentAcademicPerformanceReport', '<font color="green">Student Academic Performance(Pre & Post Transfer)</font>',        UI_HTTP_PATH . '/studentAcademicPerformanceReport.php',ARRAY(VIEW)),
                                                                        Array('GazetteReport', '<font color="green">Gazette Report(Pre & Post Transfer)</font>',        UI_HTTP_PATH . '/gazetteReport.php',ARRAY(VIEW)),
                                                                        //Array('ClassWiseConsolidatedReport',        'Class Performance Graph',        UI_HTTP_PATH . '/classWiseConsolidatedReport.php'), 
																		 
                                                                        Array('StudentConsolidatedReport',        '<font color="red">Student Consolidated Report (Post Transfer)</font>',        UI_HTTP_PATH . '/studentConsolidatedReport.php',ARRAY(VIEW)),
																		 Array('FinalInternalReport',        '<font color="red">Final Internal Marks Report (Post Transfer)</font>',        UI_HTTP_PATH . '/finalInternalReport.php',ARRAY(VIEW)),
                                                                        //Array('SubjectWiseConsolidatedReport',    'Subject wise Graph',            UI_HTTP_PATH . '/subjectWiseConsolidatedReport.php',ARRAY(VIEW)), 
                                                                        //Array('StudentPerformanceReport',            'Student Performance Report',    UI_HTTP_PATH . '/studentPerformanceReport.php'), 
                                                                        //Array('TransferredMarksReport',    'Transferred Marks Report',    'scTransferredMarksReport.php'),      
                                                                        Array('ExternalMarksReport','<font color="red">Student External Marks Report (Post Transfer)</font>',UI_HTTP_PATH . '/studentExternalMarksReport.php',ARRAY(VIEW)),
                                                                        Array('InternalMarksFoxproReport','<font color="red">Student Internal Marks Foxpro Report (Post Transfer)</font>',UI_HTTP_PATH . '/internalMarksFoxproReport.php',ARRAY(VIEW)),
                                                                        Array('TransferredMarksReport','<font color="red">Transferred Marks Report (Post Transfer)</font>',UI_HTTP_PATH . '/tranferredMarksReport.php'),
                                                                        Array('TestTypeDistributionReport','<font color="red">Test Type Distribution Consolidated Report (Post Transfer)</font>',UI_HTTP_PATH . '/testTypeConsolidatedReport.php'),
                                                                        Array('SubjectWisePerformanceComparisonReport',        '<font color="red">Subject Wise Performance Report (Post Transfer)</font>',        UI_HTTP_PATH . '/Teacher/listSubjectWisePerformance.php',ARRAY(VIEW)), 
                                                                        //Array('CourseMarksTransferredReport',    'Course Marks Transferred Report',    'scCourseMarksTransferredGraph.php'),
                                                                        //Array('StudentGradeReport',    'Student Grade Report',    'scStudentGradeReport.php'),
                                                                        //Array('TotalMarksReport',    'Total Marks Report',    'scTotalMarksReport.php'),
                                                                        //Array('StudentCGPAReport',    'Student CGPA Report',    'scStudentCgpaReport.php'),
                                                                        /*Array('FinalInternalAwardsReport',    'Final Internal Awards Report',    'finalAwardsReport.php'), 
                                                                        Array('FinalPerformanceReport',    'Final Performance Report',    'finalPerformanceReport.php'), 
                                                                        Array('ClassPerformanceGraph',    'Class Performance Graph',    'classWiseConsolidatedReport.php'), 
                                                                        Array('ResultAnalysisGraph',    'Result Analysis Graph',    'resultAnalysisGraph.php'),
                                                                        Array('SessionPerformance',    'Session Performance',    'sessionPerformance.php'),
                                                                        Array('SubjectWiseGraph',    'Subject wise Graph',    'subjectWiseConsolidatedReport.php'),
                                                                        Array('StudentAcademicPerformanceReport',    'Student Academic Performance Report',    'studentAcademicPerformanceReport.php'),
                                                                        Array('StudentPerformanceReport',    'Student Performance Report',    'studentPerformanceReport.php'),
                                                                        Array('InternalAwardsReport',    'Internal Awards Report',    ''),*/
                                                                         
                                                            ));

        $reportsMenu[] = Array(MAKE_MENU, "Fee collection reports", Array(
                                                                        //Array('InstallmentDetailOfStudents',            'Installment Detail of Students',        UI_HTTP_PATH . '/installmentDetail.php'), 
                                                                        //Array('FeesDueReport',            'Fees Due Report',        'scFeeDueReport.php'), 
                                                                        Array('FeeCollection',    'Fee Collection',    UI_HTTP_PATH . '/feeCollection.php'), 
                                                                        Array('DisplayFeePaymentHistory', 'Display Fee payment history', UI_HTTP_PATH . '/paymentHistory.php')
                                                                        /*Array('HeadWiseFeeCollectionReport',    'Head wise fee collection Report',    'scFeeHeadWise.php'), 
                                                                        Array('DiscountsGivenToStudents',    'Discounts given to students',    'scDiscountStudent.php'), */
                                                            ));

        $reportsMenu[] = Array(MAKE_MENU, "Fine", Array(
                                                                        Array('FineCollectionReport', 'Category Wise Fine Collection Report', UI_HTTP_PATH .'/listFineCollectionReport.php',Array(VIEW)),
                                                                        Array('StudentWiseFineCollectionReport', 'Student Wise Fine Collection Summary Report', UI_HTTP_PATH .'/listStudentWiseFineCollectionReport.php',Array(VIEW)),
                                                                        Array('StudentDetailFineCollectionReport', 'Student Detail Fine Collection Report', UI_HTTP_PATH .'/listStudentDetailFineCollectionReport.php',Array(VIEW)),
                                                                        Array('StudentFineHistoryReport', 'Fine Payment History Report', UI_HTTP_PATH .'/fineHistory.php',Array(VIEW)),
                                                                        
                                                            ));                                                
       
       
        if (defined('INCLUDE_LEAVE') and INCLUDE_LEAVE == true) {
            $reportsMenu[] = Array(MAKE_MENU, "Leave", Array(
                                                                        Array('EmployeeLeavesHistoryReport', 'Employee Leaves History Report', UI_HTTP_PATH .'/leavesHistoryReport.php',Array(VIEW)),
                                                                        Array('EmployeeLeavesTakenReport', 'Employee Leaves Taken Report', UI_HTTP_PATH .'/leavesTakenReport.php',Array(VIEW)),
                                                                        Array('EmployeeLeavesAnalysisReport', 'Employee Leaves Analysis Report', UI_HTTP_PATH .'/leavesAnalysisReport.php',Array(VIEW)),
                                                            ));        
                                                            
        }
                            
        /*
		  $reportsMenu[] = Array(MAKE_MENU, "Feedback Reports", Array(
                                                                        Array('EmployeeFeedbackReport',            'Teacher Survey Feedback',        UI_HTTP_PATH . '/teacherFeedbackReport.php',Array(VIEW)), 
                                                                        Array('GeneralFeedbackReport',    'General Survey Feedback',    UI_HTTP_PATH . '/generalFeedbackReport.php',Array(VIEW)), 
                                                            ));
			*/

 
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "CourseWiseResourceReport", Array('CoursewiseResourceReport','Coursewise Resources Report',UI_HTTP_PATH . '/scCourseWiseResourceReport.php'),ARRAY(VIEW));
        
        
        
         
        
      
        
        
       
        
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "TeacherConsolidatedReport", Array('TeacherConsolidatedReport','Teacher Consolidated Report',UI_HTTP_PATH . '/teacherWiseConsolidatedReport.php')); 
        
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "TeacherWiseTopicTaught", Array('TeacherWiseTopicTaught','Teacher Topic Taught Report',UI_HTTP_PATH . '/teacherTopicCoveredReport.php',Array(VIEW))); 

        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "OffenseReport", Array('OffenseReport','Offense Report',UI_HTTP_PATH . '/listOffenseReport.php',Array(VIEW)));

		$reportsMenu[] = Array(MAKE_MENU, "Fleet Management Report", Array(
																		Array('VehicleReport',			'Vehicle Detail Report',		UI_HTTP_PATH . '/listVehicleReport.php',Array(VIEW)),
																		Array('InsuranceDueReport','Insurance Due Report',         UI_HTTP_PATH.'/listInsuranceDueReport.php',array(VIEW)), 
																		Array('FuelUsageReport','Fuel Usage Report',         UI_HTTP_PATH.'/listFuelReport.php',array(VIEW)),
																		Array('TyreRetreadingReport','Tyre Retreading Report',         UI_HTTP_PATH.'/listTyreRetreadingReport.php',array(VIEW)),
																		Array('VehicleInsuranceReport','Vehicle Insurance Report',         UI_HTTP_PATH.'/listVehicleInsuranceReport.php',array(VIEW)),
																		Array('FuelConsumableReport','Fuel Consumable Report',         UI_HTTP_PATH.'/listFuelConsumableReport.php',array(VIEW)),
																		Array('FuelConsumableTimePeriodReport','Fuel Consumable Time Period Report',         UI_HTTP_PATH.'/listFuelConsumableTimePeriodReport.php',array(VIEW)),
																		Array('BusRoutePassengerReport','Bus Route Passenger Report',         UI_HTTP_PATH . '/busRoutePassengerList.php',Array(VIEW)),
																		
															));
		
		$reportsMenu[] = Array(MAKE_SINGLE_MENU, "OccupiedFreeClass", Array('OccupiedFreeClass','Occupied/Free Class(s)/Room(s) Wise Report',UI_HTTP_PATH . '/listOccupiedClassReport.php',Array(VIEW)));

		$reportsMenu[] = Array(MAKE_SINGLE_MENU, "OptionalGroupReport", Array('OptionalGroupReport','Optional/Compulsory Groups Report',UI_HTTP_PATH . '/listOptionalSubjectReport.php',Array(VIEW)));
		
		$reportsMenu[] = Array(MAKE_SINGLE_MENU, "DeletedStudentReport", Array('DeletedStudentReport','Deleted Student Report',UI_HTTP_PATH . '/listDeletedStudentReport.php',Array(VIEW)));

		$reportsMenu[] = Array(MAKE_SINGLE_MENU, "TimeTableTeacher", Array('TimeTableTeacher','Subject Taught By Teacher Report',UI_HTTP_PATH . '/timeTableTeacherReport.php',Array(VIEW)));

        /*$reportsMenu[] = Array(MAKE_MENU, "Bus Reports", Array(
                                                                        //Array('InsuranceDueReport','Insurance Due Report',         UI_HTTP_PATH . '/listInsuranceDueReport.php'), 
                                                                        //Array('FuelUsage','Fuel Usage Report',                   UI_HTTP_PATH . '/listFuelReport.php',Array(VIEW)), 
                                                                        //Array('BusRepairCost','Bus Repair Cost Report',         UI_HTTP_PATH . '/listRepairCostReport.php',Array(VIEW))
																		
                                                                        
                                                            ));    */
        
        
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "ConsolidatedReport", Array('ConsolidatedReport','Consolidated Report',UI_HTTP_PATH . '/listConsolidatedDataReport.php',Array(VIEW)));
        
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "DisplayStudentReappearReport", Array('DisplayStudentReappearReport','Display Student Re-appear Report',UI_HTTP_PATH . '/displayStudentInternalReappearReport.php',Array(VIEW)));
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "DisplayBusPassReport", Array('DisplayBusPassReport','Display Bus Pass Report',UI_HTTP_PATH . '/displayBusPassReport.php',Array(VIEW)));
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "UserLoginReport", Array('UserLoginReport','User Login Report',UI_HTTP_PATH . '/listUserLoginReport.php',Array(VIEW))); 
        $reportsMenu[] = Array(MAKE_SINGLE_MENU, "AssignmentReport", Array('AssignmentReport','Assignment Report',UI_HTTP_PATH . '/assignmentReport.php',Array(VIEW))); 
        
        $regDegreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE');
        if($regDegreeCode!='') {
          $reportsMenu[] = Array(MAKE_SINGLE_MENU, "CoursesRegistrationReport", Array('coursesRegistrationReport','Student Courses Registration Report',UI_HTTP_PATH . '/coursesRegistrationReport.php',Array(VIEW,ADD,EDIT))); 
        }
        
		/*
		  $reportsMenu[] = Array(MAKE_MENU, "Payroll", Array(
                                                                        Array('Payroll', 'Salary Slip', UI_HTTP_PATH .'/teacherSalarySlip.php',Array(VIEW)),
                                                                        Array('Payroll', 'Payroll Report', UI_HTTP_PATH .'/payrollReport.php',Array(VIEW)),
                                                                        Array('Payroll', 'Salary Sheet', UI_HTTP_PATH .'/salarySheet.php',Array(VIEW)),                           
                                                            ));
        */                                                    
        /*
        $reportsMenu[] = Array(MAKE_MENU, "Leave", Array(
                                                                  Array('Leave', 'Employee Leaves Report', UI_HTTP_PATH .'/leavesTakenReport.php',Array(VIEW)),
                                                            ));
        */
                                                            
        $dashboardMenu = Array();
		$dashboardMenu[] = Array(SET_MENU_HEADING, "Analytics");
		$dashboardMenu[] = Array(MAKE_SINGLE_MENU, "StudentDemographics", Array('StudentDemographics','Student Demographics',UI_HTTP_PATH . '/studentDemographics.php'));
		$dashboardMenu[] = Array(MAKE_SINGLE_MENU, "StudentSuggestion", Array('StudentSuggestion','Suggestions',UI_HTTP_PATH . '/listSuggestions.php'));
        
        
        $councilorsMenu = Array();
        $councilorsMenu[] = Array(SET_MENU_HEADING, "Pre Admission");
        $councilorsMenu[] = Array(MAKE_SINGLE_MENU, "UploadCandidateDetails", Array('UploadCandidateDetails','Upload Candidate Details',UI_HTTP_PATH . '/candidateUpload.php'));
        $councilorsMenu[] = Array(MAKE_SINGLE_MENU, "AddStudentEnquiry", Array('AddStudentEnquiry','View Candidate Details',UI_HTTP_PATH . '/addStudentEnquiry.php'));
        $councilorsMenu[] = Array(MAKE_SINGLE_MENU, "StudentCounseling", Array('StudentCounseling','Candidate Counseling',UI_HTTP_PATH . '/studentCounseling.php'));
        $councilorsMenu[] = Array(MAKE_SINGLE_MENU, "StudentFee", Array('StudentFee','Admission Fee',UI_HTTP_PATH . '/candidateFee.php'));
        $councilorsMenu[] = Array(MAKE_SINGLE_MENU, "StudentEnquiryDemographics", Array('StudentEnquiryDemographics','Student Enquiry Demographics',UI_HTTP_PATH . '/studentEnquiryDemographics.php'));
        
        
        
		

		$adminFunctionMenu = Array();
        $adminFunctionMenu[] = Array(SET_MENU_HEADING, "Admin Func."); 
        $adminFunctionMenu[] = Array(MAKE_MENU, "Hostel Management", Array(
                                                                        Array('RoomAllocation',    'Room Allocation Master',UI_HTTP_PATH . '/roomAllocation.php'),  
                                                                       Array('ReportComplaintsMaster','Report Complaints',UI_HTTP_PATH . '/listReportComplaints.php'), 
                                                                        Array('HandleComplaints','Handle Complaints',UI_HTTP_PATH . '/listHandleComplaints.php'),
                                                            ));
        $adminFunctionMenu[] = Array(MAKE_MENU, "Guest House Management", Array(
                                                                        Array('BudgetHeads',    'Budget Heads Master',UI_HTTP_PATH . '/listBudgetHeads.php'),
                                                                        Array('GuestHouseRequest',    'Request Guest House Allocation',UI_HTTP_PATH . '/listGuestHouseRequest.php'),  
                                                                        Array('GuestHouseAuthorization',    'Guest House Authorization',UI_HTTP_PATH . '/listGuestHouseAuthorization.php'),  
                                                            ));                                                            
        /*
		$adminFunctionMenu[] = Array(MAKE_MENU, "Fleet Management", Array(
                                                                       Array('BusRepairCourse',      'Bus Repair Master',        UI_HTTP_PATH . '/listBusRepair.php'),
                                                                        Array('FuelMaster',           'Fuel Master',              UI_HTTP_PATH . '/listFuel.php'),
                                                            ));
        
        */
               
       
        
        
       
        //$changePasswordMenu = Array();
        //$changePasswordMenu[] = Array(MAKE_HEADING_MENU, "ChangePassword, Change Password, changePassword.php");
		/*
		$reportsMenu[] = Array(MAKE_MENU, "Feedback", Array(
                         Array('FeedbackSurvey',       'Teacher Survey Feedback',      'scFeedbackTeacherReport.php')));
		*/

		$allMenus = array();
		$allMenus[] = $setupMenu;
		$allMenus[] = $studentInfoMenu;
		$allMenus[] = $schedulingMenu;
		$allMenus[] = $instituteMenu;
		$allMenus[] = $messagingMenu;
		$allMenus[] = $studentFeeMenu;
		$allMenus[] = $fineMenu;
		$allMenus[] = $activityMenu;
		$allMenus[] = $reportsMenu;
		$allMenus[] = $dashboardMenu;
		$allMenus[] = $councilorsMenu;
		$allMenus[] = $adminFunctionMenu;
		if (defined('INCLUDE_LEAVE') and INCLUDE_LEAVE == true) {
			$allMenus[] = $leaveMenu;
		}


		$accountsMenuItemsFileName = BL_PATH . '/accountsMenuItems.php';
		if (file_exists($accountsMenuItemsFileName)) {
			require_once($accountsMenuItemsFileName);
		}

		//including inventory management menu structure
        $inventoryMenuFileName = BL_PATH . '/inventoryMenuItems.php';
        if (file_exists($inventoryMenuFileName)) {
            require_once($inventoryMenuFileName);
        }



		if ($sessionHandler->getSessionVariable('hasBreadCrumbs') == '') {
			$mainMenuCounter = 0;
			$subInnerMenuCounter = 0;
			if ($roleId == 2) {
				require_once(BL_PATH . "/teacherMenuItems.php");
				$allMenus = $allTeacherMenus;
			}
			else if ($roleId == 3) {
				require_once(BL_PATH . "/parentMenuItems.php");
				$allMenus = $allParentMenus;
			}
			else if ($roleId == 4) {
				require_once(BL_PATH . "/studentMenuItems.php");
				$allMenus = $allStudentMenus;
			}
			else if($roleId == 5) {
				require_once(BL_PATH . "/managementMenuItems.php");
				$allMenus = $allManagementMenus;
			}

			$breadCrumbArray = array();
			$setMenuHeading = '';
			$makeSingleMenu = '';
			$makeHeadingMenu = '';
			$makeMenu = '';
			$menuText = '';
							
			foreach($allMenus as $independentMenu) {
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
						if ($roleId != 1 and (!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
							continue;
						}
						else {
							if ($includeHeading == false) {
								$includeHeading = true;
							}
						}
						$moduleLink = $menuItemArray[2][2];
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
							if ($roleId != 1 and (!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
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
						if ($roleId != 1 and (!is_array($sessionModule) or ($sessionModule['view'] == 0 and $sessionModule['add'] == 0 and $sessionModule['edit'] == 0 and $sessionModule['delete'] == 0))) {
							continue;
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



//$History: menuItems.php $
//
//*****************  Version 204  *****************
//User: Jaineesh     Date: 4/17/10    Time: 4:53p
//Updated in $/LeapCC/Library
//make new report optional/compulsory groups report
//
//*****************  Version 203  *****************
//User: Ajinder      Date: 4/17/10    Time: 4:29p
//Updated in $/LeapCC/Library
//done changes as per FCNS No. 1601
//
//*****************  Version 202  *****************
//User: Dipanjan     Date: 17/04/10   Time: 12:39
//Updated in $/LeapCC/Library
//Added "Daily Attenance" module in admin end
//
//*****************  Version 201  *****************
//User: Dipanjan     Date: 16/04/10   Time: 10:21
//Updated in $/LeapCC/Library
//Created "Teacher Attendance Report".This report is used to see total
//lectured delivered by a teacher for a subject within a specified date
//interval.
//
//*****************  Version 200  *****************
//User: Geetika      Date: 4/13/10    Time: 11:18a
//Updated in $/LeapCC/Library
//Moved the "Rooms master" menu from the Setup->Administrative masters to
//Setup->Buildings Masters menu
// Moved "Discipline master" from Setup->Administrative masters to the
//"Activities" menu and renamed to "Add Disciplinary Record"
//
//
//*****************  Version 199  *****************
//User: Dipanjan     Date: 7/04/10    Time: 11:50
//Updated in $/LeapCC/Library
//Done bug fixing.
//Fixed bugs---
//0003231,0003230,0003229,0003228,0003227,0003225,0003224,0003156
//
//*****************  Version 198  *****************
//User: Jaineesh     Date: 4/06/10    Time: 12:28p
//Updated in $/LeapCC/Library
//put new link occupied/free classes/rooms report and messages
//
//*****************  Version 197  *****************
//User: Abhiraj      Date: 4/06/10    Time: 11:01a
//Updated in $/LeapCC/Library
//Adding link for payroll module
//
//*****************  Version 196  *****************
//User: Parveen      Date: 4/01/10    Time: 2:54p
//Updated in $/LeapCC/Library
//menu added student enquiry
//
//*****************  Version 194  *****************
//User: Rajeev       Date: 10-03-26   Time: 10:54a
//Updated in $/LeapCC/Library
//removed calculate fees and fees installment link
//
//*****************  Version 193  *****************
//User: Dipanjan     Date: 22/03/10   Time: 13:56
//Updated in $/LeapCC/Library
//Added link for "Send student performance message to parents" module
//
//*****************  Version 192  *****************
//User: Ajinder      Date: 3/10/10    Time: 11:54a
//Updated in $/LeapCC/Library
//removed feedback reports link  [related to old feedback]
//
//*****************  Version 191  *****************
//User: Dipanjan     Date: 3/05/10    Time: 1:04p
//Updated in $/LeapCC/Library
//Added link for "Feedback Comments Report"
//
//*****************  Version 190  *****************
//User: Jaineesh     Date: 2/26/10    Time: 10:24a
//Updated in $/LeapCC/Library
//made the link uncommented for employee detail upload
//
//*****************  Version 189  *****************
//User: Dipanjan     Date: 25/02/10   Time: 13:54
//Updated in $/LeapCC/Library
//Created "Class Final Report"  for advanced feedback modules.
//
//*****************  Version 188  *****************
//User: Gurkeerat    Date: 2/25/10    Time: 1:03p
//Updated in $/LeapCC/Library
//updated link for 'Attendance Register' & 'Student Attendance Short
//Report'
//
//*****************  Version 187  *****************
//User: Parveen      Date: 2/25/10    Time: 12:09p
//Updated in $/LeapCC/Library
//Student Attendance Short Report menu added
//
//*****************  Version 186  *****************
//User: Gurkeerat    Date: 2/22/10    Time: 6:20p
//Updated in $/LeapCC/Library
//added link for user login report
//
//*****************  Version 185  *****************
//User: Dipanjan     Date: 22/02/10   Time: 17:04
//Updated in $/LeapCC/Library
//Commented out link for Feedback Teacher GPA Report as there is another
//report of detailed GPA of teachers
//
//*****************  Version 184  *****************
//User: Jaineesh     Date: 2/22/10    Time: 11:06a
//Updated in $/LeapCC/Library
//modification in link for uploading external marks
//
//*****************  Version 183  *****************
//User: Jaineesh     Date: 2/22/10    Time: 11:00a
//Updated in $/LeapCC/Library
//change menu for external marks
//
//*****************  Version 182  *****************
//User: Ajinder      Date: 2/19/10    Time: 6:33p
//Updated in $/LeapCC/Library
//fixed bug. 0002875
//
//*****************  Version 181  *****************
//User: Gurkeerat    Date: 2/19/10    Time: 2:37p
//Updated in $/LeapCC/Library
//added link for feedback teacher final report
//
//*****************  Version 180  *****************
//User: Parveen      Date: 2/19/10    Time: 1:56p
//Updated in $/LeapCC/Library
//new menu added AttendanceRegister
//
//*****************  Version 179  *****************
//User: Dipanjan     Date: 11/02/10   Time: 18:46
//Updated in $/LeapCC/Library
//Added link for "Teacher Detailed GPA Report"
//
//*****************  Version 178  *****************
//User: Dipanjan     Date: 11/02/10   Time: 15:27
//Updated in $/LeapCC/Library
//Added menu links for "Teacher GPA Report"
//
//*****************  Version 177  *****************
//User: Dipanjan     Date: 10/02/10   Time: 17:18
//Updated in $/LeapCC/Library
//Added menu links for "College GPA report" of feedback reports
//
//*****************  Version 176  *****************
//User: Jaineesh     Date: 2/09/10    Time: 5:59p
//Updated in $/LeapCC/Library
//put link & make new functions for upload student external marks
//
//*****************  Version 175  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:42
//Updated in $/LeapCC/Library
//Added links for "Feedback Assign Survey Report (Advanced)"
//
//*****************  Version 174  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 7:20p
//Updated in $/LeapCC/Library
//updated module names under feedback module
//
//*****************  Version 172  *****************
//User: Parveen      Date: 2/03/10    Time: 1:22p
//Updated in $/LeapCC/Library
//comments remove DisplayStudentReappearReport
//
//*****************  Version 171  *****************
//User: Parveen      Date: 2/03/10    Time: 1:21p
//Updated in $/LeapCC/Library
//menu added DisplayBusPassReport
//
//*****************  Version 170  *****************
//User: Parveen      Date: 2/01/10    Time: 2:50p
//Updated in $/LeapCC/Library
//Student Reappear link added
//
//*****************  Version 169  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:31p
//Updated in $/LeapCC/Library
//done changes for new Session End Activities
//
//*****************  Version 168  *****************
//User: Dipanjan     Date: 25/01/10   Time: 14:13
//Updated in $/LeapCC/Library
//Created "Send SMS" modules for sending SMSs to numbers entered by the
//end user
//
//*****************  Version 167  *****************
//User: Gurkeerat    Date: 1/22/10    Time: 2:35p
//Updated in $/LeapCC/Library
//removed link for site map
//
//*****************  Version 166  *****************
//User: Ajinder      Date: 1/21/10    Time: 3:43p
//Updated in $/LeapCC/Library
//done changes for siteMap
//
//*****************  Version 165  *****************
//User: Jaineesh     Date: 1/18/10    Time: 11:28a
//Updated in $/LeapCC/Library
//put new field university Roll No.
//
//*****************  Version 164  *****************
//User: Parveen      Date: 1/15/10    Time: 11:36a
//Updated in $/LeapCC/Library
//student re-appear link disable
//
//*****************  Version 163  *****************
//User: Parveen      Date: 1/14/10    Time: 5:18p
//Updated in $/LeapCC/Library
//DisplayStudentReappear menu added (Activities)
//
//*****************  Version 162  *****************
//User: Gurkeerat    Date: 1/06/10    Time: 5:22p
//Updated in $/LeapCC/Library
//changed link for promote students
//commented out Marks Distribution Report
//
//*****************  Version 161  *****************
//User: Ajinder      Date: 1/04/10    Time: 3:49p
//Updated in $/LeapCC/Library
//uncommented accounts menu
//
//*****************  Version 160  *****************
//User: Ajinder      Date: 12/30/09   Time: 4:05p
//Updated in $/LeapCC/Library
//done changes for new module creation 'change test category'
//
//*****************  Version 159  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 6:45p
//Updated in $/LeapCC/Library
//changed position of teat marks module
//
//*****************  Version 158  *****************
//User: Ajinder      Date: 12/29/09   Time: 6:00p
//Updated in $/LeapCC/Library
//done changes for attendance status report
//
//*****************  Version 157  *****************
//User: Jaineesh     Date: 12/29/09   Time: 3:18p
//Updated in $/LeapCC/Library
//put student group upload link
//
//*****************  Version 156  *****************
//User: Ajinder      Date: 12/29/09   Time: 1:54p
//Updated in $/LeapCC/Library
//done changes for marks status report
//
//*****************  Version 155  *****************
//User: Dipanjan     Date: 29/12/09   Time: 13:36
//Updated in $/LeapCC/Library
//Added messages and menu for "Attendance Set Master" module
//
//*****************  Version 154  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 11:47a
//Updated in $/LeapCC/Library
//commented out link for bank branch module
//
//*****************  Version 153  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:42p
//Updated in $/LeapCC/Library
//done changes to make new module for marks transfer
//
//*****************  Version 152  *****************
//User: Ajinder      Date: 12/23/09   Time: 6:33p
//Updated in $/LeapCC/Library
//added menu link for group copy
//
//*****************  Version 151  *****************
//User: Parveen      Date: 12/19/09   Time: 5:42p
//Updated in $/LeapCC/Library
//TeacherWiseTopicTaught menu added in reports
//
//*****************  Version 150  *****************
//User: Gurkeerat    Date: 12/18/09   Time: 12:44p
//Updated in $/LeapCC/Library
//added link for Upload & Download Images
//
//*****************  Version 149  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 5:28p
//Updated in $/LeapCC/Library
//resolved issues(0002281,0002305,0002282,0002279,0002280,0002277,0002307
//)
//
//*****************  Version 148  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Library
//Updated for new menu structure
//
//*****************  Version 136  *****************
//User: Dipanjan     Date: 16/11/09   Time: 14:57
//Updated in $/LeapCC/Library
//Hides menu link for "Swap" and "Move/Copy" time table for the time
//being
//
//*****************  Version 135  *****************
//User: Gurkeerat    Date: 11/14/09   Time: 5:48p
//Updated in $/LeapCC/Library
//Test Wise marks consolidated report is renamed as Test Type category
//wise detailed report
//marks not entered report is renamed as marks entered report
//
//*****************  Version 134  *****************
//User: Ajinder      Date: 11/11/09   Time: 11:55a
//Updated in $/LeapCC/Library
//added link for extra classes time table.
//
//*****************  Version 133  *****************
//User: Vimal        Date: 11/05/09   Time: 3:49p
//Updated in $/LeapCC/Library
//Removed TimeTable and AssigntStudentGroups link and added
//DeleteDailyAttendance linke.
//
//*****************  Version 132  *****************
//User: Jaineesh     Date: 11/04/09   Time: 4:28p
//Updated in $/LeapCC/Library
//give link move/copy teacher time table and add new field adjustment
//type in time_table_adjustment table
//
//*****************  Version 131  *****************
//User: Jaineesh     Date: 11/04/09   Time: 12:06p
//Updated in $/LeapCC/Library
//put new functions for student roll no. uploading and make link for
//student roll no. under student setup
//
//*****************  Version 130  *****************
//User: Dipanjan     Date: 29/10/09   Time: 15:05
//Updated in $/LeapCC/Library
//Added link for "Delete Attendance" module in admin menu
//
//*****************  Version 129  *****************
//User: Parveen      Date: 10/26/09   Time: 3:34p
//Updated in $/LeapCC/Library
//reports new menu added ConsolidatedReport
//
//*****************  Version 128  *****************
//User: Dipanjan     Date: 26/10/09   Time: 11:58
//Updated in $/LeapCC/Library
//Added link for "swap/adjust time table" module
//
//*****************  Version 127  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:33p
//Updated in $/LeapCC/Library
//make new link group upload 
//
//*****************  Version 126  *****************
//User: Rajeev       Date: 09-10-12   Time: 11:53a
//Updated in $/LeapCC/Library
//Updated with Access right parameters
//
//*****************  Version 125  *****************
//User: Ajinder      Date: 10/08/09   Time: 3:12p
//Updated in $/LeapCC/Library
//done changes for group assignment (advanced)
//
//*****************  Version 124  *****************
//User: Dipanjan     Date: 7/10/09    Time: 11:21
//Updated in $/LeapCC/Library
//Added link for "Student Academic Performance Report" and added access
//parameters
//
//*****************  Version 123  *****************
//User: Ajinder      Date: 10/03/09   Time: 11:19a
//Updated in $/LeapCC/Library
//added link for class wise day wise room wise time table.
//
//*****************  Version 122  *****************
//User: Ajinder      Date: 10/01/09   Time: 4:41p
//Updated in $/LeapCC/Library
//added link for class wise day wise time table.
//
//*****************  Version 121  *****************
//User: Jaineesh     Date: 9/30/09    Time: 7:02p
//Updated in $/LeapCC/Library
//put link for academic head privileges
//
//*****************  Version 120  *****************
//User: Ajinder      Date: 9/30/09    Time: 5:10p
//Updated in $/LeapCC/Library
//added link for new time table.
//
//*****************  Version 119  *****************
//User: Jaineesh     Date: 9/30/09    Time: 4:52p
//Updated in $/LeapCC/Library
//give link for grace marks 
//
//*****************  Version 118  *****************
//User: Jaineesh     Date: 9/29/09    Time: 11:28a
//Updated in $/LeapCC/Library
//put new link for enter marks
//
//*****************  Version 117  *****************
//User: Ajinder      Date: 9/23/09    Time: 3:49p
//Updated in $/LeapCC/Library
//added link + functions for test wise marks consolidated report.
//
//*****************  Version 116  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Library
//fixed bugs during self testing
//
//*****************  Version 115  *****************
//User: Parveen      Date: 9/10/09    Time: 10:48a
//Updated in $/LeapCC/Library
//reports -  EmployeeIcardReport menu added
//
//*****************  Version 114  *****************
//User: Dipanjan     Date: 4/09/09    Time: 13:17
//Updated in $/LeapCC/Library
//Added the link for "Bulk Attendance" module in admin section
//
//*****************  Version 113  *****************
//User: Dipanjan     Date: 1/09/09    Time: 11:21
//Updated in $/LeapCC/Library
//Done bug fixing.
//Bug ids---
//00001351,00001353,00001354,00001355,
//00001369,00001370,00001371
//
//*****************  Version 112  *****************
//User: Dipanjan     Date: 31/08/09   Time: 13:33
//Updated in $/LeapCC/Library
//Added link for "Room Allocation Master"
//
//*****************  Version 111  *****************
//User: Rajeev       Date: 09-08-31   Time: 1:10p
//Updated in $/LeapCC/Library
//Commented "Lecture Type" module link as it is not used in our
//application
//
//*****************  Version 110  *****************
//User: Rajeev       Date: 09-08-31   Time: 11:31a
//Updated in $/LeapCC/Library
//Commented Range Level link from menu as it is nowhere used
//
//*****************  Version 109  *****************
//User: Dipanjan     Date: 8/27/09    Time: 6:23p
//Updated in $/LeapCC/Library
//Gurkeerat: resolved issue 1271,1274
//
//*****************  Version 108  *****************
//User: Rajeev       Date: 09-08-24   Time: 1:05p
//Updated in $/LeapCC/Library
//Updated with Institute Wise Checks including ACCESS rights DEFINE
//
//*****************  Version 107  *****************
//User: Ajinder      Date: 8/24/09    Time: 11:30a
//Updated in $/LeapCC/Library
//fixed bug no.1204
//
//*****************  Version 105  *****************
//User: Parveen      Date: 8/21/09    Time: 10:38a
//Updated in $/LeapCC/Library
//SlabsMaster link remove (Exam Master)
//
//*****************  Version 104  *****************
//User: Ajinder      Date: 8/20/09    Time: 4:00p
//Updated in $/LeapCC/Library
//given Edit only permission in role permission
//
//*****************  Version 103  *****************
//User: Rajeev       Date: 09-08-20   Time: 1:51p
//Updated in $/LeapCC/Library
//Changed Period name define name as it was wrong in related files
//
//*****************  Version 102  *****************
//User: Ajinder      Date: 8/20/09    Time: 12:24p
//Updated in $/LeapCC/Library
//corrected report links
//
//*****************  Version 101  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:52p
//Updated in $/LeapCC/Library
//change in link
//
//*****************  Version 100  *****************
//User: Jaineesh     Date: 8/12/09    Time: 6:35p
//Updated in $/LeapCC/Library
//change the links
//
//*****************  Version 99  *****************
//User: Rajeev       Date: 8/11/09    Time: 11:52a
//Updated in $/LeapCC/Library
//0001009: Associate Subject to Class (Admin) > Print window Caption
//should be “Subject to Class Report Print” as clicked on “Print” button
//0001010: Associate Subject to Class (Admin) > Provide Save button on
//the right top of the grid. 
//
//*****************  Version 98  *****************
//User: Dipanjan     Date: 8/11/09    Time: 11:19a
//Updated in $/LeapCC/Library
//Gurkeerat: updated module names
//
//*****************  Version 97  *****************
//User: Ajinder      Date: 8/10/09    Time: 6:48p
//Updated in $/LeapCC/Library
//included accounts menu items.
//
//*****************  Version 96  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Library
//give print & export to excel facility
//
//*****************  Version 95  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Library
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 94  *****************
//User: Parveen      Date: 8/05/09    Time: 3:43p
//Updated in $/LeapCC/Library
//spelling correct smsDetailReport, smsFullDetailReport 
//
//*****************  Version 93  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Library
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 92  *****************
//User: Parveen      Date: 7/31/09    Time: 12:59p
//Updated in $/LeapCC/Library
//role permission updated reports menu (ARRAY(VIEW) updated only for
//reports)
//
//*****************  Version 91  *****************
//User: Ajinder      Date: 7/29/09    Time: 3:54p
//Updated in $/LeapCC/Library
//commented 'advance student filter report' as this filter is used as
//integral part of other reports.
//
//*****************  Version 90  *****************
//User: Jaineesh     Date: 7/29/09    Time: 3:53p
//Updated in $/LeapCC/Library
//give permissin for view & add in Student Fine Approval 
//
//*****************  Version 89  *****************
//User: Jaineesh     Date: 7/29/09    Time: 11:08a
//Updated in $/LeapCC/Library
//Make Generate Student Login link in Student Setup instead of Create
//Users and change in breadcrumb.
//
//*****************  Version 88  *****************
//User: Parveen      Date: 7/28/09    Time: 4:56p
//Updated in $/LeapCC/Library
//menu added generate parent logins
//
//*****************  Version 87  *****************
//User: Jaineesh     Date: 7/21/09    Time: 10:23a
//Updated in $/LeapCC/Library
//change the position of hostel room master
//
//*****************  Version 86  *****************
//User: Jaineesh     Date: 7/14/09    Time: 6:37p
//Updated in $/LeapCC/Library
//modified in queries, delete record student_groups,
//student_optional_subject
//
//*****************  Version 85  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:37p
//Updated in $/LeapCC/Library
//added Define for role permissions
//
//*****************  Version 84  *****************
//User: Jaineesh     Date: 7/13/09    Time: 4:34p
//Updated in $/LeapCC/Library
//fixed bug nos.0000116,0000099,0000117,0000119,0000121,0000097
//
//*****************  Version 83  *****************
//User: Dipanjan     Date: 13/07/09   Time: 12:07
//Updated in $/LeapCC/Library
//Remove link of "Thought" master's link
//
//*****************  Version 82  *****************
//User: Rajeev       Date: 7/11/09    Time: 4:03p
//Updated in $/LeapCC/Library
//Updated display teacher time table link with UI_HTTP_PATH
//
//*****************  Version 81  *****************
//User: Rajeev       Date: 7/07/09    Time: 6:44p
//Updated in $/LeapCC/Library
//added fine history report
//
//*****************  Version 80  *****************
//User: Jaineesh     Date: 7/07/09    Time: 4:48p
//Updated in $/LeapCC/Library
//put new links in menu for fine & changes in bread crum
//
//*****************  Version 79  *****************
//User: Parveen      Date: 7/07/09    Time: 2:21p
//Updated in $/LeapCC/Library
//Academics Masters menu Subject Category Master menu added 
//
//*****************  Version 78  *****************
//User: Jaineesh     Date: 7/07/09    Time: 2:18p
//Updated in $/LeapCC/Library
//make new link for fine collection report
//
//*****************  Version 77  *****************
//User: Rajeev       Date: 7/06/09    Time: 6:35p
//Updated in $/LeapCC/Library
//added collect fine link under fine tab
//
//*****************  Version 76  *****************
//User: Dipanjan     Date: 6/07/09    Time: 17:59
//Updated in $/LeapCC/Library
//Added link for "Assign Role to Fine Mapping Master" module
//
//*****************  Version 75  *****************
//User: Jaineesh     Date: 7/06/09    Time: 1:37p
//Updated in $/LeapCC/Library
//put new link student fine report
//
//*****************  Version 74  *****************
//User: Rajeev       Date: 7/03/09    Time: 4:30p
//Updated in $/LeapCC/Library
//Added link for fine student
//
//*****************  Version 73  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:06
//Updated in $/LeapCC/Library
//Added Links for Fine Category Module
//
//*****************  Version 72  *****************
//User: Rajeev       Date: 6/23/09    Time: 12:53p
//Updated in $/LeapCC/Library
//Updated teacher time table link
//
//*****************  Version 71  *****************
//User: Ajinder      Date: 6/19/09    Time: 11:29a
//Updated in $/LeapCC/Library
//commented link of 'Update Total Marks' as this is not applicable to CC.
//
//*****************  Version 70  *****************
//User: Jaineesh     Date: 6/16/09    Time: 5:47p
//Updated in $/LeapCC/Library
//modified menu by giving path
//
//*****************  Version 69  *****************
//User: Ajinder      Date: 6/16/09    Time: 5:37p
//Updated in $/LeapCC/Library
//updated menu links to point to http path.
//
//*****************  Version 68  *****************
//User: Parveen      Date: 6/15/09    Time: 2:06p
//Updated in $/LeapCC/Library
//report StudentBusPass menu added
//
//*****************  Version 67  *****************
//User: Parveen      Date: 6/12/09    Time: 2:21p
//Updated in $/LeapCC/Library
//report menu added in Student Exam Rankwise Report
//
//*****************  Version 66  *****************
//User: Ajinder      Date: 6/11/09    Time: 12:52p
//Updated in $/LeapCC/Library
//added menu for assigning optional subjects.
//
//*****************  Version 65  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Library
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 64  *****************
//User: Jaineesh     Date: 6/09/09    Time: 2:07p
//Updated in $/LeapCC/Library
//replicate of bug Nos.3,4 in cc
//
//*****************  Version 63  *****************
//User: Jaineesh     Date: 6/04/09    Time: 3:31p
//Updated in $/LeapCC/Library
//comment link update student class/roll no
//
//*****************  Version 62  *****************
//User: Dipanjan     Date: 6/04/09    Time: 1:02p
//Updated in $/LeapCC/Library
//Gurkeerat: added link for "Resource Category" module under "Academics
//Masters" in LeapCC 
//
//*****************  Version 61  *****************
//User: Administrator Date: 4/06/09    Time: 11:26
//Updated in $/LeapCC/Library
//Corrected bugs----
//bug ids--Leap bugs2.doc(10 to 15)
//
//*****************  Version 60  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:01a
//Updated in $/LeapCC/Library
//Added 'Parent, Student, Teacher and Management' Role permission
//
//*****************  Version 58  *****************
//User: Jaineesh     Date: 6/02/09    Time: 6:10p
//Updated in $/LeapCC/Library
//put new link offense report
//
//*****************  Version 57  *****************
//User: Administrator Date: 1/06/09    Time: 17:17
//Updated in $/LeapCC/Library
//Updated menu links name
//
//*****************  Version 56  *****************
//User: Jaineesh     Date: 6/01/09    Time: 3:19p
//Updated in $/LeapCC/Library
//put new link create users in student setup
//
//*****************  Version 55  *****************
//User: Jaineesh     Date: 6/01/09    Time: 12:03p
//Updated in $/LeapCC/Library
//put link of hostel room
//
//*****************  Version 54  *****************
//User: Administrator Date: 30/05/09   Time: 17:57
//Updated in $/LeapCC/Library
//Corrected bugs
//
//*****************  Version 53  *****************
//User: Rajeev       Date: 5/30/09    Time: 5:39p
//Updated in $/LeapCC/Library
//Updated with Role permissions link(Parent, teacher,management)
//
//*****************  Version 52  *****************
//User: Administrator Date: 29/05/09   Time: 16:50
//Updated in $/LeapCC/Library
//Added link for student enquiry
//
//*****************  Version 51  *****************
//User: Jaineesh     Date: 5/28/09    Time: 6:11p
//Updated in $/LeapCC/Library
//make new link role in reports
//
//*****************  Version 50  *****************
//User: Dipanjan     Date: 26/05/09   Time: 13:27
//Updated in $/LeapCC/Library
//Added link for "Send Message to Parents" module
//
//*****************  Version 49  *****************
//User: Administrator Date: 26/05/09   Time: 10:47
//Updated in $/LeapCC/Library
//Corrected display as reported by vimal sir.
//
//*****************  Version 48  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:33p
//Updated in $/LeapCC/Library
//Added Feedback Survey reports
//
//*****************  Version 47  *****************
//User: Administrator Date: 21/05/09   Time: 14:49
//Updated in $/LeapCC/Library
//Added links for "Assign Survey" module
//
//*****************  Version 46  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 45  *****************
//User: Parveen      Date: 5/20/09    Time: 2:20p
//Updated in $/LeapCC/Library
//icard menu file name update
//
//*****************  Version 44  *****************
//User: Administrator Date: 20/05/09   Time: 11:53
//Updated in $/LeapCC/Library
//Added link for "Duty Leaves" module
//
//*****************  Version 43  *****************
//User: Parveen      Date: 5/19/09    Time: 5:24p
//Updated in $/LeapCC/Library
//datewiseTestReport menu added
//
//*****************  Version 42  *****************
//User: Rajeev       Date: 5/08/09    Time: 5:50p
//Updated in $/LeapCC/Library
//Added test type distribution link
//
//*****************  Version 41  *****************
//User: Ajinder      Date: 5/07/09    Time: 8:17p
//Updated in $/LeapCC/Library
//added menu link for 'final internal report'
//
//*****************  Version 40  *****************
//User: Jaineesh     Date: 5/05/09    Time: 5:06p
//Updated in $/LeapCC/Library
//add new links for hostel, complaints, cleaning
//
//*****************  Version 39  *****************
//User: Rajeev       Date: 5/04/09    Time: 3:50p
//Updated in $/LeapCC/Library
//Added Hostel detail link
//
//*****************  Version 38  *****************
//User: Jaineesh     Date: 5/04/09    Time: 3:49p
//Updated in $/LeapCC/Library
//new links for complaint master, cleaning master
//
//*****************  Version 37  *****************
//User: Ajinder      Date: 5/02/09    Time: 7:21p
//Updated in $/LeapCC/Library
//added link for 'marks upload'
//
//*****************  Version 36  *****************
//User: Jaineesh     Date: 5/02/09    Time: 2:09p
//Updated in $/LeapCC/Library
//link closed for cleaning master
//
//*****************  Version 35  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:30p
//Updated in $/LeapCC/Library
//modified for test type category
//
//*****************  Version 34  *****************
//User: Rajeev       Date: 5/02/09    Time: 10:47a
//Updated in $/LeapCC/Library
//added academic report link
//
//*****************  Version 33  *****************
//User: Parveen      Date: 4/30/09    Time: 3:53p
//Updated in $/LeapCC/Library
//internalMarksFoxproReport menu added in reports
//
//*****************  Version 32  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:07p
//Updated in $/LeapCC/Library
//added report link
//
//*****************  Version 31  *****************
//User: Parveen      Date: 4/29/09    Time: 11:34a
//Updated in $/LeapCC/Library
//Student External Marks Report menu added in Reports
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:58p
//Updated in $/LeapCC/Library
//remove hostel data if non-existance in anoter table
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:51a
//Updated in $/LeapCC/Library
//put the menu for hostel room type
//
//*****************  Version 28  *****************
//User: Parveen      Date: 4/15/09    Time: 4:01p
//Updated in $/LeapCC/Library
//Display Multi Utility Time Table menu added in Time Table
//
//*****************  Version 27  *****************
//User: Rajeev       Date: 4/15/09    Time: 11:43a
//Updated in $/LeapCC/Library
//added suggestion link
//
//*****************  Version 26  *****************
//User: Dipanjan     Date: 10/04/09   Time: 14:18
//Updated in $/LeapCC/Library
//Added links for bus master reports
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 4/06/09    Time: 1:32p
//Updated in $/LeapCC/Library
//modified in name of listGroup
//
//*****************  Version 24  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:54
//Updated in $/LeapCC/Library
//Added links for bus masters
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 3/30/09    Time: 3:58p
//Updated in $/LeapCC/Library
//modified for delete
//
//*****************  Version 22  *****************
//User: Parveen      Date: 3/20/09    Time: 11:42a
//Updated in $/LeapCC/Library
//thoughts menu added
//
//*****************  Version 21  *****************
//User: Ajinder      Date: 3/18/09    Time: 12:50p
//Updated in $/LeapCC/Library
//changed link of transfer marks to transfer internal marks
//
//*****************  Version 20  *****************
//User: Parveen      Date: 3/18/09    Time: 12:37p
//Updated in $/LeapCC/Library
//Institute Setup - Attendance Marks Percent menu added 
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 3/18/09    Time: 12:16p
//Updated in $/LeapCC/Library
//Updated config managment link url
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Library
//modified for test type & put test type category
//
//*****************  Version 17  *****************
//User: Parveen      Date: 3/12/09    Time: 3:25p
//Updated in $/LeapCC/Library
//Bulk Subject Topic Master menu added in acadmics
//
//*****************  Version 16  *****************
//User: Ajinder      Date: 3/10/09    Time: 6:38p
//Updated in $/LeapCC/Library
//added link for update student groups.
//
//*****************  Version 15  *****************
//User: Parveen      Date: 2/20/09    Time: 4:02p
//Updated in $/LeapCC/Library
//Teacher Substitutions menu added in time table
//
//*****************  Version 14  *****************
//User: Parveen      Date: 1/19/09    Time: 6:32p
//Updated in $/LeapCC/Library
//menu added display LoadTeacherTimeTable
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:26p
//Updated in $/LeapCC/Library
//added role permission and dashboard permission
//
//*****************  Version 12  *****************
//User: Parveen      Date: 1/16/09    Time: 1:31p
//Updated in $/LeapCC/Library
//listSubjectTopic menu added in Academics Masters
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 1/14/09    Time: 5:51p
//Updated in $/LeapCC/Library
//added class timetable and room timetable links
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/12/09    Time: 4:39p
//Updated in $/LeapCC/Library
//Student Icard Report Menu Added
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:22
//Updated in $/LeapCC/Library
//Corrected spelling mistake
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/23/08   Time: 5:36p
//Updated in $/LeapCC/Library
//report==> employee filter  added
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 23/12/08   Time: 12:13
//Updated in $/LeapCC/Library
//Corrected breadcrumb and added "Decepline Master" link in the menu
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/23/08   Time: 11:22a
//Updated in $/LeapCC/Library
//master menu update
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/22/08   Time: 6:34p
//Updated in $/LeapCC/Library
//modified for Offense Master
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 12/22/08   Time: 5:36p
//Updated in $/LeapCC/Library
//added link for change password for rajeev
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:25p
//Updated in $/LeapCC/Library
//Added Change Password menu
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/22/08   Time: 3:02p
//Updated in $/LeapCC/Library
//updated the link for student filter.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/22/08   Time: 2:50p
//Created in $/LeapCC/Library
//file added for making menus dynamic
//


?>