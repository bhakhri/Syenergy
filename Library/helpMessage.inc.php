<?php
//-------------------------------------------------------
// Purpose: to centralize all help messages of the application and can be altered on need basis
// Author : Parveen Sharma
// Created on : (26.10.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

// Daily Attendance 
define('HELP_DAILY_ATTENDANCE_SCHEDULE','Shows the teaching schedule for the day for this teacher. Clicking on any row here pre-fills the rest of the feilds automatically to enable the teacher to take attendance for that period.');
define('HELP_DAILY_ATTENDANCE_CLASS','Allows the teacher to choose for which class the attendance is to be taken');
define('HELP_DAILY_ATTENDANCE_SUBJECT','Allows the teacher to choose for which subject the attendance is to be taken.');
define('HELP_DAILY_ATTENDANCE_GROUP','Allows the teacher to choose for which group the attendance is to be taken.');
define('HELP_DAILY_ATTENDANCE_PERIOD','Allows the teacher to choose for which period the attendance is to be taken.');
define('HELP_DAILY_ATTENDANCE_DEFAULTATTCODE','Allows the teacher to choose what to mark the students by default. Once the list of students is shown, changing the attendance code would mark all students with this value.');
define('HELP_DAILY_ATTENDANCE_COMMENTS','Allows the teacher to enter any special comments for the particular class. These could be for example, if some interesting presentations were made in that class by some students, they could be mentioned here.');
define('HELP_DAILY_ATTENDANCE_TOPICS','The topics section can be used very effectively. This contains the list of topics that are supposedly to be covered by the teacher during the course of the study period. This list is that same for one particular subject, irrespective of which teacher is teaching the subject. This list is created by the administrator, so if you don`t see any topics in this list, then please request the administrator to create this list for a particular subject.<br>There are some advantages to entering the topics in while taking attendance<br><ul><li> Teachers would be able to see at any point in time which topics are remaining to be taught and which they have covered </li><li>Students who have been absent for specific classes, would be able to see what topics they have missed</li></ul>');
define('HELP_DAILY_ATTENDANCE_MEMBERS','Allows the teacher to indicate that while the student was not present in the class, he/she is not to be marked absent as he/she may not have joined yet');
define('HELP_DAILY_ATTENDANCE_HISTORY','Shows a popup window to the teacher listing the history of attendance already taken. Both bulk attendance (if enabled by the admin) and daily attendance records are shown');


// Attendance History
define('HELP_ATTENDANCE_HISTORY_PERIOD','Period for which attendance has been marked. If this has been marked as bulk attendance, then the period value would not be shown as it is not relevant.');
define('HELP_ATTENDANCE_HISTORY_FROM','From and To values show the period between which the attendance has been taken. For \"daliy\" attendance these values would be the same.');
define('HELP_ATTENDANCE_HISTORY_TO','From and To values show the period between which the attendance has been taken. For \"daliy\" attendance these values would be the same.');
define('HELP_ATTENDANCE_HISTORY_TYPES','Shows type of attendance taken.<br>Bulk or Daily');
define('HELP_ATTENDANCE_HISTORY_LECTURES','This shows the number of lectures for whcih attendance is to be marked. This is also equal to the number of lectures delivered in the From, To period.');


// Bulk Attendance 
define('HELP_BULK_ATTENDANCE_FROM','The date from which you want to mark the attendance.');
define('HELP_BULK_ATTENDANCE_LECTURE','Number of lectures delivered between the \"From\" and \"To\" dates.');
define('HELP_BULK_ATTENDANCE_TO','The date to which you want to mark the attendance upto.');
define('HELP_BULK_ATTENDANCE_LAST_DEL','Lectures delivered upto now.');
define('HELP_BULK_ATTENDANCE_DELIVERED','This value is the number of lectures delivered between the \"From\" and \"To\" dates. Usually, this is the same as \"Lecture(s) Delivered\" field above but if for some reason a particular student was on some official assignment and even though he/she was absent, you do not want to makr him as absent, lectures delivered value for him/her becomes less.');
define('HELP_BULK_ATTENDANCE_LAST_ATT','This is the number of lectures student has attended upto now.');
define('HELP_BULK_ATTENDANCE_ATTENDED','This is the value you put for lectures attended by student from the lectures delivered while marking this attendance.');
define('HELP_BULK_ATTENDANCE_PERCENTAGE','Attendance upto now.');


