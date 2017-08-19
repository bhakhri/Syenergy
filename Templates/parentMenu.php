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
                require_once(BL_PATH . '/parentMenuItems.php');
                foreach($allParentMenus as $menu){

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
                 

<!-- Ending Page Content [menu nests within] -->
 </td>
 <td valign="top"  class="menu_right" style="width:10px;height:20px"></td>
 </tr>
</table>

	<script type="text/javascript">qm_create(0,false,0,500,false,false,false,false,false);</script>
</td>
 </tr>
 </table>


<?php
	//$History: parentMenu.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 2/15/10    Time: 4:32p
//Updated in $/LeapCC/Templates
//UI_HTTP_PARENT_PATH path added
//
//*****************  Version 9  *****************
//User: Parveen      Date: 9/04/09    Time: 3:02p
//Updated in $/LeapCC/Templates
//display admin message menu added
//
//*****************  Version 8  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Templates
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/17/09    Time: 2:24p
//Updated in $/LeapCC/Templates
//Display Institute Notices linik remove Parents Activities
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 16/06/09   Time: 19:25
//Updated in $/LeapCC/Templates
//Added themes support to teacher,parent,student and management roles
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/12/09    Time: 12:02p
//Updated in $/LeapCC/Templates
//commented dynamic menu part
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 6/04/09    Time: 2:28p
//Updated in $/LeapCC/Templates
//Switched off role permissions for time being
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:01a
//Updated in $/LeapCC/Templates
//Added 'Parent, Student, Teacher and Management' Role permission
?>