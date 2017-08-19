<?php
	require_once(BL_PATH . '/MenuManager.inc.php');
	$menuManager = MenuManager::getInstance();
?>
<table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
 <tr>
    <td valign="top" background="<?php echo IMG_HTTP_PATH;?>/navmid.gif" height="20">
	<table cellpadding=0 cellspacing=0 style="width:100%;" border="0" height="20">
	<tr>
		<td valign="top"><img src="<?php echo IMG_HTTP_PATH;?>/navleft.gif" /></td>
		<td style="width:100%;" height="20" valign="middle">
		<!-- QuickMenu Structure [Menu 0] -->
	    <ul id="qm0" class="qmmc">
		<?php 
		require_once(BL_PATH . '/scMenuItems.php');
		$menuManager->makeThisMenu($setupMenu);
		echo $menuManager->showMenu();
		echo $menuManager->makeThisHeadingMenu($studentInfoMenu);
		$menuManager->makeThisMenu($schedulingMenu);
		echo $menuManager->showMenu();
		$menuManager->makeThisMenu($instituteMenu);
		echo $menuManager->showMenu();
		$menuManager->makeThisMenu($messagingMenu);
		echo $menuManager->showMenu();
		$menuManager->makeThisMenu($studentFeeMenu);
		echo $menuManager->showMenu();
		$menuManager->makeThisMenu($reportsMenu);
		echo $menuManager->showMenu();
		$menuManager->makeThisMenu($dashboardMenu);
		echo $menuManager->showMenu();
    ?>

	  </ul>
	 </li>
<!-- Ending Page Content [menu nests within] -->
 </td>
 <td valign="top"><img src="<?php echo IMG_HTTP_PATH;?>/navright.gif" /></td>
 </tr>
</table>
	<script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
</td>
 </tr>
 </table>