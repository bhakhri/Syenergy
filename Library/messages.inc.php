<?php
//-------------------------------------------------------
// Purpose: to centralize all messages of the application and can be altered on need basis
// Author : Pushpender Kumar Chauhan
// Created on : (06.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

// Dashboard Welcome message
define('WELCOME_ADMIN','Welcome to Admin section');

// Standard Messages
define('SUCCESS','Data saved successfully');
define('DELETE','Data has been deleted successfully');
define('DEL','Data has been deleted successfully');
define('DEPENDENCY_CONSTRAINT','Data could not be deleted due to records existing in linked tables');
define('DEPENDENCY_CONSTRAINT_EDIT','Data could not be edited due to records existing in linked tables');
define('FAILURE','Data could not be saved successfully');
define('MAIL_SENT','Mail has been sent successfully');
define('TECHNICAL_PROBLEM','Either the file you are looking for is not available or is deleted.\nPlease copy the url and send it in an email to support@chalkpad.in with the description of the error.\nOR\nYour session has timed out due to the network connection breaking down or being very slow.\nPlease re-try the operation.');

define('ADD_MORE','Do you want to add more?');
define('DELETE_CONFIRM','Do you want to delete this record?');
define('DELETE_CONFIRM2','Do you want to delete records?');
define('RESTORE_CONFIRM','This action can not be undone.\nDo you want to restore selected student(s) ?');
define('ACCESS_DENIED','Access Denied');
define('SESSION_TIMEOUT','Oops..Your session is expired. Please login again.');
define('SELECT_INSTITUTE','Select an institute');
define('UNSAVED_DATA_ALERT','Your changes has not been saved');
define('DATABASE_TAMPERED','Database has been tampered, a mail has been sent to administrator regarding this.');


// Modules Common Messages
define('SELECT_COUNTRY','Select country');
define('SELECT_STATE','Select state');
define('SELECT_CITY','Select city');
define('ENTER_ALPHABETS','Enter only alphabetical characters (a-z)');
define('ENTER_STRING','Enter String');
define("SELECT_SUBJECT_TYPE","Select Subject Type");
define("SELECT_UNIVERSITY","Select University");
define("SELECT_DEGREE","Select Degree");
define("SELECT_BRANCH","Select Branch");
define("SELECT_BATCH","Select Batch");
define('SELECT_CLASS','Select Class');
define('SELECT_GROUP','Select Group');
define("SELECT_STUDYPERIOD","Select Study Period");
define('SELECT_FEECYCLE','Select Fee Cycle');
define('SELECT_FEEHEAD','Select Fee Head');
define('SELECT_FEEFUNDALLOCATION','Select Fee Fund Allocation');
define('SELECT_FEECYCLEFINE','Select Fee Fine Type');
define("DATE_VALIDATION","To Date can not be smaller than From Date");
define("VISIBLE_DATE_VALIDATION","Visible To Date can not be smaller than Visible From Date");
define("EMPTY_DATE_VALIDATION","Date fields can not be empty");
define("SELECT_ATLEASTONE_CHECKBOX","Please Select at least one checkbox");
define('SELECT_SUBJECT','Select subject');
define('SELECT_COURSE','Select Course');
define('SELECT_STUDY_PERIOD','Select study period');
define('ENTER_ALPHABETS_NUMERIC','Enter only alphabetical/numeric characters (a-z,0-9)');
define('ENTER_PIN','Enter a PIN');
define('ENTER_VALID_EMAIL','Enter a valid email address');
define('ENTER_VALID_PHONE_NO','Enter a valid phone number');
define('EMPTY_FROM_DATE','From Date Can Not Be empty');
define('EMPTY_TO_DATE','To Date Can Not Be empty');
define('DATE_VALIDATION2','To Date Can Not be Greater Than Current Date');
define('FUTURE_DATE_VALIDATION','Date should be equal to or less then Current Date');
define('STUDENTS_WITH_ZERO_MARKS','Students with zero marks : ');
define('COLLECT_FEE_ID_NOT_EXIST','Collect Fee Id Not Exist');
define('COLLECT_FEE_CLASS','Select Collect Fee Class');
define('SELECT_FEE_CLASS','Select Fee Class'); 
define('ENTER_NAME_ROLLNO',"Please Enter Reg./Univ/Roll No. Or Student Name");
define('FEE_HEAD_NOT_DEFINE',"Fee Head not define");   

define('STUDENT_CONCESSION_CATEGORY','Already apply for category wise concession');


define('EMPTY_DATE_FROM','Date from can not be empty');
define('EMPTY_DATE_TO','Date to can not be empty');


// State Module
define('STUDENT_NOT_EXIST','This student does not exist');
define('ENTER_STUDENT_AGE','Enter age of student');
define('ENTER_STUDENT_NAME','Enter name of student');
define('ENTER_STUDENT_CODE','Enter code of student');
define('STUDENT_NAME_LENGTH','Student Name can not be less than 3 characters');
define('STUDENT_ALREADY_EXIST','The student code you entered already exists');
define('STUDENT_NAME_ALREADY_EXIST','The student name you entered already exists');



// CITY Module
define('CITY_NOT_EXIST','This City does not exist');
define('ENTER_CITY_NAME','Enter name of city');
define('ENTER_CITY_CODE','Enter code of city');
define('SELECT_STATE_NAME','Select a state');
define('CITY_NAME_LENGTH','City Name can not be less than 3 characters');
define('CITY_CODE_ALREADY_EXIST','city code already exists.');
define('CITY_NAME_ALREADY_EXIST','city name already exists.');


// QUOTA Module
define('QUOTA_NOT_EXIST','This Quota does not exist');
define('ENTER_QUOTA_NAME','Enter Quota Name');
define('ENTER_QUOTA_ABBR','Enter Quota Abbr.');
define('QUOTA_NAME_LENGTH','Quota Name can not be less than 3 characters');
define('QUOTA_ALREADY_EXIST','Quota abbreviation already exists.');
define('QUOTA_NAME_ALREADY_EXIST','Quota Name already exists.');

//-------------------------------------------------------
// Purpose: to centralize all messages of the application and can be altered on need basis
// Author : Arvind Singh Rawat
// Created on : (19.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

// COUNTRY Module

define("ENTER_COUNTRY_NAME","Enter name of Country");
define("ENTER_COUNTRY_CODE","Enter Code of Country");
define("ENTER_NATIONALITY","Enter Nationality");
define("COUNTRY_NAME_ALREADY_EXISTS","Country name already exists");
define("COUNTRY_CODE_ALREADY_EXISTS","Country code already exists");
define("COUNTRY_NATIONALITY_ALREADY_EXISTS","Nationality already exists");
define('ENTER_ALPHABETS_CHAR','Enter following (a-z &.-) characters only');



// BRANCH Module
define("ENTER_BRANCH_NAME","Enter Branch Name");
define("ENTER_BRANCH_CODE","Enter abbreviation");
define("BRANCH_ALREADY_EXIST","Branch Name already exists");
define("ABBR_ALREADY_EXIST","Abbreviation already exists");
define('ACCEPT_ALPHABETS_NUMERIC',"Enter following (a-z A-Z 0-9 &.-/) characters only");
define('ACCEPT_ALPHABETS_NUMERIC_ABBR','Accepted characters for abbrevations (a-z,0-9)');

//PUBLICATION MODULE
define('PUBLICATION_NAME_ALREADY_EXISTS',"Publication Name already exist");
define('ENTER_PUBLICATION_NAME',"Enter Publication Name");
//BATCH Module

define("ENTER_BATCH_NAME","Enter name of Batch");
define("ENTER_BATCH_YEAR","Enter Batch Year");
define("ENTER_BATCH_START_DATE","Enter Batch Start Date");
define("ENTER_BATCH_END_DATE","Enter Batch End Date");
define("DATE_CONDITION1","From Date can not be smaller than To Date");
define("DATE_CONDITION","End Date can not be smaller than Start Date");
define("COUNTRY_NAME_LENGTH","Country Name can not be less than 3 characters");

// PERIODICITY Module

define("ENTER_PERIODICITY_NAME","Enter Periodicity Name");
define("ENTER_PERIODICITY_CODE","Enter Periodicity Code");
define("ENTER_PERIODICITY_FREQUENCY","Enter Annual Frequency");
define("ENTER_PERIODICITY_NUMBER","Enter only number in annual frequency");
define("ENTER_PERIODICITY_ABBR","Enter Periodicity Abbreviation");
define("PERIODICITY_ABBR_EXIST","Periodicity abbreviation already exists");
define("PERIODICITY_ALREADY_EXIST","Periodicity name already exists");

// SUBJECT Module
define("ENTER_SUBJECT_NAME","Enter subject name");
define("ENTER_SUBJECT_CODE","Enter subject code");
define("ENTER_SUBJECT_ABBREVATION","Enter subject Abbreviation");
define('SUBJECT_CODE_ALREADY_EXISTS','Subject code already exists');
define('SUBJECT_NAME_ALREADY_EXISTS','Subject name already exists');
define('SUBJECT_CATEGORY_NAME','Select subject category');
define("SUBJECT_CATEGORY_PARENT_RELATION","This category name is parent of one or more");




// SUBJECT TYPE Module
define("ENTER_SUBJECT_TYPE_NAME","Enter subject type name");
define("ENTER_SUBJECT_TYPE_CODE","Enter subject type abbreviation");
define("SUBJECT_TYPE_ABBR_ALREADY_EXIST","Abbr. already exists");
define("SUBJECT_TYPE_ALREADY_EXIST","Subject type already exists");


// COURSE Module

define("ENTER_COURSE_NAME","Enter Course name");
define("ENTER_COURSE_CODE","Enter Course code");
define("ENTER_COURSE_ABBREVATION","Enter Course Abbreviation");
define('COURSE_CODE_ALREADY_EXISTS','Subject code already exists.');

// SECTION Module

define("SECTION_TYPE_LECTURE","Select Section Type as Lecture");
define("ENTER_SECTION_NAME","Enter Section name");
define("ENTER_SECTION_ABBREVIATION","Enter Subject Abbreviation");
define("CHOOSE_SECTION_TYPE","Select Section Type");
define("SECTION_ABBR_NAME_ALREADY_EXISTS","Section Abbreviation Name already Exists");
define("SECTION_ABBR_ALREADY_EXISTS","Section Abbreviation already Exists");
define("DEPENDENCY_CHECK_SESSION","Section is Dependent on another");
define("CYCLIC_CHECK","Cyclic Redundancy Violating Check");
define("SECTION_PARENT_ITSELF","Section cannot be a parent of itself");
define("SECTION_PARENT_RELATIONSHIP","Section is the parent of another section.Delete child Section first");
define("SUB_SECTION_CHECK","This section has sub section So,You are not allowed to change parent Section .");
define("STUDENT_ALLOCATION_CHECK","Student has been allocated to this section. So, you cannot change any Field");
define("DELETE_ALLOCATED_STUDENT","Student has been allocated to this section. So, you cannot delete this section");

// COURSE TYPE Module

define("ENTER_COURSE_TYPE_NAME","Enter Course Type name");
define("ENTER_COURSE_TYPE_CODE","Enter Course Type code");
define("SELECT_COURSE_TYPE","Select Course Type");

// ATTENDANCE CODE Module

define("ENTER_ATTENDANCE_NAME","Enter Attendance Name");
define("ENTER_ATTENDANCE_CODE","Enter Attendance Code");
define("ENTER_ATTENDANCE_DESCRIPTION","Enter description");
define("ENTER_ATTENDANCE_PERCENTAGE","Enter percentage");
define("ENTER_ATTENDANCE_NUMBER","Enter percentage in numbers");
define("ATTENDANCE_EXISTS_SEPARATELY","Attendance already exists separately for selected dates");
define('INVALID_PERCENTAGE','Percentage can not be more than 100 and less than 0');

// FEE HEAD Module
define("ENTER_FEEHEAD_NAME","Enter Head Name");
define("ENTER_FEEHEAD_ABBR","Enter Head Abbr.");
define("ENTER_FEEHEAD_ORDER","Enter Display Order");
define('FEEHEAD_NAME_LENGTH','Fee Head Name can not be less than 3 characters.');
define('FEEHEAD_DISPLAY_ORDER_EXIST','Already assigned value in Display Order');
define('FEEHEAD_NAME_EXIST','Name already exist');
define('FEEHEAD_ABBR_EXIST','Abbr. already exist');
define("FEEHEAD_PARENT_RELATION","Parent-Child relation exist cannot delete parent, first delete the child");
define("FEEHEAD_NOT_ITSELF","Parent head cannot be parent of itself");
define("FEEHEAD_PARENT_ALREADY","Parent group already exist for this fee head name");
define("FEEHEAD_FEECYCLEFINES_ALREADY","This head name is allocated to fee cycle fines, you cannot edit this head name");
define("FEEHEAD_FEEHEADVALUE_ALREADY","This head name is allocated to fee head values, you cannot edit this head name");
define("FEEHEAD_FEEHEADVALUE_ALREADY","This head name is allocated to fee head values, you cannot edit this head name");
define("FEEHEAD_PARENT_RELATION","This fee head is parent of one or more");


// FEE HEAD VALUES Module
define("ENTER_FEEHEADVALUES_AMOUNT","Enter Fee Head Amount");
define("ENTER_FEEHEADVALUES_NUMBER","Enter only numeric value");

//Fee Concession Category
define("CATEGORY_ORDER_SHOULD_NOT_BE_ZERO_0R_NEGATIVE","Category Order should not be less than zero or negative");
define("ENTER_CATEGORY_NAME","Enter Category Name");
define("ENTER_CATEGORY_ORDER","Enter Category Order");
define("CATEGORY_ORDER_ALREADY_EXISTS","Category Order Already Exists");
define("CATEGORY_NAME_ALREADY_EXISTS","Category Name Already Exists");
define("CATEGORY_ORDER_VALUE","Category Order should be between 0 and 100");

// FEE CYCLE FINE Module

define("ENTER_FEECYCLEFINE_AMOUNT","Enter Fine Amount");
define("ENTER_FEECYCLEFINE_NUMBER","Enter only Number");
define("ENTER_FEECYCLEFINE_TYPE","Enter Fee Cycle Fine Type");
define("SELECT_FEECYCLE_FINE","Select Fee Cycle Fine");
define("SELECT_FEECYCLE_TYPE","Select Fine Type");


// FEE CYCLE Module

define("ENTER_FEECYCLE_NAME","Enter Fee Cycle Name");
define("ENTER_FEECYCLE_ABBR","Enter Fee Cycle Abbr.");
define("ENTER_FEECYCLE_FROM_DATE","Enter From Date");
define("ENTER_FEECYCLE_TODATE","Enter To Date");
define('CYCLE_NAME_EXIST','Cycle Name already exist');
define('CYCLE_ABBR_EXIST','Cycle Abbr. already exist');

// FEE FUND ALLOCATION Module

define("ENTER_FEEFUNDALLOCATION_ENTITY","Enter Allocation Entity");
define("ENTER_FEEFUNDALLOCATION_TYPE","Enter abbreviation");
define("FEEFUNDALLOCATION_ENTITY_EXIST","Allocation Entity already exist");
define("FEEFUNDALLOCATION_TYPE_EXIST","Abbreviation already exist");



// ADD NOTICES Module

define("ENTER_NOTICE_SUBJECT","Enter Subject");
define("ENTER_NOTICE_TEXT","Enter Text");
define("SELECT_DEPARTMENT","Select department");
define("OLD_NOTICE","Expired messages cannot be deleted");

//SMS ALERTS DEFINES

define('NEW_NOTICE_IS_ADDED_FOR_DETAILS_LOGIN_TO_syenergy','New Notice Is Added For Details Login To syenergy');
define('STUDENT_NEW_MARKS_HAS_BEEN_UPlOADED_FOR_DETAILS_LOGIN_TO_syenergy','Student New Marks Has Been Uploaded For Details Login To syenergy');
define('MARKS_HAS_BEEN_UPLOADED_FOR_DETAILS_LOGIN_TO_syenergy','Marks Has Been Uploaded For Details Login To syenergy');
define('ATTENDANCE_HAS_BEEN_UPLOADED_FOR_DETAILS_LOGIN_TO_syenergy','Attendance Has Been Uploaded For Details Login To syenergy');
define('TIME_TABLE_HAS_BEEN_UPDATED_FOR_DETAILS_LOGIN_TO_syenergy','Time Table Has Been Updated For Details Login To syenergy');

//Change Password Module
define('ENTER_CURRENT_PASSWORD',            'Enter Current Password');
define('ENTER_NEW_PASSWORD',            'Enter New Password');
define('ENTER_NEW_PASSWORD_AGAIN',            'Confirm New Password');
define('PASSWORD_LENGTH_CHARACTERS',    'Password Should not be less than 6 characters');
define('PASSWORD_CHARACTERS_VALUE_CHECK',    'New Password And Confirm Password should be same');
define('PASSWORD_CHANGED',                'Password Changed Successfully');
define('OLD_PASSWORD_CHECK',                'Please check your Current Password');



//--------------------------------------------------------------------------------------------------------------
// Purpose: to centralize all messages of the application and can be altered on need basis
// Author : Jaineesh
// Created on : 20.08.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------

// Designation Module
define('DESIGNATION_NOT_EXIST','This Designation does not exist');
define('ENTER_DESIGNATION_NAME','Enter name of designation');
define('ENTER_DESIGNATION_CODE','Enter code of designation');
define('DESIGNATION_NAME_LENGTH','Designation Name can not be less than 3 characters');
define('DESIGNATION_ALREADY_EXIST','The Designation code you entered already exists.');
define('DESIGNATION_NAME_EXIST','The Designation name you entered already exists.');
define('INVALID_DESIGNATION','Invalid Designation');

define('ENTER_SPECIAL_ALPHABETS',"Enter following (a-z,&-./+) characters only");

define("STUDENT_TO_FINE","Please select atleast 1 Student");

// Histogram Label Module
define('HISTOGRAMLABEL_NOT_EXIST','This Histogram label does not exist');
define('ENTER_HISTOGRAMLABEL_NAME','Enter Histogram label');
//define('DESIGNATION_NAME_LENGTH','Designation Name can not be less than 3 characters');
define('HISTOGRAMLABEL_ALREADY_EXIST','The Histogram label you entered already exists.');
define('HISTOGRAMLABEL_NAME_EXIST','The Histogram label name you entered already exists.');
define('INVALID_HISTOGRAMLABEL','Invalid Histrogram label');

// Histogram Scale Module
define('HISTOGRAMSCALE_NOT_EXIST','This Histogram scale does not exist');
define('ENTER_HISTOGRAM_RANGEFROM','Enter Histogram Range From');
define('ENTER_HISTOGRAM_RANGETO','Enter Histogram Range To');
define('ENTER_HISTOGRAM_LABEL','Choose Histogram label');
//define('DESIGNATION_NAME_LENGTH','Designation Name can not be less than 3 characters');
define('HISTOGRAMRANGEFROM_ALREADY_EXIST','Histogram Range already exist.');
define('HISTOGRAMRANGETO_ALREADY_EXIST','The Histogram Range you have entered already exists.');
define('INVALID_HISTOGRAMSCALE','Invalid Histrogram scale');
define('INVALID_RANGE','Range To cannot be less than From Range');
define('INVALID_RANGELIMIT','Range From can not more than 100 and less than 0');
define('ENTER_NUMBER','Enter numbers only');
define('INVALID_RANGETO_ZERO','Range To cannot be zero');

// Employee Module
define('EMPLOYEE_NOT_EXIST','This Employee does not exist');
define('ENTER_USER_NAME','Enter User Name');
define('ENTER_USER_PASSWORD','Enter Password');
define('ENTER_USER_ROLE','Select Role name');
define('ENTER_EMPLOYEE_NAME','Enter First Name');
define('ENTER_EMPLOYEE_CODE','Enter code of employee');
define('ENTER_EMPLOYEE_ABBR','Enter abbr. of employee');
define('CHOOSE_EMPLOYEE_DESIGNATION','Select Designation');
define('CHOOSE_EMPLOYEE_BRANCH','Select Branch');
define('ENTER_EMPLOYEE_QUALIFICATION','Enter qualification of employee');
define('ENTER_SPOUSE_NAME','Enter spouse name');
define('ENTER_FATHER_NAME','Enter father name');
define('ENTER_MOTHER_NAME','Enter mother name');
define('ENTER_CONTACT_NUMBER','Enter contact no.');
define('ENTER_MOBILE_NUMBER','Enter mobile no.');
define('ENTER_EMAIL','Enter Email');
define('ENTER_EMPLOYEE_ADDRESS1','Enter Address1');
define('ENTER_EMPLOYEE_ADDRESS2','Enter Address2');
define('ENTER_PIN','Enter Pin');
define('COMPARISON_YEAR','Date of Marriage can not be less than Date of Birth');
//define('COMPARISON_MONTH','Marriage Month can not be less than Birth Month');
//define('COMPARISON_DATE','Marriage Date can not be less than Birth Date');
define('COMPARISON_JOINING_YEAR','Date of Joining can not be less than Date of Birth');
//define('COMPARISON_JOINING_MONTH','Joinining Month can not be less than Birth Month');
//define('COMPARISON_JOINING_DATE','Joinining Date can not be less than Birth Date');
define('DATE_COMPARISON2','Leaving Year can not be less than joining Year');
define('CHOOSE_DATE','Please choose Marriage Date');
define('CHOOSE_EMAIL','Please enter a valid email');
define('VALID_PHONE','Please enter a valid phone number');
define('ENTER_STRING_NUMERIC','Enter only alphabetic and numeric values');
define('ENTER_NUMBER','Enter only number');
define('DUPLICATE_USER','User Name already exists');
define('DATA_UNSAVED','Data couldnt be saved successfully');
define('EMPLOYEE_NAME_LENGTH','Employee Name can not be less than 3 characters');
define('EMPLOYEE_ALREADY_EXIST','Employee code already exists.');
define('EMPLOYEE_NAME_EXIST','Employee name already exists.');
define('INVALID_EMPLOYEE','Invalid Employee');
define('SELECT_TEACHINGINSTITUTE','Select at least one teaching institute');
define('COMPARISON_LEAVING_YEAR','Date of Leaving can not be less than Date of Birth');
define('COMPARISON_LEAVING_JOINING_YEAR','Date of Leaving can not be less than Date of Joining');
define('ADMIN_CANNOT_CREATE','Administrator cannot be created');
define('UPLOAD_IMAGE','Upload image only');
define('BIRTH_NOT_CURDATE','Date of Birth can not greater than Current Date');
define('MARRIAGE_NOT_CURDATE','Date of Marriage can not greater than Current Date');
define('JOINING_NOT_CURDATE','Date of Joining can not greater than Current Date');
define('LEAVING_NOT_CURDATE','Date of Leaving can not greater than Current Date');
define('EMPLOYEE_ABBR_ALREADY_EXIST','Employee Abbreviation already exist');
define('ENTER_USER_NAME_SELECT_PASSWORD','Enter User Name if you enter password');
define('ENTER_USER_NAME_SELECT_ROLE','Enter User Name if you select role');
define('SELECT_ONE_INSTITUTE','Select at least One Institute');
define('SELECT_DEFAULT_INSTITUTE','Atleast one institute must be made default');
define('DEFAULT_INSTITUTE_SELECTED','Institute name is not selected corresponding to default institute');
define('FROM_NOT_GREATER_CURDATE','From can not greater than current date');
define('TO_NOT_GREATER_CURDATE','To can not greater than current date');
define('FROM_TO_NOT_GREATER_CURDATE','From can not greater than To Date');
define('ENTER_ORGANISATION','Enter Organisation');
define('ENTER_DESIGNATION','Enter Designation');
define('DUPLICATE_FROM_DATE_RESTRICTION','Dates cannot be duplicated');
define('DUPLICATE_TO_DATE_RESTRICTION','Dates cannot be duplicated');
define('ENTER_ALPHABETS_SPECIAL_CHARACTERS','Enter only characters & special characters');
define('EMPLOYEE_PAN_NO_ALREADY_EXIST','Employee Pan No. already exists');
define('MISMATCH_TITLE_GENDER','You have selected mismatch between title and gender');