// Display Attendance 
define('HELP_DISPLAY_ATTENDANCE_DATE','The From date and To date are used to determine the time interval between which you wish to see the attendance date.');
define('HELP_DISPLAY_ATTENDANCE_NAME','Specify a name here if you wish to see attendance of a specific student.');
define('HELP_DISPLAY_ATTENDANCE_ROLLNO','Specify a roll number here if you wish to see attendance of a specific student.');
define('HELP_DISPLAY_ATTENDANCE_SHORT','A red icon represents the student attendance is below a threshold value defined by the admin and a green icon represents value is above the threshold.<br> This threshold is typically defined by the institute and is usually a value bellow which students are not allowed to sit for exams.<br> This visual indicator gives a quick overview to teacher about which students need to be named about their low attendance.');


// Display Test Marks
define('HELP_TEST_MARKS_CLASS','Choose the class for which you wish to take enter marks');
define('HELP_TEST_MARKS_SUBJECT','Choose the subject for which marks are to be entered');
define('HELP_TEST_MARKS_GROUP','Choose the group for which marks are to be entered');
define('HELP_TEST_MARKS_TYPES','Choose the type of the particular test for which marks are to be entered. This could typically be any of the test types like<br>Sessionals<br>Assignments<br>Quizzes<br>Workshops, etc.<br> These test types are created by admin/dean/exam controller, etc. The different types of test types created for which marks can be entered are shown here. A teacher cannot create new \"Test Types\". However he/she has to a particular test type and can create as many tests so that test type. ');
define('HELP_TEST_MARKS_TEST','Here the teacher can either create a new test of the particular \"Test Type\" or edit the marks of a previously created one.');
define('HELP_TEST_MARKS_DELETE','Through this the teacher can delete a previous test. This the teacher may want to do if the test is created by mistake.');
define('HELP_TEST_MARKS_ABBR','This is the Abbreviation of the test. It is important to have meaningful name here as the name would typically show in marks/performance reports where test name is to be shown. For example, if 3 sessionals are to be conducted than the Test Abbr`s can be S1, S2, S3');
define('HELP_TEST_MARKS_MAX','The max. marks out of which the student is to be marked. The value of the Marks entered below has to be less or equal to than this value');
define('HELP_TEST_MARKS_DATE','The date on which the test was conducted.');
define('HELP_TEST_MARKS_TOPIC','Here the teacher entered the topics for which the test was conducted.');
define('HELP_TEST_MARKS_INDEX','This value is the test index. It is automatically incremented whenever a new test of a particular \"Test Type\" gets created. For example, If There are Three sessionals, One assignment, Two Quiz conducted then is test index for sessionals, assignment and quiz would be 3, 1 and 2 respectively');
define('HELP_TEST_MARKS_PRESENT','Indicates whether a student is present or absent for a class. Note that in some cases absent may be treated as different from getting zero marks');

//time table dialog box
define('HELP_INTERPRET_ENTRIES','The entries in each cell mean the following<br>1.Subject Code <br>2.Group<br>3.Room<br>4.Teacher Name<br>5.Link that will take you to the page to take daily attendance for this period<br>6.Shows Student Information for particular group');


//Duty Leaves Module
define('HELP_DUTY_LEAVES_ROLL_NO','If you wish to enter the duty leaves for only some specific students,then enter their comma seperated roll numbers here.For example,if duty leaves is to be entered for roll numbes A10093,A32415,B56936 and E126578 then enter these roll numbers as A10093,A32415,B56936,E126578 and press the button next to it.');
define('HELP_DUTY_LEAVES_RESTRICATION','"Nos of duty" leaves + Nos of Attended lectures < Total nos of delivered lectures.On entering duty leaves value if this condition is not satisfied,then the system would not accept the values.');

