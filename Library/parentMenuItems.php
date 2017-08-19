<?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Parent Menu
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
	 
 require_once("common.inc.php");
  require_once(BL_PATH . "/MenuCreationClassManager.inc.php");

$menuCreationManager = MenuCreationClassManager::getInstance();	


$parentActivityMenu = Array();
$menuCreationManager->addToAllMenus($parentActivityMenu);
$menuCreationManager->setMenuHeading("Parent Activities");

$parentStudentInfoArray   =  Array(
                                            'moduleName'  => 'ParentStudentInfo',
                                            'moduleLabel' => 'Student Info',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH . '/studentInfo.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($parentStudentInfoArray );  
$parentDisplayAttendanceArray  =  Array(
                                            'moduleName'  => 'ParentDisplayAttendance',
                                            'moduleLabel' => 'Display Attendance',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH . '/displayAttendance.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($parentDisplayAttendanceArray);
$parentDisplayMarksArray  =  Array(
                                            'moduleName'  => 'ParentDisplayMarks',
                                            'moduleLabel' => 'Display Marks',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH . '/displayMarks.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($parentDisplayMarksArray ); 

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


$parentStudentTimeTableArray  =  Array(
                                            'moduleName'  => 'ParentStudentTimeTable',
                                            'moduleLabel' => 'Display Time Table',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH .'/displayTimeTable.php',
                                            'accessArray' =>  Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($parentStudentTimeTableArray ); 
$parentAdminMessagesArray  =  Array(
                                            'moduleName'  => 'ParentAdminMessages',
                                            'moduleLabel' => 'Display Admin Messages',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH .'/listAdminMessages.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($parentAdminMessagesArray ); 
$parentTeacherCommentsArray  =  Array(
                                            'moduleName'  => 'ParentTeacherComments',
                                            'moduleLabel' => 'Display Teacher Comments',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH .'/displayTeacherComments.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($parentTeacherCommentsArray); 
$parentAlertsArray  =  Array(
                                            'moduleName'  => 'ParentAlerts',
                                            'moduleLabel' => 'Display Alerts',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH .'/listAllAlerts.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($parentAlertsArray); 
$parentTaskManagerArray  =  Array(
                                            'moduleName'  => 'TaskMaster',
                                            'moduleLabel' => 'Task Manager',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH .'/listTask.php',
                                            'accessArray' => '',
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($parentTaskManagerArray );   

$parentNoticeMenu= Array();
$menuCreationManager->addToAllMenus($parentNoticeMenu);
$menuCreationManager->setMenuHeading("Institute Notices");

$parentInstituteNoticesArray   =  Array(
                                            'moduleName'  => 'ParentInstituteNotices',
                                            'moduleLabel' => 'Display Institute Notices',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH . '/displayNotices.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($parentInstituteNoticesArray );   
$parentDisplayInstituteEventsArray   =  Array(
                                            'moduleName'  => 'ParentDisplayInstituteEvents',
                                            'moduleLabel' => 'Display Institute Events',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH . '/displayEvents.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => true

                            );
$menuCreationManager->makeSingleMenu($parentDisplayInstituteEventsArray  );    
$parenReportMenu= Array();
$menuCreationManager->addToAllMenus($parenReportMenu);
$menuCreationManager->setMenuHeading("Reports");

$parentDisplayFeeDetailsArray   =  Array(
                                            'moduleName'  => 'ParentDisplayFeeDetails',
                                            'moduleLabel' => 'Display Fee Details',
                                            'moduleLink'  =>  UI_HTTP_PARENT_PATH . '/displayFees.php',
                                            'accessArray' => Array(VIEW),
                                            'description' => '',
                                            'helpUrl'     => '',
                                            'videoHelpUrl'=> '',
                                            'showHelpBar' => true,
                                            'showSearch' => false

                            );
$menuCreationManager->makeSingleMenu($parentDisplayFeeDetailsArray);  
$parenMailMenu = Array(
                                                 'moduleName'  => 'ParentMailBox',
                                                 'moduleLabel' => 'Mail Box',
                                                 'moduleLink'  => UI_HTTP_PARENT_PATH.'/parentMailBox.php',
                                                 'accessArray' => '',
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($parenMailMenu); 
$parentPasswordMenu = Array(
                                                 'moduleName'  => 'ParentChangePassword',
                                                 'moduleLabel' => 'Change Password',
                                                 'moduleLink'  => UI_HTTP_PARENT_PATH.'/changePassword.php',
                                                 'accessArray' => Array(EDIT),
                                                 'description' => '',
                                                 'helpUrl'     => '',
                                                 'videoHelpUrl' => '',
                                                 'showHelpBar' => false,
                                                 'showSearch' => true
                         );

$menuCreationManager->makeHeadingMenu($parentPasswordMenu); 				   
$allParentMenus = $menuCreationManager->getAllMenus();	  

 $allModuleLabelArray=array();
     
     
     //Added for autosuggest to work
     foreach($allParentMenus as $independentMenu) {
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
				   				   
/* if ($sessionHandler->getSessionVariable('hasBreadCrumbs') == '') {
	$mainMenuCounter = 0;
	$subInnerMenuCounter = 0;
	
	$breadCrumbArray = array();
	$setMenuHeading = '';
	$makeSingleMenu = '';
	$makeHeadingMenu = '';
	$makeMenu = '';
	$menuText = '';
    $allModuleLabelArray=array();  
	foreach($allParentMenus as $independentMenu) {
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
                $allModuleLabelArray[] = array('menuLabel'=>$moduleLabel,'menuLink'=>$moduleLink);
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
                    $allModuleLabelArray[] = array('menuLabel'=>$moduleLabel,'menuLink'=>$moduleLink);
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
                $allModuleLabelArray[] = array('menuLabel'=>$menuLabel,'menuLink'=>$menuLink); 
				$mainMenuCounter++;
				$setMenuHeading = $menuLabel;
				$breadCrumbArray[trim($moduleName)] = $setMenuHeading;
			}
		}
	}
	$_SESSION['breadCrumbArray'] = $breadCrumbArray;
	$sessionHandler->setSessionVariable('hasBreadCrumbs', true);
}
global $sessionHandler;
$sessionHandler->setSessionVariable("allModuleLabelArray",$allModuleLabelArray); 
print_r($allModuleLabelArray);
//function getBreadCrumb() {
//	return "<font color='black'>".$_SESSION['breadCrumbArray'][MODULE]."</font>";
//}
 				   

/*function getHelpLinks() {
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
	 	 				   
	 

/*	 $parentActivityMenu = Array();
	$parentActivityMenu[] = Array(SET_MENU_HEADING, "Parent Activities");
	$parentActivityMenu[] = Array(MAKE_SINGLE_MENU, "ParentStudentInfo", Array('ParentStudentInfo', 'Student Info', UI_HTTP_PARENT_PATH . '/studentInfo.php',Array(VIEW)));
	$parentActivityMenu[] = Array(MAKE_SINGLE_MENU, "ParentDisplayAttendance", Array('ParentDisplayAttendance', 'Display Attendance', UI_HTTP_PARENT_PATH . '/displayAttendance.php',Array(VIEW)));
	$parentActivityMenu[] = Array(MAKE_SINGLE_MENU, "ParentDisplayMarks", Array('ParentDisplayMarks', 'Display Marks', UI_HTTP_PARENT_PATH . '/displayMarks.php',Array(VIEW)));
$parentActivityMenu[] = Array(MAKE_SINGLE_MENU, "ParentStudentTimeTable", Array('ParentStudentTimeTable', 'Display Time Table', UI_HTTP_PARENT_PATH . '/displayTimeTable.php',Array(VIEW))); 
    $parentActivityMenu[] = Array(MAKE_SINGLE_MENU, "ParentAdminMessages", Array('ParentAdminMessages', 'Display Admin Messages', UI_HTTP_PARENT_PATH . '/listAdminMessages.php',Array(VIEW))); 
	$parentActivityMenu[] = Array(MAKE_SINGLE_MENU, "ParentTeacherComments", Array('ParentTeacherComments', 'Display Teacher Comments', UI_HTTP_PARENT_PATH . '/displayTeacherComments.php',Array(VIEW))); 
	//$parentActivityMenu[] = Array(MAKE_SINGLE_MENU, ">ParentInstituteNotices", Array('ParentInstituteNotices', 'Display Institute Notices', UI_HTTP_PARENT_PATH . '/displayNotices.php',Array(VIEW)));
    $parentActivityMenu[] = Array(MAKE_SINGLE_MENU, "ParentAlerts", Array('ParentAlerts', 'Display Alerts', UI_HTTP_PARENT_PATH . '/listAllAlerts.php',Array(VIEW))); 
	$parentActivityMenu[] = Array(MAKE_SINGLE_MENU, "ParentTaskManager", Array('TaskMaster', 'Task Manager', UI_HTTP_PARENT_PATH . '/listTask.php'));
		 
	$parentNoticeMenu = Array();
	$parentNoticeMenu[] = Array(SET_MENU_HEADING, "Institute Notices");
	$parentNoticeMenu[] = Array(MAKE_SINGLE_MENU, "ParentInstituteNotices", Array('ParentInstituteNotices', 'Display Institute Notices', UI_HTTP_PARENT_PATH . '/displayNotices.php',Array(VIEW)));
	$parentNoticeMenu[] = Array(MAKE_SINGLE_MENU, "ParentDisplayInstituteEvents", Array('ParentDisplayInstituteEvents', 'Display Institute Events', UI_HTTP_PARENT_PATH . '/displayEvents.php',Array(VIEW))); 

	$parenReportMenu = Array();
	$parenReportMenu[] = Array(SET_MENU_HEADING, "Reports");
	$parenReportMenu[] = Array(MAKE_SINGLE_MENU, "ParentDisplayFeeDetails", Array('ParentDisplayFeeDetails', 'Display Fee Details', UI_HTTP_PARENT_PATH . '/displayFees.php',Array(VIEW)));
 
    $parenMailMenu = Array();
    $parenMailMenu[] = Array(MAKE_HEADING_MENU, "ParentMailBox, Mail Box, ".UI_HTTP_PARENT_PATH."/parentMailBox.php");
     
	$parentPasswordMenu = Array();
	$parentPasswordMenu[] = Array(MAKE_HEADING_MENU, "ParentChangePassword, Change Password, ".UI_HTTP_PARENT_PATH."/changePassword.php",Array(EDIT));

	$allParentMenus = Array($parentActivityMenu,$parentNoticeMenu,$parenReportMenu,$parenMailMenu,$parentPasswordMenu);
*/
 //$History: parentMenuItems.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/23/10    Time: 3:11p
//Updated in $/LeapCC/Library
//append UI_HTTP_TEACHER_PATH with file name
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:47p
//Updated in $/LeapCC/Library
//added new menus.. Display Admin Messages, Display Alerts
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:02a
//Created in $/LeapCC/Library
//Intial Checkin to implement 'Teacher,Parent,student and management'
//role permission
?>