// ROOM Module
define('ROOM_NOT_EXIST','This Room does not exist');
define('ENTER_ROOM_NAME','Enter room name');
define('ENTER_ROOM_ABBR','Enter room abbr.');
define('ENTER_ROOM_TYPE','Enter room type');
define('CHOOSE_BLOCK_NAME','Enter block name');
define('CHOOSE_BUILDING','Enter building');
define('ENTER_ROOM_CAPACITY','Enter room capacity');
define('ENTER_EXAM_CAPACITY','Enter exam room capacity');
define('ROOM_ALREADY_EXIST','Room abbr. already exists.');
define('ROOM_NAME_EXIST','Room name already exists.');
define('INVALID_ROOM','Invalid Room');

// GROUP Module
define('GROUP_NOT_EXIST','This Group does not exist');
define('ENTER_GROUP_NAME','Enter name of group');
define('ENTER_GROUP_SHORT','Enter short name for group');
define('CHOOSE_DEGREE_NAME','Select class');
define('CHOOSE_BATCH_NAME','Choose batch name');
define('CHOOSE_PERIOD_NAME','Choose study period');
define('CHOOSE_GROUP_TYPE','Select Group Type Name');
define('GROUP_ALREADY_EXIST','Short Name already exists');
define('GROUP_NAME_EXIST','Group Name already exists');
define('CLASS_NOT_EXIST','Class is not generated, try again');
define('PARENT_NOT_EXIST','Parent Group cannot be parent of itself');
define('PARENTCLASS_NOT_EXIST','Class can not be different from parent class');
define('INVALID_GROUP','Invalid Group');
define('SUBJECT_TYPE_','Subject Type: ');
define('_DOES_NOT_MATCH_WITH_GROUP_TYPE_',' does not match with Group Type: ');
define('OPTIONAL_SUBJECT_NOT_FOUND','Optional subject not found');
define('CANNOT_REMOVE_ALLOCATION_FOR','Cannot remove allocation for ');
define('DUPLICATE_GROUPS_SELECTED_FOR','Duplicate groups selected for Student: ');
define('_FOR_SUBJECT_',' For Subject ');
define('NO_RECORD_FOUND','No record found');
// GROUP TYPE Module
define('GROUPTYPE_NOT_EXIST','This Group does not exist');
define('ENTER_GROUPTYPE_NAME','Enter group type name');
define('ENTER_GROUPTYPE_CODE','Enter group type code');
define('GROUPTYPE_NAME_LENGTH','Group type Name can not be less than 2 characters');
define('CHOOSE_DEGREE_NAME','Choose degree name');
define('CHOOSE_BATCH_NAME','Choose batch name');
define('CHOOSE_PERIOD_NAME','Choose study period');
define('CHOOSE_GROUP_TYPE','Choose group type');
define('GROUPTYPECODE_ALREADY_EXIST','The Group type code you entered already exists.');
define('GROUPTYPE_NAME_EXIST','The Group type name you entered already exists.');
define('INVALID_GROUPTYPE','Invalid Group type');


// HOSTEL Module
define('HOSTEL_NOT_EXIST','This Hostel does not exist');
define('ENTER_HOSTEL_NAME','Enter hostel name');
define('ENTER_HOSTEL_CODE','Enter hostel abbreviation');
define('ENTER_TOTAL_ROOM','Enter total room');
define('HOSTEL_ALREADY_EXIST','Hostel Abbreviation already exists');
define('HOSTEL_NAME_EXIST','Hostel Name already exists');
define('INVALID_HOSTEL','Invalid Hostel');
define('ENTER_HOSTEL_TYPE','Select hostel type');
define('ENTER_TOTAL_FLOOR','Enter total floor ');
define('ENTER_TOTAL_CAPACITY','Enter total capacity ');
define('FLOOR_NOT_GREATER','Value cannot greater than 100');
define('SELECT_REPORT_TYPE','Select report type');
define('RENT_LESS_THAN','Room rent Should be less than 30,000');
//Room Type Module
define('ENTER_ROOM_TYPE1','Enter Room Type');
define('ROOM_TYPE_EXIST','Entered Room Type Already Exist');
define('ROOM_TYPE_NOT_EXIST','Entered Room Type does not exist');

//HOSTEL VISITOR module
define('ENTER_VISITOR_NAME','Enter name of visitor');
define('ENTER_TO_VISIT','Enter name of person to visit');
define('ENTER_ADDRESS','Enter address of visitor');
define('ENTER_PURPOSE','Enter purpose of visit');
define('ENTER_CONTACT','Enter contact number of visitor');
define('SELECT_RELATION','Select relation of visitor');
define('ENTER_ALPHABETS_NUMERIC1',"Enter following (a-z,0-9,#,/,.) characters only");
define('ENTER_SCHEDULE_TIME_NUM1','Enter Time in HH:MM format');
define('VISITOR_NAME_LENGTH','Visitor Name can not be less than 3 characters');
define('HOURS_LIMIT','Time cannot be greater than 24 or less than 0');

//HOSTEL DISCIPLINE module
define('ENTER_DISCIPLINECATEGORY_NAME','Enter category name');
define('DISCIPLINE_CATEGORY_EXIST','Entered category already exist');
define('DISCIPLINE_NOT_EXIST','This discipline category does not exist');

//HOSTEL COMPLAINT module
define('ENTER_COMPLAINTCATEGORY_NAME','Enter category name');
define('COMPLAINT_CATEGORY_EXIST','Entered category already exists');
define('COMPLAINT_NOT_EXIST','This complaint category does not exist');
define('NAME_ALREADY_EXIST','Entered name already exists');

//TEMPORARY EMPLOYEE module
define('ENTER_TEMP_EMPLOYEE_NAME','Enter Employee Name');
define('ENTER_EMPLOYEE_ADDRESS','Enter Address');
define('SELECT_STATUS','Select Status');
define('SELECT_DESIGNATION','Select Designation');
define('VALID_CONTACT_NO','Enter Valid Contact No.');

// HOSTEL ROOM Module
define('HOSTELROOM_NOT_EXIST','This Hostel does not exist');
define('CHOOSE_HOSTEL_NAME','Select Hostel Name');
define('ENTER_HOSTELROOM_NAME','Enter Name');
define('ENTER_HOSTELROOM_CAPACITY','Enter Room Capacity');
define('ENTER_HOSTELROOM_RENT','Enter Room Rent');
define('HOSTELROOM_NAME_EXIST','Hostel Name Already Exists.');
define('INVALID_HOSTELROOM','Invalid Hostel Room');
define('HOSTEL_ROOM_TYPE_NO_VALUE','No room capacity & rent has been found against this room type');
define('ROOM_NOT_GREATER','Room(s) cannot be greater than total hostel room');
define('CAPACITY_NOT_GREATER','Capacity cannot be greater than total hostel room capacity');
define('ENTER_HOSTEL_ROOM_TYPE_NAME','Select Room Type');
define('ENTER_ALPHABETS_HOSTEL_ROOM_NUMERIC','Enter only alphabetical/numeric characters (a-z,0-9)');


//SUPPLIER module
define('ENTER_COMPANY_NAME','Enter Company Name');
define('ENTER_ADDRESS1','Enter Address');
define('ENTER_CONTACT_PERSON_NAME','Enter Contact Person Name');
define('ENTER_CONTACT_PERSON_PHONE','Enter Contact Person Phone');
define('ENTER_COMPANY_PHONE','Enter Company Phone Number');
define('COMPANY_EXIST','Company Name Already Exist');
define('SUPPLIER_NOT_EXIST','Company Does Not Exist ');
define('ENTER_SUPPLIER_CODE','Enter Supplier Code ');
define('SUPPLIER_CODE_EXIST','Supplier Code Already Exists ');

//Item Category Module
define('ENTER_CATEGORY','Enter Item ');
define('ENTER_ITEM_CODE','Enter Item Code');
define('ENTER_ABBR','Enter Abbreviation');
define('ITEM_CATEGORY_EXIST','Item Category Already Exists');
define('ABBR_EXIST','Entered Abbreviation Already Exists');
define('ENTER_ALPHABETS_NUMERIC2',"Enter following (a-z,A-Z,0-9,/,-) characters only");

//Resource Category Module
define('ENTER_RESOURCECATEGORY_NAME','Enter category name');
define('RESOURCE_CATEGORY_EXIST','Category Name already exists');
define('RESOURCE_NOT_EXIST','This resource category does not exist');

// PERIOD Module
define('PERIOD_NOT_EXIST','This Period does not exist');
define('ENTER_PERIOD_NUMBER','Enter period number');
define('ENTER_START_TIME','Enter start time');
define('ENTER_END_TIME','Enter end time');
define('ACCEPT_INTEGER','Enter Time in HH:MM format');
define('PERIOD_NAME_LENGTH','Period Number can not be less than 2 characters');
define('SPECIAL_CHARACTERS','Special characters are not allowed');
define('PERIOD_NUMBER_EXIST','Period Number already exists');
define('INVALID_PERIOD','Invalid Period');
define('Start_TIME_NOT_GREATER','Start Time can not be greater than or equal to end time');
define('SELECT_AM_PM','Select AM/PM');
define('TIME_ALREADY_ALLOTED','Period has already been alloted during this time');
define("ENTER_INTEGER_PERIOD","Period Number should be integer");
define("START_TIME_AND_END_TIME",'Start Time and End Time should not be the same');

// DEPARTMENT Module
define('ENTER_DEPARTMENT_NAME','Enter Department Name');
define('ENTER_ABBREVIATION','Enter Abbreviation');
define('DEPARTMENT_NAME_EXIST','Department already exists.');
define('DEPARTMENT_NOT_EXIST','The Department does not exist');
define('INVALID_DEPARTMENT','Invalid Department');
define('DEPARTMENT_NAME_LENGTH','Department Name can not be less than 2 characters');
define('DEPARTMENT_ABBR_EXIST','Department abbr. already exists. ');

// Offense Module
define('ENTER_OFFENSE_NAME','Enter Offense Name');
define('ENTER_OFFENSE_ABBR','Enter Offense Abbreviation');
define('OFFENSE_NAME_LENGTH','Offense Name can not be less than 2 characters');
define('OFFENSE_ALREADY_EXIST','Offense Abbr. already exists');
define('OFFENSENAME_ALREADY_EXIST','Offense Name already exists');
define('OFFENSE_NOT_EXIST','This Offense does not exist');
define('OFFENSE_CONSTRAINT','Data could not be edited due to records existing in linked tables');
define('ENTER_OFFENSE_ALPHABETS_NUMERIC','Enter only alphabetical/numeric characters (a-z,0-9,(),-)');

// Lecture Percent Module
define('SLAB_DELETE_SUCCESSFULLY','All slabs for this attendance set deleted successfully');
//Bulk Attendance Module
define('SELECT_ATLEAST_ONE_CHECK_BOX','Please Select Atleast One Check Box');
define('HELP_FILE_NOT_FOUND','Help file not found');

// Hostel Room Type Module
define('ENTER_ROOM_TYPE','Enter Room Type');
define('ENTER_ROOM_ABBR','Enter Room Abbreviation');
define('HOSTELROOM_TYPE_ABBR_EXIST','Hostel Room Abbr. already exists');
define('HOSTELROOM_TYPE_EXIST','Hostel Room Type already exists');
define('HOSTEL_ROOM_TYPE_NOT_EXIST','Hostel Room Type does not exist');
define('HOSTEL_ROOM_TYPE','Room Type can not be less than 2 characters');

// Hostel Room Type Module
define('ENTER_HOSTEL','Enter Hostel Name');
define('ENTER_CAPACITY','Enter Capacity');
define('ENTER_BEDS','Enter no. of Beds');
define('ENTER_FEE','Enter Fee');
define('CHOOSE_ROOM_TYPE','Select Room Type');
define('HOSTELROOM_ABBR_EXIST','Room Abbr. already exists');
//define('HOSTELROOM_NAME_EXIST','Room Type already exists');
define('HOSTEL_ROOM_TYPE_DETAIL_NOT_EXIST','Hostel Room Type Detail does not exist');
define('VALUE_NOT_GREATER','Value can not be greater than 6 digits');
define('VALUE_NOT_LESS','Value can not be less than zero');
define('SELECT_HOSTEL','Select hostel name');


// Report Complaints Module
define('ENTER_SUBJECT','Enter Subject');
define('ENTER_COMPLAINT_CATEGORY','Select Category');
define('CHOOSE_HOSTEL','Select Hostel');
define('ENTER_FEE','Enter Fee');
define('CHOOSE_ROOM','Select Room');
define('CHOOSE_STUDENT','Select Reported By');
define('ENTER_TRACKING_NUMBER','Enter Tracking Number');
define('TRACKING_NOT_EXIST','Tracking Number does not exist');
define('HOSTEL_COMPLAINT_NOT_EXIST','This complaint does not exist');
define('RECORD_ALREADY_EXIST','Record already exists');
define('INVALID_COMPLAINT_DETAIL','Invalid complaint');
define('NO_COMPLAINT_FOUND','No complaint found before this complaint');
define('NO_TRACKING_NUMBER_FOUND','Tracking Number already exists');

// Handle Complaints Module
define('SELECT_DATE','Dates cannot be empty');
define('SELECT_TODATE','Dates cannot be empty');
define('WRONG_DATE','Completion date cannot be less than Complaint Date');
define('DATE_NOT_GREATER','Date cannot be greater than Current Date');

// Cleaning Room Module
define('SAFAIWALA_ALREADY_EXIST','This sweeper already exists for this hostel');
define('SELECT_TODATE','Complaint dates cannot be empty');
define('WRONG_DATE','Completion date cannot be less than Complaint Date');
define('ENTER_TOILET_NO','Enter Toilet No.');
define('CHOOSE_SAFAIWALA','Select Safaiwala');
define('ENTER_ROOM','Enter Room No.');
define('ENTER_NO_HRS','Enter No. of Hours');
define('DEPENDENCY_EDIT_CONSTRAINT','Data can be edited only on Pending status');
define('ENTER_TIME','Enter time of visit');
define('VALUE_CANNOT_GREATER','Value can not be greater than 4 digits');
define('VALUE_NOT_MORE','Value can not be greater than 100');

//Update student class rollno
define('ROLLNO_NOT_EXIST','Roll No does not exist');
define('SELECT_ROLL_NO','Select Roll No');
define('SELECT_NEW_CLASS','Select New Class');
define('SELECT_NEW_ROLLNO','New Roll No. cannot be blank if checkbox is checked');
define('SELECT_SAME_SUBJECT','Current class and new class does not have same subject');
define('SELECT_SAME_CLASS','You have selected same class');
define('ROLL_NO_EXIST','Roll No. already exist');
define('NOT_UPDATE_CLASS','Cannot update the class');
define('SELECT_ONE_OPTION','Either select new class or enter new roll no');
define('SELECT_REASON','Enter reason');
define('UNIVERSITY_ROLLNO_NOT_EXIST','University Roll No. does not exist');
define('REGNO_NOT_EXIST','Registration No. does not exist');

// Frozen Time Table Module
define('FROZEN_SUCCESS','Selected class(es) frozen successfully');
define('UNFROZEN_SUCCESS','Selected class(es) unfrozen successfully');
define('ENTER_REASON','Enter Reason');
define('ERROR_MARKS_NOT_CALCULATED','Class can not be frozen as External Marks have not entered');

// Role to Class Module
define("SELECT_ATLEAST_CLASS","Please select atleast 1 Class");
define("ASSIGN_ROLE_SUCCESS","Privileges assigned successfully");
define("SELECTED_GROUP_TYPE","Select Group Type for selected Class(s)");
define("SELECTED_GROUP","Select Group for selected Class(s)");
define("SELECT_USER","Select User");

//Move Time Table Module
define("SELECT_TEACHER_TO_MOVE","Select a teacher");
define('SELECT_TEACHER_BY_SUBSTITUTE',"Select a teacher by which to substitute");
define('SAME_EMPLOYEE_RESTRICTION','You cannot select same employees for substituion');
define('TIME_TABLE_FROM_DATE_VALIDATION','From date cannot be smaller than current date');
define('TIME_TABLE_TO_DATE_VALIDATION','To date cannot be smaller than current date');
define('TIME_TABLE_DATE_VALIDATION','From date cannot be greater than to date');
define('TIME_TABLE_CANCEL_DATE_VALIDATION','Both from and to dates cannot be smaller than current date');
define('TIME_TABLE_DATE_EQUAL','From and to Date can not be same');
define('DATE_NOT_EMPTY','Dates cannot be empty');
define('DATE_FROM_NOT_EMPTY','From Date cannot be empty');
define('DATE_TO_NOT_EMPTY','To Date cannot be empty');
define('SELECT_ADJUSTMENT_TYPE','Select adjustment type');
define('TIME_TABLE_EXISTS_FOR_THIS_LABEL','Time Table exist for this Time Table Label');


// Attendance Marks Slabs
define('SELECT_TIME_TABLE_LABEL','Select Time Table Label');
define('unfrozenSuccess','Selected class(es) unforzen successfully');
define('ENTER_REASON','Enter Reason');
define('ERROR_CGPA_NOT_CALCULATED','Class can not be frozen as CGPA is not calculated');

// Occupied/Free Class
define('SELECT_PERIODSLOT','Select Period Slot');
define('SELECT_PERIOD','Select Period');
define('SELECT_OPTION','Select Option');

// Hold/UnHold Student
define('STUDENT_HOLD_RESULT','Result hold successfully for entered student(s)');
define('STUDENT_UNHOLD_RESULT','Result unhold successfully for entered student(s)');
define('ROLL_NO_CANNOT_BLANK','Roll nos. cannot be blank');

define('INVALID_ROLLNO','Invalid Roll No./User Name');


//--------------------------------------------------------------------------------------------------------------
// Purpose: to centralize all messages of the application and can be altered on need basis
// Author : Dipanjan Bhattacharjee
// Created on : (21.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------

// UNIVERSITY Module
define('ENTER_UNIVERSITY_NAME','Enter university name');
define('ENTER_UNIVERSITY_CODE','Enter university code');
define('ENTER_UNIVERSITY_EMAIL','Enter university email');
define('ENTER_UNIVERSITY_WEBSITE','Enter university website');
define('ENTER_UNIVERSITY_ABBR','Enter university abbreviation');
define('ENTER_UNIVERSITY_ADDRESS1','Enter addess1');
define('ENTER_UNIVERSITY_ADDRESS2','Enter addess2');
define('ENTER_UNIVERSITY_CONTACT_NO','Enter a contact number');
define('ENTER_UNIVERSITY_CONTACT_PERSON','Enter a contact person');
define('UNIVERSITY_ALREADY_EXIST','The University Code you entered already exists.');
define('UNIVERSITY_ABBR_ALREADY_EXIST','The University Abbr. you entered already exists.');
define('UNIVERSITY_NOT_EXIST','This University does not exist');


// INSTITUTE Module
define('ENTER_INSTITUTE_NAME','Enter institute name');
define('ENTER_INSTITUTE_CODE','Enter institute code');
define('ENTER_INSTITUTE_EMAIL','Enter institute email');
define('ENTER_INSTITUTE_WEBSITE','Enter institute website');
define('ENTER_INSTITUTE_ABBR','Enter institute abbreviation');
define('ENTER_INSTITUTE_ADDRESS1','Enter addess1');
define('ENTER_INSTITUTE_ADDRESS2','Enter addess2');
define('ENTER_INSTITUTE_CONTACT_NO','Enter a contact number');
define('ENTER_INSTITUTE_EMPLOYEE','Select an employee');
define('ENTER_INSTITUTE_CONTACT_PERSON','Enter a contact person');
define('INSTITUTE_ALREADY_EXIST','The Institute Code you entered already exists.');
define('INSTITUTE_NAME_ALREADY_EXIST','The Institute Name you entered already exists.');
define('INSTITUTE_ABBR_ALREADY_EXIST','The Institute Abbreviation you entered already exists.');
define('INSTITUTE_NOT_EXIST','This Institute does not exist');


// TEST TYPE Module
define('TESTTYPE_NAME_LENGTH','Test type Name can not be less than 3 characters');
define('ENTER_TESTTYPE_NAME','Enter test type name');
define('ENTER_TESTTYPE_CODE','Enter test type code');
define('ENTER_TESTTYPE_ABBR','Enter test type abbreviation');
define('ENTER_TESTTYPE_WEIGHTAGE','Enter weightage amount');
define('ENTER_TESTTYPE_WEIGHTAGE_PERCENTAGE','Enter weightage percentage');
define('SELECT_TESTTYPE_EVALUATION','Select an evaluation criteria');
define('ENTER_TESTTYPE_COUNT','Enter count');
define('ENTER_TESTTYPE_SORT_ORDER','Enter Sort Order');
define('SELECT_SUBJECT_TYPE','Select a subject type');
define('SELECT_CONDUCTING_AUTHORITY','Select a conducting authority');
define('TESTTYPE_ALREADY_EXIST','The Test Type  Code you entered already exists.');
define('TESTTYPE_NOT_EXIST','This Test Type does not exist');
define('ENTER_TESTTYPE_COUNT_NUM','Please enter a numeric value for count');
define('ENTER_TESTTYPE_WEIGHTAGE_NUM','Please enter numeric value in weightage amount');
define('ENTER_TESTTYPE_WEIGHTAGE_PERCENTAGE_NUM','Please enter numeric value in weightage percentage');
define('ENTER_TESTTYPE_SORT_ORDER_NUM','Please enter a numeric value for sort order');
define('ENTER_TESTTYPE_CONDITION','Percentage and Slabs are applicable to Attendance only');
define('SELECT_SUBJECT','Select subject');
define('SELECT_SECTION','Select section');
define('SELECT_TEST_TYPE','Select test type');
define('SELECT_TEST_ABBR','Enter test abbr.');
define('SELECT_MAX_MARKS','Enter max marks');
define('SELECT_TEST_TOPIC','Enter test topic');
define('SELECT_TESTTYPE_CATEGORY','Select test type category');
define('TEST_TYPE_NOT_EXIST','Test Type not exist');
define('EXTERNAL_EXAMTYPE_ALREADY_EXIST','External Exam Type already exists');
define('ATTENDANCE_CATEGORY_ALREADY_EXIST','Attendance Category already exists');
define('TESTTYPE_CATEGORY_ALREADY_EXIST','The Test Type Category you entered already exists.');

define('WEIGHTAGE_AMOUNT_RESTRICTION','Weightage amount can not be greater than 100');
define('CNT_AMOUNT_RESTRICTION','Count can not be greater than 100');
define('SORT_ORDER_AMOUNT_RESTRICTION','Sort order can not be greater than 100');

// TEST TYPE CATEGORY Module
define('ENTER_TESTTYPECATEGORY_NAME','Enter Test Type Category Name');
define('ENTER_TESTTYPECATEGORY_ABBR','Enter Test Type Category Abbr.');
define('TEST_TYPE_CATEGORY_EXIST','The Test Type Category Name you entered already exists.');
define('TEST_TYPE_CATEGORY_ABBR_EXIST','The Test Type Category Abbr. you entered already exists.');
define('TEST_TYPE_NOT_EXIST','This Test Type Category does not exist');

// DEGREE Module
define('DEGREE_NOT_EXIST','This Degree does not exist');
define('ENTER_DEGREE_NAME','Enter name of degree');
define('ENTER_DEGREE_CODE','Enter degree code');
define('ENTER_DEGREE_ABBR','Enter degree abbreviation');
define('DEGREE_NAME_LENGTH','Degree Name can not be less than 2 characters');
define('DEGREE_ALREADY_EXIST','The Degree Code you entered already exists.');
define('DEGREE_NAME_ALREADY_EXIST','The Degree Name you entered already exists.');
define('DEGREE_ABBR_ALREADY_EXIST','The Degree Abbreviation you entered already exists.');