//Advanced Filter
define('HELP_ADVANCED_FILTER','Advanced Student Filter<br>The advanced student filter is a unique feature of the application in the sense<br>that it enables the user to search for students that match a specific criteria.<br>The beauty of this filter is that one can combine different search criteria from<br>the different variables all from one user friendly screen.So, for example,if a<br>user wants to know the list of all students who travel by bus route no.6 or 7<br>and who are from Chandigarh city,then user can expand the Address filter, in city choose Chandigarh, then expand the Misc filter and in the Bus Route filter multiselect RN-06 AND RN-07 and click Showlist button.<br><br>There are a myriad of parameters available and by combining these parameters in different combinations thousands of search criteria can be formed on the fly.<br><br>The important thing to note here is that when the user selects the multiple criteria from different fields, then these criteria are combined in an AND condition. Also, when you select multiple values from the same criteria, then these are combined in an OR condition.');
//Fleet management (set up)
define('HELP_MAIN_SPARE_TYRE','<b>Main/Spare Tyre</b><br>The tyre information needs to be added as the complete tyre maintenance history would be maintained over the period of time for vehicles of this type');
define('HELP_VEHICLE_USED_AS','<b>Used As</b><br><b>Damage</b> - The current tyre which is being replaced by this new one because it is damaged and cannot be reused <br><b>To Stores</b> -The current tyre which is being replaced by this new one is because it is being sent to store inventory for tyres');
define('HELP_VEHICLE_READING','<b>Vehicle Reading</b><br>Please enter the meter reading of the vehicle at which we are editing this entry');
define('HELP_VEHICLE_EDIT_USED','<b>Used As</b><br>Main : The tyre would be used as one of the running tyres <br>Spare : The tyre would be used as a stepney tyre in the vehicle');
define('HELP_VEHICLE_ACCIDENT_REMARKS','<b>Remarks</b><br>Please add some details of the accident here such as the location of the accident,damage etc.');
define('HELP_INSURANCE_CLAIM_AMOUNT','<b>Insurance Claim Amount</b><br>This is the amount which is filled for claim');
define('HELP_LOGGING_CLAIM','<b>Logging Claim</b><br>This is the amount that was filed for claim with the insurance company');
define('HELP_VEHICLE_EXPENSES','<b>Vehicle Expenses</b><br>This is the total actual expenses incurred on repairs');
define('HELP_VEHICLE_SELF_EXPENSES','<b>Self Expenses</b><br>This is the total expenses which was spent but not claimed for insurance');
define('HELP_SERVICE_DETAILS','<b>Service Details</b><br>This table lets you enter the standard areas under which servicing is done. For example, of Brake oil has been changed, then fill in the amount in rupees for that, then mention after how many KM should the next change be done at as well as if you need to be informed when the next change is becoming due');
define('HELP_REPAIR_DETAILS','<b>Repair Details</b><br>Use the below table to add more entries of items that may have been changed/repaired during this service trip to the workshop');



//Fee
define('HELP_FEE_RECEIPTNO','<b>Receipt No.</b><br>Receipt number has to be unique, else duplicate receipt number error. One cannot edit an old receipt number. If one wants to modify the contents of an old receipt number, he/she has to delete that receipt and then issue a new one with a new receipt number.');
define('HELP_FEE_TYPE','<b>Fee Type</b><br>This denotes whether we want to collect the combined fees for academics and hostel and transport or for individual components.');
define('HELP_FEE_CYCLE','<b>Fee Cycle</b><br>Fees cycle denotes the period for which fees is being paid. Mostly institutes collect fees once or twice in one year and these cycles would then typically be called \"Jan-Jun10\", \"Jul-Dec10\", etc depending on for which fees cycle the fees is being collected.');
define('HELP_FEE_CLASS','<b>Fee Class</b><br>This value is used to select the class for which the particular students fees is being collected. This may not always be the current class as sometimes the students pay the fees late and we may need to collect the fees for an older class as well.');
define('HELP_PRINT_REMARKS','<b>Print Remarks</b><br>Please enter the remarks here which you would want to be printed on the receipt for the fees.');
define('HELP_GENERAL_REMARKS','<b>General Remarks</b><br>Please enter the remarks here which you may not want to be printed on the receipt but which you would still want to keep a record of as you may have given some special concessions to a particular student. The remarks here can be used to explain the reason for these concessions.');
define('HELP_RECEIVED_FROM','<b>Received From</b><br>Please enter here the name of the person who has deposited th fees. This will always be useful later for record purposes, especially if there is a dispute in the fees collection.');
define('HELP_TOTAL_AMOUNT_PAID','<b>Total Amount Paid</b><br>This field is used to fill in the amount which is to be paid at the moment. The break up of this amount in cash, dd, cheque would need to be filled in the corresponding fields in Payment detail box.');



