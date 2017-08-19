<?php

require_once(BL_PATH . "/MenuCreationClassManager.inc.php");
$menuCreationManager = MenuCreationClassManager::getInstance();

$setupMenu = Array();
$menuCreationManager->addToAllMenus($setupMenu);
$menuCreationManager->setMenuHeading("Setup");


		/**
		 *			ORDER OF KEYS IN ARRAYS IS TO BE MAINTAINED.
		 */

$configMasterArray = Array(
											'moduleName'  => 'ConfigMaster',
											'moduleLabel' => 'Config Master',
											'moduleLink'  => UI_HTTP_PATH . '/configManagement.php',
											'accessArray' => Array('VIEW','','EDIT',''),
											'description' => 'Used for setting configuration values',
											'helpUrl'     => '',
											'videoHelpUrl'=> '',
											'showHelpBar' => true,
											'showSearch' => false

							);

$menuCreationManager->makeSingleMenu($configMasterArray);

$countryMasterArray = Array(
											'moduleName'  => 'CountryMaster',
											'moduleLabel' => 'Country Master',
											'moduleLink'  => UI_HTTP_PATH . '/listCountry.php',
											'accessArray' => '',
											'description' => 'Lets you define the countries that would appear in different countries drop down lists in the application. For example, when entering the addresses',
											'helpUrl'     => 'aa.html',
											'videoHelpUrl'=> '',
											'showHelpBar' => true,
											'showSearch' => true,
											'linkedModulesArray' => ''//array('StateMaster', 'CityMaster')
											);

$stateMasterArray =	Array(
											'moduleName'  => 'StateMaster',
											'moduleLabel' => 'State Master',
											'moduleLink'  => UI_HTTP_PATH . '/listState.php',
											'accessArray' => '',
											'description' => 'Lets you define the states that would appear in different countries drop down lists in the application. For example, when entering the addresses',
											'helpUrl'     => '',
											'videoHelpUrl'=> 'stateMasterHelp.flv',
											'showHelpBar' => true,
											'showSearch' => true,
											'linkedModulesArray' => ''//array('CountryMaster', 'CityMaster')

						 );

$cityMasterArray = Array(
											'moduleName'  => 'CityMaster',
											'moduleLabel' => 'City Master',
											'moduleLink'  => UI_HTTP_PATH . '/listCity.php',
											'accessArray' => '',
											'description' => 'Lets you define the states that would appear in different countries drop down lists in the application. For example, when entering the addresses',
											'helpUrl'     => 'stateMasterHelp.html',
											'videoHelpUrl'=> 'stateMasterHelp.flv',
											'showHelpBar' => false,
											'showSearch' => true,
											'linkedModulesArray' => ''//array('CountryMaster', 'StateMaster')

						);

$addressMasterArray = array();
$addressMasterArray[] = $countryMasterArray;
$addressMasterArray[] = $stateMasterArray;
$addressMasterArray[] = $cityMasterArray;

$menuCreationManager->makeMenu("Address Masters",$addressMasterArray);

$universityArray = Array(
												'moduleName'  => 'UniversityMaster',
												'moduleLabel' => 'University Master',
												'moduleLink'  => UI_HTTP_PATH . '/listUniversity.php',
												'accessArray' => '',
												'description' => 'Lets you define the Universities that the institutes defined in this application would be affiliated to',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true

);

$quotaMasterArray = Array(
												'moduleName'  => 'QuotaMaster',
												'moduleLabel' => 'Quota Master',
												'moduleLink'  =>UI_HTTP_PATH . '/listQuota.php',
												'accessArray' => '',
												'description' => 'Lets you define the different Quotas( SC/ST/Gen/Mgmt/etc ) under which students can take admissions',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => true,
											    'showSearch' => true

);


 $quotaSeatIntakeArray = Array(					'moduleName'  => 'QuotaSeatIntake',                     //Not there
												'moduleLabel' => 'Quota Seat Intake',
												'moduleLink'  =>UI_HTTP_PATH . '/listQuotaSeatIntake.php',
												'accessArray' => '',
												'description' => '',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
							);

$classwiseQuotaAllocationArray = Array(
												'moduleName'  => 'ClasswiseQuotaAllocation',		 //Not there
												'moduleLabel' => 'Class wise Quota Allocation',
												'moduleLink'  =>UI_HTTP_PATH . '/listClasswiseQuotaAllocation.php',
												'accessArray' => '',
												'description' => '',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true

);



$employeeMasterArray = Array(
												'moduleName'  => 'EmployeeMaster',
												'moduleLabel' => 'Employee Master',
												'moduleLink'  =>UI_HTTP_PATH . '/listEmployee.php',
												'accessArray' => '',
												'description' => 'Lets you create a list of employees in your institute with complete details of all the employees',
												'helpUrl'     => 'ConfigMasterHelp.html',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
						    );

$publicationMasterArray = Array(
												'moduleName'  => 'PublicationMaster',
												'moduleLabel' => 'Publication Master',
												'moduleLink'  =>UI_HTTP_PATH . '/listPublication.php',
												'accessArray' => '',
												'description' => '',
												'helpUrl'     => 'ConfigMasterHelp.html',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
						    );

$shortEmployeeMasterArray = Array(
												'moduleName'  => 'ShortEmployeeMaster',  // not there
												'moduleLabel' => 'Employee Master (Guest Faculty)',
												'moduleLink'  => UI_HTTP_PATH . '/listEmployeeShort.php',
												'accessArray' => '',
												'description' => '',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
						    );


$uploadEmployeeDetailArray = Array(
												'moduleName'  => 'UploadEmployeeDetail',
												'moduleLabel' => 'Upload/Export Employee Detail',
												'moduleLink'  => UI_HTTP_PATH . '/listEmployeeInfoUpload.php',
												'accessArray' => ARRAY(ADD),
												'description' => 'Lets you import the list of emmplyees from a pre-formatted excel sheet',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
								);

$designationMasterArray = Array(
												'moduleName'  => 'DesignationMaster',
												'moduleLabel' => 'Designation Master',
												'moduleLink'  => UI_HTTP_PATH . '/listDesignation.php',
												'accessArray' => '',
												'description' => 'Lets you create the different designations which the employees in your institute would carry(eg. Dean, HOD, lecturer, etc )',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
								);

$departmentMasterArray =	Array(
												'moduleName'  => 'DepartmentMaster',
												'moduleLabel' => 'Department Master',
												'moduleLink'  => UI_HTTP_PATH . '/listDepartment.php',
												'accessArray' => '',
												'description' => 'Lets you create the different departments in your institute( eg. Mathematics, Computer science, etc )',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
								);



$offenseMasterArray =	Array(
												'moduleName'  => 'OffenseMaster',
												'moduleLabel' => 'Offense Master',
												'moduleLink'  => UI_HTTP_PATH . '/listOffense.php',
												'accessArray' => '',
												'description' => 'Lets you define the different heads under which you record students disciplinary offences( eg. Late coming, Not in uniform, bunking classes, etc )',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
							 );




$administrativeMastersArray = array();
$administrativeMastersArray[] = $universityArray;
$administrativeMastersArray[] = $quotaMasterArray;
$administrativeMastersArray[] = $quotaSeatIntakeArray;
$administrativeMastersArray[] = $classwiseQuotaAllocationArray;
$administrativeMastersArray[] = $employeeMasterArray;
$administrativeMastersArray[] = $publicationMasterArray;
$administrativeMastersArray[] = $shortEmployeeMasterArray;
$administrativeMastersArray[] = $uploadEmployeeDetailArray;
$administrativeMastersArray[] = $designationMasterArray;
$administrativeMastersArray[] = $departmentMasterArray;
$administrativeMastersArray[] = $offenseMasterArray;



$menuCreationManager->makeMenu("Administrative Masters", $administrativeMastersArray);
/*
$testTypesMasterArray =	Array(
												'moduleName'  => 'TestTypesMaster',
												'moduleLabel' => 'Test Type Master',
												'moduleLink'  => UI_HTTP_PATH . '/listTestType.php',
												'accessArray' => '',
												'description' => 'Lets you create the different types of test types under which tests are taken in the istitute',
												'helpUrl'     => '',
											    'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
							 );             */

$testTypeCategoryMasterArray =	Array(
												'moduleName'  =>'TestTypeCategoryMaster',
												'moduleLabel' => 'Test Type Category Master',
												'moduleLink'  => UI_HTTP_PATH . '/listTestTypeCategory.php',
												'accessArray' => '',
												'description' => 'Lets you create the test type category( eg. Quizzes, Assignments, Surprise tests, etc )',
												'helpUrl'     => '',
											    'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
									 );
$evaluationCrieteriaArray =	Array(
												'moduleName'  =>'EvaluationCrieteria',
												'moduleLabel' =>'Evaluation Criteria',
												'moduleLink'  => UI_HTTP_PATH . '/listEvaluationCriteria.php',
												'accessArray' => ARRAY(VIEW),
												'description' => 'Lets you create the different criteria in terms of weightages to be assigned for different test types when final marks are to be computed',
												'helpUrl'     => '',
											    'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
								 );
$examMastersArray = array();
//$examMastersArray[] = $testTypesMasterArray;
$examMastersArray[] = $testTypeCategoryMasterArray;
$examMastersArray[] = $evaluationCrieteriaArray;
$menuCreationManager->makeMenu( "Exam Masters", $examMastersArray);

$degreeMasterArray = Array(
												'moduleName'  =>'DegreeMaster',
												'moduleLabel' =>'Degree Master',
												'moduleLink'  =>UI_HTTP_PATH . '/listDegree.php',
												'accessArray' => '',
												'description' => 'Lets you create the different degrees that the institute can confer on students(eg. B.Tech, Mt.Tech, MBA, etc)',
												'helpUrl'     => '',
											    'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
						 );
$branchMasterArray = Array(
												'moduleName'  =>'BranchMaster',
												'moduleLabel' =>'Branch Master',
												'moduleLink'  =>UI_HTTP_PATH . '/listBranch.php',
												'accessArray' => '',
												'description' => 'Lets you create the different disciplines/branches under different degrees that the institute has courses in( CSE, Civil, Electrical,etc).',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
						  );

$batchMasterArray =	Array(
												'moduleName'  =>'BatchMaster',
												'moduleLabel' =>'Batch Master',
												'moduleLink'  =>UI_HTTP_PATH . '/listBatch.php',
												'accessArray' => '',
												'description' => 'Lets you create the different batches in which students would be present/admitted( eg. 2007, 2008,2009, etc )',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
						 );
$sessionMasterArray = Array(
												'moduleName'  =>'SessionMaster',
												'moduleLabel' =>'Session Master',
												'moduleLink'  =>UI_HTTP_PATH . '/listSession.php',
												'accessArray' => '',
												'description' => 'Lets you create the different sessions running in the college and on this basic present and past records can be seen(eg. 2008-09, 2009-10, etc)',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
							);

$periodicityMasterArray = Array(
												'moduleName'  =>'PeriodicityMaster',
												'moduleLabel' =>'Periodicity Master',
												'moduleLink'  =>UI_HTTP_PATH . '/listPeriodicity.php',
												'accessArray' => '',
												'description' => 'Lets you create the different periodicities that the different degree programmes in the institutes can have. (eg. some can have Trimester system, Some Semester , etc )',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
							    );

$studyPeriodMasterArray = Array(
												'moduleName'  =>'StudyPeriodMaster',
												'moduleLabel' =>'Study Period Master',
												'moduleLink'  => UI_HTTP_PATH . '/listStudyPeriod.php',
												'accessArray' => '',
												'description' => 'Lets you create the study periods liks "1st Semester", "2nd Semester", etc',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
							   );

$groupTypeMasterArray = Array(
												'moduleName' => 'GroupTypeMaster',
												'moduleLabel' => 'Group Type Master',
												'moduleLink' => UI_HTTP_PATH . '/listGroupType.php',
												'accessArray' => ARRAY(VIEW),
												'description' => 'Lets you create the different group types in an institute( eg. Theory, Practical, Tutorial )',
                                 				'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
							  );

$groupMasterArray =	Array(
												'moduleName' => 'GroupMaster',
												'moduleLabel' => 'Group Master',
												'moduleLink' => UI_HTTP_PATH.'/listGroup.php',
												'accessArray' => '',
												'description' => 'Lets you create the actual group names in which students would be allocated.',
												'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
                         );

$groupSubjectWiseArray = Array(
                                                'moduleName' => 'SubjectWiseOptionalGroup',            // not there
                                                'moduleLabel' => 'Subject Wise Optional Group',
                                                'moduleLink' => UI_HTTP_PATH.'/subjectWiseOptionalGroup.php',
                                                'accessArray' => '',
                                                'description' => '',
                                                'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
                         );


$groupCopyArray = Array(
												'moduleName' => 'GroupCopy',
												'moduleLabel' => 'Copy Groups',
												'moduleLink' => UI_HTTP_PATH.'/copyGroups.php',
												'accessArray' => '',
												'description' => 'Lets you copy groups of students from one group to another.',
												'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
                        );

$periodSlotMasterArray = Array(					'moduleName' => 'PeriodSlotMaster',
												'moduleLabel' => 'Period Slot Master',
												'moduleLink' => UI_HTTP_PATH.'/listPeriodSlot.php',
												'accessArray' => '',
												'description' => 'Lets you create the different period slots that can possibly be used in an institute.( eg. 60 minutes slot, 90 minutes period slots, etc )',
												'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
                               );

$periodsMasterArray = Array(
												'moduleName' => 'PeriodsMaster',
												'moduleLabel' => 'Periods Master',
												'moduleLink' => UI_HTTP_PATH.'/listPeriods.php',
												'accessArray' => '',
												'description' => 'Lets you create the list of periods that would be used in an instituted for purpose of creating and managing the time table(eg. period1, period2...period9, etc).',
												'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
                            );

$createClassArray = Array(
												'moduleName' => 'CreateClass',
												'moduleLabel' => 'Create Class',
												'moduleLink' => UI_HTTP_PATH.'/listClasses.php',
												'accessArray' => '',
												'description' => 'Lets you create the diffrent classes being taught in an institute. A class is defined as a combibation of university+degree+branch+batch+semester. This menu lets you create a list of classes, including   past, currently active and future. ',
												'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
                         );

$assignCourseToClassArray = Array(
												'moduleName' => 'AssignCourseToClass',
												'moduleLabel' => 'Assign Subjects to Class',
												'moduleLink' => UI_HTTP_PATH.'/assignSubjectToClass.php',
												'accessArray' => array('VIEW','','EDIT',''),
												'description' => 'Lets you map the subjects that are to be taught in a particular class.',
												'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
                                 );
$assignOptionalCourseToClassArray = Array(
												'moduleName' => 'AssignOptionalCourseToClass',        //not there
												'moduleLabel' => 'Map Major/Minor Subjects to Class',
												'moduleLink' => UI_HTTP_PATH.'/assignOptionalSubjectToClass.php',
												'accessArray' => '',
												'description' => '',
												'helpUrl' => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => false,
											    'showSearch' => true
                                 );
$attendanceSetMasterArray = Array(
												'moduleName' => 'AttendanceSetMaster',
												'moduleLabel' => 'Attendance Set Master',
												'moduleLink' => UI_HTTP_PATH.'/listAttendanceSet.php',
												'accessArray' => '',
												'description' => 'Lets you create a attendance set for defining marks that are to be given ',
												'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true
                                 );
$classMastersArray = array();
$classMastersArray[] = $degreeMasterArray;
$classMastersArray[] = $branchMasterArray;
$classMastersArray[] = $batchMasterArray;
$classMastersArray[] = $sessionMasterArray;
$classMastersArray[] = $periodicityMasterArray;
$classMastersArray[] = $studyPeriodMasterArray;
$classMastersArray[] = $groupTypeMasterArray;
$classMastersArray[] = $groupMasterArray;
$classMastersArray[] = $groupSubjectWiseArray;
$classMastersArray[] = $groupCopyArray;
$classMastersArray[] = $periodSlotMasterArray;
$classMastersArray[] = $periodsMasterArray;
$classMastersArray[] = $createClassArray;
$classMastersArray[] = $assignCourseToClassArray;
$classMastersArray[] = $assignOptionalCourseToClassArray;
$classMastersArray[] = $attendanceSetMasterArray;

	$menuCreationManager->makeMenu( "Class Masters", $classMastersArray);




	$subjectCategoryArray = Array(
												'moduleName' => 'SubjectCategory',
                                                'moduleLabel' => 'Subject Category Master',
                                                'moduleLink' => UI_HTTP_PATH.'/listSubjectCategory.php',
                                                'accessArray' => '',
                                                'description' => 'Lets you create the subjects categories that are in used typically in MBA courses',
                                 		        'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true
                                   );

$subjectTypesMasterArray = Array(
												'moduleName' => 'SubjectTypesMaster',
                                                'moduleLabel' => 'Subject Type Master',
                                                'moduleLink' => UI_HTTP_PATH.'/listSubjectType.php',
                                                'accessArray' => '',
                                                'description' => 'Lets you create the different subject types like Theory , Practical.',
                                 		        'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true
                                 );

$subjectArray = Array(
												'moduleName' => 'Subject',
                                                'moduleLabel' => 'Subject Master',
                                                'moduleLink' => UI_HTTP_PATH.'/listSubject.php',
                                                'accessArray' => '',
                                                'description' => 'Lets you create the subjects being taught. Complete details of the subjects are entered here. For any subject to be listed in the time table it has to be created here first.',
                                 		        'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true
                     );

$subjectTopicArray = Array(
												'moduleName' => 'SubjectTopic',
                                                'moduleLabel' => 'Subject Topic Master',
                                                'moduleLink' => UI_HTTP_PATH.'/listSubjectTopic.php',
                                                'accessArray' => Array(VIEW,ADD,EDIT,DELETE),
                                                'description' => 'Lets you create the different topics that would be taught in the lectures/practicals for the subject. Topics entered here would appear in the selection list of topics when a teacher takes students attendance. This way the teacher can choose which topic was taught in a particular class and thus at any point know which topics have been covered and which remaining.The topics here are entered one by one.',
                                 		        'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true
                           );

$topicArray = Array(
												'moduleName' => 'UploadTopicDetail',								  //not there
                                                'moduleLabel' => 'Upload Topic Detail',
                                                'moduleLink' => UI_HTTP_PATH.'/subjectTopicUpload.php',
                                                'accessArray' => Array(VIEW,ADD),
                                                'description' => '',
                                 		        'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true
                   );

$bulkSubjectTopicArray = Array(
												'moduleName' => 'BulkSubjectTopic',
                                                'moduleLabel' => 'Bulk Subject Topic Master',
                                                'moduleLink' => UI_HTTP_PATH.'/bulkListSubjectTopic.php',
                                                'accessArray' => ARRAY(VIEW,ADD),
                                                'description' => 'This also allows one to enter the subjects topics to be taught but instead through a faster approach. Multiple topics can be entered into the system in one go using the subject bulk topics master.',
                                 		        'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true
                              );