// STUDY PERIOD Module
define('STUDY_PERIOD_NOT_EXIST','This Study Period does not exist');
define('ENTER_PERIOD_VALUE','Enter period value');
define('SELECT_PERIODICITY','Select a periodicity');
define('ENTER_PERIOD_NAME','Enter period name');
define('PERIOD_NAME_LENGTH','Period Name can not be less than 3 characters');
define('STUDY_PERIOD_ALREADY_EXIST','The Study Period  you entered already exists.');


//Vehicle Extra Tyres

define('SELECT_TYRE_NO','Select Vehicle Tyre');
define('SELECT_REGISTRATION_NO','Select Registration No.');
define('SELECT_TYRE_NO','Select Tyre No.');
define('ENTER_VEHICLE_READING','Enter Reading');
define('REPLACEMENT_DATE_VALIDATION','Replacement Date cannot be greater than current date');
define('SELECT_STOCK_TYRE_NUMBER','Select Stock Tyre');


//Vehicle Type

define('ENTER_VEHICLE_TYPE','Enter Type of Vehicle');
define('ENTER_MAIN_TYRE','Enter main tyres');
define('ENTER_SPARE_TYRE','Enter spare tyres');
define('VEHICLE_TYPE_ALREADY_EXIST','Type of vehicle already exist');
define('VEHICLE_TYPE_NOT_EXIST','Vehile does not exist');
define('VEHICLE_TYPE_LENGTH','Vehicle length cannot be less than 3 characters');
define('VALUE_NOT_LESS_ONE','Value of tyres can not be less than one');

//Vehicle Tyre

define('ENTER_TYRE_NUMBER','Enter Tyre Number');
define('ENTER_MANUFACTURER','Enter Manufacturer');
define('ENTER_MODEL_NUMBER','Enter Model Number');
define('ENTER_BUS_NUMBER','Enter Registration No.');
define('ENTER_BUS_READING','Enter Reading');
define('VEHICLE_TYRE_ALREADY_EXIST','Tyre No. already exists');
define('VEHICLE_TYRE_NOT_EXIST','Tyre No. does not exist');
define('PURCHASE_DATE_VALIDATION','Purchase Date cannot be greater than current date');
define('DELETE_TYRE','Capacity of tyres for the selected bus are full, first delete the tyre');
define('RETREADING_DATE_NOT_GREATER','Retreading Date cannot be greater than current date');

//Insurance

define('ENTER_INSURING_COMPANY','Enter Company Name');
define('INSURANCE_COMPANY_ALREADY_EXIST','Company Name already exists');
define('INSURANCE_NAME_NOT_EXIST','Company does not exist');
define('ACCIDENT_DATE_NOT_GREATER','Accident Date cannot be greater than current date');
define('VIEWSTATUS_CANNOT_BE_UPDATED','Notification status cannot be updated');

//Vehicle Accident

define('SELECT_BUS','Select Registration No.');
define('SELECT_TRANSPORT_STAFF','Select transport staff');
define('SELECT_BUS_ROUTE','Select Route');
define('DISCARD_VEHICLE_NOT_EDIT','Discard vehicle cannot be edited');
define('DISCARD_VEHICLE_NOT_DELETE','Discard vehicle cannot be deleted');
define('SELECT_BUS_ROUTE_CODE','Select Bus Route Code');
define('ROUTE_CHARGES_NOT_ZERO','Route charges cannot be Zero');


//Tyre Retreading

define('ENTER_TYRE_NO','Enter Tyre No.');
define('ENTER_READING','Enter Reading');


//Vehicle Insurance
define('SELECT_BUS','Select Registration No.');
define('SELECT_INSURANCE_COMPANY','Select Insurance Company');
define('ENTER_VALUE_INSURED','Enter Value Insured');
define('ENTER_INSURANCE_PREMIUM','Enter Insurance Premium');
define('ENTER_POLICY_NUMBER','Enter Policy No.');
define('ENTER_INSURANCE_AGENT_NAME','Enter Agent Name');
define('SELECT_PAYMENT_MODE','Select Payment Mode');
define('SELECT_INSURANCE_DATE','Select Insurance Date');
define('INSURANCE_DATE_VALIDATION','Insurance Date cannot be greater than current date');
define('BUS_NO_NOT_EXIST','Registration No. not exist');
define('INSURANCE_ALREADY_DONE','Insurance of selected vehicle already done');
define('INSURANCE_CANNOT_DELETE_ACTIVE_BUS','Insurance of vehicle cannot be deleted');

//REPLACE TYRE
define('SELECT_INSURANCE_COMPANY','Select Insurance Company');
define('ENTER_REMOVAL_READING','Enter Removal Reading');
define('SELECT_TO_BUS','Select to Bus');
define('SELECT_BUS_READING','Enter Bus Reading');
define('REPLACEMENT_DATE_VALIDATION','Replacement date cannot be greater than current date');

//VEHICLE BATTERY
define('ENTER_BATTERY_NO','Enter Battery No.');
define('ENTER_BATTERY_MAKE','Enter Battery Make');
define('ENTER_WARRANTY_DATE','Enter Warranty Date');
define('ENTER_METER_READING','Enter Reading');
define('ENTER_REPLACEMENT_COST','Enter Cost');
define('ENTER_REPLACEMENT_DATE','Enter Replacement Date');
define('BATTERY_NOT_DELETE','Battery cannot be deleted');
define('BATTERY_NOT_EDIT','Battery cannot edit');
define('VEHICLE_BATTERY_ALREADY_EXIST','Battery already exist');
define('BATTERY_COST_NOT_LESS','Cost should not be less than zero');

//VEHICLE SERVICE
define('SELECT_BUS_SERVICE','Select Service Type');
define('SELECT_SERVICE_NO','Select Service No.');
define('ENTER_DONE_ON','Select Date');
define('ENTER_KM_RUN','Enter KM Run');
define('DONE_ON_DATE_VALIDATION','Done on cannot be greater than current date');
define('VEHICLE_SERVICE_NOT_EDIT','Service canot be edited');
define('FREE_SERVICE_FINISHED','Free service has been finished of selected vehicle');
define('KM_RUN_NOT_LESS','KM Run cannot less than previous Free service for the selected vehicle');
define('SERVICE_ALREADY_EXIST_PARTICULAR_DATE','Service for the selected vehicle already exists on particular date');


//VEHICLE SERVICE REPAIR
define('SELECT_SERVICE_TYPE','Select Service Type');
define('ENTER_READING_ENTRY','Enter KM Reading on Entry');
define('ENTER_BILL_NO','Enter Bill/Ticket No.');
define('ENTER_SERVICED_AT','Enter Serviced At');
define('INVALID_AMOUNT','Invalid Amount');
define('INVALID_KM_RUN','Invalid KM Run');
define('INVALID_NEXT_CHANGE_KM','Invalid Next Change in KM');
define('INVALID_ITEMS','Invalid Items');
define('INVALID_CHARGES','Invalid Charges');
define('INVALID_READING','Invalid KM Reading on Entry');
define('INVALID_SERVICED_AT','Invalid Serviced At');
define('SERVICE_DATE_VALIDATION','Service Date cannot be greater than Current Date');



//VEHICLE INSURANCE CLAIM
define('SELECT_CLAIM_DATE','Select Claim Date');
define('SELECT_SETTLEMENT_DATE','Select Settlement Date');
define('INVALID_CLAIM_AMOUNT','Invalid Claim Amount');
define('INVALID_TOTAL_EXPENSES','Invalid Total Expenses');
define('INVALID_SELF_EXPENSES','Invalid Self Expenses');
define('INVALID_NO_CLAIM_BONUS','Invalid No Claim Bonus');
define('INVALID_LOGGING_CLAIM','Invalid Logging Claim');
define('TOTAL_EXPENSES_NOT_LESS','Total expenses should not less than claim amount');
define('INSURANCE_CLAIM_ALREADY_TAKEN','Claim date for the selected vehicle has already entered');
define('INSURANCE_CLAIM_NOT_EDIT','Insurance Claim cannot edit');
define('ENTER_CLAIM_AMOUNT','Enter Claim Amount');
define('ENTER_TOTAL_EXPENSES','Enter Total Expenses');
define('ENTER_SELF_EXPENSES','Enter Self Expenses');
define('ENTER_LOGGING_CLAIM','Enter Logging Claim');
define('CLAIM_DATE_VALIDATION','Claim Date cannot be greater than current date');
define('SETTLEMENT_DATE_VALIDATION','Settlement Date cannot be greater than current date');

// BUS Module
define('ENTER_BUS_NAME','Enter bus name');
define('ENTER_BUS_NO','Enter registration no');
define('SELECT_MAN_YEAR','Select manufacturing yead');
define('BUS_NAME_LENGTH','Bus name can not be less than 3 characters');
define('BUS_NO_LENGTH','Registration No. can not be less than 2 characters');
define('BUS_ALREADY_EXIST','Bus already exists');
define('BUS_NO_ALREADY_EXIST','Registration no. already exists');
define('BUS_NOT_EXIST','Bus does not exist');
   /*****Messages for Vehicle Master******/
define('ENTER_USER_CLAIM_ID','Please enter User Claim ID');
define('USER_CLAIM_ID_EXISTS','User Claim ID already exists');
define('INVALID_DETAILS_FOUND','Invalid details found');
define('SELECT_VEHICLE_TYPE','Select vehicle type');
define('SELECT_VEHICLE_CATEGORY','Select vehicle category');  
define('INVALID_VEHICLE_TYPE','Vehicle type does not exists');
define('VEHICLE_DOES_NOT_EXISTS','Vehicle does not exists');
define('ENTER_VEHICLE_NAME','Enter vehicle name');
define('ENTER_VEHICLE_NO','Enter registration no.');
define('VEHICLE_REGISTRATION_NO_ALREADY_EXISTS','Registration no. already exists');
define('ENTER_VEHICLE_MODEL','Enter Model No.');
define('INVALID_PURCHASE_DATE','Invalid purchase date');
define('ENTER_SEATING_CAPACITY','Enter seating capacity');
define('ENTER_FUEL_CAPACITY','Enter fuel capacity');
define('ENTER_ENGINE_NO','Enter Engine No.');
define('ENTER_CHASSIS_NO','Enter Chassis No.');
define('ENTER_INSURANCE_DUE_DATE','Enter Insurance Due Date');
define('ENTER_POLICY_NO','Enter Policy No.');
define('ENTER_INSURANCE_BRANCH_NAME','Enter Branch Name');
define('ENTER_AGENT_NAME','Enter Agent Name');
define('ENTER_FREE_SERVICE_NO','Enter No. of Free Services');
define('ENTER_TYRE_MODEL_NO',"Enter Tyres' Model No.");
define('ENTER_TYRE_MANUFACTURING_COMPANY',"Enter Tyres' Manufacturing Company");
define('ENTER_TYRE_NO','Enter Tyre No.');
define('INVALID_SEATING_CAPACITY','Invalid seating capacity');
define('INVALID_FUEL_CAPACITY','Invalid fuel capacity');
define('INVALID_MANUFACTURING_YEAR','Invalid manufacturing year');
define('ENGINE_NO_ALREADY_EXISTS','Engine no. already exists');
define('CHASIS_NO_ALREADY_EXISTS','Chasis no. already exists');
define('POLICY_NO_ALREADY_EXISTS','Policy no. already exists');
define('INVALID_CHASIS_COST','Invalid chassis cost');
define('INVALID_BODY_COST','Invalid body cost');
define('CHASIS_COST_MUST_BE_BETWEEN_','Chassis cost must be between ');
define('INVALID_CHASIS_PURCHASE_DATE','Invalid chasis purchase date');
define('BODY_COST_MUST_BE_BETWEEN_','Body cost must be between ');
define('INVALID_PUT_ON_ROAD_DATE','Invalid put on road date');
define('INVALID_INSURANCE_DATE','Invalid insurance date');
define('INSURANCE_COMPANY_DOES_NOT_EXISTS','Insurance does not exists');
define('INVALID_VALUE_INSURED','Invalid values insured');
define('VALUE_INSURED_MUST_BE_BETWEEN_','Insured value must be between ');
define('INVALID_INSURANCE_PREMIUM','Invalid insurance premium');
define('INSURANCE_PREMIUM_MUST_BE_BETWEEN_','Insurance premium must be between ');
define('INVALID_NCB','Invalid NCB');
define('NCB_MUST_BE_BETWEEN_','NCB must be between ');
define('INVALID_PAYMENT_MODE','Invalid payment mode');
define('ERROR_WHILE_SAVING_INSURANCE','Insurance data could not be saved');
define('TYRE_NO_','Tyre no. ( ');
define('_ALREADY_EXISTS',' ) already exists');
define('ERROR_WHILE_SAVING_TYRE','Tyre data could not be saved');
define('ERROR_WHILE_SAVING_TYRE_HISTORY','Tyre history data could not be saved');
define('ERROR_WHILE_UPDATING_INSURANCE_DETAILS','Insurance data could not be updated');
define('ERROR_WHILE_SAVING_BATTERY','Battery Data could not be saved');
define('ERROR_WHILE_SAVING_SERVICE','Service Data could not be saved');
define('ENTER_SERVICE_KM','Enter KM');
define('INVALID_SERVICE_KM','Invalid KM');
define('INVALID_DATE','Date :');
define('_NOT_SMALLER',' Date should not be smaller or equal from selected date');
define('INVALID_KM','KM Run:');
define('_NOT_SMALLER_KM',' should not smaller from giving KM');
define('SERVICE_KM_','Service KM : ');
define('_SERVICE_ALREADY_EXISTS',' already exists');
define('DISCARD_CONFIRM','Do you really want to discard this bus');
define('INVALID_TYRE_NO','Invalid tyre no.');
define('INVALID_SERVICE_KM','Invalid service KM');
define('PURCHASE_DATE_NOT_GREATER','Purchase Date cannot be greater than current date');
define('CHASSIS_PURCHASE_DATE_NOT_GREATER','Chassis Purchase Date cannot be greater than current date');
define('BATTERY_NO_ALREADY_EXISTS', 'Battery No. already exists');
define('VEHICLE_INSURANCE_DATE_NOT_GREATER','Insurance Date cannot be greater than Insurance Due Date');
define('INVALID_FREE_SERVICE_NO','Invalid No. of free services');
define('INVALID_EXTENSION','Invalid file extenstion. Try only ');
define('EXTENSION',' extensions');
define('FILE_NOT_UPLOAD','Maximum upload size is ');
define('KB',' kb');

define('ENTER_BUS_MODEL_NO','Enter model no. of the bus');
define('ENTER_BUS_CAPACITY','Enter seating capacity of the bus');
define('BUS_MODEL_LENGTH','Model no. can not be less than 3 characters');
define('BUS_CAPACITY_RESTRICTION','Seating capacity of the bus can not be zero');
define('SELECT_INSURANCE_DATE','Select insurance date');
define('INSURANCE_DATE_VALIDATION','Insurance due date can not be less than current date');
define('INSURANCE_REMINDER_RESTRICTION','Please uncheck insurance reminder as no insuring company is entered');
define('BUS_PURCHASE_DATE_EMPTY','Select purchase date');
define('BUS_PURCHASE_DATE_VALIDATION','Purchase date can not be greater than current date');
define('SELECT_INSURANCE_DATE','Select insurance date');

// BUS Repair Module
define('SELECT_BUS_NAME','Select Registration No.');
define('SELECT_STAFF','Select staff name');
define('ENTER_SERVICE_REASON','Enter Reason for service');
define('ENTER_SERVICE_COST','Enter service cost');
define('SERVICE_REASON_LENGTH','Reason for service cannot be less than 3 characters');
define('ENTER_COST_NUM','Enter valid value for service cost');
define('SERVICE_DATE_VALIDATION','Service date cannot be greater than current date');
define('BUS_REPAIR_ALREADY_EXIST','Vehicle repair record already exists');
define('BUS_REPAIR_NOT_EXIST','Vehicle repair record does not exists');

define('ENTER_WORKSHOP_NAME','Enter workshop name');
define('ENTER_BILL_NUMBER','Enter bill number');
define('WORKSHOP_NAME_LENGTH','Workshop name cannot be less than 3 characters');
define('BILL_NUMBER_LENGTH','Bill number cannot be less than 3 characters');
define('BUS_REPAIR_BILL_ALREADY_EXIST','This bill number already exists');
define('SELECT_DUE_DATE','Select due date');
define('DUE_DATE_VALIDATION','Due date cannot be smaller than current date');

// BUS STOP Module
define('BUSSTOP_NOT_EXIST','This Bus Stop does not exist');
define('ENTER_STOP_NAME','Enter stop name');
define('ENTER_STOP_ABBR','Enter stop abbreviation');
define('ENTER_SCHEDULE_TIME','Enter Schedule Time');
define('ENTER_SCHEDULE_TIME_END','Enter Drop Time');
define('ENTER_SCHEDULE_TIME_NUM','Enter Schedule Time in HH:MM:SS format');
define('ENTER_SCHEDULE_TIME_END_NUM','Enter Drop Time in HH:MM:SS format');
define('ENTER_TRANSPORT_CHARGES','Enter  transport charges');
define('INVALID_SCHEDULE_TIME','Invalid Schedule Time');
define('ENTER_ONE_TRANSPORT_CHARGES','Enter one way transport charge');
define('ENTER_TWO_TRANSPORT_CHARGES','Enter two way transport charge');
define('STOP_NAME_LENGTH','Stop Name can not be less than 3 characters');
define('STOP_ALREADY_EXIST','Vehicle Stop Abbr. already exists');
define('STOP_NAME_EXIST','Vehicle Stop Name already exists');
define('ENTER_ONE_WAY_TO_NUM','Enter numeric value for one way transport charge');
define('ENTER_TWO_WAY_TO_NUM','Enter numeric value for two way transport charge');
define('CITY_NOT_EXIST','City not exists in Bus City Wise Charges');
define('TIME_ALREADY_EXIST','Schedule time already exists');
define("ENTER_ROUTE","Please select route");
define("STOP_TO_ROUTE_ONE","Please select atleast 1 Stop");
// BUS City Wise Charges Module
define('SELECT_STATE','Select state');
define('SELECT_CITY','Select city');
define('BUS_CITY_ALREADY_EXIST','This city already exists');



// BUS ROUTE Module
define('BUSROUTE_NOT_EXIST','This Bus Route does not exist');
define('ENTER_ROUTE_NAME','Enter route name');
define('ENTER_ROUTE_CODE','Enter route code');
define('SELECT_ROUTE_CODE','Select a route');
define('ROUTE_NAME_LENGTH','Route Name can not be less than 3 characters');
define('ROUTE_ALREADY_EXIST','The Route code you entered already exists.');


// Transport Master Module
define('ENTER_STAFF_NAME','Enter staff name');
define('ENTER_STAFF_CODE','Enter staff code');
define('ENTER_DRIVING_LICENSE','Enter license no.');
define('ENTER_DRIVING_LICENSE_AUTHORITY','Enter driving license issuing authority');
define('SELECT_STAFF_TYPE','Select staff type');
define('STAFF_NAME_LENGTH','Staff name can not be less than three characters');
define('STAFF_CODE_LENGTH','Staff code can not be less than two characters');
define('DL_LENGTH','Driving license can not be less than four characters');
define('JOINING_DATE_VALIDATION','Date of joining can not be greater than current date');
define('ISSUE_DATE_VALIDATION','Issue Date can not be greater than current date');
define('DATE_OF_BIRTH_VALIDATION','Date of Birth can not be greater than current date');
define('STAFF_CODE_ALREADY_EXIST','This staff code already exists');
define('DRIVING_LICENSE_EXIST','Driving License already exists');
define('STAFF_NOT_EXIST','This staff does not exists');
define('LEAVING_DATE_VALIDATION1','Leaving date can not be smaller than joining date');
define('LEAVING_DATE_VALIDATION2','Leaving date can not be greater than current date');
define('UPLOAD_IMAGE','Upload image only');

// Fuel Module
define('ENTER_LAST_MILEGE','Enter last mileage');
define('ENTER_CURRENT_MILEGE','Enter current mileage');
define('ENTER_LITRES','Enter litres');
define('ENTER_FUEL_COST','Enter amount of fuel cost');
define('FUEL_COST_NUM','Enter valid value for fuel cost');
define('CURRENT_MILEAGE_COST_NUM','Enter valid value for current mileage');
define('LITRES_NUM','Enter valid value for litres');
define('FUEL_DATE_VALIDATION','Fuel uses date can not be greater than current date');
define('FUEL_RECORD_ALREADY_EXIST','This fuel record already exists');
define('FUEL_RECORD_NOT_EXIST','This fuel record does not exists');
define('CURRENT_FUEL_RESTRICTION','Current mileage cannot be less or equal to last mileage');
define('BACK_DATE_ENTRY_VALIDATION','Current mileage should be greater than ');
define('FUTURE_DATE_ENTRY_VALIDATION','Current mileage should be lesser than ');
define('BACK_DATE_ENTRY_VALIDATION2',' as current mileage for ');
define('BACK_DATE_ENTRY_VALIDATION3',' is ');


// BUILDING Module
define('BUILDING_NOT_EXIST','This Building does not exist');
define('ENTER_BUILDING_NAME','Enter building name');
define('ENTER_BUILDING_ABBR','Enter building abbr.');
define('BUILDING_NAME_LENGTH','Building Name can not be less than 3 characters');
define('BUILDING_ALREADY_EXIST','Building name already exists.');
define('BUILDING_ABBR_ALREADY_EXIST','Building abbr. already exists.');


// BLOCK Module
define('BLOCK_NOT_EXIST','This Block does not exist');
define('ENTER_BLOCK_NAME','Enter block name');
define('ENTER_BLOCK_ABBR','Enter block abbreviation');
define('BLOCK_NAME_LENGTH','Block Name can not be less than 3 characters');
define('BLOCK_ALREADY_EXIST','Block name already exists.');
define('BLOCK_ABBR_ALREADY_EXIST','Block abbr. already exists.');
define('SELECT_BUILDING','Select Building Name');


// CALENDER Module
define('EVENT_NOT_EXIST','This Event does not exist');
define('ENTER_EVENT_TITLE','Enter event title');
define('ENTER_SHORT_DESC','Enter short description');
define('ENTER_LONG_DESC','Enter long description');
define('EVENT_TITLE_LENGTH','Event Title can not be blank');
define('EVENT_ALREADY_EXIST','The Event Name you entered already exists.');
define('SELECT_ROLE','Select Role');
define('SELECT_ROLE1','Select Notice Visible To');
define('SELECT_ROLE2','Select Visible To');
define('DATE_VALIDATION1','End Date can not be smaller than Start Date');


// ROLE Module
define('ROLE_NOT_EXIST','This Role does not exist');
define('ENTER_ROLE_NAME','Enter role name');
define('ROLE_NAME_LENGTH','Role Name can not be less than 3 characters');
define('ROLE_ALREADY_EXIST','The Role Name you entered already exists.');
define('ROLE_DELETE_PERMISSION','You Do Not Have Permission to Delete This Role');

// MANAGE USER Module
define('USER_NOT_EXIST','This User does not exist');
define('ENTER_USER_NAME1','Enter User Name');
define('ENTER_USER_NAME_SPACE','Spaces Are Not Allowed in User Name');
define('ENTER_PASSWORD_SPACE','Spaces Are Not Allowed in Password');
define('ENTER_USER_PASSWORD1','Enter user password');
define('ENTER_USER_PASSWORD12','Re-enter user password');
define('PASSWORD_NOT_MATCH','Password does not match');
define('USER_NAME_LENGTH','User Name can not be less than 3 characters');
define('USER_PASSWORD_LENGTH','User Password can not be less than 6 characters');
define('USER_ALREADY_EXIST','The User Name you entered already exists.');

