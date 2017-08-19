<?php
     global $sessionHandler;
     
     if($sessionHandler->getSessionVariable('FEEDBACK_PERMISSION')==1){
       require_once(BL_PATH . "/FeedbackAdvanced/ajaxCheckStudentFeedbackStatus.php");
       if(isset($_SESSION['UserIdDisabledForIncompleteFeedback'])){
         if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
           //return false; //make student manu disappear
         }
       }
     }
 
?>

 <?php
//-------------------------------------------------------
//  This File contains Presentation Logic of student Menu
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
				require_once(BL_PATH . '/studentMenuItems.php');
				foreach($allStudentMenus as $menu){

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
	//$History: studentMenu.php $
//
//*****************  Version 15  *****************
//User: Gurkeerat    Date: 2/18/10    Time: 3:55p
//Updated in $/LeapCC/Templates
//removed link for teacher and general feedback
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 6/02/10    Time: 19:12
//Updated in $/LeapCC/Templates
//Added links for "Provide Feedback" modules in teacher and student
//logins.
//
//*****************  Version 12  *****************
//User: Parveen      Date: 2/01/10    Time: 2:50p
//Updated in $/LeapCC/Templates
//Student Reappear link added
//
//*****************  Version 11  *****************
//User: Parveen      Date: 1/15/10    Time: 11:36a
//Updated in $/LeapCC/Templates
//student re-appear link disable
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/08/10    Time: 11:45a
//Updated in $/LeapCC/Templates
//menu added Internal Re-appear Form
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 6/17/09    Time: 11:05a
//Updated in $/LeapCC/Templates
//modified for theme
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 6/10/09    Time: 12:24p
//Updated in $/LeapCC/Templates
//commented dynamic menu part
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 6/04/09    Time: 2:26p
//Updated in $/LeapCC/Templates
//Switched off role permissions for time being
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:01a
//Updated in $/LeapCC/Templates
//Added 'Parent, Student, Teacher and Management' Role permission
?>