//Notices
define('HELP_NOTICE_SUBJECT','<b>Subject</b><br>This is the title that would appear on the notices dashboard. Make this short and crisp and relevant. For example Freshers party on the 12th of Nov');
define('HELP_NOTICE_TEXT','<b>Notice Text</b><br>This should be a longer description of the notice. The user will see this in a tool tip when he/she hovers the mouse over the notice text. This is also shown in full when the particular notice is clicked.');
define('HELP_NOTICE_ATTACHMENT','<b>Attachment</b><br>If you have a file attachment that you want to upload, please browse and upload it from here. If an attachment is chosen then the user will see a downloadable icon, clicking on which will allow him/her to download the same. The allowed type of files that can be uploaded as attachments are with extensions .doc,.ppt,.pdf,... ');
define('HELP_NOTICE_VISIBLE','<b>Visible From/To</b><br>These dates can be selected to control from which date to which date the particular notice would be visible on the users dashboard. Even though the notice may not be visible on the dashboard after the \"visible To\" date, it can still be seen by clicking the notices menu separately.');
define('HELP_NOTICE_BY_DEPARTMENT','<b>Notice By Department</b><br>Choose this to select which department has published this notice. Different dept. notices appear in diff. colors and hence can be easily used to differentiate one from the other. Examples of these depts are \"academics\",\"student welfare\",etc.');
define('HELP_NOTICE_VISIBLE_TO','<b>Notice Visible To</b><br>Please use the labels filters for University, Degree, Branch and Role. In any combination to determine who you would want the notice to be seen by. For example, using these filters you can choose that a particular notice be seen by Students, Teacher, Parents or University X, doing B.Tech in Computer Science by choosing the appropriate values in Role, University Degree and Branch respectively');
//Teacher Dashboard
define('HELP_TEACHER_DASHBOARD_PERFROMANCE','<u><b>Toppers:</b></u><br> This shows TOP '.$sessionHandler->getSessionVariable('TOPPERS_RECORD_LIMIT').' students based on percentage*.This value can be set by admin.<br><br><u><b>Below Average:</b></u><br>This shows list of students who have scored marks below '.$sessionHandler->getSessionVariable('BELOW_AVERAGE_PERCENTAGE').' percent*.This value can be set by admin.<br><br><u><b>Above Average:</b></u><br>This shows list of students who have scored above '.$sessionHandler->getSessionVariable('ABOVE_AVERAGE_PERCENTAGE').' percent*.This value can be set by admin. <br><br>* Percentage is calculated based on <b>total marks scored / total marks</b> of all tests by students.');

// Fine 
define('HELP_FINE_ROLE','Choose the role here, users of which can fine students in the fine categories selected in the \"Fines to be taken\" selection box.');
define('HELP_FINE_MODULE','This screen is used to assign roles as to which role users are allowed to Fine students for specific categories.<br/><br/>For example users of \"Role\" are allowed fine students under fine categories \"Fines to be taken\" and for those fines to be finally applied , they have to be approved by \"Approver\".<br/><br/>You can use the add / edit / delete functionality to the right to modify existing mappings or create new ones ! ');
define('HELP_GPA','GPA is calculated as follows:<br/><br/>For example out of 100 students,50 students give 5 marks to teacher and rest 50 give 3 marks, then GPA will be calculated as<br/><br/> GPA = ((50*5)+(50*3))/(50+50) ');
//Fleet
define('HELP_VEHICLE_METER_READING','<b>Vehicle Meter Reading</b><br>This should be the reading of the meter when the battery is going to be replaced');            
define('PERFORMANCE_HELP','The user would be sent SMS messages regarding student performance in the following format. <br><b>For Attendance:</b> <br>Subject Code1: Lectures Attended / Lectures Delivered <br>Subject Code2: Lectures Attended / Lectures Delivered <br>etc<br> for all the current subjects currently student is studying. For example:<br> CS2001 : 23/25<br> PH3021: 28/28<br> <b>For Marks:</b><br>Subject Code1: Marks Obtained / Total Marks <br>Subject Code2: Marks Obtained / Total Marks etc for all the subjects student is currently studying. For example<br> CS2001 : 80/100 <br> PH3021: 45/50 ');

//Help For Find Student
define('HELP_FIND_STUDENT','Find Student<br><b>Active Students:</b> Students Who Are Currently Enrolled<br><b>Alumni Students: </b>Students who have passed out of college<br><b>All Students </b>Students Who are are either currently studying OR may have taken admission and left the course in the middlee of studies (they have been marked deleted/inactive in the system)OR students who are Alumni now ');