define('SELECT_ONE_ROLE_NAME','Atleast one role must be selected');
define('SELECT_ONE_DEFAULT_ROLE_NAME','Atleast one role must be made default');
define('DEFAULT_ROLE_SELECTED','Role name is not selected corresponding to default role');
//define('USER_ALREADY_EXIST','The User Name you entered already exists.');


// SLABS Module
define('SLAB_NOT_EXIST','This Slab does not exist');
define('ENTER_DELIVERED_FROM','Enter starting attendance value');
define('ENTER_DELIVERED_TO','Enter ending attendance value');
define('ENTER_ATT_MARKS','Enter marks for this slab range');
define('ENTER_DELIVERED_FROM_NUM','Enter numeric value for starting attendance');
define('ENTER_DELIVERED_TO_NUM','Enter numeric value for ending attendance');
define('ENTER_ATT_MARKS_NUM','Enter numeric/decimal value for marks');
define('ENTER_ATT_ALERT','Starting attendance Can not be same or greater than ending attendance');
define('SLAB_ALREADY_EXIST','This Slab Range already exists');


//MESSAGE Module(S)
define('NO_DATA_SUBMIT','No Data to Submit');
define('EMPTY_SUBJECT','Subject can not be empty');
define('EMPTY_MSG_BODY','Message Body Cannot be Empty');
define('SELECT_MSG_MEDIUM','Please Select a Message Medium');
define('MSG_SENT_OK','Message Sent Successfully');
define('MSG_SENT_COLLEAGUE_OK','Message Sent Successfully to Colleagues');
define('GET_STUDENT_LIST','Select Category OR Enter Employee Name to get Employee List');
define('CONFIRM_FILE_DOWNLOADING_FOR_SENT_MESSAGES','Do you want to download the excel file for details?');
define('INVALID_MOBILE_NO_FOUND','Invalid mobile number entered');
define('INVALID_MOBILE_DIGIT','Mobile number must be of 10 digits');
define('DUPLICATE_MOBILE_NO_FOUND','Duplicate mobile number entered');
define('EMPTY_MOBILE_NOS','Enter mobile numbers(comma seperated)');
define('EMPTY_SMS_MSG_BODY','Message Cannot be Empty');



// ADMIN MESSAGE TO EMPLOYEES Module
define('SELECT_EMPLOYEE_SELECT_ALERT','Select Category OR Enter Employee Name to get Employee List');
define('COLLEAGUE_EMPLOYEE_SELECT_ALERT','Select Colleagues to Send Message');
define('EMPLOYEE_SELECT_ALERT','Select Employess to Send Message');
define('MESSAGE_NOT_SEND','Message(s) not sent to all recipients.\nDo you want to download their list?');



// ADMIN MESSAGE TO STUDENTS Module
define('SELECT_STUDENT_SELECT_ALERT','Select Class OR Enter Student Roll No to get Student List');
define('STUDENT_SELECT_ALERT','Select Students to Send Message');



// BULK ATTENDANCE Module
define('EMPTY_LECTURE_DELIVERED','Lecture Delivered Can not be empty');
define('EMPTY_LECTURE_ATTENDED', 'Lecture Attended Can not be empty');
define('CHECK_ATTENDED_DELIVERED','Check Lecture Delivered and Attended');
define('BULK_ATTENDANCE_TAKEN','Bulk Attendance Taken');
define('BULK_ATTENDANCE_RESTRICTION_FROM','You Can Not Give Bulk Attendance From');
define('BULK_ATTENDANCE_RESTRICTION_TO','To');
define('BULK_ATTENDANCE_RESTRICTION_EXISTING','Bulk Attendance Already Taken From ');
define('BULK_ATTENDANCE_RESTRICTION_EXISTING_SC','Bulk Attendance Already Taken From ');
define('BULK_SELECT_STUDENT_LIST','Select Class,Subject and Group to get Student List');
define('BULK_SELECT_STUDENT_LIST_SC','Select Subject and Section to get Student List');
define('CHECK_ATTENDED_DELIVERED_MAX','Lecture delivered to a student can not be greater than lecture delivered to the class');
define('CHECK_ATTENDED_DELIVERED_CLASS_MEMBER','Lecture delivered to a student can be 0 only if he/she is not a member of class');
define('OLD_LECTURE_DELIVERED_RESTRICTION','Lecture Delivered Can not be less than already delivered lectures');
define('OLD_LECTURE_ATTENDED_RESTRICTION','Lecture Attended Can not less than already attended lectures');


// DAILY ATTENDANCE Module
define('EMPTY_ATTENDANCE_DATE','Attendance Date Can Not Be Empty');
define('CHECK_ATTENDANCE_DATE','Attendance Date Can Not be Greater Than Current Date');
define('CHECK_ATTENDANCE_CODE','Check Attendance Code');
define('DAILY_ATTENDANCE_RESTRICTION','You Can Not Give Daily Attendance For  ');
define('DAILY_ATTENDANCE_TAKEN','Daily Attendance Taken');
define('DAILY_SELECT_STUDENT_LIST','Select Class,subject,Group,Period to get Student List');
define('DAILY_SELECT_STUDENT_LIST_SC','Select Subject , Section and Period to get Student List');

define('SELECT_TOPICS_TAUGHT','Select the topic');
define('ENTER_YOUR_COMMENTS','Enter your comments');
define('EMPTY_TOPICS_TAUGHT','No topics are entered for this subject.\nPlease enter topics for this subject before taking attendance');

//Attendance Delete Module
define('SELECT_ATTENDANCE_CHECKBOX','Select atleast one checkbox to delete attendance');
define('ATTENDANCE_DATA_DELETED','Selected attendance records deleted');


// Duty Leave Module
define('SELECT_TIME_TABLE','Select Time Table');
define('SELECT_ATTENDANCE_CODE','Select duty leave type');
define('DUPLICATE_DUTY_LEAVE_DATE_RESTRICTION','More than one duty leave is not allowed on a single day');
define('DUPLICATE_DUTY_LEAVE_DATE_RESTRICTION2','Date cannot be greater than current date');
define('DUTY_LEAVES_GIVEN','Duty Leaves Taken ');
define('TIME_TABLE_LABEL_NOT_FOUND','Timetable label not found for this class');
define('EMPTY_DUTY_LEAVE','Duty leaves cannot be blank ');
define('ENTER_DUTY_LEAVE_IN_NUMERIC','Enter numeric values ');
define('DUTY_LEAVE_RESTRICTION','Sum of lecture attended and duty leaves cannot be greater than lecture delivered ');
define('SELECT_DUTY_LEAVE_STATUS','Select Action ');

// Medical Leave Module
define('DUPLICATE_MEDICAL_LEAVE_DATE_RESTRICTION','More than one medical leave is not allowed on a single day');
define('DUPLICATE_MEDICAL_LEAVE_DATE_RESTRICTION2','Date cannot be greater than current date');
define('MEDICAL_LEAVES_GIVEN','Medical Leaves Taken ');
define('EMPTY_MEDICAL_LEAVE','Medical leaves cannot be blank ');
define('ENTER_MEDICAL_LEAVE_IN_NUMERIC','Enter numeric values ');
define('MEDICAL_LEAVE_RESTRICTION','Sum of lecture attended and medical leaves cannot be greater than lecture delivered ');
define('SELECT_MEDICAL_LEAVE_STATUS','Select Action ');

// BULK EMAIL(TEACHER) Module
define('BULK_EMAIL_SELECT_STUDENT_LIST','Select Class,Subject and Group OR Enter Student Roll No to get Student List');
define('BULK_EMAIL_SELECT_STUDENT_LIST_SC','Select Subject and Section OR Enter Student Roll No to get Student List');
define('SELECT_STUDENT_PARENT_EMAIL','Select Student/+Parent(Father/Mother/Guardian) to Send Email');

// BULK SMS(TEACHER) Module
define('BULK_SMS_SELECT_STUDENT_LIST','Select Class,Subject and Group OR Enter Student Roll No to get Student List');
define('BULK_SMS_SELECT_STUDENT_LIST_SC','Select Subject and Section OR Enter Student Roll No to get Student List');
define('SELECT_STUDENT_PARENT_SMS','Select Student/+Parent(Father/Mother/Guardian) to Send SMS');



// DISPLAY ATTENDANCE(TEACHER) Module
define('DISPLAY_ATTENDANCE_SELECT_STUDENT_LIST','Select Class,Subject and Group to get Student List');
define('DISPLAY_ATTENDANCE_SELECT_STUDENT_LIST_SC','Select Subject and Section to get Student List');


// DISPLAY GRADES(TEACHER) Module
define('DISPLAY_GRADES_SELECT_STUDENT_LIST','Select Class,Subject and Group to get Student List');
define('DISPLAY_GRADES_SELECT_STUDENT_LIST_SC','Select Subject and Section to get Student List');
define('ACTIVE_GRADESET_DELETE','Active Gradeset Deleted');
define('ENTER_GRADESET_NAME','Enter Gradeset Name');
define('ACTIVE_GRADESET_UPDATE','Atleast one Gradeset must be Active');


// ENTER ASSIGNMENT MARKS(TEACHER) Module
define('ENTER_TEST_INFO','Please enter test information first');
define('SELECT_TESTTYPE_TEST','Please select test type and test');
define('MARKS_SELECT_STUDENT_LIST','Select Class,Subject and Group to get Student List');
define('MARKS_SELECT_STUDENT_LIST_SC','Select Subject and Section to get Student List');
define('SELECT_TESTTYPE','Select a test type');
define('SELECT_TEST','Select a test');
define('ENTER_TEST_ABBR','Enter test abbriviation');
define('ENTER_MAX_MARK','Enter maximum marks for test');
define('TEST_DATE_VALIDATION','Test Date Can Not be Greater Than Current Date');
define('ENTER_TEST_TOPIC','Enter topic for test');
define('ENTER_TEST_INDEX','Enter index for test');
define('EMPTY_MARKS','Marks can not be empty');
define('MARKS_VALIDATION','Marks can not be greater than maximum marks');
define('ASSIGNMENT_MARKS_GIVEN','Marks Given Successfully');
define('INVALID_MARKS','Invalid marks entered');
define('NEGATIVE_MARKS_NOT_ALLOWED','You cannot enter negative marks');

define('FROZEN_CLASS_RESTRICTION','You are not authorized to modify data for this class : ');

// ENTER EXTERNAL MARKS(TEACHER) Module
define('EXTERNAL_MARKS_GIVEN','External Marks Given Successfuly');
define('INCORRECT_EXTENSION_MARKS_UPLOAD','Please Browse Excel file with extension xls');

//Marks Deletion
define('DELETE_MARKS_NOT_TEST','Marks has been deleted.Test cannot be deleted due to related data');
define('DELETE_MARKS_AND_TEST','Test and Marks has been deleted');

//Grace Marks
define('GRACE_MARKS_GIVEN','Grace Marks Given');
define('GRACE_MARKS_VALIDATION','Sum of grace marks and marks scored can not be greater than maximum marks');
define('ERROR_AUDIT_TRAIL','Error occured while saving audit trail');

//Grace Marks Module
define('INVALID_MARKS','Invalid marks entered');
define('INVALID_MARKS','Invalid marks entered');
define('DISPLAY_TESTS_SUMMERY','Select Timetable to get tests details');


//Quarantine(Delete) Students
define('QUARANTINE_STUDENT_SELECT_ALERT','Select at least one student for deletion');
define('QUARANTINE_OK','Selected student(s) has been deleted');

//Restore Students
define('RESTORE_STUDENT_SELECT_ALERT','Select at least one student for restoration');
define('RESTORE_OK','Selected student(s) has been restored');


// SEND MESSAGE TO PARENTS(TEACHER) Module
define('PARENT_MSG_SELECT_STUDENT_LIST','Select Class,Subject and Group OR Enter Student Roll No to get Parent List');
define('PARENT_MSG_SELECT_STUDENT_LIST_SC','Select Subject and Section OR Enter Student Roll No to get Parent List');
define('PARENT_MSG_DATE_VALIDATION','Visible To Date can not be smaller than Visible From Date');
define('SELECT_PARENT_MSG','Select Parent(Father/Mother/Guardian)/Student to Send Message');


// SEND EMAIL TO STUDENT(TEACHER) Module
define('STUDENT_EMAIL_SELECT_STUDENT_LIST','Select Class,Subject and Group OR Enter Student Roll No to get Student List');
define('STUDENT_EMAIL_SELECT_STUDENT_LIST_SC','Select Subject and Section OR Enter Student Roll No to get Student List');
define('SELECT_STUDENT_EMAIL','Select Student(s) to Send Email');


// SEND MESSAGE TO STUDENT(TEACHER) Module
define('STUDENT_MSG_SELECT_STUDENT_LIST','Select Class,Subject and Group OR Enter Student Roll No to get Student List');
define('STUDENT_MSG_SELECT_STUDENT_LIST_SC','Select Subject and Section OR Enter Student Roll No to get Student List');
define('STUDENT_MSG_DATE_VALIDATION','Visible To Date can not be smaller than Visible From Date');
define('SELECT_STUDENT_MSG','Select Student(s) to Send Message');


// SEND SMS TO STUDENT(TEACHER) Module
define('STUDENT_SMS_SELECT_STUDENT_LIST','Select Class,Subject and Group OR Enter Student Roll No to get Student List');
define('STUDENT_SMS_SELECT_STUDENT_LIST_SC','Select Subject and Section OR Enter Student Roll No to get Student List');
define('SELECT_STUDENT_SMS','Select Student(s) to Send SMS');


// TEACHER COMMENT(TEACHER) Module
define('TEACHER_COMMENT_SELECT_STUDENT_LIST','Select Class,Subject and Group OR Enter Student Roll No to get Student List');
define('TEACHER_COMMENT_SELECT_STUDENT_LIST_SC','Select Subject and Section OR Enter Student Roll No to get Student List');
define('COMMENT_NOT_EXISTS','This Comment does not exist');


// SEARCH STUDENT(TEACHER) Module
//define('SEARCH_STUDENT_SELECT_STUDENT_LIST','Select Class,Subject and Group OR Enter Student Roll No / Name to get Student List');
define('SEARCH_STUDENT_SELECT_STUDENT_LIST','Select Class,Subject and Group to get Student List');
define('SEARCH_STUDENT_SELECT_STUDENT_LIST_SC','Select Subject and Section OR Enter Roll No / Name to get Student List');

// TIME TABLE DISPLAT(TEACHER[sc]) Module
define('DISPLAY_TIME_TABLE_SC','Select Subject and Section to get Time Table');


// Group Copy Module
define('SOURCE_CLASS_MISSING','Source class missing');
define('TARGET_CLASS_MISSING','Target class missing');
define('SAME_CLASS_RESTRICTION','Source and target class cannot be same');
define('NO_PARENT_CLASS_FOUND','Parent of target class is missing');
define('INVALID_PARENT_CLASS_RESTRICTION','Invalid parent class');
define('INSTITUTE_SESSION_INFO_MISSING_FOR_TARGET_CLASS','Institute and Session are missing for new class');
define('GROUP_ALREADY_ALLOCATED_TO_TARGET_CLASS','Group already allocated to this class');
define('SOURCE_CLASS_WITH_NO_GROUPS','No groups are allocated to source class');
define('SELECT_TARGET_CLASS_FOR_GROUP_COPY','Select target class');
define('SELECT_SOURCE_CLASS_FOR_GROUP_COPY','Select source class');
define('GROUP_COPY_SUCCESS','Group copied successfully');

//for GRADING
define('SELECT_GRADE_LABEL','Select Grading Label');
define('SELECT_TIMETABLE','Select Time Table');
define('GRADE_ASSIGNMENT_CONFIRM',  'Are you sure, you want to assign grades?');
define('PENDING_GRADE_ASSIGNMENT_CONFIRM',  'Marks not transferred for some students.\nThis might give incorrect MGPA.\nAre you sure, you want to assign grades?');
define('ACCEPT_CHARACTERS_SPECIAL','Accepted characters for grade name are (a-z,0-9, -)');
define('ACCEPT_CHARACTERS','Accepted characters for grade name are (a-z,0-9,-,+)');
define('SELECT_ROUNDING','Select Rounding');
define('ENTER_GRADING_LABEL_NAME','Enter Grading Label Name');
define('GRADING_LABEL_NAME_ALREADY_EXISTS','Grading Label Name Already Exists');
define('ERROR_WHILE_CREATING_GRADING_LABEL','Error while creating grading label');
define('INCORRECT_VALUES_FOR_GRADE_','Incorrect values for grade ');
define('INCORRECT_RANGE_FOR_GRADE_','Incorrect values for grade ');
define('ERROR_WHILE_CREATING_GRADING_SCALES','Error occured while creating grading scales ');
define('MARKS_NOT_TRANSFERRED_FOR_ALL_STUDENTS','Marks have not been transferred for all students ');
define('NO_INTERNAL_TOTAL_MARKS','Please Contact Administrator For Assigning "Total Internal Marks" In Assign Subject To Class Module');
// TIME TABLE LABEL Module
define('LABEL_NOT_EXIST','This Label does not exist');
define('ENTER_LABEL_NAME','Enter name');
define('SELECT_LABEL','Select Label');
define('LABEL_NAME_LENGTH','Label Name can not be less than 3 characters');
define('LABEL_ALREADY_EXIST','The Label Name you entered already exists.');
define('ENTER_ALPHABETS_NUMERIC_LABEL','Accepted characters for label name are (a-z,0-9,-._ )');
define('ACTIVE_TIME_TABLE_LABEL_EXISTS','At most one time table label can be active');
define('ACTIVE_TIME_TABLE_LABEL_DELETE','You can not delete an active time table label');
define('ACTIVE_TIME_TABLE_LABEL_UPDATE','You can not make an active time table label as inactive');


// Attendance Set Module
define('ENTER_ATTENDANCE_SET_NAME','Enter attendance set name');
define('ATTENDANCE_SET_NAME_LENGTH','Attendance set name cannot be blank');
define('ATTENDANCE_SET_CONDITION_MISSING','Select a criteria');
define('ATTENDANCE_SET_NAME_ALREADY_EXIST','Attendance set name already exists');
define('ATTENDANCE_SET_NAME_MAX_CHECK','Attendance set name cannot be more than 100 characters long');
define('INVALID_ATTENDANCE_SET_CRITERIA','Invalid criteria');
define('ATTENDANCE_SET_NOT_EXIST','Attendance set does not exists');
define('EVALUATION_CRITERIA_RESTRICTION','You can not change criteria now as it already used ');

// TIME TABLE Module
define('ERROR_WHILE_CLEARING_PREVIOUS_TIME_TABLE','Error while clearing previous time table');
define('ERROR_WHILE_ADDING_NEW_TIME_TABLE','Error while adding new time table');
define('NO_CONFLICTS_FOUND','No Conflicts found');
define('PLEASE_SELECT_ALL_VALUES','Please select all values');
define('ADJUSTMENT_DATA_CONFLICT','ADJUSTMENT CONFLICTS IN DATA ENTRY');
define('ADJUSTMENT_OTHER_CLASS_CONFLICT','ADJUSTMENT CONFLICTS WITH OTHER CLASSES');
define('ADJUSTMENT_OCCUPANCY_CONFLICT','ADJUSTMENT OCCUPANCY CONFLICTS');
define('OTHER_CLASS_CONFLICT','CONFLICTS WITH OTHER CLASSES');
define('OCCUPANCY_CONFLICT','OCCUPANCY CONFLICTS');
define('DATA_ENTRY_CONFLICT','CONFLICTS IN DATA ENTRY');
define('MISSING_ADJUSTMENT_RECORD','MISSING RECORD OF ADJUSTMENT');
define('DATA_CONFLICT','CONFLICTS WITH EXISTING TIME TABLE');
define('ERROR_WHILE_UPDATING_TIME_TABLE_ADJUSTMENT','Error while updating time table adjustment record.');
define('EXTRA_CLASSES_TIMETABLE_DATE_CANNOT_BE_PAST','<b>Time table for extra classes can not be set for past date.</b>');
define('TIME_TABLE_CANNOT_BE_COPIED_MOVED_TO_SAME_DAY','<b>Time table can not be copied/moved to same day of week.</b>');




// Advanced Student Filter
define('SELECT_BIRTH_DAY_YEAR','Please select date of birth year');
define('SELECT_BIRTH_DAY_MONTH','Please select date of birth month');
define('SELECT_BIRTH_DAY_DATE','Please select date of birth date');
define('RESTRICTION_BIRTH_DAY','From Date of birth cannot be greater than To Date of birth');

define('SELECT_ADMISSION_DAY_YEAR','Please select date of admission year');
define('SELECT_ADMISSION_DAY_MONTH','Please select date of admission month');
define('SELECT_ADMISSION_DAY_DATE','Please select date of admission date');
define('RESTRICTION_ADMISSION_DAY','From Date of admission cannot be greater than To Date of admission');



// Course Resource Module
define('RESOURCE_NOT_EXIST','This Resource does not exist');
define('SELECT_CATEGORY','Select category');
define('SELECT_TEACHER','Select teacher');
define('ENTER_DESCRIPTION','Enter description');
define('ATLEAST_ONE_RESOURCE','Enter at least resource URL or upload a file');
define('DESCRIPTION_LENGTH','Description can not be less than 5 characters');
define('INCORRECT_FILE_EXTENSION','This file extension is not allowed');
define('INCORRECT_URL','This URL is not valid');

  //Discipline Master
define("STUENT_NAME_EMPTY","Student name is empty");
define("STUENT_ROLL_NO_EMPTY","Please Enter Student roll no");
define("STUDENT_EMPTY","Student roll no is not valid");
define("SELECT_OFFENSE","Select an offense");
define('ENTER_REMARKS','Enter remarks');
define('REMARKS_LENGTH','Remarks length cannot be less than 5 characters');
define('DISCIPLINE_DATE_VALIDATION','Date can not be greater than current date.');
define("STUDENT_OFFENCE_EXIST","Record exists for this student on this date for this offense");
define("DISCIPLINE_NOT_EXIST","This record does not exist");
define("STUDENT_NOT_EXIST","This student does not exist");
define("ENTER_REPORTED_BY","Enter Offense reported by");
define("SELECT_NO_OFFENSE","Select No. of Offenses");
define("SELECT_INSTANCE","Enter No. of Instances");



// FeedBack Masters
   //Label Master
define("ENTER_LABEL_NAME","Enter Label name");
define("ENTER_VISIBLE_FROM","Enter Visible From");
define("ENTER_VISIBLE_TO","Enter Visible To");
define("ENTER_ATTEMPTS","Enter No. of Attempts");
define("VALUE_NOT_ZERO","Value should not less than 1");
define("LABEL_NAME_LENGTH","Label name can not be less than 3 characters");
define("ENTER_ALPHABETS_NUMERIC_LABEL","Enter alphabet/numeric value for label name");
define('LABEL_ALREADY_EXIST','Label name already exists.');
define('LABEL_NOT_EXIST','Label does not exist');

define('SELECT_FEEDBACK_LABEL','Select feedback label');
define('ACTIVE_FEEDBACK_LABEL_DELETE','You can not delete an active feedback label');
define('ACTIVE_FEEDBACK_LABEL_UPDATE','You can not make an active feedback label as inactive');
define('ENTER_LABEL_NUMBER','Enter number only');
define("VALUE_NOT_LESS_TEACHER","No. of attempts can not greater than 1 or less than 1 on teacher feedback");
define("NO_EMPLOYEE_SELECTED","No Employee is selected");
define("NO_STUDENT_SELECTED","No student is selected");

