<?php
//-------------------------------------------------------
//  This File is used for site map items description
//
//
// Author :Ajinder Singh
// Created on : 21-Jan-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


if ($roleId == 2) {		//for teachers
	define('DailyAttendance','Used for taking daily attendance. ');
}
else if ($roleId == 3) {	//for parents
	define('DailyAttendance','Used for taking daily attendance. ');
}
else if ($roleId == 4) {	//for students
	define('DailyAttendance','Used for taking daily attendance. ');
}
else if ($roleId == 5) {	//for management
	define('DailyAttendance','Used for taking daily attendance. ');
}
else {	//for all other logins
	define('ConfigMaster','Used for setting configuration');
	define('CountryMaster','Lets you define the countries that would appear in different countries drop down lists in the application. For example, when entering the addresses');
	define('StudentInfo','Used for adding students');
	define('StateMaster','Lets you define the states that would appear in different countries drop down lists in the application. For example, when entering the addresses');
	define('CityMaster','Lets you define the states that would appear in different countries drop down lists in the application. For example, when entering the addresses');
	define('UniversityMaster','Lets you define the Universities that the institutes defined in this application would be affiliated to');
	define('RoomsMaster','Lets you create the different rooms in the academic block where classes would be held');
	define('QuotaMaster','Lets you define the different Quotas( SC/ST/Gen/Mgmt/etc ) under which students can take admissions');
	define('EmployeeMaster','Lets you create a list of employees in your institute with complete details of all the employees');
	define('UploadEmployeeDetail','Lets you import the list of emmplyees from a pre-formatted excel sheet');
	define('DesignationMaster','Lets you create the different designations which the employees in your institute would carry(eg. Dean, HOD, lecturer, etc )');
	define('DepartmentMaster','Lets you create the different departments in your institute( eg. Mathematics, Computer science, etc )');
	define('OffenseMaster','Lets you define the different heads under which you record students disciplinary offences( eg. Late coming, Not in uniform, bunking classes, etc )');
	define('DisciplineMaster','Lets you enter the disciplinary offences for students.');
	define('TestTypesMaster','Lets you create the different types of test types under which tests are taken in the istitute');
	define('TestTypeCategoryMaster','Lets you create the test type category( eg. Quizzes, Assignments, Surprise tests, etc )');
	define('EvaluationCrieteria','Lets you create the different criteria in terms of weightages to be assigned for different test types when final marks are to be computed');
	define('DegreeMaster','Lets you create the different degrees that the institute can confer on students(eg. B.Tech, Mt.Tech, MBA, etc)');
	define('BranchMaster','Lets you create the different disciplines/branches under different degrees that the institute has courses in( CSE, Civil, Electrical,etc).');
    define('BatchMaster','Lets you create the different batches in which students would be present/admitted( eg. 2007, 2008,2009, etc )');
	define('SessionMaster','Lets you create the different sessions running in the college and on this basic present and past records can be seen(eg. 2008-09, 2009-10, etc)');
	define('PeriodicityMaster','Lets you create the different periodicities that the different degree programmes in the institutes can have. (eg. some can have Trimester system, Some Semester , etc )');
	define('StudyPeriodMaster','Lets you create the study periods liks "1st Semester", "2nd Semester", etc');
	define('GroupTypeMaster','Lets you create the different group types in an institute( eg. Theory, Practical, Tutorial )');
	define('GroupMaster','Lets you create the actual group names in which students would be allocated.');
	define('GroupCopy','Lets you copy groups of students from one group to another.');
	define('PeriodSlotMaster','Lets you create the different period slots that can possibly be used in an institute.( eg. 60 minutes slot, 90 minutes period slots, etc )');
	define('PeriodsMaster','Lets you create the list of periods that would be used in an instituted for purpose of creating and managing the time table(eg. period1, period2...period9, etc).');
	define('CreateClass','Lets you create the diffrent classes being taught in an institute. A class is defined as a combibation of university+degree+branch+batch+semester. This menu lets you create a list of classes, including past, currently active and future. ');
	define('AssignCourseToClass','Lets you map the subjects that are to be taught in a particular class.');
	define('AttendanceSetMaster','Lets you create a attendance set for defining marks that are to be given ');
	define('SubjectCategory','Lets you create the subjects categories that are in used typically in MBA courses');
	define('SubjectTypesMaster','Lets you create the different subject types like Theory , Practical.');
	define('Subject','Lets you create the subjects being taught. Complete details of the subjects are entered here. For any subject to be listed in the time table it has to be created here first.');
	define('SubjectTopic','Lets you create the different topics that would be taught in the lectures/practicals for the subject. Topics entered here would appear in the selection list of topics when a teacher takes students attendance. This way the teacher can choose which topic was taught in a particular class and thus at any point know which topics have been covered and which remaining.The topics here are entered one by one.');
	define('BulkSubjectTopic','This also allows one to enter the subjects topics to be taught but instead through a faster approach. Multiple topics can be entered into the system in one go using the subject bulk topics master.');
	define('AttendanceCodesMaster','Lets you define the attendance codes that would appear in the drop down when a teacher takes attendance for a class. Typically "A" is absent, "P" is for present. However, one could also define a "OD" as offical duty and assign it 50% attendance.');
	define('ResourceCategory','Lets you enter the categories under which the teacher can upload different resource materials. Examples are ppts, docs, assignments, notes, etc.');
	define('BusCourse','Lets you create the complete details of the different buses used for student trabsport.');
	define('BusRouteMaster','Lets you create the details of the bus routes on which the different buses would ply.');
	define('BusStopCourse','Lets you create the details of the different bus stops at which the buses would stop.');
	define('TransportStuffMaster','Lets you define the details of the bus staff,eg: conductor, driver');
	define('BuildingMaster','Lets you define the details of the different buildings which are on campus');
	define('BlockCourse','Lets you define the details of blocks in different buildings on the campus');
	define('HostelMaster','Lets you define the details of the different hostels');
	define('HostelRoomType','Lets you define the details of the different types of rooms which may be available in the hostels. eg. AcRooms, DoubleRooms, SingleRooms, etc');
	define('HostelRoomTypeDetail','Lets you define the actual facilities in the different rooms types defined in the Room type master.');
	define('HostelRoomCourse','Lets you define the different room names in a hostel.eg. R213, R456,C432');
	define('ComplaintCategory','Lets you record complaints related to hostel facilitities. eg. plumbing, fan not working, etc.');
	//define('','');
	define('HostelVisitor','Lets you keep a track of visitors to the hostel.');
	define('TemporaryDesignationMaster','Lets you create designations for class employees responsible for upkeep of hostel facilities.');
	//define('','');
	define('TemporaryEmployee','Lets you create class employees responsible for upkeep of hostel facilities.');
	define('FeeHeads','Lets you define what are all the possible heads under which fees can be taken from students.');
	define('FeeHeadValues','Lets you define the actualy fees amounts under various heads that need to be taken as fees for specific fees cycles and from students of specific classes/courses.');
	define('FeeCycleFines','Lets you define the fines for paying fees late for a particular fees cycle.');
	define('FeeCycleMaster','Lets you create multiple fees cycles under which fees can be defined and subsequently taken.');
	define('FundAllocationMaster','Lets you define the account heads under which fees collected would go. eg. University, Institute');
	define('BankMaster','Lets you define the different banks in which fees can be deposited.');
	define('CreateTimeTableLabels','Lets you create the time table names. eg. TT-Jan-Jun10');
	define('AssociateTimeTableToClass','Lets you map which time table applies to which class.');
	define('RoleMaster','Lets you define the different roles that can use the application.eg. HOD, Time table manager, Exam controller, etc');
	define('RolePermissions','Lets you define the permissions for the roles, ie which role is allowed to perform which functions in the application.');
	define('TeacherRolePermissions','Lets you define what all is the teacher allowed to do in the application.');
	define('StudentRolePermissions','Lets you define what all is the student allowed to do in the application.');
	define('ParentRolePermissions','Lets you define what all is the parent allowed to do in the application.');
	define('ManagementRolePermissions','Lets you define what all is the management allowed to do in the application.');
	define('ManageUsers','Lets you create/edit/delete users who can log into the application. Users can also be given multiple roles from here. eg. An employee can be a teacher and an HOD as well.');
	define('RoleToClass','Lets you define the permissions of HODs');
	define('Admit','Lets you admit a student in a class.');
	define('UpdatePasswordReport','Lets you generate single or multiple student logins so that they can then login into the application. The students should have been admitted first in order to generate his/her login.');
	define('AssignRollNumbers','Lets you generate the roll numbers of student in a bulk manner. Roll number patterns can also be defined from this screen.');
	define('UploadStudentGroup','Lets you upload a group of students from a pre-foratted excel sheet');
	define('UploadStudentRollNo','Lets you assign roll numbers to a group of students by uploading a pre-formatted excel sheet');
	define('UploadStudentDetail','Lets you upload the students to be admitted from a pre-formatted excel sheet instead of doing it one by one through the admit screen. This saves time when a lot of students need to be admitted.');
	define('QuarantineStudentMaster','Lets you delete a student. For example, after admission if a student cancels his/her admission, his/her names needs to be deleted so that it does not figure out in any of the groups, time tables, reports, etc. The student is however not deleted permanently from the database and can be restored later.');
	define('RestoreStudentMaster','Lets you restore deleted students.');
	define('StudentClassRollNo','Lets you update/edit student classes or roll numbers in bulk by uploading a pre-formatted excel sheet.');
	define('CreateParentLogin','Lets you generate the logins for parents of students. After doing this activity, the user can print letters for parents which have the instructions for logging in and mail them to the parents.');
	define('AssignGroupAdvanced','Lets you put students of different classes in various group( eg. theory , practical, tutorial )');
	define('AssignOptionalSubjects','Lets you assign the optional subjects to students. This is typically used for MBA flows where students undergo specializations in their second years and choose from a range of subjects');
	define('UpdateStudentGroups','Lets you change student groups');
	define('GraceMarks','Lets you manage the grace marks that may be assigned to students as part of their final evaluations.');
	define('CreateTimeTableAdvanced','Lets you create a classwise time table. Here you choose a class as a pivot and enter other values to create the time table
');
	define('CreateTimeTableClassWiseDayWise','Lets you create a classwise and daywise time table. Here you choose a class as the first pivot and day as the second pivot and enter other values to create the time table');
	define('CreateTimeTableClassWiseDayWiseRoomWise','Lets you create a classwise , daywise and room wise time table. Here you choose a class as the first pivot ,day as the second pivot and Room as the third pivot enter other values to create the time table');
	define('DisplayTeacherTimeTable','Lets you see any teachers time table');
	define('DisplayStudentTimeTable','Lets you see any students time table');
	define('DisplayClassTimeTable','Lets you see the time table of any class');
	define('DisplayRoomTimeTable','Lets you see the time table for a particular room');
	//define('','');
	define('TransferInternalMarksAdvanced','Lets you perform the function of transferring the internal marks. Transfer of marks involves the process of computing the students collective marks based on the internal assesments done over a period of time and according to the weightages assigned to these assesments.');
	define('UploadStudentExternalMarks','Lets you enter the external( typically the final university exam ) marks for students');
	define('AttendancePercent','Lets you define the relation between how many marks to be given to students on the basis of the attendance percentage. This would be applicable only if the institute has awarded marks attendance as part of the final assesment.');
	define('LecturePercent','Lets you define the relation between how many marks to be given to students on the basis of the lectures attended slabs. This would be applicable only if the institute has awarded marks attendance as part of the final assesment.');
	//define('','');
	define('PromoteStudentsAdvanced','Lets you promote the students.');
	define('FrozenTimeTableToClass','Lets you Freeze the data so that the marks and attendance cannot be changed by mistake.');
	define('BulkAttendance','Lets you enter students attendance over a period of time.');
	//define('','');
	define('DeleteAttendance','Lets you delete students attendance. You may want to do this if the attendance entered is wrongly entered by mistake.');
	define('TestMarks','Lets you enter the marks for a student.');
	//define('','');
	define('EmployeeIcardReport','Lets you generate employee I-cards');
	define('StudentBusPass','Lets you generate student bus passes');
	define('StudentIcardReport','Lets you generate student I-cards');
	//define('','');
	//define('','');
	//define('','');
	//define('','');
	//define('','');
	define('HostelList','Lets you see the details of rooms, room types, etc in a hostel');
	define('CleaningHistoryMaster','Lets you view the cleaning history for hostels');
	define('RoleWiseList','Lets you see which user');
	define('StudentAttendance','Lets you check the attendance details of any student');
	define('PercentageWiseAttendanceReport','Lets you check students who are falling above or below a certain student threshold');
	define('AttendanceStatusReport','Lets you view the details of the teachers who have entered attendance upto a specific date in the system');
	define('AttendanceRegister','Lets you view  the attendance for a class in the attendance register format');
	//define('','');
	define('TestWiseMarksReport','Lets you view the test marks details of the students');
	define('FinalInternalReport','Lets you view in a single screen the complete details of internal marks for students');
	//define('','');
	//define('','');
	//define('','');
	define('ExternalMarksReport','Lets you view in a single screen the complete details of external marks for students');
	define('InternalMarksFoxproReport','Lets you view in a single screen the complete details of internal marks for students in Fox-pro format');
	//define('','');
	//define('','');
	//define('','');
	//define('','');
	//define('','');
	//define('','');
	define('InstallmentDetailOfStudents','Lets you view the fee payment installment details of student');
	define('FeeCollection','Lets you collect the fees from the students');
	define('DisplayFeePaymentHistory','Lets you view the complete fee payment details of students');
	//define('','');
	//define('','');
	//define('','');
	//define('','');
	define('InsuranceDueReport','Lets you view which vehicles are having their insurance due on a specific date');
	define('FuelUsage','Lets you view the details of fuel consumption by one or more vehicles');
	define('BusRepairCost','Lets you view the details of repair cost for vehicles in the institute fleet');
	//define('','');
	//define('','');
	//define('','');
}

?>