define('HELP_MESSAGE_STUDENT','<b>Send message via</b><br><b>SMS: </b>Message will be sent through mobile SMS.Exactly delivery time depends upon operator load<br><b>E-mail:</b><br>Message will be sent as an email to the students email id recorded in the field Correspondence email at the time of admission<br><b>Dashboard :</b><br>Message will display on students dashboard the next time he/she logs in to the application');

//Help for infrastructure development
define('HELP_INFRASTRUCTURE_DEVELOPMENT','<b><center>Infrastructure Development(160)</center></b><br/><table border=1 align=center height=230px class=myFontStyle><tr><td>Establishment of new lab(s) /others(60)</td><td>1=20, 2=40, 3=60</td></tr><tr><td>Major equipment / software purchase (50)<br>Give details alongwith dates and PO number</td><td>Costing in the range 10k-50k = 10, 51k &ndash; 100k =<br/>20, 100k &ndash; 300k = 30, 301K and above = 50</td></tr><tr><td>Maintenance of Equipment / software/others<br>(20) </td><td>Mention a paragraph highlighting explicit steps<br>taken and dates and in which labs</td></tr><tr><td>Development of new testing measuring<br/>equipment, user friendly kits, software (30)</td><td>Costing in the range 10k-50k = 10, 51k - 100k =<br />20, 100k - 300k = 30</td></tr></table>');

//Help For Development Lab Manual
define('HELP_DEVELOPMENT_LAB_MANUAL','<b><center>Development of Lab Manual(s) (30)</center></b><br/><table border=1 align=center height=230px class=myFontStyle><tr><td>New Manual (20)</td><td>1=10, 2= 15, 3=20</td></tr><tr><td>Upgradation / improvement in existing manual<br />(10)</td><td>Write a paragraph highlighting what were the<br />problem areas in the old manual and how your<br />contribution in improving manuals will help the<br />students better.</td></tr></table>');

//Help For Assisting in educational trips like industrial visit and picnic
define('ASSISTING_TRIPS_AND_INDUSTRIAL_VISITS','<b><center>Assisting in Trips / Industrial visits (35)</center></b><br/><table border=1 align=center height=230px class=myFontStyle><tr><td>Number of Industrial visits assisted (15)</td><td>1=5, 2= 10, report submitted=5, mention dates,<br />how many students went and where</td></tr><tr><td>Number of Trips assisted (20) </td><td>1 = 10, 2 = 20, mention dates, how many students<br />went and where</td></tr></table>');

//help for organizing events of departmental clubs
define('ORGANIZING_EVENTS_OF_DEPARTMENTAL_CLUBS','<b><center>Organizing events of Departmental clubs (30)</center></b><br/><table border=1  align=center height=230px  class=myFontStyle><tr><td>Number of Events organized (20)</td><td>1=10, 2=20, mention dates, names of the events,<br /how many students organized, how may<br />participated, what was the budget, was an<br />audited report submitted, when and to whom</td></tr><tr><td>Number of Events assisted (10)</td><td>1 = 5, 2 = 10, mention dates, which event, what<br/>was your role, how many students organized and<br />participated.</td></tr></table>');

//help for class coordinator
define('ACTING_AS_CLASS_COORDINATOR','<b><center>Acting as class coordinator (20)</center></b><br/><table border=1 align=center height=230px class=myFontStyle><tr><td>Even semester</td><td>Self assigned score = A </td> </tr><tr><td>Class Incharge</td><td>Which class:<br />Period:<br />Yes &ndash; 5, no - 0</td></tr><tr><td>Indiscipline cases</td><td>-1 for each indiscipline case:<br />Give details of each one and dates:</td></tr></table>');

//Help for assistance in certification/inspection process
define('HELP_FOR_ASSISTANCE_IN_CERTIFICATION_INSPECTION_PROCESS','<b><center>Assistance in certification/ inspection process (50)</center></b><br/><table border=1 align=center height=230px class=myFontStyle><tr><td>Assistance in certification process(10)</td><td>Write a paragraph touching the following<br />Which course file(s) you prepared /maintained<br />Which Lab(s) did you maintain<br /> Which lab manual(s) you prepared<br />/maintained<br />Which purchase file(s) / stock register(s) you<br />prepared / maintained</td></tr><tr><td>Number of hours devoted in preparing documents (20)</td><td>Write a paragraph about your contribution to<br />preparing documents for the certification process,<br />viz. name the portions of the certification<br />document you prepared</td></tr><tr><td>Supervision / Consolidation of documents (20)</td><td>Write a paragraph about your contribution to<br />supervision / consolidation of documents for the<br />certification process, viz. name the portions of the<br />certification document you consolidated /revised</td></tr></table>');