// Period Slot Module
define('ENTER_SLOT_NAME','Enter Slot Name');
define('ENTER_SLOT_ABBR','Enter Slot Abbreviation');
define('SLOT_NAME_LENGTH','Slot Name can not be less than 2 characters');
define('PERIOD_SLOT_ALREADY_EXIST','Slot Abbr. already exists');
define('PERIOD_SLOTNAME_ALREADY_EXIST','Slot Name already exists');
define('PERIOD_SLOT_NOT_EXIST','This Period Slot not exist');
define('ACTIVE_PERIODSLOT_DELETE','Cannot Delete Active Period Slot');
define('ACTIVE_PERIOD_SLOT_UPDATE','You can not make an active period slot as inactive');

  //Category Master
define("ENTER_CATEGORY_NAME","Enter feedback category name");
define("CATEGORY_NAME_LENGTH","Feedback category can not be less than 3 characters");
define('CATEGORY_ALREADY_EXIST','Feedback category already exists.');
define('CATEGORY_NOT_EXIST','Category does not exist');
define('SELECT_FEEDBACK_CATEGORY','Select feedback category');

  //Grade Master
define("ENTER_GRADE_LABEL","Enter feedback grade label name");
define("ENTER_GRADE_VALUE","Enter feedback grade value");
define("GRADE_LABEL_NAME_LENGTH","Feedback grade can not be less than 1 characters");
define('ENTER_GRADE_LABEL_TO_NUM','Enter numeric value for grade value');
define('FEEDBACK_GRADE_ALREADY_EXIST','This grade label already exists');
define('FEEDBACK_VALUE_ALREADY_EXIST','This grade value already exists');
define('GRADE_NOT_EXIST','Feedback grade does not exist.');
define("ENTER_GRADE_LABEL_VALUE","Enter Grade Label");


  //Grade Master
define("ENTER_FEEDBACK_QUESTION","Enter question");
define("FEEDBACK_QUESTIONS_NAME_LENGTH","Question can not be less than 5 characters");
define('FEEDBACK_QUESTIONS_ALREADY_EXIST','This question already exists');
define('FEEDBACK_QUESTIONS_NOT_EXIST','Question does not exist.');
define("NO_EDIT","You cannot edit this question");
define("NO_DELETE","You cannot delete this question");
define("GRADE_CAN_NOT_MOD_DEL","You cannot edit/delete this record as it has been used");



//Survey Assignment Master
define('STUDENT_SELECT_SURVEY_ALERT','Select Student(s) for association with this survey');
define('PARENT_SELECT_SURVEY_ALERT','Select  Parent(s) for association with this survey');
define('EMPLOYEE_SELECT_SURVEY_ALERT','Select Employee(s) for association with this survey');
define('SURVEY_ASSIGNED_OK','Survey assigned successfully only for those students, who have not given their feedback for this survey');
define('SURVEY_ASSIGNED_PARENT_OK','Survey assigned successfully only for those parents, who have not given their feedback for this survey');
define('SURVEY_ASSIGNED_EMPLOYEE_OK','Survey assigned successfully only for those employees, who have not given their feedback for this survey');
define('SELECT_SURVEY','Select a survey');
define('SURVEY_DEASSINED_STUDENT','No students are assigned for this survey now.');
define('SURVEY_DEASSINED_PARENT','No parents are assigned for this survey now.');
define('SURVEY_DEASSINED_EMPLOYEE','No employees are assigned for this survey now.');


//Category Master (Advanced)
define("SELECT_ADV_LABEL_NAME","Select Label");
define("SELECT_ADV_LABEL_NAME2","Select survey name");
define("ENTER_ADV_CATEGORY_NAME","Enter category name");
define('ENTER_ADV_CATEGORY_RELN','Select relationship');
define('ENTER_ADV_PRINT_ORDER','Enter print order');
define('ENTER_ADV_CATEGORY_DESC','Enter category description');
define('ADV_CATEGORY_NAME_LENGTH','Category name cannot be less than 3 characters');
define('ADV_CATEGORY_DESC_LENGTH','Description cannot be less than 10 characters');
define('ADV_PRINT_ORDER_NUMERIC','Enter numeric value for print order');
define('ADV_PRINT_ORDER_GREATER_THAN_ZERO','Print order cannot be zero');
define('SELECT_ADV_CATEGORY_SUBJECT_TYPE','Select subject type');
define('ADV_CATEGORY_ALREADY_EXIST','Category name already exists');
define('ADV_TWO_LEVEL_HIERARCHY_FOUND','Two level hierarchy is not allowed');
define('ADV_INVALID_CATEGORY_RELN','Invalid relationship');
define('ADV_SAME_PRINT_ORDER','Print order already exists');
define('ADV_SELF_PARENT_HIERARCHY_FOUND','This category cannot be parent category of itself');
define('ADV_PARENT_DELETE_CHECK','This category cannot be deleted as it is parent of another category');

//Question Mapping (Advanced)
define("SELECT_ADV_QUESTION_SET_NAME","Select Question Set");
define("SELECT_ADV_CAT_NAME","Select Category ");
define('ENTER_ADV_PRINT_ORDER_IN_NUMERIC','Enter numeric value');
define('ADV_QUESTION_MAPPING_DEALLOCATION','All questions will be deallocated.\nAre you sure ?');
define('SELECT_ADV_QUESTION_LIST','Select atleast one question');
define('ADV_QUESTIONS_DEALLOCATED','All questions are deallocated for which feedback is not given yet');
define('ADV_QUESTIONS_MAPPED','Selected questions mapped successfully');
define('ADV_QUESTIONS_MAPPING_RESTRICTION','Questions cannot be deallocated as they are used for feedback');
define('MISMATCHED_QUESTIONS_AND_PRINT_ORDER','Questions and print order counts must be same');


//Question Set Master (Advanced)
define("ENTER_ADV_QUESTION_SET_NAME","Enter Question Set Name");
define("ADV_QUESTION_SET_NAME_LENGTH","Question set name cannot be less than 3 characters ");
define('ADV_QUESTION_SET_ALREADY_EXIST','Question Set Name already exists');


//Feedback Label Master (Advanced)
define("ADV_ENTER_ATTEMPTS","Enter no. of attempts");
define("ADV_LABEL_NAME_LENGTH","Label name cannot be less than 3 characters ");
define('ENTER_VISIBLE_FROM','Select visible from date');
define('ENTER_VISIBLE_TO','Select visible to date');
define('ADV_NO_OF_ATTEMTS_NOT_ZERO','No. of attempts cannot be zero');
define('ADV_LABEL_DATE_VALIDATION','Visible to date cannot be smaller than visible from date');
define('ADV_LABEL_ALREADY_EXIST','This label already exists');
define('ADV_LABEL_NOT_EXIST','This label does not exist');
define('ADV_LABEL_EXTEND_DATE_VALIDATION','Extend to date cannot be less than current date');

//Provide Feedback (Advanced)
define("ADV_NUMBER_OF_ATTEMPTS_MISSING","Number of attempts missing for this label");
define("ADV_ALLOWED_NUMBER_OF_ATTEMPTS_REACHED","Maximum number of attempts reached for this label.\nYou cannot give feedback for this label any more ");
define('ADV_FEEDBACK_DONE','Feedback given successfully');

//Adv. Feedback Teacher Mapping
define("SELECT_EMPLOYEE","Select teacher");


//HRMS
  //Financial Year

define("ENTER_FINANCIAL_YEAR_LABEL","Enter financial year label name");
define("ENTER_FINANCIAL_YEAR_VALUE","Enter financial year ");
define("FINANCIAL_YEAR_LABEL_NAME_LENGTH","Financial year label can not be less than 5 characters");
define('ENTER_FINANCIAL_YEAR_TO_NUM','Enter numeric value for financial year');
define('FINANCIAL_YEAR_ALREADY_EXIST','Financial year already exists');
define('FINANCIAL_YEAR_NOT_EXIST','Financial year does not exist');
define('SELECT_FEEDBACK_CATEGORY','Select feedback category');
define('ACTIVE_FINANCIAL_YEAR_DELETE','You can not delete an active financial year');
define('ACTIVE_FINANCIAL_YEAR_UPDATE','You can not make an active financial year label as inactive');


  //Leave Type

define("ENTER_LEAVE_TITLE","Enter leave title");
define("ENTER_LEAVE_ABBR","Enter leave abbreviation ");
define("LEAVE_TITLE_NAME_LENGTH","Leave title can not be less than 5 characters");
define("LEAVE_ABBR_NAME_LENGTH","Leave abbreviation can not be less than 3 characters");
define('LEAVE_TYPE_EXIST','Leave Type Name already exists');
define('LEAVE_TYPE_NOT_EXIST','This leave does not exist');
define("ENTER_LEAVE_NAME","Enter Leave Type Name");
define("LEAVE_NAME_LENGTH","Leave name cannot be less than 3 characters");
define("LEAVE_TYPE_NAME_ALREADY_EXISTS","Leave Type Name already exists");

  //Leave Detail

define("ENTER_LEAVE_VALUE","Enter leave value");
define("SELECT_LEAVE_TYPE","Enter leave type ");
define("SELECT_FINANCIAL_YEAR","Enter financial year ");
define('ENTER_LEAVE_VALUE_TO_NUM','Enter numeric value for leave value');
define('LEAVE_VALUE_ALREADY_EXIST','This leave value already exists');
define('LEAVE_VALUE_NOT_EXIST','This leave value does not exist');



// Student Enquiry Module
define('ENTER_STUDENT_FIRST_NAME','Enter first name ');
define('ENTER_STUDENT_CONTACT_NO','Enter Contact No');
define('ENTER_FATHER_NAME','Enter father name');
define('ENTER_STUDENT_ADDRESS1','Enter address');
define('STUDENT_FIRST_NAME_LENGTH','First name cannot be less than 3 characters');
define('STUDENT_BIRTHDAY_VALIDATION','Date of Birth cannot be greater than current date');
define('SELECT_ENQUIRY_DATE','Select enquiry date');
define('FATHER_NAME_LENGTH',"Father's name can not be less than 3 characters");
define('MOTHER_NAME_LENGTH',"Mother's name can not be less than 3 characters");
define('SELECT_NATIONALITY',"Select Nationality");
define('COMP_EXAM_ROLLNO_EXIST',"Exam. Roll No. already exists");
define('APPLICATION_NO_EXIST',"Application Form No. already exists");
define('CURRENT_DATE_CHECK','Set counseling date can not be less than current date');
define('COUNSELING_START_RECORD','Enter total no. of students in counseling from');
define('COUNSELING_END_RECORD','Enter total no. of students in counseling to');
define('COUNSELING_ENTER_NUMERIC_VALUE','Enter only numeric value');
define('NOT_VALID_DATE','Date Of Birth cannot be greater than current date');


//Fine Category Master
define("ENTER_FINE_CATEGORY_NAME","Enter category name");
define("ENTER_FINE_CATEGORY_ABBR","Enter category abbreviation");
define("FINE_CATEGORY_NAME_LENGTH","Category name can not be less than 5 characters");
define("FINE_CATEGORY_ABBR_LENGTH","Category abbreviation can not be less than 3 characters");
define('FINE_CATEGORY_NAME_ALREADY_EXIST','This category name already exists');
define('FINE_CATEGORY_ABBR_ALREADY_EXIST','This category abbreviation already exists');
define('FINE_CATEGORY_NOT_EXIST','This fine category does not exist.');

//Role to Fine Category Master
define("SELECT_FINE_NAME","Select a fine to be taken");
define("ENTER_APROVER_NAME","Enter approver(user names seperated by commas)");
define("ROLE_FINE_MAPPING_NOT_EXIST","This role to fine category mapping does not exists");
define('INCORRECT_USER_NAME_INPUT','Invalid user name');
define('INVALID_USER_NAME_INPUT','Invalid user name');
define('FINE_CATEGORY_NOT_EXIST','This fine category does not exist.');
define('STUDENT_PARENT_USER_CANNOT_APPROVE','Student or parent cannot approve a fine');


//Room Allocation Master
define("ENTER_STUDENT_ROLL_OR_REG_NO","Enter roll/reg. no of student");
define("SELECT_HOSTEL","Select hostel");
define('SELECT_ROOM','Select Room');
define('STUDENT_NOT_EXISTS','Roll/Reg. no does not exists');
define('CHECK_OUT_DATE_VALIDATION','Checkout date cannot be less than checkin date');
define('HOSTEL_STUDENT_ALREADY_EXIST','Room is already allocated to this student');
define('ROOM_CAPACITY_VALIDATION','This room is full');
define('INVALID_ROOM_ALLOCATION','This room allocation does not exists');
define('ROOM_ALLOCATION_EDIT_RESTRICTION','You cannot edit this record');
define('ROOM_ALLOCATION_DELETE_RESTRICTION','You cannot delete this record');
define('SAME_DAY_CHECKIN_RESTRICTION','You cannot checkout and checkin on the same date');
define('STUDENT_WITH_NO_HOSTEL_FACILITY','This student does not availed hostel facility');


//Swap Time Table Module
define("SELECT_TEACHER_TO_SUBSTITUTE","Select a teacher to substitute");
define('SELECT_TEACHER_BY_SUBSTITUTE',"Select a teacher by which to substitute");
define('SAME_EMPLOYEE_RESTRICTION','You cannot select same employees for substituion');
define('TIME_TABLE_FROM_DATE_VALIDATION','From date cannot be smaller than current date');
define('TIME_TABLE_TO_DATE_VALIDATION','To date cannot be smaller than current date');
define('TIME_TABLE_DATE_VALIDATION','From date cannot be greater than to date');
define('TIME_TABLE_CANCEL_DATE_VALIDATION','Both from and to dates cannot be smaller than current date');



//Test Wise Performance Report Module
define("ENTER_MARKS_RANGE","Enter marks range");
define('INVALID_MARKS_RANGE',"Invalid marks range");
define('ENTER_NUMERIC_VALUE_FOR_MARKS_RANGE','Enter numeric value for marks range');
define('ENTER_DECIMAL_VALUE_FOR_MARKS_RANGE','Enter numeric value for marks range');
define('INVALID_CHART_TYPE','Invalid chart type');

//Test Wise Performance Comparison Report Module
define("ENTER_STUDENT_ROLL_NOS","Enter student roll nos.");

//Group wise Performance Report Module
define('EMPTY_CONDITION_RANGE','Enter value of percentage');
define('INVALID_CONDITION_RANGE','Enter numeric value for percentage');
define('INVALID_CONDITION_RANGE','Enter numeric value for percentage');

define('SELECT_TEACHER','Select a teacher');


//--------------------------------------------------------------------------------------------------------------
// Purpose: to centralize all messages of the application and can be altered on need basis
// Author : Dipanjan Bhattacharjee
// Created on : (21.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------
// Purpose: to centralize all messages of the application and can be altered on need basis
// Author : Rajeev Aggarwal
// Created on : (25.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//------------------------------------------------------------------------------------------

// LECTURE TYPE Module

define("ENTER_LECTURE_TYPE","Enter lecture type");
define("LECTURE_TYPE_NOEXISTS","Lecture Type does not exist");
define("LECTURETYPE_NAME_LENGTH","Lecture Type can not be less than 3 characters");
define("LECTURETYPE_SPECIAL_CHARACTER","Special characters are not allowed");
define("LECTURETYPE_DELETE","Do you want to delete this record?");

// ASSIGN SUBJECT TO CLASS Module

define("SUBJECT_TO_CLASS_ONE","Please select atleast 1 Subject");
define("ENTER_SUBJECT_TO_CLASS","Please select class");
define("ENTER_NON_NUMERIC","Non Numeric values are not allowed");

// ADMIT STUDENT Module
define("STUDENT_INSTITUTE","Select institute");
define("STUDENT_DEGREE","Select degree");
define("STUDENT_ENTRANCE","Select entrance exam");
define("STUDENT_CATEGORY","Select student category");
define("STUDENT_FIRST_NAME","Please enter student first name");
define("STUDENT_COLLEGE_REG_NO","Please enter student registration number");
define("STUDENT_REG_NO","Enter following (0-9,a-z,&-_./+,{}[]()) characters in Registration No");
define("STUDENT_ROLL","Enter following (0-9,a-z,&-_./+,{}[]()) characters in Class Roll No");
define("STUDENT_UNI_ROLL_NO","Enter following (0-9,a-z,&-_./+,{}[]()) characters in University Roll No");
define("STUDENT_VALID_YEARS","Please enter valid years");
define("SELECT_MEDICAL_ATTENTION","Select Medical Attention");
define("STUDENT_VALID_LOAN_AMOUNT","Please enter valid loan amount");
define("SELECT_COMPLETED_GRADUATION","Select Completed Graduation");
define("SELECT_EVER_STAYED_HOSTEL","Select ever stayed in hostel");
define("SELECT_WORK_EXPERIENCE","Select Work Experience");


define("STUDENT_COUNTRY","Select Student Nationality");
define("STUDENT_DOMICILE","Select Student Domicile");

define("STUDENT_VALID_EMAIL","Please enter a valid email");
define("STUDENT_VALID_CONTACT_NO","Please enter a valid contact number");
define("STUDENT_VALID_MOBILE","Please enter a valid mobile number");
define("STUDENT_VALID_DATEOFADMISSION","Please enter a valid date of admission");
define("STUDENT_VALID_DATEOFBIRTH","Please enter a valid date of birth");
define("FATHER_VALID_EMAIL","Please enter a valid father email");
define("FATHER_VALID_MOBILE","Please enter a valid father mobile number");
define("FATHER_VALID_CONTACT","Please enter a valid father contact number");

define("MOTHER_VALID_EMAIL","Please enter a valid mother email");
define("MOTHER_VALID_MOBILE","Please enter a valid mother mobile number");
define("MOTHER_VALID_CONTACT","Please enter a valid mother contact number");

define("GUARDIAN_VALID_EMAIL","Please enter a valid guardian email");
define("GUARDIAN_VALID_MOBILE","Please enter a valid guardian mobile number");
define("GUARDIAN_VALID_CONTACT","Please enter a valid guardian contact number");

define("CORRESPONDENCE_PINCODE","Special characters are not allowed in correspondence pincode");
define("CORRESPONDENCE_VALID_CONTACT","Please enter a valid correspondence contact number");

define("PERMANENT_PINCODE","Special characters are not allowed in Permanent pincode");
define("PERMANENT_VALID_CONTACT","Please enter a valid permanent contact number");
define("ENTER_MARKS_TO_NUM","Please enter numeric value for Marks Obtained");
define("ENTER_MAX_MARKS_TO_NUM","Please enter numeric value for Max. Marks");
define("ENTER_MAX_MARKS_GREATER_MARKS","Marks Obtained cannot be more than Max Marks");

define("REGISTRATION_ALREADY_EXISTS","Student college registration number already exists");
define("EMAIL_ALREADY_EXISTS","Student email already exists");
define("ALTERNATE_EMAIL_ALREADY_EXISTS","Student alternate email already exists");
define("FEE_ALREADY_EXISTS","Student fee receipt already exists");

define("QUARANTINE_REGISTRATION_ALREADY_EXISTS","Student college registration number already exists in deleted records ");
define("QUARANTINE_ROLLNO_ALREADY_EXISTS","Student RollNo already exists in deleted records");
define("QUARANTINE_UNIV_ALREADY_EXISTS","Student University RollNo already exists in deleted records");

//STUDENT DETAIL Module
define("STUDENT_FIRST","Enter student first name");
define("STUDENT_LAST","Enter student last name");

define("STUDENT_YEAR","Select date of birth year");
define("STUDENT_MONTH","Select date of birth month");
define("STUDENT_DATE","Select date of birth date");

define("STUDENT_EMAIL","Select student email");

define("STUDENT_CONTACT","Enter contact no");
define("STUDENT_NATIONALITY","Select nationality");

define("STUDENT_DETAIL_CATEGORY","Select student category");

define("STUDENT_FATHER","Enter father name");
define("STUDENT_MOTHER","Enter mother name");

define("STUDENT_DETAIL_VALID_EMAIL","Please enter a valid student email");

define("SD_FATHER_VALID_EMAIL","Invalid father email");
define("SD_MOTHER_VALID_EMAIL","Invalid mother email");
define("SD_GUARDIAN_VALID_EMAIL","Invalid guardian email");

define("SD_STUDENT_VALID_USER","Invalid student username");
define("SD_STUDENT_PASSWORD","Enter student password");
define("SD_STUDENT_USER","Enter student username");
define("SD_STUDENT_MAX_PASSWORD","Student password cannot be less than 6 characters");

define("SD_FATHER_VALID_USER","Invalid father username");
define("SD_FATHER_PASSWORD","Enter student father password");
define("SD_FATHER_USER","Enter father username");
define("SD_FATHER_MAX_PASSWORD","Father password cannot be less than 6 characters");

define("SD_MOTHER_VALID_USER","Invalid mother username");
define("SD_MOTHER_PASSWORD","Enter student mother password");
define("SD_MOTHER_USER","Enter mother username");
define("SD_MOTHER_MAX_PASSWORD","Mother password cannot be less than 6 characters");

define("SD_GUARDIAN_VALID_USER","Invalid guardian username");
define("SD_GUARDIAN_PASSWORD","Enter student guardian password");
define("SD_GUARDIAN_USER","Enter guardian username");
define("SD_GUARDIAN_MAX_PASSWORD","Guardian password cannot be less than 6 characters");

define("STUDENT_REGISTRATION_NO","Enter following (a-z,0-9,&-_./+,{}[]()) characters in Registration No");
define("STUDENT_ROLL_NO","Enter following (a-z,0-9,&-_./+,{}[]()) characters in Roll No");
define("STUDENT_UNI_ROLL_NO","Enter following (a-z,0-9,&-_./+,{}[]()) characters in UNI_ROLL_No");
define("STUDENT_UNIVERSITY_REGISTRATION_NO","Enter following (a-z,0-9,&-_./+,{}[]()) characters in UNI_REG_NO");

//STUDENT collect fees module
define("STUDENT_RECEIPT_NUMBER","Please enter student receipt number");
define("STUDENT_FEES_ROLL","Please enter student roll number");
define("STUDENT_FEES_CYCLE","Please select fee cycle");

define("STUDENT_FEES_PERIOD","Please select fee study period");
define("STUDENT_FEES_PAID","Please enter amount paid");
define("STUDENT_AMOUNT_NUMERIC","Please enter numeric value in amount paid");
define("STUDENT_FEES_CHEQUE","Please enter cheque number");
define("STUDENT_FEES_PAYABLE_BANK","Please select payable bank");
define("STUDENT_FEES_BANK","Please select issuing bank");

define("STUDENT_FEES_FAVOUR","Please enter favouring bank");
define("STUDENT_FEES_ISSUE_DATE","Please enter issuing date");

define("STUDENT_CORRECT_FINE","Please enter correct student fine amount");
define("STUDENT_CORRECT_PAID","Please enter correct paid amount");
define("STUDENT_FEES_CONFIRM","Amount Paid cannot be greater than net amount payable.Are you sure you wish to continue?");
define("STUDENT_CORRECT_ROLL","Please enter correct Student roll number");

//PAYMENT HISTORY module
define("PH_CORRECT_DATE","To date cannot be greater than from date");
define("PH_CORRECT_PAID","Enter numeric value for Amount");
define("PH_CORRECT_PAID_TO","Enter numeric value for Amount");
define("PH_CORRECT_PAID_TO_FROM","Amount Paid From cannot be greater than Amount To");

//FEE RECEIPT module
define("FR_CORRECT_DATE","To date cannot be greater than from date");
define("FR_CORRECT_PAID","Enter numeric value for Amount Paid From");
define("FR_CORRECT_PAID_TO","Enter numeric value for Amount Paid To");
define("FR_CORRECT_PAID_TO_FROM","Amount Paid From cannot be greater than Amount To");
define("INSTRUMENT_NUMBER_MISSING","Please enter intrument number");
define("AMOUNT_MISSING","Please enter amount");
define("ISSUING_BANK_MISSING","Please select bank");
define("AMOUNT_PAID_MISSING","Please enter amount");
define("RECEIPT_ALREADY_EXIST","Fee Receipt no. already exists");