$attendanceCodesMasterArray = Array(
												'moduleName' => 'AttendanceCodesMaster',
												'moduleLabel' => 'Attendance Code Master',
                                                'moduleLink' => UI_HTTP_PATH.'/listAttendanceCode.php',
                                                'accessArray' => '',
                                                'description' => 'Lets you define the attendance codes that would appear in the drop down when a teacher takes attendance for a class. Typically "A" is absent, "P" is for present. However, one could also define a "OD" as offical duty and assign it 50% attendance.',
                                 		        'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true
                                   );

$resourceCategoryArray = Array(
												'moduleName' => 'ResourceCategory',
												'moduleLabel' => 'Subject Resource Category Master',
                                                'moduleLink' => UI_HTTP_PATH.'/listResourceCategory.php',
                                                'accessArray' => '',
                                                'description' => 'Lets you enter the categories under which the teacher can upload different resource materials. Examples are ppts, docs, assignments, notes, etc.',
                                 		        'helpUrl' => '',
												'videoHelpUrl'=>'',
											    'showHelpBar' => false,
											    'showSearch' => true

                              );
$academicMastersArray = array();
$academicMastersArray[] = $subjectCategoryArray;
$academicMastersArray[] = $subjectTypesMasterArray;
$academicMastersArray[] = $subjectArray;
$academicMastersArray[] = $subjectTopicArray;
$academicMastersArray[] = $topicArray;
$academicMastersArray[] = $bulkSubjectTopicArray;
$academicMastersArray[] = $attendanceCodesMasterArray;
$academicMastersArray[] = $resourceCategoryArray;

$menuCreationManager->makeMenu( "Academic Masters", $academicMastersArray);

$gradeSetMasterArray = Array(
												 'moduleName' => 'GradeSetMaster',			//Not there
                                                 'moduleLabel' => 'Grade Set Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listGradeSet.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => true,
											     'showSearch' => true
                                );

$gradeMasterArray = Array(
												 'moduleName' => 'GradeMaster',				//Not there
                                                 'moduleLabel' => 'Grade Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listGrade.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$applyGradeArray = Array(
												 'moduleName' => 'ApplyGrade',						//Not there
                                                 'moduleLabel' => 'Apply Grades',
                                                 'moduleLink' => UI_HTTP_PATH.'/applyGrade.php',
                                                 'accessArray' => array(ADD),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );

$applyGradesArray1 = Array(
												 'moduleName' => 'ApplyGrades',     // not there
                                                 'moduleLabel' => 'Apply Grade(Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/applyGrades.php',
                                                 'accessArray' => array(ADD),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );

$applyGradesArray = Array(
                                                 'moduleName' => 'ApplyGradesAdvance',     // not there
                                                 'moduleLabel' => 'Apply Grade(Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listApplyGradeAdvance.php',
                                                 'accessArray' => array(ADD),
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl'=>'',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );                         

$cgpaCalculationArray = Array(
												 'moduleName' => 'CgpaCalculation',      // not there
                                                 'moduleLabel' => 'Calculate CGPA',
                                                 'moduleLink' => UI_HTTP_PATH.'/cgpaCalculation.php',
                                                 'accessArray' => array(ADD),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );
                         
$assignFinalGrade = Array(
                                                 'moduleName' => 'AssignFinalGrade',      // not there
                                                 'moduleLabel' => 'Assign Final Grade',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAssignFinalGrade.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl'=>'',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                          );      
                          
$attendanceDeduct = Array(
                                                 'moduleName' => 'AttendanceDeductSlab',      // not there
                                                 'moduleLabel' => 'Attendance Deduct Slab',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAttendanceDeductSlab.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl'=>'',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                          );                                                  
                         
$studentFinalGradeReportArray = Array(
                                                 'moduleName' => 'StudentFinalGrade',      // not there
                                                 'moduleLabel' => 'Apply Student Final Grade',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentFinalGrade.php',
                                                 'accessArray' => array(VIEW),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl'=>'',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );                         

$gradeMastersArray = array();
$gradeMastersArray[] = $gradeSetMasterArray;
$gradeMastersArray[] = $gradeMasterArray;
$gradeMastersArray[] = $applyGradeArray;
$gradeMastersArray[] = $applyGradesArray;
$gradeMastersArray[] = $cgpaCalculationArray;
$gradeMastersArray[] = $attendanceDeduct;
$gradeMastersArray[] = $assignFinalGrade;
$gradeMastersArray[] = $studentFinalGradeReportArray;


$menuCreationManager->makeMenu( "Grade Masters",$gradeMastersArray);

$vehicleRouteMasterNew = Array(
						'moduleName' => 'VehicleRouteMasterNew',    // not there
                                                 'moduleLabel' => 'Vehicle Route Master New',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBusRouteNew.php',
                                                 'accessArray' => '',
                                                 'description' => 'This lets you To Mapp Bus No With Route Name',
                                 		  'helpUrl' => '',
						 'videoHelpUrl'=>'',
					     	'showHelpBar' => false,
					     	'showSearch' => true
                               );

$vehicleStopCityArray = Array(
						 'moduleName' => 'BusStopCityMaster',    // not there
                                                 'moduleLabel' => 'Vehicle Stop City Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/busStopCityMaster.php',
                                                 'accessArray' => '',
                                                 'description' => 'This lets you create the different City For vehicle Stop , like for example Chandigarh, Panchkula,Mohali etc
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );
                           

$vehicleStopArray = Array(
						 'moduleName' => 'BusStopMaster',    // not there
                                                 'moduleLabel' => 'Vehicle Stop Master New',
                                                 'moduleLink' => UI_HTTP_PATH.'/busStopMaster.php',
                                                 'accessArray' => '',
                                                 'description' => 'This lets you create the different Stops For vehicle Stop , like for example sec 17 ,19, etc
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );
                              
$vehicleStopRouteMappingArray = Array(
						 'moduleName' => 'VehicleStopRouteMapping',    // not there
                                                 'moduleLabel' => 'Vehicle Stop Route Mapping',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBusStopRouteMapping.php',
                                                 'accessArray' => '',
                                                 'description' => 'This lets you Map bus Stop with route number
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );
                            
                              
$vehicleRouteAllocationArray = Array(
						 'moduleName' => 'VehicleRouteAllocation',    // not there
                                                 'moduleLabel' => 'Vehicle Route Allocation',
                                                 'moduleLink' => UI_HTTP_PATH.'/vehicleRouteAllocation.php',
                                                 'accessArray' => '',
                                                 'description' => 'This lets you Map bus Stop with Student
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );
                            
$busFeeMasterArray = Array(
						 'moduleName' => 'VehicleFeeMaster',    // not there
                                                 'moduleLabel' => 'Vehicle Fee Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/busFeeMaster.php',
                                                 'accessArray' => '',
                                                 'description' => 'This lets you to define a fee for bus stop city
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );
                               
$fleetManagementArrayNew = array();
$fleetManagementArrayNew[] = $vehicleRouteMasterNew;
$fleetManagementArrayNew[] = $vehicleStopCityArray;
$fleetManagementArrayNew[] = $vehicleStopArray;
$fleetManagementArrayNew[] = $vehicleStopRouteMappingArray;
$fleetManagementArrayNew[] = $vehicleRouteAllocationArray;
$fleetManagementArrayNew[] = $busFeeMasterArray;
$menuCreationManager->makeMenu( "Fleet Management New", $fleetManagementArrayNew);
                               


$vehicleTypeMasterArray = Array(
												 'moduleName' => 'VehicleTypeMaster',    // not there
                                                 'moduleLabel' => 'Vehicle Type Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleType.php',
                                                 'accessArray' => '',
                                                 'description' => 'This lets you create the different types of  vehicles , like for example Buses, cars, scoters, etc
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$vehicleInsuranceMasterArray = Array(
												 'moduleName' => 'VehicleInsuranceMaster',   // not there
                                                 'moduleLabel' => 'Insurance Company Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleInsurance.php',
                                                 'accessArray' => '',
                                                 'description' => 'this lets you enter the names of the different companies from which you get insurance done for your vehicles. This information is later used in reports when you may want to know , for example, the total insurance business you may have given to a particular company, or you may want to know how many vehicles are being insured by a particular company, etc.
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true

                                    );

$vehicleArray = Array(
												 'moduleName' => 'Vehicle',					// not there
                                                 'moduleLabel' => 'Vehicle  Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicle.php',
                                                 'accessArray' => '',
                                                 'description' => 'This is where you record the details of all the vehicles your organizations owns. Comprehensive vehicle information , including vehicle type, registration information, insurance information, tyres information, battery information, etc is recorded here.
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                    );
$fuelMasterArray = Array(
												 'moduleName' => 'FuelMaster',                  // not there






                                                 'moduleLabel' => 'Fuel Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFuel.php',
                                                 'accessArray' => '',
                                                 'description' => 'This lets you record fuel data for your vehicle including information like amount of fuel put, date, at what mileage, on what date, by whom, etc. Correctly entering information in this module would enable you to see accurate fuel usage reports for single or a group of vehicles later on. These reports are useful for seeing usage trends and can easily help point out the discrepancies.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                        );
$vehicleTyreMasterArray = Array(
												 'moduleName' => 'VehicleTyreMaster',					//not there
                                                 'moduleLabel' => 'Purchase/Replace Tyre',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleTyre.php',
                                                 'accessArray' => '',
                                                 'description' => 'This module lets you record the information regarding the usage of the tyres in the vehicle . Just like fuel, tyres are also a consumable item , though with a longer life than fuel. Whenever new tyres replace the older one, this module lets you track the history of the tyre usage to know that typres are not being prematurely changed and that they are serving their full usage.
',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$tyreRetreadingArray = Array(
												 'moduleName' => 'TyreRetreading',					//not there
                                                 'moduleLabel' => 'Tyre Retreading',
                                                 'moduleLink' => UI_HTTP_PATH.'/listTyreRetreading.php',
                                                 'accessArray' => '',
                                                 'description' => 'This module lets you record information about tyres that are being re-treaded so that the software can later generate reports on the life of different tyres to make sure that they are serving their full life before going in for retreading.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                            );

$insuranceVehicleArray = Array(
												 'moduleName' => 'InsuranceVehicle',				//not there
                                                 'moduleLabel' => 'Vehicle Insurance',
                                                 'moduleLink' => UI_HTTP_PATH.'/listInsuranceVehicle.php',
                                                 'accessArray' => '',
                                                 'description' => 'This module lets you add/update the insurance information related to the vehicles. It helps you set up insurance reminders for various vehicles.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$insuranceVehicleAutopayArray = Array(
												 'moduleName' => 'InsuranceVehicleAutopay',				//not there
                                                 'moduleLabel' => 'Vehicle Insurance (Autopay)',
                                                 'moduleLink' => UI_HTTP_PATH.'/vehicleInsurance.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,






											     'showSearch' => true
                               );



$insuranceClaimArray = Array(
												 'moduleName' => 'InsuranceClaim',				//not there
                                                 'moduleLabel' => 'Vehicle Insurance Claim',
                                                 'moduleLink' => UI_HTTP_PATH.'/listInsuranceClaim.php',
                                                 'accessArray' => '',
                                                 'description' => 'This module lets you enter the insurance claims information into the application. Later reports can be generated based on this to know the accident history, the amount claimed and the amount received in the claim from different insurance companies.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );

$vehicleAccidentArray = Array(
												 'moduleName' => 'VehicleAccident',									//not there
                                                 'moduleLabel' => 'Vehicle Accident',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleAccident.php',
                                                 'accessArray' => '',
                                                 'description' => 'This module lets you record and maintain the accident history of different vehicles',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$vehicleServiceRepairArray = Array(
												 'moduleName' => 'VehicleServiceRepair',							//not there
                                                 'moduleLabel' => 'Vehicle Service cum Repair',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleServiceRepair.php',
                                                 'accessArray' => '',
                                                 'description' => 'This is an extremely important module and helps you track the complete vehicle service and repair expenses history of the vehicle. Based on this ,  one can see which vehicles are asking for a lot of repair and which less. What areas of the vehicles are asking for continuous repair, etc . Are there some trends or co-relation between the vehicles plying on certain routes and the repair cost, etc. This information of recorded correctly can give the users of the application a lot of rich and meaningful data relating to vehicle repair and services.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$vehicleBatteryArray = Array(
												 'moduleName' => 'VehicleBattery',										//not there
                                                 'moduleLabel' => 'Vehicle Battery',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleBattery.php',
                                                 'accessArray' => '',
                                                 'description' => 'This module is used to record the information regarding the history of changes for the batteries in the vehicle.Just like we record the information about the tyres and are able to generate the usage reports for the tyres, similarly we can do the same for the batteries.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                            );

$vehicleTaxArray = Array(
												 'moduleName' => 'VehicleTax',										//not there
                                                 'moduleLabel' => 'Vehicle Tax',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleTax.php',
                                                 'accessArray' => array('VIEW','ADD'),
                                                 'description' => 'This is an extremely important module and lets the user record information regarding the various taxes the vehicle may have to pay for meeting regulatory compliances. Through this module the user can be kept updated of the tax due dates preventing tax. Payment defaults which may lead to monetary penalties by govt. authorities.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                        );

$busRouteMasterArray = Array(
												 'moduleName' => 'BusRouteMaster',
                                                 'moduleLabel' => 'Vehicle Route Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBusRoute.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create the details of the bus routes on which the different buses would ply.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );

$busStopCourseArray = Array(
												 'moduleName' => 'BusStopCourse',
                                                 'moduleLabel' => 'Vehicle Stop Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBusStop.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create the details of the different bus stops at which the buses would stop.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );
$busRouteStopMappingArray = Array(
						  'moduleName' => 'BusRouteStopMapping',
                                                 'moduleLabel' => 'Bus Route Stop Mapping',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBusRouteStopMapping.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you Map different stops to one route.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );

$transportStaffMasterArray = Array(
												 'moduleName' => 'TransportStaffMaster',										//not there
                                                 'moduleLabel' => 'Transport Staff Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listTransportStaff.php',
                                                 'accessArray' => '',
                                                 'description' => 'This module lets you record information regarding the staff that would responsible for running the vehicles, ie drivers and staff that accompanies them, ie conductors/helpers.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );

$fleetManagementArray = array();
$fleetManagementArray[] = $vehicleTypeMasterArray;
$fleetManagementArray[] = $vehicleInsuranceMasterArray;
$fleetManagementArray[] = $vehicleArray;
$fleetManagementArray[] = $fuelMasterArray;
$fleetManagementArray[] = $vehicleTyreMasterArray;
$fleetManagementArray[] = $tyreRetreadingArray;
$fleetManagementArray[] = $insuranceVehicleArray;
$fleetManagementArray[] = $insuranceVehicleAutopayArray;
$fleetManagementArray[] = $insuranceClaimArray;
$fleetManagementArray[] = $vehicleAccidentArray;
$fleetManagementArray[] = $vehicleServiceRepairArray;
$fleetManagementArray[] = $vehicleBatteryArray;
$fleetManagementArray[] = $vehicleTaxArray;
$fleetManagementArray[] = $busRouteMasterArray;
$fleetManagementArray[] = $busStopCourseArray;
$fleetManagementArray[] = $busRouteStopMappingArray;
$fleetManagementArray[] = $transportStaffMasterArray;
$menuCreationManager->makeMenu( "Fleet Management", $fleetManagementArray);

$buildingMasterArray = Array(
												 'moduleName' => 'BuildingMaster',
                                                 'moduleLabel' => 'Building Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBuilding.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the details of the different buildings which are on campus',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );

$blockCourseArray = Array(
												 'moduleName' => 'BlockCourse',
                                                 'moduleLabel' => 'Block Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBlock.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the details of blocks in different buildings on the campus',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true

                           );

$roomTypeMasterArray = Array(
												 'moduleName' => 'RoomTypeMaster',											//not there
                                                 'moduleLabel' => 'Room Type Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listRoomType.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );

$roomsMasterArray = Array(
												 'moduleName' => 'RoomsMaster',
                                                 'moduleLabel' => 'Rooms Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listRoom.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create the different rooms in the academic block where classes would be held',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                          );

$buildingMastersArray = array();
$buildingMastersArray[] = $buildingMasterArray;
$buildingMastersArray[] = $blockCourseArray;
$buildingMastersArray[] = $roomTypeMasterArray;
$buildingMastersArray[] = $roomsMasterArray;
	$menuCreationManager->makeMenu( "Building Masters", $buildingMastersArray);


$hostelMasterArray = Array(
												 'moduleName' => 'HostelMaster',
                                                 'moduleLabel' => 'Hostel Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostel.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the details of the different hostels',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                           );

$hostelRoomTypeArray = Array(
												 'moduleName' => 'HostelRoomType',
                                                 'moduleLabel' => 'Hostel Room Type Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostelRoomType.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the details of the different types of rooms which may be available in the hostels. eg. AcRooms, DoubleRooms, SingleRooms, etc',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );

$hostelRoomTypeDetailArray = Array(
												 'moduleName' => 'HostelRoomTypeDetail',
                                                 'moduleLabel' => 'Hostel Room Type Detail Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostelRoomTypeDetail.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the actual facilities in the different rooms types defined in the Room type master',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$hostelRoomCourseArray = Array(
												 'moduleName' => 'HostelRoomCourse',
                                                 'moduleLabel' => 'Hostel Room Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostelRoom.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the different room names in a hostel.eg. R213, R456,C432',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );
$hostelFeeMasterArray = Array(
												 'moduleName'   => 'HostelFeeMaster',							//not there
                                                 'moduleLabel'  =>'Hostel Fee Master',
                                                 'moduleLink'   =>  UI_HTTP_PATH . '/Fee/hostelFeeMaster.php',
                                                 'accessArray'  =>'',
                                                 'description'  => '',
                                 		         'helpUrl'      => '',
												 'videoHelpUrl' => '',
											     'showHelpBar'  => false,
											     'showSearch'   => true
                              );

$complaintCategoryArray = Array(
												 'moduleName' => 'ComplaintCategory',
                                                 'moduleLabel' => 'Complaint Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostelComplaintCat.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you record complaints related to hostel facilitities. eg. plumbing, fan not working, etc',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$disciplineCategoryArray = Array(
												 'moduleName' => 'DisciplineCategory',										//not there
                                                 'moduleLabel' => 'Discipline Category Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostelDisciplineCat.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$hostelVisitorArray = Array(
												 'moduleName' => 'HostelVisitor',
                                                 'moduleLabel' => 'Visitor Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostelVisitor.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you keep a track of visitors to the hostel.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                            );

$temporaryDesignationMasterArray = Array(
												 'moduleName' => 'TemporaryDesignationMaster',
                                                 'moduleLabel' => 'Temporary Designation Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listDesignationTemp.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create designations for class employees responsible for upkeep of hostel facilities.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                                         );
$cleaningMasterArray = Array(
												 'moduleName' => 'CleaningMaster',												//not there
                                                 'moduleLabel' => 'Cleaning Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listCleaningRecord.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=>'',
											     'showHelpBar' => false,
											     'showSearch' => true
                            );

$temporaryEmployeeArray = Array(
												 'moduleName' => 'TemporaryEmployee',
                                                 'moduleLabel' => 'Temporary Employee Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listEmployeeTemp.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create class employees responsible for upkeep of hostel facilities.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl'=> '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );


$hostelMastersArray = array();
$hostelMastersArray[] = $hostelMasterArray;
$hostelMastersArray[] = $hostelRoomTypeArray;
$hostelMastersArray[] = $hostelRoomTypeDetailArray;
$hostelMastersArray[] = $hostelRoomCourseArray;
$hostelMastersArray[] = $hostelFeeMasterArray;
$hostelMastersArray[] = $complaintCategoryArray;
$hostelMastersArray[] = $disciplineCategoryArray;
$hostelMastersArray[] = $hostelVisitorArray;
$hostelMastersArray[] = $temporaryDesignationMasterArray;
$hostelMastersArray[] = $temporaryEmployeeArray;
$hostelMastersArray[] = $cleaningMasterArray;


$menuCreationManager->makeMenu( "Hostel Masters",$hostelMastersArray);

$feeCycleMasterArrayNew = Array(
						'moduleName' => 'FeeCycleMasterNew',
                                                 'moduleLabel' => 'Fee Cycle Master New',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/listFeeCycle.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create multiple fees cycles under which fees can be defined and subsequently taken.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );


$feeHeadsArrayNew = Array(
						 'moduleName' => 'FeeHeadsNew',
                                                 'moduleLabel' => 'Fee Head New',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/listFeeHead.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define what are all the possible heads under which fees can be taken from students.',
                                 		 'helpUrl' => '',
						 'videoHelpUrl' => '',
					     	 'showHelpBar' => false,
					         'showSearch' => true
                        );
                        
$feeHeadValuesArrayNew = Array(
						'moduleName' => 'FeeHeadValuesNew',
                                                 'moduleLabel' => 'Fee Head Values New',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/listFeeHeadValues.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the actualy fees amounts under various heads that need to be taken as fees for specific fees cycles and from students of specific classes/courses.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                           );
                           
$studentAdhocConcessionNew = Array(
                                                 'moduleName' => 'StudentAdhocConcessionNew',
                                                 'moduleLabel' => 'Student Adhoc Concession New',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/listStudentAdhocConcession.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                             );
							 
		 
                         
$generateStudentFees = Array(
                                                 'moduleName' => 'GenerateStudentFees',
                                                 'moduleLabel' => 'Generate Student Fees',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/generateStudentFees.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                  'helpUrl' => '',

                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                             );

$classFineSetUpArray = Array(
                                                 'moduleName' => 'ClassFineSetUp',
                                                 'moduleLabel' => 'Class Fine Setup',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/classFineSetUp.php',
                                                 'accessArray' => '',
                                                 'description' => 'to set fine for classes',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                             );
                             
$feeMastersArrayNew = array();
$feeMastersArrayNew[] = $feeCycleMasterArrayNew;
$feeMastersArrayNew[] = $feeHeadsArrayNew;
$feeMastersArrayNew[] = $feeHeadValuesArrayNew;
$feeMastersArrayNew[] = $studentAdhocConcessionNew;
$feeMastersArrayNew[] = $classFineSetUpArray;
//$feeMastersArrayNew[] = $generateStudentFees;


$menuCreationManager->makeMenu( "Fee Masters New",$feeMastersArrayNew);
                       

                             

$feeHeadsArray = Array(     			 'moduleName' => 'FeeHeads',
                                                 'moduleLabel' => 'Fee Head',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeeHead.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define what are all the possible heads under which fees can be taken from students.',
                         		         'helpUrl' => '',
						 'videoHelpUrl' => '',
					         'showHelpBar' => false,
					         'showSearch' => true
                        );

$feeHeadValuesArray = Array(
												 'moduleName' => 'FeeHeadValues',
                                                 'moduleLabel' => 'Fee Head Values',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeeHeadValues.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the actualy fees amounts under various heads that need to be taken as fees for specific fees cycles and from students of specific classes/courses.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                           );

$feeCycleFinesArray = Array(
												 'moduleName' => 'FeeCycleFines',
                                                 'moduleLabel' => 'Fee Cycle Fine Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeeCycleFine.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the fines for paying fees late for a particular fees cycle',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                           );

$feeCycleMasterArray = Array(
												 'moduleName' => 'FeeCycleMaster',
                                                 'moduleLabel' => 'Fee Cycle Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeeCycle.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create multiple fees cycles under which fees can be defined and subsequently taken.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );

$feeConcessionCategoryArray = Array(
												 'moduleName' => 'FeeConcessionMaster',
                                                 'moduleLabel' => 'Fee Concession Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeeConcessionCategory.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );


$fundAllocationMasterArray = Array(
												 'moduleName' => 'FundAllocationMaster',
                                                 'moduleLabel' => 'Fund Allocation Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeeFundAllocation.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the account heads under which fees collected would go. eg. University, Institute',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$bankMasterArray = Array(
												 'moduleName' => 'BankMaster',
                                                 'moduleLabel' => 'Bank Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBank.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the different banks in which fees can be deposited.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );

/*$feeCycleClassesArray = Array(
                                                 'moduleName' => 'FeeCycleClasses',
                                                 'moduleLabel' => 'Map Fee Cycle To Classes',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeeCycleClasses.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => ''
                         );
$studentConcessionArray = Array(
                                                 'moduleName' => 'StudentConcession',                                        //Not there
                                                 'moduleLabel' => 'Student Concession',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentFeeConcession.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                );
*/

$classWiseConcessionMappingArray = Array(
                                                 'moduleName' => 'ClassWiseHeadMapping',                                        //Not there
                                                 'moduleLabel' => 'Define Concession Value',
                                                 'moduleLink' => UI_HTTP_PATH.'/listDefineConcessionValue.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                );
                                
$studentConcessionMappingArray = Array(
                                                 'moduleName' => 'StudentFeeConcessionMapping',                                        //Not there
                                                 'moduleLabel' => 'Student Fee Concession Mapping',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentConcessionMapping.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                );                                

$studentMiscChargesArray = Array(
                                                 'moduleName' => 'StudentMiscCharges',                                        //Not there
                                                 'moduleLabel' => 'Student Misc Charges',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentMiscCharges.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                );
                                
$studentAdhocConcession = Array(
                                                 'moduleName' => 'StudentAdhocConcession',
                                                 'moduleLabel' => 'Student Adhoc Concession',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentAdhocConcession.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                             );                                



$feeMastersArray = array();
$feeMastersArray[] = $bankMasterArray;
$feeMastersArray[] = $fundAllocationMasterArray;
$feeMastersArray[] = $feeCycleMasterArray;
//$feeMastersArray[] = $feeCycleClassesArray;
$feeMastersArray[] = $feeHeadsArray;
$feeMastersArray[] = $feeHeadValuesArray;
$feeMastersArray[] = $feeCycleFinesArray;
$feeMastersArray[] = $feeConcessionCategoryArray;  
$feeMastersArray[] = $classWiseConcessionMappingArray;
$feeMastersArray[] = $studentConcessionMappingArray;
$feeMastersArray[] = $studentAdhocConcession;
$feeMastersArray[] = $studentMiscChargesArray;


$menuCreationManager->makeMenu( "Fee Masters",$feeMastersArray);

$createTimeTableLabelsArray = Array(
												 'moduleName' => 'CreateTimeTableLabels',
                                                 'moduleLabel' => 'Time Table Label Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listTimeTableLabel.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create the time table names. eg. TT-Jan-Jun10',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );
$associateTimeTableToClassArray = Array(
												 'moduleName' => 'AssociateTimeTableToClass',
                                                 'moduleLabel' => 'Associate Time Table to Class',
                                                 'moduleLink' => UI_HTTP_PATH.'/assignTimeTableToClass.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you map which time table applies to which class.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                        );

$timeTableMastersArray = array();
$timeTableMastersArray[] = $createTimeTableLabelsArray;
$timeTableMastersArray[] = $associateTimeTableToClassArray;
$menuCreationManager->makeMenu( "Time Table Masters",$timeTableMastersArray);
$roleMasterArray = Array(
												 'moduleName' => 'RoleMaster',
                                                 'moduleLabel' => 'Role Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listRole.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the different roles that can use the application.eg. HOD, Time table manager, Exam controller, etc',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                       );
/*
/*
$rolePermissionsArray = Array(
												 'moduleName' => 'RolePermissions',
                                                 'moduleLabel' => 'Role Permissions',
                                                 'moduleLink' => UI_HTTP_PATH.'/rolePermission.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you define the permissions for the roles, ie which role is allowed to perform which functions in the application.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );*/
$roleNewPermissionsArray = Array(
												 'moduleName' => 'RolePermissions',
                                                 'moduleLabel' => 'Role Permissions',
                                                 'moduleLink' => UI_HTTP_PATH.'/rolePermissions.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you define the permissions for the roles, ie which role is allowed to perform which functions in the application.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );


$rolePermissionsArray = Array(
												 'moduleName' => 'RolePermissions',
                                                 'moduleLabel' => 'Role Permissions',
                                                 'moduleLink' => UI_HTTP_PATH.'/rolePermissions.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you define the permissions for the roles, ie which role is allowed to perform which functions in the application.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );


$teacherRolePermissionsArray = Array(
												 'moduleName' => 'TeacherRolePermissions',
                                                 'moduleLabel' => 'Teacher Permissions',
                                                 'moduleLink' => UI_HTTP_PATH.'/teacherPermission.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => 'Lets you define what all is the teacher allowed to do in the application.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );

$studentRolePermissionsArray = Array(
												 'moduleName' => 'StudentRolePermissions',
                                                 'moduleLabel' => 'Student Permissions',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentPermission.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => 'Lets you define what all is the student allowed to do in the application.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$parentRolePermissionsArray = Array(
												 'moduleName' => 'ParentRolePermissions',
                                                 'moduleLabel' => 'Parent Permissions',
                                                 'moduleLink' => UI_HTTP_PATH.'/parentPermission.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => 'Lets you define what all is the parent allowed to do in the application.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$managementRolePermissionsArray = Array(
												 'moduleName' => 'ManagementRolePermissions',
                                                 'moduleLabel' => 'Management Permissions',
                                                 'moduleLink' => UI_HTTP_PATH.'/managementPermission.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => 'Lets you define what all is the management allowed to do in the application.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                       );

$manageUsersArray = Array(
												 'moduleName' => 'ManageUsers',
                                                 'moduleLabel' => 'Manage Users',
                                                 'moduleLink' => UI_HTTP_PATH.'/listManageUser.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you create/edit/delete users who can log into the application. Users can also be given multiple roles from here. eg. An employee can be a teacher and an HOD as well.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );
$roleToClassArray = Array(
												 'moduleName' => 'RoleToClass',
                                                 'moduleLabel' => 'Academic Head Privileges',
                                                 'moduleLink' => UI_HTTP_PATH.'/roleToClass.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the permissions of HODs',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );

$userManagementArray = array();
$userManagementArray[] = $roleMasterArray;
//$userManagementArray[] = $rolePermissionsArray;
$userManagementArray[] = $roleNewPermissionsArray;
$userManagementArray[] = $teacherRolePermissionsArray;
$userManagementArray[] = $studentRolePermissionsArray;
$userManagementArray[] = $parentRolePermissionsArray;
$userManagementArray[] = $managementRolePermissionsArray;
$userManagementArray[] = $manageUsersArray;
$userManagementArray[] = $roleToClassArray;
$menuCreationManager->makeMenu( "User Management",$userManagementArray);

$admitArray = Array(
												 'moduleName' => 'Admit',
                                                 'moduleLabel' => 'Admit Student',
                                                 'moduleLink' => UI_HTTP_PATH.'/admitStudent.php',
                                                 'accessArray' => ARRAY(ADD),
                                                 'description' => 'Lets you admit a student in a class.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                   );

$approveStudentArray = Array(
                                                 'moduleName' => 'ApproveStudentMaster',
                                                 'moduleLabel' => 'Approve Student Registration ',
                                                 'moduleLink' => UI_HTTP_PATH.'/listApproveStudents.php',
                                                 'accessArray' => ARRAY(VIEW,EDIT),
                                                 'description' => 'Lets you approve student login',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                   );

$studentProgramFeeArray = Array(
                                                 'moduleName' => 'StudentProgramFee',
                                                 'moduleLabel' => 'Student Program Fee Master ',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentProgramFee.php',
                                                 //'accessArray' => ARRAY(VIEW,EDIT),
                                                 'description' => 'Lets you create program fee to be choosen by the students',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                   );

$updatePasswordReportArray = Array(
												 'moduleName' => 'UpdatePasswordReport',
                                                 'moduleLabel' => 'Generate Student Login',
                                                 'moduleLink' => UI_HTTP_PATH.'/updatePassword.php',
                                                 'accessArray' => ARRAY(ADD),
                                                 'description' => 'Lets you generate single or multiple student logins so that they can then login into the application. The students should have been admitted first in order to generate his/her login.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$assignRollNumbersArray = Array(
												 'moduleName' => 'AssignRollNumbers',
                                                 'moduleLabel' => 'Assign Roll Numbers',
                                                 'moduleLink' => UI_HTTP_PATH.'/assignRollNo.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you generate the roll numbers of student in a bulk manner. Roll number patterns can also be defined from this screen.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );
$uploadStudentGroupArray = Array(
												 'moduleName' => 'UploadStudentGroup',
                                                 'moduleLabel' => 'Upload Student Group',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentGroupUpload.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you upload a group of students from a pre-foratted excel sheet',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                 );

$uploadStudentRollNoArray = Array(
												 'moduleName' => 'UploadStudentRollNo',
                                                 'moduleLabel' => 'Upload/Download Student Roll No./Univ. Roll No.',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentRollNoUpload.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you assign roll numbers to a group of students by uploading a pre-formatted excel sheet',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$uploadStudentDetailArray = Array(
												 'moduleName' => 'UploadStudentDetail',
                                                 'moduleLabel' => 'Upload Student Detail',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentDetailUpload.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you upload the students to be admitted from a pre-formatted excel sheet instead of doing it one by one through the admit screen. This saves time when a lot of students need to be admitted.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                 );

$quarantineStudentMasterArray = Array(
												 'moduleName' => 'QuarantineStudentMaster',
                                                 'moduleLabel' => 'Delete Students',
                                                 'moduleLink' => UI_HTTP_PATH.'/listQuarantineStudent.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you delete a student. For example, after admission if a student cancels his/her admission, his/her names needs to be deleted so that it does not figure out in any of the groups, time tables, reports, etc. The student is however not deleted permanently from the database and can be restored later.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );

$restoreStudentMasterArray = Array(
												 'moduleName' => 'RestoreStudentMaster',
                                                 'moduleLabel' => 'Restore Students',
                                                 'moduleLink' => UI_HTTP_PATH.'/listRestoreStudent.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you restore deleted students.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$studentClassRollNoArray = Array(
												 'moduleName' => 'StudentClassRollNo',
                                                 'moduleLabel' => 'Update Student Class/Roll No.',
                                                 'moduleLink' => UI_HTTP_PATH.'/updateStudentClassRollNo.php',
                                                 'accessArray' => ARRAY(VIEW,ADD),
                                                 'description' => 'Lets you update/edit student classes or roll numbers in bulk by uploading a pre-formatted excel sheet.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                               );

$createParentLoginArray = Array(
												 'moduleName' => 'CreateParentLogin',
                                                 'moduleLabel' => 'Generate Parent Logins',
                                                 'moduleLink' => UI_HTTP_PATH.'/createParentLogin.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you generate the logins for parents of students. After doing this activity, the user can print letters for parents which have the instructions for logging in and mail them to the parents.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$assignGroupAdvancedArray = Array(
											     'moduleName' => 'AssignGroupAdvanced',
                                                 'moduleLabel' => 'Assign Group to Students (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/assignGroupAdvanced.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you put students of different classes in various group( eg. theory , practical, tutorial )',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );
$assignOptionalSubjectsArray = Array(
												 'moduleName' => 'AssignOptionalSubjects',
                                                 'moduleLabel' => 'Assign Optional Subjects to Students',
                                                 'moduleLink' => UI_HTTP_PATH.'/assignGroupOptionalSubject.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you assign the optional subjects to students. This is typically used for MBA flows where students undergo specializations in their second years and choose from a range of subjects',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );
$assignOptionalSubjectslistArray = Array(
												 'moduleName' => 'AssignOptionalSubjectsList',
                                                 'moduleLabel' => 'Assign Optional Subjects to Students',
                                                 'moduleLink' => UI_HTTP_PATH.'/assignGroupOptionalSubjectList.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you assign the optional subjects to students. This is typically used for MBA flows where students undergo specializations in their second years and choose from a range of subjects',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );


$updateStudentGroupsArray = Array(
												 'moduleName' => 'UpdateStudentGroups',
                                                 'moduleLabel' => 'Update Student Groups',
                                                 'moduleLink' => UI_HTTP_PATH.'/listUpdateStudentGroup.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you change student groups',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$changeStudentBranchArray = Array(
												 'moduleName' => 'ChangeStudentBranch',										//not there
                                                 'moduleLabel' => 'Change Student Branch',
                                                 'moduleLink' => UI_HTTP_PATH.'/changeStudentBranch.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                 );

$graceMarksArray = Array(
												 'moduleName' => 'GraceMarks',
                                                 'moduleLabel' => 'Grace Marks',
                                                 'moduleLink' => UI_HTTP_PATH.'/listGraceMarks.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you manage the grace marks that may be assigned to students as part of their final evaluations.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                        );


$appraisalTabMenuArray =  Array(
												 'moduleName' => 'AppraisalTab',										//Not there
                                                 'moduleLabel' => 'Appraisal Tab Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAppraisalTab.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );


$appraisalTitleArray =  Array(
												 'moduleName' => 'AppraisalTitle',									//Not there
                                                 'moduleLabel' => 'Appraisal Title Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAppraisalTitle.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );


$appraisalQuestionMasterArray =  Array(
												 'moduleName' => 'AppraisalQuestionMaster',							//Not there
                                                 'moduleLabel' => 'Appraisal Question Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAppraisalMaster.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$appraisalEmployeeHierarchyArray =  Array(
                                                 'moduleName' => 'EmployeeHierarchy',								//Not there
                                                 'moduleLabel' => 'Employee Hierarchy',
                                                 'moduleLink' => UI_HTTP_PATH.'/listEmployeeHierarchy.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => 'This module is used to create hierarchical relation between employees of an intitute',
                                                 'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$appraisalEmployeeAppraisalArray =  Array(
                                                 'moduleName' => 'EmployeeAppraisal',							//Not there
                                                 'moduleLabel' => 'Employee Appraisal',
                                                 'moduleLink' => UI_HTTP_PATH.'/listEmployeeAppraisal.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => 'This module is used to appraise employees',
                                                 'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );
$appraisalEmployeeReportArray =  Array(
                                                 'moduleName' => 'AppraisalReport',                            //Not there
                                                 'moduleLabel' => 'Appraisal Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/appraisalReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => 'This module is used for appraisal report',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                );
$appraisalUploadArray =  Array(
												 'moduleName' => 'UploadAppraisalDetail',									//Not there
                                                 'moduleLabel' => 'Employee Appraisal Upload Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/appraisalDetailUpload.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => true,
											     'showSearch' => true
                                );

$blockStudentArray = Array(
												 'moduleName' => 'BlockStudent',							//not there
                                                 'moduleLabel' => 'Block Student',
                                                 'moduleLink' => UI_HTTP_PATH.'/blockStudent.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );



$studentSetupArray = array();


$studentSetupArray[] = $admitArray;
$studentSetupArray[] = $approveStudentArray;
$studentSetupArray[] = $studentProgramFeeArray;
$studentSetupArray[] = $updatePasswordReportArray;
$studentSetupArray[] = $assignRollNumbersArray;
$studentSetupArray[] = $uploadStudentGroupArray;
$studentSetupArray[] = $uploadStudentRollNoArray;
$studentSetupArray[] = $uploadStudentDetailArray;
$studentSetupArray[] = $quarantineStudentMasterArray;
$studentSetupArray[] = $restoreStudentMasterArray;
$studentSetupArray[] = $studentClassRollNoArray;
$studentSetupArray[] = $createParentLoginArray;
$studentSetupArray[] = $assignGroupAdvancedArray;
//$studentSetupArray[] = $assignOptionalSubjectsArray;
$studentSetupArray[] = $assignOptionalSubjectslistArray;
$studentSetupArray[] = $updateStudentGroupsArray;
 
$studentSetupArray[] = $graceMarksArray;

//$studentSetupArray[] = $studentConcessionArray;

$menuCreationManager->makeMenu( "Student Setup", $studentSetupArray);

if(defined('SHOW_EMPLOYEE_APPRAISAL_FORM') and SHOW_EMPLOYEE_APPRAISAL_FORM == 1) {
	$appraisalMastersArray[] = $appraisalTabMenuArray;
	$appraisalMastersArray[] = $appraisalTitleArray;
	$appraisalMastersArray[] = $appraisalQuestionMasterArray;
    $appraisalMastersArray[] = $appraisalEmployeeHierarchyArray;
    $appraisalMastersArray[] = $appraisalEmployeeAppraisalArray;
    $appraisalMastersArray[] = $appraisalEmployeeReportArray;
	$appraisalMastersArray[] = $appraisalUploadArray;
	$menuCreationManager->makeMenu( "Appraisal Masters",$appraisalMastersArray);
}
$menuCreationManager->makeSingleMenu($blockStudentArray);


$studentInfoArray = Array(
												 'moduleName'  => 'StudentInfo',
												 'moduleLabel' => 'Find Student',
												 'moduleLink'  => UI_HTTP_PATH . '/searchStudent.php',
												 'accessArray' => Array(VIEW),
												 'description' => 'Used for adding students',
												 'helpUrl'     => 'student info help',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
						 );

$menuCreationManager->makeHeadingMenu($studentInfoArray);

$timeTableMenu = Array();
$menuCreationManager->addToAllMenus($timeTableMenu);
$menuCreationManager->setMenuHeading("Time Table");


$createTimeTableAdvancedSubjectArray = Array(
                                                 'moduleName' => 'CreateTimeTableSubjectWiseAdvanced',
                                                 'moduleLabel' => 'Subject Wise (Weekly) Time Table',
                                                 'moduleLink' => UI_HTTP_PATH.'/createTimeTableSubjectWise.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you create a subjectwise time table. Here you choose a class as a pivot and enter other values to create the time table',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                     );

$createTimeTableAdvancedArray = Array(
												 'moduleName' => 'CreateTimeTableAdvanced',
                                                 'moduleLabel' => 'Class Wise (Weekly)',
                                                 'moduleLink' => UI_HTTP_PATH.'/createTimeTableAdvanced.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you create a classwise time table. Here you choose a class as a pivot and enter other values to create the time table',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );

$createTimeTableAdvancedDateWiseArray = Array(
												 'moduleName' => 'CreateTimeTableAdvancedDateWise',					//not there
                                                 'moduleLabel' => 'Class Wise Date Wise (Daily)',
                                                 'moduleLink' => UI_HTTP_PATH.'/createTimeTableAdvancedDayWise.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                              );

$createTimeTableClassWiseDayWiseArray = Array(
												 'moduleName' => 'CreateTimeTableClassWiseDayWise',
                                                 'moduleLabel' => 'Class and Day Wise (Weekly)',
                                                 'moduleLink' => UI_HTTP_PATH.'/createTimeTableClassWiseDayWise.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => 'Lets you create a classwise and daywise time table. Here you choose a class as the first pivot and day as the second pivot and enter other values to create the time table',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                             );

$createTimeTableClassWiseDayWiseRoomWiseArray = Array(
												 'moduleName' => 'CreateTimeTableClassWiseDayWiseRoomWise',
												 'moduleLabel' => 'Class,Day and Room Wise (Weekly)',
												 'moduleLink' => UI_HTTP_PATH.'/createTimeTableClassWiseDayWiseRoomWise.php',
												 'accessArray' => Array(EDIT),
												 'description' => 'Lets you create a classwise , daywise and room wise time table. Here you choose a class as the first pivot ,day as the second pivot and Room as the third pivot enter other values to create the time table',
                                 				 'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
													 );




$manageTimeTableArray = array();
$manageTimeTableArray[] = $createTimeTableAdvancedSubjectArray;
$manageTimeTableArray[] = $createTimeTableAdvancedArray;
$manageTimeTableArray[] = $createTimeTableAdvancedDateWiseArray;
$manageTimeTableArray[] = $createTimeTableClassWiseDayWiseArray;
$manageTimeTableArray[] = $createTimeTableClassWiseDayWiseRoomWiseArray;
$menuCreationManager->makeMenu( "Manage Time Table", $manageTimeTableArray);

$displayTeacherTimeTableArray = Array(
												 'moduleName' => 'DisplayTeacherTimeTable',
                                                 'moduleLabel' => 'Teacher',
                                                 'moduleLink' => UI_HTTP_PATH.'/displayTeacherTimeTable.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you see any teachers time table',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );

$displayStudentTimeTableArray = Array(
												 'moduleName' => 'DisplayStudentTimeTable',
                                                 'moduleLabel' => 'Student',
                                                 'moduleLink' => UI_HTTP_PATH.'/displayStudentTimeTable.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you see any students time table',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );

$displayClassTimeTableArray = Array(
												 'moduleName' => 'DisplayClassTimeTable',
                                                 'moduleLabel' => 'Class',
                                                 'moduleLink' => UI_HTTP_PATH.'/displayClassTimeTable.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you see the time table of any class',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );

$displayRoomTimeTableArray = Array(
												 'moduleName' => 'DisplayRoomTimeTable',
                                                 'moduleLabel' => 'Room',
                                                 'moduleLink' => UI_HTTP_PATH.'/displayRoomTimeTable.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you see the time table for a particular room',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );

$displayTimeTableArray = array();
$displayTimeTableArray[] = $displayTeacherTimeTableArray;
$displayTimeTableArray[] = $displayStudentTimeTableArray;
$displayTimeTableArray[] = $displayClassTimeTableArray;
$displayTimeTableArray[] = $displayRoomTimeTableArray;
$menuCreationManager->makeMenu( "Display Time Table", $displayTimeTableArray);

$displayLoadTeacherTimeTableArray = Array(
												 'moduleName' => 'DisplayLoadTeacherTimeTable',						//not there
                                                 'moduleLabel' => 'Display Teacher Load',
                                                 'moduleLink' => UI_HTTP_PATH . '/displayLoadTeacherTimeTable.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );

$menuCreationManager->makeSingleMenu($displayLoadTeacherTimeTableArray);
$teacherSubstitutionsArray = Array(
												 'moduleName' => 'TeacherSubstitutions',							//not there
                                                 'moduleLabel' => 'Display Teacher Substitutions',
                                                 'moduleLink' => UI_HTTP_PATH . '/listTeacherSubstitutions.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($teacherSubstitutionsArray);
$displayTimeTableReportArray = Array(
												 'moduleName' => 'DisplayTimeTableReport',						//not there
                                                 'moduleLabel' =>'Display Multi Utility Time Table',
                                                 'moduleLink' => UI_HTTP_PATH . '/displayTimeTable.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($displayTimeTableReportArray);
$occupiedFreeClassArray = Array(
												 'moduleName' => 'OccupiedFreeClass',								//not there
                                                 'moduleLabel' => 'Occupied/Free Class(s)/Room(s) Wise Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listOccupiedClassReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                              );

$menuCreationManager->makeSingleMenu($occupiedFreeClassArray);

/*
$extraClassesTimeTableArray = Array(
												 'moduleName' => 'ExtraClassesTimeTable',								//not there
                                                 'moduleLabel' =>'Create Extra Classes Time Table',
                                                 'moduleLink' => UI_HTTP_PATH . '/extraClassesTimeTable.php',
                                                 'accessArray' => ARRAY(EDIT),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );*/
//$menuCreationManager->makeSingleMenu($extraClassesTimeTableArray);


$communicationMenu = array();
$menuCreationManager->addToAllMenus($communicationMenu);
$menuCreationManager->setMenuHeading("Communication");
/*
$noticesMenu = array();
$menuCreationManager->addToAllMenus($noticesMenu);
$menuCreationManager->setMenuHeading("Notices");
*/
$addEventArray = Array(
												 'moduleName' => 'AddEvent',											//not there
                                                 'moduleLabel' =>'Manage Events',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listCalendar.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($addEventArray);


$addNoticesArray = Array(
												 'moduleName' => 'AddNotices',									//not there
                                                 'moduleLabel' =>'Manage Notices',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listNotice.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true

                                      );
$menuCreationManager->makeSingleMenu($addNoticesArray);

/*
$messagingArray = array();
$menuCreationManager->addToAllMenus($messagingMenu);
$menuCreationManager->setMenuHeading("Messaging");
*/
$sendMessageToStudentsArray = Array(
												 'moduleName' => 'SendMessageToStudents',                                //not there
                                                 'moduleLabel' =>'Send Message to Students',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listAdminStudentMessage.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($sendMessageToStudentsArray);

$sendMessageToParentsArray = Array(
												 'moduleName' => 'SendMessageToParents',								//not there
                                                 'moduleLabel' =>'Send Message To Parents',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listAdminParentMessage.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($sendMessageToParentsArray);


$sendMessageToEmployeesArray = Array(
												 'moduleName' => 'SendMessageToEmployees',							//not there
                                                 'moduleLabel' =>'Send Message To Employees',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listAdminEmployeeMessage.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($sendMessageToEmployeesArray);


$sendMessageToNumbersArray = Array(
												 'moduleName' => 'SendMessageToNumbers',					//not there
                                                 'moduleLabel' =>'Send bulk SMS',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listSendAdminMessage.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($sendMessageToNumbersArray);
$sendStudentPerformanceMessageToParentsArray = Array(
												 'moduleName' => 'SendStudentPerformanceMessageToParents',            //not there
                                                 'moduleLabel' =>'Send Student Performance Message to Parents',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listStudentPerformanceMessage.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($sendStudentPerformanceMessageToParentsArray);

$sendMessageParentMailBoxArray = Array(
												 'moduleName' => 'SendMessageParentMailBox',							//not there
                                                 'moduleLabel' =>'Send Message to Parents Mail Box',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listParentMailBox.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($sendMessageParentMailBoxArray);

$broadcastMessageArray = Array(
                                                 'moduleName' => 'BroadcastMessage',                            //not there
                                                 'moduleLabel' =>'Broadcast Message',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listBroadcastMessage.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($broadcastMessageArray);


$notificationsArray = Array(
                                                 'moduleName' => 'Notifications',                            //not there
                                                 'moduleLabel' =>'Notifications',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listNotifications.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($notificationsArray);

$eventMasterArray =	Array(
												'moduleName'  => 'EventMaster',
												'moduleLabel' => 'Greeting Master',
												'moduleLink'  => UI_HTTP_PATH . '/listGreetings.php',
												'accessArray' => '',
												'description' => 'Lets you define the different events  )',
												'helpUrl'     => '',
												'videoHelpUrl'=> '',
											    'showHelpBar' => 'false',
											    'showSearch' => true
							 );

$menuCreationManager->makeSingleMenu($eventMasterArray);

$feeArray = array();
$menuCreationManager->addToAllMenus($feeMenu);
$menuCreationManager->setMenuHeading("Fee");
$collectFeesArray = Array(
												 'moduleName' => 'CollectFees',					            //not there
                                                 'moduleLabel' =>'Collect Fees',
                                                 'moduleLink' => UI_HTTP_PATH . '/studentFeeReceipt.php',          //'moduleLink' => UI_HTTP_PATH . '/feeReceipt.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($collectFeesArray);

$collectFeesArrayNew = Array(
						'moduleName' => 'CollectFeesNew',					            //not there
                                                 'moduleLabel' =>'Collect Fees New',
                                                 'moduleLink' => UI_HTTP_PATH . '/Fee/studentFeeReceipt.php',          //'moduleLink' => UI_HTTP_PATH . '/feeReceipt.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($collectFeesArrayNew);

$studentGenerateSlipNew = Array(
                                                 'moduleName' => 'GenerateStudentSlip',
                                                 'moduleLabel' => 'Generate Student Slip',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/listStudentGenerateSlip.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                             );	
$menuCreationManager->makeSingleMenu($studentGenerateSlipNew);						 

$feeLedgerArray = Array(
						'moduleName' => 'FeeLedger',
                                                 'moduleLabel' => 'Fee Ledger',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/listFeeLedger.php',
                                                 'accessArray' => '',
                                                 'description' => 'Display all Fee Transaction of Student and we can be able to add Debit/Credit .',
                                 		 'helpUrl' => '',
						 'videoHelpUrl' => '',
						 'showHelpBar' => false,
						 'showSearch' => true
                             );


$menuCreationManager->makeSingleMenu($feeLedgerArray);
$feeUploadArray = Array(
												 'moduleName' => 'FeeUpload',										//not there
                                                 'moduleLabel' =>'Import Fee',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/listFeeUpload.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($feeUploadArray);

$feeReceiptStatusArray = Array(
												 'moduleName' => 'FeeReceiptStatus',							//not there
                                                 'moduleLabel' =>'Fee Receipt Status',
                                                 'moduleLink' =>  UI_HTTP_PATH . '/feeReceiptStatus.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => 'feeReceiptStatus.html',
												 'videoHelpUrl' => '',
											     'showHelpBar' => true,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($feeReceiptStatusArray);

$fineArray = array();
$menuCreationManager->addToAllMenus($fineMenu);
$menuCreationManager->setMenuHeading("Fine");
$fineCategoryMasterArray = Array(
												 'moduleName' => 'FineCategoryMaster',						//not there
                                                 'moduleLabel' =>'Fine/Activity Category Master',
                                                 'moduleLink' => UI_HTTP_PATH . '/listFineCategory.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($fineCategoryMasterArray);

$assignFinetoRolesArray = Array(
												 'moduleName' => 'AssignFinetoRoles',							//not there
                                                 'moduleLabel' =>'Assign Role to Fines/Activities Mapping Master',
                                                 'moduleLink' => UI_HTTP_PATH . '/assignFineToRole.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($assignFinetoRolesArray);



$fineStudentMasterArray = Array(
												 'moduleName' => 'FineStudentMaster',							//not there
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


$fineListArray = Array(
												 'moduleName' => 'FineList',									//not there
                                                 'moduleLabel' =>'Student Fine/Activity Approval',
																 'moduleLink'  => UI_HTTP_PATH . '/fineReport.php',
                                                 'accessArray' =>ARRAY(VIEW,ADD),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($fineListArray);
$collectFineArray = Array(
												 'moduleName' => 'CollectFine',									//not there
                                                 'moduleLabel' =>'Collect Fine/Activity Amount',
                                                 'moduleLink' => UI_HTTP_PATH . '/fineReceipt.php',
                                                 'accessArray' =>'',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
$menuCreationManager->makeSingleMenu($collectFineArray);
$bulkFineStudentMasterArray = Array(
												 'moduleName' => 'BulkFineStudentMaster',						//not there
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
$activitiesArray = array();
$menuCreationManager->addToAllMenus($activitiesMenu);
$menuCreationManager->setMenuHeading("Activities");

$transferInternalMarksAdvancedArray = Array(
												 'moduleName' => 'TransferInternalMarksAdvanced',
                                                 'moduleLabel' => 'Transfer Internal Marks (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/transferInternalMarksAdvanced.php',
                                                 'accessArray' =>Array(ADD,VIEW),
                                                 'description' => 'Lets you perform the function of transferring the internal marks. Transfer of marks involves the process of computing the students collective marks based on the internal assesments done over a period of time and according to the weightages assigned to these assesments.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                           );

$uploadStudentExternalMarksArray = Array(
												 'moduleName' => 'UploadStudentExternalMarks',
                                                 'moduleLabel' => 'Upload External Marks',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentMarksUpload.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you enter the external( typically the final university exam ) marks for students',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                         );

$updateTotalMarksArray = Array(
												 'moduleName' => 'UpdateTotalMarks',
                                                 'moduleLabel' => 'Update Total Marks',
                                                 'moduleLink' => UI_HTTP_PATH.'/updateTotalMarks.php',
                                                 'accessArray' =>Array(EDIT),
                                                 'description' => 'Lets you perform the function of updating marks at the leaf level, after transfer and grading has been done.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                           );
$attendancePercentArray = Array(
												 'moduleName' => 'AttendancePercent',
                                                 'moduleLabel' => 'Attendance Marks Percent',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAttendanceMarksPercent.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the relation between how many marks to be given to students on the basis of the attendance percentage. This would be applicable only if the institute has awarded marks attendance as part of the final assesment.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true



                               );

$LecturePercentArray = Array(
											     'moduleName' => 'LecturePercent',
                                                 'moduleLabel' => 'Attendance Marks Slabs',
                                                 'moduleLink' => UI_HTTP_PATH.'/listLecturePercent.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you define the relation between how many marks to be given to students on the basis of the lectures attended slabs. This would be applicable only if the institute has awarded marks attendance as part of the final assesment.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                            );

$promoteStudentsArray = Array(
												 'moduleName' => 'PromoteStudents',						//not there
                                                 'moduleLabel' => 'Promote Students',
                                                 'moduleLink' => UI_HTTP_PATH.'/promoteStudents.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );

$promoteStudentsAdvancedArray = Array(
												 'moduleName' => 'PromoteStudentsAdvanced',
                                                 'moduleLabel' => 'Promote Students (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/promoteStudentsAdvanced.php',
                                                 'accessArray' => ARRAY(ADD),
                                                 'description' => 'Lets you promote the students.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );

$frozenTimeTableToClassArray = Array(
												 'moduleName' => 'FrozenTimeTableToClass',
                                                 'moduleLabel' => 'Freeze/Backup Data',
                                                 'moduleLink' => UI_HTTP_PATH.'/frozenTimeTableToClass.php',
                                                 'accessArray' => array(VIEW,ADD),
                                                 'description' => 'Lets you Freeze the data so that the marks and attendance cannot be changed by mistake.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );

                                     

$examActivitiesArray = array();
$examActivitiesArray[] = $transferInternalMarksAdvancedArray;
$examActivitiesArray[] = $uploadStudentExternalMarksArray;
$examActivitiesArray[] = $updateTotalMarksArray;
$examActivitiesArray[] = $attendancePercentArray;
$examActivitiesArray[] = $LecturePercentArray;
$examActivitiesArray[] = $promoteStudentsArray;
//$examActivitiesArray[] = $promoteStudentsAdvancedArray;
$examActivitiesArray[] = $frozenTimeTableToClassArray;
$menuCreationManager->makeMenu( "Exam Activities", $examActivitiesArray);
$bulkAttendanceArray = Array(
												 'moduleName' => 'BulkAttendance',
                                                 'moduleLabel' => 'Bulk Attendance',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBulkAttendance.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you enter students attendance over a period of time.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );

$dailyAttendanceArray = Array(
												 'moduleName' => 'DailyAttendance',
                                                 'moduleLabel' => 'Daily Attendance',
                                                 'moduleLink' => UI_HTTP_PATH.'/Teacher/listDailyAttendance.php',
                                                 'accessArray' => '',
                                                 'description' => 'Used for taking daily attendance.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );
                              

$extraClassConductedArray = Array(
                                                 'moduleName' => 'ExtraClassAttendance',
                                                 'moduleLabel' => 'Extra Class Conducted by Faculty ',
                                                 'moduleLink' => UI_HTTP_PATH.'/listExtraClass.php',
                                                 'accessArray' => '',
                                                 'description' => 'Used for taking Extra Class Conducted by Faculty.',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                              );
                              

$dutyLeaveEventsArray = Array(
												 'moduleName' => 'DutyLeaveEvents',											//not there
                                                 'moduleLabel' => 'Duty Leave Events Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listDutyLeaveEvents.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );

$dutyLeaveUploadArray = Array(
												 'moduleName' => 'DutyLeaveUpload',												//not there
                                                 'moduleLabel' => 'Upload Duty Leave Entries',
                                                 'moduleLink' => UI_HTTP_PATH.'/dutyLeaveUpload.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );

$dutyLeaveConflictReportArray = Array(
												 'moduleName' => 'DutyLeaveConflictReport',									//not there
                                                 'moduleLabel' => 'Duty Leave Conflict Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/dutyLeaveConflictReport.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );
$dutyLeaveConflictReportAdminArray = Array(
												 'moduleName' => 'DutyLeaveConflictAdminReport',									//not there
                                                 'moduleLabel' => 'Duty Leave Conflict Report Admin',
                                                 'moduleLink' => UI_HTTP_PATH.'/dutyLeaveConflictAdminReport.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );
$medicalLeaveUploadArray = Array(
						'moduleName' => 'MedicalLeaveUpload',//not there
                                                'moduleLabel' => 'Upload Medical Leave Entries',
                                                'moduleLink' => UI_HTTP_PATH.'/medicalLeaveUpload.php',
                                                'accessArray' => '',
                                                'description' => '',
                                 		'helpUrl' => '',
						'videoHelpUrl' => '',
						'showHelpBar' => false,
						'showSearch' => true
                             );

$medicalLeaveConflictReportArray = Array(
						 'moduleName' => 'MedicalLeaveConflictReport',//not there
                                                 'moduleLabel' => 'Medical Leave Conflict Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/medicalLeaveConflictReport.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                               		         'helpUrl' => '',
						 'videoHelpUrl' => '',
                       			         'showHelpBar' => false,
						 'showSearch' => true
                                     );
                                     
$medicalLeaveConflictAdminReportArray = Array(
						 'moduleName' => 'MedicalLeaveConflictAdminReport',//not there
                                                 'moduleLabel' => 'Medical Leave Conflict Report Admin',
                                                 'moduleLink' => UI_HTTP_PATH.'/medicalLeaveConflictAdminReport.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                               		         'helpUrl' => '',
						 'videoHelpUrl' => '',
                       			         'showHelpBar' => false,
						 'showSearch' => true
                                     );

$deleteAttendanceArray = Array(
												 'moduleName' => 'DeleteAttendance',
                                                 'moduleLabel' => 'Delete Attendance',
                                                 'moduleLink' => UI_HTTP_PATH.'/deleteAttendance.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you delete students attendance. You may want to do this if the attendance entered is wrongly entered by mistake.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$testMarksArray = Array(
												 'moduleName' => 'TestMarks',
                                                 'moduleLabel' => 'Test Marks',
                                                 'moduleLink' => UI_HTTP_PATH.'/listEnterAssignmentMarks.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you enter the marks for a student.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                       );

$changeTestCategoryArray = Array(
												 'moduleName' => 'ChangeTestCategory',										//not there
                                                 'moduleLabel' => 'Change Test Category',
                                                 'moduleLink' => UI_HTTP_PATH.'/changeTestCategory.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$marksandAttendanceArray = array();
$marksandAttendanceArray[] = $bulkAttendanceArray;
$marksandAttendanceArray[] = $dailyAttendanceArray;
$marksandAttendanceArray[] = $extraClassConductedArray;
$marksandAttendanceArray[] = $dutyLeaveEventsArray;
$marksandAttendanceArray[] = $dutyLeaveUploadArray;
$marksandAttendanceArray[] = $dutyLeaveConflictReportArray;
$marksandAttendanceArray[] = $dutyLeaveConflictReportAdminArray;
$marksandAttendanceArray[] = $medicalLeaveUploadArray;
$marksandAttendanceArray[] = $medicalLeaveConflictReportArray;
$marksandAttendanceArray[] = $medicalLeaveConflictAdminReportArray;
$marksandAttendanceArray[] = $deleteAttendanceArray;
$marksandAttendanceArray[] = $testMarksArray;
$marksandAttendanceArray[] = $changeTestCategoryArray;
$menuCreationManager->makeMenu( "Marks and Attendance",$marksandAttendanceArray);

$allowIpArray = Array(
                                                 'moduleName' => 'AllowIp',                                        //not there
                                                 'moduleLabel' => 'Allow IP Registration',
                                                 'moduleLink' => UI_HTTP_PATH.'/RegistrationForm/listAllowIp.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                );

$changeStudentStatusArray = Array(
												 'moduleName' => 'UploadStudentStatus',										//not there
                                                 'moduleLabel' => 'Upload Student Status',
                                                 'moduleLink' => UI_HTTP_PATH.'/RegistrationForm/studentStatusUpload.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );
								
$changeUploadStudentStatusArray = Array(
												 'moduleName' => 'ScholarStatusDetails',										//not there
                                                 'moduleLabel' => 'Scholar Status Detail',
                                                 'moduleLink' => UI_HTTP_PATH.'/RegistrationForm/scholarStatusDetails.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );
$uploadMentorshipArray = Array(
												 'moduleName' => 'MentorshipUpload',										//not there
                                                 'moduleLabel' => 'Mentorship Upload',
                                                 'moduleLink' => UI_HTTP_PATH.'/RegistrationForm/mentorshipUpload.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );	
$changeMentorshipArray = Array(
												 'moduleName' => 'ChangeMentor',										//not there
                                                 'moduleLabel' => 'Change Mentor',
                                                 'moduleLink' => UI_HTTP_PATH.'/RegistrationForm/changeMentor.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );	
$registrationReportArray = Array(
												 'moduleName' => 'StudentRegistrationReport',										//not there
                                                 'moduleLabel' => 'Student Registration Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/RegistrationForm/registrationReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );		

$mentorshipArray = array();
$mentorshipArray[] = $allowIpArray; 
$mentorshipArray[] = $changeStudentStatusArray;
$mentorshipArray[] = $changeUploadStudentStatusArray;
$mentorshipArray[] = $uploadMentorshipArray;
$mentorshipArray[] = $changeMentorshipArray;
$mentorshipArray[] = $registrationReportArray;

$menuCreationManager->makeMenu( "Mentorship Programme",$mentorshipArray);
$employeeIcardReportArray = Array(
												 'moduleName' => 'EmployeeIcardReport',
                                                 'moduleLabel' => 'Employee I-Card',
                                                 'moduleLink' => UI_HTTP_PATH.'/employeeIcard.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you generate employee I-cards',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                 );

$employeeBusPassArray = Array(
												 'moduleName' => 'EmployeeBusPass',									//not there
                                                 'moduleLabel' => 'Employee Bus Pass',
                                                 'moduleLink' => UI_HTTP_PATH.'/employeeBusPass.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );

$studentIcardReportArray = Array(
												 'moduleName' => 'StudentIcardReport',
                                                 'moduleLabel' => 'Student I-Card',
                                                 'moduleLink' => UI_HTTP_PATH.'/icard.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you generate student I-cards',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$studentBusPassArray = Array(
												 'moduleName' => 'StudentBusPass',
                                                 'moduleLabel' => 'Student Bus Pass',
                                                 'moduleLink' => UI_HTTP_PATH.'/createBusPass.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you generate student bus passes',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                             );


$idGenerationArray = array();
$idGenerationArray[] = $employeeIcardReportArray;
$idGenerationArray[] = $employeeBusPassArray;
$idGenerationArray[] = $studentIcardReportArray;
$idGenerationArray[] = $studentBusPassArray;
$menuCreationManager->makeMenu( "ID Generation", $idGenerationArray);
$aDVFB_AnswerSetArray = Array(
												 'moduleName' => 'ADVFB_AnswerSet',									// not there
                                                 'moduleLabel' => 'Answer Set',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackAnswerSetAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );

$aDVFB_OptionsArray = Array(
												 'moduleName' => 'ADVFB_Options',									// not there
                                                 'moduleLabel' => 'Answer Set Options',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackOptionsAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                           );
$aDVFB_QuestionSetArray = Array(
												 'moduleName' => 'ADVFB_QuestionSet',											// not there
                                                 'moduleLabel' => 'Feedback Question Set Master (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackQuestionSetAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$aDVFB_QuestionsArray = Array(
												 'moduleName' => 'ADVFB_Questions',												// not there
                                                 'moduleLabel' => 'Feedback Questions Master (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackQuestionsAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );
$aDVFB_LabelsArray = Array(
												 'moduleName' => 'ADVFB_Labels',													// not there
                                                 'moduleLabel' => 'Feedback Label Master (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedBackLabelAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );
$aDVFB_CategoryMasterArray = Array(
												 'moduleName' => 'ADVFB_CategoryMaster',													// not there
                                                 'moduleLabel' => 'Feedback Category Master (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackCategoryAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                 );

$aDVFB_QuestionMappingMasterArray = Array(
												 'moduleName' => 'ADVFB_QuestionMappingMaster',												// not there
                                                 'moduleLabel' => 'Feedback Questions Mapping(Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackQuestionMappingAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                         );

$aDVFB_TeacherMappingArray = Array(
												 'moduleName' => 'ADVFB_TeacherMapping',										// not there
                                                 'moduleLabel' => 'Teacher Mapping',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackTeacherMapping.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$aDVFB_AssignSurveyMasterArray = Array(
												 'moduleName' => 'ADVFB_AssignSurveyMaster',									// not there
                                                 'moduleLabel' => 'Feedback Assign Survey (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAssignSurveyAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );

/*$aDVFB_AssignSurveyMasterReportArray = Array(
												 'moduleName' => 'ADVFB_AssignSurveyMasterReport',										// not there
                                                 'moduleLabel' => 'Feedback Assign Survey Report (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAssignSurveyReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                            );
*/

$aDVFB_AssignSurveyMasterLabelWiseReportArray = Array(
												 'moduleName' => 'ADVFB_AssignSurveyMasterLabelWiseReport',								// not there
												 'moduleLabel' => 'Feedback Label Wise Survey Report (Advanced)',
												 'moduleLink' => UI_HTTP_PATH.'/listAssignSurveyLabelWiseReport1.php',
												 'accessArray' => '',
												 'description' => '',
                                 				 'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
													 );


$aDVFB_CollegeGpaReportArray = Array(
												 'moduleName' => 'ADVFB_CollegeGpaReport',											// not there
                                                 'moduleLabel' => 'Feedback College GPA Report (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackCollegeGpaReport1.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                    );

$aDVFB_ClassFinalReportArray = Array(
												 'moduleName' => 'ADVFB_ClassFinalReport',											// not there
                                                 'moduleLabel' => 'Feedback Class Final Report (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackClassFinalReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );

$aDVFB_TeacherDetailedGpaReportArray = Array(
												 'moduleName' => 'ADVFB_TeacherDetailedGpaReport',										// not there
                                                 'moduleLabel' => 'Feedback Teacher Detailed GPA Report (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackTeacherDetailedGpaReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                            );
/*
$aDVFB_TeacherFinalReportArray = Array(
												 'moduleName' => 'ADVFB_TeacherFinalReport',												// not there
                                                 'moduleLabel' => 'Feedback Teacher Final Report (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackTeacherFinalReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                      );
*/
$aDVFB_CommentsReportArray = Array(
												 'moduleName' => 'ADVFB_CommentsReport',													// not there
                                                 'moduleLabel' => 'Feedback Comments Report (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackCommentsReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );
$aDVFB_TeacherCategoryResponseReportArray = Array(
												 'moduleName' => 'ADVFB_TeacherCategoryResponseReport',													// not there
                                                 'moduleLabel' => 'Feedback Category Response Report (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackCategoryResponseReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 			     'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                                  );
$aDVFB_EmployeeGPAReportArray = Array(
												 'moduleName' => 'ADVFB_EmployeeGPAReport',														// not there
                                                 'moduleLabel' => 'Feedback Employee GPA Report (Advanced)',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackEmployeeGPAReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );

$newFeedbackReportArray = Array(
                                                 'moduleName' => 'Feedback_Report',
                                                 'moduleLabel' => 'Feedback Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedbackReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                                                  );

$feedbackAdvancedArray = array();
$feedbackAdvancedArray[] = $aDVFB_AnswerSetArray;
$feedbackAdvancedArray[] = $aDVFB_OptionsArray;
$feedbackAdvancedArray[] = $aDVFB_QuestionSetArray;
$feedbackAdvancedArray[] = $aDVFB_QuestionsArray;
$feedbackAdvancedArray[] = $aDVFB_LabelsArray;
$feedbackAdvancedArray[] = $aDVFB_CategoryMasterArray;
$feedbackAdvancedArray[] = $aDVFB_QuestionMappingMasterArray;
$feedbackAdvancedArray[] = $aDVFB_TeacherMappingArray;
$feedbackAdvancedArray[] = $aDVFB_AssignSurveyMasterArray;
//$feedbackAdvancedArray[] = $aDVFB_AssignSurveyMasterReportArray;
$feedbackAdvancedArray[] = $aDVFB_AssignSurveyMasterLabelWiseReportArray;
$feedbackAdvancedArray[] = $aDVFB_CollegeGpaReportArray;
$feedbackAdvancedArray[] = $aDVFB_ClassFinalReportArray;
$feedbackAdvancedArray[] = $aDVFB_TeacherDetailedGpaReportArray;
//$feedbackAdvancedArray[] = $aDVFB_TeacherFinalReportArray;
$feedbackAdvancedArray[] = $aDVFB_CommentsReportArray;
$feedbackAdvancedArray[] = $aDVFB_TeacherCategoryResponseReportArray;
$feedbackAdvancedArray[] = $aDVFB_EmployeeGPAReportArray;
$feedbackAdvancedArray[] = $newFeedbackReportArray;
$menuCreationManager->makeMenu( "Feedback Advanced", $feedbackAdvancedArray);

$createFeedBackLabelsArray = Array(
												 'moduleName' => 'CreateFeedBackLabels',										// not there
                                                 'moduleLabel' => 'Label Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedBackLabel.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );
$feedBackCategoryMasterArray = Array(
												 'moduleName' => 'FeedBackCategoryMaster',												// not there
                                                 'moduleLabel' => 'Category Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedBackCategory.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );
$feedBackGradesMasterArray = Array(
												 'moduleName' => 'FeedBackGradesMaster',										// not there
                                                 'moduleLabel' => 'Feedback Grade Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedBackGrades.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$feedBackQuestionsMasterArray = Array(
												 'moduleName' => 'FeedBackQuestionsMaster',											// not there
                                                 'moduleLabel' => 'Question Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFeedBackQuestions.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                    );


$assignSurveyMasterArray = Array(
												 'moduleName' => 'AssignSurveyMaster',                                    //not there
                                                 'moduleLabel' => 'Assign Survey',
                                                 'moduleLink' => UI_HTTP_PATH.'/listAssignSurvey.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );

$copySurveyMasterArray = Array(
												 'moduleName' => 'CopySurveyMaster',												//not there
                                                 'moduleLabel' => 'Copy Questions Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/copySurvey.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                              );
$previewSurveyArray = Array(
												 'moduleName' => 'PreviewSurvey',												//not there
                                                 'moduleLabel' => 'Preview Survey',
                                                 'moduleLink' => UI_HTTP_PATH.'/previewSurvey.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                          );
$surveyAndPollsArray = array();
$surveyAndPollsArray[] = $createFeedBackLabelsArray;
$surveyAndPollsArray[] = $feedBackCategoryMasterArray;
$surveyAndPollsArray[] = $feedBackGradesMasterArray;
$surveyAndPollsArray[] = $feedBackQuestionsMasterArray;
$surveyAndPollsArray[] = $assignSurveyMasterArray;
$surveyAndPollsArray[] = $copySurveyMasterArray;
$surveyAndPollsArray[] = $previewSurveyArray;
$menuCreationManager->makeMenu("Survey and Polls",$surveyAndPollsArray);


$disciplineMasterArray = Array(
												 'moduleName' => 'DisciplineMaster',
                                                 'moduleLabel' => 'Add Disciplinary Record',
                                                 'moduleLink' => UI_HTTP_PATH.'/listDiscipline.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you enter the disciplinary offences for students.',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$menuCreationManager->makeSingleMenu($disciplineMasterArray);


$downloadImagesReportArray = Array(
												 'moduleName' => 'DownloadImagesReport',											//not there
                                                 'moduleLabel' => 'Upload & Download Images',
                                                 'moduleLink' => UI_HTTP_PATH.'/uploadDownloadImages.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',

                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                  );

$menuCreationManager->makeSingleMenu($downloadImagesReportArray);

$displayStudentReappearArray = Array(
												 'moduleName' => 'DisplayStudentReappear',											//not there
                                                 'moduleLabel' => 'Display Student Internal Re-appear',
                                                 'moduleLink' => UI_HTTP_PATH.'/displayStudentInternalReappear.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );
$menuCreationManager->makeSingleMenu($displayStudentReappearArray);
$superLoginArray = Array(

												 'moduleName' => 'SuperLogin',													//not there
                                                 'moduleLabel' => 'Super Login',
                                                 'moduleLink' => UI_HTTP_PATH.'/superLoginList.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );

$menuCreationManager->makeSingleMenu($superLoginArray);
$changePasswordArray = Array(
												 'moduleName' => 'ChangePassword',										//not there
                                                 'moduleLabel' => 'Change Password',
                                                 'moduleLink' => UI_HTTP_PATH.'/changePassword.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                            );


$menuCreationManager->makeSingleMenu($changePasswordArray);
$photoGalleryArray = Array(
												 'moduleName' => 'PhotoGallery',										//not there
                                                 'moduleLabel' => 'Photo Gallery',
                                                 'moduleLink' => UI_HTTP_PATH.'/listPhotoGallery.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                            );

$incentiveDetailArray = Array(
												 'moduleName' => 'AttendanceIncentiveDetails',										//not there
                                                 'moduleLabel' => 'Incentive Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentAttendanceIncentiveDetail.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
  										     'showHelpBar' => false,
											     'showSearch' => true
                            );




 $studentRegistration = Array(
                                                 'moduleName' => 'PreAdmissionMaster',                                        //not there
                                                 'moduleLabel' => 'Registration Form',
                                                 'moduleLink' => UI_HTTP_PATH.'/listPreAdmission.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                            );


$gradeCardClassFormat = Array(
                                                 'moduleName' => 'ClassGradesheetMaster',                                        //not there
                                                 'moduleLabel' => 'Class Gradesheet Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listClassGradeMaster.php',
                                                 'accessArray' =>  ARRAY(VIEW,EDIT),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                            );

$holdUnholdClassResult = Array(
                                                 'moduleName' => 'HoldUnholdClassResult',                                        //not there
                                                 'moduleLabel' => 'Hold/Unhold Class Result',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHoldUnholdClassResult.php',
                                                 'accessArray' =>  ARRAY(VIEW,EDIT),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                            );

$approveHostelRegistration = Array(
                                                 'moduleName' => 'ApproveHostelRegistration',                                        //not there
                                                 'moduleLabel' => 'Approve/Unapprove Hostel Registration',
                                                 'moduleLink' => UI_HTTP_PATH.'/Fee/listApproveHostelRegistration.php',
                                                 'accessArray' =>  ARRAY(VIEW,EDIT),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                            );

                            
$menuCreationManager->makeSingleMenu($photoGalleryArray);
//$menuCreationManager->makeSingleMenu($incentiveDetailArray);
$menuCreationManager->makeSingleMenu($studentRegistration);
$menuCreationManager->makeSingleMenu($gradeCardClassFormat);
$menuCreationManager->makeSingleMenu($holdUnholdClassResult);
$menuCreationManager->makeSingleMenu($approveHostelRegistration);
$reportsArray = array();
$menuCreationManager->addToAllMenus($reportsMenu);
$menuCreationManager->setMenuHeading("Reports");
$messagesListArray = Array(
												 'moduleName' => 'MessagesList',								//not there
                                                 'moduleLabel' => 'Messages List',
                                                 'moduleLink' => UI_HTTP_PATH.'/smsDetailReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                          );
$messagesCountListArray = Array(
												 'moduleName' => 'MessagesCountList',								//not there
                                                 'moduleLabel' => 'Messages Count List',
                                                 'moduleLink' => UI_HTTP_PATH.'/smsFullDetailReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                               );
$messagesReportArray = Array(
												 'moduleName' => 'MessagesReport',								//not there
                                                 'moduleLabel' => 'Messages Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/messagesReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                               );
$smsDeliveryArray = Array(
												 'moduleName' => 'SMSDeliveryReport',								//not there
                                                 'moduleLabel' => 'SMS Delivery Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/smsDeliveryReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
								);

$messagesArray = array();
$messagesArray[] = $messagesListArray;
$messagesArray[] = $messagesCountListArray;
$messagesArray[] = $messagesReportArray;
$messagesArray[] = $smsDeliveryArray;
$menuCreationManager->makeMenu( "Messages", $messagesArray);
$hostelListArray = Array(
												 'moduleName' => 'HostelList',
                                                 'moduleLabel' => 'Hostel Detail Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/hostelDetailReport.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you see the details of rooms, room types, etc in a hostel',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                        );

$cleaningHistoryMasterArray = Array(
						 'moduleName' => 'CleaningHistoryMaster',
                                                 'moduleLabel' => 'Cleaning Hostel Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listCleaningHistory.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you view the cleaning history for hostels',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                    );


$hostelArray = array();
$hostelArray[] = $hostelListArray;
$hostelArray[] = $cleaningHistoryMasterArray;

$menuCreationManager->makeMenu( "Hostel", $hostelArray);





$seatAllocationReportArray = Array(
												 'moduleName' => 'SeatAllocationReport',										//not there
                                                 'moduleLabel' => 'Seat Allocation Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/seatAllocationReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                   );

$menuCreationManager->makeSingleMenu($seatAllocationReportArray);


$pollReportArray = Array(
												 'moduleName' => 'PollReport',										//not there
                                                 'moduleLabel' => "Teacher's Poll Report",
                                                 'moduleLink' => UI_HTTP_PATH.'/pollReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                   );

$menuCreationManager->makeSingleMenu($pollReportArray);

$classWiseEvaluationReportArray = Array(
				'moduleName' => 'ClassWiseEvaluationReport',	//not there
                                                 'moduleLabel' => 'Class Wise Evaluation Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/classWiseEvaluationReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                   );
$menuCreationManager->makeSingleMenu($classWiseEvaluationReportArray);

$admittedStudentReportArray = Array(
												 'moduleName' => 'AdmittedStudentReport',									// not there
                                                 'moduleLabel' => 'Admitted Student Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/admittedStudentReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                    );

$menuCreationManager->makeSingleMenu($admittedStudentReportArray);
$studentListArray = Array(
												 'moduleName' => 'StudentList',														//not there
                                                 'moduleLabel' => 'Student List',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentLists.php',
                                                 'accessArray' =>ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                         );

$menuCreationManager->makeSingleMenu($studentListArray);


$auditTrailReportArray = Array(
												 'moduleName' => 'AuditTrailReport',														//not there
                                                 'moduleLabel' => 'Audit Trail Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/auditTrailReport.php',
                                                 'accessArray' =>ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                         );

$menuCreationManager->makeSingleMenu($auditTrailReportArray);


$employeeListArray = Array(
												 'moduleName' => 'EmployeeList',											//not there
                                                 'moduleLabel' => 'Employee List',
                                                 'moduleLink' => UI_HTTP_PATH.'/listEmployeeLists.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                          );

$menuCreationManager->makeSingleMenu($employeeListArray);



$finalResultArray = Array(
												 'moduleName' => 'FinalResult',											//not there
                                                 'moduleLabel' => 'Final Result Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/finalResult.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                          );

$menuCreationManager->makeSingleMenu($finalResultArray);


$roleWiseListArray = Array(
												 'moduleName' => 'RoleWiseList',
                                                 'moduleLabel' => 'Role Wise User Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/roleWiseUserReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you see which user',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                          );
$roleMenuArray = array();
$roleMenuArray[] = $roleWiseListArray;
$menuCreationManager->makeMenu( "Role", $roleMenuArray);


$studentAttendanceArray = Array(
												 'moduleName' => 'StudentAttendance',
                                                 'moduleLabel' => 'Student Attendance Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentAttendanceReport.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you check the attendance details of any student',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                );
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

$attendanceStatusReportArray = Array(
												 'moduleName' => 'AttendanceStatusReport',
                                                 'moduleLabel' => 'Last Attendance Taken Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/attendanceStatusReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you view the details of the teachers who have entered attendance upto a specific date in the system',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                    );

$attendanceRegisterArray = Array(
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

$studentAttendanceShortReportArray = Array(
												 'moduleName' => 'StudentAttendanceShortReport',						//NOT THERE
                                                 'moduleLabel' => 'Student Attendance Short Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentAttendanceShorts.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                          );

$studentAttendancePerformanceReportArray = Array(

												 'moduleName' => 'StudentAttendancePerformanceReport',						//not there
                                                 'moduleLabel' => 'Student Attendance Performance Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentAttendancePerformanceReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                                );

$teacherAttendanceReportArray = Array(
												 'moduleName' => 'TeacherAttendanceReport',											//not there
                                                 'moduleLabel' => 'Teacher Attendance Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listTeacherAttendanceReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                     );
$studentDutyLeaveReportArray = Array(
												 'moduleName' => 'studentDutyLeaveReport',											//not there
                                                 'moduleLabel' => 'Student Duty Leave Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentDutyLeaveReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                     );
$streamWiseAttedanceArray = Array(
												 'moduleName' => 'streamWiseAttendanceReport',											//not there
                                                 'moduleLabel' => 'Stream Wise Attendance Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/streamWiseAttendanceReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                     );


$attendanceArray = array();
$attendanceArray[] = $studentAttendanceArray;
$attendanceArray[] = $percentageWiseAttendanceReportArray;
$attendanceArray[] = $attendanceStatusReportArray;
$attendanceArray[] = $attendanceRegisterArray;
$attendanceArray[] = $studentAttendanceShortReportArray;
$attendanceArray[] = $studentAttendancePerformanceReportArray;
$attendanceArray[] = $teacherAttendanceReportArray;
$attendanceArray[] = $studentDutyLeaveReportArray;
$attendanceArray[]=$streamWiseAttedanceArray;
$menuCreationManager->makeMenu( "Attendance", $attendanceArray);

$testWiseMarksReportArray = Array(
												 'moduleName' => 'TestWiseMarksReport',
                                                 'moduleLabel' => '<font color="blue">Test wise Marks Report (Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/testWiseMarksReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you view the test marks details of the students',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                  );

$testWiseMarksConsolidatedReportArray = Array(
												 'moduleName' => 'TestWiseMarksConsolidatedReport',						//not there
                                                 'moduleLabel' => '<font color="blue">Test Type Category wise Detailed Report (Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/testWiseMarksConsolidatedReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                             );

$dateWiseTestReportArray = Array(
												 'moduleName' => 'DateWiseTestReport',							//not there
                                                 'moduleLabel' => '<font color="blue">Date Wise Test Report (Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/datewiseTestReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                );

/*$studentRankArray = Array(
												 'moduleName' => 'StudentRank',									//not there
                                                 'moduleLabel' => '<font color="blue">Student Exam Rankwise Report (Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentRankWiseReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                         );*/
$studentTestWiseMarksReportArray = Array(
												 'moduleName' => 'StudentTestWiseMarksReport',							//not there
                                                 'moduleLabel' => '<font color="blue">Student Test Wise Marks Report (Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentTestWiseMarksReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                         );

$marksStatusReportArray = Array(
												 'moduleName' => 'MarksStatusReport',									//not there
                                                 'moduleLabel' => '<font color="blue">Marks Status Report (Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/marksStatusReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                               );

$studentAcademicReportArray = Array(
												 'moduleName' => 'StudentAcademicReport',								//not there
                                                 'moduleLabel' => '<font color="blue">Student Academic Report (Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/academicPerformanceReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                    );

////////////////////////////////////////////////

$studentAcademicPerformanceReportArray = Array(
												 'moduleName' => 'StudentAcademicPerformanceReport',						//not there
                                                 'moduleLabel' => '<font color="blue">Student Academic Performance Report(Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentAcademicPerformanceReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                               );

$gazetteReportArray = Array(
												 'moduleName' => 'GazetteReport',										//not there Gazette Report(Pre & Post Transfer)
                                                 'moduleLabel' => '<font color="green">Free Format Awards </font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/gazetteReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                            );
                            
$studentAwardReportArray = Array(
                                                 'moduleName' => 'StudentAwardReport',                                        //not there Gazette Report(Pre & Post Transfer)
                                                 'moduleLabel' => '<font color="green">Student Award Report</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentAwardList.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                            );
                            
$studentGazzetteReport = Array(
                                                 'moduleName' => 'StudentGazetteReport',                                        //not there Gazette Report(Pre & Post Transfer)
                                                 'moduleLabel' => '<font color="green">Student Gazette Report</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentGazetteReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                            );
$studentGradeCardReportArrayNew = Array(
                                                 'moduleName' => 'StudentGradeCardReportNew',                        

                                                 'moduleLabel' => '<font color="red">Student Grade Card Report (New)</font>',
                                                 'moduleLink' => UI_HTTP_PATH . '/studentGradeCardNew.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                       );
                                                                                    

$studentGradeCardReportArray = Array(
                                                 'moduleName' => 'StudentGradeCardReport',                        //not there

                                                 'moduleLabel' => '<font color="red">Student Grade Card Report (Post Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentGradeCard.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                       );

$studentConsolidatedReportArray = Array(
												 'moduleName' => 'StudentConsolidatedReport',						//not there
                                                 'moduleLabel' => '<font color="red">Student Consolidated Report (Post Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentConsolidatedReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                       );


$finalInternalReportArray = Array(
												 'moduleName' => 'FinalInternalReport',
                                                 'moduleLabel' => '<font color="red">Final Marks Report </font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/finalInternalReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you view in a single screen the complete details of internal marks for students',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                 );


$totalMarksReportArray = Array('moduleName' => 'TotalMarksReport',
                                                 'moduleLabel' => '<font color="red">Total Marks Report (Post Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/totalMarksReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                 );

$externalMarksReportArray = Array(
												 'moduleName' => 'ExternalMarksReport',
                                                 'moduleLabel' => '<font color="red">Student External Marks Report (Post Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentExternalMarksReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you view in a single screen the complete details of external marks for students',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                 );


$internalMarksFoxproReportArray = Array(
												 'moduleName' => 'InternalMarksFoxproReport',
                                                 'moduleLabel' => '<font color="red">Student Internal Marks Foxpro Report (Post Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/internalMarksFoxproReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => 'Lets you view in a single screen the complete details of internal marks for students in Fox-pro format',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                       );

$transferredMarksReportArray = Array(
												 'moduleName' => 'TransferredMarksReport',										//not there
                                                 'moduleLabel' => '<font color="red">Transferred Marks Graph Report (Post Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/tranferredMarksReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                    );

$testTypeDistributionReportArray = Array(
												 'moduleName' => 'TestTypeDistributionReport',											//not there
                                                 'moduleLabel' => '<font color="red">Test Type Distribution Consolidated Report (PostTransfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/testTypeConsolidatedReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                        );

$subjectWisePerformanceComparisonReportArray = Array(
												 'moduleName' => 'SubjectWisePerformanceComparisonReport',								//not there
                                                 'moduleLabel' => '<font color="red">Subject Wise Performance Report (Post Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/Teacher/listSubjectWisePerformance.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                                    );
                                                    
$transcriptReportArray = Array(
                                                 'moduleName' => 'TranscriptReport',                                //not there
                                                 'moduleLabel' => '<font color="red">Transcript Report (Post Transfer)</font>',
                                                 'moduleLink' =>  UI_HTTP_PATH.'/transcriptReport.php',   
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                                  );
                                                                                                        
$subjectWisePerformanceReportArray = Array(
                                            'moduleName'  => 'SubjectWisePerformanceReport',
											'moduleLabel' => '<font color="blue">Test Wise Performance Report (Pre Transfer)</font>',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listSubjectPerformance.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$testWisePerformanceComparisonReportArray = Array(
                                            'moduleName'  => 'TestWisePerformanceComparisonReport',
											'moduleLabel' => '<font color="blue">Test Wise Performance Comparison Report (Pre Transfer)</font>',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listSubjectPerformanceComparison.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

$groupWisePerformanceReportArray = Array(
                                            'moduleName'  => 'GroupWisePerformanceReport',
											 'moduleLabel' => '<font color="blue">Group Wise Performance Report (Pre Transfer)</font>',
                                            'moduleLink'  => UI_HTTP_TEACHER_PATH . '/listGroupWisePerformance.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );

/*$subjectWisePerformanceComparisonReportArray = Array(
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
							*/

$examinationReportsArray = array();
//$examinationReportsArray[] = $testWiseMarksReportArray;
//$examinationReportsArray[] = $testWiseMarksConsolidatedReportArray;
$examinationReportsArray[] = $dateWiseTestReportArray;
//$examinationReportsArray[] = $studentRankArray;
$examinationReportsArray[] = $studentTestWiseMarksReportArray;
$examinationReportsArray[] = $marksStatusReportArray;
$examinationReportsArray[] = $groupWisePerformanceReportArray;
$examinationReportsArray[] = $testWisePerformanceComparisonReportArray;
$examinationReportsArray[] = $subjectWisePerformanceReportArray;
//$examinationReportsArray[] = $studentAcademicReportArray;
$examinationReportsArray[] = $studentAcademicPerformanceReportArray;
$examinationReportsArray[] = $gazetteReportArray;
$examinationReportsArray[] = $studentAwardReportArray;
$examinationReportsArray[] = $studentGazzetteReport;
//$examinationReportsArray[] = $studentGradeCardReportArrayNew;

$examinationReportsArray[] = $studentGradeCardReportArray;
$examinationReportsArray[] = $studentConsolidatedReportArray;
$examinationReportsArray[] = $finalInternalReportArray;
$examinationReportsArray[] = $totalMarksReportArray;
$examinationReportsArray[] = $externalMarksReportArray;
$examinationReportsArray[] = $internalMarksFoxproReportArray;
$examinationReportsArray[] = $transferredMarksReportArray;
$examinationReportsArray[] = $testTypeDistributionReportArray;
$examinationReportsArray[] = $subjectWisePerformanceComparisonReportArray;
$examinationReportsArray[] = $transcriptReportArray;


$menuCreationManager->makeMenu( "Examination Reports",$examinationReportsArray);

////////////////////////////////////////////////////////////////////////////////////////////////
$studentRankArray = Array(
												 'moduleName' => 'StudentRank',									//not there
                                                 'moduleLabel' => '<font color="blue">Student Exam Rankwise Report (Pre Transfer)</font>',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentRankWiseReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                         );

$academicReportsArray[] = $studentRankArray;
$menuCreationManager->makeMenu( "Academic Reports",$academicReportsArray);
////////////////////////////////////////////////////////////////////////////////////////////////


$feeCollectionArray = Array(
												 'moduleName' => 'FeeCollection',
                                                 'moduleLabel' => 'Fee Collection',
                                                 'moduleLink' => UI_HTTP_PATH.'/feeCollection.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you collect the fees from the students',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                           );

$displayFeePaymentHistoryArray = Array(
												 'moduleName' => 'DisplayFeePaymentHistory',
                                                 'moduleLabel' => 'Display Fee Payment History',
                                                 'moduleLink' => UI_HTTP_PATH.'/paymentHistory.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you view the complete fee payment details of students',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                      );

$feeHeadWiseReportArray = Array(
                                                 'moduleName' => 'FeeHeadReport',
                                                 'moduleLabel' => 'Fee Head Wise Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/feeHeadWiseReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                       );
                                       
$feePendingDuesReportArray = Array(
                                                 'moduleName' => 'StudentPendingDues',
                                                 'moduleLabel' => 'Student Pending Dues Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentPendingDues.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                                 'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                       );                                       
                                       

$feeCollectionReportsArray = array();
//$feeCollectionReportsArray[] = $feeCollectionArray;
$feeCollectionReportsArray[] = $displayFeePaymentHistoryArray;
$feeCollectionReportsArray[] = $feeHeadWiseReportArray;
$feeCollectionReportsArray[] = $feePendingDuesReportArray;

$menuCreationManager->makeMenu( "Fee Collection Reports", $feeCollectionReportsArray);


$fineCollectionReportArray = Array(
												 'moduleName' => 'FineCollectionReport',								//not there
                                                 'moduleLabel' => 'Category Wise Fine Collection Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFineCollectionReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                   );

$studentWiseFineCollectionReportArray = Array(
												 'moduleName' => 'StudentWiseFineCollectionReport',						//not there
                                                 'moduleLabel' => 'Student Wise Fine Collection Summary Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentWiseFineCollectionReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                              );

$studentDetailFineCollectionReportArray = Array(
												 'moduleName' => 'StudentDetailFineCollectionReport',					//not there
                                                 'moduleLabel' => 'Student Detail Fine Collection Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentDetailFineCollectionReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                               );
$studentFineHistoryReportArray = Array(
												 'moduleName' => 'StudentFineHistoryReport',								//not there
                                                 'moduleLabel' => 'Fine Payment History Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/fineHistory.php',
                                                 'accessArray' => Array(VIEW,DELETE),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                     );

$fineStudentReportArray = Array(
												 'moduleName' => 'FineStudentReport',								//not there
                                                 'moduleLabel' => 'Student Wise Fine Collection Summary Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/fineStudentReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                     );


$fineCancelledReportArray = Array(
												 'moduleName' => 'FineCancelledReport',								//not there
                                                 'moduleLabel' => 'Fine Cancelled Receipts Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/fineCancelledReceiptsReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
						  'videoHelpUrl' => '',
						  'showHelpBar' => false,
						  'showSearch' => false
                                     );

$fineArray = array();
$fineArray[] = $fineCollectionReportArray;
//$fineArray[] = $studentWiseFineCollectionReportArray;
//$fineArray[] = $studentDetailFineCollectionReportArray;
$fineArray[] = $studentFineHistoryReportArray;
$fineArray[] = $fineStudentReportArray;
$fineArray[] = $fineCancelledReportArray;
$menuCreationManager->makeMenu( "Fine", $fineArray);

$feeCollectionReportArray = Array(
						 'moduleName'   => 'FeeCollectionReport',								//not there
                                                 'moduleLabel'  => 'Fee Collection Report',
                                                 'moduleLink'   => UI_HTTP_PATH.'/Fee/listFeeCollectionReport.php',
                                                 'accessArray'  => Array(VIEW),
                                                 'description'  => '',
                         		         'helpUrl'      => '',
						 'videoHelpUrl' => '',
					      	 'showHelpBar'  => false,
					     	 'showSearch'   => false
                                     );
$consolidatedFeeCollectionReportArray = Array(
						 'moduleName'   => 'ConsolidatedFeeCollectionReport',								//not there
                                                 'moduleLabel'  => 'Consolidated Fee Collection Report',
                                                 'moduleLink'   => UI_HTTP_PATH.'/Fee/listConsolidatedFeeCollectionReport.php',
                                                 'accessArray'  => Array(VIEW),
                                                 'description'  => '',
                         		         'helpUrl'      => '',
						 'videoHelpUrl' => '',
					      	 'showHelpBar'  => false,
					     	 'showSearch'   => false
                                     );
                                     
$feePeningReportArray = Array(
						 'moduleName'   => 'PendingFeeReport',								//not there
                                                 'moduleLabel'  => 'Pending Fee Report',
                                                 'moduleLink'   => UI_HTTP_PATH.'/Fee/listPendingFeeReport.php',
                                                 'accessArray'  => Array(VIEW),
                                                 'description'  => '',
                         		         'helpUrl'      => '',
						 'videoHelpUrl' => '',
					      	 'showHelpBar'  => false,
					     	 'showSearch'   => false
                                     );
									 
$feeDetailHistoryReportArray = Array(
							 'moduleName'   => 'FeeDetailHistoryReport',								//not there
							 'moduleLabel'  => 'Fee Detail History Report',
							 'moduleLink'   => UI_HTTP_PATH.'/Fee/listFeeDetailHistoryReport.php',
							 'accessArray'  => Array(VIEW),
							 'description'  => '',
							 'helpUrl'      => '',
							 'videoHelpUrl' => '',
							 'showHelpBar'  => false,
							 'showSearch'   => false
                                     );								 
$feeHistoryReportArray = Array(
						 'moduleName'   => 'FeePaymentHistory',								//not there
                                                 'moduleLabel'  => 'Fee Payment History',
                                                 'moduleLink'   => UI_HTTP_PATH.'/Fee/paymentHistory.php',
                                                 'accessArray'  => Array(VIEW,DELETE),
                                                 'description'  => '',
                         		         'helpUrl'      => '',
						 'videoHelpUrl' => '',
					      	 'showHelpBar'  => false,
					     	 'showSearch'   => false
                                     );
									 
									 
$onlineFeeHistoryReportArray = Array(
						 'moduleName'   => 'OnlineFeePaymentHistory',								//not there
                                                 'moduleLabel'  => 'Online Fee Payment History',
                                                 'moduleLink'   => UI_HTTP_PATH.'/Fee/onlineFeePaymentHistory.php',
                                                 'accessArray'  => Array(VIEW,DELETE),
                                                 'description'  => '',
                         		         'helpUrl'      => '',
						 'videoHelpUrl' => '',
					      	 'showHelpBar'  => false,
					     	 'showSearch'   => false
                                     );
									 									 
$feeConsolidatedDetailsReportArray = Array(
						 'moduleName'   => 'ConsolidatedFeeDetailsReport',								//not there
                                                 'moduleLabel'  => 'Consolidated Fee Details Report',
                                                 'moduleLink'   => UI_HTTP_PATH.'/Fee/listConsolidatedFeeDetailsReport.php',
                                                 'accessArray'  => Array(VIEW),
                                                 'description'  => '',
                         		         'helpUrl'      => '',
						 'videoHelpUrl' => '',
					      	 'showHelpBar'  => false,
					     	 'showSearch'   => false
                                     );
$cancelReceipts = Array(
						 'moduleName'   => 'CanceledReceipts',								//not there
                                                 'moduleLabel'  => 'Canceled Receipts',
                                                 'moduleLink'   => UI_HTTP_PATH.'/Fee/canceledReceipts.php',
                                                 'accessArray'  => Array(VIEW),
                                                 'description'  => '',
                         		         'helpUrl'      => '',
						 'videoHelpUrl' => '',
					      	 'showHelpBar'  => false,
					     	 'showSearch'   => false
                                     );
$feeNewArray = array();

//$feeNewArray[] = $feeCollectionReportArray;
//$feeNewArray[] = $feePeningReportArray;

//$feeNewArray[] = $feeConsolidatedDetailsReportArray;

$feeNewArray[] = $consolidatedFeeCollectionReportArray;
$feeNewArray[] = $feeHistoryReportArray;
$feeNewArray[] = $onlineFeeHistoryReportArray;
$feeNewArray[] = $feeDetailHistoryReportArray;
$feeNewArray[] = $cancelReceipts;
$menuCreationManager->makeMenu("Fee New", $feeNewArray);

if (defined('INCLUDE_LEAVE') and INCLUDE_LEAVE == true) {
	$employeeLeavesHistoryReport = Array(
											     'moduleName' => 'EmployeeLeavesHistoryReport',								//not there
                                                 'moduleLabel' => 'Employee Leaves History Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/leavesHistoryReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
		                                         'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false

												);
	$employeeLeavesTakenReport = Array(
											    'moduleName' => 'EmployeeLeavesTakenReport',							//not there
                                                'moduleLabel' => 'Employee Leaves Taken Report',
                                                'moduleLink' => UI_HTTP_PATH.'/leavesTakenReport.php',
                                                'accessArray' => Array(VIEW),
                                                'description' => '',
                                 		        'helpUrl' => '',
		                                        'videoHelpUrl' => '',
											    'showHelpBar' => false,
											    'showSearch' => false
												);

	$EmployeeLeavesAnalysisReport = Array(
											    'moduleName' => 'EmployeeLeavesAnalysisReport',							//not there
                                                'moduleLabel' => 'Employee Leaves Analysis Report',
                                                'moduleLink' => UI_HTTP_PATH.'/leavesAnalysisReport.php',
                                                'accessArray' => Array(VIEW),
                                                'description' => '',
                                 		        'helpUrl' => '',
		                                        'videoHelpUrl' => '',
										        'showHelpBar' => false,
											    'showSearch' => false
												);

	$leaveReportArray = array();
	$leaveReportArray[] = $employeeLeavesHistoryReport;
	$leaveReportArray[] = $employeeLeavesTakenReport;
	$leaveReportArray[] = $EmployeeLeavesAnalysisReport;
	$menuCreationManager->makeMenu( "Leave", $leaveReportArray);
}


$studentTeacherAssignmentReportArray = Array(
                                                 'moduleName' => 'StudentTeacherAssignment',                                //not there
                                                 'moduleLabel' => 'Student Teacher Assignment Detail Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listStudentTeacherAssignment.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                       );


$menuCreationManager->makeSingleMenu($studentTeacherAssignmentReportArray);

$coursewiseResourceReportArray = Array(
												 'moduleName' => 'CoursewiseResourceReport',								//not there
                                                 'moduleLabel' => 'Coursewise Resources Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/scCourseWiseResourceReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false

                                       );


$menuCreationManager->makeSingleMenu($coursewiseResourceReportArray);
$teacherConsolidatedReportArray = Array(
												 'moduleName' => 'TeacherConsolidatedReport',							//not there
                                                 'moduleLabel' => 'Subject Consolidated Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/subjectConsolidatedReport.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                        );



$menuCreationManager->makeSingleMenu($teacherConsolidatedReportArray);



$teacherWiseTopicTaughtArray = Array(
												 'moduleName' => 'TeacherWiseTopicTaught',								//not there
                                                 'moduleLabel' => 'Teacher Topic Taught Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/teacherTopicCoveredReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false

                                     );


$menuCreationManager->makeSingleMenu($teacherWiseTopicTaughtArray);
$offenseReportArray = Array(
												 'moduleName' => 'OffenseReport',									//not there
                                                 'moduleLabel' => 'Offense Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listOffenseReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                          );
$menuCreationManager->makeSingleMenu($offenseReportArray);
$vehicleReportArray = Array (
												 'moduleName' => 'VehicleReport',								//not there
                                                 'moduleLabel' => 'Vehicle Detail Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 	             'helpUrl' => '',
									             'videoHelpUrl' => '',
									             'showHelpBar' => false,
									             'showSearch' => false
						);


$insuranceDueReportArray = Array (
												  'moduleName' => 'InsuranceDueReport',
                                                  'moduleLabel' => 'Insurance Due Report',
                                                  'moduleLink' => UI_HTTP_PATH.'/listInsuranceDueReport.php',
                                                  'accessArray' => ARRAY(VIEW),
                                                  'description' => 'Lets you view which vehicles are having their insurance due on a specific date',
                                 	              'helpUrl' => '',
									              'videoHelpUrl' => '',
									              'showHelpBar' => false,
								                  'showSearch' => false
						);


$fuelUsageReportArray = Array (
												 'moduleName' => 'FuelUsageReport',							//not there
                                                 'moduleLabel' => 'Fuel Usage Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFuelReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 	           	 'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
						);

$tyreRetreadingReportArray = Array (
												 'moduleName' => 'TyreRetreadingReport',							//not there
                                                 'moduleLabel' => 'Tyre Retreading Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listTyreRetreadingReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 	             'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
						);

$vehicleInsuranceReportArray = Array (
												 'moduleName' => 'VehicleInsuranceReport',							//not there
                                                 'moduleLabel' => 'Vehicle Insurance Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleInsuranceReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
						);

$fuelConsumableReportArray = Array (
												 'moduleName' => 'FuelConsumableReport',							//not there
                                                 'moduleLabel' => 'Fuel Consumable Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFuelConsumableReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 	             'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
						);

$fuelConsumableTimePeriodReportArray = Array (
												 'moduleName' => 'FuelConsumableTimePeriodReport',						//not there
                                                 'moduleLabel' => 'Fuel Consumable Time Period Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFuelConsumableTimePeriodReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
						);

$busRoutePassengerReportArray = Array (
												 'moduleName' => 'BusRoutePassengerReport',								//not there
                                                 'moduleLabel' => 'Bus Route Passenger Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/busRoutePassengerList.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
						);



	$fleetManagementReportArray = array();
	$fleetManagementReportArray[] = $vehicleReportArray;	
	$fleetManagementReportArray[] = $insuranceDueReportArray;
	$fleetManagementReportArray[] = $fuelUsageReportArray;
	$fleetManagementReportArray[] = $tyreRetreadingReportArray;
	$fleetManagementReportArray[] = $vehicleInsuranceReportArray;
	$fleetManagementReportArray[] = $fuelConsumableReportArray;
	$fleetManagementReportArray[] = $fuelConsumableTimePeriodReportArray;
	$fleetManagementReportArray[] = $busRoutePassengerReportArray;
	$menuCreationManager->makeMenu( "Fleet Management Report", $fleetManagementReportArray);


/*$occupiedFreeClassArray = Array(
												 'moduleName' => 'OccupiedFreeClass',								//not there
                                                 'moduleLabel' => 'Occupied/Free Class(s)/Room(s) Wise Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listOccupiedClassReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                              );

$menuCreationManager->makeSingleMenu($occupiedFreeClassArray);
*/
$optionalGroupReportArray = Array(
												 'moduleName' => 'OptionalGroupReport',										//not there
                                                 'moduleLabel' => 'Optional/Compulsory Groups Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listOptionalSubjectReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                 );

$menuCreationManager->makeSingleMenu($optionalGroupReportArray);
$deletedStudentReportArray = Array(
												 'moduleName' => 'DeletedStudentReport',								//not there
                                                 'moduleLabel' => 'Deleted Student Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listDeletedStudentReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                  );

$menuCreationManager->makeSingleMenu($deletedStudentReportArray);
$timeTableTeacherArray = Array(
												 'moduleName' => 'TimeTableTeacher',								//NOT THERE
                                                 'moduleLabel' => 'Subject Taught By Teacher Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/timeTableTeacherReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                              );

$menuCreationManager->makeSingleMenu($timeTableTeacherArray);

$consolidatedReportArray = Array(
												 'moduleName' => 'ConsolidatedReport',									//not there
                                                 'moduleLabel' => 'Consolidated Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listConsolidatedDataReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                );

$menuCreationManager->makeSingleMenu($consolidatedReportArray);
$displayStudentReappearReportArray = Array(
												 'moduleName' => 'DisplayStudentReappearReport',						//not there
                                                 'moduleLabel' => 'Display Student Re-appear Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/displayStudentInternalReappearReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                          );

$menuCreationManager->makeSingleMenu($displayStudentReappearReportArray);

$displayBusPassReportArray = Array(
												 'moduleName' => 'DisplayBusPassReport',						//not there
                                                 'moduleLabel' => 'Display Bus Pass Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/displayBusPassReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                  );

$menuCreationManager->makeSingleMenu($displayBusPassReportArray);

$userLoginReportArray = Array(
												 'moduleName' => 'UserLoginReport',							//not there
                                                 'moduleLabel' => 'User Login Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listUserLoginReport.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',

											     'showHelpBar' => false,
											     'showSearch' => false
                            );


$menuCreationManager->makeSingleMenu($userLoginReportArray);
$regDegreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE');
if($regDegreeCode!='') {
	$coursesRegistrationReportArray = Array(
												 'moduleName' => 'coursesRegistrationReport',					//NOT THERE
                                                 'moduleLabel' => 'Student Courses Registration Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/coursesRegistrationReport.php',
                                                 'accessArray' => Array(VIEW,EDIT,ADD),
                                                 'description' => '',
                                 		         'helpUrl' => '',
		                                         'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true


                                       );

	$menuCreationManager->makeSingleMenu($coursesRegistrationReportArray);
}
$analyticsArray = array();
$menuCreationManager->addToAllMenus($analyticsMenu);
$menuCreationManager->setMenuHeading("Analytics");

$studentDemographicsArray = Array(
												 'moduleName' => 'StudentDemographics',							//NOT THERE
                                                 'moduleLabel' => 'Student Demographics',
                                                 'moduleLink' => UI_HTTP_PATH . '/studentDemographics.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );
$menuCreationManager->makeSingleMenu($studentDemographicsArray);

$studentSuggestionArray = Array(
												 'moduleName' => 'StudentSuggestion',						//NOT THERE
                                                 'moduleLabel' => 'Suggestions',
                                                 'moduleLink' => UI_HTTP_PATH . '/listSuggestions.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );
$menuCreationManager->makeSingleMenu($studentSuggestionArray);

$preAdmissionArray = array();
$menuCreationManager->addToAllMenus($preAdmissionMenu);
$menuCreationManager->setMenuHeading("Pre Admission");
$uploadCandidateDetailsArray = Array(
												 'moduleName' => 'UploadCandidateDetails',					//not there
                                                 'moduleLabel' => 'Import Candidate Details From Excel',
                                                 'moduleLink' => UI_HTTP_PATH.'/candidateUpload.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                   );
$menuCreationManager->makeSingleMenu($uploadCandidateDetailsArray);

$addStudentEnquiryArray = Array(
												 'moduleName' => 'AddStudentEnquiry',						// not there
                                                 'moduleLabel' => 'View Candidate Details',
                                                 'moduleLink' => UI_HTTP_PATH.'/addStudentEnquiry.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$menuCreationManager->makeSingleMenu($addStudentEnquiryArray);
$studentCounselingArray = Array(
												 'moduleName' => 'StudentCounseling',							//not there
                                                 'moduleLabel' => 'Candidate Counseling',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentCounseling.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );
$menuCreationManager->makeSingleMenu($studentCounselingArray);


















$studentFeeArray = Array(
												 'moduleName' => 'StudentFee',									//not there
                                                 'moduleLabel' => 'Admission Fee',
                                                 'moduleLink' => UI_HTTP_PATH.'/candidateFee.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                        );
$menuCreationManager->makeSingleMenu($studentFeeArray);
$studentEnquiryDemographicsArray = Array(
												 'moduleName' => 'StudentEnquiryDemographics',										//not there
                                                 'moduleLabel' => 'Student Enquiry Demographics',
                                                 'moduleLink' => UI_HTTP_PATH.'/studentEnquiryDemographics.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                        );

$menuCreationManager->makeSingleMenu($studentEnquiryDemographicsArray);


$adminFuncArray = array();
$menuCreationManager->addToAllMenus($adminFuncArray);
$menuCreationManager->setMenuHeading("Admin Func.");

$roomAllocationArray = Array(
												 'moduleName' => 'RoomAllocation',													//not there
                                                 'moduleLabel' => 'Room Allocation Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/roomAllocation.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                           );


$reportComplaintsMasterArray = Array(
												 'moduleName' => 'ReportComplaintsMaster',													//not there
                                                 'moduleLabel' => 'Report Complaints',
                                                 'moduleLink' => UI_HTTP_PATH.'/listReportComplaints.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                    );

$handleComplaintsArray = Array(
												 'moduleName' => 'HandleComplaints',										//not there
                                                 'moduleLabel' => 'Handle Complaints',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHandleComplaints.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                );
$hostelManagementArray = array();
$hostelManagementArray[] = $roomAllocationArray;
$hostelManagementArray[] = $reportComplaintsMasterArray;
$hostelManagementArray[] = $handleComplaintsArray;

$menuCreationManager->makeMenu( "Hostel Management",$hostelManagementArray);

$hostelRoomDetailReportArray = Array(
						 'moduleName' => 'HostelRoomDetailReport',
                                                 'moduleLabel' => 'Hostel Room Detail Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostelRoomDetailReports.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you view the  report summary for hostel wise,batch wise,gender wise details',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                    );
$hostelOccupancyReportArray = Array(
						 'moduleName' => 'HostelOccupancyReport',
                                                 'moduleLabel' => 'Hostel Occupancy Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listHostelOccupancyReports.php',
                                                 'accessArray' => '',
                                                 'description' => 'Lets you view the  report summary for hostel wise,batch wise,gender wise details',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => false
                                    );

$vehicleRouteDetailReportArray = Array (						 							'moduleName'=>'VehicleRouteDetailReport',	//not there
                                                 'moduleLabel' => 'Vehicle Route Detail Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/listVehicleRouteDetailReports.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		  'helpUrl' => '',
						 'videoHelpUrl' => '',
						  'showHelpBar' => false,
						  'showSearch' => false
						);


$reportDetailArray = array();
$reportDetailArray[] = $hostelRoomDetailReportArray;
$reportDetailArray[] = $hostelOccupancyReportArray;
$reportDetailArray[] = $vehicleRouteDetailReportArray;
$menuCreationManager->makeMenu( "Detail Reports",$reportDetailArray);


$budgetHeadsArray = Array(
												 'moduleName' => 'BudgetHeads',									//not there
                                                 'moduleLabel' => 'Budget Heads Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBudgetHeads.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                         );

$guestHouseRequestArray = Array(
												 'moduleName' => 'GuestHouseRequest',									//not there
                                                 'moduleLabel' => 'Request Guest House Allocation',
                                                 'moduleLink' => UI_HTTP_PATH.'/listGuestHouseRequest.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                               );

$guestHouseAuthorizationArray = Array(
												 'moduleName' => 'GuestHouseAuthorization',										//not there
                                                 'moduleLabel' => 'Guest House Authorization',
                                                 'moduleLink' => UI_HTTP_PATH.'/listGuestHouseAuthorization.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                                     );

$guestHouseReportArray = Array(
                                                 'moduleName' => 'GuestHouseReport',                                        //not there
                                                 'moduleLabel' => 'Guest House Report',
                                                 'moduleLink' => UI_HTTP_PATH.'/guestHouseReport.php',
                                                 'accessArray' => array(VIEW),
                                                 'description' => '',
                                                  'helpUrl' => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => false
                                     );

$guestHouseManagementArray = array();
$guestHouseManagementArray[] = $budgetHeadsArray;
$guestHouseManagementArray[] = $guestHouseRequestArray;
$guestHouseManagementArray[] = $guestHouseAuthorizationArray;
$guestHouseManagementArray[] = $guestHouseReportArray;
$menuCreationManager->makeMenu( "Guest House Management",$guestHouseManagementArray);

if (defined('SHOW_PLACEMENT_MODULES') and SHOW_PLACEMENT_MODULES == 1) {
   $companyArray = Array(
                                                  'moduleName' => 'PlacementComapanyMaster',							//not there
                                                  'moduleLabel' => 'Company Master',
                                                  'moduleLink' => UI_HTTP_PATH.'/Placement/listCompany.php',
                                                  'accessArray' => '',
                                                  'description' => '',
                                                  'helpUrl' => '',
	                                              'videoHelpUrl' => '',
						                          'showHelpBar' => false,
						                          'showSearch' => true

                        );
   $followUpArray = Array(
                                                 'moduleName' => 'PlacementFollowUpsMaster',						//not there
                                                 'moduleLabel' => 'Follow Ups Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/Placement/listFollowUps.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
	                                             'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true

                        );
   $placementDriveArray = Array(
                                                 'moduleName' => 'PlacementDriveMaster',							//not there
                                                 'moduleLabel' => 'Placement Drive Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/Placement/listPlacementDrive.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
	                                             'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                        );

   $placementStudentDetailsUploadArray = Array(
                                                 'moduleName' => 'PlacementUploadStudentDetail',							//not there
                                                 'moduleLabel' => 'Upload Student Details',
                                                 'moduleLink' => UI_HTTP_PATH.'/Placement/studentDetailUpload.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl' => '',
	                                             'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true

                        );

   $generateStudentListArray = Array(
                                                 'moduleName' => 'PlacementGenerateStudentList',						//not there
                                                 'moduleLabel' => 'Generate Student List',
                                                 'moduleLink' => UI_HTTP_PATH.'/Placement/generateStudentList.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                                                 'helpUrl' => '',
	                                             'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                        );

  $generateStudentResultListArray = Array(
                                                  'moduleName' => 'PlacementGenerateStudentResultList',						//not there
                                                  'moduleLabel' => 'Generate Student Result List',
                                                  'moduleLink' => UI_HTTP_PATH.'/Placement/generateStudentResultList.php',
                                                  'accessArray' => Array(VIEW,EDIT),
                                                  'description' => '',
                                                  'helpUrl' => '',
	                                              'videoHelpUrl' => '',
											      'showHelpBar' => false,
											      'showSearch' => true
                        );

 $placementArray=array();
 $placementArray[]=$companyArray;
 $placementArray[]=$followUpArray;
 $placementArray[]=$placementDriveArray;
 $placementArray[]=$placementStudentDetailsUploadArray;
 $placementArray[]=$generateStudentListArray;
 $placementArray[]=$generateStudentResultListArray;
 $menuCreationManager->makeMenu( "Placement Management",$placementArray);
}

$bookListArray = Array(
                                                  'moduleName' => 'BookMaster',
                                                  'moduleLabel' => 'Book-Master',
                                                  'moduleLink' => UI_HTTP_PATH.'/listBook.php',
                                                  'accessArray' =>'',
                                                  'description' => '',
                                                  'helpUrl' => '',
                                                  'videoHelpUrl' => '',
                                                  'showHelpBar' => true,
                                                  'showSearch' => false
                        );
 $bookClassMappingArray = Array(
                                                  'moduleName' => 'BookClassMapping',
                                                  'moduleLabel' => 'Book-Class Mapping Master',
                                                  'moduleLink' => UI_HTTP_PATH.'/listBookClassMapping.php',
                                                  'accessArray' => Array(VIEW,EDIT),
                                                  'description' => '',

                                                  'helpUrl' => '',
                                                  'videoHelpUrl' => '',
                                                  'showHelpBar' => true,
                                                  'showSearch' => false
                        );

  $bookIssueArray = Array(
                                                  'moduleName' => 'BookIssue',
                                                  'moduleLabel' => 'Book Issue Master',
                                                  'moduleLink' => UI_HTTP_PATH.'/listBookIssue.php',
                                                  'accessArray' => Array(VIEW,EDIT),
                                                  'description' => '',
                                                  'helpUrl' => '',
                                                  'videoHelpUrl' => '',
                                                  'showHelpBar' => true,
                                                  'showSearch' => false
                        );

 $bookPackArray = Array(
                                                  'moduleName' => 'BookPack',
                                                  'moduleLabel' => 'Book Pack Master',
                                                  'moduleLink' => UI_HTTP_PATH.'/listBookPack.php',
                                                  'accessArray' => Array(VIEW,EDIT),
                                                  'description' => '',
                                                  'helpUrl' => '',
                                                  'videoHelpUrl' => '',
                                                  'showHelpBar' => true,
                                                  'showSearch' => false
                        );

 $bookDispatchArray = Array(
                                                  'moduleName' => 'BookDispatch',
                                                  'moduleLabel' => 'Book Dispatch Master',
                                                  'moduleLink' => UI_HTTP_PATH.'/listBookDispatch.php',
                                                  'accessArray' => Array(VIEW,EDIT),
                                                  'description' => '',
                                                  'helpUrl' => '',
                                                  'videoHelpUrl' => '',
                                                  'showHelpBar' => true,
                                                  'showSearch' => false
                        );

 $booksArray=array();
 $booksArray[]=$bookListArray;
 $booksArray[]=$bookClassMappingArray;
 $booksArray[]=$bookIssueArray;
 $booksArray[]=$bookPackArray;
 $booksArray[]=$bookDispatchArray;
 $menuCreationManager->makeMenu( "Books Management",$booksArray);

if (defined('INCLUDE_LEAVE') and INCLUDE_LEAVE == true) {
	$leaveMenu = array();
	$menuCreationManager->addToAllMenus($leaveMenu);
	$menuCreationManager->setMenuHeading("Leaves");
	$leaveSessionMasterArray = Array(
													 'moduleName' => 'LeaveSessionMaster',											//not there
																	 'moduleLabel' =>'Leave Session Master',
																	 'moduleLink' =>UI_HTTP_PATH . '/listLeaveSession.php',
																	 'accessArray' => '',
																	 'description' => '',
																	 'helpUrl' => '',
		                                                             'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true

													  );
	$menuCreationManager->makeSingleMenu($leaveSessionMasterArray);
	$leaveMasterArray = Array(
													 'moduleName' => 'LeaveMaster',															//not there
																	 'moduleLabel' =>'Leave Type Master',
																	 'moduleLink' =>UI_HTTP_PATH . '/listLeaveType.php',
																	 'accessArray' => '',
																	 'description' => '',
																	 'helpUrl' => '',
		                                                             'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true

													  );
	$menuCreationManager->makeSingleMenu($leaveMasterArray);
	$leaveSetMasterArray = Array(
													 'moduleName' => 'LeaveSetMaster',													//not there
																	 'moduleLabel' =>'Leave Set Master',
																	 'moduleLink' =>UI_HTTP_PATH . '/listLeaveSet.php',
																	 'accessArray' => '',
																	 'description' => '',
																	 'helpUrl' => '',
		                                                             'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	$menuCreationManager->makeSingleMenu($leaveSetMasterArray);
	$leaveSetMappingArray = Array(
													 'moduleName' => 'LeaveSetMapping',									//not there
																	 'moduleLabel' =>'Leave Set Mapping',
																	 'moduleLink' =>UI_HTTP_PATH . '/listLeaveSetMapping.php',
																	 'accessArray' => '',
																	 'description' => '',
															   	     'helpUrl' => '',
                                                            	     'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	$menuCreationManager->makeSingleMenu($leaveSetMappingArray);

    $bookMappingArray = Array(
													 'moduleName' => 'BookMapping',									//not there
																	 'moduleLabel' =>'Book Mapping',
																	 'moduleLink' =>UI_HTTP_PATH . '/bookMapping.php',
																	 'accessArray' => '',
																	 'description' => '',
															   	     'helpUrl' => '',
                                                            	     'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	//$menuCreationManager->makeSingleMenu($bookMappingArray);

	$employeeEmployeeLeaveSetMappingArray = Array(
													 'moduleName' => 'EmployeeEmployeeLeaveSetMapping',											//not there
																	 'moduleLabel' =>'Employee Leave Set Mapping',
																	 'moduleLink' =>UI_HTTP_PATH . '/listEmployeeLeaveSet.php',
																	 'accessArray' => '',
																	 'description' => '',
																	 'helpUrl' => '',
		                                                             'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true

													  );
	$menuCreationManager->makeSingleMenu($employeeEmployeeLeaveSetMappingArray);
	$employeeEmployeeLeaveSetMappingAdvArray = Array(
													 'moduleName' => 'EmployeeEmployeeLeaveSetMappingAdv',								//not there
																	 'moduleLabel' =>'Employee Leave Set Mapping(Advanced)',
																	 'moduleLink' =>UI_HTTP_PATH . '/listEmployeeLeaveSetAdv.php',
																	 'accessArray' => '',
																	 'description' => '',
																     'helpUrl' => '',
	                                                              	 'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	$menuCreationManager->makeSingleMenu($employeeEmployeeLeaveSetMappingAdvArray);
	$employeeLeaveAuthorizerArray = Array(
													 'moduleName' => 'EmployeeLeaveAuthorizer',									//not there
																	 'moduleLabel' =>'Employee Leave Authorizer',
																	 'moduleLink' => UI_HTTP_PATH . '/listEmployeeLeaveAuthorizer.php',
																	 'accessArray' => '',
																	 'description' => '',
																	 'helpUrl' => '',
		                                                             'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	$menuCreationManager->makeSingleMenu($employeeLeaveAuthorizerArray);

	$employeeLeaveAuthorizerArray = Array(
													 'moduleName' => 'EmployeeLeaveAuthorizerAdv',										//not there
																	 'moduleLabel' =>'Employee Leave Authorizer(Advanced)',
																	 'moduleLink' => UI_HTTP_PATH . '/listEmployeeLeaveAuthorizerAdv.php',
																	 'accessArray' => '',
																	 'description' => '',
																	 'helpUrl' => '',
		                                                             'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	$menuCreationManager->makeSingleMenu($employeeLeaveAuthorizerArray);
	$applyEmployeeLeaveArray = Array(
													 'moduleName' => 'ApplyEmployeeLeave',												//not there
																	 'moduleLabel' =>'Apply Leaves',
																	 'moduleLink' => UI_HTTP_PATH . '/applyEmployeeLeave.php',
																	 'accessArray' => '',
																	 'description' => '',
																     'helpUrl' => '',
		                                                             'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	$menuCreationManager->makeSingleMenu($applyEmployeeLeaveArray);

	$authorizeEmployeeLeaveArray = Array(
													 'moduleName' => 'AuthorizeEmployeeLeave',										//not there
																	 'moduleLabel' =>'Authorize Employee Leaves',
																	 'moduleLink' => UI_HTTP_PATH . '/authorizeEmployeeLeave.php',
																	 'accessArray' =>Array(VIEW,EDIT),
																	 'description' => '',
																     'helpUrl' => '',
	                                                             	 'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	$menuCreationManager->makeSingleMenu($authorizeEmployeeLeaveArray);
	$employeeLeaveCarryForwardArray = Array(
													 'moduleName' => 'EmployeeLeaveCarryForward',									//not there
																	 'moduleLabel' =>'Employee Leave Carry Forward',
																	 'moduleLink' => UI_HTTP_PATH . '/listEmployeeLeaveCarryForward.php',
																	 'accessArray' =>'',
																	 'description' => '',
																	 'helpUrl' => '',
		                                                             'videoHelpUrl' => '',
											                         'showHelpBar' => false,
											                         'showSearch' => true
													  );
	$menuCreationManager->makeSingleMenu($employeeLeaveCarryForwardArray);
}

$moodleMenu     = Array(
                                                 'moduleName'  => 'ShowMoodle',
                                                 'moduleLabel' => ' Moodle',
                                                 'moduleLink'  => UI_HTTP_MOODLE_PATH.'/index.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($moodleMenu );


 /*
$busRepairCourseArray = Array(
												 'moduleName' => 'BusRepairCourse',
                                                 'moduleLabel' => 'Bus Repair Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listBusRepair.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											      'showHelpBar' => false,
											      'showSearch' => true
                              );

$fuelMasterArray = Array(
												 'moduleName' => 'FuelMaster',
                                                 'moduleLabel' => 'Fuel Master',
                                                 'moduleLink' => UI_HTTP_PATH.'/listFuel.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                 		         'helpUrl' => '',
												 'videoHelpUrl' => '',
											     'showHelpBar' => false,
											     'showSearch' => true
                        );

$fleetManagementArray = array();
$fleetManagementArray[] = $busRepairCourseArray;
$fleetManagementArray[] = $fuelMasterArray;
$menuCreationManager->makeMenu( "Fleet Management", $fleetManagementArray);
*/






$accountsMenuItemsFileName = BL_PATH . '/accountsMenuItems.php';
if (file_exists($accountsMenuItemsFileName)) {
	require_once($accountsMenuItemsFileName);
}

//including inventory management menu structure
$inventoryMenuFileName = BL_PATH . '/inventoryMenuItems.php';
if (file_exists($inventoryMenuFileName)) {
	require_once($inventoryMenuFileName);
}

$allMenus = $menuCreationManager->getAllMenus();


$allMenus = $menuCreationManager->getAllMenus();
   /////////////////////////////////////////////////////////////////
$allModuleNameArray = array();
$allModuleLabelArray = array();
  

foreach($allMenus as $independentMenu) {
    foreach($independentMenu as $menuItemArray) {
        if ($menuItemArray[0] == SET_MENU_HEADING) {
        }
        elseif($menuItemArray[0] == MAKE_SINGLE_MENU) {
             $moduleName = $menuItemArray[2][0];
             $moduleLabel = $menuItemArray[2][1];
             $moduleLink = $menuItemArray[2][2];
             $allModuleNameArray[] = $moduleName;
             $allModuleLabelArray[] = array('menuLabel'=>$moduleLabel,'menuLink'=>$moduleLink);;
        }
        elseif($menuItemArray[0] == MAKE_MENU) {
            $moduleHeadLabel = $menuItemArray[1];
            //$subInnerMenuCounter = 0;
            foreach($menuItemArray[2] as $moduleMenuItem) {
                $moduleName = $moduleMenuItem[0];
                $moduleLabel = $moduleMenuItem[1];
                $moduleLink = $moduleMenuItem[2];
                $allModuleNameArray[] = $moduleName;
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
//$sessionHandler->setSessionVariable('hasBreadCrumbs','');
?>
