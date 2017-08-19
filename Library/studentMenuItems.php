<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Student Menu
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 require_once("common.inc.php");
require_once(BL_PATH . "/MenuCreationClassManager.inc.php");
$menuCreationManager = MenuCreationClassManager::getInstance();


$studentActivityMenu = Array();
$menuCreationManager->addToAllMenus($studentActivityMenu);
$menuCreationManager->setMenuHeading("Student Info");

$studentInfoDetailArray   =  Array(
                                            'moduleName'  => 'StudentInfoDetail',
                                            'moduleLabel' => 'Complete Info',
                                            'moduleLink'  => UI_HTTP_STUDENT_PATH . '/listStudentInformation.php',
                                            'accessArray' => Array(VIEW,EDIT),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($studentInfoDetailArray);


$studentDisplayAttendanceArray   =  Array(
                                            'moduleName'  => 'StudentDisplayAttendance',
                                            'moduleLabel' => 'Display Attendance',
                                            'moduleLink'  => UI_HTTP_STUDENT_PATH . '/listAttendance.php',
										    'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($studentDisplayAttendanceArray );

$studentDisplayMarksArray   =  Array(
                                            'moduleName'  => 'StudentDisplayMarks',
                                            'moduleLabel' => 'Display Marks',
                                            'moduleLink'  => UI_HTTP_STUDENT_PATH . '/listStudentMarks.php',
					    'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($studentDisplayMarksArray );

$transcriptReportArray = Array(
						 'moduleName' => 'TranscriptReport',	//not there
                                                 'moduleLabel' => 'Display Grade Card',
                                                 'moduleLink' => UI_HTTP_PATH.'/transcriptReport.php',
                                                 'accessArray' => ARRAY(VIEW),
                                                 'description' => '',
                                 		 'helpUrl' => '',
						 'videoHelpUrl' => '',
						 'showHelpBar' => false,
						 'showSearch' => false
                                   );
$menuCreationManager->makeSingleMenu($transcriptReportArray);

$studentTeacherCommentsArray   =  Array(
                                            'moduleName'  => 'StudentTeacherComments',
                                            'moduleLabel' => 'Display Teacher Comments',
                                            'moduleLink'  =>  UI_HTTP_STUDENT_PATH . '/listTeacherComments.php',
											 'accessArray' => Array(VIEW),
											 'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($studentTeacherCommentsArray );
$studentInstituteNoticesArray   =  Array(
                                            'moduleName'  => 'StudentInstituteNotices',
                                            'moduleLabel' => 'Display Institute Notices',
                                            'moduleLink'  => UI_HTTP_STUDENT_PATH . '/listInstituteNotices.php',
								            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($studentInstituteNoticesArray);
$studentInstituteEventsArray   =  Array(
                                            'moduleName'  => 'StudentInstituteEvents',
                                            'moduleLabel' => 'Display Institute Events',
                                            'moduleLink'  => UI_HTTP_STUDENT_PATH . '/listInstituteEvents.php',
											'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($studentInstituteEventsArray );
$studentAdminMessagesArray   =  Array(
                                            'moduleName'  => 'StudentAdminMessages',
                                            'moduleLabel' => 'Display Admin Messages',
                                            'moduleLink'  =>  UI_HTTP_STUDENT_PATH . '/listAdminMessages.php',
											'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($studentAdminMessagesArray  );
$studentAllAlertsArray   =  Array(
                                            'moduleName'  => 'StudentAllAlerts',
                                            'moduleLabel' => 'Display All Alerts',
                                            'moduleLink'  =>  UI_HTTP_STUDENT_PATH . '/listAllAlerts.php',
											'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($studentAllAlertsArray  );
$taskMasterArray   =  Array(
                                            'moduleName'  => 'TaskMaster',
                                            'moduleLabel' => 'Task Manager',
                                            'moduleLink'  =>  UI_HTTP_STUDENT_PATH . '/listTask.php',
											'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($taskMasterArray  );
$studentReappearMenu = Array(
                                                 'moduleName'  => 'StudentReappear',
                                                 'moduleLabel' => 'Internal Re-appear Form',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/studentInternalReappearForm.php',
                                                 'accessArray' => Array(VIEW,ADD,EDIT),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($studentReappearMenu);
# DO NOT REMOVE FOLLOWING {IF} CONDITION ----- AJINDER
	 if (empty($testCalling)) {
		 require_once(MODEL_PATH.'/CommonQueryManager.inc.php');
		 $commonQueryManager = CommonQueryManager::getInstance();
		 $tclassId = $sessionHandler->getSessionVariable('ClassId');
		 if($tclassId=='') {
			$tclassId=0;
		 }
		 $condition = " AND c.classId = ".$tclassId;
		 $foundArray1 = $commonQueryManager->getDegreeName($condition);
		 $condition = " AND c.branchId = '".$foundArray1[0]['branchId']."' AND c.batchId = '".$foundArray1[0]['batchId']."'";
		 $foundArray1 = $commonQueryManager->getRegistrationDegreeList($condition);
		 $regDegreeCode = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE');
	 }
$studentCourseRegistrationMenu = Array(
                                                 'moduleName'  => 'CourseRegistrationForm',
                                                 'moduleLabel' => 'Course Registration Form',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/courseRegistrationForm.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($studentCourseRegistrationMenu);
$studentTimeTableMenu  = Array(
                                                 'moduleName'  => 'StudentDisplayTimeTable',
                                                 'moduleLabel' => 'Time Table',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/listTimeTable.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($studentTimeTableMenu );
$studentFeeMenu   = Array(
                                                 'moduleName'  => 'StudentShowFeePaymentHistory',
                                                 'moduleLabel' => 'Fees',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/listStudentFee.php',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
//$menuCreationManager->makeHeadingMenu($studentFeeMenu );

$studentResourceMenu   = Array(
                                                 'moduleName'  => 'StudentResourceDetails',
                                                 'moduleLabel' => ' Resource Details',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/listStudentInformation.php?tabIndex=4',
                                                 'accessArray' => Array(VIEW),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($studentResourceMenu );
$studentFeedbackMenu = Array();
$menuCreationManager->addToAllMenus($studentFeedbackMenu);
$menuCreationManager->setMenuHeading("Feedback");

$ADVFB_ProvideFeedBackArray  = Array(
                                                 'moduleName'  => 'ADVFB_ProvideFeedBack',
                                                 'moduleLabel' => ' Advanced Feed Back',
                                                 'moduleLink'  => UI_HTTP_PATH . '/provideFeedbackAdv.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
$GENERAL_SurveyArray  = Array(
                                                 'moduleName'  => 'StudentGeneralFeedBack',
                                                 'moduleLabel' => 'General Survey',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH . '/scGeneralFeedBack.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
$menuCreationManager->makeSingleMenu($ADVFB_ProvideFeedBackArray );
$menuCreationManager->makeSingleMenu($GENERAL_SurveyArray);

$studentPasswordMenu    = Array(
                                                 'moduleName'  => 'StudentChangePassword',
                                                 'moduleLabel' => ' Change Password',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/changeStudentPassword.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($studentPasswordMenu );
$studentAssignmentMenu    = Array(
                                                 'moduleName'  => 'StudentAssignment',
                                                 'moduleLabel' => ' Assignment',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/studentAllocatedTask.php',
                                                 'accessArray' => Array(VIEW,EDIT),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($studentAssignmentMenu );
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


$onlineFeeMenu     = Array(
                                                 'moduleName'  => 'OnlineFeePayment',
                                                 'moduleLabel' => ' Online Fee Payment',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/studentOnlineFeePayment.php',
                                                 'accessArray' => Array(VIEW,ADD,EDIT),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
					 

$menuCreationManager->makeHeadingMenu($onlineFeeMenu );


$hostelRegistrationMenu     = Array(
                                                 'moduleName'  => 'HostelRegistration',
                                                 'moduleLabel' => 'Apply /Renew Hostel Facility',
                                                 'moduleLink'  => UI_HTTP_STUDENT_PATH.'/HostelRegistration/listHostelRegistration.php',
                                                 'accessArray' => Array(VIEW,ADD,EDIT),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );
					 

$menuCreationManager->makeHeadingMenu($hostelRegistrationMenu );


  $allStudentMenus=$menuCreationManager->getAllMenus();

    $allModuleLabelArray=array();


     //Added for autosuggest to work
     foreach($allStudentMenus as $independentMenu) {
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


 /* if ($sessionHandler->getSessionVariable('hasBreadCrumbs') == '') {
	$mainMenuCounter = 0;
	$subInnerMenuCounter = 0;

	$breadCrumbArray = array();
	$setMenuHeading = '';
	$makeSingleMenu = '';
	$makeHeadingMenu = '';
	$makeMenu = '';
	$menuText = '';

	foreach( $allStudentMenus as $independentMenu) {
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
} */
/*
	  $allStudentMenus = Array();
    $allStudentMenus[] = $studentActivityMenu;
    $allStudentMenus[] = $studentReappearMenu;
	 $allStudentMenus[] = $studentCourseRegistrationMenu;
    $allStudentMenus[] = $studentTimeTableMenu;
    $allStudentMenus[] = $studentFeeMenu;
    $allStudentMenus[] = $studentResourceMenu;
    $allStudentMenus[] = $studentFeedbackMenu;
    $allStudentMenus[] = $studentAssignmentMenu;
    $allStudentMenus[] = $studentPasswordMenu;
    $allStudentMenus[] = $moodleMenu;


	$studentActivityMenu = Array();
	$studentActivityMenu[] = Array(SET_MENU_HEADING, "Student Info");
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "StudentInfoDetail", Array('StudentInfoDetail', 'Complete Info', UI_HTTP_STUDENT_PATH . '/listStudentInformation.php',Array(VIEW,EDIT)));
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "StudentDisplayAttendance", Array('StudentDisplayAttendance', 'Display Attendance', UI_HTTP_STUDENT_PATH . '/listAttendance.php',Array(VIEW)));
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "StudentDisplayMarks", Array('StudentDisplayMarks', 'Display Marks', UI_HTTP_STUDENT_PATH . '/listStudentMarks.php',Array(VIEW)));
	//$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, ">StudentTimeTable", Array('StudentTimeTable', 'Display Time Table', UI_HTTP_STUDENT_PATH . '/listTimeTable.php',Array(VIEW)));
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "StudentTeacherComments", Array('StudentTeacherComments', 'Display Teacher Comments', UI_HTTP_STUDENT_PATH . '/listTeacherComments.php',Array(VIEW)));
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "StudentInstituteNotices", Array('StudentInstituteNotices', 'Display Institute Notices', UI_HTTP_STUDENT_PATH . '/listInstituteNotices.php',Array(VIEW)));
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "StudentInstituteEvents", Array('StudentInstituteEvents', 'Display Institute Events', UI_HTTP_STUDENT_PATH . '/listInstituteEvents.php',Array(VIEW)));
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "StudentAdminMessages", Array('StudentAdminMessages', 'Display Admin Messages', UI_HTTP_STUDENT_PATH . '/listAdminMessages.php',Array(VIEW)));
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "StudentAllAlerts", Array('StudentAllAlerts', 'Display All Alerts', UI_HTTP_STUDENT_PATH . '/listAllAlerts.php',Array(VIEW)));
	//$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, ">StudentFeedBack", Array('StudentFeedBack', 'Feed Back', UI_HTTP_STUDENT_PATH . '/studentFeedBack.php'));
	$studentActivityMenu[] = Array(MAKE_SINGLE_MENU, "TaskMaster", Array('TaskMaster', 'Task Manager', UI_HTTP_STUDENT_PATH . '/listTask.php'));

    $studentReappearMenu = Array();
    $studentReappearMenu[] = Array(MAKE_HEADING_MENU, "StudentReappear, Internal Re-appear Form, ".UI_HTTP_STUDENT_PATH."/studentInternalReappearForm.php",Array(VIEW,ADD,EDIT));





    $studentCourseRegistrationMenu = Array();
    $studentCourseRegistrationMenu[] = Array(MAKE_HEADING_MENU, "CourseRegistrationForm, Course Registration Form, ".UI_HTTP_STUDENT_PATH."/courseRegistrationForm.php");

	$studentTimeTableMenu = Array();
	$studentTimeTableMenu[] = Array(MAKE_HEADING_MENU, "StudentDisplayTimeTable, My Time Table, ".UI_HTTP_STUDENT_PATH."/listTimeTable.php",Array(VIEW));
	//$studentTimeTableMenu[] = Array(MAKE_SINGLE_MENU, ">StudentDisplayTimeTable", Array('StudentDisplayTimeTable', 'Display Student Time Table', UI_HTTP_STUDENT_PATH . '/listTimeTable.php',Array(VIEW)));

	$studentFeeMenu = Array();
	$studentFeeMenu[] = Array(MAKE_HEADING_MENU, "StudentShowFeePaymentHistory, Fees, ".UI_HTTP_STUDENT_PATH."/listStudentFee.php",Array(VIEW));

	$studentResourceMenu = Array();
	$studentResourceMenu[] = Array(MAKE_HEADING_MENU, "StudentResourceDetails, Resource Details, ".UI_HTTP_STUDENT_PATH."/listStudentInformation.php?tabIndex=4",Array(VIEW));

	$studentFeedbackMenu = Array();
	$studentFeedbackMenu[] = Array(SET_MENU_HEADING, "Feedback");
	//$studentFeedbackMenu[] = Array(MAKE_SINGLE_MENU, ">StudentTeacherFeedBack", Array('StudentTeacherFeedBack', 'Teacher Feed Back', UI_HTTP_STUDENT_PATH . '/studentFeedBack.php'));
	//$studentFeedbackMenu[] = Array(MAKE_SINGLE_MENU, ">StudentGeneralFeedBack", Array('StudentGeneralFeedBack', 'General Feed Back', UI_HTTP_STUDENT_PATH . '/generalFeedBack.php'));
    $studentFeedbackMenu[] = Array(MAKE_SINGLE_MENU, "ADVFB_ProvideFeedBack", Array('ADVFB_ProvideFeedBack', 'Advanced Feed Back', UI_HTTP_PATH . '/provideFeedbackAdv.php'));

	$studentPasswordMenu = Array();
	$studentPasswordMenu[] = Array(MAKE_HEADING_MENU, "StudentChangePassword, Change Password, ".UI_HTTP_STUDENT_PATH."/changeStudentPassword.php",Array(EDIT));

    $studentAssignmentMenu = Array();
    $studentAssignmentMenu[] = Array(MAKE_HEADING_MENU, "StudentAssignment, Assignment, ".UI_HTTP_STUDENT_PATH."/studentAllocatedTask.php",Array(VIEW,EDIT));


    $moodleMenu   = Array();
    $moodleMenu[] = Array(MAKE_HEADING_MENU, "ShowMoodle, Moodle, ".UI_HTTP_MOODLE_PATH."/index.php",Array(VIEW));


    $allStudentMenus = Array();
    $allStudentMenus[] = $studentActivityMenu;
    $allStudentMenus[] = $studentReappearMenu;



    # DO NOT REMOVE FOLLOWING {IF} CONDITION ----- AJINDER

	 if (empty($testCalling)) {
		 $ssDate = $sessionHandler->getSessionVariable('REGISTRATION_DEGREE_END_DATE');
		 if($regDegreeCode!='') {
			  if($ssDate!='') {
					$sDate = explode('-',$ssDate);
					$serverDate = explode('-',date('Y-m-d'));
					$start_date=gregoriantojd($sDate[1], $sDate[2], $sDate[0]);
					$end_date  =gregoriantojd($serverDate[1], $serverDate[2], $serverDate[0]);
					$diff=$end_date-$start_date;
					if($foundArray1[0]['degreeCode']==$regDegreeCode && $diff <= 0 ) {
					  $allStudentMenus[] = $studentCourseRegistrationMenu;
					}
			  }
		 }
	 }



    $allStudentMenus[] = $studentCourseRegistrationMenu;
    $allStudentMenus[] = $studentTimeTableMenu;
    $allStudentMenus[] = $studentFeeMenu;
    $allStudentMenus[] = $studentResourceMenu;
    $allStudentMenus[] = $studentFeedbackMenu;
    $allStudentMenus[] = $studentAssignmentMenu;
    $allStudentMenus[] = $studentPasswordMenu;
    $allStudentMenus[] = $moodleMenu;

*/
 //$History: studentMenuItems.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/30/10    Time: 4:10p
//Updated in $/LeapCC/Library
//fixed bug no. 0003151
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library
//Updated menu according to the present menu
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/23/10    Time: 3:11p
//Updated in $/LeapCC/Library
//append UI_HTTP_STUDENT_PATH with file name
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 11:46a
//Updated in $/LeapCC/Library
//commented out 'display time table' and 'feed back' links
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:02a
//Created in $/LeapCC/Library
//Intial Checkin to implement 'Teacher,Parent,student and management'
//role permission
?>
