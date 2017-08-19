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
define('DEPENDENCY_CONSTRAINT','Data could not be deleted due to records existing in linked tables');
define('DEPENDENCY_CONSTRAINT_EDIT','Data could not be edited due to records existing in linked tables');
define('FAILURE','Data could not be saved successfully');
define('MAIL_SENT','Mail has been sent successfully');
define('TECHNICAL_PROBLEM','Bad Request or Hacking attempt.');
define('ADD_MORE','Do you want to add more?');
define('DELETE_CONFIRM','Do you want to delete this record?');
define('DELETE_CONFIRM2','Do you want to delete records?');
define('RESTORE_CONFIRM','Do you want to restore this record?');
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
define('SELECT_CLASS','Select class');
define('SELECT_GROUP','Select group');
define("SELECT_STUDYPERIOD","Select Study Period");
define('SELECT_FEECYCLE','Select Fee Cycle');
define('SELECT_FEEHEAD','Select Fee Head');
define('SELECT_FEEFUNDALLOCATION','Select Fee Fund Allocation');
define('SELECT_FEECYCLEFINE','Select Fee Fine Type');
define("DATE_VALIDATION","To Date can not be smaller than From Date");
define("EMPTY_DATE_VALIDATION","Date fields can not be empty");
define("SELECT_ATLEASTONE_CHECKBOX","Please Select at least one checkbox");
define('SELECT_SUBJECT','Select subject');
define('SELECT_COURSE','Select Course');
define('SELECT_STUDY_PERIOD','Select study period');
define('ENTER_ALPHABETS_NUMERIC','Enter only alphabetical/numeric characters (a-z,0-9)');
define('SELECT_DESIGNATION','Select  a designation');
define('ENTER_PIN','Enter a PIN');
define('ENTER_VALID_EMAIL','Enter a valid email address');
define('ENTER_VALID_PHONE_NO','Enter a valid phone number');
define('EMPTY_FROM_DATE','From Date Can Not Be empty');
define('EMPTY_TO_DATE','To Date Can Not Be empty');
define('DATE_VALIDATION2','To Date Can Not be Greater Than Current Date');
define('FUTURE_DATE_VALIDATION','Date should be equal to or less then Current Date');
define('STUDENTS_WITH_ZERO_MARKS','Students with zero marks : ');

define('EMPTY_DATE_FROM','Date from can not be empty');
define('EMPTY_DATE_TO','Date to can not be empty');


// State Module
define('STATE_NOT_EXIST','This state does not exist');
define('ENTER_STATE_NAME','Enter name of state');
define('ENTER_STATE_CODE','Enter code of state');
define('STATE_NAME_LENGTH','State Name can not be less than 3 characters');
define('STATE_ALREADY_EXIST','The state code you entered already exists');
define('STATE_NAME_ALREADY_EXIST','The state name you entered already exists');



// CITY Module
define('CITY_NOT_EXIST','This City does not exist');
define('ENTER_CITY_NAME','Enter name of city');
define('ENTER_CITY_CODE','Enter code of city');
define('SELECT_STATE_NAME','Select a state');
define('CITY_NAME_LENGTH','City Name can not be less than 3 characters');
define('CITY_CODE_ALREADY_EXIST','The city code you entered already exists.');
define('CITY_NAME_ALREADY_EXIST','The city name you entered already exists.');



// QUOTA Module
define('QUOTA_NOT_EXIST','This Quota does not exist');
define('ENTER_QUOTA_NAME','Enter quota name');
define('ENTER_QUOTA_ABBR','Enter quota abbreviation');
define('QUOTA_NAME_LENGTH','Quota Name can not be less than 3 characters');
define('QUOTA_ALREADY_EXIST','The Quota Abbr. you entered already exists.');

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
define("SUBJECT_TYPE_ABBR_ALREADY_EXIST","Abbreviation already exists");          
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

define("ENTER_ATTENDANCE_NAME","Enter attendance name");
define("ENTER_ATTENDANCE_CODE","Enter code");
define("ENTER_ATTENDANCE_DESCRIPTION","Enter description");
define("ENTER_ATTENDANCE_PERCENTAGE","Enter percentage");
define("ENTER_ATTENDANCE_NUMBER","Enter percentage in numbers");
define("ATTENDANCE_EXISTS_SEPARATELY","Attendance already exists separately for selected dates");
define('INVALID_PERCENTAGE','Percentage can not more than 100 and less than 0');

// FEE HEAD Module
define("ENTER_FEEHEAD_NAME","Enter Head name");
define("ENTER_FEEHEAD_ABBR","Enter Head Abbrevation");
define('FEEHEAD_NAME_LENGTH','Fee Head Name can not be less than 3 characters.');
define('FEEHEAD_NAME_EXIST','Name already exist');
define('FEEHEAD_ABBR_EXIST','Abbreviation already exist');
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


// FEE CYCLE FINE Module

define("ENTER_FEECYCLEFINE_AMOUNT","Enter Fine Amount");
define("ENTER_FEECYCLEFINE_NUMBER","Enter only Number");
define("ENTER_FEECYCLEFINE_TYPE","Enter Fee Cycle Fine Type");
define("SELECT_FEECYCLE_FINE","Select Fee Cycle Fine");


// FEE CYCLE Module

define("ENTER_FEECYCLE_NAME","Enter cycle name");
define("ENTER_FEECYCLE_ABBR","Enter cycle abbreviation");
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
define("OLD_NOTICE","You can delete old notices");

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
define('ENTER_USER_ROLE','Enter Role name');
define('ENTER_EMPLOYEE_NAME','Enter name of employee');
define('ENTER_EMPLOYEE_CODE','Enter code of employee');
define('ENTER_EMPLOYEE_ABBR','Enter abbr. of employee');
define('CHOOSE_EMPLOYEE_DESIGNATION','Choose Designation');
define('CHOOSE_EMPLOYEE_BRANCH','Choose Branch');
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
define('ENTER_EMPLOYEE_ADDRESS','Enter Address');
define('SELECT_STATUS','Select Status');
define('SELECT_DESIGNATION','Select Designation');

// HOSTEL ROOM Module
define('HOSTELROOM_NOT_EXIST','This Hostel does not exist');
define('CHOOSE_HOSTEL_NAME','Select hostel name');
define('ENTER_HOSTELROOM_NAME','Enter name');
define('ENTER_HOSTELROOM_CAPACITY','Enter room capacity');
define('ENTER_HOSTELROOM_RENT','Enter room rent');
define('HOSTELROOM_NAME_EXIST','Hostel Name already exists.');
define('INVALID_HOSTELROOM','Invalid Hostel room');
define('HOSTEL_ROOM_TYPE_NO_VALUE','No room capacity & rent has been found against this room type');
define('ROOM_NOT_GREATER','Room(s) cannot be greater than total hostel room');
define('CAPACITY_NOT_GREATER','Capacity cannot be greater than total hostel room capacity');
define('ENTER_HOSTEL_ROOM_TYPE_NAME','Enter Room Type');
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
define('ENTER_CATEGORY','Enter Item Category');
define('ENTER_ABBR','Enter Abbreviation');
define('ITEM_CATEGORY_EXIST','Item Category Already Exists');
define('ABBR_EXIST','Entered Abbreviation Already Exists');
define('ENTER_ALPHABETS_NUMERIC2',"Enter following (a-z,A-Z,0-9,/,-) characters only");

//Resource Category Module
define('ENTER_RESOURCECATEGORY_NAME','Enter category name');
define('RESOURCE_CATEGORY_EXIST','Category Name already exist');
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

// Attendance Marks Slabs
define('SELECT_TIME_TABLE_LABEL','Select Time Table Label');
define('unfrozenSuccess','Selected class(es) unforzen successfully');
define('ENTER_REASON','Enter Reason');
define('ERROR_CGPA_NOT_CALCULATED','Class can not be frozen as CGPA is not calculated');

// Occupied/Free Class
define('SELECT_PERIODSLOT','Select Period Slot');
define('SELECT_PERIOD','Select Period');
define('SELECT_OPTION','Select Option');


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


// BUS Module
define('ENTER_BUS_NAME','Enter Bus Name');
define('ENTER_BUS_NO','Enter Registration No.');
define('SELECT_MAN_YEAR','Select Manufacturing Year');
define('BUS_NAME_LENGTH','Bus name can not be less than 3 characters');
define('BUS_NO_LENGTH','Bus no can not be less than 2 characters');
define('BUS_ALREADY_EXIST','This Bus already exists');
define('BUS_NO_ALREADY_EXIST','This Registation No. already exists');
define('BUS_NOT_EXIST','This Bus does not exist');

define('ENTER_BUS_MODEL_NO','Enter model no of the bus');
define('ENTER_BUS_CAPACITY','Enter seating capacity of the bus');
define('BUS_MODEL_LENGTH','Model no can not be less than 3 characters');
define('BUS_CAPACITY_RESTRICTION','Seating capacity of the bus can not be zero');
define('SELECT_INSURANCE_DATE','Select insurance date');
define('INSURANCE_DATE_VALIDATION','Insurance due date can not be less than current date');
define('INSURANCE_REMINDER_RESTRICTION','Please uncheck insurance reminder as no insuring company is entered');

define('BUS_PURCHASE_DATE_EMPTY','Select purchase date');
define('BUS_PURCHASE_DATE_VALIDATION','Purchase date can not be greater than current date');
define('SELECT_INSURANCE_DATE','Select insurance date');

// BUS Repair Module
define('SELECT_BUS_NAME','Select Bus Registration No.');
define('SELECT_STUFF','Select staff name');
define('ENTER_SERVICE_REASON','Enter Reason for service');
define('ENTER_SERVICE_COST','Enter service cost');
define('SERVICE_REASON_LENGTH','Reason for service cannot be less than 3 characters');
define('ENTER_COST_NUM','Enter valid value for service cost');
define('SERVICE_DATE_VALIDATION','Service date cannot be greater than current date');
define('BUS_REPAIR_ALREADY_EXIST','This bus repair record already exists');
define('BUS_REPAIR_NOT_EXIST','This bus repair record does not exist');

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
define('ENTER_SCHEDULE_TIME','Enter Pick Up Time');
define('ENTER_SCHEDULE_TIME_END','Enter Drop Time');
define('ENTER_SCHEDULE_TIME_NUM','Enter Pick Up Time in HH:MM:SS format');
define('ENTER_SCHEDULE_TIME_END_NUM','Enter Drop Time in HH:MM:SS format');
define('ENTER_ONE_TRANSPORT_CHARGES','Enter one way transport charge');
define('ENTER_TWO_TRANSPORT_CHARGES','Enter two way transport charge');
define('STOP_NAME_LENGTH','Stop Name can not be less than 3 characters');
define('STOP_ALREADY_EXIST','The Stop you entered already exists');
define('ENTER_ONE_WAY_TO_NUM','Enter numeric value for one way transport charge');
define('ENTER_TWO_WAY_TO_NUM','Enter numeric value for two way transport charge');
define('CITY_NOT_EXIST','City does not exist in Bus City Wise Charges');
define('ENTER_TRANSPORT_CHARGES','Enter transport charges');
define('SELECT_BUS_ROUTE_CODE','Select Bus Route');


// BUS City Wise Charges Module
define('SELECT_STATE','Select state');
define('SELECT_CITY','Select city');
define('BUS_CITY_ALREADY_EXIST','This city already exists');