//FEE INSTALLMENT module
define("FI_CORRECT_DATE","To date cannot be greater than from date");
define("FI_CORRECT_PAID","Enter numeric value for Amount Paid From");
define("FI_CORRECT_PAID_TO","Enter numeric value for Amount Paid To");
define("FI_CORRECT_PAID_TO_FROM","Amount Paid From cannot be greater than Amount To");

//FEE INSTALLMENT module
define("FS_FEE_CYCLE","Select atleast one fee cycle");
define("FS_CORRECT_DATE","To date cannot be greater than from date");

//---------------------------------------------------------------------------------------------------------
// Purpose: to centralize all messages of the application and can be altered on need basis
// Author : Ajinder
// Created on : 26.08.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//---------------------------------------------------------------------------------------------------------

// Bank Module
define('ENTER_BANK_NAME',            'Enter Bank Name');
define('ENTER_BANK_ABBR',            'Enter Bank Abbr.');
define('BANK_ABBR_ALREADY_EXIST',    'Bank Abbreviation already exists');
define('BANK_NAME_ALREADY_EXIST',    'Bank Name already exists');
define('BANK_NOT_EXIST',            'This Bank does not exist');

// Bank Branch Module
define('SELECT_BANK',                    'Select Bank');
define('ENTER_BRANCH_ABBR',                'Enter Branch Abbr.');
define('ENTER_ACCOUNT_TYPE',            'Enter Account Type');
define('ENTER_ACCOUNT_NUMBER',            'Enter Account Number');
define('ENTER_OPERATOR',                'Enter Operator');
define('ACCOUNT_NUMBER_ALREADY_EXIST',    'Account Number already exists');
define('BRANCH_ABBR_ALREADY_EXIST',        'Branch Abbreviation already exists');
define('BRANCH_NAME_ALREADY_EXIST',        'Branch Name already exists');
define('BRANCH_NOT_EXIST',                'This Branch does not exists');
define('ENTER_NUMERIC_ALPHABETS',		'Enter accepted characters are (a-z,0-9,-._ )');

//Session Module
define('ENTER_SESSION_NAME',            'Enter Session Name');
define('ENTER_SESSION_YEAR',            'Select Session Year');
define('ENTER_SESSION_ABBR',            'Enter Session Abbreviation');
define('SESSION_ABBR_ALREADY_EXIST',    'Session Abbreviation already exists');
define('SESSION_YEAR_ALREADY_EXIST',    'Session Year already exists');
define('SESSION_NAME_ALREADY_EXIST',    'Session Name already exists');
define('SESSION_NOT_EXIST',                'This Session does not exist');
define('SESSION_DATE',                  'Select session date');
define('ACTIVE_SESSION_EXISTS','Exactly one session can/should be active');
define('ACTIVE_SESSION_DELETE','You can not delete an active session');
define('ACTIVE_SESSION_UPDATE','You can not make an active session as inactive');
define('CAN_NOT_EDIT_CURRENT_SESSION','Details of current/active session can not be changed.');
define('CAN_NOT_DELETE_CURRENT_SESSION','Current/Active session can not be deleted.');
define('SESSION_YEAR_AND_YEAR_OF_SESSION_START_NOT_SAME','Session Year & Year of Session Start Date should be same.');




//Classes Module
define('SELECT_SESSION',                    'Select Session');
define('SELECT_DEGREE_DURATION',            'Select Degree Duration');
define('SELECT_PERIODICITY',                'Select Periodicity');
define('ENTER_MORE_STUDY_PERIODS',            'Enter more Study Periods for this periodicity');
define('CLASS_ALREADY_EXIST',                'Class already exists');
define('DELETE_ERROR_STUDENTS_EXIST',        'Cannot delete as Students exist');
define('CLASS_NOT_EXIST',                    'This class does not exist');
define('CLASS_ALREADY_EXIST',                'Class already exists');
define('SESSION_STARTING_BEFORE_BATCH',        "Session can't start before batch");
define('SESSION_STARTING_AFTER_DEGREE',        "Session can't start after degree");
define('ONE_STUDY_PERIOD_CAN_ACTIVE',        'Only one study period can be active');
define('ALL_STUDY_PERIOD_MUST_HAVE_VALUE',    'All study periods must have some value');
define('NO_STUDY_PERIOD_ACTIVE',            'No Study period has been made active');
define('INVALID_STUDY_PERIOD_ACTIVE',        'Invalid Study period is active');
define('GET_STUDY_PERIODS',					'Please get study periods');
define('DELETE_ERROR_TIME_TABLE_MAPPED',	'Cannot delete as class(es) linked with Time Table');
define('DELETE_ERROR_CLASS_SUBJECT_MAPPED',	'Cannot delete as class(es) mapped with subjects');
define('DELETE_ERROR_CLASS_GROUP_MAPPED',	'Cannot delete as class(es) mapped with groups');
define('CLASS_DELETION_ERROR',				'Error while deleting class(es)');
define('INVALID_SESSION_YEAR',				'Invalid Session Year');
define('INVALID_BATCH_YEAR',				'Invalid Batch Year');
define('INVALID_UNIVERSITY',				'Invalid University');
define('INVALID_DEGREE',					'Invalid Degree');
define('INVALID_BRANCH',					'Invalid Branch');
define('INVALID_DEGREE_DUATION',			'Invalid Degree Duration');
define('INVALID_PERIODICITY',				'Invalid Periodicity');
define('ERROR_WHILE_UPDATING_CLASS',		'Error while updating class');
define('CLASSES_FOR_BATCH_',		'Classes for batch ');
define('NEXT_SESSION_NOT_FOUND',		'Next Session Year could not be found ');
define('_CAN_NOT_BE_MADE_IN_SESSION_',		' can not be made in session ');
define('CLASS_WITH_SAME_DETAILS_ALREADY_EXISTS','Class with same Degree, Batch, Branch, Institute, University with different Periodicity already exists.');


//Assign Roll No.s Module
define('SELECT_ROLL_NO_LENGTH',                'Select Roll No. Length');
define('SELECT_SORTING',                    'Select Sorting');
define('ROLL_NO_LENGTH_SHORT',                'Roll No. Length not enough to be assigned');
define('CLASS_HAS_NO_STUDENT',                'No Student admitted to this class');
define('ROLL_NO_ASSIGNED_ALREADY',            'Roll Numbers have been assigned already');
define('SERIES_ROLL_NO_ASSIGNED_ALREADY',    'Roll No(s). already exist with this prefix and/or suffix');
define('ROLL_NO_ASSIGNMENT_CONFIRM',        'Are you sure, you want to assign roll numbers?');
define('ROLL_NO_ASSIGNED_SUCCESSFULLY',        'Roll Numbers assigned successfully');
define('USERNAMES_EXIST_ALREADY',            'Username(s) already exist with this prefix and/or suffix');
define('ROLL_NO_MESSAGE',                    'Roll No.s for unselected students will be made null.&nbsp;&nbsp;Roll No. allocation will be done for selected students only.');
define('NO_STUDENT_SELECTED',                    'No Student selected to assign roll no.');
define('INVALID_SERIES_STARTING_NUMBER',         'Invalid Series Starting Number');



//Assign Group Module
define('SELECT_GROUP_TYPE',                    'Select Group Type');
define('SELECT_GROUP',                        'Select Group');
define('ENTER_STUDENTS_TO_ASSIGN_GROUP',    'Enter No. of students to be assigned group');
define('ENTER_VALID_NO_TO_ASSIGN_GROUP',    'Enter valid No. of students to be assigned this group');
define('ALL_STUDENTS_NOT_ASSIGNED_ROLL_NO',    'All student(s) have not been issued roll numbers yet. \n Please assign roll numbers to them');
define('GROUP_ASSIGNED_ALREADY_CONFIRM',    'Groups for this class have been assigned already.\n\n This will remove all previous assignment.');
define('ERROR_OCCURED',                        'Error occured while showing list');
define('GROUP_ASSIGNED_SUCCESSFULLY',        'Group assigned successfully');
define('ATTENDANCE_ENTERED_FOR_GROUP',        'You can\'t change allocation as attendance has been entered for this group.');
define('TEST_ENTERED_FOR_GROUP',            'You can\'t change allocation as tests has been entered for this group.');
define('ATTENDANCE_ENTERED_FOR_THIS_CLASS',    'You can\'t change allocation as attendance has been entered for this class.');
define('TESTS_TAKEN_FOR_THIS_CLASS',        'You can\'t change allocation as tests has been entered for this class.');
define('PLEASE_CREATE_',					'Please create ');
define('_GROUPS_FIRST',					' group(s) first.');
define("ENTER_ROLLNO","Enter Roll No.");
define('_GROUPS_FIRST',					' group(s) first.');
define('MORE_THAN_ONE_GROUP_ALLOCATION_FOR_',					'More than one group alloted to: ');
define('_FOR_GROUP_TYPE_',	' for group type: ');

//Student Labels Report                : All messages covered already
//Student Attendance Report            : All messages covered already
//Attendance Not Entered Report        : All messages covered already
//Marks Distribution Report            : All messages covered already
//Testwise Marks Report                : All messages covered already
//Subjectwise Consolidated Report    : All messages covered already

//Classwise Consolidated Report
define('INVALID_GROUP_ENTERED', 'Invalid Group Selected');
define('INVALID_STUDENT_FOUND', 'Invalid Student Selected');
define('GROUP_NOT_FOUND_', 'Group Not Found: ');
define('GROUP_ALLOCATION_MISSING_FOR_', 'Group Allocation Missing for: ');
define('ATTENDANCE_TESTS_ALREADY_ENTERED_FOR_STUDENT', 'Attendance/Tests have been enterd for student : ');
define('_IN_GROUP_', ' in group : ');
define('_FOR_GROUP_', ' for group : ');
define('PARENT_GROUP_NOT_SELECTED_FOR_STUDENT', 'Parent group not selected for student : ');
define('ERROR_WHILE_REMOVING_CURRENT_GROUP_ALLOCATION', 'Error occured while clearing previous allocation.');
define('ERROR_WHILE_UPDATING_GROUP_ALLOCATION', 'Error occured while doing group allocation.');


//Config Master
define('ENTER_PARAM_NAME',        'Enter Parameter');
define('ENTER_LABEL',            'Enter Label');
define('ENTER_VALUE',            'Enter Value');
define('PARAM_ALREADY_EXIST',    'Parameter already exists');

//Configs Master
define('INVALID_CONFIG_SETTINGS','Invalid Config Settings');
define('ENTER_ALL_SETTINGS','Enter values for all config settings');
define('ALLOWED_SPECIAL_CHARS','Only , - _. special characters are allowed.');

define('VALID_RECORD_PER_PAGE','Records per page cannot be greater than 100');
define('VALID_LINK_PER_PAGE','Link per page cannot be greater than 25');
define('VALID_RECORD_PER_PAGE_TEACHER','Records per page cannot be greater than 100');
define('VALID_RECORD_PER_PAGE_ADMIN','Records per page cannot be greater than 100');
define('VALID_RECORD_PER_PAGE_MESSAGE','Records per page cannot be greater than 100');

//No Script message
define('NO_SCRIPT_ERROR','Java script is disabled in your browser. You must enable javascript for better performance');
define('CONNECTION_ERROR','Oops, The connection with the database could not be established.');
define('SESSION_TIME_OUT','Your session has been expired. Please click <a href="'.UI_HTTP_PATH.'/index.php" class="redLink">here </a> to login.');
define('PAGE_NOT_FOUND','The page you requested not found on our server. Please click <a href="'.UI_HTTP_PATH.'/index.php" class="redLink">here </a> to login.');


//for assigning sections to students
define('SELECT_SECTION',"Select Section");
define('SELECT_SORTBY',"Select Sort By");
define('ENTER_STUDENTS_TO_ASSIGN',"Enter No. of Students to be assigned section");
define('ASSIGNED_MORE_THAN_MAX',"Enter valid no. of students");
define('SECTION_ASSIGNMENT_CONFIRM',"Are you sure you want to assign section?");
define('SELECT_COURSE',"Select Course");

//for marks transfer
define('MARKS_TRANSFER_CONFIRM',"Are you sure you want to transfer marks?");
define('ALL_STUDENTS_NOT_GIVEN_TESTS_FOR_',"All students have not given test for ");
define('FOLLOWING_STUDENTS_NOT_GIVEN_TESTS_FOR_',"Following students have not given test for ");
define('SUBJECT_TOTAL_NOT_MATCH_FOR_',"Subject total does not match for ");
define('TEST_TYPE_SUM',"Test type sum ");
define('INTERNAL_MARKS_SUM',"internal marks sum ");
define('NO_TESTS_TAKEN_FOR_',"No tests have been taken for ");
define('ATTENDANCE_NOT_ENTERED_IN_',"Attendance not entered for ");
define('FOLLOWING_ISSUES_FOUND_DURING_TRANSFERRING_MARKS_FOR_',"Following issues found during transferring marks for ");
define('INVALID_DATA_FOUND_FOR_SUBJECT_',"Invalid data found for subject: ");
define('SUBJECT_',"Subject: ");
define('_IS_NOT_OFFERED'," is not offered");
define('_HAS_NO_ATTENDANCE_TEST_MARKS'," has no attendance or test marks");
define('INVALID_INTERNAL_MARKS_FOR_SUBJECT_',"Invalid internal marks for Subject: ");
define('INVALID_TEST_TYPE_MARKS_FOR_SUBJECT_',"Invalid test type marks for Subject: ");
define('INTERNAL_MARKS_SUM_DOES_NOT_MATCH_WITH_TEST_TYPE_SUM_FOR_SUBJECT_',"Internal marks sum does not match with test type sum for Subject: ");
define('INVALID_DATA_FOR_TEST_TYPE_',"Invalid data for test type: ");
define('_FOR_GROUP_'," for Group: ");
define('ATTENDANCE_SET_NOT_FOUND_FOR_SUBJECT_',"Attendance Set not found for Subject: ");
define('ATTENDANCE_NOT_ENTERED_IN_SUBJECT_',"Attendance not entered for Subject: ");
define('ATTENDANCE_SLAB_NOT_MADE_FOR_LECTURES_DELIVERED_',"Attendance Slab not made for lectures delivered: ");
define('SOME_ERROR_HAS_OCCURED',"Some Error has occured.");
define('SELECT_ATLEAST_ONE_SUBJECT',"Please select atleast 1 subject.");
define('NO_OPTION_SELECTED',"No option selected");
define('INVALID_TIMETABLE',"Invalid Time Table");
define('INVALID_CLASS',"Invalid Class");
define('NO_SUBJECT_ASSOCIATED_TO_THIS_CLASS',"No Subject associated to this class");
define('INVALID_SUBJECT_SELECTED',"Invalid subject selected");
define('INVALID_COPY_TO_SUBJECTS',"Invalid subject in 'copy to'");
define('NO_SUBJECT_SELECTED_TO_APPLY_ATTENDANCE_SET',"No subject has been selected to apply attendance set");
define('INVALID_VALUES_FOR_PERCENT_FROM',"Invalid values for percent from");
define('INVALID_VALUES_FOR_PERCENT_TO',"Invalid values for percent to");
define('INVALID_VALUES_FOR_MARKS_SCORED',"Invalid values for marks scored");
define('INVALID_VALUES_ENTERED',"Invalid values entered");
define('INVALID_VALUES_ENTERED_IN_FROM',"Invalid values entered in 'From'");
define('INVALID_VALUES_ENTERED_IN_TO',"Invalid values entered in 'To'");
define('INVALID_FROM_VALUES_ENTERED_FOR_MARKS_',"Invalid values entered in 'Marks'");
define('INVALID_FROM_VALUES_ENTERED_FOR_',"Invalid 'From' values entered Marks: ");
define('INVALID_TO_VALUES_ENTERED_FOR_MARKS_',"Invalid 'To' values entered Marks: ");
define('INVALID_VALUES_FOR_LECTURES_DELIVERED',"Invalid values for lectures delivered ");
define('INVALID_VALUES_FOR_ATTENDED_FROM',"Invalid values for attended from");
define('INVALID_VALUES_FOR_ATTENDED_TO',"Invalid values for attended to");
define('INVALID_VALUES_FOR_ATTENDED_TO',"Invalid values for attended to");
define('INVALID_VALUES_FOR_MARKS_SCORED',"Invalid values for marks scored");
define('INVALID_VALUES_FOR_LECTURES_ATTENDED_FROM',"Invalid values for lectures attended from");
define('INVALID_VALUES_FOR_LECTURES_ATTENDED_TO',"Invalid values for lectures attended to");
define('INVALID_VALUES_FOR_MARKS_SCORED',"Invalid values for marks scored");
define('INVALID_FROM_VALUES_ENTERED_FOR_LECTURES_DELIVERED_',"Invalid 'From' values entered for lectures delivered");
define('_AT_MARKS_'," at marks ");
define('LECTURES_ATTENDED_CAN_NOT_BE_MORE_THAN_LECTURES_DELIVERED',"Lectures attended can not be more than lectures delivered");
define('INVALID_PROCESS',"Invalid process");
define('TESTTYPE_DATA_NOT_FOUND_FOR_',"Test type data not found for ");
define('CNT_DATA_NOT_FOUND_FOR_',"Count data not found for ");
define('WEIGHTAGE_DATA_NOT_FOUND_FOR_',"Weightage data not found for ");
define('INVALID_CRITERIA_FOR_',"Invalid criteria for ");
define('CNT_NOT_REQUIRED_FOR_',"Count not required for ");
define('ENTER_CNT_FOR_',"Enter count for ");
define('ENTER_WEIGHTAGE_FOR_',"Enter weighage for ");
define('INVALID_SUBJECT_FOUND_FOR_COPYING',"Invalid subject found for copying ");
define('ERROR_WHILE_DELETING_OLD_TEST_TYPES_FOR_',"Error while deleting old test type for ");
define('ERROR_WHILE_ADDING_TEST_TYPES_FOR_',"Error while adding test type for ");
define('NO_SUBJECT_FOUND_FOR_ATTENDANCE_MARKS_PERCENT',"No Subject found for attendance marks percent.");
define('NO_ATTENDANCE_SET_SELECTED',"No attendance set selected ");
define('ATTENDANCE_SET_PERCENT_DOES_NOT_EXISTS',"Attendance set percent does not exists");
define('ERROR_WHILE_UPDATING_ATTENDANCE_SET',"Error while updating attendance set");
define('ATTENDANCE_SET_PERCENT_NOT_SELECTED',"Attendance set [percent] not selected");
define('ATTENDANCE_SET_PERCENT_NAME_NOT_ENTERED',"Attendance set [percent] name not entered");
define('ATTENDANCE_SET_PERCENT_NAME_ALREADY_EXISTS',"Attendance set [percent] name already exists");
define('ERROR_WHILE_ADDING_ATTENDANCE_SET',"Error while adding attendance set");
define('ATTENDANCE_SET_PERCENT_NAME_NOT_REQUIRED',"Attendance set name not required");
define('ATTENDANCE_SET_NOT_RELATED_TO_PERCENTAGES',"Attendance not related to percentages");
define('FAILURE_WHILE_REMOVING_OLD_ATTENDANCE_SET_MARKS',"Error while removing old attendance set marks");
define('ERROR_WHILE_ADDING_ATTENDANCE_SET',"Error while adding attendance set");
define('NO_SUBJECT_FOUND_FOR_ATTENDANCE_MARKS_SLABS',"No subject found for attendance marks slabs");
define('ATTENDANCE_SET_SLAB_NOT_SELECTED',"Attendance set slab not selected");
define('ATTENDANCE_SET_SLAB_DOES_NOT_EXISTS',"Attendance set slab does not exists");
define('ATTENDANCE_SET_SLAB_NAME_NOT_ENTERED',"Attendance set slab name not entered");
define('ATTENDANCE_SET_SLAB_NAME_ALREADY_EXISTS',"Attendance set slab name already exists");
define('ATTENDANCE_SET_SLAB_NAME_NOT_REQUIRED',"Attendance set [slab] not required");
define('ATTENDANCE_SET_NOT_RELATED_TO_SLABS',"Attendance set not related to slabs");
define('INVALID_ROUNDING',"Invalid rounding");
define('TRANSFER_PROCESS_ALREADY_RUNNING_IN_SAME_SESSION',"Transfer Processes already running. Please logout and login again.");
define('INVALID_DATA_FOUND_FOR_SUBJECT_',"Invalid data found for subject:");
define('INVALID_OPTION',"Invalid Option");
define('CLASS_FROZEN_RESTRICTION',"Marks can not be transferred as this class is frozen.");
define('FATAL_ERROR_OCCURED',"Fatal Error Occured:");
define('FOLLOWING_DUTY_LEAVE_UNRESOLVED_FOR_SUBJECT_',"Following duty leaves are Unresolved for subject:");
define('FOLLOWING_DUTY_LEAVE_CONFLICTED_FOR_SUBJECT_',"Following duty leaves are Conflicted for subject:");
define('FOLLOWING_MEDICAL_LEAVE_UNRESOLVED_FOR_SUBJECT_',"Following medical leaves are Unresolved for subject:");
define('FOLLOWING_MEDICAL_LEAVE_CONFLICTED_FOR_SUBJECT',"Following medical leaves are Conflicted for subject:");
define('FOLLOWING_DUTY_AND_MEDICAL_LEAVE_CONFLICTED_FOR_SUBJECT_',"Following duty and medical leaves are Conflicted for subject:");
define('ATTENDANCE_SET_MARKS_',"Attendance Set Marks: ");
define('_CAN_NOT_BE_GREATER_THAN_TEST_TYPE_MARKS_'," can not be greater than test type marks: ");

define('SESSION_FROM_DATE', 'Select From Date');
define('SESSION_TO_DATE',  'Select To Date');



//for histogram
define('SELECT_HISTOGRAM',"Select Histogram");

//for grade
define('ENTER_GRADE_LABEL','Enter Grade');
define('ENTER_GRADE_POINTS','Enter Grade Points');
define('GRADE_RANGE_POINTS','Enter Grade Points Between 0 to 127 ');
define('GRADE_ALREADY_EXIST','Grade already exists');
define('GRADE_NOT_EXIST','This grade does not exist');
define('ENTER_GRADE','Enter Grade');

define('SELECT_GRADESET','Select Grade Set');


//for GRADING
define('SELECT_GRADE_LABEL','Select Grading Label');
define('SELECT_TIMETABLE','Select Time Table');
define('GRADE_ASSIGNMENT_CONFIRM',  'Are you sure, you want to assign grades?');
define('PENDING_GRADE_ASSIGNMENT_CONFIRM',  'Marks not transferred for some students.\nThis might give incorrect MGPA.\nAre you sure, you want to assign grades?');
define('ACCEPT_CHARACTERS','Accepted characters for grade name are (a-z,0-9,-,+)');

// for grading range check
define('SELECT_GRADE_LABEL','Select Grading Label');
define('SELECT_GRADE','Select Grade');
define('ENTER_GRADE_RANGE','Enter Grade Range');
define('ENTER_GRADE_RANGE_VALUE','Enter Grade Range Between 0 to 100 ');
define('ENTER_GRADE_RANGE_FROM','Enter Range From Value');
define('ENTER_GRADE_RANGE_TO','Enter Range To Value');
define('GRADE_RANGE_ALREADY_EXIST','Grade Range Already Enterd ');
define('GRADE_RANGE_GREATER','Range To Can not be lesser than range from ');
define('GRADE_RANGE_ZERO','Range To value Can not be 0');
define('GRADE_RANGE_NUMERIC','Enter Numeric Values for Range From');


//for Percentage Wise attendance Report
define('SELECT_CRITERIA','Select Above/Below Criteria');
define('ENTER_AVERAGE',  'Enter Average');

