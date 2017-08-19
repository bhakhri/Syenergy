
<script type="text/javascript">
// Popup window code
function newPopup(url) {
    popupWindow = window.open(
        url,'popUpWindow','height=700,width=800,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
}
</script>
<?php
//***************OLD MENY STRUCTURE IS IN "oldMenus.php" FILE********************************
//-------------------------------------------------------
//  This File contains Presentation Logic of Teacher Menu
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
				require_once(BL_PATH . '/teacherMenuItems.php');
				foreach($allTeacherMenus as $menu){

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
	//$History: teacherMenu.php $
//
//*****************  Version 30  *****************
//User: Parveen      Date: 4/13/10    Time: 1:18p
//Updated in $/LeapCC/Templates
//menu added  Bulk Subject Topic Master
//
//*****************  Version 29  *****************
//User: Dipanjan     Date: 23/03/10   Time: 16:46
//Updated in $/LeapCC/Templates
//Created Feedback Teacher Final Report (Advanced) for Teacher login
//
//*****************  Version 28  *****************
//User: Dipanjan     Date: 23/03/10   Time: 11:08
//Updated in $/LeapCC/Templates
//Created Feedback Teacher Detailed GPA Report (Advanced) for Teacher
//login
//
//*****************  Version 27  *****************
//User: Parveen      Date: 3/17/10    Time: 12:19p
//Updated in $/LeapCC/Templates
//attendanceRegister menu added in report 
//
//*****************  Version 26  *****************
//User: Dipanjan     Date: 6/02/10    Time: 19:12
//Updated in $/LeapCC/Templates
//Added links for "Provide Feedback" modules in teacher and student
//logins.
//
//*****************  Version 25  *****************
//User: Gurkeerat    Date: 12/07/09   Time: 6:20p
//Updated in $/LeapCC/Templates
//added link for help videos (but commented out for time being)
//
//*****************  Version 24  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:12
//Updated in $/LeapCC/Templates
//created "Subject Wise Performance" report
//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 19/11/09   Time: 15:25
//Updated in $/LeapCC/Templates
//Completed/Modified duty leaves module in teacher end
//
//*****************  Version 22  *****************
//User: Dipanjan     Date: 2/11/09    Time: 15:55
//Updated in $/LeapCC/Templates
//Added link for "Group Wise Performance Report" 
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 29/10/09   Time: 13:54
//Updated in $/LeapCC/Templates
//Added link for "Test wise performance comparison" report
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 28/10/09   Time: 10:15
//Updated in $/LeapCC/Templates
//corrected link text
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates
//Corrected look and feel of teacher module logins
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Templates
//Remove administrator role from role type so that no new administrator
//can be made and Chalkpad will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:23p
//Updated in $/LeapCC/Templates
//added fine student link in teacher menu
//
//*****************  Version 15  *****************
//User: Parveen      Date: 6/24/09    Time: 5:01p
//Updated in $/LeapCC/Templates
//employeeInfo Menu added
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 16/06/09   Time: 19:25
//Updated in $/LeapCC/Templates
//Added themes support to teacher,parent,student and management roles
//
//*****************  Version 13  *****************
//User: Administrator Date: 13/06/09   Time: 11:19
//Updated in $/LeapCC/Templates
//Made bulk attendance,duty leaves and grace marks in teacher end
//configurable
//
//*****************  Version 12  *****************
//User: Parveen      Date: 6/05/09    Time: 4:42p
//Updated in $/LeapCC/Templates
//menu added Display Subject Wise Topic Taught Report
//(teacherTopicCoveredReport.php)
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 6/05/09    Time: 4:36p
//Updated in $/LeapCC/Templates
//Updated teacher menu with roll permission
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 6/04/09    Time: 2:24p
//Updated in $/LeapCC/Templates
//Switched off role permissions for time being
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:01a
//Updated in $/LeapCC/Templates
//Added 'Parent, Student, Teacher and Management' Role permission
?>