// BUS ROUTE Module
define('BUSROUTE_NOT_EXIST','This Bus Route does not exist');
define('ENTER_ROUTE_NAME','Enter route name');
define('ENTER_ROUTE_CODE','Enter route code');
define('ROUTE_NAME_LENGTH','Route Name can not be less than 3 characters');
define('ROUTE_ALREADY_EXIST','The Route code you entered already exists.');
define('ROUTE_CHARGES_NOT_ZERO','Route charges should not less than zero or zero.');
define('ENTER_ROUTE_CHARGES','Enter route charges');



// Transport Master Module
define('ENTER_STUFF_NAME','Enter staff name');
define('ENTER_STUFF_CODE','Enter staff code');
define('ENTER_DRIVING_LICENSE','Enter driving license');
define('ENTER_DRIVING_LICENSE_AUTHORITY','Enter driving licence issuing authority');
define('SELECT_STUFF_TYPE','Select staff type');
define('STUFF_NAME_LENGTH','Staff name can not be less than three characters');
define('STUFF_CODE_LENGTH','Staff code can not be less than two characters');
define('DL_LENGTH','Driving license can not be less than four characters');
define('JOINING_DATE_VALIDATION','Date of joining can not be greater than current date');
define('STUFF_CODE_ALREADY_EXIST','This staff code already exists');
define('STUFF_NOT_EXIST','This staff does not exist');
define('LEAVING_DATE_VALIDATION1','Leaving date can not be smaller than joining date');
define('LEAVING_DATE_VALIDATION2','Leaving date can not be greater than current date');

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
define('SELECT_BUILDING','Select a Building');


// CALENDER Module
define('EVENT_NOT_EXIST','This Event does not exist');
define('ENTER_EVENT_TITLE','Enter event title');
define('ENTER_SHORT_DESC','Enter short description');
define('ENTER_LONG_DESC','Enter long description');
define('EVENT_TITLE_LENGTH','Event Title can not be less than 3 characters');
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
define('SELECT_TIME_TABLE','Select time table');
define('SELECT_ATTENDANCE_CODE','Select duty leave type');
define('DUPLICATE_DUTY_LEAVE_DATE_RESTRICTION','More than one duty leave is not allowed on a single day');
define('DUPLICATE_DUTY_LEAVE_DATE_RESTRICTION2','Date cannot be greater than current date');
define('DUTY_LEAVES_GIVEN','Duty Leaves Taken ');
define('TIME_TABLE_LABEL_NOT_FOUND','Timetable label not found for this class');
define('EMPTY_DUTY_LEAVE','Duty leaves cannot be blank ');
define('ENTER_DUTY_LEAVE_IN_NUMERIC','Enter numeric values ');
define('DUTY_LEAVE_RESTRICTION','Sum of lecture attended and duty leaves cannot be greater than lecture delivered ');


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


// TIME TABLE LABEL Module
define('LABEL_NOT_EXIST','This Label does not exist');
define('ENTER_LABEL_NAME','Enter Label name');
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
define("STUENT_ROLL_NO_EMPTY","Student roll no is empty");
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
define("SELECT_ADV_LABEL_NAME","Select label");
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
define('LEAVE_TYPE_ALREADY_EXIST','This leave already exists'); 
define('LEAVE_TYPE_NOT_EXIST','This leave does not exist'); 

  //Leave Detail
  
define("ENTER_LEAVE_VALUE","Enter leave value");
define("SELECT_LEAVE_TYPE","Enter leave type ");
define("SELECT_FINANCIAL_YEAR","Enter financial year ");
define('ENTER_LEAVE_VALUE_TO_NUM','Enter numeric value for leave value'); 
define('LEAVE_VALUE_ALREADY_EXIST','This leave value already exists'); 
define('LEAVE_VALUE_NOT_EXIST','This leave value does not exist'); 



// Student Enquiry Module
define('ENTER_STUDENT_FIRST_NAME','Enter first name ');
define('ENTER_STUDENT_CONTACT_NO','Enter contact no');
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
define('ENTER_BANK_ABBR',            'Enter Bank Abbreviation');
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
define('ACTIVE_SESSION_EXISTS','Only one session can be active');
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
define('_FOR_SUBJECT_'," for Subject: ");
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




//for histogram
define('SELECT_HISTOGRAM',"Select Histogram");

//for grade
define('ENTER_GRADE_LABEL','Enter Grade');
define('ENTER_GRADE_POINTS','Enter Grade Points');            
define('GRADE_RANGE_POINTS','Enter Grade Points Between 0 to 127 ');            
define('GRADE_ALREADY_EXIST','Grade already exists');
define('GRADE_NOT_EXIST','This grade does not exist');

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
define('SUBJECT_ABBR_ALREADY_EXIST','The abbrevation already exists.');     
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

define("NO_PRACTICAL_GROUP_SELECTED","No practical group selected");
define("MORE_THAN_ONE_PRACTICAL_GROUP_SELECTED","More than one practical group selected");
define("PRACTICAL_GROUP_NOT_RELATED_TO_THEORY","Enter Subject Topic");

define("ERROR_WHILE_SAVING_ATTENDANCE_FOR_","Error while saving attendance for ");
define("ERROR_WHILE_QUARANTINING_ATTENDANCE_FOR_","Error while quarantining attendance for ");
define("ERROR_WHILE_DELETING_ATTENDANCE_FOR_","Error while deleting attendane for ");
define("ERROR_WHILE_SAVING_MARKS_FOR_","Error while saving marks for ");
define("_FOR_TEST_"," for test ");
define("ERROR_WHILE_QUARANTINING_MARKS_FOR_","Error while quarantining marks for ");
define("ERROR_WHILE_DELETING_MARKS_FOR_","Error while deleting marks for ");
define("ERROR_WHILE_UPDATING_STUDENT_GROUP_","Error while updating student group ");


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
define('INVALID_SEMINAR_FEE','Enter integer value for seminar fee');  

//Consulting Message
define('ENTER_COUNSULTING_PROJECTNAME','Enter Project Name');
define('ENTER_COUNSULTING_SPONSOR','Enter Sponsor');
define('ENTER_COUNSULTING_START_DATE','Select Start Date');
define('ENTER_COUNSULTING_END_DATE','Select End Date');
define('ENTER_COUNSULTING_AMOUNT','Enter Amount');
define('ENTER_COUNSULTING_REMARKS','Enter Remarks');
define('INVALID_COUNSULTING_AMOUNT','Enter integer value for amount funding');  
define('COUNSULTING_NOT_EXIST','Invalid Counsulting Id');
  

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
define('ACCEPT_WORKSHOP_INTEGER','Enter integer value for attendees');
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
define('ENTER_FINE_AMOUNT','Enter Fine Amount');  
define('ENTER_FINE_REASON','Select Fine Reason');  
define('ENTER_FINE_AMOUNT_TO_NUM','Enter numeric value fine amount');  
define('FINE_REASON_LENGTH','Fee reason length cannot be less than 10 characters');  
define('FINE_DATE_VALIDATION','Fine Date cannot be greater than current date');  
define('FINE_ALREADY_EXIST','Student fine already exists');  
define('ENTER_FINE_CATEGORY','Select Fine Category');  
define('FINE_ALREADY_PAID','Record cannot be edited as it has already been paid');  

// Subject Category Module                                                    
define('ENTER_SUBJECT_CATEGORY','Enter category name');
define('ENTER_SUBJECT_CATEGORY_ABBR','Enter abbreviation');
define('SUBJECT_CATEGORY_EXIST','Category name already exist');
define('SUBJECT_CATEGORY_ABBR_EXIST','Abbreviation already exist');
define('PARENT_CATEGORY_ITSELF','Parent category cannot be parent of itself');
define('SUBJECT_CATEGORY_NOT_EXIST','Category does not exist');    
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
define("OPTION_VALUE_GREATER_ZERO","Option value should be in between 0 to 1000");
define("ANSWER_OPTIONS_PRINT_ORDER_VALIDATIONS","Print order cannot be less than zero or greater than 100");
define("NUMERIC_PRINT_ORDER_VALIDATIONS","Enter only numeric value for option weight");

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
define("REASON_LENGTH_EXCEEDS","Reason length should not exceed 80 chars");

//Student Attendance Performance Report
define("INVALID_ATTENDANCE_RANGE","Invalid attendance range");
define('ENTER_NUMERIC_VALUE_FOR_ATTENDANCE_RANGE',"Enter numeric value"); 