//for student promotion
define('PROMOTE_STUDENTS_CONFIRM',  'Are you sure, you want to promote students?');
define('STUDENTS_PROMOTED',  'All Students have been promoted');
define('OLD_CLASS_CONTAIN_STUDENTS',  'Degree contains some pending students');
define('ASSIGN_GRADES_FIRST',  'Assign grades to degree students');


//for Subject Topoic
define("ENTER_SUBJECT_TOPIC","Enter Subject Topic");
define("ENTER_SUBJECT_TOPIC_ABBREVATION","Enter Subject Topic Abbreviation");
define('SUBJECT_TOPIC_ALREADY_EXIST','The subject topic already exists.');
define('SUBJECT_ABBR_ALREADY_EXIST','The abbreviation already exists.');
define('SUBJECT_TOPIC_NOT_EXIST','This subject topic does not exist');
define('ENTER_ALPHABETS_NUMERIC_ABBR','Accepted characters for abbrevations (a-z,0-9, -+._)');
define('SUBJECT_TOPIC_EMPTY_VALUE','Please enter topic after seperator');

//for group change
define("INVALID_ROLL_NO","Enter Valid Roll No.");
define("NO_THEORY_GROUP_SELECTED","No Theory Group Selected");
define("MORE_THAN_ONE_THEORY_GROUP_SELECTED","More than one theory group selected");
define("NO_TUTORIAL_GROUP_SELECTED","No Tutorial group selected");
define("MORE_THAN_ONE_TUTORIAL_GROUP_SELECTED","More than one tutorial group selected");
define("TUT_GROUP_NOT_RELATED_TO_THEORY","Tutorial group not related to theory group");

define("NO_OPTIONAL_GROUP_DATA_FOUND","No optional group data found");
define("INVALID_OPTIONAL_GROUP_COUNT","Invalid optional group selection");
define("MAX_LECTURE_DELIVERED_ERROR","Lecture attended cannot be greater than delivered");
define("MAX_MARKS_SCORED_ERROR","Marks scored cannot be greater than maximum marks");


define("NO_PRACTICAL_GROUP_SELECTED","No practical group selected");
define("MORE_THAN_ONE_PRACTICAL_GROUP_SELECTED","More than one practical group selected");
define("PRACTICAL_GROUP_NOT_RELATED_TO_THEORY","Practical group not related to theory group");

define("ERROR_WHILE_SAVING_ATTENDANCE_FOR_","Error while saving attendance for ");
define("ERROR_WHILE_QUARANTINING_ATTENDANCE_FOR_","Error while quarantining attendance for ");
define("ERROR_WHILE_DELETING_ATTENDANCE_FOR_","Error while deleting attendane for ");
define("ERROR_WHILE_SAVING_MARKS_FOR_","Error while saving marks for ");
define("_FOR_TEST_"," for test ");
define("ERROR_WHILE_QUARANTINING_MARKS_FOR_","Error while quarantining marks for ");
define("ERROR_WHILE_DELETING_MARKS_FOR_","Error while deleting marks for ");
define("ERROR_WHILE_UPDATING_STUDENT_GROUP_","Error while updating student group ");

//for CGPA calculation
define("APPLY_CGPA_CONFIRM","Are you sure, you want to calculate CGPA for selected degree?");
define("GRADES_NOT_APPLIED_FOR_","Grades are not applied for all the students.\n\rFor following subjects:  ");
define("CREDITS_NOT_ENTERED_FOR_","Credits have not been entered for: ");


// TimeTable Label
define('FROM_TO_ALREADY_EXIST','From and To date already assigned for previous TimeTable Label.');

// Session
define('FROM_TO_SESSION_ALREADY_EXIST','Session Date already assigned.');

//Thoughts
define("ENTER_THOUGHTS","Enter Thoughts");
define('THOUGHTS_ALREADY_EXIST','The thoughts already exists.');
define('THOUGHTS_NOT_EXIST','This thought does not exist.');


//ICard
define("SELECT_ICARD","Select Card Format");
define("ENTER_RECEIPT","Enter Receipt No.");
define("ENTER_VALIDITY","Enter Valid for buspass");
define('ENTER_RECEIPT_CHAR','Enter following (a-z,0-9) characters only in buspass receipt');
define('ENTER_VALIDITY_CHAR','Enter following (a-z,0-9 .&-) characters only in valid');


//transfer marks report
define("SELECT_CLASS_FOR_REPORT","Select Class");
define("SELECT_SUBJECT_TYPE_REPORT","Select Subject Type");
define("SELECT_TEST_TYPE_CATEGORY","Select Test Type");
define("STUDENT_TO_ONE","Select atleast one student");


// Foxpro List generate
define("FOXPRO_LIST_EMPTY","Kindly transfer marks before converting into foxpro.");
define("INCORRECT_FORMAT","No data found");

//upload final marks
define("SUBJECT_","Subject: ");
define("_NOT_MAPPED_TO_CLASS"," is not mapped to selected class. ");
define("FINAL_MARKS_DOES_NOT_MATCH_FOR_SUBJECT_","Final Marks does not match for subject:  ");
define("INVALID_UNIVERSITY_ROLL_NO_","Invalid universtity roll no.:  ");
define("FAILURE_WHILE_CREATING_NEW_TEST_FOR_SUBJECT_","Failure while creating new test for subject: ");
define("INVALID_STATUS_","Invalid status: ");
define("MARKS_NOT_SAVED_FOR_SUBJECT_","Marks could not be saved for subject: ");
define("FAILURE_WHILE_SAVING_TEST_MARKS_FOR_SUBJECT_","Failure while saving marks for subject: ");
define("MARKS_SAVED_FOR_SUBJECT_","Marks saved successfully for subject: ");

//Associate time table to class
define('TIMETABLE_INACTIVE_CLASS','Class cannot be associated with inactive Time Table labels');
define('SELECT_ATLEAST_ONE_CLASS','Please select atleast 1 class');
define('ClASSES_MAPPED_TO_TIMETABLE_FOR_LABEL','one / more classes have been mapped to time table for this label.');
define('ClASSES_MAPPED_OTHER_TIMETABLE_LABEL','one / more classes have been mapped to other time table labels already.');
define('MISSED_CLASS_TIMETABLE_CREATED','one / more classes mapped to this time table are missed.');


// Student List
define('START_RECORD','Enter starting record no');
define('ENTER_NUMERIC_VALUE','Enter only numeric value');

// Student Bus Pass
define('ENTER_BUSPASS_REG','Enter Student Reg./Roll No.');
define('INVALID_BUSPASS_REG','Invalid Student Reg./Roll No.');
define('ACCEPT_BUSPASS_RECEIPT','Accepted characters for receipt number are (a-z,0-9,-/.&)');
define('BUSPASS_ROUTE','Select Bus Route');
define('BUSPASS_STOPPAGE','Select Bus Stoppage');
define('ENTER_BUSPASS_RECEIPT','Enter Receipt No.');
define('BUSPASS_VALID_DATE','Select Valid Upto Date');
define('BUSPASS_DATE_CHECK','Valid Upto date can not be less than current date');
define('BUSPASS_STATUS','Select Bus Pass Status');
define('STUDENT_BUSPASS_ALREADY','Student Bus Pass already exists');
define('RECEIPT_BUSPASS_ALREADY','Receipt No. already exists');
define('DATE_FORMAT','Enter date in correct format (dd-mm-yy).');
define('ENTER_ROLLNO_NAME','Enter Reg./Roll No. or Student Name');
define('ACCEPT_ROLLNO','Accepted characters for Reg./Roll No. are (a-z,0-9,-/.)');
define('ACCEPT_NAME','Accepted characters for name are (a-z, .)');
define('CANCEL_BUSPASS','Do you want to cancel this record?');

// Publisher Message
define('ENTER_TYPE_NAME','Enter type');
define('SELECT_SCOPE','Select scope');
define('ENTER_PUBLISHER_NAME','Enter published by');
define('ENTER_DESCRIPTION','Enter description');
define('ENTER_PUBLISHER_DATE','Select publisher date');
define('PUBLISHING_NOT_EXIST','Invalid Published Id');


//Seminar Message
define('ENTER_SEMINAR_ORGANISEDBY','Enter organised by');
define('ENTER_SEMINAR_TOPIC','Enter seminar topic');
define('ENTER_SEMINAR_DESCRIPTION','Enter seminar description');
define('ENTER_SEMINAR_PLACE','Enter seminar place');
define('ENTER_SEMINAR_START_DATE','Select seminar start date');
define('ENTER_SEMINAR_END_DATE','Select seminar end date');
define('SEMINAR_NOT_EXIST','Invalid Seminar Id');
define('SELECT_PARTICIPATION','Select participation');
define('INVALID_SEMINAR_FEE','Enter numeric value for seminar fee');

//Consulting Message
define('ENTER_COUNSULTING_PROJECTNAME','Enter Project Name');
define('ENTER_COUNSULTING_SPONSOR','Enter Sponsor');
define('ENTER_COUNSULTING_START_DATE','Select Start Date');
define('ENTER_COUNSULTING_END_DATE','Select End Date');
define('ENTER_COUNSULTING_AMOUNT','Enter Amount');
define('ENTER_COUNSULTING_REMARKS','Enter Remarks');
define('INVALID_COUNSULTING_AMOUNT','Enter numeric value for amount funding');
define('COUNSULTING_NOT_EXIST','Invalid Counsulting Id');

//MDP Module
define('ENTER_MDP_NAME','Enter MDP name');
define('SELECT_MDP_START_DATE','Select Start Date');
define('SELECT_MDP_END_DATE','Select End Date');
define('SELECT_MDP','Select MDP');
define('ENTER_MDP_SESSION_ATTENDED','Enter No.Of Sessions ');
define('ENTER_MDP_SESSION_CONDUCTED','Enter MDP Sessions');
define('ENTER_MDP_HOURS','Enter MDP hours');
define('ENTER_MDP_VENUE','Enter MDP Venue');
define('SELECT_MDP_TYPE_ID','Enter Mdp Type');
define('ENTER_DESCRIPTION','Enter Description');
define('ENTER_VALID_VALUE_FOR_SESSIONS_ATTENDED','Enter Numeric Value for Session Attended');
define('ENTER_VALID_VALUE_FOR_HOURS','Enter Numeric Value for Hours');
define('MDP_NOT_EXIST','Mdp do not exist');

//Employee Workshop Message
define('ENTER_WORKSHOP_TOPIC','Enter Topic');
define('ENTER_WORKSHOP_START_DATE','Select Start Date');
define('ENTER_WORKSHOP_END_DATE','Select End Date');
define('ENTER_WORKSHOP_SPONSORED','Select Sponsored');
define('ENTER_WORKSHOP_SPONSOREDDETAIL','Enter Sponsored Detail');
define('ENTER_WORKSHOP_LOCATION','Enter Location');
define('ENTER_WORKSHOP_OTHERSPEAKERS','Enter Other Speakers');
define('ENTER_WORKSHOP_AUDIENCE','Enter Audience');
define('ENTER_WORKSHOP_ATTENDEES','Enter Attendees');
define('ACCEPT_WORKSHOP_INTEGER','Enter numeric value for attendees');
define('WORKSHOP_NOT_EXIST','Invalid Workshop Id');

// task Message
define('ENTER_TITLE','Select Title');
define('ENTER_DAYS_PRIOR','Enter prior days');
define('TITLE_ALREADY_EXIST','Title already exists');
define('CLASS_ACTIVITY_NOT_EXIST','Select the class');