//Help for central facilities
define('HELP_FOR_DEVELOPMENT_OF_CENTRAL_FACILITIES',' <p> <class=myfontstyle>The faculty writes a paragraph about he/she contributed to development of <br>central/institutionalfacilities, mentioning explicit steps taken with dates.<br> These facilities should not cover facilities exclusive to a department, which <br>are covered under departmental development.<br> This should also not cover activities and facilities which also not<br> cover activities and facilities which are covered under other heads in the appraisal form.</p>');

//Help for arranging meeting activity of profeesional bodies
define('HELP_FOR_ARRANGING_MEETING_ACTIVITY_OF_PROFEESIONAL_BODIES',' <b><center>Arranging meeting / activity of professional bodies (30)</center></b><br/><table border=1 align=center height=230px class=myFontStyle><tr><td>Initiation in setting up a professional society : no initiation = 0, successful initiation = 5</td></tr><tr><td>Arranging and coordinating activity: 1 activity = 10, 2 activities = 15, 3 activities = 20, 4 activities = 25</td></tr><tr><td>Providing assistance: 1 activity = 5, 2 activities = 10, 3 activities = 15, 4 activities = 20</td></tr></table>');

//Help for 
define('HELP_FOR_DEVELOPING_AND_CONDUCTING_IOHC',' <b><center>Developing and Conducting Industry Oriented Hands-on Courses (50)</center></b><br/><table border=1 align=center class=myfontstyle><tr><td>Conceiving, Organizing, Developing IOHC(s) (30)</td><td>1 IOHC = 10, 2 IOHCs = 20, 3 IOHCs = 30</td></tr><tr><td>Executing /Assisting a developed IOHC (20)</td><td>1 IOHC = 5, 2 IOHCs = 10, 3 IOHCs =15, 4 IOHCs = 20</td></tr></table>');

//Help For
define('HELP_FOR_ORGANIZING_SYMPOSIA_CONFERENCES_WORKSHOPS',' <b><center>Organizing Symposia /Conferences /Workshops (S/C/W) (150)</center></b><br/><table border=1 align=center class=myfontstyle><tr><td>Organizing / Convening (S/C/W) National /International</td><td>1 -20, 2-40, 3-60, 4-80</td></tr><tr><td>Organizing / Convening (S/C/W) Institutional Level</td><td>1-10,2-20, 3-30, 4-40</td></tr><tr><td>Member of the committee of S/C/W </td><td>1-5, 2-10, 3-15, 4-20, 5-25, 6-30</td></tr></table>');


//Help For 
define('HELP_FOR_STUDENT_FEEDBACK', '<b><center>Students feedback (50) = (A+B)/2</center></b></br><table border=1 class=myfontstyle><tr><td>Even Semester</td><td>=A</td></tr><tr><td>Odd Semester</td><td>=B</td></tr></table>');

//Help for Reading
define('READING_HELP','Please enter the meter reading of the vehicle at which we are editing this entry ');

//Help for Vehicle Battery
define('HELP_METER_READING','This should be the reading of the meter when the battery is going to be replaced');

//Help for edit Vehicle Tyre
define('HELP_EDIT_VEHICLE_TYRE','Please enter the meter reading of the Vehicle at which we are editing the entry');

//Help for edit used as main or spare
 define('HELP_EDIT_USED_AS_MAIN_SPARE','<b>Main</b>-The tyre would be used as one of the running tyres<br><b>Spare</b>-The tyre would be used as stepney tyre in the vehicle');

 //Help for vehicle accident 
define('HELP_FOR_REMARKS','please add some details of the accident here such as the location of the<br> accident or the damage');

//Help for claim insurance
define('HELP_FOR_INSURANCE_CLAIM','This is the amount filled for claim');
define('HELP_FOR_TOTAL_EXPENSES','This is the total actual expenses incurred on repairs');
define('HELP_FOR_SELF_EXPENSES','This is the total expenses which was spent but was not claimed for insurance');
define('HELP_FOR_LOGGING_CLAIM','This is the amount of money that was filled for claim with the insurance company');