//Student Concessoin Module
define("PERCENTAGE_WISE_MAX_VALUE_CHECK","For percentage wise concession value cannot be greater than 100");
define('TOTAL_FEES_WISE_MAX_VALUE_CHECK',"Concession amount cannot be greater than total fees"); 
define('ENTER_CONCESSION_REASON',"Enter reason for concession"); 
define('STUDENT_CONCESSION_GIVEN',SUCCESS); 
       
    
// $History: messages.inc.php $         
//
//*****************  Version 324  *****************
//User: Dipanjan     Date: 20/04/10   Time: 18:30
//Updated in $/LeapCC/Library
//Added check for CLIENT_INSTITUTES
//
//*****************  Version 323  *****************
//User: Jaineesh     Date: 4/17/10    Time: 5:29p
//Updated in $/LeapCC/Library
//fixed bug nos.0003287, 0003286, 0003290
//
//*****************  Version 322  *****************
//User: Jaineesh     Date: 4/15/10    Time: 12:53p
//Updated in $/LeapCC/Library
//fixed bug no.0003247
//
//*****************  Version 321  *****************
//User: Jaineesh     Date: 4/09/10    Time: 10:30a
//Updated in $/LeapCC/Library
//fixed bug nos.0003243,0003248
//
//*****************  Version 320  *****************
//User: Abhiraj      Date: 4/07/10    Time: 4:34p
//Updated in $/LeapCC/Library
//added error messages for payroll module
//
//*****************  Version 320  *****************
//User: Abhiraj     Date: 7/04/10    Time: 04:27p
//Updated in $/LeapCC/Library
//payroll module add financial year error messages
//
//*****************  Version 319  *****************
//User: Jaineesh     Date: 4/06/10    Time: 12:28p
//Updated in $/LeapCC/Library
//put new link occupied/free classes/rooms report and messages
//
//*****************  Version 318  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Library
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 317  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:29p
//Updated in $/LeapCC/Library
//changes for gap analysis in employee master
//
//*****************  Version 316  *****************
//User: Rajeev       Date: 10-03-27   Time: 10:42a
//Updated in $/LeapCC/Library
//resolved bug no 0002941
//
//*****************  Version 315  *****************
//User: Rajeev       Date: 10-03-26   Time: 2:06p
//Updated in $/LeapCC/Library
//updated with fees messages
//
//*****************  Version 314  *****************
//User: Parveen      Date: 3/10/10    Time: 3:07p
//Updated in $/LeapCC/Library
//student enquiry message added
//
//*****************  Version 313  *****************
//User: Parveen      Date: 3/10/10    Time: 2:50p
//Updated in $/LeapCC/Library
//CURRENT_DATE_CHECK message added
//
//*****************  Version 312  *****************
//User: Parveen      Date: 3/05/10    Time: 4:16p
//Updated in $/LeapCC/Library
//APPLICATION_NO_EXIST message added
//
//*****************  Version 311  *****************
//User: Parveen      Date: 3/05/10    Time: 1:06p
//Updated in $/LeapCC/Library
//COMP_EXAM_ROLLNO_EXIST message added
//
//*****************  Version 310  *****************
//User: Parveen      Date: 2/25/10    Time: 5:53p
//Updated in $/LeapCC/Library
//Admission Module message added
//
//*****************  Version 309  *****************
//User: Parveen      Date: 2/24/10    Time: 11:44a
//Updated in $/LeapCC/Library
//SELECT_ROLE message updated
//
//*****************  Version 308  *****************
//User: Parveen      Date: 2/24/10    Time: 11:42a
//Updated in $/LeapCC/Library
//SELECT_ROLE message updated
//
//*****************  Version 307  *****************
//User: Dipanjan     Date: 19/02/10   Time: 14:22
//Updated in $/LeapCC/Library
//Done Bug fixing.
//Bug ids---
//0002910,0002909,0002907,
//0002906,0002904,0002908,
//0002905
//
//*****************  Version 306  *****************
//User: Rajeev       Date: 10-02-19   Time: 11:37a
//Updated in $/LeapCC/Library
//added admit student messages
//
//*****************  Version 305  *****************
//User: Jaineesh     Date: 2/17/10    Time: 7:24p
//Updated in $/LeapCC/Library
//fixed bug nos. 0002885, 0002887, 0002886, 0002888, 0002889 and add time
//table filter also
//
//*****************  Version 304  *****************
//User: Jaineesh     Date: 2/17/10    Time: 12:35p
//Updated in $/LeapCC/Library
//provide the facility to change institute of an employee
//
//*****************  Version 303  *****************
//User: Ajinder      Date: 2/16/10    Time: 1:13p
//Updated in $/LeapCC/Library
//done changes FCNS No. 1298
//
//*****************  Version 302  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 6:51p
//Updated in $/LeapCC/Library
//made enhancements under feedback module
//
//*****************  Version 301  *****************
//User: Dipanjan     Date: 6/02/10    Time: 17:57
//Updated in $/LeapCC/Library
//updated message for "Question Mapping Module(Adv. Feedback)"
//
//*****************  Version 300  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 5:56p
//Updated in $/LeapCC/Library
//added messages under feedback module
//
//*****************  Version 299  *****************
//User: Gurkeerat    Date: 2/05/10    Time: 6:53p
//Updated in $/LeapCC/Library
//resolved issue 0002015
//
//*****************  Version 298  *****************
//User: Gurkeerat    Date: 2/03/10    Time: 6:06p
//Updated in $/LeapCC/Library
//resolved issue 0002642,0002689,0002015,0002666
//
//*****************  Version 297  *****************
//User: Dipanjan     Date: 1/02/10    Time: 16:32
//Updated in $/LeapCC/Library
//Added "Class->Group->Subject->Teacher" mapping module for "Adv.
//Feedback Modules"
//
//*****************  Version 296  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Library
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 295  *****************
//User: Dipanjan     Date: 25/01/10   Time: 14:23
//Updated in $/LeapCC/Library
//Corrected messages
//
//*****************  Version 293  *****************
//User: Dipanjan     Date: 22/01/10   Time: 17:06
//Updated in $/LeapCC/Library
//Made UI changes and modified images
//
//*****************  Version 292  *****************
//User: Dipanjan     Date: 22/01/10   Time: 13:30
//Updated in $/LeapCC/Library
//Added messages for "Provide Feedback" module
//
//*****************  Version 291  *****************
//User: Jaineesh     Date: 1/21/10    Time: 1:59p
//Updated in $/LeapCC/Library
//fixed bug nos. 0002672, 0002660, 0002657, 0002656, 0002658, 0002659,
//0002661, 0002662
//
//*****************  Version 290  *****************
//User: Gurkeerat    Date: 1/20/10    Time: 5:05p
//Updated in $/LeapCC/Library
//Resolved issues:0002615,0002635,0002600,0002601,0002614
//
//*****************  Version 289  *****************
//User: Parveen      Date: 1/19/10    Time: 6:27p
//Updated in $/LeapCC/Library
//student inteneral reappear messages added
//
//*****************  Version 288  *****************
//User: Gurkeerat    Date: 1/15/10    Time: 11:02a
//Updated in $/LeapCC/Library
//Added messages under questions master
//
//*****************  Version 287  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:31p
//Updated in $/LeapCC/Library
//Added messages under advanced feedback module
//
//*****************  Version 286  *****************
//User: Dipanjan     Date: 12/01/10   Time: 16:13
//Updated in $/LeapCC/Library
//Added messages for "Feedback Label Module (Advanced)"
//
//*****************  Version 285  *****************
//User: Dipanjan     Date: 12/01/10   Time: 12:29
//Updated in $/LeapCC/Library
//Added messages for "Question Set Master"  module
//
//*****************  Version 284  *****************
//User: Dipanjan     Date: 12/01/10   Time: 10:55
//Updated in $/LeapCC/Library
//Added messages for "Feedback Question Mapping (Advanced)" module
//
//*****************  Version 283  *****************
//User: Dipanjan     Date: 9/01/10    Time: 16:48
//Updated in $/LeapCC/Library
//Added messages  "Advanced Feedback Category Module"
//
//*****************  Version 282  *****************
//User: Parveen      Date: 12/29/09   Time: 4:16p
//Updated in $/LeapCC/Library
//SLAB_DELETE_SUCCESSFULLY message updated
//
//*****************  Version 281  *****************
//User: Dipanjan     Date: 29/12/09   Time: 13:36
//Updated in $/LeapCC/Library
//Added messages and menu for "Attendance Set Master" module
//
//*****************  Version 280  *****************
//User: Parveen      Date: 12/29/09   Time: 1:14p
//Updated in $/LeapCC/Library
//SELECT_ATTENDANCE_SET  message added
//
//*****************  Version 279  *****************
//User: Ajinder      Date: 12/28/09   Time: 6:44p
//Updated in $/LeapCC/Library
//added check in marks transfer, to stop process if class is frozen.
//
//*****************  Version 278  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:42p
//Updated in $/LeapCC/Library
//done changes to make new module for marks transfer
//
//*****************  Version 277  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:17p
//Updated in $/LeapCC/Library
//modified in message for employee
//
//*****************  Version 276  *****************
//User: Ajinder      Date: 12/26/09   Time: 6:16p
//Updated in $/LeapCC/Library
//released for time being for jaineesh
//
//*****************  Version 275  *****************
//User: Parveen      Date: 12/24/09   Time: 4:04p
//Updated in $/LeapCC/Library
//EMPTY_DATE_FROM, EMPTY_DATE_TO  messages updated
//
//*****************  Version 274  *****************
//User: Parveen      Date: 12/24/09   Time: 3:44p
//Updated in $/LeapCC/Library
//alignment & format updated
//
//*****************  Version 273  *****************
//User: Dipanjan     Date: 24/12/09   Time: 14:59
//Updated in $/LeapCC/Library
//Corrected messages
//
//*****************  Version 272  *****************
//User: Gurkeerat    Date: 12/24/09   Time: 12:06p
//Updated in $/LeapCC/Library
//added message SELECT_TEACHER
//
//*****************  Version 271  *****************
//User: Dipanjan     Date: 23/12/09   Time: 19:15
//Updated in $/LeapCC/Library
//Done group coping module
//
//*****************  Version 270  *****************
//User: Parveen      Date: 12/23/09   Time: 12:09p
//Updated in $/LeapCC/Library
//is_write function added
//
//*****************  Version 269  *****************
//User: Dipanjan     Date: 18/12/09   Time: 14:02
//Updated in $/LeapCC/Library
//Done bug fixing.
//Bug ids---
//0002295,0002296
//
//*****************  Version 268  *****************
//User: Jaineesh     Date: 12/17/09   Time: 5:24p
//Updated in $/LeapCC/Library
//Change in coding during class has been frozen if no marks has been
//transferred of class.
//
//*****************  Version 267  *****************
//User: Dipanjan     Date: 17/12/09   Time: 15:56
//Updated in $/LeapCC/Library
//Added messages for "Freeze" class i
//
//*****************  Version 266  *****************
//User: Gurkeerat    Date: 12/09/09   Time: 11:25a
//Updated in $/LeapCC/Library
//resolved issues 0001970,0002228,0002229,0002230,0002231,0002233
//
//*****************  Version 265  *****************
//User: Gurkeerat    Date: 12/08/09   Time: 3:32p
//Updated in $/LeapCC/Library
//resolved issue 0002216,0002211,0002214,0002215,0002217,0002220,0002221,
//0002222,0002223,0002224,0002225,0002226,0002227,0002218
//
//*****************  Version 264  *****************
//User: Dipanjan     Date: 3/12/09    Time: 11:02
//Updated in $/LeapCC/Library
//Made UI related changes :  Added alert for unsaved data
//
//*****************  Version 263  *****************
//User: Dipanjan     Date: 26/11/09   Time: 17:37
//Updated in $/LeapCC/Library
//Done enhancements in "Subject Wise Performance" report
//
//*****************  Version 262  *****************
//User: Rajeev       Date: 09-11-25   Time: 1:43p
//Updated in $/LeapCC/Library
//fixed bug no 2126,2127,2128,2129,2130,2131,2132,2133,2134,2135
//
//*****************  Version 261  *****************
//User: Jaineesh     Date: 11/20/09   Time: 1:56p
//Updated in $/LeapCC/Library
//modification in code to show exact values
//
//*****************  Version 260  *****************
//User: Parveen      Date: 11/19/09   Time: 2:37p
//Updated in $/LeapCC/Library
//message updated SELECT_CRITERIA
//
//*****************  Version 259  *****************
//User: Dipanjan     Date: 19/11/09   Time: 12:53
//Updated in $/LeapCC/Library
//Completed/modified "Duty Leaves" module in admin section
//
//*****************  Version 258  *****************
//User: Dipanjan     Date: 18/11/09   Time: 13:12
//Updated in $/LeapCC/Library
//Modified Duty Leaves module in admin section
//
//*****************  Version 257  *****************
//User: Rajeev       Date: 09-11-17   Time: 12:31p
//Updated in $/LeapCC/Library
//fixed 2032,2033 bugs
//
//*****************  Version 256  *****************
//User: Ajinder      Date: 11/17/09   Time: 12:26p
//Updated in $/LeapCC/Library
//added messages for marks transfer
//
//*****************  Version 254  *****************
//User: Ajinder      Date: 11/12/09   Time: 5:34p
//Updated in $/LeapCC/Library
//changed image to show time table.gif
//
//*****************  Version 253  *****************
//User: Rajeev       Date: 09-11-11   Time: 6:27p
//Updated in $/LeapCC/Library
//Added issue and payable bank id as per new requirement
//
//*****************  Version 252  *****************
//User: Rajeev       Date: 09-11-11   Time: 3:50p
//Updated in $/LeapCC/Library
//added validations on domicile and nationality
//
//*****************  Version 251  *****************
//User: Ajinder      Date: 11/11/09   Time: 11:48a
//Updated in $/LeapCC/Library
//done coding related to extra classes time table
//
//*****************  Version 250  *****************
//User: Dipanjan     Date: 6/11/09    Time: 16:48
//Updated in $/LeapCC/Library
//Added "Attendance History" option in bulk attendance from admin section
//
//*****************  Version 249  *****************
//User: Rajeev       Date: 09-11-06   Time: 3:54p
//Updated in $/LeapCC/Library
//In this if wrong roll no was entered then validations was not working
//during SAVE done in both admin and teacher login
//
//*****************  Version 248  *****************
//User: Ajinder      Date: 11/06/09   Time: 3:48p
//Updated in $/LeapCC/Library
//added define for marks transfer
//
//*****************  Version 247  *****************
//User: Rajeev       Date: 09-11-06   Time: 12:09p
//Updated in $/LeapCC/Library
//Updated with Paging Parameter in config management
//
//*****************  Version 246  *****************
//User: Jaineesh     Date: 11/05/09   Time: 5:33p
//Updated in $/LeapCC/Library
//fixed bug nos.0001936,0001938,0001939
//
//*****************  Version 245  *****************
//User: Dipanjan     Date: 2/11/09    Time: 15:54
//Updated in $/LeapCC/Library
//Added messages for "Group Wise Performance Report" in teacher end
//
//*****************  Version 244  *****************
//User: Ajinder      Date: 11/02/09   Time: 11:53a
//Updated in $/LeapCC/Library
//done changes to improve messaging. added code to update time table
//adjustment to update entries with new time table Id.
//
//*****************  Version 243  *****************
//User: Jaineesh     Date: 11/02/09   Time: 10:34a
//Updated in $/LeapCC/Library
//put new functions and messages for move copy time table
//
//*****************  Version 242  *****************
//User: Dipanjan     Date: 29/10/09   Time: 13:43
//Updated in $/LeapCC/Library
//Added messages for "Test wise performance comparison" report
//
//*****************  Version 241  *****************
//User: Ajinder      Date: 10/29/09   Time: 1:40p
//Updated in $/LeapCC/Library
//added messages for time table conflicts
//
//*****************  Version 240  *****************
//User: Jaineesh     Date: 10/28/09   Time: 3:19p
//Updated in $/LeapCC/Library
//put new messages for move time table
//
//*****************  Version 239  *****************
//User: Dipanjan     Date: 28/10/09   Time: 13:58
//Updated in $/LeapCC/Library
//Added messages for "Test wise perforamance report" module
//
//*****************  Version 238  *****************
//User: Ajinder      Date: 10/26/09   Time: 11:38a
//Updated in $/LeapCC/Library
//done changes for taking care of adjustment.
//
//*****************  Version 237  *****************
//User: Jaineesh     Date: 10/22/09   Time: 6:37p
//Updated in $/LeapCC/Library
//fixed bug Nos.0001858, 0001872, 0001870, 0001868, 0001867, 0001865,
//0001856, 0001866
//
//*****************  Version 236  *****************
//User: Dipanjan     Date: 22/10/09   Time: 13:19
//Updated in $/LeapCC/Library
//Added code "time table adjustment cancellation"
//
//*****************  Version 235  *****************
//User: Jaineesh     Date: 10/22/09   Time: 1:10p
//Updated in $/LeapCC/Library
//modified in messages for bus route & bus stop
//
//*****************  Version 234  *****************
//User: Jaineesh     Date: 10/21/09   Time: 6:50p
//Updated in $/LeapCC/Library
//Fixed bug nos. 0001822, 0001823, 0001824, 0001847, 0001850, 0001825
//
//*****************  Version 233  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:11
//Updated in $/LeapCC/Library
//Done bug fixing.
//Bug ids---
//00001812
//
//*****************  Version 232  *****************
//User: Jaineesh     Date: 10/20/09   Time: 11:17a
//Updated in $/LeapCC/Library
//modification in message during non selection of group in HOD role
//
//*****************  Version 231  *****************
//User: Dipanjan     Date: 13/10/09   Time: 9:35
//Updated in $/LeapCC/Library
//Added messages for "Swap time table records" module
//
//*****************  Version 230  *****************
//User: Parveen      Date: 10/12/09   Time: 11:22a
//Updated in $/LeapCC/Library
//DATE_CONDITION1 message updated
//
//*****************  Version 229  *****************
//User: Parveen      Date: 10/12/09   Time: 11:21a
//Updated in $/LeapCC/Library
//DATE_CONDITION1 message added
//
//*****************  Version 228  *****************
//User: Gurkeerat    Date: 10/08/09   Time: 5:08p
//Updated in $/LeapCC/Library
//resolved issue 1605
//
//*****************  Version 227  *****************
//User: Ajinder      Date: 10/08/09   Time: 3:12p
//Updated in $/LeapCC/Library
//done changes for group assignment (advanced)
//
//*****************  Version 226  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Library
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 225  *****************
//User: Jaineesh     Date: 10/07/09   Time: 4:58p
//Updated in $/LeapCC/Library
//fixed bug nos.0001727, 0001725, 0001724, 0001723, 0001721, 0001720,
//0001719, 0001718, 0001729
//
//*****************  Version 224  *****************
//User: Jaineesh     Date: 10/05/09   Time: 6:31p
//Updated in $/LeapCC/Library
//fixed bug nos.0001684, 0001689, 0001688, 0001687, 0001685, 0001686,
//0001683, 0001629 and report for academic head privileges
//
//*****************  Version 223  *****************
//User: Jaineesh     Date: 10/03/09   Time: 12:23p
//Updated in $/LeapCC/Library
//fixed bug nos.0001664, 0001665, 0001666
//
//*****************  Version 222  *****************
//User: Ajinder      Date: 10/01/09   Time: 4:34p
//Updated in $/LeapCC/Library
//added define for 'Please select all values'
//
//*****************  Version 221  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Library
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 220  *****************
//User: Ajinder      Date: 9/30/09    Time: 5:10p
//Updated in $/LeapCC/Library
//added messages for new time table.
//
//*****************  Version 219  *****************
//User: Jaineesh     Date: 9/30/09    Time: 4:16p
//Updated in $/LeapCC/Library
//put new messages for role to class module
//
//*****************  Version 218  *****************
//User: Gurkeerat    Date: 9/24/09    Time: 5:35p
//Updated in $/LeapCC/Library
//resolved issue 1597
//
//*****************  Version 217  *****************
//User: Parveen      Date: 9/24/09    Time: 10:56a
//Updated in $/LeapCC/Library
//task Message added
//
//*****************  Version 216  *****************
//User: Jaineesh     Date: 9/24/09    Time: 10:28a
//Updated in $/LeapCC/Library
//put new messages for HOD role
//
//*****************  Version 215  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Library
//fixed bugs during self testing
//
//*****************  Version 214  *****************
//User: Dipanjan     Date: 9/03/09    Time: 12:20p
//Updated in $/LeapCC/Library
//Gurkeerat: resolved issue 1394
//
//*****************  Version 212  *****************
//User: Rajeev       Date: 09-09-03   Time: 11:45a
//Updated in $/LeapCC/Library
//added Fee collection message
//
//*****************  Version 211  *****************
//User: Jaineesh     Date: 9/03/09    Time: 11:44a
//Updated in $/LeapCC/Library
//modify in message for student fine approval report
//
//*****************  Version 210  *****************
//User: Jaineesh     Date: 9/01/09    Time: 7:23p
//Updated in $/LeapCC/Library
//fixed bug nos.0001374, 0001375, 0001376, 0001379, 0001373
//
//*****************  Version 209  *****************
//User: Jaineesh     Date: 8/31/09    Time: 7:33p
//Updated in $/LeapCC/Library
//fixed bug nos. 0001366, 0001358, 0001305, 0001304, 0001282
//
//*****************  Version 208  *****************
//User: Dipanjan     Date: 8/31/09    Time: 6:16p
//Updated in $/LeapCC/Library
//Gurkeerat: resolved issue 1367
//
//*****************  Version 207  *****************
//User: Jaineesh     Date: 8/31/09    Time: 6:12p
//Updated in $/LeapCC/Library
//put message for file upload
//
//*****************  Version 206  *****************
//User: Parveen      Date: 8/31/09    Time: 1:57p
//Updated in $/LeapCC/Library
//FILE_NOT_UPLOAD function added
//
//*****************  Version 205  *****************
//User: Dipanjan     Date: 31/08/09   Time: 13:27
//Updated in $/LeapCC/Library
//Added messages for "Room Allocation Master " module
//
//*****************  Version 204  *****************
//User: Jaineesh     Date: 8/31/09    Time: 12:32p
//Updated in $/LeapCC/Library
//correct message in hostel room 
//
//*****************  Version 203  *****************
//User: Jaineesh     Date: 8/31/09    Time: 10:27a
//Updated in $/LeapCC/Library
//modified in message for hostel room type
//
//*****************  Version 202  *****************
//User: Dipanjan     Date: 28/08/09   Time: 13:14
//Updated in $/LeapCC/Library
//Done bug fixing.
//Bug ids---
//00001337,00001336,00001335,00001334,
//00001332,00001333,00001339,00001265,
//00001267,00001257,00001256,00001266,
//00001232,00001231
//
//*****************  Version 201  *****************
//User: Dipanjan     Date: 8/28/09    Time: 11:59a
//Updated in $/LeapCC/Library
//Gurkeerat: resolved issue 1331,1330
//
//*****************  Version 199  *****************
//User: Parveen      Date: 8/28/09    Time: 11:39a
//Updated in $/LeapCC/Library
//HOSTEL_ALREADY_EXIST message updated
//
//*****************  Version 198  *****************
//User: Dipanjan     Date: 8/26/09    Time: 12:49p
//Updated in $/LeapCC/Library
//Gurkeerat: fixed issue 1251,1250,1249
//
//*****************  Version 197  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Library
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 196  *****************
//User: Jaineesh     Date: 8/24/09    Time: 5:36p
//Updated in $/LeapCC/Library
//fixed bug no.0001222
//
//*****************  Version 195  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Library
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 194  *****************
//User: Dipanjan     Date: 8/19/09    Time: 6:38p
//Updated in $/LeapCC/Library
//Gurkeerat: resolved issue 1152,1151
//
//*****************  Version 193  *****************
//User: Dipanjan     Date: 8/19/09    Time: 11:13a
//Updated in $/LeapCC/Library
//Gurkeerart: fixed issue 1140
//
//*****************  Version 192  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Library
//Remove administrator role from role type so that no new administrator
//can be made and syenergy will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 191  *****************
//User: Jaineesh     Date: 8/17/09    Time: 7:34p
//Updated in $/LeapCC/Library
//fixed bug nos.0001093, 0001086, 0000672, 0001087
//
//*****************  Version 190  *****************
//User: Jaineesh     Date: 8/17/09    Time: 12:27p
//Updated in $/LeapCC/Library
//change in message select class instead of degree
//
//*****************  Version 189  *****************
//User: Jaineesh     Date: 8/17/09    Time: 12:25p
//Updated in $/LeapCC/Library
//show classes in drop down instead of degree, batch & study period
//
//*****************  Version 188  *****************
//User: Ajinder      Date: 8/14/09    Time: 4:40p
//Updated in $/LeapCC/Library
//added messages for if no group exists from selected group type while
//assigning groups.
//
//*****************  Version 187  *****************
//User: Dipanjan     Date: 14/08/09   Time: 13:05
//Updated in $/LeapCC/Library
//Added messages for "room" master
//
//*****************  Version 186  *****************
//User: Jaineesh     Date: 8/13/09    Time: 4:55p
//Updated in $/LeapCC/Library
//fixed bug nos.0000932,0000544,0000550,0000549,0000949
//
//*****************  Version 185  *****************
//User: Jaineesh     Date: 8/12/09    Time: 7:27p
//Updated in $/LeapCC/Library
//fixed bug nos. 0000969, 0000965, 0000962, 0000963, 0000980, 0000950
//
//*****************  Version 184  *****************
//User: Dipanjan     Date: 8/12/09    Time: 6:10p
//Updated in $/LeapCC/Library
//Gurkeerat: fixed issue 1023
//
//*****************  Version 183  *****************
//User: Parveen      Date: 8/12/09    Time: 3:24p
//Updated in $/LeapCC/Library
//INVALID_COUNSULTING_AMOUNT message updated
//
//*****************  Version 182  *****************
//User: Parveen      Date: 8/11/09    Time: 2:29p
//Updated in $/LeapCC/Library
//SUBJECT_CATEGORY new message added
//
//*****************  Version 181  *****************
//User: Parveen      Date: 8/10/09    Time: 5:28p
//Updated in $/LeapCC/Library
//formating, validation updated
//issue fix 994, 9943, 992, 991, 989, 987, 
//986, 985, 981, 914, 913, 911
//
//*****************  Version 180  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:13a
//Updated in $/LeapCC/Library
//put messages for frozen time table to class
//
//*****************  Version 179  *****************
//User: Jaineesh     Date: 8/06/09    Time: 6:47p
//Updated in $/LeapCC/Library
//modified in message for period time
//
//*****************  Version 178  *****************
//User: Jaineesh     Date: 8/06/09    Time: 6:21p
//Updated in $/LeapCC/Library
//modified in message for period
//
//*****************  Version 177  *****************
//User: Parveen      Date: 8/06/09    Time: 5:21p
//Updated in $/LeapCC/Library
//SUBJECT_CODE_ALREADY_EXISTS, SUBJECT_NAME_ALREADY_EXISTS,
//SUBJECT_TYPE_ABBR_ALREADY_EXIST, SUBJECT_TYPE_ALREADY_EXIST
//
//*****************  Version 176  *****************
//User: Dipanjan     Date: 6/08/09    Time: 11:27
//Updated in $/LeapCC/Library
//Done bug fixing.
//bug ids---
//0000919 to 0000922
//
//*****************  Version 175  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Library
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 174  *****************
//User: Ajinder      Date: 8/04/09    Time: 1:22p
//Updated in $/LeapCC/Library
//fixed bug no.s 842, 841, 840, 839, 814, 813, 812
//
//*****************  Version 173  *****************
//User: Ajinder      Date: 7/31/09    Time: 3:47p
//Updated in $/LeapCC/Library
//fixed bug no. 816
//
//*****************  Version 172  *****************
//User: Parveen      Date: 7/30/09    Time: 4:07p
//Updated in $/LeapCC/Library
//fee head message added (FEEHEAD_FEEHEADVALUE_ALREADY,
//FEEHEAD_FEECYCLEFINES_ALREADY, FEEHEAD_PARENT_ALREADY,
//FEEHEAD_NOT_ITSELF)
//
//*****************  Version 171  *****************
//User: Rajeev       Date: 7/30/09    Time: 1:25p
//Updated in $/LeapCC/Library
//1) 0000758: Admit (Admin) > Focus should be move back to appropriate
//field text box after validation message. 
//2) 0000757: Admit (Admin) > Focus should be move back to appropriate
//field text box after validation message. 
//
//*****************  Version 170  *****************
//User: Jaineesh     Date: 7/29/09    Time: 7:31p
//Updated in $/LeapCC/Library
//put new message for hostel room master
//
//*****************  Version 169  *****************
//User: Jaineesh     Date: 7/29/09    Time: 6:41p
//Updated in $/LeapCC/Library
//fixed bug nos.0000737, 0000736,0000734,0000735, 0000585, 0000584,
//0000583
//
//*****************  Version 168  *****************
//User: Parveen      Date: 7/29/09    Time: 5:59p
//Updated in $/LeapCC/Library
//FEEHEAD_PARENT_RELATION, FEEHEAD_NOT_ITSELF new message added
//
//*****************  Version 167  *****************
//User: Ajinder      Date: 7/29/09    Time: 3:43p
//Updated in $/LeapCC/Library
//done the changes to fix bug no.s 754, 751
//
//*****************  Version 166  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:15
//Updated in $/LeapCC/Library
//Done bug fixing.
//bug ids---
//0000739,0000740,0000746,0000747,0000748,0000752
//
//*****************  Version 165  *****************
//User: Jaineesh     Date: 7/28/09    Time: 6:40p
//Updated in $/LeapCC/Library
//fixed bug nos.0000574, 0000575, 0000576, 0000577, 0000578, 0000579,
//0000580, 0000581
//
//*****************  Version 164  *****************
//User: Ajinder      Date: 7/28/09    Time: 4:05p
//Updated in $/LeapCC/Library
//added common messages required for fixing bug no.s
//615,614,603,458,456,450
//
//*****************  Version 163  *****************
//User: Parveen      Date: 7/24/09    Time: 1:51p
//Updated in $/LeapCC/Library
//FEEHEAD_NAME_EXIST,FEEHEAD_ABBR_EXIST message added
//
//*****************  Version 162  *****************
//User: Ajinder      Date: 7/23/09    Time: 3:47p
//Updated in $/LeapCC/Library
//added messages for class.
//
//*****************  Version 161  *****************
//User: Jaineesh     Date: 7/23/09    Time: 10:53a
//Updated in $/LeapCC/Library
//check date of leaving with date of birth & put new message
//
//*****************  Version 160  *****************
//User: Parveen      Date: 7/22/09    Time: 5:33p
//Updated in $/LeapCC/Library
//fee fund allocation, fee master message updated
//
//*****************  Version 159  *****************
//User: Jaineesh     Date: 7/22/09    Time: 1:17p
//Updated in $/LeapCC/Library
//fixed issue nos.0000592,0000593
//
//*****************  Version 158  *****************
//User: Jaineesh     Date: 7/22/09    Time: 12:28p
//Updated in $/LeapCC/Library
//fixed bug no.0000597 & put new message
//
//*****************  Version 157  *****************
//User: Parveen      Date: 7/21/09    Time: 4:02p
//Updated in $/LeapCC/Library
//message added 
//(PERIODICITY_ABBR_EXIST, PERIODICITY_ALREADY_EXIST)
//message updated (ENTER_PERIODICITY_FREQUENCY, ENTER_PERIODICITY_NUMBER)
//
//*****************  Version 156  *****************
//User: Ajinder      Date: 7/21/09    Time: 2:58p
//Updated in $/LeapCC/Library
//added message for 'Please get study periods'
//
//*****************  Version 155  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:46p
//Updated in $/LeapCC/Library
//fixed bug nos.0000622,0000623,0000624,0000611
//
//*****************  Version 154  *****************
//User: Parveen      Date: 7/16/09    Time: 5:10p
//Updated in $/LeapCC/Library
//publisher, Workshop, consulting, seminar message updated
//
//*****************  Version 153  *****************
//User: Parveen      Date: 7/14/09    Time: 5:51p
//Updated in $/LeapCC/Library
//New Message added  (Employee Workshop)
//
//*****************  Version 152  *****************
//User: Jaineesh     Date: 7/11/09    Time: 6:32p
//Updated in $/LeapCC/Library
//fixed issue nos.0000093,0000094,0000096
//
//*****************  Version 151  *****************
//User: Parveen      Date: 7/11/09    Time: 3:44p
//Updated in $/LeapCC/Library
//SUBJECT_CATEGORY_ABBR_EXIST message added 
//
//*****************  Version 150  *****************
//User: Rajeev       Date: 7/11/09    Time: 11:01a
//Updated in $/LeapCC/Library
//made enhancement to exchange max marks and marks obtained field and
//validations
//
//*****************  Version 149  *****************
//User: Dipanjan     Date: 11/07/09   Time: 10:26
//Updated in $/LeapCC/Library
//Done bug fixing.
//bug ids---
//0000551,0000552
//
//*****************  Version 148  *****************
//User: Jaineesh     Date: 7/10/09    Time: 3:45p
//Updated in $/LeapCC/Library
//fixed bug no.0000088
//
//*****************  Version 147  *****************
//User: Jaineesh     Date: 7/09/09    Time: 4:37p
//Updated in $/LeapCC/Library
//make all field alphanumeric values
//
//*****************  Version 146  *****************
//User: Jaineesh     Date: 7/09/09    Time: 3:22p
//Updated in $/LeapCC/Library
//fixed bug no.0000358
//
//*****************  Version 145  *****************
//User: Rajeev       Date: 7/09/09    Time: 10:47a
//Updated in $/LeapCC/Library
//Updated module with dependency constraint
//
//*****************  Version 144  *****************
//User: Parveen      Date: 7/08/09    Time: 6:12p
//Updated in $/LeapCC/Library
//fee head values message  updated
//
//*****************  Version 143  *****************
//User: Parveen      Date: 7/07/09    Time: 2:17p
//Updated in $/LeapCC/Library
//message added (SUBJECT_CATEGORY_RELATION,SUBJECT_CATEGORY_NOT_EXIST)
//
//*****************  Version 142  *****************
//User: Dipanjan     Date: 7/07/09    Time: 10:48
//Updated in $/LeapCC/Library
//Added messages for "Assign Role to Fine Mapping" module
//
//*****************  Version 141  *****************
//User: Parveen      Date: 7/07/09    Time: 10:36a
//Updated in $/LeapCC/Library
//Subject Category Message added (ACCEPT_SUBJECT_CATEGORY)
//
//*****************  Version 140  *****************
//User: Rajeev       Date: 7/06/09    Time: 6:32p
//Updated in $/LeapCC/Library
//added collect fees messages
//
//*****************  Version 139  *****************
//User: Parveen      Date: 7/06/09    Time: 6:15p
//Updated in $/LeapCC/Library
//subjectCategory message added
//
//*****************  Version 138  *****************
//User: Administrator Date: 3/07/09    Time: 18:26
//Updated in $/LeapCC/Library
//Added messages for "Assign Role to Fines" module
//
//*****************  Version 137  *****************
//User: Jaineesh     Date: 7/03/09    Time: 6:12p
//Updated in $/LeapCC/Library
//put message for student fine
//
//*****************  Version 136  *****************
//User: Parveen      Date: 7/03/09    Time: 4:05p
//Updated in $/LeapCC/Library
//EMAIL_VERIFICATION message added
//
//*****************  Version 135  *****************
//User: Rajeev       Date: 7/03/09    Time: 3:45p
//Updated in $/LeapCC/Library
//added student fine messages
//
//*****************  Version 134  *****************
//User: Dipanjan     Date: 2/07/09    Time: 15:58
//Updated in $/LeapCC/Library
//Added Messages for Fine Category Master
//
//*****************  Version 133  *****************
//User: Parveen      Date: 7/02/09    Time: 2:59p
//Updated in $/LeapCC/Library
//forgot message updated
//
//*****************  Version 132  *****************
//User: Parveen      Date: 7/02/09    Time: 12:36p
//Updated in $/LeapCC/Library
//branch messages added (ACCEPT_ALPHABETS_NUMERIC_ABBR)
//
//*****************  Version 131  *****************
//User: Parveen      Date: 7/02/09    Time: 12:14p
//Updated in $/LeapCC/Library
//forgot message added
//
//*****************  Version 130  *****************
//User: Rajeev       Date: 7/01/09    Time: 11:17a
//Updated in $/LeapCC/Library
//Updated manage user module in which multiple role can be selected to
//single user
//
//*****************  Version 129  *****************
//User: Parveen      Date: 6/29/09    Time: 12:20p
//Updated in $/LeapCC/Library
//regNo. wise search conditions updated
//
//*****************  Version 128  *****************
//User: Parveen      Date: 6/26/09    Time: 5:32p
//Updated in $/LeapCC/Library
//ENTER_TITLE messaged added
//
//*****************  Version 127  *****************
//User: Dipanjan     Date: 25/06/09   Time: 12:01
//Updated in $/LeapCC/Library
//Done bug fixing.
//bug ids---
//00000287 to 00000293,00000295
//
//*****************  Version 126  *****************
//User: Parveen      Date: 6/24/09    Time: 3:36p
//Updated in $/LeapCC/Library
//DATE_FORMAT message updated
//
//*****************  Version 125  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:35p
//Updated in $/LeapCC/Library
//put new messages for period slot
//
//*****************  Version 124  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:03p
//Updated in $/LeapCC/Library
//fixed bug nos.0000258,0000260,0000265,0000270,0000255
//
//*****************  Version 123  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:49
//Updated in $/LeapCC/Library
//Bug fixing.
//bug ids---
//00000256,00000257,00000259,00000261,00000263,00000264.
//00000266,00000269,00000262
//
//*****************  Version 122  *****************
//User: Jaineesh     Date: 6/24/09    Time: 12:42p
//Updated in $/LeapCC/Library
//put new message
//
//*****************  Version 121  *****************
//User: Parveen      Date: 6/24/09    Time: 11:26a
//Updated in $/LeapCC/Library
//bus pass message updated
//
//*****************  Version 120  *****************
//User: Parveen      Date: 6/23/09    Time: 6:41p
//Updated in $/LeapCC/Library
//bus pass message added 
//
//*****************  Version 119  *****************
//User: Parveen      Date: 6/23/09    Time: 12:49p
//Updated in $/LeapCC/Library
//Publisher,  Consulting,  Seminar Messages added
//
//*****************  Version 118  *****************
//User: Parveen      Date: 6/22/09    Time: 2:28p
//Updated in $/LeapCC/Library
//bus pass messages added 
//
//*****************  Version 117  *****************
//User: Parveen      Date: 6/18/09    Time: 10:39a
//Updated in $/LeapCC/Library
//messaged added in buspass
//
//*****************  Version 116  *****************
//User: Administrator Date: 13/06/09   Time: 16:29
//Updated in $/LeapCC/Library
//Make city code and city name unique for a state
//
//*****************  Version 115  *****************
//User: Rajeev       Date: 6/12/09    Time: 6:08p
//Updated in $/LeapCC/Library
//Updated with student previous academic detail message
//
//*****************  Version 114  *****************
//User: Parveen      Date: 6/11/09    Time: 5:27p
//Updated in $/LeapCC/Library
//Attendance Code Message Added & updated
//
//*****************  Version 113  *****************
//User: Parveen      Date: 6/11/09    Time: 3:40p
//Updated in $/LeapCC/Library
//ENTER_RECEIPT_CHAR, ENTER_VALIDITY_CHAR messaged added
//
//*****************  Version 112  *****************
//User: Parveen      Date: 6/11/09    Time: 1:32p
//Updated in $/LeapCC/Library
//ENTER_RECEIPT, ENTER_VALIDITY message added in Buspass
//
//*****************  Version 111  *****************
//User: Administrator Date: 10/06/09   Time: 16:13
//Updated in $/LeapCC/Library
//Added messages for  "Test Summary" module in teacher login
//
//*****************  Version 110  *****************
//User: Jaineesh     Date: 6/10/09    Time: 3:34p
//Updated in $/LeapCC/Library
//bugs fixed nos. 1370 to 1380 of Issues [08-June-09].doc
//
//*****************  Version 109  *****************
//User: Administrator Date: 10/06/09   Time: 10:53
//Updated in $/LeapCC/Library
//Added messages for test marks deletion
//
//*****************  Version 108  *****************
//User: Parveen      Date: 6/09/09    Time: 11:18a
//Updated in $/LeapCC/Library
//ENTER_ALPHABETS_CHAR message added 
//
//*****************  Version 107  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Library
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 106  *****************
//User: Parveen      Date: 6/08/09    Time: 5:49p
//Updated in $/LeapCC/Library
//COUNTRY_NAME_ALREADY_EXISTS, COUNTRY_CODE_ALREADY_EXISTS,
//COUNTRY_NATIONALITY_ALREADY_EXISTS message added
//
//*****************  Version 105  *****************
//User: Parveen      Date: 6/08/09    Time: 3:03p
//Updated in $/LeapCC/Library
//MESSAGE_NOT_SEND message update
//
//*****************  Version 104  *****************
//User: Administrator Date: 8/06/09    Time: 14:13
//Updated in $/LeapCC/Library
//Done bug fixing.
//bug ids---> 1318 to 1329 ,Leap bugs4.doc(5 to 10,12,20)
//
//*****************  Version 103  *****************
//User: Parveen      Date: 6/08/09    Time: 12:41p
//Updated in $/LeapCC/Library
// MESSAGE_NOT_SEND added
//
//*****************  Version 102  *****************
//User: Parveen      Date: 6/05/09    Time: 4:11p
//Updated in $/LeapCC/Library
//MESSAGE_NOT_SEND added
//
//*****************  Version 101  *****************
//User: Jaineesh     Date: 6/04/09    Time: 4:39p
//Updated in $/LeapCC/Library
//put new message ENTER_SPECIAL_ALPHABETS()
//
//*****************  Version 100  *****************
//User: Jaineesh     Date: 6/04/09    Time: 3:32p
//Updated in $/LeapCC/Library
//put new messages of update student/class no
//
//*****************  Version 99  *****************
//User: Administrator Date: 4/06/09    Time: 13:05
//Updated in $/LeapCC/Library
//Done bug fixing.
//bug ids--Issues[03-june-09].doc(1 to 11)
//
//*****************  Version 98  *****************
//User: Dipanjan     Date: 6/04/09    Time: 12:52p
//Updated in $/LeapCC/Library
//Gurkeerat: added messages in "Resource Category" module in LeapCC
//
//*****************  Version 97  *****************
//User: Ajinder      Date: 6/03/09    Time: 7:10p
//Updated in $/LeapCC/Library
//added define for session timeout.
//
//*****************  Version 96  *****************
//User: Jaineesh     Date: 6/03/09    Time: 6:03p
//Updated in $/LeapCC/Library
//put new message for test type category abbr.
//
//*****************  Version 95  *****************
//User: Jaineesh     Date: 6/03/09    Time: 10:39a
//Updated in $/LeapCC/Library
//modification in name of messages of room
//
//*****************  Version 94  *****************
//User: Parveen      Date: 6/02/09    Time: 5:27p
//Updated in $/LeapCC/Library
//select_nationality message added
//
//*****************  Version 93  *****************
//User: Administrator Date: 2/06/09    Time: 11:34
//Updated in $/LeapCC/Library
//Done bug fixing.
//BugIds : 1167 to 1176,1185
//
//*****************  Version 92  *****************
//User: Parveen      Date: 6/01/09    Time: 12:57p
//Updated in $/LeapCC/Library
//subject type message update
//
//*****************  Version 91  *****************
//User: Parveen      Date: 6/01/09    Time: 11:47a
//Updated in $/LeapCC/Library
//Student enquiry message extra spacing remove 
//
//*****************  Version 90  *****************
//User: Rajeev       Date: 6/01/09    Time: 11:38a
//Updated in $/LeapCC/Library
//added find student and student detail messages
//
//*****************  Version 89  *****************
//User: Administrator Date: 30/05/09   Time: 16:55
//Updated in $/LeapCC/Library
//Added space between DataofBirth
//
//*****************  Version 88  *****************
//User: Rajeev       Date: 5/30/09    Time: 4:55p
//Updated in $/LeapCC/Library
//Added admit student validation messages
//
//*****************  Version 87  *****************
//User: Parveen      Date: 5/30/09    Time: 2:33p
//Updated in $/LeapCC/Library
//FATHER_NAME_LENGTH message added
//
//*****************  Version 86  *****************
//User: Administrator Date: 29/05/09   Time: 17:58
//Updated in $/LeapCC/Library
//Added messages for test marks modules
//
//*****************  Version 85  *****************
//User: Administrator Date: 29/05/09   Time: 16:38
//Updated in $/LeapCC/Library
//Added new messages for city module
//
//*****************  Version 84  *****************
//User: Administrator Date: 29/05/09   Time: 15:32
//Updated in $/LeapCC/Library
//Added mesages for "student enquiry" module
//
//*****************  Version 83  *****************
//User: Parveen      Date: 5/29/09    Time: 1:21p
//Updated in $/LeapCC/Library
//BRANCH_ALREADY_EXIST, ABBR_ALREADY_EXIST message added in Branch Master
//
//*****************  Version 82  *****************
//User: Administrator Date: 29/05/09   Time: 11:48
//Updated in $/LeapCC/Library
//Added new variables  for "degree" module
//
//*****************  Version 81  *****************
//User: Administrator Date: 29/05/09   Time: 11:43
//Updated in $/LeapCC/Library
//Done bug fixing------ Issues [28-May-09]Build# cc0007
//
//*****************  Version 80  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Library
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 79  *****************
//User: Parveen      Date: 5/28/09    Time: 12:56p
//Updated in $/LeapCC/Library
//validation text message added  (START_RECORD, ENTER_NUMERIC_VALUE)
//
//*****************  Version 78  *****************
//User: Jaineesh     Date: 5/27/09    Time: 7:34p
//Updated in $/LeapCC/Library
//fixed bugs & enhancement No.1071,1072,1073,1074,1075,1076,1077,1079
//issues of Issues [25-May-09]Build# cc0006.doc
//
//*****************  Version 77  *****************
//User: Parveen      Date: 5/27/09    Time: 1:06p
//Updated in $/LeapCC/Library
//validation text message updated (ACTIVE_SESSION_EXISTS,
//FROM_TO_SESSION_ALREADY_EXIST)
//
//*****************  Version 76  *****************
//User: Pushpender   Date: 5/26/09    Time: 6:32p
//Updated in $/LeapCC/Library
//had to replace again all contents from new file as kanav had made some
//mistakes in previous version
//
//*****************  Version 71  *****************
//User: Dipanjan     Date: 26/05/09   Time: 17:35
//Updated in $/LeapCC/Library
//Corrected messages for degree module
//
//*****************  Version 70  *****************
//User: Parveen      Date: 5/26/09    Time: 5:25p
//Updated in $/LeapCC/Library
//issue fix
//
//*****************  Version 69  *****************
//User: Administrator Date: 25/05/09   Time: 15:16
//Updated in $/LeapCC/Library
//Added the functionality "No of students with zero marks : X"
//
//*****************  Version 68  *****************
//User: Administrator Date: 21/05/09   Time: 14:43
//Updated in $/LeapCC/Library
//Added new functions and messages for "Assign Survey to students,parent
//and employee" module
//
//*****************  Version 67  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Library
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 66  *****************
//User: Parveen      Date: 5/21/09    Time: 10:24a
//Updated in $/LeapCC/Library
//message add ENTER_PERIODICITY_ABBR
//
//*****************  Version 65  *****************
//User: Dipanjan     Date: 5/20/09    Time: 12:17p
//Updated in $/LeapCC/Library
//Gurkeerat: Added messages in Room Type module
//
//*****************  Version 64  *****************
//User: Administrator Date: 20/05/09   Time: 11:43
//Updated in $/LeapCC/Library
//Added new messages for "Duty Leave" module
//
//*****************  Version 63  *****************
//User: Dipanjan     Date: 19/05/09   Time: 18:59
//Updated in $/LeapCC/Library
//Added messages for "Duty Leave" module in teacher section
//
//*****************  Version 62  *****************
//User: Dipanjan     Date: 5/19/09    Time: 11:20a
//Updated in $/LeapCC/Library
//Gurkeerat: messages added in module "Supplier" & "itemCategory"
//
//*****************  Version 61  *****************
//User: Rajeev       Date: 5/18/09    Time: 11:54a
//Updated in $/LeapCC/Library
//Modified module so that incactive time table labels cannot be
//associated with current class
//
//*****************  Version 60  *****************
//User: Dipanjan     Date: 5/18/09    Time: 11:36a
//Updated in $/LeapCC/Library
//new message added to module common messages by Gurkeerat
//
//*****************  Version 59  *****************
//User: Dipanjan     Date: 5/16/09    Time: 4:28p
//Updated in $/LeapCC/Library
//messages added in hostel visitor, hostel discipline and hostel
//complaint category module by Gurkeerat
//
//*****************  Version 57  *****************
//User: Jaineesh     Date: 5/12/09    Time: 5:32p
//Updated in $/LeapCC/Library
//put new message "ENTER_TIME" made by Gurkeerat
//
//*****************  Version 56  *****************
//User: Jaineesh     Date: 5/12/09    Time: 5:18p
//Updated in $/LeapCC/Library
//fixed bugs Issues Build cc0001.doc
//(Nos.991,992,993,994,995,996,997,998,999,1000)
//
//*****************  Version 55  *****************
//User: Jaineesh     Date: 5/08/09    Time: 1:07p
//Updated in $/LeapCC/Library
//fixed bugs Issues BuildCC# cc0001.doc
//
//*****************  Version 54  *****************
//User: Jaineesh     Date: 5/07/09    Time: 5:11p
//Updated in $/LeapCC/Library
//bug fixed build no. BuildCC#cc 0001.doc 
//
//*****************  Version 53  *****************
//User: Jaineesh     Date: 5/05/09    Time: 12:01p
//Updated in $/LeapCC/Library
//added messages made by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 52  *****************
//User: Jaineesh     Date: 5/04/09    Time: 7:05p
//Updated in $/LeapCC/Library
//modification in messages for cleaning master & report complaints module
//
//*****************  Version 51  *****************
//User: Ajinder      Date: 5/02/09    Time: 6:37p
//Updated in $/LeapCC/Library
//added defines for 'upload final marks'.
//
//*****************  Version 50  *****************
//User: Jaineesh     Date: 5/02/09    Time: 3:03p
//Updated in $/LeapCC/Library
//modified in messages for cleaning record
//
//*****************  Version 49  *****************
//User: Parveen      Date: 5/02/09    Time: 2:18p
//Updated in $/LeapCC/Library
//INCORRECT_FORMAT message added in foxpro 
//
//*****************  Version 48  *****************
//User: Rajeev       Date: 5/02/09    Time: 11:37a
//Updated in $/LeapCC/Library
//Added validation mesaage
//
//*****************  Version 47  *****************
//User: Jaineesh     Date: 5/02/09    Time: 11:11a
//Updated in $/LeapCC/Library
//modified messages
//
//*****************  Version 46  *****************
//User: Rajeev       Date: 5/02/09    Time: 10:33a
//Updated in $/LeapCC/Library
//added message of academic report
//
//*****************  Version 45  *****************
//User: Jaineesh     Date: 5/02/09    Time: 10:33a
//Updated in $/LeapCC/Library
//new messages for cleaning room & cleaning history
//
//*****************  Version 44  *****************
//User: Parveen      Date: 4/30/09    Time: 3:46p
//Updated in $/LeapCC/Library
//FOXPRO_LIST_EMPTY message added
//
//*****************  Version 43  *****************
//User: Jaineesh     Date: 4/30/09    Time: 3:43p
//Updated in $/LeapCC/Library
//put the new messages for report complaints & handle complaints
//
//*****************  Version 42  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:03p
//Updated in $/LeapCC/Library
//added message for transfer marks report
//
//*****************  Version 41  *****************
//User: Parveen      Date: 4/23/09    Time: 2:35p
//Updated in $/LeapCC/Library
//Icard message added
//
//*****************  Version 40  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:45p
//Updated in $/LeapCC/Library
//put new message for hostel room type detail and message in add or edit
//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 4/22/09    Time: 3:04p
//Updated in $/LeapCC/Library
//add new messages for hostel room type
//
//*****************  Version 38  *****************
//User: Ajinder      Date: 4/21/09    Time: 7:17p
//Updated in $/LeapCC/Library
//added messages for marks transfer
//
//*****************  Version 37  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:03
//Updated in $/LeapCC/Library
//Added messages for  "Grace Marks Master" module
//
//*****************  Version 36  *****************
//User: Dipanjan     Date: 17/04/09   Time: 16:35
//Updated in $/LeapCC/Library
//Added alerts for no topics entered during atttendance
//
//*****************  Version 35  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:39
//Updated in $/LeapCC/Library
//Added messages for attendace delete module
//
//*****************  Version 34  *****************
//User: Dipanjan     Date: 10/04/09   Time: 13:34
//Updated in $/LeapCC/Library
//Added new messages
//
//*****************  Version 33  *****************
//User: Rajeev       Date: 4/08/09    Time: 6:14p
//Updated in $/LeapCC/Library
//fixed bugs
//
//*****************  Version 32  *****************
//User: Dipanjan     Date: 3/04/09    Time: 18:35
//Updated in $/LeapCC/Library
//Added new  messges for bulk attendance
//
//*****************  Version 31  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:34
//Updated in $/LeapCC/Library
//Added messages for bus masters
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 3/31/09    Time: 11:42a
//Updated in $/LeapCC/Library
//modified in messaging
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 3/26/09    Time: 4:39p
//Updated in $/LeapCC/Library
//add new fields in test type category
//
//*****************  Version 28  *****************
//User: Parveen      Date: 3/20/09    Time: 4:32p
//Updated in $/LeapCC/Library
//thoughts message added
//
//*****************  Version 27  *****************
//User: Jaineesh     Date: 3/20/09    Time: 4:31p
//Updated in $/LeapCC/Library
//modified in messages for test type category
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Library
//modified for test type & put test type category
//
//*****************  Version 25  *****************
//User: Parveen      Date: 3/12/09    Time: 12:16p
//Updated in $/LeapCC/Library
//FROM_TO_SESSION_ALREADY_EXIST message update
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 3/12/09    Time: 11:49a
//Updated in $/LeapCC/Library
//modified the files for topics taught
//
//*****************  Version 23  *****************
//User: Parveen      Date: 3/12/09    Time: 11:37a
//Updated in $/LeapCC/Library
//message update
//
//*****************  Version 22  *****************
//User: Parveen      Date: 3/10/09    Time: 1:43p
//Updated in $/LeapCC/Library
//FROM_TO_ALREADY_EXIST message added
//
//*****************  Version 21  *****************
//User: Ajinder      Date: 3/07/09    Time: 4:42p
//Updated in $/LeapCC/Library
//added defines for group change.
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 4/03/09    Time: 18:47
//Updated in $/LeapCC/Library
//Added messages for "Send Message to Colleagues"
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 4/03/09    Time: 13:51
//Updated in $/LeapCC/Library
//Fixes Bugs
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:26p
//Updated in $/LeapCC/Library
//added role permission and dashboard permission
//
//*****************  Version 17  *****************
//User: Parveen      Date: 1/16/09    Time: 3:10p
//Updated in $/LeapCC/Library
//message added subject topic
//
//*****************  Version 16  *****************
//User: Parveen      Date: 1/16/09    Time: 1:29p
//Updated in $/LeapCC/Library
//message add for subject topic
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Library
//Updated with Required field, centralized message, left align
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 1/05/09    Time: 11:34a
//Updated in $/LeapCC/Library
//added reported by in student discipline
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 12/30/08   Time: 4:37p
//Updated in $/LeapCC/Library
//modified in group message
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 26/12/08   Time: 15:22
//Updated in $/LeapCC/Library
//Corrected spelling mistake
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 12/25/08   Time: 12:38p
//Updated in $/LeapCC/Library
//add new messages for offense model
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 12/22/08   Time: 1:19p
//Updated in $/LeapCC/Library
//added messages for attendance already taken or tests already taken.
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 22/12/08   Time: 12:02
//Updated in $/LeapCC/Library
//Corrected Bugs
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/19/08   Time: 3:02p
//Updated in $/LeapCC/Library
//modified in message for employee can teach in field
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 12/18/08   Time: 12:20p
//Updated in $/LeapCC/Library
//added roll no. allocation message
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 12/18/08   Time: 11:58a
//Updated in $/LeapCC/Library
//added defines for if the user names exist already
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/16/08   Time: 11:40a
//Updated in $/LeapCC/Library
//SESSION_ABBR_ALREADY_EXIST msg added 
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 13/12/08   Time: 15:56
//Updated in $/LeapCC/Library
//Corrected Bug corresponding to 25-11-2008
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/13/08   Time: 3:54p
//Updated in $/LeapCC/Library
//added defines for if attendance has been entered or tests have been
//taken for the group and class.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/09/08   Time: 5:31p
//Updated in $/LeapCC/Library
//Added Messages for FeedBack Questions Master
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library
//
//*****************  Version 91  *****************
//User: Dipanjan     Date: 11/27/08   Time: 6:23p
//Updated in $/Leap/Source/Library
//Added message for notice master
//
//*****************  Version 90  *****************
//User: Dipanjan     Date: 11/24/08   Time: 1:22p
//Updated in $/Leap/Source/Library
//Corrected user password message from 3 to 6 characters
//
//*****************  Version 89  *****************
//User: Dipanjan     Date: 11/24/08   Time: 10:56a
//Updated in $/Leap/Source/Library
//Added message for URL validation
//
//*****************  Version 88  *****************
//User: Dipanjan     Date: 11/22/08   Time: 2:23p
//Updated in $/Leap/Source/Library
//Added new message for marks module
//
//*****************  Version 87  *****************
//User: Dipanjan     Date: 11/21/08   Time: 11:54a
//Updated in $/Leap/Source/Library
//Added message for department dropdown in notice module
//
//*****************  Version 86  *****************
//User: Dipanjan     Date: 11/21/08   Time: 10:12a
//Updated in $/Leap/Source/Library
//Corrected bus route module messages
//
//*****************  Version 85  *****************
//User: Jaineesh     Date: 11/21/08   Time: 10:10a
//Updated in $/Leap/Source/Library
//modified message of department
//
//*****************  Version 84  *****************
//User: Dipanjan     Date: 11/20/08   Time: 1:32p
//Updated in $/Leap/Source/Library
//Added messages for "Leave Detail"  module
//
//*****************  Version 83  *****************
//User: Jaineesh     Date: 11/20/08   Time: 1:28p
//Updated in $/Leap/Source/Library
//modified for department
//
//*****************  Version 82  *****************
//User: Dipanjan     Date: 11/19/08   Time: 10:55a
//Updated in $/Leap/Source/Library
//Added messages for leave type module
//
//*****************  Version 81  *****************
//User: Dipanjan     Date: 11/18/08   Time: 6:20p
//Updated in $/Leap/Source/Library
//Added messages for financial year module
//
//*****************  Version 80  *****************
//User: Dipanjan     Date: 11/15/08   Time: 3:28p
//Updated in $/Leap/Source/Library
//Added messages for feedback masters
//
//*****************  Version 79  *****************
//User: Ajinder      Date: 11/14/08   Time: 4:42p
//Updated in $/Leap/Source/Library
//added define for checking if grades not applied to all students
//
//*****************  Version 78  *****************
//User: Ajinder      Date: 11/13/08   Time: 4:29p
//Updated in $/Leap/Source/Library
//added define for select label
//
//*****************  Version 77  *****************
//User: Parveen      Date: 11/12/08   Time: 1:48p
//Updated in $/Leap/Source/Library
//
//*****************  Version 76  *****************
//User: Parveen      Date: 11/12/08   Time: 12:52p
//Updated in $/Leap/Source/Library
//
//*****************  Version 74  *****************
//User: Ajinder      Date: 11/11/08   Time: 11:58a
//Updated in $/Leap/Source/Library
//created define for checking if the grades have not been assigned to
//degree students
//
//*****************  Version 73  *****************
//User: Parveen      Date: 11/10/08   Time: 3:35p
//Updated in $/Leap/Source/Library
//Grade Master Label add
//
//*****************  Version 72  *****************
//User: Ajinder      Date: 11/07/08   Time: 10:55a
//Updated in $/Leap/Source/Library
//added common message for promote students confirm
//
//*****************  Version 71  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:38p
//Updated in $/Leap/Source/Library
//Added "RESTORE_CONFIRM" message
//
//*****************  Version 70  *****************
//User: Dipanjan     Date: 11/06/08   Time: 5:11p
//Updated in $/Leap/Source/Library
//Added messages for "Quarantine(delete)" and "Restore" student modules
//
//*****************  Version 69  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:41p
//Updated in $/Leap/Source/Library
//Added Message for Course Resource Module
//
//*****************  Version 68  *****************
//User: Dipanjan     Date: 11/03/08   Time: 5:29p
//Updated in $/Leap/Source/Library
//Added messages for test & marks deletion
//
//*****************  Version 67  *****************
//User: Ajinder      Date: 10/31/08   Time: 3:30p
//Updated in $/Leap/Source/Library
//added common message for "Select Course"
//
//*****************  Version 66  *****************
//User: Ajinder      Date: 10/31/08   Time: 12:44p
//Updated in $/Leap/Source/Library
//done common messaging for student promotion
//
//*****************  Version 65  *****************
//User: Jaineesh     Date: 10/25/08   Time: 6:09p
//Updated in $/Leap/Source/Library
//modified in message
//
//*****************  Version 64  *****************
//User: Parveen      Date: 10/25/08   Time: 4:52p
//Updated in $/Leap/Source/Library
//Grade Master update new message
//
//*****************  Version 63  *****************
//User: Parveen      Date: 10/25/08   Time: 3:26p
//Updated in $/Leap/Source/Library
//MODIFY FILES
//
//*****************  Version 62  *****************
//User: Ajinder      Date: 10/25/08   Time: 3:05p
//Updated in $/Leap/Source/Library
//file modified
//
//*****************  Version 61  *****************
//User: Jaineesh     Date: 10/25/08   Time: 11:13a
//Updated in $/Leap/Source/Library
//modified
//
//*****************  Version 60  *****************
//User: Arvind       Date: 10/25/08   Time: 11:11a
//Updated in $/Leap/Source/Library
//added messages for  Percentage Wise attendance Report
//
//*****************  Version 59  *****************
//User: Jaineesh     Date: 10/25/08   Time: 11:09a
//Updated in $/Leap/Source/Library
//modified in messages
//
//*****************  Version 58  *****************
//User: Parveen      Date: 10/24/08   Time: 4:51p
//Updated in $/Leap/Source/Library
//Update Java Script Validations
//
//*****************  Version 57  *****************
//User: Jaineesh     Date: 10/24/08   Time: 3:48p
//Updated in $/Leap/Source/Library
//modified in Histogram Scale
//
//*****************  Version 56  *****************
//User: Parveen      Date: 10/24/08   Time: 2:19p
//Updated in $/Leap/Source/Library
//
//*****************  Version 55  *****************
//User: Jaineesh     Date: 10/24/08   Time: 1:45p
//Updated in $/Leap/Source/Library
//modified
//
//*****************  Version 54  *****************
//User: Jaineesh     Date: 10/23/08   Time: 3:27p
//Updated in $/Leap/Source/Library
//modified in messages
//
//*****************  Version 53  *****************
//User: Pushpender   Date: 10/23/08   Time: 1:32p
//Updated in $/Leap/Source/Library
//added PAGE_NOT_FOUND variable
//
//*****************  Version 52  *****************
//User: Pushpender   Date: 10/23/08   Time: 12:26p
//Updated in $/Leap/Source/Library
//Modified SESSION_TIME_OUT variable's value
//
//*****************  Version 48  *****************
//User: Ajinder      Date: 10/22/08   Time: 4:21p
//Updated in $/Leap/Source/Library
//messaging added for histogram
//
//*****************  Version 47  *****************
//User: Pushpender   Date: 10/21/08   Time: 7:33p
//Updated in $/Leap/Source/Library
//Added message variables for SessionError and ConnectionError
//
//*****************  Version 46  *****************
//User: Dipanjan     Date: 10/21/08   Time: 3:44p
//Updated in $/Leap/Source/Library
//Added messages for advanced student filter's date fields
//
//*****************  Version 45  *****************
//User: Jaineesh     Date: 10/21/08   Time: 10:36a
//Updated in $/Leap/Source/Library
//modified in employee messages
//
//*****************  Version 44  *****************
//User: Ajinder      Date: 10/17/08   Time: 11:34a
//Updated in $/Leap/Source/Library
//done common messaging for "marks transfer"
//
//*****************  Version 43  *****************
//User: Arvind       Date: 10/07/08   Time: 5:04p
//Updated in $/Leap/Source/Library
//modified the sort order message in sort order
//
//*****************  Version 42  *****************
//User: Ajinder      Date: 10/01/08   Time: 7:22p
//Updated in $/Leap/Source/Library
//added the message for access denied.
//
//*****************  Version 41  *****************
//User: Dipanjan     Date: 10/01/08   Time: 11:00a
//Updated in $/Leap/Source/Library
//Added Messages in TimeTable Label Module
//
//*****************  Version 40  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:33p
//Updated in $/Leap/Source/Library
//Added Messages for TimeTable Labels
//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 9/26/08    Time: 3:06p
//Updated in $/Leap/Source/Library
//modified in exam capacity
//
//*****************  Version 38  *****************
//User: Dipanjan     Date: 9/25/08    Time: 4:34p
//Updated in $/Leap/Source/Library
//Added and updated messages in "ManageUser" module
//
//*****************  Version 37  *****************
//User: Dipanjan     Date: 9/24/08    Time: 6:48p
//Updated in $/Leap/Source/Library
//Added and edited  messages for "ManageUser" modules
//
//*****************  Version 36  *****************
//User: Dipanjan     Date: 9/24/08    Time: 10:19a
//Updated in $/Leap/Source/Library
//Added messages for bus route module
//
//*****************  Version 35  *****************
//User: Dipanjan     Date: 9/20/08    Time: 2:25p
//Updated in $/Leap/Source/Library
//Modify message in student search in teacher section
//
//*****************  Version 34  *****************
//User: Dipanjan     Date: 9/19/08    Time: 12:16p
//Updated in $/Leap/Source/Library
//Modified messages in bulk attendance section
//
//*****************  Version 33  *****************
//User: Ajinder      Date: 9/18/08    Time: 8:53p
//Updated in $/Leap/Source/Library
//added defines for sc based reports
//
//*****************  Version 32  *****************
//User: Dipanjan     Date: 9/18/08    Time: 7:27p
//Updated in $/Leap/Source/Library
//Modified message in bulk attendance
//
//*****************  Version 31  *****************
//User: Dipanjan     Date: 9/18/08    Time: 3:30p
//Updated in $/Leap/Source/Library
//Added message in bulk attendance section
//
//*****************  Version 30  *****************
//User: Ajinder      Date: 9/17/08    Time: 6:52p
//Updated in $/Leap/Source/Library
//added messages for assigning sections to students
//
//*****************  Version 29  *****************
//User: Dipanjan     Date: 9/16/08    Time: 3:09p
//Updated in $/Leap/Source/Library
//Updated messages corresponding to 'Change Password' module.
//
//*****************  Version 28  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library
//Added Messages for  'SECTION CENTRIC' Modules in Teacher Section
//
//*****************  Version 27  *****************
//User: Arvind       Date: 9/15/08    Time: 3:32p
//Updated in $/Leap/Source/Library
//added definitions for section 
//
//*****************  Version 26  *****************
//User: Arvind       Date: 9/12/08    Time: 2:33p
//Updated in $/Leap/Source/Library
//added definitions for common messages
//
//*****************  Version 25  *****************
//User: Arvind       Date: 9/09/08    Time: 6:10p
//Updated in $/Leap/Source/Library
//added definitions for change Password Module
//
//*****************  Version 24  *****************
//User: Ajinder      Date: 9/08/08    Time: 7:02p
//Updated in $/Leap/Source/Library
//added common messages for configs master
//
//*****************  Version 23  *****************
//User: Arvind       Date: 9/06/08    Time: 6:27p
//Updated in $/Leap/Source/Library
//added SECTION_TYPE_LECTURE for section module
//
//*****************  Version 22  *****************
//User: Arvind       Date: 9/06/08    Time: 4:58p
//Updated in $/Leap/Source/Library
//added messages for section module
//
//*****************  Version 20  *****************
//User: Arvind       Date: 9/05/08    Time: 7:00p
//Updated in $/Leap/Source/Library
//added definitions for course and course type
//
//*****************  Version 19  *****************
//User: Ajinder      Date: 9/05/08    Time: 6:09p
//Updated in $/Leap/Source/Library
//corrected messages
//
//*****************  Version 18  *****************
//User: Pushpender   Date: 9/05/08    Time: 5:01p
//Updated in $/Leap/Source/Library
//NO_SCRIPT_ERROR message added
//
//*****************  Version 17  *****************
//User: Ajinder      Date: 9/05/08    Time: 4:45p
//Updated in $/Leap/Source/Library
//added messages for config masters
//
//*****************  Version 16  *****************
//User: Ajinder      Date: 9/01/08    Time: 5:31p
//Updated in $/Leap/Source/Library
//corrected messages.
//
//*****************  Version 15  *****************
//User: Ajinder      Date: 8/28/08    Time: 12:12p
//Updated in $/Leap/Source/Library
//done the common messaging task for Classwise Consolidated Report
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/26/08    Time: 5:28p
//Updated in $/Leap/Source/Library
//new messages for period
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:30p
//Updated in $/Leap/Source/Library
//done the common messaging for :
//1. Bank Masters
//2. Bank Branch Masters
//3. Session Masters
//4. Classes Masters
//5. Assign Roll Numbers
//6. Assign Groups
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/25/08    Time: 5:52p
//Updated in $/Leap/Source/Library
//modified in group messages
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/25/08    Time: 5:30p
//Updated in $/Leap/Source/Library
//added centralized functions
//
//*****************  Version 10  *****************
//User: Pushpender   Date: 8/23/08    Time: 5:19p
//Updated in $/Leap/Source/Library
//removed trailing spaces
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/23/08    Time: 1:42p
//Updated in $/Leap/Source/Library
//update in messages
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/23/08    Time: 1:36p
//Updated in $/Leap/Source/Library
//Added Standard Messages
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/21/08    Time: 5:35p
//Updated in $/Leap/Source/Library
//Added VSS History Tag
?>