// Forgot Password Message
define('FORGOT_TECHNICAL_PROBLEM','Invalid email address OR email address does not exist.<br>Kindly contact at '.ADMIN_MSG_EMAIL);
define('INVALID_VERIFICATION_CODE','Invalid verification code, try again.<br/>If the problem still persists.<br/>Kindly contact at '.ADMIN_MSG_EMAIL);
define('INVALID_USERNAME','Invalid username, try again.');
define('EMAIL_VERIFICATION',"Someone (presumably you) requested a password change through e-mail verification. If this was not you, ignore this message and nothing will happen.<br>
           If you requested this verification, visit the following URL to change your password:");


// Student Fine
define('ENTER_FINE_CATEGORY','Select Fine Category');
define('ENTER_FINE_AMOUNT','Enter Valid Fine Amount');
define('ENTER_FINE_REASON','Enter Fine Reason');
define('ENTER_FINE_AMOUNT_TO_NUM','Enter numeric value for fine amount');
define('FINE_REASON_LENGTH','Fine Reason length cannot be less than 10 characters');
define('FINE_DATE_VALIDATION','Fine Date cannot be greater than current date');
define('FINE_ALREADY_EXIST','Student fine already exists');
define('ENTER_FINE_CATEGORY','Select Fine Category');
define('FINE_ALREADY_PAID','Record cannot be edited as it has already been paid');

// Subject Category Module
define('ENTER_SUBJECT_CATEGORY','Enter Category Name');
define('ENTER_SUBJECT_CATEGORY_ABBR','Enter Subject Abbreviation');
define('SUBJECT_CATEGORY_EXIST','Category Name already exists');
define('SUBJECT_CATEGORY_ABBR_EXIST','Subject Abbreviation already exists');
define('PARENT_CATEGORY_ITSELF','Parent Category cannot be parent of itself');
define('SUBJECT_CATEGORY_NOT_EXIST','Category Name does not exists');
define('ACCEPT_SUBJECT_CATEGORY','Accepted characters for Category Name are (a-z 0-9 ()-/,.&)');
define('SUBJECT_CATEGORY_RELATION','Parent-Child relation exist cannot delete parent, first delete the child');
define('SUBJECT_CATEGORY_NOT_EXIST','Invalid Subject Category');


// collect fine
define('NO_FINE_CATEGORY','No student fine allocated');
define('SELECT_ONE_FINE','Select atleast one fine');
define('PARENT_CATEGORY_ITSELF','Parent category cannot be parent of itself');


// File Upload Message
define('FILE_NOT_UPLOAD','Invalid file extension or maximum upload size exceeds');
define('NOT_WRITEABLE_FOLDER','Following operation cannot be performed. Please contact administrator');

// File Upload Message
define('SELECT_SUGGESTION_SUBJECT','Select suggestion subject');
define('SELECT_SUGGESTION_TEXT','Enter Message');

// Attendance Set Message
define('SELECT_ATTENDANCE_SET','Select Attendance Set');

// Advanced Feedback Module Messages
define('ENTER_ANSWERSET_NAME','Enter Name');

//-------Answer Set Options----
define("ENTER_OPTION_LABEL","Enter option text");
define("ENTER_OPTION_VALUE","Enter option weight");
define("OPTION_LABEL_NAME_LENGTH","Feedback option label can not be less than 1 characters");
define('ENTER_OPTION_LABEL_TO_NUM','Enter numeric/decimal value');
define('FEEDBACK_OPTION_ALREADY_EXIST','Option text already exists');
define('FEEDBACK_OPTION_VALUE_ALREADY_EXIST','Option weight already exists');
define('FEEDBACK_ORDER_ALREADY_EXIST','Print order already exists');
define('OPTION_NOT_EXIST','Option does not exist.');
define("ENTER_OPTION_LABEL_VALUE","Enter answer set");
define("SELECT_ANSWERSET","Select answer set");
define("OPTION_VALUE_GREATER_ZERO","Option Weight should be in between 0 to 1000");
define("ANSWER_OPTIONS_PRINT_ORDER_VALIDATIONS","Print order cannot be less than zero or greater than 100");
define("NUMERIC_PRINT_ORDER_VALIDATIONS","Enter only numeric value for Print order");

//Question Master
define("ADV_ENTER_FEEDBACK_QUESTION","Enter question");
define("ADV_SELECT_QUESTION_SET","Select question set");
define("ADV_SELECT_ANSWER_SET","Select answer set");
define("ADV_FEEDBACK_QUESTIONS_NAME_LENGTH","Question can not be less than 5 characters");
define('ADV_FEEDBACK_QUESTIONS_ALREADY_EXIST','This question already exists');
define('ADV_FEEDBACK_QUESTIONS_NOT_EXIST','Question does not exist.');
define("NO_EDIT","You cannot edit this question");
define("NO_DELETE","You cannot delete this question");
define("GRADE_CAN_NOT_MOD_DEL","You cannot edit/delete this record as it has been used");


//Student Internal Re-appear
define("REGISTRATION_SUBMITTED","Registration form submitted successfully");
define("REGISTRATION_CANCEL","Are you sure to cancel your existing registration details?");
define("REGISTRATION_DELETE","Cancellation process completed successfully");
define("REGISTRATION_UPDATED","Registration form updated successfully");
define("REGISTRATION_SUBJECT","Select at least one subject");
define("REGISTRATION_DELETE","Registration form cancel. Career/Elective courses details not selected.");



//for Admission Module
define("ENTER_FROM_NUMBER","Enter Form Number");
define("ENTER_CANDIDATE_NAME","Enter Candidate Name");
define("ENTER_FATHER_GUARDIAN_NAME","Enter Father/Mother/Guardian Name");
define("ENTER_FATHER_GUARDIAN_NO","Enter Valid Father/Mother/Guardian Mobile Number");
define("ENTER_CANDIDATE_CATEGORY","Select candidate category");
define("ENTER_DOB","Enter Date of Birth");
define("ENTER_VALID_DOB","Enter valid Date of Birth");
define("ENTER_AIEEE_ROLLNO","Enter Exam Roll No. of candidate");
define("ENTER_AIEEE_RANK","Enter Exam Rank of candidate");
define("ENTER_CANDIDATE_MOBILE","Enter mobile no. of candidate");
define("ENTER_VALID_MOBILE","Enter valid mobile no.");
define("ENTER_CANDIDATE_EMAIL","Enter e-maiil of candidate");
define("ENTER_VALID_CANDIDATE_EMAIL","Enter valid e-maiil of candidate");
define("CANDIDATE_ALREADY_EXIST","Candidate already exist");
define('ADMISSION_UPLOAD_DONE','Data transferred successfully');
define('ADMISSION_UPLOAD_FAILURE','Data could not be transferred successfully');

define('ENTER_PROGRAM_NAME','Enter Program Name');
define('ENTER_TOTAL_SEATS','Enter Total Seats');
define('ENTER_HP_TOTAL_SEATS','Enter HP Total Seats');
define('ENTER_VISIBLE_STATUS','Enter visible status');
define('PROGRAM_NAME_LENGTH','Program Name can not be less than 2 characters');
define("ENTER_VALID_HP_SEATS_VALUE","HP Seats should be less than seats.");


define('ENTER_PROGRAM_FEE_NAME','Enter Program Fee Name');

//for Payroll Module
define("HEAD_NAME_ALREADY_EXIST","Head Name Already Exists");
define("CONFIRM_OVERWRITE","Records with the same effective date already exists. Confirm overwrite?");
define("ENTER_HEAD_NAME","Enter Head Name");
define("SELECT_HEAD_TYPE","Select Head Type");
define("SELECT_DEDUCTION_ACCOUNT","Select Deduction Account");
define("DESCRIPTION_EXCEEDS_LIMIT","Description cannot exceed 60 characters");
define("ENTER_HEAD_ABBR","Enter head abbreviation");
define("HEAD_ABBR_ALREADY_EXIST","Head abbreviation already exists");
define("EMPLOYEE_CODE_BLANK","Employee is not assigned any employee code");

define("ENTER_ACCOUNT_NAME","Enter Account Name");
define("ENTER_ACCOUNT_NUMBER","Enter Account Number");
define("ACCOUNT_NUMBER_ALREADY_EXISTS","Account number already exists");
define("ACCOUNT_NAME_ALREADY_EXISTS","Account name already exists");

define("NO_MATCH","No Matching Record Found");
define("INVALID_AMOUNT","Enter valid amount");

define("ENTER_FROM_YEAR","Enter From Year");
define("ENTER_TO_YEAR","Enter To Year");
define("ENTER_VALID_YEAR","To Year Should Be 1 Greater Than From Year");
define("START_YEAR_EXISTS","From Year Already Exists");

//payroll hold salary
define("ENTER_REASON","Enter hold/unhold reason");
define("REASON_LENGTH_EXCEEDS","Reason length should not exceed 60 chars");
define("NO_HISTORY","No history available");
//Student Attendance Performance Report
define("INVALID_ATTENDANCE_RANGE","Invalid attendance range");
define('ENTER_NUMERIC_VALUE_FOR_ATTENDANCE_RANGE',"Enter numeric value");


//Student Concessoin Module
define("PERCENTAGE_WISE_MAX_VALUE_CHECK","For percentage wise concession value cannot be greater than 100");
define('TOTAL_FEES_WISE_MAX_VALUE_CHECK',"Concession amount cannot be greater than total fees");
define('ENTER_CONCESSION_REASON',"Enter reason for concession");
define('STUDENT_CONCESSION_GIVEN',SUCCESS);


//Guest House Request Allocation Module
define("ENTER_GUEST_NAME","Enter guest name");
define('SELET_BUDGET_HEAD',"Select budget head");
define('GUEST_NAME_LENGTH',"Guest name can not be less than 3 characters");
define('ARRIVAL_DATE_RESTRICTION',"Arrival date can not be less than current date");
define('DEPARTURE_DATE_RESTRICTION',"Departure date can not be less than current date");
define('ARRIVAL_DEPARTURE_DATE_RESTRICTION',"Departure date can not be less than arrival date");
define('BOOKING_NOT_EXIST',"This record does not exists");
define('BOOKING_EDIT_RESTRICTON',"This record can not be edited");
define('BOOKING_DELETE_RESTRICTON',"This record can not be deleted");


//Guest House Request Authorization Module
define("SELECT_ALLOCATE_REJECT","Select whether this request is allocated or not ");
define('ENTER_GUEST_HOUSE_REJECTION_REASON',"Enter reason for not allocation");
define('GUEST_HOUSE_REJECTION_REASON_LENGTH',"Reason for not allocation can not be less than 5 characters");
define('SELECT_GUEST_HOUSE',"Select guest house");
define('GUEST_HOUSE_ROOM_FULL',"This room is full");


//Leave Set Mapping Module
define("SELECT_LEAVE_SESSION","Select leave session ");
define("SELECT_LEAVE_SET","Select leave set ");
define('SELECT_LEAVE_TYPE',"Select leave type");
define('SELECT_LEAVE_TYPE2',"Select leave type");
define('DUPLICATE_LEAVE_SET_TYPE',"Leave type already exist");
define('ENTER_LEAVE_TYPE_VALUE',"Enter leave value");
define('ENTER_INTEGER_VALUE',"Enter numeric value");
define('LEAVE_TYPE_VALUE_GREATER_ZERO',"Value must be greater than zero");
define('LEAVE_SET_MAPPING_NOT_EXIST',"This record does not exists");
define('LEAVE_SET_ALREADY_EXIST',"Leave Set Name already exists");


//Leave Set Mapping Module
define("ENTER_VALID_EMPLOYEE_INFO","Invalid employee code");
define('DUPLICATE_LEAVE_SET',"Duplicate leave set found");
define('EMPLOYEE_LEAVE_SET_MAPPING_NOT_EXIST',"This record does not exists");
define('EMPLOYEE_LEAVE_SET_MAPPING_CAN_NOT_MOD_DEL',"This record cannot be edited/deleted");


//Apply Leaves Module
define("LEAVE_DATE_RESTRICTION","To date can not be smaller than from date");
define('APPLY_LEAVE_DATE_RESTRICTION1',"Date of apply can not be greater than from date");
define('APPLY_LEAVE_DATE_RESTRICTION2',"Date of apply can not be greater than to date");
define('ENTER_LEAVE_REASON',"Enter reason for applying leave");
define('EMPLOYEE_LEAVE_NOT_EXIST',"This leave record does not exists");
define('EMPLOYEE_LEAVE_CAN_NOT_MOD_DEL',"This leave record cannot be edited/deleted");
define('INCORRECT_LEAVE_TYPE_FOR_EMPLOYEE',"Invalid leave type");
define('EMPLOYEE_LEAVE_EDIT_RESTRICTION',"This leave record can not be edited");
define('EMPLOYEE_LEAVE_CANCEL_RESTRICTION',"This leave record can not be cancelled");


//Authorize Leaves Module
define("SELECT_LEAVE_STATUS","Select leave status");
define('ENTER_FIRST_AUTHORIZER_REASON',"Enter reason");
define('ENTER_SECOND_AUTHORIZER_REASON',"Enter reason");
define('NO_AUTHORIZATION_RESTRICTION',"You can not authorize this leave request");


//Leave Analysis Report Module
define("ENTER_CRITERIA_VALUE","Enter value");
define('ENTER_CRITERIA_VALUE_IN_INTERGER',"Enter only integer value");
define('ENTER_CRITERIA_VALUE_POSITIVE',"Enter positive value");

define('LEAVE_SET_ALREADY_EXIST','This leave set name already exist');
define('ENTER_LEAVE_SET_NAME','Enter leave set name');
define('LEAVE_SET_NAME_LENGTH','Leave set name can not be less than 3 characters');


//Leave Authorization Mapping Module
define("SELECT_FIRST_AUTHORIZER","Select first authorizer");
define('SELECT_SECOND_AUTHORIZER',"Select second authorizer");
define('SAME_FIRST_SECOND_AUTHORIZER_RESTRICTION',"First and second authorizer can not be same person");
define("EMPLOYEE_LEAVE_AUTHORIZATION_MAPPING_NOT_EXIST","This record does not exist");
define("CYCLIC_AUTHORIZATION_RESTRICTION","This employee can not be authorized by selected employees");
define("DUPLICATE_AUTHORIZATION_RESTRICTION","Authorization of this leave type for this employee already exist");
define("SELECT_FIRST_AUTHORIZER","Select first authorizer");


//Budget Heads Master Module

//BudgetHead Module

define('BUDGETHEAD_NOT_EXIST','BudgetHead does not exist');
define('ENTER_BUDGETHEAD_TYPE','Select Budget Head Type');
define('ENTER_BUDGETHEAD_NAME','Enter Budget Head name');
define('ENTER_BUDGETHEAD_AMOUNT','Enter Budget Head Amount');
define('BUDGETHEAD_NAME_LENGTH','Budget Head Name can not be less than 3 characters');
define('BUDGETHEAD_ALREADY_EXIST','Budget Head Name entered already exists');
define('BUDGETHEAD_NAME_ALREADY_EXIST','Budget Head name already exists');
define('ENTER_BUDGETHEAD_NUMBER','Enter only numbers in Budget Head Amount');
define("ENTER_BUDGET_HEAD_NAME","Enter head name");
define('ENTER_BUDGET_HEAD_AMT',"Enter head amount");
define('SELECT_BUDGET_HEAD_TYPES',"Select head type");
define("BUDGET_HEAD_NAME_LENGTH","Head name can not be less than 3 characters");
//define("ENTER_DECIMAL_VALUE","Enter decimal value");
define("ENTER_DECIMAL_VALUE","Enter valid numeric value");
define("ENTER_POSITIVE_VALUE","Enter positive value");
define("BUDGET_HEAD_ALREADY_EXIST","This head name already exists for this head type");
define("BUDGET_HEAD_NOT_EXIST","This record does not exists");


//Duty Leave Events Master Module
define("ENTER_DUTY_LEAVE_EVENT_NAME","Enter event name");
define('DUTY_LEAVE_EVENT_NAME_LENGTH',"Event name can not be less than 3 characters");
define('DUTY_LEAVE_START_DATE_CHECK',"End date can not be less than start date");
define("DUTY_LEAVE_EVENT_ALREADY_EXIST","This event already exists");
define("DUTY_LEAVE_EVENT_NOT_EXIST","This record does not exists");


//Duty Leave Upload Module
define("SELECT_DUTY_LEAVE_EVENT","Select an event");
define('SELECT_FILE_FOR_UPLOAD',"Choose a file for upload");


// Lecture Percent Module
define('QUOTA_SLAB_UPDATE_SUCCESSFULLY','All quota seats updated successfully');
define('QUOTA_SLAB_DELETE_SUCCESSFULLY','All quota seats deleted successfully');

define('SELECT_ROUND','Select round');


//Change Student Branch Module
define("BRANCH_CHANGED_SUCCESSFULLY","Branches changed successfully");
define("SELECT_NEW_CLASS_BRANCH","Select new class / branch");

define("QUOTA_SEATS_COPY","Seats have been copied to selected class(es).");
define("SELECT_COPY_TO_CLASS","Select at least 1 class from the 'To' field Drop Down");
define("NO_SEATS_COPY","No seats found to copy");
define("LESS_THEN_SEATS","No. of seats can not be less than already allocated to students");
define("CANNOT_COPY_SEATS","Can not copy seats as allocation has been done already for Class: ");



/******************************MESSAGES FOR APPRAISAL MODULES*********************************/

//Appraisal Tab Module
define("ENTER_APPRAISAL_TAB_NAME","Enter appraisal tab name");
define("APPRAISAL_TAB_NAME_LENGTH","Appraisal tab name can not be less than three characters ");
define("APPRAISAL_TAB_NAME_ALREADY_EXIST","This appraisal tab name already exists");
define('APPRAISAL_TAB_NOT_EXIST','This record does not exists');

//Appraisal Title Module
define("ENTER_APPRAISAL_TITLE_NAME","Enter appraisal title");
define("APPRAISAL_TITLE_NAME_LENGTH","Appraisal title can not be less than three characters ");
define("APPRAISAL_TITLE_NAME_ALREADY_EXIST","This appraisal title already exists");
define('APPRAISAL_TITLE_NOT_EXIST','This record does not exists');

//Appraisal Question Module
define("ENTER_APPRAISAL_QUESTION","Enter appraisal question");
define("ENTER_APPRAISAL_QUESTION_WEIGHTAGE","Enter wieghtage");
define("SELECT_APPRAISAL_TITLE","Select title");
define('SELECT_APPRAISAL_TAB','Select tab');
define('ENTER_APPRAISAL_QUESTION_LENGTH','Question can not be less than three characters');
define('SELECT_APPRAISAL_PROOF_FORM','Select proof form');
define('APPRAISAL_QUESTION_ALREADY_EXIST','This question already exists');
define('APPRAISAL_PROOF_USED','This proof form already used');
define('APPRAISAL_QUESTION_NOT_EXIST','This record does not exist');

//Employee Hierarchy Module
define("SELECT_SUPERIOR_EMPLOYEE","Select superior employee");
define("HIERARCHY_DELETION_ALERT","Mapping with all subordinates(with respect to the search criteria)\\n of this employee will be deleted.\\nAre you sure?");
define("EMPLYEE_HIERARCHY_WILL_CHANGE","Employee hierarchy will change.\\nAre you sure");
define('HIERARCHY_DONE','Hierarchy established');
define('HIERARCHY_DELETION_DONE',"Mapping with all subordinates(with respect to the search criteria)\\n of this employee are deleted");
define('CYCLIC_HIERARCHY_FOUND','Cyclic hierarchy found');
define('EMPLOYEE_INFO_MISSING','Employee information missing');
define('SAME_HIERARCHY_ERROR','Same employee can not be his/her superior');

define('EMPLOYEE_APPRAISAL_GIVEN','Appraisal Given');
/******************************MESSAGES FOR APPRAISAL MODULES*********************************/


//Leave Session Module
define('ENTER_LEAVE_SESSION_NAME',          'Enter Session Name');
define('LEAVE_SESSION_NAME_ALREADY_EXIST',  'Session Name already exists');
define('LEAVE_SESSION_START_DATE',          'Select session start date');
define('LEAVE_SESSION_END_DATE',            'Select session end date');
define('LEAVE_ACTIVE_SESSION_EXISTS',       'Only one session can be active');
define('LEAVE_ACTIVE_SESSION_DELETE',       'You can not delete an active session');



//for Assignment allocation
define("EMPTY_TASK_TITLE","Enter Assignment Title");
define("EMPTY_TASK_DESCRIPTION","Enter Assignment Description");
define("EMPTY_MSG_ASSIGNED_DATE","Enter Assignment Date");
define("EMPTY_MSG_SUBMISSION_DATE","Enter Due Date");

define("ASSIGNED_DATE_LESS_DUE_DATE","Assigned Date cannot be greater than Due Date");
define("ASSIGNED_LESS_THAN_CURRENT","Assigned Date cannot be less than current Date");
define("ENTER_ASSIGNMENT_TEXT","Enter Remarks");
define("ASSIGNMENT_SENT_OK","Task Assigned Successfully");
define("SELECT_STUDENT_AASSIGNMENT","Select Student(S) to Assign Task");
define("STUDENT_ASS_SELECT_STUDENT_LIST_SC","Select Course and Section to get Student List");


// Leave Session Carry Forward
define("CREATE_NEXT_LEAVE_SESSION","Please create a next leave session");
define("ACTIVE_LEAVE_SESSION","Please select atleast one active session");

//Placement Company Module
define("ENTER_PLACEMENT_COMPANY_NAME","Enter company name");
define("ENTER_PLACEMENT_COMPANY_CODE","Enter company code");
define("ENTER_PLACEMENT_COMPANY_ADDRESS","Enter company address");
define("ENTER_PLACEMENT_COMPANY_CONTACT_PERSON","Enter contact person's name");
define("ENTER_PLACEMENT_COMPANY_PERSON_DESIGNATION","Enter designation");
define("ENTER_PLACEMENT_COMPANY_LANDLINE","Enter landline number");
define("ENTER_PLACEMENT_COMPANY_MOBILE_NO","Enter mobile number");
define("ENTER_PLACEMENT_COMPANY_NAME_LENGTH","Name can not be less than 3 characters");
define("ENTER_PLACEMENT_COMPANY_CODE_LENGTH","Code can not be less than 3 characters");
define("ENTER_PLACEMENT_COMPANY_ADDRESS_LENGTH","Address can not be less than 5 characters");
define("ENTER_PLACEMENT_COMPANY_PERSON_LENGTH","Contact person's name can not be less than 3 characters");
define("ENTER_PLACEMENT_COMPANY_DESIGNATION_LENGTH","Designation can not be less than 3 characters");
define("PLACEMENT_COMPANY_NAME_ALREADY_EXIST","This name already exists");
define("PLACEMENT_COMPANY_CODE_ALREADY_EXIST","This code already exists");
define("PLACEMENT_COMPANY_NOT_EXIST","This record does not exists");
define("PLACEMENT_COMPANY_EMAIL_ID_ALREADY_EXIST","This email id already exists");
define("ENTER_PLACEMENT_COMPANY_EMAIL_ID","Enter Email Id");
define("ENTER_PLACEMENT_COMPANY_REMARKS","Enter Company Remarks");
define("ENTER_VALID_MOBILE_NUMBER","Please Enter Valid Mobile Number (10 digits)");
define("ENTER_VALID_LANDLINE_NUMBER","Please Enter Valid Landline Number");



//Follow Ups Module
define("SELECT_PLACEMENT_COMPANY_NAME","Select a company");
define("ENTER_FOLLOWUP_CONTACT_PERSON","Enter contacted person's name");
define("ENTER_FOLLOWUP_PERSON_DESIGNATION","Enter designation");
define("ENTER_FOLLOWUP_COMMENTS","Enter your comments");
define("ENTER_PFOLLOWUP_CONTACT_PERSON_LENGTH","Contacted person's name can not be less than 3 characters");
define("ENTER_FOLLOWUP_PERSON_DESIGNATION_LENGTH","Designation can not be less than 3 characters");
define("ENTER_FOLLOWUP_DATE","Enter follow up date");
define("FOLLOWUP_NOT_EXIST","This record does not exists");


//Placement Drive Module
define("ENTER_PLACEMENT_DRIVE_CODE","Enter placement drive code");
define("ENTER_PLACEMENT_DRIVE_VISITING_PERSONS","Enter visiting person's name");
define("ENTER_DRIVE_CODE_LENGTH","Length of placement drive can not be less than 3 characters");
define("DRIVE_START_DATE_RESTRICTION","From date can not be less than current date");
define("DRIVE_END_DATE_RESTRICTION","To date can not be less than current date");
define("DRIVE_START_END_DATE_RESTRICTION","To date can not be less than From date");
define("ENTER_CUT_OFF_MARKS","Enter cut off marks");
define("ENTER_TEST_SUBJECT","Enter test subject");
define("ENTER_TEST_DURATION","Enter test duration");
define("PLEASE_ENTER_ATLEAST_ONE_TEST_SUBJECT_DURATION","Enter at least one test subject and its duration");
define("ENTER_INTERVIEW_DURATION","Enter interview duration");
define("ENTER_DISCUSSION_DURATION","Enter group discussion duration");
define("DUPLICATE_PLACEMENT_DRIVE_CODE","This placement drive code already existe");
define("PLACEMENT_DRIVE_TIME_CLASH","Timing of this record clashes with other placemenent drives");
define("PLACEMENT_DRIVE_NOT_EXIST","This record does not exists");
define("ENTER_PLACEMENT_DRIVE_VENUE","Enter venue");
define("ENTER_NUMERIC_VALUE_DURATION","Please Enter numeric value For Duration");
define("ENTER_CUT_OFF_MARKS_IN_EITHER_ONE","Enter value in either marks in last sem. or in graduation");
define("VALUE_NOT_MORE_THAN_100","Enter value should not be more than 100");

//Placement Student Details Upload Module
define("SELECT_PLACEMENT_DRIVE","Select a placement drive");


//Placement Genarate Student List Module
define("ENTER_GRACE_MARKS_FOR_PLACEMENT","Enter grace marks");
define("PLACEMENT_DRIVE_LIST_GENERATED","Student eligibility list generated");

//Placement Genarate Student Result List Module
define("PLACEMENT_RESULT_LIST_GENERATED","Student result list generated");


//copy role permission
define("PLEASE_SELECT_INSTITUTE_TO_COPY_ROLE_PERMISSIONS","Please select institute to copy role permissions");
define("ROLE_PERMISSIONS_CAN_NOT_BE_COPIED_TO_SELF","Role permissions can not be copied to self");
define("NO_PERMISSION_FOUND_TO_COPY","No role permission found to copy");


// Fee Cycle Classes
define("FEE_CYCLE","Select Fee Cycle");
define("FEE_CYCLE_CLASS_ADDED_SUCCESSFULLY","Fee Cycle Classes added successfully");
define("FEE_CYCLE_CLASS_UPDATE_SUCCESSFULLY","Fee Cycle Classes updated successfully");
define("FEE_CYCLE_CLASS_DELETE_SUCCESSFULLY","Fee Cycle Classes deleted successfully");
define("FEE_CYCLE_CLASS_DUPLICATE","Classes have been linked to other Fee Cylce");

// Time Table
define("TIME_TABLE_CLASS_ADDED_SUCCESSFULLY","Time Table Classes added successfully");
define("TIME_TABLE_CLASS_UPDATE_SUCCESSFULLY","Time Table Classes updated successfully");
define("TIME_TABLE_CLASS_DELETE_SUCCESSFULLY","Time Table Classes deleted successfully");
define("TIME_TABLE_CLASS_DUPLICATE","Classes have been linked to other Time Tables");
define("TIME_TABLE_NOT_GENERATED","Time Table Not Generated");

// Fee Head Values
define("FEE_HEAD_VALUE_DELETE_SUCCESSFULLY","Fee Head Value deleted successfully");
define("FEE_HEAD_VALUE_UPDATED_SUCCESSFULLY","Fee Head Value updated successfully");
define("FEE_HEAD_VALUE_ADDED_SUCCESSFULLY","Fee Head Value added successfully");
define("FEE_HEAD_VALUE_NOT_APPLICABLE_TO_BOTH","Leet and Non Leet value should not be accept able for Fee Head Value.");
define("CLASS_WISE_HEAD_VALUE_ADDED_SUCCESSFULLY","Class wise head value added successfully");
define("CLASS_WISE_HEAD_DELETE_SUCCESSFULLY","Class wise head value deleted successfully");
define("CLASS_WISE_HEAD_VALUE_UPDATED_SUCCESSFULLY","Class wise head value updated successfully");

define("FEE_HEAD_VALUE_COPY","Fee Head Value have been copied to selected class(es).");
define("SELECT_COPY_TO_CLASS","Select at least 1 class from the 'To' field Drop Down");
define("FEE_HEAD_VALUE_NO_SEATS_COPY","No Fee Head Value found to copy");
define("FEE_HEAD_VALUE_CANNOT_COPY","User should not be copy the Fee Head Values if Fee Head Values are already assigned to following classes: ");
define("PAYMENT_DETAIL","Total Amount Paid and Payment Detail mismatch");
define("APPL_HEAD_PAYMENT_DETAIL","Head wise Amount Paid and Payment Detail mismatch");
define("APPL_TRANS_HEAD_PAYMENT_DETAIL","Transport Amount Paid and Payment Detail mismatch");
define("APPL_HOSTEL_HEAD_PAYMENT_DETAIL","Hostel Amount Paid and Payment Detail mismatch");

//Fee Concession
define("FEE_CONCESSION_NO_SEATS_COPY","Not defined the Concession Value to copy");
define("FEE_CONCESSION_VALUE_CANNOT_COPY","User should not be copy the Define Concession Value if Define Concession Value are already assigned to following classes: ");
define("FEE_CONCESSION_VALUE_COPY","Define Concession Value have been copied to selected class(es).");
define("DEFINE_CONCESSION_VALUE_ADDED","Define Concession Value added successfully");   
define("DEFINE_CONCESSION_VALUE_DELETE","Define Concession Value deleted successfully");  
define("DEFINE_CONCESSION_VALUE_UPDATED","Define Concession Value updated successfully"); 


//student tab ADD-ONs
define("ENTER_DD_NO","Enter DD number");
define("ENTER_DD_DATE","Enter date");
define("DD_DATE_RESTRICTION","Date can not be greater than current date");
define("ENTER_DD_AMT","Enter DD amount");
define("ENTER_DD_BANK_NAME","Enter bank");

//Book master Values
 define('ENTER_BOOK_NAME','Enter Book Name');
 define('ENTER_BOOK_AUTHOR','Enter Author Name');
 define('ENTER_UNIQUE_CODE','Enter Unique Code');
 define('ENTER_INSTITUTE_BOOK_CODE','Enter Institute Book Code');
 define('ENTER_ISBN_CODE','Enter ISBN Code');
 define('ENTER_NO_OF_BOOKS','Enter No. of Books');
 define('BOOK_ALREADY_EXISTS','Book Already Exists');
 define('BOOK_NAME_EXISTS','Book Name Exists');
 define('BOOK_AUTHOR_EXISTS','Book Author Exists');
 define('UNIQUE_CODE_EXISTS','Unique Code Exists');
 define('INSITUTE_BOOK_CODE_EXISTS','Institue Book Code exists');
 define('ISBN_CODE_EXISTS','ISBN code exists');
 define('BOOK_NAME_LENGTH','Book name length should not be less than 3');
 define('ENTER_VALID_NO_OF_BOOKS','No. of books should be numeric');

define('RANGE_SHOULD_BE_IN_ASSENDING_ORDER','Range should be in Assending order');
 //Book master Values
 define('ENTER_MESSAGE_TEXT','Enter message text');
 define('MESSAGE_DATE_RESTRICTION','Date can not be smaller than current date');
 define('MESSAGE_NOT_EXIST','This message does not exists');
 define('ENTER_MESSAGE_DATE','Select date');


 //Daily Attendance Copy Module
 define('DAILY_ATTENDANCE_COPIED','Attendance records copied');
 define('ENTER_STATE_NAME','Enter state name');
 define('ENTER_STATE_CODE','Enter state code');
 define('STATE_NAME_LENGTH','State name length should be greater than 3 character');

 //Marks Inconsistency
 define('NO_INCONSISTENCY_FOUND','No Inconsistency Found');

// Final Grade Module
define('FINAL_GRADE_UPDATE_SUCCESSFULLY','All assign final grades updated successfully');
define('FINAL_GRADE_DELETE_SUCCESSFULLY','All assign final grades deleted successfully');

// Attendance Deduct Module
define('ATTENDANCE_DEDUCT_UPDATE_SUCCESSFULLY','All attendance deduct updated successfully');
define('ATTENDANCE_DEDUCT_DELETE_SUCCESSFULLY','All attendance deduct deleted successfully');

define('UPDATE_STUDENT_STATUS','Make Student Status as Mandatory field and default marked checked');


// Block Student Module
define('WRONG_STRING','Roll No has special characters.These are not allowed.Please remove them and try again.');
 
 
// Test Module Message
define('ENTER_TEST_NAME','Enter Test Name');  

define('OLD_DATA',"<br><B>System did not find data for this semester.</B><br><br>The possibilities could be: <br><br> 1. Data was uploaded directly from backend or <br> 2. syenergy application was not used to calculate the marks.<br><br>");
define('ENTER_VALID_LECTURES_ATTENDED_FOR_','Enter valid lectures attended for');
define('TOTAL_LECTURES_ATTENDED_MORE_THAN_TOTAL_LECTURES_DELIVERED','Total lectures attended can not be more than total lectures delivered');
define('TOTAL_LECTURES_ATTENDED_MORE_THAN_TOTAL_LECTURES_DELIVERED','Total lectures attended can not be more than total lectures delivered');
define('ERROR_WHILE_UPDATING_ATTENDANCE','Error while updating attendance');
define('ENTER_VALID_MARKS_SCORED_FOR_','Enter valid marks scored for ');
define('MARKS_SCORED_MORE_THAN_TOTAL_MARKS_FOR_','Marks scored can not be more than Max. Marks for ');
define('ERROR_IN_MARKS_CALCULATION_FOR_','Error while calculating marks for ');
define('NO_CATEGORY_FOUND_FOR_TESTS','No category found for tests ');
define('MARKS_SCORED_MORE_THAN_TOTAL_MARKS','Marks scored can not be more than Max. Marks ');
define('ERROR_IN_CALCULATION_FOR_TEST_','Error while calculating marks for ');
define('TOTAL_MARKS_SCORED_MORE_THAN_TOTAL_MAX_MARKS','Total marks scored can not be more than total Max. Marks ');
define('COMPRE_TEST_','Compre Test ');
define('_NOT_FOUND',' not found');
define('INVALID_MARKS_FOR_COMPRE_TEST_','Invalid marks for compre test');
define('ERROR_OCCURED_WHILE_UPDATING_TEST_MARKS','Error Occured while updating test marks');
define('ERROR_OCCURED_WHILE_SAVING_TEST_MARKS','Error Occured while saving test marks');
define('ERROR_OCCURED_WHILE_UPDATING_MARKS','Error Occured while updating marks');
define('ERROR_OCCURED_WHILE_UPDATING_CGPA','Error Occured while updating CGPA');
define('COMPRE_MARKS_SCORED_MORE_THAN_MAX_MARKS','Compre marks scored more than max. marks');
define('REAPPEAR_EXAM_TAKEN','<u><b>Errors Occured:</b></u><br><br>1. Regular marks can not be updated because re-exam has been taken');
 
////Update student class/rollno module
 define('STUDENT_NEW_ROLL_NO',"Enter following (a-z,0-9,&-_./+,{}[]()) characters in New Roll No");
//extra class attendence conducted by faculty 
define('SELECT_TIME_TABLE','Select TimeTable');
define('SELECT_TEACHERS',"Select Teacher");
define('SELECT_SUBJECTS',"Select Subject");
define('SELECT_GROUP',"Select Group");

define('SELECT_BUS_STOP_CITY','Select Bus Stop City');

define('PENDING_FEE_MESSAGE','syenergy Blocked due to pending fees');

define('ONLINE_CONFIRM',"Please note that 1.5% of the total fee amount will be charged extra as convenience fee.\\n\\rClick Ok to proceed or Cancel");

?>