//Help for repair
define('HELP_REPAIR',' Use the below table to add more entries of items that may have changed/repaired  during this service trip to the workshop'); 

//Help for tyre(vehicle type master)
define('HELP_MAIN_TYRES','The tyre information need to be added as the complete tyre maintenance history<br> would be maintained over the period of time for vehicle of this type');

//Help for performance in setup/config master
define('HELP_ABOVE_AVERAGE','This value would be used to determine the list of students shown as above average performers in the teachers analysis panel on the dashboard');
define('HELP_BELOW_AVERAGE','This value would be used to determine the list of students shown as below average performers in the teachers analysis panel on the dashboard');

//Help for Item Name in Item Description
define('HELP_ITEM_NAME','If Category is stationary, and item is black marker pen,<br>item name should Black Marker Pen');
define('HELP_ITEM_CODE','St_BMrkP meaning this is stationary item(St) Black(B) Marker(Mek) Pen(P)');
define('HELP_REORDER_MANAGER','Number below which,if the stock falls,<br>then item has to be ordered and procured from market');


//Help for Item Name in Item Description
define('HELP_CAT_NAME','Example Furniture');
define('HELP_CAT_CODE','Example F');


//Help for Adding Test Type Category
define('HELP_TEST_CAT_NAME','This is the category you can assign to the test. For example, you can assign category as assignment,sessional or any new category.');
define('HELP_TEST_CAT_ABB','This value will be used as abbr for the test. For example, you can enter assignment number and year like <b>ASS1_08,A1_08</b> etc.');
define('HELP_EXAM_TYPE',' This value will show whether this test is internal or external.');
define('HELP_SUB_TYPE','This is the type of the test. For example, type may be <b>theory,practical or training</b>.');
define('HELP_TEST_CAT','There are certain tests whose marks are not entered by the users but automatically calculated by the system. For such tests we set this parameter to NO and user can not create tests of defined test type.');
define('HELP_ATD_CAT','We dont explicitly take any list for test types whose attendance category is test. we just mark the attendance and attendance marks are calculated based on settings defined while tranferring marks.');
define('HELP_COLOR','Whenever we draw graphs the lines or bars will be shown as per the color selected here.');


//Help for config MAster
define('PERCENTAGE_DETAIL_BELOW_AVERAGE','<b>PERCENTAGE_DETAIL_BELOW_AVERAGE</b><br/>This value would be used to determine the list of students shown as below average performers in the teacher analysis panel on the dashboard');
define('PERCENTAGE_DETAIL_ABOVE_AVERAGE','<b>PERCENTAGE_DETAIL_ABOVE_AVERAGE</b><br/>This value would be used to determine the list of students shown as above average performers in the teacher analysis panel on the dashboard');

//Help for FeedBack Category master
define('HELP_CATEGORY_NAME','<b>Category Name</b><br>It denotes the category in which we wish to give the feedback.');
define('HELP_PARENT_CATEGORY','<b>Parent Category</b><br>It denotes the Parent category of the Category selected.<br>For ex :Parent Category of <b>Theory/Practical</b> is <b>Academic</b>.');
define('HELP_RELATIONSHIP','<b>Relationship</b><br>It denotes the relationship of the Category selected.<br>For ex :<b>Hostel</b> Category is related to <b>Hostel</b>');
define('HELP_SUBJECT_TYPE','<b>Subject Type</b><br>It denotes the subject Type of the selected category.<br>For ex :<b>Theory</b> has the subject type as <b>Theory</b>.');
define('HELP_DESCRIPTION','<b>Description</b><br>It is used to add the description of the selected Category');
define('HELP_PRINT_ORDER','<b>Print Order</b><br>It is used to give the preference of the Print Order');





// $History: helpMessage.inc.php $         
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 1/09/10    Time: 12:03p
//Updated in $/LeapCC/Library
//added help, changed table names to defines.
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 3:45p
//Updated in $/LeapCC/Library
//defined message for HELP_ADVANCED_FILTER
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 21/11/09   Time: 15:02
//Updated in $/LeapCC/Library
//Added "Help" option in "Duty Leaves" module in teacher section.
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/02/09   Time: 5:31p
//Updated in $/LeapCC/Library
//help messages updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/30/09   Time: 5:18p
//Created in $/LeapCC/Library
//initial checkin (help Messages added)
//

?>
