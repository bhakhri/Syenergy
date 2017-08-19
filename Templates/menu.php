<?php
	//require_once("common.inc.php");
	require_once(BL_PATH . "/MenuCreationClassManager.inc.php");
	$menuCreationManager = MenuCreationClassManager::getInstance();

	require_once(BL_PATH . '/MenuManager.inc.php');
	$menuManager = MenuManager::getInstance();
	$roleId = $sessionHandler->getSessionVariable('RoleId');
	if ($roleId == 2) {
        $sessionHandler->setSessionVariable('hasBreadCrumbs','');
		require_once(BL_PATH . '/teacherMenuItems.php');
		$allMenus=$allTeacherMenus;
	}
	elseif ($roleId == 3) {
        $sessionHandler->setSessionVariable('hasBreadCrumbs','');
		require_once(BL_PATH . "/parentMenuItems.php");
		$allMenus=$allParentMenus;
	}
    elseif ($roleId == 4) {
        $sessionHandler->setSessionVariable('hasBreadCrumbs','');
		require_once(BL_PATH . "/studentMenuItems.php");
		$allMenus=$allStudentMenus;
		require_once(BL_PATH . "/FeedbackAdvanced/ajaxCheckStudentFeedbackStatus.php");
		if(isset($_SESSION['UserIdDisabledForIncompleteFeedback'])){
        if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
         //return false; //make student manu disappear
        }
      }
	}
	elseif ($roleId == 5) {
        $sessionHandler->setSessionVariable('hasBreadCrumbs','');
		require_once(BL_PATH . "/managementMenuItems.php");
		$allMenus=$allManagementMenus;
	}
	else {
        $sessionHandler->setSessionVariable('hasBreadCrumbs','');
		require_once(BL_PATH . '/menuItems.php');
	}



if ($sessionHandler->getSessionVariable('hasBreadCrumbs') == '') {
	$mainMenuCounter = 0;
	$subInnerMenuCounter = 0;
	/*
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
	*/

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

function getBreadCrumb() {
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
}



 //echo $cacheFile;
	/*
	$start = microtime()-10;
	$menuContents = trim(@file_get_contents($cacheFile));
	$cacheContents = true;
	if ($menuContents != false and $menuContents!="") {
		$modificationTime = filemtime($cacheFile);
		$timeElapsed = $modificationTime - $start;
		//$rewriteTime = 1;
		if ($timeElapsed > $rewriteTime) {
			$cacheContents = true;
		}
		else {
			$cacheContents = false;
		}
	}

	if ($cacheContents == true) {
		ob_start();
		*/
	?>
	<table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
	 <tr>
		 <td valign="top" class="menu_middle" height="20">
		<table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
		<tr>
			<td valign="top" class="menu_left" style="width:10px"></td>
			  <td width="98%" height="36" valign="middle">
			 <ul id="qm0" class="qmmc">
			<?php
			foreach($allMenus as $menu) {
				if ($menu[0][0] == MAKE_HEADING_MENU) {
					echo $menuManager->makeThisHeadingMenu($menu);
				}
				else {
					$menuManager->makeThisMenu($menu);
					echo $menuManager->showMenu();
				}
			}
		 ?>
		  </ul>
		 </li>
	 </td>
	 <td valign="top"  class="menu_right" style="width:10px;height:20px"></td>
	 </tr>
	</table>
	<?php
		/*
			$menuContents = ob_get_clean();
			file_put_contents ($cacheFile, $menuContents);
			chmod($cacheFile,0777);
		}
		echo $menuContents;
		*/
		//		FOR CHECKING TIME SAVED BY CACHING
		/*
		$end = microtime();
		$timeConsumed = $end - $start;
		echo '===='.$cacheContents.' Time Consumed : '.$timeConsumed;
		*/

	?>
	<script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
</td>
 </tr>
 </table>