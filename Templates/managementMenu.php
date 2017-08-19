 <?php
//-------------------------------------------------------
//  This File contains Presentation Logic of Parent Menu
//
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
// 
//--------------------------------------------------------
	require_once(BL_PATH . '/MenuManager.inc.php');
	$menuManager = MenuManager::getInstance();
?>
<table cellpadding="0" cellspacing="0" style="width:100%;" border="0" height="20">
 <tr>
    <td valign="top" class="menu_middle" height="20">
    <table cellpadding="0" cellspacing="0" style="width:100%;" border="0" height="20">
    <tr>
        <td valign="top" class="menu_left" style="width:10px"></td>
        <td width="98%" height="36" valign="middle">
        <!-- QuickMenu Structure [Menu 0] -->
        <ul id="qm0" class="qmmc">
				<?php 
				require_once(BL_PATH . '/managementMenuItems.php');
				foreach($allManagementMenus as $menu){

					if($menu[0][0] == MAKE_HEADING_MENU){
						echo $menuManager->makeThisHeadingMenu($menu);
					}
					else{
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
		<script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
	</td>
</tr>
</table>
<?php
                //echo '<pre>';
                //print_r($_SESSION);
?>